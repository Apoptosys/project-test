

$(document).ready(function () {

    let selectedEmployeeId = null;
    let selectedEmployeeName = null;
    // Function to load employees based on search term and page number
    
    // Function to clear the jobs table
    function clearJobsTable() {
        const jobsTableBody = $('#jobsTableBody');
        jobsTableBody.empty();
        jobsTableBody.append('<tr><td colspan="4" class="text-center">Select an employee to view their jobs.</td></tr>');
        $('#addJobBtn').hide(); // Show the Add Job button
        $('#jobsHeader').text('Jobs');
         

    }

    function loadEmployees(searchValue='', page = 1, searchJob='') {
        $.ajax({
            url: '/employees',
            method: 'GET',
            data: { search: searchValue, page: page, searchjob:searchJob },
            success: function (data) {
                const employeeTableBody = $('#employeeTable tbody');
                const pagination = $('#pagination');

                employeeTableBody.empty();
                pagination.empty();

                if (data.employees && data.employees.length > 0) {
                    data.employees.forEach(employee => {
                        employeeTableBody.append(`
                            <tr data-id="${employee.employee_id}">
                                <td>${employee.employee_id}</td>
                                <td>${employee.firstname}</td>
                                <td>${employee.lastname}</td>
                                <td>
                                    <button class="btn btn-info btn-sm view-jobs" data-id="${employee.employee_id}" data-firstname="${employee.firstname}" data-lastname="${employee.lastname}">
                                        View Jobs
                                    </button>
                                    <button class="btn btn-danger btn-sm delete-employee" data-id="${employee.employee_id}">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        `);
                    });
                    pagination.html(data.pagination || '');
                } else {
                    employeeTableBody.append('<tr><td colspan="4" class="text-center">No employees found.</td></tr>');
                }
            },
            error: function () {
                const employeeTableBody = $('#employeeTable tbody');
                employeeTableBody.empty();
                employeeTableBody.append('<tr><td colspan="4" class="text-center text-danger">Error loading employees.</td></tr>');
            }
        });
    }
    loadEmployees(searchValue='', page= 1); // Load employees initially
    // Automatic search
    let timeout;
    $('#search').on('input', function () {
        const searchValue = $(this).val();
        clearTimeout(timeout);
        timeout = setTimeout(function () {
            $('#jobsearch').val(''); // Clear the search field
            loadEmployees(searchValue); // Load employees with search
        }, 500);
    });


    //Search by job
    let timeout1;
    $('#jobsearch').on('input', function () {
        const searchValue = $(this).val();
        clearTimeout(timeout1);
        timeout1 = setTimeout(function () {
            $('#search').val(''); // Clear the search field
            loadEmployees('',1,searchValue,); // Load employees with search
        }, 500);
    });


    // Intercept pagination link clicks
    $(document).on('click', '.pagination a', function (e) {
        e.preventDefault(); // Prevent default page reload behavior

        const searchValue = $('#search').val(); // Get the current search term
        const page = parseInt($(this).attr('href').match(/page=(\d+)/)[1], 10); // Extract the page number from the link

        loadEmployees(searchValue, page); // Load employees for the selected page
    });

    function getJobs(employeeId) {
        $.get('/employees/getJobs/' + employeeId, function (data) {
            const jobsTableBody = $('#jobsTableBody');
            jobsTableBody.empty();

            if (data && data.length > 0) {
                data.forEach(job => {
                    jobsTableBody.append(`
                        <tr data-job-id= "${job.id}">
                            <td>${job.job_name}</td>
                            <td>${job.no_hours}</td>
                            <td>${job.start_date}</td>
                            <td>
                                <button class="btn btn-danger btn-sm delete-job" data-job-id="${job.id}">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    `);
                });
            } else {
                jobsTableBody.append('<tr><td colspan="4" class="text-center">No jobs found.</td></tr>');
            }
        }).fail(function () {
            const jobsTableBody = $('#jobsTableBody');
            jobsTableBody.empty();
            jobsTableBody.append('<tr><td colspan="4" class="text-center text-danger">Error loading jobs.</td></tr>');
        });
    }

    // Load jobs for selected employee
    $(document).on('click', '.view-jobs', function () {
        selectedEmployeeId = $(this).data('id');
        const employeeFirstName = $(this).data('firstname');
        const employeeLastName = $(this).data('lastname');
        selectedEmployeeName = `${employeeFirstName} ${employeeLastName}`;

        $('#selectedEmployeeId').val(selectedEmployeeId); // Set the hidden input field value
        $('#addJobBtn').show(); // Show the Add Job button

        $('#jobsHeader').text(`Jobs for ${employeeFirstName} ${employeeLastName}`);
        
        // Update jobs table with employee's jobs
        getJobs(selectedEmployeeId);
    });


    // Open the modal when "Add Job" button is clicked
    $('#addJobBtn').on('click', function () {
    const employeeName = $('#jobsHeader').text().replace('Jobs for ', '');
        $('#addJobModalLabel').text(`Add Job for ${employeeName}`);
        $('#addJobModal').modal('show');
    });


    $(document).on('click', '.delete-job', function () {
        const jobId = $(this).data('job-id'); // Get job_id from the button
    
        // Show confirmation dialog
        if (!confirm('Are you sure you want to delete this job?')) {
            return; // Exit if the user cancels
        }
    
        // Send AJAX request to delete the job
        $.ajax({
            url: '/jobs/delete',
            type: 'POST',
            data: { job_id: jobId },
            success: function (response) {
                if (response.success) {
                    // Remove the deleted job row from the table
                    $(`tr[data-job-id="${jobId}"]`).remove();
                    alert('Job deleted successfully!');
                } else {
                    alert('Failed to delete job: ' + (response.errors.db_error || 'Unknown error.'));
                }
            },
            error: function (xhr) {
                console.error(xhr.responseText);
                alert('An error occurred while deleting the job.');
            }
        });
    });

    $(document).on('click', '.delete-employee', function () {
        const employeeId = $(this).data('id'); // Get employee_id from the button

        // Show confirmation dialog
        if (!confirm('Are you sure you want to delete this employee?')) {
            return; // Exit if the user cancels
        }

        // Send AJAX request to delete the employee
        $.ajax({
            url: '/employees/delete',
            type: 'POST',
            data: { employee_id: employeeId },
            success: function (response) {
                if (response.success) {
                    // Reload employees table
                    loadEmployees();
                    clearJobsTable();
                    alert('Employee deleted successfully!');
                } else {
                    alert('Failed to delete employee: ' + (response.errors.db_error || 'Unknown error.'));
                }
            },
            error: function (xhr) {
                console.error(xhr.responseText);
                alert('An error occurred while deleting the employee.');
            }
        });
    });


    $('#addJobForm').on('submit', function (e) {
        e.preventDefault();

        // Remove previous validation errors
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();

        const formData = $(this).serialize(); // Get the form data
        $.ajax({
            url: '/employees/addJob', // Route to handle adding a job
            method: 'POST',
            data: formData,
            success: function (response) {
                if (response.success) {
                    $('#addJobModal').modal('hide'); // Close the modal
                    alert('Job added successfully!');
                    $('#addJobForm')[0].reset(); // Reset the form
                    // Reload jobs for the current employee
                    getJobs(selectedEmployeeId);
                } else {
                    // Display validation errors
                    if (response.errors) {
                        for (const [key, message] of Object.entries(response.errors)) {
                            $(`#${key}`).addClass('is-invalid');
                            $(`#${key}`).next('.invalid-feedback').remove(); // Remove previous feedback
                            $(`#${key}`).after(`<div class="invalid-feedback">${message}</div>`);
                        }
                    } else {
                        alert('An error occurred while adding the job.');
                    }
                }
            },
            error: function () {
                alert('An error occurred while adding the job.');
            }
        });
    });


    $('#addEmpBtn').on('click', function () {
        $('#addEmployeeModal').modal('show');
    });

    // Handle the form submission for adding an employee
    $('#addEmployeeForm').on('submit', function (e) {
        e.preventDefault();

        const formData = $(this).serialize(); // Get the form data
        $.ajax({
            url: '/employees/addEmployee', // Route to handle adding a new employee
            method: 'POST',
            data: formData,
            success: function (response) {
                if (response.success) {
                    $('#addEmployeeModal').modal('hide'); // Close the modal
                    alert('Employee added successfully!');
                    $('#addEmployeeForm')[0].reset(); // Reset the form
                    // Optionally, reload the employee list or update the table
                    loadEmployees(); 
                } else {
                    // Display validation errors
                    if (response.errors) {
                        for (const [key, message] of Object.entries(response.errors)) {
                            $(`#${key}`).addClass('is-invalid');
                            $(`#${key}`).next('.invalid-feedback').remove(); // Remove previous feedback
                            $(`#${key}`).after(`<div class="invalid-feedback">${message}</div>`);
                        }
                    } else {
                        alert('An error occurred while adding the employee.');
                    }
                }
            },
            error: function () {
                alert('An error occurred while adding the employee.');
            }
        });
    });

});