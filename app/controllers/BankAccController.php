<?php

class BankAccController extends BaseController {

	//================== Bank Acc 1 ======================//
	/**
	 * function name : bank_acc
	 * view page home bank_acc
	 * 
	 * get
	*/
	public function bank_acc_type1()
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
			->where( 'n_position_salary.level', '=', 'พกส.(ปฏิบัติงาน)' )
			->where( 'n_datageneral.status', '=', '0' )
			->whereIn('n_position_salary.salaryID', $a )
			->select('n_datageneral.*', DB::raw('(select concat(s_bank.bank_name," : ",s_bank_acc.bank_acc) from s_bank_acc left join s_bank on s_bank.bank_id=s_bank_acc.bank_id where cid=n_position_salary.cid order by acc_id asc limit 1) as acc1'), DB::raw('(select concat(s_bank.bank_name," : ",s_bank_acc.bank_acc) from s_bank_acc left join s_bank on s_bank.bank_id=s_bank_acc.bank_id where cid=n_position_salary.cid  and  concat(s_bank.bank_name," : ",s_bank_acc.bank_acc)<>'.DB::raw('(select concat(s_bank.bank_name," : ",s_bank_acc.bank_acc) from s_bank_acc left join s_bank on s_bank.bank_id=s_bank_acc.bank_id where cid=n_position_salary.cid order by acc_id asc limit 1)').' order by acc_id desc limit 1) as acc2') )
	        ->paginate( 20 );
	      		       
		    return View::make( 'emptype1.bank_acchome',  array( 'accall' => $accall ) );
		}	
		else
		{
			//return login
    		return View::make( 'login.index' );	
		}				
	}

	/**
    * function name : bankacc1_post_search
    * search data s_bank_acc
    * post
    */
    public function bankacc1_post_search()
    {   		  
	   if ( Session::get('level') != '' )
		{
			$search  = Input::get( 'searchacc1' );	    		    			    

			$sqlp = ' select (select salaryID from n_position_salary where cid=n1.cid order by salaryID desc limit 1)  as salaryID from n_position_salary n1  group by n1.cid order by n1.salaryID ';
			$p = DB::select($sqlp);		
			foreach ($p as $key) {
				$a[] = $key->salaryID;
			}	

			if( $search == '' ){
				$accall = DB::table( 'n_datageneral' )	  
				->join( 'n_position_salary', 'n_position_salary.cid', '=', 'n_datageneral.cid' )      	        
				->where( 'n_position_salary.level', '=', 'พกส.(ปฏิบัติงาน)' )	
				->where( 'n_datageneral.status', '=', '0' )						
				->whereIn('n_position_salary.salaryID', $a )			
				->select('n_datageneral.*', DB::raw('(select concat(s_bank.bank_name," : ",s_bank_acc.bank_acc) from s_bank_acc left join s_bank on s_bank.bank_id=s_bank_acc.bank_id where cid=n_position_salary.cid order by acc_id asc limit 1) as acc1'), DB::raw('(select concat(s_bank.bank_name," : ",s_bank_acc.bank_acc) from s_bank_acc left join s_bank on s_bank.bank_id=s_bank_acc.bank_id where cid=n_position_salary.cid  and  concat(s_bank.bank_name," : ",s_bank_acc.bank_acc)<>'.DB::raw('(select concat(s_bank.bank_name," : ",s_bank_acc.bank_acc) from s_bank_acc left join s_bank on s_bank.bank_id=s_bank_acc.bank_id where cid=n_position_salary.cid order by acc_id asc limit 1)').' order by acc_id desc limit 1) as acc2') )
		        ->paginate( 20 );
			}else{
				$accall = DB::table( 'n_datageneral' )	  
				->join( 'n_position_salary', 'n_position_salary.cid', '=', 'n_datageneral.cid' )      	        
				->where( 'n_position_salary.level', '=', "พกส.(ปฏิบัติงาน)" )	
				->where( 'n_datageneral.status', '=', '0' )
				->whereIn('n_position_salary.salaryID', $a )	
		        ->where(function($query) use ( $search )
	            {
	                $query->where( 'n_datageneral.fname', 'like', "%$search%" )
	                	  ->orWhere( 'n_datageneral.lname', 'like', "%$search%" )
	                	  ->orWhere( 'n_datageneral.cid', 'like', "%$search%" );	 					     
	            })		       	
				->select('n_datageneral.*', 	DB::raw('(select concat(s_bank.bank_name," : ",s_bank_acc.bank_acc) from s_bank_acc left join s_bank on s_bank.bank_id=s_bank_acc.bank_id where cid=n_position_salary.cid order by acc_id asc limit 1) as acc1'), DB::raw('(select concat(s_bank.bank_name," : ",s_bank_acc.bank_acc) from s_bank_acc left join s_bank on s_bank.bank_id=s_bank_acc.bank_id where cid=n_position_salary.cid  and  concat(s_bank.bank_name," : ",s_bank_acc.bank_acc)<>'.DB::raw('(select concat(s_bank.bank_name," : ",s_bank_acc.bank_acc) from s_bank_acc left join s_bank on s_bank.bank_id=s_bank_acc.bank_id where cid=n_position_salary.cid order by acc_id asc limit 1)').' order by acc_id desc limit 1) as acc2') )	        		        		        	       
		        ->paginate( 70 );
			}								
		    return View::make( 'emptype1.bank_acchome',  array( 'accall' => $accall ) );
		}	
		else
		{
			//return login
    		return View::make( 'login.index' );	
		}	
    }

	/**
	 * function name : fromAcc
	 * view page fromAcc
	 * 
	 * open model form
	*/
	public function fromAcc_type1( $id=null )
	{
		if ( Session::get('level') != '' )
		{
			$bank   = DB::table( 's_bank' )->get();	
			
		  	$dataacc = DB::table('s_bank_acc')
	    	->join('s_bank','s_bank.bank_id','=','s_bank_acc.bank_id')
	    	->where('s_bank_acc.cid','=',$id)
	    	->get();

			return View::make( 'emptype1.bank_fromacc',
				array(
					'bank' 	=> $bank,
					'dataacc' => $dataacc
				)
			 );		
		}
		else{
			//return login
    		return View::make( 'login.index' );	
		}
	}

	/**
	 * function name : addAcc
	 * view page addAcc
	 * 
	 * add data bank acc
	*/
	public function addAcc_type1()
	{
		//data form ajax
    	$inputData = Input::get('formData');
	    parse_str($inputData, $formFields);  
	    $Data = array(
	      'cid'      		=> $formFields['cidAcc'],	
	      'bank_id'		=> $formFields['bank'],
	      'bank_acc'		=> $formFields['bank_acc']
	    );	 	

	    $rules = array(
        'bank_acc'     		=>  'required'
	    );

	    $messages = array(
	    'bank_acc.required' => 'กรุณากรอกเลขที่บัญชี' 	     
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
	    	$acc = DB::insert( 'insert into s_bank_acc ( cid, bank_id, bank_acc ) values ( ?, ?, ? )', 
				  array( 
				  	    $Data['cid'],
				  	    $Data['bank_id'],
				  		$Data['bank_acc']				  			  						  		
				  ) );

	    	if( $acc )
	    	{	 

		    	$dataacc = DB::table('s_bank_acc')
		    	->join('s_bank','s_bank.bank_id','=','s_bank_acc.bank_id')
		    	->where('s_bank_acc.cid','=',$Data['cid'])
		    	->get();

		    	$a = '';
		    	if( count($dataacc) > 0 )
		    	{
		    		$a = '<table  class="responsive" >';				
					$a .= '<tbody>';
					$ai=0;
					$a .= '<tr> <th  width="40" >ลำดับ</th> <th width="250" >ธนาคาร</th> <th width="250" >เลขที่บัญชี</th> <th width="40" >ลบ</th> </tr>';
	            
		            foreach ($dataacc as $dacc) {
		              $ai++;
		              $a .= '<tr>';
		              $a .= '<td>'.$ai.'</td>';           
		              $a .= '<td>'.$dacc->bank_name.'</td>';
		              $a .= '<td>'.$dacc->bank_acc.'</td>';
		              $a .= '<td><a title="ลบข้อมูล"  onclick="delAcc('.$dacc->cid.','.$dacc->acc_id.');" href="#"><i class="fi-x small"></i></a></td>';
		              $a .= '</tr>';
					}				
					$a .= '</tbody>';
					$a .= '</table>';
		    	}				

	    		return Response::json(array(
		          'success' => true,
		          'msg' 	=> 'เพิ่มข้อมูลเรียบร้อยแล้ว'	,
		          'w' 	=> $a	         
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
	}

	/**
	 * function name : deleteAcc
	 * view page deleteAcc
	 * 
	 * delete data bank acc
	*/
	function deleteAcc_type1( $id, $cid )
	{		
		if ( Session::get('level') != '' )
    	{    		
            $result = BankAcc::where( 'acc_id', $id )->delete();

            if( $result )
            {
            	$dataacc = DB::table('s_bank_acc')
		    	->join('s_bank','s_bank.bank_id','=','s_bank_acc.bank_id')
		    	->where('s_bank_acc.cid','=',$cid)
		    	->get();				

		    	$a='';
		    	if( count($dataacc) > 0 )
		    	{
		    		$a = '<table  class="responsive" >';				
					$a .= '<tbody>';
					$ai=0;
					$a .= '<tr> <th  width="40" >ลำดับ</th> <th width="250" >ธนาคาร</th> <th width="250" >เลขที่บัญชี</th> <th width="40" >ลบ</th> </tr>';
	            
		            foreach ($dataacc as $dacc) {
		              $ai++;
		              $a .= '<tr>';
		              $a .= '<td>'.$ai.'</td>';           
		              $a .= '<td>'.$dacc->bank_name.'</td>';
		              $a .= '<td>'.$dacc->bank_acc.'</td>';
		              $a .= '<td><a title="ลบข้อมูล"  onclick="delAcc('.$dacc->cid.','.$dacc->acc_id.');" href="#"><i class="fi-x small"></i></a></td>';
		              $a .= '</tr>';
					}				
					$a .= '</tbody>';
					$a .= '</table>';
		    	}				

            	return Response::json(array(
		          'success' => true,
		          'msg' => 'ลบข้อมูลเรียบร้อย',
		          'w' => $a
		        )); 
            }
            else
            {
            	return Response::json(array(
		          'success' => false,
		          'msg' => 'ไม่สามารถลบข้อมูลได้ กรุณาติดต่อผู้ดูแลระบบ'		         
		        ));
            }
		  
    	}
    	else
    	{
    		return View::make( 'login.index' );	
    	}     
	}


	//=================================== Bank Acc 2 =======================================//
	/**
	 * function name : bank_acc
	 * view page home bank_acc
	 * 
	 * get
	*/
	public function bank_acc_type2()
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
			->where( 'n_position_salary.level', '=', 'ลูกจ้างประจำ' )
			->where( 'n_datageneral.status', '=', '0' )
			->whereIn('n_position_salary.salaryID', $a )
			->select('n_datageneral.*', DB::raw('(select concat(s_bank.bank_name," : ",s_bank_acc.bank_acc) from s_bank_acc left join s_bank on s_bank.bank_id=s_bank_acc.bank_id where cid=n_position_salary.cid order by acc_id asc limit 1) as acc1'), DB::raw('(select concat(s_bank.bank_name," : ",s_bank_acc.bank_acc) from s_bank_acc left join s_bank on s_bank.bank_id=s_bank_acc.bank_id where cid=n_position_salary.cid  and  concat(s_bank.bank_name," : ",s_bank_acc.bank_acc)<>'.DB::raw('(select concat(s_bank.bank_name," : ",s_bank_acc.bank_acc) from s_bank_acc left join s_bank on s_bank.bank_id=s_bank_acc.bank_id where cid=n_position_salary.cid order by acc_id asc limit 1)').' order by acc_id desc limit 1) as acc2') )
	        ->paginate( 20 );
	      		       
		    return View::make( 'emptype2.bank_acchome',  array( 'accall' => $accall ) );
		}	
		else
		{
			//return login
    		return View::make( 'login.index' );	
		}				
	}

	/**
    * function name : bankacc2_post_search
    * search data s_bank_acc
    * post
    */
    public function bankacc2_post_search()
    {   		  
	   if ( Session::get('level') != '' )
		{
			$search  = Input::get( 'searchacc2' );	 			 		    			    

			$sqlp = ' select (select salaryID from n_position_salary where cid=n1.cid order by salaryID desc limit 1)  as salaryID from n_position_salary n1  group by n1.cid order by n1.salaryID ';
			$p = DB::select($sqlp);	
						
			foreach ($p as $key) {
				$a[] = $key->salaryID;
			}	

			if( $search == '' ){
				$accall = DB::table( 'n_datageneral' )	  
				->join( 'n_position_salary', 'n_position_salary.cid', '=', 'n_datageneral.cid' )      	        
				->where( 'n_position_salary.level', '=', 'ลูกจ้างประจำ' )	
				->where( 'n_datageneral.status', '=', '0' )						
				->whereIn('n_position_salary.salaryID', $a )			
				->select('n_datageneral.*', DB::raw('(select concat(s_bank.bank_name," : ",s_bank_acc.bank_acc) from s_bank_acc left join s_bank on s_bank.bank_id=s_bank_acc.bank_id where cid=n_position_salary.cid order by acc_id asc limit 1) as acc1'), DB::raw('(select concat(s_bank.bank_name," : ",s_bank_acc.bank_acc) from s_bank_acc left join s_bank on s_bank.bank_id=s_bank_acc.bank_id where cid=n_position_salary.cid  and  concat(s_bank.bank_name," : ",s_bank_acc.bank_acc)<>'.DB::raw('(select concat(s_bank.bank_name," : ",s_bank_acc.bank_acc) from s_bank_acc left join s_bank on s_bank.bank_id=s_bank_acc.bank_id where cid=n_position_salary.cid order by acc_id asc limit 1)').' order by acc_id desc limit 1) as acc2') )
		        ->paginate( 20 );
			}else{
				$accall = DB::table( 'n_datageneral' )	  
				->join( 'n_position_salary', 'n_position_salary.cid', '=', 'n_datageneral.cid' )      	        
				->where( 'n_position_salary.level', '=', 'ลูกจ้างประจำ' )
				->where( 'n_datageneral.status', '=', '0' )
				->whereIn('n_position_salary.salaryID', $a )			
				->where(function($query) use ( $search )
	            {
	                $query->where( 'n_datageneral.fname', 'like', "%$search%" )
	                	  ->orWhere( 'n_datageneral.lname', 'like', "%$search%" )
	                	  ->orWhere( 'n_datageneral.cid', 'like', "%$search%" );	 					     
	            })	        						
				->select('n_datageneral.*', DB::raw('(select concat(s_bank.bank_name," : ",s_bank_acc.bank_acc) from s_bank_acc left join s_bank on s_bank.bank_id=s_bank_acc.bank_id where cid=n_position_salary.cid order by acc_id asc limit 1) as acc1'), DB::raw('(select concat(s_bank.bank_name," : ",s_bank_acc.bank_acc) from s_bank_acc left join s_bank on s_bank.bank_id=s_bank_acc.bank_id where cid=n_position_salary.cid  and  concat(s_bank.bank_name," : ",s_bank_acc.bank_acc)<>'.DB::raw('(select concat(s_bank.bank_name," : ",s_bank_acc.bank_acc) from s_bank_acc left join s_bank on s_bank.bank_id=s_bank_acc.bank_id where cid=n_position_salary.cid order by acc_id asc limit 1)').' order by acc_id desc limit 1) as acc2') )
		        ->paginate( 70 );
			}							
								
		    return View::make( 'emptype2.bank_acchome',  array( 'accall' => $accall ) );
		}	
		else
		{
			//return login
    		return View::make( 'login.index' );	
		}	
    }

	/**
	 * function name : fromAcc
	 * view page fromAcc
	 * 
	 * open model form
	*/
	public function fromAcc_type2( $id=null )
	{
		if ( Session::get('level') != '' )
		{
			$bank   = DB::table( 's_bank' )->get();	
			
		  	$dataacc = DB::table('s_bank_acc')
	    	->join('s_bank','s_bank.bank_id','=','s_bank_acc.bank_id')
	    	->where('s_bank_acc.cid','=',$id)
	    	->get();

			return View::make( 'emptype2.bank_fromacc',
				array(
					'bank' 	=> $bank,
					'dataacc' => $dataacc
				)
			 );		
		}
		else{
			//return login
    		return View::make( 'login.index' );	
		}
	}

	/**
	 * function name : addAcc
	 * view page addAcc
	 * 
	 * add data bank acc
	*/
	public function addAcc_type2()
	{
		//data form ajax
    	$inputData = Input::get('formData');
	    parse_str($inputData, $formFields);  
	    $Data = array(
	      'cid'      		=> $formFields['cidAcc2'],	
	      'bank_id'		=> $formFields['bank2'],
	      'bank_acc'		=> $formFields['bank_acc2']
	    );	 	

	    $rules = array(
        'bank_acc'     		=>  'required'
	    );

	    $messages = array(
	    'bank_acc.required' => 'กรุณากรอกเลขที่บัญชี' 	     
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
	    	$acc = DB::insert( 'insert into s_bank_acc ( cid, bank_id, bank_acc ) values ( ?, ?, ? )', 
				  array( 
				  	    $Data['cid'],
				  	    $Data['bank_id'],
				  		$Data['bank_acc']				  			  						  		
				  ) );

	    	if( $acc )
	    	{	 

		    	$dataacc = DB::table('s_bank_acc')
		    	->join('s_bank','s_bank.bank_id','=','s_bank_acc.bank_id')
		    	->where('s_bank_acc.cid','=',$Data['cid'])
		    	->get();

		    	$a = '';
		    	if( count($dataacc) > 0 )
		    	{
		    		$a = '<table  class="responsive" >';				
					$a .= '<tbody>';
					$ai=0;
					$a .= '<tr> <th  width="40" >ลำดับ</th> <th width="250" >ธนาคาร</th> <th width="250" >เลขที่บัญชี</th> <th width="40" >ลบ</th> </tr>';
	            
		            foreach ($dataacc as $dacc) {
		              $ai++;
		              $a .= '<tr>';
		              $a .= '<td>'.$ai.'</td>';           
		              $a .= '<td>'.$dacc->bank_name.'</td>';
		              $a .= '<td>'.$dacc->bank_acc.'</td>';
		              $a .= '<td><a title="ลบข้อมูล"  onclick="delAcc('.$dacc->cid.','.$dacc->acc_id.');" href="#"><i class="fi-x small"></i></a></td>';
		              $a .= '</tr>';
					}				
					$a .= '</tbody>';
					$a .= '</table>';
		    	}				

	    		return Response::json(array(
		          'success' => true,
		          'msg' 	=> 'เพิ่มข้อมูลเรียบร้อยแล้ว'	,
		          'w' 	=> $a	         
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
	}

	/**
	 * function name : deleteAcc
	 * view page deleteAcc
	 * 
	 * delete data bank acc
	*/
	function deleteAcc_type2( $id, $cid )
	{		
		if ( Session::get('level') != '' )
    	{    		
            $result = BankAcc::where( 'acc_id', $id )->delete();

            if( $result )
            {
            	$dataacc = DB::table('s_bank_acc')
		    	->join('s_bank','s_bank.bank_id','=','s_bank_acc.bank_id')
		    	->where('s_bank_acc.cid','=',$cid)
		    	->get();				

		    	$a='';
		    	if( count($dataacc) > 0 )
		    	{
		    		$a = '<table  class="responsive" >';				
					$a .= '<tbody>';
					$ai=0;
					$a .= '<tr> <th  width="40" >ลำดับ</th> <th width="250" >ธนาคาร</th> <th width="250" >เลขที่บัญชี</th> <th width="40" >ลบ</th> </tr>';
	            
		            foreach ($dataacc as $dacc) {
		              $ai++;
		              $a .= '<tr>';
		              $a .= '<td>'.$ai.'</td>';           
		              $a .= '<td>'.$dacc->bank_name.'</td>';
		              $a .= '<td>'.$dacc->bank_acc.'</td>';
		              $a .= '<td><a title="ลบข้อมูล"  onclick="delAcc('.$dacc->cid.','.$dacc->acc_id.');" href="#"><i class="fi-x small"></i></a></td>';
		              $a .= '</tr>';
					}				
					$a .= '</tbody>';
					$a .= '</table>';
		    	}				

            	return Response::json(array(
		          'success' => true,
		          'msg' => 'ลบข้อมูลเรียบร้อย',
		          'w' => $a
		        )); 
            }
            else
            {
            	return Response::json(array(
		          'success' => false,
		          'msg' => 'ไม่สามารถลบข้อมูลได้ กรุณาติดต่อผู้ดูแลระบบ'		         
		        ));
            }
		  
    	}
    	else
    	{
    		return View::make( 'login.index' );	
    	}     
	}


	//=================================== Bank Acc 3 =======================================//
	/**
	 * function name : bank_acc
	 * view page home bank_acc
	 * 
	 * get
	*/
	public function bank_acc_type3()
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
			->where( 'n_position_salary.level', '=', 'ข้าราชการ' )
			->where( 'n_datageneral.status', '=', '0' )
			->whereIn('n_position_salary.salaryID', $a )
			->select('n_datageneral.*', DB::raw('(select concat(s_bank.bank_name," : ",s_bank_acc.bank_acc) from s_bank_acc left join s_bank on s_bank.bank_id=s_bank_acc.bank_id where cid=n_position_salary.cid order by acc_id asc limit 1) as acc1'), DB::raw('(select concat(s_bank.bank_name," : ",s_bank_acc.bank_acc) from s_bank_acc left join s_bank on s_bank.bank_id=s_bank_acc.bank_id where cid=n_position_salary.cid  and  concat(s_bank.bank_name," : ",s_bank_acc.bank_acc)<>'.DB::raw('(select concat(s_bank.bank_name," : ",s_bank_acc.bank_acc) from s_bank_acc left join s_bank on s_bank.bank_id=s_bank_acc.bank_id where cid=n_position_salary.cid order by acc_id asc limit 1)').' order by acc_id desc limit 1) as acc2') )
	        ->paginate( 20 );
	      		       
		    return View::make( 'emptype3.bank_acchome',  array( 'accall' => $accall ) );
		}	
		else
		{
			//return login
    		return View::make( 'login.index' );	
		}				
	}

	/**
    * function name : bankacc3_post_search
    * search data s_bank_acc
    * post
    */
    public function bankacc3_post_search()
    {   		  
	   if ( Session::get('level') != '' )
		{
			$search  = Input::get( 'searchacc3' );	    		    			    

			$sqlp = ' select (select salaryID from n_position_salary where cid=n1.cid order by salaryID desc limit 1)  as salaryID from n_position_salary n1 group by n1.cid order by n1.salaryID ';
			$p = DB::select($sqlp);		
			foreach ($p as $key) {
				$a[] = $key->salaryID;
			}	

			if( $search == '' ){
				$accall = DB::table( 'n_datageneral' )	  
				->join( 'n_position_salary', 'n_position_salary.cid', '=', 'n_datageneral.cid' )      	        
				->where( 'n_position_salary.level', '=', 'ข้าราชการ' )	
				->where( 'n_datageneral.status', '=', '0' )						
				->whereIn('n_position_salary.salaryID', $a )			
				->select('n_datageneral.*', DB::raw('(select concat(s_bank.bank_name," : ",s_bank_acc.bank_acc) from s_bank_acc left join s_bank on s_bank.bank_id=s_bank_acc.bank_id where cid=n_position_salary.cid order by acc_id asc limit 1) as acc1'), DB::raw('(select concat(s_bank.bank_name," : ",s_bank_acc.bank_acc) from s_bank_acc left join s_bank on s_bank.bank_id=s_bank_acc.bank_id where cid=n_position_salary.cid  and  concat(s_bank.bank_name," : ",s_bank_acc.bank_acc)<>'.DB::raw('(select concat(s_bank.bank_name," : ",s_bank_acc.bank_acc) from s_bank_acc left join s_bank on s_bank.bank_id=s_bank_acc.bank_id where cid=n_position_salary.cid order by acc_id asc limit 1)').' order by acc_id desc limit 1) as acc2') )
		        ->paginate( 20 );
			}else{
				$accall = DB::table( 'n_datageneral' )	  
				->join( 'n_position_salary', 'n_position_salary.cid', '=', 'n_datageneral.cid' )      	        
				->where( 'n_position_salary.level', '=', 'ข้าราชการ' )	
				->where( 'n_datageneral.status', '=', '0' )						      
				->whereIn('n_position_salary.salaryID', $a )
				->where(function($query) use ( $search )
	            {
	                $query->where( 'n_datageneral.fname', 'like', "%$search%" )
	                	  ->orWhere( 'n_datageneral.lname', 'like', "%$search%" )
	                	  ->orWhere( 'n_datageneral.cid', 'like', "%$search%" );	 					     
	            })	 			
				->select('n_datageneral.*', DB::raw('(select concat(s_bank.bank_name," : ",s_bank_acc.bank_acc) from s_bank_acc left join s_bank on s_bank.bank_id=s_bank_acc.bank_id where cid=n_position_salary.cid order by acc_id asc limit 1) as acc1'), DB::raw('(select concat(s_bank.bank_name," : ",s_bank_acc.bank_acc) from s_bank_acc left join s_bank on s_bank.bank_id=s_bank_acc.bank_id where cid=n_position_salary.cid  and  concat(s_bank.bank_name," : ",s_bank_acc.bank_acc)<>'.DB::raw('(select concat(s_bank.bank_name," : ",s_bank_acc.bank_acc) from s_bank_acc left join s_bank on s_bank.bank_id=s_bank_acc.bank_id where cid=n_position_salary.cid order by acc_id asc limit 1)').' order by acc_id desc limit 1) as acc2') )
		        ->paginate( 70 );
			}			      		       
		    return View::make( 'emptype3.bank_acchome',  array( 'accall' => $accall ) );
		}	
		else
		{
			//return login
    		return View::make( 'login.index' );	
		}	
    }

	/**
	 * function name : fromAcc
	 * view page fromAcc
	 * 
	 * open model form
	*/
	public function fromAcc_type3( $id=null )
	{
		if ( Session::get('level') != '' )
		{
			$bank   = DB::table( 's_bank' )->get();	
			
		  	$dataacc = DB::table('s_bank_acc')
	    	->join('s_bank','s_bank.bank_id','=','s_bank_acc.bank_id')
	    	->where('s_bank_acc.cid','=',$id)
	    	->get();

			return View::make( 'emptype3.bank_fromacc',
				array(
					'bank' 	=> $bank,
					'dataacc' => $dataacc
				)
			 );		
		}
		else{
			//return login
    		return View::make( 'login.index' );	
		}
	}

	/**
	 * function name : addAcc
	 * view page addAcc
	 * 
	 * add data bank acc
	*/
	public function addAcc_type3()
	{
		//data form ajax
    	$inputData = Input::get('formData');
	    parse_str($inputData, $formFields);  
	    $Data = array(
	      'cid'      		=> $formFields['cidAcc3'],	
	      'bank_id'		=> $formFields['bank3'],
	      'bank_acc'		=> $formFields['bank_acc3']
	    );	 	

	    $rules = array(
        'bank_acc'     		=>  'required'
	    );

	    $messages = array(
	    'bank_acc.required' => 'กรุณากรอกเลขที่บัญชี' 	     
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
	    	$acc = DB::insert( 'insert into s_bank_acc ( cid, bank_id, bank_acc ) values ( ?, ?, ? )', 
				  array( 
				  	    $Data['cid'],
				  	    $Data['bank_id'],
				  		$Data['bank_acc']				  			  						  		
				  ) );

	    	if( $acc )
	    	{	 

		    	$dataacc = DB::table('s_bank_acc')
		    	->join('s_bank','s_bank.bank_id','=','s_bank_acc.bank_id')
		    	->where('s_bank_acc.cid','=',$Data['cid'])
		    	->get();

		    	$a = '';
		    	if( count($dataacc) > 0 )
		    	{
		    		$a = '<table  class="responsive" >';				
					$a .= '<tbody>';
					$ai=0;
					$a .= '<tr> <th  width="40" >ลำดับ</th> <th width="250" >ธนาคาร</th> <th width="250" >เลขที่บัญชี</th> <th width="40" >ลบ</th> </tr>';
	            
		            foreach ($dataacc as $dacc) {
		              $ai++;
		              $a .= '<tr>';
		              $a .= '<td>'.$ai.'</td>';           
		              $a .= '<td>'.$dacc->bank_name.'</td>';
		              $a .= '<td>'.$dacc->bank_acc.'</td>';
		              $a .= '<td><a title="ลบข้อมูล"  onclick="delAcc('.$dacc->cid.','.$dacc->acc_id.');" href="#"><i class="fi-x small"></i></a></td>';
		              $a .= '</tr>';
					}				
					$a .= '</tbody>';
					$a .= '</table>';
		    	}				

	    		return Response::json(array(
		          'success' => true,
		          'msg' 	=> 'เพิ่มข้อมูลเรียบร้อยแล้ว'	,
		          'w' 	=> $a	         
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
	}

	/**
	 * function name : deleteAcc
	 * view page deleteAcc
	 * 
	 * delete data bank acc
	*/
	function deleteAcc_type3( $id, $cid )
	{		
		if ( Session::get('level') != '' )
    	{    		
            $result = BankAcc::where( 'acc_id', $id )->delete();

            if( $result )
            {
            	$dataacc = DB::table('s_bank_acc')
		    	->join('s_bank','s_bank.bank_id','=','s_bank_acc.bank_id')
		    	->where('s_bank_acc.cid','=',$cid)
		    	->get();				

		    	$a='';
		    	if( count($dataacc) > 0 )
		    	{
		    		$a = '<table  class="responsive" >';				
					$a .= '<tbody>';
					$ai=0;
					$a .= '<tr> <th  width="40" >ลำดับ</th> <th width="250" >ธนาคาร</th> <th width="250" >เลขที่บัญชี</th> <th width="40" >ลบ</th> </tr>';
	            
		            foreach ($dataacc as $dacc) {
		              $ai++;
		              $a .= '<tr>';
		              $a .= '<td>'.$ai.'</td>';           
		              $a .= '<td>'.$dacc->bank_name.'</td>';
		              $a .= '<td>'.$dacc->bank_acc.'</td>';
		              $a .= '<td><a title="ลบข้อมูล"  onclick="delAcc('.$dacc->cid.','.$dacc->acc_id.');" href="#"><i class="fi-x small"></i></a></td>';
		              $a .= '</tr>';
					}				
					$a .= '</tbody>';
					$a .= '</table>';
		    	}				

            	return Response::json(array(
		          'success' => true,
		          'msg' => 'ลบข้อมูลเรียบร้อย',
		          'w' => $a
		        )); 
            }
            else
            {
            	return Response::json(array(
		          'success' => false,
		          'msg' => 'ไม่สามารถลบข้อมูลได้ กรุณาติดต่อผู้ดูแลระบบ'		         
		        ));
            }
		  
    	}
    	else
    	{
    		return View::make( 'login.index' );	
    	}     
	}


	//=================================== Bank Acc 4 =======================================//
	/**
	 * function name : bank_acc
	 * view page home bank_acc
	 * 
	 * get
	*/
	public function bank_acc_type4()
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
			->where( 'n_position_salary.level', '=', 'ลูกจ้างชั่วคราว' )
			->where( 'n_datageneral.status', '=', '0' )
			->whereIn('n_position_salary.salaryID', $a )
			->select('n_datageneral.*', DB::raw('(select concat(s_bank.bank_name," : ",s_bank_acc.bank_acc) from s_bank_acc left join s_bank on s_bank.bank_id=s_bank_acc.bank_id where cid=n_position_salary.cid order by acc_id asc limit 1) as acc1'), DB::raw('(select concat(s_bank.bank_name," : ",s_bank_acc.bank_acc) from s_bank_acc left join s_bank on s_bank.bank_id=s_bank_acc.bank_id where cid=n_position_salary.cid  and  concat(s_bank.bank_name," : ",s_bank_acc.bank_acc)<>'.DB::raw('(select concat(s_bank.bank_name," : ",s_bank_acc.bank_acc) from s_bank_acc left join s_bank on s_bank.bank_id=s_bank_acc.bank_id where cid=n_position_salary.cid order by acc_id asc limit 1)').' order by acc_id desc limit 1) as acc2') )
	        ->paginate( 20 );
	      		       
		    return View::make( 'emptype4.bank_acchome',  array( 'accall' => $accall ) );
		}	
		else
		{
			//return login
    		return View::make( 'login.index' );	
		}				
	}

	/**
    * function name : bankacc4_post_search
    * search data s_bank_acc
    * post
    */
    public function bankacc4_post_search()
    {   		  
	   if ( Session::get('level') != '' )
		{
			$search  = Input::get( 'searchacc4' );	    		    			    

			$sqlp = ' select (select salaryID from n_position_salary where cid=n1.cid order by salaryID desc limit 1)  as salaryID from n_position_salary n1 group by n1.cid order by n1.salaryID ';
			$p = DB::select($sqlp);		
			foreach ($p as $key) {
				$a[] = $key->salaryID;
			}	

			if( $search == '' ){
				$accall = DB::table( 'n_datageneral' )	  
				->join( 'n_position_salary', 'n_position_salary.cid', '=', 'n_datageneral.cid' )      	        
				->where( 'n_position_salary.level', '=', 'ลูกจ้างชั่วคราว' )	
				->where( 'n_datageneral.status', '=', '0' )						
				->whereIn('n_position_salary.salaryID', $a )			
				->select('n_datageneral.*', DB::raw('(select concat(s_bank.bank_name," : ",s_bank_acc.bank_acc) from s_bank_acc left join s_bank on s_bank.bank_id=s_bank_acc.bank_id where cid=n_position_salary.cid order by acc_id asc limit 1) as acc1'), DB::raw('(select concat(s_bank.bank_name," : ",s_bank_acc.bank_acc) from s_bank_acc left join s_bank on s_bank.bank_id=s_bank_acc.bank_id where cid=n_position_salary.cid  and  concat(s_bank.bank_name," : ",s_bank_acc.bank_acc)<>'.DB::raw('(select concat(s_bank.bank_name," : ",s_bank_acc.bank_acc) from s_bank_acc left join s_bank on s_bank.bank_id=s_bank_acc.bank_id where cid=n_position_salary.cid order by acc_id asc limit 1)').' order by acc_id desc limit 1) as acc2') )
		        ->paginate( 20 );
			}else{
				$accall = DB::table( 'n_datageneral' )	  
				->join( 'n_position_salary', 'n_position_salary.cid', '=', 'n_datageneral.cid' )      	        
				->where( 'n_position_salary.level', '=', 'ลูกจ้างชั่วคราว' )	
				->where( 'n_datageneral.status', '=', '0' )						      
				->whereIn('n_position_salary.salaryID', $a )
				->where(function($query) use ( $search )
	            {
	                $query->where( 'n_datageneral.fname', 'like', "%$search%" )
	                	  ->orWhere( 'n_datageneral.lname', 'like', "%$search%" )
	                	  ->orWhere( 'n_datageneral.cid', 'like', "%$search%" );	 					     
	            })	 			
				->select('n_datageneral.*', DB::raw('(select concat(s_bank.bank_name," : ",s_bank_acc.bank_acc) from s_bank_acc left join s_bank on s_bank.bank_id=s_bank_acc.bank_id where cid=n_position_salary.cid order by acc_id asc limit 1) as acc1'), DB::raw('(select concat(s_bank.bank_name," : ",s_bank_acc.bank_acc) from s_bank_acc left join s_bank on s_bank.bank_id=s_bank_acc.bank_id where cid=n_position_salary.cid  and  concat(s_bank.bank_name," : ",s_bank_acc.bank_acc)<>'.DB::raw('(select concat(s_bank.bank_name," : ",s_bank_acc.bank_acc) from s_bank_acc left join s_bank on s_bank.bank_id=s_bank_acc.bank_id where cid=n_position_salary.cid order by acc_id asc limit 1)').' order by acc_id desc limit 1) as acc2') )
		        ->paginate( 70 );
			}			      		       
		    return View::make( 'emptype4.bank_acchome',  array( 'accall' => $accall ) );
		}	
		else
		{
			//return login
    		return View::make( 'login.index' );	
		}	
    }

	/**
	 * function name : fromAcc
	 * view page fromAcc
	 * 
	 * open model form
	*/
	public function fromAcc_type4( $id=null )
	{
		if ( Session::get('level') != '' )
		{
			$bank   = DB::table( 's_bank' )->get();	
			
		  	$dataacc = DB::table('s_bank_acc')
	    	->join('s_bank','s_bank.bank_id','=','s_bank_acc.bank_id')
	    	->where('s_bank_acc.cid','=',$id)
	    	->get();

			return View::make( 'emptype4.bank_fromacc',
				array(
					'bank' 	=> $bank,
					'dataacc' => $dataacc
				)
			 );		
		}
		else{
			//return login
    		return View::make( 'login.index' );	
		}
	}

	/**
	 * function name : addAcc
	 * view page addAcc
	 * 
	 * add data bank acc
	*/
	public function addAcc_type4()
	{
		//data form ajax
    	$inputData = Input::get('formData');
	    parse_str($inputData, $formFields);  
	    $Data = array(
	      'cid'      		=> $formFields['cidAcc4'],	
	      'bank_id'		=> $formFields['bank4'],
	      'bank_acc'		=> $formFields['bank_acc4']
	    );	 	    	

	    $rules = array(
        'bank_acc'     		=>  'required'
	    );

	    $messages = array(
	    'bank_acc.required' => 'กรุณากรอกเลขที่บัญชี' 	     
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
	    	$acc = DB::insert( 'insert into s_bank_acc ( cid, bank_id, bank_acc ) values ( ?, ?, ? )', 
				  array( 
				  	    $Data['cid'],
				  	    $Data['bank_id'],
				  		$Data['bank_acc']				  			  						  		
				  ) );

	    	if( $acc )
	    	{	 

		    	$dataacc = DB::table('s_bank_acc')
		    	->join('s_bank','s_bank.bank_id','=','s_bank_acc.bank_id')
		    	->where('s_bank_acc.cid','=',$Data['cid'])
		    	->get();

		    	$a = '';
		    	if( count($dataacc) > 0 )
		    	{
		    		$a = '<table  class="responsive" >';				
					$a .= '<tbody>';
					$ai=0;
					$a .= '<tr> <th  width="40" >ลำดับ</th> <th width="250" >ธนาคาร</th> <th width="250" >เลขที่บัญชี</th> <th width="40" >ลบ</th> </tr>';
	            
		            foreach ($dataacc as $dacc) {
		              $ai++;
		              $a .= '<tr>';
		              $a .= '<td>'.$ai.'</td>';           
		              $a .= '<td>'.$dacc->bank_name.'</td>';
		              $a .= '<td>'.$dacc->bank_acc.'</td>';
		              $a .= '<td><a title="ลบข้อมูล"  onclick="delAcc('.$dacc->cid.','.$dacc->acc_id.');" href="#"><i class="fi-x small"></i></a></td>';
		              $a .= '</tr>';
					}				
					$a .= '</tbody>';
					$a .= '</table>';
		    	}				

	    		return Response::json(array(
		          'success' => true,
		          'msg' 	=> 'เพิ่มข้อมูลเรียบร้อยแล้ว'	,
		          'w' 	=> $a	         
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
	}

	/**
	 * function name : deleteAcc
	 * view page deleteAcc
	 * 
	 * delete data bank acc
	*/
	function deleteAcc_type4( $id, $cid )
	{		
		if ( Session::get('level') != '' )
    	{    		
            $result = BankAcc::where( 'acc_id', $id )->delete();

            if( $result )
            {
            	$dataacc = DB::table('s_bank_acc')
		    	->join('s_bank','s_bank.bank_id','=','s_bank_acc.bank_id')
		    	->where('s_bank_acc.cid','=',$cid)
		    	->get();				

		    	$a='';
		    	if( count($dataacc) > 0 )
		    	{
		    		$a = '<table  class="responsive" >';				
					$a .= '<tbody>';
					$ai=0;
					$a .= '<tr> <th  width="40" >ลำดับ</th> <th width="250" >ธนาคาร</th> <th width="250" >เลขที่บัญชี</th> <th width="40" >ลบ</th> </tr>';
	            
		            foreach ($dataacc as $dacc) {
		              $ai++;
		              $a .= '<tr>';
		              $a .= '<td>'.$ai.'</td>';           
		              $a .= '<td>'.$dacc->bank_name.'</td>';
		              $a .= '<td>'.$dacc->bank_acc.'</td>';
		              $a .= '<td><a title="ลบข้อมูล"  onclick="delAcc('.$dacc->cid.','.$dacc->acc_id.');" href="#"><i class="fi-x small"></i></a></td>';
		              $a .= '</tr>';
					}				
					$a .= '</tbody>';
					$a .= '</table>';
		    	}				

            	return Response::json(array(
		          'success' => true,
		          'msg' => 'ลบข้อมูลเรียบร้อย',
		          'w' => $a
		        )); 
            }
            else
            {
            	return Response::json(array(
		          'success' => false,
		          'msg' => 'ไม่สามารถลบข้อมูลได้ กรุณาติดต่อผู้ดูแลระบบ'		         
		        ));
            }
		  
    	}
    	else
    	{
    		return View::make( 'login.index' );	
    	}     
	}







	//================== Bank Acc 5 ======================//
	/**
	 * function name : bank_acc
	 * view page home bank_acc
	 * 
	 * get
	*/
	public function bank_acc_type5()
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
			->where( 'n_position_salary.level', '=', 'ลูกจ้างรายวัน' )
			->where( 'n_datageneral.status', '=', '0' )
			->whereIn('n_position_salary.salaryID', $a )
			->select('n_datageneral.*', DB::raw('(select concat(s_bank.bank_name," : ",s_bank_acc.bank_acc) from s_bank_acc left join s_bank on s_bank.bank_id=s_bank_acc.bank_id where cid=n_position_salary.cid order by acc_id asc limit 1) as acc1'), DB::raw('(select concat(s_bank.bank_name," : ",s_bank_acc.bank_acc) from s_bank_acc left join s_bank on s_bank.bank_id=s_bank_acc.bank_id where cid=n_position_salary.cid  and  concat(s_bank.bank_name," : ",s_bank_acc.bank_acc)<>'.DB::raw('(select concat(s_bank.bank_name," : ",s_bank_acc.bank_acc) from s_bank_acc left join s_bank on s_bank.bank_id=s_bank_acc.bank_id where cid=n_position_salary.cid order by acc_id asc limit 1)').' order by acc_id desc limit 1) as acc2') )
	        ->paginate( 20 );
			//->toSql();

	        //return $accall;
	      		       
		    return View::make( 'emptype5.bank_acchome',  array( 'accall' => $accall ) );
		}	
		else
		{
			//return login
    		return View::make( 'login.index' );	
		}				
	}

	/**
    * function name : bankacc1_post_search
    * search data s_bank_acc
    * post
    */
    public function bankacc5_post_search()
    {   		  
	   if ( Session::get('level') != '' )
		{
			$search  = Input::get( 'searchacc5' );	    		    			    

			$sqlp = ' select (select salaryID from n_position_salary where cid=n1.cid order by salaryID desc limit 1)  as salaryID from n_position_salary n1  group by n1.cid order by n1.salaryID ';
			$p = DB::select($sqlp);		
			foreach ($p as $key) {
				$a[] = $key->salaryID;
			}	

			if( $search == '' ){
				$accall = DB::table( 'n_datageneral' )	  
				->join( 'n_position_salary', 'n_position_salary.cid', '=', 'n_datageneral.cid' )      	        
				->where( 'n_position_salary.level', '=', 'ลูกจ้างรายวัน' )	
				->where( 'n_datageneral.status', '=', '0' )						
				->whereIn('n_position_salary.salaryID', $a )			
				->select('n_datageneral.*', DB::raw('(select concat(s_bank.bank_name," : ",s_bank_acc.bank_acc) from s_bank_acc left join s_bank on s_bank.bank_id=s_bank_acc.bank_id where cid=n_position_salary.cid order by acc_id asc limit 1) as acc1'), DB::raw('(select concat(s_bank.bank_name," : ",s_bank_acc.bank_acc) from s_bank_acc left join s_bank on s_bank.bank_id=s_bank_acc.bank_id where cid=n_position_salary.cid  and  concat(s_bank.bank_name," : ",s_bank_acc.bank_acc)<>'.DB::raw('(select concat(s_bank.bank_name," : ",s_bank_acc.bank_acc) from s_bank_acc left join s_bank on s_bank.bank_id=s_bank_acc.bank_id where cid=n_position_salary.cid order by acc_id asc limit 1)').' order by acc_id desc limit 1) as acc2') )
		        ->paginate( 20 );
			}else{
				$accall = DB::table( 'n_datageneral' )	  
				->join( 'n_position_salary', 'n_position_salary.cid', '=', 'n_datageneral.cid' )      	        
				->where( 'n_position_salary.level', '=', "ลูกจ้างรายวัน" )	
				->where( 'n_datageneral.status', '=', '0' )
				->whereIn('n_position_salary.salaryID', $a )	
		        ->where(function($query) use ( $search )
	            {
	                $query->where( 'n_datageneral.fname', 'like', "%$search%" )
	                	  ->orWhere( 'n_datageneral.lname', 'like', "%$search%" )
	                	  ->orWhere( 'n_datageneral.cid', 'like', "%$search%" );	 					     
	            })		       	
				->select('n_datageneral.*', DB::raw('(select concat(s_bank.bank_name," : ",s_bank_acc.bank_acc) from s_bank_acc left join s_bank on s_bank.bank_id=s_bank_acc.bank_id where cid=n_position_salary.cid order by acc_id asc limit 1) as acc1'), DB::raw('(select concat(s_bank.bank_name," : ",s_bank_acc.bank_acc) from s_bank_acc left join s_bank on s_bank.bank_id=s_bank_acc.bank_id where cid=n_position_salary.cid  and  concat(s_bank.bank_name," : ",s_bank_acc.bank_acc)<>'.DB::raw('(select concat(s_bank.bank_name," : ",s_bank_acc.bank_acc) from s_bank_acc left join s_bank on s_bank.bank_id=s_bank_acc.bank_id where cid=n_position_salary.cid order by acc_id asc limit 1)').' order by acc_id desc limit 1) as acc2') )	        		        		        	       
		        ->paginate( 70 );
			}								
		    return View::make( 'emptype5.bank_acchome',  array( 'accall' => $accall ) );
		}	
		else
		{
			//return login
    		return View::make( 'login.index' );	
		}	
    }

	/**
	 * function name : fromAcc
	 * view page fromAcc
	 * 
	 * open model form
	*/
	public function fromAcc_type5( $id=null )
	{
		if ( Session::get('level') != '' )
		{
			$bank   = DB::table( 's_bank' )->get();	
			
		  	$dataacc = DB::table('s_bank_acc')
	    	->join('s_bank','s_bank.bank_id','=','s_bank_acc.bank_id')
	    	->where('s_bank_acc.cid','=',$id)
	    	->get();

			return View::make( 'emptype5.bank_fromacc',
				array(
					'bank' 	=> $bank,
					'dataacc' => $dataacc
				)
			 );		
		}
		else{
			//return login
    		return View::make( 'login.index' );	
		}
	}

	/**
	 * function name : addAcc
	 * view page addAcc
	 * 
	 * add data bank acc
	*/
	public function addAcc_type5()
	{
		//data form ajax
    	$inputData = Input::get('formData');
	    parse_str($inputData, $formFields);  
	    $Data = array(
	      'cid'      		=> $formFields['cidAcc5'],	
	      'bank_id'		=> $formFields['bank5'],
	      'bank_acc'		=> $formFields['bank_acc5']
	    );	 	

	    $rules = array(
        'bank_acc'     		=>  'required'
	    );

	    $messages = array(
	    'bank_acc.required' => 'กรุณากรอกเลขที่บัญชี' 	     
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
	    	$acc = DB::insert( 'insert into s_bank_acc ( cid, bank_id, bank_acc ) values ( ?, ?, ? )', 
				  array( 
				  	    $Data['cid'],
				  	    $Data['bank_id'],
				  		$Data['bank_acc']				  			  						  		
				  ) );

	    	if( $acc )
	    	{	 

		    	$dataacc = DB::table('s_bank_acc')
		    	->join('s_bank','s_bank.bank_id','=','s_bank_acc.bank_id')
		    	->where('s_bank_acc.cid','=',$Data['cid'])
		    	->get();

		    	$a = '';
		    	if( count($dataacc) > 0 )
		    	{
		    		$a = '<table  class="responsive" >';				
					$a .= '<tbody>';
					$ai=0;
					$a .= '<tr> <th  width="40" >ลำดับ</th> <th width="250" >ธนาคาร</th> <th width="250" >เลขที่บัญชี</th> <th width="40" >ลบ</th> </tr>';
	            
		            foreach ($dataacc as $dacc) {
		              $ai++;
		              $a .= '<tr>';
		              $a .= '<td>'.$ai.'</td>';           
		              $a .= '<td>'.$dacc->bank_name.'</td>';
		              $a .= '<td>'.$dacc->bank_acc.'</td>';
		              $a .= '<td><a title="ลบข้อมูล"  onclick="delAcc5('.$dacc->cid.','.$dacc->acc_id.');" href="#"><i class="fi-x small"></i></a></td>';
		              $a .= '</tr>';
					}				
					$a .= '</tbody>';
					$a .= '</table>';
		    	}				

	    		return Response::json(array(
		          'success' => true,
		          'msg' 	=> 'เพิ่มข้อมูลเรียบร้อยแล้ว'	,
		          'w' 	=> $a	         
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
	}

	/**
	 * function name : deleteAcc
	 * view page deleteAcc
	 * 
	 * delete data bank acc
	*/
	function deleteAcc_type5( $id, $cid )
	{		
		if ( Session::get('level') != '' )
    	{    		
            $result = BankAcc::where( 'acc_id', $id )->delete();

            if( $result )
            {
            	$dataacc = DB::table('s_bank_acc')
		    	->join('s_bank','s_bank.bank_id','=','s_bank_acc.bank_id')
		    	->where('s_bank_acc.cid','=',$cid)
		    	->get();				

		    	$a='';
		    	if( count($dataacc) > 0 )
		    	{
		    		$a = '<table  class="responsive" >';				
					$a .= '<tbody>';
					$ai=0;
					$a .= '<tr> <th  width="40" >ลำดับ</th> <th width="250" >ธนาคาร</th> <th width="250" >เลขที่บัญชี</th> <th width="40" >ลบ</th> </tr>';
	            
		            foreach ($dataacc as $dacc) {
		              $ai++;
		              $a .= '<tr>';
		              $a .= '<td>'.$ai.'</td>';           
		              $a .= '<td>'.$dacc->bank_name.'</td>';
		              $a .= '<td>'.$dacc->bank_acc.'</td>';
		              $a .= '<td><a title="ลบข้อมูล"  onclick="delAcc('.$dacc->cid.','.$dacc->acc_id.');" href="#"><i class="fi-x small"></i></a></td>';
		              $a .= '</tr>';
					}				
					$a .= '</tbody>';
					$a .= '</table>';
		    	}				

            	return Response::json(array(
		          'success' => true,
		          'msg' => 'ลบข้อมูลเรียบร้อย',
		          'w' => $a
		        )); 
            }
            else
            {
            	return Response::json(array(
		          'success' => false,
		          'msg' => 'ไม่สามารถลบข้อมูลได้ กรุณาติดต่อผู้ดูแลระบบ'		         
		        ));
            }
		  
    	}
    	else
    	{
    		return View::make( 'login.index' );	
    	}     
	}







}

?>