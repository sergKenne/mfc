<?php 
    class settings extends baseModule{

        public static function lists(){
            $sel = new selector('settings');
            $sel->isnotnull('id');
            return $sel->run();
        }

        public static function add(){
            if(count($_POST)>0 and utils::validatePost(array('name','type','descr'))){
                if(!preg_match("/^[a-zA-Z0-9\_]+$/", $_POST['name'])){
                    return utils::returnSuccess(false,$_POST['name']. ' - некорректное название поля. Оно должно содержать только латиницу, цифры и знак подчеркивания');
                }

                $setting = new settings();
                $setting->setValue('name', $_POST['name']);
                $setting->setValue('type', $_POST['type']);
                $setting->setValue('descr', $_POST['descr']);
                $newId = $setting->commit();
                if($newId){
                    if(router::$adminMode) {utils::redirect('/admin/settings/lists');}
                    else{ return utils::returnSuccess(true);}
                }
                else{ return utils::returnSuccess(false);}
            }
        }

        public static function edit($itemId = false){
            return guides::edit_item(get_called_class(), $itemId);
        }

        public static function getSetting($name = false, $returnObject = false){
            $sel = new selector('settings');
            $sel->equals('name', $name);
            $sel->limit(1);
            if($setting = $sel->run()){
                if($returnObject){ return $setting;}
                else {return $setting->getValue('value');}
            }
            else{ return false;}
        }

        public static function saveSetting(){
            if(utils::validatePost(array('name','value'))){
                if($setting = self::getSetting($_POST['name'], true)){
                    $setting->setValue('value', $_POST['value']);
                    $setting->commit();
                    return utils::returnSuccess(true,'Настройка обновлена');
                }
                else{ return utils::returnSuccess(false,'Настройка не найдена');}
            }
            else{ return utils::returnSuccess(false, 'Неполные данные');}
        }
    }
?>