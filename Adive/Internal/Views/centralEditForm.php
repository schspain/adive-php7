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
        //substr($value['name'], -4) != '_rel' and 
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
        <input type="text" class="form-control" id="field_<?=$value['name']?>" name="field_<?=$value['name']?>" value="<?=$list[0][$value['name']]?>" placeholder="<?=$value['win_description']?>" <?=$autofocus?>>
            <?php break;
	    case 15: 
        // TEXT INPUT ?>
        <input type="password" class="form-control" id="field_<?=$value['name']?>" name="field_<?=$value['name']?>" value="<?=$list[0][$value['name']]?>" placeholder="<?=$value['win_description']?>" <?=$autofocus?>>
            <?php break;
            case 2: 
        // TEXTAREA ?>
        <textarea class="form-control" rows="3" id="field_<?=$value['name']?>" name="field_<?=$value['name']?>" <?=$autofocus?>><?=$list[0][$value['name']]?></textarea>
        <span class="help-block"><?=$value['win_description']?></span>
            <?php break;
            case 3: 
        // TEXT EDITOR ?>
        <textarea class="form-control" rows="3" id="field_<?=$value['name']?>" name="field_<?=$value['name']?>" <?=$autofocus?>><?=$list[0][$value['name']]?></textarea>
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
            <?php foreach ($selectValues as $key => $optv):
            
                if($list[0][$value['name']]==$optv){
                    $optSelected=' selected';
                } else {
                    $optSelected='';
                }
            ?>
            <option value="<?=permalink($optv)?>"<?=$optSelected?>><?=$optv?></option>
            <?php endforeach; ?>
        </select>
        <span class="help-block"><?=$value['win_description']?></span>
            <?php break;
            case 5: $fieldType='Select multiple'; break;
            case 6: ?>
        <div class="input-group date col-xs-6" id="datetimepicker1">
            <input type="text" class="form-control" id="field_<?=$value['name']?>" name="field_<?=$value['name']?>" value="<?=$list[0][$value['name']]?>" placeholder="DD/MM/YYYY">
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
            </span>
        </div>
        <?php break;
            case 7: 
        // DATETIME ?>
        <input type="hidden" class="form-control" id="field_<?=$value['name']?>" value="<?=$list[0][$value['name']]?>" name="field_<?=$value['name']?>">
        <p class="form-control-static"><?=date('d/m/Y H:i')?></p>
            <?php break;
            case 8: 
        // IMAGE UPLOAD ?>
        <?php
		// Image thumb
		if(!empty($list[0][$value['name']])){
		?>
		<img src="<?=basePath()?>/Adive/uploads/<?=$list[0][$value['name']]?>" width="400" style="margin:10px; border-radius:10px;">
		<?php
		}
		?>
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
        <input type="hidden" class="form-control" id="field_<?=$value['name']?>" value="<?=$list[0][$value['name']]?>" name="field_<?=$value['name']?>">
            <?php break;
            case 12: 
        // GRID ?>
        <!--<script>
        $(function () {
            var source = [
                {id: 1, street: "C/Mendizabal 12", town: "Alicante", estate: "Alicante", country: "Spain"},
                {id: 2, street: "C/Cristobal 135", town: "Alicante", estate: "Alicante", country: "Spain"},
            ];

            function resetTabullet() {
                $("#table").tabullet({
                    data: source,
                    action: function (mode, data) {
                        console.dir(mode);
                        if (mode === 'save') {
                            source.push(data);
                        }
                        if (mode === 'edit') {
                            for (var i = 0; i < source.length; i++) {
                                if (source[i].id == data.id) {
                                    source[i] = data;
                                }
                            }
                        }
                        if(mode == 'delete'){
                            for (var i = 0; i < source.length; i++) {
                                if (source[i].id == data) {
                                    source.splice(i,1);
                                    break;
                                }
                            }
                        }
                        resetTabullet();
                    }
                });
            }

            resetTabullet();
        });
        </script>
        <div class="row">
        <div class="col-sm-12">
            <table class="table table-hover" id="table">
                <thead>
                <tr data-tabullet-map="id">
                    <th width="50" data-tabullet-map="_index" data-tabullet-readonly="true">
                        No
                    </th>
                    <th data-tabullet-map="street">Street</th>
                    <th data-tabullet-map="town">Town</th>
                    <th data-tabullet-map="estate">Estate</th>
                    <th data-tabullet-map="country">Country</th>
                    <th width="50" data-tabullet-type="edit"></th>
                    <th width="50" data-tabullet-type="delete"></th>
                </tr>
                </thead>
            </table>
        </div>
    </div>-->
        <?php break;
            case 14: 
        // CHECKBOX 
                if($list[0][$value['name']]==1){ $checked=' checked'; $active=' active'; }
                else { $checked=''; $active=''; }
                ?>
        <div class="btn-group" data-toggle="buttons">
        <label class="btn btn-default <?=$active?>">
                <input type="checkbox" autocomplete="off" class="form-control" id="field_<?=$value['name']?>" value="1" name="field_<?=$value['name']?>"<?=$checked?>>
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
            <?php foreach ($optList as $key => $valueFK): 
                if($list[0][$value['name']]==$key){
                    $optSelected=' selected';
                } else {
                    $optSelected='';
                }
             ?>
            <option value="<?=$key?>"<?=$optSelected?>><?=$valueFK?></option>
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
      <button type="submit" class="btn btn-lg btn-primary">Save changes</button>
    </div>
  </div>
</form>

<script type="text/javascript">
$(function () {
    $('#datetimepicker1').datetimepicker({
            format: 'YYYY/MM/DD'
        });

   $('#datetimepicker2').datetimepicker({
            format: 'YYYY/MM/DD'
        });          
});
</script>
