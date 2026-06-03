<?= $this->extend('Layout/template') ?>
<?= $this->section('content') ?>

<div class="container py-4">
    <h1 class="text-center mb-4">Přehled závodů</h1>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <a class="btn btn-secondary" href="<?= base_url() ?>">
            <i class="fa-solid fa-caret-left"></i> Zpět
        </a>

        <?php // if (session()->has('user_id')): 
        ?>
        <div class="btn-group" role="group" aria-label="Filtr závodů">
            <input type="checkbox" class="btn-check" id="btnCheckMoje" autocomplete="off" <?= (isset($jenMoje) && $jenMoje) ? 'checked' : '' ?>>
            <label class="btn btn-outline-primary" for="btnCheckMoje">
                <i class="fa-solid fa-user"></i> Pouze mnou vytvořené
            </label>
        </div>
        <?php // endif; 
        ?>

        <a class="btn btn-secondary" href="#" data-bs-toggle="modal" data-bs-target="#pridat">
            Přidat / Upravit <i class="fa-solid fa-caret-right"></i>
        </a>
    </div>

    <div class="row">
        <?php
        /** @var array $zavody 
         * @var object $pager
         * @var array $year
         * @var array $uci_moznosti
         * @var object $stage
         * @var int $distance
         * @var int $elevation
         */
        foreach ($zavody as $row) : ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-header d-flex flex-column justify-content-center align-items-center border-bottom-0 py-3" style="height: 140px;">
                        <h5 class="text-center mb-2 fw-bold">
                            <?= anchor('roky/zavod/' . $row->id, $row->real_name, ['class' => 'text-decoration-none text-dark hover-primary']) ?>
                        </h5>

                        <div class="d-flex align-items-center justify-content-center" style="height: 50px;">
                            <?php if (!empty($row->logo)): ?>
                                <img src="<?= base_url('img/logos/' . $row->logo) ?>" class="img-fluid" style="max-height: 50px; width: auto;">
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="card-body d-flex flex-column justify-content-between text-center">
                        <div class="mb-3">
                            <span class="fi fi-<?= $row->country ?> fs-4 shadow-sm rounded-1"></span>
                        </div>

                        <div class="row g-0 bg-light rounded p-2 text-muted small mt-auto">
                            <div class="div row"> <!-- vzelanost -->
                                <div class="col">
                                    <span class="d-block text-uppercase text-xs fw-semibold" style="color: black; ">Délka závodu</span>
                                    <span class="text-dark fw-medium" style="color: black; ">
                                        <?php $stage = new \App\Models\Stage();
                                        $distance = $stage->join('race_year', 'stage.id_race_year = race_year.id')
                                        ->join('race', 'race.id = race_year.id_race')->where('race_year.id', $row->id)
                                        ->where('race_year.year', $year)->selectSum('distance')->get()->getRow()->distance;?>
                                        <?=  $distance ?> km</span>
                                </div>
                            </div>
                            <div class="div row"> <!-- previskani -->
                                <div class="col">
                                    <span class="d-block text-uppercase text-xs fw-semibold" style="color: black; ">Převýšení</span>
                                    <span class="text-dark fw-medium" style="color: black; ">
                                        <?php $stage = new \App\Models\Stage();
                                        $elevation = $stage->join('race_year', 'stage.id_race_year = race_year.id')
                                        ->join('race', 'race.id = race_year.id_race')->where('race_year.id', $row->id)
                                        ->where('race_year.year', $year)->selectSum('vertical_meters', 'elevation')->get()->getRow()->elevation;?>
                                        <?=  $elevation ?> m</span>
                                </div>
                            </div>
                            
                            <div class="div row">
                                <?php if ($row->start_date == $row->end_date) : ?>
                                <div class="col-12">
                                    <span class="d-block text-uppercase text-xs fw-semibold" style="color: black; ">Datum</span>
                                    <span class="text-dark fw-medium"><?= date('d. m. Y', strtotime($row->start_date)) ?></span>
                                </div> <?php else : ?>
                                <div class="col-6 border-end">
                                    <span class="d-block text-uppercase text-xs fw-semibold" style="color: black; ">Od</span>
                                    <span class="text-dark fw-medium"><?= !empty($row->start_date) ? date('d. m. Y', strtotime($row->start_date)) : '' ?></span>
                                </div>
                                <div class="col-6">
                                    <span class="d-block text-uppercase text-xs fw-semibold" style="color: black; ">Do</span>
                                    <span class="text-dark fw-medium"><?= !empty($row->end_date) ? date('d. m. Y', strtotime($row->end_date)) : '' ?></span>
                                </div> <?php endif; ?>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="row mt-3">
        <?= $pager->links() ?>
    </div>
</div>

<div class="modal fade" id="pridat" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Správa závodů</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Zavřít"></button>
            </div>

            <div class="modal-body">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="pridat-tab" data-bs-toggle="tab" data-bs-target="#pridat-pane" type="button" role="tab">Přidat nový</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="edit-tab" data-bs-toggle="tab" data-bs-target="#edit-pane" type="button" role="tab">Editace</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link text-warning" id="smazat-tab" data-bs-toggle="tab" data-bs-target="#smazat-pane" type="button" role="tab">Odstranit</button>
                    </li>
                </ul>

                <div class="tab-content border-start border-end border-bottom p-3" id="myTabContent">

                    <div class="tab-pane fade show active" id="pridat-pane" role="tabpanel" aria-labelledby="pridat-tab">
                        <?php echo form_open_multipart('zavody/pridat'); ?>
                        <?php echo form_input_bs('nazev', ['id' => 'nazev_add', 'value' => ''], 'Název závodu:', 'text', true); ?>
                        <input type="hidden" name="id_rocniku" id="id_rocniku_add" value="">

                        <div class="form-floating mb-3">
                            <select name="rok" id="rok_add" class="form-select">
                                <?php for ($i = 2015; $i <= date('Y') + 2; $i++): ?>
                                    <option value="<?= $i ?>" <?= ($i == $year) ? 'selected' : '' ?>><?= $i ?></option>
                                <?php endfor; ?>
                            </select>
                            <label for="rok_add">Rok závodu:</label>
                        </div>

                        <div class="form-floating mb-3">
                            <select name="id_uci_tour" id="uci_tour_add" class="form-select">
                                <?php foreach ($uci_moznosti as $key => $value): ?>
                                    <option value="<?= $key ?>" <?= ($key == '0') ? 'selected' : '' ?>><?= $value ?></option>
                                <?php endforeach; ?>
                            </select>
                            <label for="uci_tour_add">UCI Tour:</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="file" name="logo" id="logo_add" class="form-control" placeholder="Logo závodu">
                            <label for="logo_add">Logo závodu:</label>
                            <small class="text-muted d-block mt-1">Povolené formáty: jpg, png (max 2MB)</small>
                        </div>

                        <div class="modal-footer px-0 pb-0 mt-3">
                            <button type="submit" class="btn btn-primary">Uložit nový závod</button>
                        </div>
                        <?php echo form_close(); ?>
                    </div>

                    <div class="tab-pane fade" id="edit-pane" role="tabpanel" aria-labelledby="edit-tab">
                        <?php echo form_open_multipart('zavody/editovat'); ?>

                        <div class="form-floating mb-3">
                            <input type="text" id="zavod_search_input" class="form-control" placeholder="Začněte psát název závodu..." list="zavody_datalist" autocomplete="off">
                            <label for="zavod_search_input"><i class="fa-solid fa-magnifying-glass me-1"></i> Vyhledat závod k úpravě...</label>

                            <datalist id="zavody_datalist">
                                <?php
                                $zavody_pro_vyhledani = isset($vsechny_zavody) ? $vsechny_zavody : $zavody;
                                foreach ($zavody_pro_vyhledani as $row):
                                ?>
                                    <option data-id="<?= $row->id ?>" value="<?= esc($row->real_name) ?>"></option>
                                <?php endforeach; ?>
                            </datalist>
                        </div>

                        <input type="hidden" name="zavod_id" id="zavod_id_hidden" value="">
                        <input type="hidden" name="id_rocniku" id="id_rocniku_edit" value="">

                        <div id="edit_fields_wrapper" class="d-none">
                            <hr>
                            <div class="form-floating mb-3">
                                <input type="text" name="nazev" id="nazev_edit" class="form-control" placeholder="Název závodu">
                                <label for="nazev_edit">Název závodu:</label>
                            </div>

                            <div class="form-floating mb-3">
                                <select name="uci_tour" id="uci_tour_edit" class="form-select">
                                    <?php foreach ($uci_moznosti as $key => $value): ?>
                                        <option value="<?= $key ?>"><?= $value ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <label for="uci_tour_edit">UCI Tour:</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="file" name="logo" id="logo_edit" class="form-control" placeholder="Logo závodu">
                                <label for="logo_edit">Logo závodu:</label>
                                <small class="text-muted d-block mt-1">Povolené formáty: jpg, png (max 2MB)</small>
                            </div>

                            <div class="modal-footer px-0 pb-0">
                                <button type="submit" id="submit_btn" class="btn btn-primary">Uložit změny</button>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                    </div>

                    <div class="tab-pane fade" id="smazat-pane" role="tabpanel" aria-labelledby="smazat-tab">
                        <div class="form-floating mb-3">
                            <input type="text" id="zavod_delete_input" class="form-control" placeholder="Začněte psát..." list="zavody_datalist_delete" autocomplete="off">
                            <label for="zavod_delete_input"><i class="fa-solid fa-eye-slash me-1"></i> Vyhledat závod ke skrytí...</label>

                            <datalist id="zavody_datalist_delete">
                                <?php foreach ($zavody_pro_vyhledani as $row): ?>
                                    <option data-id="<?= $row->id ?>" value="<?= esc($row->real_name) ?>"></option>
                                <?php endforeach; ?>
                            </datalist>
                        </div>

                        <div class="alert alert-warning d-none" id="delete_alert">
                            <i class="fa-solid fa-circle-info"></i> Závod bude skryt z veřejného přehledu. Historická data zůstanou zachována v administraci pro případné obnovení.
                        </div>

                        <div class="modal-footer px-0 pb-0">
                            <button type="button" id="delete_trigger_btn" class="btn btn-warning text-dark" disabled>Pokračovat k odstranění</button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<div id="delete_modal_container"></div>

<script src="<?= base_url('node_modules/tom-select/dist/js/tom-select.base.js') ?>"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {

        // --- NAŠEPTÁVAČ / VYHLEDÁVÁNÍ PRO EDITACI ---
        const searchInput = document.getElementById('zavod_search_input');
        const datalist = document.getElementById('zavody_datalist');
        const hiddenIdInput = document.getElementById('zavod_id_hidden');
        const editWrapper = document.getElementById('edit_fields_wrapper');
        const editNazevField = document.getElementById('nazev_edit');

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const inputValue = this.value;
                const options = datalist.querySelectorAll('option');
                let foundId = null;
                let foundUci = '0';

                options.forEach(option => {
                    if (option.value === inputValue) {
                        foundId = option.getAttribute('data-id');
                        foundUci = option.getAttribute('data-uci') || '0';
                    }
                });

                if (foundId) {
                    hiddenIdInput.value = foundId;
                    editNazevField.value = inputValue;
                    document.getElementById('uci_tour_edit').value = foundUci;

                    document.getElementById('nazev_edit').removeAttribute('disabled');
                    document.getElementById('uci_tour_edit').removeAttribute('disabled');
                    document.getElementById('logo_edit').removeAttribute('disabled');

                    editWrapper.classList.remove('d-none');
                } else {
                    hiddenIdInput.value = "";
                    document.getElementById('nazev_edit').setAttribute('disabled', 'disabled');
                    document.getElementById('uci_tour_edit').setAttribute('disabled', 'disabled');
                    document.getElementById('logo_edit').setAttribute('disabled', 'disabled');

                    editWrapper.classList.add('d-none');
                }
            });
        }

        // --- NAŠEPTÁVAČ PRO MAZÁNÍ (SOFT DELETE) ---
        const deleteInput = document.getElementById('zavod_delete_input');
        const deleteDatalist = document.getElementById('zavody_datalist_delete');
        const deleteTriggerBtn = document.getElementById('delete_trigger_btn');
        const deleteAlert = document.getElementById('delete_alert');
        let targetDeleteId = null;
        let targetDeleteName = "";

        if (deleteInput) {
            deleteInput.addEventListener('input', function() {
                const inputValue = this.value;
                const options = deleteDatalist.querySelectorAll('option');
                targetDeleteId = null;

                options.forEach(option => {
                    if (option.value === inputValue) {
                        targetDeleteId = option.getAttribute('data-id');
                        targetDeleteName = option.value;
                    }
                });

                if (targetDeleteId) {
                    deleteTriggerBtn.removeAttribute('disabled');
                    deleteAlert.classList.remove('d-none');
                } else {
                    deleteTriggerBtn.setAttribute('disabled', 'disabled');
                    deleteAlert.classList.add('d-none');
                }
            });

            // Vyvolání potvrzovacího modalu pro měkké smazání
            deleteTriggerBtn.addEventListener('click', function() {
                if (!targetDeleteId) return;

                const modalId = 'confirm_delete_modal';
                // Použij čisté base_url bez ručního vpisování index.php
                const actionRoute = '<?= base_url("index.php/zavody/smazat") ?>';

                document.getElementById('delete_modal_container').innerHTML = `
<div class="modal fade" id="${modalId}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Potvrdit odstranění</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Zavřít"></button>
            </div>
            <div class="modal-body">
                Opravdu chcete závod <strong>${targetDeleteName}</strong> odebrat z přehledu? Data zůstanou archivována.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zrušit</button>
                <form action="${actionRoute}" method="post">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" value="${targetDeleteId}">
                    <button type="submit" class="btn btn-warning text-dark">Odstranit z přehledu</button>
                </form>
            </div>
        </div>
    </div>
</div>
`;

                bootstrap.Modal.getInstance(document.getElementById('pridat')).hide();
                new bootstrap.Modal(document.getElementById(modalId)).show();
            });
        }

        // --- FILTER "MOJE" ---
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