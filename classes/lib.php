<?php

class lib {
	

	public function __construct(){
		
	}

		public static function send_mail($txt, $to, $subject, $from, $from_addr, $cc = null, $bcc = null, $rel_path = null) {
		$srvr = self::get_path(false, $rel_path);
		$mime_type = (strpos($txt, '<html>') === false) ? "text/plain" : "text/html";

		$txt = preg_replace(
			array("/\"img\//",
				"/href=\"(?!(http|mailto))/",
				"/url\(/"
					),
			array("\"" . $srvr . "img/",
				"href=\"" . $srvr,
				"url(" . $srvr
					),
			$txt
			);

		$from_hdr = strpos($_SERVER["SERVER_SOFTWARE"], "IIS") ? $from_addr : self::filter_chars($from) . " <" . $from_addr . ">";
		$headers = "MIME-Version: 1.0\n"
		. "Content-type: " . $mime_type . "; charset: iso-8859-1\n"
		. "Content-Transfer-Encoding: 8bit\n"
		. "From: " . $from_hdr . "\n"
		. "Reply-To: " . $from_addr . "\n"
		. "Return-Path: " . $from_addr . "\n"
		. "X-Mailer: PHP/" . phpversion() . "\n";

		if ($cc)
			$headers .= "Cc: " . $cc . "\n";
		if ($bcc == null && defined("BCC_EMAILS_TO"))
			$bcc = BCC_EMAILS_TO;
		if ($bcc)
			$headers .= "Bcc: " . $bcc . "\n";

		return mail($to, self::filter_chars($subject), $txt, $headers . "\n", "-f" . $from_addr);
	}

// filter_chars
	public static function filter_chars($str) {
		return strtr($str,
			"\xe1\xc1\xe0\xc0\xe2\xc2\xe4\xc4\xe3\xc3\xe5\xc5" .
			"\xaa\xe7\xc7\xe9\xc9\xe8\xc8\xea\xca\xeb\xcb\xed" .
			"\xcd\xec\xcc\xee\xce\xef\xcf\xf1\xd1\xf3\xd3\xf2" .
			"\xd2\xf4\xd4\xf6\xd6\xf5\xd5\x8\xd8\xba\xf0\xfa\xda" .
			"\xf9\xd9\xfb\xdb\xfc\xdc\xfd\xdd\xff\xe6\xc6\xdf\xf8",
			"aAaAaAaAaAaAacCeEeEeEeEiIiIiIiInNoOoOoOoOoOoOoouUuUuUuUyYyaAso"
			);
	}

// get_path
	public static function get_path($use_https = false, $rel_path = null) {
		$url = dirname($_SERVER["SCRIPT_NAME"]);
		if ($rel_path){
			$url .= '/' . $rel_path;
		}			
		if ($url == "\\"){ // patch pour PHP:IIS
			$url = "/";
		}
		else if ($url != "/"){
			$url .= "/";
		}			
		return ($use_https ? "https" : "http") . "://" . $_SERVER["SERVER_NAME"] . $url;
	}


	public static function trim_array($value){
		if(is_array($value)){
			return $value;
		}
		else{
			return utf8_decode(trim($value));
		}
	}





}



?>
