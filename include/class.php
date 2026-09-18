<?php
class Iot{


    private $host      = DB_HOST;
    private $user      = DB_USER;
    private $pass      = DB_PASS;
    private $dbname    = DB_NAME;
 
    private $db;
    private $error;
 
    public function __construct(){
	
		
	$dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
    $options = array(
        PDO::ATTR_PERSISTENT => true,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    );
    try {
        $this->db = new PDO($dsn, $this->user, $this->pass, $options);
    } catch(PDOException $e) {
        $this->error = $e->getMessage();
        // Handle the error appropriately - log it or display an error message
        die("Database connection error: " . $this->error); // Terminate script execution
    }
		
	}
	public function getSetting2() {
		$query = $this->db->prepare("SELECT * FROM setting ORDER BY id");
		$query->execute();
		$data =array();
		while($row=$query->fetch(PDO::FETCH_ASSOC)){
			if($row['value']=='on'){
			$value = 11;
			}else if($row['value']=='off'){
			$value = 10;
			}else{
			$value = $row['value'];	
			}
			$data[$row['name']] = $value;	
		}
		return $data;			
	}
	public function getSetting() {
		$query = $this->db->prepare("SELECT * FROM setting ORDER BY id");
		$query->execute();
		$data =array();
		while($row=$query->fetch(PDO::FETCH_ASSOC)){
			$data[$row['id']] = array($row['name'], $row['value']);	
		}
		return $data;				
	}
	public function getWeather() {		
		$qry = $this->db->prepare("SELECT id, tem, hum, ppm FROM dh11 WHERE id=:id");	
		$qry->bindValue(':id', 100, PDO::PARAM_STR);
		$qry->execute();		
		$data = $qry->fetch(PDO::FETCH_ASSOC);		
		$data = json_encode($data);	
		return $data;
	}
	public function getStatus($id) {	
		$query = $this->db->prepare("SELECT * FROM setting WHERE id=:id || name=:id");
		$query->bindValue(':id', $id, PDO::PARAM_STR);
		$query->execute();
		$row = $query->fetch(PDO::FETCH_ASSOC);
		return $row;				
	}	
	public function getMaxinfo($table) {
		//$qry = $this->db->prepare("SELECT * FROM ".$table." ORDER BY id DESC LIMIT 1");
		$qry = $this->db->prepare("SELECT * FROM ".$table." WHERE id = (SELECT MAX(id) FROM ".$table.")");
		//$query = $this->db->prepare("SELECT * FROM dh11 WHERE id=:id");
		//$query->bindValue(':id', 100, PDO::PARAM_STR);
		$qry->execute();		
		$data = $qry->fetch(PDO::FETCH_ASSOC);		
		$data = json_encode($data);	
		return $data;				
	}
	public function getAllinfo($table, $indata) {	
		
		$query = $this->db->prepare("SELECT * FROM ".$table." ORDER BY date");		
		$query->execute();
		$data = array();
			while($row=$query->fetch(PDO::FETCH_ASSOC)){
				$data[] = array(strtotime($row['date'])*1000, floatval($row[$indata]));
				//$data[] = array(strtotime($row['date'])*1000, (int)$row['value']);
				//$data[] = $row;
			}
			
		
		return $data;				
	}
	public function getLiveinfo() {
		//$query = $this->db->prepare("SELECT * FROM dh11 WHERE id = (SELECT MAX(id) FROM dh11)");
		$query = $this->db->prepare("SELECT * FROM dh11 WHERE id=:id");
		$query->bindValue(':id', 100, PDO::PARAM_STR);
		$query->execute();
		$row = $query->fetch(PDO::FETCH_ASSOC);
		return $row;				
	}	
     public function saveInfo($val,$id){
		try {
			$sql = "UPDATE  setting SET value=? WHERE name=?";
			$query = $this->db->prepare($sql);
			$query->execute(array($val, $id));
				
				$data = json_encode(array("type"=>"success", "message"=>" Your data has been saved! successfully"));	
			} catch (Exception $e) {
				$data = json_encode(array("type"=>"danger", "message"=>"Something weird happened!"));			 
			} 	 
		return $data;
	 }		

   public function itemUpdate($table, $array, $id) {
	try {
		$query='';
		$values = array();
		foreach ($array as $name => $value) {
		    $query .= ' '.$name.' = :'.$name.','; 
		    $arr1[':'.$name] = $value;
		}			
		$fields = substr($query, 0, -1).'';
		$arr2=array(':id'=>$id);
		$values= array_merge($arr1, $arr2);
		
		$query = "UPDATE ".$table." SET ".$fields." WHERE id=:id ";
		
		$sth = $this->db->prepare($query);		
		 $sth->execute($values);		 
		$data = json_encode(array("type"=>"success", "msg"=>"Successfully    "));	
		} catch (Exception $e) {
		 $data = json_encode(array("type"=>"danger", "msg"=>"Invalid Request "));
		 
		} 			
		return $data;	
		 
		 
  	  }	
	  
     public function update() {
		$data = [
			'tem' => (isset($_GET['tem'])? $_GET['tem']:''),
			'hum' => (isset($_GET['hum'])? $_GET['hum']:''),
			'ppm' => (isset($_GET['ppm'])? $_GET['ppm']:''),
			'id' => 100,
		];
		 
		$sql = "UPDATE dh11 SET tem=:tem, hum=:hum, ppm=:ppm WHERE id=:id";
		$stmt= $this->db->prepare($sql);
		
			try {
			  $result = $stmt->execute($data);
			  $data = json_encode(array("type"=>"success", "message"=>" Your data has been saved! successfully"));
			} catch(PDOException $e) {
			  $result = $e->getCode() . " - " . $e->getMessage();
			  $data = json_encode(array("type"=>"danger", "message"=>$result));
			}	
		
		return  $data;		 
	 }		 
    public function update2($val, $id) {
	try {
		$sql = "UPDATE setting SET value=? WHERE name=?";
		$stmt = $this->db->prepare($sql);

		 $stmt->execute(array($val, $id));				   
		   $msg= 'Your data has been saved! successfully';
		} catch (Exception $e) {		   
		   $msg= 'Something weird happened'; 		 
		} 	 
		return $msg;
  	 } 
	public function addItem2($table, $mos, $tem) {
		
		try{

        $rs= $this->db->prepare("INSERT INTO ". $table ." (value, tem) VALUES (? ,?)");	
		$rs->bindParam("value", $mos,PDO::PARAM_STR);
		$rs->bindParam("tem", $tem,PDO::PARAM_STR);
		$rs->execute();
		 
		$data = json_encode(array("type"=>"success", "msg"=>"Successfully    "));	
		} catch (Exception $e) {
		 $data = json_encode(array("type"=>"danger", "msg"=>"Invalid Request ".$e));
		 
		} 			
		return $data;	
	}
	
	public function addItem($table, $info) {
		$params = array();
		try{

		$sql='INSERT INTO '. $table .' ('.implode( ',', array_keys( $info ) ) .') values (:'.implode(',:',array_keys( $info ) ).');';
		foreach( $info as $field => $value ) {
		$params[":{$field}"]=($value=='')? 0 : $value;
		}
		
		$query = $this->db->prepare($sql);			
		$query->execute($params);				 
		 
		$data = json_encode(array("type"=>"success", "msg"=>"Successfully    "));	
		} catch (Exception $e) {
		 $data = json_encode(array("type"=>"danger", "msg"=>"Invalid Request ".$e));
		 
		} 			
		return $data;	
	}		 
   public function login($user, $pwd){  
       // $hash_pwd = hash('sha256', $pwd);  	
        $sql= "SELECT user, pwd FROM users WHERE user=:user && pwd=:pass ";	
        $rs= $this->db->prepare($sql);	
		$rs->bindParam("user", $user,PDO::PARAM_STR);
		$rs->bindParam("pass", $pwd,PDO::PARAM_STR);
		$rs->execute();		
		
		$row=$rs->fetch(PDO::FETCH_ASSOC);		
        if($rs->rowCount() >0) {       
            $_SESSION['login'] = true;
            $_SESSION['user'] = $row['user']; 
			$_SESSION['pwd'] = $row['pwd'];	

				$data = json_encode(array("type"=>"success", "msg"=>"Hi! {$row['user']} welcome back to IOT System"));	
			 }else{
				$data = json_encode(array("type"=>"danger", "msg"=>"invalid user and password!"));			 
			} 		
		return $data;
    }

	 
 
 	public function notify($type, $title, $msg){
		echo "<script>
			$(document).ready(function(){
				notifyUp('$type', '$title', '$msg');
			});
		</script>";
	}

	

}