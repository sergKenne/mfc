<?php

class cache{
    //загрузить объект из кэша
    public static function loadObject($guideName, $objectId, $returnPath = false){
        global $cachedQueriesCount;
        if(config::get('options','cache')){
            if(!file_exists(SITE_ROOT.'/cache/guides/'.$guideName)){ mkdir(SITE_ROOT.'/cache/guides/'.$guideName, 0777, true);}
            $cacheFilePath = SITE_ROOT.'/cache/guides/'.$guideName.'/'.$objectId.'.data';

            if($returnPath){ return $cacheFilePath;}

            if(file_exists($cacheFilePath)){
                if($cachedObject = unserialize(file_get_contents($cacheFilePath))){
                    $object = new $guideName();
                    foreach(get_object_vars($cachedObject) as $prop=>$value){
                        $object->setValue($prop, $value);
                    }
                    $cachedQueriesCount['objects']++;
                    return $object;
                }
                else{ return false;}
            }
            else{   return false;}
        }
        else{ return false;}
    }

    //обновить кэш объекта
    public static function refreshObject($guideName, $objectId, $data){
        if(config::get('options','cache')){
            $path = self::loadObject($guideName, $objectId, true);
            $data = clone $data;
            unset($data->_allFields);
            unset($data->_guideFields);
            unset($data->guideId);
            file_put_contents($path, serialize($data));
        }
    }

    //загрузить кэш селектора
    public static function loadSelector($sqlHash, $module, $returnPath = false){
        global $cachedQueriesCount;
        if(config::get('options','cache')){
            if(!file_exists(SITE_ROOT.'/cache/selectors')){ mkdir(SITE_ROOT.'/cache/selectors', 0777, true);}
            $selectorCacheFile = SITE_ROOT.'/cache/selectors/'.$sqlHash.'.data';
            if($returnPath){ return $selectorCacheFile;}

            if(file_exists($selectorCacheFile)){
                //распаковка данных из кэша
                if($selectorCacheData = unserialize(file_get_contents($selectorCacheFile)) and is_object($module)){
                    //если в кэше массив и сам кэш не протух
                    if(is_array($selectorCacheData) and $selectorCacheData['expires'] > time()){
                        //если модуль не в исключенных системных модулях, то добавить к нему служебные элементы, вырезаемые при сохранении кэша для экономии места на диске
                        if(!in_array($module->getObjectClassName(), EXCLUDED_MODULES)){
                            for($i = 0; $i < count($selectorCacheData['data']); $i++){
                                $selectorCacheData['data']->_allFields = $module->_allFields;
                                $selectorCacheData['data']->_guideFields = $module->_guideFields;
                                $selectorCacheData['data']->guideId = $module->guideId;
                            }
                        }
                        $cachedQueriesCount['selectors']++;
                        return $selectorCacheData;
                    }
                    //если в кэш объект (запрос селектора с LIMIT 1) и кэш не протух
                    else if(is_object($selectorCacheData) and $selectorCacheData->_objectExpires > time()){
                        if(isset($selectorCacheData->_isEmpty)){ $selectorCacheData = 'CACHE_EMPTY_RESULT';}
                        else{
                            if(!in_array($module->getObjectClassName(), EXCLUDED_MODULES)){
                                $selectorCacheData->_allFields = $module->_allFields;
                                $selectorCacheData->_guideFields = $module->_guideFields;
                                $selectorCacheData->guideId = $module->guideId;
                            }
                        }
                        $cachedQueriesCount['selectors']++;
                        return $selectorCacheData;
                    }
                    else{ return false;}
                }
                else{ return false;}
            }
            else{ return false;}
        }
        else{ return false;}
    }

    //обновить кэш селектора
    public static function refreshSelector($sqlHash, $data){
        if(config::get('options','cache')){
            $path = self::loadSelector($sqlHash, false, true);
            if(is_array($data)){
                foreach($data['data'] as $i=>$object){
                    unset($data['data'][$i]->_allFields);
                    unset($data['data'][$i]->_guideFields);
                    unset($data['data'][$i]->guideId);
                }
                file_put_contents($path, serialize($data));
            }
            elseif(is_object($data)){
                unset($data->_allFields);
                unset($data->_guideFields);
                unset($data->guideId);
                file_put_contents($path, serialize($data));
            }
        }
    }
}