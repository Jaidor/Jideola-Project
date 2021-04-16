<?php
class jideolaFunctions{
   
    public $dbx; /* Database connection */
	public $loggedIn = false; /* Login status of user */
	public $user = array(); /* Hold user info */
	public $ip = 0; /* User ip address */
	public $DB_HOST; /* Database host e.g localhost */
	public $DB_NAME; /* Database name */
	public $DB_USER; /* Database username */
	public $DB_PASS; /* Database password */
	public $DB_PREFIX; /* Database table prefix */
	public $DB_TYPE;
	public $debug = false;
	

   function __construct($param = array())
   {

    /* Db config */
		if(count($param)){
			$this->DB_HOST = $param['DB_HOST'];
            $this->DB_NAME = $param['DB_NAME'];
            $this->DB_USER = $param['DB_USER'];
            $this->DB_PASS = $param['DB_PASS'];
            $this->DB_PREFIX = $param['DB_PREFIX'];	
            $this->DB_TYPE = $param['DB_TYPE'];
		}

		$this->ip = $this->getIpAddress();


       /* Set the php time zone to africa lagos */
       date_default_timezone_set('Africa/Lagos');
       $siteip = 'http';
		if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {$siteip .= "s";}
		$siteip .= "://";
		if ($_SERVER["SERVER_PORT"] != "80" && $_SERVER["SERVER_PORT"] != "443") {
			$siteip .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].'/';
		} else {
			$siteip .= $_SERVER["SERVER_NAME"].'/';
		}
    }


    public function antiHacking($string, $length = null, $html = false, $striptags = true)
	{
		
		$length = 0 + $length;

		if(!$html) return ($length > 0) ? substr(addslashes(trim(preg_replace('/<[^>]*>/', '', $string))),0,$length) : addslashes(trim(preg_replace('/<[^>]*>/', '', $string)));
		$allow  = "<b><h1><h2><h3><h4><h5><h6><br><br /><hr><hr /><em><strong><a><ul><ol><li><dl><dt><dd><table><tr><th><td><blockquote><address><div><p><span><i><u><s><sup><sub><style><tbody>";
		$string = utf8_decode(trim($string)); /* Avoid unicode codec issues */
		if($striptags) $string = strip_tags($string, $allow);
		
		$aDisabledAttributes = array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavaible', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragdrop', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterupdate', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmoveout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
		$string = str_ireplace($aDisabledAttributes,'x',$string);
		
        /* Remove javascript from tagsv */
		while( preg_match("/<(.*)?javascript.*?\(.*?((?>[^()]+)|(?R)).*?\)?\)(.*)?>/i", $string))
		$string = preg_replace("/<(.*)?javascript.*?\(.*?((?>[^()]+)|(?R)).*?\)?\)(.*)?>/i", "<$1$3$4$5>", $string);
			
		/* Dump expressions from contibuted contentv */
		$string = preg_replace("/:expression\(.*?((?>[^(.*?)]+)|(?R)).*?\)\)/i", "", $string);

		while( preg_match("/<(.*)?:expr.*?\(.*?((?>[^()]+)|(?R)).*?\)?\)(.*)?>/i", $string))
		$string = preg_replace("/<(.*)?:expr.*?\(.*?((?>[^()]+)|(?R)).*?\)?\)(.*)?>/i", "<$1$3$4$5>", $string);
		
		/* Convert HTML characters */
		$string = str_replace("#", "#", htmlentities($string));
		$string = addslashes(str_replace("%", "%", $string));

		if($length > 0) $string = substr($string, 0, $length);
		return $string;
	}

    public function array2string ($multidimensional_array)
    {
        return base64_encode(serialize($multidimensional_array));
    }
    public function string2array ($encoded_serialized_string)
    {
        return unserialize(base64_decode($encoded_serialized_string));
    }

	/* Encrypt a password irreversibly */
	public function hashPass($pass)
    {
		return crypt($pass);
	}

   
	/* Check if a password is valid */
	function validatePassword($pass,$hash,$id = 0)
    {
		if (crypt($pass, $hash) == $hash) {
			return true;
		} else if(md5($pass) == $hash){
			if($id > 0 && is_numeric($id)){
				$query = "UPDATE #__users set password = '".$this->hashPass($pass)."' where id = $id";
				$this->dbquery($query);
			}
			return true;
		}
		return false;
	}

    function feedback($status,$code,$message=[],$response=[])
    {
        global $jideola;
        header('Content-Type: application/json');
        ob_end_clean();

        $responseArr = ['status' => $status, 'code' => $code, 'message' => $message, 'response' => $response];
        die(json_encode(['reqResponse' => $responseArr]));
    }

	public function log($err){
		echo $err.'<br />';
		file_put_contents(MVC.'logs/error_log.txt',date('[Y-m-d h:i:s]').' -> '.$err."\r\n", FILE_APPEND);
	}

	public function dberror (){
		$x = $this->dbx->error;
		 return empty($x) ? '<h1>No sql errors detected</h1>' : $x;
	}

	public function cleanSQL ($sql){
		return str_replace("#__",$this->DB_PREFIX,$sql);
	}


	/* Database connection and query functions	*/
	private function dbconnect($DB_NAME = "")
    {
		/* This function connects to mysqli and selects a database. */
		$DB_NAME = (empty($DB_NAME)) ? $this->DB_NAME : $DB_NAME;
		$x = new mysqli($this->DB_HOST, $this->DB_USER, $this->DB_PASS, $DB_NAME);  /* mysqli */
		/* check connection */
		if(mysqli_connect_error()) {
			die('Please refresh.' );
		} else $this->dbx = $x;
		$this->dbx->query("SET time_zone = 'TIMEZONE'");
	}

	public function dbrow ($sql=""){
		/* 	This function connects and queries a database. It returns a single row from db as a 1d array. */
		if(empty($sql)) return false;
		if(is_null($this->dbx)) $this->dbconnect(); /* Connect to database */
		$result = $this->dbx->query($this->cleanSQL($sql));
		$x = ($result) ? $result->fetch_assoc() : ''; /* Mysqli */
		if($this->debug !== false){
			$y = $this->dbx->error;
			if(!empty($y)) $this->log($y);
		}
		return $x;
	}

	public function dbarray ($sql=""){
		/* 	This function connects and queries a database. It returns all rows from d result as a 2d array. */
		if(empty($sql)) return false;
		if(is_null($this->dbx)) $this->dbconnect(); /* Connect to database */
		$result = $this->dbx->query($this->cleanSQL($sql));
		$arr = array();
		if($result){
			while($row = $result->fetch_assoc()){ $arr[]=$row; }; /* Mysqli */
		}
		if($this->debug !== false){
			$y = $this->dbx->error;
			if(!empty($y)) $this->log($y);
		}
		return $arr;
	}


	public function dbcountchanges ($sql=""){
		/* 	This function connects and queries a database. It returns the number of insert/updated/deleted/replace rows. */
		if(empty($sql)) return false;
		if(is_null($this->dbx)) $this->dbconnect(); /* Connect to database */
		$result = $this->dbx->query($this->cleanSQL($sql)); /* Mysqli */
		$x = $this->dbx->affected_rows; /* Mysqli */
		if($this->debug !== false){
			$y = $this->dbx->error;
			if(!empty($y)) $this->log($y);
		}
		return $x;
	}

	public function dbquery ($sql=""){
		/* 	This function connects and queries a database. It returns the query result identifier.	*/
		if(empty($sql)) return false;
		if(is_null($this->dbx)) $this->dbconnect(); /* Connect to database */
		return $this->dbx->query($this->cleanSQL($sql)); /* Mysqli */
	}

	public function passwordChecks($pwd) {

		$r1='/[A-Z]/';  /* Uppercase */
		$r2='/[a-z]/';  /* Lowercase */
		$r3='/[!@#$%^&*()\-_=+{};:,<.>]/';  /* Wwhatever you mean by 'special char' */
		$r4='/[0-9]/';  /* Numbers */
	
		if(preg_match_all($r1,$pwd, $o)<1) return false;
	    if(preg_match_all($r2,$pwd, $o)<1) return false;
	    if(preg_match_all($r3,$pwd, $o)<1) return false;
		if(preg_match_all($r4,$pwd, $o)<1) return false;
	
		return true;
	}

	public function unique(){
        return uniqid();
    }


	/* Function to get ip address of a client */

	private function getIpAddress() {
		$x = (empty($_SERVER['HTTP_CLIENT_IP'])?(empty($_SERVER['HTTP_X_FORWARDED_FOR'])? $_SERVER['REMOTE_ADDR']:$_SERVER['HTTP_X_FORWARDED_FOR']):$_SERVER['HTTP_CLIENT_IP']);
		$x = explode(',',$x);
		$q = count($x);
		return $x[$q-1]; 
	}

}
$jideola = new jideolaFunctions();
?>