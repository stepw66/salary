<?php

class TaxController extends BaseController {

	private function max_year()
	{
		return date("Y", strtotime( DB::table('s_salary_detail')->max('order_date') ));
	}

	private function yearThai()
	{
		$thaiweek=array("วันอาทิตย์","วันจันทร์","วันอังคาร","วันพุธ","วันพฤหัส","วันศุกร์","วันเสาร์");

     	$thaimonth=array("มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","      มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");

     	//echo $thaiweek[date("w")] ,"ที่",date(" j "), $thaimonth[date(" m ")-1] , " พ.ศ. ",date(" Y ")+543;
     	// ผลลัพธ์จะได้ดังนี้ครับ วันเสาร์ที่ 26 กันยายน พ.ศ. 2552
     	return ( date(" Y ")+543 );
	}

	/*
	* function name tax_recomend_type1
	* รายละเอียดเงินเดือน พิมพ์หนังสือรับรอง
	*
	*/
	public function tax_recomend_type1()
	{	
		if( Session::get('level') != '' )
		{	
			$data = DB::table( 's_salary_detail' )	  
				->leftjoin('n_datageneral', 'n_datageneral.cid', '=', 's_salary_detail.cid')				
				//->where( 'n_datageneral.status', '=', '0' )	
				->groupBY( 's_salary_detail.cid' )	
				->orderBY( 'n_datageneral.datainfoID','asc' )	
				->select( 'n_datageneral.*' )
		        ->paginate( 20 );		        

			return View::make( 'tax.recomend_type1', array( 'data' => $data ) );
		}
		else
		{
			return View::make('login.index');
		}	
	}

	/**
    * function name : search_type1
    * search data tax_recomend_type1
    * post
    */
    public function search_type1()
    {   		  
	   if ( Session::get('level') != '' )
		{
			$search  = Input::get( 'search_tax1' );	    		    

			$data = DB::table( 's_salary_detail' )	  
				->leftjoin('n_datageneral', 'n_datageneral.cid', '=', 's_salary_detail.cid')				
				//->where( 'n_datageneral.status', '=', '0' )	
				->where( 'n_datageneral.cid', 'like', "%$search%" )
	         	->orWhere( 'n_datageneral.fname', 'like', "%$search%" )	 
	          	->orWhere( 'n_datageneral.lname', 'like', "%$search%" )	
				->groupBY( 's_salary_detail.cid' )	
				->orderBY( 'n_datageneral.datainfoID','asc' )	
				->select( 'n_datageneral.*' )
		        ->paginate( 70 );	 		    
	     
			//view page create
		    return View::make( 'tax.recomend_type1',  array( 'data' => $data ) );	
		}	
		else
		{
			//return login
    		return View::make( 'login.index' );	
		}	
    }

    /**
    * function name : fromstax_type1
    * data to from tax model
    * post
    */
    public function fromstax_type1( $id=null, $year=null )
    {
    	
    	if( $year == 'null' )
    	{        			
    		$data = DB::table( 's_salary_detail' ) 
				->leftjoin('n_datageneral', 'n_datageneral.cid', '=', 's_salary_detail.cid')				
				//->where( 'n_datageneral.status', '=', '0' )	
				->where( 'n_datageneral.cid', '=', $id )					
				->where( DB::raw('year(s_salary_detail.order_date)'), '=', $this->max_year() )							
				->orderBY( 's_salary_detail.order_date','desc' )	
				->select( 'n_datageneral.cid', 'n_datageneral.pname', 'n_datageneral.fname', 'n_datageneral.lname', 's_salary_detail.*', DB::raw('month(s_salary_detail.order_date) as ordermonth') )
		        ->get();	

		    $sumdata = DB::table( 's_salary_detail' ) 
				->leftjoin('n_datageneral', 'n_datageneral.cid', '=', 's_salary_detail.cid')				
				//->where( 'n_datageneral.status', '=', '0' )	
				->where( 'n_datageneral.cid', '=', $id )	
				->where( DB::raw('year(s_salary_detail.order_date)'), '=', $this->max_year() )													
				->select( 's_salary_detail.cid', db::raw('sum( s_salary_detail.salary ) as salary_sum'), db::raw('sum( s_salary_detail.salary_other ) as salary_other_sum'), db::raw('sum( s_salary_detail.salary_sso ) as salary_sso_sum'), db::raw('sum( s_salary_detail.special ) as salary_special_sum'), db::raw('sum( s_salary_detail.tax ) as salary_tax_sum'), db::raw('sum( s_salary_detail.pts ) as salary_pts_sum'), db::raw('sum( s_salary_detail.ot ) as salary_ot_sum') )
		        ->first();	

		    $year = DB::select( ' select (year(order_date)+543) as year from s_salary_detail group by  year(order_date) ' );    

		    return View::make( 'tax.fromtax1',  array( 'data' => $data, 'sumdata' => $sumdata, 'year' => $year, 'id' => $id ) );	
		}
		else
		{
			$data = DB::table( 's_salary_detail' ) 
				->leftjoin('n_datageneral', 'n_datageneral.cid', '=', 's_salary_detail.cid')				
				//->where( 'n_datageneral.status', '=', '0' )	
				->where( 'n_datageneral.cid', '=', $id )					
				->where( DB::raw('year(s_salary_detail.order_date)'), '=', $year )							
				->orderBY( 's_salary_detail.order_date','desc' )	
				->select( 'n_datageneral.cid', 'n_datageneral.pname', 'n_datageneral.fname', 'n_datageneral.lname', 's_salary_detail.*', DB::raw('month(s_salary_detail.order_date) as ordermonth') )
		        ->get();	

		    $sumdata = DB::table( 's_salary_detail' ) 
				->leftjoin('n_datageneral', 'n_datageneral.cid', '=', 's_salary_detail.cid')				
				//->where( 'n_datageneral.status', '=', '0' )	
				->where( 'n_datageneral.cid', '=', $id )	
				->where( DB::raw('year(s_salary_detail.order_date)'), '=',$year )													
				->select( 's_salary_detail.cid', db::raw('sum( s_salary_detail.salary ) as salary_sum'), db::raw('sum( s_salary_detail.salary_other ) as salary_other_sum'), db::raw('sum( s_salary_detail.salary_sso ) as salary_sso_sum'), db::raw('sum( s_salary_detail.special ) as salary_special_sum'), db::raw('sum( s_salary_detail.tax ) as salary_tax_sum'), db::raw('sum( s_salary_detail.pts ) as salary_pts_sum'), db::raw('sum( s_salary_detail.ot ) as salary_ot_sum') )
		        ->first();	

		    $year = DB::select( ' select (year(order_date)+543) as year from s_salary_detail group by  year(order_date) ' );    

		    return View::make( 'tax.fromtax1',  array( 'data' => $data, 'sumdata' => $sumdata, 'year' => $year, 'id' => $id ) );	
		} 	   	
    }

    /**
    * function name : updatetax_type1
    * update data to s_salary_detail
    * post
    */
    public function updatetax_type1( $id=null, $date=null, $tax=null, $special=null, $pts=null, $ot=null )
    {    		   	   
        $user_data = array(
            'tax' 	 	=> $tax,
            'special' 	=> $special,
            'pts'		=> $pts,
            'ot'		=> $ot                      		            	                       
        );        
      
        //update user details
        $result = DB::table( 's_salary_detail' )->where( 'cid', '=', $id )->where( 'order_date', '=', $date )->update( $user_data );	        
        if( $result )
        {
        	return 'บันทึกเรียบร้อย'; 
        }
        else
        {
        	return 'ไม่สามารถบันทึกได้'; 
        }
    }



    /*
	* function name continuous_home1
	* หนังสือรับรอง  Home
	*
	*/
	public function continuous_home1()
	{		
		if( Session::get('level') != '' )
		{
			$y = DB::Select( ' select year(order_date) as year1 from s_salary_detail group by year(order_date) order by year(order_date) desc ' );	
			return View::make( 'tax.continuous_home1', array( 'data' => $y ) );
		}
		else
		{
			return View::make('login.index');
		} 
	}

	/*
	* function name tax_continuous_type1
	* พิมพ์หนังสือรับรอง
	*
	*/
	public function tax_continuous_type1( $id=null, $year=null )
	{		
		if( Session::get('level') != '' )
		{	
			$y = Input::get('y1');
			if($y != ''){
				$year = $y;
				$id = 'all';
			}		

		    $pdf = new TCPDF();
		    $pdf->SetPrintHeader(false);
		    $pdf->SetPrintFooter(false);			   		   

		    $n = DB::select( 'select * from s_general_data' );	
		    foreach ($n as $k) {
		      	$name 		= $k->name;
		      	$address 	= $k->address;
		      	$tax_id 	= $k->tax_id;
		      	$director 	= $k->director;
		    } 

		    if( $id != 'null' && $year != 'null' )
		    {
				$sql = ' select concat(n.pname,"",n.fname," ",n.lname) as name, s.cid, s.tax_id,sum(s.salary+s.salary_other) as salary, sum(s.salary_sso) as salary_sso, sum(s.ch11+s.special+s.pts+s.ot+s.ch8+s.no_v+s.outpcu-s.sub_ot) as special, sum(s.tax) as tax from s_salary_detail s left join n_datageneral n on n.cid=s.cid where  year(s.order_date)='.$year.' and s.cid='.$id.' group by s.cid order by n.datainfoID asc ';		    	    
		    }
		    if( $id != 'null' && $year == 'null' )
		    {		    	
		    	$sql = ' select concat(n.pname,"",n.fname," ",n.lname) as name, s.cid, s.tax_id,sum(s.salary+s.salary_other) as salary, sum(s.salary_sso) as salary_sso, sum(s.ch11+s.special+s.pts+s.ot+s.ch8+s.no_v+s.outpcu-s.sub_ot) as special, sum(s.tax) as tax from s_salary_detail s left join n_datageneral n on n.cid=s.cid where  year(s.order_date)='.$this->max_year().' and s.cid='.$id.' group by s.cid order by n.datainfoID asc ';
		    }
		    if( $id == 'null' && $year == 'null' )
		    {
		    	$sql = ' select concat(n.pname,"",n.fname," ",n.lname) as name, s.cid, s.tax_id,sum(s.salary+s.salary_other) as salary, sum(s.salary_sso) as salary_sso, sum(s.ch11+s.special+s.pts+s.ot+s.ch8+s.no_v+s.outpcu-s.sub_ot) as special, sum(s.tax) as tax from s_salary_detail s left join n_datageneral n on n.cid=s.cid where  year(s.order_date)='.$this->max_year().' group by s.cid order by n.datainfoID asc ';		    		    
		    }
		    if( $id == 'all' && $year != '' )
		    {
				$sql = ' select * from (select concat(n.pname,"",n.fname," ",n.lname) as name, s.cid, s.tax_id,sum(s.salary+s.salary_other) as salary, sum(s.salary_sso) as salary_sso, sum(s.ch11+s.special+s.pts+s.ot+s.ch8+s.no_v+s.outpcu-s.sub_ot) as special, sum(s.tax) as tax from s_salary_detail s left join n_datageneral n on n.cid=s.cid where  year(s.order_date)='.$year.' group by s.cid order by n.datainfoID asc) as a where a.salary > 0 ';		    	    
		    }
		    
		    $result = DB::select( $sql );		    

		    foreach ( $result as $key ) {

		    	$pdf->AddPage('P', 'A4');

		    	$pdf->SetFont('freeserif','B',11,'',true);	
		    	$pdf->SetX(18);	    
	 			$pdf->MultiCell(177, 5, 'เลขที่ งบ. ..................../'.( ($year == 'null') ? $this->yearThai() : ($year+543) ), 0, 'R', 0, 1, '', '', true);

	 			$pdf->SetFont('freeserif','B',16,'',true);
		    	$pdf->SetY(25);
	 			$pdf->SetX(18);
	 			$pdf->MultiCell(177, 5, 'หนังสือรับรองการหักภาษี ณ ที่จ่าย', 0, 'C', 0, 1, '', '', true);

	 			$pdf->SetY(34);
	 			$pdf->SetX(18);
	 			$pdf->MultiCell(177, 5, 'ตามมาตรา 50 ทวิ แห่งประมวลรัษฎากร', 0, 'C', 0, 1, '', '', true);
				
	 			//===== แนวตั้ง =====//
	 			$linever1 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(18, 190, 18,50, $linever1);

				$linever2 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(80, 190, 80,50, $linever2);

				$linever3 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(110, 190, 110,50, $linever3);

				$linever4 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(135, 190, 135,50, $linever4);

				$linever5 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(165, 190, 165,50, $linever5);

				$linever6 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(195, 190, 195,50, $linever6);

				//===== แนวนอน =====//
	 			$linetop = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(18, 50, 195,50, $linetop);

				$linetop2 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(80, 63, 195,63, $linetop2);

				$linetop3 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(18, 120, 80,120, $linetop3);

				$linetop4 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(80, 180, 195,180, $linetop4);

				$linetop5 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(18, 190, 195,190, $linetop5);

				//======= text in box 1 ========//
				$pdf->SetFont('freeserif','',13,'',true);
				$pdf->SetY(52);
	 			$pdf->SetX(19);
	 			$pdf->MultiCell(62, 5, 'ชื่อและที่อยู่ของผู้มีหน้าที่หักภาษี ณ ที่จ่าย บุคคลคณะบุคคล นิติบุคคล ส่วนราชการ องค์การ รัฐวิสาหกิจ ฯลฯ ', 0, 'L', 0, 1, '', '', true);

	 			$pdf->SetFont('freeserif','B',13,'',true);
				$pdf->SetY(82);
	 			$pdf->SetX(19);
	 			$pdf->MultiCell(62, 5, $address, 0, 'L', 0, 1, '', '', true);

	 			$pdf->SetFont('freeserif','B',13,'',true);
				$pdf->SetY(105);
	 			$pdf->SetX(19);
	 			$pdf->MultiCell(40, 5, $tax_id, 0, 'L', 0, 1, '', '', true);

	 			//======= text in box 2 ========//
	 			$pdf->SetFont('freeserif','',13,'',true);
				$pdf->SetY(122);
	 			$pdf->SetX(19);
	 			$pdf->MultiCell(62, 5, 'ชื่อและที่อยู่ของผู้ถูกหักภาษี ณ ที่จ่าย', 0, 'L', 0, 1, '', '', true);

	 			$pdf->SetFont('freeserif','B',12,'',true);
				$pdf->SetY(137);
	 			$pdf->SetX(21);
	 			$pdf->MultiCell(59, 5, $key->name, 0, 'L', 0, 1, '', '', true);
	
	 			$pdf->SetFont('freeserif','',13,'',true);
				$pdf->SetY(145);
	 			$pdf->SetX(19);
	 			$pdf->MultiCell(62, 5, $address, 0, 'L', 0, 1, '', '', true);

	 			$pdf->SetFont('freeserif','B',13,'',true);
				$pdf->SetY(165);
	 			$pdf->SetX(19);
	 			$pdf->MultiCell(62, 5, 'เลขประจำตัวผู้เสียภาษีของผู้ถูกหักภาษี ณ ที่จ่าย', 0, 'L', 0, 1, '', '', true);

	 			$pdf->SetFont('freeserif','',12,'',true);
				$pdf->SetY(178);
	 			$pdf->SetX(22);
	 			$pdf->MultiCell(62, 5, $key->cid, 0, 'L', 0, 1, '', '', true);

	 			//======= text in box 3 header content ========//
	 			$pdf->SetFont('freeserif','B',13,'',true);
				$pdf->SetY(54);
	 			$pdf->SetX(83);
	 			$pdf->MultiCell(32, 5, 'เงินได้ที่จ่าย', 0, 'L', 0, 1, '', '', true);

	 			$pdf->SetFont('freeserif','B',13,'',true);
				$pdf->SetY(54);
	 			$pdf->SetX(111);
	 			$pdf->MultiCell(32, 5, 'ปีภาษีที่จ่าย', 0, 'L', 0, 1, '', '', true);

	 			$pdf->SetFont('freeserif','B',13,'',true);
				$pdf->SetY(54);
	 			$pdf->SetX(138);
	 			$pdf->MultiCell(32, 5, 'จำนวนเงิน', 0, 'L', 0, 1, '', '', true);

	 			$pdf->SetFont('freeserif','B',13,'',true);
				$pdf->SetY(54);
	 			$pdf->SetX(167);
	 			$pdf->MultiCell(32, 5, 'ภาษีที่หักไว้', 0, 'L', 0, 1, '', '', true);


	 			//============= text in content ================//
	 			$pdf->SetFont('freeserif','',12,'',true);

	 			//-----col 1
				$pdf->SetY(70);
	 			$pdf->SetX(80);
	 			$pdf->MultiCell(30, 5, 'เงินเดือน ค่าจ้าง บำนาญ', 0, 'L', 0, 1, '', '', true);

				$pdf->SetY(90);
	 			$pdf->SetX(80);
	 			$pdf->MultiCell(31, 5, 'เงินประจำตำแหน่ง', 0, 'L', 0, 1, '', '', true);

	 			$pdf->SetY(105);
	 			$pdf->SetX(80);
	 			$pdf->MultiCell(31, 5, 'เงินค่าตอบแทน', 0, 'L', 0, 1, '', '', true);

	 			//-----col 2
	 			$pdf->SetY(70);
	 			$pdf->SetX(116);
	 			$pdf->MultiCell(31, 5, ( ($year == 'null') ? $this->yearThai() : ($year+543) ) , 0, 'L', 0, 1, '', '', true);

	 			//-----col 3
	 			$pdf->SetY(70);
	 			$pdf->SetX(135);
	 			$pdf->MultiCell(30, 5, number_format( $key->salary, 2 ), 0, 'R', 0, 1, '', '', true);

	 			$pdf->SetY(90);
	 			$pdf->SetX(135);
	 			$pdf->MultiCell(30, 5, '0.00', 0, 'R', 0, 1, '', '', true);

	 			$pdf->SetY(105);
	 			$pdf->SetX(135);
	 			$pdf->MultiCell(30, 5, number_format( $key->special, 2 ), 0, 'R', 0, 1, '', '', true);

	 			//-----col 4
	 			$pdf->SetY(70);
	 			$pdf->SetX(165);
	 			$pdf->MultiCell(30, 5, number_format( $key->tax, 2 ), 0, 'R', 0, 1, '', '', true);
	 			

	 			//============= text in box 4 footer sum ============//

	 			$pdf->SetFont('freeserif','B',13,'',true);
				$pdf->SetY(182);
	 			$pdf->SetX(89);
	 			$pdf->MultiCell(32, 5, 'รวม', 0, 'L', 0, 1, '', '', true);

	 			$pdf->SetFont('freeserif','',12,'',true);
	 			$pdf->SetY(182);
	 			$pdf->SetX(135);
	 			$pdf->MultiCell(30, 5, number_format( ($key->salary)+($key->special), 2 ), 0, 'R', 0, 1, '', '', true);

	 			$pdf->SetFont('freeserif','',12,'',true);
	 			$pdf->SetY(182);
	 			$pdf->SetX(165);
	 			$pdf->MultiCell(30, 5, number_format( $key->tax, 2 ), 0, 'R', 0, 1, '', '', true);


	 			//============= text footer ================//
	 			$pdf->SetFont('freeserif','',12,'',true);

				$pdf->SetY(195);
	 			$pdf->SetX(22);
	 			$pdf->MultiCell(32, 5, 'ผู้จ่ายเงิน', 0, 'L', 0, 1, '', '', true);
	 			
				$pdf->SetY(195);
	 			$pdf->SetX(39);
	 			$pdf->MultiCell(5, 5, '', 1, 'L', 0, 1, '', '', true);
	 			
				$pdf->SetY(195);
	 			$pdf->SetX(44);
	 			$pdf->MultiCell(30, 5, '(1) หัก ณ ที่จ่าย', 0, 'L', 0, 1, '', '', true);

				$pdf->SetY(195);
	 			$pdf->SetX(73);
	 			$pdf->MultiCell(5, 5, '', 1, 'L', 0, 1, '', '', true);
	 			
				$pdf->SetY(195);
	 			$pdf->SetX(78);
	 			$pdf->MultiCell(35, 5, '(2) ออกให้ตลอดไป', 0, 'L', 0, 1, '', '', true);
	 			
				$pdf->SetY(195);
	 			$pdf->SetX(112);
	 			$pdf->MultiCell(5, 5, '', 1, 'L', 0, 1, '', '', true);
	 			
				$pdf->SetY(195);
	 			$pdf->SetX(117);
	 			$pdf->MultiCell(35, 5, '(3) ออกให้ครั้งเดียว', 0, 'L', 0, 1, '', '', true);
	 			
				$pdf->SetY(205);
	 			$pdf->SetX(39);
	 			$pdf->MultiCell(5, 5, ' /', 1, 'L', 0, 1, '', '', true);

				$pdf->SetY(205);
	 			$pdf->SetX(44);
	 			$pdf->MultiCell(100, 5, '(4) เงินสบทบกองทุนประกันสังคม '.'  '.number_format($key->salary_sso, 2).' บาท', 0, 'L', 0, 1, '', '', true);

	 			$pdf->SetFont('freeserif','B',12,'',true);
	 			$pdf->SetY(220);
	 			$pdf->SetX(18);
	 			$pdf->MultiCell(177, 5, 'ข้าพเจ้าขอรับรองว่า ข้อความและตัวเลขดังกล่าวข้างต้นนี้ถูกต้องตามความเป็นจริงทุกประการ', 0, 'R', 0, 1, '', '', true);

	 			$pdf->SetFont('freeserif','',12,'',true);
	 			$pdf->SetY(235);
	 			$pdf->SetX(32);
	 			$pdf->MultiCell(170, 5, 'ลงชื่อ...........................................................ผู้มีหน้าที่หักภาษี ณ ที่จ่าย', 0, 'C', 0, 1, '', '', true);
	 			
	 			$pdf->SetY(245);
	 			$pdf->SetX(32);
	 			$pdf->MultiCell(140, 5, $director, 0, 'C', 0, 1, '', '', true);

	 			$pdf->SetY(255);
	 			$pdf->SetX(32);
	 			$pdf->MultiCell(140, 5, 'นายแพทย์เชี่ยวชาญ', 0, 'C', 0, 1, '', '', true);

	 			$pdf->SetY(265);
	 			$pdf->SetX(32);
	 			$pdf->MultiCell(140, 5, 'ผู้อำนวยการโรงพยาบาลโนนไทย', 0, 'C', 0, 1, '', '', true);

		    }

			$filename = storage_path() . '/report_tax_continuous_emp1.pdf';
		    // Response::download($filename);
		    $contents = $pdf->output($filename, 'I');
			$headers = array(
			    'Content-Type' => 'application/pdf',
			);
			return Response::make($contents, 200, $headers);
		}
		else
		{
			return View::make('login.index');
		}	
	}

	/*
	* function name itpc_home1
	* ภงด 1 ก  Home
	*
	*/
	public function itpc_home1()
	{
		if( Session::get('level') != '' )
		{
			$y = DB::Select( ' select year(order_date) as year1 from s_salary_detail group by year(order_date) order by year(order_date) desc ' );	
			return View::make( 'tax.itpc_home1', array( 'data' => $y ) );
		}
		else
		{
			return View::make('login.index');
		}  
	}

	/*
	* function name tax_itpc_type1
	* ภงด 1 ก
	*
	*/
	public function tax_itpc_type1()
	{
		$y = Input::get('y1');

		if( Session::get('level') != '' )
		{
			$n = DB::select( 'select * from s_general_data' );	
		    foreach ($n as $k) {
		      	$name 		= $k->name;
		      	$address 	= $k->address;
		      	$tax_id 	= $k->tax_id;
		      	$director 	= $k->director;
		    } 

			$pdf = new TCPDF();			
			
			$pdf->SetHeaderData('', '', 'ภ.ง.ด 1 ก พิเศษ', 'เลขประจำตัวผู้เสียภาษีอากร(ของผู้มีหน้าที่หักภาษี ณ ที่จ่าย)       '.$tax_id );
			
			// set header and footer fonts
			$pdf->setHeaderFont(Array('freeserif', '', 13));
			$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));	  

			$pdf->SetMargins(5, PDF_MARGIN_TOP, 5);
			$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
			$pdf->SetFooterMargin(PDF_MARGIN_FOOTER); 	   		   
		   
		    $sql = ' select * from (select datainfoID, concat(n.pname,"",n.fname," ",n.lname) as name, s.cid, s.tax_id,sum(s.salary+s.salary_other) as salary, sum(s.salary_sso) as salary_sso, sum(s.ch11+s.special+s.pts+s.ot+s.ch8+s.no_v+s.outpcu-s.sub_ot) as special, sum(s.tax) as tax from s_salary_detail s left join n_datageneral n on n.cid=s.cid where year(s.order_date)='.$y.' group by s.cid order by n.datainfoID asc) as a where (a.salary > 0 or a.special > 0 ) order by a.datainfoID asc ';		    
		    $result = DB::select( $sql );
			$j=0;
			$i=0;
			$sum1=0;
			$sum2=0;
			$row=0;
		    foreach ( $result as $key ) 
		    {			    
		    	$row++;		    	
		    	if( $j==4 )
	    		{
	    			$j=0;
	    		}

		    	if( $j == 0)
		    	{		    		
		    		$pdf->AddPage('L', 'letter');	
		    					
		 			//===== แนวตั้ง =====//
		 			$linever1 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(5, 60, 5,18, $linever1);

					$pdf->SetFont('freeserif','',11,'',true);
					$pdf->SetY(35);
		 			$pdf->SetX(5);
		 			$pdf->MultiCell(13, 5, 'ลำดับ', 0, 'C', 0, 1, '', '', true);

					//--col 2
					$linever2 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(18, 60, 18,18, $linever2);

					$pdf->SetY(24);
		 			$pdf->SetX(19);
		 			$pdf->MultiCell(60, 5, 'เลขประจำตัวประชาชน(ของผู้มีเงินได้)', 0, 'L', 0, 1, '', '', true);

		 			$pdf->SetY(34);
		 			$pdf->SetX(19);
		 			$pdf->MultiCell(62, 5, 'เลขประจำตัวผู้เสียภาษีอากร(ของผู้มีเงินได้)', 0, 'L', 0, 1, '', '', true);

		 			$pdf->SetY(49);
		 			$pdf->SetX(19);
		 			$pdf->MultiCell(60, 5, 'ชื่อ ที่อยู่ ผู้มีเงินได้', 0, 'L', 0, 1, '', '', true);


					//--col 3
					$linever3 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(80, 60, 80,18, $linever3);

					$pdf->SetY(24);
		 			$pdf->SetX(81);
		 			$pdf->MultiCell(20, 5, 'วัน เดือน ปี ที่เข้าทำงาน(เฉพาะกรณีเข้าทำงานระหว่างปี)', 0, 'L', 0, 1, '', '', true);



					//--col 4
					$linever4 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(105, 60, 105,18, $linever4);
					
					$linever41 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(116, 60, 116,26, $linever41);

					$linever42 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(126, 60, 126,34, $linever42);

					$linever43 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(137, 60, 137,26, $linever43);

					$linever44 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(150, 60, 150,46, $linever44);

					$pdf->SetY(19);
		 			$pdf->SetX(106);
		 			$pdf->MultiCell(58, 5, '(1)รายการลดหย่อน', 0, 'C', 0, 1, '', '', true);

		 			$pdf->SetY(28);
		 			$pdf->SetX(105);
		 			$pdf->MultiCell(11, 5, 'มีสามีภริยาหรือไม่', 0, 'L', 0, 1, '', '', true);

		 			$pdf->SetY(27);
		 			$pdf->SetX(117);
		 			$pdf->MultiCell(20, 5, 'จำนวนบุตร', 0, 'L', 0, 1, '', '', true);

		 			$pdf->SetY(37);
		 			$pdf->SetX(116);
		 			$pdf->MultiCell(20, 5, 'ศึกษา', 0, 'L', 0, 1, '', '', true);

		 			$pdf->SetY(37);
		 			$pdf->SetX(126);
		 			$pdf->MultiCell(11, 5, 'ไม่ศึกษา', 0, 'L', 0, 1, '', '', true);

		 			$pdf->SetY(27);
		 			$pdf->SetX(137);
		 			$pdf->MultiCell(28, 5, 'ค่าลดหย่อนอื่นๆ', 0, 'L', 0, 1, '', '', true);

		 			$pdf->SetY(37);
		 			$pdf->SetX(137);
		 			$pdf->MultiCell(28, 5, 'จำนวนเงิน', 0, 'C', 0, 1, '', '', true);

		 			$pdf->SetY(49);
		 			$pdf->SetX(136);
		 			$pdf->MultiCell(15, 5, 'บาท', 0, 'C', 0, 1, '', '', true);

		 			$pdf->SetY(49);
		 			$pdf->SetX(149);
		 			$pdf->MultiCell(15, 5, 'สต.', 0, 'C', 0, 1, '', '', true);



					//--col 5
					$linever5 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(165, 60, 165,18, $linever5);

					$linever51 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(194, 60, 194,30, $linever51);

					$linever52 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(212, 60, 212,30, $linever52);

					$linever53 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(233, 60, 233,50, $linever53);

					$pdf->SetY(19);
		 			$pdf->SetX(165);
		 			$pdf->MultiCell(77, 5, 'ประเภทเงินได้พึงประเมิณที่จ่าย(รวมทั้งประโยชน์ที่เพิ่ม อย่างอื่น)ถ้ามากกว่าหนึ่งประเภทให้กรอกเรียงลงไป', 0, 'L', 0, 1, '', '', true);

		 			$pdf->SetY(39);
		 			$pdf->SetX(165);
		 			$pdf->MultiCell(30, 5, '(2)', 0, 'C', 0, 1, '', '', true);
		 			$pdf->SetY(43);
		 			$pdf->SetX(165);
		 			$pdf->MultiCell(30, 5, 'ประเภทเงินได้', 0, 'C', 0, 1, '', '', true);

		 			$pdf->SetY(39);
		 			$pdf->SetX(193);
		 			$pdf->MultiCell(20, 5, '(3)', 0, 'C', 0, 1, '', '', true);
		 			$pdf->SetY(43);
		 			$pdf->SetX(193);
		 			$pdf->MultiCell(20, 5, 'จำนวนคราวที่จ่ายทั้งปี', 0, 'C', 0, 1, '', '', true);
		 			
		 			$pdf->SetY(32);
		 			$pdf->SetX(212);
		 			$pdf->MultiCell(30, 5, 'จำนวนเงินที่จ่ายแต่ละประเภทเฉพาะคนหนึงๆ ทั้งปี', 0, 'L', 0, 1, '', '', true);
		 			
		 			$pdf->SetY(52);
		 			$pdf->SetX(216);
		 			$pdf->MultiCell(20, 5, 'บาท', 0, 'L', 0, 1, '', '', true);

		 			$pdf->SetY(52);
		 			$pdf->SetX(233);
		 			$pdf->MultiCell(8, 5, 'สต.', 0, 'C', 0, 1, '', '', true);


					//--col 6
					$linever6 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(242, 60, 242,18, $linever6);	

					$linever61 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(265, 60, 265,50, $linever61);

					$pdf->SetY(19);
		 			$pdf->SetX(242);
		 			$pdf->MultiCell(33, 5, 'รวมเงินภาษีที่หักและนำส่งในปีที่ล่วงมาแล้ว', 0, 'L', 0, 1, '', '', true);

		 			$pdf->SetY(37);
		 			$pdf->SetX(242);
		 			$pdf->MultiCell(33, 5, 'จำนวนเงิน', 0, 'C', 0, 1, '', '', true);

		 			$pdf->SetY(52);
		 			$pdf->SetX(249);
		 			$pdf->MultiCell(20, 5, 'บาท', 0, 'L', 0, 1, '', '', true);

		 			$pdf->SetY(52);
		 			$pdf->SetX(266);
		 			$pdf->MultiCell(8, 5, 'สต.', 0, 'C', 0, 1, '', '', true);


					$lineverEnd = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(274.5, 60, 274.5,18, $lineverEnd);	


					//======= แนวนอน =========//
					$linehor1 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(274.5, 60, 5,60, $linehor1);

					//--col 4
					$linehor2 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(165, 26, 105,26, $linehor2);

					$linehor3 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(165, 34, 116,34, $linehor3);

					$linehor4 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(165, 46, 137,46, $linehor4);

					//--col 5
					$linehor5 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(274.5, 30, 165,30, $linehor5);

					$linehor6 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(274.5, 50, 212,50, $linehor6);					

				}//end add header

				if( $j == 0 ){
					$x1=95;
					$y1=60;
					$h1=95;
					
					$d1=61;
					$d2=68;
					$d3=74;
					$d4=81;
				}else if( $j == 1 ){
					$x1=130;
					$y1=90;
					$h1=130;
					
					$d1=96;
					$d2=103;
					$d3=109;
					$d4=116;
				}else if( $j == 2 ){
					$x1=165;
					$y1=120;
					$h1=165;
					
					$d1=131;
					$d2=138;
					$d3=144;
					$d4=151;	
				}else if( $j == 3 ){
					$x1=197;
					$y1=150;
					$h1=197;
					
					$d1=166;
					$d2=171;
					$d3=176;
					$d4=181;
				}

				//loop นอน
				$linehor = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(274.5, $x1, 5,$x1, $linehor);

				//loop ตั้ง
				$linever_loop1 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(5, $h1, 5,$y1, $linever_loop1);

				$linever_loop2 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(18, $h1, 18,$y1, $linever_loop2);

				$linever_loop3 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(80, $h1, 80,$y1, $linever_loop3);

				$linever_loop4 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(105, $h1, 105,$y1, $linever_loop4);

				$linever_loop5 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(116, $h1, 116,$y1, $linever_loop5);

				$linever_loop6 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(126, $h1, 126,$y1, $linever_loop6);

				$linever_loop7 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(137, $h1, 137,$y1, $linever_loop7);

				$linever_loop8 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(165, $h1, 165,$y1, $linever_loop8);

				$linever_loop9 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(194, $h1, 194,$y1, $linever_loop9);

				$linever_loop10 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(212, $h1, 212,$y1, $linever_loop10);

				$linever_loop11 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(242, $h1, 242,$y1, $linever_loop11);

				$linever_loop12 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(274.5, $h1, 274.5,$y1, $linever_loop12);

				//loop data
				
				//col number
				$pdf->SetFont('freeserif','',11,'',true);
				$pdf->SetY($d1);
	 			$pdf->SetX(5);
	 			$pdf->MultiCell(13, 5, $row, 0, 'C', 0, 0, '', '', true);				
				
				//col 1 data
				$pdf->SetFont('freeserif','B',11,'',true);
				$pdf->SetY($d1);
	 			$pdf->SetX(19);
	 			$pdf->MultiCell(60, 5, $key->cid, 0, 'L', 0, 0, '', '', true);	

	 			$pdf->SetFont('freeserif','',11,'',true);
	 			$pdf->SetY($d2);
	 			$pdf->SetX(19);
	 			$pdf->MultiCell(60, 5, $key->tax_id, 0, 'L', 0, 0, '', '', true);	

	 			$pdf->SetFont('freeserif','B',11,'',true);
	 			$pdf->SetY($d3);
	 			$pdf->SetX(19);
	 			$pdf->MultiCell(60, 5, $key->name, 0, 'L', 0, 0, '', '', true);
				
				$pdf->SetFont('freeserif','',11,'',true);
				$pdf->SetY($d4);
	 			$pdf->SetX(19);
	 			$pdf->MultiCell(60, 5, $address, 0, 'L', 0, 0, '', '', true);	 

	 			//col 8
	 			$pdf->SetFont('freeserif','',11,'',true);
				$pdf->SetY($d1);
	 			$pdf->SetX(169);
	 			$pdf->MultiCell(60, 5, 'ค่าจ้าง', 0, 'L', 0, 0, '', '', true);	

	 			$pdf->SetFont('freeserif','',11,'',true);
				$pdf->SetY($d3);
	 			$pdf->SetX(169);
	 			$pdf->MultiCell(24, 5, 'เงินค่าตอบแทนปฎิบัติ งานด้านการรักษา', 0, 'L', 0, 0, '', '', true);	

	 			//col 10
	 			$pdf->SetFont('freeserif','',11,'',true);
				$pdf->SetY($d1);
	 			$pdf->SetX(209);
	 			$pdf->MultiCell(30, 5, number_format( $key->salary, 2 ), 0, 'R', 0, 0, '', '', true);

	 			$pdf->SetFont('freeserif','',11,'',true);
				$pdf->SetY($d3);
	 			$pdf->SetX(209);
	 			$pdf->MultiCell(30, 5, number_format( $key->special, 2 ), 0, 'R', 0, 0, '', '', true);	

	 			//col 11		
	 			$pdf->SetFont('freeserif','',11,'',true);
				$pdf->SetY($d1);
	 			$pdf->SetX(241);
	 			$pdf->MultiCell(30, 5, number_format( $key->tax, 2 ), 0, 'R', 0, 0, '', '', true);

	 			$j++;		    	

		    	$sum1 = $sum1+$key->salary+$key->special;
		    	$sum2 = $sum2+$key->tax;

		    } //end foreach			    


			//================================ header > 2 ===================================//	
			//================================ header > 2 ===================================//	
			//================================ header > 2 ===================================//	
			if( count($result) > 0 )
			{		
				if( ($j > 2) )	
				{

			    	$pdf->AddPage('L', 'letter');

			    	//===== แนวตั้ง =====//
			 			$linever1 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(5, 60, 5,18, $linever1);

						$pdf->SetFont('freeserif','',11,'',true);
						$pdf->SetY(35);
			 			$pdf->SetX(5);
			 			$pdf->MultiCell(13, 5, 'ลำดับ', 0, 'C', 0, 1, '', '', true);

						//--col 2
						$linever2 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(18, 60, 18,18, $linever2);

						$pdf->SetY(24);
			 			$pdf->SetX(19);
			 			$pdf->MultiCell(60, 5, 'เลขประจำตัวประชาชน(ของผู้มีเงินได้)', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(34);
			 			$pdf->SetX(19);
			 			$pdf->MultiCell(62, 5, 'เลขประจำตัวผู้เสียภาษีอากร(ของผู้มีเงินได้)', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(49);
			 			$pdf->SetX(19);
			 			$pdf->MultiCell(60, 5, 'ชื่อ ที่อยู่ ผู้มีเงินได้', 0, 'L', 0, 1, '', '', true);


						//--col 3
						$linever3 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(80, 60, 80,18, $linever3);

						$pdf->SetY(24);
			 			$pdf->SetX(81);
			 			$pdf->MultiCell(20, 5, 'วัน เดือน ปี ที่เข้าทำงาน(เฉพาะกรณีเข้าทำงานระหว่างปี)', 0, 'L', 0, 1, '', '', true);



						//--col 4
						$linever4 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(105, 60, 105,18, $linever4);
						
						$linever41 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(116, 60, 116,26, $linever41);

						$linever42 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(126, 60, 126,34, $linever42);

						$linever43 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(137, 60, 137,26, $linever43);

						$linever44 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(150, 60, 150,46, $linever44);

						$pdf->SetY(19);
			 			$pdf->SetX(106);
			 			$pdf->MultiCell(58, 5, '(1)รายการลดหย่อน', 0, 'C', 0, 1, '', '', true);

			 			$pdf->SetY(28);
			 			$pdf->SetX(105);
			 			$pdf->MultiCell(11, 5, 'มีสามีภริยาหรือไม่', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(27);
			 			$pdf->SetX(117);
			 			$pdf->MultiCell(20, 5, 'จำนวนบุตร', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(37);
			 			$pdf->SetX(116);
			 			$pdf->MultiCell(20, 5, 'ศึกษา', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(37);
			 			$pdf->SetX(126);
			 			$pdf->MultiCell(11, 5, 'ไม่ศึกษา', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(27);
			 			$pdf->SetX(137);
			 			$pdf->MultiCell(28, 5, 'ค่าลดหย่อนอื่นๆ', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(37);
			 			$pdf->SetX(137);
			 			$pdf->MultiCell(28, 5, 'จำนวนเงิน', 0, 'C', 0, 1, '', '', true);

			 			$pdf->SetY(49);
			 			$pdf->SetX(136);
			 			$pdf->MultiCell(15, 5, 'บาท', 0, 'C', 0, 1, '', '', true);

			 			$pdf->SetY(49);
			 			$pdf->SetX(149);
			 			$pdf->MultiCell(15, 5, 'สต.', 0, 'C', 0, 1, '', '', true);



						//--col 5
						$linever5 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(165, 60, 165,18, $linever5);

						$linever51 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(194, 60, 194,30, $linever51);

						$linever52 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(212, 60, 212,30, $linever52);

						$linever53 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(233, 60, 233,50, $linever53);

						$pdf->SetY(19);
			 			$pdf->SetX(165);
			 			$pdf->MultiCell(77, 5, 'ประเภทเงินได้พึงประเมิณที่จ่าย(รวมทั้งประโยชน์ที่เพิ่ม อย่างอื่น)ถ้ามากกว่าหนึ่งประเภทให้กรอกเรียงลงไป', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(39);
			 			$pdf->SetX(165);
			 			$pdf->MultiCell(30, 5, '(2)', 0, 'C', 0, 1, '', '', true);
			 			$pdf->SetY(43);
			 			$pdf->SetX(165);
			 			$pdf->MultiCell(30, 5, 'ประเภทเงินได้', 0, 'C', 0, 1, '', '', true);

			 			$pdf->SetY(39);
			 			$pdf->SetX(193);
			 			$pdf->MultiCell(20, 5, '(3)', 0, 'C', 0, 1, '', '', true);
			 			$pdf->SetY(43);
			 			$pdf->SetX(193);
			 			$pdf->MultiCell(20, 5, 'จำนวนคราวที่จ่ายทั้งปี', 0, 'C', 0, 1, '', '', true);
			 			
			 			$pdf->SetY(32);
			 			$pdf->SetX(212);
			 			$pdf->MultiCell(30, 5, 'จำนวนเงินที่จ่ายแต่ละประเภทเฉพาะคนหนึงๆ ทั้งปี', 0, 'L', 0, 1, '', '', true);
			 			
			 			$pdf->SetY(52);
			 			$pdf->SetX(216);
			 			$pdf->MultiCell(20, 5, 'บาท', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(52);
			 			$pdf->SetX(233);
			 			$pdf->MultiCell(8, 5, 'สต.', 0, 'C', 0, 1, '', '', true);


						//--col 6
						$linever6 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(242, 60, 242,18, $linever6);	

						$linever61 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(265, 60, 265,50, $linever61);

						$pdf->SetY(19);
			 			$pdf->SetX(242);
			 			$pdf->MultiCell(33, 5, 'รวมเงินภาษีที่หักและนำส่งในปีที่ล่วงมาแล้ว', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(37);
			 			$pdf->SetX(242);
			 			$pdf->MultiCell(33, 5, 'จำนวนเงิน', 0, 'C', 0, 1, '', '', true);

			 			$pdf->SetY(52);
			 			$pdf->SetX(249);
			 			$pdf->MultiCell(20, 5, 'บาท', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(52);
			 			$pdf->SetX(266);
			 			$pdf->MultiCell(8, 5, 'สต.', 0, 'C', 0, 1, '', '', true);


						$lineverEnd = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(274.5, 60, 274.5,18, $lineverEnd);	


						//======= แนวนอน =========//
						$linehor1 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(274.5, 60, 5,60, $linehor1);

						//--col 4
						$linehor2 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(165, 26, 105,26, $linehor2);

						$linehor3 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(165, 34, 116,34, $linehor3);

						$linehor4 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(165, 46, 137,46, $linehor4);

						//--col 5
						$linehor5 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(274.5, 30, 165,30, $linehor5);

						$linehor6 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(274.5, 50, 212,50, $linehor6);
			    } 
			    //================================ END header > 2 ===================================//	
				//================================ END header > 2 ===================================//	
				//================================ END header > 2 ===================================//	   

			    //แนวตั้ง
			    if( $j > 2)
			    {
			    	$h1 = 40;
			    	$y1 = 70;
			    	$d4 = 45;
			    	$x1 = 60;
			    }

			    $verft1 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(5, $h1+10, 5,$y1, $verft1);

				$verft2 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(212, $h1+10, 212,$y1, $verft2);

				$verft3 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(242, $h1+10, 242,$y1, $verft3);

				$verft4 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(274.5, $h1+10, 274.5,$y1, $verft4);

				$pdf->SetFont('freeserif','B',11,'',true);
				$pdf->SetY($d4+17);
	 			$pdf->SetX(151);
	 			$pdf->MultiCell(50, 5, 'รวมยอดเงินได้และภาษีที่นำส่ง', 0, 'R', 0, 0, '', '', true);

	 			$pdf->SetFont('freeserif','B',11,'',true);
				$pdf->SetY($d4+17);
	 			$pdf->SetX(211);
	 			$pdf->MultiCell(30, 5, number_format($sum1, 2), 0, 'R', 0, 0, '', '', true);

	 			$pdf->SetFont('freeserif','B',11,'',true);
				$pdf->SetY($d4+17);
	 			$pdf->SetX(241);
	 			$pdf->MultiCell(32, 5, number_format($sum2, 2), 0, 'R', 0, 0, '', '', true);

				//แนวนอน
				$horft1 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(274.5, $x1+10, 5,$x1+10, $horft1);

			    //================================= last page footer ======================================//
				$pdf->SetFont('freeserif','B',11,'',true);
				$pdf->SetY(145);
	 			$pdf->SetX(8);
	 			$pdf->MultiCell(30, 5, 'หมายเหตุ', 0, 'C', 0, 0, '', '', true);
				
	 			$pdf->SetFont('freeserif','',10,'',true);
				$pdf->SetY(155);
	 			$pdf->SetX(5);
	 			$pdf->MultiCell(180, 5, '1.ให้ระบุว่า มี หรือ ไม่มี ภริยาโดยใส่เครื่องหมาย / ลงใน หน้าข้อความแต่กรณีพร้อมทั้งกรอกจำนวนบุตรที่มีสิทธิหักลดหย่อนศึกษากี่คน ไม่ศึกษากี่คนและยอดรวมจำนวนเงินค่าลดหย่อนอื่น ๆ ที่จ่ายให้แก่เบี้ยประกันชีวิต เงินสะสมดอกเบี้ยเงินกู้ยืมเพื่อซื้อ เช้าซื้อ หรือสร้างอาคารที่อยู่อาศัย ฯ และเงินสมทบ ฯ', 0, 'L', 0, 0, '', '', true);
				
				$pdf->SetFont('freeserif','',10,'',true);
				$pdf->SetY(175);
	 			$pdf->SetX(5);
	 			$pdf->MultiCell(180, 5, '2.ให้กรอกประเภทเงินได้ที่จ่าย เช่น เงินเดือน ค่าจ้าง เบี้ยเลี้ยง โบนัส บำเหน็จ เงินค่าธรรมเนียม ค่านายหน้า เปบี้ยประชุมค่าภาษีเงินได้ ฯลฯ', 0, 'L', 0, 0, '', '', true);
				
	 			$pdf->SetFont('freeserif','',10,'',true);
				$pdf->SetY(185);
	 			$pdf->SetX(5);
	 			$pdf->MultiCell(180, 5, '3.จำนวนคราวที่จ่ายทั้งปี -จ่ายเป็นรายวัน กรอก 1   -จ่ายเป็นรายสัปดาห์ กรอก 2     -จ่ายเป็นรายปักษ์ กรอก 3     -จ่ายเป็นรายเดือน กรอก 4     -จ่ายเป็นคราวไม่แน่นอน กรอก 5', 0, 'L', 0, 0, '', '', true);			
				
	 			$pdf->SetFont('freeserif','B',10,'',true);
				$pdf->SetY(155);
	 			$pdf->SetX(180);
	 			$pdf->MultiCell(94, 5, 'ลงชื่อ................................................................ผู้จ่ายเงิน', 0, 'C', 0, 0, '', '', true);
				
				$pdf->SetFont('freeserif','B',11,'',true);
				$pdf->SetY(164);
	 			$pdf->SetX(180);
	 			$pdf->MultiCell(94, 5, $director, 0, 'C', 0, 0, '', '', true);
				
				$pdf->SetY(170);
	 			$pdf->SetX(180);
	 			$pdf->MultiCell(94, 5, 'นายแพทย์เชี่ยวชาญ', 0, 'C', 0, 0, '', '', true);

	 			$pdf->SetY(180);
	 			$pdf->SetX(180);
	 			$pdf->MultiCell(94, 5, 'ผู้อำนวยการโรงพยาบาลโนนไทย', 0, 'C', 0, 0, '', '', true);
	 			
				$pdf->SetY(190);
	 			$pdf->SetX(180);
	 			$pdf->MultiCell(94, 5, 'ยื่นวันที่.................................................................', 0, 'C', 0, 0, '', '', true);
		}// check result > 0

			$filename = storage_path() . '/report_tax_itpc_emp1.pdf';
		    // Response::download($filename);
		    $contents = $pdf->output($filename, 'I');
			$headers = array(
			    'Content-Type' => 'application/pdf',
			);
			return Response::make($contents, 200, $headers);
		}
		else
		{
			return View::make('login.index');
		}	
	}




	/**
	 * ใบสรุปรายได้ ภาษี พกส. ชั่วคราว
	 */
	public function sumsalary_tax1()
	{
		if( Session::get('level') != '' )
		{
			$y = DB::Select( ' select year(order_date) as year1 from s_salary_detail group by year(order_date) order by year(order_date) desc ' );	
			return View::make( 'tax.sumsalary_tax1', array( 'data' => $y ) );
		}
		else
		{
			return View::make('login.index');
		}  
	}



	/**
	 * ใบสรุปรายได้ ภาษี พกส. ชั่วคราว PDF
	 */
	public function sumsalary_tax1_pdf()
	{
		$y = Input::get('y');

		
		$pdf = new TCPDF();
		$pdf->SetPrintHeader(true);
	    $pdf->SetPrintFooter(true);	

	    $pdf->setHeaderFont(array('freeserif','B',13));
		$pdf->setFooterFont(array('freeserif','B',PDF_FONT_SIZE_DATA));

	    $pdf->SetHeaderData('', '', 'ใบสรุปรายได้ เพื่อหักภาษี สำหรับ พกส. ชั่วคราว ประจำปี '.($y+543), ' ลำดับ    บัตรประชาชน                   ชื่อ-นามสกุล                                         รวมรายได้ ค่าจ้าง.+ค่าตอบแทน           เซ็นต์ชื่อ  ');			
		 		   
		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		 
		// set margins
		$pdf->SetMargins(10, PDF_MARGIN_TOP, 10);
		$pdf->SetHeaderMargin(15);
		$pdf->SetFooterMargin(15);

		$pdf->SetFont('freeserif','',11,'',true);

		$pdf->AddPage('L', 'A4');

		$sql = ' select * from (select concat(n.pname,"",n.fname," ",n.lname) as name, s.cid, sum(s.salary+s.salary_other+s.ch11+s.special+s.pts+s.ot+s.ch8+s.no_v+s.outpcu-s.sub_ot) as special from s_salary_detail s left join n_datageneral n on n.cid=s.cid where  year(s.order_date)='.$y.'  group by s.cid order by n.datainfoID asc) as a where a.special > 0 ';	
		
		
		$result = DB::select( $sql );

		$tbl  = ' <style> ';
		$tbl .= '  table.table-report tr td{ border-bottom:1px solid #000; height:30px; line-height: 30px; } ';	
		$tbl .= ' .text-bold { font-weight: bold; } ';		
		$tbl .= ' </style> ';

		$tbl  .= ' <table class="table-report"> ';		    
		 
		$r=0;	
		
	    foreach ($result as $key) 		    
	    {	
	    	if( $key->special > 0 )
	    	{
		        $r++;
		       
		    	$tbl .= ' <tr>';

			    $tbl .= ' <td width="25">';
			    $tbl .= $r;
			    $tbl .= ' </td>';
			    
			    $tbl .= ' <td width="100">';
			    $tbl .= $key->cid;
			    $tbl .= ' </td>';

			    $tbl .= ' <td width="250">';
			    $tbl .= $key->name;
			    $tbl .= ' </td>';

			    $tbl .= ' <td width="85" align="right">';
			    $tbl .= number_format($key->special, 2);
			    $tbl .= ' </td>';

			    $tbl .= ' <td width="200" align="right"> </td>';

			    $tbl .= ' </tr>';
			}
		}

		$tbl  .= ' </table>';	
	   
		$pdf->writeHTML( $tbl, true, false, false, false, '' );

	    $filename = storage_path() . '/report_sumsalary_tax1.pdf';
	    //return Response::download($filename);
	    $contents = $pdf->output($filename, 'I');
		$headers = array(
		    'Content-Type' => 'application/pdf',
		);
		return Response::make($contents, 200, $headers); 
	}



	//======================================= ลุกจ้างประจำ ======================================//
	//======================================= ลุกจ้างประจำ ======================================//

	/*
	* function name tax_recomend_type2
	* รายละเอียดเงินเดือน พิมพ์หนังสือรับรอง
	*
	*/
	public function tax_recomend_type2()
	{			
		if( Session::get('level') != '' )
		{							
			$data = DB::table( 's_salary_ocsc_detail' )	  
				->leftjoin( 'n_datageneral', 'n_datageneral.cid', '=', 's_salary_ocsc_detail.cid' )	
				->leftjoin( 'n_position_salary', 'n_position_salary.cid', '=', 'n_datageneral.cid' )							
				->where( 'n_position_salary.level', '=', 'ลูกจ้างประจำ' )
				->groupBY( 's_salary_ocsc_detail.cid' )	
				->orderBY( 'n_datageneral.datainfoID','asc' )	
				->select( 'n_datageneral.*' )
		        ->paginate( 20 );		        

			return View::make( 'tax.recomend_type2', array( 'data' => $data ) );
		}
		else
		{
			return View::make('login.index');
		}	
	}

	/**
    * function name : search_type2
    * search data tax_recomend_type2
    * post
    */
    public function search_type2()
    {   		  
	   if ( Session::get('level') != '' )
		{
			$search  = Input::get( 'search_tax2' );	
			if( $search != '' )
			{    		    	
				$data = DB::table( 's_salary_ocsc_detail' )	  
				->leftjoin('n_datageneral', 'n_datageneral.cid', '=', 's_salary_ocsc_detail.cid')
				->join( 'n_position_salary', 'n_position_salary.cid', '=', 's_salary_ocsc_detail.cid' )				
				//->where( 'n_datageneral.status', '=', '0' )								
	          	->where( 'n_position_salary.level', '=', 'ลูกจ้างประจำ' )	

	          	->where( 'n_datageneral.cid', 'like', "%$search%" )
	         	->orWhere( 'n_datageneral.fname', 'like', "%$search%" )	 
	          	->orWhere( 'n_datageneral.lname', 'like', "%$search%" )	

				->groupBY( 's_salary_ocsc_detail.cid' )	
				->orderBY( 'n_datageneral.datainfoID','asc' )	
				->select( 'n_datageneral.*' )
		        ->paginate( 70 );	
		    } else{
		    	$data = DB::table( 's_salary_ocsc_detail' )	  
				->leftjoin( 'n_datageneral', 'n_datageneral.cid', '=', 's_salary_ocsc_detail.cid' )	
				->leftjoin( 'n_position_salary', 'n_position_salary.cid', '=', 'n_datageneral.cid' )			
				//->where( 'n_datageneral.status', '=', '0' )	
				->where( 'n_position_salary.level', '=', 'ลูกจ้างประจำ' )
				->groupBY( 's_salary_ocsc_detail.cid' )	
				->orderBY( 'n_datageneral.datainfoID','asc' )	
				->select( 'n_datageneral.*' )
		        ->paginate( 20 );	
		    }		    
	     
			//view page create
		    return View::make( 'tax.recomend_type2',  array( 'data' => $data ) );	
		}	
		else
		{
			//return login
    		return View::make( 'login.index' );	
		}	
    }

	/**
    * function name : fromstax_type2
    * data to from tax model
    * post
    */
    public function fromstax_type2( $id=null, $year=null )
    {   	
    	if( $year == 'null' )
    	{        			
    		$data = DB::table( 's_salary_ocsc_detail' ) 
				->leftjoin('n_datageneral', 'n_datageneral.cid', '=', 's_salary_ocsc_detail.cid')
				->leftjoin( 'n_position_salary', 'n_position_salary.cid', '=', 'n_datageneral.cid' )				
				//->where( 'n_datageneral.status', '=', '0' )	
				->where( 'n_datageneral.cid', '=', $id )
				->where( 'n_position_salary.level', '=', 'ลูกจ้างประจำ' )					
				->where( DB::raw('year(s_salary_ocsc_detail.order_date)'), '=', $this->max_year() )							
				->orderBY( 's_salary_ocsc_detail.order_date','desc' )	
				->select( 'n_datageneral.cid', 'n_datageneral.pname', 'n_datageneral.fname', 'n_datageneral.lname', 's_salary_ocsc_detail.*', DB::raw('month(s_salary_ocsc_detail.order_date) as ordermonth') )
		        ->get();

		    $sumdata = DB::table( 's_salary_ocsc_detail' ) 
				->leftjoin('n_datageneral', 'n_datageneral.cid', '=', 's_salary_ocsc_detail.cid')
				->leftjoin( 'n_position_salary', 'n_position_salary.cid', '=', 'n_datageneral.cid' )				
				//->where( 'n_datageneral.status', '=', '0' )	
				->where( 'n_datageneral.cid', '=', $id )	
				->where( 'n_position_salary.level', '=', 'ลูกจ้างประจำ' )
				->where( DB::raw('year(s_salary_ocsc_detail.order_date)'), '=', $this->max_year() )													
				->select( 's_salary_ocsc_detail.cid', db::raw('sum( s_salary_ocsc_detail.salary ) as salary_sum'), db::raw('sum( s_salary_ocsc_detail.r_other ) as r_other_sum'),  db::raw('sum( s_salary_ocsc_detail.special_m ) as salary_special_sum'), db::raw('sum( s_salary_ocsc_detail.tax ) as salary_tax_sum'), db::raw('sum( s_salary_ocsc_detail.r_other ) as salary_rother_sum'), db::raw('sum( s_salary_ocsc_detail.r_pt ) as salary_rpt_sum') )
		        ->first();	

		    $year = DB::select( ' select (year(order_date)+543) as year from s_salary_ocsc_detail group by  year(order_date) ' );    

		    return View::make( 'tax.fromtax2',  array( 'data' => $data, 'sumdata' => $sumdata, 'year' => $year, 'id' => $id ) );	
		}
		else
		{
			$data = DB::table( 's_salary_ocsc_detail' ) 
				->leftjoin('n_datageneral', 'n_datageneral.cid', '=', 's_salary_ocsc_detail.cid')
				->leftjoin( 'n_position_salary', 'n_position_salary.cid', '=', 'n_datageneral.cid' )				
				//->where( 'n_datageneral.status', '=', '0' )	
				->where( 'n_datageneral.cid', '=', $id )
				->where( 'n_position_salary.level', '=', 'ลูกจ้างประจำ' )					
				->where( DB::raw('year(s_salary_ocsc_detail.order_date)'), '=', $year )							
				->orderBY( 's_salary_ocsc_detail.order_date','desc' )	
				->select( 'n_datageneral.cid', 'n_datageneral.pname', 'n_datageneral.fname', 'n_datageneral.lname', 's_salary_ocsc_detail.*', DB::raw('month(s_salary_ocsc_detail.order_date) as ordermonth') )
		        ->get();	

		    $sumdata = DB::table( 's_salary_ocsc_detail' ) 
				->leftjoin('n_datageneral', 'n_datageneral.cid', '=', 's_salary_ocsc_detail.cid')
				->leftjoin( 'n_position_salary', 'n_position_salary.cid', '=', 'n_datageneral.cid' )				
				//->where( 'n_datageneral.status', '=', '0' )	
				->where( 'n_datageneral.cid', '=', $id )
				->where( 'n_position_salary.level', '=', 'ลูกจ้างประจำ' )	
				->where( DB::raw('year(s_salary_ocsc_detail.order_date)'), '=',$year )													
				->select( 's_salary_ocsc_detail.cid', db::raw('sum( s_salary_ocsc_detail.salary ) as salary_sum'), db::raw('sum( s_salary_ocsc_detail.r_other ) as r_other_sum'), db::raw('sum( s_salary_ocsc_detail.special_m ) as salary_special_sum'), db::raw('sum( s_salary_ocsc_detail.tax ) as salary_tax_sum'), db::raw('sum( s_salary_ocsc_detail.r_other ) as salary_rother_sum'), db::raw('sum( s_salary_ocsc_detail.r_pt ) as salary_rpt_sum') )
		        ->first();	

		    $year = DB::select( ' select (year(order_date)+543) as year from s_salary_ocsc_detail group by  year(order_date) ' );    

		    return View::make( 'tax.fromtax2',  array( 'data' => $data, 'sumdata' => $sumdata, 'year' => $year, 'id' => $id ) );	
		} 	   	
    }

    /**
    * function name : updatetax_type2
    * update data to s_salary_ocsc_detail
    * post
    */
    public function updatetax_type2( $id=null, $date=null, $tax=null, $special=null, $rother=null, $rpt=null )
    {    		   	   
        $user_data = array(            
            'special_m' 	=> $special,
            'r_pt' 	 	=> $rpt, 
            'r_other' 	=> $rother,
            'tax' 	 	=> $tax                   		            	                       
        );         
      
        //update user details
        $result = DB::table( 's_salary_ocsc_detail' )->where( 'cid', '=', $id )->where( 'order_date', '=', $date )->update( $user_data );	        
        if( $result )
        {
        	return 'บันทึกเรียบร้อย'; 
        }
        else
        {
        	return 'ไม่สามารถบันทึกได้'; 
        }
    }

    /*
	* function name continuous_home2
	* หนังสือรับรอง  Home
	*
	*/
	public function continuous_home2()
	{		
		if( Session::get('level') != '' )
		{
			$y = DB::Select( ' select year(order_date) as year1 from s_salary_detail group by year(order_date) order by year(order_date) desc ' );	
			return View::make( 'tax.continuous_home2', array( 'data' => $y ) );
		}
		else
		{
			return View::make('login.index');
		} 
	}
	/*
	* function name tax_continuous_type2
	* พิมพ์หนังสือรับรอง
	*
	*/
	public function tax_continuous_type2( $id=null, $year=null )
	{		
		if( Session::get('level') != '' )
		{		
			$y = Input::get('y2');
			if($y != ''){
				$year = $y;
				$id = 'all';
			}	

		    $pdf = new TCPDF();
		    $pdf->SetPrintHeader(false);
		    $pdf->SetPrintFooter(false);			   		   

		    $n = DB::select( 'select * from s_general_data' );	
		    foreach ($n as $k) {
		      	$name 		= $k->name;
		      	$address 	= $k->address;
		      	$address2 	= $k->address2;
		      	$tax_id2 	= $k->tax_id2;
		      	$director 	= $k->director;
		    } 

		    if( $id != 'null' && $year != 'null' )
		    {
				$sql = ' select concat(n.pname,"",n.fname," ",n.lname) as name, s.cid, s.tax_id,sum(s.salary+s.r_other) as salary, sum(s.r_c) as r_c, sum(s.special_m+s.pts+s.pts2) as special, sum(s.tax) as tax, sum(s.kbk) as kbk from s_salary_ocsc_detail s left join n_datageneral n on n.cid=s.cid left join n_position_salary p on p.cid=n.cid where p.`level`="ลูกจ้างประจำ" and  year(s.order_date)='.$year.' and s.cid='.$id.' group by s.cid order by n.datainfoID asc ';		    	    
		    }
		    if( $id != 'null' && $year == 'null' )
		    {		    	
		    	$sql = ' select concat(n.pname,"",n.fname," ",n.lname) as name, s.cid, s.tax_id,sum(s.salary+s.r_other) as salary, sum(s.r_c) as r_c, sum(s.special_m+s.pts+s.pts2) as special, sum(s.tax) as tax, sum(s.kbk) as kbk from s_salary_ocsc_detail s left join n_datageneral n on n.cid=s.cid left join n_position_salary p on p.cid=n.cid where  p.`level`="ลูกจ้างประจำ" and  year(s.order_date)='.$this->max_year().' and s.cid='.$id.' group by s.cid order by n.datainfoID asc ';
		    }
		    if( $id == 'null' && $year == 'null' )
		    {
		    	$sql = ' select concat(n.pname,"",n.fname," ",n.lname) as name, s.cid, s.tax_id,sum(s.salary+s.r_other) as salary, sum(s.r_c) as r_c, sum(s.special_m+s.pts+s.pts2) as special, sum(s.tax) as tax, sum(s.kbk) as kbk from s_salary_ocsc_detail s left join n_datageneral n on n.cid=s.cid left join n_position_salary p on p.cid=n.cid where  p.`level`="ลูกจ้างประจำ" and  year(s.order_date)='.$this->max_year().' group by s.cid order by n.datainfoID asc ';		    		    
		    }
		    if( $id == 'all' && $year != '' )
		    {
				$sql = ' select * from (select concat(n.pname,"",n.fname," ",n.lname) as name, s.cid, s.tax_id,sum(s.salary+s.r_other) as salary, sum(s.r_c) as r_c, sum(s.special_m+s.pts+s.pts2) as special, sum(s.tax) as tax, sum(s.kbk) as kbk from s_salary_ocsc_detail s left join n_datageneral n on n.cid=s.cid left join n_position_salary p on p.cid=n.cid where  p.`level`="ลูกจ้างประจำ" and  year(s.order_date)='.$year.' group by s.cid order by n.datainfoID asc) as a where a.salary > 0 ';		    	    
		    }
		    
		    $result = DB::select( $sql );		    

		    foreach ( $result as $key ) 
		    {

		    	$pdf->AddPage('P', 'A4');

		    	$pdf->SetFont('freeserif','B',11,'',true);		    
	 			$pdf->MultiCell(177, 5, 'เลขที่ งบ. ....................... /'.( ($year == 'null') ? $this->yearThai() : ($year+543) ), 0, 'R', 0, 1, '', '', true);

	 			$pdf->SetFont('freeserif','B',16,'',true);
		    	$pdf->SetY(25);
	 			$pdf->SetX(18);
	 			$pdf->MultiCell(177, 5, 'หนังสือรับรองการหักภาษี ณ ที่จ่าย', 0, 'C', 0, 1, '', '', true);

	 			$pdf->SetY(34);
	 			$pdf->SetX(18);
	 			$pdf->MultiCell(177, 5, 'ตามมาตรา 50 ทวิ แห่งประมวลรัษฎากร', 0, 'C', 0, 1, '', '', true);
				
	 			//===== แนวตั้ง =====//
	 			$linever1 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(18, 190, 18,50, $linever1);

				$linever2 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(80, 190, 80,50, $linever2);

				$linever3 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(110, 190, 110,50, $linever3);

				$linever4 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(135, 190, 135,50, $linever4);

				$linever5 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(165, 190, 165,50, $linever5);

				$linever6 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(195, 190, 195,50, $linever6);

				//===== แนวนอน =====//
	 			$linetop = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(18, 50, 195,50, $linetop);

				$linetop2 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(80, 63, 195,63, $linetop2);

				$linetop3 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(18, 120, 80,120, $linetop3);

				$linetop4 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(80, 180, 195,180, $linetop4);

				$linetop5 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(18, 190, 195,190, $linetop5);

				//======= text in box 1 ========//
				$pdf->SetFont('freeserif','',13,'',true);
				$pdf->SetY(52);
	 			$pdf->SetX(19);
	 			$pdf->MultiCell(62, 5, 'ชื่อและที่อยู่ของผู้มีหน้าที่หักภาษี ณ ที่จ่าย บุคคลคณะบุคคล นิติบุคคล ส่วนราชการ องค์การ รัฐวิสาหกิจ ฯลฯ ', 0, 'L', 0, 1, '', '', true);

	 			$pdf->SetFont('freeserif','B',13,'',true);
				$pdf->SetY(82);
	 			$pdf->SetX(19);
	 			$pdf->MultiCell(62, 5, $address2, 0, 'L', 0, 1, '', '', true);

	 			$pdf->SetFont('freeserif','B',13,'',true);
				$pdf->SetY(105);
	 			$pdf->SetX(19);
	 			$pdf->MultiCell(40, 5, $tax_id2, 0, 'L', 0, 1, '', '', true);

	 			//======= text in box 2 ========//
	 			$pdf->SetFont('freeserif','',13,'',true);
				$pdf->SetY(122);
	 			$pdf->SetX(19);
	 			$pdf->MultiCell(62, 5, 'ชื่อและที่อยู่ของผู้ถูกหักภาษี ณ ที่จ่าย', 0, 'L', 0, 1, '', '', true);

	 			$pdf->SetFont('freeserif','B',12,'',true);
				$pdf->SetY(137);
	 			$pdf->SetX(21);
	 			$pdf->MultiCell(59, 5, $key->name, 0, 'L', 0, 1, '', '', true);
	
	 			$pdf->SetFont('freeserif','',13,'',true);
				$pdf->SetY(145);
	 			$pdf->SetX(19);
	 			$pdf->MultiCell(62, 5, $address, 0, 'L', 0, 1, '', '', true);

	 			$pdf->SetFont('freeserif','B',13,'',true);
				$pdf->SetY(165);
	 			$pdf->SetX(19);
	 			$pdf->MultiCell(62, 5, 'เลขประจำตัวผู้เสียภาษีของผู้ถูกหักภาษี ณ ที่จ่าย', 0, 'L', 0, 1, '', '', true);

	 			$pdf->SetFont('freeserif','',12,'',true);
				$pdf->SetY(178);
	 			$pdf->SetX(22);
	 			$pdf->MultiCell(62, 5, $key->cid, 0, 'L', 0, 1, '', '', true);

	 			//======= text in box 3 header content ========//
	 			$pdf->SetFont('freeserif','B',13,'',true);
				$pdf->SetY(54);
	 			$pdf->SetX(83);
	 			$pdf->MultiCell(32, 5, 'เงินได้ที่จ่าย', 0, 'L', 0, 1, '', '', true);

	 			$pdf->SetFont('freeserif','B',13,'',true);
				$pdf->SetY(54);
	 			$pdf->SetX(111);
	 			$pdf->MultiCell(32, 5, 'ปีภาษีที่จ่าย', 0, 'L', 0, 1, '', '', true);

	 			$pdf->SetFont('freeserif','B',13,'',true);
				$pdf->SetY(54);
	 			$pdf->SetX(138);
	 			$pdf->MultiCell(32, 5, 'จำนวนเงิน', 0, 'L', 0, 1, '', '', true);

	 			$pdf->SetFont('freeserif','B',13,'',true);
				$pdf->SetY(54);
	 			$pdf->SetX(167);
	 			$pdf->MultiCell(32, 5, 'ภาษีที่หักไว้', 0, 'L', 0, 1, '', '', true);


	 			//============= text in content ================//
	 			$pdf->SetFont('freeserif','',12,'',true);

	 			//-----col 1
				$pdf->SetY(70);
	 			$pdf->SetX(80);
	 			$pdf->MultiCell(30, 5, 'เงินเดือน ค่าจ้าง บำนาญ', 0, 'L', 0, 1, '', '', true);

				$pdf->SetY(90);
	 			$pdf->SetX(80);
	 			$pdf->MultiCell(31, 5, 'เงินประจำตำแหน่ง', 0, 'L', 0, 1, '', '', true);

	 			$pdf->SetY(105);
	 			$pdf->SetX(80);
	 			$pdf->MultiCell(31, 5, 'เงินค่าครองชีพ', 0, 'L', 0, 1, '', '', true);

	 			//-----col 2
	 			$pdf->SetY(70);
	 			$pdf->SetX(116);
	 			$pdf->MultiCell(31, 5, ( ($year == 'null') ? $this->yearThai() : ($year+543) ) , 0, 'L', 0, 1, '', '', true);

	 			//-----col 3
	 			$pdf->SetY(70);
	 			$pdf->SetX(135);
	 			$pdf->MultiCell(30, 5, number_format( $key->salary, 2 ), 0, 'R', 0, 1, '', '', true);

	 			$pdf->SetY(90);
	 			$pdf->SetX(135);
	 			$pdf->MultiCell(30, 5, number_format( $key->r_c, 2 ), 0, 'R', 0, 1, '', '', true);

	 			$pdf->SetY(105);
	 			$pdf->SetX(135);
	 			$pdf->MultiCell(30, 5, number_format( $key->special, 2 ), 0, 'R', 0, 1, '', '', true);

	 			//-----col 4
	 			$pdf->SetY(70);
	 			$pdf->SetX(165);
	 			$pdf->MultiCell(30, 5, number_format( $key->tax, 2 ), 0, 'R', 0, 1, '', '', true);
	 			

	 			//============= text in box 4 footer sum ============//

	 			$pdf->SetFont('freeserif','B',13,'',true);
				$pdf->SetY(182);
	 			$pdf->SetX(89);
	 			$pdf->MultiCell(32, 5, 'รวม', 0, 'L', 0, 1, '', '', true);

	 			$pdf->SetFont('freeserif','',12,'',true);
	 			$pdf->SetY(182);
	 			$pdf->SetX(135);
	 			$pdf->MultiCell(30, 5, number_format( ($key->salary)+($key->special)+($key->r_c), 2 ), 0, 'R', 0, 1, '', '', true);

	 			$pdf->SetFont('freeserif','',12,'',true);
	 			$pdf->SetY(182);
	 			$pdf->SetX(165);
	 			$pdf->MultiCell(30, 5, number_format( $key->tax, 2 ), 0, 'R', 0, 1, '', '', true);


	 			//============= CID=5350400051484 พี่หรั่ง ================//
	 			if( $id == '5350400051484' )
	 			{
		 			$pdf->SetFont('freeserif','',12,'',true);

					$pdf->SetY(195);
		 			$pdf->SetX(22);
		 			$pdf->MultiCell(32, 5, 'ผู้จ่ายเงิน', 0, 'L', 0, 1, '', '', true);
		 			
					$pdf->SetY(195);
		 			$pdf->SetX(39);
		 			$pdf->MultiCell(5, 5, '', 1, 'L', 0, 1, '', '', true);
		 			
					$pdf->SetY(195);
		 			$pdf->SetX(44);
		 			$pdf->MultiCell(30, 5, '(1) หัก ณ ที่จ่าย', 0, 'L', 0, 1, '', '', true);

					$pdf->SetY(195);
		 			$pdf->SetX(73);
		 			$pdf->MultiCell(5, 5, '', 1, 'L', 0, 1, '', '', true);
		 			
					$pdf->SetY(195);
		 			$pdf->SetX(78);
		 			$pdf->MultiCell(35, 5, '(2) ออกให้ตลอดไป', 0, 'L', 0, 1, '', '', true);
		 			
					$pdf->SetY(195);
		 			$pdf->SetX(112);
		 			$pdf->MultiCell(5, 5, '', 1, 'L', 0, 1, '', '', true);
		 			
					$pdf->SetY(195);
		 			$pdf->SetX(117);
		 			$pdf->MultiCell(35, 5, '(3) ออกให้ครั้งเดียว', 0, 'L', 0, 1, '', '', true);
		 			
					$pdf->SetY(205);
		 			$pdf->SetX(39);
		 			$pdf->MultiCell(5, 5, ' /', 1, 'L', 0, 1, '', '', true);

					$pdf->SetY(205);
		 			$pdf->SetX(44);
		 			$pdf->MultiCell(100, 5, '(4) เงินสบทบกองทุนประกันสังคม '.'  '.number_format($key->kbk, 2).' บาท', 0, 'L', 0, 1, '', '', true);
	 			}

	 			//============= text footer ================//
	 			$pdf->SetFont('freeserif','',12,'',true);
				
	 			$pdf->SetFont('freeserif','B',12,'',true);
	 			$pdf->SetY(220);
	 			$pdf->SetX(18);
	 			$pdf->MultiCell(177, 5, 'ข้าพเจ้าขอรับรองว่า ข้อความและตัวเลขดังกล่าวข้างต้นนี้ถูกต้องตามความเป็นจริงทุกประการ', 0, 'R', 0, 1, '', '', true);

	 			$pdf->SetFont('freeserif','',12,'',true);
	 			$pdf->SetY(235);
	 			$pdf->SetX(32);
	 			$pdf->MultiCell(170, 5, 'ลงชื่อ...........................................................ผู้มีหน้าที่หักภาษี ณ ที่จ่าย', 0, 'C', 0, 1, '', '', true);	 			
	 			
		    }

			$filename = storage_path() . '/report_tax_continuous_emp2.pdf';
		    // Response::download($filename);
		    $contents = $pdf->output($filename, 'I');
			$headers = array(
			    'Content-Type' => 'application/pdf',
			);
			return Response::make($contents, 200, $headers);
		}
		else
		{
			return View::make('login.index');
		}	
	}

	/*
	* function name itpc_home2
	* ภงด 1 ก  Home 2
	*
	*/
	public function itpc_home2()
	{
		if( Session::get('level') != '' )
		{
			$y = DB::Select( ' select year(order_date) as year1 from s_salary_detail group by year(order_date) order by year(order_date) desc ' );	
			return View::make( 'tax.itpc_home2', array( 'data' => $y ) );
		}
		else
		{
			return View::make('login.index');
		}  
	}
	/*
	* function name tax_itpc_type2
	* ภงด 1 ก
	*
	*/
	public function tax_itpc_type2()
	{
		$y = Input::get('y1');

		if( Session::get('level') != '' )
		{
			$n = DB::select( 'select * from s_general_data' );	
		    foreach ($n as $k) {
		      	$name 		= $k->name;
		      	$address 	= $k->address;
		      	$tax_id2 	= $k->tax_id2;
		      	$director 	= $k->director;
		    } 

			$pdf = new TCPDF();			
			
			$pdf->SetHeaderData('', '', 'ภ.ง.ด 1 ก พิเศษ', 'เลขประจำตัวผู้เสียภาษีอากร(ของผู้มีหน้าที่หักภาษี ณ ที่จ่าย)       '.$tax_id2 );
			
			// set header and footer fonts
			$pdf->setHeaderFont(Array('freeserif', '', 13));
			$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));	  

			$pdf->SetMargins(5, PDF_MARGIN_TOP, 5);
			$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
			$pdf->SetFooterMargin(PDF_MARGIN_FOOTER); 	   		   
		   
		    $sql = ' select concat(n.pname,"",n.fname," ",n.lname) as name, s.cid, s.tax_id,sum(s.salary+s.r_other) as salary , sum(s.r_c) as r_c, sum(s.special_m+s.pts+s.pts2) as special, sum(s.tax) as tax from s_salary_ocsc_detail s left join n_datageneral n on n.cid=s.cid left join n_position_salary p on p.cid=n.cid where  p.`level`="ลูกจ้างประจำ" and year(s.order_date)='.$y.' and s.salary > 0 group by s.cid order by n.datainfoID asc ';		    
		    $result = DB::select( $sql );
			$j=0;
			$i=0;
			$sum1=0;
			$sum2=0;
			$row=0;
		    foreach ( $result as $key ) 
		    {			    
		    	$row++;		    	
		    	if( $j==4 )
	    		{
	    			$j=0;
	    		}

		    	if( $j == 0)
		    	{		    		
		    		$pdf->AddPage('L', 'letter');	
		    					
		 			//===== แนวตั้ง =====//
		 			$linever1 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(5, 60, 5,18, $linever1);

					$pdf->SetFont('freeserif','',11,'',true);
					$pdf->SetY(35);
		 			$pdf->SetX(5);
		 			$pdf->MultiCell(13, 5, 'ลำดับ', 0, 'C', 0, 1, '', '', true);

					//--col 2
					$linever2 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(18, 60, 18,18, $linever2);

					$pdf->SetY(24);
		 			$pdf->SetX(19);
		 			$pdf->MultiCell(60, 5, 'เลขประจำตัวประชาชน(ของผู้มีเงินได้)', 0, 'L', 0, 1, '', '', true);

		 			$pdf->SetY(34);
		 			$pdf->SetX(19);
		 			$pdf->MultiCell(62, 5, 'เลขประจำตัวผู้เสียภาษีอากร(ของผู้มีเงินได้)', 0, 'L', 0, 1, '', '', true);

		 			$pdf->SetY(49);
		 			$pdf->SetX(19);
		 			$pdf->MultiCell(60, 5, 'ชื่อ ที่อยู่ ผู้มีเงินได้', 0, 'L', 0, 1, '', '', true);


					//--col 3
					$linever3 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(80, 60, 80,18, $linever3);

					$pdf->SetY(24);
		 			$pdf->SetX(81);
		 			$pdf->MultiCell(20, 5, 'วัน เดือน ปี ที่เข้าทำงาน(เฉพาะกรณีเข้าทำงานระหว่างปี)', 0, 'L', 0, 1, '', '', true);



					//--col 4
					$linever4 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(105, 60, 105,18, $linever4);
					
					$linever41 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(116, 60, 116,26, $linever41);

					$linever42 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(126, 60, 126,34, $linever42);

					$linever43 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(137, 60, 137,26, $linever43);

					$linever44 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(150, 60, 150,46, $linever44);

					$pdf->SetY(19);
		 			$pdf->SetX(106);
		 			$pdf->MultiCell(58, 5, '(1)รายการลดหย่อน', 0, 'C', 0, 1, '', '', true);

		 			$pdf->SetY(28);
		 			$pdf->SetX(105);
		 			$pdf->MultiCell(11, 5, 'มีสามีภริยาหรือไม่', 0, 'L', 0, 1, '', '', true);

		 			$pdf->SetY(27);
		 			$pdf->SetX(117);
		 			$pdf->MultiCell(20, 5, 'จำนวนบุตร', 0, 'L', 0, 1, '', '', true);

		 			$pdf->SetY(37);
		 			$pdf->SetX(116);
		 			$pdf->MultiCell(20, 5, 'ศึกษา', 0, 'L', 0, 1, '', '', true);

		 			$pdf->SetY(37);
		 			$pdf->SetX(126);
		 			$pdf->MultiCell(11, 5, 'ไม่ศึกษา', 0, 'L', 0, 1, '', '', true);

		 			$pdf->SetY(27);
		 			$pdf->SetX(137);
		 			$pdf->MultiCell(28, 5, 'ค่าลดหย่อนอื่นๆ', 0, 'L', 0, 1, '', '', true);

		 			$pdf->SetY(37);
		 			$pdf->SetX(137);
		 			$pdf->MultiCell(28, 5, 'จำนวนเงิน', 0, 'C', 0, 1, '', '', true);

		 			$pdf->SetY(49);
		 			$pdf->SetX(136);
		 			$pdf->MultiCell(15, 5, 'บาท', 0, 'C', 0, 1, '', '', true);

		 			$pdf->SetY(49);
		 			$pdf->SetX(149);
		 			$pdf->MultiCell(15, 5, 'สต.', 0, 'C', 0, 1, '', '', true);



					//--col 5
					$linever5 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(165, 60, 165,18, $linever5);

					$linever51 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(194, 60, 194,30, $linever51);

					$linever52 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(212, 60, 212,30, $linever52);

					$linever53 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(233, 60, 233,50, $linever53);

					$pdf->SetY(19);
		 			$pdf->SetX(165);
		 			$pdf->MultiCell(77, 5, 'ประเภทเงินได้พึงประเมิณที่จ่าย(รวมทั้งประโยชน์ที่เพิ่ม อย่างอื่น)ถ้ามากกว่าหนึ่งประเภทให้กรอกเรียงลงไป', 0, 'L', 0, 1, '', '', true);

		 			$pdf->SetY(39);
		 			$pdf->SetX(165);
		 			$pdf->MultiCell(30, 5, '(2)', 0, 'C', 0, 1, '', '', true);
		 			$pdf->SetY(43);
		 			$pdf->SetX(165);
		 			$pdf->MultiCell(30, 5, 'ประเภทเงินได้', 0, 'C', 0, 1, '', '', true);

		 			$pdf->SetY(39);
		 			$pdf->SetX(193);
		 			$pdf->MultiCell(20, 5, '(3)', 0, 'C', 0, 1, '', '', true);
		 			$pdf->SetY(43);
		 			$pdf->SetX(193);
		 			$pdf->MultiCell(20, 5, 'จำนวนคราวที่จ่ายทั้งปี', 0, 'C', 0, 1, '', '', true);
		 			
		 			$pdf->SetY(32);
		 			$pdf->SetX(212);
		 			$pdf->MultiCell(30, 5, 'จำนวนเงินที่จ่ายแต่ละประเภทเฉพาะคนหนึงๆ ทั้งปี', 0, 'L', 0, 1, '', '', true);
		 			
		 			$pdf->SetY(52);
		 			$pdf->SetX(216);
		 			$pdf->MultiCell(20, 5, 'บาท', 0, 'L', 0, 1, '', '', true);

		 			$pdf->SetY(52);
		 			$pdf->SetX(233);
		 			$pdf->MultiCell(8, 5, 'สต.', 0, 'C', 0, 1, '', '', true);


					//--col 6
					$linever6 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(242, 60, 242,18, $linever6);	

					$linever61 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(265, 60, 265,50, $linever61);

					$pdf->SetY(19);
		 			$pdf->SetX(242);
		 			$pdf->MultiCell(33, 5, 'รวมเงินภาษีที่หักและนำส่งในปีที่ล่วงมาแล้ว', 0, 'L', 0, 1, '', '', true);

		 			$pdf->SetY(37);
		 			$pdf->SetX(242);
		 			$pdf->MultiCell(33, 5, 'จำนวนเงิน', 0, 'C', 0, 1, '', '', true);

		 			$pdf->SetY(52);
		 			$pdf->SetX(249);
		 			$pdf->MultiCell(20, 5, 'บาท', 0, 'L', 0, 1, '', '', true);

		 			$pdf->SetY(52);
		 			$pdf->SetX(266);
		 			$pdf->MultiCell(8, 5, 'สต.', 0, 'C', 0, 1, '', '', true);


					$lineverEnd = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(274.5, 60, 274.5,18, $lineverEnd);	


					//======= แนวนอน =========//
					$linehor1 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(274.5, 60, 5,60, $linehor1);

					//--col 4
					$linehor2 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(165, 26, 105,26, $linehor2);

					$linehor3 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(165, 34, 116,34, $linehor3);

					$linehor4 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(165, 46, 137,46, $linehor4);

					//--col 5
					$linehor5 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(274.5, 30, 165,30, $linehor5);

					$linehor6 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(274.5, 50, 212,50, $linehor6);					

				}//end add header

				if( $j == 0 ){
					$x1=95;
					$y1=60;
					$h1=95;
					
					$d1=61;
					$d2=68;
					$d3=74;
					$d4=81;
				}else if( $j == 1 ){
					$x1=130;
					$y1=90;
					$h1=130;
					
					$d1=96;
					$d2=103;
					$d3=109;
					$d4=116;
				}else if( $j == 2 ){
					$x1=165;
					$y1=120;
					$h1=165;
					
					$d1=131;
					$d2=138;
					$d3=144;
					$d4=151;	
				}else if( $j == 3 ){
					$x1=197;
					$y1=150;
					$h1=197;
					
					$d1=166;
					$d2=171;
					$d3=176;
					$d4=181;
				}

				//loop นอน
				$linehor = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(274.5, $x1, 5,$x1, $linehor);

				//loop ตั้ง
				$linever_loop1 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(5, $h1, 5,$y1, $linever_loop1);

				$linever_loop2 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(18, $h1, 18,$y1, $linever_loop2);

				$linever_loop3 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(80, $h1, 80,$y1, $linever_loop3);

				$linever_loop4 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(105, $h1, 105,$y1, $linever_loop4);

				$linever_loop5 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(116, $h1, 116,$y1, $linever_loop5);

				$linever_loop6 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(126, $h1, 126,$y1, $linever_loop6);

				$linever_loop7 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(137, $h1, 137,$y1, $linever_loop7);

				$linever_loop8 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(165, $h1, 165,$y1, $linever_loop8);

				$linever_loop9 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(194, $h1, 194,$y1, $linever_loop9);

				$linever_loop10 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(212, $h1, 212,$y1, $linever_loop10);

				$linever_loop11 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(242, $h1, 242,$y1, $linever_loop11);

				$linever_loop12 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(274.5, $h1, 274.5,$y1, $linever_loop12);

				//loop data
				
				//col number
				$pdf->SetFont('freeserif','',11,'',true);
				$pdf->SetY($d1);
	 			$pdf->SetX(5);
	 			$pdf->MultiCell(13, 5, $row, 0, 'C', 0, 0, '', '', true);

				//col 1 data
				$pdf->SetFont('freeserif','B',11,'',true);
				$pdf->SetY($d1);
	 			$pdf->SetX(19);
	 			$pdf->MultiCell(60, 5, $key->cid, 0, 'L', 0, 0, '', '', true);	

	 			$pdf->SetFont('freeserif','',11,'',true);
	 			$pdf->SetY($d2);
	 			$pdf->SetX(19);
	 			$pdf->MultiCell(60, 5, $key->tax_id, 0, 'L', 0, 0, '', '', true);	

	 			$pdf->SetFont('freeserif','B',11,'',true);
	 			$pdf->SetY($d3);
	 			$pdf->SetX(19);
	 			$pdf->MultiCell(60, 5, $key->name, 0, 'L', 0, 0, '', '', true);
				
				$pdf->SetFont('freeserif','',11,'',true);
				$pdf->SetY($d4);
	 			$pdf->SetX(19);
	 			$pdf->MultiCell(60, 5, $address, 0, 'L', 0, 0, '', '', true);	 

	 			//col 8
	 			$pdf->SetFont('freeserif','',11,'',true);
				$pdf->SetY($d1);
	 			$pdf->SetX(166);
	 			$pdf->MultiCell(28, 5, 'เงินเดือน ค่าจ้าง บำนาญ เบี้ยเลี้ยง โบนัส', 0, 'L', 0, 0, '', '', true);	

	 			$pdf->SetFont('freeserif','',11,'',true);
				$pdf->SetY($d1+16);
	 			$pdf->SetX(166);
	 			$pdf->MultiCell(28, 5, 'เงินประจำตำแหน่ง', 0, 'L', 0, 0, '', '', true);

	 			$pdf->SetFont('freeserif','',11,'',true);
				$pdf->SetY($d3+10);
	 			$pdf->SetX(166);
	 			$pdf->MultiCell(24, 5, 'เงินค่าตอบแทน, ค่าครองชีพ', 0, 'L', 0, 0, '', '', true);	

	 			//col 10
	 			$pdf->SetFont('freeserif','',11,'',true);
				$pdf->SetY($d1);
	 			$pdf->SetX(209);
	 			$pdf->MultiCell(30, 5, number_format( $key->salary, 2 ), 0, 'R', 0, 0, '', '', true);

	 			$pdf->SetFont('freeserif','',11,'',true);
				$pdf->SetY($d1+16);
	 			$pdf->SetX(209);
	 			$pdf->MultiCell(30, 5, number_format( $key->r_c, 2 ), 0, 'R', 0, 0, '', '', true);

	 			$pdf->SetFont('freeserif','',11,'',true);
				$pdf->SetY($d3+10);
	 			$pdf->SetX(209);
	 			$pdf->MultiCell(30, 5, number_format( $key->special, 2 ), 0, 'R', 0, 0, '', '', true);	

	 			//col 11		
	 			$pdf->SetFont('freeserif','',11,'',true);
				$pdf->SetY($d1);
	 			$pdf->SetX(241);
	 			$pdf->MultiCell(30, 5, number_format( $key->tax, 2 ), 0, 'R', 0, 0, '', '', true);

	 			$j++;		    	

		    	$sum1 = $sum1+$key->salary+$key->special;
		    	$sum2 = $sum2+$key->tax;

		    } //end foreach			    


			//================================ header > 2 ===================================//	
			//================================ header > 2 ===================================//	
			//================================ header > 2 ===================================//	
			if( count($result) > 0 )
			{		
				if( ($j > 2) )	
				{

			    	$pdf->AddPage('L', 'letter');

			    	//===== แนวตั้ง =====//
			 			$linever1 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(5, 60, 5,18, $linever1);

						$pdf->SetFont('freeserif','',11,'',true);
						$pdf->SetY(35);
			 			$pdf->SetX(5);
			 			$pdf->MultiCell(13, 5, 'ลำดับ', 0, 'C', 0, 1, '', '', true);

						//--col 2
						$linever2 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(18, 60, 18,18, $linever2);

						$pdf->SetY(24);
			 			$pdf->SetX(19);
			 			$pdf->MultiCell(60, 5, 'เลขประจำตัวประชาชน(ของผู้มีเงินได้)', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(34);
			 			$pdf->SetX(19);
			 			$pdf->MultiCell(62, 5, 'เลขประจำตัวผู้เสียภาษีอากร(ของผู้มีเงินได้)', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(49);
			 			$pdf->SetX(19);
			 			$pdf->MultiCell(60, 5, 'ชื่อ ที่อยู่ ผู้มีเงินได้', 0, 'L', 0, 1, '', '', true);


						//--col 3
						$linever3 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(80, 60, 80,18, $linever3);

						$pdf->SetY(24);
			 			$pdf->SetX(81);
			 			$pdf->MultiCell(20, 5, 'วัน เดือน ปี ที่เข้าทำงาน(เฉพาะกรณีเข้าทำงานระหว่างปี)', 0, 'L', 0, 1, '', '', true);



						//--col 4
						$linever4 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(105, 60, 105,18, $linever4);
						
						$linever41 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(116, 60, 116,26, $linever41);

						$linever42 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(126, 60, 126,34, $linever42);

						$linever43 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(137, 60, 137,26, $linever43);

						$linever44 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(150, 60, 150,46, $linever44);

						$pdf->SetY(19);
			 			$pdf->SetX(106);
			 			$pdf->MultiCell(58, 5, '(1)รายการลดหย่อน', 0, 'C', 0, 1, '', '', true);

			 			$pdf->SetY(28);
			 			$pdf->SetX(105);
			 			$pdf->MultiCell(11, 5, 'มีสามีภริยาหรือไม่', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(27);
			 			$pdf->SetX(117);
			 			$pdf->MultiCell(20, 5, 'จำนวนบุตร', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(37);
			 			$pdf->SetX(116);
			 			$pdf->MultiCell(20, 5, 'ศึกษา', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(37);
			 			$pdf->SetX(126);
			 			$pdf->MultiCell(11, 5, 'ไม่ศึกษา', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(27);
			 			$pdf->SetX(137);
			 			$pdf->MultiCell(28, 5, 'ค่าลดหย่อนอื่นๆ', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(37);
			 			$pdf->SetX(137);
			 			$pdf->MultiCell(28, 5, 'จำนวนเงิน', 0, 'C', 0, 1, '', '', true);

			 			$pdf->SetY(49);
			 			$pdf->SetX(136);
			 			$pdf->MultiCell(15, 5, 'บาท', 0, 'C', 0, 1, '', '', true);

			 			$pdf->SetY(49);
			 			$pdf->SetX(149);
			 			$pdf->MultiCell(15, 5, 'สต.', 0, 'C', 0, 1, '', '', true);



						//--col 5
						$linever5 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(165, 60, 165,18, $linever5);

						$linever51 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(194, 60, 194,30, $linever51);

						$linever52 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(212, 60, 212,30, $linever52);

						$linever53 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(233, 60, 233,50, $linever53);

						$pdf->SetY(19);
			 			$pdf->SetX(165);
			 			$pdf->MultiCell(77, 5, 'ประเภทเงินได้พึงประเมิณที่จ่าย(รวมทั้งประโยชน์ที่เพิ่ม อย่างอื่น)ถ้ามากกว่าหนึ่งประเภทให้กรอกเรียงลงไป', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(39);
			 			$pdf->SetX(165);
			 			$pdf->MultiCell(30, 5, '(2)', 0, 'C', 0, 1, '', '', true);
			 			$pdf->SetY(43);
			 			$pdf->SetX(165);
			 			$pdf->MultiCell(30, 5, 'ประเภทเงินได้', 0, 'C', 0, 1, '', '', true);

			 			$pdf->SetY(39);
			 			$pdf->SetX(193);
			 			$pdf->MultiCell(20, 5, '(3)', 0, 'C', 0, 1, '', '', true);
			 			$pdf->SetY(43);
			 			$pdf->SetX(193);
			 			$pdf->MultiCell(20, 5, 'จำนวนคราวที่จ่ายทั้งปี', 0, 'C', 0, 1, '', '', true);
			 			
			 			$pdf->SetY(32);
			 			$pdf->SetX(212);
			 			$pdf->MultiCell(30, 5, 'จำนวนเงินที่จ่ายแต่ละประเภทเฉพาะคนหนึงๆ ทั้งปี', 0, 'L', 0, 1, '', '', true);
			 			
			 			$pdf->SetY(52);
			 			$pdf->SetX(216);
			 			$pdf->MultiCell(20, 5, 'บาท', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(52);
			 			$pdf->SetX(233);
			 			$pdf->MultiCell(8, 5, 'สต.', 0, 'C', 0, 1, '', '', true);


						//--col 6
						$linever6 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(242, 60, 242,18, $linever6);	

						$linever61 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(265, 60, 265,50, $linever61);

						$pdf->SetY(19);
			 			$pdf->SetX(242);
			 			$pdf->MultiCell(33, 5, 'รวมเงินภาษีที่หักและนำส่งในปีที่ล่วงมาแล้ว', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(37);
			 			$pdf->SetX(242);
			 			$pdf->MultiCell(33, 5, 'จำนวนเงิน', 0, 'C', 0, 1, '', '', true);

			 			$pdf->SetY(52);
			 			$pdf->SetX(249);
			 			$pdf->MultiCell(20, 5, 'บาท', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(52);
			 			$pdf->SetX(266);
			 			$pdf->MultiCell(8, 5, 'สต.', 0, 'C', 0, 1, '', '', true);


						$lineverEnd = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(274.5, 60, 274.5,18, $lineverEnd);	


						//======= แนวนอน =========//
						$linehor1 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(274.5, 60, 5,60, $linehor1);

						//--col 4
						$linehor2 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(165, 26, 105,26, $linehor2);

						$linehor3 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(165, 34, 116,34, $linehor3);

						$linehor4 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(165, 46, 137,46, $linehor4);

						//--col 5
						$linehor5 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(274.5, 30, 165,30, $linehor5);

						$linehor6 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(274.5, 50, 212,50, $linehor6);
			    } 
			    //================================ END header > 2 ===================================//	
				//================================ END header > 2 ===================================//	
				//================================ END header > 2 ===================================//	   

			    //แนวตั้ง
			    if( $j > 2)
			    {
			    	$h1 = 40;
			    	$y1 = 70;
			    	$d4 = 45;
			    	$x1 = 60;
			    }

			    $verft1 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(5, $h1+10, 5,$y1, $verft1);

				$verft2 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(212, $h1+10, 212,$y1, $verft2);

				$verft3 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(242, $h1+10, 242,$y1, $verft3);

				$verft4 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(274.5, $h1+10, 274.5,$y1, $verft4);

				$pdf->SetFont('freeserif','B',11,'',true);
				$pdf->SetY($d4+17);
	 			$pdf->SetX(151);
	 			$pdf->MultiCell(50, 5, 'รวมยอดเงินได้และภาษีที่นำส่ง', 0, 'R', 0, 0, '', '', true);

	 			$pdf->SetFont('freeserif','B',11,'',true);
				$pdf->SetY($d4+17);
	 			$pdf->SetX(211);
	 			$pdf->MultiCell(30, 5, number_format($sum1, 2), 0, 'R', 0, 0, '', '', true);

	 			$pdf->SetFont('freeserif','B',11,'',true);
				$pdf->SetY($d4+17);
	 			$pdf->SetX(241);
	 			$pdf->MultiCell(32, 5, number_format($sum2, 2), 0, 'R', 0, 0, '', '', true);

				//แนวนอน
				$horft1 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(274.5, $x1+10, 5,$x1+10, $horft1);

			    //================================= last page footer ======================================//
				$pdf->SetFont('freeserif','B',11,'',true);
				$pdf->SetY(145);
	 			$pdf->SetX(8);
	 			$pdf->MultiCell(30, 5, 'หมายเหตุ', 0, 'C', 0, 0, '', '', true);
				
	 			$pdf->SetFont('freeserif','',10,'',true);
				$pdf->SetY(155);
	 			$pdf->SetX(5);
	 			$pdf->MultiCell(180, 5, '1.ให้ระบุว่า มี หรือ ไม่มี ภริยาโดยใส่เครื่องหมาย / ลงใน หน้าข้อความแต่กรณีพร้อมทั้งกรอกจำนวนบุตรที่มีสิทธิหักลดหย่อนศึกษากี่คน ไม่ศึกษากี่คนและยอดรวมจำนวนเงินค่าลดหย่อนอื่น ๆ ที่จ่ายให้แก่เบี้ยประกันชีวิต เงินสะสมดอกเบี้ยเงินกู้ยืมเพื่อซื้อ เช้าซื้อ หรือสร้างอาคารที่อยู่อาศัย ฯ และเงินสมทบ ฯ', 0, 'L', 0, 0, '', '', true);
				
				$pdf->SetFont('freeserif','',10,'',true);
				$pdf->SetY(175);
	 			$pdf->SetX(5);
	 			$pdf->MultiCell(180, 5, '2.ให้กรอกประเภทเงินได้ที่จ่าย เช่น เงินเดือน ค่าจ้าง เบี้ยเลี้ยง โบนัส บำเหน็จ เงินค่าธรรมเนียม ค่านายหน้า เปบี้ยประชุมค่าภาษีเงินได้ ฯลฯ', 0, 'L', 0, 0, '', '', true);
				
	 			$pdf->SetFont('freeserif','',10,'',true);
				$pdf->SetY(185);
	 			$pdf->SetX(5);
	 			$pdf->MultiCell(180, 5, '3.จำนวนคราวที่จ่ายทั้งปี -จ่ายเป็นรายวัน กรอก 1   -จ่ายเป็นรายสัปดาห์ กรอก 2     -จ่ายเป็นรายปักษ์ กรอก 3     -จ่ายเป็นรายเดือน กรอก 4     -จ่ายเป็นคราวไม่แน่นอน กรอก 5', 0, 'L', 0, 0, '', '', true);			
				
	 			$pdf->SetFont('freeserif','B',10,'',true);
				$pdf->SetY(155);
	 			$pdf->SetX(180);
	 			$pdf->MultiCell(94, 5, 'ลงชื่อ................................................................ผู้จ่ายเงิน', 0, 'C', 0, 0, '', '', true);
				
				$pdf->SetFont('freeserif','B',11,'',true);
				$pdf->SetY(164);
	 			$pdf->SetX(180);
	 			$pdf->MultiCell(94, 5, $director, 0, 'C', 0, 0, '', '', true);
				
				$pdf->SetY(170);
	 			$pdf->SetX(180);
	 			$pdf->MultiCell(94, 5, 'นายแพทย์เชี่ยวชาญ', 0, 'C', 0, 0, '', '', true);

	 			$pdf->SetY(180);
	 			$pdf->SetX(180);
	 			$pdf->MultiCell(94, 5, 'ผู้อำนวยการโรงพยาบาลโนนไทย', 0, 'C', 0, 0, '', '', true);
	 			
				$pdf->SetY(190);
	 			$pdf->SetX(180);
	 			$pdf->MultiCell(94, 5, 'ยื่นวันที่.................................................................', 0, 'C', 0, 0, '', '', true);
		}// check result > 0

			$filename = storage_path() . '/report_tax_itpc_emp2.pdf';
		    // Response::download($filename);
		    $contents = $pdf->output($filename, 'I');
			$headers = array(
			    'Content-Type' => 'application/pdf',
			);
			return Response::make($contents, 200, $headers);
		}
		else
		{
			return View::make('login.index');
		}	
	}



	//======================================= ข้าราชการ ======================================//
	//======================================= ข้าราชการ ======================================//

	/*
	* function name tax_recomend_type3
	* รายละเอียดเงินเดือน พิมพ์หนังสือรับรอง
	*
	*/
	public function tax_recomend_type3()
	{			
		if( Session::get('level') != '' )
		{							
			$data = DB::table( 's_salary_ocsc_detail' )	  
				->leftjoin( 'n_datageneral', 'n_datageneral.cid', '=', 's_salary_ocsc_detail.cid' )	
				->leftjoin( 'n_position_salary', 'n_position_salary.cid', '=', 'n_datageneral.cid' )							
				->where( 'n_position_salary.level', '=', 'ข้าราชการ' )
				->groupBY( 's_salary_ocsc_detail.cid' )	
				->orderBY( 'n_datageneral.datainfoID','asc' )	
				->select( 'n_datageneral.*' )
		        ->paginate( 20 );		        

			return View::make( 'tax.recomend_type3', array( 'data' => $data ) );
		}
		else
		{
			return View::make('login.index');
		}	
	}

	/**
    * function name : search_type3
    * search data tax_recomend_type3
    * post
    */
    public function search_type3()
    {   		  
	   if ( Session::get('level') != '' )
		{
			$search  = Input::get( 'search_tax3' );	
			if( $search != '' )
			{    		    	
				$data = DB::table( 's_salary_ocsc_detail' )	  
				->leftjoin('n_datageneral', 'n_datageneral.cid', '=', 's_salary_ocsc_detail.cid')
				->join( 'n_position_salary', 'n_position_salary.cid', '=', 's_salary_ocsc_detail.cid' )				
				//->where( 'n_datageneral.status', '=', '0' )								
	          	->where( 'n_position_salary.level', '=', 'ข้าราชการ' )	
	          	->where( 'n_datageneral.cid', 'like', "%$search%" )
	         	->orWhere( 'n_datageneral.fname', 'like', "%$search%" )	 
	          	->orWhere( 'n_datageneral.lname', 'like', "%$search%" )	
				->groupBY( 's_salary_ocsc_detail.cid' )	
				->orderBY( 'n_datageneral.datainfoID','asc' )	
				->select( 'n_datageneral.*' )
		        ->paginate( 70 );	
		    } else{
		    	$data = DB::table( 's_salary_ocsc_detail' )	  
				->leftjoin( 'n_datageneral', 'n_datageneral.cid', '=', 's_salary_ocsc_detail.cid' )	
				->leftjoin( 'n_position_salary', 'n_position_salary.cid', '=', 'n_datageneral.cid' )			
				//->where( 'n_datageneral.status', '=', '0' )	
				->where( 'n_position_salary.level', '=', 'ข้าราชการ' )
				->groupBY( 's_salary_ocsc_detail.cid' )	
				->orderBY( 'n_datageneral.datainfoID','asc' )	
				->select( 'n_datageneral.*' )
		        ->paginate( 20 );	
		    }		    
	     
			//view page create
		    return View::make( 'tax.recomend_type3',  array( 'data' => $data ) );	
		}	
		else
		{
			//return login
    		return View::make( 'login.index' );	
		}	
    }

	/**
    * function name : fromstax_type3
    * data to from tax model
    * post
    */
    public function fromstax_type3( $id=null, $year=null )
    {   	
    	if( $year == 'null' )
    	{        			
    		$data = DB::table( 's_salary_ocsc_detail' ) 
				->leftjoin('n_datageneral', 'n_datageneral.cid', '=', 's_salary_ocsc_detail.cid')
				->leftjoin( 'n_position_salary', 'n_position_salary.cid', '=', 'n_datageneral.cid' )				
				//->where( 'n_datageneral.status', '=', '0' )	
				->where( 'n_datageneral.cid', '=', $id )
				->where( 'n_position_salary.level', '=', 'ข้าราชการ' )					
				->where( DB::raw('year(s_salary_ocsc_detail.order_date)'), '=', $this->max_year() )							
				->orderBY( 's_salary_ocsc_detail.order_date','desc' )	
				->select( 'n_datageneral.cid', 'n_datageneral.pname', 'n_datageneral.fname', 'n_datageneral.lname', 's_salary_ocsc_detail.*', DB::raw('month(s_salary_ocsc_detail.order_date) as ordermonth') )
		        ->get();

		    $sumdata = DB::table( 's_salary_ocsc_detail' ) 
				->leftjoin('n_datageneral', 'n_datageneral.cid', '=', 's_salary_ocsc_detail.cid')
				->leftjoin( 'n_position_salary', 'n_position_salary.cid', '=', 'n_datageneral.cid' )				
				//->where( 'n_datageneral.status', '=', '0' )	
				->where( 'n_datageneral.cid', '=', $id )	
				->where( 'n_position_salary.level', '=', 'ข้าราชการ' )
				->where( DB::raw('year(s_salary_ocsc_detail.order_date)'), '=', $this->max_year() )													
				->select( 's_salary_ocsc_detail.cid', db::raw('sum( s_salary_ocsc_detail.salary ) as salary_sum'), db::raw('sum( s_salary_ocsc_detail.r_other ) as r_other_sum'),  db::raw('sum( s_salary_ocsc_detail.special_m ) as salary_special_sum'), db::raw('sum( s_salary_ocsc_detail.tax ) as salary_tax_sum'), db::raw('sum( s_salary_ocsc_detail.r_other ) as salary_rother_sum'), db::raw('sum( s_salary_ocsc_detail.r_pt ) as salary_rpt_sum') )
		        ->first();	

		    $year = DB::select( ' select (year(order_date)+543) as year from s_salary_ocsc_detail group by  year(order_date) ' );    

		    return View::make( 'tax.fromtax3',  array( 'data' => $data, 'sumdata' => $sumdata, 'year' => $year, 'id' => $id ) );	
		}
		else
		{
			$data = DB::table( 's_salary_ocsc_detail' ) 
				->leftjoin('n_datageneral', 'n_datageneral.cid', '=', 's_salary_ocsc_detail.cid')
				->leftjoin( 'n_position_salary', 'n_position_salary.cid', '=', 'n_datageneral.cid' )				
				//->where( 'n_datageneral.status', '=', '0' )	
				->where( 'n_datageneral.cid', '=', $id )
				->where( 'n_position_salary.level', '=', 'ข้าราชการ' )					
				->where( DB::raw('year(s_salary_ocsc_detail.order_date)'), '=', $year )							
				->orderBY( 's_salary_ocsc_detail.order_date','desc' )	
				->select( 'n_datageneral.cid', 'n_datageneral.pname', 'n_datageneral.fname', 'n_datageneral.lname', 's_salary_ocsc_detail.*', DB::raw('month(s_salary_ocsc_detail.order_date) as ordermonth') )
		        ->get();	

		    $sumdata = DB::table( 's_salary_ocsc_detail' ) 
				->leftjoin('n_datageneral', 'n_datageneral.cid', '=', 's_salary_ocsc_detail.cid')
				->leftjoin( 'n_position_salary', 'n_position_salary.cid', '=', 'n_datageneral.cid' )				
				//->where( 'n_datageneral.status', '=', '0' )	
				->where( 'n_datageneral.cid', '=', $id )
				->where( 'n_position_salary.level', '=', 'ข้าราชการ' )	
				->where( DB::raw('year(s_salary_ocsc_detail.order_date)'), '=',$year )													
				->select( 's_salary_ocsc_detail.cid', db::raw('sum( s_salary_ocsc_detail.salary ) as salary_sum'), db::raw('sum( s_salary_ocsc_detail.r_other ) as r_other_sum'), db::raw('sum( s_salary_ocsc_detail.special_m ) as salary_special_sum'), db::raw('sum( s_salary_ocsc_detail.tax ) as salary_tax_sum'), db::raw('sum( s_salary_ocsc_detail.r_other ) as salary_rother_sum'), db::raw('sum( s_salary_ocsc_detail.r_pt ) as salary_rpt_sum') )
		        ->first();	

		    $year = DB::select( ' select (year(order_date)+543) as year from s_salary_ocsc_detail group by  year(order_date) ' );    

		    return View::make( 'tax.fromtax3',  array( 'data' => $data, 'sumdata' => $sumdata, 'year' => $year, 'id' => $id ) );	
		} 	   	
    }

    /**
    * function name : updatetax_type3
    * update data to s_salary_ocsc_detail
    * post
    */
    public function updatetax_type3( $id=null, $date=null, $tax=null, $special=null, $rother=null, $rpt=null )
    {    		   	   
        $user_data = array(            
            'special_m' 	=> $special,
            'r_pt' 	 	=> $rpt, 
            'r_other' 	=> $rother,
            'tax' 	 	=> $tax                   		            	                       
        );         
      
        //update user details
        $result = DB::table( 's_salary_ocsc_detail' )->where( 'cid', '=', $id )->where( 'order_date', '=', $date )->update( $user_data );	        
        if( $result )
        {
        	return 'บันทึกเรียบร้อย'; 
        }
        else
        {
        	return 'ไม่สามารถบันทึกได้'; 
        }
    }

    /*
	* function name continuous_home3
	* หนังสือรับรอง  Home
	*
	*/
	public function continuous_home3()
	{		
		if( Session::get('level') != '' )
		{
			$y = DB::Select( ' select year(order_date) as year1 from s_salary_detail group by year(order_date) order by year(order_date) desc ' );	
			return View::make( 'tax.continuous_home3', array( 'data' => $y ) );
		}
		else
		{
			return View::make('login.index');
		} 
	}
	/*
	* function name tax_continuous_type3
	* พิมพ์หนังสือรับรอง
	*
	*/
	public function tax_continuous_type3( $id=null, $year=null )
	{
		if( Session::get('level') != '' )
		{		
			$y = Input::get('y3');
			if($y != ''){
				$year = $y;
				$id = 'all';
			}			

		    $pdf = new TCPDF();
		    $pdf->SetPrintHeader(false);
		    $pdf->SetPrintFooter(false);			   		   

		    $n = DB::select( 'select * from s_general_data' );	
		    foreach ($n as $k) {
		      	$name 		= $k->name;
		      	$address 	= $k->address;
		      	$address2 	= $k->address2;
		      	$tax_id2 	= $k->tax_id2;
		      	$director 	= $k->director;
		    } 

		    if( $id != 'null' && $year != 'null' )
		    {
				$sql = ' select concat(n.pname,"",n.fname," ",n.lname) as name, s.cid, s.tax_id,sum(s.salary+s.r_other) as salary, sum(s.r_c) as r_c, sum(s.special_m+s.pts+s.pts2) as special, sum(s.tax) as tax from s_salary_ocsc_detail s left join n_datageneral n on n.cid=s.cid left join n_position_salary p on p.cid=n.cid where p.`level`="ข้าราชการ"  and year(s.order_date)='.$year.' and s.cid='.$id.' group by s.cid order by n.datainfoID asc ';		    	    
		    }
		    if( $id != 'null' && $year == 'null' )
		    {		    	
		    	$sql = ' select concat(n.pname,"",n.fname," ",n.lname) as name, s.cid, s.tax_id,sum(s.salary+s.r_other) as salary, sum(s.r_c) as r_c, sum(s.special_m+s.pts+s.pts2) as special, sum(s.tax) as tax from s_salary_ocsc_detail s left join n_datageneral n on n.cid=s.cid left join n_position_salary p on p.cid=n.cid where  p.`level`="ข้าราชการ"  and year(s.order_date)='.$this->max_year().' and s.cid='.$id.' group by s.cid order by n.datainfoID asc ';
		    }
		    if( $id == 'null' && $year == 'null' )
		    {
		    	$sql = ' select concat(n.pname,"",n.fname," ",n.lname) as name, s.cid, s.tax_id,sum(s.salary+s.r_other) as salary, sum(s.r_c) as r_c, sum(s.special_m+s.pts+s.pts2) as special, sum(s.tax) as tax from s_salary_ocsc_detail s left join n_datageneral n on n.cid=s.cid left join n_position_salary p on p.cid=n.cid where  p.`level`="ข้าราชการ"  and year(s.order_date)='.$this->max_year().' group by s.cid order by n.datainfoID asc ';		    		    
		    }
		    if( $id == 'all' && $year != '' )
		    {
				$sql = ' select concat(n.pname,"",n.fname," ",n.lname) as name, s.cid, s.tax_id,sum(s.salary+s.r_other) as salary, sum(s.r_c) as r_c, sum(s.special_m+s.pts+s.pts2) as special, sum(s.tax) as tax from s_salary_ocsc_detail s left join n_datageneral n on n.cid=s.cid left join n_position_salary p on p.cid=n.cid where  p.`level`="ข้าราชการ"  and  year(s.order_date)='.$year.' group by s.cid order by n.datainfoID asc ';		    	    
		    }		  
		    
		    $result = DB::select( $sql );		    

		    foreach ( $result as $key ) {

		    	$pdf->AddPage('P', 'A4');

		    	$pdf->SetFont('freeserif','B',11,'',true);		    
	 			$pdf->MultiCell(185, 5, 'เลขที่ งบ. ........................./ '.( ($year == 'null') ? $this->yearThai() : ($year+543) ), 0, 'R', 0, 1, '', '', true);

	 			$pdf->SetFont('freeserif','B',16,'',true);
		    	$pdf->SetY(25);
	 			$pdf->SetX(18);
	 			$pdf->MultiCell(177, 5, 'หนังสือรับรองการหักภาษี ณ ที่จ่าย', 0, 'C', 0, 1, '', '', true);

	 			$pdf->SetY(34);
	 			$pdf->SetX(18);
	 			$pdf->MultiCell(177, 5, 'ตามมาตรา 50 ทวิ แห่งประมวลรัษฎากร', 0, 'C', 0, 1, '', '', true);
				
	 			//===== แนวตั้ง =====//
	 			$linever1 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(18, 190, 18,50, $linever1);

				$linever2 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(80, 190, 80,50, $linever2);

				$linever3 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(110, 190, 110,50, $linever3);

				$linever4 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(135, 190, 135,50, $linever4);

				$linever5 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(165, 190, 165,50, $linever5);

				$linever6 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(195, 190, 195,50, $linever6);

				//===== แนวนอน =====//
	 			$linetop = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(18, 50, 195,50, $linetop);

				$linetop2 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(80, 63, 195,63, $linetop2);

				$linetop3 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(18, 120, 80,120, $linetop3);

				$linetop4 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(80, 180, 195,180, $linetop4);

				$linetop5 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(18, 190, 195,190, $linetop5);

				//======= text in box 1 ========//
				$pdf->SetFont('freeserif','',13,'',true);
				$pdf->SetY(52);
	 			$pdf->SetX(19);
	 			$pdf->MultiCell(62, 5, 'ชื่อและที่อยู่ของผู้มีหน้าที่หักภาษี ณ ที่จ่าย บุคคลคณะบุคคล นิติบุคคล ส่วนราชการ องค์การ รัฐวิสาหกิจ ฯลฯ ', 0, 'L', 0, 1, '', '', true);

	 			$pdf->SetFont('freeserif','B',13,'',true);
				$pdf->SetY(82);
	 			$pdf->SetX(19);
	 			$pdf->MultiCell(62, 5, $address2, 0, 'L', 0, 1, '', '', true);

	 			$pdf->SetFont('freeserif','B',13,'',true);
				$pdf->SetY(105);
	 			$pdf->SetX(19);
	 			$pdf->MultiCell(40, 5, $tax_id2, 0, 'L', 0, 1, '', '', true);

	 			//======= text in box 2 ========//
	 			$pdf->SetFont('freeserif','',13,'',true);
				$pdf->SetY(122);
	 			$pdf->SetX(19);
	 			$pdf->MultiCell(62, 5, 'ชื่อและที่อยู่ของผู้ถูกหักภาษี ณ ที่จ่าย', 0, 'L', 0, 1, '', '', true);

	 			$pdf->SetFont('freeserif','B',12,'',true);
				$pdf->SetY(137);
	 			$pdf->SetX(21);
	 			$pdf->MultiCell(59, 5, $key->name, 0, 'L', 0, 1, '', '', true);
	
	 			$pdf->SetFont('freeserif','',13,'',true);
				$pdf->SetY(145);
	 			$pdf->SetX(19);
	 			$pdf->MultiCell(62, 5, $address, 0, 'L', 0, 1, '', '', true);

	 			$pdf->SetFont('freeserif','B',13,'',true);
				$pdf->SetY(165);
	 			$pdf->SetX(19);
	 			$pdf->MultiCell(62, 5, 'เลขประจำตัวผู้เสียภาษีของผู้ถูกหักภาษี ณ ที่จ่าย', 0, 'L', 0, 1, '', '', true);

	 			$pdf->SetFont('freeserif','',12,'',true);
				$pdf->SetY(178);
	 			$pdf->SetX(22);
	 			$pdf->MultiCell(62, 5, $key->cid, 0, 'L', 0, 1, '', '', true);

	 			//======= text in box 3 header content ========//
	 			$pdf->SetFont('freeserif','B',13,'',true);
				$pdf->SetY(54);
	 			$pdf->SetX(83);
	 			$pdf->MultiCell(32, 5, 'เงินได้ที่จ่าย', 0, 'L', 0, 1, '', '', true);

	 			$pdf->SetFont('freeserif','B',13,'',true);
				$pdf->SetY(54);
	 			$pdf->SetX(111);
	 			$pdf->MultiCell(32, 5, 'ปีภาษีที่จ่าย', 0, 'L', 0, 1, '', '', true);

	 			$pdf->SetFont('freeserif','B',13,'',true);
				$pdf->SetY(54);
	 			$pdf->SetX(138);
	 			$pdf->MultiCell(32, 5, 'จำนวนเงิน', 0, 'L', 0, 1, '', '', true);

	 			$pdf->SetFont('freeserif','B',13,'',true);
				$pdf->SetY(54);
	 			$pdf->SetX(167);
	 			$pdf->MultiCell(32, 5, 'ภาษีที่หักไว้', 0, 'L', 0, 1, '', '', true);


	 			//============= text in content ================//
	 			$pdf->SetFont('freeserif','',12,'',true);

	 			//-----col 1
				$pdf->SetY(70);
	 			$pdf->SetX(80);
	 			$pdf->MultiCell(30, 5, 'เงินเดือน ค่าจ้าง บำนาญ เบี้ยเลี้ยง โบนัส ตามมาตรา 40(1)', 0, 'L', 0, 1, '', '', true);

				$pdf->SetY(95);
	 			$pdf->SetX(80);
	 			$pdf->MultiCell(31, 5, 'เงินประจำตำแหน่ง', 0, 'L', 0, 1, '', '', true);

	 			$pdf->SetY(104);
	 			$pdf->SetX(80);
	 			$pdf->MultiCell(27, 5, 'เงินค่าตอบแทนพิเศษ พตส ค่าครองชีพ', 0, 'L', 0, 1, '', '', true);

	 			//-----col 2
	 			$pdf->SetY(70);
	 			$pdf->SetX(116);
	 			$pdf->MultiCell(31, 5, ( ($year == 'null') ? $this->yearThai() : ($year+543) ) , 0, 'L', 0, 1, '', '', true);

	 			//-----col 3
	 			$pdf->SetY(70);
	 			$pdf->SetX(135);
	 			$pdf->MultiCell(30, 5, number_format( $key->salary, 2 ), 0, 'R', 0, 1, '', '', true);

	 			$pdf->SetY(95);
	 			$pdf->SetX(135);
	 			$pdf->MultiCell(30, 5, number_format( $key->r_c, 2 ), 0, 'R', 0, 1, '', '', true);

	 			$pdf->SetY(104);
	 			$pdf->SetX(135);
	 			$pdf->MultiCell(30, 5, number_format( $key->special, 2 ), 0, 'R', 0, 1, '', '', true);

	 			//-----col 4
	 			$pdf->SetY(70);
	 			$pdf->SetX(165);
	 			$pdf->MultiCell(30, 5, number_format( $key->tax, 2 ), 0, 'R', 0, 1, '', '', true);
	 			

	 			//============= text in box 4 footer sum ============//

	 			$pdf->SetFont('freeserif','B',13,'',true);
				$pdf->SetY(182);
	 			$pdf->SetX(89);
	 			$pdf->MultiCell(32, 5, 'รวม', 0, 'L', 0, 1, '', '', true);

	 			$pdf->SetFont('freeserif','',12,'',true);
	 			$pdf->SetY(182);
	 			$pdf->SetX(135);
	 			$pdf->MultiCell(30, 5, number_format( ($key->salary)+($key->special)+($key->r_c), 2 ), 0, 'R', 0, 1, '', '', true);

	 			$pdf->SetFont('freeserif','',12,'',true);
	 			$pdf->SetY(182);
	 			$pdf->SetX(165);
	 			$pdf->MultiCell(30, 5, number_format( $key->tax, 2 ), 0, 'R', 0, 1, '', '', true);


	 			//============= text footer ================//
	 			$pdf->SetFont('freeserif','',12,'',true);
				
	 			$pdf->SetFont('freeserif','B',12,'',true);
	 			$pdf->SetY(220);
	 			$pdf->SetX(18);
	 			$pdf->MultiCell(177, 5, 'ข้าพเจ้าขอรับรองว่า ข้อความและตัวเลขดังกล่าวข้างต้นนี้ถูกต้องตามความเป็นจริงทุกประการ', 0, 'R', 0, 1, '', '', true);

	 			$pdf->SetFont('freeserif','',12,'',true);
	 			$pdf->SetY(235);
	 			$pdf->SetX(32);
	 			$pdf->MultiCell(170, 5, 'ลงชื่อ...........................................................ผู้มีหน้าที่หักภาษี ณ ที่จ่าย', 0, 'C', 0, 1, '', '', true);	 

	 			$pdf->SetY(245);
	 			$pdf->SetX(32);
	 			$pdf->MultiCell(140, 5, $director, 0, 'C', 0, 1, '', '', true);

	 			$pdf->SetY(255);
	 			$pdf->SetX(32);
	 			$pdf->MultiCell(140, 5, 'นายแพทย์เชี่ยวชาญ', 0, 'C', 0, 1, '', '', true);

	 			$pdf->SetY(265);
	 			$pdf->SetX(32);
	 			$pdf->MultiCell(140, 5, 'ผู้อำนวยการโรงพยาบาลโนนไทย', 0, 'C', 0, 1, '', '', true);			
	 			
		    }

			$filename = storage_path() . '/report_tax_continuous_emp2.pdf';
		    // Response::download($filename);
		    $contents = $pdf->output($filename, 'I');
			$headers = array(
			    'Content-Type' => 'application/pdf',
			);
			return Response::make($contents, 200, $headers);
		}
		else
		{
			return View::make('login.index');
		}	
	}

	//---------- continuous_home3_pts ---------//
	/*
	* function name continuous_home3_pts
	* หนังสือรับรอง  Home
	*
	*/
	public function continuous_home3_pts()
	{		
		if( Session::get('level') != '' )
		{
			$y = DB::Select( ' select year(order_date) as year1 from s_salary_detail group by year(order_date) order by year(order_date) desc ' );	
			return View::make( 'tax.continuous_home3_pts', array( 'data' => $y ) );
		}
		else
		{
			return View::make('login.index');
		} 
	}
	/*
	* function name tax_continuous_type3_pts
	* พิมพ์หนังสือรับรอง
	*
	*/
	public function tax_continuous_type3_pts( $id=null, $year=null )
	{
		if( Session::get('level') != '' )
		{		
			$y = Input::get('y3');
			if($y != ''){
				$year = $y;
				$id = 'all';
			}			

		    $pdf = new TCPDF();
		    $pdf->SetPrintHeader(false);
		    $pdf->SetPrintFooter(false);			   		   

		    $n = DB::select( 'select * from s_general_data' );	
		    foreach ($n as $k) {
		      	$name 		= $k->name;
		      	$address 	= $k->address;
		      	$address2 	= $k->address2;
		      	$tax_id2 	= $k->tax_id2;
		      	$director 	= $k->director;
		    } 

		    if( $id != 'null' && $year != 'null' )
		    {
				$sql = ' select concat(n.pname,"",n.fname," ",n.lname) as name, s.cid, s.tax_id,sum(s.salary+s.r_other) as salary, sum(s.r_c) as r_c, sum(s.pts2+s.ch112) as special, sum(s.tax) as tax from s_salary_ocsc_detail s left join n_datageneral n on n.cid=s.cid left join n_position_salary p on p.cid=n.cid where p.`level` in ("ข้าราชการ","ลูกจ้างประจำ")  and year(s.order_date)='.$year.' and s.cid='.$id.' group by s.cid order by n.datainfoID asc ';		    	    
		    }
		    if( $id != 'null' && $year == 'null' )
		    {		    	
		    	$sql = ' select concat(n.pname,"",n.fname," ",n.lname) as name, s.cid, s.tax_id,sum(s.salary+s.r_other) as salary, sum(s.r_c) as r_c, sum(s.pts2+s.ch112) as special, sum(s.tax) as tax from s_salary_ocsc_detail s left join n_datageneral n on n.cid=s.cid left join n_position_salary p on p.cid=n.cid where  p.`level` in ("ข้าราชการ","ลูกจ้างประจำ") and year(s.order_date)='.$this->max_year().' and s.cid='.$id.' group by s.cid order by n.datainfoID asc ';
		    }
		    if( $id == 'null' && $year == 'null' )
		    {
		    	$sql = ' select concat(n.pname,"",n.fname," ",n.lname) as name, s.cid, s.tax_id,sum(s.salary+s.r_other) as salary, sum(s.r_c) as r_c, sum(s.pts2+s.ch112) as special, sum(s.tax) as tax from s_salary_ocsc_detail s left join n_datageneral n on n.cid=s.cid left join n_position_salary p on p.cid=n.cid where  p.`level` in ("ข้าราชการ","ลูกจ้างประจำ")  and year(s.order_date)='.$this->max_year().' group by s.cid order by n.datainfoID asc ';		    		    
		    }
		    if( $id == 'all' && $year != '' )
		    {
				$sql = ' select concat(n.pname,"",n.fname," ",n.lname) as name, s.cid, s.tax_id,sum(s.salary+s.r_other) as salary, sum(s.r_c) as r_c, sum(s.pts2+s.ch112) as special, sum(s.tax) as tax from s_salary_ocsc_detail s left join n_datageneral n on n.cid=s.cid left join n_position_salary p on p.cid=n.cid where  p.`level` in ("ข้าราชการ","ลูกจ้างประจำ") and  year(s.order_date)='.$year.'  group by s.cid order by n.datainfoID asc ';		    	    
		    }		  
		    
		    $result = DB::select( $sql );		    

		    foreach ( $result as $key ) {

		    	if( $key->special > 0 ){

			    	$pdf->AddPage('P', 'A4');

			    	$pdf->SetFont('freeserif','B',11,'',true);		    
		 			$pdf->MultiCell(185, 5, 'เลขที่ งป. ........................./ '.( ($year == 'null') ? $this->yearThai() : ($year+543) ), 0, 'R', 0, 1, '', '', true);

		 			$pdf->SetFont('freeserif','B',16,'',true);
			    	$pdf->SetY(25);
		 			$pdf->SetX(18);
		 			$pdf->MultiCell(177, 5, 'หนังสือรับรองการหักภาษี ณ ที่จ่าย', 0, 'C', 0, 1, '', '', true);

		 			$pdf->SetY(34);
		 			$pdf->SetX(18);
		 			$pdf->MultiCell(177, 5, 'ตามมาตรา 50 ทวิ แห่งประมวลรัษฎากร', 0, 'C', 0, 1, '', '', true);
					
		 			//===== แนวตั้ง =====//
		 			$linever1 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(18, 190, 18,50, $linever1);

					$linever2 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(80, 190, 80,50, $linever2);

					$linever3 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(110, 190, 110,50, $linever3);

					$linever4 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(135, 190, 135,50, $linever4);

					$linever5 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(165, 190, 165,50, $linever5);

					$linever6 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(195, 190, 195,50, $linever6);

					//===== แนวนอน =====//
		 			$linetop = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(18, 50, 195,50, $linetop);

					$linetop2 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(80, 63, 195,63, $linetop2);

					$linetop3 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(18, 120, 80,120, $linetop3);

					$linetop4 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(80, 180, 195,180, $linetop4);

					$linetop5 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(18, 190, 195,190, $linetop5);

					//======= text in box 1 ========//
					$pdf->SetFont('freeserif','',13,'',true);
					$pdf->SetY(52);
		 			$pdf->SetX(19);
		 			$pdf->MultiCell(62, 5, 'ชื่อและที่อยู่ของผู้มีหน้าที่หักภาษี ณ ที่จ่าย บุคคลคณะบุคคล นิติบุคคล ส่วนราชการ องค์การ รัฐวิสาหกิจ ฯลฯ ', 0, 'L', 0, 1, '', '', true);

		 			$pdf->SetFont('freeserif','B',13,'',true);
					$pdf->SetY(82);
		 			$pdf->SetX(19);
		 			$pdf->MultiCell(62, 5, $address2, 0, 'L', 0, 1, '', '', true);

		 			$pdf->SetFont('freeserif','B',13,'',true);
					$pdf->SetY(105);
		 			$pdf->SetX(19);
		 			$pdf->MultiCell(40, 5, $tax_id2, 0, 'L', 0, 1, '', '', true);

		 			//======= text in box 2 ========//
		 			$pdf->SetFont('freeserif','',13,'',true);
					$pdf->SetY(122);
		 			$pdf->SetX(19);
		 			$pdf->MultiCell(62, 5, 'ชื่อและที่อยู่ของผู้ถูกหักภาษี ณ ที่จ่าย', 0, 'L', 0, 1, '', '', true);

		 			$pdf->SetFont('freeserif','B',12,'',true);
					$pdf->SetY(137);
		 			$pdf->SetX(21);
		 			$pdf->MultiCell(59, 5, $key->name, 0, 'L', 0, 1, '', '', true);
		
		 			$pdf->SetFont('freeserif','',13,'',true);
					$pdf->SetY(145);
		 			$pdf->SetX(19);
		 			$pdf->MultiCell(62, 5, $address, 0, 'L', 0, 1, '', '', true);

		 			$pdf->SetFont('freeserif','B',13,'',true);
					$pdf->SetY(165);
		 			$pdf->SetX(19);
		 			$pdf->MultiCell(62, 5, 'เลขประจำตัวผู้เสียภาษีของผู้ถูกหักภาษี ณ ที่จ่าย', 0, 'L', 0, 1, '', '', true);

		 			$pdf->SetFont('freeserif','',12,'',true);
					$pdf->SetY(178);
		 			$pdf->SetX(22);
		 			$pdf->MultiCell(62, 5, $key->cid, 0, 'L', 0, 1, '', '', true);

		 			//======= text in box 3 header content ========//
		 			$pdf->SetFont('freeserif','B',13,'',true);
					$pdf->SetY(54);
		 			$pdf->SetX(83);
		 			$pdf->MultiCell(32, 5, 'เงินได้ที่จ่าย', 0, 'L', 0, 1, '', '', true);

		 			$pdf->SetFont('freeserif','B',13,'',true);
					$pdf->SetY(54);
		 			$pdf->SetX(111);
		 			$pdf->MultiCell(32, 5, 'ปีภาษีที่จ่าย', 0, 'L', 0, 1, '', '', true);

		 			$pdf->SetFont('freeserif','B',13,'',true);
					$pdf->SetY(54);
		 			$pdf->SetX(138);
		 			$pdf->MultiCell(32, 5, 'จำนวนเงิน', 0, 'L', 0, 1, '', '', true);

		 			$pdf->SetFont('freeserif','B',13,'',true);
					$pdf->SetY(54);
		 			$pdf->SetX(167);
		 			$pdf->MultiCell(32, 5, 'ภาษีที่หักไว้', 0, 'L', 0, 1, '', '', true);


		 			//============= text in content ================//
		 			$pdf->SetFont('freeserif','',12,'',true);

		 			//-----col 1
					$pdf->SetY(70);
		 			$pdf->SetX(80);
		 			$pdf->MultiCell(30, 5, 'เงินเดือน ค่าจ้าง บำนาญ เบี้ยเลี้ยง โบนัส ตามมาตรา 40(1)', 0, 'L', 0, 1, '', '', true);

					$pdf->SetY(95);
		 			$pdf->SetX(80);
		 			$pdf->MultiCell(31, 5, 'เงินประจำตำแหน่ง', 0, 'L', 0, 1, '', '', true);

		 			$pdf->SetY(104);
		 			$pdf->SetX(80);
		 			$pdf->MultiCell(27, 5, 'เงินค่าตอบแทนพิเศษ พตส ค่าครองชีพ', 0, 'L', 0, 1, '', '', true);

		 			//-----col 2
		 			$pdf->SetY(70);
		 			$pdf->SetX(116);
		 			$pdf->MultiCell(31, 5, ( ($year == 'null') ? $this->yearThai() : ($year+543) ) , 0, 'L', 0, 1, '', '', true);

		 			//-----col 3
		 			$pdf->SetY(70);
		 			$pdf->SetX(135);
		 			$pdf->MultiCell(30, 5, '', 0, 'R', 0, 1, '', '', true);//salary

		 			$pdf->SetY(95);
		 			$pdf->SetX(135);
		 			$pdf->MultiCell(30, 5, '', 0, 'R', 0, 1, '', '', true);//r_c

		 			$pdf->SetY(104);
		 			$pdf->SetX(135);
		 			$pdf->MultiCell(30, 5, number_format( $key->special, 2 ), 0, 'R', 0, 1, '', '', true);

		 			//-----col 4
		 			$pdf->SetY(70);
		 			$pdf->SetX(165);
		 			$pdf->MultiCell(30, 5, '', 0, 'R', 0, 1, '', '', true);//tax
		 			

		 			//============= text in box 4 footer sum ============//

		 			$pdf->SetFont('freeserif','B',13,'',true);
					$pdf->SetY(182);
		 			$pdf->SetX(89);
		 			$pdf->MultiCell(32, 5, 'รวม', 0, 'L', 0, 1, '', '', true);

		 			$pdf->SetFont('freeserif','',12,'',true);
		 			$pdf->SetY(182);
		 			$pdf->SetX(135);
		 			$pdf->MultiCell(30, 5, number_format(($key->special), 2 ), 0, 'R', 0, 1, '', '', true);

		 			$pdf->SetFont('freeserif','',12,'',true);
		 			$pdf->SetY(182);
		 			$pdf->SetX(165);
		 			$pdf->MultiCell(30, 5, '', 0, 'R', 0, 1, '', '', true);//tax


		 			//============= text footer ================//
		 			$pdf->SetFont('freeserif','',12,'',true);
					
		 			$pdf->SetFont('freeserif','B',12,'',true);
		 			$pdf->SetY(220);
		 			$pdf->SetX(18);
		 			$pdf->MultiCell(177, 5, 'ข้าพเจ้าขอรับรองว่า ข้อความและตัวเลขดังกล่าวข้างต้นนี้ถูกต้องตามความเป็นจริงทุกประการ', 0, 'R', 0, 1, '', '', true);

		 			$pdf->SetFont('freeserif','',12,'',true);
		 			$pdf->SetY(235);
		 			$pdf->SetX(32);
		 			$pdf->MultiCell(170, 5, 'ลงชื่อ...........................................................ผู้มีหน้าที่หักภาษี ณ ที่จ่าย', 0, 'C', 0, 1, '', '', true);	 

		 			$pdf->SetY(245);
		 			$pdf->SetX(32);
		 			$pdf->MultiCell(140, 5, '('.$director.')', 0, 'C', 0, 1, '', '', true);

		 			$pdf->SetY(255);
		 			$pdf->SetX(32);
		 			$pdf->MultiCell(140, 5, 'นายแพทย์เชี่ยวชาญ', 0, 'C', 0, 1, '', '', true);

		 			$pdf->SetY(265);
		 			$pdf->SetX(32);
		 			$pdf->MultiCell(140, 5, 'ผู้อำนวยการโรงพยาบาลโนนไทย', 0, 'C', 0, 1, '', '', true);		

	 			}//end if check pts2 > 0	
	 			
		    }//end foreach data

			$filename = storage_path() . '/report_tax_continuous_emp2_pts.pdf';
		    // Response::download($filename);
		    $contents = $pdf->output($filename, 'I');
			$headers = array(
			    'Content-Type' => 'application/pdf',
			);
			return Response::make($contents, 200, $headers);
		}
		else
		{
			return View::make('login.index');
		}	
	}

	/*
	* function name itpc_home3
	* ภงด 1 ก  Home 3
	*
	*/
	public function itpc_home3()
	{
		if( Session::get('level') != '' )
		{
			$y = DB::Select( ' select year(order_date) as year1 from s_salary_detail group by year(order_date) order by year(order_date) desc ' );	
			return View::make( 'tax.itpc_home3', array( 'data' => $y ) );
		}
		else
		{
			return View::make('login.index');
		}  
	}
	/*
	* function name tax_itpc_type3
	* ภงด 1 ก
	*
	*/
	public function tax_itpc_type3()
	{
		$y = Input::get('y1');

		if( Session::get('level') != '' )
		{
			$n = DB::select( 'select * from s_general_data' );	
		    foreach ($n as $k) {
		      	$name 		= $k->name;
		      	$address 	= $k->address;
		      	$tax_id2 	= $k->tax_id2;
		      	$director 	= $k->director;
		    } 

			$pdf = new TCPDF();			
			
			$pdf->SetHeaderData('', '', 'ภ.ง.ด 1 ก พิเศษ', 'เลขประจำตัวผู้เสียภาษีอากร(ของผู้มีหน้าที่หักภาษี ณ ที่จ่าย)       '.$tax_id2 );
			
			// set header and footer fonts
			$pdf->setHeaderFont(Array('freeserif', '', 13));
			$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));	  

			$pdf->SetMargins(5, PDF_MARGIN_TOP, 5);
			$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
			$pdf->SetFooterMargin(PDF_MARGIN_FOOTER); 	   		   
		   
		    $sql = ' select concat(n.pname,"",n.fname," ",n.lname) as name, s.cid, s.tax_id,sum(s.salary+s.r_other) as salary , sum(s.r_c) as r_c, sum(s.special_m+s.pts+s.pts2) as special, sum(s.tax) as tax from s_salary_ocsc_detail s left join n_datageneral n on n.cid=s.cid left join n_position_salary p on p.cid=n.cid where  p.`level`="ข้าราชการ" and year(s.order_date)='.$y.' group by s.cid order by n.datainfoID asc ';		    
		    $result = DB::select( $sql );
			$j=0;
			$i=0;
			$sum1=0;
			$sum2=0;
			$row=0;
		    foreach ( $result as $key ) 
		    {			    
		    	$row++;		    	
		    	if( $j==4 )
	    		{
	    			$j=0;
	    		}

		    	if( $j == 0)
		    	{		    		
		    		$pdf->AddPage('L', 'letter');	
		    					
		 			//===== แนวตั้ง =====//
		 			$linever1 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(5, 60, 5,18, $linever1);

					$pdf->SetFont('freeserif','',11,'',true);
					$pdf->SetY(35);
		 			$pdf->SetX(5);
		 			$pdf->MultiCell(13, 5, 'ลำดับ', 0, 'C', 0, 1, '', '', true);

					//--col 2
					$linever2 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(18, 60, 18,18, $linever2);

					$pdf->SetY(24);
		 			$pdf->SetX(19);
		 			$pdf->MultiCell(60, 5, 'เลขประจำตัวประชาชน(ของผู้มีเงินได้)', 0, 'L', 0, 1, '', '', true);

		 			$pdf->SetY(34);
		 			$pdf->SetX(19);
		 			$pdf->MultiCell(62, 5, 'เลขประจำตัวผู้เสียภาษีอากร(ของผู้มีเงินได้)', 0, 'L', 0, 1, '', '', true);

		 			$pdf->SetY(49);
		 			$pdf->SetX(19);
		 			$pdf->MultiCell(60, 5, 'ชื่อ ที่อยู่ ผู้มีเงินได้', 0, 'L', 0, 1, '', '', true);


					//--col 3
					$linever3 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(80, 60, 80,18, $linever3);

					$pdf->SetY(24);
		 			$pdf->SetX(81);
		 			$pdf->MultiCell(20, 5, 'วัน เดือน ปี ที่เข้าทำงาน(เฉพาะกรณีเข้าทำงานระหว่างปี)', 0, 'L', 0, 1, '', '', true);



					//--col 4
					$linever4 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(105, 60, 105,18, $linever4);
					
					$linever41 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(116, 60, 116,26, $linever41);

					$linever42 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(126, 60, 126,34, $linever42);

					$linever43 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(137, 60, 137,26, $linever43);

					$linever44 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(150, 60, 150,46, $linever44);

					$pdf->SetY(19);
		 			$pdf->SetX(106);
		 			$pdf->MultiCell(58, 5, '(1)รายการลดหย่อน', 0, 'C', 0, 1, '', '', true);

		 			$pdf->SetY(28);
		 			$pdf->SetX(105);
		 			$pdf->MultiCell(11, 5, 'มีสามีภริยาหรือไม่', 0, 'L', 0, 1, '', '', true);

		 			$pdf->SetY(27);
		 			$pdf->SetX(117);
		 			$pdf->MultiCell(20, 5, 'จำนวนบุตร', 0, 'L', 0, 1, '', '', true);

		 			$pdf->SetY(37);
		 			$pdf->SetX(116);
		 			$pdf->MultiCell(20, 5, 'ศึกษา', 0, 'L', 0, 1, '', '', true);

		 			$pdf->SetY(37);
		 			$pdf->SetX(126);
		 			$pdf->MultiCell(11, 5, 'ไม่ศึกษา', 0, 'L', 0, 1, '', '', true);

		 			$pdf->SetY(27);
		 			$pdf->SetX(137);
		 			$pdf->MultiCell(28, 5, 'ค่าลดหย่อนอื่นๆ', 0, 'L', 0, 1, '', '', true);

		 			$pdf->SetY(37);
		 			$pdf->SetX(137);
		 			$pdf->MultiCell(28, 5, 'จำนวนเงิน', 0, 'C', 0, 1, '', '', true);

		 			$pdf->SetY(49);
		 			$pdf->SetX(136);
		 			$pdf->MultiCell(15, 5, 'บาท', 0, 'C', 0, 1, '', '', true);

		 			$pdf->SetY(49);
		 			$pdf->SetX(149);
		 			$pdf->MultiCell(15, 5, 'สต.', 0, 'C', 0, 1, '', '', true);



					//--col 5
					$linever5 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(165, 60, 165,18, $linever5);

					$linever51 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(194, 60, 194,30, $linever51);

					$linever52 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(212, 60, 212,30, $linever52);

					$linever53 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(233, 60, 233,50, $linever53);

					$pdf->SetY(19);
		 			$pdf->SetX(165);
		 			$pdf->MultiCell(77, 5, 'ประเภทเงินได้พึงประเมิณที่จ่าย(รวมทั้งประโยชน์ที่เพิ่ม อย่างอื่น)ถ้ามากกว่าหนึ่งประเภทให้กรอกเรียงลงไป', 0, 'L', 0, 1, '', '', true);

		 			$pdf->SetY(39);
		 			$pdf->SetX(165);
		 			$pdf->MultiCell(30, 5, '(2)', 0, 'C', 0, 1, '', '', true);
		 			$pdf->SetY(43);
		 			$pdf->SetX(165);
		 			$pdf->MultiCell(30, 5, 'ประเภทเงินได้', 0, 'C', 0, 1, '', '', true);

		 			$pdf->SetY(39);
		 			$pdf->SetX(193);
		 			$pdf->MultiCell(20, 5, '(3)', 0, 'C', 0, 1, '', '', true);
		 			$pdf->SetY(43);
		 			$pdf->SetX(193);
		 			$pdf->MultiCell(20, 5, 'จำนวนคราวที่จ่ายทั้งปี', 0, 'C', 0, 1, '', '', true);
		 			
		 			$pdf->SetY(32);
		 			$pdf->SetX(212);
		 			$pdf->MultiCell(30, 5, 'จำนวนเงินที่จ่ายแต่ละประเภทเฉพาะคนหนึงๆ ทั้งปี', 0, 'L', 0, 1, '', '', true);
		 			
		 			$pdf->SetY(52);
		 			$pdf->SetX(216);
		 			$pdf->MultiCell(20, 5, 'บาท', 0, 'L', 0, 1, '', '', true);

		 			$pdf->SetY(52);
		 			$pdf->SetX(233);
		 			$pdf->MultiCell(8, 5, 'สต.', 0, 'C', 0, 1, '', '', true);


					//--col 6
					$linever6 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(242, 60, 242,18, $linever6);	

					$linever61 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(265, 60, 265,50, $linever61);

					$pdf->SetY(19);
		 			$pdf->SetX(242);
		 			$pdf->MultiCell(33, 5, 'รวมเงินภาษีที่หักและนำส่งในปีที่ล่วงมาแล้ว', 0, 'L', 0, 1, '', '', true);

		 			$pdf->SetY(37);
		 			$pdf->SetX(242);
		 			$pdf->MultiCell(33, 5, 'จำนวนเงิน', 0, 'C', 0, 1, '', '', true);

		 			$pdf->SetY(52);
		 			$pdf->SetX(249);
		 			$pdf->MultiCell(20, 5, 'บาท', 0, 'L', 0, 1, '', '', true);

		 			$pdf->SetY(52);
		 			$pdf->SetX(266);
		 			$pdf->MultiCell(8, 5, 'สต.', 0, 'C', 0, 1, '', '', true);


					$lineverEnd = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(274.5, 60, 274.5,18, $lineverEnd);	


					//======= แนวนอน =========//
					$linehor1 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(274.5, 60, 5,60, $linehor1);

					//--col 4
					$linehor2 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(165, 26, 105,26, $linehor2);

					$linehor3 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(165, 34, 116,34, $linehor3);

					$linehor4 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(165, 46, 137,46, $linehor4);

					//--col 5
					$linehor5 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(274.5, 30, 165,30, $linehor5);

					$linehor6 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(274.5, 50, 212,50, $linehor6);					

				}//end add header

				if( $j == 0 ){
					$x1=95;
					$y1=60;
					$h1=95;
					
					$d1=61;
					$d2=68;
					$d3=74;
					$d4=81;
				}else if( $j == 1 ){
					$x1=130;
					$y1=90;
					$h1=130;
					
					$d1=96;
					$d2=103;
					$d3=109;
					$d4=116;
				}else if( $j == 2 ){
					$x1=165;
					$y1=120;
					$h1=165;
					
					$d1=131;
					$d2=138;
					$d3=144;
					$d4=151;	
				}else if( $j == 3 ){
					$x1=197;
					$y1=150;
					$h1=197;
					
					$d1=166;
					$d2=171;
					$d3=176;
					$d4=181;
				}

				//loop นอน
				$linehor = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(274.5, $x1, 5,$x1, $linehor);

				//loop ตั้ง
				$linever_loop1 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(5, $h1, 5,$y1, $linever_loop1);

				$linever_loop2 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(18, $h1, 18,$y1, $linever_loop2);

				$linever_loop3 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(80, $h1, 80,$y1, $linever_loop3);

				$linever_loop4 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(105, $h1, 105,$y1, $linever_loop4);

				$linever_loop5 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(116, $h1, 116,$y1, $linever_loop5);

				$linever_loop6 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(126, $h1, 126,$y1, $linever_loop6);

				$linever_loop7 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(137, $h1, 137,$y1, $linever_loop7);

				$linever_loop8 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(165, $h1, 165,$y1, $linever_loop8);

				$linever_loop9 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(194, $h1, 194,$y1, $linever_loop9);

				$linever_loop10 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(212, $h1, 212,$y1, $linever_loop10);

				$linever_loop11 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(242, $h1, 242,$y1, $linever_loop11);

				$linever_loop12 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(274.5, $h1, 274.5,$y1, $linever_loop12);

				//loop data
				
				//col number
				$pdf->SetFont('freeserif','',11,'',true);
				$pdf->SetY($d1);
	 			$pdf->SetX(5);
	 			$pdf->MultiCell(13, 5, $row, 0, 'C', 0, 0, '', '', true);
				
				//col 1 data
				$pdf->SetFont('freeserif','B',11,'',true);
				$pdf->SetY($d1);
	 			$pdf->SetX(19);
	 			$pdf->MultiCell(60, 5, $key->cid, 0, 'L', 0, 0, '', '', true);	

	 			$pdf->SetFont('freeserif','',11,'',true);
	 			$pdf->SetY($d2);
	 			$pdf->SetX(19);
	 			$pdf->MultiCell(60, 5, $key->tax_id, 0, 'L', 0, 0, '', '', true);	

	 			$pdf->SetFont('freeserif','B',11,'',true);
	 			$pdf->SetY($d3);
	 			$pdf->SetX(19);
	 			$pdf->MultiCell(60, 5, $key->name, 0, 'L', 0, 0, '', '', true);
				
				$pdf->SetFont('freeserif','',11,'',true);
				$pdf->SetY($d4);
	 			$pdf->SetX(19);
	 			$pdf->MultiCell(60, 5, $address, 0, 'L', 0, 0, '', '', true);	 

	 			//col 8
	 			$pdf->SetFont('freeserif','',11,'',true);
				$pdf->SetY($d1);
	 			$pdf->SetX(166);
	 			$pdf->MultiCell(28, 5, 'เงินเดือน ค่าจ้าง บำนาญ เบี้ยเลี้ยง โบนัส', 0, 'L', 0, 0, '', '', true);	

	 			$pdf->SetFont('freeserif','',11,'',true);
				$pdf->SetY($d1+16);
	 			$pdf->SetX(166);
	 			$pdf->MultiCell(28, 5, 'เงินประจำตำแหน่ง', 0, 'L', 0, 0, '', '', true);

	 			$pdf->SetFont('freeserif','',11,'',true);
				$pdf->SetY($d3+10);
	 			$pdf->SetX(166);
	 			$pdf->MultiCell(28, 5, 'เงินค่าตอบแทน พตส ค่าครองชีพ', 0, 'L', 0, 0, '', '', true);	

	 			//col 10
	 			$pdf->SetFont('freeserif','',11,'',true);
				$pdf->SetY($d1);
	 			$pdf->SetX(209);
	 			$pdf->MultiCell(30, 5, number_format( $key->salary, 2 ), 0, 'R', 0, 0, '', '', true);

	 			$pdf->SetFont('freeserif','',11,'',true);
				$pdf->SetY($d1+16);
	 			$pdf->SetX(209);
	 			$pdf->MultiCell(30, 5, number_format( $key->r_c, 2 ), 0, 'R', 0, 0, '', '', true);

	 			$pdf->SetFont('freeserif','',11,'',true);
				$pdf->SetY($d3+10);
	 			$pdf->SetX(209);
	 			$pdf->MultiCell(30, 5, number_format( $key->special, 2 ), 0, 'R', 0, 0, '', '', true);	

	 			//col 11		
	 			$pdf->SetFont('freeserif','',11,'',true);
				$pdf->SetY($d1);
	 			$pdf->SetX(241);
	 			$pdf->MultiCell(30, 5, number_format( $key->tax, 2 ), 0, 'R', 0, 0, '', '', true);

	 			$j++;		    	

		    	$sum1 = $sum1+$key->salary+$key->special;
		    	$sum2 = $sum2+$key->tax;

		    } //end foreach			    


			//================================ header > 2 ===================================//	
			//================================ header > 2 ===================================//	
			//================================ header > 2 ===================================//	
			if( count($result) > 0 )
			{		
				if( ($j > 2) )	
				{

			    	$pdf->AddPage('L', 'letter');

			    	//===== แนวตั้ง =====//
			 			$linever1 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(5, 60, 5,18, $linever1);

						$pdf->SetFont('freeserif','',11,'',true);
						$pdf->SetY(35);
			 			$pdf->SetX(5);
			 			$pdf->MultiCell(13, 5, 'ลำดับ', 0, 'C', 0, 1, '', '', true);

						//--col 2
						$linever2 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(18, 60, 18,18, $linever2);

						$pdf->SetY(24);
			 			$pdf->SetX(19);
			 			$pdf->MultiCell(60, 5, 'เลขประจำตัวประชาชน(ของผู้มีเงินได้)', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(34);
			 			$pdf->SetX(19);
			 			$pdf->MultiCell(62, 5, 'เลขประจำตัวผู้เสียภาษีอากร(ของผู้มีเงินได้)', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(49);
			 			$pdf->SetX(19);
			 			$pdf->MultiCell(60, 5, 'ชื่อ ที่อยู่ ผู้มีเงินได้', 0, 'L', 0, 1, '', '', true);


						//--col 3
						$linever3 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(80, 60, 80,18, $linever3);

						$pdf->SetY(24);
			 			$pdf->SetX(81);
			 			$pdf->MultiCell(20, 5, 'วัน เดือน ปี ที่เข้าทำงาน(เฉพาะกรณีเข้าทำงานระหว่างปี)', 0, 'L', 0, 1, '', '', true);



						//--col 4
						$linever4 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(105, 60, 105,18, $linever4);
						
						$linever41 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(116, 60, 116,26, $linever41);

						$linever42 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(126, 60, 126,34, $linever42);

						$linever43 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(137, 60, 137,26, $linever43);

						$linever44 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(150, 60, 150,46, $linever44);

						$pdf->SetY(19);
			 			$pdf->SetX(106);
			 			$pdf->MultiCell(58, 5, '(1)รายการลดหย่อน', 0, 'C', 0, 1, '', '', true);

			 			$pdf->SetY(28);
			 			$pdf->SetX(105);
			 			$pdf->MultiCell(11, 5, 'มีสามีภริยาหรือไม่', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(27);
			 			$pdf->SetX(117);
			 			$pdf->MultiCell(20, 5, 'จำนวนบุตร', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(37);
			 			$pdf->SetX(116);
			 			$pdf->MultiCell(20, 5, 'ศึกษา', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(37);
			 			$pdf->SetX(126);
			 			$pdf->MultiCell(11, 5, 'ไม่ศึกษา', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(27);
			 			$pdf->SetX(137);
			 			$pdf->MultiCell(28, 5, 'ค่าลดหย่อนอื่นๆ', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(37);
			 			$pdf->SetX(137);
			 			$pdf->MultiCell(28, 5, 'จำนวนเงิน', 0, 'C', 0, 1, '', '', true);

			 			$pdf->SetY(49);
			 			$pdf->SetX(136);
			 			$pdf->MultiCell(15, 5, 'บาท', 0, 'C', 0, 1, '', '', true);

			 			$pdf->SetY(49);
			 			$pdf->SetX(149);
			 			$pdf->MultiCell(15, 5, 'สต.', 0, 'C', 0, 1, '', '', true);



						//--col 5
						$linever5 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(165, 60, 165,18, $linever5);

						$linever51 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(194, 60, 194,30, $linever51);

						$linever52 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(212, 60, 212,30, $linever52);

						$linever53 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(233, 60, 233,50, $linever53);

						$pdf->SetY(19);
			 			$pdf->SetX(165);
			 			$pdf->MultiCell(77, 5, 'ประเภทเงินได้พึงประเมิณที่จ่าย(รวมทั้งประโยชน์ที่เพิ่ม อย่างอื่น)ถ้ามากกว่าหนึ่งประเภทให้กรอกเรียงลงไป', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(39);
			 			$pdf->SetX(165);
			 			$pdf->MultiCell(30, 5, '(2)', 0, 'C', 0, 1, '', '', true);
			 			$pdf->SetY(43);
			 			$pdf->SetX(165);
			 			$pdf->MultiCell(30, 5, 'ประเภทเงินได้', 0, 'C', 0, 1, '', '', true);

			 			$pdf->SetY(39);
			 			$pdf->SetX(193);
			 			$pdf->MultiCell(20, 5, '(3)', 0, 'C', 0, 1, '', '', true);
			 			$pdf->SetY(43);
			 			$pdf->SetX(193);
			 			$pdf->MultiCell(20, 5, 'จำนวนคราวที่จ่ายทั้งปี', 0, 'C', 0, 1, '', '', true);
			 			
			 			$pdf->SetY(32);
			 			$pdf->SetX(212);
			 			$pdf->MultiCell(30, 5, 'จำนวนเงินที่จ่ายแต่ละประเภทเฉพาะคนหนึงๆ ทั้งปี', 0, 'L', 0, 1, '', '', true);
			 			
			 			$pdf->SetY(52);
			 			$pdf->SetX(216);
			 			$pdf->MultiCell(20, 5, 'บาท', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(52);
			 			$pdf->SetX(233);
			 			$pdf->MultiCell(8, 5, 'สต.', 0, 'C', 0, 1, '', '', true);


						//--col 6
						$linever6 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(242, 60, 242,18, $linever6);	

						$linever61 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(265, 60, 265,50, $linever61);

						$pdf->SetY(19);
			 			$pdf->SetX(242);
			 			$pdf->MultiCell(33, 5, 'รวมเงินภาษีที่หักและนำส่งในปีที่ล่วงมาแล้ว', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(37);
			 			$pdf->SetX(242);
			 			$pdf->MultiCell(33, 5, 'จำนวนเงิน', 0, 'C', 0, 1, '', '', true);

			 			$pdf->SetY(52);
			 			$pdf->SetX(249);
			 			$pdf->MultiCell(20, 5, 'บาท', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(52);
			 			$pdf->SetX(266);
			 			$pdf->MultiCell(8, 5, 'สต.', 0, 'C', 0, 1, '', '', true);


						$lineverEnd = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(274.5, 60, 274.5,18, $lineverEnd);	


						//======= แนวนอน =========//
						$linehor1 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(274.5, 60, 5,60, $linehor1);

						//--col 4
						$linehor2 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(165, 26, 105,26, $linehor2);

						$linehor3 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(165, 34, 116,34, $linehor3);

						$linehor4 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(165, 46, 137,46, $linehor4);

						//--col 5
						$linehor5 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(274.5, 30, 165,30, $linehor5);

						$linehor6 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(274.5, 50, 212,50, $linehor6);
			    } 
			    //================================ END header > 2 ===================================//	
				//================================ END header > 2 ===================================//	
				//================================ END header > 2 ===================================//	   

			    //แนวตั้ง
			    if( $j > 2)
			    {
			    	$h1 = 40;
			    	$y1 = 70;
			    	$d4 = 45;
			    	$x1 = 60;
			    }

			    $verft1 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(5, $h1+10, 5,$y1, $verft1);

				$verft2 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(212, $h1+10, 212,$y1, $verft2);

				$verft3 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(242, $h1+10, 242,$y1, $verft3);

				$verft4 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(274.5, $h1+10, 274.5,$y1, $verft4);

				$pdf->SetFont('freeserif','B',11,'',true);
				$pdf->SetY($d4+17);
	 			$pdf->SetX(151);
	 			$pdf->MultiCell(50, 5, 'รวมยอดเงินได้และภาษีที่นำส่ง', 0, 'R', 0, 0, '', '', true);

	 			$pdf->SetFont('freeserif','B',11,'',true);
				$pdf->SetY($d4+17);
	 			$pdf->SetX(211);
	 			$pdf->MultiCell(30, 5, number_format($sum1, 2), 0, 'R', 0, 0, '', '', true);

	 			$pdf->SetFont('freeserif','B',11,'',true);
				$pdf->SetY($d4+17);
	 			$pdf->SetX(241);
	 			$pdf->MultiCell(32, 5, number_format($sum2, 2), 0, 'R', 0, 0, '', '', true);

				//แนวนอน
				$horft1 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(274.5, $x1+10, 5,$x1+10, $horft1);

			    //================================= last page footer ======================================//
				$pdf->SetFont('freeserif','B',11,'',true);
				$pdf->SetY(145);
	 			$pdf->SetX(8);
	 			$pdf->MultiCell(30, 5, 'หมายเหตุ', 0, 'C', 0, 0, '', '', true);
				
	 			$pdf->SetFont('freeserif','',10,'',true);
				$pdf->SetY(155);
	 			$pdf->SetX(5);
	 			$pdf->MultiCell(180, 5, '1.ให้ระบุว่า มี หรือ ไม่มี ภริยาโดยใส่เครื่องหมาย / ลงใน หน้าข้อความแต่กรณีพร้อมทั้งกรอกจำนวนบุตรที่มีสิทธิหักลดหย่อนศึกษากี่คน ไม่ศึกษากี่คนและยอดรวมจำนวนเงินค่าลดหย่อนอื่น ๆ ที่จ่ายให้แก่เบี้ยประกันชีวิต เงินสะสมดอกเบี้ยเงินกู้ยืมเพื่อซื้อ เช้าซื้อ หรือสร้างอาคารที่อยู่อาศัย ฯ และเงินสมทบ ฯ', 0, 'L', 0, 0, '', '', true);
				
				$pdf->SetFont('freeserif','',10,'',true);
				$pdf->SetY(175);
	 			$pdf->SetX(5);
	 			$pdf->MultiCell(180, 5, '2.ให้กรอกประเภทเงินได้ที่จ่าย เช่น เงินเดือน ค่าจ้าง เบี้ยเลี้ยง โบนัส บำเหน็จ เงินค่าธรรมเนียม ค่านายหน้า เปบี้ยประชุมค่าภาษีเงินได้ ฯลฯ', 0, 'L', 0, 0, '', '', true);
				
	 			$pdf->SetFont('freeserif','',10,'',true);
				$pdf->SetY(185);
	 			$pdf->SetX(5);
	 			$pdf->MultiCell(180, 5, '3.จำนวนคราวที่จ่ายทั้งปี -จ่ายเป็นรายวัน กรอก 1   -จ่ายเป็นรายสัปดาห์ กรอก 2     -จ่ายเป็นรายปักษ์ กรอก 3     -จ่ายเป็นรายเดือน กรอก 4     -จ่ายเป็นคราวไม่แน่นอน กรอก 5', 0, 'L', 0, 0, '', '', true);			
				
	 			$pdf->SetFont('freeserif','B',10,'',true);
				$pdf->SetY(155);
	 			$pdf->SetX(180);
	 			$pdf->MultiCell(94, 5, 'ลงชื่อ................................................................ผู้จ่ายเงิน', 0, 'C', 0, 0, '', '', true);
				
				$pdf->SetFont('freeserif','B',11,'',true);
				$pdf->SetY(164);
	 			$pdf->SetX(180);
	 			$pdf->MultiCell(94, 5, $director, 0, 'C', 0, 0, '', '', true);
				
				$pdf->SetY(170);
	 			$pdf->SetX(180);
	 			$pdf->MultiCell(94, 5, 'นายแพทย์เชี่ยวชาญ', 0, 'C', 0, 0, '', '', true);

	 			$pdf->SetY(180);
	 			$pdf->SetX(180);
	 			$pdf->MultiCell(94, 5, 'ผู้อำนวยการโรงพยาบาลโนนไทย', 0, 'C', 0, 0, '', '', true);
	 			
				$pdf->SetY(190);
	 			$pdf->SetX(180);
	 			$pdf->MultiCell(94, 5, 'ยื่นวันที่.................................................................', 0, 'C', 0, 0, '', '', true);
		}// check result > 0

			$filename = storage_path() . '/report_tax_itpc_emp3.pdf';
		    // Response::download($filename);
		    $contents = $pdf->output($filename, 'I');
			$headers = array(
			    'Content-Type' => 'application/pdf',
			);
			return Response::make($contents, 200, $headers);
		}
		else
		{
			return View::make('login.index');
		}	
	}

	//------------tax pts------------//
	/*
	* function name itpc_home3_pts
	* ภงด 1 ก  Home 3 pts
	*
	*/
	public function itpc_home3_pts()
	{
		if( Session::get('level') != '' )
		{
			$y = DB::Select( ' select year(order_date) as year1 from s_salary_detail group by year(order_date) order by year(order_date) desc ' );	
			return View::make( 'tax.itpc_home3_pts', array( 'data' => $y ) );
		}
		else
		{
			return View::make('login.index');
		}  
	}
	/*
	* function name tax_itpc_type3_pts
	* ภงด 1 ก พตส
	*
	*/
	public function tax_itpc_type3_pts()
	{
		$y = Input::get('y1');

		if( Session::get('level') != '' )
		{
			$n = DB::select( 'select * from s_general_data' );	
		    foreach ($n as $k) {
		      	$name 		= $k->name;
		      	$address 	= $k->address;
		      	$tax_id2 	= $k->tax_id2;
		      	$director 	= $k->director;
		    } 

			$pdf = new TCPDF();			
			
			$pdf->SetHeaderData('', '', 'ภ.ง.ด 1 ก พิเศษ', 'เลขประจำตัวผู้เสียภาษีอากร(ของผู้มีหน้าที่หักภาษี ณ ที่จ่าย)       '.$tax_id2 );
			
			// set header and footer fonts
			$pdf->setHeaderFont(Array('freeserif', '', 13));
			$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));	  

			$pdf->SetMargins(5, PDF_MARGIN_TOP, 5);
			$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
			$pdf->SetFooterMargin(PDF_MARGIN_FOOTER); 	   		   
		   
		    $sql = ' select concat(n.pname,"",n.fname," ",n.lname) as name, s.cid, s.tax_id,sum(s.salary+s.r_other) as salary , sum(s.r_c) as r_c, sum(s.pts2+s.ch112) as special, sum(s.tax) as tax from s_salary_ocsc_detail s left join n_datageneral n on n.cid=s.cid left join n_position_salary p on p.cid=n.cid where  p.`level` in ("ข้าราชการ","ลูกจ้างประจำ")  and year(s.order_date)='.$y.' group by s.cid order by n.datainfoID asc ';		    
		    $result = DB::select( $sql );
			$j=0;
			$i=0;
			$sum1=0;
			$sum2=0;
			$row=0;
		    foreach ( $result as $key ) 
		    {	
		    	if( $key->special > 0 )		
		    	{		    
			    	$row++;		    	
			    	if( $j==4 )
		    		{
		    			$j=0;
		    		}

			    	if( $j == 0)
			    	{		    
		    		

			    		$pdf->AddPage('L', 'letter');	
			    					
			 			//===== แนวตั้ง =====//
			 			$linever1 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(5, 60, 5,18, $linever1);

						$pdf->SetFont('freeserif','',11,'',true);
						$pdf->SetY(35);
			 			$pdf->SetX(5);
			 			$pdf->MultiCell(13, 5, 'ลำดับ', 0, 'C', 0, 1, '', '', true);

						//--col 2
						$linever2 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(18, 60, 18,18, $linever2);

						$pdf->SetY(24);
			 			$pdf->SetX(19);
			 			$pdf->MultiCell(60, 5, 'เลขประจำตัวประชาชน(ของผู้มีเงินได้)', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(34);
			 			$pdf->SetX(19);
			 			$pdf->MultiCell(62, 5, 'เลขประจำตัวผู้เสียภาษีอากร(ของผู้มีเงินได้)', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(49);
			 			$pdf->SetX(19);
			 			$pdf->MultiCell(60, 5, 'ชื่อ ที่อยู่ ผู้มีเงินได้', 0, 'L', 0, 1, '', '', true);


						//--col 3
						$linever3 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(80, 60, 80,18, $linever3);

						$pdf->SetY(24);
			 			$pdf->SetX(81);
			 			$pdf->MultiCell(20, 5, 'วัน เดือน ปี ที่เข้าทำงาน(เฉพาะกรณีเข้าทำงานระหว่างปี)', 0, 'L', 0, 1, '', '', true);



						//--col 4
						$linever4 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(105, 60, 105,18, $linever4);
						
						$linever41 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(116, 60, 116,26, $linever41);

						$linever42 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(126, 60, 126,34, $linever42);

						$linever43 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(137, 60, 137,26, $linever43);

						$linever44 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(150, 60, 150,46, $linever44);

						$pdf->SetY(19);
			 			$pdf->SetX(106);
			 			$pdf->MultiCell(58, 5, '(1)รายการลดหย่อน', 0, 'C', 0, 1, '', '', true);

			 			$pdf->SetY(28);
			 			$pdf->SetX(105);
			 			$pdf->MultiCell(11, 5, 'มีสามีภริยาหรือไม่', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(27);
			 			$pdf->SetX(117);
			 			$pdf->MultiCell(20, 5, 'จำนวนบุตร', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(37);
			 			$pdf->SetX(116);
			 			$pdf->MultiCell(20, 5, 'ศึกษา', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(37);
			 			$pdf->SetX(126);
			 			$pdf->MultiCell(11, 5, 'ไม่ศึกษา', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(27);
			 			$pdf->SetX(137);
			 			$pdf->MultiCell(28, 5, 'ค่าลดหย่อนอื่นๆ', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(37);
			 			$pdf->SetX(137);
			 			$pdf->MultiCell(28, 5, 'จำนวนเงิน', 0, 'C', 0, 1, '', '', true);

			 			$pdf->SetY(49);
			 			$pdf->SetX(136);
			 			$pdf->MultiCell(15, 5, 'บาท', 0, 'C', 0, 1, '', '', true);

			 			$pdf->SetY(49);
			 			$pdf->SetX(149);
			 			$pdf->MultiCell(15, 5, 'สต.', 0, 'C', 0, 1, '', '', true);



						//--col 5
						$linever5 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(165, 60, 165,18, $linever5);

						$linever51 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(194, 60, 194,30, $linever51);

						$linever52 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(212, 60, 212,30, $linever52);

						$linever53 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(233, 60, 233,50, $linever53);

						$pdf->SetY(19);
			 			$pdf->SetX(165);
			 			$pdf->MultiCell(77, 5, 'ประเภทเงินได้พึงประเมิณที่จ่าย(รวมทั้งประโยชน์ที่เพิ่ม อย่างอื่น)ถ้ามากกว่าหนึ่งประเภทให้กรอกเรียงลงไป', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(39);
			 			$pdf->SetX(165);
			 			$pdf->MultiCell(30, 5, '(2)', 0, 'C', 0, 1, '', '', true);
			 			$pdf->SetY(43);
			 			$pdf->SetX(165);
			 			$pdf->MultiCell(30, 5, 'ประเภทเงินได้', 0, 'C', 0, 1, '', '', true);

			 			$pdf->SetY(39);
			 			$pdf->SetX(193);
			 			$pdf->MultiCell(20, 5, '(3)', 0, 'C', 0, 1, '', '', true);
			 			$pdf->SetY(43);
			 			$pdf->SetX(193);
			 			$pdf->MultiCell(20, 5, 'จำนวนคราวที่จ่ายทั้งปี', 0, 'C', 0, 1, '', '', true);
			 			
			 			$pdf->SetY(32);
			 			$pdf->SetX(212);
			 			$pdf->MultiCell(30, 5, 'จำนวนเงินที่จ่ายแต่ละประเภทเฉพาะคนหนึงๆ ทั้งปี', 0, 'L', 0, 1, '', '', true);
			 			
			 			$pdf->SetY(52);
			 			$pdf->SetX(216);
			 			$pdf->MultiCell(20, 5, 'บาท', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(52);
			 			$pdf->SetX(233);
			 			$pdf->MultiCell(8, 5, 'สต.', 0, 'C', 0, 1, '', '', true);


						//--col 6
						$linever6 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(242, 60, 242,18, $linever6);	

						$linever61 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(265, 60, 265,50, $linever61);

						$pdf->SetY(19);
			 			$pdf->SetX(242);
			 			$pdf->MultiCell(33, 5, 'รวมเงินภาษีที่หักและนำส่งในปีที่ล่วงมาแล้ว', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(37);
			 			$pdf->SetX(242);
			 			$pdf->MultiCell(33, 5, 'จำนวนเงิน', 0, 'C', 0, 1, '', '', true);

			 			$pdf->SetY(52);
			 			$pdf->SetX(249);
			 			$pdf->MultiCell(20, 5, 'บาท', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(52);
			 			$pdf->SetX(266);
			 			$pdf->MultiCell(8, 5, 'สต.', 0, 'C', 0, 1, '', '', true);


						$lineverEnd = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(274.5, 60, 274.5,18, $lineverEnd);	


						//======= แนวนอน =========//
						$linehor1 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(274.5, 60, 5,60, $linehor1);

						//--col 4
						$linehor2 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(165, 26, 105,26, $linehor2);

						$linehor3 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(165, 34, 116,34, $linehor3);

						$linehor4 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(165, 46, 137,46, $linehor4);

						//--col 5
						$linehor5 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(274.5, 30, 165,30, $linehor5);

						$linehor6 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(274.5, 50, 212,50, $linehor6);					

					}//end add header

					if( $j == 0 ){
						$x1=95;
						$y1=60;
						$h1=95;
						
						$d1=61;
						$d2=68;
						$d3=74;
						$d4=81;
					}else if( $j == 1 ){
						$x1=130;
						$y1=90;
						$h1=130;
						
						$d1=96;
						$d2=103;
						$d3=109;
						$d4=116;
					}else if( $j == 2 ){
						$x1=165;
						$y1=120;
						$h1=165;
						
						$d1=131;
						$d2=138;
						$d3=144;
						$d4=151;	
					}else if( $j == 3 ){
						$x1=197;
						$y1=150;
						$h1=197;
						
						$d1=166;
						$d2=171;
						$d3=176;
						$d4=181;
					}

					//loop นอน
					$linehor = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(274.5, $x1, 5,$x1, $linehor);

					//loop ตั้ง
					$linever_loop1 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(5, $h1, 5,$y1, $linever_loop1);

					$linever_loop2 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(18, $h1, 18,$y1, $linever_loop2);

					$linever_loop3 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(80, $h1, 80,$y1, $linever_loop3);

					$linever_loop4 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(105, $h1, 105,$y1, $linever_loop4);

					$linever_loop5 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(116, $h1, 116,$y1, $linever_loop5);

					$linever_loop6 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(126, $h1, 126,$y1, $linever_loop6);

					$linever_loop7 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(137, $h1, 137,$y1, $linever_loop7);

					$linever_loop8 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(165, $h1, 165,$y1, $linever_loop8);

					$linever_loop9 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(194, $h1, 194,$y1, $linever_loop9);

					$linever_loop10 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(212, $h1, 212,$y1, $linever_loop10);

					$linever_loop11 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(242, $h1, 242,$y1, $linever_loop11);

					$linever_loop12 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(274.5, $h1, 274.5,$y1, $linever_loop12);

					//loop data
					
					//col number
					$pdf->SetFont('freeserif','',11,'',true);
					$pdf->SetY($d1);
		 			$pdf->SetX(5);
		 			$pdf->MultiCell(13, 5, $row, 0, 'C', 0, 0, '', '', true);
					
					//col 1 data
					$pdf->SetFont('freeserif','B',11,'',true);
					$pdf->SetY($d1);
		 			$pdf->SetX(19);
		 			$pdf->MultiCell(60, 5, $key->cid, 0, 'L', 0, 0, '', '', true);	

		 			$pdf->SetFont('freeserif','',11,'',true);
		 			$pdf->SetY($d2);
		 			$pdf->SetX(19);
					$pdf->MultiCell(60, 5, (($key->tax_id > 0)?$key->tax_id:""), 0, 'L', 0, 0, '', '', true);	

		 			$pdf->SetFont('freeserif','B',11,'',true);
		 			$pdf->SetY($d3);
		 			$pdf->SetX(19);
		 			$pdf->MultiCell(60, 5, $key->name, 0, 'L', 0, 0, '', '', true);
					
					$pdf->SetFont('freeserif','',11,'',true);
					$pdf->SetY($d4);
		 			$pdf->SetX(19);
		 			$pdf->MultiCell(60, 5, $address, 0, 'L', 0, 0, '', '', true);	 

		 			//col 8
		 			$pdf->SetFont('freeserif','',11,'',true);
					$pdf->SetY($d1);
		 			$pdf->SetX(166);
		 			$pdf->MultiCell(28, 5, 'เงินเดือน ค่าจ้าง บำนาญ เบี้ยเลี้ยง โบนัส', 0, 'L', 0, 0, '', '', true);	

		 			$pdf->SetFont('freeserif','',11,'',true);
					$pdf->SetY($d1+16);
		 			$pdf->SetX(166);
		 			$pdf->MultiCell(28, 5, 'เงินประจำตำแหน่ง', 0, 'L', 0, 0, '', '', true);

		 			$pdf->SetFont('freeserif','',11,'',true);
					$pdf->SetY($d3+10);
		 			$pdf->SetX(166);
		 			$pdf->MultiCell(28, 5, 'เงินค่าตอบแทน พตส ค่าครองชีพ', 0, 'L', 0, 0, '', '', true);	

		 			//col 10
		 			$pdf->SetFont('freeserif','',11,'',true);
					$pdf->SetY($d1);
		 			$pdf->SetX(209);
		 			$pdf->MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);//salary

		 			$pdf->SetFont('freeserif','',11,'',true);
					$pdf->SetY($d1+16);
		 			$pdf->SetX(209);
		 			$pdf->MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);//r_c

		 			$pdf->SetFont('freeserif','',11,'',true);
					$pdf->SetY($d3+10);
		 			$pdf->SetX(209);
		 			$pdf->MultiCell(30, 5, number_format( $key->special, 2 ), 0, 'R', 0, 0, '', '', true);	

		 			//col 11		
		 			$pdf->SetFont('freeserif','',11,'',true);
					$pdf->SetY($d1);
		 			$pdf->SetX(241);
		 			$pdf->MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);//tax

		 			$j++;		    	

			    	$sum1 = $sum1+$key->special;
			    	$sum2 = $sum2+$key->tax;
			    }//end if check pts2 > 0

		    } //end foreach			    


			//================================ header > 2 ===================================//	
			//================================ header > 2 ===================================//	
			//================================ header > 2 ===================================//	
			if( count($result) > 0 )
			{		
				if( ($j > 2) )	
				{

			    	$pdf->AddPage('L', 'letter');

			    	//===== แนวตั้ง =====//
			 			$linever1 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(5, 60, 5,18, $linever1);

						$pdf->SetFont('freeserif','',11,'',true);
						$pdf->SetY(35);
			 			$pdf->SetX(5);
			 			$pdf->MultiCell(13, 5, 'ลำดับ', 0, 'C', 0, 1, '', '', true);

						//--col 2
						$linever2 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(18, 60, 18,18, $linever2);

						$pdf->SetY(24);
			 			$pdf->SetX(19);
			 			$pdf->MultiCell(60, 5, 'เลขประจำตัวประชาชน(ของผู้มีเงินได้)', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(34);
			 			$pdf->SetX(19);
			 			$pdf->MultiCell(62, 5, 'เลขประจำตัวผู้เสียภาษีอากร(ของผู้มีเงินได้)', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(49);
			 			$pdf->SetX(19);
			 			$pdf->MultiCell(60, 5, 'ชื่อ ที่อยู่ ผู้มีเงินได้', 0, 'L', 0, 1, '', '', true);


						//--col 3
						$linever3 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(80, 60, 80,18, $linever3);

						$pdf->SetY(24);
			 			$pdf->SetX(81);
			 			$pdf->MultiCell(20, 5, 'วัน เดือน ปี ที่เข้าทำงาน(เฉพาะกรณีเข้าทำงานระหว่างปี)', 0, 'L', 0, 1, '', '', true);



						//--col 4
						$linever4 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(105, 60, 105,18, $linever4);
						
						$linever41 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(116, 60, 116,26, $linever41);

						$linever42 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(126, 60, 126,34, $linever42);

						$linever43 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(137, 60, 137,26, $linever43);

						$linever44 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(150, 60, 150,46, $linever44);

						$pdf->SetY(19);
			 			$pdf->SetX(106);
			 			$pdf->MultiCell(58, 5, '(1)รายการลดหย่อน', 0, 'C', 0, 1, '', '', true);

			 			$pdf->SetY(28);
			 			$pdf->SetX(105);
			 			$pdf->MultiCell(11, 5, 'มีสามีภริยาหรือไม่', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(27);
			 			$pdf->SetX(117);
			 			$pdf->MultiCell(20, 5, 'จำนวนบุตร', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(37);
			 			$pdf->SetX(116);
			 			$pdf->MultiCell(20, 5, 'ศึกษา', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(37);
			 			$pdf->SetX(126);
			 			$pdf->MultiCell(11, 5, 'ไม่ศึกษา', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(27);
			 			$pdf->SetX(137);
			 			$pdf->MultiCell(28, 5, 'ค่าลดหย่อนอื่นๆ', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(37);
			 			$pdf->SetX(137);
			 			$pdf->MultiCell(28, 5, 'จำนวนเงิน', 0, 'C', 0, 1, '', '', true);

			 			$pdf->SetY(49);
			 			$pdf->SetX(136);
			 			$pdf->MultiCell(15, 5, 'บาท', 0, 'C', 0, 1, '', '', true);

			 			$pdf->SetY(49);
			 			$pdf->SetX(149);
			 			$pdf->MultiCell(15, 5, 'สต.', 0, 'C', 0, 1, '', '', true);



						//--col 5
						$linever5 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(165, 60, 165,18, $linever5);

						$linever51 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(194, 60, 194,30, $linever51);

						$linever52 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(212, 60, 212,30, $linever52);

						$linever53 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(233, 60, 233,50, $linever53);

						$pdf->SetY(19);
			 			$pdf->SetX(165);
			 			$pdf->MultiCell(77, 5, 'ประเภทเงินได้พึงประเมิณที่จ่าย(รวมทั้งประโยชน์ที่เพิ่ม อย่างอื่น)ถ้ามากกว่าหนึ่งประเภทให้กรอกเรียงลงไป', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(39);
			 			$pdf->SetX(165);
			 			$pdf->MultiCell(30, 5, '(2)', 0, 'C', 0, 1, '', '', true);
			 			$pdf->SetY(43);
			 			$pdf->SetX(165);
			 			$pdf->MultiCell(30, 5, 'ประเภทเงินได้', 0, 'C', 0, 1, '', '', true);

			 			$pdf->SetY(39);
			 			$pdf->SetX(193);
			 			$pdf->MultiCell(20, 5, '(3)', 0, 'C', 0, 1, '', '', true);
			 			$pdf->SetY(43);
			 			$pdf->SetX(193);
			 			$pdf->MultiCell(20, 5, 'จำนวนคราวที่จ่ายทั้งปี', 0, 'C', 0, 1, '', '', true);
			 			
			 			$pdf->SetY(32);
			 			$pdf->SetX(212);
			 			$pdf->MultiCell(30, 5, 'จำนวนเงินที่จ่ายแต่ละประเภทเฉพาะคนหนึงๆ ทั้งปี', 0, 'L', 0, 1, '', '', true);
			 			
			 			$pdf->SetY(52);
			 			$pdf->SetX(216);
			 			$pdf->MultiCell(20, 5, 'บาท', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(52);
			 			$pdf->SetX(233);
			 			$pdf->MultiCell(8, 5, 'สต.', 0, 'C', 0, 1, '', '', true);


						//--col 6
						$linever6 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(242, 60, 242,18, $linever6);	

						$linever61 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(265, 60, 265,50, $linever61);

						$pdf->SetY(19);
			 			$pdf->SetX(242);
			 			$pdf->MultiCell(33, 5, 'รวมเงินภาษีที่หักและนำส่งในปีที่ล่วงมาแล้ว', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(37);
			 			$pdf->SetX(242);
			 			$pdf->MultiCell(33, 5, 'จำนวนเงิน', 0, 'C', 0, 1, '', '', true);

			 			$pdf->SetY(52);
			 			$pdf->SetX(249);
			 			$pdf->MultiCell(20, 5, 'บาท', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(52);
			 			$pdf->SetX(266);
			 			$pdf->MultiCell(8, 5, 'สต.', 0, 'C', 0, 1, '', '', true);


						$lineverEnd = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(274.5, 60, 274.5,18, $lineverEnd);	


						//======= แนวนอน =========//
						$linehor1 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(274.5, 60, 5,60, $linehor1);

						//--col 4
						$linehor2 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(165, 26, 105,26, $linehor2);

						$linehor3 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(165, 34, 116,34, $linehor3);

						$linehor4 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(165, 46, 137,46, $linehor4);

						//--col 5
						$linehor5 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(274.5, 30, 165,30, $linehor5);

						$linehor6 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(274.5, 50, 212,50, $linehor6);
			    } 
			    //================================ END header > 2 ===================================//	
				//================================ END header > 2 ===================================//	
				//================================ END header > 2 ===================================//	   

			    //แนวตั้ง
			    if( $j > 2)
			    {
			    	$h1 = 40;
			    	$y1 = 70;
			    	$d4 = 45;
			    	$x1 = 60;
			    }

			    $verft1 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(5, $h1+10, 5,$y1, $verft1);

				$verft2 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(212, $h1+10, 212,$y1, $verft2);

				$verft3 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(242, $h1+10, 242,$y1, $verft3);

				$verft4 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(274.5, $h1+10, 274.5,$y1, $verft4);

				$pdf->SetFont('freeserif','B',11,'',true);
				$pdf->SetY($d4+17);
	 			$pdf->SetX(151);
	 			$pdf->MultiCell(50, 5, 'รวมยอดเงินได้และภาษีที่นำส่ง', 0, 'R', 0, 0, '', '', true);

	 			$pdf->SetFont('freeserif','B',11,'',true);
				$pdf->SetY($d4+17);
	 			$pdf->SetX(211);
	 			$pdf->MultiCell(30, 5, number_format($sum1, 2), 0, 'R', 0, 0, '', '', true);

	 			$pdf->SetFont('freeserif','B',11,'',true);
				$pdf->SetY($d4+17);
	 			$pdf->SetX(241);
	 			$pdf->MultiCell(32, 5, '', 0, 'R', 0, 0, '', '', true);//sum2

				//แนวนอน
				$horft1 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(274.5, $x1+10, 5,$x1+10, $horft1);

			    //================================= last page footer ======================================//
				$pdf->SetFont('freeserif','B',11,'',true);
				$pdf->SetY(145);
	 			$pdf->SetX(8);
	 			$pdf->MultiCell(30, 5, 'หมายเหตุ', 0, 'C', 0, 0, '', '', true);
				
	 			$pdf->SetFont('freeserif','',10,'',true);
				$pdf->SetY(155);
	 			$pdf->SetX(5);
	 			$pdf->MultiCell(180, 5, '1.ให้ระบุว่า มี หรือ ไม่มี ภริยาโดยใส่เครื่องหมาย / ลงใน หน้าข้อความแต่กรณีพร้อมทั้งกรอกจำนวนบุตรที่มีสิทธิหักลดหย่อนศึกษากี่คน ไม่ศึกษากี่คนและยอดรวมจำนวนเงินค่าลดหย่อนอื่น ๆ ที่จ่ายให้แก่เบี้ยประกันชีวิต เงินสะสมดอกเบี้ยเงินกู้ยืมเพื่อซื้อ เช้าซื้อ หรือสร้างอาคารที่อยู่อาศัย ฯ และเงินสมทบ ฯ', 0, 'L', 0, 0, '', '', true);
				
				$pdf->SetFont('freeserif','',10,'',true);
				$pdf->SetY(175);
	 			$pdf->SetX(5);
	 			$pdf->MultiCell(180, 5, '2.ให้กรอกประเภทเงินได้ที่จ่าย เช่น เงินเดือน ค่าจ้าง เบี้ยเลี้ยง โบนัส บำเหน็จ เงินค่าธรรมเนียม ค่านายหน้า เปบี้ยประชุมค่าภาษีเงินได้ ฯลฯ', 0, 'L', 0, 0, '', '', true);
				
	 			$pdf->SetFont('freeserif','',10,'',true);
				$pdf->SetY(185);
	 			$pdf->SetX(5);
	 			$pdf->MultiCell(180, 5, '3.จำนวนคราวที่จ่ายทั้งปี -จ่ายเป็นรายวัน กรอก 1   -จ่ายเป็นรายสัปดาห์ กรอก 2     -จ่ายเป็นรายปักษ์ กรอก 3     -จ่ายเป็นรายเดือน กรอก 4     -จ่ายเป็นคราวไม่แน่นอน กรอก 5', 0, 'L', 0, 0, '', '', true);			
				
	 			$pdf->SetFont('freeserif','B',10,'',true);
				$pdf->SetY(155);
	 			$pdf->SetX(180);
	 			$pdf->MultiCell(94, 5, 'ลงชื่อ................................................................ผู้จ่ายเงิน', 0, 'C', 0, 0, '', '', true);
				
				$pdf->SetFont('freeserif','B',11,'',true);
				$pdf->SetY(164);
	 			$pdf->SetX(180);
	 			$pdf->MultiCell(94, 5, $director, 0, 'C', 0, 0, '', '', true);
				
				$pdf->SetY(170);
	 			$pdf->SetX(180);
	 			$pdf->MultiCell(94, 5, 'นายแพทย์เชี่ยวชาญ', 0, 'C', 0, 0, '', '', true);

	 			$pdf->SetY(180);
	 			$pdf->SetX(180);
	 			$pdf->MultiCell(94, 5, 'ผู้อำนวยการโรงพยาบาลโนนไทย', 0, 'C', 0, 0, '', '', true);
	 			
				$pdf->SetY(190);
	 			$pdf->SetX(180);
	 			$pdf->MultiCell(94, 5, 'ยื่นวันที่.................................................................', 0, 'C', 0, 0, '', '', true);
		}// check result > 0

			$filename = storage_path() . '/report_tax_itpc_emp3_pts.pdf';
		    // Response::download($filename);
		    $contents = $pdf->output($filename, 'I');
			$headers = array(
			    'Content-Type' => 'application/pdf',
			);
			return Response::make($contents, 200, $headers);
		}
		else
		{
			return View::make('login.index');
		}	
	}



	//================================= Add Special 1 ================================//

	/**
    * function name : get_department
    * get departmentName
    * get
    */
	private function get_department( $id )
	{
		$data = DB::Select( 'select departmentName from n_department where department_id='.$id.' ' );
		foreach ($data as $k) {
			return $k->departmentName;
		}
	}

	/**
    * function name : orderBy
    * sort Array
    * get
    */
	private function  orderBy($data, $field)
   {
	    $code = "return strnatcmp(\$a['$field'], \$b['$field']);";
	    usort($data, create_function('$a,$b', $code));
	    return $data;
   }

	public function add_special1()
	{
		if( Session::get('level') != '' )
		{			
			//$dep = DB::select(' select department_id, departmentName from n_department ');
		    $year = DB::select( ' select (year(order_date)+543) as year from s_salary_detail group by  year(order_date) ' ); 	        		

        	return View::make( 'tax.special1', array( 'year' => $year ) );
		}
		else
		{
			return View::make('login.index');
		}			
	}

	/**
    * function name : viewspecial1
    * แสดงค่าตอบแทน เป็นรายการ
    * get
    */
	public function viewspecial1( $y, $m, $field, $q)
	{		
	    /*$sql =' select * from (';	
	    $sql .=' (select concat(n_datageneral.pname,"",n_datageneral.fname," ",n_datageneral.lname) as namefull,'; 
	    $sql .=' n_department.sort, n_datageneral.dep_id, n_datageneral.cid, pts, pts2, ot, ch8, no_v, outpcu, sub_ot, n_datageneral.'.$q.', (s_salary_detail.salary_id) as salary_id, (1) as type';
	    $sql .=' from s_salary_detail';
	    $sql .=' left join n_datageneral on n_datageneral.cid = s_salary_detail.cid';
	    $sql .=' inner join n_department on n_department.department_id = n_datageneral.dep_id';
	    $sql .=' where year(s_salary_detail.order_date) = '.$y.' ';   
	    $sql .=' and month(s_salary_detail.order_date) = '.$m.' ';
	    $sql .=' and n_datageneral.'.$field.' = 1 ';
	    $sql .=' and n_datageneral.dep_id='.$dep;
	    $sql .=' order by n_department.sort asc, n_datageneral.'.$q.' asc )';
	    $sql .=' union';
	    $sql .=' (select concat(n_datageneral.pname,"",n_datageneral.fname," ",n_datageneral.lname) as namefull, n_department.sort, n_datageneral.dep_id, n_datageneral.cid, pts, pts2, ot, ch8, no_v, outpcu, sub_ot, n_datageneral.'.$q.', (s_salary_ocsc_detail.salary_ocsc_id) as salary_id, (2) as type ';
	    $sql .=' from s_salary_ocsc_detail';
	    $sql .=' left join n_datageneral on n_datageneral.cid = s_salary_ocsc_detail.cid';
	    $sql .=' inner join n_department on n_department.department_id = n_datageneral.dep_id';
	    $sql .=' where year(s_salary_ocsc_detail.order_date) = '.$y.' ';
	    $sql .=' and month(s_salary_ocsc_detail.order_date) = '.$m.' ';
	    $sql .=' and n_datageneral.'.$field.' = 1 ';
	    $sql .=' and n_datageneral.dep_id='.$dep;
	    $sql .=' order by n_department.sort asc, n_datageneral.'.$q.' asc)';
	    $sql .=' ) as a  order by a.'.$q.' asc';*/

			
	    $sql =' select concat(n_datageneral.pname,"",n_datageneral.fname," ",n_datageneral.lname) as namefull,'; 
	    $sql .=' n_department.sort, n_datageneral.dep_id, n_datageneral.cid, n_datageneral.'.$q.'';
	    $sql .=' from n_datageneral';
	    $sql .=' inner join n_department on n_department.department_id = n_datageneral.dep_id';
	    $sql .=' where ';   
	    $sql .=' n_datageneral.'.$field.' = 1 ';
	    $sql .=' order by n_department.sort asc, n_datageneral.'.$q.' asc';


		$data = DB::Select( $sql );
	    //------------------ Query End --------------------//
	  
	    $t  = '<table class="tb-taxAll responsive">';  	    
	    $t .= '<tr>';
	    $t .= '<th width="120">CID</th> <th width="210">ชื่อ-สกุล</th> <th width="150">จำนวนเงิน</th>';
	    $t .= '</tr>';	   

	    $i=0;
	    foreach ( $data as $k ) 
	    {	  
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

	    	$sql2  = ' select * from s_salary_detail';
	    	$sql2 .= ' where year(s_salary_detail.order_date) = '.$y.' ';
	    	$sql2 .= ' and month(s_salary_detail.order_date) = '.$m.' ';
	    	$sql2 .= ' and cid= '.$k->cid.' ';
	    	$data2 = DB::Select( $sql2 );

	    	$sql3  = ' select * from s_salary_ocsc_detail';
	    	$sql3 .= ' where year(s_salary_ocsc_detail.order_date) = '.$y.' ';
	    	$sql3 .= ' and month(s_salary_ocsc_detail.order_date) = '.$m.' ';
	    	$sql3 .= ' and cid= '.$k->cid.' ';
	    	$data3 = DB::Select( $sql3 );

	    	$dataall = array();
	    	$type = 0;

	    	if( count($data2) > 0 ){
	    		$dataall = $data2;
	    		$type = 1;
	    	}
	    	if( count($data3) > 0 ){
	    		$dataall = $data3;
	    		$type = 2;
	    	}

	    	if( $type == 0 ){
	    		$chtype = DB::table('n_position_salary')->select('level')->where('cid', $k->cid)->orderBy('salaryID', 'desc')->limit(1)->first();
	    		if(count($chtype) > 0){
		    		if( $chtype->level == 'ข้าราชการ' || $chtype->level == 'ลูกจ้างประจำ' ){
		    			$type = '2';
		    		}

		    		if( $chtype->level == 'พกส.(ปฏิบัติงาน)' || $chtype->level == 'ลูกจ้างชั่วคราว' ||  $chtype->level == 'ลูกจ้างรายวัน' ){
		    			$type = '1';
		    		}
	    		}else{
	    			$type = '1';
	    		}
	    	}

	    	$cid = $k->cid;
	    	$salary_id = 0;
	    	$pts = 0;
	    	$pts2 = 0;
	    	$ot = 0;
	    	$ch8 = 0;
			$ch11 = 0;
			$ch112 = 0;
	    	$no_v = 0;
	    	$outpcu = 0;
	    	$sub_ot = 0;

	    	foreach ($dataall as $k2) {
	    		if( $type == 1 ){
	    			$salary_id = $k2->salary_id;
	    		}else{
	    			$salary_id = $k2->salary_ocsc_id;
	    		}
	    		
		    	$pts = $k2->pts;
		    	$pts2 = $k2->pts2;
		    	$ot = $k2->ot;
		    	$ch8 = $k2->ch8;
				$ch11 = $k2->ch11;
				$ch112 = $k2->ch112;
		    	$no_v = $k2->no_v;
		    	$outpcu = $k2->outpcu;
		    	$sub_ot = $k2->sub_ot;
	    	}
	    	    
	    	if( $field == 'has_pts' ){ $v = $pts; }
	    	if( $field == 'has_pts2' ){ $v = $pts2; }
            if( $field == 'has_ot' ){ $v = $ot; }
            if( $field == 'has_ch8' ){ $v = $ch8; }
			if( $field == 'has_ch11' ){ $v = $ch11; }
			if( $field == 'has_ch112' ){ $v = $ch112; }
            if( $field == 'has_no_v' ){ $v = $no_v; }
            if( $field == 'has_pcu' ){ $v = $outpcu; }
            if( $field == 'has_sub_ot' ){ $v = $sub_ot; }	

            $t .= '<input name="cid[]" id="cid1'.$i.'" type="hidden" value="'.$cid.'" >';
            $t .= '<input name="salary_id1[]" id="salary_id1'.$i.'" type="hidden" value="'.$salary_id.'" >';
            $t .= '<input name="type1[]" id="type1'.$i.'" type="hidden" value="'.$type.'" >';	
	    		    	
	    	$t .= '<tr>';
	    	$t .= '<td>'.$k->cid.'</td>';
	    	$t .= '<td>'.$k->namefull.'</td>';	    
	    	$t .= '<td>'.'<input name="paysp1[]" id="paysp1'.$i.'" type="text" value="'.$v.'" >'.'</td>';    	
	    	$t .= '</tr>';

	    	$i++;
	    }
	    $t .= '</table>';  

	    if( count($data) > 0 ){
	    	$t .= '<div class="fixbar-btn"><a href="#" onclick="update_specialAll( '.$y.', '.$m.','."'$field'".','."'$q'".' )" class="button small">บันทึก</a></div>';
		}
	    return $t;
	}

	/**
    * function name : viewspecial1
    * แสดงค่าตอบแทน ทั้งหมด
    * get
    */
	public function viewspecial_all( $y, $m, $t )
	{			

	    /*$sql =' select * from (';	
	    $sql .=' (select concat(n_datageneral.pname,"",n_datageneral.fname," ",n_datageneral.lname) as namefull,'; 
	    $sql .=' n_department.sort, n_datageneral.dep_id, n_datageneral.cid, pts, pts2, ot, ch8, no_v, outpcu, sub_ot, (s_salary_detail.salary_id) as salary_id, (1) as type';
	    $sql .=' from s_salary_detail';
	    $sql .=' left join n_datageneral on n_datageneral.cid = s_salary_detail.cid';
	    $sql .=' inner join n_department on n_department.department_id = n_datageneral.dep_id';
	    $sql .=' where year(s_salary_detail.order_date) = '.$y.' ';   
	    $sql .=' and month(s_salary_detail.order_date) = '.$m.' ';	 
	    $sql .=' and n_datageneral.dep_id='.$dep; 
	    $sql .=' order by n_department.sort asc, n_datageneral.dep_id asc )';
	    $sql .=' union';
	    $sql .=' (select concat(n_datageneral.pname,"",n_datageneral.fname," ",n_datageneral.lname) as namefull, n_department.sort, n_datageneral.dep_id, n_datageneral.cid, pts, pts2, ot, ch8, no_v, outpcu, sub_ot, (s_salary_ocsc_detail.salary_ocsc_id) as salary_id, (2) as type ';
	    $sql .=' from s_salary_ocsc_detail';
	    $sql .=' left join n_datageneral on n_datageneral.cid = s_salary_ocsc_detail.cid';
	    $sql .=' inner join n_department on n_department.department_id = n_datageneral.dep_id';
	    $sql .=' where year(s_salary_ocsc_detail.order_date) = '.$y.' ';
	    $sql .=' and month(s_salary_ocsc_detail.order_date) = '.$m.' ';	 
	    $sql .=' and n_datageneral.dep_id='.$dep;   
	    $sql .=' order by n_department.sort asc, n_datageneral.dep_id asc)';
	    $sql .=' ) as a  order by a.sort asc, a.namefull asc';*/

		$sql =' select concat(n_datageneral.pname,"",n_datageneral.fname," ",n_datageneral.lname) as namefull,'; 
	    $sql .=' n_department.sort, n_datageneral.dep_id, n_datageneral.cid';
	    $sql .=' from n_datageneral';
	    $sql .=' inner join n_department on n_department.department_id = n_datageneral.dep_id';
	    $sql .=' order by n_department.sort asc, n_datageneral.q_ot asc';

		$data = DB::Select( $sql );
	    //------------------ Query End --------------------//
	  
	    $t  = '<table  class="tb-taxAll responsive">';  	    
	    $t .= '<tr>';
	    $t .= '<th width="70">CID</th> <th width="150">ชื่อ-สกุล</th> <th width="56">พตส</th> <th width="56">ot</th> <th width="56">ฉ8</th> <th width="56">ฉ11</th>  <th width="56">ไม่ทำเวช</th> <th width="56">ออกหน่วย</th>';
	    $t .= '</tr>';	   

	    $i=0;
	    foreach ( $data as $k ) 
	    {	  

	    	$sql2  = ' select * from s_salary_detail';
	    	$sql2 .= ' where year(s_salary_detail.order_date) = '.$y.' ';
	    	$sql2 .= ' and month(s_salary_detail.order_date) = '.$m.' ';
	    	$sql2 .= ' and cid= '.$k->cid.' ';
	    	$data2 = DB::Select( $sql2 );

	    	$sql3  = ' select * from s_salary_ocsc_detail';
	    	$sql3 .= ' where year(s_salary_ocsc_detail.order_date) = '.$y.' ';
	    	$sql3 .= ' and month(s_salary_ocsc_detail.order_date) = '.$m.' ';
	    	$sql3 .= ' and cid= '.$k->cid.' ';
	    	$data3 = DB::Select( $sql3 );

	    	$dataall = array();
	    	$type = 0;

	    	if( count($data2) > 0 ){
	    		$dataall = $data2;
	    		$type = 1;
	    	}
	    	if( count($data3) > 0 ){
	    		$dataall = $data3;
	    		$type = 2;
	    	}

	    	if( $type == 0 ){
	    		$chtype = DB::table('n_position_salary')->select('level')->where('cid', $k->cid)->orderBy('salaryID', 'desc')->limit(1)->first();
	    		
	    		if(count($chtype) > 0){
		    		if( $chtype->level == 'ข้าราชการ' || $chtype->level == 'ลูกจ้างประจำ' ){
		    			$type = '2';
		    		}

		    		if( $chtype->level == 'พกส.(ปฏิบัติงาน)' || $chtype->level == 'ลูกจ้างชั่วคราว' ||  $chtype->level == 'ลูกจ้างรายวัน' ){
		    			$type = '1';
		    		}
	    		}else{
	    			$type = '1';
	    		}
	    	}

	    	$salary_id = 0;
	    	$pts = 0;
	    	$pts2 = 0;
	    	$ot = 0;
	    	$ch8 = 0;
			$ch11 = 0;
	    	$no_v = 0;
	    	$outpcu = 0;
	    	$sub_ot = 0;

	    	foreach ($dataall as $k2) {
	    		if( $type == 1 ){
	    			$salary_id = $k2->salary_id;
	    		}else{
	    			$salary_id = $k2->salary_ocsc_id;
	    		}
	    		
		    	$pts = $k2->pts;
		    	$pts2 = $k2->pts2;
		    	$ot = $k2->ot;
		    	$ch8 = $k2->ch8;
				$ch11 = $k2->ch11;
		    	$no_v = $k2->no_v;
		    	$outpcu = $k2->outpcu;
		    	$sub_ot = $k2->sub_ot;
	    	}

	    	$t .= '<input name="cid1[]" id="cid1'.$i.'" type="hidden" value="'.$k->cid.'" >';
	    	$t .= '<input name="salary_id1[]" id="salary_id1'.$i.'" type="hidden" value="'.$salary_id.'" >';
	    	$t .= '<input name="type1[]" id="type1'.$i.'" type="hidden" value="'.$type.'" >';	    	
	    		    	
	    	$t .= '<tr>';
	    	$t .= '<td>'.$k->cid.'</td>';
	    	$t .= '<td>'.$k->namefull.'</td>';	    
	    	$t .= '<td>'.'<input name="ptssp1[]" id="ptssp1'.$i.'" type="text" value="'.$pts.'" >'.'</td>';
	    	$t .= '<td>'.'<input name="otsp1[]" id="otsp1'.$i.'" type="text" value="'.$ot.'" >'.'</td>';
	    	$t .= '<td>'.'<input name="ch8sp1[]" id="ch8sp1'.$i.'" type="text" value="'.$ch8.'" >'.'</td>';
			$t .= '<td>'.'<input name="ch11sp1[]" id="ch11sp1'.$i.'" type="text" value="'.$ch11.'" >'.'</td>';
	    	$t .= '<td>'.'<input name="no_vsp1[]" id="no_vsp1'.$i.'" type="text" value="'.$no_v.'" >'.'</td>';
	    	$t .= '<td>'.'<input name="outpcusp1[]" id="outpcusp1'.$i.'" type="text" value="'.$outpcu.'" >'.'</td>';    	
	    	
	    	$t .= '</tr>';

	    	$i++;
	    }
	    $t .= '</table>';  
	    if( count($data) > 0 ){
	    	$t .= '<div class="fixbar-btn"><a href="#" onclick="update_specialAll_2( '.$y.', '.$m.' )" class="button small">บันทึก</a></div>';
		}
	    return $t;
	}

	/**
    * function name : update_special
    * update data special salary แบบรายการ
    * get
    */
	public function update_special()
	{	
		//$cid, $y, $m, $salary_id, $paysp, $field, $type

		$cid = Input::get('cid');
		$salary_id = Input::get('salary_id');
		$paysp = Input::get('paysp');
		$type = Input::get('type');
		$y = Input::get('y');
		$m = Input::get('m');
		$field = Input::get('field');

		if( $field == 'has_pts' ){ $v = 'pts'; }
		if( $field == 'has_pts2' ){ $v = 'pts2'; }
        if( $field == 'has_ot' ){ $v = 'ot'; }
        if( $field == 'has_ch8' ){ $v = 'ch8'; }
		if( $field == 'has_ch11' ){ $v = 'ch11'; }
		if( $field == 'has_ch112' ){ $v = 'ch112'; }
        if( $field == 'has_no_v' ){ $v = 'no_v'; }
        if( $field == 'has_pcu' ){ $v = 'outpcu'; }
        if( $field == 'has_sub_ot' ){ $v = 'sub_ot'; }   

		$c = count($cid); 
		for ($i=0; $i < $c; $i++) { 
			
			$data = array(
				$v 		=> $paysp[$i]			
			);

			if( $salary_id[$i] > 0 ){
				if( $type[$i] == 1 ){
					$result = DB::table( 's_salary_detail' )->where( 'salary_id', '=', $salary_id[$i] )->update( $data ); 					
				}else{
					$result = DB::table( 's_salary_ocsc_detail' )->where( 'salary_ocsc_id', '=', $salary_id[$i] )->update( $data ); 			
				}
			}else{
				$bank = DB::table('s_bank_acc')->where('cid', $cid[$i])->select('acc_id', 'bank_id', 'bank_acc')->orderby('cid', 'desc')->limit(1)->first();			
				if(count($bank) > 0){
					$bank_id = $bank->bank_id;
					$acc_id = $bank->acc_id;
					$bank_acc = $bank->bank_acc;
				}else{
					$bank_id = 0;
					$acc_id = 0;
					$bank_acc = 0;
				}

				$order_date = $y.'-'.$m.'-25';
				$sys_user = Session::get('cid');

				if( $type[$i] == 1 ){
					$ckr = DB::table('s_salary_detail')->where('cid', $cid[$i])->where(db::raw('year(order_date) ='.$y))->where(db::raw('month(order_date) = '.$m))->count();
					if($ckr == 0){
						$result = DB::table('s_salary_detail')->insert([
							[ 'cid' => $cid[$i], 'bank' => $bank_id, 'bank_acc_id' => $acc_id, 'bank_acc' => $bank_acc, $v => $paysp[$i], 'order_date' => $order_date, 'sys_user' => $sys_user ]
						]);
					}
				}else{
					$ckr = DB::table('s_salary_ocsc_detail')->where('cid', $cid[$i])->where(db::raw('year(order_date) ='.$y))->where(db::raw('month(order_date) = '.$m))->count();
					if($ckr == 0){
						$result = DB::table('s_salary_ocsc_detail')->insert([
							[ 'cid' => $cid[$i], 'bank' => $bank_id, 'bank_acc_id' => $acc_id, 'bank_acc' => $bank_acc, $v => $paysp[$i], 'order_date' => $order_date, 'sys_user' => $sys_user ]
						]);
					}
				}
			}
		}//end for

		

		/*if( $salary_id > 0 ){
			if( $type == 1 ){
				$result = DB::table( 's_salary_detail' )->where( 'salary_id', '=', $salary_id )->update( $data ); 
				return $cid.'::'.$result;		
			}else{
				$result = DB::table( 's_salary_ocsc_detail' )->where( 'salary_ocsc_id', '=', $salary_id )->update( $data ); 
				return $cid.'::'.$result;				
			}
		}else{

			$bank = DB::table('s_bank_acc')->where('cid', $cid)->select('acc_id', 'bank_id', 'bank_acc')->orderby('cid', 'desc')->limit(1)->first();			
			if(count($bank) > 0){
				$bank_id = $bank->bank_id;
				$acc_id = $bank->acc_id;
			}else{
				$bank_id = 0;
				$acc_id = 0;
			}

			$order_date = $y.'-'.$m.'-25';
	        $sys_user = Session::get('cid');

			if( $type == 1 ){
				$result = DB::table('s_salary_detail')->insert([
					[ 'cid' => $cid, 'bank' => $bank_id, 'bank_acc_id' => $acc_id, 'bank_acc' => $bank->bank_acc, $v => $paysp, 'order_date' => $order_date, 'sys_user' => $sys_user ]
				]);
				return $cid.'::'.$result;
			}else{
				$result = DB::table('s_salary_ocsc_detail')->insert([
					[ 'cid' => $cid, 'bank' => $bank_id, 'bank_acc_id' => $acc_id, 'bank_acc' => $bank->bank_acc, $v => $paysp, 'order_date' => $order_date, 'sys_user' => $sys_user ]
				]);
				return $cid.'::'.$result;
			}
		}*/
	}

	/**
    * function name : update_special
    * update data special salary All
    * get
    */
	public function update_special_all( )
	{	
		//$cid, $y, $m, $salary_id, $ptssp1, $otsp1, $ch8sp1, $no_vsp1, $outpcusp1, $type 

		$cid = Input::get('cid');
		$salary_id = Input::get('salary_id');
		$type = Input::get('type');
		$ptssp1 = Input::get('ptssp1');
		$otsp1 = Input::get('otsp1');
		$ch8sp1 = Input::get('ch8sp1');
		$ch11sp1 = Input::get('ch11sp1');
		$no_vsp1 = Input::get('no_vsp1');
		$outpcusp1 = Input::get('outpcusp1');
		$y = Input::get('y');
		$m = Input::get('m');

		$c = count($cid); 
		for ($i=0; $i < $c; $i++) { 
			
			$data = array(
					'pts' 		=> $ptssp1[$i],
					'ot' 		=> $otsp1[$i],
					'ch8' 		=> $ch8sp1[$i],
					'ch11' 		=> $ch11sp1[$i],
					'no_v' 		=> $no_vsp1[$i],
					'outpcu' 	=> $outpcusp1[$i]				
			    );

			if( $salary_id[$i] > 0 ){
				if( $type[$i] == 1 ){
					$result = DB::table( 's_salary_detail' )->where( 'salary_id', '=', $salary_id[$i] )->update( $data ); 
				}else{
					$result = DB::table( 's_salary_ocsc_detail' )->where( 'salary_ocsc_id', '=', $salary_id[$i] )->update( $data ); 
				}
			}else{
				$bank = DB::table('s_bank_acc')->where('cid', $cid[$i])->select('acc_id', 'bank_id', 'bank_acc')->orderby('cid', 'desc')->limit(1)->first();			
				if(count($bank) > 0){
					$bank_id = $bank->bank_id;
					$acc_id = $bank->acc_id;
					$bank_acc = $bank->bank_acc;
				}else{
					$bank_id = 0;
					$acc_id = 0;
					$bank_acc = 0;
				}

				$order_date = $y.'-'.$m.'-25';
				$sys_user = Session::get('cid');

				if( $type[$i] == 1 ){
						$ckr = DB::table('s_salary_detail')->where('cid', $cid[$i])->where(db::raw('year(order_date) ='.$y))->where(db::raw('month(order_date) = '.$m))->count();
						if($ckr == 0){					
							$result = DB::table('s_salary_detail')->insert([
								[   'cid' => $cid[$i], 
									'bank' => $bank_id, 
									'bank_acc_id' => $acc_id, 
									'bank_acc' => $bank_acc, 
									'pts' => $ptssp1[$i],
									'ot' => $otsp1[$i], 
									'ch8' => $ch8sp1[$i], 
									'ch11' => $ch11sp1[$i], 
									'no_v' => $no_vsp1[$i], 
									'outpcu' => $outpcusp1[$i],  
									'order_date' => $order_date, 
									'sys_user' => $sys_user ]
							]);	
						}		
				}else{
						$ckr = DB::table('s_salary_ocsc_detail')->where('cid', $cid[$i])->where(db::raw('year(order_date) ='.$y))->where(db::raw('month(order_date) = '.$m))->count();
						if($ckr == 0){
							$result = DB::table('s_salary_ocsc_detail')->insert([
								[   'cid' => $cid[$i], 
									'bank' => $bank_id, 
									'bank_acc_id' => $acc_id, 
									'bank_acc' => $bank_acc, 
									'pts' => $ptssp1[$i],
									'ot' => $otsp1[$i], 
									'ch8' => $ch8sp1[$i],
									'ch11' => $ch11sp1[$i],  
									'no_v' => $no_vsp1[$i], 
									'outpcu' => $outpcusp1[$i], 
									'order_date' => $order_date, 
									'sys_user' => $sys_user ]
							]);	
						}			
				}			
			}
			
		}//end for

		/*
		if( $salary_id > 0 ){
			if( $type == 1 ){
				$result = DB::table( 's_salary_detail' )->where( 'salary_id', '=', $salary_id )->update( $data ); 
			}else{
				$result = DB::table( 's_salary_ocsc_detail' )->where( 'salary_ocsc_id', '=', $salary_id )->update( $data ); 
			}
		}else{
			$bank = DB::table('s_bank_acc')->where('cid', $cid)->select('acc_id', 'bank_id', 'bank_acc')->orderby('cid', 'desc')->limit(1)->first();			

			$order_date = $y.'-'.$m.'-25';
	        $sys_user = Session::get('cid');

			if( $type == 1 ){
				if( count($bank) > 0 ){
					$result = DB::table('s_salary_detail')->insert([
					    [   'cid' => $cid, 
					    	'bank' => $bank->bank_id, 
					    	'bank_acc_id' => $bank->acc_id, 
					    	'bank_acc' => $bank->bank_acc, 
					    	'pts' => $ptssp1,
					    	'ot' => $otsp1, 
					    	'ch8' => $ch8sp1, 
					    	'no_v' => $no_vsp1, 
					    	'outpcu' => $outpcusp1,  
					    	'order_date' => $order_date, 
					    	'sys_user' => $sys_user ]
					]);
				}			
			}else{
				if( count($bank) > 0 ){
					$result = DB::table('s_salary_ocsc_detail')->insert([
					    [   'cid' => $cid, 
					    	'bank' => $bank->bank_id, 
					    	'bank_acc_id' => $bank->acc_id, 
					    	'bank_acc' => $bank->bank_acc, 
					    	'pts' => $ptssp1,
					    	'ot' => $otsp1, 
					    	'ch8' => $ch8sp1, 
					    	'no_v' => $no_vsp1, 
					    	'outpcu' => $outpcusp1, 
					    	'order_date' => $order_date, 
					    	'sys_user' => $sys_user ]
					]);
				} 				
			}			
		}*/
	}

	//================================ Meter water =====================================//
	public function unit_water()
	{
		if( Session::get('level') != '' )
		{				
			$data = DB::table( 's_general_data' )->first();	
			return View::make( 'tax.unitwater', array( 'data' => $data ) );			
		}
		else
		{
		  return View::make('login.index');
		}
	}

	public function post_general_update( $id )
	{
		$unitwater  = Input::get( 'unitwater' );
		
        $data = array(
            'unitwater'  => $unitwater                                      		            	                       
        );  
            
        $result = DB::table( 's_general_data' )->where( 'generalID', '=', $id )->update( $data );	        
        if( $result )
        {
        	return Redirect::to( 'special/unit_water' )->with( 'success_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว' ); 
        }
        else
        {
        	return Redirect::to( 'special/unit_water' )->with( 'error_message', 'ไม่สามารถแก้ไขข้อมูลได้ กรุณาแจ้งผู้ดูแลระบบ' ); 
        }	     
	}

	public function add_meter()
	{
		if( Session::get('level') != '' )
		{	
			$data = DB::Select( ' select * from s_water_meter ' );
			return View::make( 'tax.addmeter', array( 'data' => $data ) );			
		}
		else
		{
		  return View::make('login.index');
		}
	}	

	public function add_meter_todb()
	{
		$data = Input::all();

		$chk = DB::Select( ' select * from s_water_meter where name_meter="'.$data['m'].'" ' );

		if( count($chk) == 0 )
		{
			$result = DB::insert( 'insert into s_water_meter ( name_meter ) values ( ? )', 
        		array(           			 
        			$data['m']       			  
        	    )); 			
		}

		$data = DB::Select( ' select * from s_water_meter ' );
		$t  = '<table  class="responsive">';  	    
	    $t .= '<tr>';
	    $t .= '<th width="80">ลำดับ</th> <th>เลขมิเตอร์น้ำ</th> <th width="50">ลบ</th>';
	    $t .= '</tr>';	   

	    $i=0;
	    foreach ( $data as $k ) 
	    {	
	    	$i++;  	    	
	    	$t .= '<tr>';
	    	$t .= '<td>'.$i.'</td>';		    
	    	$t .= '<td>'.$k->name_meter.'</td>';	
	    	$t .= '<td><a href="#" onclick="del_meter( '.$k->meter_id.' )"><i class="fi-x small"></i></a></td>';    	     	
	    	$t .= '</tr>';		    	
	    }
	    $t .= '</table>';  	  
	    return $t;			 		
	}

	public function del_meter( $id )
	{
		$result = Water::where( 'meter_id', $id )->delete();

	   if( $result )
        {
        	$data = DB::Select( ' select * from s_water_meter ' );
			$t  = '<table  class="responsive">';  	    
		    $t .= '<tr>';
		    $t .= '<th width="80">ลำดับ</th> <th>เลขมิเตอร์น้ำ</th> <th width="50">ลบ</th>';
		    $t .= '</tr>';	   

		    $i=0;
		    foreach ( $data as $k ) 
		    {	
		    	$i++;  	    	
		    	$t .= '<tr>';
		    	$t .= '<td>'.$i.'</td>';		    
		    	$t .= '<td>'.$k->name_meter.'</td>';	
		    	$t .= '<td><a href="#" onclick="del_meter( '.$k->meter_id.' )"><i class="fi-x small"></i></a></td>';    	     	
		    	$t .= '</tr>';		    	
		    }
		    $t .= '</table>';  	  
		    return $t;	
        }
        else
        {
        	return 'no';
        }	   
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

	public function add_emp_meter()
	{
		$meter = DB::table( 's_water_meter' )->get();
		return View::make( 'tax.add_empmeter', array( 'meter' => $meter ) );	
	}

	public function view_empmeter( $meter )
	{
		$sql  = ' select s.meter_id, s.name_meter, n.cid, concat(n.pname,"",n.fname," ",n.lname) as namefull  from s_water_meter s';
		$sql .= ' inner join s_water_meter_emp e on e.meter=s.meter_id ';
		$sql .= ' left join n_datageneral n on n.cid=e.cid where s.meter_id='.$meter.' ';		

		$data = DB::Select( $sql );
		if( count($data) > 0 )
		{
			$t  = '<table  class="responsive">';  	    
		    $t .= '<tr>';
		    $t .= '<th width="80">ลำดับ</th> <th  width="150">เลขมิเตอร์น้ำ</th> <th>ชื่อ-สกุล</th>  <th width="50">ลบ</th>';
		    $t .= '</tr>';	   

		    $i=0;
		    foreach ( $data as $k ) 
		    {	
		    	$i++;  	    	
		    	$t .= '<tr>';
		    	$t .= '<td>'.$i.'</td>';		    
		    	$t .= '<td>'.$k->name_meter.'</td>';	
		    	$t .= '<td>'.$k->namefull.'</td>';
		    	$t .= '<td><a href="#" onclick="del_empmeter( '.$k->meter_id.','.$k->cid.' )"><i class="fi-x small"></i></a></td>';    	     	
		    	$t .= '</tr>';		    	
		    }
		    $t .= '</table>'; 
		    return $t;	
		}
		else{
			return '';
		}
	}

	public function update_empmeter( $cid, $meter )
	{		
        //Insert
		$result = DB::insert( 'insert into s_water_meter_emp ( meter, cid ) values ( ?, ? )', 
		array(           			 
			$meter, 
			$cid			
	    ));      
        
        $sql  = ' select s.meter_id, s.name_meter, n.cid, concat(n.pname,"",n.fname," ",n.lname) as namefull  from s_water_meter s';
		$sql .= ' inner join s_water_meter_emp e on e.meter=s.meter_id ';
		$sql .= ' left join n_datageneral n on n.cid=e.cid where s.meter_id='.$meter.' ';		

		$data = DB::Select( $sql );
		$t  = '<table  class="responsive">';  	    
	    $t .= '<tr>';
	    $t .= '<th width="80">ลำดับ</th> <th  width="150">เลขมิเตอร์น้ำ</th> <th>ชื่อ-สกุล</th>  <th width="50">ลบ</th>';
	    $t .= '</tr>';	   

	    $i=0;
	    foreach ( $data as $k ) 
	    {	
	    	$i++;  	    	
	    	$t .= '<tr>';
	    	$t .= '<td>'.$i.'</td>';		    
	    	$t .= '<td>'.$k->name_meter.'</td>';	
	    	$t .= '<td>'.$k->namefull.'</td>';
	    	$t .= '<td><a href="#" onclick="del_empmeter( '.$k->meter_id.','.$k->cid.' )"><i class="fi-x small"></i></a></td>';    	     	
	    	$t .= '</tr>';			    	
	    }
	    $t .= '</table>';  	  
	    return $t;	
	}

	public function del_empmeter( $id, $cid )
	{		
		$result = WaterEmp::where( 'meter', $id )->where( 'cid', $cid )->delete();
        
        $sql  = ' select s.meter_id, s.name_meter, n.cid, concat(n.pname,"",n.fname," ",n.lname) as namefull  from s_water_meter s';
		$sql .= ' inner join s_water_meter_emp e on e.meter=s.meter_id ';
		$sql .= ' left join n_datageneral n on n.cid=e.cid where s.meter_id='.$id.' ';		

		$data = DB::Select( $sql );
		$t  = '<table  class="responsive">';  	    
	    $t .= '<tr>';
	    $t .= '<th width="80">ลำดับ</th> <th  width="150">เลขมิเตอร์น้ำ</th> <th>ชื่อ-สกุล</th>  <th width="50">ลบ</th>';
	    $t .= '</tr>';	   

	    $i=0;
	    foreach ( $data as $k ) 
	    {	
	    	$i++;  	    	
	    	$t .= '<tr>';
	    	$t .= '<td>'.$i.'</td>';		    
	    	$t .= '<td>'.$k->name_meter.'</td>';	
	    	$t .= '<td>'.$k->namefull.'</td>';
	    	$t .= '<td><a href="#" onclick="del_empmeter( '.$k->meter_id.','.$k->cid.' )"><i class="fi-x small"></i></a></td>';    	     	
	    	$t .= '</tr>';			    	
	    }
	    $t .= '</table>';  	  
	    return $t;
	}

	//==================================== Water ==========================//
	public function add_water()
	{
		$year = DB::select( ' select (year(order_date)+543) as year from s_salary_detail group by  year(order_date) ' ); 
	
		return View::make( 'tax.add_water', array( 'year' => $year ) );	
	}

	public function view_water( $y, $m )
	{
		if( $m == 1 ){
			$y_ole = $y - 1;
			$m_ole = $m - 1;
		}else{
			$y_ole = $y;
			$m_ole = $m - 1;
		}

		$sql  = ' select  n.*, s.meter_id, s.name_meter';
		$sql .= ' ,( select water_end from s_water where cid=e.cid and s.name_meter=name_meter and year='.$y_ole.' and month='.$m_ole.' ) as water_start';
		$sql .= ' ,( select water_end from s_water where cid=e.cid and s.name_meter=name_meter and year='.$y.' and month='.$m.' ) as water_end';
		$sql .= ' ,( select unit from s_water where cid=e.cid and s.name_meter=name_meter and year='.$y.' and month='.$m.' ) as unit';
		$sql .= ' ,( select money from s_water where cid=e.cid and s.name_meter=name_meter and year='.$y.' and month='.$m.' ) as money';
		$sql .= ' from s_water_meter s';
		$sql .= ' inner join s_water_meter_emp e on e.meter=s.meter_id ';
		$sql .= ' left join n_datageneral n on n.cid=e.cid';
		$sql .= ' group by s.name_meter, e.cid order by s.name_meter';	

		$data = DB::Select( $sql );

		$unitwater = DB::table( 's_general_data' )->first();

		return View::make( 'tax.view_water', array( 'y' => $y, 'm' => $m, 'data' => $data, 'unitwater' => $unitwater ) );	
	}

	private function check_water( $name_meter, $y, $m, $cid )
	{
		$data = DB::Select( 'select * from s_water where name_meter="'.$name_meter.'" and year='.$y.' and month='.$m.' and cid="'.$cid.'" ' );
		if( count($data) == 0 ){
			return true;
		}else{
			return false;
		}
	}

	public function savewater()
	{

		$data = Input::all();

		$name_meter = explode( ',', $data['name_meter'] );
		$water_start = explode( ',', $data['water_start'] );
		$water_end = explode( ',', $data['water_end'] );
		$unit = explode( ',', $data['unit'] );
		$money = explode( ',', $data['money'] );
		$cidwater = explode( ',', $data['cidwater'] );
		$y = $data['y'];
		$m = $data['m'];

		for($i=0; $i <= count($name_meter)-1; $i++)
		{
			if( $this->check_water( $name_meter[$i], $y, $m, $cidwater[$i] ) == true )
			{
				//Insert
				$result = DB::insert( 'insert into s_water ( name_meter, cid, date_regis, month, year, water_start, water_end, unit, money ) values ( ?, ?, ?, ?, ?, ?, ?, ?, ? )', 
        		array(           			 
        			$name_meter[$i], 
        			$cidwater[$i],
        			date('y-m-d'),
        			$m,
        			$y,
        			$water_start[$i],
        			$water_end[$i],
        			$unit[$i],
        			$money[$i]
        	    ));        	    
			}
			else
			{
				//Update
				$data = array(		           		          
		            'date_regis'  	=> date('y-m-d'),		          
		            'water_start'  	=> $water_start[$i],
		            'water_end'  	=> $water_end[$i],
		            'unit'  		=> $unit[$i],
		            'money'  		=> $money[$i]
		        );  

		        $result = DB::table( 's_water' )->where( 'cid', '=', $cidwater[$i] )->where( 'month', '=', $m )->where( 'year', '=', $y )->update( $data );				               
			}

			$w_salary = array( 'water' => $money[$i] );		

			$chkcid_1 = DB::Select( 'select cid from s_salary_detail where cid= '.$cidwater[$i].' ' );
			if( count($chkcid_1) > 0 )
			{				
        		DB::table( 's_salary_detail' )->where( 'cid', '=', $cidwater[$i] )->whereRaw( ' month(order_date) = '.$m.' ' )->whereRaw( ' year(order_date) = '.$y.' ' )->update( $w_salary );    		
        	}        

        	$chkcid_2 = DB::Select( 'select cid from s_salary_ocsc_detail where cid= '.$cidwater[$i].' ' );
        	if( count($chkcid_2) > 0 )
			{				
        		DB::table( 's_salary_ocsc_detail' )->where( 'cid', '=', $cidwater[$i] )->whereRaw( ' month(order_date) = '.$m.' ' )->whereRaw( ' year(order_date) = '.$y.' ' )->update( $w_salary );
        	}
		}// end for

	}

	//==================================== Elec Home ==========================//
	public function add_home()
	{
		$home = DB::table( 's_elec_home' )->orderBY("elec_number","asc")->get();
		return View::make( 'tax.add_home', array( 'home' => $home ) );
	}

	public function add_home_todb()
	{
		$data = Input::all();

		$elec_number = $data['elec_number']; 
		$elec_home   = $data['elec_home'];

		$result = DB::insert( 'insert into s_elec_home ( elec_number, elec_home ) values ( ?, ? )', 
        		array(           			 
        			$elec_number, 
        			$elec_home       		
        	    )); 

	}

	public function del_home( $id )
	{		
		$result = Elec::where( 'home_id', $id )->delete();
	}

	public function add_emp_home()
	{
		$home = DB::Select( ' select home_id, concat( elec_number, ":", elec_home ) as name from s_elec_home ' );
		return View::make( 'tax.add_emphome', array( 'home' => $home ) );
	}

	public function view_emphome( $id )
	{
		$sql  = ' select s.home_id, s.elec_number, s.elec_home, n.cid, concat(n.pname,"",n.fname," ",n.lname) as namefull  from s_elec_home s';
		$sql .= ' inner join s_elec_home_emp e on e.home=s.home_id ';
		$sql .= ' left join n_datageneral n on n.cid=e.cid where s.home_id='.$id.' ';	

		$data = DB::Select( $sql );
		return View::make( 'tax.view_home', array( 'data' => $data ) );
	}

	public function update_emphome()
	{
		$data = Input::all();

		$home_id = $data['home_id']; 
		$cid     = $data['cid'];
                   
		$result = DB::insert( 'insert into s_elec_home_emp ( home, cid ) values ( ?, ? )', 
		array(           			 
			$home_id, 
			$cid			
	    ));   

        if( $result ){
        	$sql  = ' select s.home_id, s.elec_number, s.elec_home, n.cid, concat(n.pname,"",n.fname," ",n.lname) as namefull  from s_elec_home s';
			$sql .= ' inner join s_elec_home_emp e on e.home=s.home_id ';
			$sql .= ' left join n_datageneral n on n.cid=e.cid where s.home_id='.$home_id.' ';	

			$data = DB::Select( $sql );
			return View::make( 'tax.view_home', array( 'data' => $data ) );
        }
	}

	public function del_emphome( $id, $cid )
	{	
		$result = ElecEmp::where( 'home', $id )->where( 'cid', $cid )->delete();			
	}

	//==================================== Elec ==========================//
	public function add_elec()
	{
		$year = DB::select( ' select (year(order_date)+543) as year from s_salary_detail group by  year(order_date) ' ); 
	
		return View::make( 'tax.add_elec', array( 'year' => $year ) );	
	}

	public function view_elec( $y, $m )
	{
		$sql  = ' select  n.*, s.home_id, s.elec_number, s.elec_home';
		$sql .= ' ,( select money from s_elec where month='.$m.' and year='.$y.' and cid=e.cid  ) as money  ';
		$sql .= ' from s_elec_home s';
		$sql .= ' inner join s_elec_home_emp e on e.home=s.home_id ';
		$sql .= ' left join n_datageneral n on n.cid=e.cid';
		$sql .= ' group by s.elec_number, e.cid order by s.elec_number asc';	

		$data = DB::Select( $sql );		

		return View::make( 'tax.view_elec', array( 'y' => $y, 'm' => $m, 'data' => $data ) );
	}

	private function check_elec( $elec_number, $y, $m, $cid )
	{
		$data = DB::Select( 'select * from s_elec where elec_number="'.$elec_number.'" and year='.$y.' and month='.$m.' and cid="'.$cid.'" ' );
		if( count($data) == 0 ){
			return true;
		}else{
			return false;
		}
	}

	public function saveelec()
	{

		$data = Input::all();

		$elec_number = explode( ',', $data['elec_number'] );
		$elec_home = explode( ',', $data['elec_home'] );
		$cidelec = explode( ',', $data['cidelec'] );		
		$moneyelec = explode( ',', $data['moneyelec'] );
		$y = $data['y'];
		$m = $data['m'];

		for($i=0; $i <= count($elec_number)-1; $i++)
		{
			if( $this->check_elec( $elec_number[$i], $y, $m, $cidelec[$i] ) == true )
			{
				//Insert
				$result = DB::insert( 'insert into s_elec ( elec_number, elec_home, month, year, cid, money ) values ( ?, ?, ?, ?, ?, ? )', 
        		array(           			 
        			$elec_number[$i], 
        			$elec_home[$i],        			     			
        			$m,
        			$y,
        			$cidelec[$i], 
        			$moneyelec[$i]
        	    ));        	    
			}
			else
			{
				//Update
				$data = array(		           		          		            
		            'money'  		=> $moneyelec[$i]
		        );  

		        $result = DB::table( 's_elec' )->where( 'cid', '=', $cidelec[$i] )->where( 'month', '=', $m )->where( 'year', '=', $y )->update( $data );				               
			}

			$w_salary = array( 'elec' => $moneyelec[$i] );

			$chkcid_1 = DB::Select( 'select cid from s_salary_detail where cid= '.$cidelec[$i].' ' );
			if( count($chkcid_1) > 0 )
			{								
        		DB::table( 's_salary_detail' )->where( 'cid', '=', $cidelec[$i] )->whereRaw( ' month(order_date) = '.$m.' ' )->whereRaw( ' year(order_date) = '.$y.' ' )->update( $w_salary );      	
        	}

        	$chkcid_2 = DB::Select( 'select cid from s_salary_ocsc_detail where cid= '.$cidelec[$i].' ' );
        	if( count($chkcid_2) > 0 )
			{				
        		DB::table( 's_salary_ocsc_detail' )->where( 'cid', '=', $cidelec[$i] )->whereRaw( ' month(order_date) = '.$m.' ' )->whereRaw( ' year(order_date) = '.$y.' ' )->update( $w_salary );
        	}
		}// end for

	}


	//=================================== Tax Special ======================================//
	
	public function continuous_sp_home()
	{
		if( Session::get('level') != '' )
		{
			$y = DB::Select( ' select year(order_date) as year1 from s_salary_ocsc_detail group by year(order_date) order by year(order_date) desc ' );	
			return View::make( 'tax.continuous_sp_home', array( 'data' => $y ) );
		}
		else
		{
			return View::make('login.index');
		}  
	}

	//POST
	public function continuous_sp()
	{
		$y = Input::get('y_sp2');	

		if( Session::get('level') != '' )
		{										
		    $pdf = new TCPDF();
		    $pdf->SetPrintHeader(false);
		    $pdf->SetPrintFooter(false);			   		   

		    $n = DB::select( 'select * from s_general_data' );	
		    foreach ($n as $k) {
		      	$name 		= $k->name;
		      	$address 	= $k->address;
		      	$tax_id 	= $k->tax_id;
		      	$address2 	= $k->address2;
		      	$tax_id2 	= $k->tax_id2;
		      	$director 	= $k->director;
		    } 		    

		    //--- ไม่รวม ค่า พตส. เงินงบประมาณ --//
		    
		    $sql = ' select * from (select concat(n.pname,"",n.fname," ",n.lname) as name, s.cid, s.tax_id, sum(game_sp) as salary,sum(s.ch11+s.special+s.pts+s.ot+s.ch8+s.no_v+s.outpcu) as special from s_salary_ocsc_detail s left join n_datageneral n on n.cid=s.cid  where  year(s.order_date)='.$y.' group by s.cid order by n.datainfoID asc) as a where a.special > 0 ';		    		    		    
		    
		    $result = DB::select( $sql );		    

		    foreach ( $result as $key ) {		    

		    	$pdf->AddPage('P', 'A4');

		    	$pdf->SetFont('freeserif','B',11,'',true);		    
	 			$pdf->MultiCell(185, 5, 'เลขที่ งบ. ..................... ', 0, 'R', 0, 1, '', '', true);

	 			$pdf->SetFont('freeserif','B',16,'',true);
		    	$pdf->SetY(25);
	 			$pdf->SetX(18);
	 			$pdf->MultiCell(177, 5, 'หนังสือรับรองการหักภาษี ณ ที่จ่าย', 0, 'C', 0, 1, '', '', true);

	 			$pdf->SetY(34);
	 			$pdf->SetX(18);
	 			$pdf->MultiCell(177, 5, 'ตามมาตรา 50 ทวิ แห่งประมวลรัษฎากร', 0, 'C', 0, 1, '', '', true);
				
	 			//===== แนวตั้ง =====//
	 			$linever1 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(18, 190, 18,50, $linever1);

				$linever2 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(80, 190, 80,50, $linever2);

				$linever3 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(110, 190, 110,50, $linever3);

				$linever4 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(135, 190, 135,50, $linever4);

				$linever5 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(165, 190, 165,50, $linever5);

				$linever6 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(195, 190, 195,50, $linever6);

				//===== แนวนอน =====//
	 			$linetop = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(18, 50, 195,50, $linetop);

				$linetop2 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(80, 63, 195,63, $linetop2);

				$linetop3 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(18, 120, 80,120, $linetop3);

				$linetop4 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(80, 180, 195,180, $linetop4);

				$linetop5 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(18, 190, 195,190, $linetop5);

				//======= text in box 1 ========//
				$pdf->SetFont('freeserif','',13,'',true);
				$pdf->SetY(52);
	 			$pdf->SetX(19);
	 			$pdf->MultiCell(62, 5, 'ชื่อและที่อยู่ของผู้มีหน้าที่หักภาษี ณ ที่จ่าย บุคคลคณะบุคคล นิติบุคคล ส่วนราชการ องค์การ รัฐวิสาหกิจ ฯลฯ ', 0, 'L', 0, 1, '', '', true);

	 			$pdf->SetFont('freeserif','B',13,'',true);
				$pdf->SetY(82);
	 			$pdf->SetX(19);
	 			$pdf->MultiCell(62, 5, $address, 0, 'L', 0, 1, '', '', true);

	 			$pdf->SetFont('freeserif','B',13,'',true);
				$pdf->SetY(105);
	 			$pdf->SetX(19);
	 			$pdf->MultiCell(40, 5, $tax_id, 0, 'L', 0, 1, '', '', true);

	 			//======= text in box 2 ========//
	 			$pdf->SetFont('freeserif','',13,'',true);
				$pdf->SetY(122);
	 			$pdf->SetX(19);
	 			$pdf->MultiCell(62, 5, 'ชื่อและที่อยู่ของผู้ถูกหักภาษี ณ ที่จ่าย', 0, 'L', 0, 1, '', '', true);

	 			$pdf->SetFont('freeserif','B',12,'',true);
				$pdf->SetY(137);
	 			$pdf->SetX(21);
	 			$pdf->MultiCell(59, 5, $key->name, 0, 'L', 0, 1, '', '', true);
	
	 			$pdf->SetFont('freeserif','',13,'',true);
				$pdf->SetY(145);
	 			$pdf->SetX(19);
	 			$pdf->MultiCell(62, 5, $address, 0, 'L', 0, 1, '', '', true);

	 			$pdf->SetFont('freeserif','B',13,'',true);
				$pdf->SetY(165);
	 			$pdf->SetX(19);
	 			$pdf->MultiCell(62, 5, 'เลขประจำตัวผู้เสียภาษีของผู้ถูกหักภาษี ณ ที่จ่าย', 0, 'L', 0, 1, '', '', true);

	 			$pdf->SetFont('freeserif','',12,'',true);
				$pdf->SetY(178);
	 			$pdf->SetX(22);
	 			$pdf->MultiCell(62, 5, $key->cid, 0, 'L', 0, 1, '', '', true);

	 			//======= text in box 3 header content ========//
	 			$pdf->SetFont('freeserif','B',13,'',true);
				$pdf->SetY(54);
	 			$pdf->SetX(83);
	 			$pdf->MultiCell(32, 5, 'เงินได้ที่จ่าย', 0, 'L', 0, 1, '', '', true);

	 			$pdf->SetFont('freeserif','B',13,'',true);
				$pdf->SetY(54);
	 			$pdf->SetX(111);
	 			$pdf->MultiCell(32, 5, 'ปีภาษีที่จ่าย', 0, 'L', 0, 1, '', '', true);

	 			$pdf->SetFont('freeserif','B',13,'',true);
				$pdf->SetY(54);
	 			$pdf->SetX(138);
	 			$pdf->MultiCell(32, 5, 'จำนวนเงิน', 0, 'L', 0, 1, '', '', true);

	 			$pdf->SetFont('freeserif','B',13,'',true);
				$pdf->SetY(54);
	 			$pdf->SetX(167);
	 			$pdf->MultiCell(32, 5, 'ภาษีที่หักไว้', 0, 'L', 0, 1, '', '', true);


	 			//============= text in content ================//
	 			$pdf->SetFont('freeserif','',12,'',true);

	 			//-----col 1
				$pdf->SetY(70);
	 			$pdf->SetX(80);
	 			$pdf->MultiCell(30, 5, 'เงินเดือน ค่าจ้าง บำนาญ เบี้ยเลี้ยง โบนัส ตามมาตรา 40(1)', 0, 'L', 0, 1, '', '', true);

				$pdf->SetY(95);
	 			$pdf->SetX(80);
	 			$pdf->MultiCell(31, 5, 'เงินประจำตำแหน่ง', 0, 'L', 0, 1, '', '', true);

	 			$pdf->SetY(104);
	 			$pdf->SetX(80);
	 			$pdf->MultiCell(27, 5, 'เงินค่าตอบแทนพิเศษ พตส ค่าครองชีพ', 0, 'L', 0, 1, '', '', true);

	 			//-----col 2
	 			$pdf->SetY(70);
	 			$pdf->SetX(116);
	 			$pdf->MultiCell(31, 5, ($y+543)  , 0, 'L', 0, 1, '', '', true);

	 			//-----col 3
	 			$pdf->SetY(70);
	 			$pdf->SetX(135);
	 			//$pdf->MultiCell(30, 5, number_format( $key->salary, 2 ), 0, 'R', 0, 1, '', '', true);
	 			$pdf->MultiCell(30, 5, '', 0, 'R', 0, 1, '', '', true);

	 			$pdf->SetY(95);
	 			$pdf->SetX(135);
	 			$pdf->MultiCell(30, 5, '', 0, 'R', 0, 1, '', '', true);

	 			$pdf->SetY(104);
	 			$pdf->SetX(135);
	 			$pdf->MultiCell(30, 5, number_format( $key->special, 2 ), 0, 'R', 0, 1, '', '', true);

	 			//-----col 4
	 			$pdf->SetY(70);
	 			$pdf->SetX(165);
	 			$pdf->MultiCell(30, 5, '', 0, 'R', 0, 1, '', '', true);
	 			

	 			//============= text in box 4 footer sum ============//

	 			$pdf->SetFont('freeserif','B',13,'',true);
				$pdf->SetY(182);
	 			$pdf->SetX(89);
	 			$pdf->MultiCell(32, 5, 'รวม', 0, 'L', 0, 1, '', '', true);

	 			$pdf->SetFont('freeserif','',12,'',true);
	 			$pdf->SetY(182);
	 			$pdf->SetX(135);
	 			$pdf->MultiCell(30, 5, number_format( $key->special, 2 ), 0, 'R', 0, 1, '', '', true);

	 			$pdf->SetFont('freeserif','',12,'',true);
	 			$pdf->SetY(182);
	 			$pdf->SetX(165);
	 			$pdf->MultiCell(30, 5, '', 0, 'R', 0, 1, '', '', true);


	 			//============= text footer ================//
	 			$pdf->SetFont('freeserif','',12,'',true);
				
	 			$pdf->SetFont('freeserif','B',12,'',true);
	 			$pdf->SetY(220);
	 			$pdf->SetX(18);
	 			$pdf->MultiCell(177, 5, 'ข้าพเจ้าขอรับรองว่า ข้อความและตัวเลขดังกล่าวข้างต้นนี้ถูกต้องตามความเป็นจริงทุกประการ', 0, 'R', 0, 1, '', '', true);

	 			$pdf->SetFont('freeserif','',12,'',true);
	 			$pdf->SetY(235);
	 			$pdf->SetX(32);
	 			$pdf->MultiCell(170, 5, 'ลงชื่อ...........................................................ผู้มีหน้าที่หักภาษี ณ ที่จ่าย', 0, 'C', 0, 1, '', '', true);	 

	 			$pdf->SetY(245);
	 			$pdf->SetX(32);
	 			$pdf->MultiCell(140, 5, $director, 0, 'C', 0, 1, '', '', true);

	 			$pdf->SetY(255);
	 			$pdf->SetX(32);
	 			$pdf->MultiCell(140, 5, 'นายแพทย์เชี่ยวชาญ', 0, 'C', 0, 1, '', '', true);

	 			$pdf->SetY(265);
	 			$pdf->SetX(32);
	 			$pdf->MultiCell(140, 5, 'ผู้อำนวยการโรงพยาบาลโนนไทย', 0, 'C', 0, 1, '', '', true);			
	 			
		    }

			$filename = storage_path() . '/report_tax_continuous_pts_emp2.pdf';
		    // Response::download($filename);
		    $contents = $pdf->output($filename, 'I');
			$headers = array(
			    'Content-Type' => 'application/pdf',
			);
			return Response::make($contents, 200, $headers);
		}
		else
		{
			return View::make('login.index');
		}	
	}


	/*
	* function name itpc_sp_home
	* ภงด 1 ก ค่าตอบแทน  Home
	*
	*/
	public function itpc_sp_home()
	{
		if( Session::get('level') != '' )
		{
			$y = DB::Select( ' select year(order_date) as year1 from s_salary_ocsc_detail group by year(order_date) order by year(order_date) desc ' );	
			return View::make( 'tax.itpc_sp_home', array( 'data' => $y ) );
		}
		else
		{
			return View::make('login.index');
		}  
	}

	public function itpc_sp()
	{
		$y = Input::get('y_sp');

		if( Session::get('level') != '' )
		{
			$n = DB::select( 'select * from s_general_data' );	
		    foreach ($n as $k) {
		      	$name 		= $k->name;
		      	$address 	= $k->address;
		      	$tax_id 	= $k->tax_id;
		      	$director 	= $k->director;
		    } 

			$pdf = new TCPDF();			
			
			$pdf->SetHeaderData('', '', 'ภ.ง.ด 1 ก พิเศษ', 'เลขประจำตัวผู้เสียภาษีอากร(ของผู้มีหน้าที่หักภาษี ณ ที่จ่าย)       '.$tax_id );
			
			// set header and footer fonts
			$pdf->setHeaderFont(Array('freeserif', '', 13));
			$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));	  

			$pdf->SetMargins(5, PDF_MARGIN_TOP, 5);
			$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
			$pdf->SetFooterMargin(PDF_MARGIN_FOOTER); 	   		   
		   
		   //-------------------- ไม่รวมค่า พตส. เงินงบประมาณ ---------------//
		    $sql = ' select * from (select concat(n.pname,"",n.fname," ",n.lname) as name, s.cid, s.tax_id, sum(game_sp) as salary, sum(s.ch11+s.special+s.pts+s.ot+s.ch8+s.no_v+s.outpcu) as special from s_salary_ocsc_detail s left join n_datageneral n on n.cid=s.cid  where year(s.order_date)='.$y.' group by s.cid order by n.datainfoID asc) as a where a.special > 0 ';		    
		    $result = DB::select( $sql );
			$j=0;
			$i=0;
			$sum1=0;
			$sum2=0;
			$row=0;
		    foreach ( $result as $key ) 
		    {			    
		    	$row++;

		    	if( $j==4 )
	    		{
	    			$j=0;
	    		}

		    	if( $j == 0)
		    	{		    		
		    		$pdf->AddPage('L', 'letter');	
		    					
		 			//===== แนวตั้ง =====//
		 			$linever1 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(5, 60, 5,18, $linever1);

					$pdf->SetFont('freeserif','',11,'',true);
					$pdf->SetY(35);
		 			$pdf->SetX(5);
		 			$pdf->MultiCell(13, 5, 'ลำดับ', 0, 'C', 0, 1, '', '', true);

					//--col 2
					$linever2 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(18, 60, 18,18, $linever2);

					$pdf->SetY(24);
		 			$pdf->SetX(19);
		 			$pdf->MultiCell(60, 5, 'เลขประจำตัวประชาชน(ของผู้มีเงินได้)', 0, 'L', 0, 1, '', '', true);

		 			$pdf->SetY(34);
		 			$pdf->SetX(19);
		 			$pdf->MultiCell(62, 5, 'เลขประจำตัวผู้เสียภาษีอากร(ของผู้มีเงินได้)', 0, 'L', 0, 1, '', '', true);

		 			$pdf->SetY(49);
		 			$pdf->SetX(19);
		 			$pdf->MultiCell(60, 5, 'ชื่อ ที่อยู่ ผู้มีเงินได้', 0, 'L', 0, 1, '', '', true);


					//--col 3
					$linever3 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(80, 60, 80,18, $linever3);

					$pdf->SetY(24);
		 			$pdf->SetX(81);
		 			$pdf->MultiCell(20, 5, 'วัน เดือน ปี ที่เข้าทำงาน(เฉพาะกรณีเข้าทำงานระหว่างปี)', 0, 'L', 0, 1, '', '', true);



					//--col 4
					$linever4 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(105, 60, 105,18, $linever4);
					
					$linever41 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(116, 60, 116,26, $linever41);

					$linever42 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(126, 60, 126,34, $linever42);

					$linever43 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(137, 60, 137,26, $linever43);

					$linever44 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(150, 60, 150,46, $linever44);

					$pdf->SetY(19);
		 			$pdf->SetX(106);
		 			$pdf->MultiCell(58, 5, '(1)รายการลดหย่อน', 0, 'C', 0, 1, '', '', true);

		 			$pdf->SetY(28);
		 			$pdf->SetX(105);
		 			$pdf->MultiCell(11, 5, 'มีสามีภริยาหรือไม่', 0, 'L', 0, 1, '', '', true);

		 			$pdf->SetY(27);
		 			$pdf->SetX(117);
		 			$pdf->MultiCell(20, 5, 'จำนวนบุตร', 0, 'L', 0, 1, '', '', true);

		 			$pdf->SetY(37);
		 			$pdf->SetX(116);
		 			$pdf->MultiCell(20, 5, 'ศึกษา', 0, 'L', 0, 1, '', '', true);

		 			$pdf->SetY(37);
		 			$pdf->SetX(126);
		 			$pdf->MultiCell(11, 5, 'ไม่ศึกษา', 0, 'L', 0, 1, '', '', true);

		 			$pdf->SetY(27);
		 			$pdf->SetX(137);
		 			$pdf->MultiCell(28, 5, 'ค่าลดหย่อนอื่นๆ', 0, 'L', 0, 1, '', '', true);

		 			$pdf->SetY(37);
		 			$pdf->SetX(137);
		 			$pdf->MultiCell(28, 5, 'จำนวนเงิน', 0, 'C', 0, 1, '', '', true);

		 			$pdf->SetY(49);
		 			$pdf->SetX(136);
		 			$pdf->MultiCell(15, 5, 'บาท', 0, 'C', 0, 1, '', '', true);

		 			$pdf->SetY(49);
		 			$pdf->SetX(149);
		 			$pdf->MultiCell(15, 5, 'สต.', 0, 'C', 0, 1, '', '', true);



					//--col 5
					$linever5 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(165, 60, 165,18, $linever5);

					$linever51 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(194, 60, 194,30, $linever51);

					$linever52 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(212, 60, 212,30, $linever52);

					$linever53 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(233, 60, 233,50, $linever53);

					$pdf->SetY(19);
		 			$pdf->SetX(165);
		 			$pdf->MultiCell(77, 5, 'ประเภทเงินได้พึงประเมิณที่จ่าย(รวมทั้งประโยชน์ที่เพิ่ม อย่างอื่น)ถ้ามากกว่าหนึ่งประเภทให้กรอกเรียงลงไป', 0, 'L', 0, 1, '', '', true);

		 			$pdf->SetY(39);
		 			$pdf->SetX(165);
		 			$pdf->MultiCell(30, 5, '(2)', 0, 'C', 0, 1, '', '', true);
		 			$pdf->SetY(43);
		 			$pdf->SetX(165);
		 			$pdf->MultiCell(30, 5, 'ประเภทเงินได้', 0, 'C', 0, 1, '', '', true);

		 			$pdf->SetY(39);
		 			$pdf->SetX(193);
		 			$pdf->MultiCell(20, 5, '(3)', 0, 'C', 0, 1, '', '', true);
		 			$pdf->SetY(43);
		 			$pdf->SetX(193);
		 			$pdf->MultiCell(20, 5, 'จำนวนคราวที่จ่ายทั้งปี', 0, 'C', 0, 1, '', '', true);
		 			
		 			$pdf->SetY(32);
		 			$pdf->SetX(212);
		 			$pdf->MultiCell(30, 5, 'จำนวนเงินที่จ่ายแต่ละประเภทเฉพาะคนหนึงๆ ทั้งปี', 0, 'L', 0, 1, '', '', true);
		 			
		 			$pdf->SetY(52);
		 			$pdf->SetX(216);
		 			$pdf->MultiCell(20, 5, 'บาท', 0, 'L', 0, 1, '', '', true);

		 			$pdf->SetY(52);
		 			$pdf->SetX(233);
		 			$pdf->MultiCell(8, 5, 'สต.', 0, 'C', 0, 1, '', '', true);


					//--col 6
					$linever6 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(242, 60, 242,18, $linever6);	

					$linever61 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(265, 60, 265,50, $linever61);

					$pdf->SetY(19);
		 			$pdf->SetX(242);
		 			$pdf->MultiCell(33, 5, 'รวมเงินภาษีที่หักและนำส่งในปีที่ล่วงมาแล้ว', 0, 'L', 0, 1, '', '', true);

		 			$pdf->SetY(37);
		 			$pdf->SetX(242);
		 			$pdf->MultiCell(33, 5, 'จำนวนเงิน', 0, 'C', 0, 1, '', '', true);

		 			$pdf->SetY(52);
		 			$pdf->SetX(249);
		 			$pdf->MultiCell(20, 5, 'บาท', 0, 'L', 0, 1, '', '', true);

		 			$pdf->SetY(52);
		 			$pdf->SetX(266);
		 			$pdf->MultiCell(8, 5, 'สต.', 0, 'C', 0, 1, '', '', true);


					$lineverEnd = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(274.5, 60, 274.5,18, $lineverEnd);	


					//======= แนวนอน =========//
					$linehor1 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(274.5, 60, 5,60, $linehor1);

					//--col 4
					$linehor2 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(165, 26, 105,26, $linehor2);

					$linehor3 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(165, 34, 116,34, $linehor3);

					$linehor4 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(165, 46, 137,46, $linehor4);

					//--col 5
					$linehor5 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(274.5, 30, 165,30, $linehor5);

					$linehor6 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(274.5, 50, 212,50, $linehor6);					

				}//end add header

				if( $j == 0 ){
					$x1=95;
					$y1=60;
					$h1=95;
					
					$d1=61;
					$d2=68;
					$d3=74;
					$d4=81;
				}else if( $j == 1 ){
					$x1=130;
					$y1=90;
					$h1=130;
					
					$d1=96;
					$d2=103;
					$d3=109;
					$d4=116;
				}else if( $j == 2 ){
					$x1=165;
					$y1=120;
					$h1=165;
					
					$d1=131;
					$d2=138;
					$d3=144;
					$d4=151;	
				}else if( $j == 3 ){
					$x1=197;
					$y1=150;
					$h1=197;
					
					$d1=166;
					$d2=171;
					$d3=176;
					$d4=181;
				}

				//loop นอน
				$linehor = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(274.5, $x1, 5,$x1, $linehor);

				//loop ตั้ง
				$linever_loop1 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(5, $h1, 5,$y1, $linever_loop1);

				$linever_loop2 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(18, $h1, 18,$y1, $linever_loop2);

				$linever_loop3 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(80, $h1, 80,$y1, $linever_loop3);

				$linever_loop4 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(105, $h1, 105,$y1, $linever_loop4);

				$linever_loop5 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(116, $h1, 116,$y1, $linever_loop5);

				$linever_loop6 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(126, $h1, 126,$y1, $linever_loop6);

				$linever_loop7 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(137, $h1, 137,$y1, $linever_loop7);

				$linever_loop8 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(165, $h1, 165,$y1, $linever_loop8);

				$linever_loop9 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(194, $h1, 194,$y1, $linever_loop9);

				$linever_loop10 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(212, $h1, 212,$y1, $linever_loop10);

				$linever_loop11 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(242, $h1, 242,$y1, $linever_loop11);

				$linever_loop12 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(274.5, $h1, 274.5,$y1, $linever_loop12);

				//loop data
				//
				$pdf->SetFont('freeserif','',11,'',true);
				$pdf->SetY($d1);
	 			$pdf->SetX(8);
	 			$pdf->MultiCell(60, 5, $row, 0, 'L', 0, 0, '', '', true);
				
				//col 1 data
				$pdf->SetFont('freeserif','B',11,'',true);
				$pdf->SetY($d1);
	 			$pdf->SetX(19);
	 			$pdf->MultiCell(60, 5, $key->cid, 0, 'L', 0, 0, '', '', true);	

	 			$pdf->SetFont('freeserif','',11,'',true);
	 			$pdf->SetY($d2);
	 			$pdf->SetX(19);
	 			$pdf->MultiCell(60, 5, $key->tax_id, 0, 'L', 0, 0, '', '', true);	

	 			$pdf->SetFont('freeserif','B',11,'',true);
	 			$pdf->SetY($d3);
	 			$pdf->SetX(19);
	 			$pdf->MultiCell(60, 5, $key->name, 0, 'L', 0, 0, '', '', true);
				
				$pdf->SetFont('freeserif','',11,'',true);
				$pdf->SetY($d4);
	 			$pdf->SetX(19);
	 			$pdf->MultiCell(60, 5, $address, 0, 'L', 0, 0, '', '', true);	 

	 			//col 8
	 			$pdf->SetFont('freeserif','',11,'',true);
				$pdf->SetY($d1);
	 			$pdf->SetX(166);
	 			$pdf->MultiCell(28, 5, 'เงินเดือน ค่าจ้าง บำนาญ เบี้ยเลี้ยง โบนัส', 0, 'L', 0, 0, '', '', true);	

	 			$pdf->SetFont('freeserif','',11,'',true);
				$pdf->SetY($d1+16);
	 			$pdf->SetX(166);
	 			$pdf->MultiCell(28, 5, 'เงินประจำตำแหน่ง', 0, 'L', 0, 0, '', '', true);

	 			$pdf->SetFont('freeserif','',11,'',true);
				$pdf->SetY($d3+10);
	 			$pdf->SetX(166);
	 			$pdf->MultiCell(28, 5, 'เงินค่าตอบแทน พตส ค่าครองชีพ', 0, 'L', 0, 0, '', '', true);	

	 			//col 10
	 			$pdf->SetFont('freeserif','',11,'',true);
				$pdf->SetY($d1);
	 			$pdf->SetX(209);
	 			//$pdf->MultiCell(30, 5, number_format( $key->salary, 2 ), 0, 'R', 0, 0, '', '', true);
	 			$pdf->MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);

	 			$pdf->SetFont('freeserif','',11,'',true);
				$pdf->SetY($d1+16);
	 			$pdf->SetX(209);
	 			$pdf->MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);

	 			$pdf->SetFont('freeserif','',11,'',true);
				$pdf->SetY($d3+10);
	 			$pdf->SetX(209);
	 			$pdf->MultiCell(30, 5, number_format( $key->special, 2 ), 0, 'R', 0, 0, '', '', true);	

	 			//col 11		
	 			$pdf->SetFont('freeserif','',11,'',true);
				$pdf->SetY($d1);
	 			$pdf->SetX(241);
	 			$pdf->MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);

	 			$j++;		    	

		    	$sum1 = $sum1+$key->special;
		    	$sum2 = $sum2;

		    } //end foreach			    


			//================================ header > 2 ===================================//	
			//================================ header > 2 ===================================//	
			//================================ header > 2 ===================================//	
			if( count($result) > 0 )
			{		
				if( ($j > 2) )	
				{

			    	$pdf->AddPage('L', 'letter');

			    	//===== แนวตั้ง =====//
			 			$linever1 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(5, 60, 5,18, $linever1);

						$pdf->SetFont('freeserif','',11,'',true);
						$pdf->SetY(35);
			 			$pdf->SetX(5);
			 			$pdf->MultiCell(13, 5, 'ลำดับ', 0, 'C', 0, 1, '', '', true);

						//--col 2
						$linever2 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(18, 60, 18,18, $linever2);

						$pdf->SetY(24);
			 			$pdf->SetX(19);
			 			$pdf->MultiCell(60, 5, 'เลขประจำตัวประชาชน(ของผู้มีเงินได้)', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(34);
			 			$pdf->SetX(19);
			 			$pdf->MultiCell(62, 5, 'เลขประจำตัวผู้เสียภาษีอากร(ของผู้มีเงินได้)', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(49);
			 			$pdf->SetX(19);
			 			$pdf->MultiCell(60, 5, 'ชื่อ ที่อยู่ ผู้มีเงินได้', 0, 'L', 0, 1, '', '', true);


						//--col 3
						$linever3 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(80, 60, 80,18, $linever3);

						$pdf->SetY(24);
			 			$pdf->SetX(81);
			 			$pdf->MultiCell(20, 5, 'วัน เดือน ปี ที่เข้าทำงาน(เฉพาะกรณีเข้าทำงานระหว่างปี)', 0, 'L', 0, 1, '', '', true);



						//--col 4
						$linever4 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(105, 60, 105,18, $linever4);
						
						$linever41 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(116, 60, 116,26, $linever41);

						$linever42 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(126, 60, 126,34, $linever42);

						$linever43 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(137, 60, 137,26, $linever43);

						$linever44 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(150, 60, 150,46, $linever44);

						$pdf->SetY(19);
			 			$pdf->SetX(106);
			 			$pdf->MultiCell(58, 5, '(1)รายการลดหย่อน', 0, 'C', 0, 1, '', '', true);

			 			$pdf->SetY(28);
			 			$pdf->SetX(105);
			 			$pdf->MultiCell(11, 5, 'มีสามีภริยาหรือไม่', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(27);
			 			$pdf->SetX(117);
			 			$pdf->MultiCell(20, 5, 'จำนวนบุตร', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(37);
			 			$pdf->SetX(116);
			 			$pdf->MultiCell(20, 5, 'ศึกษา', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(37);
			 			$pdf->SetX(126);
			 			$pdf->MultiCell(11, 5, 'ไม่ศึกษา', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(27);
			 			$pdf->SetX(137);
			 			$pdf->MultiCell(28, 5, 'ค่าลดหย่อนอื่นๆ', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(37);
			 			$pdf->SetX(137);
			 			$pdf->MultiCell(28, 5, 'จำนวนเงิน', 0, 'C', 0, 1, '', '', true);

			 			$pdf->SetY(49);
			 			$pdf->SetX(136);
			 			$pdf->MultiCell(15, 5, 'บาท', 0, 'C', 0, 1, '', '', true);

			 			$pdf->SetY(49);
			 			$pdf->SetX(149);
			 			$pdf->MultiCell(15, 5, 'สต.', 0, 'C', 0, 1, '', '', true);



						//--col 5
						$linever5 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(165, 60, 165,18, $linever5);

						$linever51 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(194, 60, 194,30, $linever51);

						$linever52 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(212, 60, 212,30, $linever52);

						$linever53 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(233, 60, 233,50, $linever53);

						$pdf->SetY(19);
			 			$pdf->SetX(165);
			 			$pdf->MultiCell(77, 5, 'ประเภทเงินได้พึงประเมิณที่จ่าย(รวมทั้งประโยชน์ที่เพิ่ม อย่างอื่น)ถ้ามากกว่าหนึ่งประเภทให้กรอกเรียงลงไป', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(39);
			 			$pdf->SetX(165);
			 			$pdf->MultiCell(30, 5, '(2)', 0, 'C', 0, 1, '', '', true);
			 			$pdf->SetY(43);
			 			$pdf->SetX(165);
			 			$pdf->MultiCell(30, 5, 'ประเภทเงินได้', 0, 'C', 0, 1, '', '', true);

			 			$pdf->SetY(39);
			 			$pdf->SetX(193);
			 			$pdf->MultiCell(20, 5, '(3)', 0, 'C', 0, 1, '', '', true);
			 			$pdf->SetY(43);
			 			$pdf->SetX(193);
			 			$pdf->MultiCell(20, 5, 'จำนวนคราวที่จ่ายทั้งปี', 0, 'C', 0, 1, '', '', true);
			 			
			 			$pdf->SetY(32);
			 			$pdf->SetX(212);
			 			$pdf->MultiCell(30, 5, 'จำนวนเงินที่จ่ายแต่ละประเภทเฉพาะคนหนึงๆ ทั้งปี', 0, 'L', 0, 1, '', '', true);
			 			
			 			$pdf->SetY(52);
			 			$pdf->SetX(216);
			 			$pdf->MultiCell(20, 5, 'บาท', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(52);
			 			$pdf->SetX(233);
			 			$pdf->MultiCell(8, 5, 'สต.', 0, 'C', 0, 1, '', '', true);


						//--col 6
						$linever6 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(242, 60, 242,18, $linever6);	

						$linever61 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(265, 60, 265,50, $linever61);

						$pdf->SetY(19);
			 			$pdf->SetX(242);
			 			$pdf->MultiCell(33, 5, 'รวมเงินภาษีที่หักและนำส่งในปีที่ล่วงมาแล้ว', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(37);
			 			$pdf->SetX(242);
			 			$pdf->MultiCell(33, 5, 'จำนวนเงิน', 0, 'C', 0, 1, '', '', true);

			 			$pdf->SetY(52);
			 			$pdf->SetX(249);
			 			$pdf->MultiCell(20, 5, 'บาท', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(52);
			 			$pdf->SetX(266);
			 			$pdf->MultiCell(8, 5, 'สต.', 0, 'C', 0, 1, '', '', true);


						$lineverEnd = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(274.5, 60, 274.5,18, $lineverEnd);	


						//======= แนวนอน =========//
						$linehor1 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(274.5, 60, 5,60, $linehor1);

						//--col 4
						$linehor2 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(165, 26, 105,26, $linehor2);

						$linehor3 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(165, 34, 116,34, $linehor3);

						$linehor4 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(165, 46, 137,46, $linehor4);

						//--col 5
						$linehor5 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(274.5, 30, 165,30, $linehor5);

						$linehor6 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(274.5, 50, 212,50, $linehor6);
			    } 
			    //================================ END header > 2 ===================================//	
				//================================ END header > 2 ===================================//	
				//================================ END header > 2 ===================================//	   

			    //แนวตั้ง
			    if( $j > 2)
			    {
			    	$h1 = 40;
			    	$y1 = 70;
			    	$d4 = 45;
			    	$x1 = 60;
			    }

			    $verft1 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(5, $h1+10, 5,$y1, $verft1);

				$verft2 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(212, $h1+10, 212,$y1, $verft2);

				$verft3 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(242, $h1+10, 242,$y1, $verft3);

				$verft4 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(274.5, $h1+10, 274.5,$y1, $verft4);

				$pdf->SetFont('freeserif','B',11,'',true);
				$pdf->SetY($d4+17);
	 			$pdf->SetX(151);
	 			$pdf->MultiCell(50, 5, 'รวมยอดเงินได้และภาษีที่นำส่ง', 0, 'R', 0, 0, '', '', true);

	 			$pdf->SetFont('freeserif','B',11,'',true);
				$pdf->SetY($d4+17);
	 			$pdf->SetX(211);
	 			$pdf->MultiCell(30, 5, number_format($sum1, 2), 0, 'R', 0, 0, '', '', true);

	 			$pdf->SetFont('freeserif','B',11,'',true);
				$pdf->SetY($d4+17);
	 			$pdf->SetX(241);
	 			$pdf->MultiCell(32, 5, number_format($sum2, 2), 0, 'R', 0, 0, '', '', true);

				//แนวนอน
				$horft1 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(274.5, $x1+10, 5,$x1+10, $horft1);

			    //================================= last page footer ======================================//
				$pdf->SetFont('freeserif','B',11,'',true);
				$pdf->SetY(145);
	 			$pdf->SetX(8);
	 			$pdf->MultiCell(30, 5, 'หมายเหตุ', 0, 'C', 0, 0, '', '', true);
				
	 			$pdf->SetFont('freeserif','',10,'',true);
				$pdf->SetY(155);
	 			$pdf->SetX(5);
	 			$pdf->MultiCell(180, 5, '1.ให้ระบุว่า มี หรือ ไม่มี ภริยาโดยใส่เครื่องหมาย / ลงใน หน้าข้อความแต่กรณีพร้อมทั้งกรอกจำนวนบุตรที่มีสิทธิหักลดหย่อนศึกษากี่คน ไม่ศึกษากี่คนและยอดรวมจำนวนเงินค่าลดหย่อนอื่น ๆ ที่จ่ายให้แก่เบี้ยประกันชีวิต เงินสะสมดอกเบี้ยเงินกู้ยืมเพื่อซื้อ เช้าซื้อ หรือสร้างอาคารที่อยู่อาศัย ฯ และเงินสมทบ ฯ', 0, 'L', 0, 0, '', '', true);
				
				$pdf->SetFont('freeserif','',10,'',true);
				$pdf->SetY(175);
	 			$pdf->SetX(5);
	 			$pdf->MultiCell(180, 5, '2.ให้กรอกประเภทเงินได้ที่จ่าย เช่น เงินเดือน ค่าจ้าง เบี้ยเลี้ยง โบนัส บำเหน็จ เงินค่าธรรมเนียม ค่านายหน้า เปบี้ยประชุมค่าภาษีเงินได้ ฯลฯ', 0, 'L', 0, 0, '', '', true);
				
	 			$pdf->SetFont('freeserif','',10,'',true);
				$pdf->SetY(185);
	 			$pdf->SetX(5);
	 			$pdf->MultiCell(180, 5, '3.จำนวนคราวที่จ่ายทั้งปี -จ่ายเป็นรายวัน กรอก 1   -จ่ายเป็นรายสัปดาห์ กรอก 2     -จ่ายเป็นรายปักษ์ กรอก 3     -จ่ายเป็นรายเดือน กรอก 4     -จ่ายเป็นคราวไม่แน่นอน กรอก 5', 0, 'L', 0, 0, '', '', true);			
				
	 			$pdf->SetFont('freeserif','B',10,'',true);
				$pdf->SetY(155);
	 			$pdf->SetX(180);
	 			$pdf->MultiCell(94, 5, 'ลงชื่อ................................................................ผู้จ่ายเงิน', 0, 'C', 0, 0, '', '', true);
				
				$pdf->SetFont('freeserif','B',11,'',true);
				$pdf->SetY(164);
	 			$pdf->SetX(180);
	 			$pdf->MultiCell(94, 5, $director, 0, 'C', 0, 0, '', '', true);
				
				$pdf->SetY(170);
	 			$pdf->SetX(180);
	 			$pdf->MultiCell(94, 5, 'นายแพทย์เชี่ยวชาญ', 0, 'C', 0, 0, '', '', true);

	 			$pdf->SetY(180);
	 			$pdf->SetX(180);
	 			$pdf->MultiCell(94, 5, 'ผู้อำนวยการโรงพยาบาลโนนไทย', 0, 'C', 0, 0, '', '', true);
	 			
				$pdf->SetY(190);
	 			$pdf->SetX(180);
	 			$pdf->MultiCell(94, 5, 'ยื่นวันที่.................................................................', 0, 'C', 0, 0, '', '', true);
		}// check result > 0

			$filename = storage_path() . '/report_tax_itpc_sp_emp3.pdf';
		    // Response::download($filename);
		    $contents = $pdf->output($filename, 'I');
			$headers = array(
			    'Content-Type' => 'application/pdf',
			);
			return Response::make($contents, 200, $headers);
		}
		else
		{
			return View::make('login.index');
		}
	}

	//=================================== Tax พตส. เงินงบประมาณ ======================================//
	
	public function continuous_pts( $id=null, $year=null )
	{
		if( Session::get('level') != '' )
		{				
		    $pdf = new TCPDF();
		    $pdf->SetPrintHeader(false);
		    $pdf->SetPrintFooter(false);			   		   

		    $n = DB::select( 'select * from s_general_data' );	
		    foreach ($n as $k) {
		      	$name 		= $k->name;
		      	$address 	= $k->address;
		      	$address2 	= $k->address2;
		      	$tax_id2 	= $k->tax_id2;
		      	$director 	= $k->director;
		    } 

		    //--- ค่า พตส เงินงบประมาณ--//
		    if( $id != 'null' && $year != 'null' )
		    {
				$sql = ' select concat(n.pname,"",n.fname," ",n.lname) as name, s.cid, s.tax_id, sum(s.pts2+s.ch11) as special from s_salary_ocsc_detail s left join n_datageneral n on n.cid=s.cid  where  year(s.order_date)='.$year.' and s.cid='.$id.' group by s.cid order by n.datainfoID asc ';		    	    
		    }
		    if( $id != 'null' && $year == 'null' )
		    {		    	
		    	$sql = ' select concat(n.pname,"",n.fname," ",n.lname) as name, s.cid, s.tax_id, sum(s.pts2+s.ch11) as special from s_salary_ocsc_detail s left join n_datageneral n on n.cid=s.cid  where   year(s.order_date)='.$this->max_year().' and s.cid='.$id.' group by s.cid order by n.datainfoID asc ';
		    }
		    if( $id == 'null' && $year == 'null' )
		    {
		    	$sql = ' select concat(n.pname,"",n.fname," ",n.lname) as name, s.cid, s.tax_id, sum(s.pts2+s.ch11) as special from s_salary_ocsc_detail s left join n_datageneral n on n.cid=s.cid  where  year(s.order_date)='.$this->max_year().' group by s.cid order by n.datainfoID asc ';		    		    
		    }
		    
		    $result = DB::select( $sql );		    

		    foreach ( $result as $key ) {

		    	$pdf->AddPage('P', 'A4');

		    	$pdf->SetFont('freeserif','B',11,'',true);		    
	 			$pdf->MultiCell(185, 5, 'เลขที่ งบ. ..................... ', 0, 'R', 0, 1, '', '', true);

	 			$pdf->SetFont('freeserif','B',16,'',true);
		    	$pdf->SetY(25);
	 			$pdf->SetX(18);
	 			$pdf->MultiCell(177, 5, 'หนังสือรับรองการหักภาษี ณ ที่จ่าย', 0, 'C', 0, 1, '', '', true);

	 			$pdf->SetY(34);
	 			$pdf->SetX(18);
	 			$pdf->MultiCell(177, 5, 'ตามมาตรา 50 ทวิ แห่งประมวลรัษฎากร', 0, 'C', 0, 1, '', '', true);
				
	 			//===== แนวตั้ง =====//
	 			$linever1 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(18, 190, 18,50, $linever1);

				$linever2 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(80, 190, 80,50, $linever2);

				$linever3 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(110, 190, 110,50, $linever3);

				$linever4 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(135, 190, 135,50, $linever4);

				$linever5 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(165, 190, 165,50, $linever5);

				$linever6 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(195, 190, 195,50, $linever6);

				//===== แนวนอน =====//
	 			$linetop = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(18, 50, 195,50, $linetop);

				$linetop2 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(80, 63, 195,63, $linetop2);

				$linetop3 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(18, 120, 80,120, $linetop3);

				$linetop4 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(80, 180, 195,180, $linetop4);

				$linetop5 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(18, 190, 195,190, $linetop5);

				//======= text in box 1 ========//
				$pdf->SetFont('freeserif','B',13,'',true);
				$pdf->SetY(52);
	 			$pdf->SetX(19);
	 			$pdf->MultiCell(62, 5, 'ชื่อและที่อยู่ของผู้มีหน้าที่หักภาษี ณ ที่จ่าย บุคคลคณะบุคคล นิติบุคคล ส่วนราชการ องค์การ รัฐวิสาหกิจ ฯลฯ ', 0, 'L', 0, 1, '', '', true);

	 			$pdf->SetFont('freeserif','B',13,'',true);
				$pdf->SetY(82);
	 			$pdf->SetX(19);
	 			$pdf->MultiCell(62, 5, $address2, 0, 'L', 0, 1, '', '', true);

	 			$pdf->SetFont('freeserif','B',13,'',true);
				$pdf->SetY(105);
	 			$pdf->SetX(19);
	 			$pdf->MultiCell(40, 5, $tax_id2, 0, 'L', 0, 1, '', '', true);

	 			//======= text in box 2 ========//
	 			$pdf->SetFont('freeserif','B',13,'',true);
				$pdf->SetY(122);
	 			$pdf->SetX(19);
	 			$pdf->MultiCell(62, 5, 'ชื่อและที่อยู่ของผู้ถูกหักภาษี ณ ที่จ่าย', 0, 'L', 0, 1, '', '', true);

	 			$pdf->SetFont('freeserif','',12,'',true);
				$pdf->SetY(137);
	 			$pdf->SetX(21);
	 			$pdf->MultiCell(59, 5, $key->name, 0, 'L', 0, 1, '', '', true);
	
	 			$pdf->SetFont('freeserif','B',13,'',true);
				$pdf->SetY(145);
	 			$pdf->SetX(19);
	 			$pdf->MultiCell(62, 5, $address, 0, 'L', 0, 1, '', '', true);

	 			$pdf->SetFont('freeserif','B',13,'',true);
				$pdf->SetY(165);
	 			$pdf->SetX(19);
	 			$pdf->MultiCell(62, 5, 'เลขประจำตัวผู้เสียภาษีของผู้ถูกหักภาษี ณ ที่จ่าย', 0, 'L', 0, 1, '', '', true);

	 			$pdf->SetFont('freeserif','',12,'',true);
				$pdf->SetY(178);
	 			$pdf->SetX(22);
	 			$pdf->MultiCell(62, 5, $key->cid, 0, 'L', 0, 1, '', '', true);

	 			//======= text in box 3 header content ========//
	 			$pdf->SetFont('freeserif','B',13,'',true);
				$pdf->SetY(54);
	 			$pdf->SetX(83);
	 			$pdf->MultiCell(32, 5, 'เงินได้ที่จ่าย', 0, 'L', 0, 1, '', '', true);

	 			$pdf->SetFont('freeserif','B',13,'',true);
				$pdf->SetY(54);
	 			$pdf->SetX(111);
	 			$pdf->MultiCell(32, 5, 'ปีภาษีที่จ่าย', 0, 'L', 0, 1, '', '', true);

	 			$pdf->SetFont('freeserif','B',13,'',true);
				$pdf->SetY(54);
	 			$pdf->SetX(138);
	 			$pdf->MultiCell(32, 5, 'จำนวนเงิน', 0, 'L', 0, 1, '', '', true);

	 			$pdf->SetFont('freeserif','B',13,'',true);
				$pdf->SetY(54);
	 			$pdf->SetX(167);
	 			$pdf->MultiCell(32, 5, 'ภาษีที่หักไว้', 0, 'L', 0, 1, '', '', true);


	 			//============= text in content ================//
	 			$pdf->SetFont('freeserif','',12,'',true);

	 			//-----col 1
				$pdf->SetY(70);
	 			$pdf->SetX(80);
	 			$pdf->MultiCell(30, 5, 'เงินเดือน ค่าจ้าง บำนาญ เบี้ยเลี้ยง โบนัส ตามมาตรา 40(1)', 0, 'L', 0, 1, '', '', true);

				$pdf->SetY(95);
	 			$pdf->SetX(80);
	 			$pdf->MultiCell(31, 5, 'เงินประจำตำแหน่ง', 0, 'L', 0, 1, '', '', true);

	 			$pdf->SetY(104);
	 			$pdf->SetX(80);
	 			$pdf->MultiCell(27, 5, 'เงินค่าตอบแทนพิเศษ พตส ค่าครองชีพ', 0, 'L', 0, 1, '', '', true);

	 			//-----col 2
	 			$pdf->SetY(70);
	 			$pdf->SetX(116);
	 			$pdf->MultiCell(31, 5, ( ($year == 'null') ? $this->yearThai() : ($year+543) ) , 0, 'L', 0, 1, '', '', true);

	 			//-----col 3
	 			$pdf->SetY(70);
	 			$pdf->SetX(135);
	 			$pdf->MultiCell(30, 5, '', 0, 'R', 0, 1, '', '', true);

	 			$pdf->SetY(95);
	 			$pdf->SetX(135);
	 			$pdf->MultiCell(30, 5, '', 0, 'R', 0, 1, '', '', true);

	 			$pdf->SetY(104);
	 			$pdf->SetX(135);
	 			$pdf->MultiCell(30, 5, number_format( $key->special, 2 ), 0, 'R', 0, 1, '', '', true);

	 			//-----col 4
	 			$pdf->SetY(70);
	 			$pdf->SetX(165);
	 			$pdf->MultiCell(30, 5, '', 0, 'R', 0, 1, '', '', true);
	 			

	 			//============= text in box 4 footer sum ============//

	 			$pdf->SetFont('freeserif','B',13,'',true);
				$pdf->SetY(182);
	 			$pdf->SetX(89);
	 			$pdf->MultiCell(32, 5, 'รวม', 0, 'L', 0, 1, '', '', true);

	 			$pdf->SetFont('freeserif','',12,'',true);
	 			$pdf->SetY(182);
	 			$pdf->SetX(135);
	 			$pdf->MultiCell(30, 5, number_format( $key->special, 2 ), 0, 'R', 0, 1, '', '', true);

	 			$pdf->SetFont('freeserif','',12,'',true);
	 			$pdf->SetY(182);
	 			$pdf->SetX(165);
	 			$pdf->MultiCell(30, 5, '', 0, 'R', 0, 1, '', '', true);


	 			//============= text footer ================//
	 			$pdf->SetFont('freeserif','',12,'',true);
				
	 			$pdf->SetFont('freeserif','B',12,'',true);
	 			$pdf->SetY(220);
	 			$pdf->SetX(18);
	 			$pdf->MultiCell(177, 5, 'ข้าพเจ้าขอรับรองว่า ข้อความและตัวเลขดังกล่าวข้างต้นนี้ถูกต้องตามความเป็นจริงทุกประการ', 0, 'R', 0, 1, '', '', true);

	 			$pdf->SetFont('freeserif','',12,'',true);
	 			$pdf->SetY(235);
	 			$pdf->SetX(32);
	 			$pdf->MultiCell(170, 5, 'ลงชื่อ...........................................................ผู้มีหน้าที่หักภาษี ณ ที่จ่าย', 0, 'C', 0, 1, '', '', true);	 

	 			$pdf->SetY(245);
	 			$pdf->SetX(32);
	 			$pdf->MultiCell(140, 5, $director, 0, 'C', 0, 1, '', '', true);

	 			$pdf->SetY(255);
	 			$pdf->SetX(32);
	 			$pdf->MultiCell(140, 5, 'นายแพทย์เชี่ยวชาญ', 0, 'C', 0, 1, '', '', true);

	 			$pdf->SetY(265);
	 			$pdf->SetX(32);
	 			$pdf->MultiCell(140, 5, 'ผู้อำนวยการโรงพยาบาลโนนไทย', 0, 'C', 0, 1, '', '', true);			
	 			
		    }

			$filename = storage_path() . '/report_tax_continuous_pts_emp2.pdf';
		    // Response::download($filename);
		    $contents = $pdf->output($filename, 'I');
			$headers = array(
			    'Content-Type' => 'application/pdf',
			);
			return Response::make($contents, 200, $headers);
		}
		else
		{
			return View::make('login.index');
		}	
	}

	public function itpc_pts()
	{
		if( Session::get('level') != '' )
		{
			$n = DB::select( 'select * from s_general_data' );	
		    foreach ($n as $k) {
		      	$name 		= $k->name;
		      	$address 	= $k->address;
		      	$tax_id2 	= $k->tax_id2;
		      	$director 	= $k->director;
		    } 

			$pdf = new TCPDF();			
			
			$pdf->SetHeaderData('', '', 'ภ.ง.ด 1 ก พิเศษ', 'เลขประจำตัวผู้เสียภาษีอากร(ของผู้มีหน้าที่หักภาษี ณ ที่จ่าย)       '.$tax_id2 );
			
			// set header and footer fonts
			$pdf->setHeaderFont(Array('freeserif', '', 13));
			$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));	  

			$pdf->SetMargins(5, PDF_MARGIN_TOP, 5);
			$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
			$pdf->SetFooterMargin(PDF_MARGIN_FOOTER); 	   		   
		   
		    //-------------- พตส.เงินงบประมาณ ----------------------//
		    $sql = ' select concat(n.pname,"",n.fname," ",n.lname) as name, s.cid, s.tax_id, sum(s.pts2+s.ch11) as special from s_salary_ocsc_detail s left join n_datageneral n on n.cid=s.cid  where  year(s.order_date)='.$this->max_year().' group by s.cid order by n.datainfoID asc ';		    
		    $result = DB::select( $sql );
			$j=0;
			$i=0;
			$sum1=0;
			$sum2=0;
		    foreach ( $result as $key ) 
		    {			    
		    			    	
		    	if( $j==4 )
	    		{
	    			$j=0;
	    		}

		    	if( $j == 0)
		    	{		    		
		    		$pdf->AddPage('L', 'letter');	
		    					
		 			//===== แนวตั้ง =====//
		 			$linever1 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(5, 60, 5,18, $linever1);

					$pdf->SetFont('freeserif','',11,'',true);
					$pdf->SetY(35);
		 			$pdf->SetX(5);
		 			$pdf->MultiCell(13, 5, 'ลำดับ', 0, 'C', 0, 1, '', '', true);

					//--col 2
					$linever2 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(18, 60, 18,18, $linever2);

					$pdf->SetY(24);
		 			$pdf->SetX(19);
		 			$pdf->MultiCell(60, 5, 'เลขประจำตัวประชาชน(ของผู้มีเงินได้)', 0, 'L', 0, 1, '', '', true);

		 			$pdf->SetY(34);
		 			$pdf->SetX(19);
		 			$pdf->MultiCell(62, 5, 'เลขประจำตัวผู้เสียภาษีอากร(ของผู้มีเงินได้)', 0, 'L', 0, 1, '', '', true);

		 			$pdf->SetY(49);
		 			$pdf->SetX(19);
		 			$pdf->MultiCell(60, 5, 'ชื่อ ที่อยู่ ผู้มีเงินได้', 0, 'L', 0, 1, '', '', true);


					//--col 3
					$linever3 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(80, 60, 80,18, $linever3);

					$pdf->SetY(24);
		 			$pdf->SetX(81);
		 			$pdf->MultiCell(20, 5, 'วัน เดือน ปี ที่เข้าทำงาน(เฉพาะกรณีเข้าทำงานระหว่างปี)', 0, 'L', 0, 1, '', '', true);



					//--col 4
					$linever4 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(105, 60, 105,18, $linever4);
					
					$linever41 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(116, 60, 116,26, $linever41);

					$linever42 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(126, 60, 126,34, $linever42);

					$linever43 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(137, 60, 137,26, $linever43);

					$linever44 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(150, 60, 150,46, $linever44);

					$pdf->SetY(19);
		 			$pdf->SetX(106);
		 			$pdf->MultiCell(58, 5, '(1)รายการลดหย่อน', 0, 'C', 0, 1, '', '', true);

		 			$pdf->SetY(28);
		 			$pdf->SetX(105);
		 			$pdf->MultiCell(11, 5, 'มีสามีภริยาหรือไม่', 0, 'L', 0, 1, '', '', true);

		 			$pdf->SetY(27);
		 			$pdf->SetX(117);
		 			$pdf->MultiCell(20, 5, 'จำนวนบุตร', 0, 'L', 0, 1, '', '', true);

		 			$pdf->SetY(37);
		 			$pdf->SetX(116);
		 			$pdf->MultiCell(20, 5, 'ศึกษา', 0, 'L', 0, 1, '', '', true);

		 			$pdf->SetY(37);
		 			$pdf->SetX(126);
		 			$pdf->MultiCell(11, 5, 'ไม่ศึกษา', 0, 'L', 0, 1, '', '', true);

		 			$pdf->SetY(27);
		 			$pdf->SetX(137);
		 			$pdf->MultiCell(28, 5, 'ค่าลดหย่อนอื่นๆ', 0, 'L', 0, 1, '', '', true);

		 			$pdf->SetY(37);
		 			$pdf->SetX(137);
		 			$pdf->MultiCell(28, 5, 'จำนวนเงิน', 0, 'C', 0, 1, '', '', true);

		 			$pdf->SetY(49);
		 			$pdf->SetX(136);
		 			$pdf->MultiCell(15, 5, 'บาท', 0, 'C', 0, 1, '', '', true);

		 			$pdf->SetY(49);
		 			$pdf->SetX(149);
		 			$pdf->MultiCell(15, 5, 'สต.', 0, 'C', 0, 1, '', '', true);



					//--col 5
					$linever5 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(165, 60, 165,18, $linever5);

					$linever51 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(194, 60, 194,30, $linever51);

					$linever52 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(212, 60, 212,30, $linever52);

					$linever53 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(233, 60, 233,50, $linever53);

					$pdf->SetY(19);
		 			$pdf->SetX(165);
		 			$pdf->MultiCell(77, 5, 'ประเภทเงินได้พึงประเมิณที่จ่าย(รวมทั้งประโยชน์ที่เพิ่ม อย่างอื่น)ถ้ามากกว่าหนึ่งประเภทให้กรอกเรียงลงไป', 0, 'L', 0, 1, '', '', true);

		 			$pdf->SetY(39);
		 			$pdf->SetX(165);
		 			$pdf->MultiCell(30, 5, '(2)', 0, 'C', 0, 1, '', '', true);
		 			$pdf->SetY(43);
		 			$pdf->SetX(165);
		 			$pdf->MultiCell(30, 5, 'ประเภทเงินได้', 0, 'C', 0, 1, '', '', true);

		 			$pdf->SetY(39);
		 			$pdf->SetX(193);
		 			$pdf->MultiCell(20, 5, '(3)', 0, 'C', 0, 1, '', '', true);
		 			$pdf->SetY(43);
		 			$pdf->SetX(193);
		 			$pdf->MultiCell(20, 5, 'จำนวนคราวที่จ่ายทั้งปี', 0, 'C', 0, 1, '', '', true);
		 			
		 			$pdf->SetY(32);
		 			$pdf->SetX(212);
		 			$pdf->MultiCell(30, 5, 'จำนวนเงินที่จ่ายแต่ละประเภทเฉพาะคนหนึงๆ ทั้งปี', 0, 'L', 0, 1, '', '', true);
		 			
		 			$pdf->SetY(52);
		 			$pdf->SetX(216);
		 			$pdf->MultiCell(20, 5, 'บาท', 0, 'L', 0, 1, '', '', true);

		 			$pdf->SetY(52);
		 			$pdf->SetX(233);
		 			$pdf->MultiCell(8, 5, 'สต.', 0, 'C', 0, 1, '', '', true);


					//--col 6
					$linever6 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(242, 60, 242,18, $linever6);	

					$linever61 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(265, 60, 265,50, $linever61);

					$pdf->SetY(19);
		 			$pdf->SetX(242);
		 			$pdf->MultiCell(33, 5, 'รวมเงินภาษีที่หักและนำส่งในปีที่ล่วงมาแล้ว', 0, 'L', 0, 1, '', '', true);

		 			$pdf->SetY(37);
		 			$pdf->SetX(242);
		 			$pdf->MultiCell(33, 5, 'จำนวนเงิน', 0, 'C', 0, 1, '', '', true);

		 			$pdf->SetY(52);
		 			$pdf->SetX(249);
		 			$pdf->MultiCell(20, 5, 'บาท', 0, 'L', 0, 1, '', '', true);

		 			$pdf->SetY(52);
		 			$pdf->SetX(266);
		 			$pdf->MultiCell(8, 5, 'สต.', 0, 'C', 0, 1, '', '', true);


					$lineverEnd = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(274.5, 60, 274.5,18, $lineverEnd);	


					//======= แนวนอน =========//
					$linehor1 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(274.5, 60, 5,60, $linehor1);

					//--col 4
					$linehor2 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(165, 26, 105,26, $linehor2);

					$linehor3 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(165, 34, 116,34, $linehor3);

					$linehor4 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(165, 46, 137,46, $linehor4);

					//--col 5
					$linehor5 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(274.5, 30, 165,30, $linehor5);

					$linehor6 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(274.5, 50, 212,50, $linehor6);					

				}//end add header

				if( $j == 0 ){
					$x1=95;
					$y1=60;
					$h1=95;
					
					$d1=61;
					$d2=68;
					$d3=74;
					$d4=81;
				}else if( $j == 1 ){
					$x1=130;
					$y1=90;
					$h1=130;
					
					$d1=96;
					$d2=103;
					$d3=109;
					$d4=116;
				}else if( $j == 2 ){
					$x1=165;
					$y1=120;
					$h1=165;
					
					$d1=131;
					$d2=138;
					$d3=144;
					$d4=151;	
				}else if( $j == 3 ){
					$x1=197;
					$y1=150;
					$h1=197;
					
					$d1=166;
					$d2=171;
					$d3=176;
					$d4=181;
				}

				//loop นอน
				$linehor = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(274.5, $x1, 5,$x1, $linehor);

				//loop ตั้ง
				$linever_loop1 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(5, $h1, 5,$y1, $linever_loop1);

				$linever_loop2 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(18, $h1, 18,$y1, $linever_loop2);

				$linever_loop3 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(80, $h1, 80,$y1, $linever_loop3);

				$linever_loop4 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(105, $h1, 105,$y1, $linever_loop4);

				$linever_loop5 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(116, $h1, 116,$y1, $linever_loop5);

				$linever_loop6 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(126, $h1, 126,$y1, $linever_loop6);

				$linever_loop7 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(137, $h1, 137,$y1, $linever_loop7);

				$linever_loop8 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(165, $h1, 165,$y1, $linever_loop8);

				$linever_loop9 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(194, $h1, 194,$y1, $linever_loop9);

				$linever_loop10 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(212, $h1, 212,$y1, $linever_loop10);

				$linever_loop11 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(242, $h1, 242,$y1, $linever_loop11);

				$linever_loop12 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(274.5, $h1, 274.5,$y1, $linever_loop12);

				//loop data
				
				//col 1 data
				$pdf->SetFont('freeserif','B',11,'',true);
				$pdf->SetY($d1);
	 			$pdf->SetX(19);
	 			$pdf->MultiCell(60, 5, $key->cid, 0, 'L', 0, 0, '', '', true);	

	 			$pdf->SetFont('freeserif','',11,'',true);
	 			$pdf->SetY($d2);
	 			$pdf->SetX(19);
	 			$pdf->MultiCell(60, 5, $key->tax_id, 0, 'L', 0, 0, '', '', true);	

	 			$pdf->SetFont('freeserif','B',11,'',true);
	 			$pdf->SetY($d3);
	 			$pdf->SetX(19);
	 			$pdf->MultiCell(60, 5, $key->name, 0, 'L', 0, 0, '', '', true);
				
				$pdf->SetFont('freeserif','',11,'',true);
				$pdf->SetY($d4);
	 			$pdf->SetX(19);
	 			$pdf->MultiCell(60, 5, $address, 0, 'L', 0, 0, '', '', true);	 

	 			//col 8
	 			$pdf->SetFont('freeserif','',11,'',true);
				$pdf->SetY($d1);
	 			$pdf->SetX(166);
	 			$pdf->MultiCell(28, 5, 'เงินเดือน ค่าจ้าง บำนาญ เบี้ยเลี้ยง โบนัส', 0, 'L', 0, 0, '', '', true);	

	 			$pdf->SetFont('freeserif','',11,'',true);
				$pdf->SetY($d1+16);
	 			$pdf->SetX(166);
	 			$pdf->MultiCell(28, 5, 'เงินประจำตำแหน่ง', 0, 'L', 0, 0, '', '', true);

	 			$pdf->SetFont('freeserif','',11,'',true);
				$pdf->SetY($d3+10);
	 			$pdf->SetX(166);
	 			$pdf->MultiCell(28, 5, 'เงินค่าตอบแทน พตส ค่าครองชีพ', 0, 'L', 0, 0, '', '', true);	

	 			//col 10
	 			$pdf->SetFont('freeserif','',11,'',true);
				$pdf->SetY($d1);
	 			$pdf->SetX(209);
	 			$pdf->MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);

	 			$pdf->SetFont('freeserif','',11,'',true);
				$pdf->SetY($d1+16);
	 			$pdf->SetX(209);
	 			$pdf->MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);

	 			$pdf->SetFont('freeserif','',11,'',true);
				$pdf->SetY($d3+10);
	 			$pdf->SetX(209);
	 			$pdf->MultiCell(30, 5, number_format( $key->special, 2 ), 0, 'R', 0, 0, '', '', true);	

	 			//col 11		
	 			$pdf->SetFont('freeserif','',11,'',true);
				$pdf->SetY($d1);
	 			$pdf->SetX(241);
	 			$pdf->MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);

	 			$j++;		    	

		    	$sum1 = $sum1+$key->special;
		    	$sum2 = $sum2;

		    } //end foreach			    


			//================================ header > 2 ===================================//	
			//================================ header > 2 ===================================//	
			//================================ header > 2 ===================================//	
			if( count($result) > 0 )
			{		
				if( ($j > 2) )	
				{

			    	$pdf->AddPage('L', 'letter');

			    	//===== แนวตั้ง =====//
			 			$linever1 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(5, 60, 5,18, $linever1);

						$pdf->SetFont('freeserif','',11,'',true);
						$pdf->SetY(35);
			 			$pdf->SetX(5);
			 			$pdf->MultiCell(13, 5, 'ลำดับ', 0, 'C', 0, 1, '', '', true);

						//--col 2
						$linever2 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(18, 60, 18,18, $linever2);

						$pdf->SetY(24);
			 			$pdf->SetX(19);
			 			$pdf->MultiCell(60, 5, 'เลขประจำตัวประชาชน(ของผู้มีเงินได้)', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(34);
			 			$pdf->SetX(19);
			 			$pdf->MultiCell(62, 5, 'เลขประจำตัวผู้เสียภาษีอากร(ของผู้มีเงินได้)', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(49);
			 			$pdf->SetX(19);
			 			$pdf->MultiCell(60, 5, 'ชื่อ ที่อยู่ ผู้มีเงินได้', 0, 'L', 0, 1, '', '', true);


						//--col 3
						$linever3 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(80, 60, 80,18, $linever3);

						$pdf->SetY(24);
			 			$pdf->SetX(81);
			 			$pdf->MultiCell(20, 5, 'วัน เดือน ปี ที่เข้าทำงาน(เฉพาะกรณีเข้าทำงานระหว่างปี)', 0, 'L', 0, 1, '', '', true);



						//--col 4
						$linever4 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(105, 60, 105,18, $linever4);
						
						$linever41 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(116, 60, 116,26, $linever41);

						$linever42 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(126, 60, 126,34, $linever42);

						$linever43 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(137, 60, 137,26, $linever43);

						$linever44 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(150, 60, 150,46, $linever44);

						$pdf->SetY(19);
			 			$pdf->SetX(106);
			 			$pdf->MultiCell(58, 5, '(1)รายการลดหย่อน', 0, 'C', 0, 1, '', '', true);

			 			$pdf->SetY(28);
			 			$pdf->SetX(105);
			 			$pdf->MultiCell(11, 5, 'มีสามีภริยาหรือไม่', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(27);
			 			$pdf->SetX(117);
			 			$pdf->MultiCell(20, 5, 'จำนวนบุตร', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(37);
			 			$pdf->SetX(116);
			 			$pdf->MultiCell(20, 5, 'ศึกษา', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(37);
			 			$pdf->SetX(126);
			 			$pdf->MultiCell(11, 5, 'ไม่ศึกษา', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(27);
			 			$pdf->SetX(137);
			 			$pdf->MultiCell(28, 5, 'ค่าลดหย่อนอื่นๆ', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(37);
			 			$pdf->SetX(137);
			 			$pdf->MultiCell(28, 5, 'จำนวนเงิน', 0, 'C', 0, 1, '', '', true);

			 			$pdf->SetY(49);
			 			$pdf->SetX(136);
			 			$pdf->MultiCell(15, 5, 'บาท', 0, 'C', 0, 1, '', '', true);

			 			$pdf->SetY(49);
			 			$pdf->SetX(149);
			 			$pdf->MultiCell(15, 5, 'สต.', 0, 'C', 0, 1, '', '', true);



						//--col 5
						$linever5 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(165, 60, 165,18, $linever5);

						$linever51 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(194, 60, 194,30, $linever51);

						$linever52 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(212, 60, 212,30, $linever52);

						$linever53 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(233, 60, 233,50, $linever53);

						$pdf->SetY(19);
			 			$pdf->SetX(165);
			 			$pdf->MultiCell(77, 5, 'ประเภทเงินได้พึงประเมิณที่จ่าย(รวมทั้งประโยชน์ที่เพิ่ม อย่างอื่น)ถ้ามากกว่าหนึ่งประเภทให้กรอกเรียงลงไป', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(39);
			 			$pdf->SetX(165);
			 			$pdf->MultiCell(30, 5, '(2)', 0, 'C', 0, 1, '', '', true);
			 			$pdf->SetY(43);
			 			$pdf->SetX(165);
			 			$pdf->MultiCell(30, 5, 'ประเภทเงินได้', 0, 'C', 0, 1, '', '', true);

			 			$pdf->SetY(39);
			 			$pdf->SetX(193);
			 			$pdf->MultiCell(20, 5, '(3)', 0, 'C', 0, 1, '', '', true);
			 			$pdf->SetY(43);
			 			$pdf->SetX(193);
			 			$pdf->MultiCell(20, 5, 'จำนวนคราวที่จ่ายทั้งปี', 0, 'C', 0, 1, '', '', true);
			 			
			 			$pdf->SetY(32);
			 			$pdf->SetX(212);
			 			$pdf->MultiCell(30, 5, 'จำนวนเงินที่จ่ายแต่ละประเภทเฉพาะคนหนึงๆ ทั้งปี', 0, 'L', 0, 1, '', '', true);
			 			
			 			$pdf->SetY(52);
			 			$pdf->SetX(216);
			 			$pdf->MultiCell(20, 5, 'บาท', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(52);
			 			$pdf->SetX(233);
			 			$pdf->MultiCell(8, 5, 'สต.', 0, 'C', 0, 1, '', '', true);


						//--col 6
						$linever6 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(242, 60, 242,18, $linever6);	

						$linever61 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(265, 60, 265,50, $linever61);

						$pdf->SetY(19);
			 			$pdf->SetX(242);
			 			$pdf->MultiCell(33, 5, 'รวมเงินภาษีที่หักและนำส่งในปีที่ล่วงมาแล้ว', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(37);
			 			$pdf->SetX(242);
			 			$pdf->MultiCell(33, 5, 'จำนวนเงิน', 0, 'C', 0, 1, '', '', true);

			 			$pdf->SetY(52);
			 			$pdf->SetX(249);
			 			$pdf->MultiCell(20, 5, 'บาท', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(52);
			 			$pdf->SetX(266);
			 			$pdf->MultiCell(8, 5, 'สต.', 0, 'C', 0, 1, '', '', true);


						$lineverEnd = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(274.5, 60, 274.5,18, $lineverEnd);	


						//======= แนวนอน =========//
						$linehor1 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(274.5, 60, 5,60, $linehor1);

						//--col 4
						$linehor2 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(165, 26, 105,26, $linehor2);

						$linehor3 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(165, 34, 116,34, $linehor3);

						$linehor4 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(165, 46, 137,46, $linehor4);

						//--col 5
						$linehor5 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(274.5, 30, 165,30, $linehor5);

						$linehor6 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(274.5, 50, 212,50, $linehor6);
			    } 
			    //================================ END header > 2 ===================================//	
				//================================ END header > 2 ===================================//	
				//================================ END header > 2 ===================================//	   

			    //แนวตั้ง
			    if( $j > 2)
			    {
			    	$h1 = 40;
			    	$y1 = 70;
			    	$d4 = 45;
			    	$x1 = 60;
			    }

			    $verft1 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(5, $h1+10, 5,$y1, $verft1);

				$verft2 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(212, $h1+10, 212,$y1, $verft2);

				$verft3 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(242, $h1+10, 242,$y1, $verft3);

				$verft4 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(274.5, $h1+10, 274.5,$y1, $verft4);

				$pdf->SetFont('freeserif','B',11,'',true);
				$pdf->SetY($d4+17);
	 			$pdf->SetX(151);
	 			$pdf->MultiCell(50, 5, 'รวมยอดเงินได้และภาษีที่นำส่ง', 0, 'R', 0, 0, '', '', true);

	 			$pdf->SetFont('freeserif','B',11,'',true);
				$pdf->SetY($d4+17);
	 			$pdf->SetX(211);
	 			$pdf->MultiCell(30, 5, number_format($sum1, 2), 0, 'R', 0, 0, '', '', true);

	 			$pdf->SetFont('freeserif','B',11,'',true);
				$pdf->SetY($d4+17);
	 			$pdf->SetX(241);
	 			$pdf->MultiCell(32, 5, number_format($sum2, 2), 0, 'R', 0, 0, '', '', true);

				//แนวนอน
				$horft1 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(274.5, $x1+10, 5,$x1+10, $horft1);

			    //================================= last page footer ======================================//
				$pdf->SetFont('freeserif','B',11,'',true);
				$pdf->SetY(145);
	 			$pdf->SetX(8);
	 			$pdf->MultiCell(30, 5, 'หมายเหตุ', 0, 'C', 0, 0, '', '', true);
				
	 			$pdf->SetFont('freeserif','',10,'',true);
				$pdf->SetY(155);
	 			$pdf->SetX(5);
	 			$pdf->MultiCell(180, 5, '1.ให้ระบุว่า มี หรือ ไม่มี ภริยาโดยใส่เครื่องหมาย / ลงใน หน้าข้อความแต่กรณีพร้อมทั้งกรอกจำนวนบุตรที่มีสิทธิหักลดหย่อนศึกษากี่คน ไม่ศึกษากี่คนและยอดรวมจำนวนเงินค่าลดหย่อนอื่น ๆ ที่จ่ายให้แก่เบี้ยประกันชีวิต เงินสะสมดอกเบี้ยเงินกู้ยืมเพื่อซื้อ เช้าซื้อ หรือสร้างอาคารที่อยู่อาศัย ฯ และเงินสมทบ ฯ', 0, 'L', 0, 0, '', '', true);
				
				$pdf->SetFont('freeserif','',10,'',true);
				$pdf->SetY(175);
	 			$pdf->SetX(5);
	 			$pdf->MultiCell(180, 5, '2.ให้กรอกประเภทเงินได้ที่จ่าย เช่น เงินเดือน ค่าจ้าง เบี้ยเลี้ยง โบนัส บำเหน็จ เงินค่าธรรมเนียม ค่านายหน้า เปบี้ยประชุมค่าภาษีเงินได้ ฯลฯ', 0, 'L', 0, 0, '', '', true);
				
	 			$pdf->SetFont('freeserif','',10,'',true);
				$pdf->SetY(185);
	 			$pdf->SetX(5);
	 			$pdf->MultiCell(180, 5, '3.จำนวนคราวที่จ่ายทั้งปี -จ่ายเป็นรายวัน กรอก 1   -จ่ายเป็นรายสัปดาห์ กรอก 2     -จ่ายเป็นรายปักษ์ กรอก 3     -จ่ายเป็นรายเดือน กรอก 4     -จ่ายเป็นคราวไม่แน่นอน กรอก 5', 0, 'L', 0, 0, '', '', true);			
				
	 			$pdf->SetFont('freeserif','B',10,'',true);
				$pdf->SetY(155);
	 			$pdf->SetX(180);
	 			$pdf->MultiCell(94, 5, 'ลงชื่อ................................................................ผู้จ่ายเงิน', 0, 'C', 0, 0, '', '', true);
				
				$pdf->SetFont('freeserif','B',11,'',true);
				$pdf->SetY(164);
	 			$pdf->SetX(180);
	 			$pdf->MultiCell(94, 5, $director, 0, 'C', 0, 0, '', '', true);
				
				$pdf->SetY(170);
	 			$pdf->SetX(180);
	 			$pdf->MultiCell(94, 5, 'นายแพทย์เชี่ยวชาญ', 0, 'C', 0, 0, '', '', true);

	 			$pdf->SetY(180);
	 			$pdf->SetX(180);
	 			$pdf->MultiCell(94, 5, 'ผู้อำนวยการโรงพยาบาลโนนไทย', 0, 'C', 0, 0, '', '', true);
	 			
				$pdf->SetY(190);
	 			$pdf->SetX(180);
	 			$pdf->MultiCell(94, 5, 'ยื่นวันที่.................................................................', 0, 'C', 0, 0, '', '', true);
		}// check result > 0

			$filename = storage_path() . '/report_tax_itpc_pts_emp3.pdf';
		    // Response::download($filename);
		    $contents = $pdf->output($filename, 'I');
			$headers = array(
			    'Content-Type' => 'application/pdf',
			);
			return Response::make($contents, 200, $headers);
		}
		else
		{
			return View::make('login.index');
		}
	}



	/**
	* ใบสรุปรายได้เพื่อหักภาษี ข้าราชการ พตส. เงินงบประมาณ
	*/
	public function sumsalarytax()
	{
		if( Session::get('level') != '' )
		{
			$y = DB::Select( ' select year(order_date) as year1 from s_salary_ocsc_detail group by year(order_date) order by year(order_date) desc ' );	
			return View::make( 'tax.sumsalarytax', array( 'data' => $y ) );
		}
		else
		{
			return View::make('login.index');
		}  
	}

	public function sumsalarytax_pdf()
	{
		$y = Input::get('y');

		
		$pdf = new TCPDF();
		$pdf->SetPrintHeader(true);
	    $pdf->SetPrintFooter(true);	

	    $pdf->setHeaderFont(array('freeserif','B',13));
		$pdf->setFooterFont(array('freeserif','B',PDF_FONT_SIZE_DATA));

	    $pdf->SetHeaderData('', '', 'ใบสรุปรายได้ เพื่อหักภาษี สำหรับข้าราชการและพนักงานของรัฐ ประจำปี '.($y+543), ' ลำดับ    บัตรประชาชน                   ชื่อ-นามสกุล                                         รวมรายได้ พตส.+ค่าตอบแทน           เซ็นต์ชื่อ  ');			
		 		   
		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		 
		// set margins
		$pdf->SetMargins(10, PDF_MARGIN_TOP, 10);
		$pdf->SetHeaderMargin(15);
		$pdf->SetFooterMargin(15);

		$pdf->SetFont('freeserif','',11,'',true);

		$pdf->AddPage('L', 'A4');

		$sql = ' select concat(n.pname,"",n.fname," ",n.lname) as name, s.cid, sum(s.pts2+s.ch112) as special from s_salary_ocsc_detail s left join n_datageneral n on n.cid=s.cid left join n_position_salary p on p.cid=n.cid where  p.`level` in ("ข้าราชการ","ลูกจ้างประจำ")  and  year(s.order_date)='.$y.'  group by s.cid order by n.datainfoID asc ';	
		
		
		$result = DB::select( $sql );

		$tbl  = ' <style> ';
		$tbl .= '  table.table-report tr td{ border-bottom:1px solid #000; height:30px; line-height: 30px; } ';	
		$tbl .= ' .text-bold { font-weight: bold; } ';		
		$tbl .= ' </style> ';

		$tbl  .= ' <table class="table-report"> ';		    
		 
		$r=0;	
		
	    foreach ($result as $key) 		    
	    {	
	    	if( $key->special > 0 )
	    	{
		        $r++;
		       
		    	$tbl .= ' <tr>';

			    $tbl .= ' <td width="25">';
			    $tbl .= $r;
			    $tbl .= ' </td>';
			    
			    $tbl .= ' <td width="100">';
			    $tbl .= $key->cid;
			    $tbl .= ' </td>';

			    $tbl .= ' <td width="250">';
			    $tbl .= $key->name;
			    $tbl .= ' </td>';

			    $tbl .= ' <td width="85" align="right">';
			    $tbl .= number_format($key->special, 2);
			    $tbl .= ' </td>';

			    $tbl .= ' <td width="200" align="right"> </td>';

			    $tbl .= ' </tr>';
			}
		}

		$tbl  .= ' </table>';	
	   
		$pdf->writeHTML( $tbl, true, false, false, false, '' );

	    $filename = storage_path() . '/report_sumsalarytax.pdf';
	    //return Response::download($filename);
	    $contents = $pdf->output($filename, 'I');
		$headers = array(
		    'Content-Type' => 'application/pdf',
		);
		return Response::make($contents, 200, $headers); 
	}





	/*
	* function name itpc_home4
	* ภงด 1 ก  Home 4  พนักงานราชการ
	*
	*/
	public function itpc_home4()
	{
		if( Session::get('level') != '' )
		{
			$y = DB::Select( ' select year(order_date) as year1 from s_salary_detail group by year(order_date) order by year(order_date) desc ' );	
			return View::make( 'tax.itpc_home4', array( 'data' => $y ) );
		}
		else
		{
			return View::make('login.index');
		}  
	}

	/*
	* function name tax_itpc_type4
	* ภงด 1 ก
	*  fix cid = 5350400051484
	*/
	public function tax_itpc_type4()
	{
		$y = Input::get('y1');

		if( Session::get('level') != '' )
		{
			$n = DB::select( 'select * from s_general_data' );	
		    foreach ($n as $k) {
		      	$name 		= $k->name;
		      	$address 	= $k->address;
		      	$tax_id2 	= $k->tax_id2;
		      	$director 	= $k->director;
		    } 

			$pdf = new TCPDF();			
			
			$pdf->SetHeaderData('', '', 'ภ.ง.ด 1 ก พิเศษ', 'เลขประจำตัวผู้เสียภาษีอากร(ของผู้มีหน้าที่หักภาษี ณ ที่จ่าย)       '.$tax_id2 );
			
			// set header and footer fonts
			$pdf->setHeaderFont(Array('freeserif', '', 13));
			$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));	  

			$pdf->SetMargins(5, PDF_MARGIN_TOP, 5);
			$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
			$pdf->SetFooterMargin(PDF_MARGIN_FOOTER); 	   		   
		   
		    $sql = ' select concat(n.pname,"",n.fname," ",n.lname) as name, s.cid, s.tax_id,sum(s.salary+s.r_other) as salary , sum(s.r_c) as r_c, sum(s.special_m+s.pts+s.pts2) as special, sum(s.tax) as tax from s_salary_ocsc_detail s left join n_datageneral n on n.cid=s.cid left join n_position_salary p on p.cid=n.cid where  p.level in ("ข้าราชการ","ลูกจ้างประจำ") and year(s.order_date)='.$y.' and s.cid in (5350400051484, 3300900035899) group by s.cid order by n.datainfoID asc ';		    
		    $result = DB::select( $sql );
			$j=0;
			$i=0;
			$sum1=0;
			$sum2=0;
			$row=0;
		    foreach ( $result as $key ) 
		    {			    
		    	$row++;		    	
		    	if( $j==4 )
	    		{
	    			$j=0;
	    		}

		    	if( $j == 0)
		    	{		    		
		    		$pdf->AddPage('L', 'letter');	
		    					
		 			//===== แนวตั้ง =====//
		 			$linever1 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(5, 60, 5,18, $linever1);

					$pdf->SetFont('freeserif','',11,'',true);
					$pdf->SetY(35);
		 			$pdf->SetX(5);
		 			$pdf->MultiCell(13, 5, 'ลำดับ', 0, 'C', 0, 1, '', '', true);

					//--col 2
					$linever2 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(18, 60, 18,18, $linever2);

					$pdf->SetY(24);
		 			$pdf->SetX(19);
		 			$pdf->MultiCell(60, 5, 'เลขประจำตัวประชาชน(ของผู้มีเงินได้)', 0, 'L', 0, 1, '', '', true);

		 			$pdf->SetY(34);
		 			$pdf->SetX(19);
		 			$pdf->MultiCell(62, 5, 'เลขประจำตัวผู้เสียภาษีอากร(ของผู้มีเงินได้)', 0, 'L', 0, 1, '', '', true);

		 			$pdf->SetY(49);
		 			$pdf->SetX(19);
		 			$pdf->MultiCell(60, 5, 'ชื่อ ที่อยู่ ผู้มีเงินได้', 0, 'L', 0, 1, '', '', true);


					//--col 3
					$linever3 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(80, 60, 80,18, $linever3);

					$pdf->SetY(24);
		 			$pdf->SetX(81);
		 			$pdf->MultiCell(20, 5, 'วัน เดือน ปี ที่เข้าทำงาน(เฉพาะกรณีเข้าทำงานระหว่างปี)', 0, 'L', 0, 1, '', '', true);



					//--col 4
					$linever4 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(105, 60, 105,18, $linever4);
					
					$linever41 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(116, 60, 116,26, $linever41);

					$linever42 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(126, 60, 126,34, $linever42);

					$linever43 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(137, 60, 137,26, $linever43);

					$linever44 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(150, 60, 150,46, $linever44);

					$pdf->SetY(19);
		 			$pdf->SetX(106);
		 			$pdf->MultiCell(58, 5, '(1)รายการลดหย่อน', 0, 'C', 0, 1, '', '', true);

		 			$pdf->SetY(28);
		 			$pdf->SetX(105);
		 			$pdf->MultiCell(11, 5, 'มีสามีภริยาหรือไม่', 0, 'L', 0, 1, '', '', true);

		 			$pdf->SetY(27);
		 			$pdf->SetX(117);
		 			$pdf->MultiCell(20, 5, 'จำนวนบุตร', 0, 'L', 0, 1, '', '', true);

		 			$pdf->SetY(37);
		 			$pdf->SetX(116);
		 			$pdf->MultiCell(20, 5, 'ศึกษา', 0, 'L', 0, 1, '', '', true);

		 			$pdf->SetY(37);
		 			$pdf->SetX(126);
		 			$pdf->MultiCell(11, 5, 'ไม่ศึกษา', 0, 'L', 0, 1, '', '', true);

		 			$pdf->SetY(27);
		 			$pdf->SetX(137);
		 			$pdf->MultiCell(28, 5, 'ค่าลดหย่อนอื่นๆ', 0, 'L', 0, 1, '', '', true);

		 			$pdf->SetY(37);
		 			$pdf->SetX(137);
		 			$pdf->MultiCell(28, 5, 'จำนวนเงิน', 0, 'C', 0, 1, '', '', true);

		 			$pdf->SetY(49);
		 			$pdf->SetX(136);
		 			$pdf->MultiCell(15, 5, 'บาท', 0, 'C', 0, 1, '', '', true);

		 			$pdf->SetY(49);
		 			$pdf->SetX(149);
		 			$pdf->MultiCell(15, 5, 'สต.', 0, 'C', 0, 1, '', '', true);



					//--col 5
					$linever5 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(165, 60, 165,18, $linever5);

					$linever51 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(194, 60, 194,30, $linever51);

					$linever52 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(212, 60, 212,30, $linever52);

					$linever53 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(233, 60, 233,50, $linever53);

					$pdf->SetY(19);
		 			$pdf->SetX(165);
		 			$pdf->MultiCell(77, 5, 'ประเภทเงินได้พึงประเมิณที่จ่าย(รวมทั้งประโยชน์ที่เพิ่ม อย่างอื่น)ถ้ามากกว่าหนึ่งประเภทให้กรอกเรียงลงไป', 0, 'L', 0, 1, '', '', true);

		 			$pdf->SetY(39);
		 			$pdf->SetX(165);
		 			$pdf->MultiCell(30, 5, '(2)', 0, 'C', 0, 1, '', '', true);
		 			$pdf->SetY(43);
		 			$pdf->SetX(165);
		 			$pdf->MultiCell(30, 5, 'ประเภทเงินได้', 0, 'C', 0, 1, '', '', true);

		 			$pdf->SetY(39);
		 			$pdf->SetX(193);
		 			$pdf->MultiCell(20, 5, '(3)', 0, 'C', 0, 1, '', '', true);
		 			$pdf->SetY(43);
		 			$pdf->SetX(193);
		 			$pdf->MultiCell(20, 5, 'จำนวนคราวที่จ่ายทั้งปี', 0, 'C', 0, 1, '', '', true);
		 			
		 			$pdf->SetY(32);
		 			$pdf->SetX(212);
		 			$pdf->MultiCell(30, 5, 'จำนวนเงินที่จ่ายแต่ละประเภทเฉพาะคนหนึงๆ ทั้งปี', 0, 'L', 0, 1, '', '', true);
		 			
		 			$pdf->SetY(52);
		 			$pdf->SetX(216);
		 			$pdf->MultiCell(20, 5, 'บาท', 0, 'L', 0, 1, '', '', true);

		 			$pdf->SetY(52);
		 			$pdf->SetX(233);
		 			$pdf->MultiCell(8, 5, 'สต.', 0, 'C', 0, 1, '', '', true);


					//--col 6
					$linever6 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(242, 60, 242,18, $linever6);	

					$linever61 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(265, 60, 265,50, $linever61);

					$pdf->SetY(19);
		 			$pdf->SetX(242);
		 			$pdf->MultiCell(33, 5, 'รวมเงินภาษีที่หักและนำส่งในปีที่ล่วงมาแล้ว', 0, 'L', 0, 1, '', '', true);

		 			$pdf->SetY(37);
		 			$pdf->SetX(242);
		 			$pdf->MultiCell(33, 5, 'จำนวนเงิน', 0, 'C', 0, 1, '', '', true);

		 			$pdf->SetY(52);
		 			$pdf->SetX(249);
		 			$pdf->MultiCell(20, 5, 'บาท', 0, 'L', 0, 1, '', '', true);

		 			$pdf->SetY(52);
		 			$pdf->SetX(266);
		 			$pdf->MultiCell(8, 5, 'สต.', 0, 'C', 0, 1, '', '', true);


					$lineverEnd = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(274.5, 60, 274.5,18, $lineverEnd);	


					//======= แนวนอน =========//
					$linehor1 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(274.5, 60, 5,60, $linehor1);

					//--col 4
					$linehor2 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(165, 26, 105,26, $linehor2);

					$linehor3 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(165, 34, 116,34, $linehor3);

					$linehor4 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(165, 46, 137,46, $linehor4);

					//--col 5
					$linehor5 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(274.5, 30, 165,30, $linehor5);

					$linehor6 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(274.5, 50, 212,50, $linehor6);					

				}//end add header

				if( $j == 0 ){
					$x1=95;
					$y1=60;
					$h1=95;
					
					$d1=61;
					$d2=68;
					$d3=74;
					$d4=81;
				}else if( $j == 1 ){
					$x1=130;
					$y1=90;
					$h1=130;
					
					$d1=96;
					$d2=103;
					$d3=109;
					$d4=116;
				}else if( $j == 2 ){
					$x1=165;
					$y1=120;
					$h1=165;
					
					$d1=131;
					$d2=138;
					$d3=144;
					$d4=151;	
				}else if( $j == 3 ){
					$x1=197;
					$y1=150;
					$h1=197;
					
					$d1=166;
					$d2=171;
					$d3=176;
					$d4=181;
				}

				//loop นอน
				$linehor = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(274.5, $x1, 5,$x1, $linehor);

				//loop ตั้ง
				$linever_loop1 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(5, $h1, 5,$y1, $linever_loop1);

				$linever_loop2 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(18, $h1, 18,$y1, $linever_loop2);

				$linever_loop3 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(80, $h1, 80,$y1, $linever_loop3);

				$linever_loop4 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(105, $h1, 105,$y1, $linever_loop4);

				$linever_loop5 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(116, $h1, 116,$y1, $linever_loop5);

				$linever_loop6 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(126, $h1, 126,$y1, $linever_loop6);

				$linever_loop7 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(137, $h1, 137,$y1, $linever_loop7);

				$linever_loop8 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(165, $h1, 165,$y1, $linever_loop8);

				$linever_loop9 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(194, $h1, 194,$y1, $linever_loop9);

				$linever_loop10 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(212, $h1, 212,$y1, $linever_loop10);

				$linever_loop11 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(242, $h1, 242,$y1, $linever_loop11);

				$linever_loop12 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(274.5, $h1, 274.5,$y1, $linever_loop12);

				//loop data
				
				//col number
				$pdf->SetFont('freeserif','',11,'',true);
				$pdf->SetY($d1);
	 			$pdf->SetX(5);
	 			$pdf->MultiCell(13, 5, $row, 0, 'C', 0, 0, '', '', true);
				
				//col 1 data
				$pdf->SetFont('freeserif','B',11,'',true);
				$pdf->SetY($d1);
	 			$pdf->SetX(19);
	 			$pdf->MultiCell(60, 5, $key->cid, 0, 'L', 0, 0, '', '', true);	

	 			$pdf->SetFont('freeserif','',11,'',true);
	 			$pdf->SetY($d2);
	 			$pdf->SetX(19);
	 			$pdf->MultiCell(60, 5, $key->tax_id, 0, 'L', 0, 0, '', '', true);	

	 			$pdf->SetFont('freeserif','B',11,'',true);
	 			$pdf->SetY($d3);
	 			$pdf->SetX(19);
	 			$pdf->MultiCell(60, 5, $key->name, 0, 'L', 0, 0, '', '', true);
				
				$pdf->SetFont('freeserif','',11,'',true);
				$pdf->SetY($d4);
	 			$pdf->SetX(19);
	 			$pdf->MultiCell(60, 5, $address, 0, 'L', 0, 0, '', '', true);	 

	 			//col 8
	 			$pdf->SetFont('freeserif','',11,'',true);
				$pdf->SetY($d1);
	 			$pdf->SetX(166);
	 			$pdf->MultiCell(28, 5, 'เงินเดือน ค่าจ้าง บำนาญ เบี้ยเลี้ยง โบนัส', 0, 'L', 0, 0, '', '', true);	

	 			$pdf->SetFont('freeserif','',11,'',true);
				$pdf->SetY($d1+16);
	 			$pdf->SetX(166);
	 			$pdf->MultiCell(28, 5, 'เงินประจำตำแหน่ง', 0, 'L', 0, 0, '', '', true);

	 			$pdf->SetFont('freeserif','',11,'',true);
				$pdf->SetY($d3+10);
	 			$pdf->SetX(166);
	 			$pdf->MultiCell(28, 5, 'เงินค่าตอบแทน พตส ค่าครองชีพ', 0, 'L', 0, 0, '', '', true);	

	 			//col 10 salary
	 			$pdf->SetFont('freeserif','',11,'',true);
				$pdf->SetY($d1);
	 			$pdf->SetX(209);
	 			$pdf->MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);

				 // r_c
	 			$pdf->SetFont('freeserif','',11,'',true);
				$pdf->SetY($d1+16);
	 			$pdf->SetX(209);
	 			$pdf->MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);

	 			$pdf->SetFont('freeserif','',11,'',true);
				$pdf->SetY($d3+10);
	 			$pdf->SetX(209);
	 			$pdf->MultiCell(30, 5, number_format( ($key->salary+$key->special), 2 ), 0, 'R', 0, 0, '', '', true);	

	 			//col 11 tax		
	 			$pdf->SetFont('freeserif','',11,'',true);
				$pdf->SetY($d1);
	 			$pdf->SetX(241);
	 			$pdf->MultiCell(30, 5, '', 0, 'R', 0, 0, '', '', true);

	 			$j++;		    	

		    	$sum1 = $sum1+($key->salary+$key->special);
		    	$sum2 = $sum2+$key->tax;

		    } //end foreach			    


			//================================ header > 2 ===================================//	
			//================================ header > 2 ===================================//	
			//================================ header > 2 ===================================//	
			if( count($result) > 0 )
			{		
				if( ($j > 2) )	
				{

			    	$pdf->AddPage('L', 'letter');

			    	//===== แนวตั้ง =====//
			 			$linever1 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(5, 60, 5,18, $linever1);

						$pdf->SetFont('freeserif','',11,'',true);
						$pdf->SetY(35);
			 			$pdf->SetX(5);
			 			$pdf->MultiCell(13, 5, 'ลำดับ', 0, 'C', 0, 1, '', '', true);

						//--col 2
						$linever2 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(18, 60, 18,18, $linever2);

						$pdf->SetY(24);
			 			$pdf->SetX(19);
			 			$pdf->MultiCell(60, 5, 'เลขประจำตัวประชาชน(ของผู้มีเงินได้)', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(34);
			 			$pdf->SetX(19);
			 			$pdf->MultiCell(62, 5, 'เลขประจำตัวผู้เสียภาษีอากร(ของผู้มีเงินได้)', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(49);
			 			$pdf->SetX(19);
			 			$pdf->MultiCell(60, 5, 'ชื่อ ที่อยู่ ผู้มีเงินได้', 0, 'L', 0, 1, '', '', true);


						//--col 3
						$linever3 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(80, 60, 80,18, $linever3);

						$pdf->SetY(24);
			 			$pdf->SetX(81);
			 			$pdf->MultiCell(20, 5, 'วัน เดือน ปี ที่เข้าทำงาน(เฉพาะกรณีเข้าทำงานระหว่างปี)', 0, 'L', 0, 1, '', '', true);



						//--col 4
						$linever4 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(105, 60, 105,18, $linever4);
						
						$linever41 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(116, 60, 116,26, $linever41);

						$linever42 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(126, 60, 126,34, $linever42);

						$linever43 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(137, 60, 137,26, $linever43);

						$linever44 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(150, 60, 150,46, $linever44);

						$pdf->SetY(19);
			 			$pdf->SetX(106);
			 			$pdf->MultiCell(58, 5, '(1)รายการลดหย่อน', 0, 'C', 0, 1, '', '', true);

			 			$pdf->SetY(28);
			 			$pdf->SetX(105);
			 			$pdf->MultiCell(11, 5, 'มีสามีภริยาหรือไม่', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(27);
			 			$pdf->SetX(117);
			 			$pdf->MultiCell(20, 5, 'จำนวนบุตร', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(37);
			 			$pdf->SetX(116);
			 			$pdf->MultiCell(20, 5, 'ศึกษา', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(37);
			 			$pdf->SetX(126);
			 			$pdf->MultiCell(11, 5, 'ไม่ศึกษา', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(27);
			 			$pdf->SetX(137);
			 			$pdf->MultiCell(28, 5, 'ค่าลดหย่อนอื่นๆ', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(37);
			 			$pdf->SetX(137);
			 			$pdf->MultiCell(28, 5, 'จำนวนเงิน', 0, 'C', 0, 1, '', '', true);

			 			$pdf->SetY(49);
			 			$pdf->SetX(136);
			 			$pdf->MultiCell(15, 5, 'บาท', 0, 'C', 0, 1, '', '', true);

			 			$pdf->SetY(49);
			 			$pdf->SetX(149);
			 			$pdf->MultiCell(15, 5, 'สต.', 0, 'C', 0, 1, '', '', true);



						//--col 5
						$linever5 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(165, 60, 165,18, $linever5);

						$linever51 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(194, 60, 194,30, $linever51);

						$linever52 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(212, 60, 212,30, $linever52);

						$linever53 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(233, 60, 233,50, $linever53);

						$pdf->SetY(19);
			 			$pdf->SetX(165);
			 			$pdf->MultiCell(77, 5, 'ประเภทเงินได้พึงประเมิณที่จ่าย(รวมทั้งประโยชน์ที่เพิ่ม อย่างอื่น)ถ้ามากกว่าหนึ่งประเภทให้กรอกเรียงลงไป', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(39);
			 			$pdf->SetX(165);
			 			$pdf->MultiCell(30, 5, '(2)', 0, 'C', 0, 1, '', '', true);
			 			$pdf->SetY(43);
			 			$pdf->SetX(165);
			 			$pdf->MultiCell(30, 5, 'ประเภทเงินได้', 0, 'C', 0, 1, '', '', true);

			 			$pdf->SetY(39);
			 			$pdf->SetX(193);
			 			$pdf->MultiCell(20, 5, '(3)', 0, 'C', 0, 1, '', '', true);
			 			$pdf->SetY(43);
			 			$pdf->SetX(193);
			 			$pdf->MultiCell(20, 5, 'จำนวนคราวที่จ่ายทั้งปี', 0, 'C', 0, 1, '', '', true);
			 			
			 			$pdf->SetY(32);
			 			$pdf->SetX(212);
			 			$pdf->MultiCell(30, 5, 'จำนวนเงินที่จ่ายแต่ละประเภทเฉพาะคนหนึงๆ ทั้งปี', 0, 'L', 0, 1, '', '', true);
			 			
			 			$pdf->SetY(52);
			 			$pdf->SetX(216);
			 			$pdf->MultiCell(20, 5, 'บาท', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(52);
			 			$pdf->SetX(233);
			 			$pdf->MultiCell(8, 5, 'สต.', 0, 'C', 0, 1, '', '', true);


						//--col 6
						$linever6 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(242, 60, 242,18, $linever6);	

						$linever61 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(265, 60, 265,50, $linever61);

						$pdf->SetY(19);
			 			$pdf->SetX(242);
			 			$pdf->MultiCell(33, 5, 'รวมเงินภาษีที่หักและนำส่งในปีที่ล่วงมาแล้ว', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(37);
			 			$pdf->SetX(242);
			 			$pdf->MultiCell(33, 5, 'จำนวนเงิน', 0, 'C', 0, 1, '', '', true);

			 			$pdf->SetY(52);
			 			$pdf->SetX(249);
			 			$pdf->MultiCell(20, 5, 'บาท', 0, 'L', 0, 1, '', '', true);

			 			$pdf->SetY(52);
			 			$pdf->SetX(266);
			 			$pdf->MultiCell(8, 5, 'สต.', 0, 'C', 0, 1, '', '', true);


						$lineverEnd = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(274.5, 60, 274.5,18, $lineverEnd);	


						//======= แนวนอน =========//
						$linehor1 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(274.5, 60, 5,60, $linehor1);

						//--col 4
						$linehor2 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(165, 26, 105,26, $linehor2);

						$linehor3 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(165, 34, 116,34, $linehor3);

						$linehor4 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(165, 46, 137,46, $linehor4);

						//--col 5
						$linehor5 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(274.5, 30, 165,30, $linehor5);

						$linehor6 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
						$pdf->Line(274.5, 50, 212,50, $linehor6);
			    } 
			    //================================ END header > 2 ===================================//	
				//================================ END header > 2 ===================================//	
				//================================ END header > 2 ===================================//	   

			    //แนวตั้ง
			    if( $j > 2)
			    {
			    	$h1 = 40;
			    	$y1 = 70;
			    	$d4 = 45;
			    	$x1 = 60;
			    }

			    $verft1 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(5, $h1+10, 5,$y1, $verft1);

				$verft2 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(212, $h1+10, 212,$y1, $verft2);

				$verft3 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(242, $h1+10, 242,$y1, $verft3);

				$verft4 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(274.5, $h1+10, 274.5,$y1, $verft4);

				$pdf->SetFont('freeserif','B',11,'',true);
				$pdf->SetY($d4+17);
	 			$pdf->SetX(151);
	 			$pdf->MultiCell(50, 5, 'รวมยอดเงินได้และภาษีที่นำส่ง', 0, 'R', 0, 0, '', '', true);

				 // sum1
	 			$pdf->SetFont('freeserif','B',11,'',true);
				$pdf->SetY($d4+17);
	 			$pdf->SetX(211);
	 			$pdf->MultiCell(30, 5, number_format($sum1, 2), 0, 'R', 0, 0, '', '', true);

				 //sum2
	 			$pdf->SetFont('freeserif','B',11,'',true);
				$pdf->SetY($d4+17);
	 			$pdf->SetX(241);
	 			$pdf->MultiCell(32, 5, '', 0, 'R', 0, 0, '', '', true);

				//แนวนอน
				$horft1 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(274.5, $x1+10, 5,$x1+10, $horft1);

			    //================================= last page footer ======================================//
				$pdf->SetFont('freeserif','B',11,'',true);
				$pdf->SetY(145);
	 			$pdf->SetX(8);
	 			$pdf->MultiCell(30, 5, 'หมายเหตุ', 0, 'C', 0, 0, '', '', true);
				
	 			$pdf->SetFont('freeserif','',10,'',true);
				$pdf->SetY(155);
	 			$pdf->SetX(5);
	 			$pdf->MultiCell(180, 5, '1.ให้ระบุว่า มี หรือ ไม่มี ภริยาโดยใส่เครื่องหมาย / ลงใน หน้าข้อความแต่กรณีพร้อมทั้งกรอกจำนวนบุตรที่มีสิทธิหักลดหย่อนศึกษากี่คน ไม่ศึกษากี่คนและยอดรวมจำนวนเงินค่าลดหย่อนอื่น ๆ ที่จ่ายให้แก่เบี้ยประกันชีวิต เงินสะสมดอกเบี้ยเงินกู้ยืมเพื่อซื้อ เช้าซื้อ หรือสร้างอาคารที่อยู่อาศัย ฯ และเงินสมทบ ฯ', 0, 'L', 0, 0, '', '', true);
				
				$pdf->SetFont('freeserif','',10,'',true);
				$pdf->SetY(175);
	 			$pdf->SetX(5);
	 			$pdf->MultiCell(180, 5, '2.ให้กรอกประเภทเงินได้ที่จ่าย เช่น เงินเดือน ค่าจ้าง เบี้ยเลี้ยง โบนัส บำเหน็จ เงินค่าธรรมเนียม ค่านายหน้า เปบี้ยประชุมค่าภาษีเงินได้ ฯลฯ', 0, 'L', 0, 0, '', '', true);
				
	 			$pdf->SetFont('freeserif','',10,'',true);
				$pdf->SetY(185);
	 			$pdf->SetX(5);
	 			$pdf->MultiCell(180, 5, '3.จำนวนคราวที่จ่ายทั้งปี -จ่ายเป็นรายวัน กรอก 1   -จ่ายเป็นรายสัปดาห์ กรอก 2     -จ่ายเป็นรายปักษ์ กรอก 3     -จ่ายเป็นรายเดือน กรอก 4     -จ่ายเป็นคราวไม่แน่นอน กรอก 5', 0, 'L', 0, 0, '', '', true);			
				
	 			$pdf->SetFont('freeserif','B',10,'',true);
				$pdf->SetY(155);
	 			$pdf->SetX(180);
	 			$pdf->MultiCell(94, 5, 'ลงชื่อ................................................................ผู้จ่ายเงิน', 0, 'C', 0, 0, '', '', true);
				
				$pdf->SetFont('freeserif','B',11,'',true);
				$pdf->SetY(164);
	 			$pdf->SetX(180);
	 			$pdf->MultiCell(94, 5, $director, 0, 'C', 0, 0, '', '', true);
				
				$pdf->SetY(170);
	 			$pdf->SetX(180);
	 			$pdf->MultiCell(94, 5, 'นายแพทย์เชี่ยวชาญ', 0, 'C', 0, 0, '', '', true);

	 			$pdf->SetY(180);
	 			$pdf->SetX(180);
	 			$pdf->MultiCell(94, 5, 'ผู้อำนวยการโรงพยาบาลโนนไทย', 0, 'C', 0, 0, '', '', true);
	 			
				$pdf->SetY(190);
	 			$pdf->SetX(180);
	 			$pdf->MultiCell(94, 5, 'ยื่นวันที่.................................................................', 0, 'C', 0, 0, '', '', true);
		}// check result > 0

			$filename = storage_path() . '/report_tax_itpc_emp4.pdf';
		    // Response::download($filename);
		    $contents = $pdf->output($filename, 'I');
			$headers = array(
			    'Content-Type' => 'application/pdf',
			);
			return Response::make($contents, 200, $headers);
		}
		else
		{
			return View::make('login.index');
		}	
	}




	/*
	* function name continuous_home4
	* หนังสือรับรอง  Home   พนักงานราชการ
	*
	*/
	public function continuous_home4()
	{		
		if( Session::get('level') != '' )
		{
			$y = DB::Select( ' select year(order_date) as year1 from s_salary_detail group by year(order_date) order by year(order_date) desc ' );	
			return View::make( 'tax.continuous_home4', array( 'data' => $y ) );
		}
		else
		{
			return View::make('login.index');
		} 
	}

	/*
	* function name tax_continuous_type4
	* พิมพ์หนังสือรับรอง   พนักงานราชการ   fix cid=5350400051484
	*
	*/
	public function tax_continuous_type4( $id=null, $year=null )
	{
		if( Session::get('level') != '' )
		{		
			$y = Input::get('y3');
			if($y != ''){
				$year = $y;
				$id = 'all';
			}			

		    $pdf = new TCPDF();
		    $pdf->SetPrintHeader(false);
		    $pdf->SetPrintFooter(false);			   		   

		    $n = DB::select( 'select * from s_general_data' );	
		    foreach ($n as $k) {
		      	$name 		= $k->name;
		      	$address 	= $k->address;
		      	$address2 	= $k->address2;
		      	$tax_id2 	= $k->tax_id2;
		      	$director 	= $k->director;
			} 
			
		    
			$sql = ' select concat(n.pname,"",n.fname," ",n.lname) as name, s.cid, s.tax_id,sum(s.salary+s.r_other) as salary, sum(s.r_c) as r_c, sum(s.special_m+s.pts+s.pts2) as special, sum(s.tax) as tax ,sum(s.kbk) as kbk from s_salary_ocsc_detail s left join n_datageneral n on n.cid=s.cid left join n_position_salary p on p.cid=n.cid  where  p.level in ("ข้าราชการ","ลูกจ้างประจำ") and  s.cid in (5350400051484, 3300900035899)  and  year(s.order_date)='.$year.' group by s.cid order by n.datainfoID asc ';		    	    
							

		    $result = DB::select( $sql );		    

		    foreach ( $result as $key ) {

		    	$pdf->AddPage('P', 'A4');

		    	$pdf->SetFont('freeserif','B',11,'',true);		    
	 			$pdf->MultiCell(185, 5, 'เลขที่ งป. ........................./ '.( ($year == 'null') ? $this->yearThai() : ($year+543) ), 0, 'R', 0, 1, '', '', true);

	 			$pdf->SetFont('freeserif','B',16,'',true);
		    	$pdf->SetY(25);
	 			$pdf->SetX(18);
	 			$pdf->MultiCell(177, 5, 'หนังสือรับรองการหักภาษี ณ ที่จ่าย', 0, 'C', 0, 1, '', '', true);

	 			$pdf->SetY(34);
	 			$pdf->SetX(18);
	 			$pdf->MultiCell(177, 5, 'ตามมาตรา 50 ทวิ แห่งประมวลรัษฎากร', 0, 'C', 0, 1, '', '', true);
				
	 			//===== แนวตั้ง =====//
	 			$linever1 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(18, 190, 18,50, $linever1);

				$linever2 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(80, 190, 80,50, $linever2);

				$linever3 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(110, 190, 110,50, $linever3);

				$linever4 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(135, 190, 135,50, $linever4);

				$linever5 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(165, 190, 165,50, $linever5);

				$linever6 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(195, 190, 195,50, $linever6);

				//===== แนวนอน =====//
	 			$linetop = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(18, 50, 195,50, $linetop);

				$linetop2 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(80, 63, 195,63, $linetop2);

				$linetop3 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(18, 120, 80,120, $linetop3);

				$linetop4 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(80, 180, 195,180, $linetop4);

				$linetop5 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(18, 190, 195,190, $linetop5);

				//======= text in box 1 ========//
				$pdf->SetFont('freeserif','',13,'',true);
				$pdf->SetY(52);
	 			$pdf->SetX(19);
	 			$pdf->MultiCell(62, 5, 'ชื่อและที่อยู่ของผู้มีหน้าที่หักภาษี ณ ที่จ่าย บุคคลคณะบุคคล นิติบุคคล ส่วนราชการ องค์การ รัฐวิสาหกิจ ฯลฯ ', 0, 'L', 0, 1, '', '', true);

	 			$pdf->SetFont('freeserif','B',13,'',true);
				$pdf->SetY(82);
	 			$pdf->SetX(19);
	 			$pdf->MultiCell(62, 5, $address2, 0, 'L', 0, 1, '', '', true);

	 			$pdf->SetFont('freeserif','B',13,'',true);
				$pdf->SetY(105);
	 			$pdf->SetX(19);
	 			$pdf->MultiCell(40, 5, $tax_id2, 0, 'L', 0, 1, '', '', true);

	 			//======= text in box 2 ========//
	 			$pdf->SetFont('freeserif','',13,'',true);
				$pdf->SetY(122);
	 			$pdf->SetX(19);
	 			$pdf->MultiCell(62, 5, 'ชื่อและที่อยู่ของผู้ถูกหักภาษี ณ ที่จ่าย', 0, 'L', 0, 1, '', '', true);

	 			$pdf->SetFont('freeserif','B',12,'',true);
				$pdf->SetY(137);
	 			$pdf->SetX(21);
	 			$pdf->MultiCell(59, 5, $key->name, 0, 'L', 0, 1, '', '', true);
	
	 			$pdf->SetFont('freeserif','',13,'',true);
				$pdf->SetY(145);
	 			$pdf->SetX(19);
	 			$pdf->MultiCell(62, 5, $address, 0, 'L', 0, 1, '', '', true);

	 			$pdf->SetFont('freeserif','B',13,'',true);
				$pdf->SetY(165);
	 			$pdf->SetX(19);
	 			$pdf->MultiCell(62, 5, 'เลขประจำตัวผู้เสียภาษีของผู้ถูกหักภาษี ณ ที่จ่าย', 0, 'L', 0, 1, '', '', true);

	 			$pdf->SetFont('freeserif','',12,'',true);
				$pdf->SetY(178);
	 			$pdf->SetX(22);
	 			$pdf->MultiCell(62, 5, $key->cid, 0, 'L', 0, 1, '', '', true);

	 			//======= text in box 3 header content ========//
	 			$pdf->SetFont('freeserif','B',13,'',true);
				$pdf->SetY(54);
	 			$pdf->SetX(83);
	 			$pdf->MultiCell(32, 5, 'เงินได้ที่จ่าย', 0, 'L', 0, 1, '', '', true);

	 			$pdf->SetFont('freeserif','B',13,'',true);
				$pdf->SetY(54);
	 			$pdf->SetX(111);
	 			$pdf->MultiCell(32, 5, 'ปีภาษีที่จ่าย', 0, 'L', 0, 1, '', '', true);

	 			$pdf->SetFont('freeserif','B',13,'',true);
				$pdf->SetY(54);
	 			$pdf->SetX(138);
	 			$pdf->MultiCell(32, 5, 'จำนวนเงิน', 0, 'L', 0, 1, '', '', true);

	 			$pdf->SetFont('freeserif','B',13,'',true);
				$pdf->SetY(54);
	 			$pdf->SetX(167);
	 			$pdf->MultiCell(32, 5, 'ภาษีที่หักไว้', 0, 'L', 0, 1, '', '', true);


	 			//============= text in content ================//
	 			$pdf->SetFont('freeserif','',12,'',true);

	 			//-----col 1
				$pdf->SetY(70);
	 			$pdf->SetX(80);
	 			$pdf->MultiCell(30, 5, 'เงินเดือน ค่าจ้าง บำนาญ เบี้ยเลี้ยง โบนัส ตามมาตรา 40(1)', 0, 'L', 0, 1, '', '', true);

				$pdf->SetY(95);
	 			$pdf->SetX(80);
	 			$pdf->MultiCell(31, 5, 'เงินประจำตำแหน่ง', 0, 'L', 0, 1, '', '', true);

	 			$pdf->SetY(104);
	 			$pdf->SetX(80);
	 			$pdf->MultiCell(27, 5, 'เงินค่าตอบแทนพิเศษ พตส ค่าครองชีพ', 0, 'L', 0, 1, '', '', true);

	 			//-----col 2
	 			$pdf->SetY(70);
	 			$pdf->SetX(116);
	 			$pdf->MultiCell(31, 5, ( ($year == 'null') ? $this->yearThai() : ($year+543) ) , 0, 'L', 0, 1, '', '', true);

	 			//-----col 3 เงินเดือน
	 			$pdf->SetY(70);
	 			$pdf->SetX(135);
	 			$pdf->MultiCell(30, 5, '', 0, 'R', 0, 1, '', '', true);

				 //------- r_c ประจำตำแหน่ง
	 			$pdf->SetY(95);
	 			$pdf->SetX(135);
	 			$pdf->MultiCell(30, 5, '', 0, 'R', 0, 1, '', '', true);

	 			$pdf->SetY(104);
	 			$pdf->SetX(135);
	 			$pdf->MultiCell(30, 5, number_format( ($key->salary + $key->special), 2 ), 0, 'R', 0, 1, '', '', true);

	 			//-----col 4 tax
	 			$pdf->SetY(70);
	 			$pdf->SetX(165);
	 			$pdf->MultiCell(30, 5, '', 0, 'R', 0, 1, '', '', true);
	 			

	 			//============= text in box 4 footer sum ============//

	 			$pdf->SetFont('freeserif','B',13,'',true);
				$pdf->SetY(182);
	 			$pdf->SetX(89);
	 			$pdf->MultiCell(32, 5, 'รวม', 0, 'L', 0, 1, '', '', true);

				 // รวม
	 			$pdf->SetFont('freeserif','',12,'',true);
	 			$pdf->SetY(182);
	 			$pdf->SetX(135);
	 			$pdf->MultiCell(30, 5, number_format( ($key->salary + $key->special), 2 ), 0, 'R', 0, 1, '', '', true);

				 // tax
	 			$pdf->SetFont('freeserif','',12,'',true);
	 			$pdf->SetY(182);
	 			$pdf->SetX(165);
	 			$pdf->MultiCell(30, 5, '', 0, 'R', 0, 1, '', '', true);


	 			//============= text footer ================//
	 			$pdf->SetFont('freeserif','',12,'',true);

				$pdf->SetY(195);
	 			$pdf->SetX(22);
	 			$pdf->MultiCell(32, 5, 'ผู้จ่ายเงิน', 0, 'L', 0, 1, '', '', true);
	 			
				$pdf->SetY(195);
	 			$pdf->SetX(39);
	 			$pdf->MultiCell(5, 5, '', 1, 'L', 0, 1, '', '', true);
	 			
				$pdf->SetY(195);
	 			$pdf->SetX(44);
	 			$pdf->MultiCell(30, 5, '(1) หัก ณ ที่จ่าย', 0, 'L', 0, 1, '', '', true);

				$pdf->SetY(195);
	 			$pdf->SetX(73);
	 			$pdf->MultiCell(5, 5, '', 1, 'L', 0, 1, '', '', true);
	 			
				$pdf->SetY(195);
	 			$pdf->SetX(78);
	 			$pdf->MultiCell(35, 5, '(2) ออกให้ตลอดไป', 0, 'L', 0, 1, '', '', true);
	 			
				$pdf->SetY(195);
	 			$pdf->SetX(112);
	 			$pdf->MultiCell(5, 5, '', 1, 'L', 0, 1, '', '', true);
	 			
				$pdf->SetY(195);
	 			$pdf->SetX(117);
	 			$pdf->MultiCell(35, 5, '(3) ออกให้ครั้งเดียว', 0, 'L', 0, 1, '', '', true);
	 			
				$pdf->SetY(205);
	 			$pdf->SetX(39);
	 			$pdf->MultiCell(5, 5, ' /', 1, 'L', 0, 1, '', '', true);

				$pdf->SetY(205);
	 			$pdf->SetX(44);
	 			$pdf->MultiCell(100, 5, '(4) เงินสบทบกองทุนประกันสังคม '.'  '.number_format($key->kbk, 2).' บาท', 0, 'L', 0, 1, '', '', true);

	 			$pdf->SetFont('freeserif','B',12,'',true);
	 			$pdf->SetY(220);
	 			$pdf->SetX(18);
	 			$pdf->MultiCell(177, 5, 'ข้าพเจ้าขอรับรองว่า ข้อความและตัวเลขดังกล่าวข้างต้นนี้ถูกต้องตามความเป็นจริงทุกประการ', 0, 'R', 0, 1, '', '', true);

	 			$pdf->SetFont('freeserif','',12,'',true);
	 			$pdf->SetY(235);
	 			$pdf->SetX(32);
	 			$pdf->MultiCell(170, 5, 'ลงชื่อ...........................................................ผู้มีหน้าที่หักภาษี ณ ที่จ่าย', 0, 'C', 0, 1, '', '', true);
	 			
	 			$pdf->SetY(245);
	 			$pdf->SetX(32);
	 			$pdf->MultiCell(140, 5, $director, 0, 'C', 0, 1, '', '', true);

	 			$pdf->SetY(255);
	 			$pdf->SetX(32);
	 			$pdf->MultiCell(140, 5, 'นายแพทย์เชี่ยวชาญ', 0, 'C', 0, 1, '', '', true);

	 			$pdf->SetY(265);
	 			$pdf->SetX(32);
	 			$pdf->MultiCell(140, 5, 'ผู้อำนวยการโรงพยาบาลโนนไทย', 0, 'C', 0, 1, '', '', true);		
	 			
		    }

			$filename = storage_path() . '/report_tax_continuous_emp4.pdf';
		    // Response::download($filename);
		    $contents = $pdf->output($filename, 'I');
			$headers = array(
			    'Content-Type' => 'application/pdf',
			);
			return Response::make($contents, 200, $headers);
		}
		else
		{
			return View::make('login.index');
		}	
	}



}
