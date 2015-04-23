$(document).ready(function(){


$('.fa-trash-o').click(function(){
	var con = confirm("Do you realy want to delete this record?");
	if(con){
		return true;
	}else{
		return false;
	}
});

//var ORGANIZATION_NAME="Galaxy";
//var ORGANIZATION_NAME= '<?php echo $_SESSION['organisation_name']; ?>';

var total_tarif = 0;// global total tariff
var km_flag = 1;
var hr_flag = 2;



//-------------add vehicle page related js -----------------------



//get driver and vehicle percentage with vehicle ownership type
$("#add-vehicle-ownership").change(function() {
	getPercentages();

});

function getPercentages()
{
	var ownership = $("#add-vehicle-ownership").val();
	$('#add-vehicle-vehicle-percentage option').remove();
	$('#add-vehicle-driver-percentage option').remove();
	$('#add-vehicle-vehicle-percentage').append($("<option value='-1'></option>").text('--Vehicle Percentage--'));
	$('#add-vehicle-driver-percentage').append($("<option value='-1'></option>").text('--Driver Percentage--'));
	if(ownership > 1){
		$.post(base_url+"/trip-booking/getPercentages",
		 {},function(data){
			data=jQuery.parseJSON(data);
			if(data.driver){
				for(var i=0;i<data.driver.length;i++){
					selected_driver = "";
			  	$('#add-vehicle-driver-percentage').append($("<option value='"+data.driver[i].id+"' "+selected_driver+"></option>").text(data.driver[i].value));
				}
			}

			if(data.vehicle){
				
				for(var j=0;j<data.vehicle.length;j++){
					selected = "";
			  	$('#add-vehicle-vehicle-percentage').append($("<option value='"+data.vehicle[j].id+"' "+selected+"></option>").text(data.vehicle[j].value));
				}
			}
		});
	}
}

//------------add vehicle ends--------------------

$('.settings-add').click(function(){ 
var trigger = $(this).parent().prev().prev().find('#editbox').attr('trigger');
if(trigger=='true'){ 
$(this).siblings().find(':submit').trigger('click');
}
});
$('.settings-edit').click(function(){

$(this).siblings().find(':submit').trigger('click');
});
$('.settings-delete').click(function(){
$(this).siblings().find(':submit').trigger('click');
});

google.setOnLoadCallback(drawChart);

function drawChart() {
	var setup_dashboard='setup_dashboard';
  $.post(base_url+"/user/setup_dashboard",
		  {
			setup_dashboard:setup_dashboard
			
		  },function(data){
		  data=jQuery.parseJSON(data);
  var container = document.getElementById('front-desk-dashboard');
  var chart = new google.visualization.Timeline(container);
  var dataTable = new google.visualization.DataTable();
  dataTable.addColumn({ type: 'string', id: 'Room' });
  dataTable.addColumn({ type: 'string', id: 'Name' });
  dataTable.addColumn({ type: 'date', id: 'Start' });
  dataTable.addColumn({ type: 'date', id: 'End' });
	
	var fullDate = new Date();
	var month=fullDate.getMonth()+Number(1);
	var day=fullDate.getDate();
	var twoDigitMonth = ((month.toString().length) != 1)? (month) : ('0'+month);
	var twoDigitDay = ((day.toString().length) != 1)? (day) : ('0'+day);
  	var currentDate = fullDate.getFullYear() + "-"+twoDigitMonth +"-"+twoDigitDay;
	
	var P_time=[];
	var D_time=[];
	var json_obj=[];
	json_obj.push([
  	'All Drivers','Trips Time-Sheet of '+ data.organisation,new Date(0,0,0,0,0,0),new Date(0,0,0,24,0,0)
	]);
	
	for(var i=0;i<data.graph.length;i++){
		P_date=data.graph[i].pick_up_date.split('-');
		D_date=data.graph[i].drop_date.split('-');
		if(data.graph[i].pick_up_date==currentDate){
			P_time=data.graph[i].pick_up_time.split(':');
			
		}else{
			P_time[0]='00';
			P_time[1]='00';
		}
		if(data.graph[i].drop_date==currentDate){
			D_time=data.graph[i].drop_time.split(':');
		}else{
			D_time[0]='23';
			D_time[1]='59';
		}
		var pickdate=new Date(0,0,0,P_time[0],P_time[1],00);
		var dropdate=new Date(0,0,0,D_time[0],D_time[1],00);
		if(data.graph[i].pick_up_city!='' && data.graph[i].drop_city!=''){
			travel_city=data.graph[i].pick_up_city+' to '+data.graph[i].drop_city+',';
			
		}else{
			travel_city='';
			pickdate=new Date(0,0,0,00,00,00);
			dropdate=new Date(0,0,0,00,00,00);
		}
		
		/*json_obj.push([
	  	data[i].name,travel_city+'Driver Mobile '+data[i].mobile,pickdate,dropdate
		]);*/
		json_obj.push([
	  	data.graph[i].name,travel_city,pickdate,dropdate
		]);
		
	}
	
  dataTable.addRows(json_obj);
  
  var options = {
    timeline: { colorByRowLabel: true },
    backgroundColor: '#fff'
  };

  chart.draw(dataTable, options);
	
 });
}


 var base_url=window.location.origin;

$('.print-trip').on('click',function(){
var pickupdatepicker=$('.pickupdatepicker').val();
var dropdatepicker=$('.dropdatepicker').val();
var vehicles=$('#vehicles').val();
var drivers=$('#drivers').val();
var trip_status=$('#trip-status').val();
var url=base_url+'/organization/front-desk/download_xl/trips?';

if(pickupdatepicker!='' || dropdatepicker!='' || vehicles!='-1' || drivers!='-1' || trip_status!='-1' ){
if(pickupdatepicker!=''){
url=url+'pickupdate='+pickupdatepicker;

}
if(dropdatepicker!=''){
url=url+'&dropdate='+dropdatepicker;

}
if(vehicles!='-1'){
url=url+'&vehicles='+vehicles;

}
if(drivers!='-1'){
url=url+'&drivers='+drivers;

}
if(trip_status!='-1'){
url=url+'&trip_status='+trip_status;

}

window.open(url, '_blank');
}
});
$('.print-driver').on('click',function(){
var name=$('#driver_name').val();
var city=$('#driver_city').val();
var status=$('#status').val();
var url=base_url+'/organization/front-desk/download_xl/driver?';

if(name!=''){
url=url+'name='+name;

}
if(city!=''){
url=url+'&city='+city;

}
if(status!='-1'){
url=url+'&status='+status;

}
window.open(url, '_blank');

});
$('.print-vehicle').on('click',function(){

var reg_num=$('#reg_num').val();
var vehicle_owner=$('#vehicle-owner').val();
var vehicle_model=$('#vehicle-model').val();
var vehicle_ownership=$('#vehicle-ownership').val();
var status=$('#status').val();
var url=base_url+'/organization/front-desk/download_xl/vehicle?';

if(reg_num!=''){
url=url+'reg_num='+reg_num;

}
if(vehicle_owner!='-1'){
url=url+'&vehicle_owner='+vehicle_owner;

}
if(vehicle_model!='-1'){
url=url+'&vehicle_model='+vehicle_model;

}
if(vehicle_ownership!='-1'){
url=url+'&vehicle_ownership='+vehicle_ownership;

}
if(status!='-1'){
url=url+'&status='+status;

}
window.open(url, '_blank');
//window.location.replace(url);


});

$('.print-customer').on('click',function(){

var cust_name=$('#name').val();
var cust_mobile=$('#mobile').val();
var cust_type=$('#c_type').val();
var cust_group=$('#c_group').val();
//alert("hi");exit;
var url=base_url+'/organization/front-desk/download_xl/customers?';

if(cust_name!=''){
url=url+'cust_name='+cust_name;

}
if(cust_mobile!=''){
url=url+'&cust_mobile='+cust_mobile;

}
if(cust_type!='-1'){
url=url+'&cust_type='+cust_type;

}
if(cust_group!='-1'){
url=url+'&cust_group='+cust_group;

}
window.open(url, '_blank');
//window.location.replace(url);


});

$('.print-tariff').on('click',function(){
//alert "hi";
var title=$('#title1').val();
var trip_model=$('#model').val();
var ac_type=$('#ac_type').val();
//alert("hi");exit;
var url=base_url+'/organization/front-desk/download_xl/tariffs?';

if(title!=''){
url=url+'&title='+title;

}
if(trip_model!='-1'){
url=url+'&trip_model='+trip_model;

}
if(ac_type!='-1'){
url=url+'&ac_type='+ac_type;

}

window.open(url, '_blank');
//window.location.replace(url);


});

//masters
$('select').change(function(){ 
	var edit=$('.edit').attr('for_edit');
	if(edit=='false'){
		$id=$(this).val();
		$tbl=$(this).attr('tblname');
		$obj=$(this);
		//$(this).attr('trigger',false);
	
		$(this).next().attr('trigger',false);
		$('.edit').attr('for_edit',true);
	  
		if($tbl == 'services')	{
			$.post(base_url+"/vehicle/getStatus",
			  {
				id:$id,
				tbl:$tbl
			  },function(data){ 
			  
				var values=data.split(",",3); //alert(values[1]);
				//$(".#status option[value='"+values[2]+"']").attr('selected', true);
				$obj.parent().find('#id').val(values[0]);
				$obj.parent().find('#editbox').val(values[2]);
				$obj.parent().next().find('#status').val(values[1]);

				$obj.hide();
				$obj.parent().find('#editbox').show();
			});
		}else{


			$.post(base_url+"/vehicle/getDescription",
			  {
				id:$id,
				tbl:$tbl
			  },function(data){
			  
					var values=data.split(",",3);//alert($(this).parent().find('#id').attr('id'));
					  $obj.parent().find('#id').val(values[0]);
					  $obj.parent().find('#editbox').val(values[2]);
					  $obj.parent().next().find('#description').val(values[1]);
		
					$obj.hide();
					$obj.parent().find('#editbox').show();
			});
		}
	}	
			
});



//for tarrif trigger
if($('#tarrif').val()!=-1){
var loc=window.location;
loc=loc.toString();
if(loc.indexOf('trip-booking')>-1){
SetRoughEstimate();
}

}

$('.tarrif-add').click(function(){
$('#tarrif-add-id').trigger('click');
});
$('.tarrif-edit').click(function(){

$(this).siblings().find(':submit').trigger('click');

});
$('.tarrif-delete').click(function(){

$(this).siblings().find(':submit').trigger('click');

});
//************service tab********
$('.service-add').click(function(){ 
$('#service-add-id').trigger('click');
});

//***************
$('.room-tariff').click(function(){ 
$('#room-tariff-id').trigger('click');
});
$('.attribute-tariff').click(function(){ 
$('#attribute-tariff-id').trigger('click');
});
$('.meals-tariff').click(function(){ 
$('#meals-tariff-id').trigger('click');
});


function Trim(strInput) {
	
    while (true) {
        if (strInput.substring(0, 1) != " ")
            break;
        strInput = strInput.substring(1, strInput.length);
    }
    while (true) {
        if (strInput.substring(strInput.length - 1, strInput.length) != " ")
            break;
        strInput = strInput.substring(0, strInput.length - 1);
    }
   return strInput;
	
}

var API_KEY='AIzaSyD3Fog2G5asD5NI4iJJZDsfJHjW-gPhevA';


//trip_bookig page-js start
var pathname = window.location.pathname.split("/");

if(pathname[3]=="trip-booking" || pathname[4]=="trip-booking"){

if($('.advanced-chek-box').attr('checked')=='checked'){ 

$('.group-toggle').toggle();

}

if($('.guest-chek-box').attr('checked')=='checked'){

$('.guest-toggle').toggle();

}
if($('.advanced-vehicle-chek-box').attr('checked')=='checked'){ 

$('.group-vehicle-toggle').toggle();

}

if($('.beacon-light-chek-box').attr('checked')=='checked'){
var radio_button_to_be_checked = $('.beacon-light-chek-box').attr('radio_to_be_selected');
if(radio_button_to_be_checked=='red'){

$('.beacon-radio1-container > .iradio_minimal > .iCheck-helper').trigger('click');

	

}else if(radio_button_to_be_checked=='blue'){

$('.beacon-radio2-container > .iradio_minimal > .iCheck-helper').trigger('click');

	

}
}
/*
if($("#trip_id").val() > -1) {

$('#email').attr('disabled','');
$('#customer').attr('disabled','');
$('#mobile').attr('disabled','');

}
*/


if($("#pickupcity").val()!=''){
getDistance();
}

if($("#viacity").val()!='' || $("#viaarea").val()!='' || $("#vialandmark").val()!=''){
$('.toggle-via').toggle();
}


if($('.recurrent-yes-chek-box').attr('checked')=='checked'){

var radio_button_to_be_checked = $('.recurrent-yes-chek-box').attr('radio_button_to_be_checked');

$('.recurrent-radio-container').toggle();

if(radio_button_to_be_checked=='continues'){


$('.recurrent-container-continues').show();
$('#reccurent_continues_pickupdatepicker').daterangepicker({format: 'MM/DD/YYYY'});
$('#reccurent_continues_dropdatepicker').daterangepicker({format: 'MM/DD/YYYY'});

$('#reccurent_continues_pickuptimepicker').datetimepicker({datepicker:false,
	format:'H:i',
	step:30
});
$('#reccurent_continues_droptimepicker').datetimepicker({datepicker:false,
	format:'H:i',
	step:30
});


$('.recurrent-container-alternatives').hide();

}else if(radio_button_to_be_checked=='alternatives'){


$('.recurrent-container-continues').hide();

$('.recurrent-container-alternatives').show();

var count = $('.add-reccurent-dates').attr('count');
var slider=$('.reccurent-container').attr('slider');
if(slider>=2){
$('.reccurent-slider').css('overflow-y','scroll');
$('.reccurent-slider').css('height','300px');
}else{
$('.reccurent-container').attr('slider',Number(slider)+1);
}
for(var i=0;i<count;i++){
$('#reccurent_alternatives_pickupdatepicker'+i).datetimepicker({timepicker:false,format:'Y-m-d',formatDate:'Y-m-d'});
$('#reccurent_alternatives_dropdatepicker'+i).datetimepicker({timepicker:false,format:'Y-m-d',formatDate:'Y-m-d'});

$('#reccurent_alternatives_pickuptimepicker'+i).datetimepicker({datepicker:false,
	format:'H:i',
	step:30
});
$('#reccurent_alternatives_droptimepicker'+i).datetimepicker({datepicker:false,
	format:'H:i',
	step:30
});
}
}


}
if( $('#vehicle-ac-type').val()!=-1 || $('#vehicle-model').val()!=-1){ 

if(($('.vehicle-tarif-checker').attr('tariff_id')!=-1 || $('.vehicle-tarif-checker').attr('tariff_id')!='') && ($('.vehicle-tarif-checker').attr('available_vehicle_id')!=-1 || $('.vehicle-tarif-checker').attr('available_vehicle_id')!='')){
tariff_id=$('.vehicle-tarif-checker').attr('tariff_id');//alert(tariff_id);
available_vehicle_id=$('.vehicle-tarif-checker').attr('available_vehicle_id');
GenerateVehiclesAndTarif(tariff_id,available_vehicle_id);
}else if(($('.vehicle-tarif-checker').attr('tariff_id')!=-1 || $('.vehicle-tarif-checker').attr('tariff_id')!='') && ($('.vehicle-tarif-checker').attr('available_vehicle_id')==-1 || $('.vehicle-tarif-checker').attr('available_vehicle_id')=='')){		
tariff_id=$('.vehicle-tarif-checker').attr('tariff_id');//alert(tariff_id);
	
GenerateVehiclesAndTarif(tarif_id,available_vehicle_id='');
}else if(($('.vehicle-tarif-checker').attr('tariff_id')==-1 || $('.vehicle-tarif-checker').attr('tariff_id')=='')  && ($('.vehicle-tarif-checker').attr('available_vehicle_id')!=-1 || $('.vehicle-tarif-checker').attr('available_vehicle_id')!='')){		
available_vehicle_id=$('.vehicle-tarif-checker').attr('available_vehicle_id');
	
GenerateVehiclesAndTarif(tarif_id='',available_vehicle_id);
}else if(($('.vehicle-tarif-checker').attr('tariff_id')==-1 || $('.vehicle-tarif-checker').attr('tariff_id')=='') && ($('.vehicle-tarif-checker').attr('available_vehicle_id')==-1 || $('.vehicle-tarif-checker').attr('available_vehicle_id')=='')){		
available_vehicle_id=$('.vehicle-tarif-checker').attr('available_vehicle_id');
	
GenerateVehiclesAndTarif(tarif_id='',available_vehicle_id='');
}

}

}

$('#pickupdatepicker').datetimepicker({timepicker:false,format:'Y-m-d',formatDate:'Y-m-d'});
$('#dropdatepicker').datetimepicker({timepicker:false,format:'Y-m-d',formatDate:'Y-m-d'});
$('#pickuptimepicker').datetimepicker({datepicker:false,
	format:'H:i',
	step:5
});
$('#droptimepicker').datetimepicker({datepicker:false,
	format:'H:i',
	step:5
});

$('.beacon-light-chk-box-container > .icheckbox_minimal > .iCheck-helper').on('click',function(){

if($('.beacon-light-chek-box').attr('checked')=='checked'){
	$('.beacon-radio1-container > .iradio_minimal > .iCheck-helper').trigger('click');
}else{
	$('.beacon-radio1-container > .iradio_minimal').removeClass('checked');
	$('.beacon-radio2-container > .iradio_minimal').removeClass('checked');
	$('#beacon-light-radio1').prop('checked',false);
	$('#beacon-light-radio2').prop('checked',false);
}

});


//date picker removed for pickupdat n time drop date n time

$('#pickupdatepicker').datetimepicker({timepicker:false,format:'Y-m-d',formatDate:'Y-m-d'});
$('#dropdatepicker').datetimepicker({timepicker:false,format:'Y-m-d',formatDate:'Y-m-d'});
$('#pickuptimepicker').datetimepicker({datepicker:false,
	format:'H:i',
	step:30
});
$('#droptimepicker').datetimepicker({datepicker:false,
	format:'H:i',
	step:30
});

$('#via').click(function(event){
	event.preventDefault();
$('.toggle-via').toggle();


});

$('.advanced-container > .icheckbox_minimal > .iCheck-helper').on('click',function(){

$('.group-toggle').toggle();


});
$('.advanced-vehicle-container > .icheckbox_minimal > .iCheck-helper').on('click',function(){

$('.group-vehicle-toggle').toggle();


});
$('.guest-container > .icheckbox_minimal > .iCheck-helper').on('click',function(){

$('.guest-toggle').toggle();


});


 var base_url=window.location.origin;


$('.recurrent-yes-container > .icheckbox_minimal > .iCheck-helper').on('click',function(){

$('.recurrent-radio-container').toggle();
$('.recurrent-radio-container > .div-continues > .iradio_minimal > .iCheck-helper').trigger('click');
if($('.recurrent-yes-chek-box').attr('checked')!='checked'){
if(Trim($('.recurrent-container-continues').css('display'))=='block' || Trim($('.recurrent-container-alternatives').css('display'))=='block' ){
$('.recurrent-container-continues').hide();
$('.recurrent-container-alternatives').hide();
}
}
});

$('.recurrent-radio-container > .div-continues > .iradio_minimal > .iCheck-helper').on('click',function(){

$('.recurrent-container-continues').show();
$('#reccurent_continues_pickupdatepicker').daterangepicker({format: 'MM/DD/YYYY'});
$('#reccurent_continues_dropdatepicker').daterangepicker({format: 'MM/DD/YYYY'});

$('#reccurent_continues_pickuptimepicker').datetimepicker({datepicker:false,
	format:'H:i',
	step:30
});
$('#reccurent_continues_droptimepicker').datetimepicker({datepicker:false,
	format:'H:i',
	step:30
});


$('.recurrent-container-alternatives').hide();


});


$('.recurrent-radio-container > .div-alternatives > .iradio_minimal > .iCheck-helper').on('click',function(){

$('.recurrent-container-continues').hide();

$('.recurrent-container-alternatives').show();
$('#reccurent_alternatives_pickupdatepicker0').datetimepicker({timepicker:false,format:'Y-m-d',formatDate:'Y-m-d'});
$('#reccurent_alternatives_dropdatepicker0').datetimepicker({timepicker:false,format:'Y-m-d',formatDate:'Y-m-d'});


$('#reccurent_alternatives_pickuptimepicker0').datetimepicker({datepicker:false,
	format:'H:i',
	step:30
});
$('#reccurent_alternatives_droptimepicker0').datetimepicker({datepicker:false,
	format:'H:i',
	step:30
});

});

$('.add-reccurent-dates').click(function(){
var slider=$('.reccurent-container').attr('slider');
if(slider=='2'){
$('.reccurent-slider').css('overflow-y','scroll');
$('.reccurent-slider').css('height','300px');
}else{
$('.reccurent-container').attr('slider',Number(slider)+1);
}
var count = $('.add-reccurent-dates').attr('count');
var new_content='<div class="form-group"><input name="reccurent_alternatives_pickupdatepicker[]" value="" class="form-control width-60-percent-with-margin-10" id="reccurent_alternatives_pickupdatepicker'+count+'" placeholder="Pick up Date" type="text"><input name="reccurent_alternatives_pickuptimepicker[]" value="" class="form-control width-30-percent-with-margin-left-20" id="reccurent_alternatives_pickuptimepicker'+count+'" placeholder="Pick up Time" type="text"></div><div class="form-group"><input name="reccurent_alternatives_dropdatepicker[]" value="" class="form-control width-60-percent-with-margin-10" id="reccurent_alternatives_dropdatepicker'+count+'" placeholder="Drop Date" type="text"><input name="reccurent_alternatives_droptimepicker[]" value="" class="form-control width-30-percent-with-margin-left-20" id="reccurent_alternatives_droptimepicker'+count+'" placeholder="Drop time " type="text"></div>';
$('.new-reccurent-date-textbox').append(new_content);
$('#reccurent_alternatives_pickupdatepicker'+count).datetimepicker({timepicker:false,format:'Y-m-d',formatDate:'Y-m-d'});
$('#reccurent_alternatives_dropdatepicker'+count).datetimepicker({timepicker:false,format:'Y-m-d',formatDate:'Y-m-d'});

$('#reccurent_alternatives_pickuptimepicker'+count).datetimepicker({datepicker:false,
	format:'H:i',
	step:30
});
$('#reccurent_alternatives_droptimepicker'+count).datetimepicker({datepicker:false,
	format:'H:i',
	step:30
});

$('.add-reccurent-dates').attr('count',Number(count)+1);
});

//for checking user in db
$('#email,#mobile').on('keyup click',function(){
var email=$('#email').val();
var mobile=$('#mobile').val();
	if(Trim(email)=="" && Trim(mobile)==""){
		$('.add-customer').hide();
	}
    if(Trim(email)==""){
        
    }else{
	    
	    pattern = /^[a-zA-Z0-9]\w+(\.)?\w+@\w+\.\w{2,5}(\.\w{2,5})?$/;
	    result = pattern.test(email);
	    if( result== false) {
	     email='';
	    }
	}
 
    if(Trim(mobile)==""){
       
    }else{
   var regEx = /^(\+91|\+91|0)?\d{10}$/;
   
	if (!mobile.match(regEx)) {
 		 mobile='';
     }
	}
	if(Trim(mobile)!="" || Trim(email)!=""){
	$.post(base_url+'/customers/customer-check',{
	email:email,
	mobile:mobile,
	customer:'yes'
	},function(data){
	if(data!=false){
		data=jQuery.parseJSON(data);
		$('#customer').val(data[0].name);
		$('#email').val(data[0].email);	
		$('#mobile').val(data[0].mobile);
		$(".passenger-basic-info > .form-group > label[for=name_error]").text('');
		$(".passenger-basic-info > .form-group > label[for=email_error]").text('');
		$(".passenger-basic-info > .form-group > label[for=mobile_error]").text('');
		$('#customer-group').val('');
		$('.new-customer').attr('value',false);
		if(data[0].customer_group_id>0){
			
			$('#customer-group').val(data[0].customer_group_id);
			
			}
			
		$('.clear-customer').show();
		$('.add-customer').hide();
      }else{
		$('.clear-customer').hide();
		$('.add-customer').show();
	}
	});
	}
	});
//guest passengerchecking in db

	$('#guestemail,#guestmobile').on('keyup click',function(){
var email=$('#guestemail').val();
var mobile=$('#guestmobile').val();
	
    if(Trim(email)==""){
        
    }else{
	    
	    pattern = /^[a-zA-Z0-9]\w+(\.)?\w+@\w+\.\w{2,5}(\.\w{2,5})?$/;
	    result = pattern.test(email);
	    if( result== false) {
	     email='';
	    }
	}
 
    if(Trim(mobile)==""){
       
    }else{
	   var regEx = /^(\+91|\+91|0)?\d{10}$/;
	   if (!mobile.match(regEx)) {
	 		 mobile='';
		 }
	}
	if(Trim(mobile)!="" || Trim(email)!=""){
	$.post(base_url+'/customers/customer-check',{
	email:email,
	mobile:mobile,
	customer:'no'
	},function(data){
	if(data!=false){
		data=jQuery.parseJSON(data);
		$('#guestname').val(data[0].name);
		$('#guestemail').val(data[0].email);	
		$('#guestmobile').val(data[0].mobile);
		$('#guest_id').val(data[0].id);
		$('.clear-guest').show();
		
      }
	});
	}
	});
	//clear customer information fields
	$('.clear-customer').click(function(){
		$('#customer').val('');
		$('#email').val('');	
		$('#mobile').val('');
		$('.clear-customer').hide();
		$(".passenger-basic-info > .form-group > label[for=name_error]").text('');
		$(".passenger-basic-info > .form-group > label[for=email_error]").text('');
		$(".passenger-basic-info > .form-group > label[for=mobile_error]").text('');
		$('#customer-group').val('');
		

	});
	//clear guest information fields
	$('.clear-guest').click(function(){
		$('#guestname').val('');
		$('#guestemail').val('');	
		$('#guestmobile').val('');
		$('.clear-guest').hide();
		$('#guest_id').val('-1');
		
	});

	//add pasenger informations
	$('.add-customer').click(function(){
		var name =$('#customer').val();
		var email=$('#email').val();
		var mobile=$('#mobile').val();
		var error_email ="";
		var error_mobile ="";
		var error_name='';
	if(Trim(name)==""){
		error_name ="Name is mandatory";
	}
    if(Trim(email)=="" && Trim(mobile)==""){
       alert("Please Enter Phone Number or Email");
		return false;
    }else{
	    if(Trim(email)!=""){
	    pattern = /^[a-zA-Z0-9]\w+(\.)?\w+@\w+\.\w{2,5}(\.\w{2,5})?$/;
	    result = pattern.test(email);
	    if( result== false) {
	      error_email ="Entered email is is not valid";
	    }
		}
		if(Trim(mobile)!=""){
		var regEx = /^(\+91|\+91|0)?\d{10}$/;
   
		if (!mobile.match(regEx)) {
	 		error_mobile ="Mobile is not valid";
		 }
		}
		}
 
    
	if(error_mobile!='' || error_email!='' || error_name!='')
	{
	$(".passenger-basic-info > .form-group > label[for=name_error]").text(error_name);
	$(".passenger-basic-info > .form-group > label[for=email_error]").text(error_email);
	$(".passenger-basic-info > .form-group > label[for=mobile_error]").text(error_mobile);
	}else{
	$.post(base_url+'/customers/add-customer',{
	name:name,
	email:email,
	mobile:mobile
	},function(data){
	if(data!=true){
	
	}else{
	
	$('.new-customer').attr('value',false);
	$(".passenger-basic-info > .form-group > label[for=name_error]").html('');
	$(".passenger-basic-info > .form-group > label[for=email_error]").html('');
	$(".passenger-basic-info > .form-group > label[for=mobile_error]").text('');
	$('#email').trigger('click');
	}

	});

	}


	});


/*$("#pickupcity").on('keyup',function(){
var pickupcity=$("#pickupcity").val();
if(pickupcity!='' && pickupcity.length>3){

placeAutofillGenerator(pickupcity,'autofill-pickupcity','pickupcity');

}
});

$("#dropdownlocation").on('keyup',function(){

var dropdownlocation=$("#dropdownlocation").val();
if(dropdownlocation!='' && dropdownlocation.length>3){

placeAutofillGenerator(dropdownlocation,'autofill-dropdownlocation','dropdownlocation');

}
});

$("#viacity").on('keyup',function(){
var viacity=$("#viacity").val();
if(viacity!='' && viacity.length>3){

placeAutofillGenerator(viacity,'autofill-viacity','viacity');

}
});


$("#pickupcity,#pickuparea,#dropdownlocation,#dropdownarea,#viacity,#viaarea").on('keyup click',function(){
var pickupcity=$("#pickupcity").val();
var dropdownlocation=$("#dropdownlocation").val();
var viacity=$("#viacity").val();
if(pickupcity!='' && pickupcity.length>3 && dropdownlocation!='' && dropdownlocation.length>3 || viacity!='' && viacity.length>3){
getDistance();
}
});
*/

//-----------------starts --new google maps via place selection in trip page *** previous code for trip booking
	/*var options = {
           
			componentRestrictions: {country: "IN"}

         };
	var autocompletepickup = new google.maps.places.Autocomplete($("#pickupcity")[0], options);
	var autocompletedrop = new google.maps.places.Autocomplete($("#dropdownlocation")[0], options);
	var autocompletevia = new google.maps.places.Autocomplete($("#viacity")[0], options);
		
		google.maps.event.addListener(autocompletepickup, 'place_changed', function() {
			var place = autocompletepickup.getPlace();
			var cityLat = place.geometry.location.lat();
			var cityLng = place.geometry.location.lng();
			$('#pickupcitylat').attr('value',cityLat);
			$('#pickupcitylng').attr('value',cityLng);
			$("#pickupcity").attr('value',place.name);
			getDistance();
		}); 
		google.maps.event.addListener(autocompletedrop, 'place_changed', function() {
			var place = autocompletedrop.getPlace();
			var cityLat = place.geometry.location.lat();
			var cityLng = place.geometry.location.lng();
			$('#dropdownlocationlat').attr('value',cityLat);
			$('#dropdownlocationlng').attr('value',cityLng);
			$("#dropdownlocation").attr('value',place.name);
			getDistance();
		}); 
		google.maps.event.addListener(autocompletevia, 'place_changed', function() {
			var place = autocompletevia.getPlace();
			var cityLat = place.geometry.location.lat();
			var cityLng = place.geometry.location.lng();
			$('#viacitylat').attr('value',cityLat);
			$('#viacitylng').attr('value',cityLng);
			$("#viacity").attr('value',place.name);
			getDistance();
		}); */
		 
		 //--------ends-- new google maps
		 
	//-----------------starts --new google maps via place selection in destination page
	var options = {
           
			componentRestrictions: {country: "IN"}

         };
	 
	
	
	var autocompletedest = new google.maps.places.Autocomplete($("#dest_name")[0], options);
	
	
	google.maps.event.addListener(autocompletedest, 'place_changed', function() {
			var place = autocompletedest.getPlace();
			var cityLat = place.geometry.location.lat();
			var cityLng = place.geometry.location.lng();
			$('#dest_lat').attr('value',cityLat);
			$('#dest_long').attr('value',cityLng);
			$("#dest_name").attr('value',place.name);
		}); 
	
	
		
	
		 
		 //--------ends-- new google maps
function getDistance(){

var pickupcity=$("#pickupcity").val();//alert(pickupcity);
//var pickuparea=$("#pickuparea").val();
var viacity=$("#viacity").val();
//var viaarea=$("#viaarea").val();
var dropdownlocation=$("#dropdownlocation").val();
//var dropdownarea=$("#dropdownarea").val();
var origin='';
var destination='';
if(pickupcity!=''){
pickupcity=pickupcity.replace(/\s/g,"");
origin=pickupcity;

}
/*if(pickuparea!='' && pickupcity!=''){
pickuparea=pickuparea.replace(/\s/g,"");
origin=origin+'+'+pickuparea;

}*/

if(viacity!=''){
viacity=viacity.replace(/\s/g,"");
origin=origin+'|'+viacity;
destination=viacity;
}
/*if(viaarea!='' && viacity!=''){
viaarea=viaarea.replace(/\s/g,"");
origin=origin+'+'+viaarea;
destination=destination+'+'+viaarea;
}*/

if(dropdownlocation!=''){
if(viacity!=''){
destination=destination+'|';
}
dropdownlocation=dropdownlocation.replace(/\s/g,"");
if(destination==''){
destination=dropdownlocation;
}else{
destination=destination+dropdownlocation;
}

}
/*if(dropdownarea!='' && dropdownlocation!=''){
dropdownarea=dropdownarea.replace(/\s/g,"");
destination=destination+'+'+dropdownarea;

}*/
if(viacity!=''){
var via='YES';
}else{
var via='NO';
}
if(origin!='' && destination!=''){

var url='https://maps.googleapis.com/maps/api/distancematrix/json?origins='+origin+'&destinations='+destination+'&mode=driving&language=en&key='+API_KEY;

$.post(base_url+'/maps/get-distance',{
	url:url,
	via:via
	},function(data){
data=jQuery.parseJSON(data);
if(data.No_Data=='false'){
if(data.via=='NO'){
var tot_distance = data.distance.replace(/\km\b/g, '');
$('.estimated-distance-of-journey').html(data.distance);
$('.estimated-distance-of-journey').attr('estimated-distance-of-journey',tot_distance);
$('#est_distance').val(tot_distance);
$('.estimated-time-of-journey').html(data.duration);
$('#time_journey').val(data.duration);
}else if(data.via=='YES'){
var tot_duration_sec=Number(data.first_duration)+Number(data.second_duration);
hours = Math.floor(tot_duration_sec / 3600);
tot_duration_sec %= 3600;
minutes = Math.floor(tot_duration_sec / 60);
seconds = tot_duration_sec % 60;
var total_duration=hours+"hr "+minutes+"min "+seconds+"sec";
/*var tot_duration_min=tot_duration_sec/60;
if(tot_duration_min>60){
//var tot_duration=((tot_duration_min/60).toFixed(2)).toString();  
var tot_duration=(tot_duration_min/60).toString(); 
if(tot_duration.indexOf('.') != -1){
var duration_split=tot_duration.split('.'); 
var total_hr=duration_split[0]; 
var total_min=duration_split[1];
var total_duration=total_hr+"hr "+total_min+" min";
}
else{
alert ("no");
}
var duration_split=tot_duration.split('.'); 
var total_hr=duration_split[0]; 
var total_min=duration_split[1];
var total_duration=total_hr+"hr "+total_min+" min";

}else{
var total_duration=tot_duration_min+"min";
}
alert(tot_duration_min);*/

/*first_duration=data.first_duration.replace(/\hour\b/g, 'h');
first_duration=first_duration.replace(/\hours\b/g, 'h');
first_duration=first_duration.replace(/\mins\b/g, 'm');
second_duration=data.second_duration.replace(/\hours\b/g, 'h');
second_duration=second_duration.replace(/\hour\b/g, 'h');
second_duration=second_duration.replace(/\mins\b/g, 'm');
*/
var first_distance = data.first_distance.replace(/\km\b/g, '');

var second_distance = data.second_distance.replace(/\km\b/g, '');
var tot_distance=Number(first_distance)+Number(second_distance);
//var distance_estimation='<div class="via-distance-estimation">Pick up to Via Loc : '+data.first_distance+'<br/> Via to Drop Loc : '+data.second_distance+'</div>';
var distance_estimation='<div class="via-distance-estimation">'+tot_distance+' km </div>'; 
//var duration_estimation='<div class="via-duration-estimation">Pick up to Via Loc : '+first_duration+'<br/>  Via to Drop Loc : '+second_duration+'</div>';
var duration_estimation='<div class="via-duration-estimation">'+total_duration+'</div>';
var est_distance=tot_distance+' km';
var time_journey=total_duration;
$('.estimated-distance-of-journey').html(distance_estimation);
$('#time_journey').val(time_journey);
$('#est_distance').val(est_distance);
$('.estimated-distance-of-journey').attr('estimated-distance-of-journey',tot_distance);

$('.estimated-time-of-journey').html(duration_estimation);  
}
}else{
$('.estimated-distance-of-journey').html('');
$('.estimated-time-of-journey').html('');
}
});


}
}

function placeAutofillGenerator(city,ul_class,insert_to){
var insert_to=insert_to;
$('#'+insert_to).prop('disabled', true);

$('.display-me').css('display','block');

var 
url='https://maps.googleapis.com/maps/api/place/autocomplete/json?input='+city+'&types=(cities)&components=country:IN&language=en&key='+API_KEY;

$.post(base_url+'/maps/get-places',{
	url:url,
	insert_to:insert_to
	},function(data){
if(data!='false'){
$('.'+ul_class).html(data);
$('.'+ul_class).parent().addClass('open');
$('.display-me').css('display','none');
$('#'+insert_to).prop('disabled', false);
}

});

}
$('html').click(function(){
$('.input-group-btn').removeClass('open');
});

$('.drop-down-places').live('click',function(e){

var insert_to=$(this).attr('insert_to');
var place=$(this).attr('place');
var full_address=$(this).text();
full_address=replaceCommas(full_address);
full_address=full_address.replace(/\s+/g, '');
$('#'+insert_to).val(place);
$('#'+insert_to).trigger('click');
$(this).parent().parent().parent().removeClass('open');
getLatLng(full_address,insert_to);
});

function replaceCommas(place){ 
	 var placeArray = place.split(','); 
	 var placeWithoutCommas=''; 
	 for(var index=0;index<placeArray.length;index++){ 
		if(index==0){
			placeWithoutCommas+=placeArray[index]; 
		}else{
			placeWithoutCommas+='+'+placeArray[index]; 
		}
	} 
	 return placeWithoutCommas; 
}

function getLatLng(city,text_box_class){

var url='https://maps.googleapis.com/maps/api/geocode/json?address='+city+'&&components=country:IN&language=en&key='+API_KEY;
var text_box_class = text_box_class;
$.post(base_url+'/maps/get-latlng',{
	url:url
	},function(data){
data=jQuery.parseJSON(data);
if(data!='false'){
$('#'+text_box_class+'lat').attr('value',data.lat);
$('#'+text_box_class+'lng').attr('value',data.lng);
}

});

}




var test = 1;
window.onbeforeunload = function(){
	var redirect=$('.book-trip-validate').attr('enable_redirect');
	var pathname = window.location.pathname.split("/");
	if(pathname[3]=="trip-booking" && redirect!='true'){
    setTimeout(function(){
        test = 2;
    },500)
    return "If you leave this page, data may be lost.";
	}
}






    setInterval(function(){
    if (test === 2) {
       test = 3; 
    }
    },50);
  


$('.book-trip-validate').on('click',function(){

if($('.new-customer').val()=='false'){
$('.book-trip-validate').attr('enable_redirect','true');
$('.book-trip').trigger('click');
}else{
//alert($('.new-customer').val());return false;
alert("Add Customer Informations");

}


});

$('.cancel-trip-validate').on('click',function(){

if($('.new-customer').val()=='false'){//alert('clciked');
$('.book-trip-validate').attr('enable_redirect','true');
$('.cancel-trip').trigger('click');
}else{

alert("Add Customer Informations");

}
});

//rate display
$('#tarrif').on('change',function(){
var loc=window.location;
loc=loc.toString();
if(loc.indexOf('trip-booking')>-1){
SetRoughEstimate();
}
});
	
function SetRoughEstimate(){

		var vehicle_model=$('#tarrif option:selected').attr('vehicle_model_id');
		var vehicle_ac_type=$('#tarrif option:selected').attr('vehicle_ac_type_id');
		var tariff_master_id=$('#tarrif option:selected').attr('tariff_master_id');
		 $.post(base_url+"/tarrif/customertariff",
		  {
			vehicle_model_id:vehicle_model,
			vehicle_ac_type_id:vehicle_ac_type,
			tariff_master_id:tariff_master_id,
			from:'trip-booking'
		  },function(data){

		data=jQuery.parseJSON(data);
		if(data!=false){
			var additional_kilometer_rate = data.data[0].additional_kilometer_rate;
			var minimum_kilometers = data.data[0].minimum_kilometers;
			var rate = data.data[0].rate;
		}else{
		
			var additional_kilometer_rate = $('#tarrif option:selected').attr('additional_kilometer_rate');
			var minimum_kilometers = $('#tarrif option:selected').attr('minimum_kilometers');
			var rate = $('#tarrif option:selected').attr('rate');

		}

var estimated_distance = $('.estimated-distance-of-journey').attr('estimated-distance-of-journey');

var extra_charge=0;

var pickupdate = $('#pickupdatepicker').val();
var pickuptime = $('#pickuptimepicker').val();
var dropdate = $('#dropdatepicker').val();
var droptime = $('#droptimepicker').val();
	
	pickupdate=pickupdate.split('-');
	dropdate=dropdate.split('-');
    var start_actual_time  =  pickupdate[0]+'/'+pickupdate[1]+'/'+pickupdate[2]+' '+pickuptime;
    var end_actual_time    =  dropdate[0]+'/'+dropdate[1]+'/'+dropdate[2]+' '+droptime;


    start_actual_time = new Date(start_actual_time);
    end_actual_time = new Date(end_actual_time);

    var diff = end_actual_time - start_actual_time;

    var diffSeconds = diff/1000;
    var HH = Math.floor(diffSeconds/3600);
    var MM = Math.floor(diffSeconds%3600)/60;
	var no_of_days=Math.floor(HH/24);
    if((HH>=24 && MM>=1) || HH>24){
      no_of_days=no_of_days+1; 
		var days="Days";
    }else{
 	no_of_days=1;
	var days="Day";
	}
if($('#tarrif').val()!='-1'){
var extra_distance='';
var extra_charge='';
if(HH>=24){

if(Number(estimated_distance) > Number(minimum_kilometers)*Number(no_of_days)){
var extra_distance=Number(estimated_distance)-(Number(minimum_kilometers)*Number(no_of_days));
charge=Number(no_of_days)*Number(rate);
extra_charge=Number(extra_distance)*Number(additional_kilometer_rate);
amount=Math.round(Number(charge)+Number(extra_charge)).toFixed(2);

}else{
amount=Math.round(Number(no_of_days)*Number(rate)).toFixed(2);

}


}else{


if(Number(estimated_distance) > minimum_kilometers){
var extra_distance=Number(estimated_distance)-Number(minimum_kilometers);
charge=Number(rate);
extra_charge=Number(extra_distance)*Number(additional_kilometer_rate);
amount=Math.round(Number(charge)+Number(extra_charge)).toFixed(2);

}else{
amount=Math.round(Number(rate)).toFixed(2);

}

}
var tax=((amount*4.944)/100).toFixed(2);
var total=Number(tax)+Number(amount);



$('.additional-km').html(extra_distance+' Km');
$('#additional-km').val(extra_distance+' Km');
$('.additional-rate-per-km').html('RS . '+additional_kilometer_rate);
$('.additional-charge-per-km').html('RS . '+extra_charge);
$('#additional-charge').val('Rs . '+extra_charge);
$('.mini-km').html(minimum_kilometers+' Km');
$('#min_kilo').val(minimum_kilometers+' Km');
$('.charge-per-km').html('RS . '+rate);
$('#charge').val('RS . '+rate);
$('.estimated-amount').html('RS . '+amount);
$('#amt').val('RS . '+amount);
$('.tax-payable').html('RS . '+tax);
$('#tax').val('RS . '+tax);
$('.estimated-total-amount').html('RS . '+total);
$('#tot_amt').val('RS . '+total);
$('.no-of-days').html(no_of_days+' '+days+' Trip');

}else{
$('.additional-km').html('0 Km');
$('#additional-km').val('0 Km');
$('.additional-charge-per-km').html('RS . 0');
$('#additional-charge').val('RS . 0');
$('.mini-km').html('0 Km');
$('#min_kilo').val('0 Km');
$('.charge-per-km').html('RS . 0');
$('#charge').val('RS . 0');
$('.estimated-amount').html('RS .0');
$('#amt').val('RS .0');
$('.tax-payable').html('RS .0');
$('#tax').val('RS .0');
$('.estimated-total-amount').html('RS . 0');
$('#tot_amt').val('RS . 0');
$('.no-of-days').html(no_of_days+' '+days+' Trip');

}
});
}
/*
$('#tarrif,#available_vehicle').on('change',function(){
var tarriff_vehicle_make_id=$('#tarrif option:selected').attr('vehicle_make_id');
var avaiable_vehicle_make_id=$('#available_vehicle option:selected').attr('vehicle_make_id');


if($('#tarrif').val()!=-1 && $('#available_vehicle').val()!=-1){
if(tarriff_vehicle_make_id!=avaiable_vehicle_make_id){
alert('Select Vehicle and Tarrif correctly.');
}
}
});
*/

$('#vehicle-type').on('change',function(){
$('#vehicle-make').val('');
$('#vehicle-model').val('');
});

//tarrif selecter
$('#vehicle-ac-type,#vehicle-model').on('change',function(){
GenerateVehiclesAndTarif(tarif_id='',available_vehicle_id='');
});

function GenerateVehiclesAndTarif(tarif_id='',available_vehicle_id=''){
var vehicle_type = $('#vehicle-type').val();
var vehicle_ac_type = $('#vehicle-ac-type').val();
var vehicle_model = $('#vehicle-model').val();
var vehicle_make = $('#vehicle-make').val();

var tarif_id=tarif_id;
var pickupdate = $('#pickupdatepicker').val();
var pickuptime = $('#pickuptimepicker').val();
var dropdate = $('#dropdatepicker').val();
var droptime = $('#droptimepicker').val();

if(vehicle_model!=-1 && vehicle_ac_type!=-1 && pickupdate!='' && pickuptime!='' && dropdate!='' && droptime!='' ){

var pickupdatetime = pickupdate+' '+pickuptime+':00';
var dropdatetime   = dropdate+' '+droptime+':00';
$('.display-me').css('display','block');
id="#tarrif";
generateAvailableVehicles(vehicle_type,vehicle_make,vehicle_model,vehicle_ac_type,pickupdatetime,dropdatetime,available_vehicle_id);
generateTariffs(vehicle_model,vehicle_ac_type,tarif_id,id);

}else if(vehicle_model!=-1 && vehicle_ac_type!=-1){
$('.display-me').css('display','block');
generateTariffs(vehicle_model,vehicle_ac_type,tarif_id,id);

}


}



function generateAvailableVehicles(vehicle_type,vehicle_make,vehicle_model,vehicle_ac_type,pickupdatetime,dropdatetime,available_vehicle_id=''){
	

	 $.post(base_url+"/trip-booking/getAvailableVehicles",
		  {
			vehicle_type:vehicle_type,
			vehicle_make:vehicle_make,
			vehicle_model:vehicle_model,
			vehicle_ac_type:vehicle_ac_type,
			pickupdatetime:pickupdatetime,
			dropdatetime:dropdatetime,
			available_vehicle_id:available_vehicle_id
	},function(data){ 
			

		$('#available_vehicle option').remove();
		$('#available_vehicle').append($("<option value=''></option>").text('--Select Vehicle--'));
		
		if(data!='false'){ 
			data=jQuery.parseJSON(data);
			for(var i=0;i<data.data.length;i++){

			  $('#available_vehicle').append($("<option value='"+data.data[i].vehicle_id+"' vehicle_model_id='"+data.data[i].vehicle_model_id+"'  vehicle_make_id='"+data.data[i].vehicle_make_id+"'></option>").text(data.data[i].registration_number));
				
			}
	
		}else{	
			alert('No Available Vehicles');		
		}

		$('#available_vehicle').val(available_vehicle_id);

		$('.display-me').css('display','none');
	   });

}

function generateTariffs(vehicle_model,vehicle_ac_type,tarif_id='',id){
	var tarif_id=tarif_id;
	 $.post(base_url+"/tarrif/tariffSelecter",
		  {
			vehicle_model:vehicle_model,
			vehicle_ac_type:vehicle_ac_type
		  },function(data){
			if(data!='false'){
			data=jQuery.parseJSON(data);
			$(id+' option').remove();
			 $(id).append($("<option rate='-1' additional_kilometer_rate='-1' minimum_kilometers='-1'></option>").attr("value",'-1').text('--Select Tariff--'));
			i=0;//alert(data.data.length);
			for(var i=0;i<data.data.length;i++){
			if(tarif_id==data.data[i].id){
			var selected="selected=selected";
			}else{
			var selected="";
			}
			  $(id).append($("<option rate='"+data.data[i].rate+"' additional_kilometer_rate='"+data.data[i].additional_kilometer_rate+"' minimum_kilometers='"+data.data[i].minimum_kilometers+"' vehicle_model_id='"+data.data[i].vehicle_model_id+"' vehicle_ac_type_id ='"+data.data[i].vehicle_ac_type_id+"' tariff_master_id='"+data.data[i].tariff_master_id+"' "+selected+"></option>").attr("value",data.data[i].id).text(data.data[i].title));

			
				
			}
			if(id=='#tarrif'){
				$('.display-me').css('display','none');
				if(tarif_id!=''){

				SetRoughEstimate();

				}
				}
			}else{
			 $(id+' option').remove();
			 $(id).append($("<option rate='-1' additional_kilometer_rate='-1' minimum_kilometers='-1'></option>").attr("value",'-1').text('--Select Tariff--'));
				$('.display-me').css('display','none');
			}
			
		  });
		

}


function generateTariffsWithVehicleId(vehicle_id,tarif_id='',id){
	var tarif_id=tarif_id;
	 $.post(base_url+"/tarrif/vehicleTariffSelecter",
		  {
			vehicle_id:vehicle_id
		  },function(data){
			if(data!='false'){
			data=jQuery.parseJSON(data);
			$(id+' option').remove();
			 $(id).append($("<option rate='-1' additional_kilometer_rate='-1' minimum_kilometers='-1'></option>").attr("value",'-1').text('--Select Tariff--'));
			i=0;//alert(data.data.length);
			for(var i=0;i<data.data.length;i++){
			if(tarif_id==data.data[i].id){
			var selected="selected=selected";
			}else{
			var selected="";
			}
			  $(id).append($("<option rate='"+data.data[i].rate+"' additional_kilometer_rate='"+data.data[i].additional_kilometer_rate+"' minimum_kilometers='"+data.data[i].minimum_kilometers+"' vehicle_model_id='"+data.data[i].vehicle_model_id+"' vehicle_ac_type_id ='"+data.data[i].vehicle_ac_type_id+"' tariff_master_id='"+data.data[i].tariff_master_id+"' "+selected+"></option>").attr("value",data.data[i].id).text(data.data[i].title));
				
			}
			if(id=='#tarrif'){
				$('.display-me').css('display','none');
				if(tarif_id!=''){

				SetRoughEstimate();

				}
				}
			}else{
			 $(id+' option').remove();
			 $(id).append($("<option rate='-1' additional_kilometer_rate='-1' minimum_kilometers='-1'></option>").attr("value",'-1').text('--Select Tariff--'));
				$('.display-me').css('display','none');
			}
			
		  });
		

}	




$('.format-date').blur(function(){

var date=$(this).val();
var date_array='';

 if(date.indexOf('-') > -1)
{	
	if((date.match(new RegExp("-", "g")) || []).length>1){
 		 var date_array=date.split('-');
	}

}
 if(date.indexOf('/') > -1)
{	
	if((date.match(new RegExp("/", "g")) || []).length>1){
 		 var date_array=date.split('/');
	}
}
 if(date.indexOf('.') > -1)
{	
	if((date.match(new RegExp(".", "g")) || []).length>1){
 		 var date_array=date.split('.');
	}
}

if(date_array.length>2 && date_array.length<4){//alert(date);
var formatted_date=date_array[2]+'-'+date_array[1]+'-'+date_array[0];
$(this).val(formatted_date);
}
});
$('.format-time').blur(function(){

var time=$(this).val();
var time_array='';

 if(time.indexOf('.') > -1)
{	
	if((time.match(new RegExp(".", "g")) || []).length>1){
 		 var time_array=time.split('.');
	}
}

if(time_array.length>1 && time_array.length<3){//alert(date);

var formatted_time=time_array[0]+':'+time_array[1];
$(this).val(formatted_time);
}
});


/*

$('.format-date').keyup(function(){

var date=$('.format-date').val();
var date_array='';
if((date.match(new RegExp("-", "g")) || []).length>1){
 		 var date_array=date.split('-');
	
}else if((date.match(new RegExp("/", "g")) || []).length>1){alert((date.match(new RegExp("/", "g")) || []).length);
 		 var date_array=date.split('/');
	
}else if((date.match(new RegExp(".", "g")) || []).length>1){
 		 var date_array=date.split('.');
	
}
//alert(date);
if(date_array.length>1 && date_array.length<3){alert(date);
var formatted_date=date_array[0]+'-'+date_array[1]+'-'+date_array[2];
$('.format-date').val(formatted_date);
}
});

*/
function diffDateTime(startDT, endDT){
 
  if(typeof startDT == 'string' && startDT.match(/^[0-9]{1,2}:[0-9]{1,2}:[0-9]{1,2}[amp ]{0,3}$/i)){
    startDT = startDT.match(/^[0-9]{1,2}:[0-9]{1,2}:[0-9]{1,2}/);
    startDT = startDT.toString().split(':');
    var obstartDT = new Date();
    obstartDT.setHours(startDT[0]);
    obstartDT.setMinutes(startDT[1]);
    obstartDT.setSeconds(startDT[2]);
  }
  else if(typeof startDT == 'string' && startDT.match(/^now$/i)) var obstartDT = new Date();
  else if(typeof startDT == 'string' && startDT.match(/^tomorrow$/i)){
    var obstartDT = new Date();
    obstartDT.setHours(24);
    obstartDT.setMinutes(0);
    obstartDT.setSeconds(1);
  }
  else var obstartDT = new Date(startDT);

  if(typeof endDT == 'string' && endDT.match(/^[0-9]{1,2}:[0-9]{1,2}:[0-9]{1,2}[amp ]{0,3}$/i)){
    endDT = endDT.match(/^[0-9]{1,2}:[0-9]{1,2}:[0-9]{1,2}/);
    endDT = endDT.toString().split(':');
    var obendDT = new Date();
    obendDT.setHours(endDT[0]);
    obendDT.setMinutes(endDT[1]);
    obendDT.setSeconds(endDT[2]);  
  }
  else if(typeof endDT == 'string' && endDT.match(/^now$/i)) var obendDT = new Date();
  else if(typeof endDT == 'string' && endDT.match(/^tomorrow$/i)){
    var obendDT = new Date();
    obendDT.setHours(24);
    obendDT.setMinutes(0);
    obendDT.setSeconds(1);
  }
  else var obendDT = new Date(endDT);

  // gets the difference in number of seconds
  // if the difference is negative, the hours are from different days, and adds 1 day (in sec.)
  var secondsDiff = (obendDT.getTime() - obstartDT.getTime()) > 0 ? (obendDT.getTime() - obstartDT.getTime()) / 1000 :  (86400000 + obendDT.getTime() - obstartDT.getTime()) / 1000;
  secondsDiff = Math.abs(Math.floor(secondsDiff));

  var oDiff = {};     // object that will store data returned by this function

  oDiff.days = Math.floor(secondsDiff/86400);
  oDiff.totalhours = Math.floor(secondsDiff/3600);      // total number of hours in difference
  oDiff.totalmin = Math.floor(secondsDiff/60);      // total number of minutes in difference
  oDiff.totalsec = secondsDiff;      // total number of seconds in difference

  secondsDiff -= oDiff.days*86400;
  oDiff.hours = Math.floor(secondsDiff/3600);     // number of hours after days

  secondsDiff -= oDiff.hours*3600;
  oDiff.minutes = Math.floor(secondsDiff/60);     // number of minutes after hours

  secondsDiff -= oDiff.minutes*60;
  oDiff.seconds = Math.floor(secondsDiff);     // number of seconds after minutes

  return oDiff;
}



$('#pickuptimepicker,#droptimepicker,#pickupdatepicker,#dropdatepicker').on('blur',function(){ 
var pickupdatepicker = $('#pickupdatepicker').val();
var dropdatepicker = $('#dropdatepicker').val();
var pickuptimepicker = $('#pickuptimepicker').val();
var droptimepicker =$('#droptimepicker').val();
if(pickupdatepicker!='' && dropdatepicker!='' && pickuptimepicker!='' && droptimepicker!=''){
pickupdatepicker=pickupdatepicker.split('-');
dropdatepicker=dropdatepicker.split('-');
var new_pickupdatetime = pickupdatepicker[1]+'/'+pickupdatepicker[0]+'/'+pickupdatepicker[2]+' '+pickuptimepicker+':00';
var new_dropdatetime = dropdatepicker[1]+'/'+dropdatepicker[0]+'/'+dropdatepicker[2]+' '+droptimepicker+':00';
// start time -end time check
var start_time=new Date(pickupdatepicker[2]+'/'+pickupdatepicker[1]+'/'+pickupdatepicker[0]+' '+pickuptimepicker+':00');
var end_time=new Date(dropdatepicker[2]+'/'+dropdatepicker[1]+'/'+dropdatepicker[0]+' '+droptimepicker+':00');
if( start_time < end_time){

}
else{
alert("Correct drop time");
}

var objDiff = diffDateTime(new_pickupdatetime, new_dropdatetime);
var dtdiff = objDiff.days+ ' days, '+ objDiff.hours+ ' hours, '+ objDiff.minutes+ ' minutes, '+ objDiff.seconds+ ' seconds';
var total_hours = 'Total Hours: '+ objDiff.totalhours;
var total_min = objDiff.totalmin;
if(total_min>60){
var h = Math.floor(total_min/60); //Get whole hours
    total_min -= h*60;
	}else{
	var h = 0;
	}
    var m = total_min; //Get remaining minutes
   
  var calculated_time=Number(h+"."+(m < 10 ? '0'+m : m));
  var estimated_time=$('.estimated-time-of-journey').html();
	estimated_time=estimated_time.replace(/\hours\b/g, '.');
	estimated_time=estimated_time.replace(/\mins\b/g, '');
	estimated_time=estimated_time.split(' ');
	estimated_time=estimated_time[0]+estimated_time[1]+estimated_time[2];
	if(Number(calculated_time) < Number(estimated_time)){
		alert('Correct drop time');
	}
}

});


window.setInterval(function(){
var current_loc=window.location.href;
current_loc=current_loc.split('/');
current_loc.length;
if(current_loc[current_loc.length-1]=='trip-booking' || current_loc[current_loc.length-2]=='trip-booking'){
notify();
}

}, 60000);


function notify(){
var notify='notify';
$.post(base_url+"/user/getNotifications",
		  {
			notify:notify
		  },function(data){//alert(data);
			data=jQuery.parseJSON(data);
			var notify_content='';
			for(var i=0;i<data['notifications'].length;i++){
			pickupdate=data["notifications"][i].pick_up_date.split('-');
			current_time=$.now();
			var start_actual_time  =  pickupdate[0]+'/'+pickupdate[1]+'/'+pickupdate[2]+' '+data["notifications"][i].pick_up_time;
			var end_actual_time    = new Date($.now());


			start_actual_time = new Date(start_actual_time);
			

			var diff =start_actual_time - end_actual_time;
 			var callout_class='';
			var diffSeconds = diff/1000;
			var HH = Math.floor(diffSeconds/3600);
			var MM = Math.floor(diffSeconds%3600)/60;
			var no_of_days=Math.floor(HH/24);
			if(i<2){
			if(HH<1 && MM <=59){
			 callout_class="callout-success";
			}else{
		 	 callout_class="callout-warning";
			}
			}else{

				callout_class="callout-warning";
			}
			notify_content=notify_content+'<a href="'+base_url+'/organization/front-desk/trip-booking/'+data["notifications"][i].id+'" class="notify-link"><div class="callout '+callout_class+' no-right-padding"><div class="notification'+i+'"><table style="width:100%;" class="font-size-12-px"><tr><td class="notification-trip-id">Trip ID :</td><td>'+data["notifications"][i].id+'</td></tr><tr><td class="notification-pickup-city">Cust :</td><td>'+data["customers"][data["notifications"][i].customer_id]+'</td></tr><tr><td class="notification-trip-id">Pick up :</td><td>'+data["notifications"][i].pick_up_city+'</td></tr><tr><td class="notification-pickup-city">Date :</td><td>'+data["notifications"][i].pick_up_date+' '+data["notifications"][i].pick_up_time+'</td></tr></table></div></div></a>';
			}
			$('.ajax-notifications').html(notify_content);
		 });

}

//trip_bookig page-js end

//trips paje js start


/*
$('.trip-voucher-save').on('click',function(){

var extrakmtravelled=0;
var tarrif_id=$('#tarrif').val();
var error=false;
var rate='';
var additional_kilometer_rate='';
var minimum_kilometers='';
if(tarrif_id==-1){
	error=true;
	$('.tariff-error').html('Tariff required');

}else{
$('.tariff-error').html('');
var rate=$('#tarrif option:selected').attr('rate');
var additional_kilometer_rate=$('#tarrif option:selected').attr('additional_kilometer_rate');
var minimum_kilometers=$('#tarrif option:selected').attr('minimum_kilometers');
}

var no_of_days=$('.trip-voucher-save').attr('no_of_days');

if(no_of_days==0){
no_of_days=1;
}
//var driver_bata=$('.trip-voucher-save').attr('driver_bata');

var startkm=$('.startkm').val();
var endkm=$('.endkm').val();

var totkmtravelled=Number(endkm)-Number(startkm);


//if(totkmtravelled>minimum_kilometers){
//extrakmtravelled=totkmtravelled-minimum_kilometers;
//expense=(Number(minimum_kilometers)*Number(rate))+(Number(extrakmtravelled)*Number(additional_kilometer_rate));
//}else{

//expense=Number(totkmtravelled)*Number(rate);

//}


if(no_of_days>1){

if(Number(totkmtravelled) > Number(minimum_kilometers)*Number(no_of_days)){
var extra_distance=Number(totkmtravelled)-(Number(minimum_kilometers)*Number(no_of_days));
charge=(Number(minimum_kilometers)*Number(no_of_days))*Number(rate);
extra_charge=Number(extra_distance)*Number(additional_kilometer_rate);
totexpense=Math.round(Number(charge)+Number(extra_charge)).toFixed(2);
}else{
totexpense=Math.round((Number(minimum_kilometers)*Number(no_of_days))*Number(rate)).toFixed(2);
}
}else{

if(Number(totkmtravelled) > minimum_kilometers){
var extra_distance=Number(totkmtravelled)-Number(minimum_kilometers);
charge=Number(minimum_kilometers)*Number(rate);
extra_charge=Number(extra_distance)*Number(additional_kilometer_rate);
totexpense=Math.round(Number(charge)+Number(extra_charge)).toFixed(2);
}else{
totexpense=Math.round(Number(minimum_kilometers)*Number(rate)).toFixed(2);
}
}

var garageclosingkm=$('.garageclosingkm').val();
//var garageclosingtime=$('.garageclosingtime').val();
//var releasingplace=$('.releasingplace').val();

var trip_starting_time=$('.tripstartingtime').val();
var trip_ending_time=$('.tripendingtime').val();
var parkingfee=$('.parkingfee').val();
var tollfee=$('.tollfee').val();
var statetax=$('.statetax').val();
var nighthalt=$('.nighthalt').val();
var extrafuel=$('.extrafuel').val();
var driverbata=$('.driverbata').val();

totexpense=Number(totexpense)+Number(tollfee)+Number(parkingfee)+Number(nighthalt);

var trip_id=$(this).attr('trip_id');
var driver_id=$(this).attr('driver_id');

if(startkm==''){
$('.start-km-error').html('Start km Field is required');
error=true;
}else{
$('.start-km-error').html('');
}
if(endkm==''){
$('.end-km-error').html('End km Field is required');
error=true;
}else{
$('.end-km-error').html('');
}


if(error==false){
	 $.post(base_url+"/trip-booking/tripVoucher",
		  {
			trip_id:trip_id,
			startkm:startkm,
			endkm:endkm,
			garageclosingkm:garageclosingkm,
			parkingfee:parkingfee,
			tollfee:tollfee,
			statetax:statetax,
			nighthalt:nighthalt,
			extrafuel:extrafuel,
			driver_id:driver_id,
			totexpense:totexpense,
			trip_starting_time:trip_starting_time,
			trip_ending_time:trip_ending_time,
			no_of_days:no_of_days,
			driverbata:driverbata,
			tarrif_id:tarrif_id
			
		},function(data){
		  if(data!='false'){
				window.location.replace(base_url+'/account/front_desk/NewDelivery/'+data);
			}
		});
}else{
	return false;
}
});
*/
//trips page js end


//device-page js start


$('.addDeviceico').click(function(){
$('#addDevice ').trigger('click');
});
$('.deviceUpdate').click(function(){

$(this).siblings().find(':submit').trigger('click');

});
$('.deviceDelete').click(function(){

$(this).siblings().find(':submit').trigger('click');

});


// device-page js end

	
	//add tarrif page js start
	//$('#fromdatepicker').datetimepicker({timepicker:false,format:'Y-m-d'});
	$('.fromdatepicker').each(function(){
	$(this).datetimepicker({timepicker:false,format:'Y-m-d'});
	});
	$('.fromyearpicker').each(function(){
	$(this).datetimepicker({timepicker:false,format:'Y'});
	});
	
	//trips page js start

	$('.initialize-date-picker').datetimepicker({timepicker:false,format:'Y-m-d',formatDate:'Y-m-d'});
	$('.initialize-time-picker').datetimepicker({datepicker:false,format:'H:i',step:5});

	$('.initialize-date-picker-d-m-Y').datetimepicker({timepicker:false,format:'d-m-Y',formatDate:'d-m-Y'});
	

//for next previous button

$('.prev1').click(function(){
$('#tab_1').trigger('click');
});

	// ----to display current date while selecting driver
$('#addDriver').on('change',function(){
var driver_id=$('#addDriver').val();
if(driver_id>0){
Date.prototype.yyyymmdd = function() {         
                                
        var yyyy = this.getFullYear().toString();                                    
        var mm = (this.getMonth()+1).toString(); // getMonth() is zero-based         
        var dd  = this.getDate().toString();             
                            
        return yyyy + '-' + (mm[1]?mm:"0"+mm[0]) + '-' + (dd[1]?dd:"0"+dd[0]);
   };  

d = new Date();
	$('#driverDate').val(d.yyyymmdd());
	$('#driverDate').attr('readonly', 'true');
	$('#hdriverDate').val(d.yyyymmdd());

}else{
	var date_driver='';
	$('#driverDate').val(date_driver);
}

});
			//----ends function
						
						// ----to display current date while selecting device
$('#addDevice').on('change',function(){
var device_id=$('#addDevice').val();
if(device_id>0){
Date.prototype.yyyymmdd = function() {         
                                
        var yyyy = this.getFullYear().toString();                                    
        var mm = (this.getMonth()+1).toString(); // getMonth() is zero-based         
        var dd  = this.getDate().toString();             
                            
        return yyyy + '-' + (mm[1]?mm:"0"+mm[0]) + '-' + (dd[1]?dd:"0"+dd[0]);
   };  

d = new Date();
$('#deviceDate').val(d.yyyymmdd());
$('#deviceDate').attr('readonly', 'true');


}
else{
	var date_device='';
	$('#deviceDate').val(date_device);
}


});

			//----ends function


//show customer details on customer group change
$('.company').on('change',function(){ 
	var c_group_val=$('.company').val(); 
	if(c_group_val!=-1){
	$('#customer').css('display','none');
	$.post(base_url+'/customers/CustomersById',{
	c_group_val:c_group_val 
	},function(data){
	
	if(data!='false'){ 
			data=jQuery.parseJSON(data);
			 $('#customer-list').html("<option value='-1'>Select Customers </option>");
			i=0;
			for(var i=0;i<data.length;i++){ 
			  $('#customer-list').append($("<option mobile="+data[i].mobile+"></option>").attr("value",data[i].id).text(data[i].name));
			  
			  $('#customer-list').css('display','block'); 
			  
			}
			
		}
		else{
		$('#customer-list').css('display','none');
		}
			
	});

}else{
$('#customer-list').css('display','none');	
}
			//$('#mobile').val('');
			//$('#email').val('');
			//$('#customer').val('');	
});

//remove -1 during  new vehicle and driver entry
$('.driver-list').on('keydown',function(){
	
	$(".driver-list option[text='']").attr('selected', true);
});
$('.vehicle-list').on('keydown',function(){
	
	$(".vehicle-list option[text='']").attr('selected', true);
});


	//-----------------------------------Tour events-----------------------------------------------

	//-----------------tour booking leave alert----------------------
	window.onbeforeunload = function(){
		var redirect=$('.book-tour-validate').attr('enable_redirect');
		var pathname = window.location.pathname.split("/");
		if(pathname[2]=="tour" && pathname[3]=="booking" && redirect!='true'){
	    		setTimeout(function(){
				test = 2;
	    		},500)
	    		return "If you leave this page, data may be lost.";
		}
	}

	$('.book-tour-validate').on('click',function(){
		$(this).attr('enable_redirect','true');
		$('.trip-save-update').trigger('click');

	});

	$('#package_id').on('blur',function(){
		var package_name = $(this).val();
		$('input[name="hid_package"]').val(package_name);
	});

	$('#package_id').on('change',function(){
		var package_id = $(this).val();
		if($.isNumeric(package_id) && package_id > 0){
			$.post(base_url+'/tour/createCartFromPackage',{package_id:package_id},function(data){
				if(data!=false){
					data=jQuery.parseJSON(data);
					set_trip_date();
					build_itinerary_table(data);
				
				}
			});
		}else{
			reset_itinerary_table();
		}
	});

	$("#tour-pickupdatepicker").change(function(){
	  	set_trip_date();
	
	});



	//save itinerary table on click itinerary save button
	$('.save-itry-btn').on('click',function(){
		var errFlag = 0;
		var errMsg = '';
		var pathname = window.location.pathname.split("/");
		if(pathname[4]){//tour module
			errFlag = 0;errMsg='';
		}else{//package module
			//get package
			var _package = $('input[name="hid_package"]').val();
			if(_package == ''){
				errFlag = 1;
				errMsg = "Package Name Required";
			}
		}

		if(errFlag == 0){
			$('.book-tour-validate').attr('enable_redirect','true');
			$('.save-itry').trigger('click');
		}else{
			alert(errMsg);return false;
		}
	
	
	});
	//--------------------------------------------------------

	//if edit tour get cart elements (tour booking form)
	var pathname = window.location.pathname.split("/");
	if(pathname[2]=="tour" && pathname[3]=="booking" && pathname[4] > 0){ 
		$.post(base_url+'/tour/getFromCart',{},function(data){
			if(data!=false){
				data=jQuery.parseJSON(data);
				build_itinerary_table(data);
			}
		});
	}



	// season-multiselect
	$('#seasons').click(function(){
		if ($("#seasons option[value!='']:selected").length > 0){
		    $("#seasons option[value='']").removeAttr("selected");  
		   
		}
	});

	//for checking customer in db
	$('#customer_contact').on('keyup click blur',function(){
		var mobile=$('#customer_contact').val();
		if(Trim(mobile)!=""){
			var regEx = /^(\+91|\+91|0)?\d{10}$/;
			if (!mobile.match(regEx)) {
				mobile='';
			}
		}
		set_customer_for_booking(mobile);
	});


	//for checking guest in db
	$('#guest_contact').on('keyup click',function(){
		var mobile=$('#guest_contact').val();
		if(Trim(mobile)!=""){
	   		var regEx = /^(\+91|\+91|0)?\d{10}$/;
			if (!mobile.match(regEx)) {
				mobile='';
	     		}
		}
		set_guest_for_booking(mobile);
	});
	
	// tour date pickers, time pickers,month pickers
	$('#tour-pickupdatepicker').datetimepicker({timepicker:false,format:'Y-m-d',formatDate:'Y-m-d'});
	$('#tour-dropdatepicker').datetimepicker({timepicker:false,format:'Y-m-d',formatDate:'Y-m-d'});
	$('#tour-pickuptimepicker').datetimepicker({datepicker:false,
		format:'H:i',
		step:30
	});

	$('#tour-droptimepicker').datetimepicker({datepicker:false,
		format:'H:i',
		step:30
	});

	$('.time-picker').datetimepicker({datepicker:false,
		format:'H:i',
		step:30
	});


	$('.fromday-monthpicker').each(function(){
		$(this).datetimepicker({timepicker:false,format:'d M'});
	});

	// vehicle advanced checkbox toggle(show or hide row)
	$('.tour-advanced-container > .icheckbox_minimal > .iCheck-helper').on('click',function(){
		if($('.advanced-check-box').attr('checked')=='checked'){
		$('.tbody-toggle').show();
		}else{
		$('.tbody-toggle').css('display','none');
		}
		//$('.tbody-toggle').toggle();
	});

	if($('.advanced-check-box').attr('checked')=='checked'){ //for edit and post values to advanced block
	$('.tbody-toggle').show();
	}

	//pickup auto complete
	 $('#pick_up').on('click',function(){
	 var options = {
		componentRestrictions: {country: "IN"} 
		};
	var autocompletepickup = new google.maps.places.Autocomplete($("#pick_up")[0], options);
	google.maps.event.addListener(autocompletepickup, 'place_changed', function() {
			var place = autocompletepickup.getPlace();
			var cityLat = place.geometry.location.lat();
			var cityLng = place.geometry.location.lng();
			$('#pick_up_lat').attr('value',cityLat);
			$('#pick_up_lng').attr('value',cityLng);
			$("#pick_up").attr('value',place.name);
		});
	 });
	 
	//drop auto complete
	 $('#drop').on('click',function(){
	 var options = {
		componentRestrictions: {country: "IN"} 
		};
	var autocompletedrop = new google.maps.places.Autocomplete($("#drop")[0], options);
	google.maps.event.addListener(autocompletedrop, 'place_changed', function() {
			var place = autocompletedrop.getPlace();
			var cityLat = place.geometry.location.lat();
			var cityLng = place.geometry.location.lng();
			$('#drop_lat').attr('value',cityLat);
			$('#drop_lng').attr('value',cityLng);
			$("#drop").attr('value',place.name);
		});
	 });

	// check whether drop time earlier than pickup time or not
	$('#tour-pickuptimepicker,#tour-droptimepicker,#tour-pickupdatepicker,#tour-dropdatepicker').on('blur',function(){ 
		var pickupdatepicker = $('#tour-pickupdatepicker').val();
		var dropdatepicker = $('#tour-dropdatepicker').val();
		var pickuptimepicker = $('#tour-pickuptimepicker').val();
		var droptimepicker =$('#tour-droptimepicker').val();
	if(pickupdatepicker!='' && dropdatepicker!='' && pickuptimepicker!='' && droptimepicker!=''){
		pickupdatepicker=pickupdatepicker.split('-');
		dropdatepicker=dropdatepicker.split('-');

		var start_time=new Date(pickupdatepicker[2]+'/'+pickupdatepicker[1]+'/'+pickupdatepicker[0]+' '+pickuptimepicker+':00');
		var end_time=new Date(dropdatepicker[2]+'/'+dropdatepicker[1]+'/'+dropdatepicker[0]+' '+droptimepicker+':00');
		if( start_time < end_time){
		}
		else{
			alert("Correct drop time");
		}
	}
	});

	// get driver assigned for a vehicle in the select box
	$('.tour-booking-tbl #vehicle_id').on('change',function(){
		var vehicle_id = $(this).val();
		get_vehicle_driver(vehicle_id);
	});


	//get hotels when change destination and category
	$('#hotel_destination_id').on('change',function(){
		var destination = $(this).val();
		var category = $('#hotel_category_id').val();
		getHotels(destination,category);
	});
	$('#hotel_category_id').on('change',function(){
		var destination = $('#hotel_destination_id').val();
		var category = $(this).val();
		getHotels(destination,category);
	});
	
	
	//---------------------------------------------------

	//get hotel rooms on change hotel
	$('#hotel_id').on('change',function(){
		var hotel_id = $(this).val();
		getHotelRooms(hotel_id);
	});
	//-------------------------------------------------

	//check room availability event
	$('#room_quantity').on('keyup',function(){
		var qty = $(this).val();
		checkRoomAvailability(qty);
	});

	//get tariff for tour booking vehicle tab
	$('.tour-vehicle-tab #vehicle_model_id,.tour-vehicle-tab #vehicle_ac_type_id').on('change',function(){
		var vehicle_ac_type_id = $('.tour-vehicle-tab #vehicle_ac_type_id').val();
		var vehicle_model_id = $('.tour-vehicle-tab #vehicle_model_id').val();

		if(vehicle_ac_type_id > 0 && vehicle_model_id > 0){
			generateTariffs(vehicle_model_id,vehicle_ac_type_id,'','.tour-vehicle-tab #vehicle_tariff_id');
		}

	});

	$('.tour-vehicle-tab #vehicle_id').on('change',function(){
		var vehicle_id = $(this).val();
		get_vehicle_driver(vehicle_id,'.tour-vehicle-tab #driver_id','.tour-vehicle-tab #vehicle_contact');
	});


	//ajax calls for add itinerary
	$('.itinerary #add-travel').click(function(){
		var trip_id = $('input[name="trip_id"]').val();
		var _date = $('#travel_date').val();
		var destination_id = $('#destination_id').val();
		var destination_priority = $('input[name="destination_priority"]').val();
		var description = $('#travel_description').val();
		var destination_section_id=$('#destination_section_id').val();
	

		if(trip_id == ''){
			var dataArr = {table:"trip_destinations", _date:_date, destination_id:destination_id, destination_priority:destination_priority, description:description, id:destination_section_id};
			add_itinerary_for_package(dataArr);

		
		}else{
			var dataArr = {table:"trip_destinations", trip_id:trip_id, _date:_date, destination_id:destination_id, destination_priority:destination_priority, description:description, id:destination_section_id};
			add_itinerary_for_tour(dataArr);
		}
		
		reset_destination_tab_values();
	
	});

	$('.itinerary #add-accommodation').click(function(){
		var trip_id = $('input[name="trip_id"]').val();
		var _date = $('#accommodation_date').val();
		var hotel_id = $('#hotel_id').val();
		var room_type_id = $('#room_type_id').val();
		var room_quantity = $('#room_quantity').val();
		var accommodation_section_id=$('#accommodation_section_id').val();
		var room_attributes = [];
		$('#room_attributes option:selected').each(function(){
			room_attributes.push($(this).val());
		});
	
		var meals_package = [];
		$('.meals_package').each(function(){
			if(this.checked)
				meals_package.push($(this).val());
		}); 
		var meals_quantity = $('#meals_quantity').val();

		if(trip_id == ''){
			var dataArr = {table:"trip_accommodation", _date:_date, hotel_id:hotel_id, room_type_id:room_type_id, room_quantity:room_quantity,room_attributes:room_attributes,meals_package:meals_package, meals_quantity:meals_quantity, id:accommodation_section_id};
			add_itinerary_for_package(dataArr);
		}else{
			var dataArr = {table:"trip_accommodation", trip_id:trip_id, _date:_date, hotel_id:hotel_id, room_type_id:room_type_id, room_quantity:room_quantity,room_attributes:room_attributes,meals_package:meals_package, meals_quantity:meals_quantity, id:accommodation_section_id};
		
			add_itinerary_for_tour(dataArr);
		}
	
		reset_accomodation_tab_values();
	});

	$('.itinerary #add-service').click(function(){
		var trip_id 	= $('input[name="trip_id"]').val();
		var _date 	= $('#service_date').val();
		var service_id 	= $('#service_id').val();
		var description = $('#service_description').val();
		var location 	= $('#service_location').val();
		var quantity 	= $('#service_quantity').val();
		var amount 	= $('#service_rate').val();
		var service_section_id=$('#service_section_id').val();
		if(trip_id == ''){
			var dataArr = {table:"trip_services", _date:_date, service_id:service_id, description:description, location:location,quantity:quantity,amount:amount,id:service_section_id};
			add_itinerary_for_package(dataArr);
		}else{
			var dataArr = {table:"trip_services", trip_id:trip_id, _date:_date, service_id:service_id, description:description, location:location,quantity:quantity,amount:amount,id:service_section_id};
		
			add_itinerary_for_tour(dataArr);
		}
		
		reset_service_tab_values();
	});

	$('.itinerary #add-vehicle').click(function(){

		var trip_id 		= $('input[name="trip_id"]').val();
		var _date 		= $('#vehicle_date').val();
		var vehicle_id 		= $('.tour-vehicle-tab #vehicle_id').val();
		var vehicle_type_id 	= $('.tour-vehicle-tab #vehicle_type_id').val();
		var vehicle_ac_type_id 	= $('.tour-vehicle-tab #vehicle_ac_type_id').val();
		var vehicle_model_id 	= $('.tour-vehicle-tab #vehicle_model_id').val();
		var tariff_id	 	= $('.tour-vehicle-tab #vehicle_tariff_id').val();
		var driver_id	 	= $('.tour-vehicle-tab #driver_id').val();
		var vehicle_section_id=$('#vehicle_section_id').val();
		if(trip_id == ''){
			var dataArr = {table:"trip_vehicles", _date:_date, vehicle_id:vehicle_id,  vehicle_type_id: vehicle_type_id, vehicle_ac_type_id:vehicle_ac_type_id,vehicle_model_id:vehicle_model_id,tariff_id:tariff_id,driver_id:driver_id,id:vehicle_section_id};
			add_itinerary_for_package(dataArr);
		}else{
			var dataArr = {table:"trip_vehicles", trip_id:trip_id, _date:_date, vehicle_id:vehicle_id,  vehicle_type_id: vehicle_type_id, vehicle_ac_type_id:vehicle_ac_type_id,vehicle_model_id:vehicle_model_id,tariff_id:tariff_id,driver_id:driver_id,id:vehicle_section_id};
		
			add_itinerary_for_tour(dataArr);
		}

		reset_vehicle_tab_values();
	});
	
  //ajax calls for delete itinerary
	$('.itinerary #delete-travel').click(function(){
	var trip_id = $('input[name="trip_id"]').val();
	var _date = $('#travel_date').val();
	var destination_section_id=$('#destination_section_id').val();
	if(trip_id == ''){
		var dataArr = {table:"trip_vehicles", _date:_date, id:destination_section_id};
		delete_itinerary_for_package(dataArr);
	}else{
		var dataArr = {table:"trip_vehicles",  trip_id:trip_id, _date:_date, id:destination_section_id};
		delete_itinerary_for_tour(dataArr);
	}
	
	});
	
	
  //edit package for each value
	$(document.body).on('click', '.edit_data' ,function(){
		var row_id=$(this).attr('row_id');
		var tab=$(this).attr('tab');
		var itinerary=$(this).attr('itinerary');
		var table=getCartTableWithTab(tab); 
	$.post(base_url+"/tour/getEditableTabValues",
		 { row_id:row_id,
		   tab:tab,
		   itinerary:itinerary,
		   table:table
		 },function(data){ 
			if(data!=false){
				data=jQuery.parseJSON(data);
				//travel-tab values
				if(tab=="t_tab"){
					var href = $('a[href="#tab_1"]');
					$(href).trigger('click');
					$(".tour-travel-tab #destination_section_id").val(data.id);
					$(".tour-travel-tab #travel_date option[value='"+itinerary+"']").attr('selected', true);
					$(".tour-travel-tab #destination_id option[value='"+data.destination_id+"']").attr('selected', true);
					$(".tour-travel-tab #destination_priority").val(data.destination_priority);
					$(".tour-travel-tab #travel_description").val(data.description);
					$(".tour-travel-tab #add-travel").val('Update');
					$(".tour-travel-tab #delete-travel").css('display','inline');
				
				}else if(tab=="a_tab"){
				//accomodation-tab values
					var href = $('a[href="#tab_2"]');
					$(href).trigger('click');
					$(".tour-accomodation-tab #accommodation_section_id").val(data.id);
					$(".tour-accomodation-tab #accommodation_date option[value='"+itinerary+"']").attr('selected', true);
					$(".tour-accomodation-tab #hotel_destination_id option[value='"+data.destination_id+"']").attr('selected', true);
					$(".tour-accomodation-tab #hotel_category_id option[value='"+data.hotel_category_id+"']").attr('selected', true);
					
					$(".tour-accomodation-tab #room_type_id option[value='"+data.room_type_id+"']").attr('selected', true);
					var room_attributes=data.room_attributes;
					$.each(room_attributes, function(i,e){
					    $(".tour-accomodation-tab #room_attributes option[value='']").removeAttr("selected");
					    $(".tour-accomodation-tab #room_attributes option[value='" + e + "']").attr("selected", true);
					});
					var meals_package=data.meals_package;
					$.each(meals_package, function(i,val){
					$("#meals_package"+val).closest( "div" ).addClass( "checked" );
					$("#meals_package"+val).attr('checked',true);
					//$("#meals_package"+val+" < .icheckbox_minimal").addClass( "checked" );
					});
					if(($('#hotel_destination_id').val()!=-1)&&($('#hotel_category_id').val()!=-1)){
						var destination_id=$('#hotel_destination_id').val();
						var hotel_category_id=$('#hotel_category_id').val();
						var hotel_id=data.hotel_id;
						getHotels(destination_id,hotel_category_id,hotel_id);
					}
					if(hotel_id>0)
						getHotelRooms(hotel_id,data.room_type_id);
					$(".tour-accomodation-tab #room_quantity").val(data.room_quantity);
					$(".tour-accomodation-tab #meals_quantity").val(data.meals_quantity);
					$(".tour-accomodation-tab #add-accommodation").val('Update');
					$(".tour-accomodation-tab #delete-accommodation").css('display','inline');
					
				}else if(tab=="s_tab"){
				//service-tab values
					var href = $('a[href="#tab_3"]');
					$(href).trigger('click');
					$(".tour-service-tab #service_section_id").val(data.id);
					$(".tour-service-tab #service_date option[value='"+itinerary+"']").attr('selected', true);
					$(".tour-service-tab #service_id option[value='"+data.service_id+"']").attr('selected', true);
					$(".tour-service-tab #service_rate").val(data.amount);
					$(".tour-service-tab #service_location").val(data.location);
					$(".tour-service-tab #service_quantity").val(data.quantity);
					$(".tour-service-tab #service_description").val(data.description);
					$(".tour-service-tab #add-service").val('Update');
					$(".tour-service-tab #delete-service").css('display','inline');
				}else if(tab=="v_tab"){
				//vehicle-tab values
					var href = $('a[href="#tab_4"]');
					$(href).trigger('click');
					$(".tour-vehicle-tab #vehicle_section_id").val(data.id);
					$(".tour-vehicle-tab #vehicle_date option[value='"+itinerary+"']").attr('selected', true);
					$(".tour-vehicle-tab #vehicle_type_id option[value='"+data.vehicle_type_id+"']").attr('selected', true);
					$(".tour-vehicle-tab #vehicle_ac_type_id option[value='"+data.vehicle_ac_type_id+"']").attr('selected', true);
					$(".tour-vehicle-tab #vehicle_model_id option[value='"+data.vehicle_model_id+"']").attr('selected', true);
					$(".tour-vehicle-tab #vehicle_id option[value='"+data.vehicle_id+"']").attr('selected', true);
					$(".tour-vehicle-tab #driver_id option[value='"+data.driver_id+"']").attr('selected', true);
					if(data.vehicle_model_id>0 && data.vehicle_ac_type_id>0)
						generateTariffs(data.vehicle_model_id,data.vehicle_ac_type_id,data.tariff_id,'.tour-vehicle-tab #vehicle_tariff_id');
					$('.tour-vehicle-tab #vehicle_id').trigger('change');
					$(".tour-vehicle-tab #add-vehicle").val('Update');
					$(".tour-vehicle-tab #delete-vehicle").css('display','inline');
				}
			}
		 });
	});
	//-----------------------------------Tour events ends-----------------------------------------------


	


	//------------------------functions----------------------------

	//reset tab values functions
	function reset_destination_tab_values(){
		$(".tour-travel-tab #destination_section_id").val(-1);
		$(".tour-travel-tab #travel_date option[value=-1]").attr('selected', true);
		$(".tour-travel-tab #destination_id option[value=-1]").attr('selected', true);
		$(".tour-travel-tab #destination_priority").val('');
		$(".tour-travel-tab #travel_description").val('');
		$(".tour-travel-tab #add-travel").val('Add');
		$(".tour-travel-tab #delete-travel").css('display','none');
	}
	function reset_accomodation_tab_values(){
		$(".tour-accomodation-tab #accommodation_section_id").val(-1);
		$(".tour-accomodation-tab #accommodation_date option[value=-1]").attr('selected', true);
		$(".tour-accomodation-tab #hotel_destination_id option[value=-1]").attr('selected', true);
		$(".tour-accomodation-tab #hotel_category_id option[value=-1]").attr('selected', true);
		$(".tour-accomodation-tab #room_type_id option[value=-1]").attr('selected', true);
		$(".tour-accomodation-tab #room_attributes option[value='']").attr("selected", true);
		$(".tour-accomodation-tab #room_attributes option[value!='']").attr("selected", false);
		$(".meals_package").closest( "div" ).removeClass( "checked" );
		$(".meals_package").attr('checked',false);
		$(".tour-accomodation-tab #room_type_id option[value=-1]").attr('selected', true);
		$(".tour-accomodation-tab #hotel_id option[value=-1]").attr('selected', true);
		$(".tour-accomodation-tab #room_quantity").val('');
		$(".tour-accomodation-tab #meals_quantity").val('');
		$(".tour-accomodation-tab #add-accommodation").val('Add');
		$(".tour-accomodation-tab #delete-accommodation").css('display','none');
	}
	function reset_service_tab_values(){
		$(".tour-service-tab #service_section_id").val(-1);
		$(".tour-service-tab #service_date option[value='-1']").attr('selected', true);
		$(".tour-service-tab #service_id option[value='-1']").attr('selected', true);
		$(".tour-service-tab #service_rate").val('');
		$(".tour-service-tab #service_location").val('');
		$(".tour-service-tab #service_quantity").val('');
		$(".tour-service-tab #service_description").val('');
		$(".tour-service-tab #add-service").val('Add');
		$(".tour-service-tab #delete-service").css('display','none');		
	}
	function reset_vehicle_tab_values(){
		$(".tour-vehicle-tab #vehicle_section_id").val(-1);
		$(".tour-vehicle-tab #vehicle_date option[value='-1']").attr('selected', true);
		$(".tour-vehicle-tab #vehicle_type_id option[value='-1']").attr('selected', true);
		$(".tour-vehicle-tab #vehicle_ac_type_id option[value='-1']").attr('selected', true);
		$(".tour-vehicle-tab #vehicle_model_id option[value='-1']").attr('selected', true);
		$(".tour-vehicle-tab #vehicle_id option[value='-1']").attr('selected', true);
		$(".tour-vehicle-tab #driver_id option[value='-1']").attr('selected', true);
		$(".tour-vehicle-tab #vehicle_tariff_id option[value='-1']").attr('selected', true);
		$(".tour-vehicle-tab #vehicle_contact").val('');	
		$(".tour-vehicle-tab #add-vehicle").val('Add');
		$(".tour-vehicle-tab #delete-vehicle").css('display','none');
		
	}
	
	//get cart table index with  tab
	function getCartTableWithTab(tab){
		switch(tab){
			case 't_tab': var cartTableIndex='trip_destinations';break;
			case 'a_tab': var cartTableIndex='trip_accommodation';break;
			case 's_tab': var cartTableIndex='trip_services';break;
			case 'v_tab': var cartTableIndex='trip_vehicles';break;
		
		}
		return cartTableIndex;
	}
	
	//post itinerary to tour cart
	function add_itinerary_for_tour(dataArr){
		$.post(base_url+'/tour/addToCart',dataArr,function(data){
			if(data!=false){

				data=jQuery.parseJSON(data);
				build_itinerary_table(data);
			}
		});
	}
	function add_itinerary_for_package(dataArr){
		$.post(base_url+'/tour/addToCartPackage',dataArr,function(data){
			if(data!=false){
				data=jQuery.parseJSON(data);
				build_itinerary_table(data);
			}
		});
	}
	function delete_itinerary_for_package(dataArr){
		$.post(base_url+'/tour/deleteFromCartPackage',dataArr,function(data){
			if(data!=false){
				data=jQuery.parseJSON(data);
				build_itinerary_table(data);
			}
		});
	}


	//build itineray table with dat
	function build_itinerary_table(data){
		reset_itinerary_table();
	
		if(data != 'false' && data!=''){
			var table = '<tr>';
			for(i=0;i < data.th.length;i++){
				table += '<th '+data.th[i].attr+'>'+data.th[i].label+'</th>';
			}
			table += '</tr>';
	
			$.each( data.tr, function( key, tr ) {
				table += '<tr>';
				$.each(tr, function( key, td ) {
					table += '<td>'+td+'</td>';
				});
				table += '</tr>';
			});
			
		
	
		}
		if(table != ''){
			$("#itinerary-div").removeClass("hide-me");
		}else{
			$( "#itinerary-div" ).addClass("hide-me");
		}

		$("#itinerary-tbl").append(table);
	}


	function reset_itinerary_table()
	{
		$("#itinerary-tbl").find("tr").remove();
		$( "#itinerary-div" ).addClass("hide-me");
	}


	//checking date lies between pickup and drop date, all dates must be in mysql format'yyyy-mm-dd'
	function check_itinerary_date(dateCheck){
		var pick_up_date	= $('input[name="pick_up_date"]').val();
		var drop_date 		= $('input[name="drop_date"]').val();

		var d1 = pick_up_date.split("-");
		var d2 = drop_date.split("-");
		var c = dateCheck.split("-");

		var from = new Date(d1[0], d1[1]-1, d1[2]);  // -1 because months are from 0 to 11
		var to   = new Date(d2[0], d2[1]-1, d2[2]);
		var check = new Date(c[0], c[1]-1, c[2]);

		//console.log(check > from && check < to);
		if((check > from && check < to)){
			return true;
		}else{
			return false;
		}
	}
	//---------------------------------------------------------------------------------

	//set customer for tour booking 
	function set_customer_for_booking(mobile){
		if(Trim(mobile)!=""){
			$.post(base_url+'/customers/customer-check',{
			mobile:mobile,
			customer:'yes'
			},function(data){
			if(data!=false){
				data=jQuery.parseJSON(data);
				$('#customer').val(data[0].name);
				$('#customer_contact').val(data[0].mobile);
				$('#customer_id').val(data[0].id);
				$('.newcustomer').attr('value',false);
				}
			});
		}
	}

	//set guest for tour booking 
	function set_guest_for_booking(mobile){
		if(Trim(mobile)!=""){
			$.post(base_url+'/customers/customer-check',{
			mobile:mobile,
			customer:'yes'
			},function(data){ 
			if(data!=false){
				data=jQuery.parseJSON(data);
				$('#guest_name').val(data[0].name);
				$('#guest_contact').val(data[0].mobile);
				$('#guest_id').val(data[0].id);
				$('.newguest').attr('value',false);
				}
			});
		}
	}

	//set driver with vehicle id for tour booking
	function get_vehicle_driver(vehicle_id=0,obj_driver='select[name="driver_id"]',obj_contact=''){
	
		$.post(base_url+'/vehicle/get-vehicle-driver',{vehicle_id:vehicle_id},
		function(data){
			if(data!=false){
				data=jQuery.parseJSON(data);
			
				$(obj_driver).val(data.driver_id);
				if(obj_contact !=''){
					$(obj_contact).val(data.mobile);
				}
			}else{
				$(obj_driver).val('');
				if(obj_contact !=''){
					$(obj_contact).val('');
				}
			}
		});
	
	}

	//get hotels with destination and category
	function getHotels(destination,category,hotel_id=''){
		var id ='#hotel_id';
		$(id+' option').remove();
		$(id).append($("<option ></option>").attr("value",'-1').text('--Select Hotel--'));
		if(destination > 0 && category > 0){
			 
			var _date = $('#accommodation_date').val();
			 $.post(base_url+"/hotel/getAvailableHotels",
			  {
				destination_id:destination,
				category_id:category,
				_date:_date
			  },function(data){
				if(data!='false'){
					data=jQuery.parseJSON(data);
					
					
					for(var i=0;i< data.length;i++){
						if(hotel_id==data[i].id){
						var selected="selected=selected";
						}else{
						var selected="";
						}
						$(id).append($("<option "+selected+"></option>").attr("value",data[i].id).text(data[i].name));
					}
			
				}
			});
		
		}
		return true;
	}


	//get hotel rooms for selected hotel and set room types list
	function getHotelRooms(hotel_id,room_type_id=''){
		var id ='#room_type_id';
		$(id+' option').remove();
		$(id).append($("<option ></option>").attr("value",'-1').text('--Select Room Type--'));
		$.post(base_url+"/hotel/getHotelRooms",{hotel_id:hotel_id},
		function(data){
			if(data!='false'){
				data=jQuery.parseJSON(data);
				
				for(var i=0;i< data.length;i++){
					if(room_type_id==data[i].room_type_id){
						var selected="selected=selected";
					}else{
						var selected="";
					}
					$(id).append($("<option "+selected+"></option>").attr("value",data[i].room_type_id).text(data[i].room_type_name));
				}
		
			}
		});
		
	}

	//check room availability with quantity and rooms in trip accommodation table
	function checkRoomAvailability(reqQTY)
	{
		var hotel_id = $('#hotel_id').val();
		var room_type_id = $('#room_type_id').val();
		var _date = $('#accommodation_date').val();
		$.post(base_url+"/hotel/getRoomAvailability",{
			hotel_id:hotel_id, room_type_id:room_type_id, booking_date:_date, qty:reqQTY},
		function(avlQTY){
			$("#room_quantity").val('');
			if(reqQTY > avlQTY){
				var cnRoom = confirm("Insufficient rooms.Do you want to continue with Availabe Rooms?");
				if(cnRoom){
					$("#room_quantity").val(avlQTY);
				}
			}else{
				$("#room_quantity").val(reqQTY);
			}	
			
		});
	}


	function set_trip_date(){
	
		var date = $("#tour-pickupdatepicker").val();
		if(date != ''){
			$.post(base_url+"/tour/getItineraryCount",{},function(data){
				days = Number(data)-1;
				if(days > 0){
					str = date.split("-");
					y = str[0];m = str[1];d = str[2];
					d = new Date(date);
					d.setDate(d.getDate() + days);
					var day2 = d.getDate();
					if(day2 < 10)day2 ="0"+day2;
					var month2 = d.getMonth() + 1;if(month2 < 10)month2 ="0"+month2;
					var year2 = d.getFullYear();
					date2 = year2+"-"+month2+"-"+day2;
			  
					$("#tour-dropdatepicker").val(date2);
				}
			});
		}	
	}





	function DateFromString(str,days){ 

		str = new Date(str[2],str[0]-1,(parseInt(str[1])+days));
		return MMDDYYYY(str);
		}
		function MMDDYYYY(str) {
		var ndateArr = str.toString().split(' ');
		var Months = 'Jan Feb Mar Apr May Jun Jul Aug Sep Oct Nov Dec';
		return (parseInt(Months.indexOf(ndateArr[1])/4)+1)+'/'+ndateArr[2]+'/'+ndateArr[3];
		}

		function AddDays(days) {
		var date = $('input[name="pick_up_date"]').val();
		var ndate = DateFromString(date,days);
		return ndate;
	}

	




	

   
//-------------------------------------------------------------------------
	
 });


