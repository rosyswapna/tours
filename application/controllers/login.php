<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


//customer login controller
class Login extends CI_Controller {
	
	public function __construct()
	{
	    parent::__construct();
	    $this->load->helper('my_helper');
	    $this->load->model('login_model');
	    no_cache();

	}
	
	public function index($param1 ='')
	{
		if($param1=='login'){

			$this->checking_credentials();

		 }elseif($param1=='changepassword'){
			$this->changepassword();
		 }else{
			$this->goHome();
		}
	
	}

	public function checking_credentials() 
	{
		if($this->session_check()==true){

			$this->goHome();

		}elseif(isset($_REQUEST['username']) && isset($_REQUEST['password'])){

			 $username = $this->input->post('username');
			 $this->login_model->LoginAttemptsChecks($username);
			 if( $this->session->userdata('isloginAttemptexceeded')==false){
			 	$this->form_validation->set_rules('username','Username','trim|required|min_length[3]|max_length[20]|xss_clean');
			 	$this->form_validation->set_rules('password','Password','trim|required|min_length[3]|max_length[20]|xss_clean');
			 } else {
			 	$captcha = $this->input->post('captcha');
			 	$this->form_validation->set_rules('captcha', 'Captcha', 'trim|required|callback_captcha_check');
			 	$this->form_validation->set_rules('username','Username','trim|required|min_length[3]|max_length[20]|xss_clean');
			 	$this->form_validation->set_rules('password','Password','trim|required|min_length[3]|max_length[20]|xss_clean');
			}
			

			 if($this->form_validation->run()!=False){
			 	$username = $this->input->post('username');
		   	 	$pass  = $this->input->post('password');

		     		if( $username && $pass && $this->login_model->Login($username,$pass)) {
				 	if($this->session->userdata('loginAttemptcount') > 1){
		       	 			$this->login_model->clearLoginAttempts($username);
					 }
					$this->goHome();
					
		        
		    		} else {
					if($this->mysession->get('password_error')!='' ){
						$ip_address=$this->input->ip_address();
		        			$this->login_model->recordLoginAttempts($username,$ip_address);
					}
		        		$this->show_login();
		    		}
			} else {

		 	$this->show_login();
			}
		} else {

		 	$this->show_login();
		}
	}


	public function changepassword() 
	{
		if($this->session_check()==true) { 
		
			$data['old_password'] 	= '';
			$data['password']	= '';
			$data['cpassword']	= '';
      			if(isset($_REQUEST['password-update'])){
				$this->form_validation->set_rules('old_password','Current Password','trim|required|min_length[5]|max_length[12]|xss_clean');
				$this->form_validation->set_rules('password','New Password','trim|required|min_length[5]|max_length[12]|xss_clean');
				$this->form_validation->set_rules('cpassword','Confirm Password','trim|required|min_length[5]|max_length[12]|matches[password]|xss_clean');

				$data['old_password'] = trim($this->input->post('old_password'));
				$data['password'] = trim($this->input->post('password'));
				$data['cpassword'] = trim($this->input->post('cpassword'));

				if($this->form_validation->run() != False) {
					$dbdata['password']  	= md5($this->input->post('password'));
					$dbdata['old_password'] = md5(trim($this->input->post('old_password')));
					$val    		= $this->login_model->changePassword($dbdata);
					if($val == true) { 
						//change fa user password
						if($this->session->userdata('fa_account') > 0){
							$this->load->model('account_model');
							$this->account_model->change_password($dbdata);
						}
						
						$this->session->set_userdata(array('dbSuccess'=>'Password Changed Succesfully..!')); 
						redirect(base_url().'login/changepassword');
					}else{
						$this->show_change_password($data);
					}
				} else {
					$this->show_change_password($data);
				}
			} else {
			
				$this->show_change_password($data);
			}
		}else{
			$this->notAuthorized();
		}
		
	}

	


	function goHome(){

		if($this->session->userdata('type')==ORGANISATION_ADMINISTRATOR){
			redirect(base_url().'organization/admin');
		}
		elseif($this->session->userdata('type')==FRONT_DESK){
			redirect(base_url().'organization/front-desk');
		}
		elseif($this->session->userdata('type')==CUSTOMER){
			redirect(base_url().'customer/home');
		}elseif($this->session->userdata('type')==DRIVER){
			redirect(base_url().'driver/home');
		}elseif($this->session->userdata('type')==VEHICLE_OWNER){
			redirect(base_url().'vehicle/home');
		}
		else{
			$this->notFound();
		}
	}

	public function show_change_password($data = '') {
			$data['title']="Change Password | ".PRODUCT_NAME;  
			$page='access/change-password';
			$this->load_templates($page,$data);
	}

	public function show_login() 
	{   	$data['title']="Login | ".PRODUCT_NAME;	
		$this->load->view('access/login',$data);
		
    	}


	public function load_templates($page='',$data=''){
		if($this->session_check()==true) {
			$this->load->view('admin-templates/header',$data);
			$this->load->view('admin-templates/nav');
			$this->load->view($page,$data);
			$this->load->view('admin-templates/footer');
			}
		else{
				$this->notAuthorized();
		}
	}
					

	public function customer_session_check() {
		if(($this->session->userdata('isLoggedIn')==true ) && ($this->session->userdata('type')==CUSTOMER) ) 			{
			return true;
		} else {
			return false;
		}
	}

	public function driver_session_check() {
		if(($this->session->userdata('isLoggedIn')==true ) && ($this->session->userdata('type')==DRIVER)) {
			return true;
		} else {
			return false;
		}
	}

	//check any session exists
	public function session_check() {
		if($this->session->userdata('isLoggedIn')==true ){
			return true;
		} else {
			return false;
		}
	}

	
}
