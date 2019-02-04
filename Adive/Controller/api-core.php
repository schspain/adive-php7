<?php
/*
 * This file is part of the Adive package.
 *
 * (c) Ferdinand Martin <info@adive.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

// @Route(GET)
$API->get('/admin/login', 
    function() use($API) {
        $API->render('Adive/Internal/Views:login', array(
            'title' => 'Login',
            'description' => 'Welcome to Adive Dashboard.',
            'error' => 'no',
        ));
    }
)->name('alogin');

// @Route(POST)
$API->post('/admin/login', 
    function() use($API, $db) {
        $error='no';
        $formData=$API->request;
        $qLogin=$db->prepare("SELECT * FROM adive_users WHERE username=:user AND password=:password");
        $qLogin->execute(array(
                ':user'=> $formData->post('user'),
                ':password'=> md5($formData->post('password'))
                ));
        
        ($qLogin->rowCount()==1)? $API->flash('message','Login correct')
                                : $API->flash('error','Wrong login details, please try it again.');
        
        if($qLogin->rowCount()==1){
            $resultLogin = $qLogin->fetchAll(PDO::FETCH_ASSOC);
            
            $_SESSION['adive.id']=$resultLogin[0]['id'];
            $_SESSION['adive.user']=$resultLogin[0]['username'];
            $_SESSION['adive.name']=$resultLogin[0]['name'];
            $_SESSION['adive.permissions']=$resultLogin[0]['permissions'];
            
            $API->redirect($API->urlFor('adashboard'));
        } else {
            $error='Wrong login details, try it again.';
        }
        
        $API->render('Adive/Internal/Views:login', array(
            'title' => 'Login',
            'description' => 'Welcome to Adive Dashboard.',
            'error' => $error,
        ));
    }
);

// @Route(GET)
$API->get('/admin/logout', 
    function() use($API) {
        session_destroy();
        $API->redirect(basePath());
    }
)->name('alogout');

// @Route(GET)
$API->get('/admin/dashboard', 
    function() use($API, $db) {
        if(!isset($_SESSION['adive.id'])){ $API->redirect($API->urlFor('alogin')); }
            pathActive('dashboard');
            
            if($_SESSION['adive.user']!='admin'):
                $navMQuery = $db->prepare("SELECT c.*, CASE WHEN isnull(is_par.id) THEN c.linkorder ELSE is_par.linkorder END as sort_order,
                                            CASE WHEN isnull(is_par.id) THEN c.id ELSE is_par.id END as catid ,
                                            CASE WHEN isnull(is_par.id) THEN 0 ELSE c.linkorder END as subcat_order
                                        FROM `adive_nav` c
                                        LEFT JOIN `adive_nav` is_par ON is_par.id = c.parent
                                        ORDER BY  sort_order , catid, subcat_order");
                $navMQuery->execute();
                $menuNav = $navMQuery->fetchAll(PDO::FETCH_ASSOC);
            
                $tablesQuery = $db->prepare("SELECT * FROM adive_tables ORDER by name ASC");
                $tablesQuery->execute();
                $resTables = $tablesQuery->fetchAll(PDO::FETCH_ASSOC);
                $dashboardPage = 'dashboardPublic';
            else:
                $resTables = null;
                $menuNav = null;
                $dashboardPage = 'dashboard';
            endif;
            
            $API->render('Adive/Internal/Views:'.$dashboardPage, array(
                'title' => 'Dashboard',
                'description' => 'Welcome to Adive Dashboard.',
                'mtables' => $resTables,
                'nav' => $menuNav,
                'getid' => 999
            ));
    }
)->name('adashboard');

// @Route(GET)
$API->get('/configure', 
    function() use($API) {
        // Optional: define a menu father
        pathActive('/configure');
        // replace this example code with whatever you need
        $API->render('Adive/Internal/Views:configuration', array(
            'title' => 'Setup, Welcome to Adive!',
            'ok' => 'no',
            'error' => 'no',
        ));
    }
)->name('configure');

$API->post('/configure', 
    function() use($API) {
    
        $formData=$API->request;
        $errorCon = false;
        //Trying to connect to MySQL Server
        try{
        $db = new PDO('mysql:host=' . $formData->post('host') . 
                     ';charset=utf8', $formData->post('user'), $formData->post('password'),
                     array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        }
        catch(PDOException $ex){
            $errorCon = true;
        }
        
        if ($errorCon==false) {

        $queryDB = $db->prepare("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '".mb_strtolower($formData->post('name'),'UTF-8')."'");
        $queryDB->execute();
        
        if($queryDB->rowCount() == 1){
            $databaseName=mb_strtolower($formData->post('name'),'UTF-8');         
        } else {
            //$databaseName='adive.'.date('Ymd').'.01';
            $databaseName=mb_strtolower($formData->post('name'),'UTF-8');     
            $queryDBCreation = $db->prepare("CREATE DATABASE ".$databaseName." CHARACTER SET utf8 COLLATE utf8_general_ci;");
            $queryDBCreation->execute();
        }
		
		$db = new PDO('mysql:host=' . $formData->post('host') . 
					 ';dbname=' . $databaseName . 
                     ';charset=utf8', $formData->post('user'), $formData->post('password'),
                     array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        
        $queryDBTables = $db->prepare("CREATE TABLE `adive_events` (
  `id` bigint(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `time` varchar(15) NOT NULL,
  `type` varchar(15) NOT NULL,
  `code` text NOT NULL,
  `table_id_fk` bigint(20) NOT NULL,
  `creationDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `adive_fields` (
  `id` bigint(20) NOT NULL,
  `table_id_fk` bigint(20) NOT NULL,
  `name` tinytext NOT NULL,
  `comment` text NOT NULL,
  `win_name` tinytext NOT NULL,
  `win_description` tinytext NOT NULL,
  `win_order` int(11) NOT NULL,
  `win_type` int(11) NOT NULL,
  `win_code` text NOT NULL,
  `creationDate` datetime NOT NULL,
  `author` tinytext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `adive_nav` (
  `id` bigint(20) NOT NULL,
  `name` varchar(90) NOT NULL,
  `description` tinytext NOT NULL,
  `icon` varchar(100) NOT NULL,
  `table_id_fk` bigint(20) DEFAULT NULL,
  `parent` int(11) DEFAULT NULL,
  `creationDate` datetime NOT NULL,
  `linkorder` int(11) NOT NULL COMMENT 'Nav link order'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Navigation Menu';


CREATE TABLE `adive_tables` (
  `id` bigint(20) NOT NULL,
  `name` tinytext NOT NULL,
  `description` tinytext NOT NULL,
  `win_name` tinytext NOT NULL,
  `win_description` tinytext NOT NULL,
  `table_name_field` tinytext NOT NULL,
  `creationDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `adive_users` (
  `id` int(11) NOT NULL,
  `username` tinytext NOT NULL,
  `password` tinytext NOT NULL,
  `name` tinytext NOT NULL,
  `permissions` int(11) NOT NULL,
  `creationDate` datetime NOT NULL,
  `activeDate` datetime NOT NULL,
  `invokeType` varchar(25) NOT NULL,
  `lastInvoke` datetime NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `adive_users` (`id`, `username`, `password`, `name`, `permissions`, `creationDate`, `activeDate`, `invokeType`) VALUES
(1, 'admin', '".md5('admin')."', 'Administrator', 1, '".date('Y-m-d')." 00:00:00', '".date('Y-m-d')." 00:00:00', 'app'),
(2, 'user', '".md5('user')."', 'User', 3, '".date('Y-m-d')." 00:00:00', '".date('Y-m-d')." 00:00:00', 'app');
    
ALTER TABLE `adive_events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `table_id_fk` (`table_id_fk`);

ALTER TABLE `adive_fields`
  ADD PRIMARY KEY (`id`),
  ADD KEY `table_id_fk` (`table_id_fk`);

ALTER TABLE `adive_nav`
  ADD PRIMARY KEY (`id`),
  ADD KEY `table_id_fk` (`table_id_fk`);

ALTER TABLE `adive_tables`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `adive_users`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `adive_events`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

ALTER TABLE `adive_fields`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

ALTER TABLE `adive_nav`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

ALTER TABLE `adive_tables`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

ALTER TABLE `adive_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

ALTER TABLE `adive_events`
  ADD CONSTRAINT `tables_events_constraint` FOREIGN KEY (`table_id_fk`) REFERENCES `adive_tables` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `adive_fields`
  ADD CONSTRAINT `tableid_fieldtableid` FOREIGN KEY (`table_id_fk`) REFERENCES `adive_tables` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `adive_nav`
  ADD CONSTRAINT `navigation_tables_const` FOREIGN KEY (`table_id_fk`) REFERENCES `adive_tables` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;
  
CREATE TRIGGER `linkOrderNumber` BEFORE INSERT ON `adive_nav` FOR EACH ROW BEGIN
	DECLARE lastOrder INT DEFAULT 0;
    
    IF NEW.parent IS NULL THEN
      SELECT linkorder INTO lastOrder FROM adive_nav WHERE parent IS NULL ORDER by linkorder DESC LIMIT 1;
    ELSE 
      SELECT linkorder INTO lastOrder FROM adive_nav WHERE parent = NEW.parent ORDER by linkorder DESC LIMIT 1;
    END IF;
    
    SET NEW.linkorder = lastOrder + 1;
END ;

CREATE TRIGGER `linkOrderNumberUpdate` BEFORE UPDATE ON `adive_nav` FOR EACH ROW BEGIN
	DECLARE lastOrder INT DEFAULT 0;
    
    IF NOT NEW.parent <=> OLD.parent THEN
      IF NEW.parent IS NULL THEN
        SELECT linkorder INTO lastOrder FROM adive_nav WHERE parent IS NULL ORDER by linkorder DESC LIMIT 1;
      ELSE 
        SELECT linkorder INTO lastOrder FROM adive_nav WHERE parent = NEW.parent ORDER by linkorder DESC LIMIT 1;
      END IF;
      
      SET NEW.linkorder = lastOrder + 1;
    END IF;
END ;");
        $queryDBTables->execute();
        
        $contentConfig = '<?php
/*
 * ************** CAUTION **************
 *
 * DO NOT EDIT THIS FILE as it will be overridden by Central as part of
 * the installation/update process.
 *
 * ************** CAUTION **************
 */

$API->config(array(
    \'database.host\' => \''.$formData->post('host').'\',
    \'database.name\' => \''.$databaseName.'\',
    \'database.user\' => \''.$formData->post('user').'\',
    \'database.pass\' => \''.$formData->post('password').'\'
));

// Adive Framework Content Type
$API->contentType(\'text/html; charset=utf-8\');

// PDO Database connection
if($API->config(\'database.host\')!=\'your_host\'){
$db = new PDO(\'mysql:host=\' . $API->config(\'database.host\') . 
             \';dbname=\' . $API->config(\'database.name\') . 
             \';charset=utf8\', $API->config(\'database.user\'), $API->config(\'database.pass\')); 
} else {
$db= null;
}';
             
        $fichero = 'Adive/Config/Config.php';
        // Escribe el contenido al fichero
        file_put_contents($fichero, $contentConfig);

        // replace this example code with whatever you need
        $API->render('Adive/Internal/Views:configuration', array(
            'title' => 'Adive configured!',
            'ok' => 'ok',
            'error' => 'no',
        ));
        } else {
            $API->render('Adive/Internal/Views:configuration', array(
                'title' => 'Unable to connect to Database, Adive Framework',
                'ok' => 'no',
                'error' => 'Unable to connect to MySQL server in localhost, check your user and pass details.',
            ));
        }
    }
);


// @Route(GET)
$API->get('/admin/invoke', 
    function() use($API, $db) {
	    if(!isset($_SESSION['adive.id'])){ $API->redirect($API->urlFor('alogin')); }
            pathActive('invoke');
        
            $API->render('Adive/Internal/Views:invoke', array(
                'title' => 'API Invocation',
                'description' => 'Generate your complete running API.'
            ));
    }
)->name('invoke');

// @Route(GET)
$API->get('/admin/invoke/{type}/start', 
    function($type) use($API, $db) {
	    if(!isset($_SESSION['adive.id'])){ $API->redirect($API->urlFor('alogin')); }
            pathActive('invoke');
            
            if($type=='full'){
                $name='Full API invocation';
                $description='Complete invocation, all in /Views and /Controller will be overwrite.';
                $question='Invocation will overwrite all the files in /Controller and /Views';
            }
            if($type=='views'){
                $name='Views API invocation';
                $description='Views only invocation, all in /Views will be overwrite.';
                $question='Invocation will overwrite all the files in /Views';
            }
            if($type=='controller'){
                $name='Controller API invocation';
                $description='Controller only invocation, all in /Controller will be overwrite.';
                $question='Invocation will overwrite all the files in /Controller';
            }
            
            $qUser=$db->prepare("SELECT * FROM adive_users WHERE id=:id");
            $qUser->execute(array(
                    ':id'=> $_SESSION['adive.id']
                    ));

            $resultUser = $qUser->fetchAll(PDO::FETCH_ASSOC);
        
            $API->render('Adive/Internal/Views:invokeStart', array(
                'title' => 'API Invocation',
                'description' => 'Start with full API Invocation.',
                'type' => $type,
                'name' => $name,
                'description' => $description,
                'question' => $question,
                'defaultInvoke' => $resultUser[0]['invokeType']
            ));
    }
);

// @Route(POST) Invocation PROCESS
$API->post('/admin/invoke/{type}/start', 
    function($type) use($API, $db) {
	    if(!isset($_SESSION['adive.id'])){ $API->redirect($API->urlFor('alogin')); }
            pathActive('invoke');
            $invokeData=$API->request;
            $invocationFile = $invokeData->post('invokeType');
            
            // Loading Invocation resources
            require_once 'Adive/Invocations/'.$invocationFile.'.php';

            foreach (invocations() as $key => $inv) {
                if($key==$invocationFile){
                    $nameInvocation=$inv;
                    break;
                }
            }
            $API->flash('message','All files created: '.$nameInvocation);
            $API->flash('status',$invStatus);
            $API->redirect(basePath().'/admin/invoke/'.$type.'/end');
    }
);

// @Route(GET)
$API->get('/admin/invoke/{type}/end', 
    function($type) use($API, $db) {
	    if(!isset($_SESSION['adive.id'])){ $API->redirect($API->urlFor('alogin')); }
            pathActive('invoke');
            
            if($type=='full'){
                $name='Full API invocation finished!';
                $description='Invocation finished.';
            }
            if($type=='views'){
                $name='Views API invocation finished!';
                $description='Invocation finished.';
            }
            if($type=='controller'){
                $name='Controller API invocation finished!';
                $description='Invocation finished.';
            }

            $API->render('Adive/Internal/Views:invokeResult', array(
                'title' => 'API Invocation',
                'description' => 'Invocation finished.',
                'type' => $type,
                'name' => $name,
                'description' => $description,
            ));
    }
);

// @Route(GET)
$API->get('/admin/documentation', 
    function() use($API) {
            pathActive('documentation');
        
            $API->render('Adive/Internal/Views:documentation', array(
                'title' => 'Adive Documentation',
                'description' => 'Learn to use Adive, Although you don\'t really need it..',
            ));
    }
)->name('documentation-adive');


// @Route(GET) CENTRAL Router
$API->get('/admin/central/{id}/{permalink}', 
    function($id, $permalink) use($API, $db) {
	    if(!isset($_SESSION['adive.id'])){ $API->redirect($API->urlFor('alogin')); }
            pathActive($permalink);
            
            $navMQuery = $db->prepare("SELECT c.*, CASE WHEN isnull(is_par.id) THEN c.linkorder ELSE is_par.linkorder END as sort_order,
                                            CASE WHEN isnull(is_par.id) THEN c.id ELSE is_par.id END as catid ,
                                            CASE WHEN isnull(is_par.id) THEN 0 ELSE c.linkorder END as subcat_order
                                        FROM `adive_nav` c
                                        LEFT JOIN `adive_nav` is_par ON is_par.id = c.parent
                                        ORDER BY  sort_order , catid, subcat_order");
            $navMQuery->execute();
            $menuNav = $navMQuery->fetchAll(PDO::FETCH_ASSOC);
            
            $tablesMQuery = $db->prepare("SELECT * FROM adive_tables ORDER by name ASC");
            $tablesMQuery->execute();
            $menuTables = $tablesMQuery->fetchAll(PDO::FETCH_ASSOC);
            
            $tablesQuery = $db->prepare("SELECT * FROM adive_tables WHERE id=:id ORDER by name ASC");
            $tablesQuery->execute(array('id'=>$id));
            $resTables = $tablesQuery->fetchAll(PDO::FETCH_ASSOC);
            
            $fieldsQuery = $db->prepare("SELECT * FROM adive_fields WHERE table_id_fk=:id ORDER by win_order ASC");
            $fieldsQuery->execute(array('id'=>$id));
            $resFields = $fieldsQuery->fetchAll(PDO::FETCH_ASSOC);
            
            $listQuery = $db->prepare("SELECT * FROM ".$resTables[0]['name']." ORDER by id DESC");
            $listQuery->execute(array('tabid'=>$id));
            $resList = $listQuery->fetchAll(PDO::FETCH_ASSOC);
	    
	    $iconQuery = $db->prepare("SELECT * FROM adive_nav WHERE table_id_fk=:id ORDER by name ASC");
            $iconQuery->execute(array('id'=>$id));
            $resIcon = $iconQuery->fetchAll(PDO::FETCH_ASSOC);
        
            $API->render('Adive/Internal/Views:centralList', array(
                'title' => $resTables[0]['win_name'],
                'description' => $resTables[0]['win_description'],
                'mtables' => $menuTables,
                'tables' => $resTables,
                'table_id' => $resTables[0]['id'],
                'fields' => $resFields,
                'list' => $resList,
                'nav' => $menuNav,
                'getid' => $id,
		'icon' => $resIcon[0]['icon']
            ));
    }
);

// @Route(GET) CENTRAL Form
$API->get('/admin/central/add/{id}/{permalink}', 
    function($id, $permalink) use($API, $db) {
	    if(!isset($_SESSION['adive.id'])){ $API->redirect($API->urlFor('alogin')); }
            pathActive($permalink);
            
            $navMQuery = $db->prepare("SELECT c.*,
                                            CASE WHEN isnull(is_par.id) THEN c.linkorder ELSE is_par.linkorder END as sort_order,
                                            CASE WHEN isnull(is_par.id) THEN c.id ELSE is_par.id END as catid ,
                                            CASE WHEN isnull(is_par.id) THEN 0 ELSE c.linkorder END as subcat_order
                                        FROM `adive_nav` c
                                        LEFT JOIN `adive_nav` is_par ON is_par.id = c.parent
                                        ORDER BY  sort_order , catid, subcat_order");
            $navMQuery->execute();
            $menuNav = $navMQuery->fetchAll(PDO::FETCH_ASSOC);
            
            $tablesMQuery = $db->prepare("SELECT * FROM adive_tables ORDER by name ASC");
            $tablesMQuery->execute();
            $menuTables = $tablesMQuery->fetchAll(PDO::FETCH_ASSOC);
            
            $tablesQuery = $db->prepare("SELECT * FROM adive_tables WHERE id=:id ORDER by name ASC");
            $tablesQuery->execute(array('id'=>$id));
            $resTables = $tablesQuery->fetchAll(PDO::FETCH_ASSOC);
            
            $fieldsQuery = $db->prepare("SELECT * FROM adive_fields WHERE table_id_fk=:id ORDER by win_order ASC");
            $fieldsQuery->execute(array('id'=>$id));
            $resFields = $fieldsQuery->fetchAll(PDO::FETCH_ASSOC);
            
            $listQuery = $db->prepare("SELECT * FROM ".$resTables[0]['name']." ORDER by id DESC");
            $listQuery->execute(array('tabid'=>$id));
            $resList = $listQuery->fetchAll(PDO::FETCH_ASSOC);
	    
	    $iconQuery = $db->prepare("SELECT * FROM adive_nav WHERE table_id_fk=:id ORDER by name ASC");
            $iconQuery->execute(array('id'=>$id));
            $resIcon = $iconQuery->fetchAll(PDO::FETCH_ASSOC);
        
            $API->render('Adive/Internal/Views:centralForm', array(
                'title' => 'Create new: '.$resTables[0]['win_name'],
                'description' => $resTables[0]['win_description'],
                'mtables' => $menuTables,
                'tables' => $resTables,
                'table_id' => $resTables[0]['id'],
                'fields' => $resFields,
                'list' => $resList,
                'nav' => $menuNav,
                'getid' => $id,
		'icon' => $resIcon[0]['icon']
            ));
    }
);

// @Route(POST) CENTRAL Form Action
$API->post('/admin/central/add/{id}/{permalink}', 
    function($id, $permalink) use($API, $db) {
	if(!isset($_SESSION['adive.id'])){ $API->redirect($API->urlFor('alogin')); }
        pathActive($permalink);
        $formData=$API->request;
        
        $tablesQuery = $db->prepare("SELECT * FROM adive_tables WHERE id=:id ORDER by name ASC");
        $tablesQuery->execute(array('id'=>$id));
        $resTables = $tablesQuery->fetchAll(PDO::FETCH_ASSOC);
        
        $fieldsQuery = $db->prepare("SELECT * FROM adive_fields WHERE table_id_fk=:id ORDER by win_order ASC");
        $fieldsQuery->execute(array('id'=>$id));
        $resFields = $fieldsQuery->fetchAll(PDO::FETCH_ASSOC);
        //Query String creation
        $linQuery='';
        $linValues='';
        $nF=1;
        $aF=array();
        foreach ($resFields as $key => $fie) {
            if(substr($fie['name'], -4) != '_rel') {
            if($nF==1){
	    //$linQuery.=$fie['name'].'=:'.$fie['name'];
	    $postData = $formData->post('field_'.$fie['name']);
	    $postData = str_replace('C:\fakepath\\','',$postData);
	    $linQuery.=$fie['name'];
	    $linValues.=':'.$fie['name'];
	    $aF[$fie['name']] = $postData;
            } else {
	    //$linQuery.=','.$fie['name'].'=:'.$fie['name'];
	    $postData = $formData->post('field_'.$fie['name']);
	    $postData = str_replace('C:\fakepath\\','',$postData);
	    $linQuery.=','.$fie['name'];
	    $linValues.=',:'.$fie['name'];
	    $aF[$fie['name']] = $postData;
            }
            $nF++;
            }
        }

        //$queryInsert=$db->prepare("UPDATE ".$resTables[0]['name']." SET ".$linQuery." WHERE id=:id");
        $queryInsert=$db->prepare("INSERT INTO ".$resTables[0]['name']."(".$linQuery.") values (".$linValues.");");
        $status=$queryInsert->execute($aF);

        ($status)? $API->flash('message','Entry created.')
             : $API->flash('error','Exception detected: '.$queryInsert->errorInfo()[2].'.');
        
        ($status)? $API->redirect(basePath().'/admin/central/'.$id.'/'.$permalink)
                 : $API->redirect(basePath().'/admin/central/add/'.$id.'/'.$permalink);
    }
);


// @Route(GET) CENTRAL Edit Form
$API->get('/admin/central/edit/{id}/{id_table}/{permalink}', 
    function($id, $idTable, $permalink) use($API, $db) {
	    if(!isset($_SESSION['adive.id'])){ $API->redirect($API->urlFor('alogin')); }
            pathActive($permalink);
            
            $navMQuery = $db->prepare("SELECT c.*,
                                            CASE WHEN isnull(is_par.id) THEN c.linkorder ELSE is_par.linkorder END as sort_order,
                                            CASE WHEN isnull(is_par.id) THEN c.id ELSE is_par.id END as catid ,
                                            CASE WHEN isnull(is_par.id) THEN 0 ELSE c.linkorder END as subcat_order
                                        FROM `adive_nav` c
                                        LEFT JOIN `adive_nav` is_par ON is_par.id = c.parent
                                        ORDER BY  sort_order , catid, subcat_order");
            $navMQuery->execute();
            $menuNav = $navMQuery->fetchAll(PDO::FETCH_ASSOC);
            
            $tablesMQuery = $db->prepare("SELECT * FROM adive_tables ORDER by name ASC");
            $tablesMQuery->execute();
            $menuTables = $tablesMQuery->fetchAll(PDO::FETCH_ASSOC);
            
            $tablesQuery = $db->prepare("SELECT * FROM adive_tables WHERE id=:id ORDER by name ASC");
            $tablesQuery->execute(array('id'=>$idTable));
            $resTables = $tablesQuery->fetchAll(PDO::FETCH_ASSOC);
            
            $fieldsQuery = $db->prepare("SELECT * FROM adive_fields WHERE table_id_fk=:id ORDER by win_order ASC");
            $fieldsQuery->execute(array('id'=>$idTable));
            $resFields = $fieldsQuery->fetchAll(PDO::FETCH_ASSOC);
            
            $listQuery = $db->prepare("SELECT * FROM ".$resTables[0]['name']." WHERE id=:id ORDER by id DESC");
            $listQuery->execute(array('id'=>$id));
            $resList = $listQuery->fetchAll(PDO::FETCH_ASSOC);
	    
	    $iconQuery = $db->prepare("SELECT * FROM adive_nav WHERE table_id_fk=:id ORDER by name ASC");
            $iconQuery->execute(array('id'=>$idTable));
            $resIcon = $iconQuery->fetchAll(PDO::FETCH_ASSOC);
        
            $API->render('Adive/Internal/Views:centralEditForm', array(
                'title' => 'Update '.$resList[0][$resTables[0]['table_name_field']],
                'description' => $resTables[0]['win_name'],
                'mtables' => $menuTables,
                'tables' => $resTables,
                'table_id' => $resTables[0]['id'],
                'fields' => $resFields,
                'list' => $resList,
                'nav' => $menuNav,
                'getid' => $idTable,
		'icon' => $resIcon[0]['icon']
            ));
    }
);

// @Route(POST) CENTRAL Form Action
$API->post('/admin/central/edit/{id}/{id_table}/{permalink}', 
    function($id, $idTable, $permalink) use($API, $db) {
	if(!isset($_SESSION['adive.id'])){ $API->redirect($API->urlFor('alogin')); }
        pathActive($permalink);
        $formData=$API->request;
        
        $tablesQuery = $db->prepare("SELECT * FROM adive_tables WHERE id=:id ORDER by name ASC");
        $tablesQuery->execute(array('id'=>$idTable));
        $resTables = $tablesQuery->fetchAll(PDO::FETCH_ASSOC);
        
        $fieldsQuery = $db->prepare("SELECT * FROM adive_fields WHERE table_id_fk=:id ORDER by win_order ASC");
        $fieldsQuery->execute(array('id'=>$idTable));
        $resFields = $fieldsQuery->fetchAll(PDO::FETCH_ASSOC);
        //Query String creation
        $linQuery='';
        $nF=1;
        $aF=array();
        $aF['id'] = $id;
        foreach ($resFields as $key => $fie) {
            if(substr($fie['name'], -4) != '_rel') {
            if($nF==1){
            $postData = $formData->post('field_'.$fie['name']);
            $postData = str_replace('C:\fakepath\\','',$postData);
            $linQuery.=$fie['name'].'=:'.$fie['name'];
            $aF[$fie['name']] = $postData;
            } else {
            $postData = $formData->post('field_'.$fie['name']);
	    $postData = str_replace('C:\fakepath\\','',$postData);
            $linQuery.=','.$fie['name'].'=:'.$fie['name'];
            $aF[$fie['name']] = $postData;
            }
            $nF++;
            }
        }

        $queryUpdate=$db->prepare("UPDATE ".$resTables[0]['name']." SET ".$linQuery." WHERE id=:id");
        $status=$queryUpdate->execute($aF);

        ($status)? $API->flash('message','Entry updated.')
             : $API->flash('error','Exception detected: '.$queryUpdate->errorInfo()[2].'.');
        
        ($status)? $API->redirect(basePath().'/admin/central/'.$idTable.'/'.$permalink)
                 : $API->redirect(basePath().'/admin/central/edit/'.$id.'/'.$idTable.'/'.$permalink);
    }
);

// @Route(GET) CENTRAL Form Action
$API->get('/admin/central/delete/{id}/{id_table}/{permalink}', 
    function($id, $idTable, $permalink) use($API, $db) {
	if(!isset($_SESSION['adive.id'])){ $API->redirect($API->urlFor('alogin')); }
        pathActive($permalink);
        
        $tablesQuery = $db->prepare("SELECT * FROM adive_tables WHERE id=:id ORDER by name ASC");
        $tablesQuery->execute(array('id'=>$idTable));
        $resTables = $tablesQuery->fetchAll(PDO::FETCH_ASSOC);
        
        $fieldsQuery = $db->prepare("SELECT * FROM adive_fields WHERE table_id_fk=:id ORDER by win_order ASC");
        $fieldsQuery->execute(array('id'=>$idTable));
        $resFields = $fieldsQuery->fetchAll(PDO::FETCH_ASSOC);

        $queryDelete=$db->prepare("DELETE FROM ".$resTables[0]['name']." WHERE id=:id");
        $status=$queryDelete->execute(array('id'=>$id));

        ($status)? $API->flash('message','Entry deleted successfuly.')
                 : $API->flash('error','Exception detected: '.$queryDelete->errorInfo()[2].'.');
        
        ($status)? $API->redirect(basePath().'/admin/central/'.$idTable.'/'.$permalink)
                 : $API->redirect(basePath().'/admin/central/'.$idTable.'/'.$permalink);
    }
);
