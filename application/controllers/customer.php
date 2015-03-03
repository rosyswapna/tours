<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Customer extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('my_helper');
		$this->load->model('user_model');
		$this->load->model('driver_model');
		$this->load->model('customers_model');
		$this->load->model('trip_booking_model');
		$this->load->model('customers_model');
		$this->load->model('tarrif_model');
		$this->load->model('device_model');
		$this->load->model('vehicle_model');
		no_cache();

	}

	public function index()
	{
		$param1=$this->uri->segment(2);
		$param2=$this->uri->segment(3);
		$param3=$this->uri->segment(4);
		if($this->session_check()==true) {
			if($param1=='' || $param1 == 'home'){
				$this->Dashboard();
			}else{
				$this->notAuthorized();
			}
		}else{
			$this->notAuthorized();
		}
	}

	

	public function Dashboard(){
		$data['title']="Home | ".PRODUCT_NAME;    
       		$page='customer-pages/dashboard';
		$this->load_templates($page,$data);
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

	public function session_check() {
		if(($this->session->userdata('isLoggedIn')==true ) && ($this->session->userdata('type')==CUSTOMER)) {
			return true;
		} else {
			return false;
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
