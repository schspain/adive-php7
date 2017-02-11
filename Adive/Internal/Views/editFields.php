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
foreach ($field as $key => $value) {
    
    $endFieldName = substr($value['name'], -3);
    if($endFieldName=='_fk' or $endFieldName=='rel'){
        $disabled='readonly ';
    } else {
        $disabled='';
    }
?>
<form class="form-horizontal" method="post">
  <div class="form-group">
    <label for="fieldName" class="col-sm-2 control-label">Field Name</label>
    <div class="col-sm-10">
        <input type="hidden" class="form-control" id="oldName" name="oldName" value="<?=$value['name']?>">
        <input type="text" class="form-control" id="fieldName" name="fieldName" placeholder="name" onkeyup="kpField();" value="<?=$value['name']?>" <?=$disabled?>required>
        <span class="help-block">Field name in database, no spaces and entities allowed.</span>
    </div>
  </div>
  <div class="form-group">
    <label for="fieldComment" class="col-sm-2 control-label">Comment</label>
    <div class="col-sm-10">
        <input type="hidden" class="form-control" id="oldComment" name="oldComment" value="<?=$value['comment']?>">
        <input type="text" class="form-control" id="fieldComment" name="fieldComment" placeholder="Field comment in database" value="<?=$value['comment']?>" <?=$disabled?>required>
    </div>
  </div>
  <div class="form-group">
    <label for="winName" class="col-sm-2 control-label">View Name</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" id="winName" name="winName" placeholder="Visual name in APP" value="<?=$value['win_name']?>" required>
    </div>
  </div>
  <div class="form-group">
    <label for="winDescription" class="col-sm-2 control-label">View Description</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" id="winDescription" name="winDescription" placeholder="Visual description for this View" <?=$disabled?>value="<?=$value['win_description']?>" required>
    </div>
  </div>
  <div class="form-group">
    <label for="winOrder" class="col-sm-2 control-label">View Order</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" id="winOrder" name="winOrder" placeholder="Appareance order" value="<?=$value['win_order']?>" required>
    </div>
  </div>
  <div class="form-group">
    <label for="winType" class="col-sm-2 control-label">Field Type</label>
    <div class="col-sm-10">
        <input type="hidden" class="form-control" id="oldType" name="oldType" value="<?=$value['win_type']?>">
        <select name="winType" id="winType" class="form-control"><?php
        if($disabled==''){
        ?>
            <option value="1"<?php if($value['win_type']==1) echo ' selected'; ?>>Text</option>
            <option value="2"<?php if($value['win_type']==2) echo ' selected'; ?>>Textarea</option>
            <option value="3"<?php if($value['win_type']==3) echo ' selected'; ?>>Text Editor</option>
            <option value="4"<?php if($value['win_type']==4) echo ' selected'; ?>>Select</option>
            <option value="5"<?php if($value['win_type']==5) echo ' selected'; ?>>Select multiple</option>
            <option value="6"<?php if($value['win_type']==6) echo ' selected'; ?>>Datepicker</option>
            <option value="7"<?php if($value['win_type']==7) echo ' selected'; ?>>Datetime (Hidden)</option>
            <option value="8"<?php if($value['win_type']==8) echo ' selected'; ?>>Image upload</option>
            <option value="9"<?php if($value['win_type']==9) echo ' selected'; ?>>Multiple images upload</option>
            <option value="10"<?php if($value['win_type']==10) echo ' selected'; ?>>Invisible Script</option>
            <option value="14"<?php if($value['win_type']==14) echo ' selected'; ?>>Checkbox</option><?php
        } else { ?>
            <option value="11"<?php if($value['win_type']==11) echo ' selected'; ?>>Combo</option>
            <!--<option value="12"<?php if($value['win_type']==12) echo ' selected'; ?>>Grid</option>-->
            <option value="13"<?php if($value['win_type']==13) echo ' selected'; ?>>Select</option>
        <?php } ?>
        </select>
    </div>
  </div>
  <div class="form-group">
    <label for="winCode" class="col-sm-2 control-label">Field Code</label>
    <div class="col-sm-10">
        <textarea class="form-control" rows="3" id="winCode" name="winCode" <?=$disabled?>><?=$value['win_code']?></textarea>
        <span class="help-block">Field for related code with "Field Type" Ex: With Select database related type the name of the table: mynewtable.</span>
    </div>
  </div>
    
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-lg btn-primary">Update field</button>
    </div>
  </div>
</form>

<script type="text/javascript">     
        function slug(str) {
            
            str = str.replace(/^\s+|\s+$/g, ''); // trim
            str = str.toLowerCase();

            // remove accents, swap ñ for n, etc
            var from = "ãàáäâẽèéëêìíïîõòóöôùúüûñç·/_,:;";
            var to   = "aaaaaeeeeeiiiiooooouuuunc------";
            for (var i=0, l=from.length ; i<l ; i++) {
              str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
            }

            str = str.replace(/[^a-z0-9 -]/g, '') // remove invalid chars
              .replace(/\s+/g, '_') // collapse whitespace and replace by -
              .replace(/-+/g, '_'); // collapse dashes

            return str;
        };

        function kpField(){
            
            $('#fieldName').val(slug($('#fieldName').val()));
            
        }
</script>
<?php } ?>