<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Hotel extends CI_Controller {
	public function __construct()
	{
    		parent::__construct();
		$this->load->helper('my_helper');
		$this->load->model('tour_model');
		$this->load->model('hotel_model');
		$this->load->model('settings_model');
		$this->load->model('user_model');
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
				$this->hotel_profile($param2,$param3);
			}elseif($param1=='manage-profile'){	
				$this->manage_hotel_profile();
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


	public function hotel_profile($param2 = '',$param3=''){
	
		if($this->session_check()==true) {

			//lists
			$tblArray = array('hotel_categories','hotel_ratings','room_types','business_seasons','destinations','room_attributes','meals_options');
			foreach($tblArray as $table){
				$data[$table]=$this->user_model->getArray($table);
			}
			$active_tab = 'h_tab';
			if($param2 != null){ //edit profile
				$data['profile'] = $this->hotel_model->getHotelProfile($param2); 
				$data['owner'] = $this->hotel_model->getHotelOwner(@$data['profile']['hotel_owner_id']); 
				$data['rooms'] = $this->hotel_model->getHotelRooms($param2); 
				$data['room_attr_tariffs'] = $this->hotel_model->getHotelRoomTariffs($param2);

				if($this->mysession->get('post_profile')){
					$data['profile'] = $this->mysession->get('post_profile');
					$this->mysession->delete('post_profile');	
				}elseif($this->mysession->get('post_owner')){
					$data['owner'] = $this->mysession->get('post_owner');
					$this->mysession->delete('post_owner');
					$active_tab = 'o_tab';
				}elseif($this->mysession->get('post_rooms')){
					$data['room'] = $this->mysession->get('post_owner');
					$this->mysession->delete('post_owner');
					$active_tab = 'r_tab';
				}elseif($this->mysession->get('post_tariffs')){
					$data['tariff'] = $this->mysession->get('post_owner');
					$this->mysession->delete('post_owner');
					$active_tab = 't_tab';
				}
				
			}elseif($this->mysession->get('post_profile')){
				$data['profile'] = $this->mysession->get('post_profile');
				$this->mysession->delete('post_profile');	
			}
			//echo "<pre>";print_r($data);echo "</pre>";exit;
			switch ($param3){
					case 'owner':$active_tab = 'o_tab';break;
					case 'rooms':$active_tab = 'r_tab';break;
					case 'tariff':$active_tab = 't_tab';break;
					
				}
			
			$data['tabs'] = $this->set_up_hotel_tabs($active_tab,$param2);			
			$data['title']="Hotel | ".PRODUCT_NAME;  
			$page='user-pages/hotel-profile';
			$this->load_templates($page,$data);
		}else{
			$this->notAuthorized();
		}
	}

	//hotel add or edit action
	public function manage_hotel_profile()
	{
		$id = '';
		if(isset($_REQUEST['h-profile-add-update'])){
			
			$this->form_validation->set_rules('hotel_name','Hotel Name','trim|required|xss_clean');
			$this->form_validation->set_rules('hotel_address','Hotel Address','trim|required|xss_clean');
			$this->form_validation->set_rules('category','Hotel Category','trim|required|xss_clean');
			$this->form_validation->set_rules('destination','Hotel Destination','trim|required|xss_clean');
			$this->form_validation->set_rules('contact_person','Contact Person','trim|required|xss_clean');
			$this->form_validation->set_rules('land_line_number','Contact Phone','trim|numeric|xss_clean');
			
			$data['name'] = $this->input->post('hotel_name');
			$data['address'] = $this->input->post('hotel_address');
			$data['city'] = $this->input->post('city');
			$data['state'] = $this->input->post('state');
			$data['contact_person'] = $this->input->post('contact_person');
			$data['mobile'] = $this->input->post('mobile');
			$data['land_line_number'] = $this->input->post('land_line_number');
			$data['hotel_category_id'] = $this->input->post('category');
			$data['destination_id'] = $this->input->post('destination');
			$data['hotel_rating_id'] = $this->input->post('rating');
			$data['no_of_rooms'] = $this->input->post('no_of_rooms');
			
			if($this->input->post('h_mobile')==$data['mobile']){
			$this->form_validation->set_rules('mobile','10 digit Mobile Number ','trim|required|xss_clean|regex_match[/^[0-9]{10}$/]');
			}else{
			$this->form_validation->set_rules('mobile','10 digit Mobile Number ','trim|required|xss_clean|regex_match[/^[0-9]{10}$/]|is_unique[hotels.mobile]');
			}
			$dbData = $data;
			$err=false;
			if($data['hotel_category_id'] ==gINVALID){
			$err=true;
			$this->mysession->set('Err_category','Choose Category!');
			}
			if($data['destination_id'] ==gINVALID){
			$err=true;
			$this->mysession->set('Err_destination','Choose Destination!');
			}
			$data['seasons'] = $this->input->post('seasons');
				$seasons=array();
				$seasons=$this->input->post('seasons');
				if($seasons[0]==''|| empty($seasons)){
				   $dbData['seasons'] = '';
				}else{
				   $dbData['seasons'] = serialize($this->input->post('seasons'));
				} 
			$dbData['organisation_id'] = $this->session->userdata('organisation_id'); 
			$dbData['user_id'] = $this->session->userdata('id'); 
			
			if($this->form_validation->run() != False && $err==false) {
				$id = $this->input->post('id');		
				if(is_numeric($id) && $id > 0){//edit hotel
					if($this->settings_model->updateValues('hotels',$dbData,$id)){
						$this->session->set_userdata(array('dbSuccess'=>'Hotel Profile Updated Succesfully..!')); 
						$this->session->set_userdata(array('dbError'=>''));
					}
				}else{//add new hotel
				
					if($id = $this->settings_model->addValues_returnId('hotels',$dbData)){
						$this->session->set_userdata(array('dbSuccess'=>'Hotel Added Succesfully..!')); 
						$this->session->set_userdata(array('dbError'=>''));
					} 
				}

			}else{  
				$data['id']=$this->input->post('id');	
				$this->mysession->set('post_profile',$data);
				
			}
		}
		redirect(base_url().'front-desk/hotel/profile/'.$id);
		//$this->hotel_profile($id,'h_tab');
	}

	public function manage_hotel_owner($hotel_id='')
	{ 
		$id = '';
		if(isset($_REQUEST['h-owner-add-update'])){
			$this->form_validation->set_rules('owner-name','Hotel Name','trim|required|xss_clean');
			//$this->form_validation->set_rules('mob-no','Mobile Number','trim|required|numeric|xss_clean');
			$this->form_validation->set_rules('mail-id','E-mail Id','trim|valid_email|is_unique[hotel_owners.email]');
			if($this->input->post('username')!='') { 
			$this->form_validation->set_rules('password','Password','trim|min_length[5]|matches[cpassword]|xss_clean');
			$this->form_validation->set_rules('cpassword','Confirmation','trim|min_length[5]|xss_clean');
			$this->form_validation->set_rules('username','Username','trim|min_length[4]|xss_clean|is_unique[users.username]');
			
			}
			$data['name'] = $this->input->post('owner-name');
			$data['mobile'] = $this->input->post('mob-no');
			$data['email'] = $this->input->post('mail-id');
			
			if($this->input->post('h-mail-id')==$data['email']){
			$this->form_validation->set_rules('mail-id','E-mail Id','trim|valid_email');
			}else{
			$this->form_validation->set_rules('mail-id','E-mail Id','trim|valid_email|is_unique[hotel_owners.email]');
			}
			
			if($this->input->post('h-mob-no')==$data['mobile']){
			$this->form_validation->set_rules('mob-no','10 digit Mobile Number ','trim|required|xss_clean|regex_match[/^[0-9]{10}$/]');
			}else{
			$this->form_validation->set_rules('mob-no','10 digit Mobile Number ','trim|required|xss_clean|regex_match[/^[0-9]{10}$/]|is_unique[hotel_owners.mobile]');
			}
			
			$dbData = $data;
			$data['username'] = $this->input->post('username');

			
			$dbData['organisation_id'] = $this->session->userdata('organisation_id'); 
			$dbData['user_id'] = $this->session->userdata('id');

			if($this->form_validation->run() != False) { 
				 
				$id = $this->input->post('owner_id');
						
				if(is_numeric($id) && $id > 0){//edit hotel 
				if($this->settings_model->updateValues('hotel_owners',$dbData,$id)){
						$this->session->set_userdata(array('O_dbSuccess'=>'Hotel Owner Updated Succesfully..!')); 
						$this->session->set_userdata(array('O_dbError'=>''));
					}
				}else{//add new hotel 
				
					if($id = $this->settings_model->addValues_returnId('hotel_owners',$dbData)){
					
							$hotelData = array('hotel_owner_id'=>$id);
						$this->settings_model->updateValues('hotels',$hotelData,$hotel_id);

						$this->session->set_userdata(array('O_dbSuccess'=>'Hotel Owner Added Succesfully..!')); 
						$this->session->set_userdata(array('O_dbError'=>''));
					}
				}

			}else{
				$data['id']=$this->input->post('owner_id');
				$this->mysession->set('post_owner',$data);
			}
		}
		redirect(base_url().'front-desk/hotel/profile/'.$hotel_id.'/owner/'.$id,$data);	
		//$this->hotel_profile($hotel_id,'o_tab');
	}


	public function manage_hotel_rooms($hotel_id='')
	{ 
		$id = '';
		if(isset($_REQUEST['room-add']) || isset($_REQUEST['room-edit'])){ 
			$this->form_validation->set_rules('room_type_id','Room Type','trim|required|xss_clean');
			$this->form_validation->set_rules('no_of_rooms','Number Of Rooms','trim|required|numeric|xss_clean');
			
			$data['room_type_id'] = $this->input->post('room_type_id');
			$data['no_of_rooms'] = $this->input->post('no_of_rooms');
			
			$dbData = $data;
			$dbData['hotel_id'] = $hotel_id;
			$dbData['organisation_id'] = $this->session->userdata('organisation_id'); 
			$dbData['user_id'] = $this->session->userdata('id');
			$err=false;
			if($data['room_type_id']==gINVALID){
			$err=true;
			$this->mysession->set('Err_room_type','Choose Room Type!');
			}
			if($this->form_validation->run() != False && $err==false) { 
				
				$this->hotel_model->updateHotelRooms($dbData);
					
				$this->session->set_userdata(array('R_dbSuccess'=>'Hotel Rooms Updated Succesfully..!')); 
				$this->session->set_userdata(array('R_dbError'=>''));
				
			}else{
				$this->mysession->set('post_room',$data);
			}
		}
		redirect(base_url().'front-desk/hotel/profile/'.$hotel_id.'/rooms/'.$id,$data);
		//$this->hotel_profile($hotel_id,'r_tab');
	}

	public function manage_hotel_rooms_tariff($hotel_id='')
	{
		$id = '';$data = array();
		if(isset($_REQUEST['room-type-tariff-add']) || isset($_REQUEST['room-type-tariff-edit'])){ 

			$this->form_validation->set_rules('room_type_id','Room Type','trim|xss_clean');
			$this->form_validation->set_rules('season_id1','Business Season','trim|xss_clean');
			$this->form_validation->set_rules('amount1','Amount','trim|required|numeric|xss_clean');

			$data['room_type_id'] = $this->input->post('room_type_id');
			$dbData['room_type_id'] = $data['room_type_id'];
			$data['season_id1'] = $this->input->post('season_id1');
			$dbData['season_id'] = $data['season_id1'];
			$data['amount1'] = $this->input->post('amount1');
			$dbData['amount'] = $data['amount1'];
			$table = 'room_tariffs';
		}elseif(isset($_REQUEST['attr-tariff-add']) || isset($_REQUEST['attr-tariff-edit'])){

			$this->form_validation->set_rules('room_attr_id','Room Attribute','trim|xss_clean');
			$this->form_validation->set_rules('season_id2','Business Season','trim|xss_clean');
			$this->form_validation->set_rules('amount2','Amount','trim|required|numeric|xss_clean');

			$data['attribute_id'] = $this->input->post('room_attr_id');
			$dbData['attribute_id'] = $data['attribute_id'];
			$data['season_id2'] = $this->input->post('season_id2');
			$dbData['season_id'] = $data['season_id2'];
			$data['amount2'] = $this->input->post('amount2');
			$dbData['amount'] = $data['amount2'];
			$table = 'room_attribute_tariffs';

		}elseif(isset($_REQUEST['meals-tariff-add']) || isset($_REQUEST['meals-tariff-edit'])){
			$this->form_validation->set_rules('meals_package_id','Meals Package','trim|xss_clean');
			$this->form_validation->set_rules('season_id3','Business Season','trim|xss_clean');
			$this->form_validation->set_rules('amount3','Amount','trim|required|numeric|xss_clean');
			
			$data['meals_id'] = $this->input->post('meals_package_id');
			$dbData['meals_id'] = $data['meals_id'];
			$data['season_id3'] = $this->input->post('season_id3');
			$dbData['season_id'] = $data['season_id3'];
			$data['amount3'] = $this->input->post('amount3');
			$dbData['amount'] = $data['amount3'];
			$table = 'room_attribute_tariffs';

		}
		$err=false;
			if(isset($data['room_type_id']) && $data['room_type_id']==gINVALID){
			$err=true;
			$this->mysession->set('Err_room_type_tariff','Choose Room Type!');
			}
			if(isset($data['season_id1']) && $data['season_id1']==gINVALID){
			$err=true;
			$this->mysession->set('Err_season_id1','Choose Season!');
			}
			if(isset($data['attribute_id']) && $data['attribute_id']==gINVALID){
			$err=true;
			$this->mysession->set('Err_room_attr','Choose Attribute!');
			}
			if(isset($data['season_id2']) && $data['season_id2']==gINVALID){
			$err=true;
			$this->mysession->set('Err_season_id2','Choose Season!');
			}
			if(isset($data['meals_id']) && $data['meals_id']==gINVALID){
			$err=true;
			$this->mysession->set('Err_meals','Choose Meals Package!');
			}
			if(isset($data['season_id3']) && $data['season_id3']==gINVALID){
			$err=true;
			$this->mysession->set('Err_season_id3','Choose Season!');
			}
	
		if($data){
			//$dbData = $data;
			$dbData['hotel_id'] = $hotel_id;
			$dbData['organisation_id'] = $this->session->userdata('organisation_id'); 
			$dbData['user_id'] = $this->session->userdata('id');
			if($this->form_validation->run() != False && $err==false) {
								
				$this->hotel_model->updateHotelTariffs($dbData,$table);
					
				$this->session->set_userdata(array('T_dbSuccess'=>'Hotel Tariffs Updated Succesfully..!')); 
				$this->session->set_userdata(array('T_dbError'=>''));		
				

			}else{ 
				$this->mysession->set('post_tariff',$data);
				
			}
		}
		
		redirect(base_url().'front-desk/hotel/profile/'.$hotel_id.'/tariff/'.$id,$data);	
		//$this->hotel_profile($hotel_id,'t_tab');
	}
	//------------------------------------------------------------------------------------------
	
	public function list_hotel($param2){

		if($this->session_check()==true) {

			if($this->mysession->get('condition')!=null){
				$condition=$this->mysession->get('condition');
				if(!isset($condition['like']['name']) && !isset($condition['where']['hotel_rating_id']) || isset($condition['where']['hotel_category_id'])){
					$this->mysession->delete('condition');
				}
			}

			$tbl_arry=array('hotel_ratings','hotel_categories');
	
			for ($i=0;$i<count($tbl_arry);$i++){
				$data[$tbl_arry[$i]] = $this->user_model->getArray($tbl_arry[$i]);
			}

			$where_arry['organisation_id'] = $this->session->userdata('organisation_id');
			$like_arry = array();
			if(isset($_REQUEST['hotel_search'])){

				if($param2==''){
					$param2='0';
				}
				if($_REQUEST['hotel_name']!=null){
					$like_arry['name']=$_REQUEST['hotel_name'];
				}

				if(is_numeric($_REQUEST['hotel_rating_id']) && $_REQUEST['hotel_rating_id'] > 0){
					$where_arry['hotel_rating_id'] = $_REQUEST['hotel_rating_id'];
				}
				
				if(is_numeric($_REQUEST['hotel_category_id']) && $_REQUEST['hotel_category_id'] > 0){
					$where_arry['hotel_category_id'] = $_REQUEST['hotel_category_id'];
				}

				$this->mysession->set('condition',array("where"=>$where_arry,"like"=>$like_arry));
				
			}
			if(is_null($this->mysession->get('condition'))){
				$this->mysession->set('condition',array("where"=>$where_arry,"like"=>$like_arry));

				$data['hotel_name'] 		= @$like_arry['name'];
				$data['hotel_rating_id']	= @$where_arry['hotel_rating_id'];
				$data['hotel_category_id']	= @$where_arry['hotel_category_id'];
			}
			$tbl="hotels";
			$baseurl=base_url().'front-desk/hotel/list';
			$per_page=10;
			$uriseg ='4';
			$paginations=$this->mypage->paging($tbl,$per_page,$param2,$baseurl,$uriseg,$model='');
			if($param2==''){
				$this->mysession->delete('condition');
			}

			$data['page_links']=$paginations['page_links'];
			$hotels = $paginations['values'];
			foreach($hotels as $hotel){
				$data['hotels'][] = $this->hotel_model->getHotel($hotel['id']);
			}

			//$data['hotels'] = $this->hotel_model->getHotelList();
			//echo "<pre>";print_r($data);echo "</pre>";exit;
			$data['title']="Hotel | ".PRODUCT_NAME;  
			$page='user-pages/list-hotel';
			$this->load_templates($page,$data);
		}
		else{
			$this->notAuthorized();
		}
	}
	//------------------------------------------------------------------------------------------
	
	//-----------------ajax related functions---------------------------------------------------
	public function getAvailableHotels()
	{
		$filter['hotel.destination_id']=$_REQUEST['destination_id'];
		$filter['hotel.hotel_category_id']=$_REQUEST['category_id'];
		if(is_numeric($_REQUEST['_date']) && $_REQUEST['_date'] > 0){
			$seasons = $this->tour_model->getBusinessSeasonList();
			$season_ids = array();
			if($seasons){
				foreach($seasons as $season){
					array_push($season_ids,$season['id']);
				}
			}
			
		}else{
			$season_ids = $this->tour_model->getSeasonIdssWithDate($_REQUEST['_date']);
		}
		

		$hotels = $this->hotel_model->getDateSeasonHotels($filter,$season_ids);

		if(!$hotels){
			echo 'false';
		}else{
			echo json_encode($hotels);
		}

		
	}

	public function getHotelRooms()
	{
		$rooms = $this->hotel_model->getHotelRooms($_REQUEST['hotel_id']);
		
		if(!$rooms){
			echo 'false';
		}else{
			echo json_encode($rooms);
		}

		
	}
	
	public function getRoomAvailability($Ajax = 'NO')//not completed
	{
		$hotel_id	= $_REQUEST['hotel_id'];
		$room_type_id	= $_REQUEST['room_type_id'];
		$_date 		= '2015-04-07';//$_REQUEST['booking_date'];
		$requiredQTY	= $_REQUEST['qty'];
		$hotel_room = $this->hotel_model->getHotelRoomType($hotel_id,$room_type_id);
		if($hotel_room){
			$room_occupancy = $this->tour_model->getRoomOccupancyCount($hotel_id,$room_type_id,$_date);
			$availableQTY = $hotel_room['no_of_rooms']-$room_occupancy;
			if($requiredQTY >= $availableQTY)
				echo $availableQTY;
			else
				echo $requiredQTY;
		}else{
			echo 0;
		}
	}

	public function getRoomTariff()
	{
		$hotel_id	= $_REQUEST['hotel_id'];
		$room_type_id	= $_REQUEST['room_type_id'];
		$_date 		= $_REQUEST['_date'];
		$trip_id	= $_REQUEST['trip_id'];
		$season_ids = $this->tour_model->getSeasonIdssWithDate($_REQUEST['_date']);
		if($season_ids){
			$season_id = $season_ids[0];
		}else{
			$season_id = gINVALID;
		}
		$filter = array('hotel_id'=>$hotel_id,'room_type_id'=>$room_type_id,'season_id'=>$season_id);
		$room_tariff= $this->hotel_model->getHotelRoomTariff($filter);
		if($room_tariff){
			list($attr,$meals,$days)  = $this->hotel_model->getTourHotelAttrTariffs($hotel_id,$room_type_id,$trip_id,$season_id);
			if($days > 0){
				echo json_encode(array('room_charge'=>$room_tariff->amount,
						'attributes'=>$attr,
						'meals_package'=>$meals,
						'days'=>$days));
			}else{
				echo 'false';
			}			
		}else{
			echo 'false'	;
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
			$tabs['r_tab'] = array('class'=>'','tab_id'=>'tab_3','text'=>'Rooms',
						'content_class'=>'tab-pane');
			$tabs['t_tab'] = array('class'=>'','tab_id'=>'tab_4','text'=>'Tariffs',
						'content_class'=>'tab-pane');
			$tabs['p_tab'] = array('class'=>'','tab_id'=>'tab_5','text'=>'Payments',
						'content_class'=>'tab-pane');
			$tabs['a_tab'] = array('class'=>'','tab_id'=>'tab_6','text'=>'Accounts',
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
