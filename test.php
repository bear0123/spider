<?php
set_time_limit(180);							//设置php程序超时时间
require_once "spider/autoLoad.php";            	//自动加载
use spider\curl;
use spider\user;
use spider\db;
use spider\logs;

$log = new logs\Logs(__DIR__);
$pdo = new db\Db("mysql","localhost","3306","zhihu","root","");


	$usernameArr = user\User::$usernameArr;
	$userArr = user\User::$userArr;
	$log->dumpCountLog($usernameArr,"usernameArr");
	$log->dumpCountLog($userArr,"userArr");
	$followArr = array();
	foreach($usernameArr as $value){
		$followeesUrl = "https://www.zhihu.com/people/".$value."/followees";
		$followeesResult = curl\Curl::request($followeesUrl);
		$userInfo = user\User::getUserInfo($followeesResult);
		//$pdo->query($userInfo);
		$followees = user\User::getFollow();
		
		$followersUrl = "https://www.zhihu.com/people/".$value."/followers";
		$followersResult = curl\Curl::request($followersUrl);
		user\User::getUserInfo($followersResult,"followers");
		$followers = user\User::getFollow("followers");
		
		$followArr = array_unique(array_merge($followArr,$followees,$followers));
		var_dump($followArr);
		//$log->dumpCountLog($followArr,"followArr");
	}
	user\User::setUser($followArr);









