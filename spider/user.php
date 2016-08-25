<?php
/**
 *	@time  2016-08-17
 *	@author  bear
 *
 **/
namespace spider\user;
use spider\curl;
use spider\db;
use spider\logs;

class User{
	
	public static $userArr = array("xiong-lu-lu-72");							//已经抓取的用户
	public static $usernameArr = array("xiong-lu-lu-72");						//本轮要抓取的用户
	public static $_xsrf;														//获取多页关注者所需数据
	public static $param = array();												//获取多页关注者所需数据
	public static $followeesCount;
	public static $followersCount;
	public static $log;
	public static $pdo;
	public static $followees;													//关注人 数据
	
	/**
	 * [getUserInfo 获取用户]
	 * @param  [string] $result 	[抓取的页面信息]
	 *
	 * @return [array]  $user  		[用户信息]
	 */
	public static function getUserInfo($result,$type="followees"){
		$user = array();
		if($type == 'followees'){
			
			preg_match_all('#<a class="name" href="/people\/(.*?)">(.*?)</a>#', $result, $out);
			$user['url'] = empty($out[1]) ? '' : $out[1][0];
			$user['name'] = empty($out[2]) ? '' : $out[2][0];
			
			preg_match('#<div class="bio ellipsis" title=["|\'](.*?)["|\']>#', $result, $out);
			$user['introduction'] = empty($out[1]) ? '' : $out[1];
			
			preg_match('#<span class="location item" title=["|\'](.*?)["|\']>#', $result, $out);
			$user['address'] = empty($out[1]) ? '' : $out[1];

			preg_match('#<img class="Avatar Avatar--l" src="(.*?)" srcset=".*?" alt=".*?" />#', $result, $out);
			$img_url_tmp = empty($out[1]) ? '' : $out[1];
			// $user['img_url'] = getImg($img_url_tmp, $user['u_id']);
			$user['img_url'] = $img_url_tmp;

			preg_match('#<span class="business item" title=["|\'](.*?)["|\']>#', $result, $out);
			$user['business'] = empty($out[1]) ? '' : $out[1];

			preg_match('#<i class="icon icon-profile-(.*?)male"></i>#', $result, $out);
			$user['sex'] = empty($out[1]) ? 1 : 0;

			preg_match('#<span class="education item" title=["|\'](.*?)["|\']>#', $result, $out);
			$user['education'] = empty($out[1]) ? '' : $out[1];

			preg_match('#<span class="education-extra item" title=["|\'](.*?)["|\']>#', $result, $out);
			$user['major'] = empty($out[1]) ? '' : $out[1];

			preg_match('#<span class="content">\s(.*?)\s</span>#s', $result, $out);
			$user['description'] = empty($out[1]) ? '' : trim(strip_tags($out[1]));

			preg_match('#<span class="zg-gray-normal">关注了</span><br />\s<strong>(.*?)</strong>#', $result, $out);
			$user['followees_count'] = empty($out[1]) ? 0 : $out[1];
			self::$followeesCount = $user['followees_count'];

			preg_match('#<span class="zg-gray-normal">关注者</span><br />\s<strong>(.*?)</strong><label> 人</label>#', $result, $out);
			$user['followers_count'] = empty($out[1]) ? 0 : $out[1];
			self::$followersCount = $user['followers_count'];

			preg_match('#<strong>(.*?) 个专栏</strong>#', $result, $out);
			$user['special_count'] = empty($out[1]) ? 0 : intval($out[1]);

			preg_match('#<strong>(.*?) 个话题</strong>#', $result, $out);
			$user['follow_topic_count'] = empty($out[1]) ? 0 : intval($out[1]);

			preg_match('#<span class="zm-profile-header-user-agree"><span class="zm-profile-header-icon"></span><strong>(.*?)</strong>赞同</span>#', $result, $out);
			$user['approval_count'] = empty($out[1]) ? 0 : $out[1];

			preg_match('#<span class="zm-profile-header-user-thanks"><span class="zm-profile-header-icon"></span><strong>(.*?)</strong>感谢</span>#', $result, $out);
			$user['thank_count'] = empty($out[1]) ? 0 : $out[1];

			preg_match('#提问\s<span class="num">(.*?)</span>#', $result, $out);
			$user['ask_count'] = empty($out[1]) ? 0 : $out[1];

			preg_match('#回答\s<span class="num">(.*?)</span>#', $result, $out);
			$user['answer_count'] = empty($out[1]) ? 0 : $out[1];

			preg_match('#文章\s<span class="num">(.*?)</span>#', $result, $out);
			$user['article_count'] = empty($out[1]) ? 0 : $out[1];

			preg_match('#个人主页被 <strong>(.*?)</strong> 人浏览#', $result, $out);
			$user['pv_count'] = empty($out[1]) ? 0 : intval($out[1]);

			preg_match('#收藏\s<span class="num">(.*?)</span>#', $result, $out);
			$user['started_count'] = empty($out[1]) ? 0 : $out[1];

			preg_match('#公共编辑\s<span class="num">(.*?)</span>#', $result, $out);
			$user['public_edit_count'] = empty($out[1]) ? 0 : $out[1];
			
			preg_match_all('#<a data-hovercard="(.*?)" href="https://www.zhihu.com/people/(.*?)" class="zg-link author-link" title="(.*?)"#', $result, $out);
			$followees = empty($out[2]) ? array() : $out[2];
			self::$followees = $followees;
			
		}	
		
		//获取多页关注者所需数据
		preg_match('#<input type="hidden" name="_xsrf" value="(.*?)"/>#', $result, $out);
		$_xsrf = empty($out[1]) ? '' : trim($out[1]);
		self::$_xsrf = $_xsrf;
		
		preg_match('#<div class="zh-general-list clearfix" data-init="(.*?)">#', $result, $out);
		$param = empty($out[1]) ? '' : json_decode(html_entity_decode($out[1]), true);
		self::$param = $param;
		
		return $user;
	}
	
	
	/**
	 * [getFolloweesOrFollowers 获取关注或关注者的username](只有20条数据)
	 * @param  [string] $result 			[抓取的页面信息]
	 *
	 * @return [array]  $followerArr  		[用户关注或关注者的用户名]
	 */
	public static function getFollowers($result){
		preg_match_all('#<a data-hovercard="(.*?)" href="https://www.zhihu.com/people/(.*?)" class="zg-link author-link" title="(.*?)"#', $result, $out);
		$follower = empty($out[2]) ? array() : $out[2];
		$followerArr = array_unique(array_merge(self::$followees,$follower));	
		return $followerArr;
	}
	
	
	/**
	 * [getFolloweesOrFollowers 获取关注或关注者的username]
	 * @param  [string] $type 			[关注或关注者(默认为关注者)]
	 * @return [array]  $followerArr  	[用户所有关注或关注者的用户名]
	 */
	public static function getFollow($type='followees'){
		$max_num = 100;                                        //设置最大抓取数
		$num = $max_num * 20;
		$count = ($type == "followees") ? self::$followeesCount : self::$followersCount;
		$param = self::$param;
		$url = "https://www.zhihu.com/node/".$param['nodename'];
		$result = $followerArr = array();
		for($j=0;$j<$count;$j+=$num){
			$data = array();
			for($i=$j;$i<$j+$num;$i+=20){
				if($i>$count){
					break;
				}
				$param['params']['offset'] = $i;
				$data[] = array(
					"method" => "next",
					"params" => json_encode($param['params']),
					'_xsrf' => self::$_xsrf
				);
			}
			$result = array_merge($result,curl\Curl::multirequestParam($url,$data));
			
		}
		$preg = '';
		foreach($result as $v){
			foreach(json_decode($v,true)['msg'] as $value){
				preg_match_all('#<a data-hovercard="(.*?)" href="https://www.zhihu.com/people/(.*?)" class="zg-link author-link" title="(.*?)"#', $value, $out);
				$follower = empty($out[2]) ? array() : $out[2];
				$followerArr = array_merge($followerArr,$follower);
			}
		}
		return $followerArr;
	}
	
	
	/**
	 *	[setUser 	设置已抓取用户及下轮要抓取的用户]
	 *	@param	[array]	$user		[本轮抓取的用户]
	 *
	 *	@return	[void]
	 */
	public static function setUser($user){
		foreach($user as $key=>$value){
			if(in_array($value,self::$userArr)){
				unset($user[$key]);
			}
		}
		self::$usernameArr = $user;
		self::$userArr = array_unique(array_merge(self::$userArr,$user));
	}
}














