<!-- Page Heading -->
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">
            <?=$title?> <small><?=$description?></small>
        </h1>
        <ol class="breadcrumb">
            <li class="active">
                <i class="fa fa-table"></i> <a href="<?=path('atables')?>">Tables</a> / <a href="<?=basePath()?>/admin/tables/fields/<?=$table[0]['id']?>">Fields</a> / <?=$title?>
            </li>
        </ol>
    </div>
</div>

<?php if (isset($flash['error'])): ?>
        <div class="alert alert-danger alert-dismissable"><?php echo $flash['error'] ?></div>
<?php endif;
      if (isset($flash['message'])): ?>
        <div class="alert alert-success alert-dismissable"><?php echo $flash['message'] ?></div>
<?php endif; ?>

<div class="row">
    <div class="col-lg-12">
        <div class="alert alert-info alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <i class="fa fa-info-circle"></i>  <strong>TIP:</strong> If you change the name, comment or field type, the database field will be updated without loss of data.
        </div>
    </div>
</div>
<!-- /.row -->
<?php
foreach ($trigger as $key => $value) {
?>
<form class="form-horizontal" method="post">
   <div class="form-group">
    <label for="typeTrigger" class="col-sm-2 control-label">Trigger execution:</label>
    <div class="col-sm-10">
        <p class="form-control-static"><?=$value['time']?> <i class="fa fa-arrow-right"></i> <?=$value['type']?></p>
    </div>
  </div>
  <div class="form-group">
    <label for="winCode" class="col-sm-2 control-label">Trigger Code</label>
    <div class="col-sm-10">
        <textarea class="form-control" rows="16" id="codeTrigger" name="codeTrigger"><?=$value['code']?></textarea>
        <span class="help-block">MySQL Language <a href="https://mariadb.com/kb/en/mariadb/sql-structure-and-commands/" target="_blank"><i class="fa fa-info-circle"></i> Language documentation</a>.</span>
    </div>
  </div>
    
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-lg btn-primary">Update trigger</button>
    </div>
  </div>
</form>
<?php } ?>