<?php
echo ($this->extend('Layout/template'));
echo ($this->section('content'));
?>
<div class="p-3">
    <h1 class="text-center">Vítejte na naší stránce</h1>
    <h6 class="text-center">Autoři: Štěpán Prokop a Jiří Lhota</h6>

</div>


<div class="row">
    <?php
    /** @var array $raceYear */
    $posledniRok = null;
    $soucet = 0;
    $prvniKolo = true;

    foreach ($raceYear as $row) {
        if ($posledniRok !== null && $row->year != $posledniRok) { //vypíše kartu když narazí na nový rok
    ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h2 class="text-center"><?= anchor('roky/' . $posledniRok, $posledniRok) ?></h2>
                    </div>
                    <div class="card-body">
                        <p class="text-center">Počet závodů: <?= $soucet ?></p>
                    </div>
                </div>
            </div>
        <?php //po tom co vypíše kartu vynuluje součet
            $soucet = 0;
        }
        //přičítá pokaždé
        $soucet++;
        $posledniRok = $row->year;
    }


    //poslední rok se vypíše mimo foreach aby se tam vůbec zobrazil
    if ($posledniRok !== null) : ?>
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h2 class="text-center"><a class="odkaz" href="<?= base_url($posledniRok) ?>"><?= $posledniRok ?></a></h2>
                </div>
                <div class="card-body">
                    <p class="text-center">Počet závodů: <?= $soucet ?></p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- dva další divy které zobrazují roky 2025 a 2026 ale nejsou v databázi takže jsou zašedlé -->
    <div class="disabled-div col-lg-4 col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h2 class="text-center"><a class="odkaz" href="#">2025</a></h2>
            </div>
            <div class="card-body">
                <p class="text-center">Nedostupné</p>
            </div>
        </div>
    </div>

    <div class="disabled-div col-lg-4 col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h2 class="text-center"><a class="odkaz" href="#">2026</a></h2>
            </div>
            <div class="card-body">
                <p class="text-center">Nedostupné</p>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>