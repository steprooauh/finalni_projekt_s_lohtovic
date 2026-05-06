<?php  
echo($this->extend('Layout/template'));
echo($this->section('content'));
?>
<br>
<h1 class="text-center">Přehled závodů</h1>

<a class="btn btn-secondary" style="margin-top: -100px;" href="<?= base_url() ?>"><i class="fa-solid fa-caret-left"></i>Zpět</a>

<div class="row">
<?php
/** @var array $zavody */
foreach ($zavody as $row) : ?>
<div class="col-lg-4 col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header">
            <h4 class="text-center"><?= anchor('roky/zavod/' . $row->id, $row->real_name, ['class' => 'odkaz']) ?></h4>
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
<div class="row">
    <?= $this->pager->links() ?>
</div>
<?= $this->endSection(); ?>