<?php 
    class formgenerator extends baseModule{

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
            if(!count($_POST)){ return guides::add_item(get_called_class());}
            else{
                $formFieldsGuide = guides::getGuideByModuleName('formgenerator_fields');
                $postFields = array_keys($_POST['new_fields']);
                $fieldIds = [];
                foreach($_POST['new_fields']['name'] as $i=>$formTitle){
                    $field = $formFieldsGuide->getGuideObjectClass();
                    foreach($postFields as $postField){
                        $field->setValue($postField, $_POST['new_fields'][$postField][$i]);
                    }
                    $fieldIds[] = $field->commit();
                }

                $_POST['groups_fields'] = $fieldIds;
                return guides::add_item(get_called_class());
            }
        }

        public static function edit($itemId = false){
            if(!count($_POST)){  return guides::edit_item(get_called_class(), $itemId);}
            else{
              
                $postFields = array_keys($_POST['fields']);
                $fieldIds = [];

                foreach($_POST['fields']['name'] as $k=>$v){
                  $fields_up=array(); 
                  $fieldIds[]=$k;
                  foreach($postFields as $postField){
                    $fields_up[]="`".addslashes($postField)."`='".addslashes($_POST['fields'][$postField][$k])."'";
                  }
                  self::customQuery("UPDATE `guide_formgenerator_fields` SET ".join(',',$fields_up)." WHERE `id`='".intval($k)."' ");
                }

                if (isset($_POST['form_row_delete'])){
                  $del_list = array();
                  foreach($_POST['form_row_delete'] as $v){
                     $del_list[]=intval($v);
                  }
                  self::customQuery("DELETE FROM `guide_formgenerator_fields` WHERE `id` IN(".join(',',$del_list).") ");
                }
              
                if (isset($_POST['new_fields'])){
                  $formFieldsGuide = guides::getGuideByModuleName('formgenerator_fields');
                  foreach($_POST['new_fields']['name'] as $i=>$formTitle){
                    $field = $formFieldsGuide->getGuideObjectClass();
                    foreach($postFields as $postField){
                       $field->setValue($postField, $_POST['new_fields'][$postField][$i]);
                    }
                    $fieldIds[] = $field->commit();
                  }
                }
              
                $_POST['groups_fields'] = $fieldIds;
                return guides::edit_item(get_called_class(),$itemId);
            }
        }

        public static function view($object = false, $callFromPage = false){
            if(is_numeric($object)){ $object = formgenerator::getById($object);}
            if($object instanceof formgenerator){
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
      
      public static function send_saved_data_to_email($id){
        $res=array();
        $files = array();
        $q = self::customQuery("SELECT * FROM  `guide_request_form` WHERE id='".intval($id)."'");
          if ($row = $q->fetch_assoc()){
            $q2 = self::customQuery("SELECT * FROM  `formgenerator` WHERE id='".intval($row['form_id'])."'");
            if ($form = $q2->fetch_assoc()){
              $ids_json = json_decode($form['groups_fields']);
         
              $q3 = self::customQuery("SELECT * FROM  `guide_formgenerator_fields` WHERE `id` IN (".join(',',$ids_json).")");
              $field_list = array();
              while($fields = $q3->fetch_assoc()){
                $field_list[$fields['id']]=$fields;
              }
              $res[]='<h1>'.$form['name'].'</h1>';
              $form_data = json_decode($row['form_data']);
              foreach($form_data as $k=>$v){
                switch($field_list[$k]['type']){
                  case 'file':
                    $files[]=$v;
                  break;
                  case 'guide':
                    $sval = self::get_extra_value($field_list[$k]['extra'],$v);
                    $res[]=$field_list[$k]['name'].': '.$sval;
                  break;
                  default:
                    $res[]=$field_list[$k]['name'].': '.$v;
                }
                
              }
            }
            
            $res_mail = formgenerator::sendFormEmail($form['email_title'], join('<br>',$res),$form['email'],$files);
            $message=array('message'=>$form['result'],'redirect'=>$form['redirect']);
            return $message;
          }
           
      }
      
      
      public static function get_extra_value($extra,$id){
        $q = self::customQuery("SELECT * FROM  `guides` WHERE `id`='".intval($extra)."'");
        if($row = $q->fetch_assoc()){
          $q2 = self::customQuery("SELECT * FROM  `".($row['is_module']==0?'guide_':'').addslashes($row['name'])."` WHERE `id`='".intval($id)."'");
          if($row2 = $q2->fetch_assoc()){
            return $row2['name'];
          }
        }
      }
      
      public static function sendFormEmail($title, $message, $email,$files=array()){
        if(config::get('phpmailer', 'enabled')){
            require_once('./core/libs/phpmailer/Exception.php');
            require_once('./core/libs/phpmailer/PHPMailer.php');
            require_once('./core/libs/phpmailer/SMTP.php');

            $mailer = new \PHPMailer\PHPMailer\PHPMailer();
            $mailer->isSMTP();
            $mailer->SMTPDebug = 0;
            $mailer->Host = config::get('phpmailer', 'host');
            $mailer->SMTPAuth = true;
            $mailer->Username = config::get('phpmailer', 'user');
            $mailer->Password = config::get('phpmailer', 'password');
            $mailer->Port = (int)config::get('phpmailer', 'port');

            $mailer->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );

            $mailer->setFrom(config::get('phpmailer', 'user'));
            $mailer->addAddress($email);
            $mailer->isHTML(true);
            $mailer->CharSet = 'UTF-8';
            $mailer->Subject = $title;
            $mailer->Body = $message;
            
            foreach($files as $file){
              $mailer->addAttachment('.'.$file);
            }
            $mailer->SMTPDebug  = 1;
            return $mailer->send();
        }
        else{
            $from = config::get('mail', 'from');
            $headers = 'From: '.$from. "\r\n" .
                'Content-Type: text/html; charset=utf8'."\r\n".
                'X-Mailer: PHP/' . phpversion();
            mail($email,$title,$message,$headers, "-f $from");
        }
    }
      
       public static function save_form(){
       //  print_r($_SERVER);
        // print_r($_POST);
        // print_r($_FILES); die;
         $form_id=intval($_POST['form']);
          $q = self::customQuery("SELECT * FROM  `formgenerator` WHERE id='".intval($form_id)."'");
          if ($row = $q->fetch_assoc()){
            
            if (isset($_POST['photo'])){
              foreach($_POST['photo'] as $p1=>$p2){
                if($p2!=''){
                  $file_address = '/files/form/'.time().'.jpeg';//file_save
                  $output_file = '.'.$file_address;
                  file_put_contents($output_file, file_get_contents($p2));
                  $_POST['field'][$p1] = $file_address;
                }
              }
            }
            
             if (isset($_FILES['field'])){
              foreach($_FILES['field']['tmp_name'] as $p1=>$p2){
                if ($p2!=''){
                  $ext = pathinfo($_FILES['field']['name'][$p1])['extension'];
                  $file_address = '/files/form/'.time().'.'.$ext;//file_save
                  $output_file = '.'.$file_address;
                  if (move_uploaded_file($p2, $output_file)) {
                    $_POST['field'][$p1] = $file_address;
                  }
                }
              }
            }
            
            self::customQuery("INSERT INTO `guide_request_form` SET 
              `name`='".addslashes($row['name'])."',
              `lang`='ru',
              `create_time`=NOW(), 
              `form_id`='".intval($row['id'])."',
              `form_data`='".addslashes(json_encode($_POST['field']))."'");
            global $mysqli;
            return $mysqli->insert_id;
          }else{
            return false;
          }
       }
     
    }
?>