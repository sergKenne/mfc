<?php 
    class notices extends baseModule{

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

        public static function view($object = false){
            if(is_numeric($object)){ $object = notices::getById($object);}
            if($object instanceof notices){
                return [
                    'title'=>$object->getValue('name', false, false),
                    'content'=>$object->getValue('content', false, false),
                    'date'=>utils::formatDate($object->getValue('create_time'))
                ];
            }
            else{ return router::err404();}
        }


    }
?>