<?php
/**
 * Created by PhpStorm.
 * User: Александров Олег
 * Date: 05.07.19
 * Time: 13:18
 */

//модуль работы с пользователями
class users extends baseModule{

    //проверка авторизации
    public function checkAuth(){
        session_start();
        if(isset($_COOKIE['id']) and isset($_COOKIE['hash'])){
            $id = (int)$_COOKIE['id']; $hash = $_COOKIE['hash'];
        }
        else{
            if(isset($_SESSION['id']) and $_SESSION['hash']){
                $id = (int)$_SESSION['id']; $hash = $_SESSION['hash'];
            }
            else{   $id = false; $hash = false;}
        }

        if($id and $hash){
            $userSel = new selector('users');
            $userSel->equals('id',$id);
            $userSel->limit(1);
            $userSel->setCacheTime(0);
            $userObj = $userSel->run();
            if(!$userObj){ return false;}
            else {
                if($userObj->getValue('hash') === $hash /*and $_SERVER['REMOTE_ADDR'] === $userObj->getValue('ip')*/){
                    return $userObj;
                }
                else {
                    $this->logout(true);
                    return false;
                }
            }
        }
        else{ return false;}
    }

    public function isAdmin(){
        return $this->getValue('type')==='admin';
    }

    public function isGuest(){
        return $this->getValue('type')==='guest';
    }

    public static function lists(){
        return guides::list_items(get_called_class());
    }

    //вход
    public static function enter(){
        if(!router::$currentUser->isGuest()){ utils::redirect('/');}
        if(utils::validatePost(['email','password'])){
            $userSel = new selector('users');
            $userSel->equals('email',$_POST['email']);
            $userSel->addOR();
            $userSel->equals('name', $_POST['email']);
            $userSel->limit(1);
            $userObj = $userSel->run();
            if($userObj){
                if(password_verify($_POST['password'],$userObj->getValue('password'))){
                    if($userObj->getValue('activation') == 1){
                        $hash = md5($userObj->getId().'_'.time().'_'.rand(0,999));
                        if(isset($_POST['remember'])){
                            setcookie('id',$userObj->getId(),time()+2592000,'/');
                            setcookie('hash',$hash,time()+2592000,'/');
                        }
                        else{
                            $_SESSION['id'] = $userObj->getId();
                            $_SESSION['hash'] = $hash;
                        }

                        $userObj->setValue('hash',$hash);
                        $userObj->setValue('ip',$_SERVER['REMOTE_ADDR']);
                        $userObj->commit(false);
                        if(router::$adminMode and $userObj->isAdmin()){  utils::redirect('/admin');}
                        else {
                            if(isset($_POST['redirect'])){ utils::redirect($_POST['redirect']);}
                            else{utils::redirect('/');}
                        }
                    }
                    else{   return utils::returnSuccess(false, 'Аккаунт не активирован');}
                }
                else{   return utils::returnSuccess(false, 'Неверный логин или пароль');}
            }
            else{   return utils::returnSuccess(false, 'Неверный логин или пароль');}
        }
    }

    //выход
    public static function logout($noRedirect = false){
        setcookie('id',null,time()-86400,'/');
        setcookie('hash',null,time()-86400,'/');
        session_destroy();
        if(!$noRedirect) {utils::redirect('/');}
    }

    //регистрация
    public static function registrate(){
        $recaptchaData = [
            'recaptcha'=>[
                'enabled'=>config::get('recaptcha','enabled'),
                'public_key'=>config::get('recaptcha','public_key')
            ]
        ];

        if(router::$currentUser->isGuest() or router::$adminMode){
            if(count($_POST)){
                if(utils::validatePost(['name','email','password','password_confirm'])){
                    //check captcha
                    $captchaResponse = (isset($_POST['g-recaptcha-response'])) ? $_POST['g-recaptcha-response'] : '';
                    if(router::$adminMode or utils::checkCaptcha($captchaResponse)){
                        if(preg_match('/^([A-Za-z0-9_\-]+)$/', $_POST['name'])){
                            if($_POST['password_confirm'] === $_POST['password']){
                                if(self::checkUnique('email', $_POST['email'])){
                                    if(self::checkUnique('name', $_POST['name'])){
                                        //проверка, нужно ли пользователю активироваться
                                        if(config::get('options','require_user_activation')){
                                            if(router::$adminMode){ $activation = 1;}
                                            else{ $activation = md5($_POST['email'].'_'.time().'_'.rand(0,9999));}
                                        }
                                        else{ $activation = 1;}

                                        $newUser = new users();
                                        $newUser->setValue('name', $_POST['name']);
                                        $newUser->setValue('email', $_POST['email']);
                                        $newUser->setValue('password', escapeString(password_hash($_POST['password'], PASSWORD_DEFAULT)));
                                        $newUser->setValue('activation',$activation);
                                        if(router::$adminMode){
                                            $newUser->setValue('type', $_POST['type']);
                                        }
                                        else{
                                            $newUser->setValue('ip', $_SERVER['REMOTE_ADDR']);
                                            $newUser->setValue('type', 'user');
                                        }
                                        $newUserId = $newUser->commit();

                                        if(is_numeric($newUserId)){
                                            if(router::$adminMode){ utils::redirect('/admin/users/lists');}
                                            else{
                                                if($activation == 1){ return utils::returnSuccess(true, 'Вы успешно зарегистрированы');}
                                                else{
                                                    $message = 'Подтвердите регистрацию, перейдя по этой <a href="http://'.$_SERVER['SERVER_NAME'].'/users/activate/'.$activation.'">ссылке</a>';
                                                    utils::sendSimpleEmail('Подтверждение регистрации', $message, $_POST['email']);
                                                    return utils::returnSuccess(true, 'Вы успешно зарегистрированы. Проверьте вашу почту');
                                                }
                                            }
                                        }
                                        else{ return utils::returnSuccess(false, 'Регистрация не удалась. Попробуйте еще раз', $recaptchaData);}
                                    }
                                    else{ return utils::returnSuccess(false, 'Этот логин уже занят', $recaptchaData);}
                                }
                                else{ return utils::returnSuccess(false, 'Такой email уже зарегистрирован', $recaptchaData);}
                            }
                            else{ return utils::returnSuccess(false,'Пароли не совпадают', $recaptchaData);}
                        }
                        else {return utils::returnSuccess(false, 'Некорректный логин. Разрешена латиница, цифры, дефис и знак подчеркивания');}
                    }
                    else{ return utils::returnSuccess(false, 'Проверка CAPTCHA не пройдена', $recaptchaData);}
                }
                else{ return utils::returnSuccess(false, 'Получены не все данные', $recaptchaData);}
            }
            else{
                if(router::$adminMode){
                    $typeArr = permissions::getUserTypes();
                    return array('types'=>$typeArr,'fields'=>guides::getGuideByModuleName('users')->formatFields());
                }
                else{
                    return $recaptchaData;
                }
            }
        }
        /*else{ utils::redirect('/'); exit;}*/
    }

    public static function edit($id){
        if(!$id){ utils::redirect('/');}

        if(router::$currentUser->getId() == $id or router::$currentUser->isAdmin()){
            if(count($_POST)===0){
                $typeArr = permissions::getUserTypes();
                $user = guides::edit_item('users', $id);
                $user['_allTypes'] = $typeArr;
                return $user;
            }
            else{
                $editedUser = self::getById($id);
                if(isset($_POST['password']) and $_POST['password']!==''){
                    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                    $editedUser->setValue('password',$password);
                }
                if(router::$currentUser->isAdmin() and isset($_POST['type'])){
                    if($id == router::$currentUser->getId() and $_POST['type']!='admin'){}
                    else {$editedUser->setValue('type',$_POST['type']);}
                }
                if(isset($_POST['email'])){ $editedUser->setValue('email',$_POST['email']);}

                $editedUser->commit();
                guides::edit_item('users', $id, false, array('password','type'));

                if(router::$adminMode){ utils::redirect('/admin/users/lists');}
                else{ utils::redirect('/');}
            }
        }
        else{  return router::notEnoughPermissions();}
    }

    public static function del($id){
        self::delById($id);
        return utils::returnSuccess(true);
    }

    protected static function checkUnique($what, $value){
        if(in_array($what, ['email', 'name'])){
            $userSel = new selector('users');
            $userSel->equals($what,$value);
            $userSel->limit(1);
            $userObj = $userSel->run();
            if($userObj){ return false;}
            else{ return true;}
        }
        else{ return false;}
    }

    public static function activate($code = false){
        if($code and $code!=1){
            $usersSel = new selector('users');
            $usersSel->equals('activation',$code);
            $usersSel->limit(1);
            $user = $usersSel->run();
            if($user){
                $user->setValue('activation',1);
                $user->commit();
                utils::redirect('/users/enter','Ваша учетная запись активирована, теперь вы можете авторизоваться',true);
            }
            else{ utils::redirect('/users/enter','Неверный код активации',true);}
        }
        else{ utils::redirect('/users/enter','Неверный код активации',true);}
    }

    public static function reminder($code = false){
        if(utils::validatePost(['email'])){
            $sel = new selector('users');
            $sel->equals('email', $_POST['email']);
            $sel->limit(1);
            if($user = $sel->run()){
                $hash = md5($user->getId().'_'.time().'_'.rand(0,999));
                $user->setValue('reminder', $hash); $user->commit();
                $message = 'Чтобы восстановить пароль, нажмите <a href="http://'.$_SERVER['SERVER_NAME'].'/users/reminder/'.$hash.'">здесь</a>';
                utils::sendSimpleEmail('Восстановление пароля', $message, $user->getValue('email'));
                return utils::returnSuccess(true, 'Ссылка на восстановление пароля отправлена на ваш почтовый ящик');
            }
            else{ return utils::returnSuccess(false, 'Такой адрес не найден');}
        }
        else if($code){
            $usersSel = new selector('users');
            $usersSel->equals('reminder',$code);
            $usersSel->limit(1);
            $user = $usersSel->run();
            if($user){
                $newPassword = utils::generateRandomString();
                $newPasswordHashed = password_hash($newPassword, PASSWORD_DEFAULT);
                $user->setValue('password',$newPasswordHashed);
                $user->setValue('reminder', '');
                $user->commit();
                utils::sendSimpleEmail('Новый пароль', 'Ваш новый пароль: '.$newPassword, $user->getValue('email'));
                return utils::returnSuccess(true, 'Новый пароль выслан на вашу почту');
            }
            else{return utils::returnSuccess(false, 'Неверная ссылка на восстановление');}
        }
        else{ return [];}
    }

    public function canAccessToAdminPanel(){
        if(router::$currentUser->isAdmin()){ return true;}
        elseif(array_search(router::$currentUser->getValue('type'), config::get('admin_panel', 'role')) !== false){ return true;}
        else{ return false;}
    }
}