<?php
	function validatePresence($string){
		if (!isset($string) || empty($string)){
			$string = NULL;
			return(False);
				
		}
		else{
			$string = NULL;
			return(True);
		}
	}
	
	function stringLengthMax($string, $max){
		if(strlen($string) > $max){
			return(False);
		}
		else {
			return(True); 	
		}
	}
	function stringLengthMin($string, $min){
		if(strlen($string) < $min){
			return(False);
		}
		else {
			return(True); 	
		}
	}


?>