<?php
/**
 * Created by PhpStorm.
 * User: Stardisk
 * Date: 15.06.20
 * Time: 12:30
 */

class permissions extends baseModule{

    public static function lists(){
        $classes = array();
        $baseModuleClasses = get_class_methods('baseModule');
        $modules = loadModules('./core/modules/', true);
        foreach($modules as $module){
            if(class_exists($module)){
                $methods = get_class_methods($module);
                if(class_exists($module.'_custom')){ $customMethods = get_class_methods($module.'_custom');}
                else{ $customMethods = array();}

                foreach($methods as $method){
                    if(in_array($method, $baseModuleClasses)){ continue;}
                    $reflection = new ReflectionMethod($module, $method);
                    if($reflection->isStatic() and $reflection->isPublic()){    $classes[$module][] = $method;}
                }

                foreach($customMethods as $customMethod){
                    if(in_array($customMethod, $classes[$module]) or in_array($customMethod, $baseModuleClasses)){ continue;}
                    $reflection = new ReflectionMethod($module.'_custom', $customMethod);
                    if($reflection->isStatic() and $reflection->isPublic()){    $classes[$module][] = $customMethod;}
                }
            }
        }
        $userTypes = self::getUserTypes();
        $adminKey = array_search('admin',$userTypes);
        if($adminKey!==false){ $userTypes[$adminKey] = 'http';}
        else {array_unshift($userTypes, 'http');}
        $userTypes[] = 'guest';

        $permissions = self::loadAllPermissions();

        return array('classes'=>$classes, 'userTypes'=>$userTypes, 'permissions'=>$permissions);
    }

    public static function addUserType(){
        if(isset($_POST['type']) and !empty($_POST['type'])){
            $newType = $_POST['type'];
            if(preg_match("/^[a-zA-Z0-9\_]+$/", $newType)){
                $existingTypes = self::getUserTypes();
                $existingTypes[] = escapeString($newType);
                $sql = self::buildEnumString($existingTypes);
                self::customQuery("ALTER TABLE `users` CHANGE `type` `type` {$sql}");
                self::customQuery("ALTER TABLE `permissions` ADD `{$newType}` BOOLEAN NOT NULL");
                return utils::returnSuccess(true);
            }
            else{ return utils::returnSuccess(false, 'Название роли должно содержать только латиницу, цифры и знак подчеркивания');}
        }
        else{ return utils::returnSuccess(false, 'Недостаточно данных');}
    }

    public static function delUserType($type = false){
        if($type){
            $importantTypes = array('admin','guest');
            if(in_array($type, $importantTypes)){ return utils::returnSuccess(false, 'Этот тип пользователя нельзя удалить');}
            $allTypes = self::getUserTypes();
            $needKey = array_search($type, $allTypes);
            if($needKey){
                $sel = new selector('users');
                $sel->equals('type', $type);
                $result = $sel->run(true);
                if($result > 0){ return utils::returnSuccess(false, 'Нельзя удалить тип пользователя, пока есть пользователи такого типа');}
                else{
                    $sqlType = escapeString($type);
                    unset($allTypes[$needKey]);
                    $sql = self::buildEnumString($allTypes);
                    self::customQuery("ALTER TABLE `users` CHANGE `type` `type` {$sql}");
                    self::customQuery("ALTER TABLE `permissions` DROP `{$sqlType}`;");
                    return utils::returnSuccess(true);
                }
            }
            else{ return utils::returnSuccess(false, 'Тип не найден');}
        }
        else{ return utils::returnSuccess(false, 'Недостаточно данных');}
    }

    public static function modifyPermission(){
        if(utils::validatePost(array('module','method','group','value'))){
            $module = $_POST['module']; $method = $_POST['method']; $group = $_POST['group'];
            $value = ($_POST['value'] == 1 ) ? 1 : 0;
            $sel = new selector('permissions');
            $sel->equals('module', $module);
            $sel->addAND();
            $sel->equals('method', $method);
            $sel->limit(1);
            $permission = $sel->run();
            if($permission){
                $permission->setValue($group, $value);
                if($used_rows = $permission->commit()){
                    return utils::returnSuccess(true);
                }
                else { return utils::returnSuccess(false, 'Не удалось изменить разрешение');}
            }
            else{
                $permission = new permissions();
                $permission->setValue('module', $module);
                $permission->setValue('method', $method);
                $permission->setValue($group, $value);
                if($newId = $permission->commit()){
                    return utils::returnSuccess(true);
                }
                else { return utils::returnSuccess(false, 'Не удалось создать разрешение');}
            }
        }
        else{ return utils::returnSuccess(false, 'Недостаточно данных');}
    }

    public static function setDefaultPermissionsForModule($moduleName){
        $funcArr = array('lists','add','edit','view');
        foreach($funcArr as $func){
            $permission = new permissions();
            $permission->setValue('module', $moduleName);
            $permission->setValue('method', $func);
            $permission->setValue('http',1);

            if($func == 'view'){
                foreach($permission->_allFields as $field){
                    if($field['Type'] == 'tinyint(1)'){ $permission->setValue($field['Field'],1);}
                }
            }
            $permission->commit();
        }
    }

    private static function loadAllPermissions(){
        $sel = new selector('permissions');
        $sel->isnotnull('id');
        $data = $sel->run();
        $result = array();
        foreach($data['data'] as $permission){
            $result[$permission->getValue('module')][$permission->getValue('method')] = $permission;
        }
        return $result;
    }

    public static function checkPermission($module, $method, $userType){
        if($userType == 'admin'){$userType = 'http';}
        $sel = new selector('permissions');
        $sel->equals('module',$module);
        $sel->addAND();
        $sel->equals('method', $method);
        $sel->addAND();
        $sel->equals($userType, 1);
        $sel->limit(1);
        $check = $sel->run(true);
        if(!$check) {return false;}
        else{ return true;}
    }

    public static function getUserTypes(){
        $user = new users();
        $types = $user->_allFields['type']['Type'];
        $typeArr = explode(",", str_replace(array("enum(", ")", "'"), "", $types));
        return $typeArr;
    }

    private static function buildEnumString($arrOfTypes){
        $sqlTypes = 'ENUM(';
        foreach($arrOfTypes as $valueNum=>$type){
            $sqlTypes .= "'".$type."'";
            if($valueNum < count($arrOfTypes)-1){ $sqlTypes .= ", ";}
        }
        $sqlTypes .= ')';
        return $sqlTypes;
    }
}