<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tour extends CI_Controller {
	public function __construct()
	{
    		parent::__construct();
		$this->load->helper('my_helper');
		$this->load->model('tour_model');
		$this->load->model('user_model');
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
			}elseif($param1=='booking'){	
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

			if($this->mysession->get('post')!=NULL){ 
				$data=$this->mysession->get('post');
				$this->mysession->delete('post');
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

			$this->form_validation->set_rules('season_name','Season Name','trim|required|xss_clean');
			$this->form_validation->set_rules('starting','Season Starting','trim|required|xss_clean');
			$this->form_validation->set_rules('ending','Season Ending','trim|required|xss_clean');

			$data['season_name'] = $this->input->post('season_name');
			$data['starting'] = $this->input->post('starting');
			$data['ending'] = $this->input->post('ending');

			if($this->form_validation->run() != False) {
			//date conversion to mysql
				$start_date=$this->input->post('starting');
			        $end_date=$this->input->post('ending');
			//-------------------------------	
				$dbData['organisation_id'] = $this->session->userdata('organisation_id'); 
				$dbData['user_id'] = $this->session->userdata('id'); 
				$id = $this->input->post('id'); 
				$dbData['name'] = $this->input->post('season_name');
				$dbData['starting_date'] = $this->date_functions->seasonDate_to_mysqlDate($start_date);
				$dbData['ending_date'] = $this->date_functions->seasonDate_to_mysqlDate($end_date);
				
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
			}else{
				$this->mysession->set('post',$data);
			}		
			
		}else if(isset($_REQUEST['business-season-delete'])){//delete season click
			$id = $this->input->post('id');
			if($this->settings_model->deleteValues('business_seasons',$id)){
				$this->session->set_userdata(array('dbSuccess'=>'Business Season Deleted Succesfully..!')); 
				$this->session->set_userdata(array('dbError'=>''));
			}
		}

		redirect(base_url().'front-desk/tour/business-season/');
	}

	
	//------------------------------------------------------------------------------------------
	
	//-----------------------destination ----------------------------------
	public function show_destination($getID='')
	{
		if($this->session_check()==true) { 

			
		
			//if edit get values to form inputs
			if(is_numeric($getID) && $getID > 0){
				$destination = $this->tour_model->getDestination($getID);
				if($destination){
					//get default values for form input values
					$data['id']= $destination['id'];
					$data['dest_name']= $destination['name'];
					$data['dest_lat']= $destination['lat'];
					$data['dest_long']= $destination['lng'];
					$data['seasons']= $destination['seasons']; 
					$data['description']= $destination['description']; 
					$data['status_id']= $destination['status_id'];
				}
			}elseif($this->mysession->get('post')!=NULL){ 
				$data=$this->mysession->get('post');
				$this->mysession->delete('post');
			}else{
				$data['id'] = '';
			}

			$data['business_seasons']=$this->user_model->getArray('business_seasons');
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

			$this->form_validation->set_rules('dest_name','Destination Name','trim|required|xss_clean');
			$this->form_validation->set_rules('description','Description','trim|required|xss_clean');
			$this->form_validation->set_rules('dest_lat','Season Starting','numeric|xss_clean');
			$this->form_validation->set_rules('dest_long','Season Ending','numeric|xss_clean');

			$data['dest_name'] = $this->input->post('dest_name');
			$data['description'] = $this->input->post('description');
			$data['dest_lat'] = $this->input->post('dest_lat');
			$data['dest_long'] = $this->input->post('dest_long');
			$data['seasons'] =$this->input->post('seasons');
			$data['id'] =$this->input->post('id');

			if($this->form_validation->run() != False) {
				$dbData['organisation_id'] = $this->session->userdata('organisation_id'); 
				$dbData['user_id'] = $this->session->userdata('id'); 
				$id = $this->input->post('id');
				$dbData['name'] = $this->input->post('dest_name');
				$dbData['description'] = $this->input->post('description');
				$dbData['lat'] = $this->input->post('dest_lat');
				$dbData['lng'] = $this->input->post('dest_long');
				$seasons=array();
				$seasons=$this->input->post('seasons');
				if($seasons[0]==''|| empty($seasons)){
				   $dbData['seasons'] = '';
				}else{
				   $dbData['seasons'] = serialize($this->input->post('seasons'));
				}
				if(is_numeric($id) && $id > 0){//edit
					if($this->settings_model->updateValues('destinations',$dbData,$id)){
						$this->session->set_userdata(array('dbSuccess'=>'Destination Updated Succesfully..!')); 
						$this->session->set_userdata(array('dbError'=>''));
					}
				}else{//add

					$dbData['status_id'] = STATUS_ACTIVE;
					if($this->settings_model->addValues('destinations',$dbData)){
						$this->session->set_userdata(array('dbSuccess'=>'Destination Added Succesfully..!')); 
						$this->session->set_userdata(array('dbError'=>''));
					}
				}
			}else{
				$this->mysession->set('post',$data);
			}		
			
		}else if(isset($_REQUEST['destination-delete'])){//delete season click 
			$id = $this->input->post('id'); 
			if($this->settings_model->deleteValues('destinations',$id)){
				$this->session->set_userdata(array('dbSuccess'=>'Destination Deleted Succesfully..!')); 
				$this->session->set_userdata(array('dbError'=>''));
			}
		}elseif(isset($_REQUEST['destination-enable']) || isset($_REQUEST['destination-disable'])){ 

			$id = $this->input->post('id');
			if(isset($_REQUEST['destination-enable'])){
				$dbData['status_id'] = STATUS_ACTIVE;
			}else{
				$dbData['status_id'] = STATUS_INACTIVE;
			}

			$this->settings_model->updateValues('destinations',$dbData,$id);
		}

		redirect(base_url().'front-desk/tour/destination/');
	}

	//-----------------------------------------------------------------------------------------


	//-------------------------tour module fuctions--------------------------------------------

	public function checkRoomAvailability($Ajax = 'NO')//not completed
	{
		$hotel_id	= $_REQUEST['hotel_id'];
		$room_type_id	= $_REQUEST['room_type_id'];
		$_date 		= $_REQUEST['booking_date'];
		$hotel_room = $this->hotel_model->getHotelRoomType($hotel_id,$room_type_id);
		if($hotel_room){
			$room_occupancy = $this->tour_model->getRoomOccupancyCount($hotel_id,$room_type_id,$_date);
		}
	}

	public function getSeasonDestinations($Ajax='NO')
	{
		$_date = $_REQUEST['itinerary-date'];
	}

	public function tour_booking()
	{
		$tblArray=array('booking_sources','available_drivers','trip_models','drivers','vehicle_types',	
				'vehicle_models','vehicle_makes','vehicle_ac_types','vehicle_fuel_types',
				'vehicle_seating_capacity','vehicle_beacon_light_options','languages','payment_type',
				'customer_types','customer_groups','hotel_categories','trip-services');
			
		foreach($tblArray as $table){
			$data[$table]=$this->user_model->getArray($table);
		}

		echo "<pre>";print_r($data);echo "</pre>";exit;
		
		$data['title']="Tour Booking | ".PRODUCT_NAME;  
		$page='user-pages/tour-booking';
		$this->load_templates($page,$data);
	}

	public function manage_tour_booking()
	{
		if(isset($_REQUEST['trip-add'])){
			//validation


			//trip data
			$tripData['id'] 		= $this->input->post('id');
			$tripData['customer_id'] 	= $this->input->post('customer_id');
			$tripData['guest_id'] 		= $this->input->post('guest_id');
			$tripData['booking_date'] 	= $this->input->post('booking_date');
			$tripData['booking_time'] 	= $this->input->post('booking_time');
			$tripData['trip_status_id'] 	= $this->input->post('trip_status_id');
			$tripData['source_id'] 		= $this->input->post('source_id');
			$tripData['source_details'] 	= $this->input->post('source_details');
			$tripData['source_contact'] 	= $this->input->post('source_contact');
			$tripData['pickup_date'] 	= $this->input->post('pickup_date');
			$tripData['pickup_time'] 	= $this->input->post('pickup_time');
			$tripData['drop_date'] 		= $this->input->post('drop_date');
			$tripData['drop_time'] 		= $this->input->post('drop_time');
			$tripData['trip_from_destination_id'] 	= $this->input->post('trip_from_destination_id');
			$tripData['trip_to_destination_id'] 	= $this->input->post('trip_to_destination_id');
			$tripData['pax'] 		= $this->input->post('pax');
			$tripData['markup_type'] 	= $this->input->post('markup_type');
			$tripData['markup_value'] 	= $this->input->post('markup_value');
			$tripData['remarks'] 		= $this->input->post('remarks');

			//trip Vehicle data
			$vehicleData['vehicle_id'] 		= $this->input->post('pax');
			$vehicleData['vehicle_ac_type_id'] 	= $this->input->post('pax');
			$vehicleData['vehicle_beacon_light_option_id'] = $this->input->post('pax');
			$vehicleData['vehicle_type_id'] 	= $this->input->post('pax');
			$vehicleData['vehicle_model_id'] 	= $this->input->post('pax');
			$vehicleData['pluckcard'] 	= $this->input->post('pax');
			$vehicleData['uniform'] 	= $this->input->post('pax');
			$vehicleData['tariff_id'] 	= $this->input->post('pax');
			$vehicleData['driver_id'] 	= $this->input->post('pax');
			$vehicleData['driver_language_id'] = $this->input->post('pax');
			$vehicleData['driver_language_proficiency_id'] = $this->input->post('pax');

			
			//form input values
			$data = array_merge($tripData,$vehicleData);

			$tripData['organisation_id'] 	= $this->session->userdata('organisation_id'); 
			$tripData['user_id'] 		= $this->session->userdata('user_id');
			$trip_id = $this->settings_model->addValues_returnId('trips',$tripData);
			if($trip_id && $trip_id > 0){//trip added
				//build itinerary
				$itinerary = $this->tour_model->addItineraries($trip_id);
				if($itinerary){
					$tripVehicleUpdate = $this->tour_model->addTripVehicles($vehicleData,$trip_id);
				}

				$this->session->set_userdata(array('dbSuccess'=>'Trip booked successfully!')); 
				$this->session->set_userdata(array('dbError'=>''));
				redirect(base_url().'front-desk/tour/booking/'.$trip_id);
			}else{
				$this->session->set_userdata(array('dbSuccess'=>'')); 
				$this->session->set_userdata(array('dbError'=>'Trip booking Failed'));
				redirect(base_url().'front-desk/tour/booking/');
			}
			
		}
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
