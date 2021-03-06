<?php
function get_organisation_login($user_id){
	set_global_connection();

	$sql = "SELECT organisation_id,username,password FROM users WHERE id=".db_escape($user_id);
	$sql .= " AND fa_account > 0";
		//echo $sql;exit;


	$result = db_query($sql, "could not get user");


	return db_fetch_row($result);
}

function get_cnc_session($session_id)
{
	set_global_connection();

	$sql = "SELECT * FROM tour_sessions WHERE session_id='".$session_id."'";


	$result = db_query($sql, "could not get user");


	return db_fetch($result);
}

function get_cnc_organization($org_id=-1)
{
	$sql = "SELECT org.*,user.phone,user.email FROM organisations org";
	$sql .= " LEFT JOIN users user ON org.id = user.organisation_id AND user.user_type_id = ".CNC_ORG_ADMIN;
	$sql .=" WHERE org.id='".$org_id."'";
	
	//echo $sql;exit;

	$result = db_query($sql,"could not get company information");

	return db_fetch($result);
}

function sync_cnc_org_login($conn, $username,$password)
{
	$sql = "UPDATE ".$conn['tbpref']."users SET password=".db_escape($password) . ",user_id = ".db_escape($username) . " WHERE user_id='admin'";
	db_query($sql, "could not update user password for 'admin'");
}

function get_cnc_org_admin($org_id = -1)
{
	$sql = "SELECT * FROM users WHERE organisation_id=".db_escape($org_id)." AND user_type_id = 2";


	$result = db_query($sql, "could not get user");


	return db_fetch_assoc($result);
}

function update_fa_account_in_organisations($org_id = -1)
{
	$sql = "UPDATE organisations SET fa_account = 1"
		." WHERE id=".db_escape($org_id);
	db_query($sql, "Organisation updated");
}
function update_fa_account_in_users($user_id = -1,$id=0)
{
	$sql = "UPDATE users SET fa_account = ".db_escape($id)
		." WHERE id=".db_escape($user_id);
	db_query($sql, "User updated");
}

function get_cnc_user($userid)
{
	$sql = "SELECT * FROM users WHERE id =".db_escape($userid);

	$result = db_query($sql, "could not get user");


	return db_fetch_assoc($result);
}

//get trip voucher details

/*
function get_cnc_voucher($id = 0)
{
	$sql = "SELECT 
		cust.id AS cnc_cust_id,
		cust.name AS customer_name,
		IFNULL(guest.name,trip.guest_name) AS username,
		custg.name AS group_name,
		custg.id AS group_id,
		vehicle.registration_number AS vehicle_no,
		tripmd.name AS trip_model,
		trip.id AS trip_id,
		trip.pick_up_city AS from_city,
		trip.drop_city AS to_city,
		trip.pick_up_date,
		trip.advance_amount,
		trip.payment_no,
		voucher.trip_expense,
		voucher.driver_bata,
		voucher.night_halt_charges as night_halt,
		voucher.id as voucher_no,
		voucher.voucher_no as voucher_str,
		(voucher.end_km_reading-voucher.start_km_reading) AS total_km_reading,
		voucher.total_trip_amount as amount,
		voucher.no_of_days,
		voucher.trip_narration,
		voucher.payment_type_id,
		voucher.tax_group_id,
		vmodels.name as vehicle_model
		";
	$sql .="  FROM trip_vouchers voucher ";
	$sql .= " LEFT JOIN trips trip ON voucher.trip_id = trip.id";
	$sql .= " LEFT JOIN trip_models tripmd ON tripmd.id = trip.trip_model_id";
	$sql .= " LEFT JOIN vehicles vehicle ON vehicle.id = trip.vehicle_id";
	$sql .= " LEFT JOIN vehicle_models vmodels ON vmodels.id = voucher.vehicle_model_id";
	$sql .= " LEFT JOIN tariffs ON tariffs.id = trip.tariff_id";
	$sql .= " LEFT JOIN tariff_masters ON tariff_masters.id = tariffs.tariff_master_id";
	$sql .= " LEFT JOIN customers cust ON cust.id = trip.customer_id";
	$sql .= " LEFT JOIN customers guest ON guest.id = trip.guest_id";
	$sql .= " LEFT JOIN customer_groups custg ON custg.id = trip.customer_group_id";
	$sql .= " WHERE voucher.id = ".db_escape($id);
	$result = db_query($sql, "could not get voucher");

	return db_fetch_assoc($result);
}
*/

function get_cnc_voucher($id = 0)
{
	$sql = "SELECT voucher.*
		";
	$sql .="  FROM trip_vouchers voucher ";
	$sql .= " LEFT JOIN trips trip ON voucher.trip_id = trip.id";
	$sql .= " WHERE voucher.id = ".db_escape($id);
	$result = db_query($sql, "could not get voucher");

	return db_fetch_assoc($result);
}


function get_voucher_itinerary($id = 0)
{
	$voucherItr = array();

	$sql = "SELECT trip.customer_id,voucher.id as voucher_no,voucher.delivery_no,voucher.invoice_no,
		voucher.voucher_no as voucher_str,cust.name AS customer_name
		FROM trip_vouchers voucher,trips trip
		LEFT JOIN customers cust ON cust.id = trip.customer_id
		WHERE voucher.id= ".db_escape($id)." AND voucher.trip_id = trip.id";
	$result = db_query($sql,'Could not get Voucher');
	//echo $sql ;exit;
	if(db_num_rows($result) == 1){
		$voucherItr['voucher'] = db_fetch_assoc($result);
		//voucher vehicles
		$sql1 = "SELECT * FROM trip_voucher_vehicles WHERE trip_voucher_id = ".db_escape($id);
		$result1 = db_query($sql1,'Could not get voucher vehicles');
		if(db_num_rows($result1) > 0){
			while($vehicle = db_fetch_assoc($result1)){
				if($vehicle['trip_expense'] != '')
					$vehicle['trip_expense'] = unserialize($vehicle['trip_expense']);

				$vehicle['table'] = 'trip_voucher_vehicles';
				$voucherItr['travel'][] = $vehicle;
			}
		}

		//voucher hotels
		$sql2 = "SELECT * FROM trip_voucher_accommodation WHERE trip_voucher_id = ".db_escape($id);
		$result2 = db_query($sql2,'Could not get voucher hotels');
		if(db_num_rows($result2) > 0){
			while($accommodation = db_fetch_assoc($result2)){
				if($accommodation['room_attributes'] != '')
					$accommodation['room_attributes'] = unserialize($accommodation['room_attributes']);
				if($accommodation['meals_package'] != '')
					$accommodation['meals_package'] = unserialize($accommodation['meals_package']);
				$accommodation['table'] = 'trip_voucher_accommodation';
				$voucherItr['accommodation'][] = $accommodation;
			}
		}

		//voucher trip services
		$sql3 = "SELECT * FROM trip_voucher_services WHERE trip_voucher_id = ".db_escape($id);
		$result3 = db_query($sql3,'Could not get voucher services');
		if(db_num_rows($result3) > 0){
			while($service = db_fetch_assoc($result3)){
				$service['table'] = 'trip_voucher_services';
				$voucherItr['services'][] = $service;
			}
		}
		//echo "<pre>";print_r($voucherItr);echo "</pre>";exit;
		return $voucherItr;

	}else{
		return false;
	}
	
}

//get voucher itinerary table row with table and id
function get_voucher_itinerary_detail($table,$id){

	$sql = "SELECT * FROM ".$table." WHERE id = ".db_escape($id);
	$result = db_query($sql, "could not get voucher itinerary");
	
	return $row = db_fetch_assoc($result);
}

function get_cnc_customer_id($ref = '')
{
	$sql = "SELECT debtor_no FROM ".TB_PREF."debtors_master WHERE debtor_ref = ".db_escape($ref);
//echo $sql;exit;
	$result = db_query($sql, "could not get voucher");
	
	$row = db_fetch_assoc($result);

	return $row['debtor_no'];
}

function get_cnc_customer_branch($debtor_no = '')
{
	$sql = "SELECT branch_code FROM ".TB_PREF."cust_branch WHERE debtor_no = ".db_escape($debtor_no);
//echo $sql;exit;
	$result = db_query($sql, "could not get voucher");
	
	$row = db_fetch_assoc($result);

	return $row['branch_code'];
}


function get_cnc_supplier_id($ref = '')
{
	$sql = "SELECT supplier_id FROM ".TB_PREF."suppliers WHERE supp_ref = ".db_escape($ref);
//echo $sql;exit;
	$result = db_query($sql, "could not get voucher");
	
	$row = db_fetch_assoc($result);

	return $row['supplier_id'];
}

function get_trip_voucher_id_with_delivery_no($delivery_no){
	$sql = "SELECT v.id FROM trip_vouchers v,".TB_PREF."debtor_trans dt 
		WHERE dt.trans_no = ".db_escape($delivery_no)."
		AND dt.type=".ST_CUSTDELIVERY." AND v.trip_id = dt.reference";
	
	$result = db_query($sql, "could not get voucher");
	$row = db_fetch_row($result);

	return $row[0];
}

//get single voucher with invoice number
function get_voucher_with_invoice($inv_no){
	$sql = "SELECT v.*,vh.vehicle_owner_id FROM trip_vouchers v
		LEFT JOIN trips tr ON tr.id = v.trip_id
		LEFT JOIN vehicles vh ON vh.id = tr.vehicle_id
		WHERE v.invoice_no = ".db_escape($inv_no);
	
	$result = db_query($sql, "could not get voucher");
	$row = db_fetch_assoc($result);

	return $row;
}



//get invoiced trip vouchers
function get_vouchers_with_invoice($inv_no){
	$sql = "SELECT voucher.*,vh.vehicle_owner_id
		FROM trip_vouchers voucher
		LEFT JOIN trips tr ON tr.id = voucher.trip_id
		LEFT JOIN vehicles vh ON vh.id = tr.vehicle_id
		WHERE voucher.id IN(SELECT trip_voucher FROM ".TB_PREF."debtor_trans_details WHERE debtor_trans_no =".db_escape($inv_no)." AND debtor_trans_type = ".ST_SALESINVOICE.")";
	
	$result = db_query($sql, "could not get vouchers");
	$data = array();
	while($row = db_fetch_assoc($result)){
		$data[] = $row;
	}

	return $data;
}

//get invoiced trip vouchers by driver
/*
function get_driver_vouchers_with_invoice($inv_no){
	$sql = "SELECT voucher.*,driver.name as driver_name
		FROM trip_vouchers voucher
		LEFT JOIN trips tr ON tr.id = voucher.trip_id
		LEFT JOIN vehicles vh ON vh.id = tr.vehicle_id
		LEFT JOIN drivers driver ON driver.id = voucher.driver_id
		WHERE voucher.id IN(SELECT trip_voucher FROM ".TB_PREF."debtor_trans_details WHERE debtor_trans_no =".db_escape($inv_no)." AND debtor_trans_type = ".ST_SALESINVOICE.") AND voucher.driver_payment_amount > 0";
	
	
	$result = db_query($sql, "could not get vouchers");
	$data = array();
	while($row = db_fetch_assoc($result)){
		if($row['driver_id'] > 0)
			$data[$row['driver_id']][] = $row;	
	}

	return $data;
}*/
function get_driver_vouchers_with_invoice($inv_no){
	$sql = "SELECT voucher_vehicle.*,driver.name as driver_name
		FROM trip_voucher_vehicles voucher_vehicle
		LEFT JOIN trip_vouchers voucher ON voucher.id = voucher_vehicle.trip_voucher_id
		LEFT JOIN vehicles vh ON vh.id = voucher_vehicle.vehicle_id
		LEFT JOIN drivers driver ON driver.id = voucher_vehicle.driver_id
		WHERE voucher.invoice_no = ".db_escape($inv_no);
	
	
	$result = db_query($sql, "could not get vouchers");
	$data = array();
	while($row = db_fetch_assoc($result)){
		if($row['driver_id'] > 0)
			$data[$row['driver_id']][] = $row;	
	}

	return $data;
}

//get invoiced trip vouchers by vehicle owner
/*
function get_vehicle_vouchers_with_invoice($inv_no){
	$sql = "SELECT voucher.*,vh.vehicle_owner_id,owner.name as owner_name,vh.supplier_group_id,sg.name as group_name
		FROM trip_vouchers voucher
		LEFT JOIN trips tr ON tr.id = voucher.trip_id
		LEFT JOIN vehicles vh ON vh.id = tr.vehicle_id
		LEFT JOIN vehicle_owners owner ON owner.id = vh.vehicle_owner_id
		LEFT JOIN supplier_groups sg ON sg.id = vh.supplier_group_id
		WHERE voucher.id IN(SELECT trip_voucher FROM ".TB_PREF."debtor_trans_details WHERE debtor_trans_no =".db_escape($inv_no)." AND debtor_trans_type = ".ST_SALESINVOICE.") AND voucher.vehicle_payment_amount > 0";



	
	$result = db_query($sql, "could not get vouchers");
	$data = array();
	while($row = db_fetch_assoc($result)){

		if($row['supplier_group_id'] > 0)
			$data['SG'.$row['supplier_group_id']][] = $row;
		elseif($row['vehicle_owner_id'] > 0)
			$data['VW'.$row['vehicle_owner_id']][] = $row;
			
	}
	

	return $data;
}*/
function get_vehicle_vouchers_with_invoice($inv_no){
	$sql = "SELECT voucher_vehicle.*,vh.vehicle_owner_id,owner.name as owner_name
		FROM trip_voucher_vehicles voucher_vehicle
		LEFT JOIN trip_vouchers voucher ON voucher.id = voucher_vehicle.trip_voucher_id
		LEFT JOIN vehicles vh ON vh.id = voucher_vehicle.vehicle_id
		LEFT JOIN vehicle_owners owner ON owner.id = vh.vehicle_owner_id
		WHERE voucher.invoice_no = ".db_escape($inv_no);
	
	$result = db_query($sql, "could not get vouchers");
	$data = array();
	while($row = db_fetch_assoc($result)){

		if($row['supplier_group_id'] > 0)
			$data['SG'.$row['supplier_group_id']][] = $row;
		elseif($row['vehicle_owner_id'] > 0)
			$data['VW'.$row['vehicle_owner_id']][] = $row;
			
	}
	

	return $data;
}

function tripAdvanceParams($trip_id){
	$sql = "SELECT trip.id,trip.customer_id,trip.customer_group_id,trip.advance_amount,trip.payment_no,
		debtor.debtor_no,debtor.name,
		debtorGrp.debtor_no AS debtor_group_no,debtorGrp.name AS group_name
		FROM ".TB_PREF."debtors_master debtor,trips trip
		LEFT JOIN ".TB_PREF."debtors_master debtorGrp ON debtorGrp.debtor_ref = CONCAT('CG',trip.customer_group_id)
		WHERE trip.id = ".db_escape($trip_id)." AND CONCAT('C',trip.customer_id) = debtor.debtor_ref";
	$result = db_query($sql, "could not get Trip Params");
	$row = db_fetch_assoc($result);

	return $row;
}


function updateTripPaymentNo($payment_no=0,$trip_id){
	
	$sql ="UPDATE trips SET payment_no = ".db_escape($payment_no)." WHERE id = ".db_escape($trip_id);
	db_query($sql,"The customer could not be updated");
	
}





?>
