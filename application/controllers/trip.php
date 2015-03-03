<?php 
class Trip extends CI_Controller {
	public function __construct()
		{
		parent::__construct();
		$this->load->model("settings_model");
		$this->load->helper('my_helper');
		$this->load->model("driver_model");
		$this->load->model("customers_model");
		$this->load->model("trip_booking_model");
		$this->load->model("user_model");
		no_cache();

		}
	public function index($param1 ='',$param2='',$param3=''){
	
		if($this->session_check()==true) {
	
		$tbl=array('trip-models'=>'trip_models','trip-statuses'=>'trip_statuses','booking-sources'=>'booking_sources','trip-expense'=>'trip_expense_type');
			if($param1=='getDescription') {
			$this->getDescription();
			}else if($param1=='view') {
		
			$this->tripView($param2);
			
			}else if($param1=='proposal') {
		
			$this->tripProposal($param2);
			
			}
			else if($param1=='complete') {
		
			$this->tripComplete($param2,$param3);
			
			}else if($param1=='trip-expense') {
		
			$this->manageTripExpense($tbl[$param1]);
			
			}
			if($param1) {
			
				if(isset($_REQUEST['add'])){
					$this->add($tbl,$param1);
					}else if(isset($_REQUEST['edit'])){
					$this->edit($tbl,$param1);
					}else if(isset($_REQUEST['delete'])){
					$this->delete($tbl,$param1);
					}else{
					$this->notFound();
					}
		}
		
	}elseif($this->driver_session_check()==true){
			if($param1=='view') {
			
			$this->tripView($param2);
			
			}
		}else{
			$this->notAuthorized();
			}
	}
	
	public function driver_session_check() {
		if(($this->session->userdata('isLoggedIn')==true ) && ($this->session->userdata('type')==DRIVER)) {
			return true;
		} else {
			return false;
		}
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

	//----------------------------------trip expense action -------------------------------
	public function manageTripExpense($table)
	{
		$this->load->model('account_model');
		$ExpenseId = $this->input->post('id_val');

		if(isset($_REQUEST['add']) || isset($_REQUEST['edit'])){

			$fa_table = $this->session->userdata('organisation_id')."_chart_master";
			
			if(isset($_REQUEST['edit'])){
				$data['name']=$this->input->post('select_text');
				$name = 'select_text';
				
			}else{
				$data['name']=$this->input->post('select');
				$data['value']=$this->input->post('select');


				$name = 'select';
				$this->form_validation->set_rules($name,'Trip Expense Code','trim|required|min_length[4]|numeric|xss_clean||is_unique['.$fa_table.'.account_code]');
				
			}

			$fa_data['account_code'] = $data['name'];
			$data['description']=$this->input->post('description');
			$data['organisation_id']=$this->session->userdata('organisation_id');
			$data['user_id']=$this->session->userdata('id');
		
			

			
			$this->form_validation->set_rules('description','Trip Expense Description','trim|required|min_length[2]|max_length[30]|xss_clean');
			

			if($this->form_validation->run()==False){
				$errMSG = validation_errors();
				$this->session->set_userdata(array('Err_num_name'=>$errMSG));
				redirect(base_url().'organization/front-desk/settings');
			}else{
		
				// gl account for this expense
				$fa_data['account_name'] = $data['description'];
				$fa_data['account_type'] = CURRENT_LBTS;//FA ACCOOUNT TYPE 
				$updateGl = $this->account_model->GLAccount($fa_data);
				
				if($updateGl){
					//update trip expense
					if($ExpenseId){//edit expense
						$result=$this->settings_model->updateValues($table,$data,$ExpenseId);
						$mode = "Updated";

					}else{//add expense
						$result=$this->settings_model->addValues($table,$data);
						$mode = "Added";
					}

					if($result==true){
						$this->session->set_userdata(array('dbSuccess'=>'Details '.$mode.' Succesfully..!'));
					    	$this->session->set_userdata(array('dbError'=>''));
					     	redirect(base_url().'organization/front-desk/settings');
					}else{
						$this->session->set_userdata(array('dbSuccess'=>''));
				    		$this->session->set_userdata(array('dbError'=>'Error in updating Trip Expense'));
				     		redirect(base_url().'organization/front-desk/settings');
					}
					
				}
				
				
			}

		}else if(isset($_REQUEST['delete'])){ 

			$id=$this->input->post('id_val');
			$expense = $this->settings_model->getValues($id,$table);
			//print_r($expense);exit;	
			$Deleted = $this->account_model->GlAccountDelete($expense[0]['name']);
			if($Deleted){
				$result=$this->settings_model->deleteValues($table,$id);
				if($result==true){
					$this->session->set_userdata(array('dbSuccess'=>'Details Deleted Succesfully..!'));
					$this->session->set_userdata(array('dbError'=>''));
				}
			}else{
				$this->session->set_userdata(array('Err_num_name'=>'Cannot delete this account because transactions have been created using this account.'));
			}
			
		}
		redirect(base_url().'organization/front-desk/settings');
		
	}
	//----------------------------------trip expense action ends here-------------------------------
	
	public function add($tbl,$param1){
	
	if(isset($_REQUEST['select'])&& isset( $_REQUEST['description'])&& isset($_REQUEST['add'])){ 
			
		    $data['name']=$this->input->post('select');
			$data['description']=$this->input->post('description');
			$data['organisation_id']=$this->session->userdata('organisation_id');
			$data['user_id']=$this->session->userdata('id');
			
	        $this->form_validation->set_rules('select','Values','trim|required|min_length[2]|xss_clean');
			$this->form_validation->set_rules('description','Description','trim|required|min_length[2]|xss_clean');
		if($this->form_validation->run()==False){
         redirect(base_url().'organization/front-desk/settings');
		}
      else {
		$result=$this->settings_model->addValues($tbl[$param1],$data);
		if($result==true){
					$this->session->set_userdata(array('dbSuccess'=>'Details Added Succesfully..!'));
				    $this->session->set_userdata(array('dbError'=>''));
				     redirect(base_url().'organization/front-desk/settings');
						}
			}
							}
	}
	public function edit($tbl,$param1){
	if(isset($_REQUEST['select_text'])&& isset( $_REQUEST['description'])&& isset($_REQUEST['edit'])){ 
			
		    $data['name']=$this->input->post('select_text');
			$data['description']=$this->input->post('description');
			$id=$this->input->post('id_val');
	        $this->form_validation->set_rules('select_text','Values','trim|required|min_length[2]|xss_clean');
			$this->form_validation->set_rules('description','Description','trim|required|min_length[2]|xss_clean');
		if($this->form_validation->run()==False){
       // redirect(base_url().'user/settings');
       redirect(base_url().'organization/front-desk/settings');
		}
      else {
		$result=$this->settings_model->updateValues($tbl[$param1],$data,$id);
		if($result==true){
					$this->session->set_userdata(array('dbSuccess'=>'Details Updated Succesfully..!'));
				    $this->session->set_userdata(array('dbError'=>''));
				  //  redirect(base_url().'user/settings');
				  redirect(base_url().'organization/front-desk/settings');
						}
			}
							}
	
	}
	
	public function delete($tbl,$param1){
	if(isset($_REQUEST['delete'])){ 
	
	$id=$this->input->post('id_val');
	        $this->form_validation->set_rules('select_text','Values','trim|required|min_length[2]|xss_clean|alpha_numeric');
			//$this->form_validation->set_rules('select','Values','trim|required|min_length[2]|xss_clean|alpha_numeric');
			$this->form_validation->set_rules('description','Description','trim|required|min_length[2]|xss_clean|alpha_numeric');
		if($this->form_validation->run()==False){
        redirect(base_url().'organization/front-desk/settings');
		}
      else {
		$result=$this->settings_model->deleteValues($tbl[$param1],$id);
		if($result==true){
					$this->session->set_userdata(array('dbSuccess'=>'Details Deleted Succesfully..!'));
				    $this->session->set_userdata(array('dbError'=>''));
				    redirect(base_url().'organization/front-desk/settings');
						}
			}
	}
	}
	
	public function tripComplete($trip_id,$pagination=''){
	$data=array('trip_status_id'=>TRIP_STATUS_TRIP_COMPLETED);
	$res=$this->trip_booking_model->updateTrip($data,$trip_id);
	 redirect(base_url().'organization/front-desk/trips/'.$pagination);
	
	}	

	public function session_check() {
	if(($this->session->userdata('isLoggedIn')==true ) && ($this->session->userdata('type')==FRONT_DESK)) {
		return true;
		} else {
		return false;
		}
	} 
	public function tripView($param2){ 
	if($this->session_check()==true || $this->driver_session_check()==true) {
	$trip_id=$param2;
	$tbl_arry=array('customer_types','booking_sources','trip_models','vehicle_types','vehicle_ac_types','vehicle_beacon_light_options','vehicle_seating_capacity');
	for ($i=0;$i<count($tbl_arry);$i++){
			$result=$this->user_model->getArray($tbl_arry[$i]);
			if($result!=false){
			$data[$tbl_arry[$i]]=$result;
			}
			else{
			$data[$tbl_arry[$i]]='';
			}
	}
	$customer_types=$data['customer_types'];
	$trip_models=$data['trip_models'];
	$booking_sources=$data['booking_sources'];
	$vehicle_types=$data['vehicle_types'];
	$vehicle_beacon_light_options=$data['vehicle_beacon_light_options'];
	$vehicle_seating_capacity=$data['vehicle_seating_capacity'];
	$vehicle_ac_types=$data['vehicle_ac_types'];
	$vehicles=$this->trip_booking_model->getVehiclesArray($condition='');
	$drivers=$this->driver_model->getDriversArray($condition='');
	
	$conditon = array('id'=>$trip_id);
	$result=$this->trip_booking_model->getDetails($conditon);
	$result=$result[0];
	$data1['trip_id']=$result->id;
	
	$dbdata=array('id'=>$result->customer_id);	
	$customer 	=	$this->customers_model->getCustomerDetails($dbdata);
	$customer=$customer[0];
	$data1['customer']				=	$customer['name'];
	$data1['email']					=	$customer['email'];
	$data1['mobile']				=	$customer['mobile'];
	$data1['address']				=	$customer['address'];
	$data1['customer_type']			=	$customer_types[$customer['customer_type_id']];
	
	$data1['booking_source']			=	$booking_sources[$result->booking_source_id];	
	$data1['source']					=	$result->source;
	$data1['booking_date']				=	$result->booking_date;	
	$data1['booking_time']				=	$result->booking_time;
	$data1['trip_model']				=	$trip_models[$result->trip_model_id];
	
	$drop='';
	$pickup='';
	$via='';
	if($result->pick_up_landmark!=''){
	$pickup=$result->pick_up_landmark;
	}
	if($pickup!=''){
	$pickup.=','.$result->pick_up_area;
	}else{
	$pickup=$result->pick_up_area;
	}
	if($pickup!=''){
	$pickup.=','.$result->pick_up_city;
	}else{
	$pickup=$result->pick_up_city;
	}
	$data1['pickuploc']				=	$pickup;
	
	if($result->via_landmark!=''){
	$via=$result->via_landmark;
	}
	if($via!=''){
	$via.=','.$result->via_area;
	}else{
	$via=$result->via_area;
	}
	if($via!=''){
	$via.=','.$result->via_city;
	}else{
	$via=$result->via_city;
	}
	$data1['vialoc']				=	$via;

	if($result->drop_landmark!=''){
	$drop=$result->drop_landmark;
	}
	if($drop!=''){
	$drop.=','.$result->drop_area;
	}else{
	$drop=$result->drop_area;
	}
	if($drop!=''){
	$drop.=','.$result->drop_city;
	}else{
	$drop=$result->drop_city;
	}
	$data1['droploc']				=	$drop;

	$data1['pick_up_date']		=	$result->pick_up_date;
	$data1['drop_date']		=	$result->drop_date;
	$data1['pick_up_time']		=	$result->pick_up_time;
	$data1['drop_time']		=	$result->drop_time;
	
	$data1['vehicle_type']			=	$vehicle_types[$result->vehicle_type_id];
	$data1['vehicle_ac_type']		=	$vehicle_ac_types[$result->vehicle_ac_type_id];
	if($result->vehicle_seating_capacity_id!=gINVALID){
	$data1['vehicle_seating_capacity']		=$vehicle_seating_capacity[$result->vehicle_seating_capacity_id];
	}else{
	$data1['vehicle_seating_capacity']		='';
	}
	if($result->vehicle_seating_capacity_id!=gINVALID){
	$data1['vehicle_beacon_light']		=	$vehicle_beacon_light_options[$result->vehicle_beacon_light_option_id];		
	}else{
	$data1['vehicle_beacon_light']		='';
	}

	$data1['vehicle']				=	$vehicles[$result->vehicle_id];
	$data1['driver']				=	$drivers[$result->driver_id];
	/*echo "<pre>";
	print_r($data1);
	echo "</pre>";*/
	
		$page='user-pages/trip';
		$data1['title']="Trip | ".PRODUCT_NAME;  
		$this->load_templates($page,$data1);
		}else{
				$this->notAuthorized();
			}
	}
	public function load_templates($page='',$data=''){
	if($this->session_check()==true || $this->driver_session_check()==true) {
		$this->load->view('admin-templates/header',$data);
		$this->load->view('admin-templates/nav');
		$this->load->view($page,$data);
		$this->load->view('admin-templates/footer');
		}
	else{
			$this->notAuthorized();
		}
	}
		public function getDescription(){
		$id=$_REQUEST['id'];
		$tbl=$_REQUEST['tbl'];
		$res=$this->settings_model->getValues($id,$tbl);
		echo $res[0]['id']." ".$res[0]['description']." ".$res[0]['name'];
		}

	public function notAuthorized(){
	$data['title']='Not Authorized | '.PRODUCT_NAME;
	$page='not_authorized';
	$this->load->view('admin-templates/header',$data);
	$this->load->view('admin-templates/nav');
	$this->load->view($page,$data);
	$this->load->view('admin-templates/footer');
	
	}
	
	public function tripProposal($param2){
	
	if($this->session_check()==true) {
		$this->load->model('organization_model');
		$template = $this->organization_model->getOrgQuotationTemplate();
		
		$trip_id=$param2;
	
		$tbl_arry=array('customer_types','booking_sources','trip_models','vehicle_types','vehicle_ac_types','vehicle_beacon_light_options','vehicle_seating_capacity');
		for ($i=0;$i<count($tbl_arry);$i++){
				$result=$this->user_model->getArray($tbl_arry[$i]);
				if($result!=false){
				$data[$tbl_arry[$i]]=$result;
				}
				else{
				$data[$tbl_arry[$i]]='';
				}
		}
	
		$trip_models=$data['trip_models'];
		$booking_sources=$data['booking_sources'];
		$vehicle_types=$data['vehicle_types'];
		$vehicle_beacon_light_options=$data['vehicle_beacon_light_options'];
		$vehicle_seating_capacity=$data['vehicle_seating_capacity'];
		$vehicle_ac_types=$data['vehicle_ac_types'];
		$vehicles=$this->trip_booking_model->getVehiclesArray($condition='');
		$drivers=$this->driver_model->getDriversArray($condition='');
	
	
	
		$conditon = array('id'=>$trip_id);
		$result=$this->trip_booking_model->getDetails($conditon); 
		$result=$result[0];  
		$cust_arry['group_id']=$result->customer_group_id;
		$cust_arry['cust_id']=$result->customer_id;
		$cust_arry['guest_id']=$result->guest_id;
		$data1['trip_id']=$result->id;
		$data1['customer_details']=$this->trip_booking_model->getCustomer($cust_arry);
		if($result->booking_source_id>0){
		$data1['booking_source']			=	$booking_sources[$result->booking_source_id];	
		}
		else{
		$data1['booking_source']='';
		}
		$data1['source']					=	$result->source;
		$data1['booking_date']				=	$result->booking_date;	
		$data1['booking_time']				=	$result->booking_time;
		if($result->trip_model_id>0){
		$data1['trip_model']				=	$trip_models[$result->trip_model_id];
		}else{
		$data1['trip_model']='';
		}
		$data1['pick_up_city']		=	$result->pick_up_city;
		$data1['drop_city']		=	$result->drop_city;
		$data1['pickup_date']		=	$result->pick_up_date;
		$strt_km=$result->kilometer_reading_start;
		$end_km=$result->kilometer_reading_drop;
		$data1['total_km']		= $end_km-$strt_km;
		$pickdate=$result->pick_up_date.' '.$result->pick_up_time;
		$dropdate=$result->drop_date." ".$result->drop_time;
		$date1 = date_create($pickdate);
		$date2 = date_create($dropdate);
						
		$diff= date_diff($date1, $date2);
		if($diff->d > 0 && $diff->h >= 0 && $diff->i >=1 ){
			$no_of_days=$diff->d+1;
		}else{
			$no_of_days=$diff->d;
		}
						
		$data1['time_duration']=$no_of_days."days".nbs(3).$diff->h."hrs".nbs(3).$diff->i."mints";
		$data1['pick_up_time']		=	$result->pick_up_time;
		$data1['drop_time']		=	$result->drop_time;
		if($result->vehicle_type_id>0){
		$data1['vehicle_type']		=	$vehicle_types[$result->vehicle_type_id];
		}else{
		$data1['vehicle_type']	='';
		}
		if($result->vehicle_ac_type_id>0){
		$data1['vehicle_ac_type']	=	$vehicle_ac_types[$result->vehicle_ac_type_id];
		}else{
		$data1['vehicle_ac_type']='';
		}
		if($result->vehicle_seating_capacity_id!=gINVALID){
		$data1['vehicle_seating_capacity']	=$vehicle_seating_capacity[$result->vehicle_seating_capacity_id];
		}else{
		$data1['vehicle_seating_capacity']	='';
		}
		if($result->vehicle_seating_capacity_id!=gINVALID){
		$data1['vehicle_beacon_light']		=	$vehicle_beacon_light_options[$result->vehicle_beacon_light_option_id];		
		}else{
		$data1['vehicle_beacon_light']		='';
		}

		$data1['vehicle']			=($result->vehicle_id > 0)?$vehicles[$result->vehicle_id]:'';
		$data1['driver']			=($result->driver_id > 0)?$drivers[$result->driver_id]:'';
		$trip_id = array('trip_id'=>$trip_id);
		$tariff_details=$this->trip_booking_model->getRoughEstimate($trip_id);
		$tariff_details=$tariff_details[0];
	
		$data1['time_of_journey']			=	$tariff_details->time_of_journey;
		$data1['distance']				=	$tariff_details->distance;
		$data1['min_charge']				=	$tariff_details->min_charge;
		$data1['additional_charge']			=	$tariff_details->additional_charge;
		$data1['min_kilometers']			=	$tariff_details->min_kilometers;
		$data1['amount']				=	$tariff_details->amount;
		$data1['tax_payable']				=	$tariff_details->tax_payable;
		$data1['additional_km']				=	$tariff_details->additional_km;
		$data1['total_amt']				=	$tariff_details->total_amt;
		

		$page = 'templates/'.$template.EXT;
		if(file_exists('application/views/' . $page))
		{
			$data1['title']="Trip | ".PRODUCT_NAME;  
			$this->load_templates($page,$data1);
		}else{
			$this->notFound();
		}

		
	
	
	}
	else{
			$this->notAuthorized();
	}
	}//fn ends
}
