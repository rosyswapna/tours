<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 * @package		CodeIgniter
 * @author		Swapna, Acube Innovations
 */
class CI_Tour_cart {

	// Private variables.  Do not change!
	var $CI;
	var $trip_id=false;
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

		// Grab the shopping cart array from the session table, if it exists
		if ($this->CI->session->userdata('tour_cart_contents') !== FALSE)
		{
			$this->_tour_cart_contents = $this->CI->session->userdata('tour_cart_contents');
		}
		else
		{
			// No cart exists so we'll set some base values
			$this->_tour_cart_contentss['tour_cart_total'] = 0;
			$this->_tour_cart_contents['total_itineraries'] = 0;
		}

		log_message('debug', "Tour Cart Class Initialized");
	}

	function create($items = array()) {
		if ( ! is_array($items) OR count($items) == 0)
		{
			log_message('error', 'The create method must be passed an array containing data.');
			return FALSE;
		}
		$this->_tour_cart_contents = $items;
		$this->CI->session->set_userdata(array('tour_cart_contents' => $this->_tour_cart_contents));
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

		$this->CI->session->unset_userdata('tour_cart_contents');
	}


}

/* End of file Tour_cart.php */
/* Location: ./application/libraries/Tour_cart.php */
