$(document).ready(function(){
	var base_url=window.location.origin;

	//---------------------------tour voucher events starts -----------------------------

	//if edit tour voucher elements (tour voucher form)
	var pathname = window.location.pathname.split("/");
	var trip_id ='';
	if(pathname[2]=="voucher" && pathname[3]=="add" && Number(pathname[4]) > 0){ 
		trip_id = Number(pathname[4]);
		$.post(base_url+'/voucher/getFromVoucher',{},function(data){
			if(data!=false){
				data=jQuery.parseJSON(data);
				build_voucher_table(data);
			}
		});
	
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

	//calculate total km reading
	$('.voucher-tabs #start_km,.voucher-tabs #end_km').on('click keyup blur',function(e) {
		setTotalKM();
		$(".voucher-tabs #total_km").trigger( "blur" );
	
	});

	$('.voucher-tabs #vehicle_start_time,.voucher-tabs #vehicle_end_time,.voucher-tabs #vehicle_from_date,.voucher-tabs #vehicle_to_date').on('click blur',function(e) {

		setTotalHR();
		
	});


	$('.voucher-tabs #total_km').on('blur',function(){
		setKM_tariff();
	});

	$('.voucher-tabs #total_hr').on('blur',function(){
		setHR_tariff();
	});

	
	$(document).on('keyup', ".trip-expense-input,#driver_bata,#night_halt_charge",function () {
		checkVehicleTotAmount();
	});

	$(document).on('keyup', "#vehicle_unit_amount,#vehicle_advance_amount",function () {
		setVehicleTotalAmount();
	});



	//calculating tax amount on changing tax group from voucher vehicle tab
	$(".voucher-tabs #vehicle_tax_group_id").change(function(){
		var amount = $('.voucher-tabs #vehicle_unit_amount').val();
		$id = $(this).val();
	
		$.post(base_url+"/account/getTotalTax",{id:$id, amt:amount},
		function(data){

			$('.voucher-tabs #vehicle_tax_amount').val(data);

			setVehicleTotalAmount();
		});

		
	});


	

	
	//add vehicle itinerary for voucher======================================
	$('#add-voucher-vehicle').on('click',function(){
		
		var vehicle_data_id 		= $('.voucher-tabs #voucher_vehicle_id').val();
		var vehicle_row_id 		= $('.voucher-tabs #voucher_vehicle_row_id').val();
		var vehicle_model_id 		= $('.voucher-tabs #vehicle_model_id').val();
		var vehicle_ac_type_id		= $('.voucher-tabs #vehicle_ac_type_id').val();
		var from_date 			= $('.voucher-tabs #vehicle_from_date').val();
		var to_date 			= $('.voucher-tabs #vehicle_to_date').val();
		var start_time 			= $('.voucher-tabs #vehicle_start_time').val();
		var end_time 			= $('.voucher-tabs #vehicle_end_time').val();
		var vehicle_id 			= $('.voucher-tabs #vehicle_id').val();
		var driver_id 			= $('.voucher-tabs #driver_id').val();
		var vehicle_tariff_id		= $('.voucher-tabs #vehicle_tariff_id').val();

		var no_of_days			= $('.voucher-tabs #no_of_days').val();
	
		var start_km 			= $('.voucher-tabs #start_km').val();
		var end_km 			= $('.voucher-tabs #end_km').val();

		var base_km 			= $('.voucher-tabs #base_km').val();
		var base_km_amount 		= $('.voucher-tabs #base_km_amount').val();
		var adt_km 			= $('.voucher-tabs #adt_km').val();
		var adt_km_rate 		= $('.voucher-tabs #adt_km_rate').val();
		var adt_km_amount 		= $('.voucher-tabs #adt_km_amount').val();
		var total_km_amount 		= $('.voucher-tabs #total_km_amount').val();
		

		var base_hr 			= $('.voucher-tabs #base_hr').val();
		var base_hr_amount 		= $('.voucher-tabs #base_hr_amount').val();
		var adt_hr 			= $('.voucher-tabs #adt_hr').val();
		var adt_hr_amount 		= $('.voucher-tabs #adt_hr_amount').val();
		var total_hr_amount 		= $('.voucher-tabs #total_hr_amount').val();

		var unit_amount 		= $('.voucher-tabs #vehicle_unit_amount').val();
		var advance_amount	 	= $('.voucher-tabs #vehicle_advance_amount').val();
		var tax_group_id	 	= $('.voucher-tabs #vehicle_tax_group_id').val();
		var tax_amount 			= $('.voucher-tabs #vehicle_tax_amount').val();
		var total_amount	 	= $('.voucher-tabs #vehicle_total_amount').val();
		var km_hr = 1;

		var driver_bata			= $('.voucher-tabs #driver_bata').val();
		var night_halt_charge		= $('.voucher-tabs #night_halt_charge').val();

		var tariffAmtClass 		= $('.vehicletariffamount').attr('amount-class-to-be-selected');

		vehicle_no = $('.voucher-tabs #vehicle_id option:selected').text();
		vehicle_model = $('.voucher-tabs #vehicle_model_id option:selected').text();
		var narration 			= 'Travel : '+vehicle_model+" ( "+vehicle_no+" ) ";
		if(tariffAmtClass == 'totalkmamount'){
			km_hr = 1;

			narration += " Minimum "+base_km+"KM @ Rs. "+total_km_amount+" each day for "+no_of_days+" day(s)";
			if(Number(adt_km) > 0){
				narration += " + Additional "+adt_km+"KM @ Rs."+adt_km_rate+"/KM";
			}
			
		}else{
			km_hr = 0;

			narration += " Minimum "+base_hr+"HR @ Rs. "+total_hr_amount+" each day for "+no_of_days+" day(s)";
			if(Number(adt_hr) > 0){
				narration += " + Additional "+adt_hr+"HR @ Rs."+adt_hr_rate+"/HR";
			}
		}

		if(Number(driver_bata) > 0){
			narration += " + Driver Bata Rs."+driver_bata+" each day for "+no_of_days+" day(s)";
		}

		if(Number(night_halt_charge) > 0){
			narration += " + Night Halt Rs "+night_halt_charge+" each night ";
		}
		
		//trip expense
		var expense = {};
		$(".voucher-tabs .trip-expense-input").each(function(){
			expense_amount 	= $(this).val();
			expense_code 	= $(this).attr("id");
			expense[expense_code] = expense_amount;
			expense_name = $("label[for='"+$(this).attr('id')+"']").text();
			if(Number(expense_amount) > 0){
				narration += " + "+expense_name+" Rs. "+expense_amount;
			}
			
		});

	
			var dataArr = {
				id:vehicle_data_id,vehicle_id:vehicle_id, vehicle_model_id:vehicle_model_id, vehicle_ac_type_id:vehicle_ac_type_id, driver_id:driver_id, tariff_id:vehicle_tariff_id,
				from_date:from_date, to_date:to_date, start_time:start_time, end_time:end_time,
				start_km:start_km, end_km:end_km, km_hr:km_hr, base_km:base_km,
				base_km_amount:base_km_amount, adt_km:adt_km, adt_km_amount:adt_km_amount,
				base_hr:base_hr, base_hr_amount:base_hr_amount, adt_hr:adt_hr,
				adt_hr_amount:adt_hr_amount, driver_bata:driver_bata,
				night_halt_charge:night_halt_charge,trip_expense:expense,unit_amount:unit_amount,
				advance_amount:advance_amount, tax_group_id:tax_group_id,tax_amount:tax_amount,
				narration:narration
				};
			var dataArray={post:dataArr,table:"trip_voucher_vehicles",row_id:vehicle_row_id};
			add_voucher_itinerary(dataArray);
		
		reset_vehicle_tab();
		
	});




	//================================accommodation tab=============================
	//get hotel rooms on change hotel
	$('#acmd_hotel_id').on('change',function(){
		var hotel_id = $(this).val();
		var _date = $('#acmd_from_date').val();
		getTripHotelRooms(hotel_id,_date);
	});

	$('#acmd_room_type_id').on('change',function(){
		var room_type_id = $(this).val();
		var hotel_id = $('#acmd_hotel_id').val();
		var _date = $('#acmd_from_date').val();
		getRoomTariff(hotel_id,room_type_id,_date);

	});


	$('.voucher-tabs #acmd_checkin,.voucher-tabs #acmd_checkout,.voucher-tabs #acmd_from_date,.voucher-tabs #acmd_to_date').on('click blur',function(e) {

		setAccommodationDays();
		
	});

	$(document).on('keyup', ".acmd-total",function () {
		setAcmdTotalAmount();
	});
	
	$(".voucher-tabs #room_tariff_amt").on( "blur",function(){
		setAcmdUnitAmount();
		
	});

	$('.acmd-total').live('keyup',function(){
		setAcmdUnitAmount();
	});

	$(".voucher-tabs #acmd_days").on( "blur",function(){
		setAcmdUnitAmount();
		
	});


	$('#add-voucher-accommodation').on('click',function(){
		
		var accommodation_data_id 	= $('.voucher-tabs #voucher_accommodation_id').val();
		var accommodation_row_id	= $('.voucher-tabs #voucher_accommodation_row_id').val();
		var from_date 			= $('.voucher-tabs #acmd_from_date').val();
		var to_date 			= $('.voucher-tabs #acmd_to_date').val();
		var checkin 			= $('.voucher-tabs #acmd_checkin').val();
		var checkout 			= $('.voucher-tabs #acmd_checkout').val();
		var hotel_id 			= $('.voucher-tabs #acmd_hotel_id').val();
		var room_type_id 		= $('.voucher-tabs #acmd_room_type_id').val();
		var no_of_days			= $('.voucher-tabs #acmd_days').val();
		var room_tariff_amount		= $('.voucher-tabs #room_tariff_amt').val();
	
		var unit_amount 		= $('.voucher-tabs #acmd_unit_amount').val();
		var advance_amount	 	= $('.voucher-tabs #acmd_advance_amount').val();
		var tax_amount 			= $('.voucher-tabs #acmd_tax_amount').val();
		var total_amount	 	= $('.voucher-tabs #acmd_total_amount').val();

		hotel_name = $('.voucher-tabs #acmd_hotel_id option:selected').text();
		room_type_name = $('.voucher-tabs #acmd_room_type_id option:selected').text();
		
		var narration 	= 'Accommodation : '+hotel_name+" - "+room_type_name;	
		narration += " @ Rs "+room_tariff_amount+" per day for "+no_of_days+" day(s)";
		narration += "( checkin :"+checkin+" - checkout : "+checkout+")";

		//room attributes-------------------------------
		var room_attributes = {};
		var room_attributes_amount = 0;
		var i=0;
		var attr_narration = [];
		$(".voucher-tabs .acmd_attr").each(function(index){
			attr_id 	= $(this).val();
			attr_amt 	= $('input[name="acmd_attr_amt[]"]:eq('+index+')').val();
			attr_name 	= $('input[name="acmd_attr_name[]"]:eq('+index+')').val();
			attr_qty 	= $('input[name="acmd_attr_qty[]"]:eq('+index+')').val();
			room_attributes_amount += Number(attr_amt);
			room_attributes[attr_id] = {amount:attr_amt,name:attr_name,quantity:attr_qty};
			attr_narration.push(attr_name +" @"+attr_amt);
			i++;
			
		});
		attr_str = attr_narration.join(' + ');
		if(attr_str != ''){
			narration += " + "+attr_str;
		}
		//------------------------------------------------------

		//meals package-------------------------------
		var meals_package = {};
		var meals_package_amount = 0;
		var i=0;
		var meals_narration = [];
		$(".voucher-tabs .acmd_meals").each(function(index){
			meals_id 	= $(this).val();
			meals_amt 	= $('input[name="acmd_meals_amt[]"]:eq('+index+')').val();
			meals_name 	= $('input[name="acmd_meals_name[]"]:eq('+index+')').val();
			meals_qty 	= $('input[name="acmd_meals_qty[]"]:eq('+index+')').val();
			meals_package_amount += Number(attr_amt);
			
			meals_package[meals_id]= {amount:meals_amt,name:meals_name,quantity:meals_qty};
			meals_narration.push(meals_name +"("+meals_qty+") @"+meals_amt+"/person");
			i++;
			
		});
		
		meals_str = meals_narration.join(' + ');
		if(meals_str != ''){
			narration += " + "+meals_str;
		}
		//------------------------------------------------------
		

		
		
		var dataArr = {id:accommodation_data_id,from_date:from_date,to_date:to_date,
				checkin:checkin,checkout:checkout,hotel_id:hotel_id,room_type_id:room_type_id,
				no_of_days:no_of_days,room_tariff_amount:room_tariff_amount,unit_amount:unit_amount,advance_amount:advance_amount,
				tax_amount:tax_amount,narration:narration,room_attributes:room_attributes,
				meals_package:meals_package
				};
			var dataArray={post:dataArr,table:"trip_voucher_accommodation",row_id:accommodation_row_id};
			add_voucher_itinerary(dataArray);
		
		
		reset_accomodation_tab();
		
	});



	//================================accommodation tab=============================
	$('.voucher-tabs #service_id').on('change',function(){
		var service_id = $(this).val();
		
		setServiceTariff(service_id);
	});

	$(".service-total").on('keyup',function(){
		set_service_totals();
	});



	$('#add-voucher-service').on('click',function(){
		
		var service_data_id 	= $('.voucher-tabs #voucher_service_id').val();
		var service_row_id	= $('.voucher-tabs #voucher_service_row_id').val();
		var from_date 			= $('.voucher-tabs #service_from_date').val();
		var to_date 			= $('.voucher-tabs #service_to_date').val();
		var checkin 			= $('.voucher-tabs #service_checkin').val();
		var checkout 			= $('.voucher-tabs #service_checkout').val();
		var service_id 			= $('.voucher-tabs #service_id').val();
		var rate 			= $('.voucher-tabs #service_rate').val();
		var quantity			= $('.voucher-tabs #service_qty').val();
		var uom_id			= $('.voucher-tabs #service_uom_id').val();
	
		var unit_amount 		= $('.voucher-tabs #service_unit_amount').val();
		var advance_amount	 	= $('.voucher-tabs #service_advance_amount').val();
		var tax_amount 			= $('.voucher-tabs #service_tax_amount').val();
		var total_amount	 	= $('.voucher-tabs #service_total_amount').val();

		service_name = $('.voucher-tabs #service_id option:selected').text();
		
		var narration 	= 'Service : '+service_name;	
		narration += " @ Rs "+rate+" per day for "+quantity+" day(s)";
		
		
		var dataArr = {id:service_data_id,from_date:from_date,to_date:to_date,
				checkin:checkin,checkout:checkout,service_id:service_id,rate:rate,
				quantity:quantity,uom_id:uom_id,unit_amount:unit_amount,advance_amount:advance_amount,
				tax_amount:tax_amount,narration:narration
				};

		var dataArray={post:dataArr,table:"trip_voucher_services",row_id:service_row_id};
		add_voucher_itinerary(dataArray);
		reset_service_tab();
		
	});
	
	//edit voucher
	$(document.body).on('click', '.edit-voucher-itr' ,function(){
		
		var table=$(this).attr('itr-table');
		var row_id=$(this).attr('row-id');
			
		$.post(base_url+"/voucher/getVoucherTabValues",
		 { 
			row_id:row_id,
			table:table
		 },function(data){ 
			if(data!=false){ 
				data=jQuery.parseJSON(data);
			if(table=='trip_voucher_vehicles'){
				
				var href = $('a[href="#tab_1"]');
				$(href).trigger('click');
				$(".voucher-vehicle-tab #voucher_vehicle_row_id").val(row_id);
				$(".voucher-vehicle-tab #voucher_vehicle_id").val(data.id);
				$(".voucher-vehicle-tab #vehicle_model_id option[value='"+data.vehicle_model_id+"']").attr('selected', true);
				$(".voucher-vehicle-tab #vehicle_ac_type_id option[value='"+data.vehicle_ac_type_id+"']").attr('selected', true);
				$(".voucher-vehicle-tab #vehicle_id option[value='"+data.vehicle_id+"']").attr('selected', true);
				$(".voucher-vehicle-tab #driver_id option[value='"+data.driver_id+"']").attr('selected', true);
				generateTariffs(data.vehicle_model_id,data.vehicle_ac_type_id,data.tariff_id,'.voucher-tabs #vehicle_tariff_id');
				setTimeout(function(){$(".voucher-vehicle-tab #vehicle_tariff_id option[value='"+data.tariff_id+"']").attr('selected', true); }, 1000);
				$(".voucher-vehicle-tab #vehicle_from_date").val(data.from_date);
				$(".voucher-vehicle-tab #vehicle_to_date").val(data.to_date);
				$(".voucher-vehicle-tab #vehicle_start_time").val(data.start_time);
				$(".voucher-vehicle-tab #vehicle_end_time").val(data.end_time);
				$(".voucher-vehicle-tab #start_km").val(data.start_km);
				$(".voucher-vehicle-tab #end_km").val(data.end_km);
				/*if(data.vehicle_model_id>0 && data.vehicle_ac_type_id>0)
					generateTariffs(data.vehicle_model_id,data.vehicle_ac_type_id,data.tariff_id,'.voucher-tabs #vehicle_tariff_id');
					setTimeout(function(){ setTariffAttributes(); }, 1000);
					setTotalKM();
					setTotalHR();
					setTimeout(function(){ setKM_tariff(); }, 1000);
					setTimeout(function(){ setHR_tariff(); }, 1000);
				var trip_expense=data.trip_expense; 
					$.each(trip_expense, function(i,e){
					    $(".voucher-vehicle-tab #"+i).val(e);
					});
				$(".voucher-vehicle-tab #vehicle_advance_amount").val(data.advance_amount);
				$(".voucher-vehicle-tab #vehicle_tax_group_id option[value='"+data.tax_group_id+"']").attr('selected', true);
				setTimeout(function(){ $('.voucher-vehicle-tab #vehicle_tax_group_id').trigger('change'); }, 1000);*/
				$(".voucher-vehicle-tab #add-voucher-vehicle").val('Update');
			}else if(table=='trip_voucher_accommodation'){
				
				var href = $('a[href="#tab_2"]');
				$(href).trigger('click');
				
				$(".voucher-accomodation-tab #voucher_accommodation_row_id").val(row_id);
				$(".voucher-accomodation-tab #voucher_accommodation_id").val(data.id);
				
				$(".voucher-accomodation-tab #acmd_from_date").val(data.from_date);
				$(".voucher-accomodation-tab #acmd_to_date").val(data.to_date);
				$(".voucher-accomodation-tab #acmd_checkin").val(data.checkin);
				$(".voucher-accomodation-tab #acmd_checkout").val(data.checkout);
				
				
				$(".voucher-accomodation-tab #acmd_hotel_id option[value='"+data.hotel_id+"']").attr('selected', true);
				getTripHotelRooms(data.hotel_id,data.from_date); 
				setTimeout(function(){ $(".voucher-accomodation-tab #acmd_room_type_id option[value='"+data.room_type_id+"']").attr('selected', true);}, 1000);
				$(".voucher-accomodation-tab #acmd_days").val(data.no_of_days);
				$(".voucher-accomodation-tab #room_tariff_amt").val(data.room_tariff_amount);
				var attributes=[];
				attributes=data.room_attributes;
				var meals_package=[];
				meals_package=data.meals_package;
				set_attr_meals_rows(attributes,meals_package);
				$(".voucher-accomodation-tab #acmd_unit_amount").val(data.unit_amount);
				$(".voucher-accomodation-tab #acmd_advance_amount").val(data.advance_amount);
				$(".voucher-accomodation-tab #acmd_tax_amount").val(data.tax_amount);
				var total_amount=(Number(data.unit_amount)+Number(data.tax_amount))-(Number(data.advance_amount));
				$(".voucher-accomodation-tab #acmd_total_amount").val(total_amount);
				$(".voucher-accomodation-tab #add-voucher-accommodation").val('Update');
				
				
			}else if(table=='trip_voucher_services'){
				
				var href = $('a[href="#tab_3"]');
				$(href).trigger('click');
				
				$(".voucher-service-tab #voucher_service_row_id").val(row_id);
				$(".voucher-service-tab #voucher_service_id").val(data.id);
				
				$(".voucher-service-tab #service_from_date").val(data.from_date);
				$(".voucher-service-tab #service_to_date").val(data.to_date);
				$(".voucher-service-tab #service_checkin").val(data.checkin);
				$(".voucher-service-tab #service_checkout").val(data.checkout);
				$(".voucher-service-tab #service_id option[value='"+data.service_id+"']").attr('selected', true);
				$(".voucher-service-tab #service_rate").val(data.rate);
				$(".voucher-service-tab #service_qty").val(data.quantity);
				$(".voucher-service-tab #service_uom_id option[value='"+data.uom_id+"']").attr('selected', true);
				$(".voucher-service-tab #service_unit_amount").val(data.unit_amount);
				$(".voucher-service-tab #service_advance_amount").val(data.advance_amount);
				$(".voucher-service-tab #service_tax_amount").val(data.tax_amount);
				var total_amount=(Number(data.unit_amount)+Number(data.tax_amount))-(Number(data.advance_amount));
				$(".voucher-service-tab #service_total_amount").val(total_amount);
				$(".voucher-service-tab #add-voucher-service").val('Update');
			}
				
			}
		 
		 });
		
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
			$(".voucher-tabs #no_of_days").val(total[0]);
			$(".voucher-tabs #total_hr").trigger( "blur" );
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

	function compareTotalAmounts(){
		var total_km_amount=$('.voucher-tabs #total_km_amount').val();
		var total_hr_amount=$('.voucher-tabs #total_hr_amount').val();

		if(total_km_amount != '' && total_hr_amount != ''){

			if(Number(total_hr_amount)>Number(total_km_amount)){
				setHRadio();
			}else{
				setKMRadio();
			}
			setVehicleUnitAmount();

		}
		
	}

	function setKMRadio()
	{
		$('.totamount-radio-container1 > .iradio_minimal > .iCheck-helper').trigger('click');
		$('.vehicletariffamount').attr('amount-class-to-be-selected','totalkmamount');
	}

	function setHRadio()
	{
		$('.totamount-radio-container2 > .iradio_minimal > .iCheck-helper').trigger('click');
		$('.vehicletariffamount').attr('amount-class-to-be-selected','totalhramount');
	}


	function setVehicleUnitAmount()
	{
		var tariffAmtClass = $('.vehicletariffamount').attr('amount-class-to-be-selected');
		
		var unitAmt = 0;
		if(tariffAmtClass != '')
		{
			var tariff_amount = $('.'+tariffAmtClass).val();
			var driver_bata = $('#driver_bata').val();
			var night_halt_charge = $("#night_halt_charge").val();

			unitAmt = Number(tariff_amount) + Number(driver_bata) + Number(night_halt_charge);

			$(".voucher-tabs .trip-expense-input").each(function(){
				expense_amount 	= $(this).val();
				unitAmt += Number(expense_amount);
			});

			
		}

		$("#vehicle_unit_amount").val(unitAmt);
		setVehicleTotalAmount();
	}

	//for vehicle tab
	function setVehicleTotalAmount(){
		var unitAmt = Number($("#vehicle_unit_amount").val());
		var advAmt = Number($("#vehicle_advance_amount").val());
		var taxAmt = Number($("#vehicle_tax_amount").val());

		totalAmt = unitAmt - advAmt + taxAmt;
		$("#vehicle_total_amount").val(totalAmt);
	}

	function checkVehicleTotAmount(){
		setVehicleUnitAmount();
		setVehicleTotalAmount();
	}
	


	function add_voucher_itinerary(dataArr){
		
		$.post(base_url+"/voucher/addToVoucher",dataArr,function(data){
			if(data!=false){

				data=jQuery.parseJSON(data);
				build_voucher_table(data);
			}
		});
	}

	function reset_vehicle_tab(){
		
		$(".voucher-tabs #vehicle_model_id").val(-1);
		$(".voucher-tabs #vehicle_ac_type_id").val(-1);
		$(".voucher-tabs #vehicle_id").val(-1);
		$(".voucher-tabs #driver_id").val(-1);
		$(".voucher-tabs #vehicle_tariff_id").val(-1);
		$(".voucher-tabs #vehicle_from_date").val("");
		$(".voucher-tabs #vehicle_to_date").val("");
		$(".voucher-tabs #vehicle_start_time").val("");
		$(".voucher-tabs #vehicle_end_time").val("");
		$(".voucher-tabs #start_km").val("");
		$(".voucher-tabs #end_km").val("");
		$(".voucher-tabs #no_of_days").val("");
		$(".voucher-tabs #total_km").val("");
		$(".voucher-tabs #total_hr").val("");
		$(".voucher-tabs #base_km").val("");
		$(".voucher-tabs #base_km_amount").val("");
		$(".voucher-tabs #adt_km").val("");
		$(".voucher-tabs #adt_km_rate").val("");
		$(".voucher-tabs #adt_km_amount").val("");
		$(".voucher-tabs #total_km_amount").val("");

		$(".voucher-tabs #base_hr").val("");
		$(".voucher-tabs #base_hr_amount").val("");
		$(".voucher-tabs #adt_hr").val("");
		$(".voucher-tabs #adt_hr_rate").val("");
		$(".voucher-tabs #adt_hr_amount").val("");
		$(".voucher-tabs #total_hr_amount").val("");

		$(".voucher-tabs #driver_bata").val("");
		$(".voucher-tabs #night_halt_charge").val("");

		$(".voucher-tabs #vehicle_unit_amount").val("");
		$(".voucher-tabs #vehicle_advance_amount").val("");
		$(".voucher-tabs #vehicle_tax_group_id").val(-1);
		$(".voucher-tabs #vehicle_tax_amount").val("");
		$(".voucher-tabs #vehicle_total_amount").val("");

		$('.iradio_minimal').removeClass('checked');
		$('.iradio_minimal').attr('aria-checked',false);
		
		
	}


	
	
	function reset_accomodation_tab(){
		$('.voucher-tabs #acmd_from_date').val("");
		$('.voucher-tabs #acmd_to_date').val("");
		$('.voucher-tabs #acmd_checkin').val("");
		$('.voucher-tabs #acmd_checkout').val("");
		$('.voucher-tabs #acmd_hotel_id').val(-1);
		$('.voucher-tabs #acmd_room_type_id').val(-1);
		$('.voucher-tabs #acmd_days').val("");
		$('.voucher-tabs #room_tariff_amt').val("");
		$('.voucher-tabs #acmd_unit_amount').val("");
		$('.voucher-tabs #acmd_advance_amount').val("");
		$('.voucher-tabs #acmd_tax_amount').val("");
		$('.voucher-tabs #acmd_total_amount').val("");

		reset_attr_meals_rows();
	}
	
	function reset_service_tab(){
		$('.voucher-tabs #service_from_date').val("");
		$('.voucher-tabs #service_to_date').val("");
		$('.voucher-tabs #service_checkin').val("");
		$('.voucher-tabs #service_checkout').val("");
		$('.voucher-tabs #service_id').val(-1);
		$('.voucher-tabs #service_rate').val("");
		$('.voucher-tabs #service_qty').val("");
		$('.voucher-tabs #service_uom_id').val(-1);
		$('.voucher-tabs #service_unit_amount').val("");
		$('.voucher-tabs #service_advance_amount').val("");
		$('.voucher-tabs #service_tax_amount').val("");
		$('.voucher-tabs #service_total_amount').val("");
	
	}
	
	

	//voucher itinerary table
	function build_voucher_table(data){
	
		reset_voucher_table();
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
			
			$("#voucher-tbl").append(table);
			$("#voucher-itinerary-div").removeClass("hide-me");
			
		
	
		}else{
			$( "#voucher-itinerary-div" ).addClass("hide-me");
		}

			

		
	}

	function reset_voucher_table()
	{
		$("#voucher-tbl").find("tr").remove();
		$( "#voucher-itinerary-div" ).addClass("hide-me");
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


	//get hotel rooms for selected hotel and set room types list
	function getHotelRooms(hotel_id,room_type_id=''){
		var id ='#acmd_room_type_id';
		$(id+' option').remove();
		$(id).append($("<option ></option>").attr("value",'-1').text('--Select--'));
		$.post(base_url+"/hotel/getHotelRooms",{hotel_id:hotel_id},
		function(data){
			if(data!='false'){
				data=jQuery.parseJSON(data);
				var selected="";
				for(var i=0;i< data.length;i++){
					$(id).append($("<option "+selected+"></option>").attr("value",data[i].room_type_id).text(data[i].room_type_name));
				}
		
			}
		});
		
	}

	//get hotel rooms for selected hotel and set room types list
	function getTripHotelRooms(hotel_id,_date,room_type_id=''){

		var id ='#acmd_room_type_id';
		$(id+' option').remove();
		$(id).append($("<option ></option>").attr("value",'-1').text('--Select--'));

		$.post(base_url+"/voucher/getTripHotelRoom",{hotel_id:hotel_id,trip_id:trip_id,_date:_date},
		function(data){
			if(data!='false'){
				data=jQuery.parseJSON(data);
				var selected="";
			
				$(id).append($("<option "+selected+"></option>").attr("value",data.room_type_id).text(data.room_type_name));
			
	
			}
		});
		reset_acmd_tariffs();

		
	}

	//get room tariff amount and assign to tariff amount field
	function getRoomTariff(hotel_id,room_type_id,_date){

		if(Number(hotel_id) > 0 && Number(room_type_id) > 0){
	
			$.post(base_url+"/hotel/getRoomTariff",{hotel_id:hotel_id,room_type_id:room_type_id,_date:_date,trip_id:trip_id},
			function(data){
				if(data!='false'){
					data=jQuery.parseJSON(data);

					room_charge = Number(data.room_charge) * Number(data.days);
					$("#acmd_days").val(data.days);
					$("#room_tariff_amt").val(data.room_charge);
				
					set_attr_meals_rows(data.attributes,data.meals_package);
					setAcmdUnitAmount();

				}else{
					$("#room_tariff_amt").val('');
					reset_attr_meals_rows();
					alert('Room Tariff amount not updated');
				}
			});
		}else{
			$("#room_tariff_amt").val('');
			reset_attr_meals_rows();
		}

		
	}


	

	function getRoomAttributesNMeals(){//not completed

		var hotel_id = $("#acmd_hotel_id").val();
		var room_type_id =  $("#acmd_room_type_id").val();
		$.post(base_url+"/tour/getRoomAttributesNMealsPackage",{trip_id:trip_id,hotel_id:hotel_id,room_type_id:room_type_id},
		function(data){
			if(data!='false'){
				data=jQuery.parseJSON(data);
				
			}
		});
	}
	
	function set_attr_meals_rows(attributes,meals_package)
	{
		var attr_rows = ''; 
		$.each(attributes, function( id, val ) {

			attr_rows += '<input id="acmd_attr'+id+'" class="form-control acmd_attr hide-me" type="text" value="'+id+'" name="acmd_attr_id[]"></input>';
			attr_rows +='<div class="form-group div-with-20-percent-width-with-margin-10"><label for="acmd_attr">Attribute Name</label><input id="acmd_attr_name'+id+'" class="form-control" type="text" value="'+val.name+'" name="acmd_attr_name[]" readonly="readonly"></input></div>';

			attr_rows +='<div class="form-group div-with-20-percent-width-with-margin-10"><label for="acmd_attr">Quantity</label><input id="acmd_attr_qty'+id+'" class="form-control acmd-total" type="text" value="'+val.quantity+'" name="acmd_attr_qty[]" readonly="readonly"></input></div>';

			attr_rows +='<div class="form-group div-with-20-percent-width-with-margin-10"><label for="acmd_attr">Tariff</label><input id="acmd_attr_tariff'+id+'" class="form-control acmd-total" type="text" value="'+val.amount+'" name="acmd_attr_tariff[]" readonly="readonly"></input></div>';
		
			attr_rows +='<div class="form-group div-with-20-percent-width-with-margin-10"><label for="acmd_attr">Amount</label><input id="acmd_attr_amt'+id+'" class="form-control acmd-total" type="text" value="'+Number(val.quantity)*Number(val.amount)+'" name="acmd_attr_amt[]"></input></div>';

		});

		var meals_rows = '';
		$.each(meals_package, function( id, val ) {

			meals_rows += '<input id="acmd_meals'+id+'" class="form-control acmd_meals hide-me" type="text" value="'+id+'" name="acmd_meals_id[]"></input>';

			meals_rows +='<div class="form-group div-with-20-percent-width-with-margin-10"><label for="acmd_meals">Meals Package</label><input id="acmd_meals_name'+id+'" class="form-control" type="text" value="'+val.name+'" name="acmd_meals_name[]" readonly="readonly"></input></div>';

			meals_rows +='<div class="form-group div-with-20-percent-width-with-margin-10"><label for="acmd_meals">Quantity</label><input id="acmd_meals_qty'+id+'" class="form-control acmd-total" type="text" value="'+val.quantity+'" name="acmd_meals_qty[]" readonly="readonly"></input></div>';

			meals_rows +='<div class="form-group div-with-20-percent-width-with-margin-10"><label for="acmd_meals">Tariff</label><input id="acmd_meals_tariff'+id+'" class="form-control acmd-total" type="text" value="'+val.amount+'" name="acmd_meals_tariff[]" readonly="readonly"></input></div>';
		
			meals_rows +='<div class="form-group div-with-20-percent-width-with-margin-10"><label for="acmd_meals">Amount</label><input id="acmd_meals_amt'+id+'" class="form-control acmd-total" type="text" value="'+Number(val.quantity)*Number(val.amount)+'" name="acmd_meals_amt[]"></input></div>';

		});


		$("#attributes-rows").append(attr_rows);
		$("#meals-rows").append(meals_rows);
	}
	function reset_attr_meals_rows()
	{
		$("#attributes-rows").empty();
		$("#meals-rows").empty();
	}

	function reset_acmd_tariffs(){
		$("#acmd_days").val('');
		$("#room_tariff_amt").val('');
		reset_attr_meals_rows();
	}
		

	function setAccommodationDays()
	{
		var start = $('.voucher-tabs #acmd_checkin').val();
		var end = $('.voucher-tabs #acmd_checkout').val();
		var fromdate=$('.voucher-tabs #acmd_from_date').val(); 
		var todate=$('.voucher-tabs #acmd_to_date').val();
		if(fromdate!='' && todate!='' && end!='' && start!=''){
			var total = timeDifference(fromdate,start,todate,end);
			total=total.split('-');
			
			$(".voucher-tabs #acmd_days").val(total[0]);
			$(".voucher-tabs #acmd_days").trigger( "blur" );
		}
	}

	/*function setAccommodationTotals(){
		var no_days = $(".voucher-tabs #acmd_days").val();
		if(no_days == '')
			no_days = 1;
		var tariff_amount = $("#room_tariff_amt").val();

		unit_amount = Number(no_days)*Number(tariff_amount);

		$("#acmd_unit_amount").val(unit_amount);
		var adv_amount = $("#acmd_advance_amount").val();
		var tax_amount = $("#acmd_tax_amount").val();

		total_amount = unit_amount + Number(tax_amount) - Number(adv_amount);

		$("#acmd_total_amount").val(total_amount);
		
	}*/

	//for acc tab
	function setAcmdUnitAmount()
	{
		var room_charge = $('#room_tariff_amt').val();
		var days = $('#acmd_days').val();
		
		var unitAmt = Number(room_charge)*Number(days);

		
			
		$(".voucher-tabs .acmd_attr").each(function(index){
			attr_amt  = $('input[name="acmd_attr_amt[]"]:eq('+index+')').val();
			unitAmt += Number(attr_amt);
			
		});
		$(".voucher-tabs .acmd_meals").each(function(index){
			meals_amt  = $('input[name="acmd_meals_amt[]"]:eq('+index+')').val();
			unitAmt += Number(meals_amt);
			
		});


		$("#acmd_unit_amount").val(unitAmt);
		setAcmdTotalAmount();
	}


	function setAcmdTotalAmount(){
		var unitAmt = Number($("#acmd_unit_amount").val());
		var advAmt = Number($("#acmd_advance_amount").val());
		var taxAmt = Number($("#acmd_tax_amount").val());

		totalAmt = unitAmt - advAmt + taxAmt;
		$("#acmd_total_amount").val(totalAmt);
	}


	
	//============================================



	//====================service tab functions===============
	function setServiceTariff(service_id){
		
		if(Number(service_id) > 0){

			$.post(base_url+"/voucher/getService",{trip_id:trip_id,service_id:service_id},
				function(data){
				if(data!='false'){
					data=jQuery.parseJSON(data);

					$('.voucher-tabs #service_rate').val(data.rate);
					$('.voucher-tabs #service_qty').val(data.quantity);
					$('.voucher-tabs #uom_id').val(data.uom_id);

					set_service_totals();
				
				}else{
					reset_service_row();
				}
			});
		}else{
			reset_service_row();
		}

		
	}
	
	function reset_service_row()
	{
		$('.voucher-tabs #service_id').val(-1);
		$('.voucher-tabs #uom_id').val(-1);
		$('.voucher-tabs #service_unit_amount').val("");
		$('.voucher-tabs #service_tax_amount').val("");
		$('.voucher-tabs #service_total_amount').val("");
		$('.voucher-tabs #service_advance_amount').val("");
		$('.voucher-tabs #service_rate').val("");
		$('.voucher-tabs #service_qty').val("");
	}

	function set_service_totals()
	{
		var rate = $('.voucher-tabs #service_rate').val();
		var qty = $('.voucher-tabs #service_qty').val();
		unit_amount = Number(rate)*Number(qty);

		$('.voucher-tabs #service_unit_amount').val(unit_amount);

		var adv = $('.voucher-tabs #service_advance_amount').val();
		var tax = $('.voucher-tabs #service_tax_amount').val();
		total = unit_amount + Number(tax) - Number(adv);
		$('.voucher-tabs #service_total_amount').val(total);
		
	}

	


	//===========================================

});
