<?php
define ('TOTAL_VISITS_FUNCTION', 1);
define ('UNIQ_VISITS_FUNCTION', 2);
define ('NEW_VISITS_FUNCTION', 3);

class GraphBuilder {
	public function createLineGraph($report, $function, $getArr) {
		$duration = $getArr['duration'];
                
		$dateProcessor = new DateProcessor();
		
		if ($duration == 'last_month') {
			$from = $dateProcessor->getFromDateByText($duration);
			$to = $dateProcessor->getToDateByText($duration);
		} else if ($duration != 'from_to') { 
			$from = $dateProcessor->getFromDateByText($duration);
			$to = date('Y-m-d'); // For now for all special texts this should be null. If you want change for specific texts
		} else {			
			$to = $getArr['to'];
			$from = $getArr['from'];
		}
		
		$dateProcessor = new DateProcessor();
		$datesArr = $dateProcessor->getDatesArrayInBetween($from, $to);
		
		$vistsArr = array();
		$nameArr = array();
		foreach ($datesArr as $date) {
			if ($function == TOTAL_VISITS_FUNCTION) {
				$visitsArr[] = $report->findNumTotalUUIDs(NULL,NULL,$date['date']);
			} elseif ($function == UNIQ_VISITS_FUNCTION) {
				$visitsArr[] = $report->findNumUniqUUIDs(NULL,NULL,$date['date']);
			} elseif ($function == NEW_VISITS_FUNCTION) {
				$visitsArr[] = $report->findNumNewUUIDs(NULL,NULL,$date['date']);
			}
			$nameArr[] = $date['name'];
		}
		
		$maxLables = 10;
		$g = new graph();
		
		if (count($nameArr)>$maxLables) {
			$items = count($nameArr);
			$steps = $items/$maxLables;
			
			$steps = ceil($steps);

			$g->set_x_axis_steps($steps);
			$g->set_x_label_style( 12, '#000000', 0, $steps, '#12495c' );
                        
		}
                $g->y_axis_colour('#000000','#12495c');
                $g->x_axis_colour('#000000','#12495c');

		$g->set_bg_colour("#FFFFFF");
		$g->line(2,'#08a6e0','Total Visits',12,5);
			
		if ($function == TOTAL_VISITS_FUNCTION) {
			$g->title( "Total Visits from $from to $to" , '{font-size: 16px; color: #04455a;}' );
			$g->set_tool_tip( '#x_label# <br>Number of total visits: #val#' );
		} elseif ($function == UNIQ_VISITS_FUNCTION) {
			$g->title( "Unique Visits from $from to $to" , '{font-size: 16px; color: #04455a;}' );
			$g->set_tool_tip( '#x_label# <br>Number of uniq visits: #val#' );
		} elseif ($function == NEW_VISITS_FUNCTION) {
			$g->title( "New visits from $from to $to" , '{font-size: 16px; color: #04455a;}' );
			$g->set_tool_tip( '#x_label# <br>Number of new visits: #val#' );
		}
		
		$g->set_data( $visitsArr );
		$g->set_x_labels( $nameArr );
		
		// set the Y max
		$max = max($visitsArr);
		
		$len = strlen($max);
		$top = pow(10,$len-1);
		$max = $max / $top;
		$max = ceil($max);
		$max = $max*$top;
		$g->set_y_max($max);
		if ($max > 1) {
			$g->y_label_steps( $max/$top );
			$g->set_y_max($max);
		} else {
			$g->y_label_steps( 2 );
			$g->set_y_max(2);
		}
		
		return $g->render();			
	}

        function createOSChart($report, $getArr) {
		$duration = $getArr['duration'];

		$dateProcessor = new DateProcessor();

		if ($duration == 'last_month') {
			$from = $dateProcessor->getFromDateByText($duration);
			$to = $dateProcessor->getToDateByText($duration);
		} else if ($duration != 'from_to') { $from = $dateProcessor->getFromDateByText($duration);
			$from = $dateProcessor->getFromDateByText($duration);
			$to = date('Y-m-d'); // For now for all special texts this should be null. If you want change for specific texts
                } else {
			$to = $getArr['to'];
			$from = $getArr['from'];
		}

		//$report = new Reports($appId);
               // echo $from, $to;
		$oses = $report->findOSUsagePercentage ($from, $to);

                $valueArr = array();
                $labelArr = array();
                foreach ($oses as $os) {
                    $labelArr[] = $os[0];
                    $valueArr[] = $os[1];
                }
                
                $g = new graph();

                $g->pie(90,'#505050','{font-size: 8px; color: #404040;');
                //
                // pass in two arrays, one of data, the other data labels
                //
                if(count($valueArr)<=0) {
                    $valueArr = array(0);
                    $labelArr = array('No Data');
                    $g->set_tool_tip( 'No Data' );
                } else {
                    $g->set_tool_tip( 'iPhone OS - #x_label#<br>Usage: #val#%' );
                }
                $g->pie_values( $valueArr, $labelArr);
                $g->set_bg_colour("#FFFFFF");
                //
                // Colours for each slice, in this case some of the colours
                // will be re-used (3 colurs for 5 slices means the last two
                // slices will have colours colour[0] and colour[1]):
                //
                $g->pie_slice_colours( array('#04455a','#16719a','#08a6e0') );

                $g->title("($from - $to)", '{font-size:12px; color: #04455a}' );
                return $g->render();
        }

        function createURLBarChart($report, $getArr) {
 		$duration = $getArr['duration'];

		$dateProcessor = new DateProcessor();

		if ($duration == 'last_month') {
			$from = $dateProcessor->getFromDateByText($duration);
			$to = $dateProcessor->getToDateByText($duration);
		} else if ($duration != 'from_to') { $from = $dateProcessor->getFromDateByText($duration);
			$from = $dateProcessor->getFromDateByText($duration);
			$to = date('Y-m-d'); // For now for all special texts this should be null. If you want change for specific texts
                } else {
			$to = $getArr['to'];
			$from = $getArr['from'];
		}
                
                //echo $from, $to;
		$uris = $report->findNumUniqURIAccess ($from, $to);

                $valueArr = array();
                $labelArr = array();
                foreach ($uris as $uri) {
                    $labelArr[] = $uri[0];
                    $valueArr[] = $uri[1];
                }
                //print_r( $valueArr);
                $g = new graph();
                $g->set_x_label_style(10, '#04455a', 2);

                $bar = new bar_3d( 90, '#04455a' );
                $bar->key( 'URL Visits', 10 );

                
                $g->set_bg_colour("#FFFFFF");

                $g->title("($from - $to)", '{font-size:12px; color: #04455a}' );
                
                


                $g->set_x_axis_3d( 12 );
                $g->x_axis_colour('#000000','#12495c');
                $g->y_axis_colour('#000000','#12495c');

                $g->set_x_labels($labelArr);
                if (count($valueArr)>0) {
                    $max = max($valueArr);

                    $len = strlen($max);
                    $top = pow(10,$len-1);
                    $max = $max / $top;
                    $max = ceil($max);
                    $max = $max*$top;
                    $g->set_y_max($max);
                    if ($max > 1) {
                            $g->y_label_steps( $max/$top );
                            $g->set_y_max($max);
                    } else {
                            $g->y_label_steps( 2 );
                            $g->set_y_max(2);
                    }

                    $bar->data = $valueArr;
                    $g->set_tool_tip( 'URL - #x_label#<br>Vists: #val#' );
                } else {
                    $g->set_y_max( 2 );
                    $g->y_label_steps( 2 );
                    $bar->data = array(0);
                    $g->set_tool_tip( 'No data' );
                }

                $g->data_sets[] = $bar;
                //$g->set_y_legend( 'Uniq URL Access', 12, '#736AFF' );

                

                return $g->render();
        }
}
?>