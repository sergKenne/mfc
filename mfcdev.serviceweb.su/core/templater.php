<?php
/**
 * Created by PhpStorm.
 * User: Александров Олег
 * Date: 05.07.19
 * Time: 14:15
 */

class templater{
    private $module;
    private $method;
    private $params;
    private $templateFolder;

    public function __construct($module,$method, $params, $_RESPONSE){
        global $startExecution, $cachedQueriesCount;

        if(isset($_GET['var_dump']) and config::get('debug', 'var_dump')){ var_dump($_RESPONSE); exit;}

        if(router::$adminMode){ $this->templateFolder = 'admin_tpls'; }
        else{ $this->templateFolder = 'templates';}

        $this->module = $module;
        if(substr($method,-6) == '.phtml'){ $this->method = substr($method,0,-6);}
        else {$this->method = $method;}
        $this->params = $params;
        if(isset($_GET['tpl'])){
            $this->getTemplate($module, $method, $_RESPONSE);
        }
        else{  $this->getTemplate('default',false, $_RESPONSE);}

        if(config::get('debug', 'benchmarks')){
            global $mysqli;
            $queriesList = $mysqli->query("SHOW PROFILES"); $time = 0;
            $showQueryList = config::get('debug', 'sql_query_list');
            echo "\n<!--";

            while($row = $queriesList->fetch_assoc()){
                if($showQueryList) {echo "\n#".$row['Duration'].' secs, query: '.$row['Query'];}
                $time += $row['Duration'];
            }

            echo "\n";
            printf('Execution: %.4F sec. / ', (microtime(true) - $startExecution));
            echo "\nTotal queries: ".$queriesList->num_rows.', SQL execution time: '.$time." secs\n";
            echo 'Cached queries: objects - ', $cachedQueriesCount['objects'],' , selectors - ', $cachedQueriesCount['selectors'] .' / ';
            echo 'Memory usage: ', floatval(memory_get_usage(true) / 1024), ' KBytes-->';
        }
    }

    public function getTemplate($module = false, $method = false, $_RESPONSE = array()){
        if($module === 'default'){
            include($this->templateFolder.'/default.phtml');
        }
        elseif($module === 'err403' or $module === 'err404'){
            include($this->templateFolder.'/'.$module.'.phtml');
        }
        else{
            $tplFile = $this->templateFolder.'/'.$module.'/'.$method.'.phtml';
            if(file_exists($tplFile)){
                try{    include($tplFile);}
                catch(\Throwable $err){
                    if(config::get('debug', 'show_tpl_errors')){ var_dump($err);}
                    else{
                        ob_start();
                        var_dump($err);
                        $logData = ob_get_contents();
                        ob_end_clean();
                        if(!is_dir('./errors')){ mkdir('./errors');}
                        file_put_contents('./errors/'.date('dmY_His_').$_SERVER['REMOTE_ADDR'].'.txt',$logData);
                    }
                }
            }
            else{
                if(file_exists($this->templateFolder.'/tpl_error.phtml')){
                    $_RESPONSE['_tplFile'] = $tplFile;
                    include($this->templateFolder.'/tpl_error.phtml');
                }
                else{
                    utils::exception('Файл шаблона ['.$tplFile.'] не найден.', $_RESPONSE);
                }
            }
        }
    }

    public function getTemplateByFile($tplFile = false, $_RESPONSE = array()){
        if(file_exists($tplFile)){
            try{    include($tplFile);}
            catch(\Throwable $err){
                ob_start();
                var_dump($err);
                $logData = ob_get_contents();
                ob_end_clean();
                if(!is_dir('./errors')){ mkdir('./errors');}
                file_put_contents('./errors/'.date('dmY_His_').$_SERVER['REMOTE_ADDR'].'.txt',$logData);
            }
        }
        else{
            echo '<!--File not found: '.$tplFile.'-->';
        }
    }

    public function getId($object){
        if(is_object($object) and method_exists($object, 'getId')){
            return $object->getId();
        }
        else{
            echo '<!--Not object-->';
            return '';
        }
    }

    public function getName($object){
        if(is_object($object) and method_exists($object, 'getName')){
            return $object->getName();
        }
        else{
            echo '<!--Not object-->';
            return '';
        }
    }

    /*special chars - если true - заменяет опасные HTML-сущности на их экранированные аналоги*/
    public function getValue($object, $fieldName, $specialChars = true, $hideError = false){
        if(is_object($object) and method_exists($object, 'getValue')){
            return $object->getValue($fieldName, false, $specialChars);
        }
        else{
            if(!$hideError){
                echo '<!--Not object -->';
            }
            return '';
        }
    }

    public function callFunc($object, $funcName, $params = array()){
        if(is_object($object) and method_exists($object, $funcName)){
            return call_user_func_array([$object, $funcName], $params);
        }
        else{
            echo '<!--Not object-->';
            return '';
        }
    }

    public function getInputType($field){
        $type = $field['Type'];
        switch($type){
            case 'date': return 'date';
            case 'tinyint(1)': return 'checkbox';
            case strpos($type,'enum'):{
                $enum = explode(",", str_replace(array("enum(", ")", "'"), "", $type));
                return $enum;
            }
            default: return 'text';
        }
    }

    public function formatDate($date = false, $addTime = false){
        if(!$date){ return '';}
        $months = array('','янв','фев','мар','апр','мая','июн','июл','авг','сен','окт','ноя','дек');
        if(strpos($date,'0000')!==false){   return '00.00.0000';}
        else{
            if(strpos($date,':')){ $newformat = DateTime::createFromFormat('Y-m-d H:i:s', $date);}
            else {$newformat = DateTime::createFromFormat('Y-m-d', $date);}
        }

        if(!$newformat){ return '00.00.0000';}
        else {
            if($addTime){ return $newformat->format('j '.$months[$newformat->format('n')].' Y \г. \в H:i:s');}
            else{ return $newformat->format('j '.$months[$newformat->format('n')].' Y \г.');}
        }
    }

    public function getTimeWithoutDate($date){
        return substr($date,11,5);
    }

    public function getMonthByNumber($number){
        $monthes = array('','Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь','Ноябрь','Декабрь');
        return @$monthes[(int)$number];
    }

    public function macros($module, $method, $params = array()){
        return router::callModuleMethod($module,$method,$params,true);
    }

    public function optSel($cond1, $cond2, $attribute1 = 'selected', $attribute2 = ''){
        return ($cond1 == $cond2) ? $attribute1: $attribute2;
    }

    public function getSetting($settingName = false){
        return settings::getSetting($settingName);
    }

    public function addZeros($number, $numberLengthWithZeros){
        $number = (string)$number;                                                       //преобразуем число в строку
        while(($numberLengthWithZeros - strlen($number))>0){   $number = "0".$number;}         //пока желаемая длина числа с нулями минус длина самого числа больше 0 - добавлять к числу нули
        return $number;                                                                      //вернуть число с нулями
    }

    public function renderOptions($guideId, $addEmpty = '', $emptyAttributes = false, $selectedId = false, $fieldNameInOption = false){
        if(!$emptyAttributes){ $emptyAttributes = 'selected disabled';}
        $items = $this->macros('guides','list_items', array($guideId, false, ($fieldNameInOption), false, false, true));
        $optionsHTML = ($addEmpty) ? '<option value="0" '.$emptyAttributes.'>'.$addEmpty.'</option>' : '';
        foreach($items['data'] as $item){
            if(is_array($selectedId)){ $selected = (in_array($item->getId(), $selectedId)) ? 'selected':'';}
            else{ $selected = ($selectedId == $item->getId()) ? 'selected':'';}
            $optionText = ($fieldNameInOption) ? $this->getValue($item, $fieldNameInOption) : $this->getName($item);
            $optionsHTML .= '<option value="'.$this->getId($item).'" '.$selected.'>'.$optionText.'</option>';
        }
        return $optionsHTML;
    }

    public function showNamesOfArrObjects($data, $delimiter = ', ', $field = 'name'){
        $text = '';
        if(is_array($data)){
            foreach($data as $i=>$item){
                if($field == 'name'){ $text .= $this->getName($item);}
                else{ $text .= $this->getValue($item, $field);}

                if($i < count($data)-1){ $text .= $delimiter;}
            }
        }
        return $text;
    }

    public static function declension($num, $form_for_1, $form_for_2, $form_for_5){
        $num = abs($num) % 100; // берем число по модулю и сбрасываем сотни (делим на 100, а остаток присваиваем переменной $num)
        $num_x = $num % 10; // сбрасываем десятки и записываем в новую переменную
        if ($num > 10 && $num < 20) // если число принадлежит отрезку [11;19]
            return $form_for_5;
        if ($num_x > 1 && $num_x < 5) // иначе если число оканчивается на 2,3,4
            return $form_for_2;
        if ($num_x == 1) // иначе если оканчивается на 1
            return $form_for_1;
        return $form_for_5;
    }
}