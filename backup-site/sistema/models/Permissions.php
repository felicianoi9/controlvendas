<?php
class Permissions extends Model{

	private $group;
	private $permissions;

	public function setGroup($id,$id_company){
		$this->group=$id;
		$this->permissions = array();

		$sql=$this->db->prepare("SELECT params FROM permission_groups WHERE id=:id AND id_company=:id_company ");
		$sql->bindValue(':id', $id);
		$sql->bindValue(':id_company', $id_company);
		$sql->execute();

		if($sql->rowCount()>0){
			$row = $sql->fetch();

			if(empty($row['params'])){
				$row['params']='0';
			}

			$params =$row['params'];	
			$sql = $this->db->prepare("SELECT name FROM permission_params WHERE id IN ($params) AND id_company=:id_company ");
			
			$sql->bindValue(':id_company', $id_company);
			$sql->execute();

			if($sql->rowCount()>0){
				

				foreach ($sql->fetchAll() as $item ) {
					$this->permissions[] = $item['name'];
				}
			}

		}
		

	}

	public function hasPermission($name){
		if(in_array($name, $this->permissions)){
			return true;
		}else{
			return false;
		}

	}

	public function getList($id_company){
		$array = array();

		$sql = $this->db->prepare("SELECT * FROM permission_params WHERE id_company =:id_company");
		$sql->bindValue(':id_company', $id_company);
		$sql->execute();

		if($sql->rowCount()>0){
			$array = $sql->fetchAll();
		}

		return $array;

	}

	public function getGroupList($id_company ){
		$array = array();

		$sql = $this->db->prepare("SELECT * FROM permission_groups WHERE id_company =:id_company");
		$sql->bindValue(':id_company', $id_company);
		$sql->execute();

		if($sql->rowCount()>0){
			$array = $sql->fetchAll();
		}
		return $array;

	}
	public function getGroup($id, $id_company ){
		$array = array();

		$sql = $this->db->prepare("SELECT * FROM permission_groups WHERE id =:id AND id_company =:id_company ");
		$sql->bindValue(':id', $id);
		$sql->bindValue(':id_company', $id_company);
		$sql->execute();

		if($sql->rowCount()>0){
			$array = $sql->fetch();
			$array['params'] = explode(',', $array['params']);
		}
		return $array;

	}

	public function add($name, $id_company){
		$sql = $this->db->prepare("INSERT INTO permission_params SET name=:name , id_company =:id_company");
		$sql->bindValue(':name', $name);
		$sql->bindValue(':id_company', $id_company);
		$sql->execute();	
	}

	public function addGroup($name, $plist, $id_company){
		$params = implode(',', $plist);

		$sql = $this->db->prepare("INSERT INTO permission_groups SET name=:name , id_company =:id_company, params=:params");
		$sql->bindValue(':name', $name);
		$sql->bindValue(':id_company', $id_company);
		$sql->bindValue(':params', $params);
		$sql->execute();	

	}

	public function editGroup($name, $plist, $id, $id_company){
		$params = implode(',', $plist);

		$sql = $this->db->prepare("UPDATE permission_groups SET name=:name , id_company =:id_company, params=:params WHERE id=:id"  );
		$sql->bindValue(':name', $name);
		$sql->bindValue(':id_company', $id_company);
		$sql->bindValue(':params', $params);
		$sql->bindValue(':id', $id);
		$sql->execute();	

	}
	
	public function delete($id){
		$sql = $this->db->prepare("DELETE FROM permission_params WHERE id=:id ");
		$sql->bindValue(':id', $id);
		$sql->execute();	
	}
	public function deleteGroup($id){
		$u= new Users();
		if($u->findUsersInGroup($id)==false){
			$sql = $this->db->prepare("DELETE FROM permission_groups WHERE id=:id ");
			$sql->bindValue(':id', $id);
			$sql->execute();
		}
			
	}




	///////////////////////////////////////////////////////////////////////q
	/*
	private $userInfo;

	public function isLogged(){

		if(isset($_SESSION['ccUser']) && !empty($_SESSION['ccUser'])){
			return true;
		}else{
			return false;
		}
	}

	public function doLogin($email, $pass){
		$sql = $this->db->prepare("SELECT * FROM users WHERE email=:email AND password=:password ");
		$sql->bindValue(':email', $email);
		$sql->bindValue(':password', md5($pass));
		$sql->execute();

		if($sql->rowCount()>0){
			$row = $sql->fetch();

			$_SESSION['ccUser'] = $row['id'];
			return true;
		}else{

			return false;
		}
	}

	public function setLoggedUser(){
		if(isset($_SESSION['ccUser']) && !empty($_SESSION['ccUser'])){
			$id = $_SESSION['ccUser'];

			$sql = $this->db->prepare("SELECT * FROM users WHERE id=:id ");
			$sql->bindValue(':id', $id);
			$sql->execute();

			if($sql->rowCount()>0){
				$this->userInfo = $sql->fetch();
				
			}

		}
	}

	public function getCompany(){
		if(isset($this->userInfo['id_company'])){
			return $this->userInfo['id_company'];
		}else{
			return 0;
		}
	}

	public function getEmail(){
		if(isset($this->userInfo['email'])){
			return $this->userInfo['email'];
		}else{
			return '';
		}
	}

	public function logout(){
		unset($_SESSION['ccUser']);
	}*/
	

}
?>