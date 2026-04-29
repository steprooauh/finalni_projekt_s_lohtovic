<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Závody</title>
    <style>
        .disabled-div {
            pointer-events: none;
            opacity: 0.5;
            user-select: none;
        }

        .odkaz {
            text-decoration: none;
        }
    </style>
    <?= $this->include('layout/css') ?>
</head>

<body>
    <div class="container">
        <?= $this->renderSection('content') ?>
    </div>
    <?= $this->include('layout/js') ?>
</body>

</html>