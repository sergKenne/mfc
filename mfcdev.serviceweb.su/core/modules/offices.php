<?php 
    class offices extends baseModule{

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
            if(is_numeric($object)){ $object = offices::getById($object);}
            if($object instanceof offices){
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

        //$type - letter, city, search
        public static function officeFilter($limit = false){
            $type = (isset($_GET['type'])) ? $_GET['type'] : 'letter';
            $filter = (isset($_GET['filter'])) ? $_GET['filter'] : 'Е';

            $cityIDs = [];
            if($type == 'letter' or $type == 'city'){
                $selC = new selector('guide_cities');
                if($type == 'letter'){ $selC->like('name', $filter.'%'); }
                else { $selC->equals('name', $filter);}
                $cities = $selC->run();
                foreach($cities['data'] as $city){ $cityIDs[] = $city->getId();}
            }

            $sel = new selector('offices');
            switch($type){
                case 'letter':
                case 'city':{
                    if($cityIDs){   $sel->in('city_id', $cityIDs);}
                    else{ $sel->equals('id', 0);}
                    break;
                }
                case 'search':{     $sel->like('name', '%'.$filter.'%'); break;}
                default: {$sel->isnotnull('id');}
            }
            $sel->order('name', 'ASC');
            if($limit and is_numeric($limit)){ $sel->limit($limit);}
            $data = $sel->run();
            $data['type'] = $type; $data['filter'] = $filter;
            return $data;
        }

        public static function getLetters(){
            $letters = [];
            $data = self::customQuery("SELECT LEFT(`name`,1) FROM `guide_cities` GROUP BY LEFT(`name`,1)");
            while($row = $data->fetch_array()){     $letters[] = $row[0];}
            return $letters;
        }

        public static function byDistrict($districtId = false, $limit = false){
            if($district = guides::get_item(31, $districtId)){
                if($municipals = $district->getValue('conn_municipal', true)){
                    $sel = new selector('offices');
                    $sel->in('municipal', $municipals);
                    if($limit and is_numeric($limit)){ $sel->limit($limit);}
                    return $sel->run();
                }
                else{ return ['data'=>[], 'total'=>0];}
            }
            else{ return router::err404();}
        }

        public static function getCitiesFromOfficeList($officeList = false){
            $cities = [];
            if($officeList and is_array($officeList)){
                foreach($officeList as $office){
                    $cities[] = $office->getValue('city_id');
                }
            }
            return $cities;
        }

        public static function getAllOfficesCoords(){
            $sel = new selector('offices', ['id','name','coords']);
            $sel->isnotnull('id');
            $data = [];
            foreach($sel->run()['data'] as $office){
                $data[] = array(
                    'coordinate'=> explode(', ',$office->getValue('coords')),
                    'balloon'=> [
                        'balloonContentHeader'=> $office->getName($office),
                        'hintContent'=>'неизвестная загруженность'
                    ],
                    'icon' =>[
                        'iconLayout'=>'default#image',
                        'iconImageHref'=> '/img/geo_grey.png',
                        'iconImageSize'=> [38, 48],
                        'iconImageOffset'=> [-15, -62]
                    ]
                );
            }
            return $data;
        }
        
        public static function integration(){
            $test = utils::POST('http://10.10.0.114/api/v1/offices', [], false);
            var_dump($test); exit;
        }
    }
?>