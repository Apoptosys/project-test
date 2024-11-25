<?php

namespace App\Controllers;

use App\Models\EmployeeJobModel;
use App\Models\EmployeeModel;

class EmployeeController extends BaseController
{
    
    public function index()
    {
        $employeeModel = new EmployeeModel();
        $jobsModel = new EmployeeJobModel();
        $search = $this->request->getVar('search');
        $page = $this->request->getVar('page') ?? 1; // Default to page 1 if no page is provided
        $searchJob = $this->request->getVar('searchjob');
        $query = $employeeModel;
        $jobQuery = $jobsModel;
        if($searchJob == '') {
            
        
        if ($search) {
            $query = $query->like('firstname', $search, 'after')
                           ->orLike('lastname', $search, 'after');
        }
    
        if ($this->request->isAJAX()) {
            $employees = $query->paginate(3, 'default', $page, );
            $pager = $employeeModel->pager;
            return $this->response->setJSON([
                'employees' => $employees ?? [],
                'pagination' => $pager->links('default', 'bootstrap') ?? '',
            ]);
        }
    
        // $data['employees'] = $query->paginate(3, 'default', $page);
        // $data['pager'] = $employeeModel->pager;
    } else {
            $query = $query->distinct()
                           ->join('employee_jobs', 'employees.employee_id = employee_jobs.employee_id')
                           ->like('employee_jobs.job_name', $searchJob, 'both')
                           ->select('employees.*');

        if ($this->request->isAJAX()) {
            $jobs = $query->paginate(3, 'default', $page, );
            $pager = $employeeModel->pager;
            return $this->response->setJSON([
                'employees' => $jobs ?? [],
                'pagination' => $pager->links('default', 'bootstrap') ?? '',
            ]);
        }
    }
        return view('employees/index',);
    }

    public function addEmployee()
    {
        // Validate the input data
        $validation = \Config\Services::validation();
    
        $validation->setRules([
            'firstname' => 'required|string|min_length[3]|max_length[100]',
            'lastname' => 'required|string|min_length[3]|max_length[100]',
        ]);
    
        // Check validation
        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => $validation->getErrors(),
            ]);
        }
    
        // Insert the data into the database
        $employeeModel = new EmployeeModel();
        $data = [
            'firstname' => $this->request->getPost('firstname'),
            'lastname' => $this->request->getPost('lastname'),
        ];
    
        if ($employeeModel->insert($data)) {
            return $this->response->setJSON(['success' => true]);
        }
    
        return $this->response->setJSON([
            'success' => false,
            'errors' => ['db_error' => 'Failed to add employee.'],
        ]);
    }

    
    public function deleteEmployee()
    {
        $employeeId = $this->request->getPost('employee_id'); // Get employee_id from the request

        // Validate that employee_id is provided and exists in the database
        $validation = \Config\Services::validation();
        $validation->setRules([
            'employee_id' => 'required|integer|is_not_unique[employees.employee_id]',
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => $validation->getErrors(),
            ]);
        }

        // Delete the employee
        $employeeModel = new EmployeeModel();
        if ($employeeModel->delete($employeeId)) {
            return $this->response->setJSON(['success' => true]);
        }

        return $this->response->setJSON([
            'success' => false,
            'errors' => ['db_error' => 'Failed to delete the employee.'],
        ]);
    }

    public function deleteJob()
    {
        $jobId = $this->request->getPost('job_id'); // Get job_id from the request

        // Validate that job_id is provided and exists in the database
        $validation = \Config\Services::validation();
        $validation->setRules([
            'job_id' => 'required|integer|is_not_unique[employee_jobs.id]',
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => $validation->getErrors(),
            ]);
        }

        // Delete the job
        $employeeJobModel = new EmployeeJobModel();
        if ($employeeJobModel->delete($jobId)) {
            return $this->response->setJSON(['success' => true]);
        }

        return $this->response->setJSON([
            'success' => false,
            'errors' => ['db_error' => 'Failed to delete the job.'],
        ]);
    }




    public function addJob()
    {
        $validation = \Config\Services::validation();

        // Define validation rules
        $validation->setRules([
            'employee_id' => 'required|integer|is_not_unique[employees.employee_id]',
            'job_name' => 'required|string|min_length[3]|max_length[100]',
            'no_hours' => 'required|integer|greater_than[0]',
            'start_date' => 'required|valid_date[Y-m-d]'
        ]);

        // Check if validation fails
        if (!$validation->withRequest($this->request)->run()) {
            // echo implode("\n", $validation->getErrors());
            return $this->response->setJSON([
                'success' => false,
                'errors' => $validation->getErrors()
            ]);
        }

        // Insert the validated data into the database
        $employeeJobModel = new EmployeeJobModel();
        $data = [
            'employee_id' => $this->request->getPost('employee_id'),
            'job_name' => $this->request->getPost('job_name'),
            'no_hours' => $this->request->getPost('no_hours'),
            'start_date' => $this->request->getPost('start_date'),
        ];

        if ($employeeJobModel->insert($data)) {
            return $this->response->setJSON(['success' => true]);
        }

        return $this->response->setJSON(['success' => false, 'errors' => ['db_error' => 'Failed to add job.']]);
    }








    public function getJobs($employeeId)
    {
        $employeeJobModel = new EmployeeJobModel();
        $jobs = $employeeJobModel->where('employee_id', $employeeId)->findAll();

        return $this->response->setJSON($jobs);
    }
    
    public function getEmployees()
    {
        $employeeModel = new EmployeeModel();
        $employees = $employeeModel->findAll();

        return $this->response->setJSON($employees);
    }




}
