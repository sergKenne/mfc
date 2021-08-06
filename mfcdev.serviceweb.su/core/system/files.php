<?php
/**
 * Created by PhpStorm.
 * User: Stardisk
 * Date: 05.09.19
 * Time: 11:58
 */

class files extends baseModule{
    protected $user_id;

    public function createFile($fileName, $userId = false, $link = false){
        if(!$userId){ $userId = router::$currentUser->getId();}
        $this->setValue('user_id',$userId);
        $this->setValue('name', $fileName);
        //если пытаемся сохранить файл не из инпута
        if($link){
            $this->setValue('link', $link);
            $this->setValue('size', filesize($link));
            $this->setValue('mime', mime_content_type($link));
            $fileId = $this->commit();
            return array(
                'success'=>true,
                'file'=>$this,
                'fileId'=>$fileId
            );
        }
    }

    //$details: module, objectId, field
    public function upload($fileFieldName, $directory, $forceDirectory = false, $mimeType = false, $details = array()){
        if(!$forceDirectory) {$directory = './files/user_files/'.$this->user_id.'/'.$directory;}
        if(!is_dir($directory)){ mkdir($directory,0777,true);}

        if(isset($_FILES[$fileFieldName]) and !$_FILES[$fileFieldName]['error']){
            if($_FILES[$fileFieldName]['size']>104857600) {return array('success'=>false, 'info'=>'Размер файла превышает 100 МБ');}
            else{
                if($mimeType and strpos($_FILES[$fileFieldName]['type'],$mimeType)!==0){
                    return array('success'=>false,'info'=>'Некорректный формат файла');
                }
                else{
                    $ext = pathinfo($_FILES[$fileFieldName]['name'])['extension'];
                    $newFileName = utils::generateRandomString().'_'.time().'.'.$ext;
                    if(move_uploaded_file($_FILES[$fileFieldName]['tmp_name'],$directory.'/'.$newFileName)){
                        $this->setValue('link',$directory.'/'.$newFileName);
                        $this->setValue('size',$_FILES[$fileFieldName]['size']);
                        $this->setValue('mime',$_FILES[$fileFieldName]['type']);
                        $this->setValue('details',$details);
                        $this->setValue('shared_to', array(0));
                        $fileId = $this->commit();

                        return array(
                            'success'=>true,
                            'file'=>$this,
                            'fileId'=>$fileId
                        );
                    }
                    else{
                        return array(
                            'success'=>false,
                            'info'=>'Не удалось загрузить файл'
                        );
                    }
                }
            }
        }
        else{return array('success'=>false,'info'=>'Не выбран файл');}
    }

    public static function multiUpload($fileFieldName, $directoryName, $forceDirectory = false, $mimeType = false, $details = array()){
        $result = array();
        if(is_array($_FILES[$fileFieldName]['name'])){
            foreach($_FILES[$fileFieldName]['name'] as $key=>$value){
                if($_FILES[$fileFieldName]['error'][$key]!=0){
                    $result[$key] = array('success'=>false,'info'=>'Файл не выбран');
                }
                else{
                    $file =  new files();
                    $file->createFile($value);

                    if(!$forceDirectory) {$directory = './files/user_files/'.$file->user_id.'/'.$directoryName;}
                    if(!is_dir($directory)){ mkdir($directory,0777,true);}

                    if($_FILES[$fileFieldName]['size'][$key] > 104857600){ $result[$key] = array('success'=>false,'info'=>'Размер превышает 100 Мбайт');}
                    else{
                        if($mimeType and strpos($_FILES[$fileFieldName]['type'][$key],$mimeType)!==0){
                            $result[$key] = array('success'=>false,'info'=>'Некорректный формат файла');
                        }
                        else{
                            $ext = pathinfo($_FILES[$fileFieldName]['name'][$key])['extension'];
                            $newFileName = utils::generateRandomString().'_'.time().'.'.$ext;
                            if(move_uploaded_file($_FILES[$fileFieldName]['tmp_name'][$key],$directory.'/'.$newFileName)){
                                $file->setValue('link',$directory.'/'.$newFileName);
                                $file->setValue('size',$_FILES[$fileFieldName]['size'][$key]);
                                $file->setValue('mime',$_FILES[$fileFieldName]['type'][$key]);
                                $file->setValue('details',$details);
                                $file->setValue('shared_to', array(0));
                                $fileId = $file->commit();

                                $result[$key] = array(
                                    'success'=>true,
                                    'file'=>$file,
                                    'fileId'=>$fileId
                                );
                            }
                        }
                    }
                }
            }
            return $result;
        }
    }

    public static function download($id = false){
        if($id == 0){
            header('Content-Type: image/jpeg');
            header("Content-Disposition: attachment; filename=\"no-image.jpg\"");
            echo readfile('./no-image.jpg'); exit;
        }
        else{
            $file = self::getById($id);
            if($file){
                header('Content-Type: '.$file->getValue('mime'));
                //header('Content-Length: '.filesize($file->getValue('link')));
                header("Content-Disposition: attachment; filename=\"".$file->getName()."\"");
                echo readfile($file->getValue('link')); exit;
            }
            else{ return router::err404();}
        }
    }

    //удаление файла
    public function del($id = false){
        if($this->getId() and router::$currentUser->isAdmin()){     //вызов из объекта
            foreach(glob('./files/img_cache/'.$this->getId().'_*.jpg') as $previewToDel){ unlink($previewToDel);}

            unlink($this->getValue('link'));
            return self::delById($this->getId());
        }
        elseif($id){                                        //вызов по http
            foreach(glob('./files/img_cache/'.$id.'_*.jpg') as $previewToDel){ unlink($previewToDel);}
            $file = self::getById($id);
            if(router::$currentUser->isAdmin()){
                unlink($file->getValue('link'));
                return self::delById($id);
            }
        }
        else{   return router::notEnoughPermissions();}
    }

    //удалить файл из объекта
    public static function delFromObject(){
        if(utils::validatePost(array('fileId','guideId','itemId','field'))){
            if(router::$currentUser->isAdmin()){
                $fileId = (int)$_POST['fileId'];
                foreach(glob('./files/img_cache/'.$fileId.'_*.jpg') as $previewToDel){ unlink($previewToDel);}
                if($file = self::getById($fileId)){
                    unlink($file->getValue('link'));
                    $file->deleteObject();

                    if($editedObject = guides::get_item($_POST['guideId'], $_POST['itemId'])){
                        $field = $_POST['field'];
                        $value = $editedObject->getValue($field);
                        if(is_array($value)){
                            $key = array_search($fileId, $value);
                            if($key !== false){
                                unset($value[$key]);
                                $newArray = array_values($value);
                                $editedObject->setValue($field, $newArray);
                            }
                            else{ return utils::returnSuccess(true, 'файл удален, но не удалось убрать ссылку на него из набора файлов');}
                        }
                        else{   $editedObject->setValue($field, 0);}
                        $editedObject->commit();
                        return utils::returnSuccess(true,'файл удален');
                    }
                    else{ return utils::returnSuccess(true, 'файл удален.');}
                }
                else{ return utils::returnSuccess(false, 'файл не найден');}
            }
            else{ return utils::returnSuccess(false, 'недостаточно прав');}
        }
        else{ return utils::returnSuccess(false, 'недостаточно данных');}
    }

    public function lists($userId = false, $limit = false){
        $page = (isset($_GET['page'])) ? (int)$_GET['page']  : 1;
        $limit = utils::getRowsLimit($limit);

        $sel = new selector('files');
        $sel->isnotnull('id');
        //ид пользователя не указан - подставить ид текущего
        if(!$userId){ $sel->equals('user_id',router::$currentUser->getId());}
        else{
            //если ид пользователя передан, но он не админ- подставить ид текущего
            if(!router::$currentUser->isAdmin()){ $sel->equals('user_id',router::$currentUser->getId());}
            else {
                if($userId == 'all'){ $sel->isnotnull('user_id');}
                else {$sel->equals('user_id',$userId);}
            }
        }

        if(isset($_GET['search'])){
            $sel->addAND();
            $sel->like('name','%'.$_GET['search'].'%');
        }

        $sel->order('create_time','DESC');
        if($limit) { $sel->limit($limit, $limit * ($page-1));}
        return $sel->run();
    }

    public function getFileLink(){
        return substr($this->getValue('link'),1);
    }

    public static function getFileURL($id = false){
        $file = files::getById($id);
        if($file){ return substr($file->getValue('link'),1); }
        else { return '';}
    }

    public function getFileExtension(){
        $pathinfo = pathinfo($this->getValue('link'));
        if(isset($pathinfo['extension'])){ return $pathinfo['extension'];}
        else {return '/no-image.jpg';}
    }

    public static function preview($fileId = false, $width = 100, $height = 100, $mode = 'crop'){
        if(!is_dir('./files/img_cache')){
            mkdir('./files/img_cache',0777,true);
        }

        $fileName = './files/img_cache/'.$fileId.'_'.$width.'_'.$height.'_'.$mode.'.*';
        $checkFile = glob($fileName);
        if($checkFile){ return substr($checkFile[0],1);}
        else{
            $file = files::getById($fileId);

            if(!$file){
                $file = new files();
                $file->setValue('link', './no-image.jpg');
                $file->setValue('mime', 'image/jpg');
                $file->setValue('id', 0);
            }

            if(strpos($file->getValue('mime'),'image/')!==false and strpos($file->getValue('mime'),'svg')===false){
                if($mode == 'resize') { $croppedImg = utils::resizeImage($file->getValue('link'), $width, $height);}
                else{ $croppedImg = utils::cropImage($file->getValue('link'), $width, $height);}

                if($croppedImg){
                    $ext = $file->getFileExtension();
                    $newFileName = substr($fileName,0,-1).$ext;
                    $croppedImg->writeimage($newFileName);
                    return substr($newFileName,1);
                }
                else{ return '/no-image.jpg';}
            }
            else{ return '/file-not-image.jpg';}
        }
    }
}