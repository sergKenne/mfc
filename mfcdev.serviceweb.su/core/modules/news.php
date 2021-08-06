<?php 
    class news extends baseModule{

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
                $limit = utils::getRowsLimit(11);
                $page = (isset($_GET['page'])) ? (int)$_GET['page']  : 1;

                if($page == 1){
                    $fSel = new selector('news');
                    $fSel->equals('featured', 1);
                    $fSel->limit(1);
                    $featured = $fSel->run();
                }

                $sel = new selector('news');
                $sel->isnotnull('id');
                $sel->addAND();
                $sel->equals('featured', 0);
                $sel->addAND();
                $sel->equals('published', 1);
                if(isset($_GET['tag'])){
                    $sel->addAND();
                    $sel->equals('tag', $_GET['tag']);
                }
                $sel->order('publish_date', 'DESC');
                $sel->limit($limit, $limit * ($page-1));
                $news = $sel->run();
                if(isset($featured)) $news['featured'] = $featured;

                return $news;
            }
        }

        public static function add(){
            return guides::add_item(get_called_class());
        }

        public static function edit($itemId = false){
            if(count($_POST)){
                if(isset($_POST['featured'])){
                    $sel = new selector('news');
                    $sel->equals('featured',1);
                    $sel->addAND();
                    $sel->notequals('id',$itemId);
                    $sel->limit(1);
                    if($prevFeaturedNews = $sel->run()){
                        $prevFeaturedNews->setValue('featured', 0);
                        $prevFeaturedNews->commit();
                    }
                }
            }
            return guides::edit_item(get_called_class(), $itemId);
        }

        public static function view($object = false, $callFromPage = false){
            if(is_numeric($object)){ $object = news::getById($object);}
            if($object instanceof news){
                if(!$object->getValue('published')){ return router::err404();}
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