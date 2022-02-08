<?php
/**
* API Available Invocation methods
*
* @category   Adive
* @package    Adive
* @author     Ferdinand Martin
* @since      File available since Release 1.0.0
*/
function invocations(){
    /**
     * Add a key to $invocations array to use personal invocations
     * NOTE: No spaces and entities allowed
     * 'invoke_key' => 'Invoke description',
     */
    $invocations = array(
       'web' => 'WEB with Frontend & Backend',
       'landing' => 'LANDING Page (Only Frontend)',
	   'blog' => 'BLOG (Frontend & Backend)',
    );
    return $invocations;
}
/**
* Get base path for API
*
* @category   Adive
* @package    Adive
* @author     Ferdinand Martin
* @since      File available since Release 1.0.0
*/
function basePath(){
    $scriptName = str_replace('/index.php','',$_SERVER['SCRIPT_NAME']);
    $formatedRequest = explode('/',$_SERVER['REQUEST_URI']);
    $formatedRequestFR = '/'.$formatedRequest[1];
	(!empty($formatedRequest[2])) ? $formatedRequestFR2= '/'.$formatedRequest[2] : $formatedRequestFR2= '';
	(!empty($formatedRequest[3])) ? $formatedRequestFR3= '/'.$formatedRequest[3] : $formatedRequestFR3= '';
    return sprintf(
      "%s://%s%s",
      isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
      $_SERVER['SERVER_NAME'],
      $scriptName
    );
}

/**
* Return Views path for API
*
* @category   Adive
* @package    Adive
* @author     Ferdinand Martin
* @since      File available since Release 1.0.0
*/
function viewsPath(){
    return $_SESSION['templates.path'];
}

/**
* Set Active Path for class="active" in Bootstrap
*
* @category   Adive
* @package    Adive
* @author     Ferdinand Martin
* @since      File available since Release 1.0.0
*/
function pathActive($route = '/'){
    $_SESSION['menu.name'] = $route;
}

/**
* Get Active Path for class="active" in Bootstrap
*
* @category   Adive
* @package    Adive
* @author     Ferdinand Martin
* @since      File available since Release 1.0.0
*/
function isActive(){
    return $_SESSION['menu.name'];
}

/**
* Returns Path for Name
*
* @category   Adive
* @package    Adive
* @author     Ferdinand Martin
* @since      File available since Release 1.0.0
*/
function path($name = 'home'){
    return $_SESSION['path.name'][$name];
}

/**
* Returns Path for Name
*
* @category   Adive
* @package    Adive
* @author     Ferdinand Martin
* @since      File available since Release 1.0.0
*/
function setPathName($pathName, $pathRoute){
    $_SESSION['path.name'][$pathName] = $pathRoute;
}

/**
* SET Url
*
* @category   Adive
* @package    Adive
* @author     Ferdinand Martin
* @since      File available since Release 1.0.0
*/
function setUri($pathName, $pathRoute){
    $_SESSION['link.name'][$pathName] = $pathRoute;
}

/**
* Returns URL for Name
*
* @category   Adive
* @package    Adive
* @author     Ferdinand Martin
* @since      File available since Release 1.0.0
*/
function uri($name = 'home'){
    return $_SESSION['link.name'][$name];
}

/**
* Assets Generator
*
* @category   Adive
* @package    Adive
* @author     Ferdinand Martin
* @since      File available since Release 1.0.0
*/
function asset($resource){
    
    if(substr($resource, -3)=='css') $asset=basePath().'/'.viewsPath().'/css/'.$resource;
    if(substr($resource, -3)=='.js') $asset=basePath().'/'.viewsPath().'/js/'.$resource;
    if(substr($resource, -3)=='peg') $asset=basePath().'/'.viewsPath().'/images/'.$resource;
    if(substr($resource, -3)=='png') $asset=basePath().'/'.viewsPath().'/images/'.$resource;
    if(substr($resource, -3)=='gif') $asset=basePath().'/'.viewsPath().'/images/'.$resource;
    if(substr($resource, -3)=='jpg') $asset=basePath().'/'.viewsPath().'/images/'.$resource;
    if(substr($resource, -3)=='svg') $asset=basePath().'/'.viewsPath().'/images/'.$resource;
    
    if(empty($asset)) $asset = basePath().'/'.viewsPath().'/resources/'.$resource;
        
    return $asset;
}

/**
* Assets Internal Generator
*
* @category   Adive
* @package    Adive
* @author     Ferdinand Martin
* @since      File available since Release 1.0.0
*/
function iasset($resource){

    if(substr($resource, -3)=='css') $asset=basePath().'/Adive/Internal/css/'.$resource;
    if(substr($resource, -3)=='.js') $asset=basePath().'/Adive/Internal/js/'.$resource;
    if(substr($resource, -3)=='peg') $asset=basePath().'/Adive/Internal/images/'.$resource;
    if(substr($resource, -3)=='png') $asset=basePath().'/Adive/Internal/images/'.$resource;
    if(substr($resource, -3)=='gif') $asset=basePath().'/Adive/Internal/images/'.$resource;
    if(substr($resource, -3)=='jpg') $asset=basePath().'/Adive/Internal/images/'.$resource;
    if(substr($resource, -3)=='svg') $asset=basePath().'/Adive/Internal/images/'.$resource;
    
    if(empty($asset)) $asset = basePath().'/Adive/Internal/resources/'.$resource;
        
    return $asset;
}

/**
* Get BASE URL
*
* @category   Adive
* @package    Adive
* @author     Ferdinand Martin
* @since      File available since Release 1.0.0
*/
function url(){
  $formatedRequest = explode('/',$_SERVER['REQUEST_URI']);
  $formatedRequestFR = '/'.$formatedRequest[1];
  return sprintf(
    "%s://%s%s",
    isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
    $_SERVER['SERVER_NAME'],
    $formatedRequestFR
  );
}

/**
* Security REST API AUTH
*
* @category   Adive
* @package    Adive
* @author     Ferdinand Martin
* @since      File available since Release 1.0.0
*/
function isAuth(){
    $restSalt = $_SESSION['rest.vars']['rest.salt'];
    $restID = $_SESSION['rest.vars']['rest.id'];
    if(!isset($_SESSION['authsecret'])){
        if(stristr(base64_decode($_SERVER["HTTP_AUTHORIZATION"]), $restSalt) and 
           stristr(base64_decode($_SERVER["HTTP_AUTHORIZATION"]), $restID) and 
           md5($_SERVER["HTTP_AUTHORIZATION"])==$_SESSION['rest.pk'])
        {
          $_SESSION['authsecret']=$_SERVER["HTTP_AUTHORIZATION"];
          return true;
        } else {
          return false;
        }
    } else {
        if($_SERVER["HTTP_AUTHORIZATION"]!=$_SESSION['rest.pu']){
            return false;
        } else {
            return true;
        }
    }
}

/**
* Security DATA PRINT for API AUTH
*
* @category   Adive
* @package    Adive
* @author     Ferdinand Martin
* @since      File available since Release 1.0.0
*/
function printAuth(){
    return json_encode(array('secret' => $_SESSION['rest.pu']));
}

/**
* Security ERROR PRINT for API AUTH
*
* @category   Adive
* @package    Adive
* @author     Ferdinand Martin
* @since      File available since Release 1.0.0
*/
function errorAuth(){
    return json_encode(array('status'=>'error','code'=>'401','message' => 'Authorization Required.','exception' => 'Missing public-key authorization header.'));
}

// @URIs External URL definition
setUri('documentationUrl','http://doc.adive.es');
setUri('communityUrl','http://doc.adive.es');
setUri('tutosUrl','http://doc.adive.es');

/**
* Permalink generator
*
* @category   Adive
* @package    Adive
* @author     Ferdinand Martin
* @since      File available since Release 1.0.0
*/
function permalink($string) {
    $normalizeChars = array(
        'Š'=>'S', 'š'=>'s', 'Ð'=>'Dj','Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A',
        'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I',
        'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U',
        'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss','à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a',
        'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i',
        'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u',
        'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y', 'ƒ'=>'f', 'º'=>'o', 'ª'=>'a'
    );
   
    $permalink = mb_strtolower($string,'UTF-8');
    $permalink = strtr($permalink, $normalizeChars);
    $permalink=str_replace('&','-and-', $permalink);
    $permalink=trim(preg_replace('/[^\w\d_ -]/si', '-', $permalink));
    $permalink=str_replace(' ','-', $permalink);
    $permalink=str_replace('--','-', $permalink);
    return $permalink;
}

/**
* Fields in related table
*
* @category   Adive
* @package    Adive
* @author     Ferdinand Martin
* @since      File available since Release 1.0.0
*/
function fklist($table) {
    global $db;
    $fkarray = array();
    $fieldsQuery = $db->prepare("SELECT * FROM adive_tables WHERE name=:name");
    $fieldsQuery->execute(array('name'=>$table));
    $resTables = $fieldsQuery->fetchAll(PDO::FETCH_ASSOC);
    
    $resQuery = $db->prepare("SELECT * FROM ".$table." ORDER by ".$resTables[0]['table_name_field']." ASC");
    $resQuery->execute(array('name'=>$table));
    $resRelation = $resQuery->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($resRelation as $key => $value) {
        $fkarray[$value['id']] = $value[$resTables[0]['table_name_field']];
    }
    return $fkarray;
}

/**
* Table Related Name
*
* @category   Adive
* @package    Adive
* @author     Ferdinand Martin
* @since      File available since Release 1.0.0
*/
function fkname($table) {
    global $db;
    $fieldsQuery = $db->prepare("SELECT * FROM adive_tables WHERE name=:name");
    $fieldsQuery->execute(array('name'=>$table));
    $resTables = $fieldsQuery->fetchAll(PDO::FETCH_ASSOC);

    return $resTables[0]['win_name'];
}

/**
* FK Value
*
* @category   Adive
* @package    Adive
* @author     Ferdinand Martin
* @since      File available since Release 1.0.0
*/
function fkval($id,$table) {
    global $db;

    $fieldsQuery = $db->prepare("SELECT * FROM adive_tables WHERE name=:name");
    $fieldsQuery->execute(array('name'=>$table));
    $resTables = $fieldsQuery->fetchAll(PDO::FETCH_ASSOC);
    
    $resQuery = $db->prepare("SELECT * FROM ".$table." WHERE id=:id");
    $resQuery->execute(array('id'=>$id));
    $resRelation = $resQuery->fetchAll(PDO::FETCH_ASSOC);

    return $resRelation[0][$resTables[0]['table_name_field']];
    //return $id;
}

/**
* Sesion Var Value
*
* @category   Adive
* @package    Adive
* @author     Ferdinand Martin
* @since      File available since Release 1.0.0
*/
function session($var) {

    if(!isset($_SESSION['env.vars'])){
        $_SESSION['env.vars'] = Array();
    }

    if(isset($_SESSION['env.vars'][$var])){
        $returnedVar = $_SESSION['env.vars'][$var];
    } else {
        $_SESSION['env.vars'][$var] = null;
        $returnedVar = null;
    }
    return $_SESSION['env.vars'][$var];
}

function setSession($var,$value = null) {
    $_SESSION['env.vars'][$var] = $value;
}

function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'año',
        'm' => 'mes',
        'w' => 'semana',
        'd' => 'dia',
        'h' => 'hora',
        'i' => 'minuto',
        's' => 'segundo',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ?  'hace ' . implode(', ', $string) : 'ahora';
}


function cropAlign($image, $cropWidth, $cropHeight, $horizontalAlign = 'center', $verticalAlign = 'middle') {
    $width = imagesx($image);
    $height = imagesy($image);
    
    $horizontalAlignPixels = calculatePixelsForAlign($width, $cropWidth, $horizontalAlign);
    $verticalAlignPixels = calculatePixelsForAlign($height, $cropHeight, $verticalAlign);
    return imageCrop($image, [
        'x' => $horizontalAlignPixels[0],
        'y' => $verticalAlignPixels[0],
        'width' => $horizontalAlignPixels[1],
        'height' => $verticalAlignPixels[1]
    ]);
}

function calculatePixelsForAlign($imageSize, $cropSize, $align) {
    switch ($align) {
        case 'left':
        case 'top':
            return [0, min($cropSize, $imageSize)];
        case 'right':
        case 'bottom':
            return [max(0, $imageSize - $cropSize), min($cropSize, $imageSize)];
        case 'center':
        case 'middle':
            return [
                max(0, floor(($imageSize / 2) - ($cropSize / 2))),
                min($cropSize, $imageSize),
            ];
        default: return [0, $imageSize];
    }
}

/**
* Resize and crop images
*
* @category   Adive
* @package    Adive
* @author     Ferdinand Martin
* @since      File available since Release 2.1.0
*/
function saveImage($imgOrigin,$imgDestiny,$width = 300,$height = 300,$halign = 'center',$valign = 'middle'){

    $imgType = exif_imagetype($imgOrigin);

    // Imagen redimensionada
    list($orig_width, $orig_height) = getimagesize($imgOrigin);

    $widthOrigin = $orig_width;
    $heightOrigin = $orig_height;
    # taller
    if ($widthOrigin > $width) {
        $widthOrigin = ($height / $heightOrigin) * $widthOrigin;
        $heightOrigin = $height;
    }
    # wider
    if ($heightOrigin > $height) {
        $heightOrigin = ($width / $widthOrigin) * $heightOrigin;
        $widthOrigin = $width;
    }
    $thumb = imagecreatetruecolor($widthOrigin, $heightOrigin);

    if($imgType==IMAGETYPE_GIF){
        
        $img = imagecreatefromgif($imgOrigin);
        // Cambiar el tamaño
        imagecopyresized($thumb, $img, 0, 0, 0, 0, $widthOrigin, $heightOrigin, $orig_width, $orig_height);
        imagegif(cropAlign($thumb, $width, $height, $halign, $valign),$imgDestiny);
    } else if($imgType==IMAGETYPE_PNG){
        $img = imagecreatefrompng($imgOrigin);
        // Cambiar el tamaño
        imagecopyresized($thumb, $img, 0, 0, 0, 0, $widthOrigin, $heightOrigin, $orig_width, $orig_height);
        imagepng(cropAlign($thumb, $width, $height, $halign, $valign),$imgDestiny);
    } else {
        $img = imagecreatefromjpeg($imgOrigin);
        // Cambiar el tamaño
        imagecopyresized($thumb, $img, 0, 0, 0, 0, $widthOrigin, $heightOrigin, $orig_width, $orig_height);
        imagejpeg(cropAlign($thumb, $width, $height, $halign, $valign),$imgDestiny,90);
    }
}
