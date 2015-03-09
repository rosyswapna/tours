<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Hotel extends CI_Controller {
	public function __construct()
	{
    		parent::__construct();
		$this->load->helper('my_helper');
		$this->load->model('tour_model');
		$this->load->model('hotel_model');
		no_cache();
	}

	public function index(){
		$param1=$this->uri->segment(3);
		$param2=$this->uri->segment(4);
		$param3=$this->uri->segment(5);
		$param4=$this->uri->segment(6);
		if($this->session_check()==true) {
			if($param1==''){
				$data['title']="Home | ".PRODUCT_NAME;    
	       			$page='user-pages/user_home';
				$this->load_templates($page,$data);
			}elseif($param1=='profile'){	
				$this->hotel_profile($param2);
			}elseif($param1=='list'){	
				$this->list_hotel($param2);
			}else{
				$this->notFound();
			}

		}else{
			$this->notAuthorized();
		}
	}
	//------------------------------------------------------------------------------------------


	public function hotel_profile($param2 = ''){

		if($this->session_check()==true) {
			$active_tab = 'h_tab';
			
			$data['tabs'] = $this->set_up_hotel_tabs($active_tab,$param2);			
			$data['title']="Hotel | ".PRODUCT_NAME;  
			$page='user-pages/hotel-profile';
			$this->load_templates($page,$data);
		}else{
			$this->notAuthorized();
		}
	}
	//------------------------------------------------------------------------------------------
	
	public function list_hotel($id){

		if($this->session_check()==true) {
			
			$data['title']="Hotel | ".PRODUCT_NAME;  
			$page='user-pages/list-hotel';
			$this->load_templates($page,$data);
		}
		else{
			$this->notAuthorized();
		}
	}
	//------------------------------------------------------------------------------------------


	//-----------------common functions---------------------------------------------
	function set_up_hotel_tabs($tab_active='h_tab',$hotel_id=''){
			
		$tabs['h_tab'] = array('class'=>'','tab_id'=>'tab_1','text'=>'Profile',
						'content_class'=>'tab-pane');

		if($hotel_id!='' && $hotel_id > 0){

			$tabs['o_tab'] = array('class'=>'','tab_id'=>'tab_2','text'=>'Owner',
						'content_class'=>'tab-pane');
			$tabs['r_tab'] = array('class'=>'','tab_id'=>'tab_4','text'=>'Rooms',
						'content_class'=>'tab-pane');
			$tabs['t_tab'] = array('class'=>'','tab_id'=>'tab_5','text'=>'Tariffs',
						'content_class'=>'tab-pane');
			$tabs['p_tab'] = array('class'=>'','tab_id'=>'tab_4','text'=>'Payments',
						'content_class'=>'tab-pane');
			$tabs['a_tab'] = array('class'=>'','tab_id'=>'tab_5','text'=>'Accounts',
						'content_class'=>'tab-pane');
		}

		if (array_key_exists($tab_active, $tabs)) {
			$tabs[$tab_active]['class'] = 'active';
			$tabs[$tab_active]['content_class'] = 'tab-pane active';
		}else{
			$tabs['c_tab']['class'] = 'active';
			$tabs['c_tab']['content_class'] = 'tab-pane active';
		}


		return $tabs;
	}
	//-------------------------------------------------------------------------

	//----------------------default functions-----------------------------------------

	
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
	//-------------------------------------------------------------

}
