<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AppConfig
 *
 * @author Dhanushka
 */
class CMSConfig {
    public static function saveConfig ($userId, $configName, $value) {
        $sql = "INSERT INTO cms_user_config VALUES ($userId, '$configName', '$value') ".
               "ON DUPLICATE KEY UPDATE value='$value'";

        $dao = new Dao();
        $dao->query($sql);
    }

    public static function getConfig ($userId, $configName) {
        $sql = "SELECT * FROM cms_user_config WHERE user_id = $userId AND `key` = '$configName'";
        $dao = new Dao();
        $array = $dao->toArray($sql);

        if (count($array) == 0) {
            return FALSE;
        } else {
            return $array[0]['value'];
        }
    }
}
?>
