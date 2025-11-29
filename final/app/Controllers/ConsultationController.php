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
        $patientModel = new PatientModel();
        $search = request()->getGet('search') ?? '';
        $sort = request()->getGet('sort') ?? 'asc';
        
        // Validate sort parameter
        if (!in_array($sort, ['asc', 'desc'])) {
            $sort = 'asc';
        }

        // Get consultations based on role
        if(session()->get('role') === 'student' || session()->get('role') === 'staff'){
            // Student and Staff can only see their own consultations
            $user_id = session()->get('user_id');
            $myPatient = $patientModel->where('user_id', $user_id)->first();
            
            if($myPatient){
                if ($search) {
                    $consultations = $consult->where('patient_id', $myPatient['patient_id'])
                        ->groupStart()
                        ->like('consultation_id', $search)
                        ->orLike('diagnosis', $search)
                        ->orLike('treatment', $search)
                        ->groupEnd()
                        ->orderBy('consultation_id', $sort)
                        ->findAll();
                } else {
                    $consultations = $consult->where('patient_id', $myPatient['patient_id'])
                        ->orderBy('consultation_id', $sort)
                        ->findAll();
                }
            }
            else{
                $consultations = [];
            }
        }
        else{
            // Admin and Nurse can see all consultations
            if ($search) {
                $consultations = $consult
                    ->groupStart()
                    ->like('consultation_id', $search)
                    ->orLike('patient_id', $search)
                    ->orLike('nurse_id', $search)
                    ->orLike('diagnosis', $search)
                    ->orLike('treatment', $search)
                    ->groupEnd()
                    ->orderBy('consultation_id', $sort)
                    ->findAll();
            } else {
                $consultations = $consult->orderBy('consultation_id', $sort)->findAll();
            }
        }
        
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
        $patientModel = new PatientModel();

        $data['consult'] = $consult->find($id);

        if(!$data['consult']){
            return redirect()->to('/consultation')->with('message', 'Error: Consultation not found');
        }

        // Student and Staff can only edit their own consultation
        if(session()->get('role') === 'student' || session()->get('role') === 'staff'){
            $user_id = session()->get('user_id');
            $myPatient = $patientModel->where('user_id', $user_id)->first();
            
            if(!$myPatient || $data['consult']['patient_id'] != $myPatient['patient_id']){
                return redirect()->to('/consultation')->with('message', 'Error: You can only edit your own consultation');
            }
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
        $patientModel = new PatientModel();

        $exist = $consult->find($id);
        if(!$exist){
            return redirect()->to('/consultation')->with('message', 'Error: Consultation not found');
        }

        // Student and Staff can only delete their own consultation
        if(session()->get('role') === 'student' || session()->get('role') === 'staff'){
            $user_id = session()->get('user_id');
            $myPatient = $patientModel->where('user_id', $user_id)->first();
            
            if(!$myPatient || $exist['patient_id'] != $myPatient['patient_id']){
                return redirect()->to('/consultation')->with('message', 'Error: You can only delete your own consultation');
            }
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
            foreach($medicines as $med) {
                $item = $inventoryModel->find($med['item_id']);
                if($item) {
                    $medicinesList .= '<div class="medicine-item">💊 ' . esc($item['item_name']) . ' - ' . esc($med['quantity_used']) . ' ' . esc($med['unit']) . '</div>';
                }
            }
        }

        // Build consultation summary with professional HTML email template
        $message = '
        <!DOCTYPE html>
        <html style="margin: 0; padding: 0;">
        <head>
            <meta charset="utf-8">
            <style>
                body { font-family: Segoe UI, Arial, sans-serif; margin: 0; padding: 0; background-color: #f5f5f5; }
                .email-container { max-width: 700px; margin: 0 auto; background-color: #ffffff; }
                .header { background: linear-gradient(135deg, #206bc4 0%, #1a54a0 100%); padding: 30px; text-align: center; color: white; }
                .header-logo { font-size: 28px; font-weight: bold; margin-bottom: 8px; }
                .header-subtitle { font-size: 13px; opacity: 0.9; }
                .content { padding: 30px; }
                .greeting { font-size: 18px; color: #206bc4; font-weight: 600; margin-bottom: 5px; }
                .intro-text { color: #666; font-size: 14px; line-height: 1.6; margin-bottom: 20px; }
                .consultation-date-box { background: linear-gradient(135deg, #206bc4 0%, #1a54a0 100%); color: white; padding: 15px; border-radius: 6px; margin: 20px 0; text-align: center; }
                .consultation-date-box .date { font-size: 16px; font-weight: 600; }
                .section-box { background-color: #f8f9fa; border-left: 4px solid #206bc4; padding: 15px; margin: 15px 0; border-radius: 4px; }
                .section-title { color: #206bc4; font-weight: 600; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 10px; }
                .section-content { color: #333; font-size: 14px; line-height: 1.6; }
                .medicine-list { margin-top: 10px; }
                .medicine-item { color: #333; font-size: 13px; padding: 8px; background-color: white; margin: 5px 0; border-left: 3px solid #206bc4; padding-left: 12px; }
                .divider { border-top: 1px solid #e0e0e0; margin: 20px 0; }
                .footer { background-color: #f8f9fa; padding: 20px; text-align: center; font-size: 12px; color: #999; border-top: 1px solid #e0e0e0; }
                .footer-logo { color: #206bc4; font-weight: 600; font-size: 14px; margin-bottom: 5px; }
                .notice { background-color: #e7f3ff; border-left: 4px solid #206bc4; padding: 12px; margin: 15px 0; border-radius: 4px; font-size: 13px; color: #206bc4; }
            </style>
        </head>
        <body>
            <div class="email-container">
                <!-- Header -->
                <div class="header">
                    <div class="header-logo">🏥 CSPC</div>
                    <div class="header-subtitle">Clinic Management System</div>
                </div>

                <!-- Content -->
                <div class="content">
                    <div class="greeting">Hello ' . esc($userInfo['username']) . ',</div>
                    <p class="intro-text">Your consultation has been completed. Below is a detailed summary of your consultation with CSPC Clinic.</p>

                    <!-- Consultation Date -->
                    <div class="consultation-date-box">
                        <div class="date">📅 ' . date('F d, Y g:i A', strtotime($consultationInfo['consultation_date'])) . '</div>
                    </div>

                    <!-- Diagnosis Section -->
                    <div class="section-box">
                        <div class="section-title">📋 Diagnosis</div>
                        <div class="section-content">
                            ' . (!empty($consultationInfo['diagnosis']) ? esc($consultationInfo['diagnosis']) : '<em style="color: #999;">No diagnosis recorded</em>') . '
                        </div>
                    </div>

                    <!-- Treatment Section -->
                    <div class="section-box">
                        <div class="section-title">💊 Treatment Plan</div>
                        <div class="section-content">
                            ' . (!empty($consultationInfo['treatment']) ? esc($consultationInfo['treatment']) : '<em style="color: #999;">No treatment plan recorded</em>') . '
                        </div>
                    </div>

                    <!-- Prescription Section -->
                    <div class="section-box">
                        <div class="section-title">📝 Prescription</div>
                        <div class="section-content">
                            ' . (!empty($consultationInfo['prescription']) ? esc($consultationInfo['prescription']) : '<em style="color: #999;">No prescription recorded</em>') . '
                        </div>
                    </div>

                    <!-- Medicines Section -->
                    ' . (!empty($medicinesList) ? '
                    <div class="section-box">
                        <div class="section-title">💉 Prescribed Medicines</div>
                        <div class="medicine-list">
                            ' . $medicinesList . '
                        </div>
                    </div>
                    ' : '') . '

                    <!-- Notes Section -->
                    ' . (!empty($consultationInfo['notes']) ? '
                    <div class="section-box">
                        <div class="section-title">📌 Additional Notes</div>
                        <div class="section-content">
                            ' . esc($consultationInfo['notes']) . '
                        </div>
                    </div>
                    ' : '') . '

                    <!-- Important Notice -->
                    <div class="notice">
                        ℹ️ Please keep this email for your records and follow the prescribed treatment plan carefully. If you experience any unusual symptoms, contact the clinic immediately.
                    </div>

                    <div class="divider"></div>

                    <p style="color: #666; font-size: 13px; line-height: 1.6;">
                        If you have any questions or concerns about your consultation, please don\'t hesitate to contact CSPC Clinic. We are here to help ensure your recovery.
                    </p>
                </div>

                <!-- Footer -->
                <div class="footer">
                    <div class="footer-logo">CSPC Clinic</div>
                    <p>Professional Medical Services Center</p>
                    <p style="margin-top: 10px; padding-top: 10px; border-top: 1px solid #e0e0e0;">
                        © ' . date('Y') . ' CSPC Clinic. All rights reserved.
                    </p>
                </div>
            </div>
        </body>
        </html>
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
