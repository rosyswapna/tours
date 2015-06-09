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
		$this->load->model('tarrif_model');

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
				$this->save_cart($param2,$param3);
			}elseif($param1 == 'getEditableTabValues'){
				$this->getEditableTabValues();
			}elseif($param1 == 'packages'){
				$this->showPackageList($param2);
			}elseif($param1 == 'getRoughEstimate'){
				$this->getRoughEstimate();
			}elseif($param1 == 'getHotelAttributes'){
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

	

	public function tour_booking($param2='',$param3='')
	{	
		
	
		if($param2=='PA' || $param2=='TA'){
			
			if($param2=='PA'&& $param3=='' ){ 
				$data['flag']='PA';
			}elseif($param2=='TA'&& $param3==''){
				$data['flag']='TA';
			}
		
			$data['package_id'] = gINVALID;
			//$data['itrTable'] = false;
		//if($param2!='' && is_numeric($param2) && $param2 > 0){//valid trip id
		if($param2=='TA' && is_numeric($param3) && $param3 > 0 ){
			$tour_itms = $this->tour_model->getItineraryDataAll($param3);
			if($tour_itms){//echo "<pre>";print_r($tour_itms);echo "</pre>";exit;
				$this->tour_cart->create($tour_itms);				
			}
			$cart = $this->tour_cart->contents();//echo "<pre>";print_r($cart);echo "</pre>";exit;
			$data['header'] = $this->set_tour_header($param3);
			$data['flag']='TE';
			$this->tour_cart->trip_id = $param3;
		//}elseif($param2 == 0 && is_numeric($param3) && $param3 > 0){
		}elseif($param2=='PA' && is_numeric($param3) && $param3 > 0 ){
			$data['package_id'] = $param3;
			$param2 = '';
			$data['itrTable'] = $this->createCartFromPackage($ajax = 'NO',$param3);
			$data['flag']='PE';
			//echo "<pre>";print_r($data['itrTable']);echo "</pre>";exit;
		}else{
			$this->tour_cart->trip_id = gINVALID;
			$cart = false;
			$this->tour_cart->destroy();
		}
	
		if($this->mysession->get('post_booking')){
			$data['header'] = $this->mysession->get('post_booking');
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
		
		$data['trip_id'] = $param3;
		$data['driver_availability']=$this->driver_model->getDriversArray();
		$data['available_vehicles']=$this->trip_booking_model->getVehiclesArray();
		$active_tab = 't_tab';
		$data['tabs'] = $this->set_up_trip_tabs($active_tab);
		$data['title']="Tour Booking | ".PRODUCT_NAME;  
		$page='user-pages/tour-booking';
		$this->load_templates($page,$data);
		}
	}
	
	
	
	public function manage_tour_booking()
	{ //echo '<pre>';print_r($_REQUEST);echo '</pre>';exit;
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
			
			$customer['name']=$this->input->post('customer');
			$customer['mobile']=$this->input->post('customer_contact');
			$guest['name']=$this->input->post('guest_name');
			$guest['mobile']=$this->input->post('guest_contact');
			$tripData['customer_id']=gINVALID;
			$tripData['guest_id']=gINVALID;
			
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
			
			$data['customer_name']		= $this->input->post('customer');
			$data['customer_mobile']	= $this->input->post('customer_contact');
			$data['guest_name']		= $this->input->post('guest_name');
			$data['guest_mobile']		= $this->input->post('guest_contact');
			$data['vehicle_ac_type_id']		= $this->input->post('vehicle_ac_type_id');
			$data['vehicle_model_id']		= $this->input->post('vehicle_model_id');
			$data['vehicle_id']		= $this->input->post('vehicle_id');
			$data['driver_id']		= $this->input->post('driver_id');
			$data['customer_id']		= $this->input->post('customer_id');
			$data['guest_id']		= $this->input->post('guest_id');
			$data['package_id']		= $this->input->post('package_id');
			
			$tripData['organisation_id'] 	= $this->session->userdata('organisation_id'); 
			$tripData['user_id'] 		= $this->session->userdata('id');   
			//echo "<pre>";print_r($tripData);echo "</pre>";exit;
			$err=True;
		    
			if($this->form_validation->run() != False ) {
			
			//check new customer or not
			if($customer['name']!='' ||$customer['mobile']!=''){
				$customer_id=$this->input->post('customer_id');//echo $customer_id;
				if($customer_id<=0){
					$tripData['customer_id']=$this->customers_model->addCustomer($customer,$login=true);
				}else{
					$tripData['customer_id']=$customer_id;
					$this->customers_model->updateCustomers($customer,$customer_id); 
					
				}
			}
			//check new guest or not
			if($guest['name']!='' ||$guest['mobile']!=''){
				$guest_id=$this->input->post('guest_id');
				if($guest_id<=0){
					$tripData['guest_id']=$this->customers_model->addCustomer($guest,$login=true);
				}else{
					$tripData['guest_id']=$guest_id;
					$this->customers_model->updateCustomers($guest,$guest_id);
				}
			}

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
					if($_REQUEST['trip_id']==' '||$_REQUEST['trip_id']<=0){
						$cartFromPCk = $this->tour_cart->contents();
						//echo "<pre>";print_r($cartFromPCk);echo "</pre>";exit;
						
						$trip_id = $this->settings_model->addValues_returnId('trips',$tripData); 
						if($trip_id && $trip_id > 0){//trip added 
						
							$package_id = $this->input->post('package_id');
							$pck_itms = $this->tour_cart->total_itineraries();
							if((is_numeric($package_id ) && $package_id > 0) && $pck_itms > 0){
								
								$itinerary = $this->tour_model->save_tour_cart($this->tour_cart,$trip_id,true);
								
								
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
							redirect(base_url().'front-desk/tour/booking/TA/'.$trip_id);
						}
					}elseif($_REQUEST['trip_id']>0){
						unset($tripData['id']);
						$result = $this->settings_model->updateValues('trips',$tripData,$_REQUEST['trip_id']);
						
						$this->tour_model->resetTripItineraryData('trip_vehicles',$_REQUEST['trip_id']);
						$tripVehicleUpdate = $this->tour_model->addTripVehicles($vehicleData,$_REQUEST['trip_id']);
						if($result){
							$this->session->set_userdata(array('dbSuccess'=>'Trip Updated successfully!')); 
							$this->session->set_userdata(array('dbError'=>''));
							redirect(base_url().'front-desk/tour/booking/TA/'.$_REQUEST['trip_id']);
						}
					
					}
					
				}else{
						$this->session->set_userdata(array('dbSuccess'=>'')); 
						$this->session->set_userdata(array('dbError'=>'Trip booking Failed'));
						redirect(base_url().'front-desk/tour/booking/TA');
					}
			}
			//echo "<pre>";print_r($data);echo "</pre>";exit;
			if($_REQUEST['trip_id']==' '||$_REQUEST['trip_id']<=0){
				$this->mysession->set('post_booking',$data);
				redirect(base_url().'front-desk/tour/booking/TA');
			}else{
				$this->mysession->set('post_booking',$data);
				redirect(base_url().'front-desk/tour/booking/TA/'.$_REQUEST['trip_id']);
			}
			
				
		}
		redirect(base_url().'front-desk/tour/booking/TA');
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


	
	
	
	public function show_tour_list(){
		if($this->session_check()==true) { 
			$tblArray=array('vehicles','drivers','trip_statuses','customer_groups');
			
		foreach($tblArray as $table){
			$data[$table]=$this->user_model->getArray($table);
			
		}
			$data['status_class']=array(TRIP_STATUS_PENDING=>'label-warning',TRIP_STATUS_CONFIRMED=>'label-success',TRIP_STATUS_CANCELLED=>'label-danger',TRIP_STATUS_CUSTOMER_CANCELLED=>'label-danger',TRIP_STATUS_ON_TRIP=>'label-primary',TRIP_STATUS_TRIP_COMPLETED=>'label-success',TRIP_STATUS_TRIP_PAYED=>'label-info',TRIP_STATUS_TRIP_BILLED=>'label-success');
			$data['trips']=$this->tour_model->getTrips();//echo "<pre>";print_r($data['trips']);echo "</pre>";exit;
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
			if($trip['vehicle_beacon_light_option_id']>0 ||$trip['pluckcard']>0 ||$trip['uniform']>0||$trip['driver_language_id']>0){
			$trip['advanced_option']=true;
			}else{
			$trip['advanced_option']=false;
			}
			$vehicle_detail=$this->tour_model->getTripVehicles($trip_id);
			if($vehicle_detail){$vehicleArr=array();
			$vehicleArr=$vehicle_detail[0];//echo "<pre>";print_r($vehicleArr);echo "</pre>";exit;
			$trip['vehicle_ac_type_id']=$vehicleArr['vehicle_ac_type_id'];
			$trip['vehicle_model_id']=$vehicleArr['vehicle_model_id'];;
			$trip['vehicle_id']=$vehicleArr['vehicle_id'];;
			$trip['driver_id']=$vehicleArr['driver_id'];
			}else{
			
			$trip['vehicle_ac_type_id']=-1;
			$trip['vehicle_model_id']=-1;
			$trip['vehicle_id']=-1;
			$trip['driver_id']=-1;
			}
				return $trip;
			}else{
				redirect(base_url().'front-desk/tour/booking/TA');
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
		if(isset($_REQUEST['post'])){
			$dataArray=$_REQUEST['post'];
			$tble = $dataArray['table'];
			$fields = $dataArray;
			$itinerary = $dataArray['_date'];
			$index = $dataArray['row_id'];
			$trip_id=$dataArray['trip_id'];
			
			if(isset($dataArray['to_date']) && $dataArray['to_date']!='' ){
				$to_date = $dataArray['to_date'];
				$dateArr=array();
				$begin = new DateTime($itinerary);
				$end = new DateTime($to_date);

				$daterange = new DatePeriod($begin, new DateInterval('P1D'), $end);// P1D means  period of one day
					
					foreach($daterange as $date){ 
					    $dateArr[]= $date->format("Y-m-d");
					    }
			}
			array_shift($fields);//pop first element(url data from ajax call)
			unset($fields['table']);
			unset($fields['_date']);
			unset($fields['to_date']);
			unset($fields['trip_id']);
			unset($fields['row_id']);
			
			
			if(is_numeric($fields['id'])&& ($fields['id']>0)){
				$this->tour_cart->update($tble,$fields,$itinerary,$index);
			}else{ 
				$data[$tble] = $fields; 
				if(!empty($dateArr)){
				$dateArr[]=$to_date;
					foreach($dateArr as $itinerary){
						$this->tour_cart->insert($data,$itinerary);
					}
				}else{
				$this->tour_cart->insert($data,$itinerary);
				}
			}
		}
		//echo "<pre>";print_r($this->tour_cart->estimate());echo "</pre>";exit;
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
		//if(isset($_REQUEST['table'])&& isset($_REQUEST['_date'])){
		if(isset($_REQUEST['post'])){
			$dataArray=$_REQUEST['post'];
			$tble = $dataArray['table'];
			$fields = $dataArray;
			$itinerary = $dataArray['_date'];
			if(isset($dataArray['to_date']) && $dataArray['to_date']!='' ){
				$to_date = $dataArray['to_date'];
				$dayArr=array();
				for($i=$itinerary;$i<=$to_date;$i++){
				$dayArr[]=$i;
				}
			}
			$index = $dataArray['row_id'];
			array_shift($fields);//pop first element(url data from ajax call)
			unset($fields['table']);
			unset($fields['_date']);
			unset($fields['row_id']);
			unset($fields['to_date']);
			
			//echo "<pre>";print_r($dataArray);echo "</pre>";exit;
			if($index>=0){ 
				$this->tour_cart->update($tble,$fields,$itinerary,$index);
			}else{
				$data[$tble] = $fields; 
				if(!empty($dayArr)){
					foreach ($dayArr as $itinerary){
						$this->tour_cart->insert($data,$itinerary);
					}
				}else{
					$this->tour_cart->insert($data,$itinerary);
				}
				//print_r($this->tour_cart->estimate());exit;
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

	
	
	function save_cart($pt_flag,$pt_id)
	{ //echo $trip_id;exit;
		$cart =$this->tour_cart;
		if(isset($_REQUEST['save-itry'])){
			
			//if(is_numeric($trip_id) && $trip_id > 0){
			if($pt_flag=='TA' && $pt_id>0){
				
				//SAVE ITINERARY DATA
				$trip_id=$pt_id;
				$this->tour_model->save_tour_cart($cart,$trip_id);
				$Msg = "Tour Updated Successfully";
				$this->session->set_userdata(array('dbSuccess'=>$Msg)); 
				$this->session->set_userdata(array('dbError'=>''));
				redirect(base_url().'front-desk/tour/booking/TA/'.$trip_id);
				
			}else{
				//SAVE AS PACKAGE
				//$package_id=$pt_id;
				$package = $_REQUEST['hid_package'];
				$package_id=$this->package_model->save_package($cart,$package);
				$Msg = "Package Updated Successfully";
				$this->session->set_userdata(array('dbSuccess'=>$Msg)); 
				$this->session->set_userdata(array('dbError'=>''));
				redirect(base_url().'front-desk/tour/booking/PA/'.$package_id);
			}
		}

		
		
	}
	

	function getFromCart(){
		$cart = $this->tour_cart->contents(); //echo '<pre>';print_r($cart);echo '</pre>';exit;
		$this->build_itinerary_data($cart,$ajax = 'YES');
	}

	function createCartFromPackage($ajax = 'YES',$package_id=gINVALID){
		$this->tour_cart->destroy();
		if(isset($_REQUEST['package_id']))
			$package_id = $_REQUEST['package_id'];
		if(is_numeric($package_id) && $package_id > 0)
		{
			$package = $this->package_model->getPackage($package_id);
			//echo "<pre>";print_r($package);echo "</pre>";exit;
			if($package){
				$this->tour_cart->create($package);
				$cart = $this->tour_cart->contents();
				//echo "<pre>";print_r($cart);echo "</pre>";exit;
				$tableData = $this->build_itinerary_data($cart,$ajax);
				if($ajax == 'NO'){
					return $tableData;
				}
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

		
		if($cart){
			$trip_id = $this->tour_cart->trip_id;
			if(is_numeric($trip_id) && $trip_id > 0){
				$firstTH = "Date";
			}else{
				$firstTH = "Day";
				
			}
			$tableData['th'] = array(
					array('label'=>$firstTH,'attr'=>'width="5%"'),
					array('label'=>'Travel','attr'=>'width="40%"'),
					array('label'=>'Accommodation','attr'=>'width="15%"'),
					array('label'=>'Service','attr'=>'width="15%"'),
					array('label'=>'Vehicle','attr'=>'width="15%"'),
					array('label'=>'Remarks','attr'=>'width="10%"'),
					);
			$tableData['tr'] = array();
			foreach($cart as $itinerary=>$item){
				
				

				$destinations = array();
				if(isset($item['trip_destinations'])){ 
					foreach($item['trip_destinations'] as $dataArry_index=>$destination){  
					$description= $destination['description'];
					//$destinations[]=array($destination['id'],$destination['destination_id']);
					$destinations[]=array($dataArry_index,$destination['destination_id'],$description);
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
					$active_tab = 'v_tab';
					
					
					if($vehicle['vehicle_id']>0){
						$vehicles[]=array($dataArry_index,$vehicle['vehicle_id']); 
						$select='registration_number';
						$table='vehicles';
					}elseif($vehicle['vehicle_type_id']>0){
					
						$vehicles[]=array($dataArry_index,$vehicle['vehicle_type_id']); 
						$select='name';
						$table='vehicle_types';
					}
					
						//array_push($vehicles,$vehicle['vehicle_id']);
					}
					
					//print_r($vehicles);exit;
					$vehicles = $this->tour_model->getItineraryDataLink($table,$select,$vehicles,$active_tab,$itinerary);
					//echo "<pre>";print_r($vehicles);echo "</pre>";exit;
				}

				$tr = array($itinerary,
					implode('-',$destinations),
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
			//print_r($editable_values);exit;
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

	public function showPackageList($param2){ 
		if($this->session_check()==true) { 
			
			if($param2=='1'){ $param2='0'; }
			if($param2==''){ $this->mysession->delete('condition');$param2='0'; }
			
			$tbl	= " ";$like_arry = $where_arry = array();
			$baseurl = base_url().'front-desk/tour/packages';
			$per_page = 30;
			$data['slno_per_page'] = 10;
			$uriseg ='4';
			$data['urlseg'] = 4;
			
			
			$data['package_name']	= $like_arry['p_name'] 		= '';
			$data['status_id']	= $where_arry['status_id'] 	= '';
			
			if(isset($_REQUEST['package_search'])){
				
				$where_arry['status_id']=$_REQUEST['status_id'];
				$like_arry['p_name']=$_REQUEST['package_name'];	
				$this->mysession->set('condition',array("where"=>$where_arry,"like"=>$like_arry));
			}
			
			
			
			if($this->mysession->get('condition')!=''){ 

				$condition=$this->mysession->get('condition');

				$data['status_id']=$condition['where']['status_id'];
				$data['package_name']=$condition['like']['p_name'];
			}else{
				$condition = false;
			}
			$tbl_arry=array('statuses');
			for ($i=0;$i<count($tbl_arry);$i++){
				$data[$tbl_arry[$i]] = $this->user_model->getArray($tbl_arry[$i]);
			}
			
			$qry = $this->package_model->get_sql_for_packages($condition);
			$paginations=$this->mypage->paging($tbl='',$per_page,$param2,$baseurl,$uriseg,$custom='yes',$qry);
			$data['page_links']=$paginations['page_links'];
			$data['packages']=$paginations['values'];
				if(empty($data['packages'])){
					$data['result']="No Results Found !";
				}
			$data['title']="Package List| ".PRODUCT_NAME;  
			$page='user-pages/packageList';
			$this->load_templates($page,$data);
					
		}else{
			$this->notAuthorized();
		}
	
	}
	
	public function getRoughEstimate(){ 
		$cart = $this->tour_cart->estimate();
			//echo "<pre>";print_r($cart);echo "</pre>";exit;
			$str=array();$tr=array();$acc_tr=array();$estimate_tr=array();$travel_tr=array();$model_id=gINVALID;$vehicle_id=gINVALID; $estimate_amt=0;$total_attr_tariff=0;$estimate_total=0;
			if(isset($cart['accommodation'])){
				$accommodation=$cart['accommodation'];
				//echo "<pre>";print_r($accommodation);echo "</pre>";exit;
				foreach($accommodation as $item){
					$no_of_days=count($item['from_cart']);
					$tax=4.955;
					
					$hotel_name=$this->settings_model->getValuebyId($item['hotel_id'],'hotels','name');
					$hotel_attr=$this->hotel_model->getHotelProfile($item['hotel_id']);
					$destination=$this->settings_model->getValuebyId($hotel_attr['destination_id'],'destinations','name');
					$room_type=$this->settings_model->getValuebyId($item['room_type_id'],'room_types','name');
					$_date 		= date("Y-m-d");
					$season_ids = $this->tour_model->getSeasonIdssWithDate($_date);
						if($season_ids){
							$season_id = $season_ids[0];
						}else{
							$season_id = gINVALID;
						}
					
					$filter=array('hotel_id'=>$item['hotel_id'],'room_type_id'=>$item['room_type_id'],'season_id'=>$season_id);
					$room_tariff= $this->hotel_model->getHotelRoomTariff($filter);
					$a_particular="Accommodation : ".$hotel_name.", ".$destination;
						
					$total_rooms=0;$total_meals_qty=0;
						$attr_array=array();$meals_array=array();
						foreach($item['from_cart'] as $row){ //echo "<pre>";print_r($row);echo "</pre>";exit;
							$cart_contents = $this->tour_cart->contents();
							$dataArray=$cart_contents[$row['itinerary']][$row['table']][$row['index']];
							//echo "<pre>";print_r($cart_contents);echo "</pre>";exit;
							$room_qty=$cart_contents[$row['itinerary']][$row['table']][$row['index']]['room_quantity'];
								if($room_qty=='' || $room_qty==0){
									$room_quantity=1;
								}elseif($room_qty>0){
									$room_quantity=$room_qty;
								}
							$total_rooms+=$room_quantity;
							if(!is_array($dataArray['room_attributes'])&& $dataArray['room_attributes']!=''){
								$dataArray['room_attributes']=unserialize($dataArray['room_attributes']);
							}
							if(!empty($dataArray['room_attributes'])){ 
							
							//estimate calculation for all attributes
							if($dataArray['room_attributes'][0]==''){
								$all_attr=$this->user_model->getArray('room_attributes');
								foreach ($all_attr as $idAttr=>$Attribute){
									$dataArray['room_attributes'][]=$idAttr;
								}
							}
							
								foreach($dataArray['room_attributes'] as $room_attribute){ 
									$condition=array('hotel_id'=>$item['hotel_id'],'season_id'=>$season_id,
											'attribute_id'=>$room_attribute);
									$attr = $this->hotel_model->getAttributeTariff($condition);
									if($attr){
										$attr_tariff=$attr->amount;
										$attr_name=$attr->attr_name;

								//echo $attr_tariff." ".$attr_name;exit;
										if(array_key_exists($room_attribute,$attr_array)){
											
											$attr_array[$room_attribute][2]++;
											$attr_array[$room_attribute][3]+=$room_quantity;
										}else{
											
											$attr_array[$room_attribute]=array($attr_name,$attr_tariff,1,$room_quantity);
										}
									}
									
								}
							}
							if(!is_array($dataArray['meals_package'])&& $dataArray['meals_package']!=''){
								$dataArray['meals_package']=unserialize($dataArray['meals_package']);
							}
							if(!empty($dataArray['meals_package'])){
								
								foreach($dataArray['meals_package'] as $meals_id){
								//echo $meals_id;exit;
									$meals_qty=$cart_contents[$row['itinerary']][$row['table']][$row['index']]['meals_quantity'];
									if($meals_id>0 ){
										if($meals_qty>0){
											$meals_quantity=$meals_qty;
										}elseif($meals_qty=='' || $meals_qty==0){
											$meals_quantity=1;
										}
										//$total_meals_qty+=$meals_quantity;
									}
									$condition=array('hotel_id'=>$item['hotel_id'],'season_id'=>$season_id,'meals_id'=>$meals_id);
									$meals = $this->hotel_model->getAttributeTariff($condition);
									if($meals){
										$meals_tariff=$meals->amount;
										$meals_name=$meals->meals_name;
										
									}
								
									if(array_key_exists($meals_id,$meals_array)){
							
										$meals_array[$meals_id][2]++;
										$meals_array[$meals_id][3]+=$meals_quantity;
									
									}else{
										//$meals_idArray[]=$meals_id;
										$meals_array[$meals_id]=array($meals_name,$meals_tariff,1,$meals_quantity);
									}
									
								}
							}
							
							
						
						}
						
						if($room_tariff){
							$room_charge=$room_tariff->amount;
							$a_particular.= "- ".$room_type." Room @ Rs. ".$room_charge." per day for ".$no_of_days." day(s), No:of Rooms (".$total_rooms.").";
							}else{
								$room_charge = 0;
								$a_particular.= "- ".$room_type." Room (Charge not defined).";
							}
						$room_total=$room_charge*$total_rooms;
						
						$attr_total=0;//echo "<pre>";print_r($attr_array);echo "</pre>";exit;
						foreach($attr_array as $attribute){ 
							
							$a_particular.=" </br>".$attribute[0]."@ Rs ".$attribute[1]." per day for ".$attribute[2]." day(s).";
							$attr_total+=$attribute[1]*$attribute[3];
						}
						$meals_total=0;//echo "<pre>";print_r($meals_array);echo "</pre>";exit;
						foreach($meals_array as $meal){ 
							
							$a_particular.="</br>".$meal[0]."(Qty: ".$meal[3].") @ Rs ".$meal[1]." per day for ".$meal[2]." day(s).";
							$meals_total+=$meal[1]*$meal[3];
						}
					
						
					
					
					
					$unit_amount=$room_total+$attr_total+$meals_total;
					$tax_amount=($unit_amount*$tax)/100;
					$total=$tax_amount+$unit_amount;
					$estimate_total+=$total;
					$acc_tr[]=array($hotel_name,$a_particular,number_format($unit_amount,2),number_format($tax_amount,2),number_format($total,2));
				
				} 
				
			}
			
			if(isset($cart['services'])){
					$tax=4.955; $service_array=array(); 
					
				foreach($cart['services'] as $item){ $total_quantity=0;
				$no_of_days=count($item['from_cart']);
				$service_name=$this->settings_model->getValuebyId($item['service_id'],'services','name');
				$s_particular=$service_name." ";
					
					foreach($item['from_cart'] as $row){ //echo "<pre>";print_r($row);echo "</pre>";exit;
						$cart_contents = $this->tour_cart->contents();
						$dataArray=$cart_contents[$row['itinerary']][$row['table']][$row['index']];//echo "<pre>";print_r($dataArray);echo "</pre>";exit;
						if($dataArray['location']!=''){
							$s_particular.=", ".$dataArray['location'];
						}
						if($dataArray['quantity']=='' ||$dataArray['quantity']==0){
							$dataArray['quantity']=1;
						}
						if($no_of_days>1)
						$total_quantity+=$dataArray['quantity'];
						else{
						$total_quantity=$dataArray['quantity'];
						}
						
					}
					$s_particular.="-Rs ".$item['amount']." per day for ".$no_of_days." day(s).(Qty:".$total_quantity.").</br>";
					
				$unit_amount=$item['amount']*$total_quantity;
				$tax_amount=($unit_amount*$tax)/100;
				$total=$tax_amount+$unit_amount;
				$estimate_total+=$total;
				$str[]=array($service_name,$s_particular,number_format($unit_amount,2),number_format($tax_amount,2),number_format($total,2));
					
				
				}
				
				
				 
					
			}
			if(isset($cart['vehicles'])){
					$tax=4.955; 
					
				foreach($cart['vehicles'] as $item){ 
				$no_of_days=count($item['from_cart']);
				
				$destinations=$this->package_model->getDestinationsByOrder($cart_contents,$item['vehicle_model_id'],$item['vehicle_id']);
				if($_REQUEST['pickup']!='' ||$_REQUEST['drop']!=''){
							if($_REQUEST['pickup']!=''){
								array_unshift($destinations, $_REQUEST['pickup']);
							}
							if($_REQUEST['drop']!=''){
								array_push($destinations, $_REQUEST['drop']);
							}
						
						}
						$count= count($destinations);
						$API_KEY='AIzaSyD3Fog2G5asD5NI4iJJZDsfJHjW-gPhevA';
					
						$distance=0;
						for($i=0;$i<($count-1);$i++){
						
							$origin=$destinations[$i];
							$destination=$destinations[$i+1];
							$url='https://maps.googleapis.com/maps/api/distancematrix/json?origins='.$origin.'&destinations='.$destination.'&mode=driving&language=en&key='.$API_KEY;
							$data=file_get_contents($url);
							$decode = json_decode($data);
								if(isset($decode->rows[0]->elements[0]->status) && $decode->rows[0]->elements[0]->status!='NOT_FOUND') {
									$distance+=substr($decode->rows[0]->elements[0]->distance->text,0,-3);
								
								
								}
						
						}
				$total_distance=$distance;
				$t_particular="Travel: From ".implode(",",$destinations);
				// get first vehicle value
				$row=$item['from_cart'][0];
				$totalAmount=0;	
				$cart_contents = $this->tour_cart->contents();
				$dataArray=$cart_contents[$row['itinerary']][$row['table']][$row['index']];
				//$tarrif_id=$dataArray['tariff_id'];echo $tarrif_id;exit;
				
					$vehicle_model=$this->settings_model->getValuebyId($item['vehicle_model_id'],'vehicle_models','name'); 
					$t_particular.=" - ".$vehicle_model;	
				if($item['vehicle_id']>0){ 
					$reg_num=$this->settings_model->getValuebyId($item['vehicle_id'],'vehicles','registration_number');
					$t_particular.=" (".$reg_num.")";
				}
					$t_particular.="</br> Total Kilometers : ".$total_distance." KM.";
					$tariffdata['vehicle_ac_type']=$item['vehicle_ac_type_id'];
					$tariffdata['vehicle_model']=$item['vehicle_model_id'];
					$tariffdata['organisation_id']=$this->session->userdata('organisation_id');

					$tarrif_data=$this->tarrif_model->selectAvailableTariff($tariffdata);
				if(!empty($tarrif_data)){
					$tarrif_data=$tarrif_data[0];
					$t_particular.= "</br> Minimum ".$tarrif_data['minimum_kilometers']."KM @ Rs.".$tarrif_data['rate']." each day for ".$no_of_days."day(s).";
					$totalAmount += $tarrif_data['rate']*$no_of_days;
				
					if($total_distance>$tarrif_data['minimum_kilometers']){
						$additional_km=$total_distance-$tarrif_data['minimum_kilometers'];
						$t_particular.=" </br> Additional ".$additional_km." KM @ Rs ".$tarrif_data['additional_kilometer_rate']."/KM .";
						$totalAmount +=$tarrif_data['additional_kilometer_rate']*$additional_km;
					}
					if($tarrif_data['driver_bata']){
						$t_particular.="</br> Driver Bata @ Rs ".$tarrif_data['driver_bata']." each day for ".$no_of_days."day(s).";
						$totalAmount +=$tarrif_data['driver_bata']*$no_of_days;
					}
					if($tarrif_data['night_halt']){
						$t_particular.="</br> Night Halt @ Rs ".$tarrif_data['night_halt']." each night for ".$no_of_days."day(s).";
						$totalAmount +=$tarrif_data['night_halt']*$no_of_days;
					}
						
				}else{
					$t_particular.="</br> Tarrif not defined.";
					$totalAmount=0;
				}
					
					
					
				$unit_amount= $totalAmount;
				$tax_amount=($unit_amount*$tax)/100;
				$total=$tax_amount+$unit_amount;
				$estimate_total+=$total;
				$travel_tr[]=array("Travel",$t_particular,number_format($unit_amount,2),number_format($tax_amount,2),number_format($total,2));
					
				
				}
				
				
				 
					
			}
			
			
			
			//echo $estimate_amt;exit;
			$estimate_tr[]=array('','','','Grand Total',number_format($estimate_total,2));
			$tr=array_merge($str,$acc_tr,$travel_tr,$estimate_tr); 
			echo json_encode($tr);
		
		
	}
	//------------------------------------------------------------------------------------------------


	//generate accomodation charge for a hotel for a
	/*public function getAccomodationCharge($hotel_id,$room_type_id,$room_attributes,$room_quantity,$meals_package,$meals_quantity)
	{

		$totalAmount = 0;

		$hotel_attr=$this->hotel_model->getHotelProfile($hotel_id);
		$destination=$this->settings_model->getValuebyId($hotel_attr['destination_id'],'destinations','name');
		$room_type=$this->settings_model->getValuebyId($room_type_id,'room_types','name');
		
		$a_particulars="Accomodation: ".$hotel_attr['name'].",".$destination;

		$_date 		= date("Y-m-d");
		$season_ids = $this->tour_model->getSeasonIdssWithDate($_date);
		if($season_ids){
			$season_id = $season_ids[0];
		}else{
			$season_id = gINVALID;
		}

		//get room charge-----------------
		$filter=array('hotel_id'=>$hotel_id,'room_type_id'=>$room_type_id,'season_id'=>$season_id);
		$room_tariff= $this->hotel_model->getHotelRoomTariff($filter);
		if($room_tariff){
			$room_charge=($room_tariff->amount)*$room_quantity;
			$a_particulars.= "-".$room_type." Room @RS.".number_format($room_charge,2)." per day";
		}else{
			$room_charge = 0;
			$a_particulars.= "-".$room_type." Room (Charge not defined)";
		}
		$totalAmount += $room_charge;
		//-----------------------------------
		 
		
		//room attributes------------
		$attr_particulars = array();
		if(is_array($room_attributes)){
			foreach($room_attributes as $attribute_id){
				$condition=array('hotel_id'=>$hotel_id,'season_id'=>$season_id,
						'attribute_id'=>$attribute_id);
				$attr = $this->hotel_model->getAttributeTariff($condition);
				if($attr){
					$attr_particulars[$attribute_id] = $attr->attr_name;
					if($attr->amount > 0){
						$attr_particulars[$attribute_id].= " @Rs.".number_format($attr->amount,2);
					}
					$totalAmount += $attr->amount;
				}
			}
		}
		if($attr_particulars){
			$a_particulars.= " ( Room Attributes : ".implode(',',$attr_particulars)." )";
		}
		//---------------------------------------
		
		$meals_particulars = array();
		if(is_array($meals_package)){
			foreach($meals_package as $meals_id){
				$condition=array('hotel_id'=>$hotel_id,'season_id'=>$season_id,'meals_id'=>$meals_id);
				$meals = $this->hotel_model->getAttributeTariff($condition);
				if($meals){
					$meals_particulars[$meals_id] = $meals->meals_name;
					if($meals->amount > 0){
						$meals_particulars[$meals_id].= " @Rs.".number_format($meals->amount,2);
					}
					$totalAmount += $meals->amount;
				}
			}
		}
		if($meals_particulars){
			$a_particulars.= " ( Meals Package : ".implode(',',$meals_particulars)." )";
		}

		$tax = 4.955;
		$grandTotal = $totalAmount + $tax;
		
		return array(
				$hotel_attr['name'],
				$a_particulars,
				$totalAmount,
				$tax,
				$grandTotal
			);
			
	}*/
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
