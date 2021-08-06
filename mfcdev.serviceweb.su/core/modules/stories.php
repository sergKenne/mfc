<?php 
    class stories extends baseModule{

        /*
            Это код вашего нового модуля.
            Функция lists отвечает за отображение списка элементов
            Функция add - за добавление нового элемента
            Функция edit - за редактирование существующего элемента
            Функция view - за просмотр определенного элемента
            Вы можете как угодно редактировать эти функции, а также, при необходимости, писать свои собственные.
        */

        public static function lists($filterById = array(), $forceAllFields = false){
            if(router::$adminMode) {return guides::list_items(get_called_class(), $filterById, $forceAllFields);}
            else{
                $limit = utils::getRowsLimit(10);
                $page = (isset($_GET['page'])) ? (int)$_GET['page']  : 1;

                $sel = new selector('stories');
                $sel->isnotnull('id');
                if(isset($_GET['tag'])){
                    $sel->addAND();
                    $sel->equals('tag', $_GET['tag']);
                }
                $sel->order('create_time', 'DESC');
                $sel->limit($limit, $limit * ($page-1));
                $news = $sel->run();
                return $news;
            }
        }

        public static function add(){
            return guides::add_item(get_called_class());
        }

        public static function edit($itemId = false){
            return guides::edit_item(get_called_class(), $itemId);
        }

        public static function view($object = false, $callFromPage = false){
            if(is_numeric($object)){ $object = stories::getById($object);}
            if($object instanceof stories){
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