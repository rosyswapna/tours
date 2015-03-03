<?php
/**********************************************************************
    Copyright (C) FrontAccounting, LLC.
	Released under the terms of the GNU General Public License, GPL, 
	as published by the Free Software Foundation, either version 3 
	of the License, or (at your option) any later version.
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  
    See the License here <http://www.gnu.org/licenses/gpl-3.0.html>.
***********************************************************************/
$page_security = $_POST['PARAM_0'] == $_POST['PARAM_1'] ?
	'SA_SALESTRANSVIEW' : 'SA_SALESBULKREP';
// ----------------------------------------------------------------
// $ Revision:	2.0 $
// Creator:	Swapna
// date_:	2015 Feb
// Title:	Print Invoices
// ----------------------------------------------------------------
$path_to_root="..";

include_once($path_to_root . "/includes/session.inc");
include_once($path_to_root . "/includes/date_functions.inc");
include_once($path_to_root . "/includes/data_checks.inc");
include_once($path_to_root . "/sales/includes/sales_db.inc");

//----------------------------------------------------------------------------------------------------

print_invoices();

function getTrip($voucher)
{
	$sql = 'SELECT v.id,v.end_km_reading-v.start_km_reading AS KMs,
			TIMEDIFF(CONCAT(trip_end_date," ",v.trip_ending_time),CONCAT(v.trip_start_date," ",v.trip_starting_time)) AS HRs';
	$sql .= ' FROM trip_vouchers v';
	$sql .= ' LEFT JOIN trips trip ON trip.id = v.trip_id';
	$sql .= ' WHERE v.id = '.db_escape($voucher);
	

	$result = db_query($sql, "Error getting order details");
	return db_fetch_assoc($result);
	
}

function getTrips($invoice){
	$sql = 'SELECT v.id,v.trip_start_date';
	$sql .= ' FROM trip_vouchers v';
	$sql .= ' LEFT JOIN trips trip ON trip.id = v.trip_id';
	$sql .= ' WHERE v.invoice_no = '.db_escape($invoice);
	$sql .= ' ORDER BY v.trip_start_date';

	return db_query($sql, "Error getting order details");
}

function addTime($time1,$time2){
	$time1_list =explode(":", $time1);
	$time2_list =explode(":", $time2);

	$h = $time1_list[0]+$time2_list[0];
	$m = $time1_list[1]+$time2_list[1];
	$s = $time1_list[2]+$time2_list[2];

	while($s > 59){
		$s = $s-60;
		$m++;
	}
	while($m > 59){
		$m = $m-60;
		$h++;
	}
	 
	$time = $h.":".$m.":".$s;
	return $time;
}


function print_invoices()
{
	global $path_to_root, $alternative_tax_include_on_docs, $suppress_tax_rates, $no_zero_lines_amount;
	
	include_once($path_to_root . "/reporting/includes/pdf_report.inc");

	$from = $_POST['PARAM_0'];
	$to = $_POST['PARAM_1'];
	$currency = $_POST['PARAM_2'];
	$email = $_POST['PARAM_3'];
	$pay_service = $_POST['PARAM_4'];
	$comments = $_POST['PARAM_5'];
	$customer = $_POST['PARAM_6'];
	$orientation = $_POST['PARAM_7'];
	if (!$from || !$to) return;

	$orientation = ($orientation ? 'L' : 'P');
	$dec = user_price_dec();

	$fno = explode("-", $from);
	$tno = explode("-", $to);
	$from = min($fno[0], $tno[0]);
	$to = max($fno[0], $tno[0]);

	$cols = array(4, 50, 280,330, 380, 450,535);

	// $headers in doctext.inc
	$aligns = array('center','center','center', 'center', 'center', 'center');

	$params = array('comments' => $comments);

	$rep = new FrontReport("", "", user_pagesize(), 9, $orientation);
	$rep->title = _('INVOICE');
	$rep->filename = "Invoice" . $myrow['reference'] . ".pdf";

	if ($orientation == 'L')
		recalculate_cols($cols);

	$rep->SetHeaderType('Header5');
	$rep->Font();
	$rep->Info($params, $cols, null, $aligns);

	$slno = 1;$Total = 0;
	for ($i = $from; $i <= $to; $i++)
	{
		if (!exists_customer_trans(ST_SALESINVOICE, $i))
			continue;
		$sign = 1;
		$myrow = get_customer_trans($i, ST_SALESINVOICE);
	
		if($customer && $myrow['debtor_no'] != $customer) {
			continue;
		}

		$contacts = get_branch_contacts($branch['branch_code'], 'invoice', $branch['debtor_no'], true);
		$baccount['payment_service'] = $pay_service;
		
		$trip_period = '';
		$tripRES = getTrips($i);$trips=array();
		while($trip = db_fetch_assoc($tripRES)){
			$trips[] = $trip;
		}
		if($trips){
			$first = 0;
			$last = count($trips)-1;
			$trip_period = $trips[$first]['trip_start_date']." to ".$trips[$last]['trip_start_date'];
		}
		$myrow['trip_period'] = $trip_period;

		$rep->SetCommonData($myrow, $branch, $sales_order, $baccount, ST_SALESINVOICE, $contacts);
		$rep->NewPage();
		
		$Description = "Hire Charges for Vehicle Operation for the Period ".$trip_period."\nHire charges"; 
		

		$InvoiceTotal = $myrow['Total'];
		$Total += $InvoiceTotal;
		
	}

	$DisplayTotal = number_format2($sign*($Total),$dec);
	 


	//display each invoice total
	$rep->TextCol(0, 1,  $slno);
	$rep->TextColLines(1, 2,  $Description);
	//$rep->TextCol(2, 3, $DisplayKMs);
	//$rep->TextCol(3, 4,  $DisplayHRs);
	//$rep->TextCol(4, 5,  "0.00");
	$rep->TextCol(5, 6,  $DisplayTotal);

	
	$rep->row = $rep->totals_row;
	$GrandTotal = round($Total);
	$RoundOff = abs($Total - $GrandTotal);
	$DisplayGrandTotal = number_format2($GrandTotal,$dec);
	$DisplayRoundOff = number_format2($RoundOff,$dec);
	$words = price_in_words_custom($GrandTotal);

	$rep->TextCol(5, 6, $DisplayRoundOff);
	$rep->NewLine(2);
	$rep->TextCol(5, 6, $DisplayGrandTotal);
	$rep->NewLine(2);
	$rep->Font('bold');
	$rep->TextWrapLines($cols[0]+30, $cols[6], $words, 'center');
	$rep->Font();


	$rep->End();
}

?>
