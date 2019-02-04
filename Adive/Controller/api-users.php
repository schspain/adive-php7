<?php
/*
 * This file is part of the Adive package.
 *
 * (c) Ferdinand Martin <info@schben.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

// @Route(GET)
$API->get('/admin/user', 
    function() use($API, $db) {
        if(!isset($_SESSION['adive.id'])){ $API->redirect($API->urlFor('alogin')); }
            pathActive('config');
            $tablesQuery = $db->prepare('SELECT * FROM `adive_users` WHERE permissions != 1 ORDER BY username');
            $tablesQuery->execute();
            $resUser = $tablesQuery->fetchAll(PDO::FETCH_ASSOC);
            
            $API->render('Adive/Internal/Views:users', array(
                'title' => 'Dashboard users',
                'description' => 'Users for public dashboard.',
                'result' => $resUser
            ));
    }
)->name('auser');

// @Route(GET)
$API->get('/admin/user/add', 
    function() use($API, $db) {
        if(!isset($_SESSION['adive.id'])){ $API->redirect($API->urlFor('alogin')); }
            pathActive('config');
            
            $query = $db->prepare("SELECT * FROM adive_users ORDER by id ASC");
            $query->execute();
            $result = $query->fetchAll(PDO::FETCH_ASSOC);
        
            $API->render('Adive/Internal/Views:addUser', array(
                'title' => 'Create a new user',
                'description' => 'Complete the details for the new user.',
                'user' => $result,
            ));
    }
)->name('addUser');

// @Route(POST)
$API->post('/admin/user/add', 
    function() use($API, $db) {
        if(!isset($_SESSION['adive.id'])){ $API->redirect($API->urlFor('alogin')); }
        $formData=$API->request;

        if($formData->post('pass')!=''){
            if($formData->post('pass')==$formData->post('cpass')){
                $status = true;
            } else {
                $status = false;
                $error = 'Passwords does not match';
            }
        }

        ($status)? $foo = 1
                 : $API->flash('error','Exception detected: '.$error.'.');
        
        ($status)? $foo = 1
                 : $API->redirect($API->urlFor('addUser'));

        $queryInsert=$db->prepare("INSERT INTO adive_users(name,username,password,permissions,creationDate,invokeType,activeDate)
                                values (:name,:username,:password,:permissions,:creationDate,:invoke,:activeDate);");
        
        $status=$queryInsert->execute(
                array(
                    ':name'=> $formData->post('userName'),
                    ':username'=> $formData->post('userUsername'),
                    ':password'=> md5($formData->post('pass')),
                    ':permissions'=> $formData->post('permission'),
                    ':invoke'=> 'app',
                    ':creationDate'=> date('Y-m-d H:i:s'),
                    ':activeDate'=> date('Y-m-d H:i:s')
                    )
                );
        
        ($status)? $API->flash('message','User created.')
                 : $API->flash('error','Exception detected: '.$queryInsert->errorInfo()[2].'.');
        
        ($status)? $API->redirect($API->urlFor('auser'))
                 : $API->redirect($API->urlFor('addUser'));
    }
);

// @Route(GET)
$API->get('/admin/user/edit/{user_id}', 
    function($userid) use($API, $db) {
        if(!isset($_SESSION['adive.id'])){ $API->redirect($API->urlFor('alogin')); }
        pathActive('config');
        $query1 = $db->prepare("SELECT * FROM adive_users WHERE id=:userid ORDER by id ASC");
        $query1->execute(array(
            'userid' => $userid
        ));
        $result1 = $query1->fetchAll(PDO::FETCH_ASSOC);
        
        $API->render('Adive/Internal/Views:editUser', array(
            'title' => 'Edit user '.$result1[0]['name'],
            'description' => 'Change the details for the user '.$result1[0]['name'].'.',
            'user' => $result1,
        ));
    }
)->name('editUser');

// @Route(POST)
$API->post('/admin/user/edit/{user_id}', 
    function($userid) use($API, $db) {
        if(!isset($_SESSION['adive.id'])){ $API->redirect($API->urlFor('alogin')); }

        $formData=$API->request;
        
        $queryInsert=$db->prepare("UPDATE adive_users SET name=:name,permissions=:permissions,username=:username WHERE id=:id");
        $status=$queryInsert->execute(
                array(
                    ':id' => $userid,
                    ':name'=> $formData->post('userName'),
                    ':permissions'=> $formData->post('permission'),
                    ':username'=> $formData->post('userUsername')
                    )
                );
        
        if($formData->post('pass')!=''){
            if($formData->post('pass')==$formData->post('cpass')){
                $queryUpdatePass=$db->prepare("UPDATE adive_users SET password=:pass WHERE id=:id");
                $status=$queryUpdatePass->execute(
                        array(
                            ':id' => $userid,
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
        
        ($status)? $API->redirect($API->urlFor('auser'))
                 : $API->redirect(basepath()."/admin/user/edit/".$userid);
    }
);

// @Route(GET) DELETE table
$API->get('/admin/user/delete/{nav_id}', 
    function($userid) use($API, $db) {
        if(!isset($_SESSION['adive.id'])){ $API->redirect($API->urlFor('alogin')); }
        pathActive('config');
        
        $query2 = $db->prepare("DELETE FROM adive_users WHERE id=:userid");
        $status = $query2->execute(array(
                        'userid' => $userid
                    ));
        
        ($status)? $API->flash('message','User deleted.')
                 : $API->flash('error','Exception detected: '.$query2->errorInfo()[2].'.');
        
        $API->redirect($API->urlFor('auser'));
    }
);
