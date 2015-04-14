<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Voucher extends CI_Controller {
	public function __construct()
	{
    		parent::__construct();
		$this->load->helper('my_helper');
		$this->load->model('tour_model');
		$this->load->model('driver_model');
		$this->load->model('hotel_model');
		$this->load->model('user_model');
		$this->load->model('settings_model');
		$this->load->model('customers_model');
		$this->load->model('trip_booking_model');
		$this->load->model('vehicle_model');
		$this->load->model('package_model');
		$this->load->model('account_model');

		//$this->load->library('voucher_cart');
		no_cache();
	}

	public function index(){
		$param1=$this->uri->segment(2);
		$param2=$this->uri->segment(3);
		$param3=$this->uri->segment(4);
		$param4=$this->uri->segment(5);
		if($this->session_check()==true) {
			if($param1==''){
				$data['title']="Home | ".PRODUCT_NAME;    
	       			$page='user-pages/user_home';
				$this->load_templates($page,$data);
			}elseif($param1=='voucher'){	
				$this->voucher($param2);
			}else{
				$this->notFound();
			}

		}else{
			$this->notAuthorized();
		}
	}

	//--------------------------------voucher module functins start----------------------------
	public function voucher($trip_id='')
	{
		if($this->session_check()==true) {

			$trip = $this->tour_model->getTrip($trip_id);
			if($trip){//valid trip

				$tblArray=array('drivers','vehicle_models','vehicle_ac_types','vehicles');
			
				foreach($tblArray as $table){
					$data[$table]=$this->user_model->getArray($table);
				}

				$data['header'] = $this->set_voucher_header($trip_id);

				$data['tour_arrays'] = $this->tour_model->getTourValues($trip_id);
				$data['taxes']=$this->account_model->getTaxArray();
				

				//print_r($data['header']);exit;
				$data['tabs'] = $this->set_up_voucher_tabs('v_tab');
				$data['trip_expenses'] = $this->getTripExpenses();
				//print_r($data['trip_expenses']);exit;
				$data['trip_id'] = gINVALID;
				$data['title']="Tour Booking | ".PRODUCT_NAME;  
				$page='user-pages/tour-voucher';
				$this->load_templates($page,$data);
			}else{
				$this->notFound();
			}

			
		}else{
			$this->notAuthorized();
		}
	}

	
	//----voucher ajax calls---------------
	public function getTripExpenses($ajax='NO')
	{
		if(isset($_REQUEST['ajax']))
			$ajax=$_REQUEST['ajax'];

		$expense = $this->tour_model->getTripExpenses();

		if($expense==gINVALID){
			if($ajax=='NO'){
				return false;
			}else{
				echo 'false';
			}
		}else{
			if($ajax=='NO'){
				return $expense;
			}else{
				header('Content-Type: application/json');
				echo json_encode($expense);
			}
		}
	}

	function addToVoucher()//from ajax call
	{
		if(isset($_REQUEST['table'])){
			$tble = $_REQUEST['table'];
			$fields = $_REQUEST;

			array_shift($fields);//pop first element(url data from ajax call)
			unset($fields['table']);
			$fields['id'] = gINVALID;
			$data[$tble] = $fields;
			$this->tour_voucher->insert($data);
				
		}
		$cart = $this->tour_cart->contents();
		$this->build_itinerary_data($cart,$ajax = 'YES');
	}

	//----------------------


	function set_voucher_header($trip_id=''){
		$data = array();
		if(is_numeric($trip_id) && $trip_id > 0){
			$trip = $this->tour_model->getTrip($trip_id);
			//echo "<pre>";print_r($trip);echo "</pre>";exit;
			if($trip){
				return $trip;
			}else{
				redirect(base_url().'front-desk/tour/booking');
			}
			
		}else{
			return false;
		}
		
	}


	function set_up_voucher_tabs($tab_active='v_tab'){
			
		$tabs['v_tab'] = array('class'=>'','tab_id'=>'tab_1','text'=>'Vehicle',
						'content_class'=>'tab-pane');
		$tabs['a_tab'] = array('class'=>'','tab_id'=>'tab_2','text'=>'Accommodation',
						'content_class'=>'tab-pane');
		$tabs['s_tab'] = array('class'=>'','tab_id'=>'tab_3','text'=>'Services',
						'content_class'=>'tab-pane');

		if(array_key_exists($tab_active, $tabs)) {
			$tabs[$tab_active]['class'] = 'active';
			$tabs[$tab_active]['content_class'] = 'tab-pane active';
		}else{
			$tabs['t_tab']['class'] = 'active';
			$tabs['t_tab']['content_class'] = 'tab-pane active';
		}

		return $tabs;
	}

	//-------------------------------voucher module functins end----------------------------------


	


	//----------------------common functions---------------------------------------------------
	public function session_check() {
		if(($this->session->userdata('isLoggedIn')==true ) && ($this->session->userdata('type')==FRONT_DESK)) {
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
	public function notFound(){
		if($this->session_check()==true) {
		 	$this->output->set_status_header('404'); 
		 	$data['title']="Not Found";
      	 		$page='not_found';
        		 $this->load_templates($page,$data);
		}else{
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

}
