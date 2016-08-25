<?php
/**
 *	@time  2016-08-17
 *	@author  bear
 *
 **/
namespace spider\curl;
class Curl{
	private static $cookie = 'd_c0="AIAAobNKLgqPTuBZVYhWODaGi88FAooIEvo=|1467685282"; _za=22e11af3-d2a3-4316-8337-c52f73d34bdb; _zap=e0a3f372-df00-4fa3-89bc-73ed5cf0b3e4; _xsrf=9c2ca9168703ebf22045962adbda994e; q_c1=1d1f35dd43044e58b6fafc9ba511c755|1471405594000|1471405594000; l_cap_id="MDQ5MWUzZGUzMzllNDI1ZGI4YzExZjFkNTI3Y2IyODg=|1471405594|c36b57e33e50a99ed3210fb2c6560fc86341df2f"; cap_id="NGFkMTFlMGJlYTUyNDgyN2I0YWM5NmVkODQyMDA5MTE=|1471405594|76254fd43a839c21222137f357848b46fc6f7b3c"; login="Y2IxY2RjYWUwMzE4NDQwYmFkMzk1ZDFkZTIwY2YzODQ=|1471405601|93c9cae2082cf5b6b67c7d7b8bb045370fd09d91"; n_c=1; a_t="2.0ABDKPZrJvAgXAAAAYmvbVwAQyj2aybwIAIAAobNKLgoXAAAAYQJVTSFr21cAteEbVDZ___twHh_yWjaNCVtnVwsndtDwBOLw7vE30k0p_GyIk4iciQ=="; z_c0=Mi4wQUJES1Backp2QWdBZ0FDaHMwb3VDaGNBQUFCaEFsVk5JV3ZiVndDMTRSdFVObl9fLTNBZUhfSmFObzBKVzJkWEN3|1471405666|3d5c4846ca1621a2c5a4cf44bed1e53cc28669ec; __utma=51854390.53291223.1471405665.1471405665.1471405665.1; __utmb=51854390.2.10.1471405665; __utmc=51854390; __utmz=51854390.1471405665.1.1.utmcsr=(direct)|utmccn=(direct)|utmcmd=(none); __utmv=51854390.100--|2=registration_date=20150922=1^3=entry_date=20150922=1';
	
	/**
	 *	[request 执行一次curl请求]
	 *	@param  [string] $url        	[请求的URL]
	 *	@param	[string] $method		[请求的方式]
	 *	@param	[string] $data			[请求传的参数]
	 *
	 *	@return [stirng] $result     	[返回结果]
	 *
	 **/
	public static function request($url,$method="get",$data=null){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_COOKIE, self::$cookie);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.82 Safari/537.36');
		if($method == 'post'){
			curl_setopt($ch, CURLOPT_POST, true );
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		}
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,0);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}
	
	
	/**
	 *	[multirequestParam 执行多次curl请求(同url不同参数)]
	 *	@param  [string]  $url        	[请求的URL]
	 *	@param	[array] $data			[请求传的参数]
	 *
	 *	@return [array] $result     	[请求结果]
	 *
	 **/
	public static function multirequestParam($url,$data=null){
		$handles = $contents = array();
		$mh = curl_multi_init();
		foreach($data as $key => $value){
			$handles[$key] = curl_init($url);
			curl_setopt($handles[$key], CURLOPT_URL,$url);
			curl_setopt($handles[$key], CURLOPT_HEADER, 0);
			curl_setopt($handles[$key], CURLOPT_COOKIE, self::$cookie);
			curl_setopt($handles[$key], CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.82 Safari/537.36');
			curl_setopt($handles[$key], CURLOPT_POST, true );
			curl_setopt($handles[$key], CURLOPT_POSTFIELDS, $value);
			curl_setopt($handles[$key], CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($handles[$key], CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($handles[$key], CURLOPT_SSL_VERIFYPEER,0);
			curl_setopt($handles[$key], CURLOPT_SSL_VERIFYHOST,0);
			curl_setopt($handles[$key], CURLOPT_TIMEOUT, 10);
			curl_multi_add_handle($mh, $handles[$key]);
		}
		$active = null;
		do {
			$mrc = curl_multi_exec($mh, $active);
		} while ($mrc == CURLM_CALL_MULTI_PERFORM);
		 
		 
		while ($active and $mrc == CURLM_OK) {
			
			if(curl_multi_select($mh) === -1){
				usleep(100);
			}
			do {
				$mrc = curl_multi_exec($mh, $active);
			} while ($mrc == CURLM_CALL_MULTI_PERFORM);
		 
		}
		 
		//获取批处理内容
		foreach($handles as $i => $ch)
		{
			$content = curl_multi_getcontent($ch);
			$contents[$i] = curl_errno($ch) == 0 ? $content : '';
		}
		 
		//移除批处理句柄
		foreach($handles as $ch)
		{
			curl_multi_remove_handle($mh, $ch);
		}
		 
		//关闭批处理句柄
		curl_multi_close($mh);
		return $contents;
	}
	
	/**
	 *	[multirequestUrl 执行多次curl请求(不同url)]
	 *	@param  [array]  $url        	[请求的URL]
	 *
	 *	@return [array] $result     	[请求结果]
	 *
	 **/
	public static function multirequestUrl($url){
		$handles = $contents = array();
		$mh = curl_multi_init();
		foreach($url as $key => $value){
			$handles[$key] = curl_init($value);
			curl_setopt($handles[$key], CURLOPT_URL,$url);
			curl_setopt($handles[$key], CURLOPT_HEADER, 0);
			curl_setopt($handles[$key], CURLOPT_COOKIE, self::$cookie);
			curl_setopt($handles[$key], CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.82 Safari/537.36');
			curl_setopt($handles[$key], CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($handles[$key], CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($handles[$key], CURLOPT_SSL_VERIFYPEER,0);
			curl_setopt($handles[$key], CURLOPT_SSL_VERIFYHOST,0);
			curl_setopt($handles[$key], CURLOPT_TIMEOUT, 10);
			curl_multi_add_handle($mh, $handles[$key]);
		}
		$active = null;
		do {
			$mrc = curl_multi_exec($mh, $active);
		} while ($mrc == CURLM_CALL_MULTI_PERFORM);
		 
		 
		while ($active and $mrc == CURLM_OK) {
			
			if(curl_multi_select($mh) === -1){
				usleep(100);
			}
			do {
				$mrc = curl_multi_exec($mh, $active);
			} while ($mrc == CURLM_CALL_MULTI_PERFORM);
		 
		}
		 
		//获取批处理内容
		foreach($handles as $i => $ch)
		{
			$content = curl_multi_getcontent($ch);
			$contents[$i] = curl_errno($ch) == 0 ? $content : '';
		}
		 
		//移除批处理句柄
		foreach($handles as $ch)
		{
			curl_multi_remove_handle($mh, $ch);
		}
		 
		//关闭批处理句柄
		curl_multi_close($mh);
		return $contents;
	}
}




















