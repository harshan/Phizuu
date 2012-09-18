<?php

class link_controller{
    
    
    public static function getLinkTitleById($id) {
        $link = new Links();
        return $link->getLinkTitleByName($id);
    }
    
}
?>
