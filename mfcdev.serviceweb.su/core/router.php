<?php
/**
 * Created by PhpStorm.
 * User: Александров Олег
 * Date: 05.07.19
 * Time: 12:14
 */

//модуль роутинга и вызова соответствующих модулей
class router {
    public static $currentUser;
    public static $adminMode = false;
    public static $currentLang = '';

    public static function init(){
        $module = ''; $method = '';
        $path = parse_url($_SERVER['REQUEST_URI']);
        $actualRoute = explode('/',$path['path']);

        if(isset($actualRoute[1])){
            if($actualRoute[1] == 'admin'){
                if(isset($actualRoute[2])) {$module = $actualRoute[2];}
                if(isset($actualRoute[3])) {$method = $actualRoute[3];}
                self::$adminMode = true;
            }
            else{
                $module = $actualRoute[1];
                if(isset($actualRoute[2])) {$method = $actualRoute[2];}
            }
        }

        //проверка авторизации
        $users = new users();
        $checkAuth = $users->checkAuth();
        if(!$checkAuth){
            self::$currentUser = new users();
            self::$currentUser->setValue('type','guest');
            self::$currentUser->id = 0;
        }
        else{
            self::$currentUser = $checkAuth;
        }

        if(self::$adminMode){ $params = array_slice($actualRoute,4);}
        else{ $params = array_slice($actualRoute,3);}
        if(end($params)==''){ unset($params[count($params)-1]);}

        if(self::$adminMode and !router::$currentUser->canAccessToAdminPanel()){
            $_GET['tpl']=1;
            if($module != 'users' and $method != 'enter'){
                return new templater('users','enter',array(),array());
            }
        }

        if(self::$currentUser->isGuest()){
            if(config::get('options','require_auth')){
                if($module!='users' and !permissions::checkPermission($module, $method, router::$currentUser->getValue('type'))){
                    return new templater('users','enter',array(),array());
                }
            }
        }

        //если все-таки в адресе нет ничего
        if(!$module and !$method){
            if(self::$adminMode){ utils::redirect('/admin/pages/lists');}
            else {
                $sel = new selector('pages');
                $sel->equals('is_index_page',1);
                $sel->limit(1);
                if($page = $sel->run()){
                    $pageData = pages::view($page);
                    return new templater('pages','main_page',array(),$pageData);
                }
                else{ return self::err404('Главная страница не выбрана');}
            }
        }

        $tryLoadPage = self::loadPageByURL();
        if(!$tryLoadPage){
            if(class_exists($module)){
                $result = self::callModuleMethod($module,$method,$params);
                if((is_array($result) and !isset($result['fatal_error'])) or is_object($result)){ return $result;}
                else{ return self::err404('страница не найдена');}
            }
            else{return self::err404('страница не найдена');}
        }
    }

    public static function callModuleMethod($module,$method,$params = array(),$fromMacros = false){
        if(class_exists($module)){
            if($fromMacros){ return call_user_func_array(array($module,$method),$params);}
            else{
                if(permissions::checkPermission($module, $method, router::$currentUser->getValue('type'))){
                    $response = call_user_func_array(array($module,$method),$params);
                    if(self::isJSON()){ utils::returnJSON($response);}
                    else{   return new templater($module,$method,$params,$response);}
                }
                return router::notEnoughPermissions('Нет прав на запуск этого метода через HTTP');
            }
        }
    }

    public static function isJSON(){
        return (isset($_SERVER['HTTP_ACCEPT']) and $_SERVER['HTTP_ACCEPT'] == 'text/json');
    }

    protected static function loadPageByURL($url = false){
        if(!$url){
            $getParams = strpos($_SERVER['REQUEST_URI'],'?');
            if($getParams){$url = substr($_SERVER['REQUEST_URI'],1,$getParams-1);}
            else {$url = substr($_SERVER['REQUEST_URI'],1);}
        }

        $checkLang = substr($url,0,3);
        if(preg_match('/\w\w\//',$checkLang, $matches)){
            $lang = substr($url,0,2);
            $url = substr($url,3);
            self::$currentLang = $lang;
        }
        else { $lang = 'ru';}

        if(substr($url,-1) == '/'){ $url = substr($url, 0, -1);}

        $sel = new selector('pages');
        $sel->equals('url', $url);
        $sel->addAND();
        $sel->equals('lang', $lang);
        $sel->limit(1);
        $page = $sel->run();
        if($page){
            if($page->getValue('published')){
                $pageData = pages::view($page);
                $template = $page->getValue('template');
                if($template){
                    $parsed = explode('/', $template);
                    $module = $parsed[0];
                    $method = $parsed[1];
                }
                else{
                    if($pageData['source']){
                        $module = $pageData['source']->getObjectClassName();
                        if(substr($module,0,6) == 'guide_'){ $module = 'pages'; $method = 'content';}
                        else{ $method = 'view';}
                    }
                    else{
                        $module = 'pages';
                        $method = 'content';
                    }
                }
                return new templater($module, $method, array(), $pageData);
            }
            else{ return self::err404('страница не опубликована');}

        }
        else{   return false;}
    }

    public static function notEnoughPermissions($reason = false){
        http_response_code(403);
        if(!$reason){ $reason = 'Недостаточно прав.';}
        if(self::isJSON()){
            return utils::returnJSON(array('success'=>false,'info'=>$reason));
        }
        else{
            if(router::$adminMode){ $_GET['tpl']=1;}
            new templater('err403',false,false,$reason);
            exit;
        }
    }

    public static function err404($errType = 'Объект не найден'){
        //debug_print_backtrace();
        http_response_code(404);
        if(self::isJSON()){
            utils::returnJSON(array('success'=>false,'info'=>$errType));
        }
        else{
            if(router::$adminMode){ $_GET['tpl']=1;}
            new templater('err404',false,false,$errType);
            exit;
        }
    }
}