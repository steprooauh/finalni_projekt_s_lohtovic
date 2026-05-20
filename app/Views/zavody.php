<?php
echo ($this->extend('Layout/template'));
echo ($this->section('content'));
?>
<br>
<h1 class="text-center">Přehled závodů</h1>

<div class="d-flex justify-content-between mb-4" style="margin-top: -60px;">
    <a class="btn btn-secondary" href="<?= base_url() ?>">
        <i class="fa-solid fa-caret-left"></i> Zpět
    </a>

    <a class="btn btn-secondary" href="<?= base_url() ?>" data-bs-toggle="modal" data-bs-target="#pridat">
        Přidat <i class="fa-solid fa-caret-right"></i>
    </a>
</div>

<div class="row">
<<<<<<< HEAD
    <?php
    /** @var array $zavody 
     * @var array $year
     * @var object $pager
     */
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
=======
<?php
/** @var array $zavody 
 * @var object $pager
*/
foreach ($zavody as $row) : ?>
<div class="col-lg-4 col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header">
            <h4 class="text-center"><?= anchor('roky/zavod/' . $row->id, $row->real_name, ['class' => 'odkaz']) ?></h4>
            <?php if ($row->logo != null): ?>
            <div class="row justify-content-center">
                <div class="col justify-content-center d-flex mx-auto">
            <img src="<?= base_url('img/logos/' . $row->logo) ?>" class="img-fluid w-25" alt="<?= $row->real_name ?>">
                </div>
            </div>
            <?php endif; ?>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                <p class="text-center">Od: <?= $row->start_date ?></p>
>>>>>>> ceb95d6a6c617b72bf5681b3e81fa3718f0c6d29
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<div class="row">
    <?= $pager->links() ?>
</div>

<div class="modal fade" id="pridat" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Editace závodu</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Zavřít"></button>
            </div>

            <div class="modal-body">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="pridat-tab" data-bs-toggle="tab" data-bs-target="#pridat-pane" type="button" role="tab">Přidat</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="edit-tab" data-bs-toggle="tab" data-bs-target="#edit-pane" type="button" role="tab">Editace</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link text-danger" id="smazat-tab" data-bs-toggle="tab" data-bs-target="#smazat-pane" type="button" role="tab">Smazat</button>
                    </li>
                </ul>

                <div class="tab-content border-start border-end border-bottom p-3" id="myTabContent">

                    <div class="tab-pane fade show active" id="pridat-pane" role="tabpanel" aria-labelledby="pridat-tab">
                        <?php echo form_open_multipart('zavody/pridat');

                        echo form_input_bs('nazev', ['id'    => 'nazev', 'value' => ''], 'Název závodu:', 'text', false);
                        ?>
                        <input type="hidden" name="id_rocniku" id="id_rocniku" value="">


                        <?php
                        $roky = [];
                        for ($i = 2015; $i <= date('Y') + 2; $i++) {
                            $roky[$i] = $i;
                        }
                        echo form_dropdown_bs('rok', $roky, ['id' => 'rok'], 'mb-3', 'Rok závodu:', $year);

                        $uci_moznosti = [
                            'none' => 'Není v UCI',
                            'wt'   => 'World Tour',
                            'pro'  => 'ProSeries',
                            'cont' => 'Continental'
                        ];
                        echo form_dropdown_bs('uci_tour', $uci_moznosti, ['id' => 'uci_tour'], 'mb-3', 'UCI Tour:', 'none');
                        ?>

                        <div class="mb-3">
                            <label for="logo" class="form-label">Logo závodu:</label>
                            <input type="file" name="logo" id="logo" class="form-control">
                            <small class="text-muted">Povolené formáty: jpg, png (max 2MB)</small>
                        </div>

                        <div class="modal-footer px-0 pb-0">
                            <button type="submit" class="btn btn-primary">Uložit změny</button>
                        </div>

                        <?php echo form_close(); ?>
                    </div>

                    <div class="tab-pane fade" id="edit-pane" role="tabpanel" aria-labelledby="edit-tab">
                        <?php echo form_open_multipart('zavody/editovat'); ?>

                        <?php
                        $zavody_moznosti = ['' => '--- Vyberte závod ---'];

                        foreach ($zavody as $row) {
                            $zavody_moznosti[$row->id] = $row->real_name;
                        }

                        echo form_dropdown_bs('zavod_id', $zavody_moznosti, ['id' => 'zavod_id'], 'mb-3', 'Vyberte závod:', '');
                        ?>

                        <input type="hidden" name="id_rocniku" id="id_rocniku" value="">

                        <hr> <?php
                                echo form_input_bs('nazev', [
                                    'id'       => 'nazev',
                                    'value'    => '',
                                    'disabled' => 'disabled' // Zablokováno
                                ], 'Název závodu:', 'text', false);
                                ?>

                        <?php
                        $uci_moznosti = [
                            'none' => 'Není v UCI',
                            'wt'   => 'World Tour',
                            'pro'  => 'ProSeries',
                            'cont' => 'Continental'
                        ];
                        echo form_dropdown_bs('uci_tour', $uci_moznosti, [
                            'id' => 'uci_tour',
                            'disabled' => 'disabled' // Zablokováno
                        ], 'mb-3', 'UCI Tour:', 'none');
                        ?>

                        <div class="mb-3">
                            <label for="logo" class="form-label">Logo závodu:</label>
                            <input type="file" name="logo" id="logo" class="form-control" disabled> <small class="text-muted">Povolené formáty: jpg, png (max 2MB)</small>
                        </div>

                        <div class="modal-footer px-0 pb-0">
                            <button type="submit" id="submit_btn" class="btn btn-primary" disabled>Uložit změny</button>
                        </div>

                        <?php echo form_close(); ?>
                    </div>
                </div>

                <div class="tab-pane fade" id="smazat-pane" role="tabpanel" aria-labelledby="smazat-tab">
                </div>

            </div>
        </div>
    </div>
</div>
</div>

<?= $this->endSection(); ?>