<?php 
    class documents extends baseModule{

        /*
            Это код вашего нового модуля.
            Функция lists отвечает за отображение списка элементов
            Функция add - за добавление нового элемента
            Функция edit - за редактирование существующего элемента
            Функция view - за просмотр определенного элемента
            Вы можете как угодно редактировать эти функции, а также, при необходимости, писать свои собственные.
        */

        public static function lists($filterById = array(), $forceAllFields = false){
            if(router::$adminMode){ return guides::list_items(get_called_class(), $filterById, $forceAllFields);}
            else{
                $countByCats = [];
                $countByCatsSQL = self::customQuery("SELECT `category`, COUNT(`id`) AS `total` FROM `documents` GROUP BY `category`");
                while($row = $countByCatsSQL->fetch_assoc()){
                    $countByCats[$row['category']] = $row['total'];
                }

                $limit = utils::getRowsLimit();
                $page = (isset($_GET['page'])) ? (int)$_GET['page']  : 1;

                $sel = new selector('documents');
                $sel->isnotnull('id');
                $cat = false;
                if(isset($_GET['cat']) and is_numeric($_GET['cat'])){
                    $sel->addAND();
                    $sel->equals('category', $_GET['cat']);
                    $cat = guides::get_item(47, $_GET['cat']);
                }
                if(isset($_GET['q'])){
                    $sel->addAND();
                    $sel->like('name', '%'.$_GET['q'].'%');
                }
                $sel->limit($limit, $limit * ($page-1));
                $result = $sel->run();

                $result['countByCats'] = $countByCats;
                if($cat){ $result['cat'] = $cat;}
                return $result;
            }
        }

        public static function add(){
            return guides::add_item(get_called_class());
        }

        public static function edit($itemId = false){
            return guides::edit_item(get_called_class(), $itemId);
        }

        public static function view($object = false, $callFromPage = false){
            if(is_numeric($object)){ $object = documents::getById($object);}
            if($object instanceof documents){
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