<?php 
    class suo_services extends baseModule{

        /*
            Это код вашего нового модуля.
            Функция lists отвечает за отображение списка элементов
            Функция add - за добавление нового элемента
            Функция edit - за редактирование существующего элемента
            Функция view - за просмотр определенного элемента
            Вы можете как угодно редактировать эти функции, а также, при необходимости, писать свои собственные.
        */

        public static function lists($filterById = array(), $forceAllFields = false){
            return guides::list_items(get_called_class(), $filterById, $forceAllFields);
        }

        public static function add(){
            return guides::add_item(get_called_class());
        }

        public static function edit($itemId = false){
            return guides::edit_item(get_called_class(), $itemId);
        }

        public static function view($object = false, $callFromPage = false){
            if(is_numeric($object)){ $object = suo_services::getById($object);}
            if($object instanceof suo_services){
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
    }
?>