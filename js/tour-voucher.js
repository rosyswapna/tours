$(document).ready(function(){
	var base_url=window.location.origin;

	//---------------------------tour voucher events starts -----------------------------

	//if edit tour voucher elements (tour voucher form)
	var pathname = window.location.pathname.split("/");
	if(pathname[2]=="tour" && pathname[3]=="voucher" && pathname[4] > 0){ 
	
		build_voucher_table(data='');
	
	}

		
	//get tariff for tour voucher vehicle tab
	$('.voucher-tabs #vehicle_model_id,.voucher-tabs #vehicle_ac_type_id').on('change',function(){
		var vehicle_ac_type_id = $('.voucher-tabs #vehicle_ac_type_id').val();
		var vehicle_model_id = $('.voucher-tabs #vehicle_model_id').val();

		if(vehicle_ac_type_id > 0 && vehicle_model_id > 0){
			generateTariffs(vehicle_model_id,vehicle_ac_type_id,'','.voucher-tabs #vehicle_tariff_id');
		}

	});

	//get tariff values for voucher
	$('.voucher-tabs #vehicle_tariff_id').change(function(){
		setTariffAttributes();
	});

	//calculate total km readming
	$('.voucher-tabs #start_km,.voucher-tabs #end_km').on('click keyup blur',function(e) {
		setTotalKM();
		$(".voucher-tabs #total_km").trigger( "blur" );
	
	});

	$('.voucher-tabs #vehicle_start_time,.voucher-tabs #vehicle_end_time,.voucher-tabs #vehicle_from_date,.voucher-tabs #vehicle_to_date').on('click blur',function(e) {
		setTotalHR();
		$(".voucher-tabs #total_hr").trigger( "blur" );
		
	});


	$('.voucher-tabs #total_km').on('blur',function(){
		setKM_tariff();
	});

	$('.voucher-tabs #total_hr').on('blur',function(){
		setHR_tariff();
	});


	//calculating tax amount on changing tax group from voucher vehicle tab
	$(".voucher-tabs #vehicle_tax_group_id").change(function(){
		var amount = $('.voucher-tabs #vehicle_unit_amount').val();
		$id = $(this).val();
	
		$.post(base_url+"/account/getTotalTax",{id:$id, amt:amount},
		function(data){

			$('.voucher-tabs #vehicle_tax_amount').val(data);
		});
	});

	
	//add vehicle itinerary for voucher======================================
	$('#add-voucher-vehicle').on('click',function(){
		
		var from_date 		= $('.voucher-tabs #vehicle_from_date').val();
		var to_date 		= $('.voucher-tabs #vehicle_to_date').val();
		var start_time 		= $('.voucher-tabs #vehicle_start_time').val();
		var end_time 		= $('.voucher-tabs #vehicle_end_time').val();
		var vehicle_id 			= $('.voucher-tabs #vehicle_id').val();
		var driver_id 			= $('.voucher-tabs #driver_id').val();
		var vehicle_tariff_id		= $('.voucher-tabs #vehicle_tariff_id').val();
	
		var start_km 			= $('.voucher-tabs #start_km').val();
		var end_km 			= $('.voucher-tabs #end_km').val();

		var base_km 			= $('.voucher-tabs #base_km').val();
		var base_km_amount 		= $('.voucher-tabs #base_km_amount').val();
		var adt_km 			= $('.voucher-tabs #adt_km').val();
		var adt_km_amount 		= $('.voucher-tabs #adt_km_amount').val();

		var base_hr 			= $('.voucher-tabs #base_hr').val();
		var base_hr_amount 		= $('.voucher-tabs #base_hr_amount').val();
		var adt_hr 			= $('.voucher-tabs #adt_hr').val();
		var adt_hr_amount 		= $('.voucher-tabs #adt_hr_amount').val();

		var unit_amount 		= $('.voucher-tabs #vehicle_unit_amount').val();
		var advance_amount	 	= $('.voucher-tabs #vehicle_advance_amount').val();
		var tax_group_id	 	= $('.voucher-tabs #vehicle_tax_group_id').val();
		var tax_amount 			= $('.voucher-tabs #vehicle_tax_amount').val();
		var total_amount	 	= $('.voucher-tabs #vehicle_total_amount').val();

		//trip expense
		var expense = {};
		$(".voucher-tabs .trip-expense-input").each(function(){
			expense_amount 	= $(this).val();
			expense_code 	= $(this).attr("id");
			expense[expense_code] = expense_amount;
			expense_name = $("label[for='"+$(this).attr('id')+"']").text();
		});

		var dataArr = {table:"trip_voucher_vehicles",
				vehicle_id:vehicle_id, driver_id:driver_id, tariff_id:vehicle_tariff_id,
				from_date:from_date, to_date:to_date, start_time:start_time, end_time:end_time,
				start_km:start_km, end_km:end_km, km_hr:km_hr, base_km:base_km,
				base_km_amount:base_km_amount, adt_km:adt_km, adt_km_amount:adt_km_amount,
				base_hr:base_hr, base_hr_amount:base_hr_amount, adt_hr:adt_hr,
				adt_hr_amount:adt_hr_amount, driver_bata:driver_bata,
				night_halt_charge:night_halt_charge,trip_expense:expense,unit_amount:unit_amount,
				advance_amount:advance_amount, tax_group_id:tax_group_id,tax_amount:tax_amount};
		add_voucher_itinerary(dataArr);
		
	});
	//============================================================================


	
	//----------------------------------------tour vocuher ends---------------------------




	//----voucher functions=====================


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

				$(id).append($("<option  rate='"+data.data[i].rate+"' additional_kilometer_rate='"+data.data[i].additional_kilometer_rate+"' minimum_kilometers='"+data.data[i].minimum_kilometers+"' driver_bata='"+data.data[i].driver_bata+"' night_halt='"+data.data[i].night_halt+"' additional_hour_rate='"+data.data[i].additional_hour_rate+"' minimum_hours='"+data.data[i].minimum_hours+"' vehicle_model_id='"+data.data[i].vehicle_model_id+"' vehicle_ac_type_id ='"+data.data[i].vehicle_ac_type_id+"' tariff_master_id='"+data.data[i].tariff_master_id+"' "+selected+"></option>").attr("value",data.data[i].id).text(data.data[i].title));
				
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

	function setTariffAttributes(){
		var obj = '.voucher-tabs #vehicle_tariff_id option:selected';
		var base_km = $(obj).attr('minimum_kilometers');
		var rate = $(obj).attr('rate');
		var adt_km_rate = $(obj).attr('additional_kilometer_rate');
		var base_hr = $(obj).attr('minimum_hours');
		var base_hr_amount = $(obj).attr('rate');
		var adt_hr_rate = $(obj).attr('additional_hour_rate');
		var driver_bata = $(obj).attr('driver_bata');
		var night_halt = $(obj).attr('night_halt');

		$('.voucher-tabs #base_km').val(base_km);
		$('.voucher-tabs #base_km_amount').val(rate);
		$('.voucher-tabs #adt_km_rate').val(adt_km_rate);

		$('.voucher-tabs #base_hr').val(base_hr);
		$('.voucher-tabs #base_hr_amount').val(rate);
		$('.voucher-tabs #adt_hr_rate').val(adt_hr_rate);

		$('.voucher-tabs #driver_bata').val(driver_bata);
		$('.voucher-tabs #night_halt_charge').val(night_halt);
		
	}


	function setTotalKM()
	{
		var start_km = $('.voucher-tabs #start_km').val();
		var end_km = $('.voucher-tabs #end_km').val();

		total_km = Number(end_km)-Number(start_km);
		$('.voucher-tabs #total_km').val(total_km);
	}
	
	function setTotalHR(){
		var start = $('.voucher-tabs #vehicle_start_time').val();
		var end = $('.voucher-tabs #vehicle_end_time').val();
		var fromdate=$('.voucher-tabs #vehicle_from_date').val(); 
		var todate=$('.voucher-tabs #vehicle_to_date').val();
		if(fromdate!='' && todate!='' && end!='' && start!=''){
			var total = timeDifference(fromdate,start,todate,end);
			total=total.split('-');
			$('.voucher-tabs #total_hr').val(total[1]+':'+total[2]);
			no_of_days = total[0];
		}
	}

	function setKM_tariff()
	{
		var base_km = $('.voucher-tabs #base_km').val();
		var base_km_amount = $('.voucher-tabs #base_km_amount').val();
		var total_km = $('.voucher-tabs #total_km').val();
		var adt_km_rate = $('.voucher-tabs #adt_km_rate').val();
		var adt_km = Number(total_km)-Number(base_km);
		if(Number(total_km) <= Number(base_km)){
			$('.voucher-tabs #adt_km').val('');
			$('.voucher-tabs #adt_km_amount').val('');
			$('.voucher-tabs #total_km_amount').val(base_km_amount);
		}else{
			var adt_km=Number(total_km)-Number(base_km); //time difference
			adt_km_amount = Number(adt_km)*Number(adt_km_rate);
			total_km_amount = Number(base_km_amount)+Number(adt_km_amount);
			$('.voucher-tabs #adt_km').val(adt_km);
			$('.voucher-tabs #adt_km_amount').val(adt_km_amount);
			$('.voucher-tabs #total_km_amount').val(total_km_amount);
		}
		compareTotalAmounts();
	}

	function setHR_tariff()
	{
		var base_hr = $('.voucher-tabs #base_hr').val();
		var base_hr_amount = $('.voucher-tabs #base_hr_amount').val();
		var totaltime = $('.voucher-tabs #total_hr').val();
		var adt_hr_rate = $('.voucher-tabs #adt_hr_rate').val();

		if(base_hr.indexOf(':')>-1 ){
			base_hr = base_hr.split(':');
			base_hr = base_hr[0]+'.'+base_hr[1];
		}

		totaltime  = totaltime .split(':');
		total_hr=totaltime[0]+'.'+totaltime[1];
		if(Number(total_hr) <= Number(base_hr)){
			$('.voucher-tabs #adt_hr').val('');
			$('.voucher-tabs #adt_hr_amount').val('');
			$('.voucher-tabs #total_hr_amount').val(base_hr_amount);
		}else{
			
			var adthrs=Number(total_hr)-Number(base_hr); //time difference
			
			adthrs=adthrs.toFixed(2);
			adthrsnew=adthrs.replace(/\./g, ':');
			adt_hr_amount = calculateHrsAmount(adthrsnew,adt_hr_rate);
			total_hr_amount = Number(base_hr_amount)+Number(adt_hr_amount);
				
			$('.voucher-tabs #adt_hr').val(adthrsnew);
			$('.voucher-tabs #adt_hr_amount').val(adt_hr_amount);
			$('.voucher-tabs #total_hr_amount').val(total_hr_amount);
		}
		compareTotalAmounts();
	}

	function calculateTariffValues(){
		setKM_tariff();
		setHR_tariff()	
	}

	function compareTotalAmounts(){//not working
		var total_km_amount=$('.voucher-tabs #total_km_amount').val();
		var total_hr_amount=$('.voucher-tabs #total_hr_amount').val();

		if(total_km_amount != '' && total_hr_amount != ''){
			if(Number(total_km_amount) > Number(total_hr_amount)){
				$('.voucher-tabs #km_radio').trigger('click');
			}else{
				$('.voucher-tabs #hr_radio').trigger('click');
			}
		}
	}


	function add_voucher_itinerary(dataArr){
		$.post(base_url+'/voucher/addToVoucher',dataArr,function(data){
			if(data!=false){

				data=jQuery.parseJSON(data);
				build_voucher_table(data);
			}
		});
	}

	//voucher itinerary table
	function build_voucher_table(data){
	
		var table = '';

		if(table != ''){
			$("#voucher-itinerary-div").removeClass("hide-me");
		}else{
			$( "#voucher-itinerary-div" ).addClass("hide-me");
		}

		$("#voucher-itinerary-tbl").append(table);
	}

	function reset_voucher_table()
	{
		$("#voucher-itinerary-tbl").find("tr").remove();
		$( "#voucher-itinerary-tbl" ).addClass("hide-me");
	}

	function timeDifference(fromdate,fromtime,todate,totime){ 

		var fromdate=fromdate.split('-');
		var todate=todate.split('-');
		var start_actual_time  =  fromdate[0]+'/'+fromdate[1]+'/'+fromdate[2]+' '+fromtime;
		var end_actual_time    =  todate[0]+'/'+todate[1]+'/'+todate[2]+' '+totime;

		start_actual_time = new Date(start_actual_time);
		end_actual_time = new Date(end_actual_time);

		var diff = end_actual_time - start_actual_time;

		var diffSeconds = diff/1000;
		var HH = Math.floor(diffSeconds/3600);
		var MM = Math.floor(diffSeconds%3600)/60;
		var result='';
		var no_of_days=Math.floor(HH/24);  
		if(HH%24==0 && MM==0){
			result+=no_of_days+'-'+HH+'-'+MM;	
		}
		else if((HH>=24 && MM>=1) || HH>24){
			no_of_days=no_of_days+1; 
			result+=no_of_days+'-'+HH+'-'+MM;	
		}else{
			result+='1'+'-'+HH+'-'+MM;

		}

		return result;
	}

	function formatDate_Y_m_d(date) {
		var d = date.split('-');
		var day = d[0];
		var month = d[1];
		var year = d[2];
		
		var date = year + "-" + month + "-" + day;

		return date;
	}

	function calculateHrsAmount(time_str,hrsrate){
	
		var parts = time_str.split(':');
		var hr_amount = 0;
		var min_amount = 0;
		if(hrsrate != ''){
			if(parts[0]){
				var hrs = Number(parts[0]);
				hr_amount = hrs*hrsrate;
			}
			if(parts[1]){
				var mns = Number(parts[1]);
				if(mns >0 && mns < 16){
					min_amount = hrsrate*0.25;
				}else if(mns > 15 && mns < 31){
					min_amount = hrsrate*0.5;
				}else  if(mns > 30 && mns < 46){
					min_amount = hrsrate*0.75;
				}else if(mns > 45 && mns < 60){
					min_amount = hrsrate;
				}
			}
		}
	
		return Number(hr_amount)+Number(min_amount);
	}


	
	//============================================

});
