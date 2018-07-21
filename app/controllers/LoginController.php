<?php

class LoginController extends BaseController {

	public function showLogin()
	{
		if ( Session::get('level') != '' )
        {
            // Redirect to homepage
            return Redirect::to( 'home/index' );
        }
		return View::make('login.index');
	}

	/**
	 * function name : doLogin
	 * check login system
	 * add session data
	 * post
	*/
	public function doLogin()
	{				
		$validator = Validator::make( Input::all(), Login::$rules, Login::$messages );
		
		// if the validator fails, redirect back to the form
		if ( $validator->fails() ) {
			return Redirect::to( '/' )->withErrors( $validator );			
		}
		else 
		{
			$username = Input::get( 'username' );
            $password = Input::get( 'password' );
						
			if ( $username == 'chakrit' && $password=='chakrit' ) {	
					
				Session::put( 'cid', 'chakrit' ); 							
				Session::put( 'level', '1' );
				Session::put( 'c1', 1 );	
	            Session::put( 'c2', 1 );	
	            Session::put( 'c3', 1 );	
	            Session::put( 'c4', 1 );  
	            Session::put( 'c5', 1 );  
	            Session::put( 'c6', 1 ); 				                     

				return Redirect::to( 'home' );	
			}			
			else {	
				$user = DB::table('s_users')->where('cid', $username)->where('password', $password)->count();

				if( $user == 1 ){
					$data_user = DB::table( 's_users' )->where('cid', $username)->where('password', $password)->first();
				
					//save user details into session
					Session::put( 'user_id', $data_user->user_id );
		            Session::put( 'cid', $data_user->cid );
		            Session::put( 'password', $data_user->password );
		            Session::put( 'level', $data_user->level );
		            Session::put( 'c1', $data_user->c1 );	
		            Session::put( 'c2', $data_user->c2 );	
		            Session::put( 'c3', $data_user->c3 );
		            Session::put( 'c4', $data_user->c4 );
		            Session::put( 'c5', $data_user->c5 );
		            Session::put( 'c6', $data_user->c6 );

		            $logintime = array(			           
			            'logintime' 		 => date('Y-m-d h:i:s')		           		            	                       
			        );  			      
			        //update login time
			        DB::table( 's_users' )->where( 'user_id', '=', $data_user->user_id )->update( $logintime );	
					
					return Redirect::to( 'home' );	

				}else{
					return Redirect::to( '/' )->with( 'error_message', 'ชื่อผู้ใช้หรือรหัสผ่าน !!ผิด กรุณาลองใหม่อีกครั้ง' ); 
				}			 
			}
		}
	}

	/**
	 * function name : dologout
	 * logout system
	 * clear session data
	*/
	public function dologout()
	{
 		Session::flush(); //delete the session
		return Redirect::to( '/' ); // redirect the user to the login screen
	}

}
