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
            <i class="fa fa-info-circle"></i>  <strong>TIP:</strong> A trigger is a event, script or code that MySQL server executes in runtime, faster than PHP, write here everything that your app will do.
        </div>
    </div>
</div>
<!-- /.row -->

<form class="form-horizontal" method="post">
  <div class="form-group">
    <label for="timeTrigger" class="col-sm-2 control-label">Moment</label>
    <div class="col-sm-10">
        <select name="timeTrigger" id="timeTrigger" class="form-control">
            <option value="BEFORE">BEFORE</option>
            <option value="AFTER">AFTER</option>
        </select>
    </div>
  </div>
  <div class="form-group">
    <label for="typeTrigger" class="col-sm-2 control-label">Trigger on</label>
    <div class="col-sm-10">
        <select name="typeTrigger" id="typeTrigger" class="form-control">
            <option value="INSERT">INSERT</option>
            <option value="UPDATE">UPDATE</option>
            <option value="DELETE">DELETE</option>
        </select>
    </div>
  </div>
  <div class="form-group">
    <label for="codeTrigger" class="col-sm-2 control-label">Code</label>
    <div class="col-sm-10">
        <textarea class="form-control" rows="8" id="sqlquery" name="codeTrigger"></textarea>
        <!--<textarea tabindex="100" name="sql_query"  cols="40"  rows="30">SELECT * FROM `adive_nav` WHERE 1</textarea>-->
        <span class="help-block">MySQL Language <a href="https://mariadb.com/kb/en/mariadb/sql-structure-and-commands/" target="_blank"><i class="fa fa-info-circle"></i> Language documentation</a>.</span>
    </div>
  </div>
    
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-lg btn-primary">Create trigger</button>
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