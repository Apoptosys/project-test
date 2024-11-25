<?= $this->extend('layouts/base') ?>


<?= $this->section('title') ?>
Employees
<?= $this->endSection() ?>

<?= $this->section('content') ?>


<div class="container mt-4">
    <h1>Employee List</h1>

    <!-- Search Bar -->
    <input type="text" id="search" class="form-control mb-3" placeholder="Search employees..." />

    <input type="text" id="jobsearch" class="form-control mb-3" placeholder="Search employees by jobs..." />

    <!-- Employee Table -->
    <table class="table table-bordered table-hover" id="employeeTable">
        <thead>
        <tr>
            <th>#</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>

        </tbody>
    </table>

    <!-- Pagination -->
    <nav id="pagination" aria-label="Page navigation">

    </nav>

    <!-- Add Employee Button -->
    <button id="addEmpBtn" class="btn btn-success my-3">Add Employee</button>

    <!-- Jobs Table -->
    <h2 id="jobsHeader" class="mt-4">Jobs</h2>
    <table class="table table-bordered table-hover" id="jobsTable">
        <thead>
        <tr>
            <th>Job Name</th>
            <th>No. of Hours</th>
            <th>Start Date</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody id="jobsTableBody">
            <tr><td colspan="4" class="text-center">Select an employee to view their jobs.</td></tr>
        </tbody>
    </table>
    <div style="position: fixed; right: 20px; bottom: 20px;">
        <a href="/" class="btn btn-primary">Home</a>
    </div>

    <!-- Add Job Button (Hidden by Default) -->
    <button id="addJobBtn" class="btn btn-success my-3" style="display: none;">Add Job</button>

    <!-- Add Job Modal -->
    <div class="modal fade" id="addJobModal" tabindex="-1" aria-labelledby="addJobModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="addJobModalLabel">Add Job</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form id="addJobForm">
             <div class="form-group">
        <label for="job_name">Job Name</label>
        <input type="text" id="job_name" name="job_name" class="form-control">
        <div id="job_nameError" class="error-message text-danger"></div>
    </div>

    <div class="form-group">
        <label for="no_hours">No. of Hours</label>
        <input type="number" id="no_hours" name="no_hours" class="form-control">
        <div id="no_hoursError" class="error-message text-danger"></div>
    </div>

    <div class="form-group">
        <label for="start_date">Start Date</label>
        <input type="date" id="start_date" name="start_date" class="form-control">
        <div id="start_dateError" class="error-message text-danger"></div>
    </div>
            <input type="hidden" id="selectedEmployeeId" name="employee_id">
            <button type="submit" class="btn btn-primary">Add Job</button>
            </form>
        </div>
        </div>
    </div>
    </div>

    <!-- Add New Employee Modal -->
    <div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="addEmployeeModalLabel">Add New Employee</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form id="addEmployeeForm">
             <div class="form-group">
        <label for="firstname">First Name</label>
        <input type="text" id="firstname" name="firstname" class="form-control">
        <div id="firstnameError" class="error-message text-danger"></div>
    </div>

    <div class="form-group">
        <label for="lastname">Last Name</label>
        <input type="text" id="lastname" name="lastname" class="form-control">
        <div id="lastnameError" class="error-message text-danger"></div>
    </div>
            <button type="submit" class="btn btn-primary">Add Employee</button>
            </form>
        </div>
        </div>
    </div>
    </div>

</div>



<?= $this->endSection() ?>


<?= $this->section('scripts') ?>

    <script src="/js/employee_index.js"></script>

<?= $this->endSection() ?>

