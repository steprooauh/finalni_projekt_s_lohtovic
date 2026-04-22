<nav class="navbar navbar-expand-sm bg-dark" data-bs-theme="dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="index.html"><img src=""></a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor02" aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarColor02">
        <ul class="navbar-nav me-auto">
          <li class="nav-item"><?= anchor('kraj/20', 'Kraj', ['class' => 'nav-link']) ?></li>
        <?php  
        foreach ($navbar as $row) {
          echo ('<li class="nav-item">'
          .anchor('okres/' . $row->kod . '/str/' . $stranky, $row->nazev, ['class' => 'nav-link'])
          .'</li>');}    
          ?>
        </ul>
      </div>
    </div>
</nav>