<?= $this->extend('layouts/base') ?>


<?= $this->section('title') ?>
Home 
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-8">
            <h1>Welcome to Our Company</h1>
            <p>Explore our services and learn more about us.</p>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Employee Directory</h5>
                    <p class="card-text">View all employees and their details.</p>
                    <a href="/employees" class="btn btn-primary">Go to Employees</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

