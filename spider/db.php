<?php
namespace spider\db;
class Db{
	public $type;
	public $host;
	public $port;
	public $name;
	public $user;
	public $password;
	public $pdo;
	public $table = "zh_user";
	public function __construct($type,$host,$port,$name,$user,$password){
		$this->type = $type;
		$this->host = $host;
		$this->port = $port;
		$this->name = $name;
		$this->user = $user;
		$this->password = $password;
		$dsn = $type.":dbname=".$name.";host=".$host.";port=".$port;
		try{
			$this->pdo = new \PDO($dsn,$user,$password,"array(PDO::ATTR_PERSISTENT => true)");
		}catch(Exception $e) {
			echo 'catch connection exception, info : ' . $e->__toString();
			exit;
		}
	}
	
	/**
	 *	[query			执行一次入库]
	 *	@param	[array] $user	[需要入库的数据(k为字段名)]	
	 *
	 *	$return	[void]
	 */
	public function query($user){
		$key = implode(",",array_keys($user));
		$value = "'".implode("','",$user)."'";
		$sql = "insert into ".$this->table."(".$key.") value(".$value.")";
		try{
			$this->pdo->query("set names utf8");
			$this->pdo->query($sql);
		}catch(Exception $e){
			echo $e->__toString();
			exit;
		}	
	}

}




















