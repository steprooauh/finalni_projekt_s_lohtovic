<?php  
echo($this->extend('Layout/template'));
echo($this->section('content'));
?>

<h1 class="text-center mt-4">Výpis ročníků</h1>
<div class="row mx-5">
<?php foreach($Year as $row):?>
<div class="col-12 col-sm-10 col-md-6 col-lg-4 my-3">
    <div class="card">
        <div class="card-header h4"></div>
        <div class="card-body">
            <?= anchor('roky/' . $row->year, 'Ukázat rozpis závodů', ['class' => 'btn btn-outline-secondary']) ?>
        </div>
    </div>
</div>  
<?php endforeach; ?>
</div>

<?= $this->endSection(); ?>