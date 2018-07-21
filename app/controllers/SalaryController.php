<?php

class SalaryController extends BaseController {


	/**
    * function name : get_datebank
    * get data s_bank_acc from acc_id
    * 
    */
	private function get_datebank( $acc_id )
	{
		$datebank = DB::table('s_bank_acc')
	    	->join('s_bank','s_bank.bank_id','=','s_bank_acc.bank_id')
	    	->where('s_bank_acc.acc_id','=',$acc_id)
	    	->get();    	
	    return $datebank; 
	}







	//================================ Salary emptype 5 ====================================//
	/**
	 * function name : salary_type5
	 * view page home salary_type5
	 * 
	 * get
	*/
	public function salary_type5()
	{	
		if ( Session::get('level') != '' )
		{				
			$sqlp = ' select (select salaryID from n_position_salary where cid=n1.cid order by salaryID desc limit 1)  as salaryID from n_position_salary n1  group by n1.cid order by n1.salaryID ';
			
			//return $sqlp;

			$p = DB::select($sqlp);		
			foreach ($p as $key) {
				$a[] = $key->salaryID;
			}	

			$accall = DB::table( 'n_datageneral' )	  
			->join( 'n_position_salary', 'n_position_salary.cid', '=', 'n_datageneral.cid' ) 
			->leftjoin( 's_salary', 's_salary.cid', '=', 'n_datageneral.cid' ) 
			->leftjoin( 's_bank', 's_bank.bank_id', '=', 's_salary.bank' ) 		
			->where( 'n_position_salary.level', '=', 'ลูกจ้างรายวัน' )
			->where( 'n_datageneral.status', '=', '0' )
			->whereIn( 'n_position_salary.salaryID', $a )
			->select( 'n_datageneral.*', 's_bank.bank_name', 's_salary.bank_acc', 's_salary.salary', 's_salary.salary_other', 's_salary.salary_sso', 's_salary.salary_cpk', 's_salary.tax_id' )
	        ->paginate( 20 );
	      		       
		    return View::make( 'emptype5.salary_home',  array( 'accall' => $accall ) );
		}	
		else
		{
			//return login
    		return View::make( 'login.index' );	
		}				
	}

	/**
    * function name : salary5_post_search
    * search data s_salary
    * post
    */
    public function salary5_post_search()
    {   		  
	   if ( Session::get('level') != '' )
		{
			$search  = Input::get( 'search_salary5' );	    		    			    

			$sqlp = ' select (select salaryID from n_position_salary where cid=n1.cid order by salaryID desc limit 1)  as salaryID from n_position_salary n1  group by n1.cid order by n1.salaryID ';
			$p = DB::select($sqlp);		
			foreach ($p as $key) {
				$a[] = $key->salaryID;
			}	

			if( $search == '' ){
				$accall = DB::table( 'n_datageneral' )	  
				->join( 'n_position_salary', 'n_position_salary.cid', '=', 'n_datageneral.cid' )   
				->leftjoin( 's_salary', 's_salary.cid', '=', 'n_datageneral.cid' ) 
				->leftjoin( 's_bank', 's_bank.bank_id', '=', 's_salary.bank' ) 	
				->where( 'n_position_salary.level', '=', 'ลูกจ้างรายวัน' )	
				->where( 'n_datageneral.status', '=', '0' )						
				->whereIn('n_position_salary.salaryID', $a )			
				->select( 'n_datageneral.*', 's_bank.bank_name', 's_salary.bank_acc', 's_salary.salary', 's_salary.salary_other', 's_salary.salary_sso', 's_salary.salary_cpk', 's_salary.tax_id' )
		        ->paginate( 20 );
			}else{
				$accall = DB::table( 'n_datageneral' )	  
				->join( 'n_position_salary', 'n_position_salary.cid', '=', 'n_datageneral.cid' ) 
				->leftjoin( 's_salary', 's_salary.cid', '=', 'n_datageneral.cid' ) 
				->leftjoin( 's_bank', 's_bank.bank_id', '=', 's_salary.bank' ) 	     	        
				->where( 'n_position_salary.level', '=', "ลูกจ้างรายวัน" )
				->where( 'n_datageneral.status', '=', '0' )	
				->whereIn('n_position_salary.salaryID', $a )	
		        ->where(function($query) use ( $search )
	            {
	                $query->where( 'n_datageneral.fname', 'like', "%$search%" )
	                	  ->orWhere( 'n_datageneral.lname', 'like', "%$search%" )
	                	  ->orWhere( 'n_datageneral.cid', 'like', "%$search%" );	 					     
	            })		       	
				->select( 'n_datageneral.*', 's_bank.bank_name', 's_salary.bank_acc', 's_salary.salary', 's_salary.salary_other', 's_salary.salary_sso', 's_salary.salary_cpk', 's_salary.tax_id' )	        		        		        	       
		        ->paginate( 70 );
			}								
		    return View::make( 'emptype5.salary_home',  array( 'accall' => $accall ) );
		}	
		else
		{
			//return login
    		return View::make( 'login.index' );	
		}	
    }

	/**
	 * function name : fromSalary_type5
	 * view page fromSalary_type5
	 * 
	 * open model form
	*/
	public function fromSalary_type5( $id=null )
	{
		if ( Session::get('level') != '' )
		{					
		  	$dataacc = DB::table('s_bank_acc')
	    	->join('s_bank','s_bank.bank_id','=','s_bank_acc.bank_id')
	    	->where('s_bank_acc.cid','=',$id)
	    	->get();

	    	$datasalary =  DB::table('s_salary')
	    	->join('s_bank','s_bank.bank_id','=','s_salary.bank')
	    	->where('s_salary.cid','=',$id)
	    	->first();    	

	    	if( count( $datasalary ) > 0)
	    	{
	    		return View::make( 'emptype5.salary_fromsalary',
					array(					
						'dataacc' 		=> $dataacc,
						'datasalary' 	=> $datasalary
					)
			 	);	
	    	} 
	    	else
	    	{
	    		return View::make( 'emptype5.salary_fromsalary',
					array(					
						'dataacc' => $dataacc
					)
			 	);	
	    	}			
		}
		else{
			//return login
    		return View::make( 'login.index' );	
		}
	}

	/**
	 * function name : addAccSlary5
	 * view page addAccSlary5
	 * 
	 * add data salary
	*/
	public function addSalary_type5()
	{
		//data form ajax
    	$inputData = Input::get('formData');
	    parse_str($inputData, $formFields);  

	    if( isset($formFields['salarybanktype5']) ){
		    $b = $this->get_datebank( $formFields['salarybanktype5'] );	
		    if( count($b) > 0){
			    foreach ($b as $a) {
			   		$bank 	  		= $a->bank_id;
			   		$bank_acc_id 	= $a->acc_id;
			   	 	$bank_acc 		= $a->bank_acc;
			    }
			}
		}else{
			$bank = '';
			$bank_acc_id = '';
			$bank_acc = '';
		}	

	    $Data = array(
	      'cid'      		=> $formFields['cidSalary5'],	
	      'bank'			=> $bank,
	      'bank_acc_id'		=> $bank_acc_id,
	      'bank_acc'		=> $bank_acc,
	      'salary'			=> $formFields['salary5'],
	      'salary_other'	=> $formFields['salaryother5'],
	      'salary_sso'		=> $formFields['salarysso5'],
	      'salary_cpk'		=> $formFields['salarycpk5'],
	      'tax_id'			=> $formFields['salarytaxid5']
	    );	 	

	    $rules = array(
	    	'bank_acc'   => 'required', 
        	'salary'     =>  'required'      	
	    );

	    $messages = array(
	    	'bank_acc.required' => 'กรุณาเลือกธนาคาร',
	    	'salary.required' => 'กรุณากรอกเงินเดือน'	     		         
	    ); 

	    $validator = Validator::make( $Data, $rules, $messages );
		
	    //check if the form is valid
	    if ( $validator->fails() )
	    {			
	       return Response::json(array(
            'fail' => true,
            'errors' => $validator->getMessageBag()->toArray()
           ));
	    }
	    else
	    {
	    	$acc = DB::insert( 'insert into s_salary ( cid, bank, bank_acc_id, bank_acc, salary, salary_other, salary_sso, salary_cpk, tax_id ) values ( ?, ?, ?, ?, ?, ?, ?, ?, ? )', 
				  array( 
				  	    $Data['cid'],
				  	    $Data['bank'],
				  	    $Data['bank_acc_id'],
				  		$Data['bank_acc'],	
				  		$Data['salary'],
				  		$Data['salary_other'],
				  		$Data['salary_sso'],
				  		$Data['salary_cpk'],
				  		$Data['tax_id']			  			  						  		
				  ) );

	    	if( $acc )
	    	{	 
	    		return Response::json(array(
		          'success' => true,
		          'msg' 	=> 'เพิ่มข้อมูลเรียบร้อยแล้ว'		                   
		        ));
	    	}
	    	else
	    	{
	    		return Response::json(array(
		          'success' => false,
		          'msg' => 'ไม่สามารถบันทึกข้อมูลได้ กรุณาติดต่อผู้ดูแลระบบ'		         
		        ));
	    	}
	    }//end else  
	}

	/**
	 * function name : salary_edit_type5
	 * reciep data post form edit
	 * edit s_salary
	 * post
	*/
    public function salary_edit_type5( $id )
    {    	
    	//data form ajax
    	$inputData = Input::get('formData');
	    parse_str($inputData, $formFields);  

	    if( isset($formFields['salarybanktype5']) ){
		    $b = $this->get_datebank( $formFields['salarybanktype5'] );	
		    if( count($b) > 0){
			    foreach ($b as $a) {
			   		$bank 	  		= $a->bank_id;
			   		$bank_acc_id 	= $a->acc_id;
			   	 	$bank_acc 		= $a->bank_acc;
			    }
			}
		}else{
			$bank = '';
			$bank_acc_id = '';
			$bank_acc = '';
		}	

	    $Data = array(
	      'cid'      		=> $formFields['cidSalary5'],	
	      'bank'			=> $bank,
	      'bank_acc_id'		=> $bank_acc_id,
	      'bank_acc'		=> $bank_acc,
	      'salary'			=> $formFields['salary5'],
	      'salary_other'	=> $formFields['salaryother5'],
	      'salary_sso'		=> $formFields['salarysso5'],
	      'salary_cpk'		=> $formFields['salarycpk5'],
	      'tax_id'			=> $formFields['salarytaxid5']
	    );	 	

	    $rules = array(
	    	'bank_acc'   => 'required', 
        	'salary'     =>  'required'      	
	    );

	    $messages = array(
	    	'bank_acc.required' => 'กรุณาเลือกธนาคาร',
	    	'salary.required' => 'กรุณากรอกเงินเดือน'	     		         
	    ); 

	    $validator = Validator::make( $Data, $rules, $messages );
		
	    //check if the form is valid
	    if ( $validator->fails() )
	    {			
	        return Response::json(array(
            'fail' => true,
            'errors' => $validator->getMessageBag()->toArray()
           ));
	    }
	    else
	    {	 	    	    	
	        $result = DB::table( 's_salary' )->where( 'cid', '=', $id )->update( $Data );	       		    				

           if( $result )
	    	{	 
	    		return Response::json(array(
		          'success' => true,
		          'msg' 	=> 'แก้ไขข้อมูลเรียบร้อยแล้ว'		                   
		        ));
	    	}
	    	else
	    	{
	    		return Response::json(array(
		          'success' => false,
		          'msg' => 'ไม่สามารถแก้ไขข้อมูลได้ กรุณาติดต่อผู้ดูแลระบบ'		         
		        ));
	    	}
        }
    }

    /**
	 * function name : salary_insert_type5
	 * view page home salary_insert_type5
	 * 
	 * get
	*/
	public function salary_insert_type5()
	{	
		if ( Session::get('level') != '' )
		{				
			$sqlp = ' select (select salaryID from n_position_salary where cid=n1.cid order by salaryID desc limit 1)  as salaryID from n_position_salary n1  group by n1.cid order by n1.salaryID ';
			$p = DB::select($sqlp);		
			foreach ($p as $key) {
				$a[] = $key->salaryID;
			}		


			$accall = DB::table( 'n_datageneral' )	  
			->join( 'n_position_salary', 'n_position_salary.cid', '=', 'n_datageneral.cid' ) 
			->leftjoin( 's_salary', 's_salary.cid', '=', 'n_datageneral.cid' ) 
			->leftjoin( 's_salary_detail', 's_salary_detail.cid', '=', 'n_datageneral.cid' )
			->leftjoin( 's_bank', 's_bank.bank_id', '=', 's_salary_detail.bank' ) 		
			->where( 'n_position_salary.level', '=', 'ลูกจ้างรายวัน' )
			->where( 'n_datageneral.status', '=', '0' )
			->whereIn( 'n_position_salary.salaryID', $a )
            ->groupBY( 'n_datageneral.cid' )
			->orderBY( 'n_datageneral.datainfoID','asc' )
			->select( 'n_datageneral.*', 's_salary.bank', 's_salary.bank_acc_id', 's_salary.bank_acc', 's_salary.salary'
			, 's_salary.salary_other', 's_salary.salary_sso', 's_salary.salary_cpk'
			, 's_salary.tax_id',  DB::raw('max(s_salary_detail.order_date) as order_date') 
			, DB::raw('(select ss.ot from s_salary_detail ss where ss.cid=s_salary.cid and year(ss.order_date)=year(NOW())  and month(ss.order_date)=month(NOW()) limit 1) as otck  ')
			, DB::raw('(select ss.salary from s_salary_detail ss where ss.cid=s_salary.cid and year(ss.order_date)=year(NOW())  and month(ss.order_date)=month(NOW()) limit 1) as saralyck  '))
	        ->paginate( 20 );
	      		       
		    return View::make( 'emptype5.salary_insert_home',  array( 'accall' => $accall ) );
		}	
		else
		{
			//return login
    		return View::make( 'login.index' );	
		}				
	}

	/**
	 * function name : fromsalary_insert_type5
	 * view page fromsalary_insert_type5
	 * 
	 * open model form
	*/
	public function fromsalary_insert_type5( $id=null )
	{
		if ( Session::get('level') != '' )
		{					
		  	$dataacc = DB::table('s_salary')
		  	->join('s_bank_acc','s_bank_acc.cid','=','s_salary.cid')
	    	->join('s_bank','s_bank.bank_id','=','s_bank_acc.bank_id')
	    	->where('s_bank_acc.cid','=',$id)
	    	->get();		    	

	    	$sql = ' select s_salary.*, s_bank.*, s_salary_detail.comment, s_salary_detail.save, s_salary_detail.shop, s_salary_detail.rice, ';
	    	$sql .= ' s_salary_detail.water, s_salary_detail.elec, s_salary_detail.other, s_salary_detail.order_date ';
	    	$sql .= ' from s_salary_detail ';
	    	$sql .= ' inner join s_salary on s_salary.cid = s_salary_detail.cid';
	    	$sql .= ' inner join s_bank on s_bank.bank_id = s_salary_detail.bank';
	    	$sql .= ' where s_salary_detail.cid = '.$id.' ';
	    	$sql .= ' and s_salary_detail.order_date = (select max(b.order_date)  from s_salary_detail b where b.cid='.$id.')';	    	

	    	$datasalary = DB::Select($sql);  	 

	    	//เดือนล่าสุด
	    	$m =  DB::table('s_salary_detail')	    	
	    	->where('cid','=',$id)
	    	->max('order_date');	
	    	$date = date_create($m);	   	

	    	if( ( count( $datasalary ) > 0 ) && ( date('m')==date_format($date, 'm') )  )
	    	{
	    		return View::make( 'emptype5.salary_insert_from',
					array(											
						'datasalary' 	=> $datasalary
					)
			 	);	
	    	} 
	    	else
	    	{
	    		return View::make( 'emptype5.salary_insert_from',
					array(					
						'dataacc' => $dataacc
					)
			 	);	
	    	}			
		}
		else{
			//return login
    		return View::make( 'login.index' );	
		}
	}

	/**
	 * function name : salary_add_type5
	 * reciep data post form insert
	 * edit s_salary_detail
	 * post
	*/
    public function salary_add_type5()
    {    	
    	//data form ajax
    	$inputData = Input::get('formData');
	    parse_str($inputData, $formFields);  
	    
	    $Data = array(
	      'cid'      		=> $formFields['cidsalary_insert5'],
	      'bank'			=> $formFields['banksalary_insert5'],	
	      'bank_acc_id'		=> $formFields['bank_acc_idsalary_insert5'],
	      'bank_acc'		=> $formFields['bank_accsalary_insert5'],
	      'salary'			=> $formFields['salarysalary_insert5'],
	      'salary_other'	=> $formFields['salary_othersalary_insert5'],
	      'salary_sso'		=> $formFields['salary_ssosalary_insert5'],
	      'salary_cpk'		=> $formFields['salary_cpksalary_insert5'],
	      'tax_id'			=> $formFields['tax_idsalary_insert5'],  	       
	      'save'			=> $formFields['save5'],
	      'rice'			=> $formFields['rice5'],
	      'elec'			=> $formFields['elec5'],
	      'shop'			=> $formFields['shop5'],
	      'water'			=> $formFields['water5'],
	      'other'			=> $formFields['other5'],
	      'comment'			=> $formFields['comment5'],
	      'order_date'  	=> date('Y-m-d'),
	      'sys_user'		=> Session::get('cid')
	    );	 
        
        $dchk = DB::Select( ' select * from s_salary_detail where cid='.$formFields['cidsalary_insert5'].' and order_date="'.date('Y-m-d').'" ' ); 	  	  	
	  	 if( count( $dchk ) == 0 )
	    {
             
                $result = DB::insert( 'insert into s_salary_detail ( cid, bank, bank_acc_id, bank_acc, salary, salary_other, salary_sso, salary_cpk, tax_id, save, shop, rice, water, elec, other, comment, order_date, sys_user ) values ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? )', 
                          array( 
				  	   $Data['cid'],
				  	    $Data['bank'],
				  	    $Data['bank_acc_id'],
				  		$Data['bank_acc'],	
				  		$Data['salary'],
				  		$Data['salary_other'],
				  		$Data['salary_sso'],
				  		$Data['salary_cpk'],
				  		$Data['tax_id'],	
				  		$Data['save'],
				  		$Data['shop'],
				  		$Data['rice'],
				  		$Data['water'],
				  		$Data['elec'],
				  		$Data['other'],
				  		$Data['comment'],
				  		$Data['order_date'],
				  		$Data['sys_user']	  			  						  		
				  ) );   	    	        		    				

                   if( $result )
                    {	 
                        return Response::json(array(
                          'success' => true,
                          'msg' 	=> 'บันทึกข้อมูลเรียบร้อยแล้ว'		                   
                        ));
                    }
                    else
                    {
                        return Response::json(array(
                          'success' => false,
                          'msg' => 'ไม่สามารถบันทึกข้อมูลได้ กรุณาติดต่อผู้ดูแลระบบ'		         
                        ));
                    }  
         }
        else
    	{
    		return Response::json(array(
	          'success' => false,
	          'msg' => 'ไม่สามารถบันทึกข้อมูลได้ กรุณาติดต่อผู้ดูแลระบบ'		         
		    ));
    	} 
    }

	/**
	 * function name : salary_insert_edit_type5
	 * reciep data post form edit
	 * edit s_salary_detail
	 * post
	*/
    public function salary_insert_edit_type5( $id, $order_date )
    {    	
    	//data form ajax
    	$inputData = Input::get('formData');
	    parse_str($inputData, $formFields);  	    

	    $Data = array(
	      'cid'      		=> $formFields['cidsalary_insert5'],
	      'bank'			=> $formFields['banksalary_insert5'],	
	      'bank_acc_id'		=> $formFields['bank_acc_idsalary_insert5'],
	      'bank_acc'		=> $formFields['bank_accsalary_insert5'],
	      'salary'			=> $formFields['salarysalary_insert5'],
	      'salary_other'	=> $formFields['salary_othersalary_insert5'],
	      'salary_sso'		=> $formFields['salary_ssosalary_insert5'],
	      'salary_cpk'		=> $formFields['salary_cpksalary_insert5'],
	      'tax_id'			=> $formFields['tax_idsalary_insert5'],  	   
	      'save'			=> $formFields['save5'],
	      'rice'			=> $formFields['rice5'],
	      'elec'			=> $formFields['elec5'],
	      'shop'			=> $formFields['shop5'],
	      'water'			=> $formFields['water5'],
	      'other'			=> $formFields['other5'],
	      'comment'			=> $formFields['comment5'],
	      'order_date'  	=> date('Y-m-d'),
	      'sys_user'		=> Session::get('cid')
	    );	 	
	  	 	    	    	
       $result = DB::table( 's_salary_detail' )->where( 'cid', '=', $id )->where('order_date', '=', $order_date)->update( $Data );	       		    			       		    				

       if( $result )
    	{	 
    		return Response::json(array(
	          'success' => true,
	          'msg' 	=> 'แก้ไขข้อมูลเรียบร้อยแล้ว'		                   
	        ));
    	}
    	else
    	{
    		return Response::json(array(
	          'success' => false,
	          'msg' => 'ไม่สามารถแก้ไขข้อมูลได้ กรุณาติดต่อผู้ดูแลระบบ'		         
	        ));
    	}      
    }

    /**
    * function name : salary_insert5_post_search
    * search data s_salary
    * post
    */
    public function salary_insert5_post_search()
    {   		  
	   if ( Session::get('level') != '' )
		{
			$search  = Input::get( 'search_salary_insert5' );	    		    			    

			$sqlp = ' select (select salaryID from n_position_salary where cid=n1.cid order by salaryID desc limit 1)  as salaryID from n_position_salary n1  group by n1.cid order by n1.salaryID ';
			$p = DB::select($sqlp);		
			foreach ($p as $key) {
				$a[] = $key->salaryID;
			}	

			if( $search == '' ){
				$accall = DB::table( 'n_datageneral' )	  
				->join( 'n_position_salary', 'n_position_salary.cid', '=', 'n_datageneral.cid' ) 
				->leftjoin( 's_salary', 's_salary.cid', '=', 'n_datageneral.cid' )
				->leftjoin( 's_salary_detail', 's_salary_detail.cid', '=', 'n_datageneral.cid' ) 
				->leftjoin( 's_bank', 's_bank.bank_id', '=', 's_salary_detail.bank' ) 		
				->where( 'n_position_salary.level', '=', 'ลูกจ้างรายวัน' )	
				->where( 'n_datageneral.status', '=', '0' )						
				->whereIn('n_position_salary.salaryID', $a )	
                ->groupBY( 'n_datageneral.cid' )
				->orderBY( 'n_datageneral.datainfoID','asc' )
				->select( 'n_datageneral.*', 's_salary.bank', 's_salary.bank_acc_id', 's_salary.bank_acc', 's_salary.salary'
				, 's_salary.salary_other', 's_salary.salary_sso', 's_salary.salary_cpk'
				, 's_salary.tax_id',  DB::raw('max(s_salary_detail.order_date) as order_date') 
				, DB::raw('(select ss.ot from s_salary_detail ss where ss.cid=s_salary.cid and year(ss.order_date)=year(NOW())  and month(ss.order_date)=month(NOW()) limit 1) as otck  ')
				, DB::raw('(select ss.salary from s_salary_detail ss where ss.cid=s_salary.cid and year(ss.order_date)=year(NOW())  and month(ss.order_date)=month(NOW()) limit 1) as saralyck  '))
		        ->paginate( 20 );
			}else{
				$accall = DB::table( 'n_datageneral' )	  
				->join( 'n_position_salary', 'n_position_salary.cid', '=', 'n_datageneral.cid' ) 
				->leftjoin( 's_salary', 's_salary.cid', '=', 'n_datageneral.cid' )
				->leftjoin( 's_salary_detail', 's_salary_detail.cid', '=', 'n_datageneral.cid' ) 
				->leftjoin( 's_bank', 's_bank.bank_id', '=', 's_salary_detail.bank' )     	        
				->where( 'n_position_salary.level', '=', "ลูกจ้างรายวัน" )
				->where( 'n_datageneral.status', '=', '0' )	
				->whereIn('n_position_salary.salaryID', $a )	
		        ->where(function($query) use ( $search )
	            {
	                $query->where( 'n_datageneral.fname', 'like', "%$search%" )
	                	  ->orWhere( 'n_datageneral.lname', 'like', "%$search%" )
	                	  ->orWhere( 'n_datageneral.cid', 'like', "%$search%" );	 					     
	            })	
                ->groupBY( 'n_datageneral.cid' )
				->orderBY( 'n_datageneral.datainfoID','asc' )
				->select( 'n_datageneral.*', 's_salary.bank', 's_salary.bank_acc_id', 's_salary.bank_acc', 's_salary.salary'
				, 's_salary.salary_other', 's_salary.salary_sso', 's_salary.salary_cpk'
				, 's_salary.tax_id',  DB::raw('max(s_salary_detail.order_date) as order_date') 
				, DB::raw('(select ss.ot from s_salary_detail ss where ss.cid=s_salary.cid and year(ss.order_date)=year(NOW())  and month(ss.order_date)=month(NOW()) limit 1) as otck  ')
				, DB::raw('(select ss.salary from s_salary_detail ss where ss.cid=s_salary.cid and year(ss.order_date)=year(NOW())  and month(ss.order_date)=month(NOW()) limit 1) as saralyck  '))            		        		        	       
		        ->paginate( 70 );
			}								
		    return View::make( 'emptype5.salary_insert_home',  array( 'accall' => $accall ) );
		}	
		else
		{
			//return login
    		return View::make( 'login.index' );	
		}	
    }







	//============================= Salary emptype 1 ==================================//

	/**
	 * function name : salary_type1
	 * view page home salary_type1
	 * 
	 * get
	*/
	public function salary_type1()
	{	
		if ( Session::get('level') != '' )
		{				
			$sqlp = ' select (select salaryID from n_position_salary where cid=n1.cid order by salaryID desc limit 1)  as salaryID from n_position_salary n1  group by n1.cid order by n1.salaryID ';
			$p = DB::select($sqlp);		
			foreach ($p as $key) {
				$a[] = $key->salaryID;
			}		

			$accall = DB::table( 'n_datageneral' )	  
			->join( 'n_position_salary', 'n_position_salary.cid', '=', 'n_datageneral.cid' ) 
			->leftjoin( 's_salary', 's_salary.cid', '=', 'n_datageneral.cid' ) 
			->leftjoin( 's_bank', 's_bank.bank_id', '=', 's_salary.bank' ) 		
			->where( 'n_position_salary.level', '=', 'พกส.(ปฏิบัติงาน)' )
			->where( 'n_datageneral.status', '=', '0' )
			->whereIn( 'n_position_salary.salaryID', $a )
			->select( 'n_datageneral.*', 's_bank.bank_name', 's_salary.bank_acc', 's_salary.salary', 's_salary.salary_other', 's_salary.salary_sso', 's_salary.salary_cpk', 's_salary.salary_cprt', 's_salary.tax_id' )
	        ->paginate( 20 );
	      		       
		    return View::make( 'emptype1.salary_home',  array( 'accall' => $accall ) );
		}	
		else
		{
			//return login
    		return View::make( 'login.index' );	
		}				
	}

	/**
    * function name : salary1_post_search
    * search data s_salary
    * post
    */
    public function salary1_post_search()
    {   		  
	   if ( Session::get('level') != '' )
		{
			$search  = Input::get( 'search_salary1' );	    		    			    

			$sqlp = ' select (select salaryID from n_position_salary where cid=n1.cid order by salaryID desc limit 1)  as salaryID from n_position_salary n1  group by n1.cid order by n1.salaryID ';
			$p = DB::select($sqlp);		
			foreach ($p as $key) {
				$a[] = $key->salaryID;
			}	

			if( $search == '' ){
				$accall = DB::table( 'n_datageneral' )	  
				->join( 'n_position_salary', 'n_position_salary.cid', '=', 'n_datageneral.cid' )   
				->leftjoin( 's_salary', 's_salary.cid', '=', 'n_datageneral.cid' ) 
				->leftjoin( 's_bank', 's_bank.bank_id', '=', 's_salary.bank' ) 	
				->where( 'n_position_salary.level', '=', 'พกส.(ปฏิบัติงาน)' )	
				->where( 'n_datageneral.status', '=', '0' )						
				->whereIn('n_position_salary.salaryID', $a )			
				->select( 'n_datageneral.*', 's_bank.bank_name', 's_salary.bank_acc', 's_salary.salary', 's_salary.salary_other', 's_salary.salary_sso', 's_salary.salary_cpk', 's_salary.salary_cprt', 's_salary.tax_id' )
		        ->paginate( 20 );
			}else{
				$accall = DB::table( 'n_datageneral' )	  
				->join( 'n_position_salary', 'n_position_salary.cid', '=', 'n_datageneral.cid' ) 
				->leftjoin( 's_salary', 's_salary.cid', '=', 'n_datageneral.cid' ) 
				->leftjoin( 's_bank', 's_bank.bank_id', '=', 's_salary.bank' ) 	     	        
				->where( 'n_position_salary.level', '=', "พกส.(ปฏิบัติงาน)" )
				->where( 'n_datageneral.status', '=', '0' )	
				->whereIn('n_position_salary.salaryID', $a )	
		        ->where(function($query) use ( $search )
	            {
	                $query->where( 'n_datageneral.fname', 'like', "%$search%" )
	                	  ->orWhere( 'n_datageneral.lname', 'like', "%$search%" )
	                	  ->orWhere( 'n_datageneral.cid', 'like', "%$search%" );	 					     
	            })		       	
				->select( 'n_datageneral.*', 's_bank.bank_name', 's_salary.bank_acc', 's_salary.salary', 's_salary.salary_other', 's_salary.salary_sso', 's_salary.salary_cpk', 's_salary.salary_cprt', 's_salary.tax_id' )	        		        		        	       
		        ->paginate( 70 );
			}								
		    return View::make( 'emptype1.salary_home',  array( 'accall' => $accall ) );
		}	
		else
		{
			//return login
    		return View::make( 'login.index' );	
		}	
    }

	/**
	 * function name : fromSalary_type1
	 * view page fromSalary_type1
	 * 
	 * open model form
	*/
	public function fromSalary_type1( $id=null )
	{
		if ( Session::get('level') != '' )
		{					
		  	$dataacc = DB::table('s_bank_acc')
	    	->join('s_bank','s_bank.bank_id','=','s_bank_acc.bank_id')
	    	->where('s_bank_acc.cid','=',$id)
	    	->get();

	    	$datasalary =  DB::table('s_salary')
	    	->join('s_bank','s_bank.bank_id','=','s_salary.bank')
	    	->where('s_salary.cid','=',$id)
	    	->first();    	

	    	if( count( $datasalary ) > 0)
	    	{
	    		return View::make( 'emptype1.salary_fromsalary',
					array(					
						'dataacc' 		=> $dataacc,
						'datasalary' 	=> $datasalary
					)
			 	);	
	    	} 
	    	else
	    	{
	    		return View::make( 'emptype1.salary_fromsalary',
					array(					
						'dataacc' => $dataacc
					)
			 	);	
	    	}			
		}
		else{
			//return login
    		return View::make( 'login.index' );	
		}
	}

	/**
	 * function name : addAccSlary1
	 * view page addAccSlary1
	 * 
	 * add data salary
	*/
	public function addSalary_type1()
	{
		//data form ajax
    	$inputData = Input::get('formData');
	    parse_str($inputData, $formFields);  

	    if( isset($formFields['salarybanktype1']) ){
		    $b = $this->get_datebank( $formFields['salarybanktype1'] );	
		    if( count($b) > 0){
			    foreach ($b as $a) {
			   		$bank 	  		= $a->bank_id;
			   		$bank_acc_id 	= $a->acc_id;
			   	 	$bank_acc 		= $a->bank_acc;
			    }
			}
		}else{
			$bank = '';
			$bank_acc_id = '';
			$bank_acc = '';
		}	

	    $Data = array(
	      'cid'      		=> $formFields['cidSalary1'],	
	      'bank'			=> $bank,
	      'bank_acc_id'		=> $bank_acc_id,
	      'bank_acc'		=> $bank_acc,
	      'salary'			=> $formFields['salary1'],
	      'salary_other'	=> $formFields['salaryother1'],
	      'salary_sso'		=> $formFields['salarysso1'],
		  'salary_cpk'		=> $formFields['salarycpk1'],
		  'salary_cprt'		=> $formFields['salarycprt1'],
	      'tax_id'			=> $formFields['salarytaxid1']
	    );	 	

	    $rules = array(
	    	'bank_acc'   => 'required', 
        	'salary'     =>  'required'      	
	    );

	    $messages = array(
	    	'bank_acc.required' => 'กรุณาเลือกธนาคาร',
	    	'salary.required' => 'กรุณากรอกเงินเดือน'	     		         
	    ); 

	    $validator = Validator::make( $Data, $rules, $messages );
		
	    //check if the form is valid
	    if ( $validator->fails() )
	    {			
	       return Response::json(array(
            'fail' => true,
            'errors' => $validator->getMessageBag()->toArray()
           ));
	    }
	    else
	    {
	    	$acc = DB::insert( 'insert into s_salary ( cid, bank, bank_acc_id, bank_acc, salary, salary_other, salary_sso, salary_cpk, salary_cprt, tax_id ) values ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ? )', 
				  array( 
				  	    $Data['cid'],
				  	    $Data['bank'],
				  	    $Data['bank_acc_id'],
				  		$Data['bank_acc'],	
				  		$Data['salary'],
				  		$Data['salary_other'],
				  		$Data['salary_sso'],
						$Data['salary_cpk'],
						$Data['salary_cprt'],
				  		$Data['tax_id']			  			  						  		
				  ) );

	    	if( $acc )
	    	{	 
	    		return Response::json(array(
		          'success' => true,
		          'msg' 	=> 'เพิ่มข้อมูลเรียบร้อยแล้ว'		                   
		        ));
	    	}
	    	else
	    	{
	    		return Response::json(array(
		          'success' => false,
		          'msg' => 'ไม่สามารถบันทึกข้อมูลได้ กรุณาติดต่อผู้ดูแลระบบ'		         
		        ));
	    	}
	    }//end else  
	}

	/**
	 * function name : salary_edit_type1
	 * reciep data post form edit
	 * edit s_salary
	 * post
	*/
    public function salary_edit_type1( $id )
    {    	
    	//data form ajax
    	$inputData = Input::get('formData');
	    parse_str($inputData, $formFields);  

	    if( isset($formFields['salarybanktype1']) ){
		    $b = $this->get_datebank( $formFields['salarybanktype1'] );	
		    if( count($b) > 0){
			    foreach ($b as $a) {
			   		$bank 	  		= $a->bank_id;
			   		$bank_acc_id 	= $a->acc_id;
			   	 	$bank_acc 		= $a->bank_acc;
			    }
			}
		}else{
			$bank = '';
			$bank_acc_id = '';
			$bank_acc = '';
		}	

	    $Data = array(
	      'cid'      		=> $formFields['cidSalary1'],	
	      'bank'			=> $bank,
	      'bank_acc_id'		=> $bank_acc_id,
	      'bank_acc'		=> $bank_acc,
	      'salary'			=> $formFields['salary1'],
	      'salary_other'	=> $formFields['salaryother1'],
	      'salary_sso'		=> $formFields['salarysso1'],
		  'salary_cpk'		=> $formFields['salarycpk1'],
		  'salary_cprt'		=> $formFields['salarycprt1'],
	      'tax_id'			=> $formFields['salarytaxid1']
	    );	 	

	    $rules = array(
	    	'bank_acc'   => 'required', 
        	'salary'     =>  'required'      	
	    );

	    $messages = array(
	    	'bank_acc.required' => 'กรุณาเลือกธนาคาร',
	    	'salary.required' => 'กรุณากรอกเงินเดือน'	     		         
	    ); 

	    $validator = Validator::make( $Data, $rules, $messages );
		
	    //check if the form is valid
	    if ( $validator->fails() )
	    {			
	        return Response::json(array(
            'fail' => true,
            'errors' => $validator->getMessageBag()->toArray()
           ));
	    }
	    else
	    {	 	    	    	
	        $result = DB::table( 's_salary' )->where( 'cid', '=', $id )->update( $Data );	       		    				

           if( $result )
	    	{	 
	    		return Response::json(array(
		          'success' => true,
		          'msg' 	=> 'แก้ไขข้อมูลเรียบร้อยแล้ว'		                   
		        ));
	    	}
	    	else
	    	{
	    		return Response::json(array(
		          'success' => false,
		          'msg' => 'ไม่สามารถแก้ไขข้อมูลได้ กรุณาติดต่อผู้ดูแลระบบ'		         
		        ));
	    	}
        }
    }

	/**
	 * function name : salary_insert_type1
	 * view page home salary_insert_type1
	 * 
	 * get
	*/
	public function salary_insert_type1()
	{	
		if ( Session::get('level') != '' )
		{				
			$sqlp = ' select (select salaryID from n_position_salary where cid=n1.cid order by salaryID desc limit 1)  as salaryID from n_position_salary n1  group by n1.cid order by n1.salaryID ';
			$p = DB::select($sqlp);		
			foreach ($p as $key) {
				$a[] = $key->salaryID;
			}		

			$accall = DB::table( 'n_datageneral' )	  
			->join( 'n_position_salary', 'n_position_salary.cid', '=', 'n_datageneral.cid' ) 
			->leftjoin( 's_salary', 's_salary.cid', '=', 'n_datageneral.cid' )
			->leftjoin( 's_salary_detail', 's_salary_detail.cid', '=', 'n_datageneral.cid' ) 
			->leftjoin( 's_bank', 's_bank.bank_id', '=', 's_salary_detail.bank' ) 		
			->where( 'n_position_salary.level', '=', 'พกส.(ปฏิบัติงาน)' )
			->where( 'n_datageneral.status', '=', '0' )
			->whereIn( 'n_position_salary.salaryID', $a )
			->groupBY( 'n_datageneral.cid' )
			->orderBY( 'n_datageneral.datainfoID','asc' )
			->select( 'n_datageneral.*', 's_salary.bank', 's_salary.bank_acc_id', 's_salary.bank_acc'
			, 's_salary.salary', 's_salary.salary_other', 's_salary.salary_sso', 's_salary.salary_cpk', 's_salary.salary_cprt'
			, 's_salary.tax_id',  DB::raw('max(s_salary_detail.order_date) as order_date')
			, DB::raw('(select ss.ot from s_salary_detail ss where ss.cid=s_salary.cid and year(ss.order_date)=year(NOW())  and month(ss.order_date)=month(NOW()) limit 1) as otck  ')
			, DB::raw('(select ss.salary from s_salary_detail ss where ss.cid=s_salary.cid and year(ss.order_date)=year(NOW())  and month(ss.order_date)=month(NOW()) limit 1) as saralyck  ') )
	        ->paginate( 20 );
	      		       
		    return View::make( 'emptype1.salary_insert_home',  array( 'accall' => $accall ) );
		}	
		else
		{
			//return login
    		return View::make( 'login.index' );	
		}				
	}

	/**
	 * function name : fromsalary_insert_type1
	 * view page fromsalary_insert_type1
	 * 
	 * open model form
	*/
	public function fromsalary_insert_type1( $id=null )
	{
		if ( Session::get('level') != '' )
		{					
		  	$dataacc = DB::table('s_salary')
		  	->join('s_bank_acc', 's_bank_acc.cid', '=', 's_salary.cid')
	    	->join('s_bank', 's_bank.bank_id', '=', 's_bank_acc.bank_id')
	    	->where('s_bank_acc.cid','=',$id)
	    	->get();	    		    	

	    	$sql = ' select s_salary.*, s_bank.*, s_salary_detail.comment, s_salary_detail.save, s_salary_detail.shop, s_salary_detail.rice, ';
	    	$sql .= ' s_salary_detail.water, s_salary_detail.elec, s_salary_detail.cprt, s_salary_detail.other, s_salary_detail.order_date ';
	    	$sql .= ' from s_salary_detail ';
	    	$sql .= ' inner join s_salary on s_salary.cid = s_salary_detail.cid';
	    	$sql .= ' inner join s_bank on s_bank.bank_id = s_salary_detail.bank';
	    	$sql .= ' where s_salary_detail.cid = '.$id.' ';
	    	$sql .= ' and s_salary_detail.order_date = (select max(b.order_date)  from s_salary_detail b where b.cid='.$id.')';	    	

	    	$datasalary = DB::Select($sql);    		    	     
                       
	    	//เดือนล่าสุด
	    	$m =  DB::table('s_salary_detail')	    	
	    	->where('cid','=',$id)
	    	->max('order_date');	
	    	$date = date_create($m);	    	

	    	if( ( count( $datasalary ) > 0 ) && ( date('m')==date_format($date, 'm') )  )
	    	{	    		
	    		return View::make( 'emptype1.salary_insert_from',
					array(											
						'datasalary' 	=> $datasalary
					)
			 	);	
	    	} 
	    	else
	    	{	    		
	    		return View::make( 'emptype1.salary_insert_from',
					array(					
						'dataacc' => $dataacc						
					)
			 	);	
	    	}			
		}
		else{
			//return login
    		return View::make( 'login.index' );	
		}
	}

	/**
	 * function name : salary_add_type1
	 * reciep data post form insert
	 * edit s_salary_detail
	 * post
	*/
    public function salary_add_type1()
    {    	
    	//data form ajax
    	$inputData = Input::get('formData');
	    parse_str($inputData, $formFields);  
	    
	    $Data = array(
	      'cid'      		=> $formFields['cidsalary_insert1'],
	      'bank'			=> $formFields['banksalary_insert1'],	
	      'bank_acc_id'		=> $formFields['bank_acc_idsalary_insert1'],
	      'bank_acc'		=> $formFields['bank_accsalary_insert1'],
	      'salary'			=> $formFields['salarysalary_insert1'],
	      'salary_other'	=> $formFields['salary_othersalary_insert1'],
	      'salary_sso'		=> $formFields['salary_ssosalary_insert1'],
	      'salary_cpk'		=> $formFields['salary_cpksalary_insert1'],
	      'tax_id'			=> $formFields['tax_idsalary_insert1'],  	       
	      'save'			=> $formFields['save1'],
	      'rice'			=> $formFields['rice1'],
		  'elec'			=> $formFields['elec1'],
		  'cprt'			=> $formFields['salary_cprtsalary_insert1'],
	      'shop'			=> $formFields['shop1'],
	      'water'			=> $formFields['water1'],
	      'other'			=> $formFields['other1'],
	      'comment'			=> $formFields['comment1'],
	      'order_date'  	=> date('Y-m-d'),
	      'sys_user'		=> Session::get('cid')
	    );	

	    $dchk = DB::Select( ' select * from s_salary_detail where cid='.$formFields['cidsalary_insert1'].' and order_date="'.date('Y-m-d').'" ' ); 	  
	  	
	    if( count( $dchk ) == 0 )
	    {
		  	$result = DB::insert( 'insert into s_salary_detail ( cid, bank, bank_acc_id, bank_acc, salary, salary_other, salary_sso, salary_cpk, tax_id, save, shop, rice, water, elec, cprt, other, comment, order_date, sys_user ) values ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? )', 
					  array( 
					  	    $Data['cid'],
					  	    $Data['bank'],
					  	    $Data['bank_acc_id'],
					  		$Data['bank_acc'],	
					  		$Data['salary'],
					  		$Data['salary_other'],
					  		$Data['salary_sso'],
					  		$Data['salary_cpk'],
					  		$Data['tax_id'],	
					  		$Data['save'],
					  		$Data['shop'],
					  		$Data['rice'],
					  		$Data['water'],
							$Data['elec'],
							$Data['cprt'],
					  		$Data['other'],
					  		$Data['comment'],
					  		$Data['order_date'],
					  		$Data['sys_user']		  			  						  		
					  ) );   	    	        		    				

	       if( $result )
	    	{	 
	    		return Response::json(array(
		          'success' => true,
		          'msg' 	=> 'บันทึกข้อมูลเรียบร้อยแล้ว'		                   
		        ));
	    	}
	    	else
	    	{
	    		return Response::json(array(
		          'success' => false,
		          'msg' => 'ไม่สามารถบันทึกข้อมูลได้ กรุณาติดต่อผู้ดูแลระบบ'		         
		        ));
	    	} 
    	}
    	else
    	{
    		return Response::json(array(
	          'success' => false,
	          'msg' => 'ไม่สามารถบันทึกข้อมูลได้ กรุณาติดต่อผู้ดูแลระบบ'		         
		    ));
    	}     
    }

	/**
	 * function name : salary_insert_edit_type1
	 * reciep data post form edit
	 * edit s_salary_detail
	 * post
	*/
    public function salary_insert_edit_type1( $id, $order_date )
    {    	
    	//data form ajax
    	$inputData = Input::get('formData');
	    parse_str($inputData, $formFields);  

	    $Data = array(
	      'cid'      		=> $formFields['cidsalary_insert1'],
	      'bank'			=> $formFields['banksalary_insert1'],	
	      'bank_acc_id'		=> $formFields['bank_acc_idsalary_insert1'],
	      'bank_acc'		=> $formFields['bank_accsalary_insert1'],
	      'salary'			=> $formFields['salarysalary_insert1'],
	      'salary_other'	=> $formFields['salary_othersalary_insert1'],
	      'salary_sso'		=> $formFields['salary_ssosalary_insert1'],
	      'salary_cpk'		=> $formFields['salary_cpksalary_insert1'],
	      'tax_id'			=> $formFields['tax_idsalary_insert1'],  
	      'save'			=> $formFields['save1'],
	      'rice'			=> $formFields['rice1'],
		  'elec'			=> $formFields['elec1'],
		  'cprt'			=> $formFields['salary_cprtsalary_insert1'],
	      'shop'			=> $formFields['shop1'],
	      'water'			=> $formFields['water1'],
	      'other'			=> $formFields['other1'],
	      'comment'			=> $formFields['comment1'],
	      'order_date'  	=> date('Y-m-d'),
	      'sys_user'		=> Session::get('cid')
	    );	 	
    	 	    	    	
       $result = DB::table( 's_salary_detail' )->where( 'cid', '=', $id )->where('order_date', '=', $order_date)->update( $Data );	       		    				
       if( $result )
    	{	 
    		return Response::json(array(
	          'success' => true,
	          'msg' 	=> 'แก้ไขข้อมูลเรียบร้อยแล้ว'		                   
	        ));
    	}
    	else
    	{
    		return Response::json(array(
	          'success' => false,
	          'msg' => 'ไม่สามารถแก้ไขข้อมูลได้ กรุณาติดต่อผู้ดูแลระบบ'		         
	        ));
    	}      
    }

    /**
    * function name : salary_insert1_post_search
    * search data s_salary_detail
    * post
    */
    public function salary_insert1_post_search()
    {   		  
	   if ( Session::get('level') != '' )
		{
			$search  = Input::get( 'search_salary_insert1' );	    		    			    

			$sqlp = ' select (select salaryID from n_position_salary where cid=n1.cid order by salaryID desc limit 1)  as salaryID from n_position_salary n1  group by n1.cid order by n1.salaryID ';
			$p = DB::select($sqlp);		
			foreach ($p as $key) {
				$a[] = $key->salaryID;
			}	

			if( $search == '' ){
				$accall = DB::table( 'n_datageneral' )	  
				->join( 'n_position_salary', 'n_position_salary.cid', '=', 'n_datageneral.cid' ) 
				->leftjoin( 's_salary', 's_salary.cid', '=', 'n_datageneral.cid' )
				->leftjoin( 's_salary_detail', 's_salary_detail.cid', '=', 'n_datageneral.cid' ) 
				->leftjoin( 's_bank', 's_bank.bank_id', '=', 's_salary_detail.bank' ) 		
				->where( 'n_position_salary.level', '=', 'พกส.(ปฏิบัติงาน)' )
				->where( 'n_datageneral.status', '=', '0' )
				->whereIn( 'n_position_salary.salaryID', $a )
				->groupBY( 'n_datageneral.cid' )
				->orderBY( 'n_datageneral.datainfoID','asc' )
				->select( 'n_datageneral.*', 's_salary.bank', 's_salary.bank_acc_id', 's_salary.bank_acc'
				, 's_salary.salary', 's_salary.salary_other', 's_salary.salary_sso', 's_salary.salary_cpk'
				, 's_salary.tax_id',  DB::raw('max(s_salary_detail.order_date) as order_date')
				, DB::raw('(select ss.ot from s_salary_detail ss where ss.cid=s_salary.cid and year(ss.order_date)=year(NOW())  and month(ss.order_date)=month(NOW()) limit 1) as otck  ')
				, DB::raw('(select ss.salary from s_salary_detail ss where ss.cid=s_salary.cid and year(ss.order_date)=year(NOW())  and month(ss.order_date)=month(NOW()) limit 1) as saralyck  ') )
		        ->paginate( 20 );
			}else{
				$accall = DB::table( 'n_datageneral' )	  
				->join( 'n_position_salary', 'n_position_salary.cid', '=', 'n_datageneral.cid' ) 
				->leftjoin( 's_salary', 's_salary.cid', '=', 'n_datageneral.cid' )
				->leftjoin( 's_salary_detail', 's_salary_detail.cid', '=', 'n_datageneral.cid' ) 
				->leftjoin( 's_bank', 's_bank.bank_id', '=', 's_salary_detail.bank' ) 	     	        
				->where( 'n_position_salary.level', '=', "พกส.(ปฏิบัติงาน)" )
				->where( 'n_datageneral.status', '=', '0' )	
				->whereIn('n_position_salary.salaryID', $a )	
		        ->where(function($query) use ( $search )
	            {
	                $query->where( 'n_datageneral.fname', 'like', "%$search%" )
	                	  ->orWhere( 'n_datageneral.lname', 'like', "%$search%" )
	                	  ->orWhere( 'n_datageneral.cid', 'like', "%$search%" );	 					     
	            })
                ->groupBY( 'n_datageneral.cid' )
				->orderBY( 'n_datageneral.datainfoID','asc' )
				->select( 'n_datageneral.*', 's_salary.bank', 's_salary.bank_acc_id', 's_salary.bank_acc'
				, 's_salary.salary', 's_salary.salary_other', 's_salary.salary_sso', 's_salary.salary_cpk'
				, 's_salary.tax_id',  DB::raw('max(s_salary_detail.order_date) as order_date')
				, DB::raw('(select ss.ot from s_salary_detail ss where ss.cid=s_salary.cid and year(ss.order_date)=year(NOW())  and month(ss.order_date)=month(NOW()) limit 1) as otck  ')
				, DB::raw('(select ss.salary from s_salary_detail ss where ss.cid=s_salary.cid and year(ss.order_date)=year(NOW())  and month(ss.order_date)=month(NOW()) limit 1) as saralyck  ') )        		        		        	       
		        ->paginate( 70 );
			}								
		    return View::make( 'emptype1.salary_insert_home',  array( 'accall' => $accall ) );
		}	
		else
		{
			//return login
    		return View::make( 'login.index' );	
		}	
    }

    /**
    * function name : salary_auto_emptype1
    * data s_salary_detail
    * get
    */
    public function salary_auto_emptype1()
    {
    	if ( Session::get('level') != '' )
    	{
			return View::make( 'emptype1.salary_auto' );
    	}
    	else
    	{
			return View::make( 'login.index' );	
    	}   	
    }

    /**
    * function name : salary_auto_add_emptype1
    * data s_salary_detail
    * get
    */
    public function salary_auto_add_emptype1()
    {
    	$sql1  = ' select cid, max(order_date) as order_date from s_salary_detail where cid not in (select cid from n_datageneral where status=1) group by cid';
    	$data1 = DB::Select( $sql1 );

    	foreach ($data1 as $k1) 
    	{
    		$status = 'no';
    		$m = date("m", strtotime( $k1->order_date ));
    		$y = date("Y", strtotime( $k1->order_date ));   		

    		if( date('m') == '01' )
    		{
    			if( (date('Y')-1) == $y && $m == '12' )
    			{
    				$status = 'ok';
    				$m = 12;
    				$y = (date('Y')-1);
    			}
    		}
    		else
    		{
    			if( date('Y') == $y && (date('m')-1) == $m )
    			{
    				$status = 'ok';
    				$m = (date('m')-1);
    				$y = $y;
    			}
    		}   		
    		
    		if( $status == 'ok' )
    		{
    			$sql2  = ' select * from s_salary_detail';
	    		$sql2 .= ' where cid='.$k1->cid;
	    		$sql2 .= ' and year(order_date)='.$y;
	    		$sql2 .= ' and month(order_date)='.$m;

	    		$data2 = DB::Select( $sql2 );

	    		foreach ($data2 as $k2) 
	    		{	    			
	    			$result = DB::insert( 'insert into s_salary_detail ( cid, bank, bank_acc_id, bank_acc, salary, salary_other, salary_sso, salary_cpk, tax_id, save, shop, rice, water, elec, cprt, other, comment, order_date, sys_user ) values ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? )', 
					  array( 
					  	    $k2->cid,
					  	    $k2->bank,
					  	    $k2->bank_acc_id,
					  		$k2->bank_acc,	
					  		$k2->salary,
					  		0,//salary_other
					  		$k2->salary_sso,
					  		$k2->salary_cpk,
					  		$k2->tax_id,	
					  		$k2->save,
					  		$k2->shop,
					  		$k2->rice,
					  		0,//water
							0,//elec
							0,//cprt
					  		$k2->other,
					  		'',//comment
					  		date('Y-m-d'),//order_date
					  		$k2->sys_user		  			  						  		
					  ) ); 
	    		}// end data2 
    		}// end status  		
    	}// end data1
    }








	//================================ Salary emptype 4 ====================================//
	/**
	 * function name : salary_type4
	 * view page home salary_type4
	 * 
	 * get
	*/
	public function salary_type4()
	{	
		if ( Session::get('level') != '' )
		{				
			$sqlp = ' select (select salaryID from n_position_salary where cid=n1.cid order by salaryID desc limit 1)  as salaryID from n_position_salary n1  group by n1.cid order by n1.salaryID ';
			$p = DB::select($sqlp);		
			foreach ($p as $key) {
				$a[] = $key->salaryID;
			}		

			$accall = DB::table( 'n_datageneral' )	  
			->join( 'n_position_salary', 'n_position_salary.cid', '=', 'n_datageneral.cid' ) 
			->leftjoin( 's_salary', 's_salary.cid', '=', 'n_datageneral.cid' ) 
			->leftjoin( 's_bank', 's_bank.bank_id', '=', 's_salary.bank' ) 		
			->where( 'n_position_salary.level', '=', 'ลูกจ้างชั่วคราว' )
			->where( 'n_datageneral.status', '=', '0' )
			->whereIn( 'n_position_salary.salaryID', $a )
			->select( 'n_datageneral.*', 's_bank.bank_name', 's_salary.bank_acc', 's_salary.salary', 's_salary.salary_other', 's_salary.salary_sso', 's_salary.salary_cpk', 's_salary.salary_cprt', 's_salary.tax_id' )
	        ->paginate( 20 );
	      		       
		    return View::make( 'emptype4.salary_home',  array( 'accall' => $accall ) );
		}	
		else
		{
			//return login
    		return View::make( 'login.index' );	
		}				
	}

	/**
    * function name : salary4_post_search
    * search data s_salary
    * post
    */
    public function salary4_post_search()
    {   		  
	   if ( Session::get('level') != '' )
		{
			$search  = Input::get( 'search_salary4' );	    		    			    

			$sqlp = ' select (select salaryID from n_position_salary where cid=n1.cid order by salaryID desc limit 1)  as salaryID from n_position_salary n1  group by n1.cid order by n1.salaryID ';
			$p = DB::select($sqlp);		
			foreach ($p as $key) {
				$a[] = $key->salaryID;
			}	

			if( $search == '' ){
				$accall = DB::table( 'n_datageneral' )	  
				->join( 'n_position_salary', 'n_position_salary.cid', '=', 'n_datageneral.cid' )   
				->leftjoin( 's_salary', 's_salary.cid', '=', 'n_datageneral.cid' ) 
				->leftjoin( 's_bank', 's_bank.bank_id', '=', 's_salary.bank' ) 	
				->where( 'n_position_salary.level', '=', 'ลูกจ้างชั่วคราว' )	
				->where( 'n_datageneral.status', '=', '0' )						
				->whereIn('n_position_salary.salaryID', $a )			
				->select( 'n_datageneral.*', 's_bank.bank_name', 's_salary.bank_acc', 's_salary.salary', 's_salary.salary_other', 's_salary.salary_sso', 's_salary.salary_cpk', 's_salary.salary_cprt', 's_salary.tax_id' )
		        ->paginate( 20 );
			}else{
				$accall = DB::table( 'n_datageneral' )	  
				->join( 'n_position_salary', 'n_position_salary.cid', '=', 'n_datageneral.cid' ) 
				->leftjoin( 's_salary', 's_salary.cid', '=', 'n_datageneral.cid' ) 
				->leftjoin( 's_bank', 's_bank.bank_id', '=', 's_salary.bank' ) 	     	        
				->where( 'n_position_salary.level', '=', "ลูกจ้างชั่วคราว" )
				->where( 'n_datageneral.status', '=', '0' )	
				->whereIn('n_position_salary.salaryID', $a )	
		        ->where(function($query) use ( $search )
	            {
	                $query->where( 'n_datageneral.fname', 'like', "%$search%" )
	                	  ->orWhere( 'n_datageneral.lname', 'like', "%$search%" )
	                	  ->orWhere( 'n_datageneral.cid', 'like', "%$search%" );	 					     
	            })		       	
				->select( 'n_datageneral.*', 's_bank.bank_name', 's_salary.bank_acc', 's_salary.salary', 's_salary.salary_other', 's_salary.salary_sso', 's_salary.salary_cpk', 's_salary.salary_cprt', 's_salary.tax_id' )	        		        		        	       
		        ->paginate( 70 );
			}								
		    return View::make( 'emptype4.salary_home',  array( 'accall' => $accall ) );
		}	
		else
		{
			//return login
    		return View::make( 'login.index' );	
		}	
    }

	/**
	 * function name : fromSalary_type4
	 * view page fromSalary_type4
	 * 
	 * open model form
	*/
	public function fromSalary_type4( $id=null )
	{
		if ( Session::get('level') != '' )
		{					
		  	$dataacc = DB::table('s_bank_acc')
	    	->join('s_bank','s_bank.bank_id','=','s_bank_acc.bank_id')
	    	->where('s_bank_acc.cid','=',$id)
	    	->get();

	    	$datasalary =  DB::table('s_salary')
	    	->join('s_bank','s_bank.bank_id','=','s_salary.bank')
	    	->where('s_salary.cid','=',$id)
	    	->first();    	

	    	if( count( $datasalary ) > 0)
	    	{
	    		return View::make( 'emptype4.salary_fromsalary',
					array(					
						'dataacc' 		=> $dataacc,
						'datasalary' 	=> $datasalary
					)
			 	);	
	    	} 
	    	else
	    	{
	    		return View::make( 'emptype4.salary_fromsalary',
					array(					
						'dataacc' => $dataacc
					)
			 	);	
	    	}			
		}
		else{
			//return login
    		return View::make( 'login.index' );	
		}
	}

	/**
	 * function name : addAccSlary4
	 * view page addAccSlary4
	 * 
	 * add data salary
	*/
	public function addSalary_type4()
	{
		//data form ajax
    	$inputData = Input::get('formData');
	    parse_str($inputData, $formFields);  

	    if( isset($formFields['salarybanktype4']) ){
		    $b = $this->get_datebank( $formFields['salarybanktype4'] );	
		    if( count($b) > 0){
			    foreach ($b as $a) {
			   		$bank 	  		= $a->bank_id;
			   		$bank_acc_id 	= $a->acc_id;
			   	 	$bank_acc 		= $a->bank_acc;
			    }
			}
		}else{
			$bank = '';
			$bank_acc_id = '';
			$bank_acc = '';
		}	

	    $Data = array(
	      'cid'      		=> $formFields['cidSalary4'],	
	      'bank'			=> $bank,
	      'bank_acc_id'		=> $bank_acc_id,
	      'bank_acc'		=> $bank_acc,
	      'salary'			=> $formFields['salary4'],
	      'salary_other'	=> $formFields['salaryother4'],
	      'salary_sso'		=> $formFields['salarysso4'],
		  'salary_cpk'		=> $formFields['salarycpk4'],
		  'salary_cprt'		=> $formFields['salarycprt4'],
	      'tax_id'			=> $formFields['salarytaxid4']
	    );	 	

	    $rules = array(
	    	'bank_acc'   => 'required', 
        	'salary'     =>  'required'      	
	    );

	    $messages = array(
	    	'bank_acc.required' => 'กรุณาเลือกธนาคาร',
	    	'salary.required' => 'กรุณากรอกเงินเดือน'	     		         
	    ); 

	    $validator = Validator::make( $Data, $rules, $messages );
		
	    //check if the form is valid
	    if ( $validator->fails() )
	    {			
	       return Response::json(array(
            'fail' => true,
            'errors' => $validator->getMessageBag()->toArray()
           ));
	    }
	    else
	    {
	    	$acc = DB::insert( 'insert into s_salary ( cid, bank, bank_acc_id, bank_acc, salary, salary_other, salary_sso, salary_cpk, salary_cprt, tax_id ) values ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ? )', 
				  array( 
				  	    $Data['cid'],
				  	    $Data['bank'],
				  	    $Data['bank_acc_id'],
				  		$Data['bank_acc'],	
				  		$Data['salary'],
				  		$Data['salary_other'],
				  		$Data['salary_sso'],
						$Data['salary_cpk'],
						$Data['salary_cprt'],
				  		$Data['tax_id']			  			  						  		
				  ) );

	    	if( $acc )
	    	{	 
	    		return Response::json(array(
		          'success' => true,
		          'msg' 	=> 'เพิ่มข้อมูลเรียบร้อยแล้ว'		                   
		        ));
	    	}
	    	else
	    	{
	    		return Response::json(array(
		          'success' => false,
		          'msg' => 'ไม่สามารถบันทึกข้อมูลได้ กรุณาติดต่อผู้ดูแลระบบ'		         
		        ));
	    	}
	    }//end else  
	}

	/**
	 * function name : salary_edit_type4
	 * reciep data post form edit
	 * edit s_salary
	 * post
	*/
    public function salary_edit_type4( $id )
    {    	
    	//data form ajax
    	$inputData = Input::get('formData');
	    parse_str($inputData, $formFields);  

	    if( isset($formFields['salarybanktype4']) ){
		    $b = $this->get_datebank( $formFields['salarybanktype4'] );	
		    if( count($b) > 0){
			    foreach ($b as $a) {
			   		$bank 	  		= $a->bank_id;
			   		$bank_acc_id 	= $a->acc_id;
			   	 	$bank_acc 		= $a->bank_acc;
			    }
			}
		}else{
			$bank = '';
			$bank_acc_id = '';
			$bank_acc = '';
		}	

	    $Data = array(
	      'cid'      		=> $formFields['cidSalary4'],	
	      'bank'			=> $bank,
	      'bank_acc_id'		=> $bank_acc_id,
	      'bank_acc'		=> $bank_acc,
	      'salary'			=> $formFields['salary4'],
	      'salary_other'	=> $formFields['salaryother4'],
	      'salary_sso'		=> $formFields['salarysso4'],
		  'salary_cpk'		=> $formFields['salarycpk4'],
		  'salary_cprt'		=> $formFields['salarycprt4'],
	      'tax_id'			=> $formFields['salarytaxid4']
	    );	 	

	    $rules = array(
	    	'bank_acc'   => 'required', 
        	'salary'     =>  'required'      	
	    );

	    $messages = array(
	    	'bank_acc.required' => 'กรุณาเลือกธนาคาร',
	    	'salary.required' => 'กรุณากรอกเงินเดือน'	     		         
	    ); 

	    $validator = Validator::make( $Data, $rules, $messages );
		
	    //check if the form is valid
	    if ( $validator->fails() )
	    {			
	        return Response::json(array(
            'fail' => true,
            'errors' => $validator->getMessageBag()->toArray()
           ));
	    }
	    else
	    {	 	    	    	
	        $result = DB::table( 's_salary' )->where( 'cid', '=', $id )->update( $Data );	       		    				

           if( $result )
	    	{	 
	    		return Response::json(array(
		          'success' => true,
		          'msg' 	=> 'แก้ไขข้อมูลเรียบร้อยแล้ว'		                   
		        ));
	    	}
	    	else
	    	{
	    		return Response::json(array(
		          'success' => false,
		          'msg' => 'ไม่สามารถแก้ไขข้อมูลได้ กรุณาติดต่อผู้ดูแลระบบ'		         
		        ));
	    	}
        }
    }

    /**
	 * function name : salary_insert_type4
	 * view page home salary_insert_type4
	 * 
	 * get
	*/
	public function salary_insert_type4()
	{	
		if ( Session::get('level') != '' )
		{				
			$sqlp = ' select (select salaryID from n_position_salary where cid=n1.cid order by salaryID desc limit 1)  as salaryID from n_position_salary n1  group by n1.cid order by n1.salaryID ';
			$p = DB::select($sqlp);		
			foreach ($p as $key) {
				$a[] = $key->salaryID;
			}		

			$accall = DB::table( 'n_datageneral' )	  
			->join( 'n_position_salary', 'n_position_salary.cid', '=', 'n_datageneral.cid' ) 
			->leftjoin( 's_salary', 's_salary.cid', '=', 'n_datageneral.cid' ) 
			->leftjoin( 's_salary_detail', 's_salary_detail.cid', '=', 'n_datageneral.cid' )
			->leftjoin( 's_bank', 's_bank.bank_id', '=', 's_salary_detail.bank' ) 		
			->where( 'n_position_salary.level', '=', 'ลูกจ้างชั่วคราว' )
			->where( 'n_datageneral.status', '=', '0' )
			->whereIn( 'n_position_salary.salaryID', $a )
            ->groupBY( 'n_datageneral.cid' )
			->orderBY( 'n_datageneral.datainfoID','asc' )
			->select( 'n_datageneral.*', 's_salary.bank', 's_salary.bank_acc_id', 's_salary.bank_acc', 's_salary.salary'
			, 's_salary.salary_other', 's_salary.salary_sso', 's_salary.salary_cpk', 's_salary.salary_cprt'
			, 's_salary.tax_id',  DB::raw('max(s_salary_detail.order_date) as order_date') 
			, DB::raw('(select ss.ot from s_salary_detail ss where ss.cid=s_salary.cid and year(ss.order_date)=year(NOW())  and month(ss.order_date)=month(NOW()) limit 1) as otck  ') 
			, DB::raw('(select ss.salary from s_salary_detail ss where ss.cid=s_salary.cid and year(ss.order_date)=year(NOW())  and month(ss.order_date)=month(NOW()) limit 1) as saralyck  '))
	        ->paginate( 20 );
	      		       
		    return View::make( 'emptype4.salary_insert_home',  array( 'accall' => $accall ) );
		}	
		else
		{
			//return login
    		return View::make( 'login.index' );	
		}				
	}

	/**
	 * function name : fromsalary_insert_type4
	 * view page fromsalary_insert_type4
	 * 
	 * open model form
	*/
	public function fromsalary_insert_type4( $id=null )
	{
		if ( Session::get('level') != '' )
		{					
		  	$dataacc = DB::table('s_salary')
		  	->join('s_bank_acc','s_bank_acc.cid','=','s_salary.cid')
	    	->join('s_bank','s_bank.bank_id','=','s_bank_acc.bank_id')
	    	->where('s_bank_acc.cid','=',$id)
	    	->get();		    	

	    	$sql = ' select s_salary.*, s_bank.*, s_salary_detail.comment, s_salary_detail.save, s_salary_detail.shop, s_salary_detail.rice, ';
	    	$sql .= ' s_salary_detail.water, s_salary_detail.elec, s_salary_detail.cprt, s_salary_detail.other, s_salary_detail.order_date ';
	    	$sql .= ' from s_salary_detail ';
	    	$sql .= ' inner join s_salary on s_salary.cid = s_salary_detail.cid';
	    	$sql .= ' inner join s_bank on s_bank.bank_id = s_salary_detail.bank';
	    	$sql .= ' where s_salary_detail.cid = '.$id.' ';
	    	$sql .= ' and s_salary_detail.order_date = (select max(b.order_date)  from s_salary_detail b where b.cid='.$id.')';	    	

	    	$datasalary = DB::Select($sql);  	 

	    	//เดือนล่าสุด
	    	$m =  DB::table('s_salary_detail')	    	
	    	->where('cid','=',$id)
	    	->max('order_date');	
	    	$date = date_create($m);	   	

	    	if( ( count( $datasalary ) > 0 ) && ( date('m')==date_format($date, 'm') )  )
	    	{
	    		return View::make( 'emptype4.salary_insert_from',
					array(											
						'datasalary' 	=> $datasalary
					)
			 	);	
	    	} 
	    	else
	    	{
	    		return View::make( 'emptype4.salary_insert_from',
					array(					
						'dataacc' => $dataacc
					)
			 	);	
	    	}			
		}
		else{
			//return login
    		return View::make( 'login.index' );	
		}
	}

	/**
	 * function name : salary_add_type4
	 * reciep data post form insert
	 * edit s_salary_detail
	 * post
	*/
    public function salary_add_type4()
    {    	
    	//data form ajax
    	$inputData = Input::get('formData');
	    parse_str($inputData, $formFields);  
	    
	    $Data = array(
	      'cid'      		=> $formFields['cidsalary_insert4'],
	      'bank'			=> $formFields['banksalary_insert4'],	
	      'bank_acc_id'		=> $formFields['bank_acc_idsalary_insert4'],
	      'bank_acc'		=> $formFields['bank_accsalary_insert4'],
	      'salary'			=> $formFields['salarysalary_insert4'],
	      'salary_other'	=> $formFields['salary_othersalary_insert4'],
	      'salary_sso'		=> $formFields['salary_ssosalary_insert4'],
	      'salary_cpk'		=> $formFields['salary_cpksalary_insert4'],
	      'tax_id'			=> $formFields['tax_idsalary_insert4'],  	       
	      'save'			=> $formFields['save4'],
	      'rice'			=> $formFields['rice4'],
		  'elec'			=> $formFields['elec4'],
		  'cprt'			=> $formFields['salary_cprtsalary_insert4'],
	      'shop'			=> $formFields['shop4'],
	      'water'			=> $formFields['water4'],
	      'other'			=> $formFields['other4'],
	      'comment'			=> $formFields['comment4'],
	      'order_date'  	=> date('Y-m-d'),
	      'sys_user'		=> Session::get('cid')
	    );	 
        
        $dchk = DB::Select( ' select * from s_salary_detail where cid='.$formFields['cidsalary_insert4'].' and order_date="'.date('Y-m-d').'" ' ); 	  	  	
	  	 if( count( $dchk ) == 0 )
	    {
             
                $result = DB::insert( 'insert into s_salary_detail ( cid, bank, bank_acc_id, bank_acc, salary, salary_other, salary_sso, salary_cpk, tax_id, save, shop, rice, water, elec, cprt,other, comment, order_date, sys_user ) values ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? )', 
                          array( 
							$Data['cid'],
							$Data['bank'],
							$Data['bank_acc_id'],
							$Data['bank_acc'],	
							$Data['salary'],
							$Data['salary_other'],
							$Data['salary_sso'],
							$Data['salary_cpk'],
							$Data['tax_id'],	
							$Data['save'],
							$Data['shop'],
							$Data['rice'],
							$Data['water'],
							$Data['elec'],
							$Data['cprt'],
							$Data['other'],
							$Data['comment'],
							$Data['order_date'],
							$Data['sys_user']	  			  						  		
				  ) );   	    	        		    				

                   if( $result )
                    {	 
                        return Response::json(array(
                          'success' => true,
                          'msg' 	=> 'บันทึกข้อมูลเรียบร้อยแล้ว'		                   
                        ));
                    }
                    else
                    {
                        return Response::json(array(
                          'success' => false,
                          'msg' => 'ไม่สามารถบันทึกข้อมูลได้ กรุณาติดต่อผู้ดูแลระบบ'		         
                        ));
                    }  
         }
        else
    	{
    		return Response::json(array(
	          'success' => false,
	          'msg' => 'ไม่สามารถบันทึกข้อมูลได้ กรุณาติดต่อผู้ดูแลระบบ'		         
		    ));
    	} 
    }

	/**
	 * function name : salary_insert_edit_type4
	 * reciep data post form edit
	 * edit s_salary_detail
	 * post
	*/
    public function salary_insert_edit_type4( $id, $order_date )
    {    	
    	//data form ajax
    	$inputData = Input::get('formData');
	    parse_str($inputData, $formFields);  	    

	    $Data = array(
	      'cid'      		=> $formFields['cidsalary_insert4'],
	      'bank'			=> $formFields['banksalary_insert4'],	
	      'bank_acc_id'		=> $formFields['bank_acc_idsalary_insert4'],
	      'bank_acc'		=> $formFields['bank_accsalary_insert4'],
	      'salary'			=> $formFields['salarysalary_insert4'],
	      'salary_other'	=> $formFields['salary_othersalary_insert4'],
	      'salary_sso'		=> $formFields['salary_ssosalary_insert4'],
	      'salary_cpk'		=> $formFields['salary_cpksalary_insert4'],
	      'tax_id'			=> $formFields['tax_idsalary_insert4'],  	   
	      'save'			=> $formFields['save4'],
	      'rice'			=> $formFields['rice4'],
		  'elec'			=> $formFields['elec4'],
		  'cprt'			=> $formFields['salary_cprtsalary_insert4'],
	      'shop'			=> $formFields['shop4'],
	      'water'			=> $formFields['water4'],
	      'other'			=> $formFields['other4'],
	      'comment'			=> $formFields['comment4'],
	      'order_date'  	=> date('Y-m-d'),
	      'sys_user'		=> Session::get('cid')
	    );	 	
	  	 	    	    	
       $result = DB::table( 's_salary_detail' )->where( 'cid', '=', $id )->where('order_date', '=', $order_date)->update( $Data );	       		    			       		    				

       if( $result )
    	{	 
    		return Response::json(array(
	          'success' => true,
	          'msg' 	=> 'แก้ไขข้อมูลเรียบร้อยแล้ว'		                   
	        ));
    	}
    	else
    	{
    		return Response::json(array(
	          'success' => false,
	          'msg' => 'ไม่สามารถแก้ไขข้อมูลได้ กรุณาติดต่อผู้ดูแลระบบ'		         
	        ));
    	}      
    }

    /**
    * function name : salary_insert4_post_search
    * search data s_salary
    * post
    */
    public function salary_insert4_post_search()
    {   		  
	   if ( Session::get('level') != '' )
		{
			$search  = Input::get( 'search_salary_insert4' );	    		    			    

			$sqlp = ' select (select salaryID from n_position_salary where cid=n1.cid order by salaryID desc limit 1)  as salaryID from n_position_salary n1  group by n1.cid order by n1.salaryID ';
			$p = DB::select($sqlp);		
			foreach ($p as $key) {
				$a[] = $key->salaryID;
			}	

			if( $search == '' ){
				$accall = DB::table( 'n_datageneral' )	  
				->join( 'n_position_salary', 'n_position_salary.cid', '=', 'n_datageneral.cid' ) 
				->leftjoin( 's_salary', 's_salary.cid', '=', 'n_datageneral.cid' )
				->leftjoin( 's_salary_detail', 's_salary_detail.cid', '=', 'n_datageneral.cid' ) 
				->leftjoin( 's_bank', 's_bank.bank_id', '=', 's_salary_detail.bank' ) 		
				->where( 'n_position_salary.level', '=', 'ลูกจ้างชั่วคราว' )	
				->where( 'n_datageneral.status', '=', '0' )						
				->whereIn('n_position_salary.salaryID', $a )	
                ->groupBY( 'n_datageneral.cid' )
				->orderBY( 'n_datageneral.datainfoID','asc' )
				->select( 'n_datageneral.*', 's_salary.bank', 's_salary.bank_acc_id', 's_salary.bank_acc', 's_salary.salary'
				, 's_salary.salary_other', 's_salary.salary_sso', 's_salary.salary_cpk', 's_salary.salary_cprt'
				, 's_salary.tax_id',  DB::raw('max(s_salary_detail.order_date) as order_date') 
				, DB::raw('(select ss.ot from s_salary_detail ss where ss.cid=s_salary.cid and year(ss.order_date)=year(NOW())  and month(ss.order_date)=month(NOW()) limit 1) as otck  ') 
				, DB::raw('(select ss.salary from s_salary_detail ss where ss.cid=s_salary.cid and year(ss.order_date)=year(NOW())  and month(ss.order_date)=month(NOW()) limit 1) as saralyck  '))
		        ->paginate( 20 );
			}else{
				$accall = DB::table( 'n_datageneral' )	  
				->join( 'n_position_salary', 'n_position_salary.cid', '=', 'n_datageneral.cid' ) 
				->leftjoin( 's_salary', 's_salary.cid', '=', 'n_datageneral.cid' )
				->leftjoin( 's_salary_detail', 's_salary_detail.cid', '=', 'n_datageneral.cid' ) 
				->leftjoin( 's_bank', 's_bank.bank_id', '=', 's_salary_detail.bank' )     	        
				->where( 'n_position_salary.level', '=', "ลูกจ้างชั่วคราว" )
				->where( 'n_datageneral.status', '=', '0' )	
				->whereIn('n_position_salary.salaryID', $a )	
		        ->where(function($query) use ( $search )
	            {
	                $query->where( 'n_datageneral.fname', 'like', "%$search%" )
	                	  ->orWhere( 'n_datageneral.lname', 'like', "%$search%" )
	                	  ->orWhere( 'n_datageneral.cid', 'like', "%$search%" );	 					     
	            })	
                ->groupBY( 'n_datageneral.cid' )
				->orderBY( 'n_datageneral.datainfoID','asc' )
				->select( 'n_datageneral.*', 's_salary.bank', 's_salary.bank_acc_id', 's_salary.bank_acc', 's_salary.salary'
				, 's_salary.salary_other', 's_salary.salary_sso', 's_salary.salary_cpk', 's_salary.salary_cprt'
				, 's_salary.tax_id',  DB::raw('max(s_salary_detail.order_date) as order_date') 
				, DB::raw('(select ss.ot from s_salary_detail ss where ss.cid=s_salary.cid and year(ss.order_date)=year(NOW())  and month(ss.order_date)=month(NOW()) limit 1) as otck  ') 
				, DB::raw('(select ss.salary from s_salary_detail ss where ss.cid=s_salary.cid and year(ss.order_date)=year(NOW())  and month(ss.order_date)=month(NOW()) limit 1) as saralyck  '))	            		        		        	       
		        ->paginate( 70 );
			}								
		    return View::make( 'emptype4.salary_insert_home',  array( 'accall' => $accall ) );
		}	
		else
		{
			//return login
    		return View::make( 'login.index' );	
		}	
    }








    //============================= Salary emptype 2 ==================================//
	/**
	 * function name : salary_type2
	 * view page home salary_type2
	 * 
	 * get
	*/
	public function salary_type2()
	{	
		if ( Session::get('level') != '' )
		{				
			$sqlp = ' select (select salaryID from n_position_salary where cid=n1.cid order by salaryID desc limit 1)  as salaryID from n_position_salary n1  group by n1.cid order by n1.salaryID ';
			$p = DB::select($sqlp);		
			foreach ($p as $key) {
				$a[] = $key->salaryID;
			}		

			$accall = DB::table( 'n_datageneral' )	  
			->join( 'n_position_salary', 'n_position_salary.cid', '=', 'n_datageneral.cid' ) 
			->leftjoin( 's_salary_ocsc', 's_salary_ocsc.cid', '=', 'n_datageneral.cid' ) 
			->leftjoin( 's_bank', 's_bank.bank_id', '=', 's_salary_ocsc.bank' ) 		
			->where( 'n_position_salary.level', '=', 'ลูกจ้างประจำ' )
			->where( 'n_datageneral.status', '=', '0' )
			->whereIn( 'n_position_salary.salaryID', $a )
			->select( 'n_datageneral.*', 's_bank.bank_name', 's_salary_ocsc.bank_acc', 's_salary_ocsc.salary', 's_salary_ocsc.r_c', 's_salary_ocsc.tax_id' )
	        ->paginate( 20 );
	      		       
		    return View::make( 'emptype2.salary_home',  array( 'accall' => $accall ) );
		}	
		else
		{
			//return login
    		return View::make( 'login.index' );	
		}				
	}

	/**
    * function name : salary2_post_search
    * search data s_salary_ocsc
    * post
    */
    public function salary2_post_search()
    {   		  
	   if ( Session::get('level') != '' )
		{
			$search  = Input::get( 'search_salary2' );	    		    			    

			$sqlp = ' select (select salaryID from n_position_salary where cid=n1.cid order by salaryID desc limit 1)  as salaryID from n_position_salary n1  group by n1.cid order by n1.salaryID ';
			$p = DB::select($sqlp);		
			foreach ($p as $key) {
				$a[] = $key->salaryID;
			}	

			if( $search == '' ){
				$accall = DB::table( 'n_datageneral' )	  
				->join( 'n_position_salary', 'n_position_salary.cid', '=', 'n_datageneral.cid' )   
				->leftjoin( 's_salary_ocsc', 's_salary_ocsc.cid', '=', 'n_datageneral.cid' ) 
				->leftjoin( 's_bank', 's_bank.bank_id', '=', 's_salary_ocsc.bank' ) 	
				->where( 'n_position_salary.level', '=', 'ลูกจ้างประจำ' )	
				->where( 'n_datageneral.status', '=', '0' )						
				->whereIn('n_position_salary.salaryID', $a )			
				->select( 'n_datageneral.*', 's_bank.bank_name', 's_salary_ocsc.bank_acc', 's_salary_ocsc.salary', 's_salary_ocsc.r_c', 's_salary_ocsc.tax_id' )
	        	->paginate( 20 );
			}else{
				$accall = DB::table( 'n_datageneral' )	  
				->join( 'n_position_salary', 'n_position_salary.cid', '=', 'n_datageneral.cid' ) 
				->leftjoin( 's_salary_ocsc', 's_salary_ocsc.cid', '=', 'n_datageneral.cid' ) 
				->leftjoin( 's_bank', 's_bank.bank_id', '=', 's_salary_ocsc.bank' ) 	     	        
				->where( 'n_position_salary.level', '=', "ลูกจ้างประจำ" )
				->where( 'n_datageneral.status', '=', '0' )	
				->whereIn('n_position_salary.salaryID', $a )	
		        ->where(function($query) use ( $search )
	            {
	                $query->where( 'n_datageneral.fname', 'like', "%$search%" )
	                	  ->orWhere( 'n_datageneral.lname', 'like', "%$search%" )
	                	  ->orWhere( 'n_datageneral.cid', 'like', "%$search%" );	 					     
	            })		       	
				->select( 'n_datageneral.*', 's_bank.bank_name', 's_salary_ocsc.bank_acc', 's_salary_ocsc.salary', 's_salary_ocsc.r_c', 's_salary_ocsc.tax_id' )
	        	->paginate( 70 );
			}								
		    return View::make( 'emptype2.salary_home',  array( 'accall' => $accall ) );
		}	
		else
		{
			//return login
    		return View::make( 'login.index' );	
		}	
    }

	/**
	 * function name : fromSalary_type2
	 * view page fromSalary_type2
	 * 
	 * open model form
	*/
	public function fromSalary_type2( $id=null )
	{
		if ( Session::get('level') != '' )
		{					
		  	$dataacc = DB::table('s_bank_acc')
	    	->join('s_bank','s_bank.bank_id','=','s_bank_acc.bank_id')
	    	->where('s_bank_acc.cid','=',$id)
	    	->get();

	    	$datasalary =  DB::table('s_salary_ocsc')
	    	->join('s_bank','s_bank.bank_id','=','s_salary_ocsc.bank')
	    	->where('s_salary_ocsc.cid','=',$id)
	    	->first();    	

	    	if( count( $datasalary ) > 0)
	    	{
	    		return View::make( 'emptype2.salary_fromsalary',
					array(					
						'dataacc' 		=> $dataacc,
						'datasalary' 	=> $datasalary
					)
			 	);	
	    	} 
	    	else
	    	{
	    		return View::make( 'emptype2.salary_fromsalary',
					array(					
						'dataacc' => $dataacc
					)
			 	);	
	    	}			
		}
		else{
			//return login
    		return View::make( 'login.index' );	
		}
	}

	/**
	 * function name : addAccSlary2
	 * view page addAccSlary2
	 * 
	 * add data salary_ocsc
	*/
	public function addSalary_type2()
	{
		//data form ajax
    	$inputData = Input::get('formData');
	    parse_str($inputData, $formFields);  	   

	    if( isset($formFields['salarybanktype2']) ){
		    $b = $this->get_datebank( $formFields['salarybanktype2'] );	
		    if( count($b) > 0){
			    foreach ($b as $a) {
			   		$bank 	  		= $a->bank_id;
			   		$bank_acc_id 	= $a->acc_id;
			   	 	$bank_acc 		= $a->bank_acc;
			    }
			}
		}else{
			$bank = '';
			$bank_acc_id = '';
			$bank_acc = '';
		}	 		

	    $Data = array(
	      'cid'      		=> $formFields['cidSalary2'],	
	      'bank'			=> $bank,
	      'bank_acc_id'		=> $bank_acc_id,
	      'bank_acc'		=> $bank_acc,
	      'salary'			=> $formFields['salary2'],
	      'r_c'				=> $formFields['r_c2'],
	      'special'			=> $formFields['special2'],
	      'son'				=> $formFields['son2'],
	      'kbk'				=> $formFields['kbk2'],
	      'tax'				=> $formFields['tax2'],	      
	      'tax_id'			=> $formFields['tax_id2']
	    );	 	    	

	    $rules = array(
	    	'bank_acc'   => 'required', 
        	'salary'     =>  'required'      	
	    );

	    $messages = array(
	    	'bank_acc.required' => 'กรุณาเลือกธนาคาร',
	    	'salary.required' => 'กรุณากรอกเงินเดือน'	     		         
	    ); 

	    $validator = Validator::make( $Data, $rules, $messages );
		
	    //check if the form is valid
	    if ( $validator->fails() )
	    {			
	       return Response::json(array(
            'fail' => true,
            'errors' => $validator->getMessageBag()->toArray()
           ));
	    }
	    else
	    {
	    	$acc = DB::insert( 'insert into s_salary_ocsc ( cid, bank, bank_acc_id, bank_acc, salary, r_c, special, son, kbk, tax, tax_id ) values ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? )', 
				  array( 
				  	    $Data['cid'],
				  	    $Data['bank'],
				  	    $Data['bank_acc_id'],
				  		$Data['bank_acc'],	
				  		$Data['salary'],
				  		$Data['r_c'],
				  		$Data['special'],
				  		$Data['son'],
				  		$Data['kbk'],
				  		$Data['tax'],
				  		$Data['tax_id']			  			  						  		
				  ) );

	    	if( $acc )
	    	{	 
	    		return Response::json(array(
		          'success' => true,
		          'msg' 	=> 'เพิ่มข้อมูลเรียบร้อยแล้ว'		                   
		        ));
	    	}
	    	else
	    	{
	    		return Response::json(array(
		          'success' => false,
		          'msg' => 'ไม่สามารถบันทึกข้อมูลได้ กรุณาติดต่อผู้ดูแลระบบ'		         
		        ));
	    	}
	    }//end else  
	}
	
	/**
	 * function name : salary_edit_type2
	 * reciep data post form edit
	 * edit s_salary_ocsc
	 * post
	*/
    public function salary_edit_type2( $id )
    {    	
    	//data form ajax
    	$inputData = Input::get('formData');
	    parse_str($inputData, $formFields);  

	    if( isset($formFields['salarybanktype2']) ){
		    $b = $this->get_datebank( $formFields['salarybanktype2'] );	
		    if( count($b) > 0){
			    foreach ($b as $a) {
			   		$bank 	  		= $a->bank_id;
			   		$bank_acc_id 	= $a->acc_id;
			   	 	$bank_acc 		= $a->bank_acc;
			    }
			}
		}else{
			$bank = '';
			$bank_acc_id = '';
			$bank_acc = '';
		}	

	    $Data = array(
	      'cid'      		=> $formFields['cidSalary2'],	
	      'bank'			=> $bank,
	      'bank_acc_id'		=> $bank_acc_id,
	      'bank_acc'		=> $bank_acc,
	      'salary'			=> $formFields['salary2'],
	      'r_c'				=> $formFields['r_c2'],
	      'special'		=> $formFields['special2'],
	      'son'				=> $formFields['son2'],
	      'kbk'				=> $formFields['kbk2'],
	      'tax'				=> $formFields['tax2'],	      
	      'tax_id'			=> $formFields['tax_id2']
	    );	 		

	    $rules = array(
	    	'bank_acc'   => 'required', 
        	'salary'     =>  'required'      	
	    );

	    $messages = array(
	    	'bank_acc.required' => 'กรุณาเลือกธนาคาร',
	    	'salary.required' => 'กรุณากรอกเงินเดือน'	     		         
	    ); 

	    $validator = Validator::make( $Data, $rules, $messages );
		
	    //check if the form is valid
	    if ( $validator->fails() )
	    {			
	        return Response::json(array(
            'fail' => true,
            'errors' => $validator->getMessageBag()->toArray()
           ));
	    }
	    else
	    {	 	    	    	
	        $result = DB::table( 's_salary_ocsc' )->where( 'cid', '=', $id )->update( $Data );	       		    				

           if( $result )
	    	{	 
	    		return Response::json(array(
		          'success' => true,
		          'msg' 	=> 'แก้ไขข้อมูลเรียบร้อยแล้ว'		                   
		        ));
	    	}
	    	else
	    	{
	    		return Response::json(array(
		          'success' => false,
		          'msg' => 'ไม่สามารถแก้ไขข้อมูลได้ กรุณาติดต่อผู้ดูแลระบบ'		         
		        ));
	    	}
        }
    }

    /**
	 * function name : salary_insert_type2
	 * view page home salary_insert_type2
	 * 
	 * get
	*/
	public function salary_insert_type2()
	{	
		if ( Session::get('level') != '' )
		{				
			$sqlp = ' select (select salaryID from n_position_salary where cid=n1.cid order by salaryID desc limit 1)  as salaryID from n_position_salary n1  group by n1.cid order by n1.salaryID ';
			$p = DB::select($sqlp);		
			foreach ($p as $key) {
				$a[] = $key->salaryID;
			}		

			$accall = DB::table( 'n_datageneral' )	  
			->join( 'n_position_salary', 'n_position_salary.cid', '=', 'n_datageneral.cid' ) 
			->leftjoin( 's_salary_ocsc', 's_salary_ocsc.cid', '=', 'n_datageneral.cid' ) 
			->leftjoin( 's_salary_ocsc_detail', 's_salary_ocsc_detail.cid', '=', 'n_datageneral.cid' ) 
			->leftjoin( 's_bank', 's_bank.bank_id', '=', 's_salary_ocsc_detail.bank' ) 		
			->where( 'n_position_salary.level', '=', 'ลูกจ้างประจำ' )
			->where( 'n_datageneral.status', '=', '0' )
			->whereIn( 'n_position_salary.salaryID', $a )
			->groupBY( 'n_datageneral.cid' )
			->orderBY( 'n_datageneral.datainfoID','asc' )
			->select( 'n_datageneral.cid' ,'n_datageneral.pname', 'n_datageneral.fname', 'n_datageneral.lname'
			, 's_salary_ocsc.bank', 's_salary_ocsc.bank_acc_id', 's_salary_ocsc.bank_acc', 's_salary_ocsc.salary'
			, 's_salary_ocsc.r_c', 's_salary_ocsc.special', 's_salary_ocsc.son', 's_salary_ocsc.kbk'
			, 's_salary_ocsc.tax', 's_salary_ocsc.tax_id',  DB::raw('max(s_salary_ocsc_detail.order_date) as order_date') 
			, DB::raw('(select ss.ot from s_salary_ocsc_detail ss where ss.cid=s_salary_ocsc.cid and year(ss.order_date)=year(NOW())  and month(ss.order_date)=month(NOW()) limit 1) as otck ')
			, DB::raw('(select ss.salary from s_salary_ocsc_detail ss where ss.cid=s_salary_ocsc.cid and year(ss.order_date)=year(NOW())  and month(ss.order_date)=month(NOW()) limit 1) as saralyck ') )
	        ->paginate( 20 );
	      		       
		    return View::make( 'emptype2.salary_insert_home',  array( 'accall' => $accall ) );
		}	
		else
		{
			//return login
    		return View::make( 'login.index' );	
		}				
	}

	/**
	 * function name : fromsalary_insert_type2
	 * view page fromsalary_insert_type2
	 * 
	 * open model form
	*/
	public function fromsalary_insert_type2( $id=null )
	{
		if ( Session::get('level') != '' )
		{					
		  	$dataacc = DB::table('s_salary_ocsc')
	    	->join('s_bank_acc','s_bank_acc.cid','=','s_salary_ocsc.cid')
	    	->join('s_bank','s_bank.bank_id','=','s_bank_acc.bank_id')
	    	->where('s_bank_acc.cid','=',$id)
	    	->get();	    	

	    	$sql = ' select s_salary_ocsc.*, s_bank.*,  ';
	    	$sql .= ' s_salary_ocsc_detail.r_pt, s_salary_ocsc_detail.r_other, s_salary_ocsc_detail.cas, ';
	    	$sql .= ' s_salary_ocsc_detail.save_p, s_salary_ocsc_detail.houseLoan, s_salary_ocsc_detail.save_h, ';
	    	$sql .= ' s_salary_ocsc_detail.p_other, s_salary_ocsc_detail.shop, s_salary_ocsc_detail.rice, ';
	    	$sql .= ' s_salary_ocsc_detail.water, s_salary_ocsc_detail.elec, s_salary_ocsc_detail.pt, ';
	    	$sql .= ' s_salary_ocsc_detail.bank_o, s_salary_ocsc_detail.fund_p, s_salary_ocsc_detail.order_date ';	    	
	    	$sql .= ' from s_salary_ocsc_detail ';
	    	$sql .= ' inner join s_salary_ocsc on s_salary_ocsc.cid = s_salary_ocsc_detail.cid';
	    	$sql .= ' inner join s_bank on s_bank.bank_id = s_salary_ocsc_detail.bank';
	    	$sql .= ' where s_salary_ocsc_detail.cid = '.$id.' ';
	    	$sql .= ' and s_salary_ocsc_detail.order_date = (select max(b.order_date)  from s_salary_ocsc_detail b where b.cid='.$id.')';	    	

	    	$datasalary = DB::Select($sql);   	    

	    	//เดือนล่าสุด
	    	$m =  DB::table('s_salary_ocsc_detail')	    	
	    	->where('cid','=',$id)
	    	->max('order_date');	
	    	$date = date_create($m);

	    	if( ( count( $datasalary ) > 0 ) && ( date('m')==date_format($date, 'm') )  )
	    	{
	    		return View::make( 'emptype2.salary_insert_from',
					array(										
						'datasalary' 	=> $datasalary
					)
			 	);	
	    	} 
	    	else
	    	{
	    		return View::make( 'emptype2.salary_insert_from',
					array(					
						'dataacc' => $dataacc
					)
			 	);	
	    	}			
		}
		else{
			//return login
    		return View::make( 'login.index' );	
		}
	}

	/**
	 * function name : salary_add_type2
	 * reciep data post form insert
	 * edit s_salary_ocsc_detail
	 * post
	*/
    public function salary_add_type2()
    {    	
    	//data form ajax
    	$inputData = Input::get('formData');
	    parse_str($inputData, $formFields);  
	    
	    $Data = array(
	      'cid'      	=> $formFields['cidsalary_insert2'],
	      'bank'      	=> $formFields['banksalary_insert2'],
	      'bank_acc_id' => $formFields['bank_acc_idsalary_insert2'],
	      'bank_acc'    => $formFields['bank_accsalary_insert2'],
	      'salary'     	=> $formFields['salarysalary_insert2'],
	      'r_c'      	=> $formFields['r_csalary_insert2'],
	      'special_m'     => $formFields['specialsalary_insert2'],
	      'son'      	=> $formFields['sonsalary_insert2'],
	      'tax'      	=> $formFields['taxsalary_insert2'],
	      'tax_id'      => $formFields['tax_idsalary_insert2'],
	      'r_pt'		=> $formFields['r_pt2'],
	      'r_other'		=> $formFields['r_other2'],
	      'kbk'			=> $formFields['kbk2'],
	      'cas'			=> $formFields['cas2'],
	      'save_p'		=> $formFields['save_p2'],
	      'houseLoan'	=> $formFields['houseLoan2'],
	      'save_h'		=> $formFields['save_h2'],
	      'p_other'		=> $formFields['p_other2'],
	      'shop'		=> $formFields['shop2'],
	      'rice'		=> $formFields['rice2'],
	      'water'		=> $formFields['water2'],
	      'elec'		=> $formFields['elec2'],
	      'pt'			=> $formFields['pt2'],
	      'bank_o'		=> $formFields['bank_o2'],
	      'fund_p'		=> $formFields['fund_p2'],
	      'order_date'  => date('Y-m-d'),
	      'sys_user'	=> Session::get('cid')
	    );	 

	    $dchk = DB::Select( ' select * from s_salary_ocsc_detail where cid='.$formFields['cidsalary_insert2'].' and order_date="'.date('Y-m-d').'" ' ); 	  
	  	
	  	if( count($dchk) == 0 )	 
	  	{
		  	$result = DB::insert( 'insert into s_salary_ocsc_detail ( cid, bank, bank_acc_id, bank_acc, salary, r_c, special_m, son, tax, tax_id, r_pt, r_other, kbk, cas, save_p, houseLoan, save_h, p_other, shop, rice, water, elec, pt, bank_o, fund_p, order_date, sys_user ) values ( ?, ? , ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? )', 
					  array( 
					  	    $Data['cid'],
					  	    $Data['bank'],
					  	    $Data['bank_acc_id'],
					  		$Data['bank_acc'],	
					  		$Data['salary'],
					  		$Data['r_c'],
					  		$Data['special_m'],
					  		$Data['son'],
					  		$Data['tax'],	
					  		$Data['tax_id'],
					  		$Data['r_pt'],
					  		$Data['r_other'],
					  		$Data['kbk'],
					  		$Data['cas'],
					  		$Data['save_p'],
					  		$Data['houseLoan'],
					  		$Data['save_h'],
					  		$Data['p_other'],
					  		$Data['shop'],
					  		$Data['rice'],
					  		$Data['water'],
					  		$Data['elec'],
					  		$Data['pt'],
					  		$Data['bank_o'],
					  		$Data['fund_p'],
					  		$Data['order_date'],
					  		$Data['sys_user'],
					  ) );   	    	        		    				

	       if( $result )
	    	{	 
	    		return Response::json(array(
		          'success' => true,
		          'msg' 	=> 'บันทึกข้อมูลเรียบร้อยแล้ว'		                   
		        ));
	    	}
	    	else
	    	{
	    		return Response::json(array(
		          'success' => false,
		          'msg' => 'ไม่สามารถบันทึกข้อมูลได้ กรุณาติดต่อผู้ดูแลระบบ'		         
		        ));
	    	}  
    	}
    	else
    	{
    		return Response::json(array(
	          'success' => false,
	          'msg' => 'ไม่สามารถบันทึกข้อมูลได้ กรุณาติดต่อผู้ดูแลระบบ'		         
	        ));
    	}    
    }

	/**
	 * function name : salary_insert_edit_type2
	 * reciep data post form edit
	 * edit s_salary_ocsc
	 * post
	*/
    public function salary_insert_edit_type2( $id, $order_date )
    {    	
    	//data form ajax
    	$inputData = Input::get('formData');
	    parse_str($inputData, $formFields);  	    

	    $Data = array(
	      'cid'      	=> $formFields['cidsalary_insert2'],
	      'bank'      	=> $formFields['banksalary_insert2'],
	      'bank_acc_id' => $formFields['bank_acc_idsalary_insert2'],
	      'bank_acc'    => $formFields['bank_accsalary_insert2'],
	      'salary'     	=> $formFields['salarysalary_insert2'],
	      'r_c'      	=> $formFields['r_csalary_insert2'],
	      'special_m'   => $formFields['specialsalary_insert2'],
	      'son'      	=> $formFields['sonsalary_insert2'],
	      'tax'      	=> $formFields['taxsalary_insert2'],
	      'tax_id'      => $formFields['tax_idsalary_insert2'],	   
	      'r_pt'		=> $formFields['r_pt2'],
	      'r_other'		=> $formFields['r_other2'],
	      'kbk'			=> $formFields['kbk2'],
	      'cas'			=> $formFields['cas2'],
	      'save_p'		=> $formFields['save_p2'],
	      'houseLoan'	=> $formFields['houseLoan2'],
	      'save_h'		=> $formFields['save_h2'],
	      'p_other'		=> $formFields['p_other2'],
	      'shop'		=> $formFields['shop2'],
	      'rice'		=> $formFields['rice2'],
	      'water'		=> $formFields['water2'],
	      'elec'		=> $formFields['elec2'],
	      'pt'			=> $formFields['pt2'],
	      'bank_o'		=> $formFields['bank_o2'],
	      'fund_p'		=> $formFields['fund_p2'],
	      'order_date'  => date('Y-m-d'),
	      'sys_user'	=> Session::get('cid')
	    );	 	
	  	 	    	    	       
       $result = DB::table( 's_salary_ocsc_detail' )->where( 'cid', '=', $id )->where('order_date', '=', $order_date)->update( $Data );	       	    				       		    				
       if( $result )
    	{	 
    		return Response::json(array(
	          'success' => true,
	          'msg' 	=> 'แก้ไขข้อมูลเรียบร้อยแล้ว'		                   
	        ));
    	}
    	else
    	{
    		return Response::json(array(
	          'success' => false,
	          'msg' => 'ไม่สามารถแก้ไขข้อมูลได้ กรุณาติดต่อผู้ดูแลระบบ'		         
	        ));
    	}      
    }

    /**
    * function name : salary_insert2_post_search
    * search data s_salary_ocsc
    * post
    */
    public function salary_insert2_post_search()
    {   		  
	   if ( Session::get('level') != '' )
		{
			$search  = Input::get( 'search_salary_insert2' );	    		    			    

			$sqlp = ' select (select salaryID from n_position_salary where cid=n1.cid order by salaryID desc limit 1)  as salaryID from n_position_salary n1  group by n1.cid order by n1.salaryID ';
			$p = DB::select($sqlp);		
			foreach ($p as $key) {
				$a[] = $key->salaryID;
			}	

			if( $search == '' ){
				$accall = DB::table( 'n_datageneral' )	  
				->join( 'n_position_salary', 'n_position_salary.cid', '=', 'n_datageneral.cid' ) 
				->leftjoin( 's_salary_ocsc', 's_salary_ocsc.cid', '=', 'n_datageneral.cid' ) 
				->leftjoin( 's_salary_ocsc_detail', 's_salary_ocsc_detail.cid', '=', 'n_datageneral.cid' ) 
				->leftjoin( 's_bank', 's_bank.bank_id', '=', 's_salary_ocsc_detail.bank' ) 
				->where( 'n_position_salary.level', '=', 'ลูกจ้างประจำ' )	
				->where( 'n_datageneral.status', '=', '0' )						
				->whereIn('n_position_salary.salaryID', $a )	
				->groupBY( 'n_datageneral.cid' )
				->orderBY( 'n_datageneral.datainfoID','asc' )
				->select( 'n_datageneral.cid' ,'n_datageneral.pname', 'n_datageneral.fname', 'n_datageneral.lname'
				, 's_salary_ocsc.bank', 's_salary_ocsc.bank_acc_id', 's_salary_ocsc.bank_acc', 's_salary_ocsc.salary'
				, 's_salary_ocsc.r_c', 's_salary_ocsc.special', 's_salary_ocsc.son', 's_salary_ocsc.kbk'
				, 's_salary_ocsc.tax', 's_salary_ocsc.tax_id',  DB::raw('max(s_salary_ocsc_detail.order_date) as order_date') 
				, DB::raw('(select ss.ot from s_salary_ocsc_detail ss where ss.cid=s_salary_ocsc.cid and year(ss.order_date)=year(NOW())  and month(ss.order_date)=month(NOW()) limit 1) as otck ')
				, DB::raw('(select ss.salary from s_salary_ocsc_detail ss where ss.cid=s_salary_ocsc.cid and year(ss.order_date)=year(NOW())  and month(ss.order_date)=month(NOW()) limit 1) as saralyck ') )
		        ->paginate( 20 );
			}else{
				$accall = DB::table( 'n_datageneral' )	  
				->join( 'n_position_salary', 'n_position_salary.cid', '=', 'n_datageneral.cid' ) 
				->leftjoin( 's_salary_ocsc', 's_salary_ocsc.cid', '=', 'n_datageneral.cid' ) 
				->leftjoin( 's_salary_ocsc_detail', 's_salary_ocsc_detail.cid', '=', 'n_datageneral.cid' ) 
				->leftjoin( 's_bank', 's_bank.bank_id', '=', 's_salary_ocsc_detail.bank' ) 	     	        
				->where( 'n_position_salary.level', '=', "ลูกจ้างประจำ" )
				->where( 'n_datageneral.status', '=', '0' )	
				->whereIn('n_position_salary.salaryID', $a )	
		        ->where(function($query) use ( $search )
	            {
	                $query->where( 'n_datageneral.fname', 'like', "%$search%" )
	                	  ->orWhere( 'n_datageneral.lname', 'like', "%$search%" )
	                	  ->orWhere( 'n_datageneral.cid', 'like', "%$search%" );	 					     
	            })	
	            ->groupBY( 'n_datageneral.cid' )	       	
				->orderBY( 'n_datageneral.datainfoID','asc' )
				->select( 'n_datageneral.cid' ,'n_datageneral.pname', 'n_datageneral.fname', 'n_datageneral.lname'
				, 's_salary_ocsc.bank', 's_salary_ocsc.bank_acc_id', 's_salary_ocsc.bank_acc', 's_salary_ocsc.salary'
				, 's_salary_ocsc.r_c', 's_salary_ocsc.special', 's_salary_ocsc.son', 's_salary_ocsc.kbk'
				, 's_salary_ocsc.tax', 's_salary_ocsc.tax_id',  DB::raw('max(s_salary_ocsc_detail.order_date) as order_date') 
				, DB::raw('(select ss.ot from s_salary_ocsc_detail ss where ss.cid=s_salary_ocsc.cid and year(ss.order_date)=year(NOW())  and month(ss.order_date)=month(NOW()) limit 1) as otck ')
				, DB::raw('(select ss.salary from s_salary_ocsc_detail ss where ss.cid=s_salary_ocsc.cid and year(ss.order_date)=year(NOW())  and month(ss.order_date)=month(NOW()) limit 1) as saralyck ') )        		        		        	       
		        ->paginate( 70 );
			}								
		    return View::make( 'emptype2.salary_insert_home',  array( 'accall' => $accall ) );
		}	
		else
		{
			//return login
    		return View::make( 'login.index' );	
		}	
    }







    //============================= Salary emptype 3 ==================================//
	/**
	 * function name : salary_type3
	 * view page home salary_type3
	 * 
	 * get
	*/
	public function salary_type3()
	{	
		if ( Session::get('level') != '' )
		{				
			$sqlp = ' select (select salaryID from n_position_salary where cid=n1.cid order by salaryID desc limit 1)  as salaryID from n_position_salary n1  group by n1.cid order by n1.salaryID ';
			$p = DB::select($sqlp);		
			foreach ($p as $key) {
				$a[] = $key->salaryID;
			}		

			$accall = DB::table( 'n_datageneral' )	  
			->join( 'n_position_salary', 'n_position_salary.cid', '=', 'n_datageneral.cid' ) 
			->leftjoin( 's_salary_ocsc', 's_salary_ocsc.cid', '=', 'n_datageneral.cid' ) 
			->leftjoin( 's_bank', 's_bank.bank_id', '=', 's_salary_ocsc.bank' ) 		
			->where( 'n_position_salary.level', '=', 'ข้าราชการ' )
			->where( 'n_datageneral.status', '=', '0' )
			->whereIn( 'n_position_salary.salaryID', $a )
			->select( 'n_datageneral.*', 's_bank.bank_name', 's_salary_ocsc.bank_acc', 's_salary_ocsc.salary', 's_salary_ocsc.r_c', 's_salary_ocsc.tax_id' )
	        ->paginate( 20 );
	      		       
		    return View::make( 'emptype3.salary_home',  array( 'accall' => $accall ) );
		}	
		else
		{
			//return login
    		return View::make( 'login.index' );	
		}				
	}

	/**
    * function name : salary3_post_search
    * search data s_salary_ocsc
    * post
    */
    public function salary3_post_search()
    {   		  
	   if ( Session::get('level') != '' )
		{
			$search  = Input::get( 'search_salary3' );	    		    			    

			$sqlp = ' select (select salaryID from n_position_salary where cid=n1.cid order by salaryID desc limit 1)  as salaryID from n_position_salary n1  group by n1.cid order by n1.salaryID ';
			$p = DB::select($sqlp);		
			foreach ($p as $key) {
				$a[] = $key->salaryID;
			}	

			if( $search == '' ){
				$accall = DB::table( 'n_datageneral' )	  
				->join( 'n_position_salary', 'n_position_salary.cid', '=', 'n_datageneral.cid' )   
				->leftjoin( 's_salary_ocsc', 's_salary_ocsc.cid', '=', 'n_datageneral.cid' ) 
				->leftjoin( 's_bank', 's_bank.bank_id', '=', 's_salary_ocsc.bank' ) 	
				->where( 'n_position_salary.level', '=', 'ข้าราชการ' )	
				->where( 'n_datageneral.status', '=', '0' )						
				->whereIn('n_position_salary.salaryID', $a )			
				->select( 'n_datageneral.*', 's_bank.bank_name', 's_salary_ocsc.bank_acc', 's_salary_ocsc.salary', 's_salary_ocsc.r_c', 's_salary_ocsc.tax_id' )
	        	->paginate( 20 );
			}else{
				$accall = DB::table( 'n_datageneral' )	  
				->join( 'n_position_salary', 'n_position_salary.cid', '=', 'n_datageneral.cid' ) 
				->leftjoin( 's_salary_ocsc', 's_salary_ocsc.cid', '=', 'n_datageneral.cid' ) 
				->leftjoin( 's_bank', 's_bank.bank_id', '=', 's_salary_ocsc.bank' ) 	     	        
				->where( 'n_position_salary.level', '=', "ข้าราชการ" )
				->where( 'n_datageneral.status', '=', '0' )	
				->whereIn('n_position_salary.salaryID', $a )	
		        ->where(function($query) use ( $search )
	            {
	                $query->where( 'n_datageneral.fname', 'like', "%$search%" )
	                	  ->orWhere( 'n_datageneral.lname', 'like', "%$search%" )
	                	  ->orWhere( 'n_datageneral.cid', 'like', "%$search%" );	 					     
	            })		       	
				->select( 'n_datageneral.*', 's_bank.bank_name', 's_salary_ocsc.bank_acc', 's_salary_ocsc.salary', 's_salary_ocsc.r_c', 's_salary_ocsc.tax_id' )
	        	->paginate( 70 );
			}								
		    return View::make( 'emptype3.salary_home',  array( 'accall' => $accall ) );
		}	
		else
		{
			//return login
    		return View::make( 'login.index' );	
		}	
    }

	/**
	 * function name : fromSalary_type3
	 * view page fromSalary_type3
	 * 
	 * open model form
	*/
	public function fromSalary_type3( $id=null )
	{
		if ( Session::get('level') != '' )
		{					
		  	$dataacc = DB::table('s_bank_acc')
	    	->join('s_bank','s_bank.bank_id','=','s_bank_acc.bank_id')
	    	->where('s_bank_acc.cid','=',$id)
	    	->get();

	    	$datasalary =  DB::table('s_salary_ocsc')
	    	->join('s_bank','s_bank.bank_id','=','s_salary_ocsc.bank')
	    	->where('s_salary_ocsc.cid','=',$id)
	    	->first();    	

	    	if( count( $datasalary ) > 0)
	    	{
	    		return View::make( 'emptype3.salary_fromsalary',
					array(					
						'dataacc' 		=> $dataacc,
						'datasalary' 	=> $datasalary
					)
			 	);	
	    	} 
	    	else
	    	{
	    		return View::make( 'emptype3.salary_fromsalary',
					array(					
						'dataacc' => $dataacc
					)
			 	);	
	    	}			
		}
		else{
			//return login
    		return View::make( 'login.index' );	
		}
	}

	/**
	 * function name : addAccSlary3
	 * view page addAccSlary3
	 * 
	 * add data salary_ocsc
	*/
	public function addSalary_type3()
	{
		//data form ajax
    	$inputData = Input::get('formData');
	    parse_str($inputData, $formFields);  	   

	    if( isset($formFields['salarybanktype3']) ){
		    $b = $this->get_datebank( $formFields['salarybanktype3'] );	
		    if( count($b) > 0){
			    foreach ($b as $a) {
			   		$bank 	  		= $a->bank_id;
			   		$bank_acc_id 	= $a->acc_id;
			   	 	$bank_acc 		= $a->bank_acc;
			    }
			}
		}else{
			$bank = '';
			$bank_acc_id = '';
			$bank_acc = '';
		}	 		

	    $Data = array(
	      'cid'      		=> $formFields['cidSalary3'],	
	      'bank'			=> $bank,
	      'bank_acc_id'		=> $bank_acc_id,
	      'bank_acc'		=> $bank_acc,
	      'salary'			=> $formFields['salary3'],
	      'r_c'				=> $formFields['r_c3'],
	      'special'			=> $formFields['special3'],
	      'son'				=> $formFields['son3'],
	      'kbk'				=> $formFields['kbk3'],
	      'tax'				=> $formFields['tax3'],	      
	      'tax_id'			=> $formFields['tax_id3']
	    );	 	    	

	    $rules = array(
	    	'bank_acc'   => 'required', 
        	'salary'     =>  'required'      	
	    );

	    $messages = array(
	    	'bank_acc.required' => 'กรุณาเลือกธนาคาร',
	    	'salary.required' => 'กรุณากรอกเงินเดือน'	     		         
	    ); 

	    $validator = Validator::make( $Data, $rules, $messages );
		
	    //check if the form is valid
	    if ( $validator->fails() )
	    {			
	       return Response::json(array(
            'fail' => true,
            'errors' => $validator->getMessageBag()->toArray()
           ));
	    }
	    else
	    {
	    	$acc = DB::insert( 'insert into s_salary_ocsc ( cid, bank, bank_acc_id, bank_acc, salary, r_c, special, son, kbk, tax, tax_id ) values ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? )', 
				  array( 
				  	    $Data['cid'],
				  	    $Data['bank'],
				  	    $Data['bank_acc_id'],
				  		$Data['bank_acc'],	
				  		$Data['salary'],
				  		$Data['r_c'],
				  		$Data['special'],
				  		$Data['son'],
				  		$Data['kbk'],
				  		$Data['tax'],
				  		$Data['tax_id']			  			  						  		
				  ) );

	    	if( $acc )
	    	{	 
	    		return Response::json(array(
		          'success' => true,
		          'msg' 	=> 'เพิ่มข้อมูลเรียบร้อยแล้ว'		                   
		        ));
	    	}
	    	else
	    	{
	    		return Response::json(array(
		          'success' => false,
		          'msg' => 'ไม่สามารถบันทึกข้อมูลได้ กรุณาติดต่อผู้ดูแลระบบ'		         
		        ));
	    	}
	    }//end else  
	}
	
	/**
	 * function name : salary_edit_type3
	 * reciep data post form edit
	 * edit s_salary_ocsc
	 * post
	*/
    public function salary_edit_type3( $id )
    {    	
    	//data form ajax
    	$inputData = Input::get('formData');
	    parse_str($inputData, $formFields);  

	    if( isset($formFields['salarybanktype3']) ){
		    $b = $this->get_datebank( $formFields['salarybanktype3'] );	
		    if( count($b) > 0){
			    foreach ($b as $a) {
			   		$bank 	  		= $a->bank_id;
			   		$bank_acc_id 	= $a->acc_id;
			   	 	$bank_acc 		= $a->bank_acc;
			    }
			}
		}else{
			$bank = '';
			$bank_acc_id = '';
			$bank_acc = '';
		}	

	    $Data = array(
	      'cid'      		=> $formFields['cidSalary3'],	
	      'bank'			=> $bank,
	      'bank_acc_id'		=> $bank_acc_id,
	      'bank_acc'		=> $bank_acc,
	      'salary'			=> $formFields['salary3'],
	      'r_c'				=> $formFields['r_c3'],
	      'special'			=> $formFields['special3'],
	      'son'				=> $formFields['son3'],
	      'kbk'				=> $formFields['kbk3'],
	      'tax'				=> $formFields['tax3'],	      
	      'tax_id'			=> $formFields['tax_id3']
	    );	 		

	    $rules = array(
	    	'bank_acc'   => 'required', 
        	'salary'     =>  'required'      	
	    );

	    $messages = array(
	    	'bank_acc.required' => 'กรุณาเลือกธนาคาร',
	    	'salary.required' => 'กรุณากรอกเงินเดือน'	     		         
	    ); 

	    $validator = Validator::make( $Data, $rules, $messages );
		
	    //check if the form is valid
	    if ( $validator->fails() )
	    {			
	        return Response::json(array(
            'fail' => true,
            'errors' => $validator->getMessageBag()->toArray()
           ));
	    }
	    else
	    {	 	    	    	
	        $result = DB::table( 's_salary_ocsc' )->where( 'cid', '=', $id )->update( $Data );	       		    				

           if( $result )
	    	{	 
	    		return Response::json(array(
		          'success' => true,
		          'msg' 	=> 'แก้ไขข้อมูลเรียบร้อยแล้ว'		                   
		        ));
	    	}
	    	else
	    	{
	    		return Response::json(array(
		          'success' => false,
		          'msg' => 'ไม่สามารถแก้ไขข้อมูลได้ กรุณาติดต่อผู้ดูแลระบบ'		         
		        ));
	    	}
        }
    }

    /**
	 * function name : salary_insert_type3
	 * view page home salary_insert_type3
	 * 
	 * get
	*/
	public function salary_insert_type3()
	{	
		if ( Session::get('level') != '' )
		{				
			$sqlp = ' select (select salaryID from n_position_salary where cid=n1.cid order by salaryID desc limit 1)  as salaryID from n_position_salary n1  group by n1.cid order by n1.salaryID ';
			$p = DB::select($sqlp);		
			foreach ($p as $key) {
				$a[] = $key->salaryID;
			}		

			$accall = DB::table( 'n_datageneral' )	  
			->join( 'n_position_salary', 'n_position_salary.cid', '=', 'n_datageneral.cid' ) 
			->leftjoin( 's_salary_ocsc', 's_salary_ocsc.cid', '=', 'n_datageneral.cid' ) 
			->leftjoin( 's_salary_ocsc_detail', 's_salary_ocsc_detail.cid', '=', 'n_datageneral.cid' ) 
			->leftjoin( 's_bank', 's_bank.bank_id', '=', 's_salary_ocsc_detail.bank' ) 		
			->where( 'n_position_salary.level', '=', 'ข้าราชการ' )
			->where( 'n_datageneral.status', '=', '0' )
			->whereIn( 'n_position_salary.salaryID', $a )
			->groupBY( 'n_datageneral.cid' )
			->orderBY( 'n_datageneral.datainfoID','asc' )
			->select( 'n_datageneral.cid' ,'n_datageneral.pname', 'n_datageneral.fname', 'n_datageneral.lname'
			, 's_salary_ocsc.bank', 's_salary_ocsc.bank_acc_id', 's_salary_ocsc.bank_acc', 's_salary_ocsc.salary'
			, 's_salary_ocsc.r_c', 's_salary_ocsc.special', 's_salary_ocsc.son', 's_salary_ocsc.kbk'
			, 's_salary_ocsc.tax', 's_salary_ocsc.tax_id',  DB::raw('max(s_salary_ocsc_detail.order_date) as order_date') 
			, DB::raw('(select ss.ot from s_salary_ocsc_detail ss where ss.cid=s_salary_ocsc.cid and year(ss.order_date)=year(NOW())  and month(ss.order_date)=month(NOW()) limit 1) as otck  ')
			, DB::raw('(select ss.salary from s_salary_ocsc_detail ss where ss.cid=s_salary_ocsc.cid and year(ss.order_date)=year(NOW())  and month(ss.order_date)=month(NOW()) limit 1) as saralyck  '))
	        ->paginate( 20 );	        	     
	      		       
		    return View::make( 'emptype3.salary_insert_home',  array( 'accall' => $accall ) );
		}	
		else
		{
			//return login
    		return View::make( 'login.index' );	
		}				
	}

	/**
	 * function name : fromsalary_insert_type3
	 * view page fromsalary_insert_type3
	 * 
	 * open model form
	*/
	public function fromsalary_insert_type3( $id=null )
	{
		if ( Session::get('level') != '' )
		{					
		  	$dataacc = DB::table('s_salary_ocsc')
	    	->join('s_bank_acc','s_bank_acc.cid','=','s_salary_ocsc.cid')
	    	->join('s_bank','s_bank.bank_id','=','s_bank_acc.bank_id')
	    	->where('s_bank_acc.cid','=',$id)
	    	->get();

	    	$sql = ' select s_salary_ocsc.*, s_bank.*,  ';
	    	$sql .= ' s_salary_ocsc_detail.r_pt, s_salary_ocsc_detail.r_other, s_salary_ocsc_detail.cas, ';
	    	$sql .= ' s_salary_ocsc_detail.save_p, s_salary_ocsc_detail.houseLoan, s_salary_ocsc_detail.save_h, ';
	    	$sql .= ' s_salary_ocsc_detail.p_other, s_salary_ocsc_detail.shop, s_salary_ocsc_detail.rice, ';
	    	$sql .= ' s_salary_ocsc_detail.water, s_salary_ocsc_detail.elec, s_salary_ocsc_detail.pt, ';
	    	$sql .= ' s_salary_ocsc_detail.bank_o, s_salary_ocsc_detail.fund_p, s_salary_ocsc_detail.order_date ';	    	
	    	$sql .= ' from s_salary_ocsc_detail ';
	    	$sql .= ' inner join s_salary_ocsc on s_salary_ocsc.cid = s_salary_ocsc_detail.cid';
	    	$sql .= ' inner join s_bank on s_bank.bank_id = s_salary_ocsc_detail.bank';
	    	$sql .= ' where s_salary_ocsc_detail.cid = '.$id.' ';
	    	$sql .= ' and s_salary_ocsc_detail.order_date = (select max(b.order_date)  from s_salary_ocsc_detail b where b.cid='.$id.')';	    	

	    	$datasalary = DB::Select($sql);  

	    	//เดือนล่าสุด
	    	$m =  DB::table('s_salary_ocsc_detail')	    	
	    	->where('cid','=',$id)
	    	->max('order_date');	
	    	$date = date_create($m);

	    	if( ( count( $datasalary ) > 0 ) && ( date('m')==date_format($date, 'm') )  )
	    	{
	    		return View::make( 'emptype3.salary_insert_from',
					array(											
						'datasalary' 	=> $datasalary
					)
			 	);	
	    	} 
	    	else
	    	{
	    		return View::make( 'emptype3.salary_insert_from',
					array(					
						'dataacc' => $dataacc
					)
			 	);	
	    	}			
		}
		else{
			//return login
    		return View::make( 'login.index' );	
		}
	}

	/**
	 * function name : salary_add_type3
	 * reciep data post form insert
	 * edit s_salary_ocsc_detail
	 * post
	*/
    public function salary_add_type3()
    {    	
    	//data form ajax
    	$inputData = Input::get('formData');
	    parse_str($inputData, $formFields);  
	    
	    $Data = array(
	      'cid'      	=> $formFields['cidsalary_insert3'],
	      'bank'      	=> $formFields['banksalary_insert3'],
	      'bank_acc_id' => $formFields['bank_acc_idsalary_insert3'],
	      'bank_acc'    => $formFields['bank_accsalary_insert3'],
	      'salary'     	=> $formFields['salarysalary_insert3'],
	      'r_c'      	=> $formFields['r_csalary_insert3'],
	      'special_m'     => $formFields['specialsalary_insert3'],
	      'son'      	=> $formFields['sonsalary_insert3'],
	      'tax'      	=> $formFields['taxsalary_insert3'],
	      'tax_id'      => $formFields['tax_idsalary_insert3'],
	      'r_pt'		=> $formFields['r_pt3'],
	      'r_other'		=> $formFields['r_other3'],
	      'kbk'			=> $formFields['kbk3'],
	      'cas'			=> $formFields['cas3'],
	      'save_p'		=> $formFields['save_p3'],
	      'houseLoan'	=> $formFields['houseLoan3'],
	      'save_h'		=> $formFields['save_h3'],
	      'p_other'		=> $formFields['p_other3'],
	      'shop'		=> $formFields['shop3'],
	      'rice'		=> $formFields['rice3'],
	      'water'		=> $formFields['water3'],
	      'elec'		=> $formFields['elec3'],
	      'pt'			=> $formFields['pt3'],
	      'bank_o'		=> $formFields['bank_o3'],
	      'fund_p'		=> $formFields['fund_p3'],
	      'order_date'  => date('Y-m-d'),
	      'sys_user'	=> Session::get('cid')
	    );	 

	    $dchk = DB::Select( ' select * from s_salary_ocsc_detail where cid='.$formFields['cidsalary_insert3'].' and order_date="'.date('Y-m-d').'" ' ); 	  
	  	
	  	if( count($dchk) == 0 )	 	 
	  	{
		  	$result = DB::insert( 'insert into s_salary_ocsc_detail ( cid, bank, bank_acc_id, bank_acc, salary, r_c, special_m, son, tax, tax_id, r_pt, r_other, kbk, cas, save_p, houseLoan, save_h, p_other, shop, rice, water, elec, pt, bank_o, fund_p, order_date, sys_user ) values ( ?, ? , ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? )', 
					  array( 
					  	    $Data['cid'],
					  	    $Data['bank'],
					  	    $Data['bank_acc_id'],
					  		$Data['bank_acc'],	
					  		$Data['salary'],
					  		$Data['r_c'],
					  		$Data['special_m'],
					  		$Data['son'],
					  		$Data['tax'],	
					  		$Data['tax_id'],
					  		$Data['r_pt'],
					  		$Data['r_other'],
					  		$Data['kbk'],
					  		$Data['cas'],
					  		$Data['save_p'],
					  		$Data['houseLoan'],
					  		$Data['save_h'],
					  		$Data['p_other'],
					  		$Data['shop'],
					  		$Data['rice'],
					  		$Data['water'],
					  		$Data['elec'],
					  		$Data['pt'],
					  		$Data['bank_o'],
					  		$Data['fund_p'],
					  		$Data['order_date'],
					  		$Data['sys_user'],
					  ) );   	    	        		    				

	       if( $result )
	    	{	 
	    		return Response::json(array(
		          'success' => true,
		          'msg' 	=> 'บันทึกข้อมูลเรียบร้อยแล้ว'		                   
		        ));
	    	}
	    	else
	    	{
	    		return Response::json(array(
		          'success' => false,
		          'msg' => 'ไม่สามารถบันทึกข้อมูลได้ กรุณาติดต่อผู้ดูแลระบบ'		         
		        ));
	    	} 
    	}
	    else
	    {
	    	return Response::json(array(
	          'success' => false,
	          'msg' => 'ไม่สามารถบันทึกข้อมูลได้ กรุณาติดต่อผู้ดูแลระบบ'		         
	        ));
	    }     
    }

	/**
	 * function name : salary_insert_edit_type3
	 * reciep data post form edit
	 * edit s_salary_ocsc
	 * post
	*/
    public function salary_insert_edit_type3( $id, $order_date )
    {    	
    	//data form ajax
    	$inputData = Input::get('formData');
	    parse_str($inputData, $formFields);  	    

	    $Data = array(
	      'cid'      	=> $formFields['cidsalary_insert3'],
	      'bank'      	=> $formFields['banksalary_insert3'],
	      'bank_acc_id' => $formFields['bank_acc_idsalary_insert3'],
	      'bank_acc'    => $formFields['bank_accsalary_insert3'],
	      'salary'     	=> $formFields['salarysalary_insert3'],
	      'r_c'      	=> $formFields['r_csalary_insert3'],
	      'special_m'   => $formFields['specialsalary_insert3'],
	      'son'      	=> $formFields['sonsalary_insert3'],
	      'tax'      	=> $formFields['taxsalary_insert3'],
	      'tax_id'      => $formFields['tax_idsalary_insert3'],	   
	      'r_pt'		=> $formFields['r_pt3'],
	      'r_other'		=> $formFields['r_other3'],
	      'kbk'			=> $formFields['kbk3'],
	      'cas'			=> $formFields['cas3'],
	      'save_p'		=> $formFields['save_p3'],
	      'houseLoan'	=> $formFields['houseLoan3'],
	      'save_h'		=> $formFields['save_h3'],
	      'p_other'		=> $formFields['p_other3'],
	      'shop'		=> $formFields['shop3'],
	      'rice'		=> $formFields['rice3'],
	      'water'		=> $formFields['water3'],
	      'elec'		=> $formFields['elec3'],
	      'pt'			=> $formFields['pt3'],
	      'bank_o'		=> $formFields['bank_o3'],
	      'fund_p'		=> $formFields['fund_p3'],
	      'order_date'  => date('Y-m-d'),
	      'sys_user'	=> Session::get('cid')
	    );	 	
	  	 	    	    	
       $result = DB::table( 's_salary_ocsc_detail' )->where( 'cid', '=', $id )->where('order_date', '=', $order_date)->update( $Data );	       		           		    				

       if( $result )
    	{	 
    		return Response::json(array(
	          'success' => true,
	          'msg' 	=> 'แก้ไขข้อมูลเรียบร้อยแล้ว'		                   
	        ));
    	}
    	else
    	{
    		return Response::json(array(
	          'success' => false,
	          'msg' => 'ไม่สามารถแก้ไขข้อมูลได้ กรุณาติดต่อผู้ดูแลระบบ'		         
	        ));
    	}      
    }

    /**
    * function name : salary_insert3_post_search
    * search data s_salary_ocsc
    * post
    */
    public function salary_insert3_post_search()
    {   		  
	   if ( Session::get('level') != '' )
		{
			$search  = Input::get( 'search_salary_insert3' );	    		    			    

			$sqlp = ' select (select salaryID from n_position_salary where cid=n1.cid order by salaryID desc limit 1)  as salaryID from n_position_salary n1  group by n1.cid order by n1.salaryID ';
			$p = DB::select($sqlp);		
			foreach ($p as $key) {
				$a[] = $key->salaryID;
			}	

			if( $search == '' ){
				$accall = DB::table( 'n_datageneral' )	  
				->join( 'n_position_salary', 'n_position_salary.cid', '=', 'n_datageneral.cid' ) 
				->leftjoin( 's_salary_ocsc', 's_salary_ocsc.cid', '=', 'n_datageneral.cid' ) 
				->leftjoin( 's_salary_ocsc_detail', 's_salary_ocsc_detail.cid', '=', 'n_datageneral.cid' ) 
				->leftjoin( 's_bank', 's_bank.bank_id', '=', 's_salary_ocsc_detail.bank' ) 	
				->where( 'n_position_salary.level', '=', 'ข้าราชการ' )	
				->where( 'n_datageneral.status', '=', '0' )						
				->whereIn('n_position_salary.salaryID', $a )	
				->groupBY( 'n_datageneral.cid' )
				->orderBY( 'n_datageneral.datainfoID','asc' )
				->select( 'n_datageneral.cid' ,'n_datageneral.pname', 'n_datageneral.fname', 'n_datageneral.lname'
				, 's_salary_ocsc.bank', 's_salary_ocsc.bank_acc_id', 's_salary_ocsc.bank_acc', 's_salary_ocsc.salary'
				, 's_salary_ocsc.r_c', 's_salary_ocsc.special', 's_salary_ocsc.son', 's_salary_ocsc.kbk'
				, 's_salary_ocsc.tax', 's_salary_ocsc.tax_id',  DB::raw('max(s_salary_ocsc_detail.order_date) as order_date') 
				, DB::raw('(select ss.ot from s_salary_ocsc_detail ss where ss.cid=s_salary_ocsc.cid and year(ss.order_date)=year(NOW())  and month(ss.order_date)=month(NOW()) limit 1) as otck  ')
				, DB::raw('(select ss.salary from s_salary_ocsc_detail ss where ss.cid=s_salary_ocsc.cid and year(ss.order_date)=year(NOW())  and month(ss.order_date)=month(NOW()) limit 1) as saralyck  '))
		        ->paginate( 20 );
			}else{
				$accall = DB::table( 'n_datageneral' )	  
				->join( 'n_position_salary', 'n_position_salary.cid', '=', 'n_datageneral.cid' ) 
				->leftjoin( 's_salary_ocsc', 's_salary_ocsc.cid', '=', 'n_datageneral.cid' ) 
				->leftjoin( 's_salary_ocsc_detail', 's_salary_ocsc_detail.cid', '=', 'n_datageneral.cid' ) 
				->leftjoin( 's_bank', 's_bank.bank_id', '=', 's_salary_ocsc_detail.bank' )  	 	     	        
				->where( 'n_position_salary.level', '=', "ข้าราชการ" )
				->where( 'n_datageneral.status', '=', '0' )	
				->whereIn('n_position_salary.salaryID', $a )	
		        ->where(function($query) use ( $search )
	            {
	                $query->where( 'n_datageneral.fname', 'like', "%$search%" )
	                	  ->orWhere( 'n_datageneral.lname', 'like', "%$search%" )
	                	  ->orWhere( 'n_datageneral.cid', 'like', "%$search%" );	 					     
	            })	
	            ->groupBY( 'n_datageneral.cid' )	       	
				->orderBY( 'n_datageneral.datainfoID','asc' )
				->select( 'n_datageneral.cid' ,'n_datageneral.pname', 'n_datageneral.fname', 'n_datageneral.lname'
				, 's_salary_ocsc.bank', 's_salary_ocsc.bank_acc_id', 's_salary_ocsc.bank_acc', 's_salary_ocsc.salary'
				, 's_salary_ocsc.r_c', 's_salary_ocsc.special', 's_salary_ocsc.son', 's_salary_ocsc.kbk'
				, 's_salary_ocsc.tax', 's_salary_ocsc.tax_id',  DB::raw('max(s_salary_ocsc_detail.order_date) as order_date') 
				, DB::raw('(select ss.ot from s_salary_ocsc_detail ss where ss.cid=s_salary_ocsc.cid and year(ss.order_date)=year(NOW())  and month(ss.order_date)=month(NOW()) limit 1) as otck  ')
				, DB::raw('(select ss.salary from s_salary_ocsc_detail ss where ss.cid=s_salary_ocsc.cid and year(ss.order_date)=year(NOW())  and month(ss.order_date)=month(NOW()) limit 1) as saralyck  '))      		        		        	       
		        ->paginate( 70 );
			}								
		    return View::make( 'emptype3.salary_insert_home',  array( 'accall' => $accall ) );
		}	
		else
		{
			//return login
    		return View::make( 'login.index' );	
		}	
    }








    //============================ salary_insert_auto ===================================//
    /**
    * function name : salary_insert_auto
    * search data s_salary_ocsc_detail
    * get
    */
    public function salary_insert_auto()
    {
    	return View::make( 'emptype2.salary_auto' );
    }

    /**
    * function name : addauto
    * search data s_salary_ocsc_detail
    * get
    */
    public function addauto()
    { 	
    	$sql1  = ' select cid, max(order_date) as order_date from s_salary_ocsc_detail where cid not in (select cid from n_datageneral where status=1) and cid <> 5350400051484 group by cid';
    	$data1 = DB::Select( $sql1 );

    	foreach ($data1 as $k1) 
    	{
    		$status = 'no';
    		$m = date("m", strtotime( $k1->order_date ));
    		$y = date("Y", strtotime( $k1->order_date ));   		

    		if( date('m') == '01' )
    		{
    			if( (date('Y')-1) == $y && $m == '12' )
    			{
    				$status = 'ok';
    				$m = 12;
    				$y = (date('Y')-1);
    			}
    		}
    		else
    		{
    			if( date('Y') == $y && (date('m')-1) == $m )
    			{
    				$status = 'ok';
    				$m = (date('m')-1);
    				$y = $y;
    				//$m = '09';
    			}
    		}     			
    		
    		if( $status == 'ok' )
    		{
    			$sql2  = ' select * from s_salary_ocsc_detail';
	    		$sql2 .= ' where cid='.$k1->cid;
	    		$sql2 .= ' and year(order_date)='.$y;
	    		$sql2 .= ' and month(order_date)='.$m;

	    		$data2 = DB::Select( $sql2 );

	    		foreach ($data2 as $k2) 
	    		{	    			
	    			$result = DB::insert( 'insert into s_salary_ocsc_detail ( cid, bank, bank_acc_id, bank_acc, salary, r_c, tax_id, water, elec, order_date, sys_user ) values ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? )', 
					  array( 
					  	    $k2->cid,
					  	    $k2->bank,
					  	    $k2->bank_acc_id,
					  		$k2->bank_acc,	
					  		$k2->salary,
					  		$k2->r_c,			  		
					  		$k2->tax_id,						  		
					  		0,//water
					  		0,//elec					  						  		
					  		date('Y-m-d'),//order_date	
					  		//'2015-10-20',				  		
					  		$k2->sys_user		  			  						  		
					  ) ); 
	    		}// end data2 
    		}// end status  		
    	}// end data1
    }







    //=================== เงินเดือนย้อนหลัง =======================//
    
    /**
    * function name : empAll_list
    * home empAll_list
    * get
    */
    public function empAll_list()
    {
    	if ( Session::get('level') != '' ){
    		$user = DB::Select( 'select cid, concat( pname,"",fname," ",lname ) as name from n_datageneral' );
    		$y = DB::Select( ' select year(order_date) as year1 from s_salary_detail group by year(order_date) order by year(order_date) desc ' );	
        	return View::make( 'salaryall.index', array('user' => $user, 'data' => $y ) );
    	}else{
    		//return login
    		return View::make( 'login.index' );
    	}
    }

    /**
    * function name : checktype_emp
    * เช็คประเภทพนักงาน
    * get
    */
    public function checktype_emp($id)
    {
    	$sql = 'select level from n_position_salary where cid= '.$id.' order by salaryID desc limit 1 ';
    	$data = DB::Select($sql);
    	foreach ($data as $key => $value) {
    		return $value->level;
    	}
    	$data = '';
    	return $data;
    }

    /**
    * function name : get_empall
    * ดึงข้อมูลเงินเดือนไปแก้ไข
    * get
    */
    public function get_empall($id, $type, $y, $m)
    {
    	if( $type == 'type1' ){
    		$sql = 'select * from s_salary_detail where cid='.$id.' and year(order_date)='.$y.' and month(order_date)='.$m.' ';
    		$data = DB::Select($sql);
    		return $data;
    	}else{
    		$sql = 'select * from s_salary_ocsc_detail where cid='.$id.' and year(order_date)='.$y.' and month(order_date)='.$m.' ';
    		$data = DB::Select($sql);
    		return $data;
    	}
    }

    /**
    * function name : salary_add_new
    * เพิ่มเงินเดือนใหม่
    * post
    */
 	public function salary_add_new()
 	{
 		if ( Session::get('level') != '' ){
 			
 			$cid 			= Input::get('cid');
 			$typeaction 	= Input::get('typeaction');

    		$Data = array(
		      'cid'      		=> Input::get('cid'),
		      'bank_acc'    	=> Input::get('bank_acc'),
		      'salary'     		=> Input::get('salary'),
		      'salary_other'    => Input::get('salary_other'),
		      'salary_sso'     	=> Input::get('salary_sso'),
		      'water'     		=> Input::get('water'),
		      'elec'     		=> Input::get('elec'),
		      'order_date'     	=> Input::get('order_date'),
		      'pts'     		=> Input::get('pts'),
		      'ot'				=> Input::get('ot'),
		      'ch8'				=> Input::get('ch8'),
		      'outpcu'			=> Input::get('outpcu'),
		      'u_travel'		=> Input::get('u_travel'),
		      'sys_user'		=> Session::get('cid')
		    );	 	  

 			if( $typeaction == 'add' ){
 				$result = DB::insert( 'insert into s_salary_detail ( cid, bank_acc, salary, salary_other, salary_sso, water, elec, order_date, pts, ot, ch8, outpcu, u_travel, sys_user ) values ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? )', 
					       array( 
						  	   	  Input::get('cid'),
							      Input::get('bank_acc'),
							      Input::get('salary'),
							      Input::get('salary_other'),
							      Input::get('salary_sso'),
							      Input::get('water'),
							      Input::get('elec'),
							      Input::get('order_date'),
							      Input::get('pts'),
							      Input::get('ot'),
							      Input::get('ch8'),
							      Input::get('outpcu'),
							      Input::get('u_travel'),
							      Session::get('cid')  			  						  		
					  		) 
					      ); 
 			    
 			    if( $result ){
 			    	return 'ok';
 			    }else{
 			    	return 'no';
 			    }

 			}else{
 				$result = DB::table( 's_salary_detail' )->where( 'cid', '=', $cid )->where('order_date', '=', Input::get('order_date'))->update( $Data );	
 				
 				if( $result ){
 			    	return 'ok';
 			    }else{
 			    	return 'no';
 			    }
 			}

 		}else{
    		//return login
    		return View::make( 'login.index' );
    	}
 	}

 	/**
    * function name : salary_add_new_ocsc
    * เพิ่มเงินเดือนใหม่ ข้าราชการ
    * post
    */
 	public function salary_add_new_ocsc()
 	{
 		if ( Session::get('level') != '' ){
 			
 			$cid 			= Input::get('cid');
 			$typeaction 	= Input::get('typeaction');

    		$Data = array(
		      'cid'      		=> Input::get('cid'),
		      'bank_acc'    	=> Input::get('bank_acc'),
		      'salary'     		=> Input::get('salary'),
		      'r_c'    			=> Input::get('r_c'),
		      'r_other'     	=> Input::get('r_other'),
		      'water'     		=> Input::get('water'),
		      'elec'     		=> Input::get('elec'),
		      'order_date'     	=> Input::get('order_date'),
		      'pts2'     		=> Input::get('pts2'),
		      'ot'				=> Input::get('ot'),
		      'ch8'				=> Input::get('ch8'),
		      'no_v'			=> Input::get('no_v'),
		      'outpcu'			=> Input::get('outpcu'),
		      'special_m'		=> Input::get('special_m'),
		      'u_travel'		=> Input::get('u_travel'),
		      'game_sp'			=> Input::get('game_sp'),
		      'sys_user'		=> Session::get('cid')
		    );	 	  

 			if( $typeaction == 'add' ){
 				$result = DB::insert( 'insert into s_salary_ocsc_detail ( cid, bank_acc, salary, r_c, r_other, water, elec, order_date, pts2, ot, ch8, no_v, outpcu, special_m, u_travel, game_sp, sys_user ) values ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? )', 
					       array( 
						  	   	  Input::get('cid'),
							      Input::get('bank_acc'),
							      Input::get('salary'),
							      Input::get('r_c'),
							      Input::get('r_other'),
							      Input::get('water'),
							      Input::get('elec'),
							      Input::get('order_date'),
							      Input::get('pts2'),
							      Input::get('ot'),
							      Input::get('ch8'),
							      Input::get('no_v'),
							      Input::get('outpcu'),
							      Input::get('special_m'),
							      Input::get('u_travel'),
							      Input::get('game_sp'),
							      Session::get('cid')  			  						  		
					  		) 
					      ); 
 			    
 			    if( $result ){
 			    	return 'ok';
 			    }else{
 			    	return 'no';
 			    }

 			}else{
 				$result = DB::table( 's_salary_ocsc_detail' )->where( 'cid', '=', $cid )->where('order_date', '=', Input::get('order_date'))->update( $Data );	
 				
 				if( $result ){
 			    	return 'ok';
 			    }else{
 			    	return 'no';
 			    }
 			}

 		}else{
    		//return login
    		return View::make( 'login.index' );
    	}
 	}






	


}


?>