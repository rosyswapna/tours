<?php 
class Date_functions{

	//convert season date to mysql format
	function seasonDate_to_mysqlDate($dateString = '')
	{
		$CI = & get_instance();
		if($dateString == '')
			return false;

		$date= $dateString.' '.date("Y");
                return date('Y-m-d', strtotime($date));
	}

	//convert mysql date to season date
	function mysqlDate_to_seasonDate($date = '')
	{
		$CI = & get_instance();
		$_dates = explode('-',date('Y-M-d', strtotime($date)));

		return $_dates[2]. ' '.$_dates[1];
	}
}
?>
