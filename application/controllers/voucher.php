<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Voucher extends CI_Controller {
	public function __construct()
	{
    		parent::__construct();
		$this->load->helper('my_helper');
		$this->load->model('tour_model');
		$this->load->model('voucher_model');
		$this->load->model('driver_model');
		$this->load->model('hotel_model');
		$this->load->model('user_model');
		$this->load->model('settings_model');
		$this->load->model('customers_model');
		$this->load->model('trip_booking_model');
		$this->load->model('vehicle_model');
		$this->load->model('package_model');
		$this->load->model('account_model');

		$this->load->library('tour_voucher');
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
			}elseif($param1=='add'){	
				$this->add($param2);
			}elseif($param1 == 'addToVoucher'){
				$this->addToVoucher();
			}elseif($param1 == 'getFromVoucher'){
				$this->getFromVoucher();
			}elseif($param1=='save'){	
				$this->save($param2);
			}else{
				$this->notFound();
			}

		}else{
			$this->notAuthorized();
		}
	}

	//--------------------------------voucher module functins start----------------------------
	public function add($trip_id = gINVALID)
	{
		$this->tour_voucher->destroy();
		if($this->session_check()==true) {

			$trip = $this->tour_model->getTrip($trip_id);
			if($trip){//valid trip

				$voucher = $this->voucher_model->getTripVoucher($trip_id);
				$items = array();
				if($voucher){//get voucher items
					$trip_voucher_id = $voucher['id'];
					$items = $this->voucher_model->getVoucherDataAll($trip_voucher_id);
				}
			
				//create voucher cart
				$this->tour_voucher->create($trip_id,$items);

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
				$data['trip_id'] = $trip_id;
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

	function getFromVoucher(){
		$voucher = $this->tour_voucher->contents();
		$this->build_itinerary_data($voucher,$ajax = 'YES');
	}


	function addToVoucher()//from ajax call
	{	//echo "<pre>";print_r($_REQUEST);echo "</pre>";exit;

		if(isset($_REQUEST['table'])){
			$tble = $_REQUEST['table'];
			$fields = $_REQUEST;

			array_shift($fields);//pop first element(url data from ajax call)
			unset($fields['table']);
			$fields['id'] = gINVALID;
			$data[$tble] = $fields;
			
			$this->tour_voucher->insert($data);		
				
		}
		$voucher = $this->tour_voucher->contents();
		$this->build_itinerary_data($voucher,$ajax = 'YES');
	}


	//save voucher action
	function save()
	{
		$trip_id = $this->tour_voucher->tripId();
		$saveVoucher = $this->voucher_model->saveVoucherCart($this->tour_voucher);
		$this->tour_voucher->destroy();
		if($saveVoucher){
			$this->session->set_userdata(array('dbSuccess'=>'Voucher Added Succesfully..!')); 
			$this->session->set_userdata(array('dbError'=>''));
		}else{
			$this->session->set_userdata(array('dbSuccess'=>'')); 
			$this->session->set_userdata(array('dbError'=>'Invalid Trip Voucher..!'));
		}

		redirect(base_url().'front-desk/voucher/add/'.$trip_id);
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


	function build_itinerary_data($voucher,$ajax = 'NO'){

		//echo "<pre>";print_r($voucher);echo "</pre>";exit;
		if($voucher){			
			$tableData['th'] = array(
					array('label'=>'SlNo','attr'=>'width="5%"'),
					array('label'=>'Date','attr'=>'width="10%"'),
					array('label'=>'Particulars','attr'=>''),
					array('label'=>'Unit Amt','attr'=>'width="10%"'),
					array('label'=>'Tax','attr'=>'width="10%"'),
					array('label'=>'Total','attr'=>'width="10%"'),
					);
			$tableData['tr'] = array();
			$slno =1;$edit = false;
			foreach($voucher as $table=>$rows){
				
				//echo "<pre>";print_r($rows);echo "</pre>";exit;
				foreach($rows as $row){
					$totAmt = (double)$row['unit_amount'] + (double)$row['tax_amount'] - (double)$row['advance_amount'];
					$tr = array($slno,
						$row['from_date'],
						$row['narration'],
						number_format((double)$row['unit_amount'],2),
						number_format((double)$row['tax_amount'],2),
						number_format((double)$totAmt,2)
						);

					//add edit link if need
					if($row['id'] > 0){
						$link = '<a class="edit-voucher-itr" itr-id ="'.$row['id'].'" itr-table="'.$table.'" href="#">Edit</a>';
						array_push($tr,$link);
						$edit = true;
					}
					array_push($tableData['tr'],$tr);

					


					$slno++;
				}	
			} 
			
			if($edit){
				array_push($tableData['th'],array('label'=>'','attr'=>''));
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
