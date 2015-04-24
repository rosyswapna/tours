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
		$this->load->model('vehicle_model');
		$this->load->model('package_model');
		$this->load->model('account_model');

		$this->load->library('tour_cart');
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
				$this->tour_booking($param2,$param3);
			}elseif($param1=='list'){
			        $this->show_tour_list();
			}
			elseif($param1 == 'addToCart'){
				$this->addToCart();
			}elseif($param1 == 'addToCartPackage'){ 
				$this->addToCartPackage();
			}elseif($param1 == 'deleteFromCart'){
				$this->deleteFromCart();
			}elseif($param1 == 'deleteFromCartPackage'){ 
				$this->deleteFromCartPackage();
			}elseif($param1 == 'getFromCart'){
				$this->getFromCart();
			}elseif($param1 == 'createCartFromPackage'){
				$this->createCartFromPackage();
			}elseif($param1 == 'getItineraryCount'){
				$this->getItineraryCount();
			}elseif($param1 == 'getItinerary'){
				$this->getItinerary();
			}elseif($param1 == 'save_cart'){
				$this->save_cart($param2);
			}elseif($param1 == 'getEditableTabValues'){
				$this->getEditableTabValues();
			}elseif($param1 == 'packages'){
				$this->showPackageList();
			}elseif($param1 == 'getHotelAttributes'){echo "hi";exit;
				$this->getHotelAttributes();
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

	

	//get room types with hotel id
	public function getHotelSettings($Ajax='NO')//not used yet
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

	

	public function tour_booking($param2='')
	{
		
		if($param2!='' && is_numeric($param2) && $param2 > 0){//valid trip id
			$tour_itms = $this->tour_model->getItineraryDataAll($param2);
			if($tour_itms){
				$this->tour_cart->create($tour_itms);				
			}
			$cart = $this->tour_cart->contents();
			$data['header'] = $this->set_tour_header($param2);

			$this->tour_cart->trip_id = $param2;
		}else{
			$this->tour_cart->trip_id = gINVALID;
			$cart = false;
			$this->tour_cart->destroy();
		}
	
		if($this->mysession->get('post_booking')){
			$data = $this->mysession->get('post_booking');
			$this->mysession->delete('post_booking');	
		}
		
		$tblArray=array('booking_sources','available_drivers','trip_models','drivers','vehicle_types',	
				'vehicle_models','vehicle_makes','vehicle_ac_types','vehicle_fuel_types',
				'vehicle_seating_capacity','vehicle_beacon_light_options','languages','payment_type',
				'customer_types','customer_groups','hotel_categories','trip-services','destinations','room_attributes','meals_options','services','vehicles','packages');
			
		foreach($tblArray as $table){
			$data[$table]=$this->user_model->getArray($table);
			
		}
		//print_r($data);exit;
		$data['days'] = array();
		for($i=1;$i<=10;$i++){
			$data['days'][$i] = $i;
		}
		
		$data['trip_id'] = $param2;
		$data['driver_availability']=$this->driver_model->getDriversArray();
		$data['available_vehicles']=$this->trip_booking_model->getVehiclesArray();
		$active_tab = 't_tab';
		$data['tabs'] = $this->set_up_trip_tabs($active_tab);
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
			$this->form_validation->set_rules('source_contact','Mobile Number','trim|regex_match[/^[0-9]{10}$/]|xss_clean');
			$this->form_validation->set_rules('customer_contact','Mobile Number','trim|regex_match[/^[0-9]{10}$/]|xss_clean');
			$this->form_validation->set_rules('guest_contact','Mobile Number','trim|regex_match[/^[0-9]{10}$/]|xss_clean');
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
			$tripData['package_id'] 	= $this->input->post('package_id');
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
			$tripData['driver_language_id'] = $this->input->post('driver_language_id');
			$tripData['driver_language_proficiency_id'] = $this->input->post('driver_language_proficiency_id');//??
					
			//trip Vehicle data
			$vehicleData['vehicle_id'] 		= $this->input->post('vehicle_id');
			$vehicleData['vehicle_ac_type_id'] 	= $this->input->post('vehicle_ac_type_id');
			$vehicleData['vehicle_type_id'] 	= $this->input->post('vehicle_type_id');
			$vehicleData['vehicle_model_id'] 	= $this->input->post('vehicle_model_id');
			$vehicleData['tariff_id'] 		= $this->input->post('tariff_id');//??
			$vehicleData['driver_id'] 		= $this->input->post('driver_id');
			
				
			//form input values
			$data = array_merge($tripData,$vehicleData);
			
			$data['advanced_option']='';
			if(isset($_REQUEST['vehicle_beacon_light_option_id'])){
				$tripData['vehicle_beacon_light_option_id']=$data['vehicle_beacon_light_option_id']=TRUE;
				$data['advanced_option']=TRUE;
			}else{
				$tripData['vehicle_beacon_light_option_id']='';
			}
			if(isset($_REQUEST['pluckcard'])){ 
				$tripData['pluckcard']=$data['pluckcard']=TRUE;
				$data['advanced_option']=TRUE;
			}else{
				$tripData['pluckcard']='';
			} 
			if(isset($_REQUEST['uniform'])){
				$tripData['uniform']=$data['uniform']=TRUE;
				$data['advanced_option']=TRUE;
			}else{
				$tripData['uniform']='';
			}
			
			if($vehicleData['vehicle_id']!='' && $vehicleData['driver_id']!=''){
				$tripData['trip_status_id'] 	= TRIP_STATUS_CONFIRMED;
			}else{
				$tripData['trip_status_id'] 	= TRIP_STATUS_PENDING;
			}
			
			$data['customer']		= $this->input->post('customer');
			$data['customer_contact']	= $this->input->post('customer_contact');
			$data['guest_name']		= $this->input->post('guest_name');
			$data['guest_contact']		= $this->input->post('guest_contact');
			
			$tripData['organisation_id'] 	= $this->session->userdata('organisation_id'); 
			$tripData['user_id'] 		= $this->session->userdata('id');   
			//echo "<pre>";print_r($tripData);echo "</pre>";exit;
			$err=True;
		    
			if($this->form_validation->run() != False ) {

				//-------------------get vehicle -----------------------------
	
				if(is_numeric($this->input->post('vehicle_id')) && $this->input->post('vehicle_id') > 0){ 
					$vehicleData['vehicle_id'] = $this->input->post('vehicle_id');
				}elseif($this->input->post('vehicle_id') == '' || $this->input->post('vehicle_id') == gINVALID){ 
					$vehicleData['vehicle_id'] = gINVALID;
				}else{
					$v_details['vehicle_model_id']=$vehicleData['vehicle_model_id'];
					$v_details['vehicle_ac_type_id']=$vehicleData['vehicle_ac_type_id'];
					$v_details['registration_number']=$this->input->post('vehicle_id');
					$exp_match=True; 
					if (!preg_match('/^[A-Z]{2}[ -][0-9]{1,2}(?: [A-Z])?(?: [A-Z]*)? [0-9]{4}$/', $this->input->post('vehicle_id')) ){ 
					$exp_match=False;
					$err=False;
					$this->mysession->set('Err_reg_num','Invalid Registration Number');
					} 
					//$this->form_validation->set_rules('available_vehicle','Registration Number','trim|required|xss_clean|regex_match[/^[A-Z]{2}[ -][0-9]{1,2}(?: [A-Z])?(?: [A-Z]*)? [0-9]{4}$/]');
					if($vehicleData['vehicle_model_id'] ==gINVALID){
						 $err=False;
						 $this->mysession->set('Err_Vmodel','Choose Model Type');
					}
					if($vehicleData['vehicle_ac_type_id'] ==gINVALID){
						 $err=False;
						 $this->mysession->set('Err_V_Ac','Choose AC Type');
					}
					if($vehicleData['vehicle_model_id'] !=gINVALID  && $vehicleData['vehicle_ac_type_id'] !=gINVALID && $exp_match==True){
					$vehicleData['vehicle_id'] = $this->vehicle_model->addVehicleFromTripBooking($v_details);
					}else{
					$vehicleData['vehicle_id']='';
					}
				}

				//----------------------get driver--------------------------------------------
			
				if(is_numeric($this->input->post('driver_id')) && $this->input->post('driver_id') > 0){

					$vehicleData['driver_id'] = $this->input->post('driver_id');

				}else if($this->input->post('driver_id') == '' || $this->input->post('driver_id') == gINVALID){
					$vehicleData['driver_id'] = gINVALID;
				}else{ 
					 $vehicleData['driver_id'] = $this->driver_model->addDriverFromTripBooking($this->input->post('driver_id'));
				}

				//-----------------------------------------------------------------------

				if($err==True){
					$vehicleData = $this->checkVehicleData($vehicleData);
					$cartFromPCk = $this->tour_cart->contents();
					//echo "<pre>";print_r($cartFromPCk);echo "</pre>";exit;
					$trip_id = $this->settings_model->addValues_returnId('trips',$tripData); 
					if($trip_id && $trip_id > 0){//trip added
						$package_id = $this->input->post('package_id');
						$pck_itms = $this->tour_cart->total_itineraries();
						if((is_numeric($package_id ) && $package_id > 0) && $pck_itms > 0){

							//get cart items generated with package
							$cartFromPCk = $this->tour_cart->contents();
							
							//make package cart to tour cart
							$cart = $this->pckCart_to_tourCart($cartFromPCk,$trip_id);
							//echo "<pre>";print_r($cart);echo "</pre>";exit;
							if($cart){
								$itinerary = $this->tour_model->save_tour_cart($cart,$trip_id);
							}
							
						}else{
							//build itinerary
							$itinerary = $this->tour_model->addItineraries($trip_id);
						}
						if($itinerary && $vehicleData){
							$this->tour_model->resetTripItineraryData('trip_vehicles',$trip_id);
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
			$this->mysession->set('post_booking',$data);
				
		}
		redirect(base_url().'front-desk/tour/booking/');
	}


	//check vehicle data valid to insert into trip vehicles
	function checkVehicleData($vehicleData){
		foreach($vehicleData as $data){
			if(is_numeric($data) && $data > 0){
				return $vehicleData;
			}
		}
		return false;
	}


	//package cart to tour cart converting function
	public function pckCart_to_tourCart($cartFromPCk,$trip_id){

		//echo "<pre>";print_r($cartFromPCk);echo "</pre>";exit;
		$buildItrs = $this->tour_model->buildItinerary($trip_id);
		$tourCart = array();
		if($buildItrs!= false && count($cartFromPCk) == count($buildItrs)){
			$i=0;//index throungh buildItries
			foreach($cartFromPCk as $dayno=>$itryData){
				$tour_dataArray = array();
				foreach($itryData as $tbl=>$dataArray){
					foreach($dataArray as $data){
						unset($data['itinerary_id']);
						$data['id'] = gINVALID;
						$tour_dataArray[$tbl][] = $data;
						
					}
					
				}
				$tourCart[$buildItrs[$i]] = $tour_dataArray;
				$i++;
			}
		}	
		//echo "<pre>";print_r($tourCart);echo "</pre>";exit;	
		
		return $tourCart;
	}
	
	
	public function show_tour_list(){
		if($this->session_check()==true) { 
			$data['trips']=$this->tour_model->getTrips();
			$data['title']="Tour List | ".PRODUCT_NAME;  
			$page='user-pages/trip-list';
			$this->load_templates($page,$data);
		}else{
				$this->notAuthorized();
		}
	}
	//-----------------------------------------------------------------------------------------

	function set_tour_header($trip_id=''){
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

	function getItinerary(){
		$itineraryId = -1;
		if(isset($_REQUEST['itmDate']) && isset($_REQUEST['trip_id'])){
			
			$itinerary = $this->tour_model->getItineraryWithDate($_REQUEST['itmDate'],$_REQUEST['trip_id']);

			if($itinerary){
				echo json_encode($itinerary);
			}else{
				echo 'false';
			}
		}else{
			echo 'false';
		}
		
	}



	function addToCart()//from ajax call
	{
		if(isset($_REQUEST['table'])&& isset($_REQUEST['_date']) && isset($_REQUEST['trip_id'])){
			$tble = $_REQUEST['table'];
			$fields = $_REQUEST;
			$itinerary = $fields['_date'];
			$index = $fields['row_id'];
			array_shift($fields);//pop first element(url data from ajax call)
			unset($fields['table']);
			unset($fields['_date']);
			unset($fields['trip_id']);
			unset($fields['row_id']);
			//echo "<pre>";print_r($fields);echo "</pre>";exit;
			if(is_numeric($fields['id'])&& ($fields['id']>0)){
			$this->tour_cart->update($tble,$fields,$itinerary,$index);
			}else{
			$data[$tble] = $fields;
			$this->tour_cart->insert($data,$itinerary);
			}
				
		}
		$cart = $this->tour_cart->contents();
		$this->build_itinerary_data($cart,$ajax = 'YES');
	}

	//package save
	function addToCartPackage(){

		//create cart
		if($this->tour_cart->total_itineraries() == 0){
			$this->tour_cart->create();
		}
		//print_r($_REQUEST);exit;
		if(isset($_REQUEST['table'])&& isset($_REQUEST['_date'])){
			$tble = $_REQUEST['table'];
			$fields = $_REQUEST;
			$itinerary = $fields['_date'];
			$index = $fields['row_id'];
			array_shift($fields);//pop first element(url data from ajax call)
			unset($fields['table']);
			unset($fields['_date']);
			unset($fields['row_id']);
			
			//echo "<pre>";print_r($fields);echo "</pre>";exit;
			if(is_numeric($fields['id'])&& ($fields['id']>0)){
			$this->tour_cart->update($tble,$fields,$itinerary,$index);
			}else{ 
			$data[$tble] = $fields; 
			$this->tour_cart->insert($data,$itinerary);
			}
		}

		$cart = $this->tour_cart->contents();
		//echo "<pre>";print_r($cart);echo "</pre>";exit;
		$this->build_itinerary_data($cart,$ajax = 'YES');
		
	}
	
	//package delete
	function deleteFromCartPackage(){
		if(isset($_REQUEST['table'])&& isset($_REQUEST['_date'])){
			$tble = $_REQUEST['table'];
			$fields = $_REQUEST;
			$itinerary = $fields['_date'];
			$index = $fields['row_id'];
			array_shift($fields);//pop first element(url data from ajax call)
			unset($fields['table']);
			unset($fields['_date']);
			unset($fields['row_id']);
			
			//echo "<pre>";print_r($fields);echo "</pre>";exit;
			
				$this->tour_cart->delete($itinerary,$tble,$index,$fields['id']);
			
		}

		$cart = $this->tour_cart->contents();
		//echo "<pre>";print_r($cart);echo "</pre>";exit;
		$this->build_itinerary_data($cart,$ajax = 'YES');
	
	}

	function save_cart($trip_id=gINVALID)
	{ 
		$cart =$this->tour_cart;
		if(isset($_REQUEST['save-itry'])){
			
			if(is_numeric($trip_id) && $trip_id > 0){
				//SAVE ITINERARY DATA
				$this->tour_model->save_tour_cart($cart,$trip_id);
				$Msg = "Tour Updated Successfully";
				
			}else{
				//SAVE AS PACKAGE
				$package = $_REQUEST['hid_package'];
				$this->package_model->save_package($cart,$package);
				$Msg = "Package Updated Successfully";
			}
		}

		$this->session->set_userdata(array('dbSuccess'=>$Msg)); 
		$this->session->set_userdata(array('dbError'=>''));
		redirect(base_url().'front-desk/tour/booking/'.$trip_id);
	}

	function getFromCart(){
		$cart = $this->tour_cart->contents();
		$this->build_itinerary_data($cart,$ajax = 'YES');
	}

	function createCartFromPackage(){
		$this->tour_cart->destroy();
		if(isset($_REQUEST['package_id']) && is_numeric($_REQUEST['package_id']) && $_REQUEST['package_id'] > 0)
		{
			$package = $this->package_model->getPackage($_REQUEST['package_id']);
			//echo "<pre>";print_r($package);echo "</pre>";exit;
			if($package){
				$this->tour_cart->create($package);
				$cart = $this->tour_cart->contents();//echo "<pre>";print_r($cart);echo "</pre>";exit;
				$this->build_itinerary_data($cart,$ajax = 'YES');
			}
		}else{
			echo 'false';
		}
	}
	
	function getItineraryCount()
	{
		echo $this->tour_cart->total_itineraries();
	}


	function build_itinerary_data($cart,$ajax = 'NO'){

		//echo "<pre>";print_r($cart);echo "</pre>";exit;
		if($cart){
			$trip_id = $this->tour_cart->trip_id;
			if(is_numeric($trip_id) && $trip_id > 0){
				$firstTH = "Date";
			}else{
				$firstTH = "Day";
				
			}
			$tableData['th'] = array(
					array('label'=>$firstTH,'attr'=>'width="20%"'),
					array('label'=>'Particulars','attr'=>'width="20%"'),
					array('label'=>'Accommodation','attr'=>'width="20%"'),
					array('label'=>'Service','attr'=>'width="20%"'),
					array('label'=>'Vehicle','attr'=>'width="20%"'),
					array('label'=>'Others','attr'=>'width="20%"'),
					);
			$tableData['tr'] = array();
			foreach($cart as $itinerary=>$item){
				

				$destinations = array();
				if(isset($item['trip_destinations'])){
					foreach($item['trip_destinations'] as $dataArry_index=>$destination){
					//$destinations[]=array($destination['id'],$destination['destination_id']);
					$destinations[]=array($dataArry_index,$destination['destination_id']);
						//array_push($destinations,$destination['destination_id']);
					}
					$active_tab = 't_tab';
					$destinations = $this->tour_model->getItineraryDataLink('destinations','name',$destinations,$active_tab,$itinerary);
				}

				$hotels = array();
				if(isset($item['trip_accommodation'])){
					foreach($item['trip_accommodation'] as $dataArry_index=>$accommodation){
					$hotels[]=array($dataArry_index,$accommodation['hotel_id']);
						//array_push($hotels,$accommodation['hotel_id']);
					}
					$active_tab = 'a_tab';
					$hotels = $this->tour_model->getItineraryDataLink('hotels','name',$hotels,$active_tab,$itinerary);
				}

				$services = array();
				if(isset($item['trip_services'])){
					foreach($item['trip_services'] as $dataArry_index=>$service){
					$services[]=array($dataArry_index,$service['service_id']);
						//array_push($services,$service['service_id']);
					}
					$active_tab = 's_tab';
					$services = $this->tour_model->getItineraryDataLink('services','name',$services,$active_tab,$itinerary);
				}
			
				$vehicles = array();
				if(isset($item['trip_vehicles'])){
					foreach($item['trip_vehicles'] as $dataArry_index=>$vehicle){
					$vehicles[]=array($dataArry_index,$vehicle['vehicle_id'],$vehicle['vehicle_type_id']); 
					//$vehicle_type=$this->package_model->getVehicleType($vehicle['vehicle_type_id']);
					
						//array_push($vehicles,$vehicle['vehicle_id']);
					}
					
					$active_tab = 'v_tab';//print_r($vehicles);exit;
					$vehicles = $this->tour_model->getItineraryDataLink('vehicles','registration_number',$vehicles,$active_tab,$itinerary);
					//echo "<pre>";print_r($vehicles);echo "</pre>";exit;
				}

				$tr = array($itinerary,
					implode(',',$destinations),
					implode(',',$hotels),
					implode(',',$services),
					implode(',',$vehicles),
					''
					);
				array_push($tableData['tr'],$tr);
			} 

			//echo "<pre>";print_r($tableData);echo "</pre>";exit;

			if($ajax == 'YES'){
				echo json_encode($tableData);
			}else{
				return $tableData;
			}
			
		}else{
			if($ajax == 'YES'){			
				echo 'false';
			}else{
				return false;
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


	function getRoomAttributesNMealsPackage()//not completed
	{	
		
		
	}



	public function getEditableTabValues(){
		if((isset($_REQUEST['row_id']))&& (isset($_REQUEST['table'])) && (isset($_REQUEST['itinerary']))){
			
			$editable_values=$this->tour_cart->select($_REQUEST['itinerary'],$_REQUEST['table'],$_REQUEST['row_id']);
			//echo "<pre>";print_r($editable_values);echo "</pre>";exit;
			echo json_encode($editable_values);
		}else{
			return false;
		}
	}
	public function  getHotelAttributes(){ 
		if($_REQUEST['hotel_id']!=''){ 
			$hotel_attributes=$this->tour_model->getHotelAttributes($_REQUEST['hotel_id']);
			echo json_encode($hotel_attributes);
	}else{
			return false;
		}
		
	}

	public function showPackageList(){
		if($this->session_check()==true) { 
			$data['package_lists']=$this->package_model->getAllPackages();
			$data['title']="Package List| ".PRODUCT_NAME;  
			$page='user-pages/packageList';
			$this->load_templates($page,$data);
		}else{
			$this->notAuthorized();
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
