<?php
/**
 * Created by PhpStorm.
 * User: Александров Олег
 * Date: 08.07.19
 * Time: 10:23
 */

//модуль поиска по базе данных и выборки строк
class selector{
    private $sql;
    private $module;
    private $onlyOne = false;
    private $cacheInMinutes = 10;

    //todo: add between

    public function __construct($module, $fields = array(), $distinct = false){
        $this->sql = "SELECT SQL_CALC_FOUND_ROWS ";
        if($distinct){ $this->sql .= "DISTINCT ";}
        $this->module = $module;
        $fieldsLine = '';

        if(!$fields) {$fieldsLine .= "*";}
        else{
            foreach($fields as $key=>$field){
                $fieldsLine .= '`'.escapeString($field).'`';
                if($key !== count($fields)-1){ $fieldsLine.=',';}
            }
        }

        $this->sql .= $fieldsLine.' FROM `'.$module.'` WHERE ';
    }

    //добавляет логическое AND в запрос
    public function addAND(){ $this->sql.=' AND ';}

    //добавляет логическое OR в запрос
    public function addOR(){ $this->sql.=' OR ';}

    public function openBracket(){ $this->sql .= ' (';}

    public function closeBracket() { $this->sql .= ') ';}

    public function between($fieldName, $valueFrom, $valueTo){
        $this->sql .= "`".escapeString($fieldName)."` BETWEEN '".escapeString($valueFrom)."' AND '".escapeString($valueTo)."'";
    }

    //равно
    //@param $fieldName - название поля
    //@param $value - значение поля
    public function equals($fieldName, $value){
        $this->sql .= "`".escapeString($fieldName)."` = '".escapeString($value)."'";
    }

    //не равно
    //@param $fieldName - название поля
    //@param $value - значение поля
    public function notequals($fieldName, $value){
        $this->sql .= "`".escapeString($fieldName)."` != '".escapeString($value)."'";
    }

    //больше
    //@param $fieldName - название поля
    //@param $value - значение поля
    public function more($fieldName, $value){
        $this->sql .= "`".escapeString($fieldName)."` > '".escapeString($value)."'";
    }

    //больше или равно
    //@param $fieldName - название поля
    //@param $value - значение поля
    public function eqmore($fieldName, $value, $wrapValueInQuotes = true){
        if($wrapValueInQuotes) {$this->sql .= "`".escapeString($fieldName)."` >= '".escapeString($value)."'";}
        else{ $this->sql .= "`".escapeString($fieldName)."` >= ".escapeString($value);}
    }

    //меньше
    //@param $fieldName - название поля
    //@param $value - значение поля
    public function less($fieldName, $value){
        $this->sql .= "`".escapeString($fieldName)."` < '".escapeString($value)."'";
    }

    //меньше или равно
    //@param $fieldName - название поля
    //@param $value - значение поля
    public function eqless($fieldName, $value){
        $this->sql .= "`".escapeString($fieldName)."` <= '".escapeString($value)."'";
    }

    //проверка на пустое значение
    //@param $fieldName - название поля
    //@param $value - значение поля
    public function isnull($fieldName){
        $this->sql .= "`".escapeString($fieldName)."` IS NULL ";
    }

    //проверка на непустое значение
    //@param $fieldName - название поля
    //@param $value - значение поля
    public function isnotnull($fieldName){
        $this->sql .= "`".escapeString($fieldName)."` IS NOT NULL ";
    }
    
    public function like($fieldName, $value){
        $this->sql .= "`".escapeString($fieldName)."` LIKE '".escapeString($value)."'";
    }

    public function not_like($fieldName, $value){
        $this->sql .= "`".escapeString($fieldName)."`NOT LIKE '".escapeString($value)."'";
    }

    public function in($fieldName,$value){
        if(is_array($value)){
            foreach($value as $i=>$val){
                if(!is_numeric($val)){ $value[$i] = "'".escapeString($val)."'";}
            }
            $value = implode(',',$value);
            $this->sql .= "`".escapeString($fieldName)."` IN (".$value.")";
        }
        else{ $this->sql .= "`".escapeString($fieldName)."` IN (".escapeString($value).")";}
    }

    public function in_cycle($fieldName,$value){
        $newValue = ''; $lastIndex = count($value)-1;
        foreach($value as $key=>$val){
            $newValue .= "'".escapeString($val)."'";
            if($lastIndex > $key) $newValue .= ",";
        }
        $this->sql .= "`".escapeString($fieldName)."` IN (".$newValue.")";
    }

    public function not_in($fieldName,$value){
        $value = implode(',',$value);
        $this->sql .= "`".escapeString($fieldName)."` NOT IN (".escapeString($value).")";
    }

    public function json_contains($fieldName,$jsonFieldName,$value){
        if(!is_numeric($value)){ $value = "'".$value."'";}
        $this->sql .= "JSON_CONTAINS (`".$fieldName."`, JSON_OBJECT('".$jsonFieldName."',".escapeString($value).")) ";
    }

    public function json_contains_simple($fieldName, $fieldValue){
        $this->sql .= "JSON_CONTAINS(`".$fieldName."`,'".escapeString('"'.$fieldValue.'"')."', '$')";
    }

    public function json_extract($fieldName,$jsonFieldName,$value){
        $this->sql .= "JSON_EXTRACT(`".$fieldName."`, '".$jsonFieldName."') = '".escapeString($value)."'";
    }

    public function json_extract_not($fieldName,$jsonFieldName,$value){
        $this->sql .= "JSON_EXTRACT(`".$fieldName."`, '".$jsonFieldName."') != '".escapeString($value)."'";
    }

    //установка лимита
    //@param $limit - ограничение числа записей
    //@param $offset - сдвиг на число записей
    public function limit($limit, $offset = false){
        if($limit === 1){
            $this->onlyOne = true;
            $this->sql = str_replace("SQL_CALC_FOUND_ROWS", "", $this->sql);
        }

        if(!$offset){ $this->sql .= ' LIMIT '.(int)$limit;}
        else{ $this->sql .= ' LIMIT '.(int)$offset.', '.(int)$limit;}
    }

    //сортировка
    //@param $fieldName - название поля
    //@param $order - направление сортировки. Может принимать только значения ASC и DESC
    public function order($fieldName, $order){
        if($order == 'ASC' or $order == 'DESC'){
            $this->sql.=' ORDER BY `'.escapeString($fieldName).'` '.$order;
        }
        else if($order == 'RAND'){
            $this->sql.=' ORDER BY RAND()';
        }
    }

    public function setCacheTime($minutes = 10){
        if(is_numeric($minutes)){ $this->cacheInMinutes = $minutes;}
        else{ $this->cacheInMinutes = 0;}
    }

    //запуск собранного запроса
    public function run($count = false, $totalNotRequired = false, $showSQL = false){
        global $selectorCacheRequests, $selectorCacheResults;

        if($showSQL) {var_dump($this->sql);}
        if($totalNotRequired){ $this->sql = str_replace("SQL_CALC_FOUND_ROWS", "", $this->sql);}

        if(!class_exists($this->module)){
            $checkModuleInGuides = guides::getGuideByModuleName($this->module);
            if(!$checkModuleInGuides){ utils::exception('Selector: Type does not exist');}
            else{ $checkModuleInGuides->getGuideObjectClass();}
        }
        $module = new $this->module();

        if($count){     return $module::countRows($this->sql);}
        else{
            if($isUsedRequest = array_search($this->sql, $selectorCacheRequests)){
                $data = $selectorCacheResults[$isUsedRequest];
            }
            else{
                $cacheEnabled = (config::get('options', 'cache') and $this->cacheInMinutes > 0 and !router::$adminMode and !router::isJSON());
                $data = $cacheEnabled ? cache::loadSelector(md5($this->sql), $module) : $module::selectRows($this->sql, $this->onlyOne);
                //данные не пришли
                if($data === 'CACHE_EMPTY_RESULT'){ $data = false;}
                else{
                    if(!$data){
                        //кэш используется
                        if($cacheEnabled and !router::isJSON()){
                            if($data = $module::selectRows($this->sql, $this->onlyOne)){    //пробуем получить их из базы
                                //если запрошен не единственный объект, считаем их общее количество и присобачиваем элемент со сроком жизни
                                if(!$this->onlyOne){
                                    $data['total'] = $this->calcTotal($module);
                                    $data['expires'] = time() + $this->cacheInMinutes * 60;
                                }
                                else{ $data->_objectExpires = time() + $this->cacheInMinutes * 60;}
                                cache::refreshSelector(md5($this->sql), $data);
                            }
                            else{ //данные опять не пришли
                                if(!$this->onlyOne){ $data = array('data'=>array(), 'total'=>0, 'expires' => time() + $this->cacheInMinutes * 60);}   //если запрошен не единственный объект, отдаем пустую рыбу
                                else{               //если запрошен единственный объект, но там false, отправим пустой объект со временем протухания
                                    $EO = new stdClass(); $EO->_isEmpty = true; $EO->_objectExpires = time() + $this->cacheInMinutes * 60;
                                    cache::refreshSelector(md5($this->sql), $EO);
                                }
                            }
                        }
                        //кэш не используется
                        else{
                            if(!$this->onlyOne){ $data = array('data'=>array(), 'total'=>0);}   //если запрошен не единственный объект, отдаем пустую рыбу
                        }
                    }
                    //данные пришли
                    else{
                        //если кэш не используется и запрошен не единственный объект, считаем их общее количество
                        if(!$cacheEnabled and !$this->onlyOne and !$totalNotRequired){
                            $data['total'] = $this->calcTotal($module);
                        }
                    }
                }
                $selectorCacheRequests[] = $this->sql; $selectorCacheResults[] = $data;     //запись в переменную для более быстрого вызова этих данных из памяти в случае повторного запроса за этот вызов
            }

            /*if($short and $data){
                $resultArr = array();
                foreach($data['data'] as $obj){
                    $resultArr[] = array('id'=>$obj->getId(),'name'=>$obj->getName());
                }
                return $resultArr;
            }
            else{ return $data;}*/
            return $data;
        }
    }

    private function calcTotal($module){
        $countRows = $module::customQuery("SELECT FOUND_ROWS() as `totalRows`");
        $countRowsResult = $countRows->fetch_assoc();
        return $countRowsResult['totalRows'];
    }
}