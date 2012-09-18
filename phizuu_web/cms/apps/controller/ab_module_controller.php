<?php

require_once "../../../model/ab_module.php";

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ab_module_controller
 *
 * @author Harshan
 */
class ab_module_controller {
    
    public static function getAllModulesByUser($userId){
        $abModule = new ab_module();
        return $abModule->getAllModulesByUser($userId);
    }
    
}

?>
