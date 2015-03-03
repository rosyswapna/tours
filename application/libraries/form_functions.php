<?php 
class Form_functions{
function populate_dropdown($name = '', $options = array(), $selected = array(),$class='',$id='',$msg='select',$disabled=''){
$CI = & get_instance();

$form = '<select name="'.$name.'" class="'.$class.'" id="'.$id.'" '.$disabled.'/>';
if($selected==''){
$form.='<option value="-1" selected="selected" >--'.$msg.'--</option></br>';
}
else{
$form.='<option value="-1"  >--'.$msg.'--</option></br>';
}
if(!empty($options)){
foreach ($options as $key => $val)
		{
			$key = (string) $key;

			if($key==$selected){
						$sel=' selected="selected"';
						}
						else{
						$sel='';
						}

					$form .= '<option value="'.$key.'"'.$sel.'>'.(string) $val."</option>\n";
					
		}
}
		$form .= '</select>';

		return $form;
}

function populate_editable_dropdown($name = '', $options = array(),$class='',$tbl='',$attr=array(),$msg='',$selected ='',$id=''){
$CI = & get_instance();
$attr_str='';
	foreach($attr as $k=>$v){
		$attr_str.= ' '.$k.'="'.$v.'"';
	}
	
	if($id==''){
	$id="lstDropDown_A";
	}
	
	$form = '<select'.$attr_str.' name='.$name.' id="'.$id.'" class="'.$class.'" onKeyDown="fnKeyDownHandler_A(this, event);" onKeyUp="fnKeyUpHandler_A(this, event); return false;" onKeyPress = "return fnKeyPressHandler_A(this, event);"  onChange="fnChangeHandler_A(this);" onFocus="fnFocusHandler_A(this);" tblname="'.$tbl.'">';

		
	if($selected==''){
		if($msg != ''){ 

			$form.='<option selected="selected" value="">--Select '.$msg.'--</option></br>';
			
		}else{
			$form.='<option selected="selected"></option></br>';
		}
	}else{  
		$form.='<option value="">New '.$msg.'</option></br>';
	}
	
	
	if(!empty($options)){
	foreach ($options as $key => $val)
		{
			$key = (string) $key;
			
			if($key==$selected){
				$sel=' selected="selected"';
			}else{
				$sel='';
			}

			
			$form .= '<option value="'.$key.'"'.$sel.'>'.(string) $val."</option>\n";
					
		}
		}
		
		$form .= '</select>';

		return $form;
}

function form_error_session($field = '', $container_open ='', $container_close=''){
		$CI = & get_instance();
		if(isset($field) && $field!=''){
		$form_error_session=$container_open.$CI->mysession->get($field).$container_close;
		$CI->mysession->delete($field);
		return $form_error_session;
		}else{
		return '';
		}
}

}
?>
