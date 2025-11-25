<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\ReportModel;

class ReportController extends BaseController
{
    public function report()
    {
        $report = new ReportModel();

        $data['report'] = $report->findAll();

        return view('Report/report', $data);
    }

    public function generate_report()
    {
        return view('Report/generate_report');
    }

    public function store_report()
    {
        $report = new ReportModel();

        $data = [
            'generated_by' => session()->get('user_id'),
            'report_type' => request()->getPost('report_type'),
            'file_path' => request()->getPost('file_path') ?: null,
        ];

        $report->insert($data);

        return redirect()->to('/report')->with('message', 'Report Generated Successfully');
    }

    public function view_report($id)
    {
        $report = new ReportModel();

        $data['rep'] = $report->find($id);

        return view('Report/view_report', $data);
    }

    public function delete_report($id)
    {
        $report = new ReportModel();
        
        $report->delete($id);

        return redirect()->to('/report')->with('message', 'Report #' . $id . ' Deleted Successfully');
    }
}
