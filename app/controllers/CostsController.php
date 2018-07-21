<?php

	class CostsController extends BaseController {

		//================== Unit Cost ======================//
		/**
		 * function name : unitcosts
		 * view page home s_unit_costs
		 * 
		 * get
		*/
		public function unitcosts()
		{	
			if ( Session::get('level') != '' )
			{		
				$data = DB::table( 's_unit_costs' )		       
		         ->orderBy( 'unit_id', 'asc')
		         ->paginate( 20 );	

			    return View::make( 'unitcosts.unithome',  array( 'data' => $data ) );			    		 		
			}	
			else
			{
				//return login
	    		return View::make( 'login.index' );	
			}				
		}

		/**
		 * function name : unitcosts_create
		 * view page create unitcosts
		 * 
		 * get
		*/
	    public function unitcosts_create()
	    {   	
	    	if ( Session::get('level') != '' )
	    	{      	    		
		        return View::make( 'unitcosts.unitcreate' );
	    	}
	    	else
	    	{
	    		//return login
	    		return View::make( 'login.index' );	
	    	} 	
	    }

	     /**
		 * function name : post_new_unitcosts
		 * reciep data post form create
		 * create new s_unit_costs
		 * post
		*/
	    public function post_new_unitcosts()
	    {
	    	//get user details
		    $unitcode  		= Input::get( 'unitcode' );
		    $unitname 		= Input::get( 'unitname' );

			$rules = array(
				'unitcode'    => 'required',
				'unitname'    => 'required'
			);
			$messages = array(
				'unitcode.required'    => '*** กรุณากรอกรหัสหน่วยต้นทุน ***',
				'unitname.required'    => '*** กรุณากรอกชื่อหน่วยต้นทุน ***'
			);
	  
		    $validator = Validator::make( Input::all(), $rules, $messages );
		    //check if the form is valid
		    if ( $validator->fails() )
		    {			
		        $messages = $validator->messages();			
				return Redirect::to( 'unitcosts/create' )->withErrors( $validator );
		    }
		    else
		    {		    	
            	//create new user
	            $users = DB::insert( 'insert into s_unit_costs ( unitcode, unitname ) values ( ?, ? )', 
	            		array( 		            			  
	            			  $unitcode,
                              $unitname 
	            	     ));          

	            if( $users )
	            {
	            	return Redirect::to( 'unitcosts' )->with( 'success_message', 'เพิ่มข้อมูลเรียบร้อยแล้ว' );    
	            }
	            else
	            {
	                return Redirect::to( 'unitcosts' )->with( 'error_message', 'ไม่สามารถเพิ่มข้อมูลได้ กรุณาแจ้งผู้ดูแลระบบ' );   
		        }	              	
	        }
	    }

	     /**
	    * function name : unitcosts_edit
	    * edit data s_unit_costs
	    * get
	    */
	    public function unitcosts_edit( $id=null ) 
	    {
	    	if ( Session::get('level') != '' )
	    	{
	    		$data =  DB::table( 's_unit_costs' )				               
				        ->where( 'unit_id', '=', $id )
				        ->first();  				

			    return View::make(
			        'unitcosts.unitedit', 
			        array(
			            'data'      => $data	          		                
			            )
			    );
	    	}
	    	else
	    	{
	    		return View::make( 'login.index' );	
	    	}      
	    }

	    /**
	    * function name : post_edit_unitcosts
	    * edit data s_unit_costs
	    * post
	    */
	    public function post_edit_unitcosts( $id )
	    {
	    	//get user details
		    $unitcode  		= Input::get( 'unitcode' );
		    $unitname 		= Input::get( 'unitname' );

			$rules = array(
				'unitcode'    => 'required',
				'unitname'    => 'required'
			);
			$messages = array(
				'unitcode.required'    => '*** กรุณากรอกรหัสหน่วยต้นทุน ***',
				'unitname.required'    => '*** กรุณากรอกชื่อหน่วยต้นทุน ***'
			);
	  
		    $validator = Validator::make( Input::all(), $rules, $messages );
		    //check if the form is valid
		    if ( $validator->fails() )
		    {			
		        $messages = $validator->messages();			
				return Redirect::to( 'unitcosts/edit/'.$id )->withErrors( $validator );
		    }
		    else
		    {
		    	$data = array(
		            'unitcode' 	 => $unitcode,
		            'unitname' 		 => $unitname	          	           		            	                       
		        );  
		      
		        //update unitcosts details
		        $result = DB::table( 's_unit_costs' )->where( 'unit_id', '=', $id )->update( $data );	        
		        if( $result )
		        {
		        	return Redirect::to( 'unitcosts' )->with( 'success_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว' ); 
		        }
		        else
		        {
		        	return Redirect::to( 'unitcosts' )->with( 'error_message', 'ไม่สามารถแก้ไขข้อมูลได้ กรุณาแจ้งผู้ดูแลระบบ' ); 
		        }	 
		    }		                   
	    }

	    /**
	    * function name : unitcosts_post_search
	    * search data s_unit_costs
	    * post
	    */
	    public function unitcosts_post_search()
	    {   		  
		   if ( Session::get('level') != '' )
			{
				$search  = Input::get( 'search' );	    		    

				$data = DB::table( 's_unit_costs' )	             
		         ->where( 'unitcode', 'like', "%$search%" )
		         ->orWhere( 'unitname', 'like', "%$search%" )	 	              
		         ->orderBy( 'unit_id', 'asc')
		         ->paginate( 20 );	 		    
		     
				//view page create
			    return View::make( 'unitcosts.unithome',  array( 'data' => $data ) );	
			}	
			else
			{
				//return login
	    		return View::make( 'login.index' );	
			}	
	    }

	    /**
	    * function name : unitcosts_delete
	    * edit data s_unit_costs
	    * get
	    */
	    public function unitcosts_delete( $id ) 
	    {
	    	if ( Session::get('level') != '' )
	    	{    		
	            $result = UnitCosts::where( 'unit_id', $id )->delete();

			   if( $result )
		        {
		        	return Redirect::to( 'unitcosts' )->with( 'success_message', 'ลบข้อมูลเรียบร้อยแล้ว' ); 
		        }
		        else
		        {
		        	return Redirect::to( 'unitcosts' )->with( 'error_message', 'ไม่สามารถลบข้อมูลได้ กรุณาแจ้งผู้ดูแลระบบ' ); 
		        }	   
	    	}
	    	else
	    	{
	    		return View::make( 'login.index' );	
	    	}      
	    }


	    //==========================menu 2=============================//

	    /**
		 * function name : _autocomplete
		 * view page create autocomplete
		 * add data to select option
		 * get
		*/
	    public function _autocomplete( )
	    {		    		    	  	
			$term = Input::get('term');			
			
			$results = array();
			
			$datageneral = DB::table('n_datageneral')
		               ->where('cid', 'LIKE', '%'.$term.'%')
		               ->orWhere('fname', 'LIKE', '%'.$term.'%')
					   ->orWhere('lname', 'LIKE', '%'.$term.'%')		              
		               ->get(array( DB::raw('CONCAT(cid," ", pname, "", fname, " ", lname) as value') ));  		
		
			foreach ($datageneral as $query)
			{
			    $results[] = [ 'value' => $query->value ];
			}
			return Response::json($results);
		}

	    /**
	    * function name : unitcosts_addemp
	    * edit data s_unit_costs_emp
	    * get
	    */
	    public function unitcosts_addemp(  )	   
	    {
	    	if ( Session::get('level') != '' )
	    	{   
	    		$unitcosts = DB::table('s_unit_costs')->get();	    		
		        return View::make( 'unitcosts.addemp', 
		        	array( 
		        		'unitcosts'   => $unitcosts		        		        		
		        	) 
		        );
	    	}
	    	else
	    	{
	    		//return login
	    		return View::make( 'login.index' );	
	    	} 
	    }

	    /**
	    * function name : viewempunit
	    * show data
	    * get
	    */
	    public function viewempunit( $id )
	    {
	    	$data = DB::Select( 'select s.in_id, s.cid, concat( n.pname, "", n.fname, " ", n.lname ) as name, s.cal, s.unitcode, s.unitname from s_unit_costs_emp s inner join n_datageneral n on n.cid=s.cid inner join s_unit_costs u on u.unitcode=s.unitcode where s.cid ='.$id.' order by s.cal desc' );
	    	 
	    	if( count( $data ) > 0 )
	    	{ 		    	 
	    	 	$t ='<table class="responsive">';
	    	 	$t .='<tr>';
	    	 	$t .='<th width="140">รหัสหน่วยต้นทุน</th> <th width="270">หน่วยต้นทุน</th> <th width="180">เปอร์เซ็นต่อหนวยต้นทุน</th> <th width="40">#</th>';
	    	 	$t .='</tr>';
	    	 	foreach ($data as $k) {
	    	 		$t .='<tr>';
	    	 		$t .=' <td>'.$k->unitcode.'</td>';
	    	 		$t .=' <td>'.$k->unitname.'</td>';
	    	 		$t .=' <td>'.$k->cal.'</td>';
	    	 		$t .=' <td>';
	    	 		$t .=' <a title="ลบ" onclick="delemp('.$k->in_id.','.$id.','.$k->cid.')" href="#"> <i class="fi-x small"></i> </a> ';
	    	 		$t .=' </td>';
	    	 		$t .='</tr>';
	    	 	}
	    	 	$t .='</table>';
    	 	}
    	 	else
    	 	{
    	 		$t='-ไม่มีข้อมูล';
    	 	}
    	 	return $t;
	    }

	    /**
	    * function name : addemp
	    * add data to s_unit_costs_emp
	    * get
	    */
	    public function addemp( $unitid, $cid, $cal )
	    {
	    	 $d = DB::Select( 'select * from s_unit_costs where unit_id='.$unitid );

	    	 foreach ($d as $a) {
	    	 	$unitcode = $a->unitcode;
	    	 	$unitname = $a->unitname;
	    	 }	

	    	 $chk = DB::Select( ' select * from s_unit_costs_emp where cid='.$cid.' and unitcode="'.$unitcode.'" ' );    	
	    	 if( count( $chk ) > 0 )
	    	 {
	    	 	return 'NO2';
	    	 }

	    	 $result = DB::insert( 'insert into s_unit_costs_emp ( cid, unitcode, unitname, cal ) values ( ?, ?, ?, ? )', 
	            		array( 	
	            			  $cid,	            			  
	            			  $unitcode,
                              $unitname,
                              $cal 
	            	     )); 

	    	 if( $result )
	    	 {
	    	 	$data = DB::Select( 'select u.unit_id, s.in_id, s.cid, concat( n.pname, "", n.fname, " ", n.lname ) as name, s.cal, s.unitcode, s.unitname from s_unit_costs_emp s inner join n_datageneral n on n.cid=s.cid inner join s_unit_costs u on u.unitcode=s.unitcode where s.cid='.$cid.' order by s.cal desc' );
	    	 		    	 	    	 
	    	 	$t ='<table class="responsive">';
	    	 	$t .='<tr>';
	    	 	$t .='<th width="140">รหัสหน่วยต้นทุน</th> <th width="270">หน่วยต้นทุน</th> <th width="180">เปอร์เซ็นต่อหนวยต้นทุน</th> <th width="40">#</th>';
	    	 	$t .='</tr>';
	    	 	foreach ($data as $k) {
	    	 		$t .='<tr>';
	    	 		$t .=' <td>'.$k->unitcode.'</td>';
	    	 		$t .=' <td>'.$k->unitname.'</td>';
	    	 		$t .=' <td>'.$k->cal.'</td>';
	    	 		$t .=' <td>';
	    	 		$t .=' <a title="ลบ" onclick="delemp('.$k->in_id.','.$unitid.','.$k->cid.')" href="#"> <i class="fi-x small"></i> </a> ';
	    	 		$t .=' </td>';
	    	 		$t .='</tr>';
	    	 	}
	    	 	$t .='</table>';
	    	 	return $t;
	    	 }
	    	 else
	    	 {
	    	 	return 'NO1';
	    	 }
	    }

	    /**
	    * function name : delemp
	    * delete data from s_unit_costs_emp
	    * get
	    */
	    public function delemp( $id, $unit_id, $cid ) 
	    {
	    	if ( Session::get('level') != '' )
	    	{    		
	            $result = UnitCostsEmp::where( 'in_id', $id )->delete();

			   if( $result )
		        {
		        	$data = DB::Select( 'select u.unit_id, s.in_id, s.cid, concat( n.pname, "", n.fname, " ", n.lname ) as name, s.cal, s.unitcode, s.unitname from s_unit_costs_emp s inner join n_datageneral n on n.cid=s.cid inner join s_unit_costs u on u.unitcode=s.unitcode where s.cid='.$cid.' order by s.cal desc' );
	    	 
			    	if( count( $data ) > 0 )
			    	{ 		    	 
			    	 	$t ='<table class="responsive">';
			    	 	$t .='<tr>';
			    	 	$t .='<th width="140">รหัสหน่วยต้นทุน</th> <th width="270">หน่วยต้นทุน</th> <th width="180">เปอร์เซ็นต่อหนวยต้นทุน</th> <th width="40">#</th>';
			    	 	$t .='</tr>';
			    	 	foreach ($data as $k) {
			    	 		$t .='<tr>';
			    	 		$t .=' <td>'.$k->unitcode.'</td>';
	    	 				$t .=' <td>'.$k->unitname.'</td>';
			    	 		$t .=' <td>'.$k->cal.'</td>';
			    	 		$t .=' <td>';
			    	 		$t .=' <a title="ลบ" onclick="delemp('.$k->in_id.','.$unit_id.','.$k->cid.')" href="#"> <i class="fi-x small"></i> </a> ';
			    	 		$t .=' </td>';
			    	 		$t .='</tr>';
			    	 	}
			    	 	$t .='</table>';
		    	 	}
		    	 	else
		    	 	{
		    	 		$t='-ไม่มีข้อมูล';
		    	 	}

		    	 	return $t;
		        }	           
	    	}
	    	else
	    	{
	    		return View::make( 'login.index' );	
	    	}      
	    }


	    //==========================menu 3=============================//

	    private function max_year()
		{
			return date("Y", strtotime( DB::table('s_salary_detail')->max('order_date') ));
		}

		private function get_range_ot()
		{
			$r = DB::Select( ' select * from s_unit_costs_range where name="OT" ');
			return $r;
		}

		private function get_range_sso()
		{
			$r = DB::Select( ' select * from s_unit_costs_range where name="ประกันสังคม" ' );
			return $r;
		}

		/**
	    * function name : unitcosts_money_home_month
	    * home report unit cost
	    * get
	    */
		public function unitcosts_money_home_month()
		{
			if( Session::get('level') != '' )
			{
				$y = DB::Select( ' select year(order_date) as year1 from s_salary_detail group by year(order_date) order by year(order_date) desc ' );	
				return View::make( 'unitcosts.money_home_month', array( 'data' => $y ) );
			}
			else
			{
				return View::make('login.index');
			} 
		}

		/**
	    * function name : unitcosts_money_month
	    * home report unit cost
	    * post
	    */
		public function unitcosts_money_month()
		{
			$y = Input::get('y_unit');	
			$m = Input::get('m_unit');	

			$mall = explode('-', $m);

			$m = $mall[0];
			
			//$mname = $mall[1];    	

	    	//-----Date ALL
	    	$date_start = $y.'-'.$m.'-01';
	    	$date_end   = $y.'-'.$m.'-31';	    	

	    	//------Date OT
	    	$mt = $mall[0];
	    	$mt = $mt+1;
	    	if($mt == 12){
	    		$mot = 1;
	    		$yot = $y+1;
	    	}else{
	    		$mot = $mt;
	    		$yot = $y;
	    	}

	    	$start_ot = $yot.'-'.$mot.'-01';
	    	$end_ot   = $yot.'-'.$mot.'-31';

	    	//-------Date SSO
	    	$mss = $mall[0];
	    	$mss=$mss-1;
	    	if($mss == 1){
	    		$msso = 12;
	    		$ysso = $y-1;
	    	}else{
	    		$msso = $mss;
	    		$ysso = $y;
	    	}

			$start_sso = $ysso.'-'.$msso.'-01';
    		$end_sso   = $ysso.'-'.$msso.'-31';
	    		

    		//-------sql ดึงหน่วยต้นทุน    		
	    		$sql  = ' select *';					
				$sql .= ' from s_unit_costs';								

				$result = DB::select( $sql );		

				$objPHPExcel = new PHPExcel();
				$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial'); 
			    $objPHPExcel->getActiveSheet()->setTitle('Sheet1');
				$objPHPExcel->setActiveSheetIndex(0);				

				//--------coloum name ชุด 1
				$objPHPExcel->getActiveSheet()->setCellValue('A1', 'รหัส');	
				$objPHPExcel->getActiveSheet()->mergeCells('A1:A2');
				$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(7);									
				$objPHPExcel->getActiveSheet()->setCellValue('B1', 'ลำดับ');	
				$objPHPExcel->getActiveSheet()->mergeCells('B1:B2');		
				$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(5);	
				$objPHPExcel->getActiveSheet()->setCellValue('C1', 'ชื่อ');
				$objPHPExcel->getActiveSheet()->mergeCells('C1:C2');
				$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);	
				$objPHPExcel->getActiveSheet()->setCellValue('D1', 'สกุล');
				$objPHPExcel->getActiveSheet()->mergeCells('D1:D2');
				$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);

				//-------แถว coloum name ชุด 1 bg color
				$objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getFill()->applyFromArray(
			            array(
			            'type'       => PHPExcel_Style_Fill::FILL_SOLID,
			            'startcolor' => array('rgb' => '60D0F2'),
			            'endcolor' => array('rgb' => '60D0F2')

			            )
			    );
			   
			   //------fix tab
			    $objPHPExcel->getActiveSheet()->freezePane('E1');			   			    

			    //------แถว รวมทั้งจำนวน(บาท) bg color
				$objPHPExcel->getActiveSheet()->setCellValue('E1',  'เดือน '. $m.' ปี '.($y+543).' รวมทั้งจำนวน(บาท)');
				$objPHPExcel->getActiveSheet()->mergeCells('E1:N1');				
				$objPHPExcel->getActiveSheet()->getStyle('E1:N1')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER));
				$objPHPExcel->getActiveSheet()->getStyle('E1:N1')->getFill()->applyFromArray(
			            array(
			            'type'       => PHPExcel_Style_Fill::FILL_SOLID,
			            'startcolor' => array('rgb' => 'D39BFF'),
			            'endcolor' => array('rgb' => 'D39BFF')

			            )
			    );

				//--------coloum name ชุด 2
				$objPHPExcel->getActiveSheet()->setCellValue('E2', '1.เงินเดือน+ค่าจ้างชั่วคราว');	
				$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
				$objPHPExcel->getActiveSheet()->getStyle('E')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1); 

				$objPHPExcel->getActiveSheet()->setCellValue('F2', '2.เงินประจำตำแหน่ง');	
				$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
				$objPHPExcel->getActiveSheet()->getStyle('F')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1); 

				$objPHPExcel->getActiveSheet()->setCellValue('G2', '3.ค่าล่วงเวลา(OT)');	
				$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(26);	
				$objPHPExcel->getActiveSheet()->getStyle('G')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1); 

				$objPHPExcel->getActiveSheet()->setCellValue('H2', '4.ไม่ทำเวชฯ');	
				$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(30);
				$objPHPExcel->getActiveSheet()->getStyle('H')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1); 

				$objPHPExcel->getActiveSheet()->setCellValue('I2', '5.ฉ.8');	
				$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(23);
				$objPHPExcel->getActiveSheet()->getStyle('I')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1); 

				$objPHPExcel->getActiveSheet()->setCellValue('J2', '6.เงินตอบแทนอื่น');	
				$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(23);
				$objPHPExcel->getActiveSheet()->getStyle('J')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1); 

				$objPHPExcel->getActiveSheet()->setCellValue('K2', '7.ค่ารักษาพยาบาล/ช่วยเหลือบุตร');	
				$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(23);
				$objPHPExcel->getActiveSheet()->getStyle('K')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1); 

				$objPHPExcel->getActiveSheet()->setCellValue('L2', '8.เดินทางไปราชการ');	
				$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(23);
				$objPHPExcel->getActiveSheet()->getStyle('L')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1); 

				$objPHPExcel->getActiveSheet()->setCellValue('M2', '9.อื่น ๆ');	
				$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(23);
				$objPHPExcel->getActiveSheet()->getStyle('M')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1); 				

				//------แถว coloum name bg color
				$objPHPExcel->getActiveSheet()->getStyle('E2:N2')->getFill()->applyFromArray(
			            array(
			            'type'       => PHPExcel_Style_Fill::FILL_SOLID,
			            'startcolor' => array('rgb' => 'FCC153'),
			            'endcolor' => array('rgb' => 'FCC153')

			            )
			    );

				//------แถว coloum name รวม bg color
				$objPHPExcel->getActiveSheet()->setCellValue('N2', 'รวม');	
				$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(20);	
				$objPHPExcel->getActiveSheet()->getStyle('N')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1); 						
				$objPHPExcel->getActiveSheet()->getStyle('N2')->getFill()->applyFromArray(
			            array(
			            'type'       => PHPExcel_Style_Fill::FILL_SOLID,
			            'startcolor' => array('rgb' => 'F6FC53'),
			            'endcolor' => array('rgb' => 'F6FC53')

			            )
			    );			   

				$all_sum_salary		=0;
				$all_sum_r_c		=0;
				$all_sum_ot 		=0;
				$all_sum_no_v 		=0;
				$all_sum_ch8 		=0;
				$all_sum_sp_other 	=0;
				$all_sum_son 		=0;
				$all_sum_u_travel 	=0;
				$all_sum_u_other 	=0;
				$all_sum_money1_all =0;

				$row = 0;
				$sum = 0;
				foreach ($result as $key) //for sql 1
				{	
					//--------- Code UnitCost
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (0, $row+3, $key->unitcode);
					$objPHPExcel->getActiveSheet()->getStyle('A'.($row+3))->getFill()->applyFromArray(
				            array(
				            'type'       => PHPExcel_Style_Fill::FILL_SOLID,
				            'startcolor' => array('rgb' => 'F6FC53'),
				            'endcolor' => array('rgb' => 'F6FC53')

				            )
				    );

					//--------- Name UnitCost
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (1, $row+3, $key->unitname);						
					$objPHPExcel->getActiveSheet()->getStyle('B'.($row+3).':N'.($row+3))->getFill()->applyFromArray(
				            array(
				            'type'       => PHPExcel_Style_Fill::FILL_SOLID,
				            'startcolor' => array('rgb' => 'FC8AE7'),
				            'endcolor' => array('rgb' => 'FC8AE7')

				            )
				    );

					//-------sql 2
					$sql2  = '  select s1.unitname, a.y, a.m, s1.cid, a.type, concat(n1.pname,"",n1.fname) as fname, n1.lname,';
					$sql2 .= '  sum(a.salary) as salary, sum(a.r_c) as r_c, ';					
					$sql2 .= '  sum(a.ot) as ot, sum(a.no_v) as no_v, ';
					$sql2 .= '  sum(a.ch8) as ch8, sum(a.sp_other) as sp_other, ';
					$sql2 .= '  sum(a.son) as son, sum(a.u_travel) as u_travel, ';
					$sql2 .= '  sum(a.u_other) as u_other ';
					$sql2 .= '  from s_unit_costs_emp s1 ';
					$sql2 .= '  left join ( ';
					$sql2 .= '  ( select cid,year(order_date) as y, month(order_date) as m, 1 as type, sum(salary+salary_other) as salary, 0 as r_c,';
					$sql2 .= '  (select sum(c.ot) as ot from s_salary_detail c where order_date between "'.$start_ot.'" and "'.$end_ot.'" and c.cid=s_salary_detail.cid) as ot, ';
					$sql2 .= '  sum(no_v) as no_v, sum(ch8) as ch8, ';
					$sql2 .= '  ((select sum(b.salary_sso) from s_salary_detail b where order_date between "'.$start_sso.'" and "'.$end_sso.'" and b.cid=s_salary_detail.cid)+sum(pts)) as sp_other,';
					$sql2 .= '  0 as son, sum(u_travel) as u_travel, sum(u_other) as u_other';
					$sql2 .= '  from s_salary_detail';
					$sql2 .= '  where order_date between "'.$date_start.'" and "'.$date_end.'" group by cid ) ';
					$sql2 .= '  union ';
					$sql2 .= '  ( select cid,year(order_date) as y, month(order_date) as m, 2 as type, sum(salary+special_m+game_sp) as salary, sum(r_c) as r_c,';
					$sql2 .= '  (select sum(c.ot) from s_salary_ocsc_detail c where order_date between "'.$start_ot.'" and "'.$end_ot.'" and c.cid=s_salary_ocsc_detail.cid) as ot,';
					$sql2 .= '  sum(no_v) as no_v, sum(ch8) as ch8,';
					$sql2 .= '  sum(save_h+p_other+pts2+r_pt+r_other) as sp_other, sum(son) as son, sum(u_travel) as u_travel, sum(u_other) as u_other ';
					$sql2 .= '  from s_salary_ocsc_detail';
					$sql2 .= '  where order_date between "'.$date_start.'" and "'.$date_end.'" group by cid )';
					$sql2 .= '  ) as a on a.cid = s1.cid ';
					$sql2 .= '  left join n_datageneral n1 on n1.cid=s1.cid';
					$sql2 .= '  where s1.unitname="'.$key->unitname.'" ';
					$sql2 .= '  group by s1.cid order by s1.unitcode asc,a.type desc,a.salary desc';					
					
					$result2 = DB::select( $sql2 );	
					$n 				=0;
					$sum_salary 	=0;
					$sum_r_c 		=0;
					$sum_ot 		=0;
					$sum_no_v 		=0;
					$sum_ch8 		=0;
					$sum_sp_other 	=0;
					$sum_son 		=0;
					$sum_u_travel 	=0;
					$sum_u_other 	=0;
					$sum_money1_all =0;
					//-------------------- Detail Start -------------------//
					foreach ( $result2 as $key2 ) //for sql 2
					{	
						$sum_money1=0;					
						$n++;
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (1, $row+4, $n);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (2, $row+4, $key2->fname);	
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (3, $row+4, $key2->lname);		
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (4, $row+4, $key2->salary);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (5, $row+4, $key2->r_c);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (6, $row+4, $key2->ot);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (7, $row+4, $key2->no_v);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (8, $row+4, $key2->ch8);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (9, $row+4, $key2->sp_other);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (10, $row+4, $key2->son);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (11, $row+4, $key2->u_travel);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (12, $row+4, $key2->u_other);
						//----colum รวมเงิน
						$sum_money1 = $key2->salary+$key2->r_c+$key2->ot+$key2->no_v+$key2->ch8+$key2->sp_other+$key2->son+$key2->u_travel+$key2->u_other;
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (13, $row+4, $sum_money1);

						$sum_salary 	= $sum_salary + $key2->salary;
						$sum_r_c 		= $sum_r_c + $key2->r_c;
						$sum_ot 		= $sum_ot + $key2->ot;
						$sum_no_v 		= $sum_no_v + $key2->no_v;
						$sum_ch8 		= $sum_ch8 + $key2->ch8;
						$sum_sp_other 	= $sum_sp_other + $key2->sp_other;
						$sum_son 		= $sum_son + $key2->son;
						$sum_u_travel 	= $sum_u_travel + $key2->u_travel;
						$sum_u_other 	= $sum_u_other + $key2->u_other;
						$sum_money1_all = $sum_money1_all + $sum_money1;

						$row = $row+1;
					}//end for sql 2
					//-------------------- Detail End -------------------//

					//----------------- รวมแต่ละ unitcosts Start -------------- //
					//-------color bg coloum ชุด 1
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (1, $row+4,$key->unitcode);	
					$objPHPExcel->getActiveSheet()->mergeCells('B'.($row+4).':D'.($row+4));				
					$objPHPExcel->getActiveSheet()->getStyle('B'.($row+4).':D'.($row+4))->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER));
					$objPHPExcel->getActiveSheet()->getStyle('B'.($row+4).':D'.($row+4))->getFill()->applyFromArray(
			            array(
			            'type'       => PHPExcel_Style_Fill::FILL_SOLID,
			            'startcolor' => array('rgb' => 'F6FC53'),
			            'endcolor' => array('rgb' => 'F6FC53')

			            )
			    	);	

					//-------color bg coloum ชุด 2  แถวรวมด้านล่าง
			    	$objPHPExcel->getActiveSheet()->getStyle('E'.($row+4).':N'.($row+4))->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER));
			    	$objPHPExcel->getActiveSheet()->getStyle('E'.($row+4).':N'.($row+4))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1); 	
					$objPHPExcel->getActiveSheet()->getStyle('E'.($row+4).':N'.($row+4))->getFill()->applyFromArray(
			            array(
			            'type'       => PHPExcel_Style_Fill::FILL_SOLID,
			            'startcolor' => array('rgb' => 'F6FC53'),
			            'endcolor' => array('rgb' => 'F6FC53')

			            )
			    	);	    	

					//-------SUM  coloum &  1.เงินเดือน+ค่าจ้างชั่วคราว
			    	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (4, $row+4, $sum_salary);													

			    	//-------SUM coloum &  2.เงินประจำตำแหน่ง
			    	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (5, $row+4, $sum_r_c);

			    	//-------SUM  coloum & 3.ค่าล่วงเวลา(OT)
			    	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (6, $row+4, $sum_ot);

			    	//-------SUM  coloum & 4.ไม่ทำเวชฯ
			    	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (7, $row+4, $sum_no_v);	

			    	//-------SUM  coloum & 5.ฉ.8
			    	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (8, $row+4, $sum_ch8);

			    	//-------SUM  coloum & 6.เงินตอบแทนอื่น
			    	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (9, $row+4, $sum_sp_other);	

			    	//-------SUM  coloum & 7.ค่ารักษาพยาบาล/ช่วยเหลือบุตร
			    	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (10, $row+4, $sum_son);	

			    	//-------SUM  coloum & 8.เดินทางไปราชการ
			    	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (11, $row+4, $sum_u_travel);	

			    	//-------SUM  coloum & 9.อื่น ๆ
			    	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (12, $row+4, $sum_u_other);	

			    	//-------SUM  coloum & 10.รวม
			    	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (13, $row+4, $sum_money1_all);				
					
			    	$row = $row+1;
			    	//----------------- รวมแต่ละ unitcosts END -------------- //
			    	$row++;	

			    	//--------------SUM ALL UNIT-------------//
			    	$all_sum_salary 	= $all_sum_salary + $sum_salary;
					$all_sum_r_c 		= $sum_r_c + $all_sum_r_c;
					$all_sum_ot 		= $all_sum_ot + $sum_ot;
					$all_sum_no_v 		= $all_sum_no_v + $sum_no_v;
					$all_sum_ch8 		= $all_sum_ch8 + $sum_ch8;
					$all_sum_sp_other 	= $all_sum_sp_other + $sum_sp_other;
					$all_sum_son 		= $all_sum_son + $sum_son;
					$all_sum_u_travel 	= $all_sum_u_travel + $sum_u_travel;
					$all_sum_u_other 	= $all_sum_u_other + $sum_u_other;
					$all_sum_money1_all = $all_sum_money1_all + $sum_money1_all;			    	
									
				}//end for sql 1

				//----------------- color bg coloum รวม All UnitCosts All Coloum ทั้งหทด ท้ายไฟล์ 1-------------- //
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (0, $row+4, "รวม");	
				$objPHPExcel->getActiveSheet()->mergeCells('A'.($row+4).':D'.($row+4));				
				$objPHPExcel->getActiveSheet()->getStyle('A'.($row+4).':D'.($row+4))->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER));
				$objPHPExcel->getActiveSheet()->getStyle('A'.($row+4).':D'.($row+4))->getFill()->applyFromArray(
		            array(
		            'type'       => PHPExcel_Style_Fill::FILL_SOLID,
		            'startcolor' => array('rgb' => 'FCC153'),
		            'endcolor' => array('rgb' => 'FCC153')

		            )
		    	);	

		    	//-------color bg coloum รวม All UnitCosts All Coloum 2-------------- //
			    	$objPHPExcel->getActiveSheet()->getStyle('E'.($row+4).':N'.($row+4))->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER));
			    	$objPHPExcel->getActiveSheet()->getStyle('E'.($row+4).':N'.($row+4))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1); 	
					$objPHPExcel->getActiveSheet()->getStyle('E'.($row+4).':N'.($row+4))->getFill()->applyFromArray(
			            array(
			            'type'       => PHPExcel_Style_Fill::FILL_SOLID,
			            'startcolor' => array('rgb' => 'FCC153'),
			            'endcolor' => array('rgb' => 'FCC153')

			            )
			    	);	 

		    	//-------SUM  All UnitCosts All Coloum &  1.เงินเดือน+ค่าจ้างชั่วคราว
		    	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (4, $row+4, $all_sum_salary);													

		    	//-------SUM All UnitCosts All Coloum &  2.เงินประจำตำแหน่ง
		    	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (5, $row+4, $all_sum_r_c);

		    	//-------SUM  All UnitCosts All Coloum & 3.ค่าล่วงเวลา(OT)
		    	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (6, $row+4, $all_sum_ot);

		    	//-------SUM  All UnitCosts All Coloum & 4.ไม่ทำเวชฯ
		    	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (7, $row+4, $all_sum_no_v);	

		    	//-------SUM  All UnitCosts All Coloum & 5.ฉ.8
		    	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (8, $row+4, $all_sum_ch8);

		    	//-------SUM  All UnitCosts All Coloum & 6.เงินตอบแทนอื่น
		    	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (9, $row+4, $all_sum_sp_other);	

		    	//-------SUM  All UnitCosts All Coloum & 7.ค่ารักษาพยาบาล/ช่วยเหลือบุตร
		    	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (10, $row+4, $all_sum_son);	

		    	//-------SUM  All UnitCosts All Coloum & 8.เดินทางไปราชการ
		    	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (11, $row+4, $all_sum_u_travel);	

		    	//-------SUM  All UnitCosts All Coloum & 9.อื่น ๆ
		    	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (12, $row+4, $all_sum_u_other);	

		    	//-------SUM  All UnitCosts All Coloum & 10.รวม
		    	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (13, $row+4, $all_sum_money1_all);		
				

				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5'); // Set excel version 2007	  		
			    $objWriter->save(storage_path()."/excel/reportCostsMonth.xls");

			    return Response::download( storage_path()."/excel/reportCostsMonth.xls", "reportCostsMonth.xls");	

		}


		/**
	    * function name : unitcosts_money_home
	    * home report unit cost
	    * get
	    */
		public function unitcosts_money_home()
		{
			if( Session::get('level') != '' )
			{
				$y = DB::Select( ' select year(order_date) as year1 from s_salary_detail group by year(order_date) order by year(order_date) desc ' );	
				return View::make( 'unitcosts.money_home', array( 'data' => $y ) );
			}
			else
			{
				return View::make('login.index');
			} 
		}
	  
	    /**
	    * function name : unitcosts_money
	    * report unitcost excel
	    * post
	    */
	    public function unitcosts_money()
	    {
	    	$y = Input::get('y_unit');	    	

	    	//-----Date ALL
	    	$date_start = ($y-1).'-10-01';
	    	$date_end   = ($y).'-09-30';	    	

	    	//------Date OT
	    	$range_ot = $this->get_range_ot();	    	

	    	foreach ($range_ot as $r1) {
	    		$start_ot = ($y-1).'-'.$r1->r_start.'-01';
	    		$end_ot   = ($y).'-'.$r1->r_end.'-31';
	    	}	    	
	    	if( $start_ot == '' || $end_ot == '' ){
	    		$start_ot = $date_start;
	    		$end_ot   = $date_end;
	    	}

	    	//-------Date SSO
	    	$range_sso = $this->get_range_sso();
	    	foreach ($range_sso as $r2) {
	    		$start_sso = ($y-1).'-'.$r2->r_start.'-01';
	    		$end_sso   = ($y).'-'.$r2->r_end.'-31';
	    	}
	    	if( $start_sso == '' || $end_sso == '' ){
				$start_sso = $date_start;
	    		$end_sso   = $date_end;
	    	}	    	


	    	if ( Session::get('level') != '' )
	    	{	
	    		//-------sql ดึงหน่วยต้นทุน    		
	    		$sql  = ' select *';					
				$sql .= ' from s_unit_costs';								

				$result = DB::select( $sql );		

				$objPHPExcel = new PHPExcel();
				$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial'); 
			    $objPHPExcel->getActiveSheet()->setTitle('Sheet1');
				$objPHPExcel->setActiveSheetIndex(0);				

				//--------coloum name ชุด 1
				$objPHPExcel->getActiveSheet()->setCellValue('A1', 'รหัส');	
				$objPHPExcel->getActiveSheet()->mergeCells('A1:A2');
				$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(7);									
				$objPHPExcel->getActiveSheet()->setCellValue('B1', 'ลำดับ');	
				$objPHPExcel->getActiveSheet()->mergeCells('B1:B2');		
				$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(5);	
				$objPHPExcel->getActiveSheet()->setCellValue('C1', 'ชื่อ');
				$objPHPExcel->getActiveSheet()->mergeCells('C1:C2');
				$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);	
				$objPHPExcel->getActiveSheet()->setCellValue('D1', 'สกุล');
				$objPHPExcel->getActiveSheet()->mergeCells('D1:D2');
				$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);

				//-------แถว coloum name ชุด 1 bg color
				$objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getFill()->applyFromArray(
			            array(
			            'type'       => PHPExcel_Style_Fill::FILL_SOLID,
			            'startcolor' => array('rgb' => '60D0F2'),
			            'endcolor' => array('rgb' => '60D0F2')

			            )
			    );
			   
			   //------fix tab
			    $objPHPExcel->getActiveSheet()->freezePane('E1');			   			    

			    //------แถว รวมทั้งจำนวน(บาท) bg color
				$objPHPExcel->getActiveSheet()->setCellValue('E1', 'รวมทั้งจำนวน(บาท)');
				$objPHPExcel->getActiveSheet()->mergeCells('E1:N1');				
				$objPHPExcel->getActiveSheet()->getStyle('E1:N1')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER));
				$objPHPExcel->getActiveSheet()->getStyle('E1:N1')->getFill()->applyFromArray(
			            array(
			            'type'       => PHPExcel_Style_Fill::FILL_SOLID,
			            'startcolor' => array('rgb' => 'D39BFF'),
			            'endcolor' => array('rgb' => 'D39BFF')

			            )
			    );

				//--------coloum name ชุด 2
				$objPHPExcel->getActiveSheet()->setCellValue('E2', '1.เงินเดือน+ค่าจ้างชั่วคราว');	
				$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
				$objPHPExcel->getActiveSheet()->getStyle('E')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1); 

				$objPHPExcel->getActiveSheet()->setCellValue('F2', '2.เงินประจำตำแหน่ง');	
				$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
				$objPHPExcel->getActiveSheet()->getStyle('F')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1); 

				$objPHPExcel->getActiveSheet()->setCellValue('G2', '3.ค่าล่วงเวลา(OT)');	
				$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(26);	
				$objPHPExcel->getActiveSheet()->getStyle('G')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1); 

				$objPHPExcel->getActiveSheet()->setCellValue('H2', '4.ไม่ทำเวชฯ');	
				$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(30);
				$objPHPExcel->getActiveSheet()->getStyle('H')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1); 

				$objPHPExcel->getActiveSheet()->setCellValue('I2', '5.ฉ.8');	
				$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(23);
				$objPHPExcel->getActiveSheet()->getStyle('I')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1); 

				$objPHPExcel->getActiveSheet()->setCellValue('J2', '6.เงินตอบแทนอื่น');	
				$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(23);
				$objPHPExcel->getActiveSheet()->getStyle('J')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1); 

				$objPHPExcel->getActiveSheet()->setCellValue('K2', '7.ค่ารักษาพยาบาล/ช่วยเหลือบุตร');	
				$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(23);
				$objPHPExcel->getActiveSheet()->getStyle('K')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1); 

				$objPHPExcel->getActiveSheet()->setCellValue('L2', '8.เดินทางไปราชการ');	
				$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(23);
				$objPHPExcel->getActiveSheet()->getStyle('L')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1); 

				$objPHPExcel->getActiveSheet()->setCellValue('M2', '9.อื่น ๆ');	
				$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(23);
				$objPHPExcel->getActiveSheet()->getStyle('M')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1); 				

				//------แถว coloum name bg color
				$objPHPExcel->getActiveSheet()->getStyle('E2:N2')->getFill()->applyFromArray(
			            array(
			            'type'       => PHPExcel_Style_Fill::FILL_SOLID,
			            'startcolor' => array('rgb' => 'FCC153'),
			            'endcolor' => array('rgb' => 'FCC153')

			            )
			    );

				//------แถว coloum name รวม bg color
				$objPHPExcel->getActiveSheet()->setCellValue('N2', 'รวม');	
				$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(20);	
				$objPHPExcel->getActiveSheet()->getStyle('N')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1); 						
				$objPHPExcel->getActiveSheet()->getStyle('N2')->getFill()->applyFromArray(
			            array(
			            'type'       => PHPExcel_Style_Fill::FILL_SOLID,
			            'startcolor' => array('rgb' => 'F6FC53'),
			            'endcolor' => array('rgb' => 'F6FC53')

			            )
			    );			   

				$all_sum_salary		=0;
				$all_sum_r_c		=0;
				$all_sum_ot 		=0;
				$all_sum_no_v 		=0;
				$all_sum_ch8 		=0;
				$all_sum_sp_other 	=0;
				$all_sum_son 		=0;
				$all_sum_u_travel 	=0;
				$all_sum_u_other 	=0;
				$all_sum_money1_all =0;

				$row = 0;
				$sum = 0;
				foreach ($result as $key) //for sql 1
				{	
					//--------- Code UnitCost
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (0, $row+3, $key->unitcode);
					$objPHPExcel->getActiveSheet()->getStyle('A'.($row+3))->getFill()->applyFromArray(
				            array(
				            'type'       => PHPExcel_Style_Fill::FILL_SOLID,
				            'startcolor' => array('rgb' => 'F6FC53'),
				            'endcolor' => array('rgb' => 'F6FC53')

				            )
				    );

					//--------- Name UnitCost
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (1, $row+3, $key->unitname);						
					$objPHPExcel->getActiveSheet()->getStyle('B'.($row+3).':N'.($row+3))->getFill()->applyFromArray(
				            array(
				            'type'       => PHPExcel_Style_Fill::FILL_SOLID,
				            'startcolor' => array('rgb' => 'FC8AE7'),
				            'endcolor' => array('rgb' => 'FC8AE7')

				            )
				    );

					//-------sql 2
					$sql2  = '  select s1.unitname, a.y, a.m, s1.cid, a.type, concat(n1.pname,"",n1.fname) as fname, n1.lname,';
					$sql2 .= '  sum(round(((a.salary * s1.cal) / 100),2)) as salary, sum(round(((a.r_c * s1.cal) / 100),2)) as r_c, ';					
					$sql2 .= '  sum(round(((a.ot * s1.cal) / 100),2)) as ot, sum(round(((a.no_v * s1.cal) / 100),2)) as no_v, ';
					$sql2 .= '  sum(round(((a.ch8 * s1.cal) / 100),2)) as ch8, sum(round(((a.sp_other * s1.cal) / 100),2)) as sp_other, ';
					$sql2 .= '  sum(round(((a.son * s1.cal) / 100),2)) as son, sum(round(((a.u_travel * s1.cal) / 100),2)) as u_travel, ';
					$sql2 .= '  sum(round(((a.u_other * s1.cal) / 100),2)) as u_other ';
					$sql2 .= '  from s_unit_costs_emp s1 ';
					$sql2 .= '  left join ( ';
					$sql2 .= '  ( select cid,year(order_date) as y, month(order_date) as m, 1 as type, sum(salary+salary_other) as salary, 0 as r_c,';
					$sql2 .= '  (select sum(c.ot) as ot from s_salary_detail c where order_date between "'.$start_ot.'" and "'.$end_ot.'" and c.cid=s_salary_detail.cid) as ot, ';
					$sql2 .= '  sum(no_v) as no_v, sum(ch8) as ch8, ';
					$sql2 .= '  ((select sum(b.salary_sso) from s_salary_detail b where order_date between "'.$start_sso.'" and "'.$end_sso.'" and b.cid=s_salary_detail.cid)+sum(pts+pts2)) as sp_other,';
					$sql2 .= '  0 as son, sum(u_travel) as u_travel, sum(u_other) as u_other';
					$sql2 .= '  from s_salary_detail';
					$sql2 .= '  where order_date between "'.$date_start.'" and "'.$date_end.'" group by cid ) ';
					$sql2 .= '  union ';
					$sql2 .= '  ( select cid,year(order_date) as y, month(order_date) as m, 2 as type, sum(salary+special_m+game_sp) as salary, sum(r_c) as r_c,';
					$sql2 .= '  (select sum(c.ot) from s_salary_ocsc_detail c where order_date between "'.$start_ot.'" and "'.$end_ot.'" and c.cid=s_salary_ocsc_detail.cid) as ot,';
					$sql2 .= '  sum(no_v) as no_v, sum(ch8) as ch8,';
					$sql2 .= '  sum(save_h+p_other+pts+pts2+r_pt+r_other) as sp_other, sum(son) as son, sum(u_travel) as u_travel, sum(u_other) as u_other ';
					$sql2 .= '  from s_salary_ocsc_detail';
					$sql2 .= '  where order_date between "'.$date_start.'" and "'.$date_end.'" group by cid )';
					$sql2 .= '  ) as a on a.cid = s1.cid ';
					$sql2 .= '  left join n_datageneral n1 on n1.cid=s1.cid';
					$sql2 .= '  where s1.unitname="'.$key->unitname.'" ';
					$sql2 .= '  group by s1.cid order by s1.unitcode asc,a.type desc,a.salary desc';					
					
					$result2 = DB::select( $sql2 );	
					$n 				=0;
					$sum_salary 	=0;
					$sum_r_c 		=0;
					$sum_ot 		=0;
					$sum_no_v 		=0;
					$sum_ch8 		=0;
					$sum_sp_other 	=0;
					$sum_son 		=0;
					$sum_u_travel 	=0;
					$sum_u_other 	=0;
					$sum_money1_all =0;
					//-------------------- Detail Start -------------------//
					foreach ( $result2 as $key2 ) //for sql 2
					{	
						$sum_money1=0;					
						$n++;
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (1, $row+4, $n);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (2, $row+4, $key2->fname);	
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (3, $row+4, $key2->lname);		
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (4, $row+4, $key2->salary);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (5, $row+4, $key2->r_c);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (6, $row+4, $key2->ot);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (7, $row+4, $key2->no_v);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (8, $row+4, $key2->ch8);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (9, $row+4, $key2->sp_other);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (10, $row+4, $key2->son);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (11, $row+4, $key2->u_travel);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (12, $row+4, $key2->u_other);
						//----colum รวมเงิน
						$sum_money1 = $key2->salary+$key2->r_c+$key2->ot+$key2->no_v+$key2->ch8+$key2->sp_other+$key2->son+$key2->u_travel+$key2->u_other;
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (13, $row+4, $sum_money1);

						$sum_salary 	= $sum_salary + $key2->salary;
						$sum_r_c 		= $sum_r_c + $key2->r_c;
						$sum_ot 		= $sum_ot + $key2->ot;
						$sum_no_v 		= $sum_no_v + $key2->no_v;
						$sum_ch8 		= $sum_ch8 + $key2->ch8;
						$sum_sp_other 	= $sum_sp_other + $key2->sp_other;
						$sum_son 		= $sum_son + $key2->son;
						$sum_u_travel 	= $sum_u_travel + $key2->u_travel;
						$sum_u_other 	= $sum_u_other + $key2->u_other;
						$sum_money1_all = $sum_money1_all + $sum_money1;

						$row = $row+1;
					}//end for sql 2
					//-------------------- Detail End -------------------//

					//----------------- รวมแต่ละ unitcosts Start -------------- //
					//-------color bg coloum ชุด 1
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (1, $row+4,$key->unitcode);	
					$objPHPExcel->getActiveSheet()->mergeCells('B'.($row+4).':D'.($row+4));				
					$objPHPExcel->getActiveSheet()->getStyle('B'.($row+4).':D'.($row+4))->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER));
					$objPHPExcel->getActiveSheet()->getStyle('B'.($row+4).':D'.($row+4))->getFill()->applyFromArray(
			            array(
			            'type'       => PHPExcel_Style_Fill::FILL_SOLID,
			            'startcolor' => array('rgb' => 'F6FC53'),
			            'endcolor' => array('rgb' => 'F6FC53')

			            )
			    	);	

					//-------color bg coloum ชุด 2  แถวรวมด้านล่าง
			    	$objPHPExcel->getActiveSheet()->getStyle('E'.($row+4).':N'.($row+4))->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER));
			    	$objPHPExcel->getActiveSheet()->getStyle('E'.($row+4).':N'.($row+4))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1); 	
					$objPHPExcel->getActiveSheet()->getStyle('E'.($row+4).':N'.($row+4))->getFill()->applyFromArray(
			            array(
			            'type'       => PHPExcel_Style_Fill::FILL_SOLID,
			            'startcolor' => array('rgb' => 'F6FC53'),
			            'endcolor' => array('rgb' => 'F6FC53')

			            )
			    	);	    	

					//-------SUM  coloum &  1.เงินเดือน+ค่าจ้างชั่วคราว
			    	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (4, $row+4, $sum_salary);													

			    	//-------SUM coloum &  2.เงินประจำตำแหน่ง
			    	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (5, $row+4, $sum_r_c);

			    	//-------SUM  coloum & 3.ค่าล่วงเวลา(OT)
			    	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (6, $row+4, $sum_ot);

			    	//-------SUM  coloum & 4.ไม่ทำเวชฯ
			    	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (7, $row+4, $sum_no_v);	

			    	//-------SUM  coloum & 5.ฉ.8
			    	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (8, $row+4, $sum_ch8);

			    	//-------SUM  coloum & 6.เงินตอบแทนอื่น
			    	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (9, $row+4, $sum_sp_other);	

			    	//-------SUM  coloum & 7.ค่ารักษาพยาบาล/ช่วยเหลือบุตร
			    	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (10, $row+4, $sum_son);	

			    	//-------SUM  coloum & 8.เดินทางไปราชการ
			    	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (11, $row+4, $sum_u_travel);	

			    	//-------SUM  coloum & 9.อื่น ๆ
			    	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (12, $row+4, $sum_u_other);	

			    	//-------SUM  coloum & 10.รวม
			    	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (13, $row+4, $sum_money1_all);				
					
			    	$row = $row+1;
			    	//----------------- รวมแต่ละ unitcosts END -------------- //
			    	$row++;	

			    	//--------------SUM ALL UNIT-------------//
			    	$all_sum_salary 	= $all_sum_salary + $sum_salary;
					$all_sum_r_c 		= $sum_r_c + $all_sum_r_c;
					$all_sum_ot 		= $all_sum_ot + $sum_ot;
					$all_sum_no_v 		= $all_sum_no_v + $sum_no_v;
					$all_sum_ch8 		= $all_sum_ch8 + $sum_ch8;
					$all_sum_sp_other 	= $all_sum_sp_other + $sum_sp_other;
					$all_sum_son 		= $all_sum_son + $sum_son;
					$all_sum_u_travel 	= $all_sum_u_travel + $sum_u_travel;
					$all_sum_u_other 	= $all_sum_u_other + $sum_u_other;
					$all_sum_money1_all = $all_sum_money1_all + $sum_money1_all;			    	
									
				}//end for sql 1

				//----------------- color bg coloum รวม All UnitCosts All Coloum ทั้งหทด ท้ายไฟล์ 1-------------- //
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (0, $row+4, "รวม");	
				$objPHPExcel->getActiveSheet()->mergeCells('A'.($row+4).':D'.($row+4));				
				$objPHPExcel->getActiveSheet()->getStyle('A'.($row+4).':D'.($row+4))->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER));
				$objPHPExcel->getActiveSheet()->getStyle('A'.($row+4).':D'.($row+4))->getFill()->applyFromArray(
		            array(
		            'type'       => PHPExcel_Style_Fill::FILL_SOLID,
		            'startcolor' => array('rgb' => 'FCC153'),
		            'endcolor' => array('rgb' => 'FCC153')

		            )
		    	);	

		    	//-------color bg coloum รวม All UnitCosts All Coloum 2-------------- //
			    	$objPHPExcel->getActiveSheet()->getStyle('E'.($row+4).':N'.($row+4))->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER));
			    	$objPHPExcel->getActiveSheet()->getStyle('E'.($row+4).':N'.($row+4))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1); 	
					$objPHPExcel->getActiveSheet()->getStyle('E'.($row+4).':N'.($row+4))->getFill()->applyFromArray(
			            array(
			            'type'       => PHPExcel_Style_Fill::FILL_SOLID,
			            'startcolor' => array('rgb' => 'FCC153'),
			            'endcolor' => array('rgb' => 'FCC153')

			            )
			    	);	 

		    	//-------SUM  All UnitCosts All Coloum &  1.เงินเดือน+ค่าจ้างชั่วคราว
		    	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (4, $row+4, $all_sum_salary);													

		    	//-------SUM All UnitCosts All Coloum &  2.เงินประจำตำแหน่ง
		    	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (5, $row+4, $all_sum_r_c);

		    	//-------SUM  All UnitCosts All Coloum & 3.ค่าล่วงเวลา(OT)
		    	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (6, $row+4, $all_sum_ot);

		    	//-------SUM  All UnitCosts All Coloum & 4.ไม่ทำเวชฯ
		    	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (7, $row+4, $all_sum_no_v);	

		    	//-------SUM  All UnitCosts All Coloum & 5.ฉ.8
		    	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (8, $row+4, $all_sum_ch8);

		    	//-------SUM  All UnitCosts All Coloum & 6.เงินตอบแทนอื่น
		    	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (9, $row+4, $all_sum_sp_other);	

		    	//-------SUM  All UnitCosts All Coloum & 7.ค่ารักษาพยาบาล/ช่วยเหลือบุตร
		    	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (10, $row+4, $all_sum_son);	

		    	//-------SUM  All UnitCosts All Coloum & 8.เดินทางไปราชการ
		    	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (11, $row+4, $all_sum_u_travel);	

		    	//-------SUM  All UnitCosts All Coloum & 9.อื่น ๆ
		    	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (12, $row+4, $all_sum_u_other);	

		    	//-------SUM  All UnitCosts All Coloum & 10.รวม
		    	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (13, $row+4, $all_sum_money1_all);		
				

				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5'); // Set excel version 2007	  		
			    $objWriter->save(storage_path()."/excel/reportCosts.xls");

			    return Response::download( storage_path()."/excel/reportCosts.xls", "reportCosts.xls");	

	    	}
	    	else
	    	{
	    		return View::make( 'login.index' );	
	    	} 
	    }

	    /**
	    * function name : unitcosts_money_home_lc
	    * home report unit cost LC สมบูรณ์
	    * get
	    */
		public function unitcosts_money_home_lc()
		{
			if( Session::get('level') != '' )
			{
				$y = DB::Select( ' select year(order_date) as year1 from s_salary_detail group by year(order_date) order by year(order_date) desc ' );	
				return View::make( 'unitcosts.money_home_lc', array( 'data' => $y ) );
			}
			else
			{
				return View::make('login.index');
			} 
		}

		/**
	    * function name : unitcosts_money_lc
	    * excel lc สมบูรณ์
	    * post
	    */
		public function unitcosts_money_lc()
		{

			$y = Input::get('y_unit_lc');	    	

	    	//-----Date ALL
	    	$date_start = ($y-1).'-10-01';
	    	$date_end   = ($y).'-09-30';	    	

	    	//------Date OT
	    	$range_ot = $this->get_range_ot();	    	

	    	foreach ($range_ot as $r1) {
	    		$start_ot = ($y-1).'-'.$r1->r_start.'-01';
	    		$end_ot   = ($y).'-'.$r1->r_end.'-31';
	    	}	    	
	    	if( $start_ot == '' || $end_ot == '' ){
	    		$start_ot = $date_start;
	    		$end_ot   = $date_end;
	    	}

	    	//-------Date SSO
	    	$range_sso = $this->get_range_sso();
	    	foreach ($range_sso as $r2) {
	    		$start_sso = ($y-1).'-'.$r2->r_start.'-01';
	    		$end_sso   = ($y).'-'.$r2->r_end.'-31';
	    	}
	    	if( $start_sso == '' || $end_sso == '' ){
				$start_sso = $date_start;
	    		$end_sso   = $date_end;
	    	}	

	    	if ( Session::get('level') != '' )
	    	{
	    		//-------sql ดึงหน่วยต้นทุน    		
	    		$sql  = ' select *';					
				$sql .= ' from s_unit_costs';								

				$result = DB::select( $sql );		

				$objPHPExcel = new PHPExcel();
				$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial'); 
			    $objPHPExcel->getActiveSheet()->setTitle('Sheet1');
				$objPHPExcel->setActiveSheetIndex(0);				

				//--------coloum name ชุด 1
				$objPHPExcel->getActiveSheet()->setCellValue('A1', 'รหัส');	
				$objPHPExcel->getActiveSheet()->mergeCells('A1:A2');
				$objPHPExcel->getActiveSheet()->getStyle('A1:A2')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER));
				
				//-------แถว coloum name ชุด 1 bg color
				$objPHPExcel->getActiveSheet()->getStyle('A1')->getFill()->applyFromArray(
			            array(
			            'type'       => PHPExcel_Style_Fill::FILL_SOLID,
			            'startcolor' => array('rgb' => '60D0F2'),
			            'endcolor' => array('rgb' => '60D0F2')

			            )
			    );
			   
			   //------fix tab
			    $objPHPExcel->getActiveSheet()->freezePane('A3');			   			    

			    //------แถว รวมทั้งจำนวน(บาท) bg color
				$objPHPExcel->getActiveSheet()->setCellValue('B1', 'รวมทั้งจำนวน(บาท)');
				$objPHPExcel->getActiveSheet()->mergeCells('B1:K1');				
				$objPHPExcel->getActiveSheet()->getStyle('B1:K1')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER));
				$objPHPExcel->getActiveSheet()->getStyle('B1:K1')->getFill()->applyFromArray(
			            array(
			            'type'       => PHPExcel_Style_Fill::FILL_SOLID,
			            'startcolor' => array('rgb' => 'D39BFF'),
			            'endcolor' => array('rgb' => 'D39BFF')

			            )
			    );

				//--------coloum name ชุด 2
				$objPHPExcel->getActiveSheet()->setCellValue('B2', '1.เงินเดือน+ค่าจ้างชั่วคราว');	
				$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
				$objPHPExcel->getActiveSheet()->getStyle('B')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1); 

				$objPHPExcel->getActiveSheet()->setCellValue('C2', '2.เงินประจำตำแหน่ง');	
				$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
				$objPHPExcel->getActiveSheet()->getStyle('C')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1); 

				$objPHPExcel->getActiveSheet()->setCellValue('D2', '3.ค่าล่วงเวลา(OT)');	
				$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(26);	
				$objPHPExcel->getActiveSheet()->getStyle('D')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1); 

				$objPHPExcel->getActiveSheet()->setCellValue('E2', '4.ไม่ทำเวชฯ');	
				$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
				$objPHPExcel->getActiveSheet()->getStyle('E')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1); 

				$objPHPExcel->getActiveSheet()->setCellValue('F2', '5.ฉ.8');	
				$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(23);
				$objPHPExcel->getActiveSheet()->getStyle('F')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1); 

				$objPHPExcel->getActiveSheet()->setCellValue('G2', '6.เงินตอบแทนอื่น');	
				$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(23);
				$objPHPExcel->getActiveSheet()->getStyle('G')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1); 

				$objPHPExcel->getActiveSheet()->setCellValue('H2', '7.ค่ารักษาพยาบาล/ช่วยเหลือบุตร');	
				$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(23);
				$objPHPExcel->getActiveSheet()->getStyle('H')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1); 

				$objPHPExcel->getActiveSheet()->setCellValue('I2', '8.เดินทางไปราชการ');	
				$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(23);
				$objPHPExcel->getActiveSheet()->getStyle('I')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1); 

				$objPHPExcel->getActiveSheet()->setCellValue('J2', '9.อื่น ๆ');	
				$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(23);
				$objPHPExcel->getActiveSheet()->getStyle('J')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1); 				

				//------แถว coloum name bg color
				$objPHPExcel->getActiveSheet()->getStyle('B2:K2')->getFill()->applyFromArray(
			            array(
			            'type'       => PHPExcel_Style_Fill::FILL_SOLID,
			            'startcolor' => array('rgb' => 'FCC153'),
			            'endcolor' => array('rgb' => 'FCC153')

			            )
			    );

				//------แถว coloum name รวม bg color
				$objPHPExcel->getActiveSheet()->setCellValue('K2', 'รวม');	
				$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);	
				$objPHPExcel->getActiveSheet()->getStyle('K')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1); 						
				$objPHPExcel->getActiveSheet()->getStyle('K2')->getFill()->applyFromArray(
			            array(
			            'type'       => PHPExcel_Style_Fill::FILL_SOLID,
			            'startcolor' => array('rgb' => 'F6FC53'),
			            'endcolor' => array('rgb' => 'F6FC53')

			            )
			    );	

				$sum_salary 	=0;
				$sum_r_c 		=0;
				$sum_ot 		=0;
				$sum_no_v 		=0;
				$sum_ch8 		=0;
				$sum_sp_other 	=0;
				$sum_son 		=0;
				$sum_u_travel 	=0;
				$sum_u_other 	=0;
				$sum_money1_all =0;

				$row = 0;
				$sum = 0;
				foreach ($result as $key) //for sql 1
				{	
					//--------- Code UnitCost
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (0, $row+3, $key->unitcode);
					$objPHPExcel->getActiveSheet()->getStyle('A'.($row+3))->getFill()->applyFromArray(
				            array(
				            'type'       => PHPExcel_Style_Fill::FILL_SOLID,
				            'startcolor' => array('rgb' => 'FFEFD3'),
				            'endcolor' => array('rgb' => 'FFEFD3')

				            )
				    );
					
					//-------sql 2
					$sql2  = '  select s1.unitname, a.y, a.m, s1.cid, a.type, concat(n1.pname,"",n1.fname) as fname, n1.lname,';
					$sql2 .= '  sum(round(((a.salary * s1.cal) / 100),2)) as salary, sum(round(((a.r_c * s1.cal) / 100),2)) as r_c, ';					
					$sql2 .= '  sum(round(((a.ot * s1.cal) / 100),2)) as ot, sum(round(((a.no_v * s1.cal) / 100),2)) as no_v, ';
					$sql2 .= '  sum(round(((a.ch8 * s1.cal) / 100),2)) as ch8, sum(round(((a.sp_other * s1.cal) / 100),2)) as sp_other, ';
					$sql2 .= '  sum(round(((a.son * s1.cal) / 100),2)) as son, sum(round(((a.u_travel * s1.cal) / 100),2)) as u_travel, ';
					$sql2 .= '  sum(round(((a.u_other * s1.cal) / 100),2)) as u_other ';
					$sql2 .= '  from s_unit_costs_emp s1 ';
					$sql2 .= '  left join ( ';
					$sql2 .= '  ( select cid,year(order_date) as y, month(order_date) as m, 1 as type, sum(salary+salary_other) as salary, 0 as r_c,';
					$sql2 .= '  (select sum(c.ot) as ot from s_salary_detail c where order_date between "'.$start_ot.'" and "'.$end_ot.'" and c.cid=s_salary_detail.cid) as ot, ';
					$sql2 .= '  sum(no_v) as no_v, sum(ch8) as ch8, ';
					$sql2 .= '  ((select sum(b.salary_sso) from s_salary_detail b where order_date between "'.$start_sso.'" and "'.$end_sso.'" and b.cid=s_salary_detail.cid)+sum(pts+pts2)) as sp_other,';
					$sql2 .= '  0 as son, sum(u_travel) as u_travel, sum(u_other) as u_other';
					$sql2 .= '  from s_salary_detail';
					$sql2 .= '  where order_date between "'.$date_start.'" and "'.$date_end.'" group by cid ) ';
					$sql2 .= '  union ';
					$sql2 .= '  ( select cid,year(order_date) as y, month(order_date) as m, 2 as type, sum(salary+special_m+game_sp) as salary, sum(r_c) as r_c,';
					$sql2 .= '  (select sum(c.ot) from s_salary_ocsc_detail c where order_date between "'.$start_ot.'" and "'.$end_ot.'" and c.cid=s_salary_ocsc_detail.cid) as ot,';
					$sql2 .= '  sum(no_v) as no_v, sum(ch8) as ch8,';
					$sql2 .= '  sum(save_h+p_other+pts+pts2+r_pt+r_other) as sp_other, sum(son) as son, sum(u_travel) as u_travel, sum(u_other) as u_other ';
					$sql2 .= '  from s_salary_ocsc_detail';
					$sql2 .= '  where order_date between "'.$date_start.'" and "'.$date_end.'" group by cid )';
					$sql2 .= '  ) as a on a.cid = s1.cid ';
					$sql2 .= '  left join n_datageneral n1 on n1.cid=s1.cid';
					$sql2 .= '  where s1.unitname="'.$key->unitname.'" ';
					$sql2 .= '  group by s1.unitcode order by s1.unitcode asc';					
					
					$result2 = DB::select( $sql2 );	
					
					//-------------------- Detail Start -------------------//
					foreach ( $result2 as $key2 ) //for sql 2
					{	
						//---------bg color detail
						$objPHPExcel->getActiveSheet()->getStyle('K'.($row+3))->getFill()->applyFromArray(
				            array(
				            'type'       => PHPExcel_Style_Fill::FILL_SOLID,
				            'startcolor' => array('rgb' => 'F6FC53'),
				            'endcolor' => array('rgb' => 'F6FC53')
				            )
				    	);
				    	$objPHPExcel->getActiveSheet()->getStyle('B'.($row+3).':'.'K'.($row+3))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1); 
				    	
						$sum_money1=0;																		
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (1, $row+3, $key2->salary);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (2, $row+3, $key2->r_c);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (3, $row+3, $key2->ot);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (4, $row+3, $key2->no_v);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (5, $row+3, $key2->ch8);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (6, $row+3, $key2->sp_other);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (7, $row+3, $key2->son);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (8, $row+3, $key2->u_travel);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (9, $row+3, $key2->u_other);
						//----colum รวมเงิน
						$sum_money1 = $key2->salary+$key2->r_c+$key2->ot+$key2->no_v+$key2->ch8+$key2->sp_other+$key2->son+$key2->u_travel+$key2->u_other;
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (10, $row+3, $sum_money1);

						$sum_salary 	= $sum_salary + $key2->salary;
						$sum_r_c 		= $sum_r_c + $key2->r_c;
						$sum_ot 		= $sum_ot + $key2->ot;
						$sum_no_v 		= $sum_no_v + $key2->no_v;
						$sum_ch8 		= $sum_ch8 + $key2->ch8;
						$sum_sp_other 	= $sum_sp_other + $key2->sp_other;
						$sum_son 		= $sum_son + $key2->son;
						$sum_u_travel 	= $sum_u_travel + $key2->u_travel;
						$sum_u_other 	= $sum_u_other + $key2->u_other;
						$sum_money1_all = $sum_money1_all + $sum_money1;
						
					}//end for sql 2
					//-------------------- Detail End -------------------//		
														    	
			    	$row++;					    			    									
				}//end for sql 1
				
				//----------------- color bg coloum รวม All UnitCosts All Coloum ทั้งหทด ท้ายไฟล์ 1-------------- //
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (0, $row+3, "รวม");									
				$objPHPExcel->getActiveSheet()->getStyle('A'.($row+3))->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER));
				$objPHPExcel->getActiveSheet()->getStyle('A'.($row+3))->getFill()->applyFromArray(
		            array(
		            'type'       => PHPExcel_Style_Fill::FILL_SOLID,
		            'startcolor' => array('rgb' => 'FCC153'),
		            'endcolor' => array('rgb' => 'FCC153')

		            )
		    	);	
				
		    	//-------color bg coloum รวม All UnitCosts All Coloum 2-------------- //
			    	$objPHPExcel->getActiveSheet()->getStyle('B'.($row+3).':K'.($row+3))->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT));
			    	$objPHPExcel->getActiveSheet()->getStyle('B'.($row+3).':K'.($row+3))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1); 	
					$objPHPExcel->getActiveSheet()->getStyle('B'.($row+3).':K'.($row+3))->getFill()->applyFromArray(
			            array(
			            'type'       => PHPExcel_Style_Fill::FILL_SOLID,
			            'startcolor' => array('rgb' => 'FCC153'),
			            'endcolor' => array('rgb' => 'FCC153')

			            )
			    	);	 
				
		    	//-------SUM  All UnitCosts All Coloum &  1.เงินเดือน+ค่าจ้างชั่วคราว
		    	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (1, $row+3, $sum_salary);													

		    	//-------SUM All UnitCosts All Coloum &  2.เงินประจำตำแหน่ง
		    	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (2, $row+3, $sum_r_c);

		    	//-------SUM  All UnitCosts All Coloum & 3.ค่าล่วงเวลา(OT)
		    	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (3, $row+3, $sum_ot);

		    	//-------SUM  All UnitCosts All Coloum & 4.ไม่ทำเวชฯ
		    	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (4, $row+3, $sum_no_v);	

		    	//-------SUM  All UnitCosts All Coloum & 5.ฉ.8
		    	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (5, $row+3, $sum_ch8);

		    	//-------SUM  All UnitCosts All Coloum & 6.เงินตอบแทนอื่น
		    	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (6, $row+3, $sum_sp_other);	

		    	//-------SUM  All UnitCosts All Coloum & 7.ค่ารักษาพยาบาล/ช่วยเหลือบุตร
		    	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (7, $row+3, $sum_son);	

		    	//-------SUM  All UnitCosts All Coloum & 8.เดินทางไปราชการ
		    	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (8, $row+3, $sum_u_travel);	

		    	//-------SUM  All UnitCosts All Coloum & 9.อื่น ๆ
		    	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (9, $row+3, $sum_u_other);	

		    	//-------SUM  All UnitCosts All Coloum & 10.รวม
		    	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (10, $row+3, $sum_money1_all);


			    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5'); // Set excel version 2007	  		
			    $objWriter->save(storage_path()."/excel/reportCostsLC.xls");

			    return Response::download( storage_path()."/excel/reportCostsLC.xls", "reportCostsLC.xls");
	    	}
	    	else
	    	{
	    		return View::make( 'login.index' );	
	    	} 

		}
        
         /**
	    * function name : av_ot_sso
	    * ช่วงข้อมูลการดึง OT และ ประกันสังคม
	    * get
	    */
        public function range_ot_sso()
        {
             $data = DB::Select( 'select * from s_unit_costs_range' );
             return View::make( 'unitcosts.view_range', array( 'data' => $data ) );
        }
        
         /**
	    * function name : update_range
	    * แก้ไข ช่วงข้อมูลการดึง OT และ ประกันสังคม
	    * get
	    */
        public function update_range($name, $r_start, $r_end)
        {
           $data = array(            
                'r_start' 	=> $r_start,
                'r_end' 	=> $r_end 		                         		            	                       
            );         

            //update
            $result = DB::table( 's_unit_costs_range' )->where( 'name', '=', $name )->update( $data );	   
        }


	     /**
	    * function name : unitcosts_manager
	    * edit unitcode money
	    * get
	    */
	    public function unitcosts_manager()	   
	    {
	    	if ( Session::get('level') != '' )
	    	{   
	    		$y = DB::Select( ' select year(order_date) as year1 from s_salary_detail group by year(order_date) order by year(order_date) desc ' );

	    		$u = DB::Select( ' select unitname from ((select unitname from s_unit_costs) UNION (select unit_cost as unitname from s_unit_costs_result group by unit_cost)) as a ' );

		        return View::make( 'unitcosts.unitcosts_manager', 
		        	array( 'y' => $y, 'u' => $u )
		        );
	    	}
	    	else
	    	{
	    		//return login
	    		return View::make( 'login.index' );	
	    	} 
	    }

	    private function get_year_unitcosts($y)
	    {
	    	$data = DB::Select( ' select count(*) as num from  s_unit_costs_result where unit_cost_year='.$y.' ' );
	    	foreach ($data as $key) {
	    		return $key->num;
	    	}
	    }

	     /**
	    * function name : view_manager
	    * edit view_manager unitcode money
	    * get
	    */
	    public function view_manager($y, $m, $u)
	    {	
            //----- sso ----//
            if( $m == 1 ){
                $y_s = $y-1;
                $m_s = 12;
            }else{
               $y_s = $y;
               $m_s = $m-1;
            }
            
            //----- ot ----//
            if( $m == 12 ){
                $y_t = $y+1;
                $m_t = 1;
            }else{
                $y_t = $y;
                $m_t = $m+1; 
            }
            
    		$sql  = ' select s1.unitname, a.y, a.m, s1.cid, a.type, concat(n1.pname,"",n1.fname," ",n1.lname) as fullname, a.salary, a.r_c ';
    		$sql .= ' , a.ot, a.no_v, a.ch8, a.sp_other, a.son, a.u_travel, a.u_other ';
    		$sql .= ' from s_unit_costs_emp s1 ';
    		$sql .= ' left join ';
    		$sql .= ' ( ';
    		$sql .= ' ( select cid,year(order_date) as y, month(order_date) as m, 1 as type, sum(salary+salary_other) as salary, 0 as r_c, (select c.ot from s_salary_detail c where year(c.order_date)='.$y_t.' and month(c.order_date)='.$m_t.' and c.cid=s_salary_detail.cid) as ot, sum(no_v) as no_v, sum(ch8) as ch8, ((select b.salary_sso from s_salary_detail b where year(b.order_date)='.$y_s.' and month(b.order_date)='.$m_s.' and b.cid=s_salary_detail.cid)+pts) as sp_other, 0 as son, sum(u_travel) as u_travel, sum(u_other) as u_other from s_salary_detail where year(order_date)='.$y.' and month(order_date)='.$m.'  group by cid  ) ';
    		$sql .= ' union ';
    		$sql .= ' ( select cid,year(order_date) as y, month(order_date) as m, 2 as type, sum(salary+r_other+special_m) as salary, sum(r_c) as r_c, (select c.ot from s_salary_ocsc_detail c where year(c.order_date)='.$y_t.' and month(c.order_date)='.$m_t.' and c.cid=s_salary_ocsc_detail.cid) as ot, sum(no_v) as no_v, sum(ch8) as ch8, sum(save_h+p_other+pts) as sp_other, sum(r_pt+son) as son, sum(u_travel) as u_travel, sum(u_other) as u_other  from s_salary_ocsc_detail where year(order_date)='.$y.' and month(order_date)='.$m.' group by cid ) ';
    		$sql .= ' ) as a ';
    		$sql .= ' on a.cid = s1.cid ';
    		$sql .= ' left join n_datageneral n1 on n1.cid=s1.cid ';
    		$sql .= ' where s1.unitname="'.$u.'" ';
            
            //return $sql;
    	
    		$data = DB::Select( $sql );	    	 
			
			return View::make( 'unitcosts.view_unitcosts_manager', 
		        	array( 'data' => $data )
		        ); 	   	
	    }

	     /**
	    * function name : update_manager
	    * edit update_manager unitcode money
	    * get
	    */
	    public function update_manager($y, $m, $type, $cid, $u_travel, $u_other)
	    {
	    	if($type == 1)
	    	{
	    		//s_salary_detail
	    		//s_salary_ocsc_detail
	    		$user_data = array(            
		            'u_travel' 	=> $u_travel,
		            'u_other' 	 	=> $u_other 		                         		            	                       
		        );         
		      
		        //update user details
		        $result = DB::table( 's_salary_detail' )->where( 'cid', '=', $cid )->where( DB::raw('year(order_date)'), '=', $y )->where( DB::raw('month(order_date)'), '=', $m )->update( $user_data );	        
		        
		        return 'บันทึกเรียบร้อย'; 		        	
	    	}
	    	else
	    	{
	    		//s_salary_ocsc_detail
	    		$user_data = array(            
		            'u_travel' 	=> $u_travel,
		            'u_other' 	 	=> $u_other 		                         		            	                       
		        );         
		      
		        //update user details
		        $result = DB::table( 's_salary_ocsc_detail' )->where( 'cid', '=', $cid )->where( DB::raw('year(order_date)'), '=', $y )->where( DB::raw('month(order_date)'), '=', $m )->update( $user_data );	        
		        
		        return 'บันทึกเรียบร้อย'; 		       		
	    	}
	    }





	}

?>