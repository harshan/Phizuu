<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of WorldMapAPI
 *
 * @author Dhanushka
 */
class WorldMapAPI {
    private function _getAppsWithModule() {
        $sql = "SELECT app_id FROM module WHERE world_map=1 GROUP BY app_id";

        $dao = new Dao();
        $appIds = $dao->toArray($sql);
        $rtn = array();
        foreach ($appIds as $appId) {
            $rtn[] = $appId['app_id'];
        }
        return $rtn;
    }

    public function generateApi() {
        $appIds = $this->_getAppsWithModule();

        $inClause = '(' . implode(',', $appIds) . ')';

        $sql = "SELECT app_id, country_code, COUNT(*) as visits FROM `an_connections` WHERE app_id IN $inClause AND DATEDIFF(CURDATE(), connect_date)<=30 GROUP BY `country_code`,app_id";

        $dao = new Dao();
        $arr = $dao->toArray($sql);

        $visits = array();
        foreach($arr as $row) {
            $newVisitObj = new stdClass();
            $newVisitObj->country_code = $row['country_code'];
            $newVisitObj->visits = $row['visits'];
            $visits[$row['app_id']][] = $newVisitObj;
        }

        foreach ($appIds as $appId) {
            if (isset ($visits[$appId])) {
                $jsonObj = array('counts'=>$visits[$appId]);
                $this->_writeApiToFile($appId, json_encode($jsonObj));
            } else {
                $jsonObj = array('counts'=>array());
                $this->_writeApiToFile($appId, json_encode($jsonObj));
            }
        }
    }

    private function _writeApiToFile($appId, $json) {
        $path = ROOT_PATH . "/../../static-api/$appId/";
        if(!is_dir($path)) {
            mkdir($path);
        }

        $path .= "world_map/";
        if(!is_dir($path)) {
            mkdir($path);
        }

        $path .= "visits/";
        if(!is_dir($path)) {
            mkdir($path);
        }

        $path .= "by_country/";
        if(!is_dir($path)) {
            mkdir($path);
        }

        file_put_contents($path."index.html", $json);
    }
}
?>
