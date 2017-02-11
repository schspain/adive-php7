<!-- Page Heading -->
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">
            <?=$title?> <small><?=$description?></small>
        </h1>
        <a href="<?=path('auser')?>" class="pull-right btn btn-primary"><i class="fa fa-users"></i> Manage dashboard users</a>
        <ol class="breadcrumb">
            <li class="active">
                <i class="fa fa-cogs"></i> <?=$title?>
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
            <i class="fa fa-info-circle"></i>  <strong>TIP:</strong> If you change the name or table description, the database table will be updated without loss of data.
        </div>
    </div>
</div>


<!-- /.row -->
<?php
foreach ($conf as $key => $value) {
?>
<form id="configForm" class="form-horizontal" method="post">
  <div class="form-group">
    <label for="userName" class="col-sm-2 control-label">User Name</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" id="userName" name="userName" placeholder="mynewtable" value="<?=$value['name']?>" required>
    </div>
  </div>
  <div class="form-group">
    <label for="confPermissions" class="col-sm-2 control-label">Permissions</label>
    <div class="col-sm-10">
        <select name="confPermissions" id="confPermissions" class="form-control">
            <option value="1"<?php if($value['permissions']==1) echo ' selected'; ?>>Administrator</option>
            <option value="2"<?php if($value['permissions']==2) echo ' selected'; ?>>Developer</option>
            <option value="3"<?php if($value['permissions']==3) echo ' selected'; ?>>Editor</option>
        </select>
    </div>
  </div>
  <div class="form-group">
    <label for="winName" class="col-sm-2 control-label">User</label>
    <div class="col-sm-10">
        <p class="form-control-static"><?=$_SESSION['adive.user']?></p>
    </div>
  </div>
  <div class="form-group">
    <label for="winDescription" class="col-sm-2 control-label">Change password</label>
    <div class="col-sm-10">
        <div class="form-inline">
            <input id="pass" name="pass" type="password" placeholder="Password" class="form-control">
            <input id="cpass" name="cpass" type="password" placeholder="Confirm Password" class="form-control">
        </div>
        <span class="help-block">To change the password, fill the two password fields.</span>
    </div>
  </div>
  <div class="form-group">
    <label for="invokeType" class="col-sm-2 control-label">Invocation API result</label>
    <div class="col-sm-10">
        <select name="invokeType" id="invokeType" class="form-control">
            <?php
            foreach (invocations() as $key => $inv) {
            ?>
            <option value="<?=$key?>"<?php if($value['invokeType']==$key) echo ' selected'; ?>><?=$inv?></option>
            <?php } ?>
        </select>
        <span class="help-block">All the invocations will use Bootstrap as base code.</span>
    </div>
  </div>
    
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-lg btn-primary">Save configuration</button>
    </div>
  </div>
</form>
<?php } ?>