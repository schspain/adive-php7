<!-- Page Heading -->
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">
            <?=$title?> <small><?=$description?></small>
        </h1>
        <ol class="breadcrumb">
            <li class="active">
                <i class="fa fa-dashboard"></i> <?=$title?>
            </li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="alert alert-info alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <i class="fa fa-info-circle"></i>  <strong>Like Adive Framework?</strong> Feedback us in <a href="https://github.com/ferdinandmartin/adive-php7" class="alert-link">GitHub</a> and get it connected!
        </div>
    </div>
</div>
<!-- /.row -->

    <!-- Jumbotron -->
      <div class="jumbotron">
        <h1>Welcome to Adive Dashboard!</h1>
        <p class="lead">Manage your website app by the easy way.</p>
      </div>

    

      <!-- FOOTER -->
      <footer>
        <p class="pull-right"><a href="#">Back to top</a></p>
        <p>&copy; 2014-<?=date('Y')?> Adive Framework Version.<?=Adive\Adive::VERSION?> &middot;</p>
      </footer>
