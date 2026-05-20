<?= $this->extend('Layout/template') ?>
<?= $this->section('content') ?>

<br>
<h1 class="text-center">Přehled závodů</h1>

<div class="d-flex justify-content-between align-items-center mb-4" style="margin-top: -60px;">
    <a class="btn btn-secondary" href="<?= base_url() ?>">
        <i class="fa-solid fa-caret-left"></i> Zpět
    </a>

    <?php if (session()->has('user_id')): ?>
        <div class="btn-group" role="group" aria-label="Filtr závodů">
            <input type="checkbox" class="btn-check" id="btnCheckMoje" autocomplete="off" <?= $jenMoje ? 'checked' : '' ?>>
            <label class="btn btn-outline-primary" for="btnCheckMoje">
                <i class="fa-solid fa-user"></i> Pouze mnou vytvořené
            </label>
        </div>
    <?php endif; ?>

    <a class="btn btn-secondary" href="<?= base_url() ?>" data-bs-toggle="modal" data-bs-target="#pridat">
        Přidat <i class="fa-solid fa-caret-right"></i>
    </a>
</div>

<div class="row">
    <?php
    /** @var array $zavody 
     * @var object $pager
     * @var array $year
     */
    foreach ($zavody as $row) : ?>
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-header d-flex flex-column justify-content-center align-items-center border-bottom-0 py-3" style="height: 140px;">
                    <h5 class="text-center mb-2 fw-bold">
                        <?= anchor('roky/zavod/' . $row->id, $row->real_name, ['class' => 'text-decoration-none text-dark hover-primary']) ?>
                    </h5>

                    <div class="d-flex align-items-center justify-content-center" style="height: 50px;">
                        <?php if ($row->logo != null): ?>
                            <img src="<?= base_url('img/logos/' . $row->logo) ?>" class="img-fluid" style="max-height: 50px; width: auto;">
                        <?php endif; ?>
                    </div>
                </div>

                <div class="card-body d-flex flex-column justify-content-between text-center">
                    <div class="mb-3">
                        <span class="fi fi-<?= $row->country ?> fs-4 shadow-sm rounded-1"></span>
                    </div>

                    <div class="row g-0 bg-light rounded p-2 text-muted small mt-auto">
                        <div class="col-6 border-end">
                            <span class="d-block text-uppercase text-xs fw-semibold" style="color: black; ">Od</span>
                            <span class="text-dark fw-medium"><?= date('d. m. Y', strtotime($row->start_date)) ?></span>
                        </div>
                        <div class="col-6">
                            <span class="d-block text-uppercase text-xs fw-semibold" style="color: black; ">Do</span>
                            <span class="text-dark fw-medium"><?= date('d. m. Y', strtotime($row->end_date)) ?></span>
                        </div>
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

                        echo form_input_bs('nazev', ['id' => 'nazev_add', 'value' => ''], 'Název závodu:', 'text', false);
                        ?>
                        <input type="hidden" name="id_rocniku" id="id_rocniku_add" value="">

                        <?php
                        $roky = [];
                        for ($i = 2015; $i <= date('Y') + 2; $i++) {
                            $roky[$i] = $i;
                        }
                        echo form_dropdown_bs('rok', $roky, ['id' => 'rok_add'], 'mb-3', 'Rok závodu:', $year);

                        $uci_moznosti = [
                            '0'  => 'Není v UCI',
                            '1'  => 'UCI Worldtour',
                            '2'  => 'UCI World Championships',
                            '3'  => 'Africa Tour',
                            '4'  => 'Asia Tour',
                            '5'  => 'Europe Tour',
                            '6'  => 'Men Junior',
                            '7'  => 'Women Elite',
                            '8'  => 'Women Junior',
                            '9'  => 'America Tour',
                            '10' => 'Nations Cup',
                            '11' => 'National Championship',
                            '12' => 'WWT',
                            '13' => 'UCI Pro Series',
                            '14' => 'Oceania Tour'
                        ];

                        echo form_dropdown_bs('id_uci_tour', $uci_moznosti, ['id' => 'uci_tour_add'], 'mb-3', 'UCI Tour:', '0');
                        ?>

                        <div class="mb-3">
                            <label for="logo_add" class="form-label">Logo závodu:</label>
                            <input type="file" name="logo" id="logo_add" class="form-control">
                            <small class="text-muted">Povolené formáty: jpg, png (max 2MB)</small>
                        </div>

                        <div class="modal-footer px-0 pb-0">
                            <button type="submit" class="btn btn-primary">Uložit změny</button>
                        </div>

                        <?php echo form_close(); ?>
                    </div>

                    <div class="tab-pane fade" id="edit-pane" role="tabpanel" aria-labelledby="edit-tab">
                        <?php echo form_open_multipart('zavody/editovat'); ?>

                        <div class="mb-3">
                            <label for="zavod_search" class="form-label">Vyberte nebo vyhledejte závod:</label>
                            <select id="zavod_search" name="zavod_id" autocomplete="off" placeholder="Začněte psát název závodu...">
                                <option value="">--- Vyberte nebo vyhledejte závod ---</option>
                                <?php foreach ($zavody as $row): ?>
                                    <option value="<?php echo $row->id; ?>"><?php echo $row->real_name; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <input type="hidden" name="id_rocniku" id="id_rocniku_edit" value="">

                        <hr>

                        <?php
                        echo form_input_bs('nazev', [
                            'id'       => 'nazev_edit',
                            'value'    => '',
                            'disabled' => 'disabled'
                        ], 'Název závodu:', 'text', false);

                        echo form_dropdown_bs('uci_tour', $uci_moznosti, [
                            'id' => 'uci_tour_edit',
                            'disabled' => 'disabled'
                        ], 'mb-3', 'UCI Tour:', 'none');
                        ?>

                        <div class="mb-3">
                            <label for="logo_edit" class="form-label">Logo závodu:</label>
                            <input type="file" name="logo" id="logo_edit" class="form-control" disabled>
                            <small class="text-muted">Povolené formáty: jpg, png (max 2MB)</small>
                        </div>

                        <div class="modal-footer px-0 pb-0">
                            <button type="submit" id="submit_btn" class="btn btn-primary" disabled>Uložit změny</button>
                        </div>

                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url('node_modules/tom-select/dist/js/tom-select.base.') ?>"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Inicializace vyhledávacího pole
        var select = new TomSelect("#zavod_search", {
            create: false,
            sortField: {
                field: "text",
                direction: "asc"
            }
        });

        // Hlídání změny – nahrazuje původní "change" event pro aktivaci formuláře
        select.on('change', function(value) {
            if (value !== "") {
                // Uživatel vybral závod -> odemknout formulář
                document.getElementById('nazev_edit').removeAttribute('disabled');
                document.getElementById('uci_tour_edit').removeAttribute('disabled');
                document.getElementById('logo_edit').removeAttribute('disabled');
                document.getElementById('submit_btn').removeAttribute('disabled');

                // TADY pravděpodobně spouštíš nějaký AJAX, který ti dotáhne data do "nazev_edit" atd.
                // ID vybraného závodu máš v proměnné: value
            } else {
                // Pokud pole vymaže, formulář se zase zamkne
                document.getElementById('nazev_edit').setAttribute('disabled', 'disabled');
                document.getElementById('uci_tour_edit').setAttribute('disabled', 'disabled');
                document.getElementById('logo_edit').setAttribute('disabled', 'disabled');
                document.getElementById('submit_btn').setAttribute('disabled', 'disabled');
            }
        });

        const filterCheckbox = document.getElementById('btnCheckMoje');
        if (filterCheckbox) {
            filterCheckbox.addEventListener('change', function() {
                let currentUrl = new URL(window.location.href);

                if (this.checked) {
                    currentUrl.searchParams.set('moje', '1');
                } else {
                    currentUrl.searchParams.delete('moje');
                }
 
                window.location.href = currentUrl.toString();
            });
        }
    });
</script>
<?= $this->endSection() ?>