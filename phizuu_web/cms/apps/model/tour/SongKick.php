<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SongKick
 *
 * @author Dhanushka
 */


class SongKick {
    private $SK_API_KEY = "UtnCb7zWxPQn35A9";
    
    function SongKick() {

    }

    function getEventsArr($songKickArtistUserName) {
        $songKickArtistUserName = urlencode($songKickArtistUserName);
        $url = "http://api.songkick.com/api/3.0/artists/$songKickArtistUserName/calendar.json?apikey={$this->SK_API_KEY}";

        $jsonString = file_get_contents($url);

        $jsonObj = json_decode($jsonString);

        $events = $jsonObj->resultsPage->results->event;

        $eventArray = array();
        foreach ($events as $event) {
            $eventObj = new stdClass();

            

            $eventObj->title = $event->displayName;
            $eventObj->date = $event->start->date;

            $eventObj->location = $event->location->city;

            $eventArray[] = $eventObj;

            //echo $eventRow->plaintext . "<br>";
        }

        return $eventArray;
    }
}

/*$songKick = new SongKick();
$eventArr = $songKick->getEventsArr('282481-markus-schulz');
echo "<pre>";
print_r($eventArr);
echo "</pre>"*/
?>
