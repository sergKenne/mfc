<?php 
    class lk extends baseModule{

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

        public static function view($address = false){
          

            $sel = new selector('lk');
            $sel->equals('address', $address);
            $sel->limit(1);
            if($lkPage = $sel->run()){

            $sel = new selector('pages');
            $sel->equals('source_guide', $lkPage->guideId);
            $sel->addAND();
            $sel->equals('source_object',$lkPage->id);
            $sel->limit(1);
            $page = $sel->run();
              
            if (in_array($address,array('status','appeal','refuse_appeal','specialist','free_specialist','withdrawal_personal_data'))){
            //  return router::err404();
            }
              
                return ['source'=>$lkPage,'page'=>$page];
            }
            else{ return router::err404();}
        }
      //53 55 56
       public static function get_ajax_items($table,$value=0){
            $sel = new selector('guide_formgenerator_fields');
            $sel->equals('id', $table);
            $sel->limit(1);
            $res = $sel->run();
            if ($guide_id = $res->getValue('extra')){
              $guide = guides::getById($guide_id);
              $m = $guide->getValue('is_module'); // if 0 -> guide_
              $table_name=$guide->getName();
              $res_list = array();
              if ($table==55){
                if ($value>0){
                  $q = self::customQuery("SELECT * FROM  `guide_cities` WHERE municipal_id='".intval($value)."'");
                }else{
                  $q = self::customQuery("SELECT * FROM  `guide_cities` ORDER by name");
                }
              }elseif ($table==56){
                $q = self::customQuery("SELECT * FROM  `offices` WHERE city_id='".intval($value)."'");
              }
              while($row = $q->fetch_assoc()){
                if (isset($row['city_id'])){
                  $extra = $row['city_id'];
                }
                if (isset($row['municipal_id'])){
                  $extra = $row['municipal_id'];
                }
                
                $res_list[]=array('id'=>$row['id'],'name'=>$row['name'],'extra'=>$extra);  
              }
            }
         utils::returnJSON($res_list);
        }
      
       
      
      public static function save_form(){
        $id = formgenerator::save_form();
        $res=array();
        if ($id){
          $res=formgenerator::send_saved_data_to_email($id);
        }
        utils::returnJSON($res);
      }
      
      
      
      /*
      Порядок:
      1. Ticket
      2. Dates
      3. Slots
      4. Appointments/Create
      */
      
      
      
      public static function get_calendar($date=''){
        $office_id = 50;// $_POST['56'];
        $req_date = $date;//$_POST['req_date']; //$req_date = '2021-08-03T00:00:00+03:00';
        $docs_count = (isset($_POST['docs_count'])?$_POST['docs_count']:1); 
        $calendar = array();
        
        $system_type = '';
        $q = self::customQuery("SELECT * FROM  `offices` WHERE id='".intval($office_id)."'");
        if ($row = $q->fetch_assoc()){
          if($row['id_vne_ocheredi']!='' && ($row['id_vne_ocheredi']!=0)){
            $office_key = $row['id_vne_ocheredi'];
            $system_type = 'vne_ocheredi';
          }elseif($row['id_damask']!='' && ($row['id_damask']!=0)){
            $office_key = $row['id_damask'];
            $system_type = 'damask';
          }
        }
        
        switch($system_type){
          case 'damask':
            
          break;
          case 'vne_ocheredi':
            $ticket_name = (isset($_POST['ticket'])?$_POST['ticket']:'');
            if ($ticket_name==''){
              $pearson_type =array(1=>'PhysicalPerson',2=>'LegalPerson',3=>'SelfEmployed');
              $userdata= array("user"=>array(
                          "firstName"=>$_POST['field']['73'],
                          "middleName"=>$_POST['field']['74'],
                          "lastName"=>$_POST['field']['72'],
                          "phone"=>$_POST['field']['79'],
                          "gender"=>"Male",
                          "email"=>$_POST['field']['78'],
                          "passport"=>$_POST['field']['76'],
                          "snils"=>$_POST['field']['77']),
                      "applicant"=>array(
                          "firstName"=>$_POST['field']['73'],
                          "middleName"=>$_POST['field']['74'],
                          "lastName"=>$_POST['field']['72'],
                          "phone"=>$_POST['field']['79'],
                          "gender"=>"Male",
                          "email"=>$_POST['field']['78'],
                          "passport"=>$_POST['field']['76'],
                          "snils"=>$_POST['field']['77']),
                      "applicantCategory"=>$pearson_type[$_POST['field']['80']],
                      "apiKey"=>"string"
                     ); 
             $ticket_name = vneocheredi::get_ticket_name($userdata);
            }
            $calendar = vneocheredi::get_calendar($office_key,$ticket_name,$docs_count,$req_date);
          break;
        }
        
        utils::returnJSON($calendar);
    }
      
     public static function reserve(){ 
       if (isset($_POST['ticket'])){
          $data = array("ticket"=>$_POST['ticket'],
                        "units"=>$_POST['docs_count'],
                        "timeSlotId"=>$_POST['field'][71],
                        "comment"=>"string");
         $res = vneocheredi::reserve($data);
         $unix_time = strtotime($res->appointment->time);
         $res_html = '<h2 class="user__inner-title user__inner-title--status">Запись успешна</h2>
                                        <div class="user__board">
                                            <p class="user__board-result"><span class="user__board-title">Номер записи</span>'.$res->appointment->token.'</p>
                                            <p class="user__board-result"><span class="user__board-title">Дата записи</span>'.date('d.m.Y',$unix_time).'</p>
                                            <p class="user__board-result"><span class="user__board-title">Время записи</span>'.date('H:i',$unix_time).'</p>
                                            <img src="/img/confirm.svg" alt="" class="user__board-image">
                                        </div>';
         
        }else{
         
       }
       
       
       echo $res_html; die;
       
     }
      
      
     public static function get_all_reserve(){
        $userdata= array("user"=>array(
                          "firstName"=>'name',
                          "middleName"=>'middleName',
                          "lastName"=>'last',
                          "phone"=>'phone',
                          "gender"=>"Male",
                          "email"=>'test@mail.com',
                          "passport"=>'pass',
                          "snils"=>'0000000000'),
                      "apiKey"=>"string"
                     ); 
        $data = vneocheredi::get_reserve($userdata);
        foreach ($data as &$v){
          $v['checksum']=md5($v['token'].'vneocheredi');
        }
       $ru_fields = array('firstName'=>'Имя',
            'middleName' => 'Отчество',
            'lastName' => 'Фамилия',
            'phone' => 'Номер телефона',
            //'gender' => 'Пол',
            'email' => 'Email',
            'passport' => 'Паспорт',
            'snils' => 'Снилс',
            'office_city' => 'Офис МФЦ',
            'office_addr' => 'Адрес офиса МФЦ',
            'service' => 'Услуга',
            'date' => 'Дата',
            'time' => 'Время',
            //'date_ru' => 'Дата',
            //'docs_count' => 'Количество документов',
            'token' => 'Номер записи'
           );
       
       $json_styles=array('token'=>array('name'=>'bold','value'=>'red'), 'email'=>array('value'=>'bold'),'date'=>array('name'=>'bold','value'=>'red'),'time'=>array('name'=>'bold','value'=>'red'));
       
       foreach($data as &$v){
         $json_fields=array();
         foreach($ru_fields as $ru_k=>$ru_v){
           if (isset($v[$ru_k]))
            $json_fields[$ru_k]=array('name'=>$ru_v,'value'=>$v[$ru_k],'style'=>(isset($json_styles[$ru_k])?$json_styles[$ru_k]:array()));
         }
         $v['json']=base64_encode(json_encode($json_fields));
        
       }
       //TODO REQUEST to damask and merge
        return $data;
     }
    public static function cancel_reservation(){
      if (isset($_POST['token'])){
        if ($_POST['checksum']==md5($_POST['token'].'vneocheredi')){
          $userdata =array('appointmentToken'=>$_POST['token']);
          $data = vneocheredi::cancel_reserve($userdata);
          echo $data; die;
        }
      }
     
    }
      
      
    public static function getDateRus($n){
    $monthes = array(
        1 => 'января', 2 => 'февраля', 3 => 'марта', 4 => 'апреля',
        5 => 'мая', 6 => 'июня', 7 => 'июля', 8 => 'августа',
        9 => 'сентября', 10 => 'октября', 11 => 'ноября', 12 => 'декабря'
    );
    return $monthes[$n];
}
      
      
}
?>