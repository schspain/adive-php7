<!-- Page Heading -->
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">
            <?=$title?> <small><?=$description?></small>
        </h1>
        <ol class="breadcrumb">
            <li class="active">
                <i class="fa <?=$icon?>"></i> <a href="<?=basePath()?>/admin/central/<?=$tables[0]['id']?>/<?=permalink($tables[0]['win_name'])?>"><?=$tables[0]['win_name']?></a> / <?=$title?>
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
            <i class="fa fa-info-circle"></i>  <strong>TIP:</strong> Fill the form to add a new field, "Field Type" is the type of entity that you want to use in your forms.
        </div>
    </div>
</div>
<!-- /.row -->

<form class="form-horizontal" method="post">
    <?php 
    $f=1;
    foreach ($fields as $key => $value): 
    if(substr($value['name'], -4) != '_rel' and substr($value['name'], -3) != '_fk') { 
        if($f==1){
            $autofocus = 'autofocus';
        } else { $autofocus = ''; }
    ?>
  <div class="form-group">
    <label for="field_<?=$value['name']?>" class="col-sm-2 control-label"><?=$value['win_name'];?></label>
    <div class="col-sm-10">
        <?php
        switch ($value['win_type']):
            case 1: 
        // TEXT INPUT ?>
        <input type="text" class="form-control" id="field_<?=$value['name']?>" name="field_<?=$value['name']?>" placeholder="<?=$value['win_description']?>" <?=$autofocus?>>
            <?php break;
            case 2: 
        // TEXTAREA ?>
        <textarea class="form-control" rows="3" id="field_<?=$value['name']?>" name="field_<?=$value['name']?>" <?=$autofocus?>></textarea>
        <span class="help-block"><?=$value['win_description']?></span>
            <?php break;
            case 3: 
        // TEXT EDITOR ?>
        <textarea class="form-control" rows="3" id="field_<?=$value['name']?>" name="field_<?=$value['name']?>" <?=$autofocus?>></textarea>
        <span class="help-block"><?=$value['win_description']?></span>
        <script>
            $(document).ready(function() {
                $('#field_<?=$value['name']?>').summernote();
            });
        </script>
            <?php break;
            case 4: 
        // SELECT
        $selectValues = explode(',',$value['win_code']);  
        ?>
        <select name="field_<?=$value['name']?>" id="field_<?=$value['name']?>" class="form-control selectpicker" data-live-search="true">
            <?php foreach ($selectValues as $key => $optv): ?>
            <option value="<?=permalink($optv)?>"><?=$optv?></option>
            <?php endforeach; ?>
        </select>
        <span class="help-block"><?=$value['win_description']?></span>
            <?php break;
            case 5: $fieldType='Select multiple'; break;
            case 6: ?>
        <div class="input-group date col-xs-6" id="datetimepicker1">
            <input type="text" class="form-control" id="field_<?=$value['name']?>" name="field_<?=$value['name']?>" placeholder="YYYY/MM/DD" required>
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
            </span>
        </div>
        <?php break;
            case 7: 
        // DATETIME ?>
        <input type="hidden" class="form-control" id="field_<?=$value['name']?>" value="<?=date('Y-m-d H:i:s')?>" name="field_<?=$value['name']?>">
        <p class="form-control-static"><?=date('d/m/Y H:i')?></p>
            <?php break;
            case 8:
        // IMAGE UPLOAD ?>
        <input type="hidden" class="form-control" id="field_<?=$value['name']?>" value="<?=$list[0][$value['name']]?>" name="field_<?=$value['name']?>">
        <input id="field_file_<?=$value['name']?>" name="field_file_<?=$value['name']?>" value="<?=$list[0][$value['name']]?>" type="file" class="form-control file-loading" accept="image/*" onchange="var wholePIC = this.value; var splitPIC = wholePIC.split("."); $(#field_<?=$value['name']?>).val(splitPIC[1]+"_<?=$_SESSION['adive.time']?>."+splitPIC[2]);">
        <script>
        $("#field_file_<?=$value['name']?>").fileinput({
            uploadUrl: "<?=basePath()?>/Adive/Internal/upload.php",
            uploadAsync: true,
            allowedFileExtensions: ["jpg", "png", "gif"],
            maxImageWidth: 400,
            maxImageHeight: 350,
            resizePreference: 'height',
            maxFileCount: 1,
        uploadExtraData: function() {
            return {
                inputname: 'field_file_<?=$value['name']?>',
                uploadfolder: '../uploads'
            };
        },
            resizeImage: true
        }).on('filepreupload', function() {
            $('#kv-success-box').html('');
        }).on('fileuploaded', function(event, data) {
            $('#kv-success-box').append(data.response.link);
            $('#kv-success-modal').modal('show');
			$('#field_<?=$value['name']?>').val($('#field_file_<?=$value['name']?>').val());
        });
        </script>
        <?php break;
            case 9: $fieldType='Multiple images'; break;
            case 10: 
        // TEXT INPUT ?>
        <input type="hidden" class="form-control" id="field_<?=$value['name']?>" value="" name="field_<?=$value['name']?>">
            <?php break;
            case 14: 
        // CHECKBOX ?>
        <div class="btn-group" data-toggle="buttons">
        <label class="btn btn-default">
                <input type="checkbox" autocomplete="off" class="form-control" id="field_<?=$value['name']?>" value="1" name="field_<?=$value['name']?>">
                <span class="glyphicon glyphicon-ok"></span>
        </label>
        </div>
            <?php break;
        endswitch;
        ?>
    </div>
  </div>
    <?php 
    } else {
        if(substr($value['name'], -3) == '_fk') {
            $optList = fklist($value['win_description']);
            $relationName = fkname($value['win_description']);
    ?>
  <div class="form-group">
    <label for="field_<?=$value['name']?>" class="col-sm-2 control-label"><?=$relationName?></label>
    <div class="col-sm-10">
        <select name="field_<?=$value['name']?>" id="field_<?=$value['name']?>" class="form-control selectpicker" data-live-search="true">
            <?php foreach ($optList as $key => $value): ?>
            <option value="<?=$key?>"><?=$value?></option>
            <?php endforeach; ?>
        </select>
    </div>
  </div>
    <?php
        } 
    }
    $f++;
    endforeach; ?>
    
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-lg btn-primary">Add new entry</button>
    </div>
  </div>
</form>


<script type="text/javascript">
$(function () {
    $('#datetimepicker1').datetimepicker({
            format: 'DD/MM/YYYY'
        });

   $('#datetimepicker2').datetimepicker({
            format: 'DD/MM/YYYY'
        });    
});
</script>
