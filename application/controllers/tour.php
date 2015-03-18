<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tour extends CI_Controller {
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

	//get season destination
	public function season_destinations($Ajax='NO')
	{	
		if(isset($_REQUEST['ajax']))
			$Ajax=$_REQUEST['ajax'];
			
		$_date = $_REQUEST['itinerary_date'];
		$destinations = $this->tour_model->getDateSeasonDestinations($_date);
		if($Ajax=='NO'){
			return $destinations;
		}else{
			header('Content-Type: application/json');
			echo json_encode($destinations);
		}
	}

	//get hotel list with hotel category and destination
	public function getAvailableHotels($Ajax='NO')
	{
		$destination_id = $_REQUEST['destination_id'];
		$category_id = $_REQUEST['category_id'];
		$_date = $_REQUEST['itinerary-date'];
		$seasons = $this->tour_model->getSeasonIdssWithDate($_date);
		$condition = array('hotel.destination_id'=>$destination_id,'hotel.hotel_category_id'=>$category_id);
		
		$hotels = $this->hotel_model->getAvailableHotels($condition,$seasons);
		
		if($Ajax=='NO'){
			return $hotels;
		}else{

			if($destinations){
				header('Content-Type: application/json');
				echo json_encode($destinations);
			}else{
				echo 'false';
			}
		}
	}

	//get room types with hotel id
	public function getHotelSettings($Ajax='NO')
	{
		$hotel_id = $_REQUEST['hotel_id'];
		
		$room_types = $this->hotel_model->getHotelRooms($hotel_id);
		$room_attributes = $this->hotel_model->getHotelRoomAttributes($hotel_id);
		$room_meals_package = $this->hotel_model->getHotelRoomMealsPackage($hotel_id);

		
		if($Ajax=='NO'){
			return $hotel;
		}else{
	
			header('Content-Type: application/json');
			echo json_encode($hotel);
			
		}
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

		$data['driver_availability']=$this->driver_model->getDriversArray();
		$data['available_vehicles']=$this->trip_booking_model->getVehiclesArray();
		
		$data['title']="Tour Booking | ".PRODUCT_NAME;  
		$page='user-pages/tour-booking';
		$this->load_templates($page,$data);
	}

	public function manage_tour_booking()
	{
		if(isset($_REQUEST['trip-add'])){
			//validation
			$this->form_validation->set_rules('customer','Customer Name','trim|required|xss_clean');
			$this->form_validation->set_rules('pick_up_date','From Date','trim|required|xss_clean');
			$this->form_validation->set_rules('drop_date','To Date','trim|required|xss_clean');
			//trip data
			$tripData['id'] 		= $this->input->post('id');
			//check new customer or not
			$new_customer=$this->input->post('newcustomer');
			if($new_customer=='true'){
				$customer['name']=$this->input->post('customer');
				$customer['mobile']=$this->input->post('customer_contact');
				if($customer['name']!='')
				$tripData['customer_id']=$this->customers_model->addCustomer($customer,$login=true);
			}elseif($new_customer=='false'){
				$tripData['customer_id'] 	= $this->input->post('customer_id');
			}
			//check new guest or not
			$new_guest=$this->input->post('newguest');
			if($new_guest=='true'){
				$guest['name']=$this->input->post('guest_name');
				$guest['mobile']=$this->input->post('guest_contact');
				if($guest['name']!='')
				$tripData['guest_id']=$this->customers_model->addCustomer($guest,$login=true);
			}elseif($new_guest=='false'){
				$tripData['guest_id'] 		= $this->input->post('guest_id');
			}
			
			$tripData['booking_date'] 	= date('Y-m-d');
			$tripData['booking_time'] 	= date('H:i');
			$tripData['trip_source_id'] 	= $this->input->post('source_id');
			$tripData['source_details'] 	= $this->input->post('source_details');
			$tripData['source_contact'] 	= $this->input->post('source_contact');
			$tripData['pick_up_date'] 	= $this->input->post('pick_up_date');
			$tripData['pick_up_time'] 	= $this->input->post('pick_up_time');
			$tripData['drop_date'] 		= $this->input->post('drop_date');
			$tripData['drop_time'] 		= $this->input->post('drop_time');
			$tripData['pick_up_location'] 	= $this->input->post('pick_up');
			$tripData['pick_up_lat'] 	= $this->input->post('pick_up_lat');
			$tripData['pick_up_lng'] 	= $this->input->post('pick_up_lng');
			$tripData['drop_location'] 	= $this->input->post('drop');
			$tripData['drop_lat'] 		= $this->input->post('drop_lat');
			$tripData['drop_lng'] 		= $this->input->post('drop_lng');
			$tripData['pax'] 		= $this->input->post('pax');
			$tripData['markup_type'] 	= $this->input->post('markup_type');
			$tripData['markup_value'] 	= $this->input->post('markup_value');
			$tripData['remarks'] 		= $this->input->post('remarks');
					
			//trip Vehicle data
			$vehicleData['vehicle_id'] 		= $this->input->post('vehicle_id');
			$vehicleData['vehicle_ac_type_id'] 	= $this->input->post('vehicle_ac_type_id');
			$vehicleData['vehicle_type_id'] 	= $this->input->post('vehicle_type_id');
			$vehicleData['vehicle_model_id'] 	= $this->input->post('vehicle_model_id');
			$vehicleData['tariff_id'] 		= $this->input->post('tariff_id');//??
			$vehicleData['driver_id'] 		= $this->input->post('driver_id');
			$vehicleData['driver_language_id'] = $this->input->post('driver_language_id');
			$vehicleData['driver_language_proficiency_id'] = $this->input->post('driver_language_proficiency_id');//??
				
			//form input values
			$data = array_merge($tripData,$vehicleData);
			
			$data['advanced_option']='';
			if(isset($_REQUEST['vehicle_beacon_light_option_id'])){
				$vehicleData['vehicle_beacon_light_option_id']=$data['vehicle_beacon_light_option_id']=TRUE;
				$data['advanced_option']=TRUE;
			}else{
				$vehicleData['vehicle_beacon_light_option_id']='';
			}
			if(isset($_REQUEST['pluckcard'])){ 
				$vehicleData['pluckcard']=$data['pluckcard']=TRUE;
				$data['advanced_option']=TRUE;
			}else{
				$vehicleData['pluckcard']='';
			} 
			if(isset($_REQUEST['uniform'])){
				$vehicleData['uniform']=$data['uniform']=TRUE;
				$data['advanced_option']=TRUE;
			}else{
				$vehicleData['uniform']='';
			}
			
			if($vehicleData['vehicle_id']!=gINVALID && $vehicleData['driver_id']!=gINVALID){
			$tripData['trip_status_id'] 	= TRIP_STATUS_CONFIRMED;
			}else{
			$tripData['trip_status_id'] 	= TRIP_STATUS_PENDING;
			}
			
			$tripData['organisation_id'] 	= $this->session->userdata('organisation_id'); 
			$tripData['user_id'] 		= $this->session->userdata('id');   //echo "<pre>";print_r($tripData);echo "</pre>";exit;
		     
		     if($this->form_validation->run() != False) {
			
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
		    }else{
				echo "post data";exit;
				//$this->mysession->set('post',$data);
			}	
		}
	}

	//-----------------------------------------------------------------------------------------

	function set_up_trip_tabs($tab_active='t_tab'){
			
		$tabs['t_tab'] = array('class'=>'','tab_id'=>'tab_1','text'=>'Travel',
						'content_class'=>'tab-pane');
		$tabs['a_tab'] = array('class'=>'','tab_id'=>'tab_2','text'=>'Accommodation',
						'content_class'=>'tab-pane');
		$tabs['s_tab'] = array('class'=>'','tab_id'=>'tab_3','text'=>'Services',
						'content_class'=>'tab-pane');
		$tabs['v_tab'] = array('class'=>'','tab_id'=>'tab_4','text'=>'Vehicles',
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
	
	//get Available vehicles for tour booking
	public function getAvailableVehicles(){
		if($_REQUEST['vehicle_ac_type'] &&  $_REQUEST['vehicle_model']){
			$data['vehicle_ac_type']=$_REQUEST['vehicle_ac_type'];
			$data['vehicle_model']=$_REQUEST['vehicle_model'];
			$data['organisation_id']=$this->session->userdata('organisation_id');
			$data['trip_vehicle']=$_REQUEST['available_vehicle_id'];
			$res['data']=$this->trip_booking_model->selectAvailableVehicles($data);
			if($res['data']==false){
				echo 'false';
			}else{
				echo json_encode($res);
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
