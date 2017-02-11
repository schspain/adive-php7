<!-- Page Heading -->
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">
            <?=$title?> <small><?=$description?></small>
        </h1>
        <ol class="breadcrumb">
            <li class="active">
                <i class="fa fa-dashcube"></i> <?=$title?>
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

<!-- /.row -->
<span class="btn-group pull-right">
    <a href="<?=basePath()?>/admin/central/add/<?=$tables[0]['id']?>/<?=permalink($tables[0]['win_name'])?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i> Create new</a> 
</span>

<table class="table table-striped">
    <thead>
        <?php 
        $f=1;
        foreach ($fields as $key => $value): 
        if(substr($value['name'], -4) != '_rel' and substr($value['name'], -3) != '_fk') { ?>
        <th><?=$value['win_name']?></th>
        <?php 
            if($f==4){ break; }
        } else {
            if(substr($value['name'], -3) == '_fk') {
                $relationName = fkname($value['win_description']);
                ?>
        <th><?=$relationName?></th>
                <?php
                if($f==4){ break; }
            }
        }
        
        $f++;
        endforeach; ?>
        <th class="text-right">Options</th>
    </thead>
    <tbody>
<?php
// Aquí recibimos la variable $resultados
// Que es un array de una posición que contiene en dicha posición otro array con todas las filas
foreach ($list as $key => $value) {
?>
        <tr>
            <?php 
            $f=1;
            foreach ($fields as $key => $valueInt):
            if(substr($valueInt['name'], -4) != '_rel' and substr($valueInt['name'], -3) != '_fk') {
            if($f==1){ ?>
            <td><a href="<?=basePath()?>/admin/central/edit/<?=$value['id']?>/<?=$tables[0]['id']?>/<?=permalink($tables[0]['win_name'])?>"><?=$value[$valueInt['name']]?></a></td>
            <?php 
            } else { ?>
            <td><?=$value[$valueInt['name']]?></td> 
            <?php }
            if($f==4){ break; }
            } else {
                if(substr($valueInt['name'], -3) == '_fk') {
                ?>
            <td><a href="<?=basePath()?>/admin/central/edit/<?=$value['id']?>/<?=$tables[0]['id']?>/<?=permalink($tables[0]['win_name'])?>"><?=fkval($value[$valueInt['name']],$valueInt['win_description'])?></a></td>
                <?php
                if($f==4){ break; }
                }
            }
            $f++;
            endforeach; ?>
            <td class="text-right">
                <span class="btn-group">
                    <a href="<?=basePath()?>/admin/central/edit/<?=$value['id']?>/<?=$tables[0]['id']?>/<?=permalink($tables[0]['win_name'])?>" class="btn btn-default btn-xs"><i class="fa fa-pencil"></i> Edit</a>
                    <a href="javascript:void(0);" class="btn btn-warning btn-xs" onClick="if(confirm('Delete field?')) location.href = '<?=basePath()?>/admin/central/delete/<?=$value['id']?>/<?=$tables[0]['id']?>/<?=permalink($tables[0]['win_name'])?>';"><i class="fa fa-trash"></i> Delete</a>
                </span>
            </td>
        </tr>
<?php
}
?>
    </tbody>
</table>