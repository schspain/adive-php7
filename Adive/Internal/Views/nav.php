<!-- Page Heading -->
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">
            <?=$title?> <small><?=$description?></small>
        </h1>
        <ol class="breadcrumb">
            <li class="active">
                <i class="fa fa-navicon"></i> <?=$title?>
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
            <i class="fa fa-info-circle"></i>  <strong>Need some help?</strong> <a href="<?=path('addNav')?>" class="alert-link">Add a new link</a> to your APP navigation menu.
        </div>
    </div>
</div>
<!-- /.row -->

<a href="<?=path('addNav')?>" class="pull-right btn btn-primary"><i class="fa fa-plus-circle"></i> Create new menu link</a>
<table class="table table-striped">
    <thead>
        <th>Name</th>
        <th>Description</th>
        <th>Date</th>
        <th class="text-right">Options</th>
    </thead>
    <tbody>
<?php
$lastParent = null;
$status = array();
$i=1;
$parent=1;
$child=1;
$sub = 0;
foreach ($result as $key => $value) {
    if($i==1 and $value['parent']==NULL){
        $status[$i]=1;
        $parent++;
    }
    if($i>1 and $sub==0 and $value['parent']==NULL){
        $status[$i]=0;
        $parent++;
    }
    if($sub>0 and $value['parent']!=NULL){
        $status[$i]=0;
        $child++;
        $sub++;
    }
    if($sub==0 and $value['parent']!=NULL){
        $status[$i]=1;
        $child++;
        $sub++;
    }
    if($sub>0 and $value['parent']==NULL){
        $status[$i]=0;
        $status[$i-1]=2;
        $parent++;
        $sub=0;
        $lastParent=$i;
    }
    $i++;
}
$lastParent = $i - $lastParent;
$status[$i-1]=2;
$status[$i-($lastParent)]=2;

$i=1;
foreach ($result as $key => $value) {
    if($value['parent']==0){
        $iconFolder='<i class="fa '.$value['icon'].'"></i> ';
    } else {
        $iconFolder='<i class="fa '.$value['icon'].'" style="margin-left:20px;"></i> ';
    }
?>
        <tr>
            <td><a href="<?=basePath()?>/admin/nav/edit/<?=$value['id']?>"><?=$iconFolder.$value['name']?></a></td>
            <td><?=$value['description']?></td>
            <td><?=$value['creationDate']?></td>
            <td class="text-right">
                <span class="btn-group">
                    <?php if($status[$i]==2 or $status[$i]==0){ ?><a href="<?=basePath()?>/admin/nav/up/<?=$value['id']?>" class="btn btn-default btn-xs"><i class="fa fa-arrow-up"></i></a> 
                    <?php } else { ?><a href="javascript:void(0);" class="btn btn-default disabled btn-xs"><i class="fa fa-arrow-up"></i></a> 
                    <?php } ?>
                    <?php if($status[$i]==1 or $status[$i]==0){ ?><a href="<?=basePath()?>/admin/nav/down/<?=$value['id']?>" class="btn btn-default btn-xs"><i class="fa fa-arrow-down"></i></a> 
                    <?php } else { ?><a href="javascript:void(0);" class="btn btn-default disabled btn-xs"><i class="fa fa-arrow-down"></i></a>
                    <?php } ?>
                </span>
                <span class="btn-group"> 
                    <a href="<?=basePath()?>/admin/nav/edit/<?=$value['id']?>" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i> Edit</a> 
                    <a href="javascript:void(0);" class="btn btn-warning btn-xs" onClick="if(confirm('Delete navigation link?')) location.href = '<?=basePath()?>/admin/nav/delete/<?=$value['id']?>';"><i class="fa fa-trash"></i> Delete</a>
                </span>
            </td>
        </tr>
<?php
$i++;
}
?>
    </tbody>
</table>