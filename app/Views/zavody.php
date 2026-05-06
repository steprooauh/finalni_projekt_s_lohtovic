<?php  
echo($this->extend('Layout/template'));
echo($this->section('content'));
?>

<h1>Přehled závodů</h1>

<div class="row">
<?php
/** @var array $zavody */
foreach ($zavody as $row) : ?>
<div class="disabled-div col-lg-4 col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h4 class="text-center"><a class="odkaz" href="#"><?= $row->real_name ?></a></h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                <p class="text-center">Od: <?= $row->start_date ?></p>
                    </div>
                    <div class="col-6">
                <p class="text-center">Do: <?= $row->end_date ?></p>
                    </div>

                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>
</div>
<?= $this->endSection(); ?>