<!-- Page Heading -->
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">
            <?=$title?> <small><?=$description?></small>
        </h1>
        <ol class="breadcrumb">
            <li class="active">
                <i class="fa fa-table"></i> <?=$title?>
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
            <i class="fa fa-info-circle"></i>  <strong>Need some help?</strong> <a href="<?=path('addTables')?>" class="alert-link">Add a new table</a> in your database like a form entity to use in your APP!
        </div>
    </div>
</div>
<!-- /.row -->

<a href="<?=path('addTables')?>" class="pull-right btn btn-primary"><i class="fa fa-plus-circle"></i> Create new table</a>
<table class="table table-striped">
    <thead>
        <th>Name</th>
        <th>Description</th>
        <th>Date</th>
        <th class="text-right">Options</th>
    </thead>
    <tbody>
<?php
foreach ($result as $key => $value) {
?>
        <tr>
            <td><a href="<?=basePath()?>/admin/tables/fields/<?=$value['id']?>"><?=$value['name']?></a> <span class="badge" title="Fields in this table"><?=$value['FieldsIn']?></span> <span class="text-muted">fields</span></td>
            <td><?=$value['description']?></td>
            <td><?=$value['creationDate']?></td>
            <td class="text-right">
                <span class="btn-group">
                    <a href="<?=basePath()?>/admin/tables/fields/<?=$value['id']?>" class="btn btn-default btn-xs"><i class="fa fa-search"></i> View fields</a> 
                    <a href="<?=basePath()?>/admin/tables/edit/<?=$value['id']?>" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i> Edit</a> 
                    <a href="javascript:void(0);" class="btn btn-warning btn-xs" onClick="if(confirm('Delete table?')) location.href = '<?=basePath()?>/admin/tables/delete/<?=$value['id']?>';"><i class="fa fa-trash"></i> Delete</a>
                </span>
            </td>
        </tr>
<?php
}
?>
    </tbody>
</table>