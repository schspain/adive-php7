<?php
/**
 * WEB Invocation.
 * (c) Ferdinand Martin <https://github.com/ferdinandmartin>
 *
 * WEB Invocation Procedure (Bootstrap)
 * This invocation generates:
 * 1 - Frontend with sections
 * 2 - Backend with new user and dashboard
 * 3 - Contact form & location section
 */

/**
 * Invocation Status Array
 */
$invStatus = array();

/**
 * Datetime Set
 */
$dateTime = date('Y-m-d H:i:s');

/**
 * STEP 1: Update Database
 */
$adiveUpdate = $db->prepare("
CREATE TABLE `sections_sec` (
  `id` int(6) NOT NULL,
  `name` varchar(200) DEFAULT NULL COMMENT 'Section Name',
  `content` text COMMENT 'Section Content',
  `url` varchar(200) DEFAULT NULL COMMENT 'Section URL',
  `seo_title` varchar(200) DEFAULT NULL COMMENT 'Head SEO Title',
  `seo_description` tinytext COMMENT 'Head SEO Description',
  `seo_h1` varchar(200) DEFAULT NULL COMMENT 'Body H1 Tag',
  `seo_h2` varchar(200) DEFAULT NULL COMMENT 'Body H2 Tag',
  `creation_date` datetime DEFAULT NULL COMMENT 'Creation Date'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Web Sections';

ALTER TABLE `sections_sec`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `sections_sec`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT;
  
INSERT INTO `adive_tables` (`id`, `name`, `description`, `win_name`, `win_description`, `table_name_field`, `creationDate`) VALUES
(765, 'sections_sec', 'Web Sections', 'Sections', 'Web Sections', 'name', '".$dateTime."');
  
INSERT INTO `adive_fields` (`table_id_fk`, `name`, `comment`, `win_name`, `win_description`, `win_order`, `win_type`, `win_code`, `creationDate`, `author`) VALUES
(765, 'name', 'Section Name', 'Name', 'Section Name', 10, 1, '', '".$dateTime."', 'admin'),
(765, 'content', 'Section Content', 'Content', 'Section Content (HTML)', 20, 3, '', '".$dateTime."', 'admin'),
(765, 'url', 'Section URL', 'Permalink', 'Section URL Permalink', 30, 1, '', '".$dateTime."', 'admin'),
(765, 'seo_title', 'Head SEO Title', 'SEO Title', 'SEO Title in Head', 50, 1, '', '".$dateTime."', 'admin'),
(765, 'seo_description', 'Head SEO Description', 'SEO Description', 'SEO-META Description in Head', 60, 2, '', '".$dateTime."', 'admin'),
(765, 'seo_h1', 'Body H1 Tag', 'SEO H1', 'Body H1 Tag, must be different of Title.', 70, 1, '', '".$dateTime."', 'admin'),
(765, 'seo_h2', 'Body H2 Tag', 'SEO H2', 'Body H2 Tag, optional.', 80, 1, '', '".$dateTime."', 'admin'),
(765, 'creation_date', 'Creation Date', 'Creation Date', 'Creation Date', 90, 7, '', '".$dateTime."', 'admin');

INSERT INTO `adive_users` (`username`, `password`, `name`, `permissions`, `creationDate`, `activeDate`, `invokeType`, `lastInvoke`) VALUES
('web', '".md5('web')."', 'Web Editor', 1, '".date('Y-m-d')." 00:00:00', '0000-00-00 00:00:00', 'web', '0000-00-00 00:00:00');

INSERT INTO `sections_sec` (`id`, `name`, `content`, `url`, `seo_title`, `seo_description`, `seo_h1`, `seo_h2`, `creation_date`) VALUES
(1, 'Company', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'company', 'SEO Title Inlay', 'SEO Description for this test section.', 'My Company', 'Lorem ipsum dolor sit amet.', '".date('Y-m-d')." 00:00:00');");
$adiveUpdate->execute();
$updateResult = $adiveUpdate->fetchAll(PDO::FETCH_ASSOC);
// Creation Status
($updateResult)? $tablesNot = "Ok" : $tablesNot = "Error: ".$adiveUpdate->errorInfo()[2];
$invStatus["Creating necessary tables and users."] = $tablesNot;
        
/**
 * STEP 2: Create necessary files
 * 
 * CSS Library needed
 */
$cssLib1 = '/* Move down content because we have a fixed navbar that is 50px tall */
body {
  padding-top: 50px;
  padding-bottom: 20px;
}';

$filename = 'Views/css/jumbotron.css'
$dirname = dirname($filename);
if (!is_dir($dirname))
{
    mkdir($dirname, 0755, true);
}
$cssFile = fopen($filename, 'w');
fwrite($cssFile, $cssLib1);
fclose($cssFile);
// Creation Status
(file_exists('Views/css/jumbotron.css'))? $cssNot = "Ok" : $cssNot = "Error: File not exists.";
$invStatus["Creating CSS files."] = $cssNot;

/**
 * Contact Form Page
 */
$contactView = <<<'EOD'
<?php
// INPUT REAL
$randA = rand(1,9);
$randB = rand(1,9);
?>
<div class="row">
  <div class="col-md-12">
    
    <?php if (isset($flash['error'])): ?>
        <div class="alert alert-danger"><span class="glyphicon glyphicon-alert"></span><strong> <?php echo $flash['error'] ?></strong></div>
    <?php endif;
          if (isset($flash['message'])): ?>
        <div class="alert alert-success"><strong><span class="glyphicon glyphicon-send"></span> <?php echo $flash['message'] ?></strong></div>	 
    <?php endif; ?>
    
  </div>
  <form role="form" action="" method="post">
    <div class="col-lg-6">
      <div class="well well-sm"><strong><i class="glyphicon glyphicon-ok form-control-feedback"></i> Required Field</strong></div>
      <div class="form-group">
        <label for="InputName">Your Name</label>
        <div class="input-group">
          <input type="text" class="form-control" name="InputName" id="InputName" placeholder="Enter Name" required>
          <span class="input-group-addon"><i class="glyphicon glyphicon-ok form-control-feedback"></i></span></div>
      </div>
      <div class="form-group">
        <label for="InputEmail">Your Email</label>
        <div class="input-group">
          <input type="email" class="form-control" id="InputEmail" name="InputEmail" placeholder="Enter Email" required  >
          <span class="input-group-addon"><i class="glyphicon glyphicon-ok form-control-feedback"></i></span></div>
      </div>
      <div class="form-group">
        <label for="InputMessage">Message</label>
        <div class="input-group">
          <textarea name="InputMessage" id="InputMessage" class="form-control" rows="5" required></textarea>
          <span class="input-group-addon"><i class="glyphicon glyphicon-ok form-control-feedback"></i></span></div>
      </div>
      <div class="form-group">
        <label for="InputReal">What is <?=$randA?>+<?=$randB?>? (Simple Spam Checker)</label>
        <div class="input-group">
          <input type="hidden" value="<?=$randA?>" name="InputRealA" id="InputRealA">
          <input type="hidden" value="<?=$randB?>" name="InputRealB" id="InputRealB">
          <input type="text" class="form-control" name="InputReal" id="InputReal" required>
          <span class="input-group-addon"><i class="glyphicon glyphicon-ok form-control-feedback"></i></span></div>
      </div>
      <input type="submit" name="submit" id="submit" value="Submit" class="btn btn-info pull-right">
    </div>
  </form>
  <hr class="featurette-divider hidden-lg">
  <div class="col-lg-5 col-md-push-1">
    <address>
    <h3>Office Location</h3>
    <p class="lead"><a href="https://www.google.com/maps/preview?ie=UTF-8&q=The+Pentagon&fb=1&gl=us&hq=1400+Defense+Pentagon+Washington,+DC+20301-1400&cid=12647181945379443503&ei=qmYfU4H8LoL2oATa0IHIBg&ved=0CKwBEPwSMAo&safe=on" target="_blank">The Pentagon<br>
Washington, DC 20301</a><br>
      Phone: XXX-XXX-XXXX<br>
      Fax: XXX-XXX-YYYY</p>
    </address>
  </div>
</div>
EOD;
$contactFile = fopen('Views/Default/contact.php', 'w');
fwrite($contactFile, $contactView);
fclose($contactFile);
// Creation Status
(file_exists('Views/Default/contact.php'))? $contactNot = "Ok" : $contactNot = "Error: File not exists.";
$invStatus["Creating Contact page."] = $contactNot;

/**
 * WEB Body index.php
 */
$indexView = <<<'EOD'
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="<?=asset('favicon.ico')?>">
    <title>Jumbotron Template for Bootstrap</title>
    <!-- Bootstrap core CSS -->
    <link href="<?=basePath()?>/Adive/Internal/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?=asset('jumbotron.css')?>" rel="stylesheet">
    
    <!-- Custom Fonts -->
    <link href="<?=basePath()?>/Adive/Internal/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
            <a class="navbar-brand" href="<?=basePath()?>">Company name</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <li<?php if(isActive()=='/'){ echo' class="active"'; } ?>><a href="<?=basePath()?>">Home</a></li>
            <?php foreach ($menu as $key => $link): ?>
            <li<?php if(isActive()==$link['url']){ echo' class="active"'; } ?>><a href="<?=basePath()?>/<?=$link['url']?>"><?=$link['name']?></a></li>
            <?php endforeach; ?>
            <li<?php if(isActive()=='contact'){ echo' class="active"'; } ?>><a href="<?=path('contact')?>">Contact</a></li>
          </ul>
        </div><!--/.navbar-collapse -->
      </div>
    </nav>
      
    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron">
      <div class="container">
        <h1><?=$title?></h1>
        <p><?=$description?></p>
        <p><a class="btn btn-primary btn-lg" href="#" role="button">Learn more &raquo;</a></p>
      </div>
    </div>

    <div class="container">
      <?php if($_SESSION['path.now']=='Default/'): if(empty($section)): ?>
      <!-- Example row of columns -->
      <div class="row">
        <div class="col-md-4">
          <h2>Heading</h2>
          <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
          <p><a class="btn btn-default" href="#" role="button">View details &raquo;</a></p>
        </div>
        <div class="col-md-4">
          <h2>Heading</h2>
          <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
          <p><a class="btn btn-default" href="#" role="button">View details &raquo;</a></p>
       </div>
        <div class="col-md-4">
          <h2>Heading</h2>
          <p>Donec sed odio dui. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulum id ligula porta felis euismod semper. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>
          <p><a class="btn btn-default" href="#" role="button">View details &raquo;</a></p>
        </div>
      </div>
      <?php else: ?>

      <div class="row">
          <div class="col-sm-8">
                <div class="blog-header">
                  <h3 class="blog-title"><?=$section[0]['seo_description']?></h3>
                </div>
                <div class="blog-post">
                    <p><?=$section[0]['content']?></p>
                </div>
          </div>
          <div class="col-sm-3 col-sm-offset-1">
            <div class="sidebar-module sidebar-module-inset">
              <h4>About</h4>
              <p>Etiam porta <em>sem malesuada magna</em> mollis euismod. Cras mattis consectetur purus sit amet fermentum. Aenean lacinia bibendum nulla sed consectetur.</p>
            </div>
          </div>
      </div>

      <?php endif; else: include $_SESSION['path.now'].'.php'; endif; ?>
      <hr>
      
      <footer>
        <p>&copy; 2016 Company, Inc.</p>
      </footer>
    </div> <!-- /container -->

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="<?=iasset('jquery.min.js')?>"></script>
    <script src="<?=iasset('bootstrap.min.js')?>"></script>
  </body>
</html>
EOD;
$indexFile = fopen('Views/index.php', 'w');
fwrite($indexFile, $indexView);
fclose($indexFile);
// Creation Status
(file_exists('Views/index.php'))? $indexNot = "Ok" : $indexNot = "Error: File not exists.";
$invStatus["Creating Index page."] = $indexNot;

/**
 * STEP 3: Update Controller
 *
 * Controller Append to Default Controller
 */
$controllerView = <<<'EOD'
<?php
/*
 * WEB Controller Invocation.
 * (c) Ferdinand Martin <info@adive.es>
 */

/*
 * ************** CAUTION **************
 *
 * THIS FILE WILL BE OVERWRITTEN ON CONTROLLER API INVOCATION
 *
 * ************** CAUTION **************
 */
 
/**
 * @Route(GET) Home Page.
 */
$API->get('/', 
    function() use($API, $db) {
        pathActive('/');
        $menuQ = $db->prepare("SELECT * FROM sections_sec ORDER BY name ASC");
        $menuQ->execute();
        $menu = $menuQ->fetchAll(PDO::FETCH_ASSOC);
            
        $API->render('Default:', array(
            'menu' => $menu,
            'title' => 'Hello, world!',
            'description' => 'This is a template for a simple marketing or informational website. It includes a large callout called a jumbotron and three supporting pieces of content. Use it as a starting point to create something more unique.',
        ));
    }
);

/**
 * @Route(GET) Contact Page.
 */
$API->get('/contact', 
    function() use($API, $db) {
        pathActive('contact');
        $menuQ = $db->prepare("SELECT * FROM sections_sec ORDER BY name ASC");
        $menuQ->execute();
        $menu = $menuQ->fetchAll(PDO::FETCH_ASSOC);

        $API->render('Default:contact', array(
            'menu' => $menu,
            'title' => 'Get in touch',
            'description' => 'Contact form & location',
        ));
    }
)->name('contact');

/**
 * @Route(POST) Contact Page Action
 */
$API->post('/contact', 
    function() use($API, $db) {
        $formData=$API->request;
        $inputA = $formData->post('InputRealA');
        $inputB = $formData->post('InputRealB');
        $inputReal = $formData->post('InputReal');
        
        if($inputA+$inputB==$inputReal){
            $for     = 'nobody@example.com';
            $subject = 'Web Contact Form';
            $message = 'Name: '.$formData->post('InputName').'\n'
                     . 'Email: '.$formData->post('InputEmail').'\n'
                     . 'Message: '.$formData->post('InputMessage').'\n';
            $headers = 'From: webmaster@example.com' . "\r\n" .
                'Reply-To: '.$formData->post('InputEmail').'' . "\r\n" .
                'X-Mailer: PHP/' . phpversion();
            
            // Uncomment the following line to active MAIL function
            //$status = mail($for, $subject, $message, $headers);
            $message = 'Error! Mail has not send, Please check the fields.';
        } else {
            $status = false;
            $message = 'Error! Wrong SPAM Checker. Try it again.';
        }
        ($status)? $API->flash('message','Success! Message sent.')
                 : $API->flash('error',$message);
        
        $API->redirect($API->urlFor('contact'));
    }
);

/**
 * @Route(GET) Website Router Dinamic Pages.
 */
$API->get('/{section}', 
    function($section) use($API, $db) {
        pathActive($section);
        $menuQ = $db->prepare("SELECT * FROM sections_sec ORDER BY name ASC");
        $menuQ->execute();
        $menu = $menuQ->fetchAll(PDO::FETCH_ASSOC);
        
        $sectionQ = $db->prepare("SELECT * FROM sections_sec WHERE url='".$section."'");
        $sectionQ->execute();
        $section = $sectionQ->fetchAll(PDO::FETCH_ASSOC);
        
        $API->render('Default:', array(
            'menu' => $menu,
            'section' => $section,
            'title' => $section[0]['seo_h1'],
            'description' => $section[0]['seo_h2'],
        ));
    }
);
EOD;

$controllerFile = fopen('Controller/Default.php', 'w');
fwrite($controllerFile, $controllerView);
fclose($controllerFile);
// Creation Status
(file_exists('Controller/Default.php'))? $contNot = "Ok" : $contNot = "Error: File not exists.";
$invStatus["Updating Default Controller."] = $contNot;
