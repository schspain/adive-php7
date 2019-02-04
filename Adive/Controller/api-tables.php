<?php
/*
 * This file is part of the Adive package.
 *
 * (c) Ferdinand Martin <info@adive.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

// @Route(GET) Tables List
$API->get('/admin/tables', 
    function() use($API, $db) {
        if(!isset($_SESSION['adive.id'])){ $API->redirect($API->urlFor('alogin')); }
            pathActive('tables');
            $tablesQuery = $db->prepare("SELECT p.id, p.name, p.description, p.win_name, p.creationDate,
                                        (SELECT COUNT(id) FROM adive_fields WHERE table_id_fk = p.id) FieldsIn FROM adive_tables p ORDER by p.name ASC");
            $tablesQuery->execute();
            $resTables = $tablesQuery->fetchAll(PDO::FETCH_ASSOC);
            
            $API->render('Adive/Internal/Views:tables', array(
                'title' => 'Tables',
                'description' => 'Crud of Tables in Database, deploy your ideas.',
                'result' => $resTables
            ));
    }
)->name('atables');

// @Route(GET) Add Tables Form
$API->get('/admin/tables/add', 
    function() use($API) {
	    if(!isset($_SESSION['adive.id'])){ $API->redirect($API->urlFor('alogin')); }
            pathActive('tables');
            $API->render('Adive/Internal/Views:addTables', array(
                'title' => 'Create new table',
                'description' => 'Complete the details for the new table.'
            ));
    }
)->name('addTables');

// @Route(POST) Add Tables Action
$API->post('/admin/tables/add', 
    function() use($API, $db) {
	if(!isset($_SESSION['adive.id'])){ $API->redirect($API->urlFor('alogin')); }
        pathActive('tables');
        $formData=$API->request;

        $queryInsert=$db->prepare("INSERT INTO adive_tables(name,description,win_name,win_description,table_name_field,creationDate)
                                values (:name,:description,:win_name,:win_description,:table_name_field,:creationDate);");

        $status=$queryInsert->execute(
                array(
                    ':name'=> $formData->post('tableName').'_'.$formData->post('nameSplit'),
                    ':description'=> $formData->post('tableDescription'),
                    ':win_name'=> $formData->post('winName'),
                    ':win_description'=> $formData->post('winDescription'),
                    ':table_name_field'=> $formData->post('tableNameField'),
                    ':creationDate'=> date('Y-m-d H:i:s')
                    )
                );
        
        if($status){
            $queryTableCrt=$db->prepare("CREATE TABLE ".$formData->post('tableName').'_'.$formData->post('nameSplit')." (id INT(6) AUTO_INCREMENT PRIMARY KEY) COMMENT='".$formData->post('tableDescription')."';");
            $queryTableCrt->execute();
        }
        
        ($status)? $API->flash('message','Table created.')
                 : $API->flash('error','Exception detected: '.$queryInsert->errorInfo()[2].'.');
        
        ($status)? $API->redirect($API->urlFor('atables'))
                 : $API->redirect($API->urlFor('addTables'));
    }
);

// @Route(GET) Tables Edit Form
$API->get('/admin/tables/edit/{table_id}', 
    function($tableID) use($API, $db) {
	if(!isset($_SESSION['adive.id'])){ $API->redirect($API->urlFor('alogin')); }
        pathActive('tables');
        $query1 = $db->prepare("SELECT * FROM adive_tables WHERE id=:tabid ORDER by id ASC");
        $query1->execute(array(
            'tabid' => $tableID
        ));
        $result1 = $query1->fetchAll(PDO::FETCH_ASSOC);
        
        $API->render('Adive/Internal/Views:editTables', array(
            'title' => 'Edit table '.$result1[0]['win_name'],
            'description' => 'Change the details for the table '.$result1[0]['name'].'.',
            'tables' => $result1,
        ));
    }
);

// @Route(POST) Tables Edit Action
$API->post('/admin/tables/edit/{table_id}', 
    function($tableID) use($API, $db) {
	if(!isset($_SESSION['adive.id'])){ $API->redirect($API->urlFor('alogin')); }
        pathActive('tables');
        $formData=$API->request;

        $queryInsert=$db->prepare("UPDATE adive_tables SET name=:name,description=:description,win_name=:win_name,win_description=:win_description,table_name_field=:table_name_field WHERE id=:id");

        $status=$queryInsert->execute(
                array(
                    ':id' => $tableID,
                    ':name'=> $formData->post('tableName').'_'.$formData->post('nameSplit'),
                    ':description'=> $formData->post('tableDescription'),
                    ':win_name'=> $formData->post('winName'),
                    ':win_description'=> $formData->post('winDescription'),
                    ':table_name_field'=> $formData->post('tableNameField')
                    )
                );
        
        if($status){
            if($formData->post('tableName').'_'.$formData->post('nameSplit')!=$formData->post('oldName')){
                $queryTableCrt=$db->prepare("RENAME TABLE `" . $formData->post('oldName') . "` TO `" . $formData->post('tableName').'_'.$formData->post('nameSplit') . "`");
                $queryTableCrt->execute();
            }
            if($formData->post('tableDescription')!=$formData->post('oldDescription')){
                $queryTableCrt=$db->prepare("ALTER TABLE ".$formData->post('tableName').'_'.$formData->post('nameSplit')." COMMENT = '".$formData->post('tableDescription')."';");
                $queryTableCrt->execute();
            }
        }
        
        ($status)? $API->flash('message','Table updated.')
                 : $API->flash('error','Exception detected: '.$queryInsert->errorInfo()[2].'.');
        
        ($status)? $API->redirect($API->urlFor('atables'))
                 : $API->redirect(basePath().'/admin/tables/edit/'.$tableID);
    }
);

// @Route(GET) DELETE Table Action
$API->get('/admin/tables/delete/{table_id}', 
    function($tableID) use($API, $db) {
	if(!isset($_SESSION['adive.id'])){ $API->redirect($API->urlFor('alogin')); }
        pathActive('tables');
        $query1 = $db->prepare("SELECT * FROM adive_tables WHERE id=:tabid ORDER by id ASC");
        $query1->execute(array(
            'tabid' => $tableID
        ));
        $result1 = $query1->fetchAll(PDO::FETCH_ASSOC);
        
        $query2 = $db->prepare("DELETE FROM adive_tables WHERE id=:tabid");
        $status = $query2->execute(array(
                        'tabid' => $tableID
                    ));
        
        if($status){
            $queryTableDrop=$db->prepare("DROP TABLE ".$result1[0]['name'].";");
            $queryTableDrop->execute();
        }
        
        ($status)? $API->flash('message','Field deleted.')
                 : $API->flash('error','Exception detected: '.$query2->errorInfo()[2].'.');
        
        $API->redirect($API->urlFor('atables'));
    }
);

// @Route(GET) Relations List
$API->get('/admin/tables/relations/add/{table_id}', 
    function($tableID) use($API, $db) {
	if(!isset($_SESSION['adive.id'])){ $API->redirect($API->urlFor('alogin')); }
        pathActive('tables');
        $query1 = $db->prepare("SELECT * FROM adive_tables WHERE id=:tabid ORDER by id ASC");
        $query1->execute(array(
            'tabid' => $tableID
        ));
        $result1 = $query1->fetchAll(PDO::FETCH_ASSOC);
        
        $query2 = $db->prepare("SELECT * FROM adive_tables WHERE id!=:tabid ORDER by id ASC");
        $query2->execute(array(
            'tabid' => $tableID
        ));
        $result2 = $query2->fetchAll(PDO::FETCH_ASSOC);
        
        $API->render('Adive/Internal/Views:addRelations', array(
            'title' => 'New relation for '.$result1[0]['win_name'],
            'description' => 'Add a new relation for table '.$result1[0]['name'].'.',
            'tableid' => $result1[0]['id'],
            'tablename' => $result1[0]['name'],
            'tables' => $result2,
        ));
    }
);

// @Route(POST) ADD Relation
$API->post('/admin/tables/relations/add/{table_id}', 
    function($tableID) use($API, $db) {
	if(!isset($_SESSION['adive.id'])){ $API->redirect($API->urlFor('alogin')); }
        pathActive('tables');
        $formData=$API->request;
        $query1 = $db->prepare("SELECT * FROM adive_tables WHERE name=:tabname ORDER by id ASC");
        $query1->execute(array(
            'tabname' => $formData->post('destTable')
        ));
        $result1 = $query1->fetchAll(PDO::FETCH_ASSOC);
        
        $queryInsert=$db->prepare("INSERT INTO adive_fields(table_id_fk,name,comment,win_name,win_description,win_order,win_type,win_code,creationDate,author)
                                values (:fkid,:name,:comment,:win_name,:win_description,:win_order,:win_type,:win_code,:creationDate,:author);");

        $queryInsert->execute(
                array(
                    ':fkid'=> $result1[0]['id'],
                    ':name'=> $formData->post('primaryKey'),
                    ':comment'=> "References ".$formData->post('originTable')." ID",
                    ':win_name'=> $formData->post('primaryKey'),
                    ':win_description'=> $formData->post('originTable'),
                    ':win_order'=> 999,
                    ':win_type'=> 11,
                    ':win_code'=> "c_".$formData->post('destTable')."_".$formData->post('originTable')."_rl",
                    ':creationDate'=> date('Y-m-d H:i:s'),
                    ':author'=> $_SESSION['adive.user'],
                    )
                );
        
        $queryInsertLocal=$db->prepare("INSERT INTO adive_fields(table_id_fk,name,comment,win_name,win_description,win_order,win_type,win_code,creationDate,author)
                                values (:fkid,:name,:comment,:win_name,:win_description,:win_order,:win_type,:win_code,:creationDate,:author);");

        $queryInsertLocal->execute(
                array(
                    ':fkid'=> $tableID,
                    ':name'=> $formData->post('destTable').'_rel',
                    ':comment'=> "References ".$formData->post('destTable')." ".$formData->post('primaryKey'),
                    ':win_name'=> $result1[0]['win_name'],
                    ':win_description'=> "c_".$formData->post('destTable')."_".$formData->post('originTable')."_rl",
                    ':win_order'=> 999,
                    ':win_type'=> 11,
                    ':win_code'=> $formData->post('destTable').':'.$formData->post('primaryKey'),
                    ':creationDate'=> date('Y-m-d H:i:s'),
                    ':author'=> $_SESSION['adive.user'],
                    )
                );
        
        $queryRelCrt=$db->prepare("ALTER TABLE `".$formData->post('destTable')."` ADD `".$formData->post('primaryKey')."` INT(6) NOT NULL COMMENT 'References ".$formData->post('originTable')." ID';
        ALTER TABLE `".$formData->post('destTable')."` ADD KEY(`".$formData->post('primaryKey')."`);
        ALTER TABLE `".$formData->post('destTable')."` ADD CONSTRAINT `c_".$formData->post('destTable')."_".$formData->post('originTable')."_rl` FOREIGN KEY (`".$formData->post('primaryKey')."`) REFERENCES `".$formData->post('originTable')."`(`id`) ON DELETE ".$formData->post('deleteRule')." ON UPDATE ".$formData->post('updateRule').";");
        $status=$queryRelCrt->execute();
        
        ($status)? $API->flash('message','Relation created.')
                 : $API->flash('error','Exception detected: '.$queryRelCrt->errorInfo()[2].'.');
        
        ($status)? $API->redirect(basePath().'/admin/tables/fields/'.$tableID)
                 : $API->redirect(basePath().'/admin/tables/fields/'.$tableID);
    }
);

// @Route(GET) Fields in Table List
$API->get('/admin/tables/fields/{table_id}', 
    function($tableID) use($API, $db) {
	if(!isset($_SESSION['adive.id'])){ $API->redirect($API->urlFor('alogin')); }
        pathActive('tables');
        
        $query1 = $db->prepare("SELECT * FROM adive_tables WHERE id=:tabid ORDER by id ASC");
        $query1->execute(array(
            'tabid' => $tableID
        ));
        $result1 = $query1->fetchAll(PDO::FETCH_ASSOC);
        
        $query2 = $db->prepare("SELECT * FROM adive_fields WHERE table_id_fk=:tabid ORDER by win_order ASC");
        $query2->execute(array(
            'tabid' => $tableID
        ));
        $result2 = $query2->fetchAll(PDO::FETCH_ASSOC);
        
        $query3 = $db->prepare("SELECT * FROM adive_events WHERE table_id_fk=:tabid ORDER by time,type ASC");
        $query3->execute(array(
            'tabid' => $tableID
        ));
        $result3 = $query3->fetchAll(PDO::FETCH_ASSOC);
        
        $API->render('Adive/Internal/Views:fields', array(
            'title' => 'Fields in '.$result1[0]['win_name'],
            'description' => 'List of fields in this table.',
            'tables' => $result1,
            'fields' => $result2,
            'triggers' => $result3
        ));
    }
);

// @Route(GET) Add Field Form
$API->get('/admin/tables/fields/add/{table_id}', 
    function($tableID) use($API, $db) {
	    if(!isset($_SESSION['adive.id'])){ $API->redirect($API->urlFor('alogin')); }
            pathActive('tables');
	    
	    $query = $db->prepare("SELECT COUNT(*) as num_fields FROM adive_fields WHERE table_id_fk=:tabid ORDER by win_order ASC");
	    $query->execute(array(
		'tabid' => $tableID
	    ));
	    $result = $query->fetchAll(PDO::FETCH_ASSOC);
	    
            $API->render('Adive/Internal/Views:addFields', array(
                'title' => 'Create new field',
                'description' => 'The field added will be available in database at the moment.',
                'tableid' => $tableID,
		'numfields' => ($result[0]['num_fields']+1)
            ));
    }
)->name('addFields');

// @Route(POST) Add Field Action
$API->post('/admin/tables/fields/add/{table_id}', 
    function($tableID) use($API, $db) {
	if(!isset($_SESSION['adive.id'])){ $API->redirect($API->urlFor('alogin')); }
        pathActive('tables');
        $formData=$API->request;

        $queryInsert=$db->prepare("INSERT INTO adive_fields(table_id_fk,name,comment,win_name,win_description,win_order,win_type,win_code,creationDate,author)
                                values (:fkid,:name,:comment,:win_name,:win_description,:win_order,:win_type,:win_code,:creationDate,:author);");

        $status=$queryInsert->execute(
                array(
                    ':fkid'=> $tableID,
                    ':name'=> $formData->post('fieldName'),
                    ':comment'=> $formData->post('fieldComment'),
                    ':win_name'=> $formData->post('winName'),
                    ':win_description'=> $formData->post('winDescription'),
                    ':win_order'=> $formData->post('winOrder'),
                    ':win_type'=> $formData->post('winType'),
                    ':win_code'=> $formData->post('winCode'),
                    ':creationDate'=> date('Y-m-d H:i:s'),
                    ':author'=> $_SESSION['adive.user'],
                    )
                );
        
        $query1 = $db->prepare("SELECT * FROM adive_tables WHERE id=:tabid ORDER by id ASC");
        $query1->execute(array(
            'tabid' => $tableID
        ));
        $result1 = $query1->fetchAll(PDO::FETCH_ASSOC);
        
        if($status){
            //PREPARING THE TYPE
            switch ($formData->post('winType')):
                case 1: $fieldType='VARCHAR(200)'; break;
                case 2: $fieldType='TINYTEXT'; break;
                case 3: $fieldType='TEXT'; break;
                case 4: $fieldType='VARCHAR(200)'; break;
                case 5: $fieldType='INT'; break;
                case 6: $fieldType='DATETIME'; break;
                case 7: $fieldType='DATETIME'; break;
                case 8: $fieldType='TINYTEXT'; break;
                case 9: $fieldType='TINYTEXT'; break;
                case 10: $fieldType='TINYTEXT'; break;
				case 14: $fieldType='INT(1)'; break;
            endswitch;
            $queryTableCrt=$db->prepare("ALTER TABLE `".$result1[0]['name']."` ADD `".$formData->post('fieldName')."` ".$fieldType." NULL COMMENT '".$formData->post('fieldComment')."';");
            $queryTableCrt->execute();
        }
        
        ($status)? $API->flash('message','Field created.')
                 : $API->flash('error','Exception detected: '.$queryInsert->errorInfo()[2].'.');
        
        ($status)? $API->redirect(basePath().'/admin/tables/fields/'.$tableID)
                 : $API->redirect(basePath().'/admin/tables/fields/add/'.$tableID);
        
    }
);

// @Route(GET) Edit Field Form
$API->get('/admin/tables/{table_id}/fields/edit/{field_id}', 
    function($tableID, $fieldID) use($API, $db) {
	if(!isset($_SESSION['adive.id'])){ $API->redirect($API->urlFor('alogin')); }
        pathActive('tables');
        $query1 = $db->prepare("SELECT * FROM adive_tables WHERE id=:tabid ORDER by id ASC");
        $query1->execute(array(
            'tabid' => $tableID
        ));
        $result1 = $query1->fetchAll(PDO::FETCH_ASSOC);
        
        $query2 = $db->prepare("SELECT * FROM adive_fields WHERE id=:fieid ORDER by id ASC");
        $query2->execute(array(
            'fieid' => $fieldID
        ));
        $result2 = $query2->fetchAll(PDO::FETCH_ASSOC);
        
        $API->render('Adive/Internal/Views:editFields', array(
            'title' => 'Edit field '.$result2[0]['win_name'],
            'description' => 'Change the details for the field '.$result2[0]['name'].'.',
            'table' => $result1,
            'field' => $result2,
        ));
    }
);

// @Route(POST) Edit Field Action
$API->post('/admin/tables/{table_id}/fields/edit/{field_id}', 
    function($tableID, $fieldID) use($API, $db) {
	if(!isset($_SESSION['adive.id'])){ $API->redirect($API->urlFor('alogin')); }
        pathActive('tables');
        $formData=$API->request;
        $queryInsert=$db->prepare("UPDATE adive_fields SET name=:name,comment=:comment,win_name=:win_name,win_description=:win_description,win_type=:win_type,win_order=:win_order,win_code=:win_code WHERE id=:id");

        $status=$queryInsert->execute(
                array(
                    ':id' => $fieldID,
                    ':name'=> $formData->post('fieldName'),
                    ':comment'=> $formData->post('fieldComment'),
                    ':win_name'=> $formData->post('winName'),
                    ':win_description'=> $formData->post('winDescription'),
                    ':win_type'=> $formData->post('winType'),
                    ':win_order'=> $formData->post('winOrder'),
                    ':win_code'=> $formData->post('winCode'),
                    )
                );
        
        $query1 = $db->prepare("SELECT * FROM adive_tables WHERE id=:tabid ORDER by id ASC");
        $query1->execute(array(
            'tabid' => $tableID
        ));
        $result1 = $query1->fetchAll(PDO::FETCH_ASSOC);
        
        if($status){
            //PREPARING THE TYPE
            switch ($formData->post('winType')):
                case 1: $fieldType='VARCHAR(200)'; break;
                case 2: $fieldType='TINYTEXT'; break;
                case 3: $fieldType='TEXT'; break;
                case 4: $fieldType='VARCHAR(200)'; break;
                case 5: $fieldType='INT'; break;
                case 6: $fieldType='DATETIME'; break;
                case 7: $fieldType='DATETIME'; break;
                case 8: $fieldType='TINYTEXT'; break;
                case 9: $fieldType='TINYTEXT'; break;
                case 10: $fieldType='TINYTEXT'; break;
            endswitch;
            
            if($formData->post('fieldName')!=$formData->post('oldName') 
            or $formData->post('fieldComment')!=$formData->post('oldComment') 
            or $formData->post('winType')!=$formData->post('oldType')){
                $queryTableCrt=$db->prepare("ALTER TABLE ".$result1[0]['name']." CHANGE ".$formData->post('oldName')." ".$formData->post('fieldName')." ".$fieldType." NULL COMMENT '".$formData->post('fieldComment')."';");
                $queryTableCrt->execute();
            }
        }
        
        ($status)? $API->flash('message','Field updated.')
                 : $API->flash('error','Exception detected: '.$queryInsert->errorInfo()[2].'.');
        
        ($status)? $API->redirect(basePath().'/admin/tables/fields/'.$tableID)
                 : $API->redirect(basePath().'/admin/tables/'.$tableID.'/fields/edit/'.$fieldID);
    }
);

// @Route(GET) DELETE field
$API->get('/admin/tables/{table_id}/fields/delete/{field_id}', 
    function($tableID, $fieldID) use($API, $db) {
	if(!isset($_SESSION['adive.id'])){ $API->redirect($API->urlFor('alogin')); }
        pathActive('tables');
        
        $query1 = $db->prepare("SELECT * FROM adive_tables WHERE id=:tabid ORDER by id ASC");
        $query1->execute(array(
            'tabid' => $tableID
        ));
        $result1 = $query1->fetchAll(PDO::FETCH_ASSOC);
        
        $query2 = $db->prepare("SELECT * FROM adive_fields WHERE id=:fieid ORDER by id ASC");
        $query2->execute(array(
            'fieid' => $fieldID
        ));
        $result2 = $query2->fetchAll(PDO::FETCH_ASSOC);
        
        $query3 = $db->prepare("DELETE FROM adive_fields WHERE id=:fieid");
        $status = $query3->execute(array(
                        'fieid' => $fieldID
                    ));
        
        if($status){
            $endFieldName = substr($result2[0]['name'], -2);
            if($endFieldName=='fk'){
                $query4 = $db->prepare("DELETE FROM adive_fields WHERE win_description=:windesc");
                $query4->execute(array(
                                    'windesc' => $result2[0]['win_code']
                                ));
                $result4 = $query4->fetchAll(PDO::FETCH_ASSOC);
        
                $queryFieldCrt=$db->prepare("ALTER TABLE ".$result1[0]['name']." DROP FOREIGN KEY ".$result2[0]['win_code'].";");
                $queryFieldCrt->execute();
            }
            
            $queryFieldCrt=$db->prepare("ALTER TABLE ".$result1[0]['name']." DROP ".$result2[0]['name'].";");
            $queryFieldCrt->execute();
        }
        
        ($status)? $API->flash('message','Field deleted.')
                 : $API->flash('error','Exception detected: '.$query3->errorInfo()[2].'.');
        
        $API->redirect(basePath().'/admin/tables/fields/'.$tableID);
    }
);


// @Route(GET) ADD TRIGGER FORM
$API->get('/admin/tables/trigger/add/{table_id}', 
    function($tableID) use($API) {
	    if(!isset($_SESSION['adive.id'])){ $API->redirect($API->urlFor('alogin')); }
            pathActive('tables');
            $API->render('Adive/Internal/Views:addTrigger', array(
                'title' => 'Create new trigger',
                'description' => 'The trigger added will be available in database at the moment.',
                'tableid' => $tableID
            ));
    }
)->name('addTrigger');

// @Route(POST) ADD TRIGGER ACTION
$API->post('/admin/tables/trigger/add/{table_id}', 
    function($tableID) use($API, $db) {
	if(!isset($_SESSION['adive.id'])){ $API->redirect($API->urlFor('alogin')); }
        pathActive('tables');
        $formData=$API->request;
        
        $query1 = $db->prepare("SELECT * FROM adive_tables WHERE id=:tabid ORDER by id ASC");
        $query1->execute(array(
            'tabid' => $tableID
        ));
        $result1 = $query1->fetchAll(PDO::FETCH_ASSOC);
        
        $queryTableCrt=$db->prepare("CREATE TRIGGER ".$result1[0]['name'].'_'.strtolower($formData->post('timeTrigger')).'_'.strtolower($formData->post('typeTrigger'))."
".$formData->post('timeTrigger')." ".$formData->post('typeTrigger')."
   ON ".$result1[0]['name']." FOR EACH ROW

BEGIN

   -- Code
   ".$formData->post('codeTrigger')."
   
END; //

DELIMITER ;");
        $status2=$queryTableCrt->execute();
        $aData = $queryTableCrt->fetchAll();
        $queryTableCrt->closeCursor();
        
        if($status2){
            $queryInsert = $db->prepare("INSERT INTO adive_events(table_id_fk,name,time,type,code,creationDate)
                                values (:fkid,:name,:time,:type,:code,:creationDate);");

            $queryInsert->execute(array(
                    ':fkid'=> $tableID,
                    ':name'=> $result1[0]['name'].'_'.strtolower($formData->post('timeTrigger')).'_'.strtolower($formData->post('typeTrigger')),
                    ':time'=> $formData->post('timeTrigger'),
                    ':type'=> $formData->post('typeTrigger'),
                    ':code'=> $formData->post('codeTrigger'),
                    ':creationDate'=> date('Y-m-d H:i:s')
                    )
                );
        }
        
        ($status2)? $API->flash('message','Trigger created. '.$queryInsert->errorInfo()[2].'.')
                 : $API->flash('error','Exception detected: '.$queryTableCrt->errorInfo()[2].'.');
        
        ($status2)? $API->redirect(basePath().'/admin/tables/fields/'.$tableID)
                 : $API->redirect(basePath().'/admin/tables/trigger/add/'.$tableID);
    }
);

// @Route(GET) EDIT TRIGGER FORM
$API->get('/admin/tables/{table_id}/trigger/edit/{event_id}', 
    function($tableID, $eventID) use($API, $db) {
	if(!isset($_SESSION['adive.id'])){ $API->redirect($API->urlFor('alogin')); }
        pathActive('tables');
        $query1 = $db->prepare("SELECT * FROM adive_tables WHERE id=:tabid ORDER by id ASC");
        $query1->execute(array(
            'tabid' => $tableID
        ));
        $result1 = $query1->fetchAll(PDO::FETCH_ASSOC);
        
        $query2 = $db->prepare("SELECT * FROM adive_events WHERE id=:eveid ORDER by id ASC");
        $query2->execute(array(
            'eveid' => $eventID
        ));
        $result2 = $query2->fetchAll(PDO::FETCH_ASSOC);
        
        $API->render('Adive/Internal/Views:editTrigger', array(
            'title' => 'Edit trigger '.$result2[0]['name'],
            'description' => 'Change the properties for the trigger '.$result2[0]['name'].'.',
            'table' => $result1,
            'trigger' => $result2,
        ));
    }
);

// @Route(GET) EDIT TRIGGER ACTION
$API->post('/admin/tables/{table_id}/trigger/edit/{event_id}', 
    function($tableID, $eventID) use($API, $db) {
	if(!isset($_SESSION['adive.id'])){ $API->redirect($API->urlFor('alogin')); }
        pathActive('tables');
        $formData=$API->request;
        
        $query1 = $db->prepare("SELECT * FROM adive_tables WHERE id=:tabid ORDER by id ASC");
        $query1->execute(array(
            'tabid' => $tableID
        ));
        $result1 = $query1->fetchAll(PDO::FETCH_ASSOC);
        
        $query2 = $db->prepare("SELECT * FROM adive_events WHERE id=:eveid ORDER by id ASC");
        $query2->execute(array(
            'eveid' => $eventID
        ));
        $result2 = $query2->fetchAll(PDO::FETCH_ASSOC);
        
        $queryInsert=$db->prepare("UPDATE adive_events SET code=:code WHERE id=:id");

        $status=$queryInsert->execute(
                array(
                    ':id' => $eventID,
                    ':code'=> $formData->post('codeTrigger')
                    )
                );
        if($status){
            $queryTableCrt01=$db->prepare("DROP TRIGGER IF EXISTS `".$result2[0]['name']."`;");
            $queryTableCrt01->execute();
            $queryTableCrt=$db->prepare("CREATE TRIGGER ".$result2[0]['name']."
".$result2[0]['time']." ".$result2[0]['type']."
   ON ".$result1[0]['name']." FOR EACH ROW

BEGIN

   -- Code
   ".$formData->post('codeTrigger')."
   
END; //

DELIMITER ;");
            $queryTableCrt->execute();
        }
        
        ($status)? $API->flash('message','Trigger updated.')
                 : $API->flash('error','Exception detected: '.$queryInsert->errorInfo()[2].'.');
        
        ($status)? $API->redirect(basePath().'/admin/tables/fields/'.$tableID)
                 : $API->redirect(basePath().'/admin/tables/trigger/add/'.$tableID);
    }
);

// @Route(GET) DELETE Trigger Action
$API->get('/admin/tables/{table_id}/trigger/delete/{event_id}', 
    function($tableID, $eventID) use($API, $db) {
	if(!isset($_SESSION['adive.id'])){ $API->redirect($API->urlFor('alogin')); }
        pathActive('tables');
        
        $query2 = $db->prepare("SELECT * FROM adive_events WHERE id=:eveid ORDER by id ASC");
        $query2->execute(array(
            'eveid' => $eventID
        ));
        $result2 = $query2->fetchAll(PDO::FETCH_ASSOC);
        
        $query3 = $db->prepare("DELETE FROM adive_events WHERE id=:eveid");
        $status = $query3->execute(array(
                        'eveid' => $eventID
                    ));
        
        if($status){
            $queryEventCrt=$db->prepare("DROP TRIGGER IF EXISTS `".$result2[0]['name']."`;");
            $queryEventCrt->execute();
        }
        
        ($status)? $API->flash('message','Trigger deleted.')
                 : $API->flash('error','Exception detected: '.$query3->errorInfo()[2].'.');
        
        $API->redirect(basePath().'/admin/tables/fields/'.$tableID);
    }
);
