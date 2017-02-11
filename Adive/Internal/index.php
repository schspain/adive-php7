<!doctype html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="<?=asset('bootstrap.min.css')?>">
        <link rel="stylesheet" href="<?=asset('styles.css')?>">
        <title><?=$title?></title>
        <link rel="icon" type="image/png" href="<?=asset('adiveLogo.png')?>" />
    </head>
    <body>
        <nav class="navbar navbar-inverse navbar-fixed-top">
          <div class="container">
            <div class="navbar-header">
              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
                <a class="navbar-brand" href="<?=basePath()?>">Adive.</a>
            </div>
            <div id="navbar" class="collapse navbar-collapse">
              <ul class="nav navbar-nav">
                  <li<?php if(isActive()=='/'){ echo' class="active"'; } ?>><a href="<?=basePath()?>">Welcome</a></li>
              </ul>
            </div><!--/.nav-collapse -->
          </div>
        </nav>
        <div class="container">
            <div class="starter-template">
                 <?php require_once $_SESSION['path.now'].'.php'; ?>
            </div>
        </div>
        <!-- Bootstrap core JavaScript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <script src="<?=asset('jquery.min.js')?>"></script>
        <script src="<?=asset('bootstrap.min.js')?>"></script>
    </body>
</html>