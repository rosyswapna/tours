<?php 
tcpdf();
$pdf =  new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('ConnectNCabs');
$pdf->SetTitle('Trip');


// set default header data
$pdf->SetHeaderData('', '',$this->session->userdata('organisation_name'),'');

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);



// ---------------------------------------------------------

// set font
$pdf->SetFont('helvetica', 'B', 20);

// add a page
$pdf->AddPage();

//$pdf->Write(0, 'Example of HTML tables', '', 0, 'L', true, 0, false, false, 0);

$pdf->SetFont('helvetica', '', 8);

ob_start();
if($customer_details['group']!='' && $customer_details['customer_name']!=''){
$customer_group=$customer_details['group'];
$customer_name=$customer_details['customer_name'];
$customer=$customer_group.','.$customer_name;
$cust_mob=$customer_details['customer_mob'];
}
else if($customer_details['group']=='' && $customer_details['customer_name']!=''){
$customer=$customer_details['customer_name'];
$cust_mob=$customer_details['customer_mob'];
}
else if($customer_details['group']=='' && $customer_details['customer_name']=='' && $customer_details['guest_name']!='' ){
$customer=$customer_details['guest_name'];
$cust_mob=$customer_details['guest_mob'];
} 
$content='<div style="border:1px solid #333;width:80%;display:inline-block;margin:20px 10% auto;">
<div style="float:left;width:100%;">

<p style="font-size:10px;">Reference : '.$customer.'
</p>
<p style="font-size:10px;">Contact Number : '.$cust_mob.'</p>
<p style="font-size:10px;">Date : '.date('Y-m-d').'</p>
</div>
<div style="border-top:1px solid #333;width:100%;float:left;">
<div style="width:48%;float:left;">
<div>
Dear '.$customer_details['customer_name'].',</br>
<p>We have pleasure in providing you with a quotation for your proposed travel requirement.</p>
</div>
<div>
<h4>TRAVEL DETAILS</h4><hr>
<p>From : '.$pick_up_city.'</p>
<p>To : '.$drop_city.'</p>
<p><Date of Journey : '.$pickup_date.'/p>
</div>
';
$content1='<div>
<h4 >VEHICLE & DRIVER DETAILS</h4><hr>
<p>Vehicle Registration Number : '.$vehicle.'</p>
<p>AC Type : '.$vehicle_ac_type.'</p>
<p>Vehicle Type :'.$vehicle_type.'</p>
<p>Seating Capacity : '.$vehicle_seating_capacity.'</p>
<p>Beacon Light : '.$vehicle_beacon_light.'</p>
<p>Driver Name : '.$driver.'</p>
</div>';
$content2='<div>
<h4>TARRIF DETAILS</h4><hr>
<p>Time Of Journey : '.$time_of_journey.'</p>
<p>Distance : '.$distance.'</p>
<p>Minimum Charge : '.$min_charge.'</p>
<p>Additional Charge : '.$additional_charge.'</p>
<p>Additional Kilometer : '.$additional_km.'</p>
<p>Minimum Kilometers per Day : '.$min_kilometers.'</p>
<p>Amount : '.$amount.'</p>
<p>Tax Payable : '.$tax_payable.'</p>
<p>Total Amount : '.$total_amt.'</p>
</div>';
$content3='<div>
<h4 >TERMS & CONDITIONS</h4><hr>
<p>We are delighted to advise that once the booking is confirmed and provided all payment schedules are met, the tour is
fully guaranteed against currency surcharges. For detailed booking terms and conditions please visit our website or
contact us</p>
</div>';
$content4='';
$content5='';
$content5='';
$content6='
';
$content7='';
$content8='';

ob_end_clean();

//$pdf->writeHTML($content, true, false, false, false, '');
//$pdf->writeHTML($content1, true, false, false, false, '');

$content.=$content1;
$content.=$content2;
$content.=$content3;
$content.=$content4;
$content.=$content5;
$content.=$content6;
$content.=$content7;
$content.=$content8;
$pdf->writeHTML($content, true, false, false, false, '');
$file_name='trip-'.$trip_id.'.pdf';
$pdf->Output($file_name, 'I');



?>
