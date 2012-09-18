<?php
/**
* IPInfoDB geolocation API class
* http://ipinfodb.com/ip_location_api.php
* Bug report : http://forum.ipinfodb.com/viewforum.php?f=7
* Updated January 19th 2010
* @version 1.0
* @author Marc-Andre Caron - IPInfoDB -  http://www.ipinfodb.com
* @license http://www.gnu.org/copyleft/lesser.html LGPL
*/
class geolocation
{
  //------------------------------------------------------------
  // PROPERTIES
  //------------------------------------------------------------

  //--Protected properties--//

  /**
  * The IP array
  * @var array
  * @access protected
  */
  protected $_ips = array();
  
  /**
  * The errors array
  * @var array
  * @access protected
  */
  protected $_errors = array();
  
  /**
  * The IP geolocation
  * @var array
  * @access protected
  */
  protected $_geolocation = array();
  
  /**
  * The IP geolocation
  * @var array
  * @access protected
  */
  protected $_cityPrecision;

  //--Constants--//
  
  const API_DOMAIN = 'ipinfodb.com';
  const API_BACKUP_DOMAIN = 'backup.ipinfodb.com';
  const IP_QUERY = 'ip_query.php';
  const IP_QUERY_COUNTRY = 'ip_query_country.php';
  const IP_QUERY2 = 'ip_query2.php';
  const IP_QUERY2_COUNTRY = 'ip_query2_country.php';
  
  const IP_ERROR = 'is an invalid IP address  (eg : 123.123.123.123)';
  const DOMAIN_ERROR = 'is an invalid domain name (eg : example.com)';
  const NONE_SPECIFIED = 'No IP or domain specified';
  const CONNECT_ERROR = 'Could not connect to API server. Will try backup server';
  const CONNECT_BACKUP_ERROR = 'Could not connect to backup API server.';


  //------------------------------------------------------------
  // METHODS
  //------------------------------------------------------------

  //--Public methods--//
  /**
  * Class constructor
  * Set if the query should get city or country precision
  * @param bool $cityPrecision True for city precision, false for country precision
  * @access public
  * @return	void
  */
  public function __construct($cityPrecision)
  {
    $this->_cityPrecision = (bool)$cityPrecision;
  }

	// --------------------------------------------------------------------

  /**
  * Set IP address
  * @param string $ip The ip address
  * @param bool $test To test if the IP is valid or not
  * @access public
  * @return	void
  */
  public function setIP($ip, $test = false)
  {
    //Test IP if required
    if ($test) {
      if (!preg_match('/^(?:25[0-5]|2[0-4]\d|1\d\d|[1-9]\d|\d)(?:[.](?:25[0-5]|2[0-4]\d|1\d\d|[1-9]\d|\d)){3}$/', $ip)) {
        $this->_setError(new Exception($ip . ' ' . self::IP_ERROR));
        return;
      }
    }
    
    $this->_ips[] = $ip;
  }

	// --------------------------------------------------------------------

  /**
  * Set domain
  * @param string $domain The domain name
  * @param bool $test To test if the domain is valid or not
  * @access public
  * @return	void
  */
  public function setDomain($domain, $test = false)
  {
    //Test domain if required
    if ($test) {
      if (!preg_match ("/^[a-z0-9][a-z0-9\-]+[a-z0-9](\.[a-z]{2,4})+$/i", $domain)) {
        $this->_setError(new Exception($domain . ' ' . self::DOMAIN_ERROR));
        return;
      }
    }
    
    $this->_ips[] = gethostbyname($domain);
  }                       

	// --------------------------------------------------------------------

  /**
  * Get geolocation as an array
  * @access public
  * @return	array
  */
  public function getGeoLocation() {
    //Make sure IPs and/or domains are specified
    if (empty($this->_ips)) {
      $this->_setError(new Exception(self::NONE_SPECIFIED));
      return array();
    }
    
    //Check if ip_query.php or ip_query2.php to be used
    $singleLookup = (empty($this->_domains) && (count($this->_ips) == 1)) ? true : false;
    
    switch ($singleLookup) {
    case true:
      //Use ip_query
      $this->_query();
      break;
      
    case false:
      //Use ip_query2 for domain or multiple lookups
      
      //Split IP array by 25
      $k = 0;
      $ipsSplit = array();
      for ($i=0; $i<count($this->_ips); $i++) {
        if (!(($i+1) % 25)) $k++;
        $ipsSplit[$k][] = $this->_ips[$i];
      }
      
      //Do multiple queries if required
      for ($i=0;$i<count($ipsSplit);$i++) {
        if (count($ipsSplit[$i])) {
          $this->_query2($ipsSplit[$i]);
        }
      }
    
      //Unset $ipsSplit 
      unset($ipsSplit);
      
      break;
    }
    
    return $this->_geolocation;
  }

	// --------------------------------------------------------------------

  /**
  * Get the errors
  * @access public
  * @return	void
  */
  public function getErrors() {
    return $this->_errors;
  }

	// --------------------------------------------------------------------

  //--protected methods--//

  /**
  * Single IP query
  * @access protected
  * @return	void
  */
  protected function _query() {
    //Select the proper API
    $api = $this->_cityPrecision ? self::IP_QUERY : self::IP_QUERY_COUNTRY;
    
    //Connect to IPInfoDB
    if (!($d = @file_get_contents("http://" . self::API_DOMAIN . "/$api?ip={$this->_ips[0]}"))) {
      $this->_setError(new Exception(self::CONNECT_ERROR));
      //Try backup server
      if (!($d = @file_get_contents("http://" . self::API_BACKUP_DOMAIN . "/$api?ip={$this->_ips[0]}"))) {
        $this->_setError(new Exception(self::CONNECT_BACKUP_ERROR));
        return;
      }
    }
    
   try {
      $answer = @new SimpleXMLElement($d);
    } catch(Exception $e) {
      $this->_setError($e);
      return;
    }
    
    foreach($answer as $field => $val) {
      $this->_geolocation[0][(string)$field] = (string)$val;
    }
  }

	// --------------------------------------------------------------------

  /**
  * Multiple IP query
  * @param array $ipsSplit The ips array (max 25)
  * @access protected
  * @return	void
  */
  protected function _query2($ipsSplit) {
    //Select the proper API
    $api = $this->_cityPrecision ? self::IP_QUERY2 : self::IP_QUERY2_COUNTRY;
    
    //Separate all IPs with a comma
    $ipsCs = implode(",", $ipsSplit);
    
    //Connect to IPInfoDB
    if (!($d = @file_get_contents("http://" . self::API_DOMAIN . "/$api?ip=$ipsCs"))) {
      $this->_setError(new Exception(self::CONNECT_ERROR));
      //Try backup server
      if (!($d = @file_get_contents("http://" . self::API_BACKUP_DOMAIN . "/$api?ip=$ipsCs"))) {
        $this->_setError(new Exception(self::CONNECT_BACKUP_ERROR));
        return;
      }
    }
    
    try {
      $answer = @new SimpleXMLElement($d);
    } catch(Exception $e) {
      $this->_setError($e);
      return;
    }
    
    //Add them to _geolocation
    foreach($answer->Location as $key => $ipData) {
      foreach($ipData as $field => $val) {
        $location[(string)$field] = (string)$val;
      }
      $this->_geolocation[] = $location;
      unset($location);
    }
  }

	// --------------------------------------------------------------------

  /**
  * Set error
  * @param string $msg The error message
  * @access protected
  * @return	void
  */
  protected function _setError($msg) {
    $exceptionMessage = "{$msg->getMessage()} in {$msg->getFile()}({$msg->getLine()})\n";
    $exceptionTrace = "Trace : {$msg->getTraceAsString()}";
    $this->_errors[] = $exceptionMessage . $exceptionTrace;
  }
}
