<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\ConsultationModel;
use App\Models\AppointmentModel;
use App\Models\PatientModel;
use App\Models\InventoryModel;
use App\Models\ConsultationMedicineModel;
use App\Models\InventoryLogModel;
use App\Models\UserModel;

class ConsultationController extends BaseController
{

    public function consultation()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('message', 'Please login to continue');
        }

        $consult = new ConsultationModel();
        $medicineModel = new ConsultationMedicineModel();
        $inventoryModel = new InventoryModel();

        $consultations = $consult->findAll();
        
        // Fetch medicines for each consultation
        foreach($consultations as &$c) {
            $medicines = $medicineModel->where('consultation_id', $c['consultation_id'])->findAll();
            $c['medicines'] = [];
            foreach($medicines as $med) {
                $item = $inventoryModel->find($med['item_id']);
                if($item) {
                    $c['medicines'][] = [
                        'item_name' => $item['item_name'],
                        'quantity_used' => $med['quantity_used'],
                        'unit' => $med['unit']
                    ];
                }
            }
        }

        $data['consult'] = $consultations;

        return view('Consultation/consultation', $data);
    }

    public function add_consultation()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('message', 'Please login to continue');
        }

        $service = request()->getGet('service');
        $allowedServices = ['walkin', 'appoint'];

        // Validate service parameter - default to 'appoint' if invalid
        if(empty($service) || !in_array($service, $allowedServices)){
            $service = 'appoint';
        }

        if($service === 'walkin'){
            session()->set('service', 'walkin');
        }
        else{
            session()->set('service', 'appoint');
        }

        // Fetch approved appointments with patient info
        $appointmentModel = new AppointmentModel();
        $data['appointments'] = $appointmentModel
            ->where('status', 'approved')
            ->select('appointments.appointment_id, appointments.patient_id, appointments.appointment_date, patients.first_name, patients.last_name')
            ->join('patients', 'patients.patient_id = appointments.patient_id', 'left')
            ->orderBy('appointments.appointment_date', 'DESC')
            ->findAll();

        // Combine first and last name for display
        foreach($data['appointments'] as &$appt) {
            $appt['patient_name'] = trim(($appt['first_name'] ?? '') . ' ' . ($appt['last_name'] ?? ''));
        }

        return view('Consultation/add_consultation', $data);
    }

    public function store_consultation()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('message', 'Please login to continue');
        }

        $appoint = new AppointmentModel();
        $patient = new PatientModel();
        $consult = new ConsultationModel();

        $service = request()->getPost('service');
        
        if($service === 'walkin'){
            $patient_id = request()->getPost('patient_id');

            $patientExist = $patient->where('patient_id', $patient_id)->first();

            if(!$patientExist){
                return redirect()->to('/consultation/add')->with('message', 'Patient ID is not found.');
            }

            $data = [
                'patient_id' => $patient_id,
                'diagnosis' => request()->getPost('diagnosis'),
                'treatment' => request()->getPost('treatment'),
                'prescription' => request()->getPost('prescription'),
                'notes' => request()->getPost('notes'),
                'consultation_date' => date('Y-m-d H:i:s')
            ];
        }
        else{
            $appoint_id = request()->getPost('appointment_id');

            $appointExist = $appoint->where('appointment_id', $appoint_id)->first();

            if(!$appointExist){
                return redirect()->to('/consultation/add')->with('message', 'Appointment ID is not found.');
            }

            $patient_id = $appointExist['patient_id'];

            $data = [
                'patient_id' => $patient_id,
                'diagnosis' => request()->getPost('diagnosis'),
                'treatment' => request()->getPost('treatment'),
                'appointment_id' => request()->getPost('appointment_id'),
                'prescription' => request()->getPost('prescription'),
                'notes' => request()->getPost('notes'),
                'consultation_date' => date('Y-m-d H:i:s')
            ];

        }

         $consult->insert($data);
         $newConsultId = $consult->getInsertID();

         // Handle medicine allocation if provided
         $this->allocateMedicines($newConsultId);

         // If appointment-based consultation, mark appointment as completed
         if(!empty($data['appointment_id'])){
             $appoint->update($data['appointment_id'], ['status' => 'completed']);
         }

         // Send consultation email to patient
         $this->sendConsultationEmail($data['patient_id'], $newConsultId);

         return redirect()->to('/consultation/add/?service='.session()->get('service'))->with('message', 'Consultation Added Successfully');
    }

    public function edit_consultation($id)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('message', 'Please login to continue');
        }

        $consult = new ConsultationModel();
        $medicineModel = new ConsultationMedicineModel();
        $inventoryModel = new InventoryModel();

        $data['consult'] = $consult->find($id);

        if(!$data['consult']){
            return redirect()->to('/consultation')->with('message', 'Error: Consultation not found');
        }

        // Fetch medicines for this consultation
        $medicines = $medicineModel->where('consultation_id', $id)->findAll();
        $data['medicines'] = [];
        foreach($medicines as $med) {
            $item = $inventoryModel->find($med['item_id']);
            if($item) {
                $data['medicines'][] = [
                    'item_name' => $item['item_name'],
                    'quantity_used' => $med['quantity_used'],
                    'unit' => $med['unit']
                ];
            }
        }

        return view('Consultation/edit_consultation', $data);
    }

    public function update_consultation($id)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('message', 'Please login to continue');
        }

        $consult = new ConsultationModel();

        $data = [
            'diagnosis' => request()->getPost('diagnosis'),
            'treatment' => request()->getPost('treatment'),
            'prescription' => request()->getPost('prescription'),
            'notes' => request()->getPost('notes'),
        ];

        $consult->update($id, $data);

        return redirect()->to('/consultation/edit/'.$id)->with('message', 'Consultation Updated Successfully');
    }

    public function delete_consultation($id)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('message', 'Please login to continue');
        }

        $consult = new ConsultationModel();
        $consult_med = new ConsultationMedicineModel();
        $inventory = new InventoryModel();
        $inv_log = new InventoryLogModel();

        $exist = $consult->find($id);
        if(!$exist){
            return redirect()->to('/consultation')->with('message', 'Error: Consultation not found');
        }

        // Rollback inventory for any medicines allocated to this consultation
        $medicines = $consult_med->where('consultation_id', $id)->findAll();
        foreach ($medicines as $med) {
            // Restore inventory
            $inventory->set('quantity', 'quantity + ' . $med['quantity_used'], false)->where('item_id', $med['item_id'])->update();
            
            // Log the rollback
            $inv_log->insert([
                'item_id' => $med['item_id'],
                'quantity_change' => $med['quantity_used'],
                'reason' => 'consultation',
                'related_consultation_id' => $id,
                'logged_by' => session()->get('user_id'),
                'notes' => 'Rollback - Consultation deleted'
            ]);
        }

        // Delete consultation (cascade will delete consultation_medicines entries)
        $consult->delete($id);

        return redirect()->to('/consultation')->with('message', 'Consultation Deleted Successfully');
    }

    /**
     * Allocate medicines to a consultation and decrement inventory
     */
    private function allocateMedicines($consultation_id)
    {
        $medicines_json = request()->getPost('medicines'); // JSON string from form
        if (empty($medicines_json)) {
            return;
        }

        $medicines = json_decode($medicines_json, true);
        if (empty($medicines) || !is_array($medicines)) {
            return;
        }

        $consult_med = new ConsultationMedicineModel();
        $inventory = new InventoryModel();
        $inv_log = new InventoryLogModel();

        foreach ($medicines as $item_id => $quantity) {
            $item_id = (int) $item_id;
            $quantity = (int) $quantity;
            if ($quantity <= 0) {
                continue;
            }

            // Get the item to check available quantity and get unit
            $item = $inventory->find($item_id);
            if (!$item) {
                continue;
            }

            // Check if enough stock
            if ($item['quantity'] < $quantity) {
                log_message('warning', "Insufficient inventory for item $item_id. Available: {$item['quantity']}, Requested: $quantity");
                continue;
            }

            // Record the medicine in consultation_medicines
            $consult_med->insert([
                'consultation_id' => $consultation_id,
                'item_id' => $item_id,
                'quantity_used' => $quantity,
                'unit' => $item['unit']
            ]);

            // Decrement inventory
            $inventory->set('quantity', 'quantity - ' . $quantity, false)->where('item_id', $item_id)->update();

            // Log the consumption
            $inv_log->insert([
                'item_id' => $item_id,
                'quantity_change' => -$quantity,
                'reason' => 'consultation',
                'related_consultation_id' => $consultation_id,
                'logged_by' => session()->get('user_id'),
                'notes' => "Used in consultation #{$consultation_id}"
            ]);
        }
    }

    private function sendConsultationEmail($patient_id, $consultation_id)
    {
        $patient = new PatientModel();
        $user = new UserModel();
        $consult = new ConsultationModel();
        $medicineModel = new ConsultationMedicineModel();
        $inventoryModel = new InventoryModel();

        // Get patient info
        $patientInfo = $patient->find($patient_id);
        if(!$patientInfo){
            return;
        }

        // Get user email
        $userInfo = $user->find($patientInfo['user_id']);
        if(!$userInfo || empty($userInfo['email'])){
            return;
        }

        // Validate email format
        if(!filter_var($userInfo['email'], FILTER_VALIDATE_EMAIL)){
            return;
        }

        // Get consultation details
        $consultationInfo = $consult->find($consultation_id);
        if(!$consultationInfo){
            return;
        }

        // Get medicines for this consultation
        $medicines = $medicineModel->where('consultation_id', $consultation_id)->findAll();
        $medicinesList = '';
        if(!empty($medicines)){
            $medicinesList .= '<h4>Prescribed Medicines:</h4><ul>';
            foreach($medicines as $med) {
                $item = $inventoryModel->find($med['item_id']);
                if($item) {
                    $medicinesList .= '<li>' . $item['item_name'] . ' - ' . $med['quantity_used'] . ' ' . $med['unit'] . '</li>';
                }
            }
            $medicinesList .= '</ul>';
        }

        // Build consultation summary
        $message = '
            <h2>Consultation Summary</h2>
            <p>Dear ' . $userInfo['username'] . ',</p><br>
            <p>Your consultation has been completed. Below is a copy of your consultation details:</p><br>
            
            <div style="background-color: #f8f9fa; padding: 15px; border-left: 4px solid #007bff; margin: 15px 0;">
                <p><strong>Consultation Date:</strong> ' . date('F d, Y g:i A', strtotime($consultationInfo['consultation_date'])) . '</p>
            </div><br>

            <h3>Diagnosis</h3>
            <p>' . (!empty($consultationInfo['diagnosis']) ? nl2br($consultationInfo['diagnosis']) : 'N/A') . '</p><br>

            <h3>Treatment</h3>
            <p>' . (!empty($consultationInfo['treatment']) ? nl2br($consultationInfo['treatment']) : 'N/A') . '</p><br>

            <h3>Prescription</h3>
            <p>' . (!empty($consultationInfo['prescription']) ? nl2br($consultationInfo['prescription']) : 'N/A') . '</p><br>

            ' . $medicinesList . '

            <h3>Notes</h3>
            <p>' . (!empty($consultationInfo['notes']) ? nl2br($consultationInfo['notes']) : 'N/A') . '</p><br>

            <p>If you have any questions or concerns, please contact CSPC Clinic.</p><br>
            <p>Best regards,<br>CSPC Clinic Team</p>
        ';

        // Send email
        $email = \Config\Services::email();
        $email->setTo($userInfo['email']);
        $email->setFrom('jeoffgbanaria@gmail.com', 'CSPC Clinic');
        $email->setSubject('Consultation Summary - CSPC Clinic');
        $email->setMessage($message);
        $email->send();
        $email->clear(true);
    }
}
