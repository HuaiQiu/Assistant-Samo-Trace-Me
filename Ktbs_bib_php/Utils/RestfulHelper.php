<?php

  /*##################################################################################################################################
    Title 							|| RestfullHelper class.
    Version 						|| 1.2
    Author 							|| Anh Hoang Lê (version 1.0), Derbel Fatma(version 1.1) and Chaieb Mohamed Yassine (version 1.2)
    Last modification date 			|| 05-09-2014
    Description 					|| This class is used to communicate with the KTBS ( Kernel for Trace Based Systems) using URL 
    								||	requests. 
    ##################################################################################################################################  
    History 						|| 

	
    */

class RestfulHelper {

    static public function get($url){
		
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_HTTPGET, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_USERPWD, "admin".":"."admin");		
		$reponse = curl_exec($curl);
		$infos = curl_getinfo($curl);
		curl_close($curl);
		$http_code = $infos["http_code"];
		//var_dump('error',curl_error($curl));
		//var_dump ('get',$reponse);var_dump('info',$infos);
		if($http_code == "200"|| $http_code == "303") return true; else return false;
	}
	static public function post($url,$content){
			
		$header = array("Content-type:text/turtle", "Expect:");
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_USERPWD, "admin".":"."admin");		
		curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
		$reponse = curl_exec($curl);
		$infos = curl_getinfo($curl);
        curl_close($curl);
		$http_code = $infos["http_code"];
		//var_dump ('post',$reponse);var_dump($infos);
		if($http_code == "201") return true; 
			else return $reponse;
	}	

	static public function json_post($url,$content){
			
		$header = array("Content-Type: application/json", "Expect:");
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_USERPWD, "admin".":"."admin");		
		curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
		$reponse = curl_exec($curl);
		$infos = curl_getinfo($curl);
        curl_close($curl);
		$http_code = $infos["http_code"];
		if($http_code == "201") return true; 
			else return $reponse;
	}

    static public function post_file_ktbs_by_curl($url,$filename){
				
		$file = file_get_contents($filename);
	    $header = array("Content-type:text/turtle");
	    $curl = curl_init($url);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);		
		curl_setopt($curl, CURLOPT_POSTFIELDS, $file);
		$reponse = curl_exec($curl);
		curl_close($curl);		
		
	}
		
    static public function Delete($url,$content){
			
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_USERPWD, "admin".":"."admin");		
		curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
		$reponse = curl_exec($curl);
		$infos = curl_getinfo($curl);
        curl_close($curl);
		$http_code = $infos["http_code"];
		if($http_code == "201") return true; 
			else return $reponse;
	}		
	
	static public function put($url,$content){
					
		$header = array("Content-type:text/turtle");
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_USERPWD, "admin".":"."admin");
		curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
		$reponse = curl_exec($curl);
		$infos = curl_getinfo($curl);
		curl_close($curl);
		$http_code = $infos["http_code"];
		if($http_code == "200") return true; else return false;
	}

	static public function json_getEtagAndPut($url,$content){

		$etag = RestfulHelper::getEtag($url,'json');
		$header = array("Content-Type:application/json",'If-match: '.$etag.'');
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_USERPWD, "admin".":"."admin");
		curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
		$reponse = curl_exec($curl);
		$infos = curl_getinfo($curl);
		curl_close($curl);
		$http_code = $infos["http_code"];
		if($http_code == "200") return true; else return false;
	}

    static public function getEtagAndPut($url,$content){
        
		$etag = RestfulHelper::getEtag($url,"ttl");
		$header = array("Content-type:text/turtle",'If-match: '.$etag.'');
		
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_USERPWD, "admin".":"."admin");
		curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
		$reponse = curl_exec($curl);
		$infos = curl_getinfo($curl);
		curl_close($curl);
		$http_code = $infos["http_code"];
		if($http_code == "200") return true;
			else return $reponse;
	}
	

	static public function getInfo($url){
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_HTTPGET, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_USERPWD, "admin".":"."admin");		
		$reponse = curl_exec($curl);
		$infos = curl_getinfo($curl);
	//	$reponse = curl_exec($curl);
		curl_close($curl);
		$http_code = $infos["http_code"];
		if($http_code == "200"|| $http_code == "303") return $reponse; else return null;
	}

	static public function httpInfo($url){
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_HTTPGET, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);	
		curl_setopt($curl, CURLOPT_USERPWD, "admin".":"."admin");	
		$reponse = curl_exec($curl);
		$infos = curl_getinfo($curl);
	//	$reponse = curl_exec($curl);
		curl_close($curl);
		$http_code = $infos["http_code"];
		return $http_code;
	}

	static public function getEtag($url,$param){

		if($param == "ttl"){ 
			$url = $url.'.ttl';
		}
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_HTTPGET, true);
		curl_setopt($curl, CURLOPT_HEADER, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_USERPWD, "admin".":"."admin");			
		$response = curl_exec($curl);
		$headers = curl_getinfo($curl);
		curl_close($curl);
		//echo $response;
		$pos = strpos($response,"etag:");
		if($pos<0) return false;
		$pos = $pos+strlen("etag:");
		$response1 = substr($response,$pos);
		$array = explode ('"',$response1);

		$etag ="W/"."\"".$array[1]."\"";

		if($param == 'ttl'){
			return $etag;
		}
		else{

			$etag = str_replace('ld+', '', $etag);
			return $etag;
		}
	}

	
}
?>
