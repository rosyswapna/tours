<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 * @package		CodeIgniter
 * @author		Swapna, Acube Innovations
 */
class CI_Tour_voucher {

	// Private variables.  Do not change!
	var $CI;
	var $_tour_voucher_contents = array();
	

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
		//echo "<pre>";print_r($this->CI->session->userdata('tour_voucher_contents'));echo "</pre>";

		// Grab the shopping voucher array from the session table, if it exists
		if ($this->CI->session->userdata('tour_voucher_contents') !== FALSE)
		{
			$this->_tour_voucher_contents = $this->CI->session->userdata('tour_voucher_contents');
		}
		else
		{
			// No voucher exists so we'll set some base values
			$this->_tour_voucher_contents['total_itineraries'] = 0;
			$this->_tour_voucher_contents['totals'] = array();
			$this->_tour_voucher_contents['trip_id'] = gINVALID;
		}

		log_message('debug', "Tour voucher Class Initialized");
	}

	function create($trip_id,$items= array()) {
		if ( ! is_numeric($trip_id) OR $trip_id <= 0)
		{
			log_message('error', 'The create method must be passed a valid trip id.');
			return FALSE;
		}

		if (  is_array($items) OR count($items) > 0){
			$this->_tour_voucher_contents = $items;
		}

		$this->_tour_voucher_contents['trip_id'] = $trip_id;

		$this->save_voucher();
	}


	function insert($items){//new voucher item
		if ( ! is_array($items) OR count($items) == 0)
		{
			log_message('error', 'The insert method must be passed an array containing data.');
			return FALSE;
		}
		
		foreach($items as $tbl=>$dataArr){
			$this->_tour_voucher_contents[$tbl][] = $dataArr;
		}
		
		$this->save_voucher();

		
	}

	function save_voucher()
	{

		$trip_id = $this->_tour_voucher_contents['trip_id'];
		// Unset these so our total can be calculated correctly below
		unset($this->_tour_voucher_contents['total_itineraries']);
		unset($this->_tour_voucher_contents['totals']);
		unset($this->_tour_voucher_contents['trip_id']);

		// Is our voucher empty?  If so we delete it from the session
		if ($trip_id <= 0)
		{
			$this->CI->session->unset_userdata('tour_voucher_contents');

			// Nothing more to do... coffee time!
			return FALSE;
		}
		$total_itinerary = 0;
		$totals = array('total_accommodation_amount' => 0,
				'total_travel_amount' => 0,
				'total_service_amount' => 0,
				'total_trip_amount' => 0,
				'total_trip_km' =>0
				);
		foreach($this->_tour_voucher_contents as $table=>$voucher_itinerary){
			$total_itinerary++;

			foreach($voucher_itinerary as $dataArray){
				$totalAmt = (double)$dataArray['unit_amount']+(double)$dataArray['tax_amount']-(double)$dataArray['advance_amount'];
				switch($table){
					case 'trip_voucher_vehicles':
						$totals['total_travel_amount'] += $totalAmt;
						$vehicle_km = (double)$dataArray['end_km'] - (double)$dataArray['start_km'];
						$totals['total_trip_km'] += $vehicle_km;
						break;
					case 'trip_voucher_accommodation':
						$totals['total_accommodation_amount'] += $totalAmt;
						break;
					case 'trip_voucher_services':
						$totals['total_service_amount'] += $totalAmt;
						break;
				}
				$totals['total_trip_amount'] += $totalAmt;	
			}
	
		}
		$this->_tour_voucher_contents['total_itineraries'] = $total_itinerary;
		$this->_tour_voucher_contents['totals'] = $totals;
		$this->_tour_voucher_contents['trip_id'] = $trip_id;

		$this->CI->session->set_userdata(array('tour_voucher_contents' => $this->_tour_voucher_contents));

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
		return $this->_tour_voucher_contents['total_itineraries'];
	}


	/**
	 * Total amounts foreach itinerary and trip total kilometer from vehicle itinerary
	 *
	 * Returns the totals array for trip_voucher
	 *
	 * @access	public
	 * @return	array
	 */
	function totals()
	{
		return $this->_tour_voucher_contents['totals'];
	}


	/**
	 * voucher trip id
	 *
	 * Returns the trip id
	 *
	 * @access	public
	 * @return	numeric
	 */
	function tripId()
	{
		return $this->_tour_voucher_contents['trip_id'];
	}

	// --------------------------------------------------------------------

	/**
	 * voucher Contents
	 *
	 * Returns the entire voucher array
	 *
	 * @access	public
	 * @return	array
	 */
	function contents()
	{
		$voucher = $this->_tour_voucher_contents;

		// Remove these so they don't create a problem when showing the voucher table
		unset($voucher['totals']);
		unset($voucher['total_itineraries']);
		unset($voucher['trip_id']);

		return $voucher;
	}


	// --------------------------------------------------------------------

	/**
	 * Destroy the voucher
	 *
	 * Empties the voucher and kills the session
	 *
	 * @access	public
	 * @return	null
	 */
	function destroy()
	{
		unset($this->_tour_voucher_contents);

		$this->_tour_voucher_contents['totals'] = array();
		$this->_tour_voucher_contents['total_itineraries'] = 0;
		$this->_tour_voucher_contents['trip_id'] = gINVALID;

		$this->CI->session->unset_userdata('tour_voucher_contents');
	}


}

/* End of file Tour_voucher.php */
/* Location: ./application/libraries/Tour_voucher.php */
