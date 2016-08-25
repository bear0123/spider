<?php
/**
 *	@time  2016-08-17
 *	@author  bear
 *
 **/
namespace spider\autoLoad;
class AutoLoad{
	public static function load($fileName){
		$fileName = str_replace("\\","/",$fileName);
		$num = strrpos($fileName,"/");
		$fileName = substr($fileName,0,$num);
		$fileName = sprintf("%s.php",$fileName);
		if(is_file($fileName)){
			require_once $fileName;
		}
	}
}

spl_autoload_register(array("spider\autoLoad\AutoLoad","load"));













