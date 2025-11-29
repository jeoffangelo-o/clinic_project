<?php

namespace App\Controllers;

use App\Models\PatientModel;
use App\Models\ConsultationModel;
use App\Models\AppointmentModel;
use App\Models\InventoryModel;
use App\Models\UserModel;

class Home extends BaseController
{
    public function index(): string
    {
        $data['role'] = session()->get('role');
        $data['username'] = session()->get('username');
        $data['user_id'] = session()->get('user_id');
        $data['isLoggedIn'] = session()->get('isLoggedIn');
        
        if (!$data['isLoggedIn']) {
            return view('dashboard', $data);
        }

        // Fetch statistics based on role
        $patientModel = new PatientModel();
        $consultationModel = new ConsultationModel();
        $appointmentModel = new AppointmentModel();
        $inventoryModel = new InventoryModel();
        $userModel = new UserModel();

        // Role-specific statistics
        if($data['role'] === 'student' || $data['role'] === 'staff'){
            // For Student/Staff: Show only their own data
            $user_id = $data['user_id'];
            $myPatient = $patientModel->where('user_id', $user_id)->first();
            
            if($myPatient){
                $patient_id = $myPatient['patient_id'];
                $data['my_patient'] = $myPatient;
                $data['total_patients'] = 1; // Only their own
                $data['total_consultations'] = $consultationModel->where('patient_id', $patient_id)->countAllResults();
                $data['total_appointments'] = $appointmentModel->where('patient_id', $patient_id)->countAllResults();
                
                // Appointment status for their own appointments
                $data['pending_appointments'] = $appointmentModel->where('patient_id', $patient_id)->where('status', 'pending')->countAllResults();
                $data['approved_appointments'] = $appointmentModel->where('patient_id', $patient_id)->where('status', 'approved')->countAllResults();
                $data['completed_appointments'] = $appointmentModel->where('patient_id', $patient_id)->where('status', 'completed')->countAllResults();
                $data['cancelled_appointments'] = $appointmentModel->where('patient_id', $patient_id)->where('status', 'cancelled')->countAllResults();
                
                // Recent consultations for their own patient
                $data['recent_consultations'] = $consultationModel
                    ->where('patient_id', $patient_id)
                    ->orderBy('consultation_date', 'DESC')
                    ->limit(5)
                    ->findAll();

                // Recent appointments for their own patient
                $data['recent_appointments'] = $appointmentModel
                    ->where('patient_id', $patient_id)
                    ->orderBy('created_at', 'DESC')
                    ->limit(5)
                    ->findAll();
            }
            else{
                // No patient record yet
                $data['total_patients'] = 0;
                $data['total_consultations'] = 0;
                $data['total_appointments'] = 0;
                $data['pending_appointments'] = 0;
                $data['approved_appointments'] = 0;
                $data['completed_appointments'] = 0;
                $data['cancelled_appointments'] = 0;
                $data['recent_consultations'] = [];
                $data['recent_appointments'] = [];
            }
            
            $data['total_users'] = 0; // Not shown for student/staff
            $data['total_inventory'] = 0; // Not shown for student/staff
            $data['low_stock_items'] = 0; // Not shown for student/staff
        }
        else if($data['role'] === 'nurse'){
            // For Nurse: Show all data except user management
            $data['total_patients'] = $patientModel->countAll();
            $data['total_consultations'] = $consultationModel->countAll();
            $data['total_appointments'] = $appointmentModel->countAll();
            $data['total_users'] = 0; // Not shown for nurse
            $data['total_inventory'] = $inventoryModel->countAll();

            // Appointment status breakdown
            $data['pending_appointments'] = $appointmentModel->where('status', 'pending')->countAllResults();
            $data['approved_appointments'] = $appointmentModel->where('status', 'approved')->countAllResults();
            $data['completed_appointments'] = $appointmentModel->where('status', 'completed')->countAllResults();
            $data['cancelled_appointments'] = $appointmentModel->where('status', 'cancelled')->countAllResults();

            // Low stock items
            $data['low_stock_items'] = $inventoryModel->where('quantity <', 5)->countAllResults();

            // Recent consultations
            $data['recent_consultations'] = $consultationModel
                ->orderBy('consultation_date', 'DESC')
                ->limit(5)
                ->findAll();

            // Recent appointments
            $data['recent_appointments'] = $appointmentModel
                ->orderBy('created_at', 'DESC')
                ->limit(5)
                ->findAll();
        }
        else{
            // For Admin: Show all statistics
            $data['total_patients'] = $patientModel->countAll();
            $data['total_consultations'] = $consultationModel->countAll();
            $data['total_appointments'] = $appointmentModel->countAll();
            $data['total_users'] = $userModel->countAll();
            $data['total_inventory'] = $inventoryModel->countAll();

            // Appointment status breakdown
            $data['pending_appointments'] = $appointmentModel->where('status', 'pending')->countAllResults();
            $data['approved_appointments'] = $appointmentModel->where('status', 'approved')->countAllResults();
            $data['completed_appointments'] = $appointmentModel->where('status', 'completed')->countAllResults();
            $data['cancelled_appointments'] = $appointmentModel->where('status', 'cancelled')->countAllResults();

            // Low stock items
            $data['low_stock_items'] = $inventoryModel->where('quantity <', 5)->countAllResults();

            // Recent consultations
            $data['recent_consultations'] = $consultationModel
                ->orderBy('consultation_date', 'DESC')
                ->limit(5)
                ->findAll();

            // Recent appointments
            $data['recent_appointments'] = $appointmentModel
                ->orderBy('created_at', 'DESC')
                ->limit(5)
                ->findAll();
        }

        return view('dashboard', $data);
    }
}