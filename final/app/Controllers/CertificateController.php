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
        $cert = new CertificateModel();

        $data['certificate'] = $cert->findAll();

        return view('Certificate/certificate', $data);
    }

    public function add_certificate()
    {
        return view('Certificate/add_certificate');
    }

    public function store_certificate()
    {
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
        $cert = new CertificateModel();

        $data['cert'] = $cert->find($id);

        if(!$data['cert']){
            return redirect()->to('/certificate')->with('message', 'Error: Certificate not found');
        }

        return view('Certificate/view_certificate', $data);
    }

    public function edit_certificate($id)
    {
        $cert = new CertificateModel();

        $data['cert'] = $cert->find($id);

        if(!$data['cert']){
            return redirect()->to('/certificate')->with('message', 'Error: Certificate not found');
        }

        return view('Certificate/edit_certificate', $data);
    }

    public function update_certificate($id)
    {
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
        $cert = new CertificateModel();
        
        $exist = $cert->find($id);
        if(!$exist){
            return redirect()->to('/certificate')->with('message', 'Error: Certificate not found');
        }

        $cert->delete($id);

        return redirect()->to('/certificate')->with('message', 'Certificate #' . $id . ' Deleted Successfully');
    }
}
