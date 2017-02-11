<!-- Page Heading -->
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">
            <?=$title?> <small><?=$description?></small>
        </h1>
        <ol class="breadcrumb">
            <li class="active">
                <i class="fa fa-heartbeat"></i> <?=$title?>
            </li>
        </ol>
    </div>
</div>

<div class="jumbotron">
    <div class="container">
      <h2><?=$name?></h2>
      <p>Select your API invocation type</p>
      <form id="configForm" class="form-horizontal" method="post" onsubmit="if(confirm('<?=$question?>, sure?')){ return true; } else { return false; }">  
        <div class="form-group">
          <label for="invokeType" class="col-sm-2 control-label">Invocation API result</label>
          <div class="col-sm-10">
              <select name="invokeType" id="invokeType" class="form-control">
                  <?php
                  foreach (invocations() as $key => $inv) {
                  ?>
                  <option value="<?=$key?>"<?php if($defaultInvoke==$key) echo 'selected'; ?>><?=$inv?></option>
                  <?php } ?>
              </select>
          </div>
        </div>
          <p>You can customize your own invocations in <code>/Adive/Invocations</code> and load it in <code>/Adive/Procedures/Default/Default.php</code>.</p>
        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-lg">Start Invocation &raquo;</button>
        </div>
      </form>
    </div>
  </div>
<!-- /.row -->

<hr>

<footer>
  <p>&copy; 2014-<?=date('Y')?> Adive Framework Version.<?=Adive\Adive::VERSION?></p>
</footer>