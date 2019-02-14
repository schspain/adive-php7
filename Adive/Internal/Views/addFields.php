<!-- Page Heading -->
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">
            <?=$title?> <small><?=$description?></small>
        </h1>
        <ol class="breadcrumb">
            <li class="active">
                <i class="fa fa-table"></i> <a href="<?=path('atables')?>">Tables</a> / <a href="<?=basePath()?>/admin/tables/fields/<?=$tableid?>">Fields</a> / <?=$title?>
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
  <div class="form-group">
    <label for="fieldName" class="col-sm-2 control-label">Field Name</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" id="fieldName" name="fieldName" placeholder="name" onkeyup="kpField();" required autofocus>
        <span class="help-block">Field name in database, no spaces and entities allowed.</span>
    </div>
  </div>
  <div class="form-group">
    <label for="fieldComment" class="col-sm-2 control-label">Comment</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" id="fieldComment" name="fieldComment" placeholder="Field comment in database" onblur="winName.value=this.value; winDescription.value=this.value;" required>
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
    <label for="winOrder" class="col-sm-2 control-label">View Order</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" id="winOrder" name="winOrder" placeholder="Appareance order" value="<?=$numfields?>" required>
    </div>
  </div>
  <div class="form-group">
    <label for="winType" class="col-sm-2 control-label">Field Type</label>
    <div class="col-sm-10">
        <select name="winType" id="winType" class="form-control">
            <option value="1">Text</option>
            <option value="2">Textarea</option>
            <option value="3">Text Editor</option>
            <option value="4">Select</option>
            <option value="5">Select multiple</option>
            <option value="6">Datepicker</option>
            <option value="7">Datetime (Hidden)</option>
            <option value="8">Image upload</option>
            <option value="9">Multiple images upload</option>
            <option value="10">Invisible Script</option>
            <option value="14">Checkbox</option>
        </select>
    </div>
  </div>
  <div class="form-group">
    <label for="winCode" class="col-sm-2 control-label">Field Code</label>
    <div class="col-sm-10">
        <textarea class="form-control" rows="3" id="winCode" name="winCode"></textarea>
        <span class="help-block">Field for related code with "Field Type" Ex: With Select database related type the name of the table: mynewtable.</span>
    </div>
  </div>
  <div class="form-group">
    <label for="winType" class="col-sm-2 control-label">Visible</label>
    <div class="col-sm-10">
        <select name="visible" id="visible" class="form-control">
            <option value="1">Yes (Users can view this field)</option>
            <option value="0">No (No one can make changes in this field)</option>
        </select>
    </div>
  </div>
    
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-lg btn-primary">Create field</button>
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
