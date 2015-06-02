<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 * @package		CodeIgniter
 * @author		Swapna, Acube Innovations
 */
class CI_Tour_cart {

	// Private variables.  Do not change!
	var $CI;
	var $trip_id=gINVALID;
	var $_itineraries = array();
	var $_tour_cart_contents = array();
	

	public function __construct($params = array())
	{
		// Set the super object to a local variable for use later
		$this->CI =& get_instance();

		// Are any config settings being passed manually?  If so, set them
		$config = array();
		if (count($params) > 0)
		{
			foreach ($params as $key => $val)
			{
				$config[$key] = $val;
			}
		}

		// Load the Sessions class
		$this->CI->load->library('session', $config);
		//echo "<pre>";print_r($this->CI->session->userdata('tour_cart_contents'));echo "</pre>";

		// Grab the shopping cart array from the session table, if it exists
		if ($this->CI->session->userdata('tour_cart_contents') !== FALSE)
		{
			$this->_tour_cart_contents = $this->CI->session->userdata('tour_cart_contents');
		}
		else
		{
			// No cart exists so we'll set some base values
			$this->_tour_cart_contents['tour_cart_total'] = 0;
			$this->_tour_cart_contents['total_itineraries'] = 0;
			$this->_tour_cart_contents['delete_itineraries'] = array();
			$this->_tour_cart_contents['estimate'] = array();
		}

		log_message('debug', "Tour Cart Class Initialized");
	}

	function create($items = array()) {
		if ( ! is_array($items) OR count($items) == 0)
		{
			log_message('error', 'The create method must be passed an array containing data.');
			return FALSE;
		}

		
		//echo "<pre>";print_r($items);echo "</pre>";exit;
		$this->_tour_cart_contents = $items;
		
		foreach ($items as $itinerary=>$tableArr){ 
			
			foreach ($tableArr as $table=>$data){
				foreach ($data as $index=>$dataArr){
				
				$this->build_estimate($table,$itinerary,$index,$dataArr);
				}
			}
			
		}
		//echo "<pre>";print_r($this->_tour_cart_contents['estimate']);echo "</pre>";exit;
		$this->_tour_cart_contents['delete_itineraries'] = array();
		//$this->_tour_cart_contents['estimate'] = array();
		$this->_tour_cart_contents['tour_cart_total'] = 0;
		$this->_tour_cart_contents['total_itineraries'] = 0;
		$this->save_cart();
	}


	function insert($items,$itinerary){ //new cart item
		if ( ! is_array($items) OR count($items) == 0)
		{
			log_message('error', 'The insert method must be passed an array containing data.');
			return FALSE;
		}
		
		foreach($items as $tbl=>$dataArr){
				
			$this->_tour_cart_contents[$itinerary][$tbl][] = $dataArr;
			
			end($this->_tour_cart_contents[$itinerary][$tbl]);
			$index=key($this->_tour_cart_contents[$itinerary][$tbl]); 
			$this->build_estimate($tbl,$itinerary,$index,$dataArr);
			
		}
			$this->save_cart();
			
		} 
		
		
	function build_estimate($tbl,$itinerary,$index,$dataArr){ // print_r($dataArr);exit;
		if($tbl=='trip_accommodation'){  //----------------------------------------Accommodation-------------------------------------
			
				$from_cart=array(
					'itinerary'=>$itinerary,
					'table'=>$tbl,
					'index'=>$index
					);
				//echo "<pre>";print_r($this->_tour_cart_contents['estimate']['accommodation']);echo "</pre>";exit;
				
				if(isset($this->_tour_cart_contents['estimate']['accommodation'])){ 
					$temp=$this->_tour_cart_contents['estimate']['accommodation'];
				
						foreach($temp as $key=>$acm){   
						
								
								if($acm['hotel_id']==$dataArr['hotel_id'] && $acm['room_type_id']==$dataArr['room_type_id']){
									$row=1;
									$acm['from_cart'][]=$from_cart;
									$this->_tour_cart_contents['estimate']['accommodation'][$key]=$acm; 
								}
						
							//echo "<pre>";print_r($this->_tour_cart_contents['estimate']['accommodation']);echo "</pre>";exit;
						}
						if( !isset($row)){
							$this->_tour_cart_contents['estimate']['accommodation'][]=array(
									'hotel_id'=>$dataArr['hotel_id'],
									'room_type_id'=>$dataArr['room_type_id'],
									'from_cart'=>array($from_cart),
									
									);
						}
					
					
					
				
				}
				if(!isset($this->_tour_cart_contents['estimate']['accommodation'])){ 
					$this->_tour_cart_contents['estimate']['accommodation'][]=array(
						'hotel_id'=>$dataArr['hotel_id'],
						'room_type_id'=>$dataArr['room_type_id'],
						'from_cart'=>array($from_cart),
						);
				}
				
			}elseif($tbl=='trip_vehicles'){ //----------------------------------------Vehicle-------------------------------------
				$from_cart=array(
					'itinerary'=>$itinerary,
					'table'=>$tbl,
					'index'=>$index
					);
				if(isset($this->_tour_cart_contents['estimate']['vehicles'])){ 
					$temp=$this->_tour_cart_contents['estimate']['vehicles'];
				
						foreach($temp as $key=>$vehicle){  
						
								
								if($vehicle['vehicle_model_id']==$dataArr['vehicle_model_id'] && $vehicle['vehicle_ac_type_id']==$dataArr['vehicle_ac_type_id'] && $vehicle['vehicle_id']==$dataArr['vehicle_id']){
									$row=1;
									$vehicle['from_cart'][]=$from_cart;
									$this->_tour_cart_contents['estimate']['vehicles'][$key]=$vehicle; 
								}
						
							
						}
						if( !isset($row)){
							$this->_tour_cart_contents['estimate']['vehicles'][]=array(
									'vehicle_model_id'=>$dataArr['vehicle_model_id'],
									'vehicle_ac_type_id'=>$dataArr['vehicle_ac_type_id'],
									'vehicle_id'=>$dataArr['vehicle_id'],
									'from_cart'=>array($from_cart),
									
									);
						}
					
					
					
				
				}
				if(!isset($this->_tour_cart_contents['estimate']['vehicles'])){ 
					$this->_tour_cart_contents['estimate']['vehicles'][]=array(
						'vehicle_model_id'=>$dataArr['vehicle_model_id'],
						'vehicle_ac_type_id'=>$dataArr['vehicle_ac_type_id'],
						'vehicle_id'=>$dataArr['vehicle_id'],
						'from_cart'=>array($from_cart),
						);
				}
				
			}elseif($tbl=='trip_services'){ //----------------------------------------Services-------------------------------------
				$from_cart=array(
					'itinerary'=>$itinerary,
					'table'=>$tbl,
					'index'=>$index
					);
				if(isset($this->_tour_cart_contents['estimate']['services'])){ 
					$temp=$this->_tour_cart_contents['estimate']['services'];
				
						foreach($temp as $key=>$service){   
						
								
								if($service['service_id']==$dataArr['service_id'] && $service['amount']==$dataArr['amount']){
									$row=1;
									$service['from_cart'][]=$from_cart;
									$this->_tour_cart_contents['estimate']['services'][$key]=$service; 
								}
						
							
						}
						if( !isset($row)){
							$this->_tour_cart_contents['estimate']['services'][]=array(
									'service_id'=>$dataArr['service_id'],
									'amount'=>$dataArr['amount'],
									'from_cart'=>array($from_cart),
									
									);
						}
					
					
					
				
				}
				if(!isset($this->_tour_cart_contents['estimate']['services'])){ 
					$this->_tour_cart_contents['estimate']['services'][]=array(
						'service_id'=>$dataArr['service_id'],
						'amount'=>$dataArr['amount'],
						'from_cart'=>array($from_cart),
						);
				}
				//echo "<pre>";print_r($this->_tour_cart_contents['estimate']['services']);exit;
			}elseif($tbl=='trip_destinations'){
				$this->_tour_cart_contents['estimate']['destinations']=array();
				
			}
			//echo "<pre>";print_r($this->_tour_cart_contents['estimate']);exit;
	}
		
	
	function update($tble,$items,$itinerary,$index){//update cart item
		if ( ! is_array($items) OR count($items) == 0)
		{
			log_message('error', 'The insert method must be passed an array containing data.');
			return FALSE;
		}
		
		$cart=$this->contents();
		if(isset($cart[$itinerary][$tble][$index])){ //echo "<pre>";print_r($cart[$itinerary][$tble][$index]);exit;
		
			$this->_tour_cart_contents[$itinerary][$tble][$index]=$items;
			$this->build_estimate($tble,$itinerary,$index,$items);
		}
		$this->save_cart();
	}
	
	//delete from cart
	function delete($itinerary,$tbl,$index,$delete_id){
		
		$cart=$this->contents();
		if(isset($cart[$itinerary][$tbl][$index])){
			unset($this->_tour_cart_contents[$itinerary][$tbl][$index]);
			
			$delete_items = $this->delete_itineraries();
			if(is_numeric($delete_id) && $delete_id > 0){
				$delete_items[$tbl][] = $delete_id;
			}
			$this->_tour_cart_contents['delete_itineraries'] = $delete_items;
			
		}
		$this->save_cart();

	}


	// select value by index
	function select($itinerary,$tble,$index){
		$cart=$this->contents();
		//print_r($this->CI->session->userdata('tour_cart_contents'));exit;
		if(isset($cart[$itinerary][$tble][$index])){
			return $cart[$itinerary][$tble][$index];
		}
		else{
			return false;
		}
	
	}
	
	function save_cart()
	{
		// Unset these so our total can be calculated correctly below
		unset($this->_tour_cart_contents['tour_cart_total']);
		unset($this->_tour_cart_contents['total_itineraries']);
		$delete_itineraries = $this->_tour_cart_contents['delete_itineraries'];
		unset($this->_tour_cart_contents['delete_itineraries']);
		$estimate = $this->_tour_cart_contents['estimate']; // echo "<pre>";print_r($estimate);echo "</pre>";exit;
		unset($this->_tour_cart_contents['estimate']);

		// Is our cart empty?  If so we delete it from the session
		if (count($this->_tour_cart_contents) <= 0)
		{
			$this->CI->session->unset_userdata('tour_cart_contents');

			// Nothing more to do... coffee time!
			return FALSE;
		}
		$total_itinerary = 0; 
		foreach($this->_tour_cart_contents as $cart_itinerary){
			$total_itinerary++;
			
		}
		$this->_tour_cart_contents['total_itineraries'] = $total_itinerary;
		$this->_tour_cart_contents['delete_itineraries'] = $delete_itineraries;
		$this->_tour_cart_contents['estimate'] = $estimate;
		
		$this->CI->session->set_userdata(array('tour_cart_contents' => $this->_tour_cart_contents));
		//echo "<pre>";print_r($this->CI->session->userdata("tour_cart_contents"));echo "</pre>";exit;
		
		// Woot!
		return TRUE;
	}

	/**
	 * Total Items
	 *
	 * Returns the total item count
	 *
	 * @access	public
	 * @return	integer
	 */
	function total_itineraries()
	{
		return $this->_tour_cart_contents['total_itineraries'];
	}

	/**
	 * Total Items
	 *
	 * Returns the delete items rows by table index(Eg: [table]=array(id1,id2))
	 *
	 * @access	public
	 * @return	integer
	 */
	function delete_itineraries()
	{
		return $this->_tour_cart_contents['delete_itineraries'];
	}
	
	function estimate(){
		return $this->_tour_cart_contents['estimate'];
	}

	// --------------------------------------------------------------------

	/**
	 * Cart Contents
	 *
	 * Returns the entire cart array
	 *
	 * @access	public
	 * @return	array
	 */
	function contents()
	{
		$cart = $this->_tour_cart_contents;
		
		// Remove these so they don't create a problem when showing the cart table
		unset($cart['tour_cart_total']);
		unset($cart['total_itineraries']);
		unset($cart['delete_itineraries']);
		unset($cart['estimate']);

		return $cart;
	}


	// --------------------------------------------------------------------

	/**
	 * Destroy the cart
	 *
	 * Empties the cart and kills the session
	 *
	 * @access	public
	 * @return	null
	 */
	function destroy()
	{
		unset($this->_tour_cart_contents);

		$this->_tour_cart_contents['tour_cart_total'] = 0;
		$this->_tour_cart_contents['total_itineraries'] = 0;
		$this->_tour_cart_contents['delete_itineraries'] = array();
		$this->_tour_cart_contents['estimate'] = array();
		$this->CI->session->unset_userdata('tour_cart_contents');
	}


}

/* End of file Tour_cart.php */
/* Location: ./application/libraries/Tour_cart.php */
