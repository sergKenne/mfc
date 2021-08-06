<?php
/**
 * Created by PhpStorm.
 * User: Stardisk
 * Date: 02.12.20
 * Time: 1:02
 */

class cacher{

    public static function control(){
        $guidesFolder = SITE_ROOT.'/cache/guides';
        $result = array();
        if(is_dir($guidesFolder)){
            $guideCaches = scandir($guidesFolder);
            $guideCachesInfo = array(); $totalGuideCaches = array('space'=>0, 'count'=>0);
            foreach($guideCaches as $guideCache){
                if($guideCache == '.' or $guideCache == '..'){ continue;}
                else{
                    $stats = self::countFiles('/cache/guides/'.$guideCache.'/');
                    $tmp = array(
                        'name'=>$guideCache,
                        'space'=>$stats['space'],
                        'count'=>$stats['count']
                    );
                    $totalGuideCaches['space'] += $stats['space'];
                    $totalGuideCaches['count'] += $stats['count'];
                    $guideCachesInfo[] = $tmp;
                }
            }

            $selectorStats = self::countFiles('/cache/selectors/');
            $result['data'] = array(
                'selectors'=>array(
                    'space'=>$selectorStats['space'],
                    'count'=>$selectorStats['count']
                ),
                'guides'=>array(
                    'space'=>$totalGuideCaches['space'],
                    'count'=>$totalGuideCaches['count'],
                    'list'=>$guideCachesInfo
                )
            );
        }
        return $result;
    }

    private static function countFiles($path){
        $fi = new FilesystemIterator(SITE_ROOT.$path); $totalSize = 0;
        foreach($fi as $item){ $totalSize+=$item->getSize();}
        return array('count'=>iterator_count($fi), 'space'=>$totalSize);
    }

    public static function clearCache($what = false, $guideName = false){
        switch($what){
            case 'selectors':{
                foreach(scandir(SITE_ROOT.'/cache/selectors') as $file){
                    if($file == '.' or $file == '..'){ continue;}
                    else {unlink(SITE_ROOT.'/cache/selectors/'.$file);}
                } break;
            }
            case 'guides':{
                $guides = scandir(SITE_ROOT.'/cache/guides');
                foreach($guides as $guide){
                    foreach(scandir(SITE_ROOT.'/cache/guides/'.$guide) as $file){
                        if($file == '.' or $file == '..'){ continue;}
                        else {unlink(SITE_ROOT.'/cache/guides/'.$guide.'/'.$file);}
                    }
                    rmdir(SITE_ROOT.'/cache/guides/'.$guide);
                } break;
            }
            case 'guide':{
                foreach(scandir(SITE_ROOT.'/cache/guides/'.$guideName) as $file){
                    if($file == '.' or $file == '..'){ continue;}
                    else {unlink(SITE_ROOT.'/cache/guides/'.$guideName.'/'.$file);}
                }
                rmdir(SITE_ROOT.'/cache/guides/'.$guideName);
                break;
            }
        }
        header('Location: /admin/cacher/control'); exit;
    }
}