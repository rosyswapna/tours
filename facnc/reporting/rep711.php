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
// date_:	2005-05-19
// Title:	Print Invoices
// ----------------------------------------------------------------
$path_to_root="..";

include_once($path_to_root . "/includes/session.inc");
include_once($path_to_root . "/includes/date_functions.inc");
include_once($path_to_root . "/includes/data_checks.inc");
include_once($path_to_root . "/sales/includes/sales_db.inc");

//----------------------------------------------------------------------------------------------------

print_invoices();

function get_delivery_no($src_id){
	$sql = "SELECT dtd.debtor_trans_no FROM ".TB_PREF."debtor_trans_details dtd WHERE id = ".db_escape($src_id);
	$result = db_query($sql, "Error getting order details");
	$row = db_fetch_row($result);
	return $row[0];
}

function get_trip($voucher = 0)
{
	$sql = "SELECT vehicle.registration_number as vehicle_no,trip.pick_up_date as trip_date,voucher.id as voucher_no,voucher.total_trip_amount as amount,voucher.voucher_no AS voucher_str,IFNULL(guest.name,trip.guest_name) AS username,trip.advance_amount,trip.payment_no,voucher.releasing_place as voucher_description,v_ac.name as vehicle_ac_type_name,v_model.name as vehicle_model_name";
	$sql .= " FROM trip_vouchers voucher";
	$sql .= " LEFT JOIN trips trip ON trip.id = voucher.trip_id";
	$sql .= " LEFT JOIN customers customer ON customer.id = trip.customer_id";
	$sql .= " LEFT JOIN customers guest ON guest.id = trip.guest_id";
	$sql .= " LEFT JOIN vehicles vehicle ON trip.vehicle_id = vehicle.id";
	$sql .= " LEFT JOIN vehicle_ac_types v_ac ON trip.vehicle_ac_type_id = v_ac.id";
	$sql .= " LEFT JOIN vehicle_models v_model ON voucher.vehicle_model_id = v_model.id";
	$sql .= " WHERE voucher.id = ".db_escape($voucher);

	$result = db_query($sql, "Error getting order details");
	return db_fetch_assoc($result);
	
}

//----------------------------------------------------------------------------------------------------

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

	//$cols = array(4, 60, 225, 300, 325, 385, 450, 515);
	//$cols = array(4, 30, 80, 140, 210, 270, 500, 535);
	$cols = array(4, 30, 80, 140, 210, 270, 480, 535);

	// $headers in doctext.inc
	$aligns = array('center','center','center', 'center', 'center', 'center', 'center');

	$params = array('comments' => $comments);

	

	$cur = get_company_Pref('curr_default');

	if ($email == 0)
		$rep = new FrontReport(_('INVOICE'), "InvoiceBulk", user_pagesize(), 9, $orientation);
	if ($orientation == 'L')
		recalculate_cols($cols);
	for ($i = $from; $i <= $to; $i++)
	{
			if (!exists_customer_trans(ST_SALESINVOICE, $i))
				continue;
			$sign = 1;
			$myrow = get_customer_trans($i, ST_SALESINVOICE);
			
			
			
			if($customer && $myrow['debtor_no'] != $customer) {
				continue;
			}
			$baccount = get_default_bank_account($myrow['curr_code']);
			$params['bankaccount'] = $baccount['id'];

			$branch = get_branch($myrow["branch_code"]);
			$sales_order = get_sales_order_header($myrow["order_"], ST_SALESORDER);
			if ($email == 1)
			{
				$rep = new FrontReport("", "", user_pagesize(), 9, $orientation);
				$rep->title = _('INVOICE');
				$rep->filename = "Invoice" . $myrow['reference'] . ".pdf";
			}	
			$rep->SetHeaderType('Header4');
			$rep->currency = $cur;
			$rep->Font();
			$rep->Info($params, $cols, null, $aligns);

			$contacts = get_branch_contacts($branch['branch_code'], 'invoice', $branch['debtor_no'], true);
			$baccount['payment_service'] = $pay_service;
			$rep->SetCommonData($myrow, $branch, $sales_order, $baccount, ST_SALESINVOICE, $contacts);
			$rep->NewPage();
   			$result = get_customer_trans_details(ST_SALESINVOICE, $i,'trips.pick_up_date ASC');
			$SubTotal = 0;
			$slno = 1;
			$advanceTotal = 0;

		
			while ($myrow2=db_fetch($result))
			{
				//$memo = get_comments_string(ST_SALESINVOICE, $myrow2['id']);
				 if($myrow2["quantity"]==0) continue;
	
				//echo "<pre>";print_r($myrow2);echo "</pre>";
				$memo = get_comment_value(ST_CUSTDELIVERY,get_delivery_no($myrow2['src_id']));
	
			
				$trip = get_trip($myrow2['trip_voucher']);

				$advPmtNo = @$trip['payment_no'];
				if(is_numeric($advPmtNo) && $advPmtNo > 0){
					$advanceTotal += @$trip['advance_amount'];
				}

				$vehicle_info = $trip['vehicle_no']."\n".$trip['vehicle_model_name']." ".$trip['vehicle_ac_type_name'];
				
				$rep->TextCol(0, 1,  $slno);
				$rep->TextCol(1, 2,  @$trip['voucher_str']);
				$rep->TextCol(2, 3,  sql2date($trip['trip_date']));
				$temp_row = $rep->row;
				$rep->TextColLines(3, 4,  $vehicle_info);
				$rep->row=$temp_row;
				$rep->TextCol(4, 5,  @$trip['username']);
				if($trip['voucher_description'] != ''){
					$memo .= "\n(".$trip['voucher_description'].")";
				}
				$rep->TextColLines(5, 6,  $memo);
				$next_item_row = $rep->row;
				$rep->row=$temp_row;

				$Net = round2($sign * ((1 - $myrow2["discount_percent"]) * $myrow2["unit_price"] * $myrow2["quantity"]), user_price_dec());
				$SubTotal += $Net;
		    		
		    		$DisplayNet = number_format2($Net,$dec);
				
				$rep->TextCol(6, 7,  $DisplayNet);


				$rep->row = $next_item_row;
				$rep->NewLine();

				if ($rep->row < $rep->bottomMargin + (15 * $rep->lineHeight))
					$rep->NewPage();

				$slno++;
			}
			//exit;

   			$DisplaySubTot = number_format2($SubTotal,$dec);//total amount
   			$DisplayFreight = number_format2($sign*$myrow["ov_freight"],$dec);

    			$rep->row = $rep->bottomMargin + (15 * $rep->lineHeight);
			$doctype = ST_SALESINVOICE;

			$tax_items = get_trans_tax_details(ST_SALESINVOICE, $i);
			$first = true;
			$Tax = 0;
	    		while ($tax_item = db_fetch($tax_items))
	    		{
	    			if ($tax_item['amount'] == 0)
	    				continue;
				$Tax += $tax_item['amount'];

				if($tax_item['rate']==12){
				 $taxDsp[] = array('name'=>$tax_item['tax_type_name'].' of 40% of '.$DisplaySubTot,
						'value' => number_format2($tax_item['amount'], $dec)
				 		);
				$modPrice =  number_format2($tax_item['amount'], $dec);

				}else{
					$rate = ($tax_item['rate']*10);
					 $taxDsp[] = array('name'=>$tax_item['tax_type_name'].' of '.$modPrice,
						'value' => number_format2($tax_item['amount'], $dec)
				 		);
				}
	    			
	    			
	    			/*if (isset($suppress_tax_rates) && $suppress_tax_rates == 1)
	    				$tax_type_name = $tax_item['tax_type_name'];
	    			else
	    				$tax_type_name = $tax_item['tax_type_name']." (".$tax_item['rate']."%) ";
				*/

	    		}
			//echo "<pre>";print_r($taxDsp);echo "</pre>";exit;
			$DisplayTax = number_format2($Tax, $dec);
			$Total = $myrow["ov_freight"] + $myrow["ov_gst"] +
				$myrow["ov_amount"]+$myrow["ov_freight_tax"];
			$Balance = $Total - $advanceTotal;
			

			$DisplayTotal = number_format2($sign*($Total),$dec);//grand total
			
			$DisplayAdvance = number_format2($advanceTotal,$dec);//less cash advance
			$DisplayBalance = number_format2($Balance,$dec);//balance
			

			//$rep->Font('bold');
			$words = price_in_words_custom($Balance);
			$rep->row = $rep->words_row;	
			$rep->NewLine();
			$rep->Text($rep->words_column, "Rupees : ");
			if ($words != "")
				$rep->TextWrapLines($rep->words_column+50, $rep->words_column +180, $words." Only", 'left');
			

			$rep->NewLine(3);
			$rep->Text($rep->words_column,"Net Payable : Rs. ".$DisplayBalance);

			$rep->row = $rep->totals_row;	

			$rep->Text($rep->totals_column + 5, 'Total Amount');
			$rep->Text($rep->totals_column + 192, ":");
			$rep->Text($rep->totals_column + 200, $DisplaySubTot);
			$rep->NewLine(1.2);
			
			foreach($taxDsp as $displayTax){
				$rep->Text($rep->totals_column + 5, $displayTax['name']);
				$rep->Text($rep->totals_column + 192, ":");
				$rep->Text($rep->totals_column + 200, $displayTax['value']);
				$rep->NewLine(1.2);
			}

			$rep->Text($rep->totals_column + 5, 'Less Cash Advance');
			$rep->Text($rep->totals_column + 192, ":");
			$rep->Text($rep->totals_column + 200, $DisplayAdvance);
			$rep->NewLine(1.2);

			$rep->Text($rep->totals_column + 5, 'BALANCE');
			$rep->Text($rep->totals_column + 192, ":");
			$rep->Text($rep->totals_column + 200, $DisplayBalance);
			$rep->NewLine(1.2);
			
			$rep->Font();
			if ($email == 1)
			{
				$rep->End($email);
			}
	}
	if ($email == 0)
		$rep->End();
}

?>
