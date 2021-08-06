<?php
/**
 * Created by PhpStorm.
 * User: Александров Олег
 * Date: 05.07.19
 * Time: 14:36
 */

//утилиты
class utils{
    //проверка наличия всех данных в POST-запросе
    //@param $requiredFields - массив полей, которые нужно проверить
    public static function validatePost($requiredFields = array()){
        foreach($requiredFields as $requiredField){
            if(isset($_POST[$requiredField]) and $_POST[$requiredField]!=''){ continue;}
            else{   return false;}
        }
        return true;
    }

    //вернуть ответ в формате JSON
    //@param $data - массив данных
    public static function returnJSON($data){
        header('Content-type: text/json');
        if(is_array($data)){
            $newData = self::observeArray($data);
            echo json_encode($newData);
        }
        else {echo $data;}
        exit;
    }

    private static function observeArray($arr){
        foreach($arr as $key=>$value){
            if(is_object($value)){  $arr[$key] = self::removeServiceDataForJson($value, 0);}
            elseif(is_array($value)){   $arr[$key] = self::observeArray($value);}
        }
        return $arr;
    }

    private static function removeServiceDataForJson($object, $depth){
        $maxDepth = isset($_GET['max_depth']) ? (int)$_GET['max_depth'] : 4;
        $depth++;
        $object = clone($object);
        if(isset($object->_allFields)) unset($object->_allFields);
        $object->depth = $depth;
        if($depth < $maxDepth){
            foreach(get_object_vars($object) as $fieldName=>$propValue){
                if($fieldName == '_guideFields'){ continue;}
                if(isset($object->_guideFields[$fieldName])){
                    if($object->_guideFields[$fieldName]['type'] == 'guide'){
                        $gotValue = $object->getValue($fieldName);
                        if(is_object($gotValue)){ $object->$fieldName = self::removeServiceDataForJson($gotValue, $depth);}
                        elseif(is_array($gotValue)){
                            foreach($gotValue as $i=>$objectInArr){
                                if(is_object($objectInArr)){ $object->$fieldName[$i] = self::removeServiceDataForJson($objectInArr, $depth);}
                            }
                        }
                    }
                }
            }
        }
        return $object;
    }

    public static function returnSuccess($success = false, $info = '', $additionalInfo = array()){
        return array_merge(array('success'=>$success, 'info'=>$info),$additionalInfo);
    }

    //редиректы
    //@param $addr - на какой адрес редиректить
    //@param @text - какой текст отобразить на странице, либо в GET-параметре
    //@param $refresh - задержка перед редиректом
    public static function redirect($addr = false, $text = false, $refresh = false){
        if(!$addr){
            $addr = parse_url($_SERVER['HTTP_REFERER'])['path'];
        }

        if($refresh){
            header('Refresh: '.$refresh.'; url=http://'.$_SERVER['HTTP_HOST'].$addr);
            if($text){  echo $text;}
        }
        else{
            $headerData = 'Location: http://'.$_SERVER['HTTP_HOST'].$addr;
            if($text){ $headerData.='?_err='.urlencode($text);}
            header($headerData);
        }
        exit;
    }

    public static function exception($err){
        echo 'Fatal: '.$err.'<br><br>';
        if(router::$currentUser->isAdmin()){debug_print_backtrace();}
        exit();
    }

    public static function mysqlError($query){
        global $mysqli;
        $error = $mysqli->error;
        if($error){
            file_put_contents('sqlerrors.txt','['.date('d.m.Y H:i:s').'] Error: '.$error.PHP_EOL.'query: '.$query.PHP_EOL.PHP_EOL,FILE_APPEND);
        }
        return $error;
    }

    public static function pregDigits($value){
        return preg_replace( '/[^0-9]/', '', $value);
    }

    public static function POST($url,$headers,$data){
        $context = stream_context_create(array(
            'http'=>array(
                'header'=>$headers,
                'method'=>'POST',
                'content'=>$data
            )
        ));
        return file_get_contents($url,false,$context);
    }

    public static function dateToMySQLFormat($date){
        return date('Y-m-d',strtotime($date));
    }

    public static function generateRandomString($length = 12){
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public static function numberToString($L){
        //http://php.spb.ru/php/propis.html
        $_1_2[1]="одна "; $_1_2[2]="две ";

        $_1_19[1]="один ";
        $_1_19[2]="два ";
        $_1_19[3]="три ";
        $_1_19[4]="четыре ";
        $_1_19[5]="пять ";
        $_1_19[6]="шесть ";
        $_1_19[7]="семь ";
        $_1_19[8]="восемь ";
        $_1_19[9]="девять ";
        $_1_19[10]="десять ";

        $_1_19[11]="одиннацать ";
        $_1_19[12]="двенадцать ";
        $_1_19[13]="тринадцать ";
        $_1_19[14]="четырнадцать ";
        $_1_19[15]="пятнадцать ";
        $_1_19[16]="шестнадцать ";
        $_1_19[17]="семнадцать ";
        $_1_19[18]="восемнадцать ";
        $_1_19[19]="девятнадцать ";

        $des[2]="двадцать ";
        $des[3]="тридцать ";
        $des[4]="сорок ";
        $des[5]="пятьдесят ";
        $des[6]="шестьдесят ";
        $des[7]="семьдесят ";
        $des[8]="восемьдесят ";
        $des[9]="девяносто ";

        $hang[1]="сто ";
        $hang[2]="двести ";
        $hang[3]="триста ";
        $hang[4]="четыреста ";
        $hang[5]="пятьсот ";
        $hang[6]="шестьсот ";
        $hang[7]="семьсот ";
        $hang[8]="восемьсот ";
        $hang[9]="девятьсот ";

        $namerub[1]="рубль ";
        $namerub[2]="рубля ";
        $namerub[3]="рублей ";

        $nametho[1]="тысяча ";
        $nametho[2]="тысячи ";
        $nametho[3]="тысяч ";

        $namemil[1]="миллион ";
        $namemil[2]="миллиона ";
        $namemil[3]="миллионов ";

        $namemrd[1]="миллиард ";
        $namemrd[2]="миллиарда ";
        $namemrd[3]="миллиардов ";

        $kopeek[1]="копейка ";
        $kopeek[2]="копейки ";
        $kopeek[3]="копеек ";

        $semantic = function($i,&$fem,$f) use ($_1_2, $_1_19, $des, $hang){
            $words="";
            if($i >= 100){
                $jkl = intval($i / 100);
                $words.=$hang[$jkl];
                $i%=100;
            }
            if($i >= 20){
                $jkl = intval($i / 10);
                $words.=$des[$jkl];
                $i%=10;
            }
            switch($i){
                case 1: $fem=1; break;
                case 2:
                case 3:
                case 4: $fem=2; break;
                default: $fem=3; break;
            }
            if( $i ){
                if( $i < 3 && $f > 0 ){
                    if ( $f >= 2 ) {    $words.=$_1_19[$i];}
                    else           {    $words.=$_1_2[$i];}
                }
                else {  $words.=$_1_19[$i];}
            }
            return $words;
        };
        $s=" ";
        $kop=intval( ( $L*100 - intval( $L )*100 ));
        $L=intval($L);
        if($L>=1000000000){
            $many=0;
            $s.= $semantic($partNum = intval($L / 1000000000),$many,3);
            $s.=' '.$namemrd[$many];
            $L%=1000000000;
        }

        if($L >= 1000000){
            $many=0;
            $s.= $semantic($partNum = intval($L / 1000000),$many,2);
            $s.=' '.$namemil[$many];
            $L%=1000000;
            if($L==0){  $s.="рублей ";}
        }

        if($L >= 1000){
            $many=0;
            $s.= $semantic($partNum = intval($L / 1000),$many,1);
            $s.=' '.$nametho[$many];
            $L%=1000;
            if($L==0){  $s.="рублей ";}
        }

        if($L != 0){
            $many=0;
            $s.= $semantic($L,$many,0);
            $s.=' '.$namerub[$many];
        }

        if($kop > 0){
            $many=0;
            $semantic($kop,$many,1);
            $s.=$kop.' '.$kopeek[$many];
        }
        else {  $s.=" 00 копеек";}
        return self::capitalFirstLetter(trim($s));
    }

    public static function capitalFirstLetter($string){
        $strlen = mb_strlen($string);
        $firstChar = mb_substr($string, 0, 1);
        $then = mb_substr($string, 1, $strlen - 1);
        return mb_strtoupper($firstChar) . $then;
    }

    public static function isAssoc(array $arr){
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    public static function getRowsLimit($limit = false){
        if(!$limit){    $limit = (isset($_GET['limit'])) ? (int)$_GET['limit'] : 10;}
        if($limit > (int)config::get('options','max_row_limit')) { $limit = (int)config::get('options','max_row_limit');}
        return $limit;
    }    

    public static function sendSimpleEmail($title, $message, $email){
        if(config::get('phpmailer', 'enabled')){
            require_once('./core/libs/phpmailer/Exception.php');
            require_once('./core/libs/phpmailer/PHPMailer.php');
            require_once('./core/libs/phpmailer/SMTP.php');

            $mailer = new \PHPMailer\PHPMailer\PHPMailer();
            $mailer->isSMTP();
            $mailer->SMTPDebug = 0;
            $mailer->Host = config::get('phpmailer', 'host');
            $mailer->SMTPAuth = true;
            $mailer->Username = config::get('phpmailer', 'user');
            $mailer->Password = config::get('phpmailer', 'password');
            $mailer->Port = (int)config::get('phpmailer', 'port');

            $mailer->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );

            $mailer->setFrom(config::get('phpmailer', 'user'));
            $mailer->addAddress($email);
            $mailer->isHTML(true);
            $mailer->CharSet = 'UTF-8';
            $mailer->Subject = $title;
            $mailer->Body = $message;
            return $mailer->send();
        }
        else{
            $from = config::get('mail', 'from');
            $headers = 'From: '.$from. "\r\n" .
                'Content-Type: text/html; charset=utf8'."\r\n".
                'X-Mailer: PHP/' . phpversion();
            mail($email,$title,$message,$headers, "-f $from");
        }
    }

    public static function blurImage($image = false, $radius, $sigma){
        if(!($image instanceof Imagick)){
            $image = new Imagick($image);
        }
        $image->blurimage($radius, $sigma);
        return $image;
    }

    public static function cropImage($image = false, $width, $height){
        if(!($image instanceof Imagick)){
            $image = new Imagick($image);
        }
        $image->cropthumbnailimage($width,$height);
        return $image;
    }

    public static function resizeImage($image = false, $width, $height){
        if(!($image instanceof Imagick)){
            $image = new Imagick($image);
        }
        $image->resizeimage($width,$height, imagick::FILTER_LANCZOS, 1);
        return $image;
    }

    public static function getVideo($address){
        $youtu_be = strpos($address,'youtu.be/');                       //ищем в адресе слово youtu.be/
        if($youtu_be!==false){                                          //если нашли
            $video_id = substr($address,$youtu_be+9,11);                       //получаем все, что после youtu.be/
        }
        else{                                                           //если не нашли
            $start = strpos($address,"watch?v=");                           //ищем watch?v=
            if($start!==false){                                             //если нашли
                $video_id = substr($address,$start+8,11);                          //получаем все, что идет после этого
            }
            else{
                $start = strpos($address,'youtube.com/embed/');
                if($start!==false){
                    $video_id = substr($address,$start+18,11);
                }
                else {return false;}
            }                                          //не нашли - возвращаем false
        }
        //генерируем ссылки на основе полученного video_id
        //(если сюда дошло выполнение)
        $result=array();

        $result['original_link'] = $address;                            //оригинальная ссылка
        $result['video_id'] = $video_id;                                //id видео
        $result['preview'] = 'https://img.youtube.com/vi/'.$video_id.'/0.jpg';//превьюха
        $result['iframe'] = 'https://www.youtube.com/embed/'.$video_id; //embed для iframe
        $result['type'] = 'youtube';
        return $result;
    }
    
    public static function translit($string, $space = '_'){
        $fromUpper = ['Э', 'Ч', 'Ш', 'Ё', 'Ё', 'Ж', 'Ю', 'Ю', 'Я', 'Я', 'А', 'Б', 'В', 'Г', 'Д', 'Е', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Щ', 'Ъ', 'Ы', 'Ь'];
        $fromLower = ['э', 'ч', 'ш', 'ё', 'ё', 'ж', 'ю', 'ю', 'я', 'я', 'а', 'б', 'в', 'г', 'д', 'е', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'щ', 'ъ', 'ы', 'ь'];
        $toLower = ['e', 'ch', 'sh', 'yo', 'jo', 'zh', 'yu', 'ju', 'ya', 'ja', 'a', 'b', 'v', 'g', 'd', 'e', 'z', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'w', '~', 'y', ""];

        $space = $space ? addcslashes($space, ']/\$') : '_';
        $string = str_replace($fromLower, $toLower, $string);
        $string = str_replace($fromUpper, $toLower, $string);
        $string = mb_strtolower($string);
        $string = preg_replace("/([^A-z0-9_\-]+)/", $space, $string);
        $string = preg_replace("/[\/\\\',\t`\^\[\]]*/", '', $string);
        $string = str_replace(chr(8470), '', $string);
        $string = preg_replace("/[ \.]+/", $space, $string);
        $string = preg_replace('/([' . $space . ']+)/', $space, $string);
        $string = trim(trim($string), $space);
        return $string;
    }

    public static function writeINIFile($assoc_arr, $path, $has_sections=FALSE) {
        $content = "";
        if ($has_sections) {
            foreach ($assoc_arr as $key=>$elem) {
                $content .= "[".$key."]\n";
                foreach ($elem as $key2=>$elem2) {
                    if(is_array($elem2))
                    {
                        for($i=0;$i<count($elem2);$i++)
                        {
                            $content .= $key2."[] = \"".$elem2[$i]."\"\n";
                        }
                    }
                    else if($elem2=="") $content .= $key2." = \n";
                    else $content .= $key2." = \"".$elem2."\"\n";
                }
            }
        }
        else {
            foreach ($assoc_arr as $key=>$elem) {
                if(is_array($elem))
                {
                    for($i=0;$i<count($elem);$i++)
                    {
                        $content .= $key."[] = \"".$elem[$i]."\"\n";
                    }
                }
                else if($elem=="") $content .= $key." = \n";
                else $content .= $key." = \"".$elem."\"\n";
            }
        }

        if(!$path){ return $content;}

        if (!$handle = fopen($path, 'w')) {
            return false;
        }

        $success = fwrite($handle, $content);
        fclose($handle);

        return $success;
    }

    public static function readINIFile($path = false){
        if(file_exists($path)){
            return parse_ini_file($path, true);
        }
        else{ return false;}
    }

    public static function recursiveDelDir($src) {
        if(is_dir($src)){
            $dir = opendir($src);
            while(false !== ($file = readdir($dir))) {
                if (($file != '.') && ($file != '..')) {
                    $full = $src.'/'.$file;
                    if (is_dir($full)) {    self::recursiveDelDir($full);}
                    elseif(file_exists($full)){  unlink($full);}
                }
            }
            closedir($dir);
            rmdir($src);
        }
    }

    public static function dateDiff($date1 = false, $date2 = false){
        if($date1 and $date2){
            $date1 = new DateTime($date1);
            $date2 = new DateTime($date2);
            $diff = $date1->diff($date2);
            $diffInDays = $diff->format('%r%a');
            return ($diffInDays < 0) ? 0 : $diffInDays;
        }
        else {return '';}
    }

    public static function formatDate($date = false, $addTime = false){
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

    public static function checkCaptcha($response = false){
        if(config::get('recaptcha','enabled')){
            $response = json_decode(
                self::POST('https://www.google.com/recaptcha/api/siteverify',
                    ['Content-Type: application/x-www-form-urlencoded'],
                    http_build_query([
                        'secret'=>config::get('recaptcha','private_key'),
                        'response'=>$response,
                        'remoteip'=>$_SERVER['REMOTE_ADDR']
                    ])
                ),true);

            if(is_array($response) and isset($response['success']) and $response['success'] === true){  return true;}
            else{ return false;}
        }
        else{ return true;}
    }

    //закрыть незакрытые теги
    //@param text - текст с тегами
    public static function closeTags($text) {
        $ignore_tags = array('img', 'br', 'hr');
        if(preg_match_all("/<(\/?)(\w+)/", $text, $matches, PREG_SET_ORDER)) {     // найдем все тэги (и откывающиеся и закрывающиеся)
            $opened_tags_stack = array();
            foreach ($matches as $tag) {                                      // Цикл по всем найденным
                $tag_name = strtolower($tag[2]);
                if ($tag[1]) {                                                      // Если тэг закрывается то удаляем из стека
                    if (end($opened_tags_stack) == $tag_name) array_pop($opened_tags_stack);
                } else {                                                            // Если тэг открывается и он не одиночный, то кладем в стек
                    if (!in_array($tag_name, $ignore_tags)) array_push($opened_tags_stack, $tag_name);
                }
            };
            while ($tag = array_pop($opened_tags_stack)) {
                $text .= "</$tag>";
            }
        }
        return $text;
    }

}