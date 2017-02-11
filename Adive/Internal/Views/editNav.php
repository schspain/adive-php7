<script>
$(function() {
    $('#iconsGlyph').selectpicker();
});
</script>
<!-- Page Heading -->
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">
            <?=$title?> <small><?=$description?></small>
        </h1>
        <ol class="breadcrumb">
            <li class="active">
                <i class="fa fa-navicon"></i> <a href="<?=path('anav')?>">Navigation menu</a> / <?=$title?>
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
            <i class="fa fa-info-circle"></i>  <strong>TIP:</strong> If you change the name or description, the database table will be updated without loss of data.
        </div>
    </div>
</div>
<!-- /.row -->
<?php
foreach ($nav as $key => $value) {
?>
<form class="form-horizontal" method="post">
  <div class="form-group">
    <label for="navName" class="col-sm-2 control-label">Link Name</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" id="navName" name="navName" placeholder="<?=$value['name']?>" value="<?=$value['name']?>" required>
    </div>
  </div>
  <div class="form-group">
    <label for="tableDescription" class="col-sm-2 control-label">Table Association</label>
    <div class="col-sm-10">
        <div class="input-group">
            <select name="fkTable" id="fkTable" class="form-control">
                <option value="NULL"<?php if($value['table_id_fk']=='NULL') echo ' selected'; ?>>No association (empty link)</option>
                <?php
                foreach ($tables as $key => $tval) {
                    if($value['table_id_fk']==$tval['id']){
                        $selected=' selected';
                    } else {
                        $selected='';
                    }
                ?>
                <option value="<?=$tval['id']?>"<?=$selected?>><?=$tval['win_name']?></option>
                <?php 
                } ?>
            </select>
        </div>
        <span class="help-block">Select view association or simple link.</span>
    </div>
  </div>
  <div class="form-group">
    <label for="navDescription" class="col-sm-2 control-label">Description</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" id="navDescription" name="navDescription" placeholder="Navigation link description" value="<?=$value['description']?>" required>
    </div>
  </div>
  <div class="form-group">
    <label for="navParent" class="col-sm-2 control-label">Category</label>
    <div class="col-sm-10">
        <select name="navParent" id="navParent" class="form-control">
            <option value="NULL"<?php if($value['parent']=='NULL') echo ' selected'; ?>>Base Category (Parent)</option>
            <?php
            foreach ($parent as $key => $pval) {
                if($pval['id']==$value['parent']){
                    $selected=' selected';
                } else {
                    $selected='';
                }
            ?>
            <option value="<?=$pval['id']?>"<?=$selected?>><?=$pval['name']?></option>
            <?php } ?>
        </select>
    </div>
  </div>
    
  <div class="form-group">
    <label for="navParent" class="col-sm-2 control-label">Icon</label>
    <div class="col-sm-10">
        <div class="input-group">
            <input name="navIcon" id="navIcon" data-placement="bottomRight" class="form-control icp icp-auto" value="<?=$value['icon']?>" type="text" />
            <span class="input-group-addon"></span>
        </div>
    </div>
  </div>
     
    
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-lg btn-primary">Update link</button>
    </div>
  </div>
</form>
<script>
    $(function() {
            $('.icp-auto').iconpicker();

            $('.icp-dd').iconpicker({
                //title: 'Dropdown with picker',
                //component:'.btn > i'
            });

            $('.icp-glyphs').iconpicker({
                title: 'Prepending glypghicons',
                icons: $.merge(['glyphicon-home', 'glyphicon-repeat', 'glyphicon-search',
                    'glyphicon-arrow-left', 'glyphicon-arrow-right', 'glyphicon-star'], $.iconpicker.defaultOptions.icons),
                fullClassFormatter: function(val){
                    if(val.match(/^fa-/)){
                        return 'fa '+val;
                    }else{
                        return 'glyphicon '+val;
                    }
                }
            });


        // Events sample:
        // This event is only triggered when the actual input value is changed
        // by user interaction
        $('.icp').on('iconpickerSelected', function(e) {
            $('.lead .picker-target').get(0).className = 'picker-target fa-3x ' +
                    e.iconpickerInstance.options.iconBaseClass + ' ' +
                    e.iconpickerInstance.options.fullClassFormatter(e.iconpickerValue);
        });
    });
</script>
<?php }