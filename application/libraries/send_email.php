<?php 
class Send_email{

	function emailMe($email,$subject,$message){
	$CI = & get_instance();
	$CI->load->model('trip_booking_model');
	$config['protocol'] = 'sendmail';
    $config['charset'] = 'iso-8859-1';
	$config['mailpath'] = '/usr/sbin/sendmail';
    $config['wordwrap'] = TRUE;
    $config['mailtype'] = 'html';
	$CI->email->initialize($config);
	//$CI->email->from(SYSTEM_EMAIL, PRODUCT_NAME);
	$org_id=$CI->session->userdata('organisation_id'); 
	$system_mail_arry=$CI->trip_booking_model->get_System_mail($org_id);
	$system_mail=$system_mail_arry[0]->system_email;	
	$CI->email->from($system_mail, PRODUCT_NAME);
	$CI->email->to($email);
	$CI->email->subject($subject);
	$CI->email->message($message);
	//echo $message;
	$CI->email->send();
	 //echo $CI->email->print_debugger();
	return true;

	}

}
?>
