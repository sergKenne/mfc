<?php 
class vneocheredi extends baseModule{

  private static function call_API($method, $data){
  if ($_SERVER['SERVER_NAME']=='mfc.serviceweb.su'){
	$url= "https://mfcdev.egov66.ru/services/vneocheredi.php?do=".$method;
		  $curl = curl_init();
		  curl_setopt($curl, CURLOPT_POST, 1);
		  curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
		  curl_setopt($curl, CURLOPT_URL, $url);
		  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		  $result = curl_exec($curl);
		  curl_close($curl);
		  return $result;
	  }else{
		$res = '';
		 switch($method){
			case 'ping':
				$res = self::call_API2('GET','ping');
			break;
			case 'ticket':
				$res = self::call_API2('POST','ticket',$data);
			break;
			case 'dates':
				$res = self::call_API2('POST','dates',$data);
			break;
			case 'slots':
				$res = self::call_API2('POST','slots',$data);
			break;
			case 'create':
				$res = self::call_API2('POST','appointments/create',$data);
			break;
		  case 'all':
				$res = self::call_API2('POST','appointments/all',$data);
			break;  
		  case 'cancel':
				$res = self::call_API2('POST','appointments/cancel',$data);
			break;  
		}
	return $res;
	}
  }
  
  private static function call_API2($type, $method, $data = false){
	$url= "http://10.0.25.70:85/site/v1/".$method;
    $curl = curl_init();

    switch ($type){
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);
            if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
            break;
        default:
            if ($data)
                $url = sprintf("%s?%s", $url, http_build_query($data));
    }
	curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Content-Length: ' . strlen(json_encode($data))));
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($curl);
    curl_close($curl);
    return $result;
}
  
  
  public static function get_calendar($office_id,$ticket_name,$docs_count=1,$req_date=''){
      
      $result = array('available_dates'=>array(),'available_time'=>array(),'office'=>$office_id,'ticket'=>$ticket_name);

      if ($ticket_name){
         $office = array("officeId"=>$office_id,
                        "serviceType"=>"string",
                        "ticket"=>$ticket_name);
          
        $dates = self::call_API('dates',$office);
		
        $available_dates = json_decode($dates);
        $req_id =0;
        foreach($available_dates->dates as $date){
          if (date('d-m-Y',strtotime($date))==date('d-m-Y',strtotime($req_date))){
            $req_id = count($result['available_dates']);
          }
          $result['available_dates'][]=date('d-m-Y',strtotime($date));
          
        }
     
        $slots_data = array("officeId"=>$office_id,
                            "serviceType"=>"string",
                            "units"=>$docs_count,
                            "date"=>date('c',strtotime($result['available_dates'][$req_id])),
                            "ticket"=>$ticket_name);
          
        $slots = self::call_API('slots',$slots_data);
        $available_time = json_decode($slots);
        
        foreach($available_time->timeSlots as $time){
          $result['available_time'][$time->id]= array('start_time'=>date('H:i',strtotime($time->startTime)),'end_time'=>date('H:i',strtotime($time->stopTime)));
        }
        

      }
     $result['date']=date('d-m-Y',strtotime($result['available_dates'][$req_id]));
    $result['docs_count']=$docs_count;
     $result['date_m']=date('n',strtotime($result['available_dates'][$req_id]))-1;
    $result['date_d']=date('j',strtotime($result['available_dates'][$req_id]))-1;
     $result['date_y']=date('Y',strtotime($result['available_dates'][$req_id]));
     $result['log'] = date('c',strtotime($result['available_dates'][$req_id]));
    
      return $result;
  }
  
  
  public static function get_ticket_name($userdata){
      $ticket_name = self::call_API('ticket',$userdata);
      return $ticket_name;
  }
  
  public static function get_reserve($userdata){
      $result=array();
      $data = json_decode(self::call_API('all',$userdata));
	  
      foreach($data->appointments as $v){
        $result[]=array('firstName'=>$v->applicant->firstName,
                        'middleName'=>$v->applicant->middleName,
                        'lastName'=>$v->applicant->lastName,
                        'phone'=>$v->applicant->phone,
                        'gender'=>$v->applicant->gender,
                        'email'=>$v->applicant->email,
                        'passport'=>$v->applicant->passport,
                        'snils'=>$v->applicant->snils,
                        'date'=>date('d.m.Y',strtotime($v->time)),
                        'time'=>date('H:i',strtotime($v->time)),
                        'date_ru'=>date('d ',strtotime($v->time)).lk::getDateRus(date('n',strtotime($v->time))).date(' Y',strtotime($v->time)),
                        'docs_count'=>$v->units,
                        'token'=>$v->token,
                        'office'=>$v->office->name,
                        'office_city'=>$v->office->city,
                        'office_addr'=> $v->office->address,
                        'service'=> $v->serviceType,
                       );
      }
      return $result;
  }
  
  public static function reserve($userdata){
      $data = self::call_API('create',$userdata);
      return json_decode($data);
  }
  
   public static function cancel_reserve($userdata){
      $data = self::call_API('create',$userdata);
      return json_decode($data);
  }
  

   
}