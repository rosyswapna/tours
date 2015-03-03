<?php
$path_to_root = "..";
$page_security = 'SA_DRIVERINVOICE';
		     

include_once($path_to_root . "/purchasing/includes/po_class.inc");
include_once($path_to_root . "/includes/session.inc");
include_once($path_to_root . "/purchasing/includes/purchasing_ui.inc");
include_once($path_to_root . "/purchasing/includes/db/suppliers_db.inc");
include_once($path_to_root . "/reporting/includes/reporting.inc");

include_once($path_to_root . "/includes/db/cnc_session_db.inc");

set_page_security( @$_SESSION['PO']->trans_type,
	array(ST_SUPPINVOICE => 'SA_DRIVERINVOICE'),
	array(	'DriverInvoice' => 'SA_DRIVERINVOICE',
		'AddedDPI' => 'SA_DRIVERINVOICE')
);

$js = '';
if ($use_popup_windows)
	$js .= get_js_open_window(900, 500);
if ($use_date_picker)
	$js .= get_js_date_picker();

$_SESSION['page_title'] = _($help_context = "Driver Invoice");

page($_SESSION['page_title'], false, false, "", $js);

$vouchers = array();

if(isset($_GET['TripInvoice'])){

	$tripInvoice = $_GET['TripInvoice'];
	$vouchers = get_driver_vouchers_with_invoice($tripInvoice);
	//echo "<pre>";print_r($vouchers);echo "</pre>";exit;
	$_POST['tripinvoice'] = $tripInvoice;
	if(!$vouchers){
		meta_forward($path_to_root.'/purchasing/vehicle_invoice.php','TripInvoice='.$tripInvoice);
	}
}



//echo "<pre>";print_r($_SESSION);echo "</pre>";	



function getReference()
{
	global $Refs;
	$ref = $Refs->get_next(ST_SUPPINVOICE);
	return $ref;
}	


if (isset($_POST['Commit']))
{
	$tripinvoice = $_POST['tripinvoice'];
	$item = get_item(DRIVER_COMMISSION);//get trip driver commission in fa as item record
	$location = 'DEF';

	//get default location 
	$sql = "SELECT delivery_address, phone FROM ".TB_PREF."locations WHERE loc_code = ".db_escape($location);

        $result = db_query($sql,"could not get location info");
	if (db_num_rows($result) == 1)
        {
    	  	$loc_row = db_fetch($result);
    	  	$delivery_address = $loc_row["delivery_address"];

        }
        else
        { /*The default location of the user is crook */
    	  	display_error(_("The default stock location set up for this user is not a currently defined stock location. Your system administrator needs to amend your user record."));
        }

	
	if(isset($_SESSION['vouchers'])){

		//-----------------process each driver's invoice looping through vouchers-------
		foreach($_SESSION['vouchers'] as $driver => $vouchers){

			$supplier_id = get_cnc_supplier_id("DR".$driver);
			create_new_po(ST_SUPPINVOICE, 0);
			copy_from_cart();
			$_SESSION['PO']->voucher = $vouchers;

			//create line items
			$trip_ids = array();
			foreach($vouchers as $voucher){
				$_SESSION['PO']->add_to_order (count($_SESSION['PO']->line_items), $item['stock_id'], 1, $item['description'], $voucher['driver_payment_amount'], '', '', 0, 0, $voucher['trip_id'],$voucher['no_of_days']);
				$trip_ids[] = $voucher['trip_id'];
			}

			$comments = "Driver Commission for the trip, IDs:". implode(",",$trip_ids);

			//copy to cart
			$cart = &$_SESSION['PO'];
			$cart->supplier_id = $supplier_id;	
			$cart->orig_order_date = $_POST['OrderDate'];
			$cart->due_date = $_POST['OrderDate'];
			$cart->reference = 0;
			$cart->supp_ref = 0;
			$cart->Comments = $comments;	
			$cart->Location = $location;
			$cart->delivery_address = $delivery_address;
			$cart->ex_rate = input_num('_ex_rate', null);

			foreach ($cart->line_items as $line_no =>$line)
				$cart->line_items[$line_no]->req_del_date = $cart->orig_order_date;

			$cart->reference = 'auto';
			begin_transaction();
			$order_no = add_po($cart);
			new_doc_date($cart->orig_order_date); 
        		$cart->order_no = $order_no;

			foreach($cart->line_items as $key => $line)
				$cart->line_items[$key]->receive_qty = $line->quantity;
			$grn_no = add_grn($cart);

			//Direct Purchase Invoice
			$ref = getReference();
 			$inv = new supp_trans(ST_SUPPINVOICE);
			$inv->Comments = $cart->Comments;
			$inv->supplier_id = $cart->supplier_id;
			$inv->tran_date = $cart->orig_order_date;
			$inv->due_date = $cart->due_date;
			$inv->reference = $ref;
			$inv->supp_reference = $ref;
			$inv->tax_included = $cart->tax_included;
			$supp = get_supplier($cart->supplier_id);
			$inv->tax_group_id = $supp['tax_group_id'];

			$inv->ov_amount = $inv->ov_gst = $inv->ov_discount = 0;

			$total = 0;
			foreach($cart->line_items as $key => $line) {
				$inv->add_grn_to_trans($line->grn_item_id, $line->po_detail_rec, $line->stock_id,
					$line->item_description, $line->receive_qty, 0, $line->receive_qty,
					$line->price, $line->price, true, get_standard_cost($line->stock_id), '');
				$inv->ov_amount += round2(($line->receive_qty * $line->price), user_price_dec());
			}
			$inv->tax_overrides = $cart->tax_overrides;
			if (!$inv->tax_included) {
				$taxes = $inv->get_taxes($inv->tax_group_id, 0, false);
				foreach( $taxes as $taxitem) {
					$total += isset($taxitem['Override']) ? $taxitem['Override'] : $taxitem['Value'];
				}
			}
			$inv->ex_rate = $cart->ex_rate;

			$inv_no[] = add_supp_invoice($inv);
			commit_transaction(); // save PO+GRN+PI
			// FIXME payment for cash terms. (Needs cash account selection)
			unset($_SESSION['PO']);

		}
		//----------------------------voucher loop ends-------------------------------------

	}
	meta_forward($path_to_root.'/purchasing/vehicle_invoice.php','TripInvoice='.$tripinvoice);
	
}


start_form();
hidden('tripinvoice');

start_outer_table(TABLESTYLE2, 'width=80%');
	table_section(1);
	
	date_row(_("Invoice Date:"),'OrderDate', '', true, 0, 0, 0, null, true);
	
end_outer_table(); // outer table
br();

display_heading(_("Driver Trip Details"));
	div_start('items_table');
    	start_table(TABLESTYLE, "width=80%");

	   	$th = array(_("Driver"), _("Trip Data"), _("No Of Days"), _("Amount"));
		
	   	table_header($th);

		$total = 0;
		$k = 0;
	   	foreach ($vouchers as $driver => $voucher_list)
	   	{
			$trip_ids = array();
			$driver_name = (isset($voucher_list[0]))?$voucher_list[0]['driver_name']:"";
			$line_total = 0;
			$no_of_days = 0;
			foreach($voucher_list as $voucher){
				$trip_ids[] = $voucher['trip_id'];
				$no_of_days += $voucher['no_of_days'];
				$line_total += $voucher['driver_payment_amount'];
			}
			
	    		$line_total =	round($line_total,  user_price_dec());
	    	
	    		alt_table_row_color($k);
				label_cell($driver_name);
				label_cell(implode(',',$trip_ids));
				
				label_cell($no_of_days);
		    		amount_cell($line_total);	
			end_row();
			$total += $line_total;
	       	}
		
		$display_sub_total = price_format($total);
		$colspan = 3;

		label_row(_("Amount Total"), $display_sub_total, "colspan=$colspan align=right","align=right");

	end_table(1);
	div_end();

	if(count($vouchers) > 0) 
	{
		$_SESSION['vouchers'] = $vouchers;
		//submit_center_first('Commit', _("Process Driver Invoice"), '', 'default');
		submit_center_first('Commit', _("Process Driver Invoice"));
		submit_center_last('CancelOrder', _("Cancel")); 	
	}
	else
		submit_center('CancelOrder', _("Cancel"), true, false, 'cancel');



end_form();

end_page();



?>
