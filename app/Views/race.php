<?= $this->extend('Layout/template') ?>
<?= $this->section('content') ?>
<br>
<?php
/**
 * @var object $zavod
 * @var object $year
 */

// Pomocná kontrola pro neplatné databázové datum
$has_valid_start = !empty($zavod->start_date) && $zavod->start_date !== '0000-00-00';
$has_valid_end   = !empty($zavod->end_date) && $zavod->end_date !== '0000-00-00';
?>

<?php if ($zavod->vytvoril_uzivatel_id == 1): ?>
  <h1 class="text-center text-danger">Závod: <?= $zavod->real_name ?></h1>
  <p class="text-center text-muted">Tento závod vytvořil uživatel!</p>
<?php else: ?>
  <h1 class="text-center">Závod: <?= $zavod->real_name ?></h1>
<?php endif; ?>

<div class="d-flex justify-content-center align-items-center gap-3 mb-4">
  <a class="btn btn-secondary" href="<?= base_url('index.php/roky/' . $year) ?>">
    <i class="fa-solid fa-caret-left"></i> Zpět
  </a>
</div>

<div class="card mb-4">
  <div class="card-header d-flex justify-content-between align-items-center">
    <span>Rok: <?= $year ?> (<?= $zavod->real_name ?>)</span>
    <div>
      <?php if (!empty($zavod->country)): ?>
        <span class="badge bg-dark me-1"><?= $zavod->country ?></span>
      <?php endif; ?>
      <?php if (!empty($zavod->sex)): ?>
        <span class="badge bg-secondary"><?= $zavod->sex === 'W' ? 'Ženy (W)' : 'Muži (M)' ?></span>
      <?php endif; ?>
    </div>
  </div>

  <div class="card-body">
    <span class="d-block text-xs mb-1">
      <strong>Datum:</strong>
      <?php if ($has_valid_start && $has_valid_end): ?>
        <?php if ($zavod->start_date == $zavod->end_date) : ?>
          <?= date('d. m. Y', strtotime($zavod->start_date)) ?>
        <?php else : ?>
          <?= date('d. m. Y', strtotime($zavod->start_date)) ?> - <?= date('d. m. Y', strtotime($zavod->end_date)) ?>
        <?php endif; ?>
      <?php else: ?>
        <span class="text-muted">Nezadáno</span>
      <?php endif; ?>
    </span>

    <span class="d-block text-xs mb-1">
      <strong>Vzdálenost:</strong> <?= ($zavod->total_distance > 0) ? $zavod->total_distance . ' km' : '-' ?>
    </span>

    <span class="d-block text-xs mb-1">
      <strong>Převýšení:</strong> <?= ($zavod->total_elevation > 0) ? $zavod->total_elevation . ' m' : '-' ?>
    </span>

    <span class="d-block text-xs mb-3">
      <strong>UCI Kategorie:</strong> <span class="badge bg-info text-dark"><?= $zavod->uci_tour_text ?></span>
    </span>

    <hr>

    <div class="mb-2">
      <strong>Bio / Popis závodu:</strong>
      <div class="p-3 rounded mt-2" style="border: 1px solid gray">
        <?php if (!empty($zavod->description)): ?>
          <?= $zavod->description
          ?>
        <?php else: ?>
          <span class="text-muted text-italic">Tento závod zatím nemá žádný popis.</span>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <?php if (!empty($zavod->logo)): ?>
    <div class="card-footer">
      <strong>Logo</strong>
      <div class="p-3 text-center">
        <img src="<?= base_url('img/logos/' . $zavod->logo) ?>" class="img-fluid" style="max-height: 150px;" alt="Logo závodu">
      </div>
    </div>
  <?php endif; ?>
</div>
<?= $this->endSection() ?>