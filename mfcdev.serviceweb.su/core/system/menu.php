<?php 
    class menu extends baseModule{

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

        public static function view($objectId = false){
            $object = menu::getById($objectId);
            if($object){
                $page = $object->getConnectedPage();
                return array('page'=>$page, 'source'=>$object);
            }
            else{ return router::err404();}
        }

        public static function getMenu($menuName = false){
            $sel = new selector('menu');
            if(is_numeric($menuName)){ $sel->equals('id', $menuName);}
            else{ $sel->equals('name', $menuName);}
            $sel->limit(1);
            if($menu = $sel->run()){
                return $menu->getValue('structure');
            }
        }
    }
?>