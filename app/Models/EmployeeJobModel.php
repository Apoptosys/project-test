<?php

namespace App\Models;

use CodeIgniter\Model;

class EmployeeJobModel extends Model
{

    protected $table = 'employee_jobs'; // Table name
    protected $primaryKey = 'id'; // Primary key
    protected $allowedFields = ['employee_id', 'job_name', 'no_hours', 'start_date']; // Columns that can be inserted/updated
    
}
