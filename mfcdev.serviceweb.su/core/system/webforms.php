<?php 
    class webforms extends baseModule{

        public static function lists(){
            $sel = new selector('webforms');
            $sel->isnotnull('id');
            return $sel->run();
        }

        public static function add(){
            if(!count($_POST)){ return array();}
            else{
                if(utils::validatePost(array('descr','field_name','email'))){
                    $_POST['name'] = 'webforms_'.utils::translit($_POST['descr']);
                    $_POST['icon_class'] = '';
                    $webformGuide = guides::add(true);
                    if($webformGuide instanceof guides){
                        $webform = new webforms();
                        $webform->setValue('name',$_POST['descr']);
                        $webform->setValue('email', $_POST['email']);
                        $webform->setValue('guide_id', $webformGuide->getId());
                        $webform->commit();
                        return utils::returnSuccess(true,'Форма обратной связи создана', array('redirect'=>'/admin/webforms/lists'));
                    }
                    else{ return $webformGuide;}
                }
                else{ return utils::returnSuccess(false,'Недостаточно данных');}
            }
        }

        public static function edit($itemId = false){
            if($webform = self::getById($itemId)){
                if(!count($_POST)){
                    $guide = guides::getById($webform->getValue('guide_id'));
                    return array('edit'=>$webform, 'guide'=>$guide);
                }
                else{
                    if(utils::validatePost(array('descr','field_name','email'))){
                        $_POST['name'] = 'webforms_'.utils::translit($_POST['descr']);
                        $_POST['icon_class'] = '';

                        $webformGuide = guides::edit($webform->getValue('guide_id'), true);
                        if($webformGuide instanceof guides){
                            $webform->setValue('name',$_POST['descr']);
                            $webform->setValue('email', $_POST['email']);
                            $webform->commit();
                            utils::redirect('/admin/webforms/lists');
                        }
                        else{ return utils::returnSuccess(false,'Не удалось отредактировать связанный справочник');}
                    }
                    else{ return utils::returnSuccess(false,'Недостаточно данных 1');}
                }
            }
        }

        public static function getForm($formId = false, $pageId = false){
            if($webform = self::getById($formId)){
                if($guide = guides::getById($webform->getValue('guide_id'))){
                    return array('form_id'=>$formId, 'pageId'=>$pageId, 'fields'=>$guide->formatFields(), 'email'=>$webform->getValue('email'));
                }
                else{ return array();}
            }
            else{ return array();}
        }

        public static function send($formId = false){
            if(count($_POST)){
                if($webform = self::getById($formId)){
                    if($guide = guides::getById($webform->getValue('guide_id'))){
                        if(!isset($_POST['name'])){
                            $_POST['name'] = 'Сообщение с формы "'.$webform->getName().'" от '.date('d.m.Y H:i:s');
                        }
                        $newFormObject = guides::add_item($guide->getId(), true);

                        if(is_object($newFormObject)){
                            $message = '';

                            foreach($newFormObject->getValue('_guideFields') as $guideField){
                                if($guideField == 'file'){ continue;}
                                $message .= $guideField['descr'].': '.$newFormObject->getValue($guideField['name'])."<br>";
                            }

                            $message .= '<a href="http://'.$_SERVER['SERVER_NAME'].'/admin/guides/edit_item/'.$guide->getId().'/'.$newFormObject->getId().'">Перейти к обращению в админку</a>';
                            utils::sendSimpleEmail('Соообщение с формы '.$webform->getName(), $message, $webform->getValue('email'));

                            return utils::returnSuccess(true, 'Данные успешно отправлены!');
                        }
                        else{   return utils::returnSuccess(false, 'Не удалось отправить данные');}
                    }
                    else{   return utils::returnSuccess(false, 'Неправильная конфигурация формы');}
                }
                else{   return utils::returnSuccess(false, 'Форма не найдена');}
            }
            else{ return utils::returnSuccess(false, 'Данные отсутствуют');}
        }

        public static function del($webformId = false){
            if($webform = self::getById($webformId)){
                if(!isset($_POST['confirm'])){
                    $result = array('confirm'=>1,'webform'=>$webform);
                    return $result;
                }
                else{
                    $guideId = $webform->getValue('guide_id');
                    $guide = guides::getById($guideId);

                    $tableName = 'guide_'.$guide->getName();
                    self::customQuery("DROP TABLE `$tableName`");

                    $guide->deleteObject();
                    $webform->deleteObject();
                    utils::redirect('/admin/webforms/lists');
                }
            }
            else{ return router::err404('Форма не найдена');}
        }
    }
?>