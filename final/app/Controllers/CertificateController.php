<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\CertificateModel;
use App\Models\PatientModel;

class CertificateController extends BaseController
{
    public function certificate()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('message', 'Please login to continue');
        }

        $cert = new CertificateModel();
        $patientModel = new PatientModel();
        $search = request()->getGet('search') ?? '';
        $sort = request()->getGet('sort') ?? 'asc';
        
        // Validate sort parameter
        if (!in_array($sort, ['asc', 'desc'])) {
            $sort = 'asc';
        }

        // Student and Staff can only see their own certificates
        if(session()->get('role') === 'student' || session()->get('role') === 'staff'){
            $user_id = session()->get('user_id');
            $myPatient = $patientModel->where('user_id', $user_id)->first();
            
            if($myPatient){
                if ($search) {
                    $data['certificate'] = $cert->where('patient_id', $myPatient['patient_id'])
                        ->groupStart()
                        ->like('certificate_id', $search)
                        ->orLike('certificate_type', $search)
                        ->groupEnd()
                        ->orderBy('certificate_id', $sort)
                        ->findAll();
                } else {
                    $data['certificate'] = $cert->where('patient_id', $myPatient['patient_id'])
                        ->orderBy('certificate_id', $sort)
                        ->findAll();
                }
            }
            else{
                $data['certificate'] = [];
            }
        }
        else{
            // Admin and Nurse can see all certificates
            if ($search) {
                $data['certificate'] = $cert
                    ->groupStart()
                    ->like('certificate_id', $search)
                    ->orLike('patient_id', $search)
                    ->orLike('certificate_type', $search)
                    ->groupEnd()
                    ->orderBy('certificate_id', $sort)
                    ->findAll();
            } else {
                $data['certificate'] = $cert->orderBy('certificate_id', $sort)->findAll();
            }
        }

        return view('Certificate/certificate', $data);
    }

    public function add_certificate()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('message', 'Please login to continue');
        }

        // Only admin and nurse can add certificates
        if(session()->get('role') === 'student' || session()->get('role') === 'staff'){
            return redirect()->to('/certificate')->with('message', 'Error: You do not have permission to add certificates');
        }

        return view('Certificate/add_certificate');
    }

    public function store_certificate()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('message', 'Please login to continue');
        }

        // Only admin and nurse can create certificates
        if(session()->get('role') === 'student' || session()->get('role') === 'staff'){
            return redirect()->to('/certificate')->with('message', 'Error: You do not have permission to create certificates');
        }

        $cert = new CertificateModel();

        $patient_id = request()->getPost('patient_id');
        $patient = new PatientModel();

        $exist = $patient->where('patient_id', $patient_id)->first();

        if(!$exist){
            return redirect()->to('/certificate/add')->with('message', 'Patient ID does not exist');
        }

        $data = [
            'patient_id' => $patient_id,
            'consultation_id' => request()->getPost('consultation_id') ?: null,
            'issued_by' => session()->get('user_id'),
            'certificate_type' => request()->getPost('certificate_type'),
            'diagnosis_summary' => request()->getPost('diagnosis_summary'),
            'recommendation' => request()->getPost('recommendation'),
            'validity_start' => request()->getPost('validity_start'),
            'validity_end' => request()->getPost('validity_end'),
            'file_path' => request()->getPost('file_path') ?: null,
        ];

        $cert->insert($data);

        return redirect()->to('/certificate/add')->with('message', 'Certificate Added Successfully');
    }

    public function view_certificate($id)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('message', 'Please login to continue');
        }

        $cert = new CertificateModel();
        $patientModel = new PatientModel();

        $data['cert'] = $cert->find($id);

        if(!$data['cert']){
            return redirect()->to('/certificate')->with('message', 'Error: Certificate not found');
        }

        // Student and Staff can only view their own certificate
        if(session()->get('role') === 'student' || session()->get('role') === 'staff'){
            $user_id = session()->get('user_id');
            $myPatient = $patientModel->where('user_id', $user_id)->first();
            
            if(!$myPatient || $data['cert']['patient_id'] != $myPatient['patient_id']){
                return redirect()->to('/certificate')->with('message', 'Error: You can only view your own certificate');
            }
        }

        return view('Certificate/view_certificate', $data);
    }

    public function edit_certificate($id)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('message', 'Please login to continue');
        }

        // Only admin and nurse can edit certificates
        if(session()->get('role') === 'student' || session()->get('role') === 'staff'){
            return redirect()->to('/certificate')->with('message', 'Error: You do not have permission to edit certificates');
        }

        $cert = new CertificateModel();

        $data['cert'] = $cert->find($id);

        if(!$data['cert']){
            return redirect()->to('/certificate')->with('message', 'Error: Certificate not found');
        }

        return view('Certificate/edit_certificate', $data);
    }

    public function update_certificate($id)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('message', 'Please login to continue');
        }

        // Only admin and nurse can update certificates
        if(session()->get('role') === 'student' || session()->get('role') === 'staff'){
            return redirect()->to('/certificate')->with('message', 'Error: You do not have permission to update certificates');
        }

        $cert = new CertificateModel();

        $data = [
            'patient_id' => request()->getPost('patient_id'),
            'consultation_id' => request()->getPost('consultation_id') ?: null,
            'certificate_type' => request()->getPost('certificate_type'),
            'diagnosis_summary' => request()->getPost('diagnosis_summary'),
            'recommendation' => request()->getPost('recommendation'),
            'validity_start' => request()->getPost('validity_start'),
            'validity_end' => request()->getPost('validity_end'),
            'file_path' => request()->getPost('file_path') ?: null,
        ];

        $cert->update($id, $data);

        return redirect()->to('/certificate/edit/'.$id)->with('message', 'Certificate Updated Successfully');
    }

    public function delete_certificate($id)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('message', 'Please login to continue');
        }

        // Only admin and nurse can delete certificates
        if(session()->get('role') === 'student' || session()->get('role') === 'staff'){
            return redirect()->to('/certificate')->with('message', 'Error: You do not have permission to delete certificates');
        }

        $cert = new CertificateModel();
        
        $exist = $cert->find($id);
        if(!$exist){
            return redirect()->to('/certificate')->with('message', 'Error: Certificate not found');
        }

        $cert->delete($id);

        return redirect()->to('/certificate')->with('message', 'Certificate #' . $id . ' Deleted Successfully');
    }

    public function export_pdf($id)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('message', 'Please login to continue');
        }

        $cert = new CertificateModel();
        $patient = new PatientModel();
        $patientModel = new PatientModel();

        $data['cert'] = $cert->find($id);

        if(!$data['cert']){
            return redirect()->to('/certificate')->with('message', 'Error: Certificate not found');
        }

        // Student and Staff can only export their own certificate
        if(session()->get('role') === 'student' || session()->get('role') === 'staff'){
            $user_id = session()->get('user_id');
            $myPatient = $patientModel->where('user_id', $user_id)->first();
            
            if(!$myPatient || $data['cert']['patient_id'] != $myPatient['patient_id']){
                return redirect()->to('/certificate')->with('message', 'Error: You can only export your own certificate');
            }
        }

        $data['patient'] = $patient->find($data['cert']['patient_id']);

        // Get issuer details
        $userModel = new \App\Models\UserModel();
        $data['issuer'] = $userModel->find($data['cert']['issued_by']);

        // Generate PDF using MPDF
        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_top' => 10,
            'margin_bottom' => 10,
            'margin_left' => 15,
            'margin_right' => 15,
        ]);

        $html = view('Certificate/certificate_pdf', $data);
        $mpdf->WriteHTML($html);
        $filename = 'Certificate_' . $data['cert']['certificate_id'] . '_' . date('Ymd_His') . '.pdf';
        $mpdf->Output($filename, 'D');
    }
}
