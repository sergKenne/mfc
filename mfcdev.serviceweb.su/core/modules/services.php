<?php 
    class services extends baseModule{

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
            $editData = guides::edit_item(get_called_class(), $itemId);
            $dataInApi = self::loadFromApi($editData['edit']->getValue('service_id'));
            $editData['api'] = $dataInApi;
            return $editData;
        }

        public static function view($object = false, $callFromPage = false){
            if(is_numeric($object)){ $object = services::getById($object);}
            if($object instanceof services){
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

        public static function filter(){
            $sel = new selector('services');
            $sel->isnotnull('id');

            if(isset($_GET['user_categories']) and is_array($_GET['user_categories'])){
                $sel->addAND();
                $sel->openBracket();
                foreach($_GET['user_categories'] as $i=>$catId){
                    $sel->json_contains_simple('user_categories', $catId);
                    if($i < count($_GET['user_categories'])-1){ $sel->addOR();}
                }
                $sel->closeBracket();
            }

            if(isset($_GET['cheap_user_categories']) and is_array($_GET['cheap_user_categories'])){
                $sel->addAND();
                $sel->openBracket();
                foreach($_GET['cheap_user_categories'] as $i=>$catId){
                    $sel->json_contains_simple('cheap_user_categories', $catId);
                    if($i < count($_GET['cheap_user_categories'])-1){ $sel->addOR();}
                }
                $sel->closeBracket();
            }

            if(isset($_GET['category']) and is_array($_GET['category'])){
                $sel->addAND();
                $sel->in('category', $_GET['category']);
            }
            if(isset($_GET['sit']) and is_numeric($_GET['sit'])){
                $sel->addAND();
                $sel->equals('situation', $_GET['sit']);
            }
            if(isset($_GET['search'])){
                $sel->addAND();
                $sel->like('name', '%'.$_GET['search'].'%');
            }
            return $sel->run();
        }

        public static function loadCategories($groupId = false){
            $sel = new selector('guide_service_categories');
            $sel->equals('group', $groupId);
            return $sel->run();
        }

        public static function loadFromApi($serviceId = false){
            if($serviceId){
                $sendData = [
                    "search"=>[
                        "search"=>[
                            [
                                "field"=>"serviceId",
                                "operator"=> "eq",
                                "value"=> $serviceId
                            ]
                        ]
                    ]
                ];
            }
            else{
                $sendData = [
                    "search"=>[
                        "search"=>[]
                    ]
                ];
            }

            $data = utils::POST('http://sier40.evolenta.ru/api/v1/search/subservices', [
                'Content-type: application/json;charset="utf-8"',
                'Authorization: Basic cG9ydGFsOnVHWX4qWTlIMm5lVw==',
                'OrgId: 2cda1b28-c295-41b2-a48d-2dea952ab82c'
            ], json_encode($sendData));

            $services = json_decode($data, true);

            //запрошена одна услуга
            if($serviceId){
                if(isset($services['content'][0])) return $services['content'][0];
                else{ return [];}
            }
            //запрошены все услуги
            else{
                foreach($services['content'] as $service){
                    //ищем эту услугу
                    $sel = new selector('services');
                    $sel->equals('service_id', $service['serviceId']);
                    $sel->limit(1);
                    $item = $sel->run();
                    if(!$item){ $item = new services();}
                    //заполняем в ней данные
                    $item->setValue('name', $service['serviceName']);
                    $item->setValue('service_id', $service['serviceId']);
                    $item->commit();
                }
                utils::redirect('/admin/services/lists');
            }
        }

        public static function check_status(){
            if(isset($_GET['number'])){
                $sendData = [
                    "search"=>[
                        "search"=>[
                            [
                                "field"=>"shortNumber",
                                "operator"=> "eq",
                                "value"=> $_GET['number']
                            ]
                        ]
                    ]
                ];

                $dataRaw = utils::POST('http://sier40.evolenta.ru/api/v1/search/appeals', [
                    'Content-type: application/json;charset="utf-8"',
                    'Authorization: Basic cG9ydGFsOnVHWX4qWTlIMm5lVw==',
                    'OrgId: 2cda1b28-c295-41b2-a48d-2dea952ab82c'
                ], json_encode($sendData));

                $data = json_decode($dataRaw, true);

                if(isset($data['content'][0])){
                    $result = [
                        'status'=>$data['content'][0]['status']['name'],
                        'created'=>date('d.m.Y', strtotime($data['content'][0]['dateCreation'])),
                        'planeFinish'=>date('d.m.Y', strtotime($data['content'][0]['datePlaneFinish']))
                    ];

                    foreach($data['content'][0]['statusHistory'] as $statusHistory){
                        $result['history'][] = ['name'=>$statusHistory['name'], 'start'=>$statusHistory['dateStart']];
                    }

                    return $result;
                }
                else{ return false;}
            }
            else{ return false;}
        }

        public static function getSituations(){
            $situations = guides::list_items('service_situations', false, true, 'ASC', 'id', true);
            $countServices = self::customQuery("SELECT `situation`, COUNT(`id`) AS `count` from `services` GROUP BY `situation`");
            $totalServices = 0; $counters = [];
            while($row = $countServices->fetch_assoc()){
                $totalServices+= $row['count'];
                $counters[$row['situation']] = $row['count'];
            }

            return ['situations'=>$situations, 'counters'=>$counters, 'totalServices'=>$totalServices];
        }
    }
?>