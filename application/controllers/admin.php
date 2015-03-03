<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('my_helper');
		$this->load->model('admin_model');
		no_cache();

	}

	public function session_check() {
		if(($this->session->userdata('isLoggedIn')==true ) && ($this->session->userdata('type')==SYSTEM_ADMINISTRATOR)) {
			return true;
		} else {
			return false;
		}
	} 
   
	public function index(){
	
		if($this->session_check()==true) {
			$data['title']="Home | ".PRODUCT_NAME;   
			$page='admin-pages/home';
			$this->load_templates($page,$data);
		}else{
			$this->notAuthorized();
		}

	}


	//New organisation 
	public function NewOrganisation()
	{
		
		if(isset($_REQUEST['name']) && isset($_REQUEST['addr'])&& isset($_REQUEST['uname'])&& isset($_REQUEST['pwd'])&& isset($_REQUEST['mail'])&& isset($_REQUEST['phn'])&& isset($_REQUEST['fname'])&& isset($_REQUEST['lname'])&&  isset($_REQUEST['submit'])){ 
			$name = $this->input->post('name');
			$fname = trim($this->input->post('fname'));
			$lname = trim($this->input->post('lname'));
			$addr  = $this->input->post('addr');
			$uname  = trim($this->input->post('uname'));
			$pwd  = $this->input->post('pwd');
			$mail  = $this->input->post('mail');
			$phn = $this->input->post('phn');
			$sms = $this->input->post('sms');
			$sys_mail = $this->input->post('sys_mail');
			$this->form_validation->set_rules('name','Organization','trim|required|min_length[2]|xss_clean|is_unique[organisations.name]');
			$this->form_validation->set_rules('fname','First Name','trim|required|min_length[2]|xss_clean');
			$this->form_validation->set_rules('lname','Last Name','trim|required|min_length[2]|xss_clean');
			$this->form_validation->set_rules('addr','Address','trim|required|min_length[10]|xss_clean');
			$this->form_validation->set_rules('uname','Username','trim|required|min_length[4]|max_length[15]|xss_clean|is_unique[users.username]');
			$this->form_validation->set_rules('pwd','Password','trim|required|min_length[5]|max_length[12]|matches[cpwd]|xss_clean');
			$this->form_validation->set_rules('cpwd','Confirmation','trim|required|min_length[5]|max_length[12]|xss_clean');
			$this->form_validation->set_rules('mail','Mail','trim|required|valid_email|is_unique[users.email]');
			$this->form_validation->set_rules('phn','Contact Info','trim|required|regex_match[/^[0-9]{10}$/]|numeric|xss_clean|is_unique[users.phone]');
			$this->form_validation->set_rules('sms','Sms Gateway','trim|min_length[10]|xss_clean');
			$this->form_validation->set_rules('sys_mail','System Mail','trim|valid_email|is_unique[organisations.system_email]');
			if($this->form_validation->run()==False){
				$data=array('title'=>'Add New Organization | '.PRODUCT_NAME,'name'=>$name,'fname'=>$fname,'lname'=>$lname,'uname'=>$uname,'pwd'=>$pwd,'addr'=>$addr,'mail'=>$mail,'phn'=>$phn,'sms'=>$sms,'sys_mail'=>$sys_mail,'cpwd'=>'');
$this->showAddOrg($data);
			}else {
				
   
				//inserting values to db
				$res=$this->admin_model->insertOrg($name,$fname,$lname,$addr,$uname,$pwd,$mail,$phn,$sms,$sys_mail);
				if($res==true){ 
					//sending email to admin
					// $data=array('name'=>$name,'fname'=>$fname,'lname'=>$lname,'uname'=>$uname,'pwd'=>$pwd,'addr'=>$addr,'mail'=>$mail,'phn'=>$phn);
					// $rs=$this->sendEmail($data);
					// if($rs==true){
					$this->session->set_userdata(array('dbSuccess'=>'Organization Added Succesfully..!'));
					$this->session->set_userdata(array('dbError'=>''));
					redirect(base_url().'admin/organization/list');

		  			//}
				}
			}
		}else if(($this->session->userdata('isLoggedIn')==true )&&($this->session->userdata('type')==SYSTEM_ADMINISTRATOR)){
			$data=array('title'=>'Add New Organization |'.PRODUCT_NAME,'name'=>'','fname'=>'','lname'=>'','uname'=>'','pwd'=>'','addr'=>'','mail'=>'','phn'=>'','sms'=>'','sys_mail'=>'','cpwd'=>'');
			$this->showAddOrg($data);
		}
	}

	//List organisation
	public function ListOrganisation($secondaction='')
	{
		
		$data['status']=$this->admin_model->getStatus();
		$condition='';
		$per_page=3;
		$like_arry='';
		$where_arry='';
		//for search
		if((isset($_REQUEST['sname'])|| isset($_REQUEST['status']))&& isset($_REQUEST['search'])){
			if($secondaction==''){
			$secondaction='0';
			}
			$this->mysession->delete('condition');
			if($_REQUEST['sname']!=null&& $_REQUEST['status']!=-1){
			$like_arry=array('name'=> $_REQUEST['sname']);
			$where_arry=array('status_id'=>$_REQUEST['status']);

			}
			if($_REQUEST['sname']==null&& $_REQUEST['status']!=-1){
			$where_arry=array('status_id'=>$_REQUEST['status']);
			}
			if($_REQUEST['sname']!=null&& $_REQUEST['status']==-1){
			$like_arry=array('name'=> $_REQUEST['sname']);
			}
			$this->mysession->set('condition',array("like"=>$like_arry,"where"=>$where_arry));
			$data['status_id']=$_REQUEST['status'];
			$data['sname']=$_REQUEST['sname'];
		}
		if(is_null($this->mysession->get('condition'))){
		$this->mysession->set('condition',array("like"=>$like_arry,"where"=>$where_arry));
		}
		$tbl='organisations';
		$data['org_status']=$this->admin_model->getStatus();
		$baseurl=base_url().'admin/organization/list/';
		$uriseg ='4';

		    $p_res=$this->mypage->paging($tbl,$per_page,$secondaction,$baseurl,$uriseg);
			if($secondaction==''){
				$this->mysession->delete('condition');
				}
			//check company exists in fa. If exists then remove link add account for this organisation
			$data['values']=$p_res['values'];
			if(empty($data['values'])){
			$data['result']="No Results Found !";
			}
			$data['page_links']=$p_res['page_links'];
			$data['title']='Organization List| '.PRODUCT_NAME;
			$page='admin-pages/orgList';
			$this->load_templates($page,$data);
	}
	
	//Admin actions with Organisation(new,list,organisation change password)
	public function organization($action = '', $secondaction = ''){

		if($this->session_check()==true) {
			if ($action =='new' && $secondaction == ''){ 
				$this->NewOrganisation();
			}else if($action=='list' && ($secondaction == ''|| is_numeric($secondaction))) {
				$this->ListOrganisation($secondaction);
	     		}else{
				$result=$this->admin_model->checkOrgWithId($action);
				if(!$result){
					echo "page not found";
				}else{ 
					$org_res=$result['org_res'];
					$user_res=$result['user_res'];
	
					// $org_result holds organization info && $user_res holds user info		
					if($secondaction =='password-reset'){
						//if organization name  and password-reset comes what to do?
						$this->load->model('admin_model');
					   	$data['title']		  = "Reset Password | ".PRODUCT_NAME; 
						$data['password']	  = '';
						$data['cpassword'] 	  = '';
						$data['orgname']	  = $action;

				      		if(isset($_REQUEST['admin-org-password-reset'])){
							$this->form_validation->set_rules('password','New Password','trim|required|min_length[5]|max_length[12]|xss_clean');
							$this->form_validation->set_rules('cpassword','Confirm Password','trim|required|min_length[5]|max_length[12]|matches[password]|xss_clean');
							$data['password'] = trim($this->input->post('password'));
							$data['cpassword'] = trim($this->input->post('cpassword'));
							if($this->form_validation->run() != False) {
								$dbdata['password']  = md5($this->input->post('password'));
								$dbdata['passwordnotencrypted']  = $this->input->post('password');
								$dbdata['name']  	= $org_res['name'];
								$dbdata['username'] 	= $user_res['username'];
								$dbdata['email']  	= $user_res['email'];
								$dbdata['id'] 		= $user_res['id'];
								$val= $this->admin_model->resetOrganizationPasswordAdmin($dbdata);
								if($val == true) {
									//$this->sendEmailOnorganizationPaswordReset($dbdata);			
									redirect(base_url().'admin/organization/list');
								}else{
									$this->show_org_reset_password($data);
								}
							} else {
				
									$this->show_org_reset_password($data);
							}
						} else {
			
							$this->show_org_reset_password($data);
						}
					}else{
		
						$data['title']=$org_res['name'].'\'s Profile Update |'.PRODUCT_NAME;
						if(isset($_REQUEST['admin-org-profile-update'])){
							$data['name'] = $this->input->post('name');
							$data['uname'] = trim($this->input->post('uname'));
							$data['hname']  = trim($this->input->post('hname'));
							$data['fname'] = $this->input->post('fname');
							$data['lname'] = $this->input->post('lname');
							$data['addr']  = $this->input->post('addr');
							$data['mail']  = $this->input->post('mail');
							$data['hmail']  = trim($this->input->post('hmail'));
							$data['phn'] = $this->input->post('phn');
							$data['sms'] = $this->input->post('sms');
							$data['sys_mail'] = $this->input->post('sys_mail'); 
							$data['hsys_mail'] = $this->input->post('hsys_mail'); 
							$data['hphone']  = trim($this->input->post('hphone'));
							$data['user_id'] = $this->input->post('user_id');
							$data['org_id'] = $this->input->post('org_id');
							$data['status'] = $this->input->post('status');
							if($data['name'] == $data['hname']){
								$this->form_validation->set_rules('name','Organization','trim|required|min_length[2]|xss_clean');
							}else{
								$this->form_validation->set_rules('name','Organization','trim|required|min_length[2]|xss_clean|is_unique[organisations.name]');
							}
							$this->form_validation->set_rules('fname','First Name','trim|required|min_length[2]|xss_clean');
							$this->form_validation->set_rules('lname','Last Name','trim|required|min_length[2]|xss_clean');
							$this->form_validation->set_rules('addr','Address','trim|required|min_length[10]|xss_clean');
							$this->form_validation->set_rules('sms','SMS Gateway','trim|min_length[10]|xss_clean');
							if($data['mail'] == $data['hmail']){
								$this->form_validation->set_rules('mail','Mail','trim|required|valid_email');
							}else{
								$this->form_validation->set_rules('mail','Mail','trim|required|valid_email|is_unique[users.email]');
							}
							if($data['sys_mail'] == $data['hsys_mail']){ 
								$this->form_validation->set_rules('sys_mail','System Mail','trim|valid_email');
							}else{ 
								$this->form_validation->set_rules('sys_mail','System Mail','trim|valid_email|is_unique[organisations.system_email]');
							}
							if($data['phn'] == $data['hphone']){
								$this->form_validation->set_rules('phn','Contact Info','trim|required|regex_match[/^[0-9]{10}$/]|numeric|xss_clean');
							}else{
								$this->form_validation->set_rules('phn','Contact Info','trim|required|regex_match[/^[0-9]{10}$/]|numeric|xss_clean||is_unique[users.phone]');
							}
		
							if($this->form_validation->run()!=False){ 

								$res    = $this->admin_model->updateOrganization($data);
								if($res == true) { 
				   	    				$this->session->set_userdata(array('dbSuccess'=>'Organization Profile Updated Succesfully..!'));
					    				$this->session->set_userdata(array('dbError'=>''));
					    				redirect(base_url().'admin/organization/list');
								}
							}else{ //print_r($data);exit;
								$this->showAddOrg($data);

							}
		
						} else if(isset($_REQUEST['admin-org-profile-status-change'])){
							if($this->input->post('status') == STATUS_ACTIVE){
								$dbdata['status_id'] = STATUS_INACTIVE;
								$dbdata['user_status_id'] = USER_STATUS_DISABLED;
							}else if($this->input->post('status')==STATUS_INACTIVE){
								$dbdata['status_id'] = STATUS_ACTIVE;
								$dbdata['user_status_id'] = USER_STATUS_ACTIVE;
							} 
							$dbdata['user_id'] = $this->input->post('user_id');
							$dbdata['org_id']  =  $this->input->post('org_id');
							$res    = $this->admin_model->orgEnableDisable($dbdata);
							if($res == true) { 
			   	    				$this->session->set_userdata(array('dbSuccess'=>'Organization Status changed Successfully.!'));
				    				$this->session->set_userdata(array('dbError'=>''));
				    				redirect(base_url().'admin/organization/list');
							}
						} else {
							$status=$this->admin_model->getStatus();
							$data['org_id']=$org_res['id'];
							$data['name']=$org_res['name'];
							$data['hname']  = $org_res['name'];
							$data['addr']=$org_res['address'];

							$data['user_id']=$user_res['id'];
							$data['uname']=$user_res['username'];
							$data['fname']=$user_res['first_name'];
							$data['lname']=$user_res['last_name'];
							$data['mail']=$user_res['email'];
							$data['hmail']  = $user_res['email'];
							$data['phn']=$user_res['phone'];
							$data['sms']=$org_res['sms_gateway_url']; 
							$data['sys_mail']=$org_res['system_email'];
							$data['hsys_mail']=$org_res['system_email'];
							$data['hphone']=$user_res['phone'];
							$data['status']=$org_res['status_id'];
							$this->showAddOrg($data);
						}
		
					}
				}
			}
		}else{
			$this->notAuthorized();
		}
	}


	public function show_org_reset_password($data) {
		if($this->session_check()==true) {
			$page='admin-pages/password-reset';
			$this->load_templates($page,$data);
		}else{
			$this->notAuthorized();
		}
	}

	public function profile(){
	   if($this->session_check()==true) {
		$this->load->model('admin_model');
		$dbdata = '';
              if(isset($_REQUEST['admin-profile-update'])){
			$this->form_validation->set_rules('username','Username','trim|required|min_length[4]|max_length[15]|xss_clean');
			$this->form_validation->set_rules('firstname','First Name','trim|required|min_length[2]|xss_clean');
			$this->form_validation->set_rules('lastname','Last Name','trim|required|min_length[2]|xss_clean');
			if($this->input->post('email')==$this->input->post('hmail')){
			$this->form_validation->set_rules('email','Email','trim|required|valid_email|xss_clean');
			}
			else{
			$this->form_validation->set_rules('email','Email','trim|required|valid_email|xss_clean|is_unique[users.email]');
			}
			$this->form_validation->set_rules('phone','Phone','trim|required|regex_match[/^[0-9]{10}$/]|numeric|xss_clean');
			$this->form_validation->set_rules('address','Address','trim|required|min_length[10]|xss_clean');
			$dbdata['username']  = $this->input->post('username');
		   	$dbdata['first_name'] = $this->input->post('firstname');
			$dbdata['last_name']  = $this->input->post('lastname');
		    $dbdata['email'] 	   = $this->input->post('email');
			$dbdata['phone'] 	   = $this->input->post('phone');
		    $dbdata['address']   = $this->input->post('address');
			if($this->form_validation->run() != False) {
				$val    		   = $this->admin_model->updateProfile($dbdata);
				redirect(base_url().'admin');
			}else{
				$this->show_profile($dbdata);
			}
		}else{
			
			$this->show_profile($dbdata);

		}
	   }	
		else{
			$this->notAuthorized();
		}
	}
	public function show_profile($data) {
		  if($this->session_check()==true) {
			if($data == ''){
			$data['values']=$this->admin_model->getProfile();
			}else{
			$data['postvalues']=$data;
			}
			$data['title']="Profile |".PRODUCT_NAME;  
		    $page='admin-pages/profile';
			$this->load_templates($page,$data);
		    }
			else{
				$this->notAuthorized();
			}
	}
	public function changePassword() {
	if($this->session_check()==true) {
	   $this->load->model('admin_model');
	   	$data['title']		  =		"Change Password | ".PRODUCT_NAME; 
		$data['old_password'] = 	'';
		$data['password']	  = 	'';
		$data['cpassword'] 	  = 	'';
       if(isset($_REQUEST['admin-password-update'])){
			$this->form_validation->set_rules('old_password','Current Password','trim|required|min_length[5]|max_length[12]|xss_clean');
			$this->form_validation->set_rules('password','New Password','trim|required|min_length[5]|max_length[12]|xss_clean');
			$this->form_validation->set_rules('cpassword','Confirm Password','trim|required|min_length[5]|max_length[12]|matches[password]|xss_clean');
			$data['old_password'] = trim($this->input->post('old_password'));
			$data['password'] = trim($this->input->post('password'));
			$data['cpassword'] = trim($this->input->post('cpassword'));
			if($this->form_validation->run() != False) {
				$dbdata['password']  	= md5($this->input->post('password'));
				$dbdata['old_password'] = md5(trim($this->input->post('old_password')));
				$val    			    = $this->admin_model->changePassword($dbdata);
				if($val == true) {				
					//change fa admin password
					$this->load->model('account_model');
					$this->account_model->change_password($dbdata);
					
					redirect(base_url().'logout');
				}else{
					$this->show_change_password($data);
				}
			} else {
				
					$this->show_change_password($data);
			}
		} else {
			
					$this->show_change_password($data);
		}
		           }
		else{
			$this->notAuthorized();
		}
	}	
   
	public function show_change_password($data) {
		if($this->session_check()==true) {
				$page='admin-pages/change-password';
				$this->load_templates($page,$data);
					}
		else{
			$this->notAuthorized();
		}
	}

	public function showAddOrg($data){
	   if($this->session_check()==true) {
		$page='admin-pages/addOrg';
		$this->load_templates($page,$data);
		}
	   else{
			$this->notAuthorized();
		}
	}
	
	public function sendEmail($data){
	   if($this->session_check()==true) {
	
	 $config = Array(
  'protocol' => 'smtp',
  'smtp_host' => 'ssl://smtp.googlemail.com',
  'smtp_port' => 465,
  'smtp_user' => 'xxx@gmail.com', // change it to yours
  'smtp_pass' => 'xxx', // change it to yours
  'mailtype' => 'html',
  'charset' => 'iso-8859-1',
  'wordwrap' => TRUE
);
		$this->email->from(SYSTEM_EMAIL, 'Acube Innovations');
		$this->email->to($data['mail']);
		$this->email->subject('Succes Message');
		$this->email->message('You have succesfully added a new organisation'.$data['name'].'!!</br> Following are your User Credentials.</br> Username:'.$data['uname'].'</br> Password:'.$data['pwd'].'Thanks & Regards</br> Acube Innovations');
		if($this->email->send())
			{
			echo 'Email sent.';
			return true;
			}
		else
			{
			show_error($this->email->print_debugger());
			}
			}
		else{
			$this->notAuthorized();
		}
	}
	public function sendEmailOnorganizationPaswordReset($data){
	   if($this->session_check()==true) {
	
		$config = Array(
		  'protocol' => 'smtp',
		  'smtp_host' => 'ssl://smtp.googlemail.com',
		  'smtp_port' => 465,
		  'smtp_user' => 'xxx@gmail.com', // change it to yours
		  'smtp_pass' => 'xxx', // change it to yours
		  'mailtype' => 'html',
		  'charset' => 'iso-8859-1',
		  'wordwrap' => TRUE
		);
		$this->email->from(SYSTEM_EMAIL, 'Acube Innovations');
		$this->email->to($data['email']);
		$this->email->subject('Succes Message');
		$message ='Hi '.$data['name'].'!!</br> Your Passsword is reset by admin.Your </br> Username:'.$data['username'].'</br> Password:'.$data['passwordnotencrypted'].'</br>Thanks & Regards</br> Acube Innovations';
	    $this->email->message($message);
		if($this->email->send())
			{
			
			return true;
			}
		else
			{
			show_error($this->email->print_debugger());
			}
			}
		else{
			$this->notAuthorized();
		}
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
	public function notAuthorized(){
	$data['title']='Not Authorized | '.PRODUCT_NAME;
	$page='not_authorized';
	$this->load->view('admin-templates/header',$data);
	$this->load->view('admin-templates/nav');
	$this->load->view($page,$data);
	$this->load->view('admin-templates/footer');
	
	}

}
