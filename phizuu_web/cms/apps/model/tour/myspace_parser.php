<?php
include_once('simple_html_dom.php');



   function getEvents($url) {

       $html = new simple_html_dom();
       $html->load_file($url);

       $events = array();

       foreach ($html->find('table[width=615]') as $table)
       {
            $details = array();

            $EvtTitle = $table->find('input[name=calEvtTitle]');
            $details["title"] = $EvtTitle[0]->value;

            $EvtLocation = $table->find('input[name=calEvtLocation]');
            $details["location"] = $EvtLocation[0]->value;

            $EvtCity = $table->find('input[name=calEvtCity]');
            $details["city"] = $EvtCity[0]->value;

            $EvtDateTime = $table->find('input[name=calEvtDateTime]');
            $details["datetime"] = $EvtDateTime[0]->value;
            
            $events[] = $details;
        }

       return $events;
   }
?>
