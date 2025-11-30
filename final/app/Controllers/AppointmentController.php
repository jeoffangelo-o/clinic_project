<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\AppointmentModel;
use App\Models\PatientModel;
use App\Models\UserModel;


class AppointmentController extends BaseController
{
    public function appointment()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('message', 'Please login to continue');
        }

        $appoint = new AppointmentModel;
        $patient = new PatientModel;
        $search = request()->getGet('search') ?? '';
        $sort = request()->getGet('sort') ?? 'desc';
        
        // Validate sort parameter
        if (!in_array($sort, ['asc', 'desc'])) {
            $sort = 'desc';
        }

        $data['appoint'] = [];

        if(session()->get('role') === 'student' || session()->get('role') === 'staff'){
            $user_id = session()->get('user_id');

            $exist = $patient->where('user_id', $user_id)->first();

            if($exist){
                session()->set([
                    'hasPatient' => true
                ]);
                
                // Build base query with join
                $query = $appoint->select('appointments.*, CONCAT(patients.last_name, ", ", patients.first_name, " ", patients.middle_name) as patient_name')
                                 ->join('patients', 'appointments.patient_id = patients.patient_id')
                                 ->where('appointments.patient_id', $exist['patient_id']);
                
                if ($search) {
                    $query = $query->groupStart()
                                   ->like('appointments.appointment_id', $search)
                                   ->orLike('appointments.purpose', $search)
                                   ->orLike('appointments.status', $search)
                                   ->orLike('appointments.remarks', $search)
                                   ->groupEnd();
                }
                
                $data['appoint'] = $query->orderBy('appointments.appointment_id', $sort)
                                        ->findAll();
            }
            else{
                session()->set([
                    'hasPatient' => false
                ]);
            }
        }
        else if(session()->get('role') === 'nurse' || session()->get('role') === 'admin' ){
            $status = request()->getGet('status') ?? 'all';
            $allowedStatus = ['all', 'pending', 'approved', 'rejected', 'completed'];

            // Validate status parameter
            if(empty($status) || !in_array($status, $allowedStatus)){
                $status = 'all';
            }

            // Build base query with join
            $query = $appoint->select('appointments.*, CONCAT(patients.last_name, ", ", patients.first_name, " ", patients.middle_name) as patient_name')
                             ->join('patients', 'appointments.patient_id = patients.patient_id');

            if($status !== 'all'){
                $query = $query->where('appointments.status', $status);
            }

            if ($search) {
                $query = $query->groupStart()
                               ->like('appointments.appointment_id', $search)
                               ->orLike('appointments.patient_id', $search)
                               ->orLike('appointments.purpose', $search)
                               ->orLike('appointments.status', $search)
                               ->orLike('appointments.remarks', $search)
                               ->groupEnd();
            }
            
            $data['appoint'] = $query->orderBy('appointments.appointment_id', $sort)
                                     ->findAll();

            session()->set('hasPatient', true); // Admin and nurse have access to all appointments
        }

        
        
        return view('Appointment/appointment', $data);
    }

    public function add_appointment(){
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('message', 'Please login to continue');
        }

        return view('Appointment/add_appointment');
    }

    public function store_appointment()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('message', 'Please login to continue');
        }

        $appoint = new AppointmentModel();
        $patient = new PatientModel();

        $user_id = session()->get('user_id');

        $exist = $patient->where('user_id', $user_id)->first();

        if(!$exist){
            return redirect()->to('/appointment/add')->with('message', 'Error: Patient information not found');
        }

        $data = [
            'patient_id' => $exist['patient_id'],
            'appointment_date' => request()->getPost('appointment_date'),
            'purpose' => request()->getPost('purpose'),
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s')
        ];

        $appoint->insert($data);

        return redirect()->to('/appointment/add')->with('message', 'Appointment Created Successfully');
    }

    public function edit_appointment($id)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('message', 'Please login to continue');
        }

        $appoint = new AppointmentModel();

        $data['a'] = $appoint->find($id);

        if(!$data['a']){
            return redirect()->to('/appointment')->with('message', 'Error: Appointment not found');
        }

        return view('Appointment/edit_appointment', $data);
    }

    public function update_appointment($id){
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('message', 'Please login to continue');
        }

        $appoint = new AppointmentModel();

        $exist = $appoint->find($id);
        if(!$exist){
            return redirect()->to('/appointment')->with('message', 'Error: Appointment not found');
        }

        if(request()->getPost('activity') === 'save'){

            $data = [
                'status' => request()->getPost('status'),
                'remarks' => request()->getPost('remarks') ?? '',
            ];

            $appoint->update($id, $data);

            // Send email notification
            $this->sendAppointmentStatusEmail($exist['patient_id'], $data['status'], $data['remarks']);

            return redirect()->to('/appointment')->with('message', 'Appointment Saved Successfully');
        }

        $data = [
                'appointment_date' => request()->getPost('appointment_date'),
                'purpose' => request()->getPost('purpose'),
        ];

        $appoint->update($id, $data);

        return redirect()->to('/appointment/edit/'.$id)->with('message', 'Appointment Updated Successfully');
    }

    public function delete_appointment($id)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('message', 'Please login to continue');
        }

        $appoint = new AppointmentModel();

        $exist = $appoint->find($id);
        if(!$exist){
            return redirect()->to('/appointment')->with('message', 'Error: Appointment not found');
        }

        $appoint->delete($id);

        return redirect()->to('/appointment')->with('message', 'Appointment Deleted Successfully');
    }

    private function sendAppointmentStatusEmail($patient_id, $status, $remarks)
    {
        $patient = new PatientModel();
        $user = new UserModel();

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

        // Create status message
        $statusMessage = '';
        $statusColor = '';
        
        switch($status){
            case 'approved':
                $statusMessage = 'APPROVED';
                $statusColor = '#28a745';
                break;
            case 'rejected':
                $statusMessage = 'REJECTED';
                $statusColor = '#dc3545';
                break;
            case 'completed':
                $statusMessage = 'COMPLETED';
                $statusColor = '#007bff';
                break;
            case 'cancelled':
                $statusMessage = 'CANCELLED';
                $statusColor = '#ffc107';
                break;
            default:
                $statusMessage = strtoupper($status);
                $statusColor = '#6c757d';
        }

        // Build email message
        $message = '
            <h2>Appointment Status Update</h2>
            <p>Dear ' . $userInfo['username'] . ',</p><br>
            <p>Your appointment status has been updated:</p><br>
            <div style="background-color: ' . $statusColor . '; color: white; padding: 15px; border-radius: 5px; margin: 15px 0;">
                <h3 style="margin: 0;">Status: ' . $statusMessage . '</h3>
            </div><br>
            ' . (!empty($remarks) ? '<p><strong>Remarks:</strong> ' . $remarks . '</p><br>' : '') . '
            <p>If you have any questions, please contact CSPC Clinic.</p><br>
            <p>Best regards,<br>CSPC Clinic Team</p>
        ';

        // Send email
        $email = \Config\Services::email();
        $email->setTo($userInfo['email']);
        $email->setFrom('jeoffgbanaria@gmail.com', 'CSPC Clinic');
        $email->setSubject('Appointment Status: ' . $statusMessage);
        $email->setMessage($message);
        $email->send();
        $email->clear(true);
    }
}
