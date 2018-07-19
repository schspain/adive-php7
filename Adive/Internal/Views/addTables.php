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
            <i class="fa fa-info-circle"></i>  <strong>TIP:</strong> Fill the form to add a new table in your database and a cool form to your APP.
        </div>
    </div>
</div>
<!-- /.row -->

<form class="form-horizontal" method="post">
  <div class="form-group">
    <label for="tableName" class="col-sm-2 control-label">Table Name</label>
    <div class="col-sm-10">
        <div class="input-group">
            <input type="text" class="form-control" id="tableName" name="tableName" placeholder="mynewtable" onkeyup="kpTableName();" required autofocus>
            <input type="hidden" class="form-control" id="nameSplit" name="nameSplit">
            <span id="nameSplitValue" class="input-group-addon">
                _tab
            </span>
        </div>
        <span class="help-block">Table name in database, Adive add a tagline of 3 letters for prevent duplicates.</span>
    </div>
  </div>
  <div class="form-group">
    <label for="tableDescription" class="col-sm-2 control-label">Description</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" id="tableDescription" name="tableDescription" placeholder="Table comment in database" onblur="winName.value=this.value; winDescription.value=this.value;" required>
    </div>
  </div>
  <div class="form-group">
    <label for="winName" class="col-sm-2 control-label">View Name</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" id="winName" name="winName" placeholder="Visual name in APP" required>
    </div>
  </div>
  <div class="form-group">
    <label for="winDescription" class="col-sm-2 control-label">View Description</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" id="winDescription" name="winDescription" placeholder="Visual description for this View" required>
    </div>
  </div>
  <div class="form-group">
    <label for="tableNameField" class="col-sm-2 control-label">Field name for table relations</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" id="tableNameField" name="tableNameField" placeholder="Name field, Ex: name" required>
        <span class="help-block">Type the name of the field that indicates the name of resource.</span>
    </div>
  </div>
    
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-lg btn-primary">Create table</button>
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

        function kpTableName(){
            
            $('#tableName').val(slug($('#tableName').val()));
            var name = $('#tableName').val();
            $('#nameSplit').val(name.substring(0, 3));
            $('#nameSplitValue').html('_'+name.substring(0, 3));
            
        }
</script>
