<?php 
    class extend_services extends baseModule{

        /*
            Это код вашего нового модуля.
            Функция lists отвечает за отображение списка элементов
            Функция add - за добавление нового элемента
            Функция edit - за редактирование существующего элемента
            Функция view - за просмотр определенного элемента
            Вы можете как угодно редактировать эти функции, а также, при необходимости, писать свои собственные.
        */

        public static function lists($filterById = array(), $forceAllFields = false){
            if(router::$adminMode){
                $page = (isset($_GET['page'])) ? (int)$_GET['page']  : 1;
                $limit = utils::getRowsLimit();

                $sel = new selector('extend_services');
                $sel->equals('is_inner', 0);
                $sel->order('id', 'DESC');
                $sel->limit($limit, $limit * ($page-1));
                $result = $sel->run();

                $guide = guides::getGuideByModuleName('extend_services');

                $needFields = [];
                foreach($guide->getValue('fields') as $field){
                    if((isset($field['table']) and $field['table']) or $forceAllFields){
                        $needFieldsForRequest[] = $field['name'];
                        $needFields[] = $field;
                    }
                }
                $result['guide'] = $guide;
                $result['need_fields'] = $needFields;

                return $result;
            }
            else{
                $result = [];
                $sel = new selector('extend_services');
                $sel->equals('for', 'Физические лица');
                $sel->addAND();
                $sel->equals('is_inner', 0);
                $result['phis'] = $sel->run()['data'];

                $sel = new selector('extend_services');
                $sel->equals('for', 'Юридические лица');
                $sel->addAND();
                $sel->equals('is_inner', 0);
                $result['legal'] = $sel->run()['data'];
                return ['data'=>$result];
            }
        }

        public static function add(){
            return guides::add_item(get_called_class());
        }

        public static function edit($itemId = false){
            return guides::edit_item(get_called_class(), $itemId);
        }

        public static function view($object = false, $callFromPage = false){
            if(is_numeric($object)){ $object = extend_services::getById($object);}
            if($object instanceof extend_services){
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