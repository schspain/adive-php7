<!-- Page Heading -->
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">
            <?=$title?> <small><?=$description?></small>
        </h1>
        <ol class="breadcrumb">
            <li class="active">
                <i class="fa fa-heartbeat"></i> <?=$title?>
            </li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="alert alert-info alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <i class="fa fa-info-circle"></i>  <strong>Like Adive Framework?</strong> Feedback us in <a href="https://github.com/ferdinandmartin" class="alert-link">GitHub</a> and get it connected!
        </div>
    </div>
</div>

<div class="jumbotron">
      <div class="container">
        <h1>Invoke your API now!</h1>
        <p>Start the invocation process will generate all the necessary forms, lists and configuration necessary to start using your api.<br>
            This action will overwrite all your files in /Controller and /Views folders.</p>
        <p><a class="btn btn-primary btn-lg" href="<?=basePath()?>/admin/invoke/full/start" role="button">Start Invocation &raquo;</a></p>
      </div>
    </div>
<!-- /.row -->

<!-- Example row of columns -->
<div class="row">
  <div class="col-md-6">
    <h2>Forms & Views only</h2>
    <p>This action only updates the HTML forms in <code>Views</code></p>
    <p><a class="btn btn-default" href="<?=basePath()?>/admin/invoke/views/start" role="button">Start partial invocation &raquo;</a></p>
  </div>
  <div class="col-md-6">
    <h2>Controller only</h2>
    <p>This action only updates the php files in <code>Controller</code></p>
    <p><a class="btn btn-default" href="<?=basePath()?>/admin/invoke/controller/start" role="button">Start partial invocation &raquo;</a></p>
 </div>
</div>

<hr>

<footer>
  <p>&copy; 2014-<?=date('Y')?> Adive Framework Version.<?=Adive\Adive::VERSION?></p>
</footer>
