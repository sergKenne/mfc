<?php 
    class monitoring extends baseModule{

        /*
            Это код вашего нового модуля.
            Функция lists отвечает за отображение списка элементов
            Функция add - за добавление нового элемента
            Функция edit - за редактирование существующего элемента
            Функция view - за просмотр определенного элемента
            Вы можете как угодно редактировать эти функции, а также, при необходимости, писать свои собственные.
        */

        public static function lists($filterById = array(), $forceAllFields = false){
            if(isset($_GET['log'])){
                return guides::list_items(get_called_class(), $filterById, $forceAllFields);
            }
            else{
                //получить инфу о пользователе
                $commonData = [];
                exec('whoami', $whoami);
                $commonData['whoami'] = $whoami[0];

                //получить инфу о диске
                exec("df -h \"$@\" | grep -E '^/' ", $diskData);

                foreach($diskData as $diskLine){
                    $diskInfo = explode(' ',preg_replace('/\s+/', ' ',$diskLine));
                    $commonData['disks'][] = array(
                        'name'=>$diskInfo[0],
                        'total'=>$diskInfo[1],
                        'free'=>$diskInfo[3]
                    );
                }

                //получить инфу о памяти
                exec('free -h', $ram);
                if(isset($ram[1])){
                    $ramInfo = explode(' ',preg_replace('/\s+/', ' ',$ram[1]));
                    $commonData['ram'] = array('total'=>$ramInfo[1], 'free'=>$ramInfo[6]);
                }
                $commonData['cpu'] = sys_getloadavg();

                return ['resources'=>$commonData];
            }
        }

        public static function add(){
            return guides::add_item(get_called_class());
        }

        public static function edit($itemId = false){
            return guides::edit_item(get_called_class(), $itemId);
        }

        public static function view($object = false, $callFromPage = false){
            if(is_numeric($object)){ $object = monitoring::getById($object);}
            if($object instanceof monitoring){
                if(!$callFromPage){
                    $page = $object->getConnectedPage();
                    if($page){
                        $pageUrl = $page->getValue('url');
                        if($pageUrl){ utils::redirect('/'.$pageUrl); }
                        else {return array('page'=>$page, 'source'=>$object);}
                    }
                    else{   return array('page'=>false, 'source'=>$object);}
                }
                else{ return $object;}
            }
            else{ return router::err404();}
        }

        public static function backup($mode = 'lists', $what = false){
            global $configINI;
            $folder = '/var/www/u0718690/data/backups';

            switch($mode){
                case 'add':{
                    set_time_limit(9999);
                    ini_set('max_execution_time', 9999);

                    $date = date('d-m-Y_H-i-s');
                    $backupFilesName = $what.'_'.$date;
                    //создать папку для этого бэкапа
                    if(!is_dir($folder)){    exec('mkdir '.$folder, $test);}

                    switch($what){
                        case 'files':{
                            $siteDir = getcwd();
                            $ext = '.zip';
                            exec('zip '.$folder.'/'.$backupFilesName.$ext.' -r '.$siteDir.'  >/dev/null 2>&1 &', $output);
                            break;
                        }
                        case 'db':{
                            $ext = '.sql.gz';
                            exec('mysqldump -u '.$configINI['DB']['db_user'].' -p'.$configINI['DB']['db_password'].' '.$configINI['DB']['db_name'].' | gzip > '.$folder.'/'.$backupFilesName.$ext.' &', $output);
                            break;
                        }
                    }
                    header('Location: /admin/monitoring/backup/lists');
                    break;
                }
                case 'lists':{
                    if(!is_dir($folder)){ return ['mode'=>$mode, 'files'=>[]];}
                    $backups = array_slice(scandir($folder),2); $backupData = [];
                    //показывать файлы
                    foreach($backups as $backup){
                        if($backup == '.' or $backup == '..'){ continue;}
                        else{
                            $size = round((filesize($folder.'/'.$backup)) / 1048576, 0);
                            if(strpos($backup,'db')!==false){
                                $backupData[] = array(
                                    'type'=>'База данных',
                                    'full_name'=>$backup,
                                    'size'=>$size,
                                    'date'=>substr($backup,3)
                                );
                            }
                            else{
                                $backupData[] = array(
                                    'type'=>'Архив файлов',
                                    'full_name'=>$backup,
                                    'size'=>$size,
                                    'date'=>substr($backup,6)
                                );
                            }
                        }
                    }

                    return ['mode'=>$mode, 'files'=>$backupData];
                }
                case 'download':{
                    if(isset($_GET['backup'])){
                        if(file_exists($folder.'/'.$_GET['backup'])){
                            header('Content-Type: application/x-tgz');
                            header('Content-Length: '.filesize('/home/backups/'.$_GET['backup']));
                            header("Content-Disposition: attachment; filename=\"".$_GET['backup']."\"");

                            ob_clean();
                            ob_end_flush();
                            $handle = fopen($folder.'/'.$_GET['backup'], "rb");
                            while (!feof($handle)) {    echo fread($handle, 1000);}
                            exit;
                        }
                        else{   http_response_code(404); echo 'File not found'; exit;}
                    }
                    else{ http_response_code(404); echo 'File not found'; exit;}
                    break;
                }
                case 'del':{
                    $filename = $_GET['backup'];
                    if(strpos($filename,'/')!==false or strpos($filename,'\\')!==false){ header('Location: /admin/monitoring/backup'); exit;}
                    else{
                        if(is_file($folder.'/'.$filename)){ unlink($folder.'/'.$filename);}
                        header('Location: /admin/monitoring/backup'); exit;
                    }
                }
            }
        }
    }
?>