<?php
/*require_once ('../../config/config.php');
require_once '../../database/Dao.php';
require_once '../../config/database.php';
require_once('../../controller/db_connect.php');
require_once '../../model/tour/MySpaceTourParser.php';
require_once '../../model/tour/simple_html_dom.php';
require_once '../../model/tours_model.php';
require_once '../../model/settings_model.php';
require_once ('../../common/SampleImage.php');
*/

class MySpaceTourParser {
    private $url;
    private $urlSchemes = array (
        array('/^(http\:\/\/collect.myspace.com\/index.cfm)/','parseToursCollectURL'),//Pattern, Function
        array('/^(http\:\/\/events.myspace.com)\/(\w+)\/Events/','parseToursEventsURL'),//http://www.myspace.com/erickmorillo/shows
        array('/^(http\:\/\/www.myspace.com)\/(\w+)\/shows/','parseToursShowsURL')
    );

    function __construct($url) {
        $this->url = $url;
    }

    function getTours() {
        foreach($this->urlSchemes as $urlScheme) {
            if (preg_match($urlScheme[0], $this->url)) {
                return call_user_func(array($this, $urlScheme[1]));
            }
        }

        throw new Exception('URL is invalid');
    }

    function parseToursCollectURL() {
        $eventContainTableWidth = 625;
        $eventTourRowTableWidth = 615;
        
        $html = @file_get_html($this->url);
        if($html==NULL) {
            throw new Exception('URL is invalid');
        }
        
        $eventContainingTable = $this->_checkData($html->find("table[width=$eventContainTableWidth] tbody tr td", 0));
        $eventTableArray = $this->_checkData($eventContainingTable->find("table[width=$eventTourRowTableWidth] tbody"));

        $eventArray = array();
        foreach ($eventTableArray as $eventRow) {
            $eventObj = new stdClass();

            $unformattedDate = $this->_checkData(trim($eventRow->find('tr',0)->find('td',0)->find('b',0)->innertext));
            $formatedDate = preg_replace("/[\s]+/", " ", $unformattedDate);

            $formatedDate = preg_replace("/(\w+), (\d+) (\d+) ([0-9:]*) ([A-Z]*)/", "$3 $1 $2", $formatedDate);
            $parts = explode(' ', $formatedDate);
            $formatedDate = $parts[0] . "-" . $this->_getMonthNumber($parts[1]) . "-" . $parts[2];
            $eventObj->date = $formatedDate;

            $eventObj->title = $this->_checkData($eventRow->find('tr',0)->find('td',0)->find('b',1)->innertext);

            $unformattedLocation = $this->_checkData($eventRow->find('tr',1)->find('td',0)->plaintext);
            $formattedLocation = trim(preg_replace("/[\s]+/", " ", $unformattedLocation));
            $formattedLocation = preg_replace("/^(,\s)/", "", $formattedLocation);
            $formattedLocation = preg_replace("/,\s\-$/", "", $formattedLocation);
            $eventObj->location = $formattedLocation;

            $eventArray[] = $eventObj;
            
            //echo $eventRow->plaintext . "<br>";
        }
        
        return $eventArray;
    }

    function parseToursEventsURL() {
        $html = @file_get_html($this->url);
        if($html==NULL) {
            throw new Exception('URL is invalid');
        }

        $eventContainingDiv = $this->_checkData($html->find("#home-rec-events", 0));
        $eventDivArray = $this->_checkData($eventContainingDiv->find(".eventitem"));
            
        $eventArray = array();
        foreach($eventDivArray as $eventDiv) {
            $eventObj = new stdClass();
            
            $eventObj->title = $this->_checkData($eventDiv->find('.event-info .event-titleinfo a span',0)->innertext);
            $eventObj->location = $this->_checkData($eventDiv->find('.event-info .event-titleinfo span span',0)->innertext);
            $date = $this->_checkData($eventDiv->find('.event-info .event-cal', 0)->innertext);

            if (($ticketFindLinkElem = $eventDiv->find('.ticketFindLink', 0)) != NULL) {
                $eventObj->ticketURL = $this->_checkData($ticketFindLinkElem->href);
            }
            
            if(preg_match('/(\w+), (\w+) (\d+)/', $date, $matches)) {
                $month = $this->_getMonthNumber($matches[2]);
                $recentYears = array(date('Y',strtotime('-1 year')),date('Y'),date('Y',strtotime('-1 year')));
                $dayExpected = $matches[1];
                foreach ($recentYears as $year) {
                    $date = $year . '-' . $month . '-' . $matches[3];
                    $day = date('D',strtotime($date));
                    if ($day==$dayExpected) {
                        break;
                    }
                }
            } elseif(preg_match('/^((\w+) @ )/', $date, $matches)) {
                if ($matches[2] == 'Today')
                    $date = date('Y-m-d');
                elseif ($matches[2] == 'Tomorrow')
                    $date = date('Y-m-d',strtotime('+1 day'));
                elseif ($matches[2] == 'Yesterday') {
                    $date = date('Y-m-d',strtotime('-1 day'));
                } else {
                    throw new Exception('Date Parsing Error');
                }
            } else {
                throw new Exception('Date Parsing Error');
            }

            $eventObj->date = $date;

            $eventArray[] = $eventObj;
        }


        return $eventArray;
    }
    
    function parseToursShowsURL() {
        $html = @file_get_html($this->url);
        if($html==NULL) {
            throw new Exception('URL is invalid');
        }

        $eventContainingUL = $this->_checkData($html->find(".moduleBody .eventsContainer", 0));
        $eventLIArray = $this->_checkData($eventContainingUL->find("li"));
            
        $eventArray = array();
        foreach($eventLIArray as $eventLI) {
            $eventObj = new stdClass();
            
            $eventObj->title = $this->_checkData($eventLI->find('.details h4 a',0)->innertext);
            $eventObj->location = $this->_checkData($eventLI->find('.details p',0)->innertext);

            $month = trim($this->_checkData($eventLI->find('.entryDate .month',0)->innertext));
            $day = trim($this->_checkData($eventLI->find('.entryDate .day',0)->innertext));
            $monthNo = $this->_getShortMonthNumber($month);

            $year = '';
            if ($monthNo < date('n')) {
                $year = date('Y',strtotime('+1 year'));
            } else {
                $year = date('Y');
            }

            $date = $year . '-' . $monthNo . '-' . $day;
            $eventObj->date = $date;
            $eventObj->ticketURL = ''; //No buy ticket URL available

            $eventArray[] = $eventObj;
        }
        
        return $eventArray;
    }
    

    private function _getMonthNumber($month_name) {
        $month_number = 0;
        for($i=1;$i<=12;$i++) {
            if(strtolower(date("F", mktime(0, 0, 0, $i, 1, 0))) == strtolower($month_name)) {
                $month_number = $i;
                break;
            }
        }
        return $month_number;
    }

    private function _getShortMonthNumber($month_name) {
        $month_number = 0;
        for($i=1;$i<=12;$i++) {
            if(strtolower(date("M", mktime(0, 0, 0, $i, 1, 0))) == strtolower($month_name)) {
                $month_number = $i;
                break;
            }
        }
        return $month_number;
    }

    private function _checkData($data) {
        if ($data==NULL) {
            throw new Exception('Parsing Error');
        } else {
            return $data;
        }
    }
}
/*
echo "<pre>";
$test = new MySpaceTourParser('http://collect.myspace.com/index.cfm?fuseaction=bandprofile.listAllShows&friendid=34433191&n=Roger+Sanchez');
print_r($test->getTours());
echo "</pre>";
 * 
 */
?>