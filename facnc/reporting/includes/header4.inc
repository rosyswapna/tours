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

/**
	Document blueprint use following parameters set in doctext.inc:

	$Addr1,	$Addr2 - address info
	$Payment_Terms - payment terms line
	$Footer - footer texts
	$this->company - company info
	$this->title - report title
	$this->formData - some other info
***/
		$this->headers = array(_('Sl.No.'),	_('Indent No.'), _('Date'),	_('Vehicle No.'),
		_('User Name'), _('Particulars'), _('Amount'));

		$this->row = $this->pageHeight - $this->topMargin;

		$upper = $this->row - 2 * $this->lineHeight;
		$lower = $this->bottomMargin + 8 * $this->lineHeight;

		
		$right = $this->pageWidth - $this->rightMargin;
		$width = ($right - $this->leftMargin) / 5;

		$icol = $this->pageWidth / 2;
		$ccol = $this->cols[0] + 4;
		$c2col = $ccol + 60;
		$ccol2 = $icol / 2;
		$mcol = $icol + 8;
		$mcol2 = $this->pageWidth - $ccol2;
		$cols = count($this->cols);
		
		//----------------------------------------------------------------------------
		$head_box_line1 = $this->pageHeight-9;
		$this->Line($head_box_line1);

		// Company Logo
		$coy_dt = $this->row;
		$this->NewLine(2);
		$logo = company_path() . "/images/" . $this->company['coy_logo'];
		if ($this->company['coy_logo'] != '' && file_exists($logo))
		{
			$this->AddImage($logo, $ccol, $this->row, 70, 40);
		}
		
		
		//Company Name
		$this->row = $coy_dt;
		$this->fontSize += 4;
		$this->Font('bold');
		$this->TextWrap($left, $this->row, $right, $this->company['coy_name'],'center');
		$this->Font();
		$this->fontSize -= 4;
		
		// Company data
		$this->NewLine();
		$this->TextWrap($left, $this->row, $right, $this->company['postal_address'],'center');
		
		$this->Font('italic');
		if (@$this->company['phone'])
		{
			$this->NewLine();
			$phone = "Phone : ".$this->company['phone'];
			$this->TextWrap($left, $this->row, $right, $phone,'center');
			
		}
		if (@$this->company['fax'])
		{
			$this->NewLine();
			$fax = "Fax : ".$this->company['fax'];
			$this->TextWrap($left, $this->row, $right, $fax,'center');
		}
		if (@$this->company['email'])
		{

			$this->NewLine();
			$email = "Email : ".$this->company['email'];
			$this->TextWrap($left, $this->row, $right, $email,'center');

		}
		//draw line after header
		$this->NewLine();
		$this->Font();
		$head_box_line2 = $this->row;
		$this->Line($head_box_line2);
		$this->LineTo($this->leftMargin, $head_box_line1 , $this->leftMargin, $head_box_line2);
		$this->LineTo($right, $head_box_line1 ,$right, $head_box_line2);
		//-------------------------------------------------------------------------------------
		

		//$this->NewLine();
		$head_right1 = $this->row - 3;
		$r_box_left = $mcol + 25;
		$r_txt_left = $r_box_left + 5;
		$this->Line1($head_right1,0,$r_box_left,$this->pageWidth - $this->rightMargin);
		
		$this->Font();
		$this->NewLine();
		$this->row = $head_right1 -15 ;
		$addr = $this->row;
		$this->Text($r_txt_left, _("Date"));
		$this->Text($mcol + 120, " : ".sql2date($this->formData['document_date']));
		$this->NewLine();
		$this->Text($r_txt_left, $this->formData['document_name']);
		$this->Text($mcol + 120, " : ".$this->formData['document_number']);

		$head_right2 = $this->row - 6;
		$this->Line1($head_right2,0,$r_box_left,$this->pageWidth - $this->rightMargin);

		$this->LineTo($r_box_left, $head_right1 ,$r_box_left, $head_right2);
		$this->LineTo($right, $head_right1 ,$right, $head_right2);
		
		$head_right3 = $head_right2 - 3;
		$this->Line1($head_right3,0,$r_box_left,$this->pageWidth - $this->rightMargin);
	
		$this->NewLine(2);
		$this->Text($r_txt_left, _("Service Tax Reg. No."));
		$this->Text($mcol + 120, " : ");
		$this->Text($mcol + 130, "AADCC0959NST001");

		$this->NewLine();
		$this->Text($r_txt_left, _("Category"));
		$this->Text($mcol + 120, " : ");
		$this->Text($mcol + 130, "Tour Operator / Cab Operator");

		$this->NewLine();
		$this->Text($r_txt_left, _("PAN No."));
		$this->Text($mcol + 120, " : ");
		$this->Text($mcol + 130, "AADCC0959N");

		$sub_head = $this->row;
		$head_right4 = $this->row - 6;
		$this->Line1($head_right4,0,$r_box_left,$this->pageWidth - $this->rightMargin);
		
		$this->LineTo($r_box_left, $head_right3 ,$r_box_left, $head_right4);
		$this->LineTo($right, $head_right3 ,$right, $head_right4);
	
		


		// address1
		$l_box_right = $mcol + 20;
		$this->Line1($head_right1,0,$this->leftMargin,$l_box_right);
		$this->row = $addr;
		$this->Text($ccol, _("To"), $icol);
		$temp = $this->row = $this->row - $this->lineHeight - 5;
		$this->Text($c2col, $Addr1['name'], $icol);
		$this->NewLine();
		$this->TextWrapLines($c2col, $icol - $ccol, $Addr1['address']);
		$this->Line1($head_right4,0,$this->leftMargin,$l_box_right);

		$this->LineTo($this->leftMargin, $head_right1 ,$this->leftMargin, $head_right4);
		$this->LineTo($l_box_right, $head_right1 , $l_box_right, $head_right4);
		//---------------------------------------------------------------------------------
		

		//draw table
		$this->row = $sub_head;
		$this->SetDrawColor(0, 0, 0);
		$this->NewLine();
		$tb_line1 = $this->row;
		$tb_line2 = $tb_line1 - 1.5 * $this->lineHeight;
		$tb_line3 = $lower + 60;
		$this->rectangle($this->leftMargin, $tb_line1, $right - $this->leftMargin, $tb_line1 - $tb_line2, "F", null, array(222, 231, 236));   
		$this->Line($tb_line1);
		$this->Line($tb_line2);
		$this->Line($tb_line3);
		$this->LineTo($this->leftMargin, $tb_line1 ,$this->leftMargin, $tb_line3);
		$col = $this->leftMargin;
		for ($i = 1; $i < count($this->headers); $i++)
		{
			$this->LineTo($this->cols[$i], $tb_line1,$this->cols[$i], $tb_line3);
		}
		
		//$this->LineTo($this->cols[$cols - 2] + 4, $tb_line1 ,$this->cols[$cols - 2] + 4, $tb_line3);
		$this->LineTo($right, $tb_line1 ,$right, $tb_line3);

		// Line headers

		$this->row = $tb_line1 - $this->lineHeight - 1;
		$this->Font('bold');
		$count = count($this->headers);
		$this->cols[$count] = $right - 3;
		for ($i = 0; $i < $count; $i++)
			$this->TextCol($i, $i + 1, $this->headers[$i], -2);
		$this->Font();

		//bottom box left
		$bottom_box1 = $tb_line3 - 3;
		$bottom_box2 = $bottom_box1 - 100;
		$this->Line1($bottom_box1,0,$this->leftMargin,$l_box_right);
		$this->Line1($bottom_box2,0,$this->leftMargin,$l_box_right);

		$this->LineTo($this->leftMargin, $bottom_box1 ,$this->leftMargin, $bottom_box2);
		$this->LineTo($l_box_right, $bottom_box1 ,$l_box_right, $bottom_box2);
	
		//bottom box right
		$this->Line1($bottom_box1,0,$r_box_left,$this->pageWidth - $this->rightMargin);
		$this->Line1($bottom_box2,0,$r_box_left,$this->pageWidth - $this->rightMargin);
		
		$this->LineTo($r_box_left, $bottom_box1 ,$r_box_left, $bottom_box2);
		$this->LineTo($right, $bottom_box1 ,$right, $bottom_box2);



		//footer
		$ftr_line1 = $bottom_box2 - 3;
		$ftr_line2 = $bottom_box2 - 50;
		$this->Line($ftr_line1);
		$this->Line($ftr_line2);
		
		$sign_column = $r_box_left + 40;
		$this->LineTo($this->leftMargin, $ftr_line1 , $this->leftMargin, $ftr_line2);
		$this->LineTo($sign_column, $ftr_line1 , $sign_column, $ftr_line2);
		$this->LineTo($right, $ftr_line1 ,$right, $ftr_line2);

		//footer left content
		$this->row =  $ftr_line1 - 12;
		$this->Text($ccol, _("Registered Office"));

		$this->TextWrapLines($ccol+100, $sign_column-2,$registered_office);

		//footer right content
		$this->row =  $ftr_line1 - 12;
		$this->TextWrap($sign_column, $this->row, $right-$sign_column,"For ".$this->company['coy_name']." Tours & Travels Pvt. Ltd.", 'center');
		$this->NewLine(2);
		$this->TextWrap($sign_column, $this->row, $right-$sign_column,"Authorised Signatory", 'center');
		//$this->Text($sign_column + 2, _("Registered Office"));
		
		
		//table content row
		$temp = $tb_line2 - $this->lineHeight - 2;
		//totals cell
		$totals_row =  $bottom_box1 - $this->lineHeight - 2;
		$totals_column = $r_box_left;
		//price in words cell
		$words_row =  $bottom_box1 - $this->lineHeight - 2;
		$words_column = $ccol;
?>
