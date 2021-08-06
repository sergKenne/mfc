<?php
    class pages_custom extends pages{

        public static function dataForMainPage(){
            $nSel = new selector('news');
            $nSel->isnotnull('id');
            $nSel->order('create_time', 'DESC');
            $nSel->limit(2);

            $oSel = new selector('notices');
            $oSel->isnotnull('id');
            $oSel->order('create_time', 'DESC');
            $oSel->limit(7);

            $sSel = new selector('stories');
            $sSel->isnotnull('id');
            $sSel->order('create_time', 'DESC');
            $sSel->limit(2);

            return ['news'=>$nSel->run(), 'notices'=>$oSel->run(), 'stories'=>$sSel->run()];
        }
    }