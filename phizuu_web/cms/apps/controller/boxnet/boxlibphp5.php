<?php

/**
* Box REST Client Library for PHP5 Developers
* 
* 
* @author James Levy <james@box.net>
* @link http://enabled.box.net
* @access public
* @version 1.0
* copyright Box.net 2007 
* Available for use and distribution under GPL-license
* Go to http://www.gnu.org/licenses/gpl-3.0.txt for full text
*/


include 'class.curl.php';
     
class boxclient {
    
    
  public function __construct($api_key, $auth_token) {
     
     $this->api_key    = $api_key;
     $this->auth_token     = $auth_token;
    
}
    // Toggle Debug Mode 
    
    var $_debug = false;

	// Setup variables

    var $_box_api_url = 'http://www.box.net/api/1.0/rest';
    
    var $_box_api_upload_url = 'http://upload.box.net/api/1.0/upload';
	var $_box_api_auth_url = 'https://www.box.net/api/1.0/rest';
	

    var $_error_code = '';
 
    var $_error_msg = '';
    

    
    

        
    // Setup for Functions
    
    function makeRequest($method, $params = array()) {
        $this->_clearErrors();
        

        $useCURL        = in_array('curl', get_loaded_extensions());

        if ($method == 'upload'){
            $args = array();
	
		$request = $this->_box_api_upload_url.'/'.
                    $this->auth_token.'/'.$params['folder_id'];

        if ($this->_debug){ echo "Upload Request: ".$request; }
        
    }
	else if($method == 'authorization'){
	$args = array();
        foreach($params as $k => $v){
            array_push($args, urlencode($k).'='.urlencode($v));
            $query_str = implode('&', $args);
        }

    $request            = $this->_box_api_auth_url .'?'. $method . '&' . $query_str;

	    if ($this->_debug)
		{ 
		echo "Request: ".$request; 
		}
	}
	else{
            
        $args = array();
        foreach($params as $k => $v){
            array_push($args, urlencode($k).'='.urlencode($v));
            $query_str = implode('&', $args);
        }

   $request            = $this->_box_api_url .'?'. $method . '&' . $query_str;

	    if ($this->_debug)
		{ 
		echo "Request: ".$request; 
		}
    }
        
        
        
        if ($useCURL) {
		if($method == 'upload'){

		$fname=$_FILES['new_file1']['tmp_name'];
		$c = curl_init();
		curl_setopt($c, CURLOPT_URL, $request);
		curl_setopt($c, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($c, CURLOPT_POST, true);
		curl_setopt($c, CURLOPT_POSTFIELDS, array('new_file1'=>"@$fname"));
		curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);

		$xml = curl_exec ($c);
		curl_close ($c); 
		}
		else{
            $c = &new curl($request );
            $c->setopt(CURLOPT_FOLLOWLOCATION, true);
            $xml = $c->exec();
            $error = $c->hasError();
            if ($error) {
                $this->_error_msg = $error;
                return false;
            }
            $c->close() ;
			}
        } else {
            $url_parsed = parse_url($request);
            $host       = $url_parsed["host"];
            $port       = ($url_parsed['port'] == 0) ? 80 : $url_parsed['port'];
            $path       = $url_parsed["path"] . (($url_parsed['query'] != '') ? $path .= "?{$url_parsed[query]}" : '');
           // $headers    = "GET $path HTTP/1.0\r\n";
		   $headers    = "POST $path HTTP/1.0\r\n";
            $headers    .= "Host: $host\r\n\r\n";
            $fp         = fsockopen($host, $port, $errno, $errstr, 30);
            if (!$fp) {
                $this->_error_msg   = $errstr;
                $this->_error_code  = $errno;
                return false;
            } else {
                fwrite($fp, $headers);
                while (!feof($fp)) {
                    $xml .= fgets($fp, 1024);
                }
                fclose($fp);
                
            
                $xml_start = strpos($xml, '<?xml');
                $xml = substr($xml, $xml_start, strlen($xml));
            }
        }
        
        if ($this->_debug) {
            echo '<h2>XML Response</h2>';
            echo '<pre class="xml">';
            echo htmlspecialchars($xml);
            echo '</pre>';
        }
        
        $xml_parser = xml_parser_create();
        xml_parse_into_struct($xml_parser, $xml, $data);
        xml_parser_free($xml_parser);
        
        return $data;
    }
    //
    
    
    //////// Functions 

    
    // Get Ticket
    
    function getTicket($params = array()) {
        $params['api_key']  = $this->api_key;
        $ret_array = array();
        
    
        $data = $this->makeRequest('action=get_ticket', $params);
        
        
        
    
        if ($this->_checkForError($data)) {
            return false;
        }
    
        
        foreach ($data as $a) {

                        
            switch ($a['tag']) {
            
                        
                            case 'STATUS':
                
                        
                        $ret_array['status'] = $a['value'];
                        
                        break;
                        
                            case 'TICKET':
                
                        $ret_array['ticket'] = $a['value'];
                        
            
                    break;
                    
                
                        
                    }
                
            
            }
            
        if ($this->_debug) {
            echo '<h2>Ticket Return</h2>';
            $this->_a($ret_array);
            print_r ($a);
            
            echo '<hr />';
        }   
        
        return $ret_array;
        
        
            
        
    }
    
    
    
    // Get Auth Token

    
        function getAuthToken($ticket) {
      	//https://www.box.net/api/1.0/rest?action=authorization&api_key=your_api_key&login=user@email.com&password=box_password&method   

		
        $params['api_key']  = $this->api_key;
        $params['ticket']   = $ticket;

        $ret_array = array();
        
       $data = $this->makeRequest('action=get_auth_token', $params);

        if ($this->_checkForError($data)) {
            return false;
        }
        
        
        foreach ($data as $a) {
            switch ($a['tag']) {
            case 'STATUS':      
            $ret_array['status'] = $a['value'];
            break;  
            case 'AUTH_TOKEN':
            $ret_array['auth_token'] = $a['value'];
            break;  
                    }}
                    
        if ($ret_array['status'] == 'get_auth_token_ok'){
            $auth_token = $ret_array['auth_token'];
            global $auth_token;
        }else{

		$params['api_key']  = $this->api_key;
		$params['ticket']   = $ticket;
		

        $params['login']   = $_SESSION['box_user'];
		$params['password']   = $_SESSION['box_pwd'];
		

		$params['method']   = '';

        
        $ret_array = array();
        
        
       $data = $this->makeRequest('action=authorization', $params);
		//https:www.box.net/api/1.0/rest?action=authorization&api_key=your_api_key&login=user@email.com&password=box_password&method
		
		if ($this->_checkForError($data)) {
            return false;
        }
        
        
       foreach ($data as $a) {
            switch ($a['tag']) {
            case 'STATUS':      
            $ret_array['status'] = $a['value'];
            break;  
            case 'AUTH_TOKEN':
            $ret_array['auth_token'] = $a['value'];
		  $_SESSION['auth_token']=$ret_array['auth_token'];
            break;  
                    }
					
					}
				//	print_r($data);
		
	   }   
    }
    
    // Retrieve Account Tree (http://enabled.box.net/docs/rest#get_account_tree)
    
        function getAccountTree($params = array()) {

          $params['api_key']  = $this->api_key;
		
          $params['auth_token']   = $_SESSION['auth_token'];
	 		
			//echo     $params['auth_token']   = $this->auth_token;
			// have to specify the forder that I want to display
         	//echo $params['folder_id']    = 0;
		    //$params['folder_id']    = '33314413';
			
		  $params['folder_id']    = '';
		  
		 
        //echo  $params['params[]']     = "['onelevel','nofiles','nozip']";
		  $params['params[]']     = 'nozip';
		
        $ret_array = array();
        $data = $this->makeRequest('action=get_account_tree', $params);
        if ($this->_checkForError($data)) {
            return false;
        }
         $tree_count=count($data);
        global $tree_count;
        
for ($i=0, $tree_count=count($data); $i<$tree_count; $i++) {
            $a = $data[$i];

            switch ($a['tag']) 
            {
                case 'FOLDER':
                    if (is_array($a['attributes'])) {
                     echo   $ret_array['folder_id'][$i] = $a['attributes']['ID'];
                      echo   $ret_array['folder_name'][$i] = $a['attributes']['NAME'];
                       echo  $ret_array['shared'][$i] = $a['attributes']['SHARED'];
                    }
                    break;
                    
                case 'FILE':
                    if (is_array($a['attributes'])) {
                      echo   $ret_array['file_id'][$i] = $a['attributes']['ID'];
                      echo   $ret_array['file_name'][$i] = $a['attributes']['FILE_NAME'];
                      echo   $ret_array['file_keyword'][$i] = $a['attributes']['KEYWORD'];
                        $entry_count++;
                    }
                    break;
            }}
            if ($this->_debug) {
            echo '<h2>Account Tree Return</h2>';
            $this->_a($ret_array);
            "<br/>";
            print_r ($a);
            echo '<hr />';
        }
		//print_r($ret_array);
		return $ret_array;
		}
	

	
	// Create New Folder
	
	function CreateFolder($new_folder_name, $params = array()) {
	
		$params['api_key']  	= $this->api_key;
		$params['auth_token'] 	=  $this->auth_token;
		$params['parent_id'] 	= 0; //Set to '0' by default. Change to create within sub-folder.
		$params['name'] 	    = $new_folder_name;
	    $params['share'] 	    = 1; //Set to '1' by default. Set to '0' to make folder private.
		
		$ret_array = array();
		
		
	
		$data = $this->makeRequest('action=create_folder', $params);
		

		if ($this->_checkForError($data)) {
			return false;
		}
		
		
		foreach ($data as $a) {
			
		
			
			switch ($a['tag']) {
			
						
							case 'FOLDER_ID':
				
						
						$ret_array['folder_id'] = $a['value'];
						
						break;
						
							case 'FOLDER_NAME':
				
						$ret_array['folder_type'] = $a['value'];
						
			
					   break;
					
					       case 'SHARED':
				
					    $ret_array['shared'] = $a['value'];
					    
					    break;
					    
					    case 'PASSWORD':
				
					    $ret_array['password'] = $a['value'];
						
			
					break;
					
				
						
					}
			
			
		
		
		}
		
			if ($this->_debug) {
			echo '<h2>Account Tree Return</h2>';
			$this->_a($ret_array);
			"<br/>";
			print_r ($a);
			
			echo '<hr />';
		}
	
		
		return $ret_array;
		
		
		
	}
	
	
	
	
	// Upload File 
	


	//... This function not using now...
	function UploadFile ($params = array()) {

		$params['auth_token'] = $this->auth_token;
        // this param should be the full path of the file
        $params['new_file1']  = $_FILES['new_file1'];
        unset($params['file']);
        /*$defaults = array(
            'folder_id' => 0, //Set to '0' by default. Change to create within sub-folder.
            'share'     => 1, //Set to '1' by default. Set to '0' to make folder private.
        );*/
		//34183072
		//33314413
		$defaults = array(
            'folder_id' => 34183072, //Set to '0' by default. Change to create within sub-folder.
            'share'     => 1, //Set to '1' by default. Set to '0' to make folder private.
        );
		
        foreach ($defaults as $key => $value) {
            if (!array_key_exists($key, $params)) {
                $params[$key] = $value;
            }
        }
        $ret_array = array();
        $entry_count = 0;
		
	
		//$data = $this->makeUploadRequst($params);
		$data = $this->makeRequest('upload',$params);
		

		if ($this->_checkForError($data)) {
			return false;
		}
		
		
		for ($i=0, $tree_count=count($data); $i<$tree_count; $i++) {
			$a = $data[$i]; {
				
			


			
			switch ($a['tag']) {
			
						
							case 'STATUS':
				
						
						$ret_array['status'] = $a['value'];
						
						break;
						
					case 'FILE':
					if (is_array($a['attributes'])) {
						$ret_array['file_name'][$i] = $a['attributes']['FILE_NAME'];
						$ret_array['id'][$i] = $a['attributes']['ID'];
						$ret_array['folder_name'][$i] = $a['attributes']['FOLDER_NAME'];
							$ret_array['error'][$i] = $a['attributes']['ERROR'];
							$ret_array['public_name'][$i] = $a['attributes']['PUBLIC_NAME'];
						$entry_count++;
					}
					break;
					
				
						
					}
			
			
		
		
		}
	}
		
			if ($this->_debug) {
			echo '<h2>Account Tree Return</h2>';
			$this->_a($ret_array);
			"<br/>";
			print_r ($a);
			
			echo '<hr />';
		}
	
		
		return $ret_array;
		
		
		
	}
	
	
	// Set Description of File or Folder
	
	
	
	
	// Register New User
	
	function RegisterUser($params = array()) {
	
		$params['api_key'] 	= $this->api_key;
		$params['login']  = $_REQUEST['login'];
		$params['password']  = $_REQUEST['password'];
		


		$ret_array = array();
		
	
		$data = $this->makeRequest('action=register_new_user', $params);
		
		
		
	
		if ($this->_checkForError($data)) {
			return false;
		}
	
		
		foreach ($data as $a) {
			
			switch ($a['tag']) {
						
							case 'STATUS':
				
						
						$ret_array['status'] = $a['value'];
						
						break;
						
							case 'AUTH_TOKEN':
				
						$ret_array['auth_token'] = $a['value'];
						
			
					break;
					
					case 'LOGIN':
				
						$ret_array['login'] = $a['value'];
						
			
					break;
					
					
					
					case 'SPACE_AMOUNT':
				
						$ret_array['space_amount'] = $a['value'];
						
			
					break;
					
				case 'SPACE_USED':
				
						$ret_array['space_used'] = $a['value'];
						
			
					break;
						
					}
				
			
			}
			
		if ($this->_debug) {
			echo '<h2>Registration Return</h2>';
			$this->_a($ret_array);
			print_r ($a);
			
			echo '<hr />';
		}	
		
		return $ret_array;
		
		
			
		
	}
	
		   // Add Tags  (http://enabled.box.net/docs/rest#add_to_tag)
     
       function AddTag($tag, $id, $target_type, $params = array()) {
   
          $params['api_key']  = $this->api_key;
        $params['auth_token']   = $this->auth_token;
        $params['target']    = $target_type; // File or folder
         $params['target_id']    = $id; // Set to ID of file or folder
        $params['tags[]']     = $tag;
        $ret_array = array();
        $data = $this->makeRequest('action=add_to_tag', $params);
        
        
        
        
    
        if ($this->_checkForError($data)) {
            return false;
        }
    
        
        foreach ($data as $a) {
            
            switch ($a['tag']) {
                        
                            case 'STATUS':
                
                        
                        $ret_array['status'] = $a['value'];
                        
                        break;
                        
                    
                
            
            }}
            
        if ($this->_debug) {
            echo '<h2>Tag Return</h2>';
            $this->_a($ret_array);
            print_r ($a);
            
            echo '<hr />';
        }   
        
        return $ret_array;
        
    
    }
    
     // Public Share  (http://enabled.box.net/docs/rest#public_share)
     
       function PublicShare($message, $emails, $id, $target_type, $password, $params = array()) {

          $params['api_key']  = $this->api_key;
        $params['auth_token']   = $this->auth_token;
        $params['target']    = $target_type; // File or folder
         $params['target_id']    = $id; // Set to ID of file or folder
          $params['password']    =  $password; //optional
           $params['message']    = $message;
            $params['emails']    = $emails;
        $ret_array = array();
        $data = $this->makeRequest('action=public_share', $params);
        
        
        
        
    
        if ($this->_checkForError($data)) {
            return false;
        }
    
        
        foreach ($data as $a) {
            
            switch ($a['tag']) {
                        
                            case 'STATUS':
                
                        
                        $ret_array['status'] = $a['value'];
                        
                        break;
                        
                        case 'PUBLIC_NAME':
                
                        
                        $ret_array['public_name'] = $a['value'];
                        
                        break;
                        
                        
                    
                        
                    
                
            
            }}
            
        if ($this->_debug) {
            echo '<h2>Public Share Return</h2>';
            $this->_a($ret_array);
            print_r ($a);
            
            echo '<hr />';
        }   
        
        return $ret_array;
        
    
    }
    
    
    // Get Friends  (http://enabled.box.net/docs/rest#get_friends)
     
       function GetFriends ($params = array()) {
    
          $params['api_key']  = $this->api_key;
        $params['auth_token']   = $this->auth_token;
        $params['params[]']    = 'nozip';
        $ret_array = array();
        $data = $this->makeRequest('action=get_friends', $params);
        
        
        
        
    
        if ($this->_checkForError($data)) {
            return false;
        }
    
        
        foreach ($data as $a) {
            
            switch ($a['tag']) {
	            
                        
                            case 'NAME':
                
                        
                        $ret_array['name'] = $a['value'];
                        
                        break;
                        
                        case 'EMAIL':
                
                        
                        $ret_array['email'] = $a['value'];
                        
                        break;
                        
                        case 'ACCEPTED':
                
                        
                        $ret_array['accepted'] = $a['value'];
                        
                        break;
                        
                        case 'AVATAR_URL':
                
                        
                        $ret_array['avatar_url'] = $a['value'];
                        
                        break;
                          
                        case 'ID':
                
                        
                        $ret_array['id'] = $a['value'];
                        
                        break;
                          
                        case 'URL':
                
                        
                        $ret_array['url'] = $a['value'];
                        
                        break;
                          
                        case 'STATUS':
                
                        
                        $ret_array['status'] = $a['value'];
                        
                        break;
                        
                    
                
            
            }}
            
        if ($this->_debug) {
            echo '<h2>Get Friend Return</h2>';
            $this->_a($ret_array);
            print_r ($a);
            
            echo '<hr />';
        }   
        
        return $ret_array;
        
    
    }
    
	
		// Logout User
	
	function Logout($params = array()) {
	
		$params['api_key'] 	= $this->api_key;
		$params['auth_token']  = $this->auth_token;
		


		$ret_array = array();
		
	
		$data = $this->makeRequest('action=logout', $params);
		
		
		
	
		if ($this->_checkForError($data)) {
			return false;
		}
	
		
		foreach ($data as $a) {
			
			switch ($a['tag']) {
						
							case 'ACTION':
				
						
						$ret_array['logout'] = $a['value'];
						
						break;
						
					
				
			
			}
			
		if ($this->_debug) {
			echo '<h2>Logout Return</h2>';
			$this->_a($ret_array);
			print_r ($a);
			
			echo '<hr />';
		}	
		
		return $ret_array;
		
		
		
	}
	

    
}
	
	

		
	
	/*
	/
	/  Debugging & Error Codes
	/
   */
	
   
	function _checkForError($data) {
	
		if (isset($data[0]['attributes']) && $data[0]['attributes']['STAT'] == 'fail') {
			$this->_error_code = $data[1]['attributes']['CODE'];
			$this->_error_msg = $data[1]['attributes']['MSG'];
			return true;
		}
		return false;
	}
	

	function isError() {
	
		if  ($this->_error_msg != '') {
			return true;
		}
		return false;
	}
	

	function getErrorMsg() {
	
		return '<p>Error: (' . $this->_error_code . ') ' . $this->_error_msg . '</p>';
	}

	function getErrorCode() {
	
		return $this->_error_code;
	}
	
	
	function _clearErrors() {
	
		$this->_error_code = '';
		$this->_error_msg = '';
	}
	
	
	function setDebug($debug) {
	
		$this->_debug = $debug;
	}
	
	function _a($array) {
	
		echo '<pre>';
		print_r($array);
		echo '</pre>';
	}
	
	
//..............get file list new.............

function getFileList($tree,$tree_count){

for ($i=0, $tree_count; $i<$tree_count; $i++) {
  $folder_id=$tree['folder_id'][$i];
	if($folder_id !=''){
	$folder_id1=$folder_id;
	}
if ($tree['file_name'][$i] != ''){
$file[]=array ('file_id' => $tree['file_id'][$i],'file_name' => $tree['file_name'][$i],'file_keyword' => $tree['file_keyword'][$i],'folder_id' => $tree['folder_id'][$i],'folder_name' => $tree['folder_name'][$i],'shared' => $tree['shared'][$i]);
}
}
return $file;
}


//..............end get file list new.........
	
	
}

?>