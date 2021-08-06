<?php
/**
 * Created by PhpStorm.
 * User: Stardisk
 * Date: 12.02.20
 * Time: 20:50
 */

class guides extends baseModule{

    public static function lists($only_modules = false, $forAdminMenu = false, $guideId = false){
        $sel = new selector('guides');
        $sel->isnotnull('id');
        if($only_modules){
            $sel->addAND();
            $sel->equals('is_module',1);
        }
        if($forAdminMenu){
            $sel->addAND();
            $sel->equals('show_in_menu',1);
        }
        if($guideId!==false){
            $sel->addAND();
            $sel->notequals('id', $guideId);
            $sel->addAND();
            $sel->not_like('name', 'webform_%');
        }
        return $sel->run();
    }

    public static function add($fromOtherModule = false){
        if(!count($_POST)){
            $guidesList = self::lists(false, false, 0);
            if($guidesList){ return array('guides'=>$guidesList['data']);}
            else {return array('guides'=>array());}
        }
        else{
            if(utils::validatePost(array('name','descr'))){
                if(!preg_match("/^[a-zA-Z0-9\_]+$/", $_POST['name'])){
                    return utils::returnSuccess(false,'Внутреннее название справочника должно содержать только латиницу, цифры и знак подчеркивания');
                }
                else{
                    $fieldData = array();

                    $systemNames = array('files', 'guides', 'languages', 'pages', 'pages_languages', 'page_extend', 'settings', 'users');
                    if(in_array($_POST['name'],$systemNames)){ return utils::returnSuccess(false,'Зарезервированное имя справочника');}
                    if($checkExistanse = self::getGuideByModuleName($_POST['name'])){ return utils::returnSuccess(false, 'Справочник уже существует');}
                    $sql_request = 'CREATE TABLE ';
                    if(isset($_POST['is_module'])){  $sql_request .= '`'.escapeString($_POST['name']).'`';}
                    else { $sql_request .= '`guide_'.escapeString($_POST['name']).'`';}

                    $sql_request .= ' (`id` int PRIMARY KEY AUTO_INCREMENT, `name` VARCHAR(255) NOT NULL, `lang` VARCHAR(2) NOT NULL, ';

                    if(isset($_POST['field_name'])){
                        foreach($_POST['field_name'] as $key=>$name){
                            $validateName = self::validateFieldName($name);
                            if(!$validateName['success']){ return $validateName;}
                            //если ткнута галка "отображать как список", но не ткнута "можно выбрать несколько значений", принудительно ее ткнуть
                            if(isset($_POST['guideAsTable'][$key])){ $_POST['multiple'][$key] = 1;}
                            $sql_type = self::postTypeToSql($_POST['field_type'][$key], $key);
                            $sql_request .= '`'.$name.'` '.$sql_type.' NOT NULL,';
                            $fieldData[] = self::appendFieldToJson($key,$name);
                        }
                    }

                    $sql_request .= '`create_time` DATETIME NOT NULL)';
                    if(self::customQuery($sql_request)){
                        $guide = new guides();
                        $guide->setValue('name', $_POST['name']);
                        $guide->setValue('descr', $_POST['descr']);
                        $guide->setValue('is_module', (isset($_POST['is_module']) ? 1:0));
                        $guide->setValue('auto_page', (isset($_POST['auto_page']) ? 1:0));
                        $guide->setValue('fields', $fieldData);
                        $guide->setValue('show_in_menu', (isset($_POST['show_in_menu']) ? 1:0));
                        $guide->setValue('icon_class', $_POST['icon_class']);
                        $guideId = $guide->commit();

                        if(isset($_POST['is_module'])){ self::createModuleFiles($_POST['name']);}
                        if($fromOtherModule){ return $guide;}
                        return utils::returnSuccess(true, 'Справочник создан', array('redirect'=>'/admin/guides/edit/'.$guideId));
                    }
                    else{ return utils::returnSuccess(false, utils::mysqlError($sql_request));}
                }
            }
            else{ return utils::returnSuccess(false,'Недостаточно данных');}
        }
    }

    public static function edit($guideId = false, $fromOtherModule = false){
        if($guideId){
            $guide = guides::getById($guideId);
            if($guide){
                if(!count($_POST)){
                    $guidesList = self::lists(false, false);
                    if($guidesList){ return array('guides'=>$guidesList['data'],'edit'=>$guide, 'fields'=>$guide->getValue('fields'));}
                    else {return array('guides'=>array(),'edit'=>$guide, 'fields'=>$guide->getValue('fields'));}
                }
                else{
                    if(utils::validatePost(array('name','descr'))){
                        if(!preg_match("/^[a-zA-Z0-9\_]+$/", $_POST['name'])){
                            return utils::returnSuccess(false,'Внутреннее название справочника должно содержать только латиницу, цифры и знак подчеркивания');
                        }
                        else{
                            //определяем изначальное название таблицы
                            if(!$guide->getValue('is_module')){
                                $oldTableName = 'guide_'.$guide->getName();
                                $newTableName = 'guide_'.escapeString($_POST['name']);
                            }
                            else{
                                $oldTableName = $guide->getName();
                                $newTableName = escapeString($_POST['name']);
                            }

                            //проверка возможности изменения имени справочника
                            if($guide->getValue('is_module') and ($oldTableName != $newTableName)){
                                return utils::returnSuccess(false,'Изменить название справочника-модуля невозможно');
                            }
                            if($checkExistanse = self::getGuideByModuleName($_POST['name']) and $checkExistanse->getId() != $guide->getId()){ return utils::returnSuccess(false, 'Справочник с таким именем существует');}

                            $oldFieldData = $guide->getValue('fields');
                            $newFieldData = array();

                            //проверяем изменения полей
                            $sql_request = '';
                            $someChanges = false;
                            foreach($oldFieldData as $key=>$oldField){
                                if(!isset($_POST['field_name'][$key])){
                                    $someChanges = true;
                                    $sql_request .= 'DROP `'.$oldField['name'].'`, ';
                                }
                                else{
                                    $newName = escapeString($_POST['field_name'][$key]);
                                    $validateName = self::validateFieldName($newName);
                                    if(!$validateName['success']){ return $validateName;}

                                    //если ткнута галка "отображать как список", но не ткнута "можно выбрать несколько значений", принудительно ее ткнуть
                                    if(isset($_POST['guideAsTable'][$key])){ $_POST['multiple'][$key] = 1;}

                                    if(
                                        $newName != $oldField['name'] or
                                        $_POST['field_type'][$key] == 'list' or
                                        $_POST['field_type'][$key]!=$oldField['type'] or
                                        @$oldField['multiple'] != @$_POST['multiple'][$key])
                                    {
                                        $someChanges = true;
                                        $new_sql_type = self::postTypeToSql($_POST['field_type'][$key], $key);

                                        //фикс ошибки, когда поле с не-json данными меняем на тип данных json
                                        if($new_sql_type == 'json'){
                                            //проверяем какого типа было это поле в БД до изменений
                                            $checkOldSQLField = self::customQuery("SHOW FIELDS FROM `{$oldTableName}` where Field ='{$oldField['name']}'");
                                            $oldSQLField = $checkOldSQLField->fetch_assoc();
                                            //если оно не было json
                                            if($oldSQLField['Type']!='json'){
                                                //меняем тип на TEXT
                                                self::customQuery("ALTER TABLE `{$oldTableName}` MODIFY `{$oldField['name']}` TEXT");
                                                //если поле было числового типа, то запишем это число в первый элемент json-массива, в других случаях сносим в нем данные
                                                if(substr($oldSQLField['Type'],0,3) == 'int'){
                                                    self::customQuery("UPDATE `{$oldTableName}` SET `{$oldField['name']}` = CONCAT('[\"',{$oldField['name']},'\"]')");
                                                }
                                                else{
                                                    self::customQuery("UPDATE `{$oldTableName}` SET `{$oldField['name']}` = '[]'");
                                                }
                                            }
                                        }
                                        $sql_request .= 'CHANGE `'.$oldField['name'].'` `'.$newName.'` '.$new_sql_type.' NOT NULL, ';
                                    }
                                    $newFieldData[] = self::appendFieldToJson($key,$newName, $guideId);
                                }
                            }

                            //проверяем добавление полей
                            if(isset($_POST['field_name'])){
                                end($oldFieldData);
                                end($_POST['field_name']);
                                $lastKeyOld = key($oldFieldData);
                                $lastKeyNew = key($_POST['field_name']);
                                if($lastKeyOld === NULL){ $lastKeyOld = -1;}
                                if($lastKeyNew > $lastKeyOld){
                                    for($i = ($lastKeyOld+1); $i <= $lastKeyNew; $i++){
                                        $someChanges = true;
                                        $validateName = self::validateFieldName($_POST['field_name'][$i]);
                                        if(!$validateName['success']){ return $validateName;}
                                        $sql_request .= "ADD COLUMN `".escapeString($_POST['field_name'][$i]).'` '.self::postTypeToSql($_POST['field_type'][$i], $i).' NOT NULL, ';
                                        $newFieldData[] = self::appendFieldToJson($i, escapeString($_POST['field_name'][$i]), $guideId);
                                    }
                                }
                            }

                            if($someChanges){
                                $sql_request = 'ALTER TABLE `'.$newTableName.'` '.$sql_request;
                                $sql_request = substr($sql_request, 0, -2);
                            }

                            if(!$guide->getValue('is_module') and $newTableName != $oldTableName){
                                $sql_request_rename = "RENAME TABLE `".$oldTableName."` TO `".escapeString($newTableName)."`";
                                self::customQuery($sql_request_rename);
                                $guide->setValue('name', $_POST['name']);
                            }

                            if($sql_request){
                                if(!self::customQuery($sql_request)){  return utils::returnSuccess(false, utils::mysqlError($sql_request)); }
                            }

                            $guide->setValue('fields', $newFieldData);
                            $guide->setValue('descr', $_POST['descr']);
                            $guide->setValue('auto_page', (isset($_POST['auto_page']) ? 1:0));
                            $guide->setValue('show_in_menu', (isset($_POST['show_in_menu']) ? 1:0));
                            $guide->setValue('icon_class', $_POST['icon_class']);
                            $guide->commit();

                            if(file_exists('./cache/columns/'.$oldTableName)){ unlink('./cache/columns/'.$oldTableName);}

                            if($fromOtherModule){ return $guide;}
                            else { return utils::returnSuccess(true,'Справочник изменен',array('redirect'=>'/admin/guides/lists/'));}
                        }
                    }
                    else{ return utils::returnSuccess(false,'Недостаточно данных');}
                }
            }
        }
    }

    public static function del($guideId = false){
        if($guide = self::getById($guideId)){
            if(!isset($_POST['confirm'])){
                $guideModule = $guide->getGuideObjectClass();
                $sel = new selector(get_class($guideModule),array('id'));
                $sel->isnotnull('id');
                $objects = $sel->run(true);

                $pageSel = new selector('pages', array('id','name'));
                $pageSel->equals('source_guide', $guideId);
                $pages = $pageSel->run();

                $result = array('confirm'=>1,'objectsNumber'=>$objects, 'pagesList'=>array(), 'guide'=>$guide);
                foreach($pages['data'] as $page){
                    $result['pagesList'][] = array('id'=>$page->getId(), 'name'=>$page->getName());
                }
                return $result;
            }
            else{
                $pageSel = new selector('pages', array('id','name'));
                $pageSel->equals('source_guide', $guideId);
                $pages = $pageSel->run();
                foreach($pages['data'] as $page){ $page->deleteObject();}

                if(!$guide->getValue('is_module')){ $tableName = 'guide_'.$guide->getName();}
                else{   $tableName = $guide->getName();}
                self::customQuery("DROP TABLE `$tableName`");

                if($guide->getValue('is_module')){
                    $permissionsSel = new selector('permissions');
                    $permissionsSel->equals('module', $guide->getName());
                    $permissions = $permissionsSel->run();
                    foreach($permissions['data'] as $permission){
                        $permission->deleteObject();
                    }
                }
                $guide->deleteObject();
                if($guide->getValue('is_module')){self::deleteModuleFiles($guide->getName());}

                if(router::$adminMode){utils::redirect('/admin/guides/lists');}
                else{ utils::redirect('/');}
            }
        }
        else{ return router::err404('Справочник не найден');}
    }

    //guideId - может быть числом и именем модуля
    public static function add_item($guideId = false, $fromOtherModule = false, $returnEmptyObject = false, $fromModal = false){
        if(!is_numeric($guideId)){  $guide = self::getGuideByModuleName($guideId);}
        else{   $guide = self::getGuide($guideId);}

        if($guide){
            if(!count($_POST)){
                if($guide->getValue('is_module')==1 and is_numeric($guideId) and router::$adminMode and !$fromModal){  header('Location: /admin/'.$guide->getName().'/add/'); exit;}
                return array('guide'=>$guide, 'fields'=>$guide->formatFields());
            }
            else{
                $guideObject = $guide->getGuideObjectClass();
                if($returnEmptyObject){ return $guideObject;}

                $objectData = self::prepareObjectData($guide, $guideObject);
                $guideObject = $objectData['item'];

                if($guideObject instanceof pages){ return $guideObject;}

                $newId = $guideObject->commit();
                if($newId){
                    $guideObject->appendPageToObject();

                    if($fromOtherModule){   return $guideObject;}
                    else{
                        if(router::isJSON()){
                            return utils::returnSuccess(true, 'Объект создан',
                                array(
                                    'guideId'=>$guideId,
                                    'object'=>$guideObject->toJson(true),
                                    'previews'=>$objectData['previews'],
                                    'fields'=>$objectData['fields']
                                ));
                        }
                        else{
                            if(router::$adminMode){ utils::redirect('/admin/guides/list_items/'.$guideId);}
                            else{ return utils::returnSuccess(true,'Объект создан', array('objectId'=>$newId));}
                        }
                    }
                }
                else { return utils::returnSuccess(false, 'Не удалось создать объект');}
            }
        }
        else{ router::err404('Справочник не существует');}
    }

    //guideId - может быть числом и именем модуля
    public static function add_item_modal($guideId = false){
        return self::add_item($guideId, false, false, true);
    }

    //guideId - может быть числом и именем модуля
    public static function edit_item_modal($guideId = false, $itemId = false){
        return self::edit_item($guideId, $itemId, false, array(), true);
    }

    public static function edit_item($guideId = false, $itemId = false, $fromPagesModule = false, $ignoreFields = array()){
        if(!is_numeric($guideId)){  $guide = self::getGuideByModuleName($guideId);}
        else{   $guide = self::getGuide($guideId);}

        if($guide){
            $guideObject = $guide->getGuideObjectClass();
            if($item = $guideObject::getById($itemId)){
                $success = 'unknown';
                if(!count($_POST)){
                    if($guide->getValue('is_module')==1 and is_numeric($guideId) and router::$adminMode){
                        if(!isset($_GET['tpl'])){
                            header('Location: /admin/'.$guide->getName().'/edit/'.$itemId);
                            exit;
                        }
                    }
                }
                else{
                    $objectData = self::prepareObjectData($guide, $item, $ignoreFields);
                    $item = $objectData['item'];

                    if($item instanceof pages){ return $item;}

                    $used_rows = $item->commit(); $success = true;
                    if(!$fromPagesModule){
                        $connectedPage = $item->editConnectedPage();
                        if(!$connectedPage){    $item->appendPageToObject();}
                    }
                    if(router::isJSON()){
                        return utils::returnSuccess(true,'Объект изменен',
                            array(
                                'guideId'=>$guideId,
                                'object'=>$item->toJson(true),
                                'previews'=>$objectData['previews'],
                                'fields'=>$objectData['fields']
                            ));
                    }
                    else{
                        if($fromPagesModule){ return $used_rows;}
                        else{
                            if(router::$adminMode){
                                if($guide->getValue('is_module')==1){   utils::redirect('/admin/'.$guide->getName().'/edit/'.$itemId.'?success='.$used_rows);}
                                else{ utils::redirect('/admin/guides/edit_item/'.$guide->getId().'/'.$itemId.'?success='.$used_rows);}
                            }
                            else{return utils::returnSuccess(($used_rows > 0));}
                        }
                    }
                }
                if($guide->getName() == 'pages'){   return array('guide'=>$guide, 'edit'=>$item, 'success'=>$success);}
                else{
                    $extend = array();
                    if($appendedPage = $item->getConnectedPage()){ $extend = page_extend::lists($appendedPage->getId());}
                    return array('guide'=>$guide, 'edit'=>$item, 'appendedPage'=>$appendedPage, 'extend'=>$extend, 'success'=>$success);
                }
            }
            else { return router::err404('Объект не найден');}
        }
        else{ return router::err404('Справочник не найден');}
    }

    private static function prepareObjectData($guide, $item, $ignoreFields = array()){
        if(!isset($_POST['name'])){
            if(isset($_POST['page']['name'])){ $item->setValue('name', $_POST['page']['name']);}
            else{ $item->setValue('name', '');}
        }
        else{   $item->setValue('name', $_POST['name']);}

        $guideFields = $guide->formatFields();
        $previews = array();
        foreach($guideFields as $field){
            $fieldName = $field['name'];
            if(!in_array($fieldName, $ignoreFields)){
                if(isset($_POST[$fieldName])){
                    //из-за странно работающих функций JSON MySQL, которые не могут найти число в одномерном массиве, все числа должны быть строка. логика, ау
                    if(is_array($_POST[$fieldName]) and $field['type'] == 'guide'){
                        foreach($_POST[$fieldName] as $i=>$arrItem){
                            if(is_numeric($arrItem)){ $_POST[$fieldName][$i] = (string)$_POST[$fieldName][$i];}
                        }
                    }
                    $item->setValue($fieldName, $_POST[$fieldName]);
                }
                else if(isset($_FILES[$fieldName])){
                    if($field['type'] === 'filelist' and is_array($_FILES[$fieldName]['name'])){
                        $filesData = files::multiUpload($fieldName,'data');
                        $fileIds = array();
                        foreach($filesData as $fileData){
                            if(isset($fileData['fileId'])) {$fileIds[] = $fileData['fileId'];}
                        }
                        $previousIds = $item->getValue($fieldName);
                        if(!$previousIds){ $previousIds = array();}
                        $newIds = array_merge($previousIds, $fileIds);
                        $item->setValue($fieldName, $newIds);
                    }

                    else if($field['type'] === 'file' and !$_FILES[$fieldName]['error']){
                        $fileObject = new files();
                        $fileObject->createFile($_FILES[$fieldName]['name']);
                        $fileData = $fileObject->upload($fieldName,'files');

                        if($fileData['success']){
                            if($oldFileId = $item->getValue($fieldName) and $oldFile = files::getById($oldFileId)){
                                $oldFile->del();
                            }

                            $item->setValue($fieldName, $fileData['fileId']);
                            $preview = files::preview($fileData['fileId'], 50, 50, 'crop');
                            if($preview!='/file-not-image.jpg'){
                                $previews[$fieldName] = $preview;
                            }
                        }
                    }
                }
                else{
                    if($field['type']=='boolean'){ $item->setValue($fieldName, 0);}
                    /*if($field['type'] == 'guide'){
                        if(isset($field['multiple'])){ $item->setValue($fieldName, array());}
                        else{ $item->setValue($fieldName, 0);}
                    }*/
                }
            }
        }
        return array('item'=>$item, 'previews'=>$previews, 'fields'=>$guideFields);
    }

    public static function item_changeFileOrder(){
        if(utils::validatePost(array('guideId','itemId','fileId','field'))){
            if($item = self::get_item($_POST['guideId'], $_POST['itemId'])){
                $item->setValue($_POST['field'], $_POST['fileId']);
                $item->commit();
                return utils::returnSuccess(true);
            }
            else{ return utils::returnSuccess(false, 'Объект не найден');}
        }
        else{ return utils::returnSuccess(false, 'Неполные данные');}
    }

    public static function list_items($guideId = false, $filterById = array(), $forceAllFields = false, $order = 'DESC', $orderField = 'id', $ignorePaging = false){
        if(!is_numeric($guideId)){  $guide = self::getGuideByModuleName($guideId);}
        else{   $guide = self::getGuide($guideId);}

        if(!$ignorePaging) $ignorePaging = isset($_GET['ignore_paging']);

        $page = (isset($_GET['page'])) ? (int)$_GET['page']  : 1;
        $limit = utils::getRowsLimit();

        if($guide){
            if($forceAllFields){ $needFieldsForRequest = []; $needFields = [];}
            else{
                $needFieldsForRequest = array('id','name','create_time'); $needFields = array();
                foreach($guide->getValue('fields') as $field){
                    if((isset($field['table']) and $field['table']) or $forceAllFields){
                        $needFieldsForRequest[] = $field['name'];
                        $needFields[] = $field;
                    }
                }
            }

            $guideObject = $guide->getGuideObjectClass();

            $sel = new selector(get_class($guideObject), $needFieldsForRequest);
            $sel->isnotnull('id');
            if($filterById){
                $sel->addAND();
                $sel->in('id',$filterById);
            }

            if(!$filterById){
                if(isset($_GET['search'])){
                    $sel->addAND();
                    $sel->like('name','%'.$_GET['search'].'%');
                }
            }

            if(property_exists($guideObject, 'lang')){
                //язык
                $sel->addAND();
                if(isset($_GET['lang'])){ $lang = $_GET['lang'];}
                else{ $lang = 'ru';}
                $sel->equals('lang',$lang);
            }

            $sel->order($orderField,$order);
            if($limit and !$ignorePaging) { $sel->limit($limit, $limit * ($page-1));}

            $result = $sel->run(0, $ignorePaging);
            if($result) { return array_merge(array('guide'=>$guide, 'need_fields'=>$needFields), $result); }
            else {return array('guide'=>$guide, 'need_fields'=>$needFields);}
        }
        else{ router::err404('Справочник не найден');}
    }

    public static function del_item($guideId = false, $itemId = false){
        if(!is_numeric($guideId)){  $guide = self::getGuideByModuleName($guideId);}
        else{   $guide = self::getGuide($guideId);}
        if($guide){
            $guideObject = $guide->getGuideObjectClass();
            $item = $guideObject::getById($itemId);
            if($item){
                $connectedPage = $item->getConnectedPage();
                if($connectedPage){ $connectedPage->deleteObject();}
                $item->deleteObject();
            }
        }
        if(router::isJSON()){ return array('success'=>true);}
        else{
            if(router::$adminMode){ utils::redirect('/admin/guides/list_items/'.$guideId);}
            else{ return array('success'=>true);}
        }
    }

    public static function get_item($guideId = false, $itemId = false, $returnWithGuide = false){
        $guide = self::getGuide($guideId);

        if($guide){
            $guideObject = $guide->getGuideObjectClass();
            $item = $guideObject::getById($itemId);
            if($returnWithGuide){ return array('guide'=>$guide, 'item'=>$item);}
            else {return $item;}
        }
        else{ return false;}
    }

    private static function getGuide($guideId){
        global $loadedGuides;

        if(isset($loadedGuides['id'.$guideId])){ return $loadedGuides['id'.$guideId];}
        else{
            if($guideId){
                $guide = self::getById($guideId);
                if($guide){ $loadedGuides['id'.$guideId] = $guide; return $guide;}
                else{ return false;}
            }
            else {return false;}
        }
    }

    private static function appendFieldToJson($key, $fieldName, $oldGuideId = false){
        $fieldDataElement = array(
            'name'=>$fieldName,
            'descr'=>$_POST['field_descr'][$key],
            'type'=>$_POST['field_type'][$key],
            'index'=>$_POST['field_index'][$key],
            'table'=>(isset($_POST['field_table'][$key])) ? $_POST['field_table'][$key]:0,
            'required'=>(isset($_POST['field_required'][$key])) ? $_POST['field_required'][$key]:0
        );
        if($_POST['field_type'][$key] == 'guide'){
            //if($oldGuideId and $_POST['guide'][$key] == $oldGuideId){ $_POST['guide'][$key] = 0;}
            $fieldDataElement['fromId'] = $_POST['guide'][$key];
            if(isset($_POST['guideAsTable'][$key])){
                $fieldDataElement['guideAsTable'] = 1;
                $fieldDataElement['multiple'] = 1;  //одно без другого не работает
            }
            if(isset($_POST['multiple'][$key])){
                $fieldDataElement['multiple'] = 1;
            }
        }
        if($_POST['field_type'][$key] == 'list'){ $fieldDataElement['list'] = $_POST['field_values'][$key];}
        if(isset($_POST['field_hidden'][$key])){ $fieldDataElement['hidden'] = 1;}
        return $fieldDataElement;
    }

    private static function validateFieldName($fieldName){
        if(!preg_match("/^[a-zA-Z0-9\_]+$/", $fieldName)){
            return utils::returnSuccess(false,$fieldName. ' - некорректное название поля. Оно должно содержать только латиницу, цифры и знак подчеркивания');
        }
        if(in_array($fieldName, array('id','name','create_time'/*, 'alias'*/))){
            return utils::returnSuccess(false,'Поля id, name, create_time - зарезервированные названия');
        }
        if(count(array_keys($_POST['field_name'], $fieldName))>1){
            return utils::returnSuccess(false,$fieldName.' встречается больше 1 раза');
        }
        return utils::returnSuccess(true);
    }

    private static function postTypeToSql($type, $key){
        switch($type){
            case 'guide':{
                if(isset($_POST['multiple'][$key])){ $sql_type = 'json';}
                else { $sql_type = 'int';}
                break;
            }
            case 'file':{ $sql_type = 'int'; break;}
            case 'filelist': {$sql_type = 'json'; break;}
            case 'list':{
                $sql_type = 'ENUM(';
                foreach($_POST['field_values'][$key] as $valueNum=>$value){
                    $sql_type.="'".escapeString($value)."'";
                    if($valueNum < count($_POST['field_values'][$key])-1){ $sql_type .= ", ";}
                }
                $sql_type .= ")"; break;
            }
            case 'varchar':{    $sql_type = 'VARCHAR(255)'; break;}
            case 'htmltext':{   $sql_type = 'TEXT'; break;}
            default:{   $sql_type = escapeString(strtoupper($_POST['field_type'][$key]));}
        }
        return $sql_type;
    }

    public function getGuideObjectClass(){
        $guideObjectClassName = $this->getValue('name');
        if(class_exists($guideObjectClassName)){
            $guideObject = new $guideObjectClassName();
        }
        else{
            $guideObjectClassName = 'guide_'.$guideObjectClassName;
            if(!class_exists($guideObjectClassName)){
                eval('class '.$guideObjectClassName.' extends baseModule{}');
            }
            $guideObject = new $guideObjectClassName();
        }
        return $guideObject;
    }

    protected static function createModuleFiles($newModuleName){
        $moduleFileText = file_get_contents('./core/module_tpl/module_tpl.ph_');
        $moduleFileCode = str_replace('%module_name%', $newModuleName, $moduleFileText);
        file_put_contents('./core/modules/'.$newModuleName.'.php', $moduleFileCode);
        if(!is_dir('./admin_tpls/'.$newModuleName)){ mkdir('./admin_tpls/'.$newModuleName, 0777, true);}
        if(!is_dir('./templates/'.$newModuleName)){ mkdir('./templates/'.$newModuleName, 0777, true);}

        copy('./core/module_tpl/add.phtm_','./admin_tpls/'.$newModuleName.'/add.phtml');
        copy('./core/module_tpl/edit.phtm_','./admin_tpls/'.$newModuleName.'/edit.phtml');
        copy('./core/module_tpl/lists.phtm_','./admin_tpls/'.$newModuleName.'/lists.phtml');
        copy('./core/module_tpl/view.phtm_','./templates/'.$newModuleName.'/view.phtml');

        permissions::setDefaultPermissionsForModule($newModuleName);
    }

    protected static function deleteModuleFiles($moduleName){
        unlink('./core/modules/'.$moduleName.'.php');
        utils::recursiveDelDir(SITE_ROOT.'/admin_tpls/'.$moduleName);
        utils::recursiveDelDir(SITE_ROOT.'/templates/'.$moduleName);
    }

    public function formatFields($sortFields = true){
        $fields = $this->getValue('fields');
        $newFields = array(); $hasSortableFields = false;
        foreach($fields as $field){
            $newFields[$field['name']] = $field;
            if(!$hasSortableFields and isset($field['index'])){ $hasSortableFields = true;}
        }

        if($hasSortableFields and $sortFields){
            uasort($newFields, function($item1, $item2){
                if($item1['index'] == $item2['index']){ return 0;}
                else{
                    return ($item1['index']  < $item2['index']) ? -1: 1;
                }
            });
        }

        return $newFields;
    }

    public static function getGuideByModuleName($moduleName = false){
        global $loadedGuides;
        if(isset($loadedGuides[$moduleName])){ return $loadedGuides[$moduleName];}
        else{
            if(substr($moduleName,0,5) == 'guide'){ $moduleName = substr($moduleName,6);}
            $sel = new selector('guides');
            $sel->equals('name',$moduleName);
            $sel->limit(1);
            return $sel->run();
        }
    }

    /* если предполагается у массива готовых объектов дергать через getValue одни и те же связанные объекты,
     * то рекомендуется воспользоваться этой функцией. В этом случае вместо одиночных getById в цикле будет произведен
     * один запрос по всем ID связанных объектов, и полученные связанные объекты будут загружены в объекты массива,
     * что благотворно скажется на производительности системы и количестве запросов к БД
     * $arrOfObjects - массив объектов
     * field - поле с ID связанного объекта
     *
     * $byIds = [
     *    '3'=>[object, object, ...],
     *    '5'=>[object, object, ...],
     *    '7,8,1'=>[object, object]
     * ]
     */
    public static function massGetObjectsByIDs($arrOfObjects, $field){
        if(!isset($arrOfObjects[0])){ return [];}
        else{
            if(!is_object($arrOfObjects[0])){ return false;}
            $byIds = []; $valueArray = [];

            $objectClassName = $arrOfObjects[0]->getObjectClassName();
            foreach($arrOfObjects as $object){
                //все объекты в мссиве должны быть объектами, и одного класса
                if(is_object($object) and $objectClassName == $object->getObjectClassName()){
                    $fieldValue = $object->getValue($field, 'jsonAsArray');

                    if($fieldValue){
                        //проверка на массив значений или одиночное значение
                        if(is_array($fieldValue)){
                            $valueArray = array_merge($valueArray, $fieldValue);
                            $byIds[implode(',',$fieldValue)][] = $object;
                        }
                        else{
                            $valueArray[] = $fieldValue;
                            $byIds[$fieldValue][] = $object;
                        }
                    }
                }
                else{ return false;}
            }
            $valueArray = array_unique($valueArray);
           // if(count($valueArray) == 1 and $valueArray[0] == 0){ return false;}
            //узнаем, к какому справочнику принадлежит искомое поле

            if(count($valueArray)>0){
                $guideID = $arrOfObjects[0]->_guideFields[$field]['fromId'];
                if($guide = self::getGuide($guideID)){
                    $isMultipleField = (isset($arrOfObjects[0]->_guideFields[$field]['multiple'])) ? $arrOfObjects[0]->_guideFields[$field]['multiple'] : 0;

                    //запрашиваем объекты одним массивом
                    $sel = new selector($guide->getName());
                    $sel->in('id', $valueArray);
                    $allConnObjects = $sel->run(0, 1);

                    //перестраиваем массив так, чтобы в ключах были IDы объектов
                    $connObjectsByIDs = [];
                    foreach($allConnObjects['data'] as $connectedObject){ $connObjectsByIDs[$connectedObject->getId()] = $connectedObject;}

                    //раскладываем связанные объекты в их родительские объекты
                    foreach($byIds as $connObjIDs => $parentObjectsArray){
                        $connObjIDsArray = explode(',', $connObjIDs);   //разбиваем строку вида "1,2,3" на массив [1,2,3]

                        //если там только один ID (поле без множественного выбора)
                        if(!$isMultipleField){
                            //проверяем, что связанный объект с этим ID существует
                            if(isset($connObjectsByIDs[$connObjIDsArray[0]])){
                                foreach($parentObjectsArray as $object) $object->$field = $connObjectsByIDs[$connObjIDsArray[0]];
                            }
                        }
                        //если массив ID
                        else{
                            $connObjectsArr = [];
                            //собираем эти объекты из массива $connObjectsByIDs
                            foreach($connObjIDsArray as $connObjectId){
                                //проверяем, что связанный объект с этим ID существует
                                if(isset($connObjectsByIDs[$connObjectId])){
                                    $connObjectsArr[] = $connObjectsByIDs[$connObjectId];
                                }
                            }
                            //вкладываем массив связанных объектов в родительские объекты
                            foreach($parentObjectsArray as $object) $object->$field = $connObjectsArr;
                        }
                    }
                    //вернем список полученных связанных объектов
                    return $allConnObjects['data'];
                }
                else{ return false;}
            }
            else{ return false;}
        }
    }
}