<?php
require_once "config.php";
interface DBinterface{
	function SQLquery_with_output($sql,$prepareKeys,$prepareValues);
	function SQLquery_without_output($sql,$prepareKeys,$prepareValues);
	function SQLquery_with_output_oneitem($sql,$prepareKeys,$prepareValues);
}
class Database implements DBinterface{
	public function __construct($db){$this->db = $db;}
	function SQLquery_with_output_oneitem($sql,$prepareKeys,$prepareValues){
	$executeValues=array_combine($prepareKeys, $prepareValues); //массив в который запишутся ключи из prepareVal[] и значения из $data
	$stmt=$this->db->prepare($sql);
	$stmt->execute($executeValues);
	$res=$stmt->fetch(PDO::FETCH_ASSOC);
		return $res;
	}
	function SQLquery_with_output($sql,$prepareKeys,$prepareValues){
	$executeValues=array_combine($prepareKeys, $prepareValues); //массив в который запишутся ключи из prepareVal[] и значения из $data
	$stmt=$this->db->prepare($sql);
	$stmt->execute($executeValues);
	$res=$stmt->fetchAll(PDO::FETCH_ASSOC);
		return $res;
	}
	function SQLquery_without_output($sql,$prepareKeys,$prepareValues){
	$executeValues=array_combine($prepareKeys, $prepareValues); //массив в который запишутся ключи из prepareVal[] и значения из $data
	$stmt=$this->db->prepare($sql);
	$res=$stmt->execute($executeValues);
	if(!$res){
		showError();
	}
}
}
class User{
	private $name;
	private $email;
	private $pswd;
	private $id;
	private $db;
	public function __construct(DBinterface $db){
		$this->db=$db;
	}
	public function registration(){
		$data=[
		$name=$_POST['name'],
		$email=$_POST['email'],
		$pswd=$_POST['pswd']
		];
		empty_validation($data);
		$email_inDB=$this->check_email($email);
		if(!empty($email_inDB)){
			showError("Такой email уже существует.");
		}
		if(mb_strlen($pswd)<8){
			showError("Пароль должен содержать больше 8 символов");
		}
		$pswd=md5($pswd);
		$this->db->SQLquery_without_output("INSERT INTO `users` (`name`,`email`,`password`) VALUES (:name,:email,:pswd)",['name','email','pswd'],[$name,$email,$pswd]);
		header("Location: login-form.php");
	}
	public function authorization(){
		$data=[
		$email=$_POST['email'],
		$pswd=$_POST['pswd']
		];
		$rememberme = (isset($_POST['rememberMe'])) ? 'on' : 'off' ;
		empty_validation($data);
		$email_inDB=$this->check_email($email);
		if(empty($email_inDB)){
			showError("Неверные email или пароль.");
		}
		$pswd=md5($pswd);
		$user_fromDB=$this->db->SQLquery_with_output_oneitem("SELECT * FROM `users` WHERE `email`=:email",['email'],[$email]);
		$id=$user_fromDB['user_id'];
		if($user_fromDB['password']!=$pswd){
			showError("Неверные email или пароль.");
		}
		if ($rememberme=='on') {
			$this->rememberme($id);
		}
		session_start();
		$_SESSION['sess_user_id']=$id;
		$_SESSION['hiddenTasks']='off';
		header("Location: list.php");
	}
	public function get_email(){
		$res=$this->db->SQLquery_with_output_oneitem("SELECT `email` FROM `users` WHERE `user_id`=:user_id",['user_id'],[$_SESSION['sess_user_id']]);
		return $res['email'];
	}
	private function check_email($emailforCheck){
		$res=$this->db->SQLquery_with_output_oneitem("SELECT * FROM `users` WHERE `email`=:email",['email'],[$emailforCheck]);
		return $res;
	}
	private function rememberme($id){
		setcookie("cookie_id",$id,time()+24*60*60*31);
	}
}
class Task
{
	private $name;
	private $text;
	private $img;
	private $img_name;
	private $ishidden;
	private $id;
	private $user_id;
	public function __construct(DBinterface $db){
		$this->db=$db;
	}
	public function get_all(){
		$res=$this->db->SQLquery_with_output("SELECT * FROM `tasks` WHERE `user_id`=:user_id",['user_id'],[$_SESSION['sess_user_id']]);
		return $res;
	} 
	public function get_one(){
		$res=$this->db->SQLquery_with_output_oneitem("SELECT * FROM `tasks` WHERE `task_id`=:task_id",['task_id'],[$_GET['task_id']]);
		return $res;
	}
	public function create(){
		$data=[
		$name=$_POST['name'],
		$text=$_POST['text']
		];
		$user_id=$_SESSION['sess_user_id'];
		$ishidden = (isset($_POST['ishidden'])) ? 'yes' : 'no' ;
		$img=$_FILES['img'];
		if($img['error']==4){
			$noImg=true;
		}else{
			$noImg=false;}
		if(!$noImg){
			$this->img_handle($img);
			$img_name=$this->create_img_name($img);
		}
		if($noImg){
			$img_name="uploads/no-img.jpg";
		}
		$id=$this->get_last_task_id()+1;
		$this->db->SQLquery_without_output("INSERT INTO `tasks`
			(`task_name`,`task_description`,`task_img`,`task_id`,`user_id`,`task_isHidden`) VALUES
			(:task_name,:task_description,:task_img,:task_id,:user_id,:task_isHidden)",
			['task_name','task_description','task_img','task_id','user_id','task_isHidden'],
			[$name,$text,$img_name,$id,$user_id,$ishidden]);
		header("Location: list.php");
	}
	public function edit(){
		$data=[
		$name=$_POST['name'],
		$text=$_POST['text'],
		$id=$_POST['id']
		];
		empty_validation($data);
		$ishidden = (isset($_POST['ishidden'])) ? 'yes' : 'no' ;
		$isEdittingPhoto = (isset($_POST['isEdittingPhoto'])) ? true : false ;
		if($isEdittingPhoto){
			$img=$_FILES['img'];
			if ($_POST['img']!='uploads/no-img.jpg') {
				unlink($_POST['img']);
			}
			if($img['error']==4){
				$noImg=true;
			}else{
				$noImg=false;}
			if(!$noImg){
			$this->img_edit_handle($img);
			$img_name=$this->create_img_name_edit($img);
			}
			if($noImg){
				$img_name="uploads/no-img.jpg";
			}
		}else{
			$img_name=$_POST['img'];
		}
			$this->db->SQLquery_without_output("UPDATE `tasks` SET 
				`task_name`=:task_name,`task_description`=:task_description,`task_isHidden`=:task_isHidden,`task_img`=:task_img WHERE `task_id`=:task_id",
				['task_name','task_description','task_isHidden','task_img',':task_id'],
				[$name,$text,$ishidden,$img_name,$id]);
			header("Location: list.php");
	}
	public function delete(){
		$res=$this->db->SQLquery_with_output_oneitem("SELECT `task_img` FROM `tasks` WHERE task_id=:task_id",['task_id'],[$_GET['task_id']]);
		if ($res['task_img']!='uploads/no-img.jpg') {
				unlink($res['task_img']);
			}
		$this->db->SQLquery_without_output("DELETE FROM `tasks` WHERE task_id=:task_id",['task_id'],[$_GET['task_id']]);
		header("Location: list.php");
	}
	private function get_img_format($img){
		$regexp="/\/(jpeg|png)/ui";
		$matches=[];
		preg_match($regexp,$img['type'],$matches);
		if(empty($matches)){
			showError("Загрузите картнику формата jpg,png");
		}
		return $matches['1'];
	}
	private function validate_img($img){
		if($img['size']>pow(10,7))
		{
			showError("Слишком большой размер файла");
		}
		$this->get_img_format($img);
	}
	private function get_last_task_id(){
		$res=$this->db->SQLquery_with_output_oneitem('SELECT MAX(`task_id`) AS last_task_id FROM `tasks`',[],[]);
		if(!is_null($res['last_task_id'])){
		$last_task_id=$res['last_task_id'];
		}else{
		$last_task_id=0;
		}
		return $last_task_id;
	}
	private function create_img_name($img){
		$task_id=$this->get_last_task_id()+1;
		$img_name='uploads/task_img_'.$task_id.'.'.$this->get_img_format($img);
		return $img_name;
	}
	private function create_img_name_edit($img){
		$task_id=$_POST['id'];
		$img_name='uploads/task_img_'.$task_id.'.'.$this->get_img_format($img);
		return $img_name;
	}
	private function upload_img($img){
		move_uploaded_file($img['tmp_name'],$this->create_img_name($img));
	}
	private function upload_img_edit($img){
		move_uploaded_file($img['tmp_name'],$this->create_img_name_edit($img));
	}
	private function img_handle($img){
		$this->validate_img($img);
		$this->upload_img($img);
	}
	private function img_edit_handle($img){
		$this->validate_img($img);
		$this->upload_img_edit($img);
	}
}
?>