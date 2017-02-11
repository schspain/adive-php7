<!-- Page Heading -->
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">
            <?=$title?> <small><?=$description?></small>
        </h1>
        <ol class="breadcrumb">
            <li class="active">
                <i class="fa fa-users"></i> <a href="<?=path('auser')?>">Users</a> / <?=$title?>
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
            <i class="fa fa-info-circle"></i>  <strong>TIP:</strong> Fill the form to add a new user in your database with reseller/customer permissions.
        </div>
    </div>
</div>
<!-- /.row -->

<form class="form-horizontal" method="post" autocomplete="off">
   <div class="form-group">
    <label for="navName" class="col-sm-2 control-label">Name</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" id="userName" name="userName" placeholder="New User" required autofocus>
    </div>
  </div>
  <div class="form-group">
    <label for="navName" class="col-sm-2 control-label">Username</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" id="userUsername" name="userUsername" placeholder="username"  onkeyup="kpTableName();" autocomplete="off" required>
    </div>
  </div>
  <div class="form-group">
    <label for="winDescription" class="col-sm-2 control-label">Change password</label>
    <div class="col-sm-10">
        <div class="form-inline">
            <input id="pass" name="pass" type="password" placeholder="Password" class="form-control" required>
            <input id="cpass" name="cpass" type="password" placeholder="Confirm Password" class="form-control" required>
        </div>
        <span class="help-block">Fill the two password fields.</span>
    </div>
  </div>
  <div class="form-group">
    <label for="permission" class="col-sm-2 control-label">Permissions</label>
    <div class="col-sm-10">
        <div class="input-group">
            <select name="permission" id="permission" class="form-control">
                <option value="2">Developer</option>
                <option value="3" selected>Editor</option>
            </select>
        </div>
        <span class="help-block">Select permissions for this user.</span>
    </div>
  </div>
    
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-lg btn-primary">Create user</button>
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
            $('#userUsername').val(slug($('#userUsername').val()));
            var name = $('#userUsername').val();
        }
</script>