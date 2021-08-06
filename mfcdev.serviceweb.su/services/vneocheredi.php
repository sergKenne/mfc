<?
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

/*
Порядок:
1. Ticket
2. Dates
3. Slots
4. Appointments/Create
*/
$res = '';
switch($_GET['do']){
	case 'ping':
		$res = call_API('GET','ping');
	break;
	case 'ticket':
		$res = call_API('POST','ticket',$_POST);
	break;
	case 'dates':
		$res = call_API('POST','dates',$_POST);
	break;
	case 'slots':
		$res = call_API('POST','slots',$_POST);
	break;
	case 'create':
		$res = call_API('POST','appointments/create',$_POST);
	break;
  case 'all':
		$res = call_API('POST','appointments/all',$_POST);
	break;  
  case 'cancel':
		$res = call_API('POST','appointments/cancel',$_POST);
	break;  
}
echo $res;


function call_API($type, $method, $data = false){
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
	curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($curl);
    curl_close($curl);
    return $result;
}

?>