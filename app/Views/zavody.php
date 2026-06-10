    <?= $this->extend('Layout/template') ?>
    <?= $this->section('content') ?>

    <div class="container py-4">
        <h1 class="text-center mb-4">Přehled závodů</h1>

        <div class="d-flex justify-content-center align-items-center gap-3 mb-4">
            <a class="btn btn-secondary" href="<?= base_url() ?>">
                <i class="fa-solid fa-caret-left"></i> Zpět
            </a>

            <div class="btn-group" role="group" aria-label="Filtr závodů">
                <input type="checkbox" class="btn-check" id="btnCheckMoje" autocomplete="off" <?= (isset($jenMoje) && $jenMoje) ? 'checked' : '' ?>>
                <label class="btn btn-outline-primary" for="btnCheckMoje">
                    <i class="fa-solid fa-user"></i> Pouze mnou vytvořené
                </label>
            </div>

            <a class="btn btn-secondary" href="#" data-bs-toggle="modal" data-bs-target="#pridat">
                Přidat / Upravit <i class="fa-solid fa-caret-right"></i>
            </a>
        </div>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

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
                <div class="col-lg-4 col-md-6 mb-3">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-header d-flex flex-column justify-content-center align-items-center border-bottom-0 mt-2">
                            <h5 class="text-center fw-bold">
                                <?= anchor('index.php/race/show/' . $row->id, $row->real_name, ['class' => 'text-decoration-none text-dark hover-primary']) ?>
                            </h5>
                        </div>
                        <div class="card-body d-flex flex-column justify-content-between text-center">
                            <div class="mb-1">
                                <span class="fi fi-<?= $row->country ?> fs-4 shadow-sm rounded-1"></span>
                            </div>

                            <div class="row g-0 bg-light rounded p-2 text-muted small">
                                <div class="col">
                                    <div class="div row mb-2"> <!-- uci tour -->
                                        <div class="col-12">
                                            <span class="d-block text-uppercase text-xs fw-semibold" style="color: black; ">UCI TOUR</span>
                                            <span class="text-success-emphasis fw-medium" style="color: black; ">
                                                <?php $race_year = new \App\Models\RaceYear();
                                                $uci_tour_id = $race_year->where('id', $row->id)->select('uci_tour')->get()->getRow()->uci_tour;
                                                echo $uci_moznosti[$uci_tour_id] ?? 'Nezařazeno';
                                                ?></span>
                                        </div>
                                    </div>
                                    <div class="div row mb-2">
                                        <div class="col">
                                            <span class="d-block text-uppercase text-xs fw-semibold" style="color: black;">Délka závodu</span>
                                            <span class="text-success-emphasis fw-medium" style="color: black;">
                                                <?php
                                                if (isset($row->total_distance) && $row->total_distance > 0) {
                                                    echo $row->total_distance . ' km';
                                                } else {
                                                    $stageModel = new \App\Models\Stage();
                                                    $distanceData = $stageModel->join('race_year', 'stage.id_race_year = race_year.id')
                                                        ->join('race', 'race.id = race_year.id_race')
                                                        ->where('race_year.id', $row->id)
                                                        ->where('race_year.year', $year)
                                                        ->selectSum('distance')
                                                        ->get()
                                                        ->getRow();

                                                    $distance = $distanceData ? $distanceData->distance : 0;

                                                    if ($distance > 0) {
                                                        echo $distance . ' km';
                                                    } else {
                                                        echo '-';
                                                    }
                                                }
                                                ?>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="div row mb-2">
                                        <div class="col">
                                            <span class="d-block text-uppercase text-xs fw-semibold" style="color: black;">Převýšení</span>
                                            <span class="text-success-emphasis fw-medium" style="color: black;">
                                                <?php
                                                if (isset($row->total_elevation) && $row->total_elevation > 0) {
                                                    echo $row->total_elevation . ' m';
                                                } else {
                                                    $stageModel = new \App\Models\Stage();
                                                    $elevationData = $stageModel->join('race_year', 'stage.id_race_year = race_year.id')
                                                        ->join('race', 'race.id = race_year.id_race')
                                                        ->where('race_year.id', $row->id)
                                                        ->where('race_year.year', $year)
                                                        ->selectSum('vertical_meters', 'elevation')
                                                        ->get()
                                                        ->getRow();

                                                    $elevation = $elevationData ? $elevationData->elevation : 0;

                                                    if ($elevation > 0) {
                                                        echo $elevation . ' m';
                                                    } else {
                                                        echo '-';
                                                    }
                                                }
                                                ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="div row">
                                        <?php if ($row->start_date == $row->end_date) : ?>
                                            <div class="col-12">
                                                <span class="d-block text-uppercase text-xs fw-semibold" style="color: black; ">Datum</span>
                                                <span class="text-success-emphasis fw-medium"><?= date('d. m. Y', strtotime($row->start_date)) ?></span>
                                            </div> <?php else : ?>
                                            <div class="col-6 border-end">
                                                <span class="d-block text-uppercase text-xs fw-semibold" style="color: black; ">Od</span>
                                                <span class="text-success-emphasis fw-medium"><?= !empty($row->start_date) ? date('d. m. Y', strtotime($row->start_date)) : '' ?></span>
                                            </div>
                                            <div class="col-6">
                                                <span class="d-block text-uppercase text-xs fw-semibold" style="color: black; ">Do</span>
                                                <span class="text-success-emphasis fw-medium"><?= !empty($row->end_date) ? date('d. m. Y', strtotime($row->end_date)) : '' ?></span>
                                            </div> <?php endif; ?>
                                    </div>
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
                            <form action="<?= base_url('index.php/zavody/pridat') ?>" method="post" enctype="multipart/form-data">
                                <?= csrf_field() ?>

                                <?php echo form_input_bs('nazev', ['id' => 'nazev_add', 'value' => ''], 'Název závodu:', 'text', true); ?>
                                <input type="hidden" name="id_rocniku" id="id_rocniku_add" value="">

                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-floating mb-3">
                                            <input type="date" name="start_date" id="start_date_add" class="form-control" value="<?= $year . date('-m-d') ?>" required>
                                            <label for="start_date_add">Datum začátku:</label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-floating mb-3">
                                            <input type="date" name="end_date" id="end_date_add" class="form-control" value="<?= $year . date('-m-d') ?>" required>
                                            <label for="end_date_add">Datum konce:</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" name="country" id="country_add" class="form-control" placeholder="Např. CZ" maxlength="3" required>
                                            <label for="country_add">Stát (zkratka např. CZ):</label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-floating mb-3">
                                            <select name="sex" id="sex_add" class="form-select" required>
                                                <option value="M" selected>Muži (M)</option>
                                                <option value="W">Ženy (W)</option>
                                            </select>
                                            <label for="sex_add">Kategorie (Pohlaví):</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-floating mb-3">
                                    <select name="rok" id="rok_add" class="form-select">
                                        <?php for ($i = 2015; $i <= date('Y') + 2; $i++): ?>
                                            <option value="<?= $i ?>" <?= ($i == $year) ? 'selected' : '' ?>><?= $i ?></option>
                                        <?php endfor; ?>
                                    </select>
                                    <label for="rok_add">Rok závodu:</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <select name="uci_tour" id="uci_tour_edit" class="form-select">
                                        <?php foreach ($uci_moznosti as $key => $value): ?>
                                            <option value="<?= $key ?>"><?= $value ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <label for="uci_tour_edit">UCI Tour:</label>
                                </div>

                                <div class="mb-3">
                                    <label for="bio_add" class="form-label text-muted small ps-2">Bio závodu (popis):</label>
                                    <textarea name="description" id="bio_add" class="form-control" placeholder="Popis závodu..."></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="logo_add" class="form-label fw-semibold text-xs text-uppercase">Logo závodu:</label>
                                    <input type="file" name="logo" id="logo_add" class="form-control" required>
                                    <small class="text-muted d-block mt-1">Povolené formáty: jpg, png (max 2MB)</small>
                                </div>

                                <div class="modal-footer px-0 pb-0 mt-3">
                                    <button type="submit" class="btn btn-primary">Uložit nový závod</button>
                                </div>
                            </form>
                        </div>

                        <div class="tab-pane fade" id="edit-pane" role="tabpanel" aria-labelledby="edit-tab">
                            <form action="<?= base_url('index.php/zavody/change') ?>" method="post" enctype="multipart/form-data">
                                <?= csrf_field() ?>

                                <div class="form-floating mb-3">
                                    <input type="text" id="zavod_search_input" class="form-control" placeholder="Začněte psát název závodu..." list="zavody_datalist" autocomplete="off">
                                    <label for="zavod_search_input"><i class="fa-solid fa-magnifying-glass me-1"></i> Vyhledat závod k úpravě...</label>

                                    <datalist id="zavody_datalist">
                                        <?php
                                        $zavody_pro_vyhledani = isset($vsechny_zavody) ? $vsechny_zavody : $zavody;
                                        foreach ($zavody_pro_vyhledani as $row):
                                        ?>
                                            <option data-id="<?= $row->id ?>"
                                                data-uci="<?= $row->id_uci_tour ?? $row->uci_tour ?? '0' ?>"
                                                data-bio="<?= $row->bio ?? '' ?>"
                                                data-distance="<?= $row->total_distance ?? '0' ?>"
                                                data-elevation="<?= $row->total_elevation ?? '0' ?>"
                                                data-start="<?= !empty($row->start_date) && $row->start_date !== '0000-00-00' ? $row->start_date : '' ?>"
                                                data-end="<?= !empty($row->end_date) && $row->end_date !== '0000-00-00' ? $row->end_date : '' ?>"
                                                value="<?= $row->real_name ?>"></option>
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

                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-floating mb-3">
                                                <input type="date" name="start_date" id="start_date_edit" class="form-control" value="<?= $year . date('-m-d') ?>" required>
                                                <label for="start_date_edit">Datum začátku:</label>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-floating mb-3">
                                                <input type="date" name="end_date" id="end_date_edit" class="form-control" value="<?= $year . date('-m-d') ?>" required>
                                                <label for="end_date_edit">Datum konce:</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-floating mb-3">
                                                <input type="number" step="0.1" name="total_distance" id="distance_edit" class="form-control" placeholder="Délka (km)">
                                                <label for="distance_edit">Délka (km):</label>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-floating mb-3">
                                                <input type="number" name="total_elevation" id="elevation_edit" class="form-control" placeholder="Převýšení (m)">
                                                <label for="elevation_edit">Převýšení (m):</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-floating mb-3">
                                        <select name="uci_tour" id="uci_tour_edit" class="form-select">
                                            <?php foreach ($uci_moznosti as $key => $value): ?>
                                                <option value="<?= $key ?>"><?= $value ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <label for="uci_tour_edit">UCI Tour:</label>
                                    </div>

                                    <div class="mb-3">
                                        <label for="bio_edit" class="form-label text-muted small ps-2">Bio závodu:</label>
                                        <textarea name="bio" id="bio_edit" class="form-control" placeholder="Popis závodu..."></textarea>
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
                            </form>
                        </div>

                        <div class="tab-pane fade" id="smazat-pane" role="tabpanel" aria-labelledby="smazat-tab">
                            <form action="<?= base_url('index.php/zavodyc/delete') ?>" method="post" id="delete_race_form">
                                <?= csrf_field() ?>
                                <input type="hidden" name="id" id="zavod_delete_id" value="">

                                <div class="form-floating mb-3">
                                    <input type="text" id="zavod_delete_input" class="form-control" placeholder="Začněte psát..." list="zavody_datalist_delete" autocomplete="off">
                                    <label for="zavod_delete_input"><i class="fa-solid fa-eye-slash me-1"></i> Vyhledat závod ke skrytí...</label>

                                    <datalist id="zavody_datalist_delete">
                                        <?php foreach ($zavody_pro_vyhledani as $row): ?>
                                            <option data-id="<?= $row->id ?>" value="<?= $row->real_name ?>"></option>
                                        <?php endforeach; ?>
                                    </datalist>
                                </div>

                                <div class="alert alert-warning d-none" id="delete_alert">
                                    <i class="fa-solid fa-circle-info"></i> Závod bude skryt z veřejného přehledu. Historická data zůstanou zachována v administraci pro případné obnovení.
                                </div>

                                <div class="modal-footer px-0 pb-0">
                                    <button type="submit" id="delete_trigger_btn" class="btn btn-warning text-dark" disabled>Pokračovat k odstranění</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="delete_modal_container"></div>

    <script src="<?= base_url('node_modules/tinymce/tinymce.min.js') ?>" referrerpolicy="origin"></script>
    <script src="<?= base_url('node_modules/tom-select/dist/js/tom-select.base.js') ?>"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            // --- AUTOMATICKÝ TERMÍN PODLE ROKU (PRO PŘIDÁNÍ) ---
            const rokSelect = document.getElementById('rok_add');
            const startDateInput = document.getElementById('start_date_add');
            const endDateInput = document.getElementById('end_date_add');

            if (rokSelect && startDateInput && endDateInput) {
                rokSelect.addEventListener('change', function() {
                    const vybranyRok = this.value;
                    const dnes = new Date();
                    const mesic = String(dnes.getMonth() + 1).padStart(2, '0');
                    const den = String(dnes.getDate()).padStart(2, '0');
                    const noveDatum = `${vybranyRok}-${mesic}-${den}`;

                    startDateInput.value = noveDatum;
                    endDateInput.value = noveDatum;
                });
            }

            // --- NAŠEPTÁVAČ PRO EDITACI ---
            const searchInput = document.getElementById('zavod_search_input');
            const datalist = document.getElementById('zavody_datalist');
            const hiddenIdInput = document.getElementById('zavod_id_hidden');
            const editWrapper = document.getElementById('edit_fields_wrapper');
            const editNazevField = document.getElementById('nazev_edit');

            // Oprava focusu pro TinyMCE uvnitř Bootstrap modalu
            document.addEventListener('focusin', function(e) {
                if (e.target.closest(".tox-tinymce, .tox-tinymce-aux, .moxman-window, .tox-dialog") !== null) {
                    e.stopImmediatePropagation();
                }
            });

            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const inputValue = this.value;
                    const options = datalist.querySelectorAll('option');
                    let foundId = null;
                    let foundUci = '0';
                    let foundBio = '';
                    let foundDistance = '0';
                    let foundElevation = '0';
                    let foundStart = '';
                    let foundEnd = '';

                    options.forEach(option => {
                        if (option.value === inputValue) {
                            foundId = option.getAttribute('data-id');
                            foundUci = option.getAttribute('data-uci') || '0';
                            foundBio = option.getAttribute('data-bio') || '';
                            foundDistance = option.getAttribute('data-distance') || '0';
                            foundElevation = option.getAttribute('data-elevation') || '0';
                            foundStart = option.getAttribute('data-start') || '';
                            foundEnd = option.getAttribute('data-end') || '';
                        }
                    });

                    if (foundId) {
                        hiddenIdInput.value = foundId;
                        editNazevField.value = inputValue;
                        document.getElementById('uci_tour_edit').value = foundUci;
                        document.getElementById('distance_edit').value = foundDistance;
                        document.getElementById('elevation_edit').value = foundElevation;

                        // Propisování datumu do editačních kalendářů
                        // Pokud v DB datum chybí, předvyplní se dnešní den s aktuálním rokem stránky
                        document.getElementById('start_date_edit').value = foundStart ? foundStart : "<?= $year . date('-m-d') ?>";
                        document.getElementById('end_date_edit').value = foundEnd ? foundEnd : "<?= $year . date('-m-d') ?>";

                        // Propisování obsahu do TinyMCE pro editaci
                        if (tinymce.get('bio_edit')) {
                            tinymce.get('bio_edit').setContent(foundBio);
                            tinymce.get('bio_edit').mode.set('design');
                        }

                        document.getElementById('nazev_edit').removeAttribute('disabled');
                        document.getElementById('uci_tour_edit').removeAttribute('disabled');
                        document.getElementById('logo_edit').removeAttribute('disabled');
                        document.getElementById('start_date_edit').removeAttribute('disabled');
                        document.getElementById('end_date_edit').removeAttribute('disabled');

                        editWrapper.classList.remove('d-none');
                    } else {
                        hiddenIdInput.value = "";
                        document.getElementById('nazev_edit').setAttribute('disabled', 'disabled');
                        document.getElementById('uci_tour_edit').setAttribute('disabled', 'disabled');
                        document.getElementById('logo_edit').setAttribute('disabled', 'disabled');
                        document.getElementById('start_date_edit').setAttribute('disabled', 'disabled');
                        document.getElementById('end_date_edit').setAttribute('disabled', 'disabled');

                        if (tinymce.get('bio_edit')) {
                            tinymce.get('bio_edit').setContent('');
                            tinymce.get('bio_edit').mode.set('readonly');
                        }

                        editWrapper.classList.add('d-none');
                    }
                });
            }

            // --- NAŠEPTÁVAČ PRO MAZÁNÍ ---
            const deleteInput = document.getElementById('zavod_delete_input');
            const deleteDatalist = document.getElementById('zavody_datalist_delete');
            const deleteTriggerBtn = document.getElementById('delete_trigger_btn');
            const deleteAlert = document.getElementById('delete_alert');
            const hiddenDeleteInput = document.getElementById('zavod_delete_id');

            let targetDeleteId = null;
            let targetDeleteName = "";

            if (deleteInput) {
                deleteInput.addEventListener('input', function() {
                    const inputValue = this.value;
                    const options = deleteDatalist.querySelectorAll('option');

                    targetDeleteId = null;
                    targetDeleteName = "";

                    options.forEach(option => {
                        if (option.value === inputValue) {
                            targetDeleteId = option.getAttribute('data-id');
                            targetDeleteName = option.value;
                        }
                    });

                    if (targetDeleteId) {
                        if (hiddenDeleteInput) hiddenDeleteInput.value = targetDeleteId;
                        deleteTriggerBtn.removeAttribute('disabled');
                        deleteAlert.classList.remove('d-none');
                    } else {
                        if (hiddenDeleteInput) hiddenDeleteInput.value = '';
                        deleteTriggerBtn.setAttribute('disabled', 'disabled');
                        deleteAlert.classList.add('d-none');
                    }
                });

                deleteTriggerBtn.addEventListener('click', function() {
                    if (!targetDeleteId) return;

                    const modalId = 'confirm_delete_modal';
                    const actionRoute = '<?= base_url("index.php/zavodyc/delete") ?>';

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
        </div>`;

                    const pridatModalEl = document.getElementById('pridat');
                    if (pridatModalEl) {
                        const modalInstance = bootstrap.Modal.getInstance(pridatModalEl);
                        if (modalInstance) modalInstance.hide();
                    }

                    new bootstrap.Modal(document.getElementById(modalId)).show();
                });
            }

            // --- INICIALIZACE TINYMCE PO OTEVŘENÍ MODALU ---
            const pridatModal = document.getElementById('pridat');

            if (pridatModal) {
                pridatModal.addEventListener('shown.bs.modal', function() {
                    const tinyConfig = {
                        height: 250,
                        menubar: false,
                        plugins: 'lists link image charmap preview anchor searchreplace visualblocks code fullscreen table wordcount',
                        toolbar: 'undo redo | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat',
                        language: 'cs',
                        license_key: 'gpl'
                    };

                    if (!tinymce.get('bio_add')) {
                        tinymce.init({
                            ...tinyConfig,
                            selector: '#bio_add'
                        });
                    }
                    if (!tinymce.get('bio_edit')) {
                        tinymce.init({
                            ...tinyConfig,
                            selector: '#bio_edit'
                        });
                    }
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