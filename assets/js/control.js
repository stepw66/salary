$(document).ready(function() {


    $( "#cid" ).autocomplete({
		  source: "user_autocomplete",
		  minLength: 2,
		  select: function(event, ui) {			  		  		
		  		$('#cid').val(ui.item.value);
		  }
	}); 

    //----------- add emp meter -------------//
	$( "#empmeter_s" ).autocomplete({
		  source: "_autocomplete",
		  minLength: 2,
		  select: function(event, ui) {			  		  		
		  		$('#empmeter_s').val(ui.item.value);
		  }
	}); 

	//----------- add emp home -------------//
	$( "#emphome_s" ).autocomplete({
		  source: "_autocomplete",
		  minLength: 2,
		  select: function(event, ui) {			  		  		
		  		$('#emphome_s').val(ui.item.value);
		  }
	}); 

    //------------ unit cost -----------------//
	$( "#empunit" ).autocomplete({
		  source: "_autocomplete",
		  minLength: 2,
		  select: function(event, ui) {			  		  		  		
		  		$('#empunit').val(ui.item.value);

		  		$( '#name-unit-show' ).html(  ' : ' + $('#empunit').val() );
				
				var cid = $('#empunit').val().split(" ");

				$.ajax({
					type:"GET",
					url:"viewempunit/"+cid[0],
					data:"", 
					cache: false,     
					success:function( result ){										   			   
					   $( '#listname-emp-unitcosts' ).html( result );			  
					}
				});
		  }
	});		

	$( '#add-emp-unit' ).click( function(){
		var unitid  = $( '#unit' ).val();		
		var name 	= $( '#empunit' ).val();
		var cal 	= $( '#cal' ).val();		

		if( unitid == 0 || name == '' || cal == '' )
		{
			if(  name == '' ){
				alert( 'กรุณากรอกชื่อ' );
				$( '#empunit' ).focus();
			}else if( unitid == 0 ){
				alert( 'กรุณาเลือกหน่วยต้นทุน' );
				$( '#unit' ).focus();
			}else{
				alert( 'กรุณากรอกเปอร์เซ็นในการคำนวน' );
				$( '#cal' ).focus();
			}
			$( '#cal' ).val('100');
		}
		else 
		{
			var cid = name.split(" ");

			$.ajax({
				type:"GET",
				url:"addemp/"+unitid+"/"+cid[0]+"/"+cal,
				data:"", 
				cache: false,     
				success:function( result ){	
					if( result == 'NO1' )
					{
						alert( 'ไม่สามารถเพิ่มหน่วยต้นทุนได้ กรุณาแจ้งผู้ดูแลระบบ' );
						$( '#empunit' ).val(''); 
					    $( '#cal' ).val('100');
					    $( '#listname-emp-unitcosts' ).html('');
					    $( '#name-unit-show' ).html('');
					}
					else if( result == 'NO2' )
					{
						alert( 'ไม่สามารถเพิ่มหน่วยต้นทุนได้ ** มีข้อมูลซ้ำ **' );
						$( '#empunit' ).val(''); 
					    $( '#cal' ).val('100');
					    $( '#listname-emp-unitcosts' ).html('');
					    $( '#name-unit-show' ).html('');
					}
					else
					{
						$( '#empunit' ).val(''); 
					    $( '#listname-emp-unitcosts' ).html( result );					  
					    $( '#cal' ).val('100');
					}				  
				}
			});
		}
	});







	//-------------- get salary empall  แก้ไขเงินเดือนย้อนหลัง เพิ่มย้อนหลัง ------------------//
	$( "#empallget" ).autocomplete({
		  source: "empsalaryAll/_autocomplete",
		  minLength: 2,
		  select: function(event, ui) {			  		  		  		
	  		$('#salary_add_new, #salary_edit_old').prop('checked', false);
	  		$('#f-empallgettype').hide();
	  		$('#bank_acc, #salary, #salary_other, #salary_sso, #water, #elec, #order_date, #pts, #ot, #ch8, #outpcu, #u_travel').val('');
	  		$('#bank_acc, #salary, #r_c, #r_other, #water, #elec, #order_date, #pts2, #ot, #ch8, #no_v, #outpcu, #special_m, #u_travel, #game_sp').val('');
		  }
	});	

	$('#f-empallgettype').hide();
	$('#salary_edit_old_m, #salary_edit_old_y, #btnsalaryedit').hide();

	//add
	$('#salary_add_new').click(function(){
		$('#salary_edit_old_m, #salary_edit_old_y, #btnsalaryedit').hide();
		$('#bank_acc, #salary, #salary_other, #salary_sso, #water, #elec, #order_date, #pts, #ot, #ch8, #outpcu, #u_travel').val('');
		$('#bank_acc, #salary, #r_c, #r_other, #water, #elec, #order_date, #pts2, #ot, #ch8, #no_v, #outpcu, #special_m, #u_travel, #game_sp').val('');

		if( $('#empallget').val() == '' ){

			$('#empallget').focus();
			$('#f-empallgettype').hide();
			alert('กรุณาเลือกชื่อที่ต้องการแก้ไข');

			$('#salary_add_new, #salary_edit_old').prop('checked', false);
		}else{
			$('#h-empallgettype').html('เพิ่มรายการเงินเดือน');
			$('#f-empallgettype').show();

			var name = $( "#empallget" ).val();
			var cid = name.split(" ");
			cid = cid[0];

			$('#t-emp1').hide();
			$('#t-emp2').hide();

			$.ajax({
				type:"GET",
				url:"empsalaryAll/checktype_emp/"+cid,
				data:"", 
				cache: false,     
				success:function( result ){	
					$('#cidemp').val(cid);
					$('#typeaction').val('add');					
					if( result == 'พกส.(ปฏิบัติงาน)' || result == 'ลูกจ้างชั่วคราว' || result == 'ลูกจ้างรายวัน' ){
						$('#t-emp1').show();
					}else if( result == 'ข้าราชการ' || result == 'ลูกจ้างประจำ' ){
						$('#t-emp2').show();
					}
				}
			});	

		}//else
	});
	$('#salary_edit_old').click(function(){
		$('#bank_acc, #salary, #salary_other, #salary_sso, #water, #elec, #order_date, #pts, #ot, #ch8, #outpcu, #u_travel').val('');
		$('#bank_acc2, #salary2, #r_c2, #r_other2, #water2, #elec2, #order_date2, #pts2, #ot2, #ch82, #no_v2, #outpcu2, #special_m2, #u_travel2, #game_sp').val('');

		if( $('#empallget').val() == '' ){

			$('#empallget').focus();
			$('#f-empallgettype').hide();
			alert('กรุณาเลือกชื่อที่ต้องการแก้ไข');

			$('#salary_add_new, #salary_edit_old').prop('checked', false);
		}else{
			
			$('#h-empallgettype').html('');
			$('#f-empallgettype').hide();
			$('#salary_edit_old_m, #salary_edit_old_y, #btnsalaryedit').show();
			
		}
	});
	//edit
    $('#btnsalaryedit').click(function(){
    	$('#h-empallgettype').html('แก้ไขรายการเงินเดือน');
		$('#f-empallgettype').show();

		$('#bank_acc, #salary, #salary_other, #salary_sso, #water, #elec, #order_date, #pts, #ot, #ch8, #outpcu, #u_travel').val('');
		$('#bank_acc2, #salary2, #r_c2, #r_other2, #water2, #elec2, #order_date2, #pts2, #ot2, #ch82, #no_v2, #outpcu2, #special_m2, #u_travel2, #game_sp').val('');

		var name = $( "#empallget" ).val();
		var cid = name.split(" ");
		cid = cid[0];
		var y = $('#salary_edit_old_y').val();
		var m = $('#salary_edit_old_m').val();

		$('#t-emp1').hide();
		$('#t-emp2').hide();

		$.ajax({
			type:"GET",
			url:"empsalaryAll/checktype_emp/"+cid,
			data:"", 
			cache: false,     
			success:function( result ){	
				$('#cidemp').val(cid);	
				$('#typeaction').val('edit');				
				if( result == 'พกส.(ปฏิบัติงาน)' || result == 'ลูกจ้างชั่วคราว' || result == 'ลูกจ้างรายวัน' ){

					$.ajax({
						type:"GET",
						url:"empsalaryAll/get_empall/"+cid+"/type1/"+y+"/"+m,
						data:"", 
						cache: false,     
						success:function( result ){		
							for (var i = 0; i < result.length; i++) { 
								$('#bank_acc').val(result[i].bank_acc);
								$('#salary').val(result[i].salary);
								$('#salary_other').val(result[i].salary_other);
								$('#salary_sso').val(result[i].salary_sso);
								$('#water').val(result[i].water);
								$('#elec').val(result[i].elec);
								$('#cprt').val(result[i].cprt);
								$('#order_date').val(result[i].order_date);
								$('#pts').val(result[i].pts);
								$('#ot').val(result[i].ot);
								$('#ch8').val(result[i].ch8);
								$('#outpcu').val(result[i].outpcu);
								$('#u_travel').val(result[i].u_travel);
							}
						}
					});

					$('#t-emp1').show();
				}else if( result == 'ข้าราชการ' || result == 'ลูกจ้างประจำ' ){

					$.ajax({
						type:"GET",
						url:"empsalaryAll/get_empall/"+cid+"/type2/"+y+"/"+m,
						data:"", 
						cache: false,     
						success:function( result ){						
							for (var i = 0; i < result.length; i++) { 
								$('#bank_acc2').val(result[i].bank_acc);
								$('#salary2').val(result[i].salary);
								$('#r_c2').val(result[i].r_c);
								$('#r_other2').val(result[i].r_other);
								$('#water2').val(result[i].water);
								$('#elec2').val(result[i].elec);
								$('#order_date2').val(result[i].order_date);
								$('#pts2').val(result[i].pts2);
								$('#ot2').val(result[i].ot);
								$('#ch82').val(result[i].ch8);
								$('#no_v2').val(result[i].no_v);
								$('#outpcu2').val(result[i].outpcu);
								$('#special_m2').val(result[i].special_m);
								$('#u_travel2').val(result[i].u_travel);
								$('#game_sp').val(result[i].game_sp);
							}					  									  
						}
					});

					$('#t-emp2').show();
				}
			}
		});	
    });

    //save and edit
    $('#btnsalarysave').click(function(){
    	var cid 		= $('#cidemp').val();
    	var typeaction 	= $('#typeaction').val();
    	var bank_acc 	= $('#bank_acc').val();
    	var salary 		= $('#salary').val();
    	var salary_other = $('#salary_other').val();
    	var salary_sso 	= $('#salary_sso').val();
    	var water 		= $('#water').val();
    	var elec 		= $('#elec').val();
    	var order_date 	= $('#order_date').val();
    	var pts 		= $('#pts').val();
    	var ot 			= $('#ot').val();
    	var ch8 		= $('#ch8').val();
    	var outpcu 		= $('#outpcu').val();
    	var u_travel 	= $('#u_travel').val();  	
	
		$.ajax({
			type:"POST",
			url:"empsalaryAll/salary_add_new",
			data:"cid="+ cid+"&typeaction="+ typeaction +"&bank_acc="+bank_acc+"&salary="+salary+"&salary_other="+salary_other+"&salary_sso="+salary_sso+"&water="+water+"&elec="+elec+"&order_date="+order_date+"&pts="+pts+"&ot="+ot+"&ch8="+ch8+"&outpcu="+outpcu+"&u_travel="+u_travel, 
			cache: false,     
			success:function( result ){																
				if( result == 'ok' ){
					alert('บันทึกข้อมูลเรียบร้อย');
				}else{
					alert('ไม่สามารถบันทึกข้อมูลได้');
				}	

				location.reload();	
				$('#salary_add_new, #salary_edit_old').prop('checked', false);
	  			$('#f-empallgettype').hide();		  									  
			}
		});		
    });

    //save and edit ocsc
    $('#btnsalarysave_ocsc').click(function(){
    	var cid 		= $('#cidemp').val();
    	var typeaction 	= $('#typeaction').val();
    	var bank_acc 	= $('#bank_acc2').val();
    	var salary 		= $('#salary2').val();
    	var r_c 		= $('#r_c2').val();
    	var r_other 	= $('#r_other2').val();
    	var water 		= $('#water2').val();
    	var elec 		= $('#elec2').val();
    	var order_date = $('#order_date2').val();
    	var pts2 		= $('#pts2').val();
    	var ot 			= $('#ot2').val();
    	var ch8 		= $('#ch82').val();
    	var no_v 		= $('#no_v2').val();
    	var outpcu 		= $('#outpcu2').val();
    	var special_m 	= $('#special_m2').val();
    	var u_travel 	= $('#u_travel2').val();
    	var game_sp 	= $('#game_sp').val();
	
		$.ajax({
			type:"POST",
			url:"empsalaryAll/salary_add_new_ocsc",
			data:"cid="+ cid+"&typeaction="+ typeaction +"&bank_acc="+bank_acc+"&salary="+salary+"&r_c="+r_c+"&r_other="+r_other+"&water="+water+"&elec="+elec+"&order_date="+order_date+"&pts2="+pts2+"&ot="+ot+"&ch8="+ch8+"&no_v="+no_v+"&outpcu="+outpcu+"&special_m="+special_m+"&u_travel="+u_travel+"&game_sp="+game_sp, 
			cache: false,     
			success:function( result ){																
				if( result == 'ok' ){
					alert('บันทึกข้อมูลเรียบร้อย');
				}else{
					alert('ไม่สามารถบันทึกข้อมูลได้');
				}	

				location.reload();	
				$('#salary_add_new, #salary_edit_old').prop('checked', false);
	  			$('#f-empallgettype').hide();		  									  
			}
		});	
    });







	//---------------- UserDep --------------------//
	$( '#dep' ).change(function(){
		var depid  = $( '#dep' ).val();	
		$( '#listname-emp' ).html( '' );
		if( depid == 0 )
		{
			$( '#listname-emp' ).html( '' );
		}	
		else
		{
			$.ajax({
				type:"GET",
				url:"userdep/viewuserdep/"+depid,
				data:"", 
				cache: false,     
				success:function( result ){						
					$( '#listname-emp' ).html( result );					  									  
				}
			});
		}		
	});

	$( "#empdep" ).autocomplete({
		  source: "userdep/_autocomplete",
		  minLength: 2,
		  select: function(event, ui) {			  		  		  		
		  		
		  }
	});	

	$( '#add-emp' ).click( function(){
		var depid  = $( '#dep' ).val();		
		var name 	= $( '#empdep' ).val();	
		$( '#listname-emp' ).html( '' );		

		if( depid == 0 || name == '' )
		{			
			if( depid == 0 ){
				alert( 'กรุณาเลือกหน่วยต้นทุน' );
				$( '#dep' ).focus();
			}else if(  name == '' ){
				alert( 'กรุณากรอกชื่อ' );
				$( '#empdep' ).focus();
			}
		}
		else 
		{
			var cid = name.split(" ");

			$.ajax({
				type:"GET",
				url:"userdep/edituserdep/"+depid+"/"+cid[0],
				data:"", 
				cache: false,     
				success:function( result ){						
					$( '#listname-emp' ).html( result );
					$( '#empdep' ).val( '' );					  									  
				}
			});
		}
	});

	//----------- get user add special 1 ------------//
	$( '#speyear1' ).change(function(){
		$( '#spemonth1' ).val(0);
		$( '#view-data-special' ).html( '' );
	});
	$( '#paylist1' ).change(function(){
		$( '#spemonth1' ).val(0);
		$( '#view-data-special' ).html( '' );
	});

	$( '#spemonth1' ).change(function(){
		if( $( '#speyear1' ).val() == 0 || $( '#paylist1' ).val() == 0 )
		{
			alert('กรุณาเลือกข้อมูลที่ต้องการให้ครบ');
		}
		else
		{
			if( $( '#spemonth1' ).val() == 0 )
			{
				$( '#view-data-special' ).html( '' );
			}
			else
			{
				var pay;
				var q_pay;	
				var pay_all=0;			
				switch( $( '#paylist1' ).val() ) {				  
				    case '1'://1 พตส.เงินนอกงบประมาณ
				        pay = 'has_pts';
				        q_pay = 'q_pts';				     
				        break;
				    case '2'://2 ค่า OT
				        pay = 'has_ot';	
				        q_pay = 'q_ot';			      
				        break;
				    case '3'://3 ค่า ฉ 8
				        pay = 'has_ch8';
				        q_pay = 'q_ch8';				     
				        break;
				    case '4'://4 ค่า ไม่ทำเวช
				        pay = 'has_no_v';	
				        q_pay = 'q_no_v';			      
				        break;
				    case '5'://5 ค่า ออกหน่วย
				        pay = 'has_pcu';
				        q_pay = 'q_pcu';				      
				        break;
				    case '6'://6	พตส.เงินงบประมาณ
				        pay = 'has_pts2';	
				        q_pay = 'q_pts2';			    
				        break;
				    case '7'://7	ทั้งหมด
				        pay_all = 1;		    
				        break;
					case '8'://8
				        pay = 'has_ch11';	
				        q_pay = 'q_ch11';			    
						break;
					case '9'://9
				        pay = 'has_ch112';	
				        q_pay = 'q_ch112';			    
				        break;
				    default://0 กรุณาเลือก
				        pay = '';
				        q_pay = '';
				}


				if( pay_all == 0 ){
					$.ajax({
						type:"GET",
						url:"add_special1/"+$( '#speyear1' ).val()+"/"+$( '#spemonth1' ).val()+"/"+pay+"/"+q_pay,
						data:"", 
						cache: false,     
						success:function( result ){						
							$( '#view-data-special' ).html( result );				  									  
						}
					});	
				}else{
					$.ajax({
						type:"GET",
						url:"add_special1/"+$( '#speyear1' ).val()+"/"+$( '#spemonth1' ).val()+"/all",
						data:"", 
						cache: false,     
						success:function( result ){						
							$( '#view-data-special' ).html( result );				  									  
						}
					});	
				}			
			}
		}
	});


	//------------ ลำดับค่าตอบแทน -------------//
	$( '#paylist' ).change(function(){
						
		var pay;
		var name_pay;
		switch( $( '#paylist' ).val() ) {
		    case '0'://0 กรุณาเลือก
		        pay = '0';
		        break;
		    case '1'://1 ค่า พตส.
		        pay = 'has_pts';
		        name_pay = 'ค่า พตส.เงินนอกงบประมาณ';
		        break;
		    case '2'://2 ค่า OT
		        pay = 'has_ot';
		        name_pay = 'ค่า OT';
		        break;
		    case '3'://3 ค่า ฉ 8
		        pay = 'has_ch8';
		        name_pay = 'ค่า ฉ 8';
		        break;
		    case '4'://4 ค่า ไม่ทำเวช
		        pay = 'has_no_v';
		        name_pay = 'ค่า ไม่ทำเวช';
		        break;
		    case '5'://5 ค่า ออกหน่วย
		        pay = 'has_pcu';
		        name_pay = 'ค่า ออกหน่วย';
		        break;
		    case '6'://6	หักค่าน้ำ-ค่าไฟ
		        pay = 'has_pts2';
		        name_pay = 'ค่า พตส.เงินงบประมาณ';
		        break;
			case '7'://7
		        pay = 'has_ch11';
		        name_pay = 'ค่า ฉ 11 เงินนอกงบประมาณ';
				break;
			case '8'://8
		        pay = 'has_ch112';
		        name_pay = 'ค่า ฉ 11 เงินงบประมาณ';
		        break;
		    default://0 กรุณาเลือก
		        pay = '0';
		}		

		if( pay != '0' ){
			$( '#name-has-show' ).html( name_pay );
			$.ajax({
				type:"GET",
				url:"sortrepay/list_userpay/"+pay,
				data:"", 
				cache: false,     
				success:function( result ){						
					$( '#listname-has' ).html( result );						  									  
				}
			});	
		}else{
			$( '#listname-has' ).html( '' );	
		}

	});

	//--------------- add_meter ---------------//
	$( '#addmeter' ).click(function(){
		//alert( escape($( '#meter' ).val()) );
		if( $( '#meter' ).val() == '' ){
			alert( 'กรุณากรอกข้อมูล' );
			$( '#meter' ).focus();
		}else{
			$.ajax({
				type:"POST",
				url:"add_meter_todb",
				data:"m="+ $( '#meter' ).val(), 
				cache: false,     
				success:function( result ){											
					$( '#view-meter' ).html( result );
					$( '#meter' ).val('');						  									  
				}
			});	
		}
	});

	//--------------- add emp meter ----------------//
	$( '#addempmeter' ).click(function(){
		var meter = $( '#meter_s' ).val();
		var name = $( '#empmeter_s' ).val();
		var cid = name.split(" ");
		
		$.ajax({
			type:"GET",
			url:"update_empmeter/"+cid[0]+"/"+meter,
			data:"", 
			cache: false,     
			success:function( result ){				
			    $( '#empmeter_s' ).val('');				
				$( '#view-empmeter' ).html( result );						  									  
			}
		});			
	});

	//--------------- view emp meter ----------------//
	$( '#meter_s' ).change(function(){
		$( '#empmeter_s' ).val('');
		$.ajax({
			type:"GET",
			url:"view_empmeter/"+$( '#meter_s' ).val(),
			data:"", 
			cache: false,     
			success:function( result ){								
				$( '#view-empmeter' ).html( result );						  									  
			}
		});
	});

	//-------------------- add water --------------------//
	$( '#year_water' ).change(function(){
		if( $( '#year_water' ).val() == 0 ){
			$( '#view_water' ).html( '' );
			$( '#month_water' ).val(0);
		}
	});
	//---------------view water--------------------//
	$( '#month_water' ).change(function(){
		if( $( '#month_water' ).val() == 0 )
		{
			$( '#view_water' ).html( '' );
		}
		else
		{
			$.ajax({
				type:"GET",
				url:"view_water/"+$( '#year_water' ).val()+"/"+$( '#month_water' ).val(),
				data:"", 
				cache: false,     
				success:function( result ){	
					$( '#view_water' ).html('');										
					$( '#view_water' ).html( result );						  									  
				}
			});	
		}
	});

	//--------------- add home -----------------//
	$( '#addhome' ).click(function(){
		if( $( '#elec_number' ).val() == '' || $( '#elec_home' ).val() == '' ){
			alert( 'กรุณากรอกข้อมูล' );
		}else{
			$.ajax({
				type:"POST",
				url:"add_home_todb",
				data:"elec_number="+ $( '#elec_number' ).val()+"&elec_home="+ $( '#elec_home' ).val(), 
				cache: false,     
				success:function( result ){																
					$( '#elec_number' ).val('');
					$( '#elec_home' ).val('');	
					location.reload();						  									  
				}
			});	
		}
	});

    //--------------- view home -----------------//
	$( '#home_s' ).change(function(){
		$( '#emphome_s' ).val('');
		$.ajax({
			type:"GET",
			url:"view_emphome/"+$( '#home_s' ).val(),
			data:"", 
			cache: false,     
			success:function( result ){								
				$( '#view-emphome' ).html( result );						  									  
			}
		});
	});

	//--------------- addemphome_s-----------------//
	$( '#addemphome_s' ).click(function(){
		if( $( '#home_s' ).val() != 0 )
		{
			var cid = $( '#emphome_s' ).val().split(" ");
			$.ajax({
					type:"POST",
					url:"update_emphome",
					data:"home_id="+ $( '#home_s' ).val()+"&cid="+cid[0] , 
					cache: false,     
					success:function( result ){																
						$( '#home_s' ).val(0);
						$( '#emphome_s' ).val('');	
						$( '#view-emphome' ).html( result );						  									  
					}
			});	
		}
		else{
			alert('กรุณาเลือกบ้านพัก');
			$( '#home_s' ).focus();
		}	
	});

	//-------------------- add elec --------------------//
	$( '#year_elec' ).change(function(){
		if( $( '#year_elec' ).val() == 0 ){
			$( '#view_elec' ).html( '' );
			$( '#month_elec' ).val(0);
		}
	});
	//---------------view elec--------------------//
	$( '#month_elec' ).change(function(){
		if( $( '#month_elec' ).val() == 0 )
		{
			$( '#view_elec' ).html( '' );
		}
		else
		{
			$.ajax({
				type:"GET",
				url:"view_elec/"+$( '#year_elec' ).val()+"/"+$( '#month_elec' ).val(),
				data:"", 
				cache: false,     
				success:function( result ){	
					$( '#view_elec' ).html('');										
					$( '#view_elec' ).html( result );						  									  
				}
			});	
		}
	});

	//------------------- unit costs --------------------------//
	$( '#bt_manager' ).click(function(){
		if( $( '#unitcost_y' ).val() == 0 || $( '#unitcost_m' ).val() == 0 || $( '#unitcost_u' ).val() == 0 )
		{
			alert('กรุณาเลือกข้อมูลที่ต้องการให้ครบ');
		}
		else
		{
			var y;
			var m;
			var u;

			y = $( '#unitcost_y' ).val();
			m = $( '#unitcost_m' ).val();
			u = $( '#unitcost_u' ).val();

			$.ajax({
				type:"GET",
				url:"manager/view_manager/"+y+"/"+m+"/"+u,
				data:"", 
				cache: false,     
				success:function( result ){															
					$( '#list-manager-unitcosts' ).html( result );						  									  
				}
			});	

		}
	});
	





});

//------------------------- แก้ไขข้อมูลช่วงการดึงข้อมูล OT กับ ประกันสังคม -------------------------------//
function update_range()
{
    var i=0;	
 	for( i; i <= $('input[name="r_name[]"]').length-1; i++ )
    {   
        var name = $('#r_name'+i).val();
    	var r_start = $('#r_start'+i).val();
    	var r_end = $('#r_end'+i).val();    	   	

    	$.ajax({
			type:"GET",
			url:"range_ot_sso/update_range/"+name+"/"+r_start+"/"+r_end,
			data:"", 
			cache: false,     
			success:function( result ){	
               	   			  
			}
		});
    }
    
    alert('บันทึกข้อมูลเรียบร้อย');
    location.reload();	
}

//--------------------- แก้ไขข้อมูล UnitCosts ----------------------//
function update_unitcosts()
{
	var i=0;	
 	for( i; i <= $('input[name="u_cid1[]"]').length-1; i++ )
    {   
    	var y = $('#u_y1'+i).val();
    	var m = $('#u_m1'+i).val();
    	var type = $('#u_type1'+i).val();   
    	var cid = $('#u_cid1'+i).val();
    	var u_travel1 = $('#u_travel1'+i).val();
    	var u_other1 = $('#u_other1'+i).val();   	    	

    	$.ajax({
			type:"GET",
			url:"manager/update_manager/"+y+"/"+m+"/"+type+"/"+cid+"/"+u_travel1+"/"+u_other1,
			data:"", 
			cache: false,     
			success:function( result ){				   			   
			   $( '#list-manager-unitcosts' ).html( result );			   			  
			}
		});
    }
}

//-------------- เพิ่มข้อเงินเดือนอัตโนมัต  พกส - ลูกจ้างชั่วคราว------------------//
function emp1_salary_auto()
{
	$.ajax({
		type:"GET",
		url:"salary_auto/emp1_addauto",
		data:"", 
		cache: false,     
		success:function( result ){	
			$( '#status_addauto_emp1' ).html('<span class="[success alert secondary] [round radius] label">เพิ่มข้อมูลเรียบร้อย</span>');					  									  
		}
	});
}

//-------------- เพิ่มข้อเงินเดือนอัตโนมัต ราชการ ลูกจ้างประจำ------------------//
function addAuto()
{
	$.ajax({
		type:"GET",
		url:"salary_insert_auto/addauto",
		data:"", 
		cache: false,     
		success:function( result ){	
			$( '#status_addauto' ).html('<span class="[success alert secondary] [round radius] label">เพิ่มข้อมูลเรียบร้อย</span>');					  									  
		}
	});	

}

//------------------ del_emphome ----------------//
function del_emphome( id, cid )
{
	$.ajax({
		type:"GET",
		url:"del_emphome/"+id+"/"+cid,
		data:"", 
		cache: false,     
		success:function( result ){	
			location.reload();					  									  
		}
	});	
}

//------------------ del_home ----------------//
function del_home( id )
{
	$.ajax({
		type:"GET",
		url:"del_home/"+id,
		data:"", 
		cache: false,     
		success:function( result ){	
			location.reload();					  									  
		}
	});	
}

//------------------- del_empmeter --------------------//
function del_empmeter( id, cid )
{
	$.ajax({
		type:"GET",
		url:"del_empmeter/"+id+"/"+cid,
		data:"", 
		cache: false,     
		success:function( result ){	
			$( '#view-empmeter' ).html('');										
			$( '#view-empmeter' ).html( result );						  									  
		}
	});	
}

//-------------------- del_meter ------------------------//
function del_meter( id )
{
	$.ajax({
		type:"GET",
		url:"del_meter/"+id,
		data:"", 
		cache: false,     
		success:function( result ){											
			$( '#view-meter' ).html( result );						  									  
		}
	});	
}

//--------------------- update data table n_datageneral has -----------------//
function chk_has( cid, value, has, dep_id )
{
	var data;
	if( value == 0 ){
		data = 1;
	}else{
		data = 0;
	}

	$.ajax({
		type:"GET",
		url:"userdep/edit_hasData/"+cid+"/"+data+"/"+has+"/"+dep_id,
		data:"", 
		cache: false,     
		success:function( result ){						
			$( '#listname-emp' ).html( result );						  									  
		}
	});	
}

//--------------------- update data table n_datageneral q_pay -----------------//
function update_qpay( key )
{
	var i=0;	
 	for( i; i <= $('input[name="cidpay1[]"]').length-1; i++ )
    {      
    	if( $('#q_pay1'+i).val() == '' ){
    		var q_pay1 = 0;
    	}else{
    		var q_pay1 = $('#q_pay1'+i).val();
    	}

    	$.ajax({
			type:"GET",
			url:"sortrepay/list_qpay/"+key+"/"+q_pay1+"/"+$('#cidpay1'+i).val(),
			data:"", 
			cache: false,     
			success:function( result ){				   			   
			   $( '#listname-has' ).html( '' );			   			  
			}
		});
    }
}

//------------- ลบคนออกแผนก -------------//
function delempdep( cid, dep_id )
{
	$.ajax({
		type:"GET",
		url:"userdep/delempdep/"+cid+"/"+dep_id,
		data:"", 
		cache: false,     
		success:function( result ){				   			   
		   $( '#listname-emp' ).html( result );			  
		}
	});
}

//------------- ลบคนออกแผนก -------------//
function delemp( id, unit_id, cid )
{
	$.ajax({
		type:"GET",
		url:"delemp/"+id+'/'+unit_id+'/'+cid,
		data:"", 
		cache: false,     
		success:function( result ){				   			   
		   $( '#listname-emp-unitcosts' ).html( result );			  
		}
	});
}

//------------- แก้ไขการเรียง -------------//
function editsort( id )
{
	var num = $( '#sort_number'+id ).val();
	$.ajax({
		type:"GET",
		url:"usersort/depupdate/"+id+'/'+num,
		data:"", 
		cache: false,     
		success:function( result ){				   			   
		   	location.reload();		  
		}
	});
}

//========== update special =============//
function update_specialAll( y, m, field, q )
{	
	var v_cid = [];
	$("input[name='cid[]']").each(function(i){
		v_cid[i] = $(this).val();
	});

	var v_salary_id1 = [];
	$("input[name='salary_id1[]']").each(function(i){
		v_salary_id1[i] = $(this).val();
	});

	var v_type1 = [];
	$("input[name='type1[]']").each(function(i){
		v_type1[i] = $(this).val();
	});

	var v_pay1 = [];
	$("input[name='paysp1[]']").each(function(i){
		v_pay1[i] = $(this).val();
	});

	var dataSet={ cid: v_cid, salary_id: v_salary_id1, type: v_type1, paysp: v_pay1, y:y, m:m, field:field }; 
	$.post('add_special1/update_special',dataSet,function(data){  
		//console.log(data);
		location.reload();	
	}); 

 	/*for( i; i <= $('input[name="salary_id1[]"]').length-1; i++ )
    {    		
    	if( $('#paysp1'+i).val() == '' ){
    		var paysp1 = 0;
    	}else{
    		var paysp1 = $('#paysp1'+i).val();
    	}      
		
		r++;
		$.ajax({
			type:"GET",
			url:"add_special1/update_special/"+$('#cid1'+i).val()+"/"+y+"/"+m+"/"+$('#salary_id1'+i).val()+"/"+paysp1+"/"+field+"/"+$('#type1'+i).val(),
			data:"", 
			cache: false,     
			success:function( result ){	
				console.log(result);	 
			}
		});    
    }  
    if( r > 0){
		alert(r);
    	location.reload();	
    }*/
}
function update_specialAll_2( y, m )
{	
	var v_cid = [];
	$("input[name='cid1[]']").each(function(i){
		v_cid[i] = $(this).val();
	});

	var v_salary_id1 = [];
	$("input[name='salary_id1[]']").each(function(i){
		v_salary_id1[i] = $(this).val();
	});

	var v_type1 = [];
	$("input[name='type1[]']").each(function(i){
		v_type1[i] = $(this).val();
	});

	var v_ptssp1 = [];
	$("input[name='ptssp1[]']").each(function(i){
		v_ptssp1[i] = $(this).val();
	});

	var v_otsp1 = [];
	$("input[name='otsp1[]']").each(function(i){
		v_otsp1[i] = $(this).val();
	});

	var v_ch8sp1 = [];
	$("input[name='ch8sp1[]']").each(function(i){
		v_ch8sp1[i] = $(this).val();
	});

	var v_ch11sp1 = [];
	$("input[name='ch11sp1[]']").each(function(i){
		v_ch11sp1[i] = $(this).val();
	});

	var v_no_vsp1 = [];
	$("input[name='no_vsp1[]']").each(function(i){
		v_no_vsp1[i] = $(this).val();
	});

	var v_outpcusp1 = [];
	$("input[name='outpcusp1[]']").each(function(i){
		v_outpcusp1[i] = $(this).val();
	});

	var dataSet={ cid: v_cid, salary_id: v_salary_id1, type: v_type1, ptssp1: v_ptssp1, otsp1:v_otsp1, ch8sp1:v_ch8sp1, ch11sp1:v_ch11sp1, no_vsp1:v_no_vsp1, outpcusp1:v_outpcusp1, y:y, m:m }; 
	$.post('add_special1/update_special_all',dataSet,function(data){  
		//console.log(data);
		location.reload();	
	}); 

	/*var i=0;
	var r=0;	
 	for( i; i <= $('input[name="salary_id1[]"]').length-1; i++ )
    {  
		r++;  

    	if( $('#ptssp1'+i).val() == '' ){
    		var ptssp1 = 0;
    	}else{ var ptssp1 = $('#ptssp1'+i).val(); }  

    	if( $('#otsp1'+i).val() == '' ){
    		var otsp1 = 0;
    	}else{ var otsp1 = $('#otsp1'+i).val(); }  

    	if( $('#ch8sp1'+i).val() == '' ){
    		var ch8sp1 = 0;
    	}else{ var ch8sp1 = $('#ch8sp1'+i).val(); }  

    	if( $('#no_vsp1'+i).val() == '' ){
    		var no_vsp1 = 0;
    	}else{ var no_vsp1 = $('#no_vsp1'+i).val(); }  

    	if( $('#outpcusp1'+i).val() == '' ){
    		var outpcusp1 = 0;
    	}else{ var outpcusp1 = $('#outpcusp1'+i).val(); }      	

	    $.ajax({
			type:"GET",
			url:"add_special1/update_special_all/"+$('#cid1'+i).val()+"/"+y+"/"+m+"/"+$('#salary_id1'+i).val()+"/"+ptssp1+"/"+otsp1+"/"+ch8sp1+"/"+no_vsp1+"/"+outpcusp1+"/"+$('#type1'+i).val(),
			data:"", 
			cache: false,     
			success:function( result ){	  
				//console.log(result);						   			   			   
			}
	    });
    }  
    if( r > 0){
    	location.reload();	
    }*/   
}

//====== from Model Acc 1 =======//
 function ModalAcc( cid, pname, fname, lname )
 {
 	$( '#ModalAccTitle' ).html( cid +' '+ pname + fname +' '+ lname );
	$( '#fromAcc' ).empty();
	$( '#fromAcc' ).html('');	
		$.ajax({
		type:"GET",
		url:"fromAcc/"+cid,
		data:"", 
		cache: false,     
		success:function( result ){				   			   
		   $( '#fromAcc' ).html( result );
		   $( '#cidAcc' ).val( cid );
		}
    });
 }   
 //====== from Model Salary 1 =======//
 function ModalSalary1( cid, pname, fname, lname )
 {
 	$( '#ModalSalaryTitle1' ).html( cid +' '+ pname + fname +' '+ lname );
	$( '#fromSalary1' ).empty();
	$( '#fromSalary1' ).html('');	
		$.ajax({
		type:"GET",
		url:"fromSalary1/"+cid,
		data:"", 
		cache: false,     
		success:function( result ){				   			   
		   $( '#fromSalary1' ).html( result );
		   $( '#cidSalary1' ).val( cid );
		}
    });
 }  
 //====== from Model Salary_insert 1 =======//
 function Modalsalary_insert1( cid, pname, fname, lname, bank, bank_acc_id, bank_acc, salary, salary_other, salary_sso, salary_cpk, salary_cprt, tax_id )
 {
 	$( '#Modalsalary_insertTitle1' ).html( cid +' '+ pname + fname +' '+ lname );
	$( '#fromsalary_insert1' ).empty();
	$( '#fromsalary_insert1' ).html('');	
		$.ajax({
		type:"GET",
		url:"fromsalary_insert1/"+cid,
		data:"", 
		cache: false,     
		success:function( result ){				   			   
		   $( '#fromsalary_insert1' ).html( result );
		   $( '#cidsalary_insert1' ).val( cid );
		   $( '#banksalary_insert1' ).val( bank );
		   $( '#bank_acc_idsalary_insert1' ).val( bank_acc_id );
		   $( '#bank_accsalary_insert1' ).val( bank_acc );

		   $( '#salarysalary_insert1' ).val( salary );
		   $( '#salary_othersalary_insert1' ).val( salary_other );
		   $( '#salary_ssosalary_insert1' ).val( salary_sso );
		   $( '#salary_cpksalary_insert1' ).val( salary_cpk );
		   $( '#salary_cprtsalary_insert1' ).val( salary_cprt );
		   $( '#tax_idsalary_insert1' ).val( tax_id );
		}
    });
 }  

//====== from Model TAX 1 =======//
 function ModalTax1( cid, pname, fname, lname )
 {
 	$( '#ModalTaxTitle1' ).html( cid +' '+ pname + fname +' '+ lname );
	$( '#fromTax1' ).empty();
	$( '#fromTax1' ).html('');	
	$.ajax({
		type:"GET",
		url:"fromTax/"+cid+'/null',
		data:"", 
		cache: false,     
		success:function( result ){				   			   
		   $( '#fromTax1' ).html( result );		  
		}
    });
 }   
//====== Update from Model TAX 1 =======//
 function updateTax1( cid )
 {
 	var i=0;	
 	for(i;i<=$('input[name="taxTax1[]"]').length-1;i++)
    {        	
	    $.ajax({
			type:"POST",
			url:"updateTax/"+cid+'/'+$('#orderdate1'+i).val()+'/'+$('#taxTax1'+i).val()+'/'+$('#specialTax1'+i).val()+'/'+$('#ptsTax1'+i).val()+'/'+$('#otTax1'+i).val(),
			data:"", 
			cache: false,     
			success:function( result ){						   			   
			   $.ajax({
					type:"GET",
					url:"fromTax/"+cid+'/null',
					data:"", 
					cache: false,     
					success:function( result ){				   			   
					   $( '#fromTax1' ).html( result );		  
					}
			    }); 
			}
	    });
    }      	
 }




//====== from Model Acc 2 =======//
  function ModalAcc2( cid, pname, fname, lname )
 {
 	$( '#ModalAccTitle2' ).html( cid +' '+ pname + fname +' '+ lname );
	$( '#fromAcc2' ).empty();
	$( '#fromAcc2' ).html('');	
		$.ajax({
		type:"GET",
		url:"fromAcc2/"+cid,
		data:"", 
		cache: false,     
		success:function( result ){				   			   
		   $( '#fromAcc2' ).html( result );
		   $( '#cidAcc2' ).val( cid );
		}
    });
 }   
 //====== from Model Salary 2 =======//
 function ModalSalary2( cid, pname, fname, lname )
 {
 	$( '#ModalSalaryTitle2' ).html( cid +' '+ pname + fname +' '+ lname );
	$( '#fromSalary2' ).empty();
	$( '#fromSalary2' ).html('');	
		$.ajax({
		type:"GET",
		url:"fromSalary2/"+cid,
		data:"", 
		cache: false,     
		success:function( result ){				   			   
		   $( '#fromSalary2' ).html( result );
		   $( '#cidSalary2' ).val( cid );
		}
    });
 }  
//====== from Model Salary_insert 2 =======//
 function Modalsalary_insert2( cid, pname, fname, lname, bank, bank_acc_id, bank_acc, salary, r_c, special, son, kbk, tax, tax_id )
 {
 	$( '#Modalsalary_insertTitle2' ).html( cid +' '+ pname + fname +' '+ lname );
	$( '#fromsalary_insert2' ).empty();
	$( '#fromsalary_insert2' ).html('');	
		$.ajax({
		type:"GET",
		url:"fromsalary_insert2/"+cid,
		data:"", 
		cache: false,     
		success:function( result ){				   			   
		   $( '#fromsalary_insert2' ).html( result );
		   $( '#cidsalary_insert2' ).val( cid );

		   $( '#banksalary_insert2' ).val( bank );
		   $( '#bank_acc_idsalary_insert2' ).val( bank_acc_id );
		   $( '#bank_accsalary_insert2' ).val( bank_acc );
		   $( '#salarysalary_insert2' ).val( salary );
		   $( '#r_csalary_insert2' ).val( r_c );
		   $( '#specialsalary_insert2' ).val( special );
		   $( '#sonsalary_insert2' ).val( son );
		   $( '#kbksalary_insert2' ).val( kbk );
		   $( '#taxsalary_insert2' ).val( tax );
		   $( '#tax_idsalary_insert2' ).val( tax_id );		   
		}
    });
 } 

 //====== from Model TAX 2 =======//
 function ModalTax2( cid, pname, fname, lname )
 {
 	$( '#ModalTaxTitle2' ).html( cid +' '+ pname + fname +' '+ lname );
	$( '#fromTax2' ).empty();
	$( '#fromTax2' ).html('');	
	$.ajax({
		type:"GET",
		url:"fromTax/"+cid+'/null',
		data:"", 
		cache: false,     
		success:function( result ){				   			   
		   $( '#fromTax2' ).html( result );		  
		}
    });
 }   
//====== Update from Model TAX 2 =======//
 function updateTax2( cid )
 {	
 	var i=0;	
 	for(i;i<=$('input[name="taxTax2[]"]').length-1;i++)
    {        	
	    $.ajax({
			type:"POST",
			url:"updateTax/"+cid+'/'+$('#orderdate2'+i).val()+'/'+$('#taxTax2'+i).val()+'/'+$('#specialTax2'+i).val()+'/'+$('#rotherTax2'+i).val()+'/'+$('#rptTax2'+i).val(),
			data:"", 
			cache: false,     
			success:function( result ){						   			   
			   $.ajax({
					type:"GET",
					url:"fromTax/"+cid+'/null',
					data:"", 
					cache: false,     
					success:function( result ){				   			   
					   $( '#fromTax2' ).html( result );		  
					}
			    });
			}
	    });
    }      	
 }




 //====== from Model Acc 3 =======//
  function ModalAcc3( cid, pname, fname, lname )
 {
 	$( '#ModalAccTitle3' ).html( cid +' '+ pname + fname +' '+ lname );
	$( '#fromAcc3' ).empty();
	$( '#fromAcc3' ).html('');	
		$.ajax({
		type:"GET",
		url:"fromAcc3/"+cid,
		data:"", 
		cache: false,     
		success:function( result ){				   			   
		   $( '#fromAcc3' ).html( result );
		   $( '#cidAcc3' ).val( cid );
		}
    });
 }     
//====== from Model Salary 3 =======//
 function ModalSalary3( cid, pname, fname, lname )
 {
 	$( '#ModalSalaryTitle3' ).html( cid +' '+ pname + fname +' '+ lname );
	$( '#fromSalary3' ).empty();
	$( '#fromSalary3' ).html('');	
		$.ajax({
		type:"GET",
		url:"fromSalary3/"+cid,
		data:"", 
		cache: false,     
		success:function( result ){				   			   
		   $( '#fromSalary3' ).html( result );
		   $( '#cidSalary3' ).val( cid );
		}
    });
 }  
//====== from Model Salary_insert 3 =======//
 function Modalsalary_insert3( cid, pname, fname, lname, bank, bank_acc_id, bank_acc, salary, r_c, special, son, kbk, tax, tax_id )
 {
 	$( '#Modalsalary_insertTitle3' ).html( cid +' '+ pname + fname +' '+ lname );
	$( '#fromsalary_insert3' ).empty();
	$( '#fromsalary_insert3' ).html('');	
		$.ajax({
		type:"GET",
		url:"fromsalary_insert3/"+cid,
		data:"", 
		cache: false,     
		success:function( result ){				   			   
		   $( '#fromsalary_insert3' ).html( result );
		   $( '#cidsalary_insert3' ).val( cid );

		   $( '#banksalary_insert3' ).val( bank );
		   $( '#bank_acc_idsalary_insert3' ).val( bank_acc_id );
		   $( '#bank_accsalary_insert3' ).val( bank_acc );
		   $( '#salarysalary_insert3' ).val( salary );
		   $( '#r_csalary_insert3' ).val( r_c );
		   $( '#specialsalary_insert3' ).val( special );
		   $( '#sonsalary_insert3' ).val( son );
		   $( '#kbksalary_insert3' ).val( kbk );
		   $( '#taxsalary_insert3' ).val( tax );
		   $( '#tax_idsalary_insert3' ).val( tax_id );	
		}
    });
 } 

 //====== from Model TAX 3 =======//
 function ModalTax3( cid, pname, fname, lname )
 {
 	$( '#ModalTaxTitle3' ).html( cid +' '+ pname + fname +' '+ lname );
	$( '#fromTax3' ).empty();
	$( '#fromTax3' ).html('');	
	$.ajax({
		type:"GET",
		url:"fromTax/"+cid+'/null',
		data:"", 
		cache: false,     
		success:function( result ){				   			   
		   $( '#fromTax3' ).html( result );		  
		}
    });
 }   
//====== Update from Model TAX 3 =======//
 function updateTax3( cid )
 {	
 	var i=0;	
 	for(i;i<=$('input[name="taxTax3[]"]').length-1;i++)
    {        	
	    $.ajax({
			type:"POST",
			url:"updateTax/"+cid+'/'+$('#orderdate3'+i).val()+'/'+$('#taxTax3'+i).val()+'/'+$('#specialTax3'+i).val()+'/'+$('#rotherTax3'+i).val()+'/'+$('#rptTax3'+i).val(),
			data:"", 
			cache: false,     
			success:function( result ){						   			   
			   $.ajax({
					type:"GET",
					url:"fromTax/"+cid+'/null',
					data:"", 
					cache: false,     
					success:function( result ){				   			   
					   $( '#fromTax3' ).html( result );		  
					}
			    });
			}
	    });
    }      	
 }




 //====== from Model Acc 4 =======//
  function ModalAcc4( cid, pname, fname, lname )
 {
 	$( '#ModalAccTitle4' ).html( cid +' '+ pname + fname +' '+ lname );
	$( '#fromAcc4' ).empty();
	$( '#fromAcc4' ).html('');	
		$.ajax({
		type:"GET",
		url:"fromAcc4/"+cid,
		data:"", 
		cache: false,     
		success:function( result ){				   			   
		   $( '#fromAcc4' ).html( result );
		   $( '#cidAcc4' ).val( cid );
		}
    });
 }     
  //====== from Model Salary 4 =======//
 function ModalSalary4( cid, pname, fname, lname )
 {
 	$( '#ModalSalaryTitle4' ).html( cid +' '+ pname + fname +' '+ lname );
	$( '#fromSalary4' ).empty();
	$( '#fromSalary4' ).html('');	
		$.ajax({
		type:"GET",
		url:"fromSalary4/"+cid,
		data:"", 
		cache: false,     
		success:function( result ){				   			   
		   $( '#fromSalary4' ).html( result );
		   $( '#cidSalary4' ).val( cid );
		}
    });
 }  
  //====== from Model Salary_insert 4 =======//
 function Modalsalary_insert4( cid, pname, fname, lname, bank, bank_acc_id, bank_acc, salary, salary_other, salary_sso, salary_cpk, salary_cprt, tax_id )
 {
 	$( '#Modalsalary_insertTitle4' ).html( cid +' '+ pname + fname +' '+ lname );
	$( '#fromsalary_insert4' ).empty();
	$( '#fromsalary_insert4' ).html('');	
		$.ajax({
		type:"GET",
		url:"fromsalary_insert4/"+cid,
		data:"", 
		cache: false,     
		success:function( result ){				   			   
		   $( '#fromsalary_insert4' ).html( result );
		   $( '#cidsalary_insert4' ).val( cid );
		   $( '#banksalary_insert4' ).val( bank );
		   $( '#bank_acc_idsalary_insert4' ).val( bank_acc_id );
		   $( '#bank_accsalary_insert4' ).val( bank_acc );

		   $( '#salarysalary_insert4' ).val( salary );
		   $( '#salary_othersalary_insert4' ).val( salary_other );
		   $( '#salary_ssosalary_insert4' ).val( salary_sso );
		   $( '#salary_cpksalary_insert4' ).val( salary_cpk );
		   $( '#salary_cprtsalary_insert4' ).val( salary_cprt );
		   $( '#tax_idsalary_insert4' ).val( tax_id );
		}
    });
 } 






 //====== from Model Acc 5 =======//
  function ModalAcc5( cid, pname, fname, lname )
 {
 	$( '#ModalAccTitle5' ).html( cid +' '+ pname + fname +' '+ lname );
	$( '#fromAcc5' ).empty();
	$( '#fromAcc5' ).html('');	
		$.ajax({
		type:"GET",
		url:"fromAcc5/"+cid,
		data:"", 
		cache: false,     
		success:function( result ){				   			   
		   $( '#fromAcc5' ).html( result );
		   $( '#cidAcc5' ).val( cid );
		}
    });
 }     
  //====== from Model Salary 5 =======//
 function ModalSalary5( cid, pname, fname, lname )
 {
 	$( '#ModalSalaryTitle5' ).html( cid +' '+ pname + fname +' '+ lname );
	$( '#fromSalary5' ).empty();
	$( '#fromSalary5' ).html('');	
		$.ajax({
		type:"GET",
		url:"fromSalary5/"+cid,
		data:"", 
		cache: false,     
		success:function( result ){				   			   
		   $( '#fromSalary5' ).html( result );
		   $( '#cidSalary5' ).val( cid );
		}
    });
 }  
  //====== from Model Salary_insert 5 =======//
 function Modalsalary_insert5( cid, pname, fname, lname, bank, bank_acc_id, bank_acc, salary, salary_other, salary_sso, salary_cpk, tax_id )
 {
 	$( '#Modalsalary_insertTitle5' ).html( cid +' '+ pname + fname +' '+ lname );
	$( '#fromsalary_insert5' ).empty();
	$( '#fromsalary_insert5' ).html('');	
		$.ajax({
		type:"GET",
		url:"fromsalary_insert5/"+cid,
		data:"", 
		cache: false,     
		success:function( result ){				   			   
		   $( '#fromsalary_insert5' ).html( result );
		   $( '#cidsalary_insert5' ).val( cid );
		   $( '#banksalary_insert5' ).val( bank );
		   $( '#bank_acc_idsalary_insert5' ).val( bank_acc_id );
		   $( '#bank_accsalary_insert5' ).val( bank_acc );

		   $( '#salarysalary_insert5' ).val( salary );
		   $( '#salary_othersalary_insert5' ).val( salary_other );
		   $( '#salary_ssosalary_insert5' ).val( salary_sso );
		   $( '#salary_cpksalary_insert5' ).val( salary_cpk );
		   $( '#tax_idsalary_insert5' ).val( tax_id );
		}
    });
 } 