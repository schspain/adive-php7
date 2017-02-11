<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title><?=$title?> - Adive Framework</title>
    <link rel="icon" type="image/png" href="<?=basePath()?>/Adive/Internal/images/adiveLogo.png" />
    <!-- Bootstrap core CSS -->
    <link href="<?=iasset('bootstrap.min.css')?>" rel="stylesheet">
    <link href="<?=iasset('bootstrap-datetimepicker.css')?>" rel="stylesheet">
    <link href="<?=iasset('bootstrap-select.min.css')?>" rel="stylesheet">
    <link href="<?=iasset('fontawesome-iconpicker.min.css')?>" rel="stylesheet">
    <link href="<?=iasset('fileinput.min.css')?>" rel="stylesheet">
    <link href="<?=basepath()?>/Adive/Internal/codemirror/lib/codemirror.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="<?=iasset('admin.css')?>" rel="stylesheet">
    <link href="<?=iasset('sb-admin.css')?>" rel="stylesheet">
    <link href="<?=iasset('summernote.css')?>" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="<?=iasset('font-awesome.min.css')?>" rel="stylesheet" type="text/css">

    <!-- jQuery -->
    <script src="<?=iasset('jquery.min.js')?>"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="<?=iasset('bootstrap.min.js')?>"></script>
    <script src="<?=iasset('moment.js')?>"></script>
    <script src="<?=iasset('bootstrap-datetimepicker.js')?>"></script>
    <script src="<?=iasset('bootstrap-select.min.js')?>"></script>
    <script src="<?=iasset('fontawesome-iconpicker.js')?>"></script>
    <script src="<?=iasset('summernote.js')?>"></script>
    <script src="<?=basepath()?>/Adive/Internal/js/plugins/canvas-to-blob.min.js"></script>
    <script src="<?=iasset('fileinput.min.js')?>"></script>
    <script src="<?=iasset('tabullet.js')?>"></script>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
    <div id="wrapper">
        <!-- Navigation -->
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?=path('adashboard')?>">Adive.</a>
            </div>
            <!-- Top Menu Items -->
            <ul class="nav navbar-right top-nav">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?=$_SESSION['adive.name']?> <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="<?=basepath()?>"><i class="fa fa-fw fa-heartbeat"></i> My Home</a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="<?=path('alogout')?>"><i class="fa fa-fw fa-power-off"></i> Log Out</a>
                        </li>
                    </ul>
                </li>
            </ul>
            <?php
            if($_SESSION['adive.user']=='admin'):
            ?>
            <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav side-nav">
                    <li<?php if(isActive()=='dashboard'){ echo' class="active"'; } ?>>
                        <a href="<?=path('adashboard')?>"><i class="fa fa-fw fa-dashboard"></i> Dashboard</a>
                    </li>
                    <li<?php if(isActive()=='tables'){ echo' class="active"'; } ?>>
                        <a href="<?=path('atables')?>"><i class="fa fa-fw fa-table"></i> Tables</a>
                    </li>
                    <li<?php if(isActive()=='nav'){ echo' class="active"'; } ?>>
                        <a href="<?=path('anav')?>"><i class="fa fa-fw fa-bars"></i> Navigation menu</a>
                    </li>
                    <li<?php if(isActive()=='invoke'){ echo' class="active"'; } ?>>
                        <a href="<?=path('invoke')?>"><i class="fa fa-fw fa-heartbeat"></i> Invoke API</a>
                    </li>
                    <li<?php if(isActive()=='config'){ echo' class="active"'; } ?>>
                        <a href="<?=path('config')?>"><i class="fa fa-fw fa-cogs"></i> Configuration</a>
                    </li>
                </ul>
            </div>
            <?php
            else:
            ?>
            <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav side-nav">
                    <?php
                    // Foreach replication Active parent
                    $pActive=1;
                    $parentActive=null;
                    foreach ($nav as $key => $value) {
                        if($getid==$value['table_id_fk']){
                            // Check active parent
                            $parentActive = $value['parent'];
                        }
                        $pActive++;
                    }
                    // NAV foreach
                    $navCount=1;
                    $entChild=0;
                    foreach ($nav as $key => $value) {
                        
                        // Collapse parent
                        if($parentActive==$value['id']){
                            $classCollapseIn=' in';
                            $classAriaExp=' aria-expanded="true"';
                            $parentActive=null;
                        } else {
                            $classCollapseIn='';
                            $classAriaExp='';
                        }
                        
                    if($value['parent']==null){
                            if($navCount>1 and $entChild==1){
                                echo '
                            </ul>
                        </li>';
                                $entChild=0;
                            }

                    if($value['table_id_fk']==null){
                    ?>
                    <li<?php if($getid==$value['table_id_fk']){ echo' class="active" style="background-color:#000;"'; } ?>>
                        <a href="javascript:void(0);" data-toggle="collapse" data-target="#<?=permalink($value['name'])?>"><i class="fa fa-fw <?=$value['icon']?>"></i> <?=$value['name']?> <i class="fa fa-fw fa-caret-down"></i></a>
                        <ul id="<?=permalink($value['name'])?>" class="collapse<?=$classCollapseIn?>"<?=$classAriaExp?>>       
                    <?php 
                    } else {
                            if(!empty($value['table_id_fk'])){
                                $hrefLink = basePath().'/admin/central/'.$value['table_id_fk'].'/'.permalink($value['name']);
                            } else {
                                $hrefLink = 'javascript:void(0);';
                            }
                    ?>
                    <li<?php if($getid==$value['table_id_fk']){ echo' class="active" style="background-color:#000;"'; } ?>>
                        <a href="<?=$hrefLink?>"><i class="fa fa-fw <?=$value['icon']?>"></i> <?=$value['name']?></a>
                    </li>
                    <?php
                    }
                    } else {
                        $entChild = 1;
                        if(!empty($value['table_id_fk'])){
                            $hrefLink = basePath().'/admin/central/'.$value['table_id_fk'].'/'.permalink($value['name']);
                        } else {
                            $hrefLink = 'javascript:void(0);';
                        }
                        ?>
                        <li<?php if($getid==$value['table_id_fk']){ echo' class="active" style="background-color:#000;"'; } ?>>
                            <a href="<?=$hrefLink?>"><i class="fa fa-fw <?=$value['icon']?>"></i> <?=$value['name']?></a>
                        </li>
                        <?php
                    }
                    $navCount++;
                    }
                    ?>
                </ul>
            </div>
            <?php
            endif;
            ?>
            <!-- /.navbar-collapse -->
        </nav>

        <div id="page-wrapper">
            <div class="container-fluid">
                <?php require_once $_SESSION['path.now'].'.php'; ?>
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper --> 
</body>
</html>
