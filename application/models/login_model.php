<?php
class Login_model extends CI_Model {

	//get user types except system admin
	function getUserTypes(){
	
		$query = "SELECT * FROM user_types WHERE  name <>'System Administrator' order by name ASC";
		$qry=$this->db->query($query);
	
		$rows = $qry->result_array();
		$i=0;
		foreach($rows as $row){
			$list[$row['id']] = $row['name'];$i++;
		}
		return $list;
	}
			
	
	function LoginAttemptsChecks($username) {
		$this->db->from('users');
        	$this->db->where('username',$username );
		$login = $this->db->get()->result();
		$this->db->from('user_login_attempts');
		if(count($login) > 0){
        		$this->db->where('user_id',$login[0]->id);
        		$login_attempts = $this->db->get()->result();
		 	if (count( $login_attempts) >= 3 ) {
				$this->session->set_userdata(array('isloginAttemptexceeded'=>true));
				$this->session->set_userdata(array('loginAttemptcount'=>count($login_attempts)));
			}else{
				$this->session->set_userdata(array('isloginAttemptexceeded'=>false));
			}
		}
	}


	//customer login with username and password
	function Login( $username, $password) {
	
		$filter = array(
				'username' => $username,
				'password' => md5($password),
				);
		$this->db->select('users.*,organisations.name as organisation_name');
        	$this->db->from('users');
		$this->db->join('organisations','users.organisation_id = organisations.id','left');
        	$this->db->where($filter);
        	$login = $this->db->get()->result();
       
        	if ( is_array($login) && count($login) == 1 ) {
			
            		$this->details = $login[0];
			
			if($this->details->user_status_id==USER_STATUS_ACTIVE){
				$this->set_session();
  			  	return true;
			}else{
			 	$this->mysession->set('user_status_error','User Not Active.');
				return false;
			}
			
            
        	}else{
			$this->mysession->set('password_error','Invalid Login');
        		return false;
		}
   	}


	function changePassword($data) {
		$this->db->from('users');
        	$this->db->where('id',$this->session->userdata('id'));
        	$this->db->where( 'password', $data['old_password']);
        	$changepassword = $this->db->get()->result();
		if ( is_array($changepassword) && count($changepassword) == 1 ) {
			$dbdata=array('password'=>$data['password']);
			$this->db->where('id',$this->session->userdata('id') );
			$succes=$this->db->update('users',$dbdata);
			if($succes > 0) {
				$this->session->set_userdata(array('dbSuccess'=>'Password changed Successfully'));
				$this->session->set_userdata(array('dbError'=>''));
				return true;
			}
		}else{
			$this->session->set_userdata(array('dbError'=>'Current Password seems to be different'));
			return false;
		}

   	}


	//set user session 
	function set_session() {
		$this->session->set_userdata( array(
			'id'=>$this->details->id,
			'name'=> $this->details->first_name . ' ' . $this->details->last_name,
			'email'=>$this->details->email,
			'username'=>$this->details->username,
			'organisation_id'=>$this->details->organisation_id,
			'organisation_name'=>$this->details->organisation_name,
			'type'=>$this->details->user_type_id,
			'isLoggedIn'=>true,
			'token_pass' =>$this->details->password,
			'fa_account' =>$this->details->fa_account
			));

		if($this->details->user_type_id == CUSTOMER){
			$this->db->from('customers');
        		$this->db->where('login_id', $this->details->id);
        		$customer = $this->db->get()->result();
			$this->session->set_userdata('customer',$customer[0]);
		}elseif($this->details->user_type_id == DRIVER){
			$this->db->from('drivers');
        		$this->db->where('login_id', $this->details->id);
        		$driver = $this->db->get()->result();
			$this->session->set_userdata('driver',$driver[0]);
		}elseif($this->details->user_type_id == VEHICLE_OWNER){
			$this->db->from('vehicle_owners');
        		$this->db->where('login_id', $this->details->id);
        		$v_owner = $this->db->get()->result();
			$this->session->set_userdata('v_owner',$v_owner[0]);
		}
	}

	function clearLoginAttempts($username){
		$tables = array('user_login_attempts');
		$this->db->where('user_id',$this->session->userdata('id'));
		$this->db->delete($tables);

	}

	function recordLoginAttempts($username,$ip_address) {
		$this->db->from('users');
        	$this->db->where('username',$username );
		$login = $this->db->get()->result();
		$this->db->from('user_login_attempts');
		if(count($login) > 0){
		$data=array('user_id'=>$login[0]->id,'ip_address'=>$ip_address);
		$this->db->set('created', 'NOW()', FALSE);
		$this->db->insert('user_login_attempts',$data);
		}

	}
}

