<?php
/* 
 * CONTROLLERS LOADER
 * List of API controlllers
 * WEB & REST
 */
require_once 'Adive/Controller/api-core.php';
require_once 'Adive/Controller/api-tables.php';
require_once 'Adive/Controller/api-nav.php';
require_once 'Adive/Controller/api-users.php';
require_once 'Controller/Default.php';

//Add your own Controllers here:
//require_once 'Controller/MyControler.php

$API->processor();