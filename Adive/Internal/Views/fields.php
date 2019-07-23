<!-- Page Heading -->
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">
            <?=$title?> <small><?=$description?></small>
        </h1>
        <ol class="breadcrumb">
            <li class="active">
                <i class="fa fa-table"></i> <a href="<?=path('atables')?>">Tables</a> / <?=$title?>
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
            <i class="fa fa-info-circle"></i>  <strong>TIP:</strong> The fields are added in database tables and use it like form entities, <a href="<?=basePath()?>//admin/tables/fields/add/<?=$tables[0]['id']?>" class="alert-link">add a new field</a> to automatically generate cool forms!
        </div>
    </div>
</div>
<!-- /.row -->
<span class="btn-group pull-right">
    <a href="<?=basePath()?>/admin/tables/fields/add/<?=$tables[0]['id']?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i> Create new field</a> 
    <a href="<?=basePath()?>/admin/tables/relations/add/<?=$tables[0]['id']?>" class="btn btn-default"><i class="fa fa-link"></i> Create new relation</a>
</span>

<table class="table table-striped">
    <thead>
        <th>Name</th>
        <th>Description</th>
        <th>Type</th>
        <th>Date</th>
        <th>Author</th>
        <th class="text-right">Options</th>
    </thead>
    <tbody>
<?php
// Aquí recibimos la variable $resultados
// Que es un array de una posición que contiene en dicha posición otro array con todas las filas
$codeGenFields = array();
foreach ($fields as $key => $value) {
    $codeGenFields[] = $value['name'];
    $endFieldName = substr($value['name'], -3);
    if($endFieldName=='rel'){
        $enabledDelete=false;
    } else {
        $enabledDelete=true;
    }
    if($endFieldName=='rel' or $endFieldName=='_fk'){
        $iconKey=' <i class="fa fa-key"></i>';
    } else {
        $iconKey='';
    }
    
    switch ($value['win_type']):
        case 1: $fieldType='Text'; break;
        case 2: $fieldType='Textarea'; break;
        case 3: $fieldType='Text Editor'; break;
        case 4: $fieldType='Select'; break;
        case 5: $fieldType='Database Related Select'; break;
        case 6: $fieldType='Datepicker'; break;
        case 7: $fieldType='Datetime (Invisible)'; break;
        case 8: $fieldType='Image Upload'; break;
        case 9: $fieldType='Multiple images'; break;
        case 10: $fieldType='Invisible'; break;
		case 11: $fieldType='Combo'; break;
		case 11: $fieldType='Grid'; break;
		case 11: $fieldType='Select'; break;
		case 14: $fieldType='Checkbox'; break;
	case 15: $fieldType='Password'; break;
    endswitch;
?>
        <tr>
            <td><a href="<?=basePath()?>/admin/tables/<?=$tables[0]['id']?>/fields/edit/<?=$value['id']?>"><?=$value['name'].$iconKey?></a></td>
            <td><?=$value['comment']?></td>
            <td><?=$fieldType?></td>
            <td><?=date('d/m/Y',strtotime($value['creationDate']))?></td>
            <td><?=$value['author']?></td>
            <td class="text-right">
                <span class="btn-group">
                    <a href="<?=basePath()?>/admin/tables/<?=$tables[0]['id']?>/fields/edit/<?=$value['id']?>" class="btn btn-default btn-xs"><i class="fa fa-pencil"></i> Edit</a>
                    <?php if($enabledDelete==true){ ?><a href="javascript:void(0);" class="btn btn-warning btn-xs" onClick="if(confirm('Delete field?')) location.href = '<?=basePath()?>/admin/tables/<?=$tables[0]['id']?>/fields/delete/<?=$value['id']?>';"><i class="fa fa-trash"></i> Delete</a><?php } ?>
                </span>
            </td>
        </tr>
<?php
}
?>
    </tbody>
</table>
<span class="btn-group pull-right">
    <a href="<?=basePath()?>/admin/tables/trigger/add/<?=$tables[0]['id']?>" class="btn btn-info"><i class="fa fa-code"></i> Create new trigger</a> 
</span>
<?php if(!empty($triggers)){ ?>
<table class="table table-striped">
    <thead>
        <th>Name</th>
        <th>Time</th>
        <th>Trigger Type</th>
        <th>Date</th>
        <th class="text-right">Options</th>
    </thead>
    <tbody>
<?php
// Aquí recibimos la variable $resultados
// Que es un array de una posición que contiene en dicha posición otro array con todas las filas
foreach ($triggers as $key => $value) {
?>
        <tr>
            <td><a href="<?=basePath()?>/admin/tables/<?=$tables[0]['id']?>/trigger/edit/<?=$value['id']?>"><?=$value['name']?></a></td>
            <td><?=$value['time']?></td>
            <td><?=$value['type']?></td>
            <td><?=date('d/m/Y',strtotime($value['creationDate']))?></td>
            <td class="text-right">
                <span class="btn-group">
                    <a href="<?=basePath()?>/admin/tables/<?=$tables[0]['id']?>/trigger/edit/<?=$value['id']?>" class="btn btn-default btn-xs"><i class="fa fa-pencil"></i> Edit</a>
                    <a href="javascript:void(0);" class="btn btn-warning btn-xs" onClick="if(confirm('Delete trigger?')) location.href = '<?=basePath()?>/admin/tables/<?=$tables[0]['id']?>/trigger/delete/<?=$value['id']?>';"><i class="fa fa-trash"></i> Delete</a>
                </span>
            </td>
        </tr>
<?php
}
?>
    </tbody>
</table>
<?php } ?>



<div class="row">
<hr>
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li class="active">
                <i class="fa fa-flask"></i> <strong>CODE GENERATED:</strong> The following code is generated automatically by Adive to use it on Controllers or Views
            </li>
        </ol>
    </div>
</div>
<fieldset>
<legend>Controller path <code>Paste in: Controller/Default.php</code></legend>
<pre>
<?php
$nameFormatted = explode("_",$tables[0]['name']);
?>
// @Route(GET)
$API->get('/<?=$nameFormatted[0]?>', 
    function() use($API, $db) {
            pathActive('<?=$nameFormatted[0]?>');
            
            // Customize your Query to get specific data
            $tableQuery = $db->prepare("SELECT * FROM <?=$tables[0]['name']?> ORDER by id ASC");
            $tableQuery->execute();
            $resultTable = $tableQuery->fetchAll(PDO::FETCH_ASSOC);
            
            // Views/Default/<?=$nameFormatted[0]?>.php will be needed
            $API->render('Default:<?=$nameFormatted[0]?>', array(
                'title' => '<?=$tables[0]['win_name']?>',
                'description' => '<?=$tables[0]['win_description']?>',
                'data' => $resultTable
            ));
    }
)->name('<?=$nameFormatted[0]?>');

// @Route(GET)
$API->get('/<?=$nameFormatted[0]?>/add', 
    function() use($API, $db) {
            pathActive('<?=$nameFormatted[0]?>');
            
            // Views/Default/<?=$nameFormatted[0]?>_add.php will be needed
            $API->render('Default:<?=$nameFormatted[0]?>_add', array(
                'title' => '<?=$tables[0]['win_name']?>',
                'description' => '<?=$tables[0]['win_description']?>'
            ));
    }
)->name('add<?=ucfirst($nameFormatted[0])?>');

// @Route(POST)
$API->post('/<?=$nameFormatted[0]?>/add', 
    function() use($API, $db) {
        $formData=$API->request;
        $queryInsert=$db->prepare("INSERT INTO <?=$tables[0]['name']?> (<?php
        $currentRow = 1;
        $totalRows = count($codeGenFields);
        foreach ($codeGenFields as $value) { 
            echo $value; 
            if($currentRow<$totalRows) echo ', ';
        $currentRow++;
        }?>)
                                values (<?php
        $currentRow = 1;
        $totalRows = count($codeGenFields);
        foreach ($codeGenFields as $value) { 
            echo ':'.$value; 
            if($currentRow<$totalRows) echo ', ';
        $currentRow++;
        }?>);");
        
        $status=$queryInsert->execute(
                array(
                    <?php
                    $currentRow = 1;
                    $totalRows = count($codeGenFields);
                    foreach ($codeGenFields as $value) { 
                    echo '\':'.$value.'\'=> $formData->post(\'field'.ucfirst($value).'\')'; 
                    if($currentRow<$totalRows) echo ',
                    ';
                    $currentRow++;
                    }?>
                    
                    )
                );
        
        ($status)? $API->flash('message','Data created.')
                 : $API->flash('error','Exception detected: '.$queryInsert->errorInfo()[2].'.');
        
        ($status)? $API->redirect($API->urlFor('<?=$nameFormatted[0]?>'))
                 : $API->redirect($API->urlFor('add<?=ucfirst($nameFormatted[0])?>'));
    }
);

// @Route(GET)
$API->get('/<?=$nameFormatted[0]?>/edit/{<?=$nameFormatted[0]?>_id}', 
    function($<?=$nameFormatted[0]?>ID) use($API, $db) {
        pathActive('<?=$nameFormatted[0]?>');

        $query1 = $db->prepare("SELECT * FROM <?=$tables[0]['name']?> WHERE id=:<?=$nameFormatted[0]?>id ORDER by id ASC");
        $query1->execute(array(
            '<?=$nameFormatted[0]?>id' => $<?=$nameFormatted[0]?>ID
        ));
        $result1 = $query1->fetchAll(PDO::FETCH_ASSOC);
        
        $API->render('Default:<?=$nameFormatted[0]?>_edit', array(
            'title' => '<?=$tables[0]['win_name']?>',
            'description' => '<?=$tables[0]['win_description']?>',
            'data' => $result1
        ));
    }
)->name('edit<?=ucfirst($nameFormatted[0])?>');

// @Route(POST)
$API->post('/<?=$nameFormatted[0]?>/edit/{<?=$nameFormatted[0]?>_id}', 
    function($<?=$nameFormatted[0]?>ID) use($API, $db) {
        pathActive('<?=$nameFormatted[0]?>');
        $formData=$API->request;
        
        $queryUpdate=$db->prepare("UPDATE <?=$tables[0]['name']?> SET <?php
        $currentRow = 1;
        $totalRows = count($codeGenFields);
        foreach ($codeGenFields as $value) { 
            echo $value.'=:'.$value; 
            if($currentRow<$totalRows) echo ', ';
        $currentRow++;
        }?> WHERE id=:id");

        $status=$queryUpdate->execute(
                array(
                    ':id' => $<?=$nameFormatted[0]?>ID,
                    <?php
                    $currentRow = 1;
                    $totalRows = count($codeGenFields);
                    foreach ($codeGenFields as $value) { 
                    echo '\':'.$value.'\'=> $formData->post(\'field'.ucfirst($value).'\')'; 
                    if($currentRow<$totalRows) echo ',
                    ';
                    $currentRow++;
                    }?>
                    )
                );
        
        ($status)? $API->flash('message','Data updated.')
                 : $API->flash('error','Exception detected: '.$queryInsert->errorInfo()[2].'.');
        
        ($status)? $API->redirect($API->urlFor('<?=$nameFormatted[0]?>'))
                 : $API->redirect(basePath().'/<?=$nameFormatted[0]?>/edit/'.$<?=$nameFormatted[0]?>ID);
    }
);
</pre>
</fieldset>
<fieldset>
<legend>View print content <code>Paste in: Views/Default/<?=$nameFormatted[0]?>.php</code></legend>
<pre>
<?php
$nameFormatted = explode("_",$tables[0]['name']);

$text = '<?php
// Get database data from Controller, put this code in Views/Default/'.$nameFormatted[0].'.php
foreach ($data as $key => $value) {
    // Customize your view with Bootstrap
';
    foreach ($codeGenFields as $value) {
        $text .= '      echo $value[\''.$value.'\'] . "<br>";
';
    }
$text .= '}
?>
';

echo htmlentities($text);
?>
</pre>
</fieldset>
