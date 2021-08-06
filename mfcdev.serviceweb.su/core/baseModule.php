<?php
/**
 * Created by PhpStorm.
 * User: Александров Олег
 * Date: 05.07.19
 * Time: 13:22
 */
//базовый модуль для всех остальных модулей
class baseModule{

    //это вызывается в случае, если не найден метод в классе, проверка на наличие метода в кастомном классе
    public static function __callStatic($methodName = false, $arguments = false){
        if(strpos(get_called_class(),'_custom')===false){
            $customClass = get_called_class().'_custom';
            if(class_exists($customClass) and method_exists($customClass, $methodName)){
                return forward_static_call_array (array($customClass, $methodName), $arguments);
            }
            else{  return array('fatal_error'=>'Метод не найден: '.get_called_class().'/'.$methodName);}
        }
        else{ return array('fatal_error'=>'Метод не найден: '.get_called_class().'/'.$methodName);}
    }

    public static function get_called_class(){
        return str_replace('_custom','',get_called_class());
    }

    public function __construct(){
        global $loadedClasses, $loadedGuides;

        if(!isset($loadedClasses[self::get_called_class()])){
            $loadedClasses[self::get_called_class()] = self::getColumnsList();
        }

        foreach($loadedClasses[self::get_called_class()] as $field => $fieldParams){
            switch($fieldParams['Type']){
                case 'json':{ $this->$field = '[]'; break;}
                case 'date':{ $this->$field = '0000-00-00'; break;}
                case 'datetime':{ $this->$field = '0000-00-00 00:00:00'; break;}
                default: {$this->$field = '';}
            }
        }

        if(!in_array(self::get_called_class(), EXCLUDED_MODULES)){
            if(!isset($loadedGuides[self::get_called_class()])){
                $loadedGuides[self::get_called_class()] = guides::getGuideByModuleName(self::get_called_class());
            }

            if($loadedGuides[self::get_called_class()]){
                $this->_guideFields = $loadedGuides[self::get_called_class()]->formatFields();
                $this->guideId = $loadedGuides[self::get_called_class()]->getId();
            }
        }

        $this->_allFields = $loadedClasses[self::get_called_class()];
    }

    //запрос строки из базы данных
    //@param $sql - сформированный классом selector запрос
    public static function selectRows($sql, $onlyOne = false){
        global $mysqli;
        $result = array();
        $resultResource = $mysqli->query($sql);
        $error = utils::mysqlError($sql);

        if(!$resultResource or $resultResource->num_rows === 0){    return false;}
        else{
            if($onlyOne){   return self::fetchObject($resultResource);}
            else{
                while($row = self::fetchObject($resultResource)){
                    $result['data'][] = $row;
                }
            }
        }
        return $result;
    }

    //создание объекта из данных из БД
    private static function fetchObject($mysqlResource){
        $data = $mysqlResource->fetch_assoc();
        $className = self::get_called_class();
        if(is_array($data)){
            $object = new $className();
            //заполняем данные
            foreach($data as $key=>$value){
                if(isset($object->_guideFields[$key])){
                    if($object->_guideFields[$key]['type'] == 'json') {$object->$key = $value;}
                    elseif($object->_guideFields[$key]['type'] == 'boolean') {$object->$key = (int)$value;}
                    else {$object->$key = stripslashes(str_replace('\r\n',"\r\n",$value));}
                }
                else {$object->$key = stripslashes(str_replace('\r\n',"\r\n",$value));}
            }
            return $object;
        }
        else{ return false;}
    }

    public static function countRows($sql){
        global $mysqli;
        $sql = preg_replace('/SELECT (.*) FROM/','SELECT COUNT(`id`) as `totalRows` FROM', $sql);
        $resultResource = $mysqli->query($sql);
        $result = $resultResource->fetch_array();
        return $result['totalRows'];
    }

    //вставка новых записей в таблицу
    //@param $fieldLine - последовательность названий полей
    //@param @valueLine - последовательность значений полей
    protected function insertRows($fieldLine,$valueLine){
        global $mysqli;
        $sql = "INSERT INTO `".get_class($this)."` (".$fieldLine.") VALUES (".$valueLine.")";
        $mysqli->query($sql);

        $error = utils::mysqlError($sql);
        if($error){ return $error;}
        else {return $mysqli->insert_id;}
    }

    //обновление записей в таблице
    //@param $sqlLine - последовательность обновляемых значений
    protected function updateRows($sqlLine){
        global $mysqli;
        $sql = "UPDATE `".get_class($this)."` SET ".$sqlLine." WHERE `id` = '".$this->id."'";
        $mysqli->query($sql);
        $error = utils::mysqlError($sql);
        if($error){ return $error;}
        else {return $mysqli->affected_rows;}
    }

    //удаление записей
    //@param $id - ИД удаляемой записи
    protected static function deleteRow($id){
        global $mysqli;
        $sql = "DELETE FROM `".self::get_called_class()."` WHERE `id` = '".escapeString((int)$id)."'";
        $mysqli->query($sql);

        $error = utils::mysqlError($sql);
        if($error){ return $error;}
        else {return $mysqli->affected_rows;}
    }

    //любой запрос
    public static function customQuery($sql){
        global $mysqli;
        return $mysqli->query($sql);
    }

    //получить значение поля
    //@param название поля
    public function getValue($field, $rawValue = false, $htmlSpecialChars = true){
        // получить значение без обработки
        if($rawValue and isset($this->$field)){
            //jsonAsArray - передать, если из поля типа "справочник" нужно извлечь только массив ID объектов
            if($rawValue == 'jsonAsArray'){ return json_decode($this->$field, true);}
            else {return $this->$field;}
        }

        if(isset($this->$field) and $this->$field !== 'null'){
            if(is_object($this->$field)){ return $this->$field;}    //получить объект сразу
            //если это справочник
            if(isset($this->_guideFields[$field]) and $this->_guideFields[$field]['type'] == 'guide' and !router::$adminMode and !$rawValue){
                //получаем список id элементов справочника
                if(is_array($this->$field)){
                    $thisFieldArray = $this->$field;
                    if(isset($thisFieldArray[0]) and is_object($thisFieldArray[0])) { return $this->$field;} //если в массиве уже объекты, значит оно уже было запрошено ранее
                    else{$selectedIds = $this->$field;}
                }
                else {$selectedIds = json_decode($this->$field, true);}

                if(!is_array($selectedIds)){ $selectedIds = array($this->$field);}
                if(count($selectedIds)>0){
                    //получаем ид справочника
                    $guideID = $this->_guideFields[$field]['fromId'];
                    //получаем объекты по айдишникам
                    $selectedItems = guides::list_items($guideID, $selectedIds, true, false, false, true);
                    //возвращаем их
                    if(isset($selectedItems['data'])){
                        if(!isset($this->_guideFields[$field]['multiple']) and !isset($this->_guideFields[$field]['guideAsTable'])){  $this->$field = current($selectedItems['data']);}
                        else {  $this->$field = $selectedItems['data'];}
                    }
                    else{ $this->$field = stripslashes(str_replace('\r\n',"\r\n",$this->$field));}
                }
                else{ $this->$field = array();}
                return $this->$field;
            }
            //если это не справочник, не массив и не объект
            elseif(!is_array($this->$field) and !is_object($this->$field)){
                //возможно это обычный JSON-массив?
                $json_decoded = json_decode($this->$field,true);
                if(is_array($json_decoded) and !$rawValue){     return $json_decoded;}
                else{
                    $json_decoded = json_decode(stripslashes($this->$field),true);
                    if(is_array($json_decoded) and !$rawValue){   return $json_decoded;}
                    else {
                        if($htmlSpecialChars){ return htmlspecialchars($this->$field, ENT_QUOTES);}
                        else{return $this->$field;}
                    }
                }
            }
            else{   return $this->$field;}
        }
        else {return false;}
    }

    public function getId(){
        return $this->id;
    }

    public function getName(){
        $name = $this->getValue('name');
        if(!$name) { return '(без названия)';}
        else{ return $this->getValue('name');}
    }

    public static function getById($id, $fields = array(), $allowCache = true){
        global $loadedObjects;

        if(isset($loadedObjects[self::get_called_class().':'.$id])){ return $loadedObjects[self::get_called_class().':'.$id];}
        else{
            $fieldsLine = '';
            if(!$fields) {$fieldsLine .= "*";}
            else{
                foreach($fields as $key=>$field){
                    $fieldsLine .= '`'.escapeString($field).'`';
                    if($key !== count($fields)-1){ $fieldsLine.=',';}
                }
            }
            if(count($fields) == 1){
                $object = self::selectRows("SELECT ".$fieldsLine." FROM `".self::get_called_class()."` WHERE `id` = ".escapeString((int)$id)." LIMIT 1", true);
                if($object) {return $object->getValue($fields[0]);}
                else { return '';}
            }
            else{
                if($allowCache and !router::isJSON()){
                    $object = cache::loadObject(get_called_class(), $id);
                    if(!$object){
                        $object = self::selectRows("SELECT ".$fieldsLine." FROM `".self::get_called_class()."` WHERE `id` = ".escapeString((int)$id)." LIMIT 1", true);
                        cache::refreshObject(get_called_class(), $id, $object);
                    }
                }
                else{   $object = self::selectRows("SELECT ".$fieldsLine." FROM `".self::get_called_class()."` WHERE `id` = ".escapeString((int)$id)." LIMIT 1", true);}

                $loadedObjects[self::get_called_class().':'.$id] = $object;
                return $object;
            }
        }
    }

    public static function delById($id){
        return self::deleteRow($id);
    }

    public function deleteObject(){
        return self::deleteRow($this->getId());
    }

    public static function getByMultipleIds($ids = array()){
        if($ids){
            $idsSql = implode(',',array_unique($ids));
            return self::selectRows("SELECT * FROM `".self::get_called_class()."` WHERE `id` IN(".escapeString($idsSql).")");
        }
        else{ return false;}
    }

    public function getObjectClassName(){
        return get_class($this);
    }

    //записать новое значение (обязательно после этого вызвать commit())
    //@param $key название поля
    //@param $value - значение поля
    public function setValue($key,$value){
        if(property_exists($this,$key)){
            if(is_array($value)){ $value = json_encode($value); $this->$key = $value;}
            else{
                if($value or $value === 0){
                    if(is_string($value)){ $value = mb_ereg_replace('/\x20+/', ' ', trim($value));}
                    if(isset($this->_guideFields[$key]['type']) and $this->_guideFields[$key]['type'] == 'htmltext'){
                        $value = utils::closeTags($value);
                    }
                    $this->$key = $value;
                }
                else{
                    switch($this->_allFields[$key]['Type']){
                        case 'json':{ $this->$key = '[]'; break;}
                        case 'date':{ $this->$key = '0000-00-00'; break;}
                        case 'datetime':{ $this->$key = '0000-00-00 00:00:00'; break;}
                        case 'int':{ $this->$key = 0; break;}
                        default: {$this->$key = '';}
                    }
                }
            }
            if($this->id){
                if(!isset($this->_updatedValues)){ $this->_updatedValues = new stdClass();}
                $this->_updatedValues->$key = $this->$key;
            }
            return true;
        }
        else { return false;}
    }

    //сохранение данных в базу данных
    public function commit(){
        $skipFields = array('id','_allFields','_guideFields','guideId','_updatedValues');

        //update rows
        if($this->id){
            $sqlString = '';
            if(isset($this->_updatedValues)){
                foreach(get_object_vars($this->_updatedValues) as $field=>$value){
                    if(in_array($field, $skipFields)){ continue;}
                    else{
                        //обход бага при попытке обновления объекта, в который вложен другой объект
                        if(is_array($value)){
                            $newValue = array();
                            foreach($value as $valueObj){
                                if(is_object($valueObj)){ $newValue[] = $valueObj->getId();}
                                else{ $newValue[] = $valueObj;}
                            }
                            $value = json_encode($newValue);
                        }
                        else if(is_object($value)){
                            $value = $value->getId();
                        }
                        $sqlString .= "`".escapeString($field)."` = '".escapeString($value)."', ";
                    }
                }

                $updated = $this->updateRows(substr($sqlString,0,-2));
                cache::refreshObject($this->getObjectClassName(), $this->id, $this);
                return $updated;
            }
            else{ return 0;}
        }
        //insert rows
        else{
            $fieldLine = ''; $valueLine = '';
            if(isset($this->lang) and !$this->getValue('lang')) { $this->setValue('lang','ru');}
            $this->setValue('create_time',date('Y-m-d H:i:s'));
            foreach(get_object_vars($this) as $field=>$value){
                if(in_array($field, $skipFields)){ continue;}
                else{
                    $fieldLine .= "`".escapeString($field)."`, ";
                    if(is_array($value)){$valueLine .= "'".json_encode($value)."', ";}
                    else{$valueLine .= "'".escapeString($value)."', ";}
                    //else{$valueLine .= "'".($value)."', ";}
                }
            }
            $insert = $this->insertRows(substr($fieldLine,0,-2),substr($valueLine,0,-2));
            $this->id = $insert;
            return $insert;
        }
    }

    public static function getColumnsList(){
        if(!file_exists('./cache/columns/'.self::get_called_class())){
            if(!is_dir('./cache/columns')){   mkdir('./cache/columns', 0777, true);}
            $columns = self::customQuery('SHOW COLUMNS FROM `'.self::get_called_class().'`');
            $columnsArray = array();
            while($column = $columns->fetch_assoc()){
                $columnsArray[$column['Field']] = $column;
            }
            file_put_contents('./cache/columns/'.self::get_called_class(), json_encode($columnsArray));
        }
        else{
            $columnsArray = json_decode(file_get_contents('./cache/columns/'.self::get_called_class()), true);
        }

        return $columnsArray;
    }

    public function toJson($asArray = false){
        $props = get_object_vars($this);
        $objArr = array(); $ignoreFields = array('_updatedValues','_allFields','_guideFields');
        foreach($props as $key=>$value){
            if(!in_array($key, $ignoreFields)){
                if(is_object($value)){ $objArr[$key] = $value->getId();}
                else{$objArr[$key] = $value;}
            }
        }
        if($asArray){ return $objArr;}
        else{return json_encode($objArr);}
    }

    public function jsonToObj($json){
        if(!is_array($json)) {$arr = json_decode($json,true);}
        else{$arr = $json;}

        $mod = new $this;
        foreach($arr as $key=>$value){ $mod->setValue($key,stripslashes($value));}
        return $mod;
    }

    public function duplicateObject($newLangPrefix = false){
        $this->id = 0;
        if($newLangPrefix){ $this->setValue('lang', $newLangPrefix);}
        return $this->commit();
    }

    public function editConnectedPage(){
        $connectedPage = self::getConnectedPage($this->guideId, $this->getId());
        if($connectedPage){
            pages::edit($connectedPage->getId(), true);
            return $connectedPage;
        }
        else{ return false;}
    }

    public function getConnectedPage(){
        $sel = new selector('pages');
        $sel->equals('source_guide', $this->guideId);
        $sel->addAND();
        $sel->equals('source_object',$this->getId());
        $sel->limit(1);
        return $sel->run();
    }

    public function appendPageToObject(){
        if(isset($_POST['create_page']) and get_class()!='pages'){
            pages::add($this->guideId, $this->getId(), true);
        }
    }

    public function getObjectPageLink(){
        $sel = new selector('pages', array('url'));
        $sel->equals('source_guide', $this->guideId);
        $sel->addAND();
        $sel->equals('source_object',$this->getId());
        $sel->limit(1);
        $page = $sel->run();
        if($page){ return $page->getValue('url');}
        else{ return '/'.$this->getObjectClassName().'/view/'.$this->getId();}
    }

    public function __toString(){
        if($this->getId()){ return (string)$this->getId();}
        else{ return '0';}
    }

    //+1 / -1 к счетчику чего угодно
    //math_operator - либо +, либо -
    public function counter($field, $math_operator){
        if($this->getId()){
            $tblName = get_class($this);
            $field = escapeString($field);
            if(in_array($math_operator, ['+', '-'])){
                self::customQuery("UPDATE `{$tblName}` SET `{$field}` = `{$field}` {$math_operator} 1 WHERE `id` = '{$this->getId()}'");
            }
        }
    }

    //докинуть уникальное значение в поле типа JSON (только для UPDATE | только уникальное значение | только для одномерных массивов)
    public function appendToJson($field, $value){
        if($this->getId()){
            $tblName = get_class($this);
            $field = escapeString($field); $value = escapeString($value);
            self::customQuery("UPDATE `{$tblName}` SET `{$field}` = JSON_ARRAY_APPEND(`{$field}`, '$', '{$value}') WHERE `id` = '{$this->getId()}' AND NOT JSON_CONTAINS(`{$field}`, '{$value}')");
        }
    }

    //убрать значение из поля типа JSON (только для UPDATE | только для одномерных массивов)
    public function removeFromJson($field, $value){
        if($this->getId()){
            $tblName = get_class($this);
            $field = escapeString($field); $value = escapeString($value);
            self::customQuery("UPDATE `{$tblName}` SET `{$field}` = JSON_REMOVE(`{$field}`, JSON_UNQUOTE(JSON_SEARCH(`{$field}`, 'one', '{$value}'))) WHERE `id` = '{$this->getId()}'");
        }
    }
}