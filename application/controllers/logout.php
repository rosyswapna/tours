<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Logout extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	

	function index() 
	{ 
		$this->load->helper('url');
		if($this->session->userdata('isLoggedIn')==true && $this->session->userdata('type')==SYSTEM_ADMINISTRATOR){
			$logout_redirect_url=base_url().'syslogin';
			$ret = 1;
	  	}else if($this->session->userdata('isLoggedIn')==true && ($this->session->userdata('type')==ORGANISATION_ADMINISTRATOR ||$this->session->userdata('type')==FRONT_DESK)){
			$logout_redirect_url=base_url();
			$ret = 2;
	  	}
	  	//ob_start();
      		$this->session->sess_destroy();
	  
	  	$this->mysession->destroy();
	  
		redirect(base_url()."facnc/access/logout.php?ret=".$ret);
		///ob_end_clean();
	     // redirect($logout_redirect_url);
    	}
}
