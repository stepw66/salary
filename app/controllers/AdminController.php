<?php

class AdminController extends BaseController {

	//================== User ======================//
	/**
	 * function name : user
	 * view page home user
	 * add data to select option
	 * get
	*/
	public function user()
	{	
		if ( Session::get('level') != '' )
		{		
			$userall = DB::table( 's_users' )
	         ->join( 'n_datageneral', 'n_datageneral.cid', '=', 's_users.cid' )
	         ->orderBy( 's_users.user_id', 'asc')
	         ->paginate( 15 );	

		    return View::make( 'admin.userhome',  array( 'userall' => $userall ) );			    		 		
		}	
		else
		{
			//return login
    		return View::make( 'login.index' );	
		}				
	}

	/**
    * function name : user_post_search
    * search data s_users
    * post
    */
    public function user_post_search()
    {   		  
	   if ( Session::get('level') != '' )
		{
			$search  = Input::get( 'search' );	    		    

			$userall = DB::table( 's_users' )
	         ->join( 'n_datageneral', 'n_datageneral.cid', '=', 's_users.cid' )	     
	         ->where( 's_users.cid', 'like', "%$search%" )
	         ->orWhere( 'n_datageneral.fname', 'like', "%$search%" )	 
	          ->orWhere( 'n_datageneral.lname', 'like', "%$search%" )	     
	         ->orderBy( 's_users.user_id', 'asc')
	         ->paginate( 15 );	 		    
	     
			//view page create
		    return View::make( 'admin.userhome',  array( 'userall' => $userall ) );	
		}	
		else
		{
			//return login
    		return View::make( 'login.index' );	
		}	
    }

	/**
	 * function name : user_create
	 * view page create user
	 * add data to select option
	 * get
	*/
    public function user_create()
    {   	
    	if ( Session::get('level') != '' )
    	{      	
    		$datageneral = DB::table('n_datageneral')
    		               ->where('cid','<>','null')   		              
    		               ->get(array( DB::raw('CONCAT(cid," ",day(birthday),month(birthday),year(birthday)+543," ",pname,"",fname, " ", lname) as value') ));  		

    		foreach ($datageneral as $k => $v) {		       
    			$return_array[] = $v;    
		    }			
		
	        return View::make( 'admin.usercreate', array('datageneral' => $return_array) );
    	}
    	else
    	{
    		//return login
    		return View::make( 'login.index' );	
    	} 	
    }

    /**
	 * function name : user_autocomplete
	 * view page create autocomplete
	 * add data to select option
	 * get
	*/
    public function user_autocomplete()
    {
		$term = Input::get('term');
		
		$results = array();
		
		$datageneral = DB::table('n_datageneral')
	               ->where('cid', 'LIKE', '%'.$term.'%')
	               ->orWhere('fname', 'LIKE', '%'.$term.'%')
				   ->orWhere('lname', 'LIKE', '%'.$term.'%')		              
	               ->get(array( DB::raw('CONCAT(cid," ",DATE_FORMAT(birthday,"%d"),DATE_FORMAT(birthday,"%m"),DATE_FORMAT(birthday,"%Y")+543," ",pname,"",fname, " ", lname) as value') ));  		
	
		foreach ($datageneral as $query)
		{
		    $results[] = [ 'value' => $query->value ];
		}
		return Response::json($results);
	}

    /**
	 * function name : post_new_user
	 * reciep data post form create
	 * create new users
	 * post
	*/
    public function post_new_user()
    {
    	//get user details
	    $cid  		= Input::get( 'cid' );
	    $level 		= Input::get( 'level' );
	    $c1 		= ((Input::get( 'c1' ) == 1) ? 1:0);
	    $c2 		= ((Input::get( 'c2' ) == 1) ? 1:0);
	    $c3 		= ((Input::get( 'c3' ) == 1) ? 1:0);
	    $c4 		= ((Input::get( 'c4' ) == 1) ? 1:0);
        $c5         = ((Input::get( 'c5' ) == 1) ? 1:0);
        $c6         = ((Input::get( 'c6' ) == 1) ? 1:0);
			
		$rules = array(
			'cid'    => 'required'
		);
		$messages = array(
			'cid.required'    => '*** กรุณากรอกชื่อผู้ใช้ ***'
		);
  
	    $validator = Validator::make( Input::all(), $rules, $messages );
	    //check if the form is valid
	    if ( $validator->fails() )
	    {			
	        $messages = $validator->messages();			
			return Redirect::to( 'user/create' )->withErrors( $validator );
	    }
	    else
	    {
	    	$cid = explode(' ',$cid);
          
            if( count($cid) == 1 )
            {
            	return Redirect::to( 'user' )->with( 'error_message', 'ไม่สามารถเพิ่มข้อมูลผู้ใช้งานได้ กรุณาแจ้งผู้ดูแลระบบ' );   
            }
            else
            {
            	//create new user
	            $users = DB::insert( 'insert into s_users ( cid, password, level, c1, c2, c3, c4, c5, c6 ) values ( ?, ?, ?, ?, ?, ?, ?, ?, ? )', 
	            		array( 
	            			  $cid[0],
	            			  $cid[1],
	            			  $level,
	            			  $c1,
	            			  $c2,
	            			  $c3,
	            			  $c4,
                              $c5,
                              $c6 
	            	     ));          

	            if( $users )
	            {
	            	return Redirect::to( 'user' )->with( 'success_message', 'เพิ่มผู้ใช้งานเรียบร้อยแล้ว' );    
	            }
	            else
	            {
	                return Redirect::to( 'user' )->with( 'error_message', 'ไม่สามารถเพิ่มข้อมูลผู้ใช้งานได้ กรุณาแจ้งผู้ดูแลระบบ' );   
		        }
            }    	
        }
    }

     /**
    * function name : user_edit
    * edit data s_users
    * get
    */
    public function user_edit( $id ) 
    {
    	if ( Session::get('level') != '' )
    	{
    		$user = $this->_get_userdata( $id );      

		    return View::make(
		        'admin.useredit', 
		        array(
		            'user'      => $user	          		                
		            )
		    );
    	}
    	else
    	{
    		return View::make( 'login.index' );	
    	}      
    }

    /**
    * function name : post_edit_user
    * edit data c_users
    * post
    */
    public function post_edit_user( $id )
    {
    	//get user details
	    $level  	= Input::get( 'level' );
	    $c1 		= ((Input::get( 'c1' ) == 1) ? 1:0);
	    $c2 		= ((Input::get( 'c2' ) == 1) ? 1:0);
	    $c3 		= ((Input::get( 'c3' ) == 1) ? 1:0);
	    $c4 		= ((Input::get( 'c4' ) == 1) ? 1:0);
        $c5         = ((Input::get( 'c5' ) == 1) ? 1:0);
        $c6         = ((Input::get( 'c6' ) == 1) ? 1:0);
	   
        $user_data = array(
            'level' 	 => $level,
            'c1' 		 => $c1,
            'c2' 		 => $c2,
            'c3' 		 => $c3,
            'c4' 		 => $c4,
            'c5'         => $c5,
            'c6' 		 => $c6          		            	                       
        );  
      
        //update user details
        $result = DB::table( 's_users' )->where( 'cid', '=', $id )->update( $user_data );	        
        if( $result )
        {
        	return Redirect::to( 'user' )->with( 'success_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว' ); 
        }
        else
        {
        	return Redirect::to( 'user' )->with( 'error_message', 'ไม่สามารถแก้ไขข้อมูลได้ กรุณาแจ้งผู้ดูแลระบบ' ); 
        }	           
    }

    /**
    * function name : _get_userdata
    * get data s_users from id
    * 
    */
    private function _get_userdata( $id ){
	    $user_data = DB::table( 's_users' )
	         ->join( 'n_datageneral', 'n_datageneral.cid', '=', 's_users.cid' )	        
	        ->where( 's_users.cid', '=', $id )
	        ->first();
	    return $user_data;  
	}  

	 /**
    * function name : user_delete
    * edit data s_users
    * get
    */
    public function user_delete( $id ) 
    {
    	if ( Session::get('level') != '' )
    	{    		
            $result = User::where( 'cid', $id )->delete();

		   if( $result )
	        {
	        	return Redirect::to( 'user' )->with( 'success_message', 'ลบข้อมูลเรียบร้อยแล้ว' ); 
	        }
	        else
	        {
	        	return Redirect::to( 'user' )->with( 'error_message', 'ไม่สามารถลบข้อมูลได้ กรุณาแจ้งผู้ดูแลระบบ' ); 
	        }	   
    	}
    	else
    	{
    		return View::make( 'login.index' );	
    	}      
    }


    //====================================== Bank ==========================================//
    /**
	 * function name : bank
	 * view page home bank
	 * add data to select option
	 * get
	*/
	public function bank()
	{	
		if ( Session::get('level') != '' )
		{		
			$userall = DB::table( 's_bank' )	         
	         ->orderBy( 's_bank.bank_id', 'asc')
	         ->paginate( 15 );	

		    return View::make( 'admin.bankhome',  array( 'bankall' => $userall ) );			    		 		
		}	
		else
		{
			//return login
    		return View::make( 'login.index' );	
		}				
	}

	/**
    * function name : bank_post_search
    * search data s_bank
    * post
    */
    public function bank_post_search()
    {   		  
	   if ( Session::get('level') != '' )
		{
			$search  = Input::get( 'search' );	    		    

			$userall = DB::table( 's_bank' )	       
	         ->where( 's_bank.bank_name', 'like', "%$search%" )	        	             
	         ->orderBy( 's_bank.bank_id', 'asc')
	         ->paginate( 15 );	 		    
	     
			//view page create
		    return View::make( 'admin.bankhome',  array( 'bankall' => $userall ) );	
		}	
		else
		{
			//return login
    		return View::make( 'login.index' );	
		}	
    }

	/**
	 * function name : bank_create
	 * view page create bank
	 * 
	 * get
	*/
    public function bank_create()
    {   	
    	if ( Session::get('level') != '' )
    	{      	    		
	        return View::make( 'admin.bankcreate' );
    	}
    	else
    	{
    		//return login
    		return View::make( 'login.index' );	
    	} 	
    }

    /**
	 * function name : post_new_bank
	 * reciep data post form create
	 * create new bank
	 * post
	*/
    public function post_new_bank()
    {
    	//get user details
	    $bank_name  = Input::get( 'bank_name' );
			
	    $validator = Validator::make( Input::all(), Bank::$rules, Bank::$messages );
	    //check if the form is valid
	    if ( $validator->fails() )
	    {			
	        $messages = $validator->messages();			
			return Redirect::to( 'bank/create' )->withErrors( $validator );
	    }
	    else
	    {    	
        	//create new bank
            $bank = DB::insert( 'insert into s_bank ( bank_name ) values ( ? )', 
            		array(           			 
            			  $bank_name 
            	     ));          

            if( $bank )
            {
            	return Redirect::to( 'bank' )->with( 'success_message', 'เพิ่มธนาคารเรียบร้อยแล้ว' );    
            }
            else
            {
                return Redirect::to( 'bank' )->with( 'error_message', 'ไม่สามารถเพิ่มข้อมูลธนาคารได้ กรุณาแจ้งผู้ดูแลระบบ' );   
	        }
                	
        }
    }

    /**
    * function name : bank_edit
    * edit data s_bank
    * get
    */
    public function bank_edit( $id ) 
    {
    	if ( Session::get('level') != '' )
    	{
    		$bank = $this->_get_bankdata( $id );      

		    return View::make(
		        'admin.bankedit', 
		        array(
		            'bank'      => $bank	          		                
		            )
		    );
    	}
    	else
    	{
    		return View::make( 'login.index' );	
    	}      
    }

    /**
    * function name : post_edit_bank
    * edit data c_bank
    * post
    */
    public function post_edit_bank( $id )
    {
    	//get bank details
	    $bank_name  	= Input::get( 'bank_name' );
	   
        $bank_data = array(
            'bank_name' 	 => $bank_name                    		            	                       
        );  
      
        //update user details
        $result = DB::table( 's_bank' )->where( 'bank_id', '=', $id )->update( $bank_data );	        
        if( $result )
        {
        	return Redirect::to( 'bank' )->with( 'success_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว' ); 
        }
        else
        {
        	return Redirect::to( 'bank' )->with( 'error_message', 'ไม่สามารถแก้ไขข้อมูลได้ กรุณาแจ้งผู้ดูแลระบบ' ); 
        }	           
    }

    /**
    * function name : _get_bankdata
    * get data s_bank from id
    * 
    */
    private function _get_bankdata( $id ){
	    $bank_data = DB::table( 's_bank' )	        	        
	        ->where( 's_bank.bank_id', '=', $id )
	        ->first();
	    return $bank_data;  
	}  

	 /**
    * function name : bank_delete
    * edit data s_bank
    * get
    */
    public function bank_delete( $id ) 
    {
    	if ( Session::get('level') != '' )
    	{    		
            $result = Bank::where( 'bank_id', $id )->delete();

		   if( $result )
	        {
	        	return Redirect::to( 'bank' )->with( 'success_message', 'ลบข้อมูลเรียบร้อยแล้ว' ); 
	        }
	        else
	        {
	        	return Redirect::to( 'bank' )->with( 'error_message', 'ไม่สามารถลบข้อมูลได้ กรุณาแจ้งผู้ดูแลระบบ' ); 
	        }	   
    	}
    	else
    	{
    		return View::make( 'login.index' );	
    	}      
    }


    /**
    * function name : user_general_data
    * edit data ข้อมูลทั่วไป
    * get
    */
    public function user_general_data()
    {
    	if ( Session::get('level') != '' )
    	{    
    		 $result = DB::table( 's_general_data' )->first();	

             return View::make( 'admin.generaldata', array('data' => $result ) );	
    	}
    	else
    	{
    		return View::make( 'login.index' );	
    	}   
    }

    /**
    * function name : post_general_add
    * edit data add ข้อมูลทั่วไป
    * get
    */
    public function post_general_add()
    {
    	$name      = Input::get( 'name' );
		$address   = Input::get( 'address' );
        $tax_id    = Input::get( 'tax_id' );
        $address2   = Input::get( 'address2' );
        $tax_id2    = Input::get( 'tax_id2' );
        $director  = Input::get( 'director' );

		if( $name == '' )
		{
			return Redirect::to( 'user/general_data' )->with( 'error_message', 'ไม่สามารถเพิ่มข้อมูลได้ กรุณากรอกชื่อโรงพยาบาล' );  
		}

    	//create new bank
        $result = DB::insert( 'insert into s_general_data ( name, address, tax_id, address2, tax_id2, director ) values ( ?, ?, ?, ?, ?, ? )', 
        		array(           			 
        			  $name,
        			  $address,
                      $tax_id,
                      $address2,
                      $tax_id2,
                      $director 
        	     ));          

        if( $result )
        {
        	return Redirect::to( 'user/general_data' )->with( 'success_message', 'เพิ่มข้อมูลเรียบร้อยแล้ว' );    
        }
        else
        {
            return Redirect::to( 'user/general_data' )->with( 'error_message', 'ไม่สามารถเพิ่มข้อมูลได้ กรุณาแจ้งผู้ดูแลระบบ' );   
        }               	       
    }

    /**
    * function name : post_general_add
    * edit data update ข้อมูลทั่วไป
    * get
    */
    public function post_general_update( $id=null )
    {
    	$name       = Input::get( 'name' );
		$address    = Input::get( 'address' );
        $tax_id     = Input::get( 'tax_id' );
        $address2    = Input::get( 'address2' );
        $tax_id2     = Input::get( 'tax_id2' );
        $director   = Input::get( 'director' );
	   
        $data = array(
            'name' 	    => $name,
            'address' 	=> $address,
            'tax_id'    => $tax_id,
            'address2'   => $address2,
            'tax_id2'    => $tax_id2,
            'director'  => $director                                  		            	                       
        );  
            
        $result = DB::table( 's_general_data' )->where( 'generalID', '=', $id )->update( $data );	        
        if( $result )
        {
        	return Redirect::to( 'user/general_data' )->with( 'success_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว' ); 
        }
        else
        {
        	return Redirect::to( 'user/general_data' )->with( 'error_message', 'ไม่สามารถแก้ไขข้อมูลได้ กรุณาแจ้งผู้ดูแลระบบ' ); 
        }	      
    }


    public function userdep()
    {
        $dep = DB::Select( 'select * from n_department' );
        $user = DB::Select( 'select cid, concat( pname,"",fname," ",lname ) as name from n_datageneral where status = 0' );

        return View::make( 'admin.userdep', array('dep' => $dep, 'user' => $user ) );
    }

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
     * function name : edituserdep
     * view
     * edit n_datageneral
     * get
    */
    public function edituserdep( $depid, $cid )
    {              
        $d = array(
            'dep_id'      => $depid                                                                 
        );  
           
        $result = DB::table( 'n_datageneral' )->where( 'cid', '=', $cid )->update( $d );        
        
        $data = DB::Select( 'select n.cid, concat( n.pname, "", n.fname, " ", n.lname ) as name, dep_id, has_pts, has_pts2, has_ot, has_ch8, has_ch11, has_no_v, has_pcu, has_sub_ot from  n_datageneral n  where n.dep_id='.$depid.' ' );
                                 
        $t ='<table class="responsive">';
        $t .='<tr>';
        $t .='<th width="140">รหัสบัตร</th> <th>ชื่อ-นามสกุล</th> <th width="70">พตส.เงินนอกงบประมาณ</th> <th width="70">พตส.เงินงบประมาณ</th> <th width="50">ot</th> <th width="50">ฉ8</th> <th width="50">ฉ11</th> <th width="80">ไม่ทำเวช</th> <th width="95">ออกหน่วย</th> <th width="80">หักน้ำ-ไฟ</th> <th width="40">#</th>';
        $t .='</tr>';
        foreach ($data as $k) {
            $t .='<tr>';
            $t .=' <td>'.$k->cid.'</td>';
            $t .=' <td>'.$k->name.'</td>'; 
            $t .=' <td><input '.(($k->has_pts == 1) ? 'checked="checked"':'').'  onclick="chk_has('.$k->cid.','.$k->has_pts.','."'has_pts'".','.$k->dep_id.')" id="h_pts1"  type="checkbox"></td>'; 
            $t .=' <td><input '.(($k->has_pts2 == 1) ? 'checked="checked"':'').'  onclick="chk_has('.$k->cid.','.$k->has_pts2.','."'has_pts2'".','.$k->dep_id.')" id="h_pts21"  type="checkbox"></td>'; 
            $t .=' <td><input '.(($k->has_ot == 1) ? 'checked="checked"':'').' onclick="chk_has('.$k->cid.','.$k->has_ot.','."'has_ot'".','.$k->dep_id.')" id="h_ot1" type="checkbox"></td>';
            $t .=' <td><input '.(($k->has_ch8 == 1) ? 'checked="checked"':'').' onclick="chk_has('.$k->cid.','.$k->has_ch8.','."'has_ch8'".','.$k->dep_id.')" id="h_ch81" type="checkbox"></td>';
            $t .=' <td><input '.(($k->has_ch11 == 1) ? 'checked="checked"':'').' onclick="chk_has('.$k->cid.','.$k->has_ch11.','."'has_ch11'".','.$k->dep_id.')" id="h_ch111" type="checkbox"></td>';
            $t .=' <td><input '.(($k->has_no_v == 1) ? 'checked="checked"':'').' onclick="chk_has('.$k->cid.','.$k->has_no_v.','."'has_no_v'".','.$k->dep_id.')" id="h_nov1" type="checkbox"></td>';
            $t .=' <td><input '.(($k->has_pcu == 1) ? 'checked="checked"':'').' onclick="chk_has('.$k->cid.','.$k->has_pcu.','."'has_pcu'".','.$k->dep_id.')" id="h_pcu1" type="checkbox"></td>';
            $t .=' <td><input '.(($k->has_sub_ot == 1) ? 'checked="checked"':'').' onclick="chk_has('.$k->cid.','.$k->has_sub_ot.','."'has_sub_ot'".','.$k->dep_id.')" id="h_subot1" type="checkbox"></td>';              
            $t .=' <td>';
            $t .=' <a title="ลบ" onclick="delempdep('.$k->cid.','.$k->dep_id.')" href="#"> <i class="fi-x small"></i> </a> ';
            $t .=' </td>';
            $t .='</tr>';
        }
        $t .='</table>';
        return $t;             
    }

     /**
     * function name : viewuserdep
     * view
     * edit n_datageneral
     * get
    */
    public function viewuserdep( $depid )
    {
        $data = DB::Select( 'select n.cid, concat( n.pname, "", n.fname, " ", n.lname ) as name, dep_id, has_pts, has_pts2, has_ot, has_ch8, has_ch11, has_ch112, has_no_v, has_pcu, has_sub_ot  from  n_datageneral n  where n.dep_id='.$depid.' ' );
                                 
        $t ='<table class="responsive">';
        $t .='<tr>';
        $t .='<th width="140">รหัสบัตร</th> <th>ชื่อ-นามสกุล</th> <th width="70">พตส.เงินนอกงบประมาณ</th> <th width="70">พตส.เงินงบประมาณ</th> <th width="50">ot</th> <th width="50">ฉ8</th><th width="80">ฉ11 เงินนอกงบประมาณ</th><th width="80">ฉ11 เงินงบประมาณ</th> <th width="60">ไม่ทำเวช</th> <th width="95">ออกหน่วย</th> <th width="80">หักน้ำ-ไฟ</th> <th width="40">#</th>';
        $t .='</tr>';
        foreach ($data as $k) {
            $t .='<tr>';
            $t .=' <td>'.$k->cid.'</td>';
            $t .=' <td>'.$k->name.'</td>';  
            $t .=' <td><input '.(($k->has_pts == 1) ? 'checked="checked"':'').'  onclick="chk_has('.$k->cid.','.$k->has_pts.','."'has_pts'".','.$k->dep_id.')" id="h_pts1"  type="checkbox"></td>'; 
            $t .=' <td><input '.(($k->has_pts2 == 1) ? 'checked="checked"':'').'  onclick="chk_has('.$k->cid.','.$k->has_pts2.','."'has_pts2'".','.$k->dep_id.')" id="h_pts21"  type="checkbox"></td>'; 
            $t .=' <td><input '.(($k->has_ot == 1) ? 'checked="checked"':'').' onclick="chk_has('.$k->cid.','.$k->has_ot.','."'has_ot'".','.$k->dep_id.')" id="h_ot1" type="checkbox"></td>';
            $t .=' <td><input '.(($k->has_ch8 == 1) ? 'checked="checked"':'').' onclick="chk_has('.$k->cid.','.$k->has_ch8.','."'has_ch8'".','.$k->dep_id.')" id="h_ch81" type="checkbox"></td>';
            $t .=' <td><input '.(($k->has_ch11 == 1) ? 'checked="checked"':'').' onclick="chk_has('.$k->cid.','.$k->has_ch11.','."'has_ch11'".','.$k->dep_id.')" id="h_ch111" type="checkbox"></td>';
            $t .=' <td><input '.(($k->has_ch112 == 1) ? 'checked="checked"':'').' onclick="chk_has('.$k->cid.','.$k->has_ch112.','."'has_ch112'".','.$k->dep_id.')" id="h_ch1112" type="checkbox"></td>';
            $t .=' <td><input '.(($k->has_no_v == 1) ? 'checked="checked"':'').' onclick="chk_has('.$k->cid.','.$k->has_no_v.','."'has_no_v'".','.$k->dep_id.')" id="h_nov1" type="checkbox"></td>';
            $t .=' <td><input '.(($k->has_pcu == 1) ? 'checked="checked"':'').' onclick="chk_has('.$k->cid.','.$k->has_pcu.','."'has_pcu'".','.$k->dep_id.')" id="h_pcu1" type="checkbox"></td>';
            $t .=' <td><input '.(($k->has_sub_ot == 1) ? 'checked="checked"':'').' onclick="chk_has('.$k->cid.','.$k->has_sub_ot.','."'has_sub_ot'".','.$k->dep_id.')" id="h_subot1" type="checkbox"></td>';          
            $t .=' <td>';
            $t .=' <a title="ลบ" onclick="delempdep('.$k->cid.','.$k->dep_id.')" href="#"> <i class="fi-x small"></i> </a> ';
            $t .=' </td>';
            $t .='</tr>';
        }
        $t .='</table>';
        return $t;        
    }

    /**
     * function name : delempdep
     * view
     * edit n_datageneral
     * get
    */
    public function delempdep( $cid, $depid )
    {
        $d = array(
            'dep_id'      => ''                                                                 
        );  
           
        $result = DB::table( 'n_datageneral' )->where( 'cid', '=', $cid )->update( $d );        
        
        $data = DB::Select( 'select n.cid, concat( n.pname, "", n.fname, " ", n.lname ) as name, dep_id, has_pts, has_pts2, has_ot, has_ch8, has_ch11, has_no_v, has_pcu, has_sub_ot from  n_datageneral n  where n.dep_id='.$depid.' ' );
                                 
        $t ='<table class="responsive">';
        $t .='<tr>';
        $t .='<th width="140">รหัสบัตร</th> <th>ชื่อ-นามสกุล</th> <th width="70">พตส.เงินนอกงบประมาณ</th> <th width="70">พตส.เงินงบประมาณ</th> <th width="50">ot</th> <th width="50">ฉ8</th><th width="80">ฉ11 เงินนอกงบประมาณ</th><th width="80">ฉ11 เงินงบประมาณ</th> <th width="60">ไม่ทำเวช</th> <th width="95">ออกหน่วย</th> <th width="80">หักน้ำ-ไฟ</th> <th width="40">#</th>';
        $t .='</tr>';
        foreach ($data as $k) {
            $t .='<tr>';
            $t .=' <td>'.$k->cid.'</td>';
            $t .=' <td>'.$k->name.'</td>'; 
            $t .=' <td><input '.(($k->has_pts == 1) ? 'checked="checked"':'').'  onclick="chk_has('.$k->cid.','.$k->has_pts.','."'has_pts'".','.$k->dep_id.')" id="h_pts1"  type="checkbox"></td>'; 
            $t .=' <td><input '.(($k->has_pts2 == 1) ? 'checked="checked"':'').'  onclick="chk_has('.$k->cid.','.$k->has_pts2.','."'has_pts2'".','.$k->dep_id.')" id="h_pts21"  type="checkbox"></td>'; 
            $t .=' <td><input '.(($k->has_ot == 1) ? 'checked="checked"':'').' onclick="chk_has('.$k->cid.','.$k->has_ot.','."'has_ot'".','.$k->dep_id.')" id="h_ot1" type="checkbox"></td>';
            $t .=' <td><input '.(($k->has_ch8 == 1) ? 'checked="checked"':'').' onclick="chk_has('.$k->cid.','.$k->has_ch8.','."'has_ch8'".','.$k->dep_id.')" id="h_ch81" type="checkbox"></td>';
            $t .=' <td><input '.(($k->has_ch11 == 1) ? 'checked="checked"':'').' onclick="chk_has('.$k->cid.','.$k->has_ch11.','."'has_ch11'".','.$k->dep_id.')" id="h_ch111" type="checkbox"></td>';
            $t .=' <td><input '.(($k->has_ch112 == 1) ? 'checked="checked"':'').' onclick="chk_has('.$k->cid.','.$k->has_ch112.','."'has_ch112'".','.$k->dep_id.')" id="h_ch1112" type="checkbox"></td>';
            $t .=' <td><input '.(($k->has_no_v == 1) ? 'checked="checked"':'').' onclick="chk_has('.$k->cid.','.$k->has_no_v.','."'has_no_v'".','.$k->dep_id.')" id="h_nov1" type="checkbox"></td>';
            $t .=' <td><input '.(($k->has_pcu == 1) ? 'checked="checked"':'').' onclick="chk_has('.$k->cid.','.$k->has_pcu.','."'has_pcu'".','.$k->dep_id.')" id="h_pcu1" type="checkbox"></td>';
            $t .=' <td><input '.(($k->has_sub_ot == 1) ? 'checked="checked"':'').' onclick="chk_has('.$k->cid.','.$k->has_sub_ot.','."'has_sub_ot'".','.$k->dep_id.')" id="h_subot1" type="checkbox"></td>';               
            $t .=' <td>';
            $t .=' <a title="ลบ" onclick="delempdep('.$k->cid.','.$k->dep_id.')" href="#"> <i class="fi-x small"></i> </a> ';
            $t .=' </td>';
            $t .='</tr>';
        }
        $t .='</table>';
        return $t;  
    }

    /**
     * function name : edit_hasData
     * view
     * edit n_datageneral
     * get
    */
    public function edit_hasData( $cid, $value, $field, $dep_id )
    {
        $d = array(
            $field  => $value                                                                
        ); 

        $result = DB::table( 'n_datageneral' )->where( 'cid', '=', $cid )->update( $d ); 

        $data = DB::Select( 'select n.cid, concat( n.pname, "", n.fname, " ", n.lname ) as name, dep_id, has_pts, has_pts2, has_ot, has_ch8, has_ch11, has_ch112, has_no_v, has_pcu, has_sub_ot  from  n_datageneral n  where n.dep_id='.$dep_id.' ' );
                                 
        $t ='<table class="responsive">';
        $t .='<tr>';
        $t .='<th width="140">รหัสบัตร</th> <th>ชื่อ-นามสกุล</th> <th width="70">พตส.เงินนอกงบประมาณ</th> <th width="70">พตส.เงินงบประมาณ</th> <th width="50">ot</th> <th width="50">ฉ8</th><th width="80">ฉ11 เงินนอกงบประมาณ</th><th width="80">ฉ11 เงินงบประมาณ</th> <th width="60">ไม่ทำเวช</th> <th width="95">ออกหน่วย</th> <th width="80">หักน้ำ-ไฟ</th> <th width="40">#</th>';
        $t .='</tr>';
        foreach ($data as $k) {
            $t .='<tr>';
            $t .=' <td>'.$k->cid.'</td>';
            $t .=' <td>'.$k->name.'</td>';  
            $t .=' <td><input '.(($k->has_pts == 1) ? 'checked="checked"':'').'  onclick="chk_has('.$k->cid.','.$k->has_pts.','."'has_pts'".','.$k->dep_id.')" id="h_pts1"  type="checkbox"></td>'; 
            $t .=' <td><input '.(($k->has_pts2 == 1) ? 'checked="checked"':'').'  onclick="chk_has('.$k->cid.','.$k->has_pts2.','."'has_pts2'".','.$k->dep_id.')" id="h_pts21"  type="checkbox"></td>';
            $t .=' <td><input '.(($k->has_ot == 1) ? 'checked="checked"':'').' onclick="chk_has('.$k->cid.','.$k->has_ot.','."'has_ot'".','.$k->dep_id.')" id="h_ot1" type="checkbox"></td>';
            $t .=' <td><input '.(($k->has_ch8 == 1) ? 'checked="checked"':'').' onclick="chk_has('.$k->cid.','.$k->has_ch8.','."'has_ch8'".','.$k->dep_id.')" id="h_ch81" type="checkbox"></td>';
            $t .=' <td><input '.(($k->has_ch11 == 1) ? 'checked="checked"':'').' onclick="chk_has('.$k->cid.','.$k->has_ch11.','."'has_ch11'".','.$k->dep_id.')" id="h_ch111" type="checkbox"></td>';
            $t .=' <td><input '.(($k->has_ch112 == 1) ? 'checked="checked"':'').' onclick="chk_has('.$k->cid.','.$k->has_ch112.','."'has_ch112'".','.$k->dep_id.')" id="h_ch1112" type="checkbox"></td>';
            $t .=' <td><input '.(($k->has_no_v == 1) ? 'checked="checked"':'').' onclick="chk_has('.$k->cid.','.$k->has_no_v.','."'has_no_v'".','.$k->dep_id.')" id="h_nov1" type="checkbox"></td>';
            $t .=' <td><input '.(($k->has_pcu == 1) ? 'checked="checked"':'').' onclick="chk_has('.$k->cid.','.$k->has_pcu.','."'has_pcu'".','.$k->dep_id.')" id="h_pcu1" type="checkbox"></td>';
            $t .=' <td><input '.(($k->has_sub_ot == 1) ? 'checked="checked"':'').' onclick="chk_has('.$k->cid.','.$k->has_sub_ot.','."'has_sub_ot'".','.$k->dep_id.')" id="h_subot1" type="checkbox"></td>';          
            $t .=' <td>';
            $t .=' <a title="ลบ" onclick="delempdep('.$k->cid.','.$k->dep_id.')" href="#"> <i class="fi-x small"></i> </a> ';
            $t .=' </td>';
            $t .='</tr>';
        }
        $t .='</table>';
        return $t; 
    }

    public function usersort()
    {
        $dep = DB::Select( 'select * from n_department' );
       
        return View::make( 'admin.usersort', array( 'dep' => $dep ) );
    }

    public function depupdate( $id, $num )
    {
        $d = array(
            'sort'  => $num                                                                
        ); 

        $result = DB::table( 'n_department' )->where( 'department_id', '=', $id )->update( $d ); 
    }


    public function sortrepay()
    {
        return View::make( 'admin.sortrepay' );
    }

    private function get_department( $id )
    {
        $data = DB::Select( 'select departmentName from n_department where department_id='.$id.' ' );
        foreach ($data as $k) {
            return $k->departmentName;
        }
    }

    public function list_userpay( $field )
    {
       // $data = DB::Select( 'select concat( pname, "", fname, " ", lname ) as name, cid, dep_id, q_pts, q_ot, q_ch8, q_no_v, q_pcu, q_sub_ot  from  n_datageneral  where '.$field.'=1 ' );
        
        $data = DB::table( 'n_datageneral' )            
            ->join('n_department', 'n_department.department_id', '=', 'n_datageneral.dep_id')                                               
            ->where( 'n_datageneral.'.$field, '=', '1' )                                   
            ->orderBY( 'n_department.sort','asc' )
            ->orderBy( 'n_datageneral.datainfoID', 'asc' )  
            ->select( DB::Raw('concat(n_datageneral.pname,"",n_datageneral.fname," ",n_datageneral.lname) as name'),'n_department.sort', 'n_datageneral.*' )
            ->get();

        $t ='<table class="responsive">';
        $t .='<tr>';
        $t .='<th width="140">รหัสบัตร</th> <th>ชื่อ-นามสกุล</th> <th width="150">ลำดับ</th>';
        $t .='</tr>';
        $i=0;
       
        foreach ($data as $k) {

            if( $i == 0 ){
                $d = $k->sort;
                
                $t .= '<tr>';
                $t .= '<td colspan="3" class="tr_dep">'.$this->get_department( $k->dep_id ).'</td>';
                $t .= '</tr>';
            }

            if( $d != $k->sort )
            {
                $t .= '<tr>';
                $t .= '<td colspan="3" class="tr_dep">'.$this->get_department( $k->dep_id ).'</td>';
                $t .= '</tr>';

                $d = $k->sort;
            }   

            $t .='<tr>';
            $t .=' <td>'.'<input name="cidpay1[]" id="cidpay1'.$i.'" type="hidden" value="'.$k->cid.'" >'.$k->cid.'</td>';
            $t .=' <td>'.$k->name.'</td>';  

            if( $field == 'has_pts' ){ $v=$k->q_pts; }
            if( $field == 'has_pts2' ){ $v=$k->q_pts2; }
            if( $field == 'has_ot' ){ $v=$k->q_ot; }
            if( $field == 'has_ch8' ){ $v=$k->q_ch8; }
            if( $field == 'has_ch11' ){ $v=$k->q_ch11; }
            if( $field == 'has_ch112' ){ $v=$k->q_ch112; }
            if( $field == 'has_no_v' ){ $v=$k->q_no_v; }
            if( $field == 'has_pcu' ){ $v=$k->q_pcu; }
            if( $field == 'has_sub_ot' ){ $v=$k->q_sub_ot; }

            $t .= '<td>'.'<input name="q_pay1[]" id="q_pay1'.$i.'" type="text" value="'.$v.'" >'.'</td>';          
            $t .='</tr>';

            $i++;
        }
        $t .='</table>';
        if( count($data) > 0 ){
            $t .= '<center><a href="#" onclick="update_qpay( '."'$field'".' )" class="button success small">บันทึก</a></center>';
        }
        return $t; 
    }

    public function list_qpay( $field, $value, $cid )
    {
        if( $field == 'has_pts' ){ $v='q_pts'; }
        if( $field == 'has_pts2' ){ $v='q_pts2'; }
        if( $field == 'has_ot' ){ $v='q_ot'; }
        if( $field == 'has_ch8' ){ $v='q_ch8'; }
        if( $field == 'has_ch11' ){ $v='q_ch11'; }
        if( $field == 'has_ch112' ){ $v='q_ch112'; }
        if( $field == 'has_no_v' ){ $v='q_no_v'; }
        if( $field == 'has_pcu' ){ $v='q_pcu'; }
        if( $field == 'has_sub_ot' ){ $v='q_sub_ot'; }

        $d = array(
            $v  => $value                                                                
        ); 

        $result = DB::table( 'n_datageneral' )->where( 'cid', '=', $cid )->update( $d ); 
        //return  $field;
    }


    //===================== upload excel ====================//
    
    private function get_accid( $cid )
    {
        $datas = BankAcc::where('cid', '=', $cid)->get();

        foreach ($datas as $data)
        {
            return $data->acc_id;
        }
    }

    private function get_bankid( $cid )
    {
        $datas = BankAcc::where('cid', '=', $cid)->get();

        foreach ($datas as $data)
        {
            return $data->bank_id;
        }
    }

    public function upexcel()
    {
        return View::make( 'admin.upexcel' );
    }

    public function excel_upload()
    {   
        if( Input::file('file') == '' ){
            return View::make( 'admin.upexcel', array( 'status' => 'ไม่สามารถอัพข้อมูลได้' ) );
        }            
        $destinationPath = 'upload_excel';
        $filename = Input::file('file')->getClientOriginalName();               
        $uploadSuccess = Input::file('file')->move($destinationPath, $filename);

        /*$objPHPExcel = PHPExcel_IOFactory::load( "upload_excel/".$filename );
        foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) 
        {
            $worksheetTitle     = $worksheet->getTitle();
            $highestRow         = $worksheet->getHighestRow(); // e.g. 10
            $highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
            $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
            $nrColumns = ord($highestColumn) - 64;                        

            for ($row = 2; $row <= $highestRow; ++ $row) 
            {
               $val = array();
                for ($col = 0; $col < $highestColumnIndex; ++ $col) 
                {
                    $cell  = $worksheet->getCellByColumnAndRow($col, $row);
                    $val[] = $cell->getValue();                                                   
                } 

                $order_date  = ($val[1]-543).'-'.$val[2].'-28';
                $cid         = $val[3];
                $bank        = $this->get_bankid( $val[3] );
                $bank_acc_id = $this->get_accid( $val[3] );
                $bank_acc    = $val[13];
                $salary      = substr($val[14], 0, -2);
                $r_c         = substr($val[16], 0, -2);
                $special     = substr($val[24], 0, -2);
                $save_p      = substr($val[46], 0, -2);
                $kbk         = $val[48];
                $cas         = substr($val[58], 0, -2); 
            
                $Data = array(
                  'cid'         => $cid,
                  'bank'        => $bank,
                  'bank_acc_id' => $bank_acc_id,
                  'bank_acc'    => $bank_acc,
                  'salary'      => $salary,
                  'r_c'         => $r_c,
                  'special'     => $special,                                                                             
                  'kbk'         => $kbk,
                  'cas'         => $cas,
                  'save_p'      => $save_p,                                 
                  'order_date'  => $order_date,
                  'sys_user'    => Session::get('cid')
                );   

                 $result = DB::insert( 'insert into s_salary_ocsc_detail ( cid, bank, bank_acc_id, bank_acc, salary, r_c, special, kbk, cas, save_p, order_date, sys_user ) values ( ? , ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? )', 
                  array( 
                        $Data['cid'],
                        $Data['bank'],
                        $Data['bank_acc_id'],
                        $Data['bank_acc'],  
                        $Data['salary'],
                        $Data['r_c'],
                        $Data['special'],                                                              
                        $Data['kbk'],
                        $Data['cas'],
                        $Data['save_p'],                      
                        $Data['order_date'],
                        $Data['sys_user']
                  ) ); 
            }                   
        }*/
        return View::make( 'admin.upexcel', array( 'status' => 'อัพโหลดข้อมูลเรียบร้อย' ) );
    }






    //upexcel ข้าราชการ พี่นก
    public function upexcelnth()
    {
        return View::make( 'admin.upexcelnth' );
    }

    public function excelnth_upload()
    {
        if( Input::file('file') == '' ){
            return View::make( 'admin.upexcelnth', array( 'status' => 'ไม่สามารถอัพข้อมูลได้' ) );
        }            
        $destinationPath = 'upload_excel';
        $filename = Input::file('file')->getClientOriginalName();               
        $uploadSuccess = Input::file('file')->move($destinationPath, $filename);

        $objPHPExcel = PHPExcel_IOFactory::load( "upload_excel/".$filename );

        $isheet=0;
        $isheetrow=0;
        $datamsg = 'อัพโหลดข้อมูลเรียบร้อย';

        foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) 
        {
            $isheet++;

            $worksheetTitle     = $worksheet->getTitle();
            $highestRow         = $worksheet->getHighestRow(); // e.g. 10
            $highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
            $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
            $nrColumns = ord($highestColumn) - 64;   

            $datadate = explode("-", $worksheetTitle);
            $m = $datadate[0];
            $y = $datadate[1]-543;                    

            for ($row = 3; $row <= $highestRow; ++ $row) 
            {
               $isheetrow++;

               $val = array();
                for ($col = 0; $col < $highestColumnIndex; ++ $col) 
                {
                    $cell  = $worksheet->getCellByColumnAndRow($col, $row);
                    $val[] = $cell->getCalculatedValue();                                               
                } 

                $cid          = trim($val[3]);
                $salary       = (($val[4] == null )?0:$val[4]);
                $r_other1     = (($val[5] == null )?0:$val[5]);
                $r_c          = (($val[6] == null )?0:$val[6]);
                $special_m    = (($val[7] == null )?0:$val[7]);
                $r_other2     = (($val[8] == null )?0:$val[8]);
                $r_other3     = (($val[9] == null )?0:$val[9]);
                $tax          = (($val[10] == null )?0:$val[10]);
                $cas          = (($val[11] == null )?0:$val[11]);
                $kbk          = (($val[12] == null )?0:$val[12]);
                $save_p       = (($val[13] == null )?0:$val[13]);
                $fund_p       = (($val[14] == null )?0:$val[14]);
                $pg           = (($val[15] == null )?0:$val[15]);
                $r_other4     = (($val[16] == null )?0:$val[16]);      
            
                if( $cid != null || $cid != '' ){
                    $Data = array(
                      'cid'         => $cid,
                      'salary'      => $salary,
                      'r_c'         => $r_c,
                      'r_other'     => ($r_other1+$r_other2+$r_other3),
                      'kbk'         => $kbk,
                      'tax'         => $tax,
                      'cas'         => $cas,
                      'save_p'      => $save_p,
                      'fund_p'      => $fund_p,
                      'order_date'  => $y.'-'.$m.'-25',
                      'special_m'   => $special_m,
                      'sys_user'    => Session::get('cid')
                    );  

                    $p = DB::table('n_position_salary')
                            ->where('cid', '=' , $cid)
                            ->select('level')
                            ->orderBy('salaryID', 'desc')
                            ->limit(1)
                            ->first();

                    if( $p ){
                    
                        //check data in table s_salary_xx
                        $check_salary = DB::table('s_salary_ocsc')->where('cid', '=', $cid)->count();

                        if( $check_salary > 0 ){
                            //มีข้อมูลใน s_salary แล้ว
                            $this->insert_to__salary_detail($Data, $y, $m);
                        }else{
                            //ไม่มีข้อมูลใน s_salary ให้เพิ่มก่อน
                            $datamsg = $this->insert_to_salary($Data);
                        }
                    }
                }//end if chechk salary empty
                
                 
            }//end for           
        }//end foreach  


        return View::make( 'admin.upexcelnth', array( 'status' => $datamsg ) );

    }

    public function insert_to__salary_detail($Data, $y, $m)
    {
        $chk_data = DB::table('s_salary_ocsc_detail')
                    ->where('cid', '=', $Data['cid'])
                    ->where(DB::raw('year(order_date)'), $y)
                    ->where(DB::raw('month(order_date)'), $m)
                    ->count();

        if ( $chk_data == 0 ) {
            #insert to s_salary_ocsc

            $datasalary = DB::table('s_salary_ocsc')->where('cid', '=', $Data['cid'])->first();

            $result = DB::insert( 'insert into s_salary_ocsc_detail ( cid, bank, bank_acc_id, bank_acc, salary, r_c, special_m, tax, r_other, kbk, cas, save_p, fund_p, order_date, sys_user ) values ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', 
                      array( 
                            $Data['cid'],
                            $datasalary->bank,
                            $datasalary->bank_acc_id,
                            $datasalary->bank_acc,  
                            $Data['salary'],
                            $Data['r_c'],
                            $Data['special_m'],
                            $Data['tax'],   
                            $Data['r_other'],
                            $Data['kbk'],
                            $Data['cas'],
                            $Data['save_p'],
                            $Data['fund_p'],
                            $Data['order_date'],
                            $Data['sys_user'],
                      ) ); 

        }else{
            #update to s_salary_ocsc

            $result = DB::table('s_salary_ocsc_detail')
                        ->where('cid', '=', $Data['cid'])
                        ->where(DB::raw('year(order_date)'), $y)
                        ->where(DB::raw('month(order_date)'), $m)
                        ->update( $Data );    

        }
    }







    //upexcel พนักงานประจำ พี่นก
    public function upexcelnth2()
    {
        return View::make( 'admin.upexcelnth2' );
    }

    public function excelnth2_upload()
    {
        if( Input::file('file') == '' ){
            return View::make( 'admin.upexcelnth2', array( 'status' => 'ไม่สามารถอัพข้อมูลได้' ) );
        }            
        $destinationPath = 'upload_excel';
        $filename = Input::file('file')->getClientOriginalName();               
        $uploadSuccess = Input::file('file')->move($destinationPath, $filename);

        $objPHPExcel = PHPExcel_IOFactory::load( "upload_excel/".$filename );

        $isheet=0;
        $isheetrow=0;
        $datamsg = 'อัพโหลดข้อมูลเรียบร้อย';

        foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) 
        {
            $isheet++;

            $worksheetTitle     = $worksheet->getTitle();
            $highestRow         = $worksheet->getHighestRow(); // e.g. 10
            $highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
            $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
            $nrColumns = ord($highestColumn) - 64;   

            $datadate = explode("-", $worksheetTitle);
            $m = $datadate[0];
            $y = $datadate[1]-543;                    

            for ($row = 3; $row <= $highestRow; ++ $row) 
            {
               $isheetrow++;

               $val = array();
                for ($col = 0; $col < $highestColumnIndex; ++ $col) 
                {
                    $cell  = $worksheet->getCellByColumnAndRow($col, $row);
                    $val[] = $cell->getCalculatedValue();                                               
                } 

                $cid          = trim($val[3]);
                $salary       = (($val[4] == null )?0:$val[4]);
                $r_other1     = (($val[5] == null )?0:$val[5]);
                $r_other2     = (($val[6] == null )?0:$val[6]);
                $tax          = (($val[7] == null )?0:$val[7]);
                $cas          = (($val[8] == null )?0:$val[8]);
                $kbk          = (($val[9] == null )?0:$val[9]);
                $save_p       = (($val[10] == null )?0:$val[10]);
                $r_other4     = (($val[11] == null )?0:$val[11]);      
            
                if( $cid != null || $cid != '' ){
                    $Data = array(
                      'cid'         => $cid,
                      'salary'      => $salary,
                      'r_other'     => ($r_other1+$r_other2),
                      'kbk'         => $kbk,
                      'tax'         => $tax,
                      'cas'         => $cas,
                      'save_p'      => $save_p,
                      'order_date'  => $y.'-'.$m.'-25',
                      'sys_user'    => Session::get('cid')
                    );  

                    $p = DB::table('n_position_salary')
                            ->where('cid', '=' , $cid)
                            ->select('level')
                            ->orderBy('salaryID', 'desc')
                            ->limit(1)
                            ->first();

                    if( $p ){
                    
                        //check data in table s_salary_xx
                        $check_salary = DB::table('s_salary_ocsc')->where('cid', '=', $cid)->count();

                        if( $check_salary > 0 ){
                            //มีข้อมูลใน s_salary แล้ว
                            $this->insert_to__salary_detail2($Data, $y, $m);
                        }else{
                            //ไม่มีข้อมูลใน s_salary ให้เพิ่มก่อน
                            $datamsg = $this->insert_to_salary($Data);
                        }
                    }
                }//end if chechk salary empty
                
                 
            }//end for           
        }//end foreach  


        return View::make( 'admin.upexcelnth2', array( 'status' => $datamsg ) );
    }

    public function insert_to__salary_detail2($Data, $y, $m)
    {
        $chk_data = DB::table('s_salary_ocsc_detail')
                    ->where('cid', '=', $Data['cid'])
                    ->where(DB::raw('year(order_date)'), $y)
                    ->where(DB::raw('month(order_date)'), $m)
                    ->count();

        if ( $chk_data == 0 ) {
            #insert to s_salary_ocsc

            $datasalary = DB::table('s_salary_ocsc')->where('cid', '=', $Data['cid'])->first();

            $result = DB::insert( 'insert into s_salary_ocsc_detail ( cid, bank, bank_acc_id, bank_acc, salary, r_other, kbk, tax, cas, save_p, order_date, sys_user ) values ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', 
                      array( 
                            $Data['cid'],
                            $datasalary->bank,
                            $datasalary->bank_acc_id,
                            $datasalary->bank_acc,  
                            $Data['salary'],
                            $Data['r_other'],
                            $Data['kbk'],
                            $Data['tax'],
                            $Data['cas'],
                            $Data['save_p'],
                            $Data['order_date'],
                            $Data['sys_user'],
                      ) ); 

        }else{
            #update to s_salary_ocsc

            $result = DB::table('s_salary_ocsc_detail')
                        ->where('cid', '=', $Data['cid'])
                        ->where(DB::raw('year(order_date)'), $y)
                        ->where(DB::raw('month(order_date)'), $m)
                        ->update( $Data );    

        }
    }




    //upexcel พนักงานราชการ พี่นก
    public function upexcelnth3()
    {
        return View::make( 'admin.upexcelnth3' );
    }

    public function excelnth3_upload()
    {
        if( Input::file('file') == '' ){
            return View::make( 'admin.upexcelnth3', array( 'status' => 'ไม่สามารถอัพข้อมูลได้' ) );
        }            
        $destinationPath = 'upload_excel';
        $filename = Input::file('file')->getClientOriginalName();               
        $uploadSuccess = Input::file('file')->move($destinationPath, $filename);

        $objPHPExcel = PHPExcel_IOFactory::load( "upload_excel/".$filename );

        $isheet=0;
        $isheetrow=0;
        $datamsg = 'อัพโหลดข้อมูลเรียบร้อย';

        foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) 
        {
            $isheet++;

            $worksheetTitle     = $worksheet->getTitle();
            $highestRow         = $worksheet->getHighestRow(); // e.g. 10
            $highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
            $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
            $nrColumns = ord($highestColumn) - 64;   

            $datadate = explode("-", $worksheetTitle);
            $m = $datadate[0];
            $y = $datadate[1]-543;                    

            for ($row = 3; $row <= $highestRow; ++ $row) 
            {
               $isheetrow++;

               $val = array();
                for ($col = 0; $col < $highestColumnIndex; ++ $col) 
                {
                    $cell  = $worksheet->getCellByColumnAndRow($col, $row);
                    $val[] = $cell->getCalculatedValue();                                               
                } 

                $cid          = trim($val[3]);
                $salary       = (($val[4] == null )?0:$val[4]);
                $r_other1     = (($val[5] == null )?0:$val[5]);
                $cas          = (($val[6] == null )?0:$val[6]);     
            
                if( $cid != null || $cid != '' ){
                    $Data = array(
                      'cid'         => $cid,
                      'salary'      => $salary,
                      'r_other'     => $r_other1,
                      'cas'         => $cas,
                      'order_date'  => $y.'-'.$m.'-25',
                      'sys_user'    => Session::get('cid')
                    );  

                    $p = DB::table('n_position_salary')
                            ->where('cid', '=' , $cid)
                            ->select('level')
                            ->orderBy('salaryID', 'desc')
                            ->limit(1)
                            ->first();

                    if( $p ){
                    
                        //check data in table s_salary_xx
                        $check_salary = DB::table('s_salary_ocsc')->where('cid', '=', $cid)->count();

                        if( $check_salary > 0 ){
                            //มีข้อมูลใน s_salary แล้ว
                            $this->insert_to__salary_detail3($Data, $y, $m);
                        }else{
                            //ไม่มีข้อมูลใน s_salary ให้เพิ่มก่อน
                            $datamsg = $this->insert_to_salary($Data);
                        }
                    }
                }//end if chechk salary empty
                
                 
            }//end for           
        }//end foreach  


        return View::make( 'admin.upexcelnth3', array( 'status' => $datamsg ) );
    }

    public function insert_to__salary_detail3($Data, $y, $m)
    {
        $chk_data = DB::table('s_salary_ocsc_detail')
                    ->where('cid', '=', $Data['cid'])
                    ->where(DB::raw('year(order_date)'), $y)
                    ->where(DB::raw('month(order_date)'), $m)
                    ->count();

        if ( $chk_data == 0 ) {
            #insert to s_salary_ocsc

            $datasalary = DB::table('s_salary_ocsc')->where('cid', '=', $Data['cid'])->first();

            $result = DB::insert( 'insert into s_salary_ocsc_detail ( cid, bank, bank_acc_id, bank_acc, salary, r_other, cas, order_date, sys_user ) values ( ?, ?, ?, ?, ?, ?, ?, ?, ?)', 
                      array( 
                            $Data['cid'],
                            $datasalary->bank,
                            $datasalary->bank_acc_id,
                            $datasalary->bank_acc,  
                            $Data['salary'],
                            $Data['r_other'],
                            $Data['cas'],
                            $Data['order_date'],
                            $Data['sys_user'],
                      ) ); 

        }else{
            #update to s_salary_ocsc

            $result = DB::table('s_salary_ocsc_detail')
                        ->where('cid', '=', $Data['cid'])
                        ->where(DB::raw('year(order_date)'), $y)
                        ->where(DB::raw('month(order_date)'), $m)
                        ->update( $Data );    

        }
    }





    //upexcel อัพไฟล์ข้อมูลรายได้ พตส. พี่นก
    public function upexcelnth4()
    {
        $y = DB::Select( ' select year(order_date) as year1 from s_salary_detail group by year(order_date) order by year(order_date) desc ' );
        return View::make( 'admin.upexcelnth4', array('y' => $y) );
    }

    public function excelnth4_upload()
    {
        $y = DB::Select( ' select year(order_date) as year1 from s_salary_detail group by year(order_date) order by year(order_date) desc ' );

        if( Input::file('file') == '' || Input::get('pts_y') == '0' ){
            return View::make( 'admin.upexcelnth4', array( 'status' => 'ไม่สามารถอัพข้อมูลได้', 'y' => $y ) );
        }      
        $destinationPath = 'upload_excel';
        $filename = Input::file('file')->getClientOriginalName();               
        $uploadSuccess = Input::file('file')->move($destinationPath, $filename);

        $objPHPExcel = PHPExcel_IOFactory::load( "upload_excel/".$filename );

        $isheet=0;
        $isheetrow=0;
        $datamsg = 'อัพโหลดข้อมูลเรียบร้อย';

        foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) 
        {
            $isheet++;

            $worksheetTitle     = $worksheet->getTitle();
            $highestRow         = $worksheet->getHighestRow(); // e.g. 10
            $highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
            $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
            $nrColumns = ord($highestColumn) - 64;                      

            for ($row = 6; $row <= $highestRow; ++ $row) 
            {
               $isheetrow++;

               $val = array();
                for ($col = 0; $col < $highestColumnIndex; ++ $col) 
                {
                    $cell  = $worksheet->getCellByColumnAndRow($col, $row);
                    $val[] = $cell->getCalculatedValue();                                               
                } 

                $cid     = trim($val[0]);
                $pts1    = (($val[5] == null )?0:$val[5]);
                $pts2    = (($val[11] == null )?0:$val[11]);
                $pts3    = (($val[17] == null )?0:$val[17]);
                $pts4    = (($val[23] == null )?0:$val[23]);
                $pts5    = (($val[29] == null )?0:$val[29]);
                $pts6    = (($val[35] == null )?0:$val[35]);
                $pts7    = (($val[41] == null )?0:$val[41]);
                
                if( isset($val[47]) ){
                    $pts8 = (($val[47] == null )?0:$val[47]);
                }else{
                    $pts8 = 0;
                }
                    
                if( isset($val[53]) ){
                    $pts9 = (($val[53] == null )?0:$val[53]);
                }else{
                    $pts9 = 0;
                }

                if( isset($val[59]) ){
                    $pts10 = (($val[59] == null )?0:$val[59]);
                }else{
                    $pts10 = 0;
                }
                
                if( isset($val[65]) ){
                    $pts11   = (($val[65] == null )?0:$val[65]);
                }else{
                    $pts11 = 0;
                }
                
                if( isset($val[71]) ){
                    $pts12   = (($val[71] == null )?0:$val[71]);
                }else{
                    $pts12 = 0;
                }
                


                if( $cid != null || $cid != '' ){

                    $p = DB::table('n_position_salary')
                            ->where('cid', '=' , $cid)
                            ->select('level')
                            ->orderBy('salaryID', 'desc')
                            ->limit(1)
                            ->first();

                    if( $p ){
                    
                        //check data in table s_salary_xx
                        $check_salary = DB::table('s_salary_ocsc')->where('cid', '=', $cid)->count();

                        if( $check_salary > 0 ){
                            //มีข้อมูลใน s_salary แล้ว
                            $this->insert_to__salary_detail4($cid, array('pts2' => $pts1, 'sys_user' => Session::get('cid')), Input::get('pts_y'), 1);
                            $this->insert_to__salary_detail4($cid, array('pts2' => $pts2, 'sys_user' => Session::get('cid')), Input::get('pts_y'), 2);
                            $this->insert_to__salary_detail4($cid, array('pts2' => $pts3, 'sys_user' => Session::get('cid')), Input::get('pts_y'), 3);
                            $this->insert_to__salary_detail4($cid, array('pts2' => $pts4, 'sys_user' => Session::get('cid')), Input::get('pts_y'), 4);
                            $this->insert_to__salary_detail4($cid, array('pts2' => $pts5, 'sys_user' => Session::get('cid')), Input::get('pts_y'), 5);
                            $this->insert_to__salary_detail4($cid, array('pts2' => $pts6, 'sys_user' => Session::get('cid')), Input::get('pts_y'), 6);
                            $this->insert_to__salary_detail4($cid, array('pts2' => $pts7, 'sys_user' => Session::get('cid')), Input::get('pts_y'), 7);
                            $this->insert_to__salary_detail4($cid, array('pts2' => $pts8, 'sys_user' => Session::get('cid')), Input::get('pts_y'), 8);
                            $this->insert_to__salary_detail4($cid, array('pts2' => $pts9, 'sys_user' => Session::get('cid')), Input::get('pts_y'), 9);
                            $this->insert_to__salary_detail4($cid, array('pts2' => $pts10, 'sys_user' => Session::get('cid')), Input::get('pts_y'), 10);
                            $this->insert_to__salary_detail4($cid, array('pts2' => $pts11, 'sys_user' => Session::get('cid')), Input::get('pts_y'), 11);
                            $this->insert_to__salary_detail4($cid, array('pts2' => $pts12, 'sys_user' => Session::get('cid')), Input::get('pts_y'), 12);
                        }else{
                            //ไม่มีข้อมูลใน s_salary ให้เพิ่มก่อน
                            //$datamsg = $this->insert_to_salary(array('cid' => $cid));
                        }
                    }
                }//end if chechk salary empty
                
                 
            }//end for           
        }//end foreach  


        return View::make( 'admin.upexcelnth4', array( 'status' => $datamsg, 'y' => $y ) );
    }

    public function insert_to__salary_detail4($cid, $Data, $y, $m)
    { 
        #update to s_salary_ocsc
        $result = DB::table('s_salary_ocsc_detail')
                    ->where('cid', '=', $cid)
                    ->where(DB::raw('year(order_date)'), $y)
                    ->where(DB::raw('month(order_date)'), $m)
                    ->update( $Data );    

    }








    //upexcel - ค่าเดินทาง
    public function upexceltravel()
    {
        return View::make( 'admin.upexceltravel' );
    }

    public function exceltravel_upload()
    {
        if( Input::file('file') == '' ){
            return View::make( 'admin.upexceltravel', array( 'status' => 'ไม่สามารถอัพข้อมูลได้' ) );
        }            
        $destinationPath = 'upload_excel';
        $filename = Input::file('file')->getClientOriginalName();               
        $uploadSuccess = Input::file('file')->move($destinationPath, $filename);

        $objPHPExcel = PHPExcel_IOFactory::load( "upload_excel/".$filename );

        $isheet=0;
        $isheetrow=0;

        foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) 
        {
            $isheet++;

            $worksheetTitle     = $worksheet->getTitle();
            $highestRow         = $worksheet->getHighestRow(); // e.g. 10
            $highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
            $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
            $nrColumns = ord($highestColumn) - 64;                       

            for ($row = 0; $row <= $highestRow; ++ $row) 
            {
               $isheetrow++;

               $val = array();
                for ($col = 0; $col < $highestColumnIndex; ++ $col) 
                {
                    $cell  = $worksheet->getCellByColumnAndRow($col, $row);
                    $val[] = $cell->getValue();                                               
                } 

                //$dateObj = PHPExcel_Shared_Date::ExcelToPHPObject($val[3]);
               // $datetravel         = (($dateObj->format('Y'))-543).'-'.($dateObj->format('m-d'));
                $cid                = $val[0];    
                $money              = $val[1];
                $datetravel         = PHPExcel_Shared_Date::ExcelToPHPObject($val[2]);
            
                $Data = array(
                  'cid'           => $cid,
                  'money'         => $money,
                  'datetravel'    => $datetravel
                );  

                if( $Data['cid'] != null || $Data['cid'] != '' ){

                    $result = DB::insert( 'insert into s_travel_temp ( cid, money, datetravel ) values ( ? , ?, ? )', 
                      array(
                        $Data['cid'],
                        $Data['money'],
                        $Data['datetravel']
                     )
                    ); 
                  
                }//if check cid null
            }           
        }//end foreach

        $dataall = DB::table('s_travel_temp')
                        ->select('cid', DB::raw('month(datetravel) as travel_m'), DB::raw('year(datetravel) as travel_y'), DB::raw('sum(money) as total_money') )
                        ->groupby('cid')
                        ->groupby(DB::raw('month(datetravel)'))
                        ->orderby(DB::raw('year(datetravel)'), 'asc')
                        ->orderby(DB::raw('month(datetravel)'), 'asc')
                        ->get();

        foreach ($dataall as $key => $value) {

            $Data_update = array(
                  'u_travel'  => $value->total_money
                ); 

            $p = DB::table('n_position_salary')
                            ->where('cid', '=' , $value->cid)
                            ->select('level')
                            ->orderBy('salaryID', 'desc')
                            ->limit(1)
                            ->first();

            $this->makeSalaryOneMonth($value->cid, $value->travel_m, $value->travel_y, $value->total_money, $p->level);          
          
        }// end foreach update u_travel 2 table
        
        //clear table s_travel_temp        
        DB::table('s_travel_temp')->truncate();

        return View::make( 'admin.upexceltravel', array( 'status' => 'อัพโหลดข้อมูลเรียบร้อย' ) );

    }





     public function insert_to_salary($Data)
    {
        return 'CID '. $Data['cid'] .' นี้ไม่มีข้อมูลบัญชีในระบบ โปรดเพิ่มข้อมูลบัญชีในระบบก่อนทำการอัพโหลดข้อมูล';
    }




     
    /**
     * 
     * เพิ่มข้อมูลเงินเดือน Auto Make 1 เดือน
     * 
     */
    public function makeSalaryOneMonth($cid, $month, $year, $total_money, $level)
    {
        if($level == 'ข้าราชการ' || $level == 'ลูกจ้างประจำ'){
            $ck= DB::table('s_salary_ocsc_detail')->where('cid', $cid)->where(DB::raw('month(order_date)'), $month)->where(DB::raw('year(order_date)'), $year)->count();

            if($ck == 0){
                //insert
                $datasalary = DB::table('s_salary_ocsc')->where('cid', '=', $cid)->first();
                
                DB::insert( 'insert into s_salary_ocsc_detail ( cid, order_date, sys_user, u_travel ) values (?, ?, ?, ?)', 
                array( 
                    $cid,
                    $year.'-'.$month.'-01',
                    Session::get('cid'),
                    $total_money
                )); 

                return true;
            }else{
                //update
                $u_travel = array(
                    'u_travel' 	 => $total_money   		            	                       
                );
                
                DB::table('s_salary_ocsc_detail')
                ->where('cid', $cid)
                ->where(DB::raw('year(order_date)'), $year)
                ->where(DB::raw('month(order_date)'), $month)
                ->update( $u_travel );  
            
                return true;
            }
        }else{
            $ck = DB::table('s_salary_detail')->where('cid', $cid)->where(DB::raw('month(order_date)'), $month)->where(DB::raw('year(order_date)'), $year)->count();

            if($ck == 0){
                //insert
                DB::insert( 'insert into s_salary_detail ( cid, order_date, sys_user, u_travel ) values (?, ?, ?, ?)', 
                array( 
                    $cid,
                    $year.'-'.$month.'-01',
                    Session::get('cid'),
                    $total_money
                )); 
            }else{
                //update
                $u_travel = array(
                    'u_travel' 	 => $total_money   		            	                       
                );
                
                DB::table('s_salary_detail')
                ->where('cid', $cid)
                ->where(DB::raw('year(order_date)'), $year)
                ->where(DB::raw('month(order_date)'), $month)
                ->update( $u_travel ); 
            }
        }
           
        return false;
    }




}
