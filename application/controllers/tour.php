<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tour extends CI_Controller {
	public function __construct()
	{
    		parent::__construct();
		$this->load->helper('my_helper');
		$this->load->model('tour_model');
		$this->load->model('settings_model');
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
			}elseif($param1=='business-season'){	
				$this->show_business_season($param2);
			}elseif($param1=='manage-business-season'){	
				$this->manage_business_season($param2);
			}elseif($param1=='destination'){	
				$this->show_destination($param2);
			}elseif($param1=='tour-booking'){	
				$this->tour_booking($param2);
			}else{
				$this->notFound();
			}

		}else{
			$this->notAuthorized();
		}
	}

	//-----------------------business season ----------------------------------
	public function show_business_season($getID='')
	{
		if($this->session_check()==true) {

			$data['id']= '';
			$data['name']= '';
			$data['starting']= '';
			$data['ending']= '';

			//if edit get values to form inputs
			if(is_numeric($getID) && $getID > 0){
				$season = $this->tour_model->getBusinessSeason($getID);
				if($season){
					//get default values for form input values
					$data['id']= $season['id'];
					$data['name']= $season['name'];
					$data['starting']= $season['starting_date'];
					$data['ending']= $season['ending_date'];
				}
			}
			$data['season_list'] = $this->tour_model->getBusinessSeasonList();
			$data['title']="Business Season | ".PRODUCT_NAME;  
			$page='user-pages/business-season';
			$this->load_templates($page,$data);
		}
		else{
			$this->notAuthorized();
		}	
	}

	//form action
	public function manage_business_season(){
		
		//add or edit
		if(isset($_REQUEST['business-season-add']) || isset($_REQUEST['business-season-edit'])){

			$this->form_validation->set_rules('name','Season Name','trim|required|xss_clean');
			$this->form_validation->set_rules('starting','Season Starting','trim|required|xss_clean');
			$this->form_validation->set_rules('ending','Season Ending','trim|required|xss_clean');

			if($this->form_validation->run() != False) {
				$dbData['organisation_id'] = $this->session->userdata('organisation_id'); 
				$dbData['user_id'] = $this->session->userdata('user_id'); 
				$id = $this->input->post('id');
				$dbData['name'] = $this->input->post('name');
				$dbData['starting_date'] = $this->input->post('starting');
				$dbData['ending_date'] = $this->input->post('ending');
			
				if(is_numeric($id) && $id > 0){//edit
					if($this->settings_model->updateValues('business_seasons',$dbData,$id)){
						$this->session->set_userdata(array('dbSuccess'=>'Business Season Updated Succesfully..!')); 
						$this->session->set_userdata(array('dbError'=>''));
					}
				}else{//add
					if($this->settings_model->addValues('business_seasons',$dbData)){
						$this->session->set_userdata(array('dbSuccess'=>'Business Season Added Succesfully..!')); 
						$this->session->set_userdata(array('dbError'=>''));
					}
				}
			}		
			
		}else if(isset($_REQUEST['business-season-delete'])){//delete season click
			$id = $this->input->post('id');
			if($this->settings_model->deleteValues('business_seasons',$id)){
				$this->session->set_userdata(array('dbSuccess'=>'Business Season Deleted Succesfully..!')); 
				$this->session->set_userdata(array('dbError'=>''));
			}
		}

		$this->show_business_season();
	}

	
	//------------------------------------------------------------------------------------------
	
	//-----------------------destination ----------------------------------
	public function show_destination($getID='')
	{
		if($this->session_check()==true) {

			$data['id']= '';
			$data['name']= '';
			$data['latitude']= '';
			$data['longitude']= '';
			$data['seasons']= '';

			//if edit get values to form inputs
			if(is_numeric($getID) && $getID > 0){
				$destination = $this->tour_model->getDestination($getID);
				if($destination){
					//get default values for form input values
					$data['id']= $destination['id'];
					$data['name']= $destination['name'];
					$data['latitude']= $destination['lat'];
					$data['longitude']= $destination['lng'];
					$data['seasons']= $destination['seasons'];
				}
			}
			$data['destination_list'] = $this->tour_model->getDestinationList();
			$data['title']="Destination | ".PRODUCT_NAME;  
			$page='user-pages/destination';
			$this->load_templates($page,$data);
		}
		else{
			$this->notAuthorized();
		}	
	}

	//form action
	public function manage_destination(){
		
		//add or edit
		if(isset($_REQUEST['destination-add']) || isset($_REQUEST['destination-edit'])){

			$this->form_validation->set_rules('name','Destination Name','trim|required|xss_clean');
			$this->form_validation->set_rules('lat','Season Starting','numeric|xss_clean');
			$this->form_validation->set_rules('lng','Season Ending','numeric|xss_clean');
			if($this->form_validation->run() != False) {
				$dbData['organisation_id'] = $this->session->userdata('organisation_id'); 
				$dbData['user_id'] = $this->session->userdata('user_id'); 
				$id = $this->input->post('id');
				$dbData['name'] = $this->input->post('name');
				$dbData['lat'] = $this->input->post('latitude');
				$dbData['lng'] = $this->input->post('longitude');
				$dbData['seasons'] = searialize($this->input->post('seasons'));
			
				if(is_numeric($id) && $id > 0){//edit
					if($this->settings_model->updateValues('destinations',$dbData,$id)){
						$this->session->set_userdata(array('dbSuccess'=>'Destination Updated Succesfully..!')); 
						$this->session->set_userdata(array('dbError'=>''));
					}
				}else{//add
					if($this->settings_model->addValues('destinations',$dbData)){
						$this->session->set_userdata(array('dbSuccess'=>'Destination Added Succesfully..!')); 
						$this->session->set_userdata(array('dbError'=>''));
					}
				}
			}		
			
		}else if(isset($_REQUEST['destination-delete'])){//delete season click
			$id = $this->input->post('id');
			if($this->settings_model->deleteValues('destinations',$id)){
				$this->session->set_userdata(array('dbSuccess'=>'Destination Deleted Succesfully..!')); 
				$this->session->set_userdata(array('dbError'=>''));
			}
		}

		$this->show_destination();
	}

	//-----------------------------------------------------------------------------------------


	//-------------------------tour module fuctions--------------------------------------------

	public function tour_booking()
	{
		$data['title']="Tour Booking | ".PRODUCT_NAME;  
		$page='user-pages/tour-booking';
		$this->load_templates($page,$data);
	}

	//-----------------------------------------------------------------------------------------

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
