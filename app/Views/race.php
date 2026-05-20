<?php
echo ($this->extend('Layout/template'));
echo ($this->section('content'));
?>
<br>

<?php
/**
 * @var object $zavod
 * @var object $year
 * 
 */
?>

<h1 class="text-center">Závod: <?= $zavod->default_name; ?></h1>

<div class="d-flex justify-content-between align-items-center mb-4">
    <a class="btn btn-secondary" href="<?= base_url('index.php/roky') .'/' . $year ?>">
        <i class="fa-solid fa-caret-left"></i> Zpět
    </a>
</div>

<div class="card">
  <div class="card-header">Rok: <?= $year ?></div>
  <div class="card-body">Nějaký info o tom</div>
  <div class="card-footer">Logo</div>
</div>

<?= $this->endSection(); ?>