<?php 
class Sms{

function sendSms($phone,$message){

//$url="http://enterprise.smsgupshup.com/GatewayAPI/rest?method=SendMessage&send_to=".$phone."&msg=".urlencode($message)."&msg_type=TEXT&userid=2000140534&auth_scheme=plain&password=HvI5XjA98&v=1.1&format=text";
		
		$CI = & get_instance();
		$CI->load->model('trip_booking_model');
		$org_id=$CI->session->userdata('organisation_id');
		$url_arry=$CI->trip_booking_model->get_URL($org_id);
		$url=$url_arry[0]->sms_gateway_url;
		if($phone!='' && $message!=''){
		$old_val=array('".$phone."','".$message."');
		$message=urlencode($message);
		$new_val=array($phone,$message);
		$url=str_replace($old_val,$new_val,$url);
		}
		
		$ch=curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$output=curl_exec($ch);
		curl_close($ch);                                
		return $output;

}

}
?>
