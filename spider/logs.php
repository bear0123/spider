<?php
/**
 *	@time  2016-08-19
 *	@author  bear
 *
 **/
namespace spider\logs;
class Logs{
	public $fileName;
	public function __construct($dir){
		$fileName = $dir . "/log.txt";
		$this->fileName = $fileName;
		touch($fileName);                //如果文件不存在，则创建文件
	}
	
	/**
	 *	[echoLog				输出字符串到文件]
	 *	@param	[string] $str	[需要输出的字符串]
	 *	@param	[string] $name	[输出字符串的名字]
	 *
	 *	@return	[void]
	 */
	public function echoLog($str,$name=null){
		$str = $name." ".$str."\n";
		file_put_contents($this->fileName,$str,FILE_APPEND);
	}
	
	/**
	 *	[dumpLog				输出数组到文件]
	 *	@param	[array] $arr	[需要输出的数组]
	 *	@param	[string] $name	[输出数组的名字]
	 *
	 *	@return	[void]
	 */
	public function dumpLog($arr,$name=null){
		$str = $name." "."Array\n";
		foreach($arr as $value){
			$str .= " ".$value."\n";
		}
		file_put_contents($this->fileName,$str,FILE_APPEND);
	}
	
	/**
	 *	[dumpCountLog			输出数组长度到文件]
	 *	@param	[array] $arr	[需要计算长度的数组]
	 *	@param	[string] $name	[输出数组的名字]
	 *
	 *	@return	[void]
	 */
	public function dumpCountLog($arr,$name=null){
		$str = $name." "."Array"." ".count($arr)."\n";
		file_put_contents($this->fileName,$str,FILE_APPEND);
	}
}




















