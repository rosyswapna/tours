<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 * @package		CodeIgniter
 * @author		Swapna, Acube Innovations
 */
class CI_Tour_voucher {

	// Private variables.  Do not change!
	var $CI;
	var $trip_id=gINVALID;
	var $_itineraries = array();
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
			$this->_tour_voucher_contents['tour_voucher_total'] = 0;
			$this->_tour_voucher_contents['total_itineraries'] = 0;
		}

		log_message('debug', "Tour voucher Class Initialized");
	}

	function create($items = array()) {
		if ( ! is_array($items) OR count($items) == 0)
		{
			log_message('error', 'The create method must be passed an array containing data.');
			return FALSE;
		}

		
		//echo "<pre>";print_r($items);echo "</pre>";exit;
		$this->_tour_voucher_contents = $items;
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
		// Unset these so our total can be calculated correctly below
		unset($this->_tour_voucher_contents['tour_voucher_total']);
		unset($this->_tour_voucher_contents['total_itineraries']);

		// Is our voucher empty?  If so we delete it from the session
		if (count($this->_tour_voucher_contents) <= 0)
		{
			$this->CI->session->unset_userdata('tour_voucher_contents');

			// Nothing more to do... coffee time!
			return FALSE;
		}
		$total_itinerary = 0;
		foreach($this->_tour_voucher_contents as $voucher_itinerary){
			$total_itinerary++;
			
		}
		$this->_tour_voucher_contents['total_itineraries'] = $total_itinerary;

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
		unset($voucher['tour_voucher_total']);
		unset($voucher['total_itineraries']);

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

		$this->_tour_voucher_contents['tour_voucher_total'] = 0;
		$this->_tour_voucher_contents['total_itineraries'] = 0;

		$this->CI->session->unset_userdata('tour_voucher_contents');
	}


}

/* End of file Tour_voucher.php */
/* Location: ./application/libraries/Tour_voucher.php */
