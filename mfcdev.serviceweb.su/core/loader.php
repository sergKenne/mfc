<?php

define('SITE_ROOT',$_SERVER['DOCUMENT_ROOT']);
define('EXCLUDED_MODULES', array('page_extend', 'guides', 'pages_languages'));
define('EMPTY_DATE', '0000-00-00 00:00:00');

$startExecution = microtime(true);
$cachedQueriesCount = array('objects'=>0,'selectors'=>0);

//загрузчик файлов и модулей
require_once('baseModule.php');         //базовый модуль всех модулей
require_once('selector.php');           //основной модуль поиска по таблице
require_once('router.php');             //модуль роутинга
require_once('templater.php');          //модуль шаблонизации
require_once('utils.php');              //модуль дополнительных утилит
require_once('cache.php');

function loadModules($folder, $returnNames = false){
    if(is_readable($folder)){
        $allModules = scandir($folder); $moduleNames = array();
        foreach($allModules as $moduleFile){
            if($moduleFile == '.' or $moduleFile == '..'){ continue;}
            $pathinfo = pathinfo($folder.$moduleFile);
            if(isset($pathinfo['extension']) and $pathinfo['extension'] == 'php'){
                if(!$returnNames){ require_once($folder.$moduleFile);}
                else{ $moduleNames[] = $pathinfo['filename'];}
            }
        }
        if($returnNames){ return $moduleNames;}
    }
}

//системные модули
loadModules('./core/system/');
loadModules('./core/modules/');
config::load();

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try{
    $mysqli = mysqli_connect(
        config::get('DB','db_server'),
        config::get('DB','db_user'),
        config::get('DB','db_password'),
        config::get('DB','db_name')
    );
}
catch(mysqli_sql_exception $e){
    router::notEnoughPermissions('Сайт временно недоступен. Ошибка подключения к БД');
    exit;
}
$mysqli->query("SET collation_connection = utf8_general_ci");
$mysqli->query("SET NAMES utf8");
$mysqli->query("SET SESSION sql_mode='NO_ENGINE_SUBSTITUTION'");


if(config::get('debug', 'benchmarks')){
    $mysqli->query("SET PROFILING = 1");
    $mysqli->query("SET profiling_history_size=99");
}

//глобальная функция экранирования строк
function escapeString($string){
    global $mysqli;
    return $mysqli->real_escape_string(strip_tags($string, config::get('options','allowed_tags')));
}

$loadedClasses = array();
$loadedGuides = array();
$loadedObjects = array();

$selectorCacheRequests = array();
$selectorCacheResults = array();


//загрузим сразу все имеющиеся справочники
$sel = new selector('guides');
$sel->isnotnull('id');
foreach($sel->run(0,1)['data'] as $guide){
    $loadedGuides[$guide->getName()] = $guide;
    if(!$guide->getValue('is_module')){
        $loadedGuides['guide_'.$guide->getName()] = $guide;
    }
    $loadedGuides['id'.$guide->getId()] = $guide;
}