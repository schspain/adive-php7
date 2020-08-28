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
$API->get('/admin/nav', 
    function() use($API, $db) {
        if(!isset($_SESSION['adive.id']) OR $_SESSION['site.hash']!=$API->config('site.hash')){$API->redirect($API->urlFor('alogin'));}
            pathActive('nav');
            $tablesQuery = $db->prepare('SELECT c.*,
                                            CASE WHEN isnull(is_par.id) THEN c.linkorder ELSE is_par.linkorder END as sort_order,
                                            CASE WHEN isnull(is_par.id) THEN c.id ELSE is_par.id END as catid ,
                                            CASE WHEN isnull(is_par.id) THEN 0 ELSE c.linkorder END as subcat_order
                                        FROM `adive_nav` c
                                        LEFT JOIN `adive_nav` is_par ON is_par.id = c.parent
                                        ORDER BY  sort_order , catid, subcat_order');
            $tablesQuery->execute();
            $resNav = $tablesQuery->fetchAll(PDO::FETCH_ASSOC);
            
            $API->render('Adive/Internal/Views:nav', array(
                'title' => 'Navigation menu',
                'description' => 'Menu options in your APP.',
                'result' => $resNav
            ));
    }
)->name('anav');

// @Route(GET)
$API->get('/admin/nav/add', 
    function() use($API, $db) {
        if(!isset($_SESSION['adive.id']) OR $_SESSION['site.hash']!=$API->config('site.hash')){$API->redirect($API->urlFor('alogin'));}
            pathActive('nav');
            
            $query2 = $db->prepare("SELECT * FROM adive_tables ORDER by id ASC");
            $query2->execute();
            $result2 = $query2->fetchAll(PDO::FETCH_ASSOC);
            
            $query3 = $db->prepare("SELECT * FROM adive_nav ORDER by id ASC");
            $query3->execute();
            $result3 = $query3->fetchAll(PDO::FETCH_ASSOC);
        
            $API->render('Adive/Internal/Views:addNav', array(
                'title' => 'Create new navigation link',
                'description' => 'Complete the details for the new link.',
                'tables' => $result2,
                'nav' => $result3,
            ));
    }
)->name('addNav');

// @Route(POST)
$API->post('/admin/nav/add', 
    function() use($API, $db) {
        if(!isset($_SESSION['adive.id']) OR $_SESSION['site.hash']!=$API->config('site.hash')){$API->redirect($API->urlFor('alogin'));}
        $formData=$API->request;
        $queryInsert=$db->prepare("INSERT INTO adive_nav(name,description,parent,table_id_fk,creationDate,icon)
                                values (:name,:description,:parent,:table_id_fk,:creationDate,:icon);");
        
        ($formData->post('fkTable')=='NULL')? $fkTable = NULL : $fkTable = $formData->post('fkTable');
        ($formData->post('navParent')=='NULL')? $navParent = NULL : $navParent = $formData->post('navParent');
        
        $status=$queryInsert->execute(
                array(
                    ':name'=> $formData->post('navName'),
                    ':description'=> $formData->post('navDescription'),
                    ':parent'=> $navParent,
                    ':table_id_fk'=> $fkTable,
                    ':creationDate'=> date('Y-m-d H:i:s'),
                    ':icon'=> $formData->post('navIcon')
                    )
                );
        
        ($status)? $API->flash('message','Navigation link created.')
                 : $API->flash('error','Exception detected: '.$queryInsert->errorInfo()[2].'.');
        
        ($status)? $API->redirect($API->urlFor('anav'))
                 : $API->redirect($API->urlFor('addNav'));
    }
);

// @Route(GET)
$API->get('/admin/nav/edit/{nav_id}', 
    function($navID) use($API, $db) {
        if(!isset($_SESSION['adive.id']) OR $_SESSION['site.hash']!=$API->config('site.hash')){$API->redirect($API->urlFor('alogin'));}
        pathActive('nav');
        $query1 = $db->prepare("SELECT * FROM adive_nav WHERE id=:navid ORDER by id ASC");
        $query1->execute(array(
            'navid' => $navID
        ));
        $result1 = $query1->fetchAll(PDO::FETCH_ASSOC);
        
        $query2 = $db->prepare("SELECT * FROM adive_tables ORDER by id ASC");
        $query2->execute();
        $result2 = $query2->fetchAll(PDO::FETCH_ASSOC);
        
        $query3 = $db->prepare("SELECT * FROM adive_nav WHERE parent IS NULL ORDER by id ASC");
        $query3->execute();
        $result3 = $query3->fetchAll(PDO::FETCH_ASSOC);
        
        $API->render('Adive/Internal/Views:editNav', array(
            'title' => 'Edit nav link '.$result1[0]['name'],
            'description' => 'Change the details for the nav link '.$result1[0]['name'].'.',
            'nav' => $result1,
            'tables' => $result2,
            'parent' => $result3,
        ));
    }
);

// @Route(POST)
$API->post('/admin/nav/edit/{nav_id}', 
    function($navID) use($API, $db) {
        if(!isset($_SESSION['adive.id']) OR $_SESSION['site.hash']!=$API->config('site.hash')){$API->redirect($API->urlFor('alogin'));}
        pathActive('nav');
        $formData=$API->request;
        // RE-ORDERING NAV
        $query1 = $db->prepare("SELECT * FROM adive_nav WHERE id=:navid ORDER by id ASC");
        $query1->execute(array(
            'navid' => $navID
        ));
        $result1 = $query1->fetchAll(PDO::FETCH_ASSOC);
        
        $queryInsert=$db->prepare("UPDATE adive_nav SET name=:name,description=:description,parent=:parent,table_id_fk=:table_id_fk,icon=:icon WHERE id=:id");
        
        ($formData->post('fkTable')=='NULL')? $fkTable = NULL : $fkTable = $formData->post('fkTable');
        ($formData->post('navParent')=='NULL')? $navParent = NULL : $navParent = $formData->post('navParent');

        $status=$queryInsert->execute(
                array(
                    ':id' => $navID,
                    ':name'=> $formData->post('navName'),
                    ':description'=> $formData->post('navDescription'),
                    ':parent'=> $navParent,
                    ':table_id_fk'=> $fkTable,
                    ':icon'=> $formData->post('navIcon')
                    )
                );
        
        if($result1[0]['parent']!=$formData->post('navParent')){
            if($result1[0]['parent']==null){
                $queryParent = $db->prepare("SELECT * FROM adive_nav WHERE parent IS NULL  ORDER by linkorder ASC");
                $queryParent->execute(array(
                    'oldParent' => $result1[0]['parent']
                ));
            } else {
                $queryParent = $db->prepare("SELECT * FROM adive_nav WHERE parent=:oldParent ORDER by linkorder ASC");
                $queryParent->execute(array(
                    'oldParent' => $result1[0]['parent']
                ));
            }
            $resOldParent = $queryParent->fetchAll(PDO::FETCH_ASSOC);
            $reOrder = 1;
            foreach ($resOldParent as $key => $value) {
                $reOrderParent = $db->prepare("UPDATE adive_nav SET linkorder=:newOrder WHERE id=:navID;");
                $reOrderParent->execute(array(
                    'navID' => $value['id'],
                    'newOrder' => $reOrder
                ));
                $reOrder++;
            }
        }
        
        ($status)? $API->flash('message','Navigation link updated.')
                 : $API->flash('error','Exception detected: '.$queryInsert->errorInfo()[2].'.');
        
        ($status)? $API->redirect($API->urlFor('anav'))
                 : $API->redirect(basePath().'/admin/nav/edit/'.$navID);
    }
);

// @Route(GET) DELETE table
$API->get('/admin/nav/delete/{nav_id}', 
    function($navID) use($API, $db) {
        if(!isset($_SESSION['adive.id']) OR $_SESSION['site.hash']!=$API->config('site.hash')){$API->redirect($API->urlFor('alogin'));}
        pathActive('nav');
        
        $query2 = $db->prepare("DELETE FROM adive_nav WHERE id=:navid");
        $status = $query2->execute(array(
                        'navid' => $navID
                    ));
        
        ($status)? $API->flash('message','Navigation link deleted.')
                 : $API->flash('error','Exception detected: '.$query2->errorInfo()[2].'.');
        
        $API->redirect($API->urlFor('anav'));
    }
);

// @Route(GET) UPDATE order UP
$API->get('/admin/nav/up/{nav_id}', 
    function($navID) use($API, $db) {
        if(!isset($_SESSION['adive.id']) OR $_SESSION['site.hash']!=$API->config('site.hash')){$API->redirect($API->urlFor('alogin'));}
        pathActive('nav');
        $query1 = $db->prepare("SELECT * FROM adive_nav WHERE id=:navid ORDER by id ASC");
        $query1->execute(array(
            'navid' => $navID
        ));
        $result1 = $query1->fetchAll(PDO::FETCH_ASSOC);
        
        if($result1[0]['parent']==null){
            $query2 = $db->prepare("SELECT * FROM adive_nav WHERE linkorder=:lorder AND parent IS NULL LIMIT 1");
            $query2->execute(array(
                'lorder' => ($result1[0]['linkorder']-1),
            ));
        } else {
            $query2 = $db->prepare("SELECT * FROM adive_nav WHERE linkorder=:lorder AND parent=:lparent LIMIT 1");
            $query2->execute(array(
                'lorder' => ($result1[0]['linkorder']-1),
                'lparent' => $result1[0]['parent']
            ));
        }
        $result2 = $query2->fetchAll(PDO::FETCH_ASSOC);
        
        $query3 = $db->prepare("UPDATE adive_nav SET linkorder=linkorder-1 WHERE id=:navid; "
                             . "UPDATE adive_nav SET linkorder=linkorder+1 WHERE id=:prenavid;");
        $status = $query3->execute(array(
                        'navid' => $navID,
                        'prenavid' => $result2[0]['id']
                    ));
        
        ($status)? $API->flash('message','Navigation link ordered.')
                 : $API->flash('error','Exception detected: '.$query2->errorInfo()[2].'.');
        
        $API->redirect($API->urlFor('anav'));
    }
);

// @Route(GET) UPDATE order DOWN
$API->get('/admin/nav/down/{nav_id}', 
    function($navID) use($API, $db) {
        if(!isset($_SESSION['adive.id']) OR $_SESSION['site.hash']!=$API->config('site.hash')){$API->redirect($API->urlFor('alogin'));}
        pathActive('nav');
        $query1 = $db->prepare("SELECT * FROM adive_nav WHERE id=:navid ORDER by id ASC");
        $query1->execute(array(
            'navid' => $navID
        ));
        $result1 = $query1->fetchAll(PDO::FETCH_ASSOC);
        
        if($result1[0]['parent']==null){
            $query2 = $db->prepare("SELECT * FROM adive_nav WHERE linkorder=:lorder AND parent IS NULL LIMIT 1");
            $query2->execute(array(
                'lorder' => ($result1[0]['linkorder']+1),
            ));
        } else {
            $query2 = $db->prepare("SELECT * FROM adive_nav WHERE linkorder=:lorder AND parent=:lparent LIMIT 1");
            $query2->execute(array(
                'lorder' => ($result1[0]['linkorder']+1),
                'lparent' => $result1[0]['parent']
            ));
        }
        $result2 = $query2->fetchAll(PDO::FETCH_ASSOC);
        
        $query3 = $db->prepare("UPDATE adive_nav SET linkorder=linkorder+1 WHERE id=:navid; "
                             . "UPDATE adive_nav SET linkorder=linkorder-1 WHERE id=:prenavid;");
        $status = $query3->execute(array(
                        'navid' => $navID,
                        'prenavid' => $result2[0]['id']
                    ));
        
        ($status)? $API->flash('message','Navigation link ordered.')
                 : $API->flash('error','Exception detected: '.$query2->errorInfo()[2].'.');
        
        $API->redirect($API->urlFor('anav'));
    }
);

// @Route(GET) Configuration
$API->get('/admin/config', 
    function() use($API, $db) {
        if(!isset($_SESSION['adive.id']) OR $_SESSION['site.hash']!=$API->config('site.hash')){$API->redirect($API->urlFor('alogin'));}
            pathActive('config');
            
            $query = $db->prepare("SELECT * FROM adive_users WHERE id=".$_SESSION['adive.id']);
            $query->execute();
            $result = $query->fetchAll(PDO::FETCH_ASSOC);
        
            $API->render('Adive/Internal/Views:config', array(
                'title' => 'Configuration',
                'description' => 'Change Adive configuration details.',
                'conf' => $result,
            ));
    }
)->name('config');

// @Route(POST) Update Configuration
$API->post('/admin/config', 
    function() use($API, $db) {
        if(!isset($_SESSION['adive.id']) OR $_SESSION['site.hash']!=$API->config('site.hash')){$API->redirect($API->urlFor('alogin'));}
        pathActive('config');
        $formData=$API->request;

        $queryInsert=$db->prepare("UPDATE adive_users SET name=:name,permissions=:permissions,invokeType=:invokeType WHERE id=:id");
        $status=$queryInsert->execute(
                array(
                    ':id' => $_SESSION['adive.id'],
                    ':name'=> $formData->post('userName'),
                    ':permissions'=> $formData->post('confPermissions'),
                    ':invokeType'=> $formData->post('invokeType')
                    )
                );
        
        if($formData->post('pass')!=''){
            if($formData->post('pass')==$formData->post('cpass')){
                $queryUpdatePass=$db->prepare("UPDATE adive_users SET password=:pass WHERE id=:id");
                $status=$queryUpdatePass->execute(
                        array(
                            ':id' => $_SESSION['adive.id'],
                            ':pass'=> md5($formData->post('pass'))
                            )
                        );
            } else {
                $status = false;
                $error = 'Passwords does not match';
            }
        }
        
        ($status)? $API->flash('message','Configuration saved.')
                 : $API->flash('error','Exception detected: '.$error.'.');
        
        ($status)? $API->redirect($API->urlFor('config'))
                 : $API->redirect($API->urlFor('config'));
    }
);
