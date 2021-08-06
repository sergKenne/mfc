<?php
/**
 * Created by PhpStorm.
 * User: Александров Олег
 * Date: 08.07.19
 * Time: 15:13
 */
class pages extends baseModule{

    public static function lists($parent = 0, $lang = false, $withExtendFields = false){
        if(!$lang){ $lang = 'ru';}
        $sel = new selector('pages', array('id','name','source_guide','source_object','url'));
        if(!isset($_GET['search'])){
            $sel->equals('parent', $parent);
            $sel->addAND();
        }
        $sel->equals('lang',$lang);
        if(isset($_GET['search'])){
            $sel->addAND();
            $sel->like('name','%'.$_GET['search'].'%');
        }
        $result = $sel->run();
        if(!$result){ return array('lang'=>$lang);}
        else{
            if($withExtendFields){
                foreach($result['data'] as $i=>$page){
                    $result['data'][$i]->loadExtendFields();
                }
            }
            $result['lang'] = $lang;
            return $result;
        }
    }

    public static function lists_modal($parent = 0, $lang = 'ru'){
        return self::lists($parent, $lang);
    }

    public static function transfer($pageId = false, $newParentId = false){
        if($pageId == $newParentId){ return utils::returnSuccess(false,'Нельзя сделать страницу собственным родителем');}

        $page = pages::getById($pageId); $newParent = pages::getById($newParentId);
        if(!$page){ return utils::returnSuccess(false,'Страница не существует');}
        if(!$newParent and $newParent!=0){ return utils::returnSuccess(false,'Целевая страница не существует');}

        $page->setValue('parent',$newParentId);
        $page->generateFullURL();
        $page->commit();

        self::changeURLsAfterTransfer($page->getId());
        return self::lists($newParentId);
    }

    private static function changeURLsAfterTransfer($parentId = false){
        $sel = new selector('pages');
        $sel->equals('parent', $parentId);
        $subPages = $sel->run();
        if(!$subPages){ return;}

        foreach($subPages['data'] as $subPage){
            $subPage->generateFullURL();
            $subPage->commit();
            self::changeURLsAfterTransfer($subPage->getId());
        }
    }

    private function generateFullURL(){
        $url = $this->getValue('alias');
        $parentId = $this->getValue('parent');
        while($parentId!=0){
            $sel = new selector('pages');
            $sel->equals('id', $parentId);
            $sel->limit(1);
            $parentPage = $sel->run();
            if($parentPage){
                $url = $parentPage->getValue('alias').'/'.$url;
                $parentId = $parentPage->getValue('parent');
            }
            else{ break;}
        }
        $this->setValue('url', $url);
    }

    public static function add($sourceGuide = false, $sourceObject = false, $callFromAnotherModule = false){
        $guide = guides::getGuideByModuleName('pages');
        if(!count($_POST)){
            return array('guide'=>$guide, 'fields'=>$guide->formatFields());
        }
        else{
            $page = guides::add_item($guide->getId());
            $page->prepareData($sourceGuide, $sourceObject);
            $newId = $page->commit();
            if($newId){
                pages_languages::addLink($newId, $page->getValue('lang'));
                if(router::isJSON()){
                    return utils::returnSuccess(true, 'Объект создан', array('object'=>$page->toJson(true)));
                }
                else{
                    if(!$callFromAnotherModule and router::$adminMode){  utils::redirect('/admin/pages/edit/'.$newId);}
                    else{ return $page;}
                }
            }
            else { return utils::returnSuccess(false, 'Не удалось создать объект');}
        }
    }

    public static function edit($itemId = false, $callFromAnotherModule = false){
        $guide = guides::getGuideByModuleName('pages');
        if(!count($_POST)){
            $page = self::getById($itemId);                             //получение самой страницы
            if(!$page){ return router::err404('страница не найдена');}
            $additionalFields = page_extend::lists($itemId);                    //получение доп.полей
            $guidesList = guides::lists(false, false, 0);                                      //получение списка справочников
            if($guidesList){ $guidesList = $guidesList['data'];}
            //получение связанного объекта данных
            $sourceObject = $page->getSourceObject();
            if($sourceObject){  $objectGuide = guides::getGuideByModuleName($sourceObject->getObjectClassName());}
            else {$objectGuide = false;}
            return array('edit'=>$page,'extend'=>$additionalFields, 'guides'=>$guidesList, 'source'=>$sourceObject, 'guide'=>$guide, 'objectGuide'=>$objectGuide);
        }
        else{
            page_extend::save($itemId);
            if($guide){
                if($page = guides::edit_item($guide->getId(), $itemId)){
                    $oldAlias = $page->getValue('alias');
                    $page->prepareData();
                    $used_rows = $page->commit();

                    if($oldAlias != $page->getValue('alias')){ self::changeURLsAfterTransfer($page->getId());}

                    //обновить связанный объект, если он есть
                    if($object = $page->getSourceObject()){
                        $updateObject = guides::edit_item($page->getValue('source_guide'), $page->getValue('source_object'), true);
                    }
                    else{ $updateObject = 0;}

                    if(router::isJSON()){
                        return utils::returnSuccess(true,'Объект изменен',
                            array(
                                'guideId'=>$guide->getId(),
                                'object'=>$page->toJson(true)
                            ));
                    }
                    else{
                        if(!$callFromAnotherModule and router::$adminMode){
                            utils::redirect('/admin/pages/edit/'.$itemId.'?success='.((int)$used_rows+(int)$updateObject));
                        }
                    }
                }

            }
            else{ return router::err404('Справочник не найден');}
        }
    }

    public static function del($pageId = false){
        if($page = self::getById($pageId)){
            $lang = $page->getValue('lang');
            $toDel = array();
            $toDel['page'] = $page;
            $toDel['children'] = self::getAllChildren($pageId);
            $toDel['object'] = $page->getSourceObject();
            $toDel['langLink'] = $link = pages_languages::getAllLinks($pageId, $lang, true);
            //todo: удалять связанный объект, удалять дочерние объекты, удалять ид из связки языковых версий
            if(!isset($_POST['confirm'])){
                $toDel['confirm'] = true;
                return $toDel;
            }
            else{
                //удаление страницы
                $toDel['page']->deleteObject();
                //удаление подчиненных
                if(is_array($toDel['children'])){
                    foreach($toDel['children'] as $child){
                        if(!isset($_POST['dontDeleteChildObjects'])){
                            if($childObject = $child->getSourceObject()){
                                $childObject->deleteObject();
                            }
                        }
                        $child->deleteObject();
                    }
                }

                //удаление связанного объекта
                if(!isset($_POST['dontDeleteObject']) and is_object($toDel['object'])){
                    $toDel['object']->deleteObject();
                }
                //удаление языковой связки
                $toDel['langLink']->setValue($lang, 0);
                //если в объекте связки все значения стали нулями - удаляем ее, если нет - сохраняем
                $reformatted = $toDel['langLink']->reFormat();
                $allZeros = true;
                foreach($reformatted as $langLinkItem){
                    if($langLinkItem['id']){ $allZeros = false;}
                }
                if($allZeros){ $toDel['langLink']->deleteObject();}
                else{ $toDel['langLink']->commit();}

                if(router::$adminMode){utils::redirect('/admin/pages/lists');}
                else{ utils::redirect('/');}
            }
        }
    }

    public static function phtmlFiles($templateFolder = false){
        if(!$templateFolder){ return array();}
        if(file_exists('./templates/'.$templateFolder)){
            $files = scandir('./templates/'.$templateFolder);
        }
        else{   return array();}

        if(!$files){ return array();}
        $result = array();
        if($files){
            foreach($files as $file){
                if(is_dir('./templates/'.$templateFolder.'/'.$file)){ continue;}
                if(pathinfo('./templates/'.$templateFolder.'/'.$file, PATHINFO_EXTENSION) != 'phtml'){ continue;}
                $result[] = $file;
            }
        }
        return array('phtml'=>$result);
    }

    private function prepareData($sourceGuide = false, $sourceObject = false){
        //проверить галку "это главная страница" и удалить эту галку у других страниц
        if(isset($_POST['page']['is_index_page']) and !$this->getValue('is_index_page')){
            self::customQuery("UPDATE `pages` SET `is_index_page` = 0 WHERE `is_index_page` = 1");
        }

        //запись данных страницы
        foreach($_POST['page'] as $field=>$value){
            $this->setValue($field,$value);
        }

        //если у страницы не указано название, но указано у объекта - вставить его туда
        if(isset($_POST['name']) and !$_POST['page']['name']){
            $this->setValue('name', $_POST['name']);
        }

        //если страница не опубликована - поставить 0
        if(!isset($_POST['page']['published'])){ $this->setValue('published', 0);}

        //проверка языка
        if(!$this->getValue('lang')){
            if(isset($_POST['lang'])) {$this->setValue('lang',$_POST['lang']);}
            else{ $this->setValue('lang','ru');}
        }

        //если сняли галку главная страница
        if(!isset($_POST['page']['is_index_page']) and $this->getValue('is_index_page')){
            $this->setValue('is_index_page',0);
        }

        //установка шаблона
        if($_POST['template']['folder'] and $_POST['template']['file']){
            $this->setValue('template', $_POST['template']['folder'].'/'.$_POST['template']['file']);
        }
        else{ $this->setValue('template','');}

        //установка алиаса
        if($alias = $this->getValue('alias')){
            if(!preg_match("/^[a-zA-Z0-9\_]+$/", $alias)){
                $alias = utils::translit($this->getName());
                $this->setValue('alias', $alias);
            }
        }
        else{
            $alias = utils::translit($this->getName());
            $this->setValue('alias', $alias);
        }

        $sel = new selector('pages');
        $sel->equals('parent', $this->getValue('parent'));
        $sel->addAND();
        $sel->equals('alias', $alias);
        if($this->getId()){
            $sel->addAND();
            $sel->notequals('id', $this->getId());
        }
        $sel->limit(1);
        $exist = $sel->run();
        if($exist){
            $alias .= time();
            $this->setValue('alias', $alias);
        }

        if($sourceGuide and $sourceObject){
            $this->setValue('source_guide', $sourceGuide);
            $this->setValue('source_object', $sourceObject);
        }
        $this->generateFullURL();
    }

    public static function view($page = false){
        if(is_numeric($page)){ $page = pages::getById($page);}

        if($page instanceof pages){
            if($redirectUrl = $page->getValue('redirect')){ utils::redirect($redirectUrl);}
            else{
                $page->loadExtendFields();
                if($sourceObject = $page->getSourceObject()){   $sourceObjectView = $sourceObject::view($sourceObject, true);}
                else{ $sourceObjectView = false;}
                $parents = $page->getAllParents();
                return array(
                    'page'=>$page,
                    'source'=>$sourceObjectView,
                    'langs'=>pages_languages::getAllLinks($page->getId(),$page->getValue('lang')),
                    'breadcrumbs'=>array_values($parents)
                );
            }
        }
        else{ return router::err404();}
    }

    private function loadExtendFields(){
        $extendFields = page_extend::lists($this->getId());
        if(isset($extendFields['data'])){
            foreach($extendFields['data'] as $field){
                $fieldName = $field->getName();
                if($field->getValue('type') == 'guide'){
                    //получаем список id элементов справочника
                    $selectedIds = $field->getValue('value');
                    if(!is_array($selectedIds)){ $selectedIds = array($selectedIds);}
                    if(count($selectedIds)>0){
                        //получаем ид справочника
                        $guideID = $field->getValue('additional_data');
                        //получаем объекты по айдишникам
                        $selectedItems = guides::list_items($guideID, $selectedIds,true, false, false, true);
                        //возвращаем их
                        if(isset($selectedItems['data'])){
                            if(!$field->getValue('multiple') and !$field->getValue('guideAsTable')){  $this->$fieldName = current($selectedItems['data']);}
                            else {  $this->$fieldName = $selectedItems['data'];}
                        }
                        else{ $this->$fieldName = $field->getValue('value',1);}
                    }
                    else{ $this->$fieldName = $field->getValue('value',1);}
                }
                elseif($field->getValue('type') == 'json'){
                    $this->$fieldName = $field->getValue('value');
                }
                else {$this->$fieldName = $field->getValue('value', false, false);}
            }
        }
    }

    public static function add_field(){
        return page_extend::add();
    }

    public static function edit_field($fieldId = false){
        return page_extend::edit($fieldId);
    }

    public static function del_field($fieldId = false){
        return page_extend::del($fieldId);
    }

    public static function list_fields(){
        return page_extend::lists();
    }

    private static function getAllChildren($pageId){
        static $childrenList;
        $sel = new selector('pages', array('id','name'));
        $sel->equals('parent', $pageId);
        $subPages = $sel->run();

        if($subPages){
            foreach($subPages['data'] as $subPage){
                $childrenList[] = $subPage;
                self::getAllChildren($subPage->getId());
            }
        }
        return $childrenList;
    }

    //todo исправить эту дрянь. в качестве основы можно использовать функцию generateFullURL()
    private function getAllParents($fullObject = false){
        $parentList = array();
        $object = $this;
        if($this->getValue('is_index_page')){
            if($fullObject){    $parentList[] = $object;}
            else{   $parentList[] = array('id'=>$object->getId(), 'url'=>'/', 'name'=>$object->getName());}
        }
        else{
            while($object){
                if($fullObject){    $parentList[] = $object;}
                else{   $parentList[] = array('id'=>$object->getId(), 'url'=>'/'.$object->getValue('url'), 'name'=>$object->getName());}

                $parentId = $object->getValue('parent');
                if($parentId){
                    $object = self::getById($parentId);
                }
                else{
                    $sel = new selector('pages');
                    $sel->equals('is_index_page',1);
                    $sel->limit(1);
                    $object = $sel->run();
                    if($object){
                        if($fullObject){    $parentList[] = $object;}
                        else{   $parentList[] = array('id'=>$object->getId(), 'url'=>'/', 'name'=>$object->getName());}
                    }
                    break;
                }
            }
        }
        return array_reverse($parentList);
    }

    public static function makeLangPageCopy($pageId, $newLangPrefix){
        $page = pages::getById($pageId);
        if($page){
            $currentLangPrefix = $page->getValue('lang');

            if($object = $page->getSourceObject()){
                $newObjectId = $object->duplicateObject($newLangPrefix);
                $page->setValue('source_object',$newObjectId);
            }

            $page->setValue('parent',0);
            $newPageId = $page->duplicateObject($newLangPrefix);
            if($newPageId){
                $page->generateFullURL();
                pages_languages::addNewLangToLink($pageId,$currentLangPrefix,$newPageId,$newLangPrefix);
                utils::redirect('/admin/pages/edit/'.$newPageId);
            }
            else{ return utils::returnSuccess(false, 'Не удалось создать копию');}
        }
        else{ return router::err404('Исходная страница не найдена');}
    }

    public static function numpages($thisPageContains = false, $total = false){
        $result = array();
        $limit = (isset($_GET['limit'])) ? '&limit='.$_GET['limit']:'';

        if(isset($_GET['page']) and $_GET['page']>0){
            $result['prev_link'] = "?page=".($_GET['page']-1).$limit;
        }

        if($thisPageContains >= @$_GET['limit']){
            if(isset($_GET['page']) and $_GET['page']>0){
                $result['next_link'] = "?page=".($_GET['page']+1).$limit;
            }
            else{ $result['next_link'] = "?page=1".$limit;}
        }
        return $result;
    }

    public function getSourceObject(){
        if($sourceGuideId = $this->getValue('source_guide') and $sourceObjectId = $this->getValue('source_object')){
            return guides::get_item($sourceGuideId, $sourceObjectId);
        }
        else{ return false;}
    }

    public function getPageURL(){
        $url = '';
        if(router::$currentLang){ $url = '/'.router::$currentLang;}
        return $url .= '/'.$this->getValue('url');
    }
}

class page_extend extends baseModule{

    public static function lists($pageId = false){
        $sel = new selector('page_extend');
        $sel->isnotnull('id');
        if($pageId){
            $sel->addAND();
            $sel->equals('pageId', $pageId);
            $sel->order('priority','ASC');
        }
        else{
            $sel->order('pageId','ASC');
        }
        return $sel->run();
    }

    public static function add(){
        if(!count($_POST)){
            $guidesList = guides::lists(false, false, 0);
            return array('guides'=>$guidesList);
        }
        else{
            if(!preg_match("/^[a-zA-Z0-9\_]+$/", $_POST['name'])){
                return utils::returnSuccess(false,$_POST['name']. ' - некорректное название поля. Оно должно содержать только латиницу, цифры и знак подчеркивания');
            }
            $pageField = new page_extend();
            $pageField->prepareData();
            $pageField->commit();
            utils::redirect('/admin/pages/list_fields');
        }
    }

    public static function edit($fieldId = false){
        if($pageField = self::getById($fieldId)){
            if(!count($_POST)){
                $guidesList = guides::lists(false, false, 0);
                $pageName = pages::getById($pageField->getValue('pageId'),array('name'));
                if(!$pageName){ $pageName = 'Выбрать страницу';}
                return array('guides'=>$guidesList, 'edit'=>$pageField, 'pageName'=>$pageName);
            }
            else{
                $pageField->prepareData();
                $pageField->commit();
                utils::redirect('/admin/pages/list_fields');
            }
        }
        else {return router::err404();}
    }

    private function prepareData(){
        $this->setValue('name', $_POST['name']);
        $this->setValue('descr', $_POST['descr']);
        $this->setValue('pageId', $_POST['pageId']);
        $this->setValue('type', $_POST['field_type']);
        $this->setValue('priority', $_POST['priority']);

        switch($_POST['field_type']){
            case 'guide':{
                $this->setValue('additional_data', $_POST['guide']);
                $this->setValue('guideAsTable', (isset($_POST['guideAsTable'])) ? 1: 0);
                $this->setValue('multiple', (isset($_POST['multiple'])) ? 1: 0);
                break;
            }
            case 'list':{ $this->setValue('additional_data', $_POST['field_values']); break;}
        }
    }

    public static function del($fieldId = false){
        self::delById($fieldId);
        utils::redirect('/admin/pages/list_fields');
    }

    public static function save($pageId = false){
        $fields = self::lists($pageId);
        if(isset($fields['data'])){
            foreach($fields['data'] as $field){
                $fieldName = $field->getName();
                if(isset($_POST[$fieldName])){
                    $field->setValue('value', $_POST[$fieldName]);
                }
                else if(isset($_FILES[$fieldName])){
                    if($field->getValue('type') === 'filelist' and is_array($_FILES[$fieldName]['name'])){
                        $filesData = files::multiUpload($fieldName,'data');
                        $fileIds = array();
                        foreach($filesData as $fileData){
                            if(isset($fileData['fileId'])) {$fileIds[] = $fileData['fileId'];}
                        }
                        $previousIds = $field->getValue('value');
                        if(!$previousIds){ $previousIds = array();}
                        $newIds = array_merge($previousIds, $fileIds);
                        $field->setValue('value', $newIds);
                    }
                    else if($field->getValue('type') === 'file' and !$_FILES[$fieldName]['error']){
                        $fileObject = new files();
                        $fileObject->createFile($_FILES[$fieldName]['name']);
                        $fileData = $fileObject->upload($fieldName,'files');

                        if($fileData['success']){
                            $field->setValue('value', $fileData['fileId']);
                            $preview = files::preview($fileData['fileId'], 50, 50, 'crop');
                            if($preview!='/file-not-image.jpg'){
                                $previews[$fieldName] = $preview;
                            }
                        }
                    }
                }
                else{
                    if($field->getValue('type')=='boolean'){ $field->setValue('value', 0);}
                    if($field->getValue('type') == 'guide'){
                        if($field->getValue('multiple')){ $field->setValue('value', array());}
                        else{ $field->setValue('value', 0);}
                    }
                }
                $field->commit();
            }
        }
    }
}