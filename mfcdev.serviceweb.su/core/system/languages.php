<?php 
    class languages extends baseModule{

        public static function lists($simpleList = true){
            $sel = new selector('languages');
            $sel->isnotnull('id');
            $sel->order('id','ASC');
            $result = $sel->run();

            if(!$simpleList){
                $langsArray = array();
                foreach($result['data'] as $lang){
                    $langsArray[$lang->getValue('prefix')] = $lang->getName();
                }
                return $langsArray;
            }
            else{ return $result;}
        }

        public static function add(){
            $guide = guides::getGuideByModuleName('pages');

            if(!count($_POST)){ return array('guide'=>$guide, 'fields'=>$guide->formatFields());}
            else{
                $lang = new languages();
                $lang->prepareData();
                if($lang->commit()){
                    self::customQuery("ALTER TABLE `pages_languages` ADD `".$lang->getValue('prefix')."` INT NOT NULL");
                    utils::redirect('/admin/languages/lists');
                }
            }
        }

        public static function edit($itemId = false){
            $lang = languages::getById($itemId);
            if($lang){
                $oldPrefix = $lang->getValue('prefix');
                $lang->prepareData();
                if($lang->commit()){
                    if($oldPrefix != $lang->getValue('prefix')){
                        self::customQuery("ALTER TABLE `pages_languages` CHANGE `".escapeString($oldPrefix)."` `".$lang->getValue('prefix')."` INT(11) NOT NULL;");

                        $sel = new selector('pages');
                        $sel->equals('lang', $oldPrefix);
                        $pages = $sel->run();
                        if($pages){
                            foreach($pages as $page){
                                $page->setValue('lang', $lang->getValue('prefix'));
                                $page->commit();
                            }
                        }
                    }

                    utils::redirect('/admin/languages/lists');
                }
            }
        }

        public static function view($objectId = false){
            $object = languages::getById($objectId);
            if($object){ return $object;}
            else{ return router::err404();}
        }

        private function prepareData(){
            foreach($_POST as $field=>$value){
                if($field == 'prefix'){
                    if(!preg_match("/^[a-zA-Z0-9\_]+$/", $value)){
                        return utils::returnSuccess(false,$value. ' - некорректное название поля. Оно должно содержать только латиницу, цифры и знак подчеркивания');
                    }
                    else{   $value = substr($value,0,2);}
                }
                if($value){ $this->setValue($field, $value);}
            }
        }
    }

    class pages_languages extends baseModule{

        public static function addLink($pageId, $langPrefix){
            $langLink = new pages_languages();
            $langLink->setValue($langPrefix, $pageId);
            $langLink->commit();
        }

        public static function addNewLangToLink($existingPageId, $existingLangPrefix, $newLangPageId, $newlangPrefix){
            $sel = new selector('pages_languages');
            $sel->equals($existingLangPrefix, $existingPageId);
            $sel->limit(1);
            $link = $sel->run();
            if($link){
                $link->setValue($newlangPrefix, $newLangPageId);
                $link->commit();
                return true;
            }
            else{
                return false;
            }
        }

        public static function getAllLinks($pageId, $selectedLang, $asObject = false){
            $sel = new selector('pages_languages');
            $sel->equals($selectedLang, $pageId);
            $sel->limit(1);
            $result = $sel->run();
            if($result){
                if($asObject){ return $result;}
                else{   return $result->reFormat();}
            }
            else{ return false;}
        }

        public function reFormat(){
            $langsArray = languages::lists(false);
            $resultArray = array();
            foreach(get_object_vars($this) as $langPrefix=>$langPageId){
                $ignore = ['id','_allFields','_objectExpires','_updatedValues'];
                if(in_array($langPrefix, $ignore)){ continue;}
                else{
                    $resultArray[] = array(
                        'lang'=>$langsArray[$langPrefix],
                        'prefix'=>$langPrefix,
                        'id'=>$langPageId
                    );
                }
            }
            return $resultArray;
        }
    }
?>