<?php
set_time_limit(180);							//设置php程序超时时间
require_once "spider/autoLoad.php";            	//自动加载
use spider\curl;
use spider\user;
use spider\db;
use spider\logs;

$log = new logs\Logs(__DIR__);
$pdo = new db\Db("mysql","localhost","3306","zhihu","root","");


while(1){
	$usernameArr = user\User::$usernameArr;
	$userArr = user\User::$userArr;
	$log->dumpCountLog($usernameArr,"usernameArr");
	$log->dumpCountLog($userArr,"userArr");
	$followArr = array();
	foreach($usernameArr as $value){
		$followeesUrl = "https://www.zhihu.com/people/".$value."/followees";
		$followeesResult = curl\Curl::request($followeesUrl);
		$userInfo = user\User::getUserInfo($followeesResult);
		$pdo->query($userInfo);
		
		$followersUrl = "https://www.zhihu.com/people/".$value."/followers";
		$followersResult = curl\Curl::request($followersUrl);
		$followArr = user\User::getFollowers($followersResult);
		
		$log->dumpCountLog($followArr,"followArr");
	}
	user\User::setUser($followArr);
}


//$urlArr = user\User::start();
/*$url = "https://www.zhihu.com/people/".$urlArr[0]."/followees";
$result = curl\Curl::request($url);
$user = user\User::getUserInfo($result);
var_dump($user);
user\User::getFolloweesOrFollowers();
/*recursion($url,$userArr);
function recursion($url,$userArr){
	$log = new logs\Logs(__DIR__);
	$pdo = new db\Db("mysql","localhost","3306","zhihu","root","");
	$userArr[] = $url;
	//$log->dumpLog($userArr);
	$userInfo = getUser($url);
	$follow = getFollow($url);
	$pdo->query($userInfo);
	foreach($follow as $value){
		//$log->echoLog($value);
		if(!in_array($value,$userArr,true)){
			recursion($value,$userArr);
		}
	}
}*/



/*function getUser($username){
	$followeeUrl = "https://www.zhihu.com/people/".$username."/followees";
	$followerUrl = "https://www.zhihu.com/people/".$username."/followers";
	$followeeResult = curl\Curl::request($followeeUrl);
	$followerResult = curl\Curl::request($followerUrl);
	$followees = func\Func::getFolloweesOrFollowers($followeeResult);        	//关注了
	$followers = func\Func::getFolloweesOrFollowers($followerResult);			//关注者
	$follow = array_merge($followees,$followers);
	db\Db::setUser($follow);
	$userInfo = func\Func::getUserInfo($followeeResult);
	return $userInfo;
}*/











