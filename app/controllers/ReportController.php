<?php

class ReportController extends BaseController {


	private function max_month()
	{
		return date("m", strtotime( DB::table('s_salary_detail')->max('order_date') ));
	}

	private function max_year()
	{
		return date("Y", strtotime( DB::table('s_salary_detail')->max('order_date') ));
	}

	/**
    * function name : monthyearThai
    * get data แสดง เดือน ปี ไทย
    * 
    */
	private function monthyearThai()
	{
		$thaiweek=array("วันอาทิตย์","วันจันทร์","วันอังคาร","วันพุธ","วันพฤหัส","วันศุกร์","วันเสาร์");

     	$thaimonth=array("มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","      มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");

     	//echo $thaiweek[date("w")] ,"ที่",date(" j "), $thaimonth[date(" m ")-1] , " พ.ศ. ",date(" Y ")+543;
     	// ผลลัพธ์จะได้ดังนี้ครับ วันเสาร์ที่ 26 กันยายน พ.ศ. 2552
     	return $thaimonth[date(" m ")-1].' '.( date(" Y ")+543 );
	}

	private function get_monthyearThai( $m, $y )
	{
		$thaiweek=array("วันอาทิตย์","วันจันทร์","วันอังคาร","วันพุธ","วันพฤหัส","วันศุกร์","วันเสาร์");

     	$thaimonth=array("มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","      มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");

     	//echo $thaiweek[date("w")] ,"ที่",date(" j "), $thaimonth[date(" m ")-1] , " พ.ศ. ",date(" Y ")+543;
     	// ผลลัพธ์จะได้ดังนี้ครับ วันเสาร์ที่ 26 กันยายน พ.ศ. 2552
     	return $thaimonth[$m-1].' '.( $y+543 );
	}

	/**
    * function name : numberTotext
    * เลข ไป ตัวหนังสือ
    * 
    */
	private function ThaiBahtConversion($amount_number)
	{
	    $amount_number = number_format($amount_number, 2, ".","");
	    //echo "<br/>amount = " . $amount_number . "<br/>";
	    $pt = strpos($amount_number , ".");
	    $number = $fraction = "";
	    if ($pt === false) 
	        $number = $amount_number;
	    else
	    {
	        $number = substr($amount_number, 0, $pt);
	        $fraction = substr($amount_number, $pt + 1);
	    }	    
	    
	    //list($number, $fraction) = explode(".", $number);
	    $ret = "";
	    $baht = $this->ReadNumber ($number);
	    if ($baht != "")
	        $ret .= $baht . "บาท";
	    
	    $satang = $this->ReadNumber($fraction);
	    if ($satang != "")
	        $ret .=  $satang . "สตางค์";
	    else 
	        $ret .= "ถ้วน";
	    //return iconv("UTF-8", "TIS-620", $ret);
	    return $ret;
	}

	private function ReadNumber($number)
	{
	    $position_call = array("แสน", "หมื่น", "พัน", "ร้อย", "สิบ", "");
	    $number_call = array("", "หนึ่ง", "สอง", "สาม", "สี่", "ห้า", "หก", "เจ็ด", "แปด", "เก้า");
	    $number = $number + 0;
	    $ret = "";
	    if ($number == 0) return $ret;
	    if ($number > 1000000)
	    {
	        $ret .= $this->ReadNumber(intval($number / 1000000)) . "ล้าน";
	        $number = intval(fmod($number, 1000000));
	    }
	    
	    $divider = 100000;
	    $pos = 0;
	    while($number > 0)
	    {
	        $d = intval($number / $divider);
	        $ret .= (($divider == 10) && ($d == 2)) ? "ยี่" : 
	            ((($divider == 10) && ($d == 1)) ? "" :
	            ((($divider == 1) && ($d == 1) && ($ret != "")) ? "เอ็ด" : $number_call[$d]));
	        $ret .= ($d ? $position_call[$pos] : "");
	        $number = $number % $divider;
	        $divider = $divider / 10;
	        $pos++;
	    }
	    return $ret;
	}


	/**
    * function name : salary_all
    * get data รวมเงินเดือน ลูกจ้างชั่วคราว-พกส.(ปฏิบัติงาน)
    * 
    */
	public function salary_all()
	{
		if( Session::get('level') != '' )
		{
			$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		    $pdf->SetPrintHeader(false);
		    //$pdf->SetPrintFooter(false);	
			 
			// set header and footer fonts
			$pdf->setHeaderFont(array('freeserif','B',PDF_FONT_SIZE_MAIN));
			$pdf->setFooterFont(array('freeserif','B',PDF_FONT_SIZE_DATA));
			 
			// set default monospaced font
			$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
			 
			// set margins
			$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
			$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
			$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
	 
			$pdf->SetFont('freeserif','',14,'',true);

		    $pdf->AddPage();

		    $n = DB::select( 'select * from s_general_data' );	
		    foreach ($n as $k) {
		      	$name = $k->name;
		    }  		    
		   
		    $pdf->MultiCell(165, 5, 'รายงานสรุปเงินเดือน ลูกจ้างชั่วคราว/พกส.(ปฏิบัติงาน)'.$name, 0, 'L', 0, 1, '', '', true);
		    $pdf->MultiCell(128, 5, 'ประจำเดือน '. $this->get_monthyearThai( $this->max_month(), $this->max_year() ), 0, 'C', 0, 1, '', '', true);
		    $pdf->MultiCell(155, 5, '______________________________________________________', 0, 'L', 0, 1, '', '', true);     

		    $sql  = ' select sum(s.salary) as salary, sum(s.salary_other) as salary_other, ';
		    $sql .= ' sum(s.salary_sso) as salary_sso, sum(s.salary_cpk) as salary_cpk ';
		    $sql .= ' , sum(s.save) as save, sum(s.shop) as shop, sum(s.rice) as rice, ';
		    $sql .= ' sum(s.water) as water, sum(s.elec) as elec, sum(s.other) as other ';
		    $sql .= ' from n_datageneral n ';
			$sql .= ' inner join  s_salary_detail s on s.cid=n.cid '; 
			$sql .= ' where month(s.order_date)='.$this->max_month().' and year(s.order_date)='.$this->max_year();
			$sql .= '  and (';
			$sql .= '  ((select np1.level from n_position_salary np1 where np1.cid=n.cid order by np1.salaryID desc limit 1) = "พกส.(ปฏิบัติงาน)" ) or ';
			$sql .= '  ((select np2.level from n_position_salary np2 where np2.cid=n.cid order by np2.salaryID desc limit 1) = "ลูกจ้างชั่วคราว") ';
			$sql .= '  ) order by n.q_pts asc';

		    $result = DB::select( $sql );		
			foreach ( $result as $key ) {
				$pdf->SetFont('freeserif','',12,'',true);
			    $pdf->MultiCell(74, 5, 'เงินเดือนรวม', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(50, 5, number_format( $key->salary, 2 ), 0, 'R', 0, 0, '', '', true);
				$pdf->MultiCell(10, 5, 'บาท', 0, 'R', 0, 1, '', '', true);

				$pdf->MultiCell(74, 5, 'รับอื่น ๆ รวม', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(50, 5, number_format( $key->salary_other, 2 ), 0, 'R', 0, 0, '', '', true);
				$pdf->MultiCell(10, 5, 'บาท', 0, 'R', 0, 1, '', '', true);

			   	$pdf->MultiCell(74, 5, 'ประกันสังคม', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(50, 5, number_format( $key->salary_sso, 2 ), 0, 'R', 0, 0, '', '', true);
				$pdf->MultiCell(10, 5, 'บาท', 0, 'R', 0, 1, '', '', true);

				$pdf->MultiCell(74, 5, 'ฌกส', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(50, 5, number_format( $key->salary_cpk, 2 ), 0, 'R', 0, 0, '', '', true);
				$pdf->MultiCell(10, 5, 'บาท', 0, 'R', 0, 1, '', '', true);

				$pdf->MultiCell(74, 5, 'ค่าธรรมเนียมธนาคาร', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(50, 5, number_format( $key->save, 2 ), 0, 'R', 0, 0, '', '', true);
				$pdf->MultiCell(10, 5, 'บาท', 0, 'R', 0, 1, '', '', true);

				$pdf->MultiCell(74, 5, 'สหกรณ์ร้านค้า', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(50, 5, number_format( $key->shop, 2 ), 0, 'R', 0, 0, '', '', true);
				$pdf->MultiCell(10, 5, 'บาท', 0, 'R', 0, 1, '', '', true);

				$pdf->MultiCell(74, 5, 'ลากิจ', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(50, 5, number_format( $key->rice, 2 ), 0, 'R', 0, 0, '', '', true);
				$pdf->MultiCell(10, 5, 'บาท', 0, 'R', 0, 1, '', '', true);

				$pdf->MultiCell(74, 5, 'ค่าน้ำประปา', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(50, 5, number_format( $key->water, 2 ), 0, 'R', 0, 0, '', '', true);
				$pdf->MultiCell(10, 5, 'บาท', 0, 'R', 0, 1, '', '', true);

				$pdf->MultiCell(74, 5, 'ค่าไฟฟ้า', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(50, 5, number_format( $key->elec, 2 ), 0, 'R', 0, 0, '', '', true);
				$pdf->MultiCell(10, 5, 'บาท', 0, 'R', 0, 1, '', '', true);

				$pdf->MultiCell(74, 5, 'ค่าใช้จ่ายอื่น ๆ', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(50, 5, number_format( $key->other, 2 ), 0, 'R', 0, 0, '', '', true);
				$pdf->MultiCell(10, 5, 'บาท', 0, 'R', 0, 1, '', '', true);		

				$pdf->MultiCell(155, 5, '_______________________________________________________________', 0, 'L', 0, 1, '', '', true);  

				$pdf->Ln(2);
				$pdf->MultiCell(74, 5, 'รวมหักค่าใช้จ่าย', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(50, 5, number_format( ( $key->salary_sso+$key->salary_cpk+$key->save+$key->shop+$key->rice+$key->water+$key->elec+$key->other ), 2 ), 0, 'R', 0, 0, '', '', true);
				$pdf->MultiCell(10, 5, 'บาท', 0, 'R', 0, 1, '', '', true);	
				$linestyle1 = array('width' => 0.7, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(15, 111, 150, 111, $linestyle1);  		  		   		    
			    
			    $pdf->Ln(2);
			    $pdf->MultiCell(74, 5, 'รายรับ รวม', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(50, 5, number_format( ($key->salary+$key->salary_other), 2 ), 0, 'R', 0, 0, '', '', true);
				$pdf->MultiCell(10, 5, 'บาท', 0, 'R', 0, 1, '', '', true);	
				$linestyle2 = array('width' => 0.7, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(15, 118, 150, 118, $linestyle2);
			   
			     
				$pdf->Ln(2);
			    $pdf->MultiCell(74, 1, 'รับจริง รวม', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(50, 1, number_format( ($key->salary+$key->salary_other)-($key->salary_sso+$key->salary_cpk+$key->save+$key->shop+$key->rice+$key->water+$key->elec+$key->other), 2 ), 0, 'R', 0, 0, '', '', true);
				$pdf->MultiCell(10, 1, 'บาท', 0, 'R', 0, 1, '', '', true);	

				$linestyle3 = array('width' => 0.7, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(15, 126, 150, 126, $linestyle3);
		    }

		    $filename = storage_path() . '/report_salaryAll1.pdf';
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
    * function name : salary_emp
    * get data รวมเงินเดือน ลูกจ้างชั่วคราว-พกส.(ปฏิบัติงาน) รายคน
    * 
    */
	public function salary_emp()
	{
		if( Session::get('level') != '' )
		{
			$pdf = new TCPDF();
		    $pdf->SetPrintHeader(false);
		    $pdf->SetPrintFooter(false);			 		 				

			$pdf->SetDisplayMode('fullpage', 'SinglePage', 'UseNone');

			$n = DB::select( 'select * from s_general_data' );	
		    foreach ($n as $k) {
		      	$name = $k->name;
		    } 

			$sql  = ' select concat(n.pname,"",n.fname," ",n.lname) as name,salary, salary_other ';
		    $sql .= ' , salary_sso, salary_cpk ';
		    $sql .= ' , save, shop, rice ';
		    $sql .= ' , water, elec, cprt, other ';
		    $sql .= ' from n_datageneral n ';
		    $sql .= ' inner join  s_salary_detail s on s.cid=n.cid ';
			$sql .= ' where s.salary > 0 and month(s.order_date)='.$this->max_month().' and year(s.order_date)='.$this->max_year();
			$sql .= '  and (';
			$sql .= '  ((select np1.level from n_position_salary np1 where np1.cid=n.cid order by np1.salaryID desc limit 1) = "พกส.(ปฏิบัติงาน)" ) or ';
			$sql .= '  ((select np2.level from n_position_salary np2 where np2.cid=n.cid order by np2.salaryID desc limit 1) = "ลูกจ้างชั่วคราว") ';
			$sql .= '  ) order by n.q_pts asc';


		    $result = DB::select( $sql );

		    $i=0;
		    $n=0;		
			foreach ( $result as $key ) {
				$i++;
				$n++;
				if( $i == 5 ){
					$i=0;
					$i++;
				}

				if( $i == 1 ){
					$pdf->AddPage('L', 'letter');
				}

				$linestyle03 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '5,10,5,10', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(0.4, 210, 0.4,0, $linestyle03);

				$linestyle00 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '5,10,5,10', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(68, 210, 68,0, $linestyle00);

				$linestyle01 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '5,10,5,10', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(136, 210, 136,0, $linestyle01);

				$linestyle02 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '5,10,5,10', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(204, 210, 204,0, $linestyle02);

				$linestyle04 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '5,10,5,10', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(272, 210, 272,0, $linestyle04);

				if( $i == 1 ){
					$pdf->SetY(9);
	 				$pdf->SetX(1);
				}
				if( $i == 2 ){
					$pdf->SetY(9);
	 				$pdf->SetX(69);
				}
				if( $i == 3 ){
					$pdf->SetY(9);
	 				$pdf->SetX(137);
				}
				if( $i == 4 ){
					$pdf->SetY(9);
	 				$pdf->SetX(205);
				}
				$pdf->SetFont('freeserif','',14,'',true);
				$pdf->MultiCell(66, 5, $name, 0, 'C', 0, 1, '', '', true);

				if( $i == 1 ){
					$pdf->SetY(15);
	 				$pdf->SetX(1);
				}
				if( $i == 2 ){
					$pdf->SetY(15);
	 				$pdf->SetX(69);
				}
				if( $i == 3 ){
					$pdf->SetY(15);
	 				$pdf->SetX(137);
				}
				if( $i == 4 ){
					$pdf->SetY(15);
	 				$pdf->SetX(205);
				}
			    $pdf->MultiCell(66, 5, 'ประจำเดือน '. $this->get_monthyearThai( $this->max_month(), $this->max_year() ), 0, 'L', 0, 1, '', '', true);

			    if( $i == 1 ){
					$pdf->SetY(18);
	 				$pdf->SetX(1);
				}
			    if( $i == 2 ){
					$pdf->SetY(18);
	 				$pdf->SetX(69);
				}
				if( $i == 3 ){
					$pdf->SetY(18);
	 				$pdf->SetX(137);
				}
				if( $i == 4 ){
					$pdf->SetY(18);
	 				$pdf->SetX(205);
				}
			    $pdf->MultiCell(70, 5, '__________________________', 0, 'L', 0, 1, '', '', true); 		  

			    $pdf->SetFont('freeserif','',12,'',true);

			    if( $i == 1 ){
					$pdf->SetY(25);
	 				$pdf->SetX(1);
				}
			    if( $i == 2 ){
					$pdf->SetY(25);
	 				$pdf->SetX(69);
				}
				if( $i == 3 ){
					$pdf->SetY(25);
	 				$pdf->SetX(137);
				}
				if( $i == 4 ){
					$pdf->SetY(25);
	 				$pdf->SetX(205);
				}
			    $pdf->MultiCell(69, 5, '('.$n.')'.' ชื่อ  '. $key->name, 0, 'L', 0, 1, '', '', true);
			    		    
			    $pdf->Ln(2);
			    if( $i == 1 ){
					$pdf->SetY(35);
	 				$pdf->SetX(1);
				}
			    if( $i == 2 ){
					$pdf->SetY(35);
	 				$pdf->SetX(69);
				}
				if( $i == 3 ){
					$pdf->SetY(35);
	 				$pdf->SetX(137);
				}
				if( $i == 4 ){
					$pdf->SetY(35);
	 				$pdf->SetX(205);
				}
			    $pdf->MultiCell(40, 5, 'เงินเดือนรวม', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(26, 5, number_format( $key->salary, 2 ), 0, 'R', 0, 1, '', '', true);
				
				if( $i == 1 ){
					$pdf->SetY(42);
	 				$pdf->SetX(1);
				}
				if( $i == 2 ){
					$pdf->SetY(42);
	 				$pdf->SetX(69);
				}
				if( $i == 3 ){
					$pdf->SetY(42);
	 				$pdf->SetX(137);
				}
				if( $i == 4 ){
					$pdf->SetY(42);
	 				$pdf->SetX(205);
				}
				$pdf->MultiCell(40, 5, 'รับอื่น ๆ รวม', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(26, 5, number_format( $key->salary_other, 2 ), 0, 'R', 0, 1, '', '', true);
				
				if( $i == 1 ){
					$pdf->SetY(49);
	 				$pdf->SetX(1);
				}
				if( $i == 2 ){
					$pdf->SetY(49);
	 				$pdf->SetX(69);
				}
				if( $i == 3 ){
					$pdf->SetY(49);
	 				$pdf->SetX(137);
				}
				if( $i == 4 ){
					$pdf->SetY(49);
	 				$pdf->SetX(205);
				}
			   	$pdf->MultiCell(40, 5, 'ประกันสังคม', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(26, 5, number_format( $key->salary_sso, 2 ), 0, 'R', 0, 1, '', '', true);
				
				if( $i == 1 ){
					$pdf->SetY(56);
	 				$pdf->SetX(1);
				}
				if( $i == 2 ){
					$pdf->SetY(56);
	 				$pdf->SetX(69);
				}
				if( $i == 3 ){
					$pdf->SetY(56);
	 				$pdf->SetX(137);
				}
				if( $i == 4 ){
					$pdf->SetY(56);
	 				$pdf->SetX(205);
				}
				$pdf->MultiCell(40, 5, 'ฌกส', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(26, 5, number_format( $key->salary_cpk, 2 ), 0, 'R', 0, 1, '', '', true);			

				if( $i == 1 ){
					$pdf->SetY(63);
	 				$pdf->SetX(1);
				}
				if( $i == 2 ){
					$pdf->SetY(63);
	 				$pdf->SetX(69);
				}
				if( $i == 3 ){
					$pdf->SetY(63);
	 				$pdf->SetX(137);
				}
				if( $i == 4 ){
					$pdf->SetY(63);
	 				$pdf->SetX(205);
				}
				$pdf->MultiCell(40, 5, 'ค่าธรรมเนียมธนาคาร', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(26, 5, number_format( $key->save, 2 ), 0, 'R', 0, 1, '', '', true);
				
				if( $i == 1 ){
					$pdf->SetY(70);
	 				$pdf->SetX(1);
				}
				if( $i == 2 ){
					$pdf->SetY(70);
	 				$pdf->SetX(69);
				}
				if( $i == 3 ){
					$pdf->SetY(70);
	 				$pdf->SetX(137);
				}
				if( $i == 4 ){
					$pdf->SetY(70);
	 				$pdf->SetX(205);
				}
				$pdf->MultiCell(40, 5, 'สหกรณ์ร้านค้า', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(26, 5, number_format( $key->shop, 2 ), 0, 'R', 0, 1, '', '', true);			

				if( $i == 1 ){
					$pdf->SetY(77);
	 				$pdf->SetX(1);
				}
				if( $i == 2 ){
					$pdf->SetY(77);
	 				$pdf->SetX(69);
				}
				if( $i == 3 ){
					$pdf->SetY(77);
	 				$pdf->SetX(137);
				}
				if( $i == 4 ){
					$pdf->SetY(77);
	 				$pdf->SetX(205);
				}
				$pdf->MultiCell(40, 5, 'ลากิจ', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(26, 5, number_format( $key->rice, 2 ), 0, 'R', 0, 1, '', '', true);	

				if( $i == 1 ){
					$pdf->SetY(84);
	 				$pdf->SetX(1);
				}
				if( $i == 2 ){
					$pdf->SetY(84);
	 				$pdf->SetX(69);
				}
				if( $i == 3 ){
					$pdf->SetY(84);
	 				$pdf->SetX(137);
				}
				if( $i == 4 ){
					$pdf->SetY(84);
	 				$pdf->SetX(205);
				}
				$pdf->MultiCell(40, 5, 'ค่าน้ำประปา', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(26, 5, number_format( $key->water, 2 ), 0, 'R', 0, 1, '', '', true);			

				if( $i == 1 ){
					$pdf->SetY(91);
	 				$pdf->SetX(1);
				}
				if( $i == 2 ){
					$pdf->SetY(91);
	 				$pdf->SetX(69);
				}
				if( $i == 3 ){
					$pdf->SetY(91);
	 				$pdf->SetX(137);
				}
				if( $i == 4 ){
					$pdf->SetY(91);
	 				$pdf->SetX(205);
				}
				$pdf->MultiCell(40, 5, 'ค่าไฟฟ้า', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(26, 5, number_format( $key->elec, 2 ), 0, 'R', 0, 1, '', '', true);			

				if( $i == 1 ){
					$pdf->SetY(98);
	 				$pdf->SetX(1);
				}
				if( $i == 2 ){
					$pdf->SetY(98);
	 				$pdf->SetX(69);
				}
				if( $i == 3 ){
					$pdf->SetY(98);
	 				$pdf->SetX(137);
				}
				if( $i == 4 ){
					$pdf->SetY(98);
	 				$pdf->SetX(205);
				}

				$pdf->MultiCell(40, 5, 'สหกรณ์ออมทรัพย์', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(26, 5, number_format( $key->cprt, 2 ), 0, 'R', 0, 1, '', '', true);			

				if( $i == 1 ){
					$pdf->SetY(105);
	 				$pdf->SetX(1);
				}
				if( $i == 2 ){
					$pdf->SetY(105);
	 				$pdf->SetX(69);
				}
				if( $i == 3 ){
					$pdf->SetY(105);
	 				$pdf->SetX(137);
				}
				if( $i == 4 ){
					$pdf->SetY(105);
	 				$pdf->SetX(205);
				}

				$pdf->MultiCell(40, 5, 'ค่าใช้จ่ายอื่น ๆ', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(26, 5, number_format( $key->other, 2 ), 0, 'R', 0, 1, '', '', true);				

				if( $i == 1 ){
					$pdf->SetY(107);
	 				$pdf->SetX(1);
				}
				if( $i == 2 ){
					$pdf->SetY(107);
	 				$pdf->SetX(69);
				}
				if( $i == 3 ){
					$pdf->SetY(107);
	 				$pdf->SetX(137);
				}
				if( $i == 4 ){
					$pdf->SetY(107);
	 				$pdf->SetX(205);
				}
				$pdf->MultiCell(70, 5, '______________________________', 0, 'L', 0, 1, '', '', true); 

				$pdf->Ln(2);
				if( $i == 1 ){
					$pdf->SetY(112);
	 				$pdf->SetX(1);
				}
				if( $i == 2 ){
					$pdf->SetY(112);
	 				$pdf->SetX(69);
				}
				if( $i == 3 ){
					$pdf->SetY(112);
	 				$pdf->SetX(137);
				}
				if( $i == 4 ){
					$pdf->SetY(112);
	 				$pdf->SetX(205);
				}
				$pdf->MultiCell(40, 5, 'รวมรับ', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(26, 5, number_format( ($key->salary+$key->salary_other), 2 ), 0, 'R', 0, 1, '', '', true);				
						  		   		    		    
			    $pdf->Ln(4);
			    if( $i == 1 ){
					$pdf->SetY(121);
	 				$pdf->SetX(1);
				}
			    if( $i == 2 ){
					$pdf->SetY(121);
	 				$pdf->SetX(69);
				}
				if( $i == 3 ){
					$pdf->SetY(121);
	 				$pdf->SetX(137);
				}
				if( $i == 4 ){
					$pdf->SetY(121);
	 				$pdf->SetX(205);
				}
			    $pdf->MultiCell(40, 5, 'รวมหัก', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(26, 5, number_format( ( $key->salary_sso+$key->salary_cpk+$key->save+$key->shop+$key->rice+$key->water+$key->elec+$key->cprt+$key->other ), 2 ) , 0, 'R', 0, 1, '', '', true);										
				
				if( $i == 1 ){
					$linestyle2 = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));			
					$pdf->Line(1, 126, 66, 126, $linestyle2);
				}
				if( $i == 2 ){
					$linestyle2 = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));			
					$pdf->Line(69, 126, 134, 126, $linestyle2);
				}
				if( $i == 3 ){
					$linestyle2 = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));			
					$pdf->Line(137, 126, 202, 126, $linestyle2);
				}	
				if( $i == 4 ){
					$linestyle2 = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));			
					$pdf->Line(205, 126, 270, 126, $linestyle2);
				}			
			   	     
				$pdf->Ln(4);
				if( $i == 1 ){
					$pdf->SetY(130);
	 				$pdf->SetX(1);
				}
				if( $i == 2 ){
					$pdf->SetY(130);
	 				$pdf->SetX(69);
				}
				if( $i == 3 ){
					$pdf->SetY(130);
	 				$pdf->SetX(137);
				}
				if( $i == 4 ){
					$pdf->SetY(130);
	 				$pdf->SetX(205);
				}
			    $pdf->MultiCell(40, 1, 'รับจริง', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(26, 1, number_format( ($key->salary+$key->salary_other)-($key->salary_sso+$key->salary_cpk+$key->save+$key->shop+$key->rice+$key->water+$key->elec+$key->cprt+$key->other), 2 ), 0, 'R', 0, 1, '', '', true);
				
				if( $i == 1 ){
					$linestyle3 = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(1, 135, 66, 135, $linestyle3);
				}
				if( $i == 2 ){
					$linestyle3 = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));			
					$pdf->Line(69, 135, 134, 135, $linestyle3);
				}
				if( $i == 3 ){
					$linestyle3 = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));			
					$pdf->Line(137, 135, 202, 135, $linestyle3);
				}
				if( $i == 4 ){
					$linestyle3 = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));			
					$pdf->Line(205, 135, 270, 135, $linestyle3);
				}
				

				$pdf->Ln(4);
				if( $i == 1 ){
					$pdf->SetY(140);
	 				$pdf->SetX(1);
				}
				if( $i == 2 ){
					$pdf->SetY(140);
	 				$pdf->SetX(69);
				}
				if( $i == 3 ){
					$pdf->SetY(140);
	 				$pdf->SetX(137);
				}
				if( $i == 4 ){
					$pdf->SetY(140);
	 				$pdf->SetX(205);
				}
				$pdf->MultiCell(33, 5, 'ผู้รับเงิน', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(26, 5, 'ผู้จ่ายเงิน', 0, 'L', 0, 1, '', '', true);
				
		    }

		    $filename = storage_path() . '/report_salary_emp1.pdf';
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



	public function salary_emp_card()
	{
		if( Session::get('level') != '' ) {

			$y = DB::Select( ' select year(order_date) as year1 from s_salary_detail group by year(order_date) order by year(order_date) desc ' );
			return View::make( 'report.report_emp_card', array( 'data' => $y ) );

		}else{
			return View::make('login.index');
		}
	}




  /**
    * function name : salary_emp
    * get data รวมเงินเดือน ลูกจ้างชั่วคราว-พกส.(ปฏิบัติงาน) รายคน
    * 
    */
	public function salary_emp_cid()
	{
		if( Session::get('level') != '' )
		{
			$is_m  	= Input::get( 'm1' );
	    	$is_y 	= Input::get( 'y1' );
			$is_cid = Input::get( 'cid1' );

			$pdf = new TCPDF();
		    $pdf->SetPrintHeader(false);
		    $pdf->SetPrintFooter(false);			 		 				

			$pdf->SetDisplayMode('fullpage', 'SinglePage', 'UseNone');

			$n = DB::select( 'select * from s_general_data' );	
		    foreach ($n as $k) {
		      	$name = $k->name;
		    } 

			//$is_m = 03;
			//$is_y = 2017;
					   
		    $sql  = ' select concat(n.pname,"",n.fname," ",n.lname) as name,salary, salary_other ';
		    $sql .= ' , salary_sso, salary_cpk ';
		    $sql .= ' , save, shop, rice ';
		    $sql .= ' , water, elec, other ';
		    $sql .= ' from n_datageneral n ';
			$sql .= ' inner join  s_salary_detail s on s.cid=n.cid';
			$sql .= ' where month(s.order_date)='.$is_m.' and year(s.order_date)='.$is_y.' ';
			$sql .= ' and n.cid in ('.$is_cid.') ';
		    $sql .= ' order by n.q_pts asc';

		    $result = DB::select( $sql );

		    $i=0;
		    $n=0;		
			foreach ( $result as $key ) {
				$i++;
				$n++;
				if( $i == 5 ){
					$i=0;
					$i++;
				}

				if( $i == 1 ){
					$pdf->AddPage('L', 'letter');
				}

				$linestyle03 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '5,10,5,10', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(0.4, 210, 0.4,0, $linestyle03);

				$linestyle00 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '5,10,5,10', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(68, 210, 68,0, $linestyle00);

				$linestyle01 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '5,10,5,10', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(136, 210, 136,0, $linestyle01);

				$linestyle02 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '5,10,5,10', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(204, 210, 204,0, $linestyle02);

				$linestyle04 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '5,10,5,10', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(272, 210, 272,0, $linestyle04);

				if( $i == 1 ){
					$pdf->SetY(9);
	 				$pdf->SetX(1);
				}
				if( $i == 2 ){
					$pdf->SetY(9);
	 				$pdf->SetX(69);
				}
				if( $i == 3 ){
					$pdf->SetY(9);
	 				$pdf->SetX(137);
				}
				if( $i == 4 ){
					$pdf->SetY(9);
	 				$pdf->SetX(205);
				}
				$pdf->SetFont('freeserif','',14,'',true);
				$pdf->MultiCell(66, 5, $name, 0, 'C', 0, 1, '', '', true);

				if( $i == 1 ){
					$pdf->SetY(15);
	 				$pdf->SetX(1);
				}
				if( $i == 2 ){
					$pdf->SetY(15);
	 				$pdf->SetX(69);
				}
				if( $i == 3 ){
					$pdf->SetY(15);
	 				$pdf->SetX(137);
				}
				if( $i == 4 ){
					$pdf->SetY(15);
	 				$pdf->SetX(205);
				}
			    $pdf->MultiCell(66, 5, 'ประจำเดือน '. $this->get_monthyearThai( $is_m, $is_y ), 0, 'L', 0, 1, '', '', true);

			    if( $i == 1 ){
					$pdf->SetY(18);
	 				$pdf->SetX(1);
				}
			    if( $i == 2 ){
					$pdf->SetY(18);
	 				$pdf->SetX(69);
				}
				if( $i == 3 ){
					$pdf->SetY(18);
	 				$pdf->SetX(137);
				}
				if( $i == 4 ){
					$pdf->SetY(18);
	 				$pdf->SetX(205);
				}
			    $pdf->MultiCell(70, 5, '__________________________', 0, 'L', 0, 1, '', '', true); 		  

			    $pdf->SetFont('freeserif','',12,'',true);

			    if( $i == 1 ){
					$pdf->SetY(25);
	 				$pdf->SetX(1);
				}
			    if( $i == 2 ){
					$pdf->SetY(25);
	 				$pdf->SetX(69);
				}
				if( $i == 3 ){
					$pdf->SetY(25);
	 				$pdf->SetX(137);
				}
				if( $i == 4 ){
					$pdf->SetY(25);
	 				$pdf->SetX(205);
				}
			    $pdf->MultiCell(69, 5, '('.$n.')'.' ชื่อ  '. $key->name, 0, 'L', 0, 1, '', '', true);
			    		    
			    $pdf->Ln(2);
			    if( $i == 1 ){
					$pdf->SetY(35);
	 				$pdf->SetX(1);
				}
			    if( $i == 2 ){
					$pdf->SetY(35);
	 				$pdf->SetX(69);
				}
				if( $i == 3 ){
					$pdf->SetY(35);
	 				$pdf->SetX(137);
				}
				if( $i == 4 ){
					$pdf->SetY(35);
	 				$pdf->SetX(205);
				}
			    $pdf->MultiCell(40, 5, 'เงินเดือนรวม', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(26, 5, number_format( $key->salary, 2 ), 0, 'R', 0, 1, '', '', true);
				
				if( $i == 1 ){
					$pdf->SetY(42);
	 				$pdf->SetX(1);
				}
				if( $i == 2 ){
					$pdf->SetY(42);
	 				$pdf->SetX(69);
				}
				if( $i == 3 ){
					$pdf->SetY(42);
	 				$pdf->SetX(137);
				}
				if( $i == 4 ){
					$pdf->SetY(42);
	 				$pdf->SetX(205);
				}
				$pdf->MultiCell(40, 5, 'รับอื่น ๆ รวม', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(26, 5, number_format( $key->salary_other, 2 ), 0, 'R', 0, 1, '', '', true);
				
				if( $i == 1 ){
					$pdf->SetY(49);
	 				$pdf->SetX(1);
				}
				if( $i == 2 ){
					$pdf->SetY(49);
	 				$pdf->SetX(69);
				}
				if( $i == 3 ){
					$pdf->SetY(49);
	 				$pdf->SetX(137);
				}
				if( $i == 4 ){
					$pdf->SetY(49);
	 				$pdf->SetX(205);
				}
			   	$pdf->MultiCell(40, 5, 'ประกันสังคม', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(26, 5, number_format( $key->salary_sso, 2 ), 0, 'R', 0, 1, '', '', true);
				
				if( $i == 1 ){
					$pdf->SetY(56);
	 				$pdf->SetX(1);
				}
				if( $i == 2 ){
					$pdf->SetY(56);
	 				$pdf->SetX(69);
				}
				if( $i == 3 ){
					$pdf->SetY(56);
	 				$pdf->SetX(137);
				}
				if( $i == 4 ){
					$pdf->SetY(56);
	 				$pdf->SetX(205);
				}
				$pdf->MultiCell(40, 5, 'ฌกส', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(26, 5, number_format( $key->salary_cpk, 2 ), 0, 'R', 0, 1, '', '', true);			

				if( $i == 1 ){
					$pdf->SetY(63);
	 				$pdf->SetX(1);
				}
				if( $i == 2 ){
					$pdf->SetY(63);
	 				$pdf->SetX(69);
				}
				if( $i == 3 ){
					$pdf->SetY(63);
	 				$pdf->SetX(137);
				}
				if( $i == 4 ){
					$pdf->SetY(63);
	 				$pdf->SetX(205);
				}
				$pdf->MultiCell(40, 5, 'ค่าธรรมเนียมธนาคาร', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(26, 5, number_format( $key->save, 2 ), 0, 'R', 0, 1, '', '', true);
				
				if( $i == 1 ){
					$pdf->SetY(70);
	 				$pdf->SetX(1);
				}
				if( $i == 2 ){
					$pdf->SetY(70);
	 				$pdf->SetX(69);
				}
				if( $i == 3 ){
					$pdf->SetY(70);
	 				$pdf->SetX(137);
				}
				if( $i == 4 ){
					$pdf->SetY(70);
	 				$pdf->SetX(205);
				}
				$pdf->MultiCell(40, 5, 'สหกรณ์ร้านค้า', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(26, 5, number_format( $key->shop, 2 ), 0, 'R', 0, 1, '', '', true);			

				if( $i == 1 ){
					$pdf->SetY(77);
	 				$pdf->SetX(1);
				}
				if( $i == 2 ){
					$pdf->SetY(77);
	 				$pdf->SetX(69);
				}
				if( $i == 3 ){
					$pdf->SetY(77);
	 				$pdf->SetX(137);
				}
				if( $i == 4 ){
					$pdf->SetY(77);
	 				$pdf->SetX(205);
				}
				$pdf->MultiCell(40, 5, 'ลากิจ', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(26, 5, number_format( $key->rice, 2 ), 0, 'R', 0, 1, '', '', true);	

				if( $i == 1 ){
					$pdf->SetY(84);
	 				$pdf->SetX(1);
				}
				if( $i == 2 ){
					$pdf->SetY(84);
	 				$pdf->SetX(69);
				}
				if( $i == 3 ){
					$pdf->SetY(84);
	 				$pdf->SetX(137);
				}
				if( $i == 4 ){
					$pdf->SetY(84);
	 				$pdf->SetX(205);
				}
				$pdf->MultiCell(40, 5, 'ค่าน้ำประปา', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(26, 5, number_format( $key->water, 2 ), 0, 'R', 0, 1, '', '', true);			

				if( $i == 1 ){
					$pdf->SetY(91);
	 				$pdf->SetX(1);
				}
				if( $i == 2 ){
					$pdf->SetY(91);
	 				$pdf->SetX(69);
				}
				if( $i == 3 ){
					$pdf->SetY(91);
	 				$pdf->SetX(137);
				}
				if( $i == 4 ){
					$pdf->SetY(91);
	 				$pdf->SetX(205);
				}
				$pdf->MultiCell(40, 5, 'ค่าไฟฟ้า', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(26, 5, number_format( $key->elec, 2 ), 0, 'R', 0, 1, '', '', true);			

				if( $i == 1 ){
					$pdf->SetY(98);
	 				$pdf->SetX(1);
				}
				if( $i == 2 ){
					$pdf->SetY(98);
	 				$pdf->SetX(69);
				}
				if( $i == 3 ){
					$pdf->SetY(98);
	 				$pdf->SetX(137);
				}
				if( $i == 4 ){
					$pdf->SetY(98);
	 				$pdf->SetX(205);
				}
				$pdf->MultiCell(40, 5, 'ค่าใช้จ่ายอื่น ๆ', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(26, 5, number_format( $key->other, 2 ), 0, 'R', 0, 1, '', '', true);				

				if( $i == 1 ){
					$pdf->SetY(105);
	 				$pdf->SetX(1);
				}
				if( $i == 2 ){
					$pdf->SetY(105);
	 				$pdf->SetX(69);
				}
				if( $i == 3 ){
					$pdf->SetY(105);
	 				$pdf->SetX(137);
				}
				if( $i == 4 ){
					$pdf->SetY(105);
	 				$pdf->SetX(205);
				}
				$pdf->MultiCell(70, 5, '______________________________', 0, 'L', 0, 1, '', '', true); 

				$pdf->Ln(2);
				if( $i == 1 ){
					$pdf->SetY(112);
	 				$pdf->SetX(1);
				}
				if( $i == 2 ){
					$pdf->SetY(112);
	 				$pdf->SetX(69);
				}
				if( $i == 3 ){
					$pdf->SetY(112);
	 				$pdf->SetX(137);
				}
				if( $i == 4 ){
					$pdf->SetY(112);
	 				$pdf->SetX(205);
				}
				$pdf->MultiCell(40, 5, 'รวมรับ', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(26, 5, number_format( ($key->salary+$key->salary_other), 2 ), 0, 'R', 0, 1, '', '', true);				
						  		   		    		    
			    $pdf->Ln(4);
			    if( $i == 1 ){
					$pdf->SetY(121);
	 				$pdf->SetX(1);
				}
			    if( $i == 2 ){
					$pdf->SetY(121);
	 				$pdf->SetX(69);
				}
				if( $i == 3 ){
					$pdf->SetY(121);
	 				$pdf->SetX(137);
				}
				if( $i == 4 ){
					$pdf->SetY(121);
	 				$pdf->SetX(205);
				}
			    $pdf->MultiCell(40, 5, 'รวมหัก', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(26, 5, number_format( ( $key->salary_sso+$key->salary_cpk+$key->save+$key->shop+$key->rice+$key->water+$key->elec+$key->other ), 2 ) , 0, 'R', 0, 1, '', '', true);										
				
				if( $i == 1 ){
					$linestyle2 = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));			
					$pdf->Line(1, 126, 66, 126, $linestyle2);
				}
				if( $i == 2 ){
					$linestyle2 = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));			
					$pdf->Line(69, 126, 134, 126, $linestyle2);
				}
				if( $i == 3 ){
					$linestyle2 = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));			
					$pdf->Line(137, 126, 202, 126, $linestyle2);
				}	
				if( $i == 4 ){
					$linestyle2 = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));			
					$pdf->Line(205, 126, 270, 126, $linestyle2);
				}			
			   	     
				$pdf->Ln(4);
				if( $i == 1 ){
					$pdf->SetY(130);
	 				$pdf->SetX(1);
				}
				if( $i == 2 ){
					$pdf->SetY(130);
	 				$pdf->SetX(69);
				}
				if( $i == 3 ){
					$pdf->SetY(130);
	 				$pdf->SetX(137);
				}
				if( $i == 4 ){
					$pdf->SetY(130);
	 				$pdf->SetX(205);
				}
			    $pdf->MultiCell(40, 1, 'รับจริง', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(26, 1, number_format( ($key->salary+$key->salary_other)-($key->salary_sso+$key->salary_cpk+$key->save+$key->shop+$key->rice+$key->water+$key->elec+$key->other), 2 ), 0, 'R', 0, 1, '', '', true);
				
				if( $i == 1 ){
					$linestyle3 = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
					$pdf->Line(1, 135, 66, 135, $linestyle3);
				}
				if( $i == 2 ){
					$linestyle3 = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));			
					$pdf->Line(69, 135, 134, 135, $linestyle3);
				}
				if( $i == 3 ){
					$linestyle3 = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));			
					$pdf->Line(137, 135, 202, 135, $linestyle3);
				}
				if( $i == 4 ){
					$linestyle3 = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));			
					$pdf->Line(205, 135, 270, 135, $linestyle3);
				}
				

				$pdf->Ln(4);
				if( $i == 1 ){
					$pdf->SetY(140);
	 				$pdf->SetX(1);
				}
				if( $i == 2 ){
					$pdf->SetY(140);
	 				$pdf->SetX(69);
				}
				if( $i == 3 ){
					$pdf->SetY(140);
	 				$pdf->SetX(137);
				}
				if( $i == 4 ){
					$pdf->SetY(140);
	 				$pdf->SetX(205);
				}
				$pdf->MultiCell(33, 5, 'ผู้รับเงิน', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(26, 5, 'ผู้จ่ายเงิน', 0, 'L', 0, 1, '', '', true);
				
		    }

		    $filename = storage_path() . '/report_salary_emp1_cid.pdf';
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
	 * 
	 * รายงานรับเงินเดือน พกส ชั่วคราว
	 */
	public function salary_receivef1()
	{
		if( Session::get('level') != '' )
		{
			$y = DB::Select( ' select year(order_date) as year1 from s_salary_detail group by year(order_date) order by year(order_date) desc ' );	
			return View::make( 'report.report2', array( 'data' => $y ) );
		}
		else
		{
			return View::make('login.index');
		}  
	}
	/**
    * function name : salary_receive
    * get data รายงานรับเงินเดือน พกส ชั่วคราว
    * 
    */
	public function salary_receive()
	{
		if( Session::get('level') != '' )
		{
			$m  	= Input::get( 'm1' );
	    	$y 		= Input::get( 'y1' );

			$n = DB::select( 'select * from s_general_data' );	
		    foreach ($n as $k) {
		      	$name = $k->name;
		      	$address = $k->address;
		      	$tax_id = $k->tax_id;
		    } 

			$pdf = new TCPDF();
			$pdf->SetPrintHeader(true);
		    $pdf->SetPrintFooter(true);	

		    $pdf->setHeaderFont(array('freeserif','B',13));
			$pdf->setFooterFont(array('freeserif','B',PDF_FONT_SIZE_DATA));

		    $pdf->SetHeaderData('', '', $name.' '.'- รายงานเงินเดือน ลูกจ้างชั่วคราว/พกส.(ปฏิบัติงาน) เดือน '.$this->get_monthyearThai( $m, $y ), ' รหัส                ชื่อ-นามสกุล                                    เงินเดือน          รายรับอื่น ๆ       ประกันสังคม     ฌกส      ธรรมเนียม ธ.    สหกรณ์ออมทรัพย์   ลากิจ   น้ำประปา      ไฟฟ้า        อื่น ๆ         รวมรับ           รวมหัก          รับจริง ');			
			 		   
			// set default monospaced font
			$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
			 
			// set margins
			$pdf->SetMargins(10, PDF_MARGIN_TOP, 10);
			$pdf->SetHeaderMargin(15);
			$pdf->SetFooterMargin(15);

			$pdf->SetFont('freeserif','',11,'',true);

			$pdf->AddPage('L', 'A3');

			$sql  = ' select s.cid, concat(n.pname,"",n.fname," ",n.lname) as name, s.salary';	
			$sql .= ' ,s.salary_other, s.salary_sso, s.salary_cpk, s.save, s.shop ';
			$sql .= ' ,s.rice, s.water, s.elec, s.cprt, s.other';
			$sql .= ' from s_salary_detail s';
			$sql .= ' left join n_datageneral n on n.cid=s.cid';
			$sql .= ' where  month(s.order_date)='.$m.' and year(s.order_date)='.$y;
			$sql .= '  and (';
			$sql .= '  ((select np1.level from n_position_salary np1 where np1.cid=s.cid order by np1.salaryID desc limit 1) = "พกส.(ปฏิบัติงาน)" ) or ';
			$sql .= '  ((select np2.level from n_position_salary np2 where np2.cid=s.cid order by np2.salaryID desc limit 1) = "ลูกจ้างชั่วคราว") ';
			$sql .= '  )';
			$sql .= ' and s.salary > 0  order by n.q_pts asc';
			
			$result = DB::select( $sql );

			$tbl  = ' <style> ';
			$tbl .= '  table.table-report tr td{ border-bottom:1px solid #000; height:30px; line-height: 30px; } ';	
			$tbl .= ' .text-bold { font-weight: bold; } ';		
			$tbl .= ' </style> ';

			$tbl  .= ' <table class="table-report"> ';		    
			 
			$r=0;	
			$salary = 0;
			$salary_other = 0;
			$salary_sso = 0;
			$salary_cpk = 0;
			$save = 0;	
			$cprt = 0; 
			$rice = 0; 
			$water = 0;
			$elec = 0;
			$other = 0;
		    foreach ($result as $key) 		    
		    {	
		       $r++;
		       $salary 			= $salary+$key->salary;
		       $salary_other 	= $salary_other+$key->salary_other;
		       $salary_sso 		= $salary_sso+$key->salary_sso;
		       $salary_cpk 		= $salary_cpk+$key->salary_cpk;
		       $save 			= $save+$key->save;
		       $cprt			= $cprt+$key->cprt;
		       $rice			= $rice+$key->rice;
		       $water			= $water+$key->water;
		       $elec			= $elec+$key->elec;
		       $other   		= $other+$key->other;

		    	$tbl .= ' <tr>';

			    $tbl .= ' <td>';
			    $tbl .= $key->cid;
			    $tbl .= ' </td>';
			    
			    $tbl .= ' <td width="170">';
			    $tbl .= $key->name;
			    $tbl .= ' </td>';

			    $tbl .= ' <td width="59" align="right">';
			    $tbl .= number_format($key->salary, 2);
			    $tbl .= ' </td>';

			    $tbl .= ' <td width="83" align="right">';
			    $tbl .= number_format($key->salary_other, 2);
			    $tbl .= ' </td>';

			    $tbl .= ' <td width="87" align="right">';
			    $tbl .= number_format($key->salary_sso, 2);
			    $tbl .= ' </td>';

			    $tbl .= ' <td  width="53" align="right">';
			    $tbl .= number_format($key->salary_cpk, 2);
			    $tbl .= ' </td>';

			    $tbl .= ' <td width="100" align="right">';
			    $tbl .= number_format($key->save, 2);
			    $tbl .= ' </td>';

			    $tbl .= ' <td width="62" align="right">';
			    $tbl .= number_format($key->cprt, 2);
			    $tbl .= ' </td>';

			    $tbl .= ' <td width="55" align="right">';
			    $tbl .= number_format($key->rice, 2);
			    $tbl .= ' </td>';

			    $tbl .= ' <td width="56" align="right">';
			    $tbl .= number_format($key->water, 2);
			    $tbl .= ' </td>';

			    $tbl .= ' <td width="56" align="right">';
			    $tbl .= number_format($key->elec, 2);
			    $tbl .= ' </td>';

			    $tbl .= ' <td width="54" align="right">';
			    $tbl .= number_format($key->other, 2);
			    $tbl .= ' </td>';

			    $tbl .= ' <td width="75" align="right">';
			    $tbl .= number_format( ($key->salary)+($key->salary_other), 2);
			    $tbl .= ' </td>';

			    $tbl .= ' <td width="78" align="right">';
			    $tbl .= number_format( ($key->salary_sso)+($key->salary_cpk)+($key->save)+($key->cprt)+($key->rice)+($key->water)+($key->elec)+($key->other), 2);
			    $tbl .= ' </td>';

			    $tbl .= ' <td width="79" align="right">';
			    $tbl .= number_format( (($key->salary)+($key->salary_other))-(($key->salary_sso)+($key->salary_cpk)+($key->save)+($key->cprt)+($key->rice)+($key->water)+($key->elec)+($key->other)), 2 );
			    $tbl .= ' </td>';

			    $tbl .= ' </tr>';			    
		   				    	
			
			}
			$tbl .=' <tr class="text-bold">';

			$tbl .=' <td align="right">';
			$tbl .=' รวม';
			$tbl .=' </td>';

			$tbl .=' <td align="center">';
			$tbl .= $r.'   ราย';
			$tbl .=' </td>';

			$tbl .=' <td align="right">';
			$tbl .= number_format( $salary, 2 );
			$tbl .=' </td>';

			$tbl .=' <td align="right">';
			$tbl .= number_format( $salary_other, 2 );
			$tbl .=' </td>';

			$tbl .=' <td align="right">';
			$tbl .= number_format( $salary_sso, 2 );
			$tbl .=' </td>';

			$tbl .=' <td align="right">';
			$tbl .= number_format( $salary_cpk, 2 );
			$tbl .=' </td>';

			$tbl .=' <td align="right">';
			$tbl .= number_format( $save, 2 );
			$tbl .=' </td>';

			$tbl .=' <td align="right">';
			$tbl .= number_format( $cprt, 2 );
			$tbl .=' </td>';

			$tbl .=' <td align="right">';
			$tbl .= number_format( $rice, 2 );
			$tbl .=' </td>';

			$tbl .=' <td align="right">';
			$tbl .= number_format( $water, 2 );
			$tbl .=' </td>';

			$tbl .=' <td align="right">';
			$tbl .= number_format( $elec, 2 );
			$tbl .=' </td>';

			$tbl .=' <td align="right">';
			$tbl .= number_format( $other, 2 );
			$tbl .=' </td>';

			$tbl .=' <td align="right">';
			$tbl .= number_format( ($salary)+($salary_other), 2 );
			$tbl .=' </td>';

			$tbl .=' <td align="right">';
			$tbl .= number_format( ($salary_sso+$salary_cpk+$save+$cprt+$rice+$water+$elec+$other), 2 );
			$tbl .=' </td>';

			$tbl .=' <td align="right">';
			$tbl .= number_format( (($salary)+($salary_other))-(($salary_sso+$salary_cpk+$save+$cprt+$rice+$water+$elec+$other)), 2 );
			$tbl .=' </td>';

			$tbl .=' </tr>';

			$tbl .='</table>';

			//return $tbl;
		   
			$pdf->writeHTML( $tbl, true, false, false, false, '' );

		    $filename = storage_path() . '/report_receive1.pdf';
		    //return Response::download($filename);
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
	 * 
	 * รายงานรับเงินเดือน รายวัน
	 */
	public function salary_receivef1_day()
	{
		if( Session::get('level') != '' )
		{
			$y = DB::Select( ' select year(order_date) as year1 from s_salary_detail group by year(order_date) order by year(order_date) desc ' );	
			return View::make( 'report.report2_day', array( 'data' => $y ) );
		}
		else
		{
			return View::make('login.index');
		}  
	}

	/**
	 * 
	 * get data รายงานรับเงินเดือน รายวัน
	 */
	public function salary_receive_day()
	{
		if( Session::get('level') != '' )
		{
			$m  	= Input::get( 'm1' );
	    	$y 		= Input::get( 'y1' );

			$n = DB::select( 'select * from s_general_data' );	
		    foreach ($n as $k) {
		      	$name = $k->name;
		      	$address = $k->address;
		      	$tax_id = $k->tax_id;
		    } 

			$pdf = new TCPDF();
			$pdf->SetPrintHeader(true);
		    $pdf->SetPrintFooter(true);	

		    $pdf->setHeaderFont(array('freeserif','B',13));
			$pdf->setFooterFont(array('freeserif','B',PDF_FONT_SIZE_DATA));

			$mm=0;
			$yy=0;
			if($m == 1){
				$mm = 12;
				$yy = $y-1;
			}else{
				$mm = $m-1;
				$yy = $y;
			}

		    $pdf->SetHeaderData('', '', $name.' '.'- รายงานเงินเดือน ลูกจ้างรายวัน เดือน '.$this->get_monthyearThai( $mm, $yy ), ' รหัส                ชื่อ-นามสกุล                                    เงินเดือน          รายรับอื่น ๆ       ประกันสังคม     ฌกส      ธรรมเนียม ธ.    สหกรณ์ออมทรัพย์   ลากิจ   น้ำประปา      ไฟฟ้า        อื่น ๆ         รวมรับ           รวมหัก          รับจริง ');			
			 		   
			// set default monospaced font
			$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
			 
			// set margins
			$pdf->SetMargins(10, PDF_MARGIN_TOP, 10);
			$pdf->SetHeaderMargin(15);
			$pdf->SetFooterMargin(15);

			$pdf->SetFont('freeserif','',11,'',true);

			$pdf->AddPage('L', 'A3');

			$sql  = ' select s.cid, concat(n.pname,"",n.fname," ",n.lname) as name, s.salary';	
			$sql .= ' ,s.salary_other, s.salary_sso, s.salary_cpk, s.save, s.shop ';
			$sql .= ' ,s.rice, s.water, s.elec, s.cprt, s.other';
			$sql .= ' from s_salary_detail s';
			$sql .= ' left join n_datageneral n on n.cid=s.cid';
			$sql .= ' where  month(s.order_date)='.$m.' and year(s.order_date)='.$y;
			$sql .= '  and (';
			$sql .= '  ((select np1.level from n_position_salary np1 where np1.cid=s.cid order by np1.salaryID desc limit 1) = "ลูกจ้างรายวัน" ) ';
			$sql .= '  )';
			$sql .= ' and s.salary > 0  order by n.q_pts asc';
			
			$result = DB::select( $sql );

			$tbl  = ' <style> ';
			$tbl .= '  table.table-report tr td{ border-bottom:1px solid #000; height:30px; line-height: 30px; } ';	
			$tbl .= ' .text-bold { font-weight: bold; } ';		
			$tbl .= ' </style> ';

			$tbl  .= ' <table class="table-report"> ';		    
			 
			$r=0;	
			$salary = 0;
			$salary_other = 0;
			$salary_sso = 0;
			$salary_cpk = 0;
			$save = 0;	
			$cprt = 0; 
			$rice = 0; 
			$water = 0;
			$elec = 0;
			$other = 0;
		    foreach ($result as $key) 		    
		    {	
		       $r++;
		       $salary 			= $salary+$key->salary;
		       $salary_other 	= $salary_other+$key->salary_other;
		       $salary_sso 		= $salary_sso+$key->salary_sso;
		       $salary_cpk 		= $salary_cpk+$key->salary_cpk;
		       $save 			= $save+$key->save;
		       $cprt			= $cprt+$key->cprt;
		       $rice			= $rice+$key->rice;
		       $water			= $water+$key->water;
		       $elec			= $elec+$key->elec;
		       $other   		= $other+$key->other;

		    	$tbl .= ' <tr>';

			    $tbl .= ' <td>';
			    $tbl .= $key->cid;
			    $tbl .= ' </td>';
			    
			    $tbl .= ' <td width="170">';
			    $tbl .= $key->name;
			    $tbl .= ' </td>';

			    $tbl .= ' <td width="59" align="right">';
			    $tbl .= number_format($key->salary, 2);
			    $tbl .= ' </td>';

			    $tbl .= ' <td width="83" align="right">';
			    $tbl .= number_format($key->salary_other, 2);
			    $tbl .= ' </td>';

			    $tbl .= ' <td width="87" align="right">';
			    $tbl .= number_format($key->salary_sso, 2);
			    $tbl .= ' </td>';

			    $tbl .= ' <td  width="53" align="right">';
			    $tbl .= number_format($key->salary_cpk, 2);
			    $tbl .= ' </td>';

			    $tbl .= ' <td width="100" align="right">';
			    $tbl .= number_format($key->save, 2);
			    $tbl .= ' </td>';

			    $tbl .= ' <td width="62" align="right">';
			    $tbl .= number_format($key->cprt, 2);
			    $tbl .= ' </td>';

			    $tbl .= ' <td width="55" align="right">';
			    $tbl .= number_format($key->rice, 2);
			    $tbl .= ' </td>';

			    $tbl .= ' <td width="56" align="right">';
			    $tbl .= number_format($key->water, 2);
			    $tbl .= ' </td>';

			    $tbl .= ' <td width="56" align="right">';
			    $tbl .= number_format($key->elec, 2);
			    $tbl .= ' </td>';

			    $tbl .= ' <td width="54" align="right">';
			    $tbl .= number_format($key->other, 2);
			    $tbl .= ' </td>';

			    $tbl .= ' <td width="75" align="right">';
			    $tbl .= number_format( ($key->salary)+($key->salary_other), 2);
			    $tbl .= ' </td>';

			    $tbl .= ' <td width="78" align="right">';
			    $tbl .= number_format( ($key->salary_sso)+($key->salary_cpk)+($key->save)+($key->cprt)+($key->rice)+($key->water)+($key->elec)+($key->other), 2);
			    $tbl .= ' </td>';

			    $tbl .= ' <td width="79" align="right">';
			    $tbl .= number_format( (($key->salary)+($key->salary_other))-(($key->salary_sso)+($key->salary_cpk)+($key->save)+($key->cprt)+($key->rice)+($key->water)+($key->elec)+($key->other)), 2 );
			    $tbl .= ' </td>';

			    $tbl .= ' </tr>';			    
		   				    	
			
			}
			$tbl .=' <tr class="text-bold">';

			$tbl .=' <td align="right">';
			$tbl .=' รวม';
			$tbl .=' </td>';

			$tbl .=' <td align="center">';
			$tbl .= $r.'   ราย';
			$tbl .=' </td>';

			$tbl .=' <td align="right">';
			$tbl .= number_format( $salary, 2 );
			$tbl .=' </td>';

			$tbl .=' <td align="right">';
			$tbl .= number_format( $salary_other, 2 );
			$tbl .=' </td>';

			$tbl .=' <td align="right">';
			$tbl .= number_format( $salary_sso, 2 );
			$tbl .=' </td>';

			$tbl .=' <td align="right">';
			$tbl .= number_format( $salary_cpk, 2 );
			$tbl .=' </td>';

			$tbl .=' <td align="right">';
			$tbl .= number_format( $save, 2 );
			$tbl .=' </td>';

			$tbl .=' <td align="right">';
			$tbl .= number_format( $cprt, 2 );
			$tbl .=' </td>';

			$tbl .=' <td align="right">';
			$tbl .= number_format( $rice, 2 );
			$tbl .=' </td>';

			$tbl .=' <td align="right">';
			$tbl .= number_format( $water, 2 );
			$tbl .=' </td>';

			$tbl .=' <td align="right">';
			$tbl .= number_format( $elec, 2 );
			$tbl .=' </td>';

			$tbl .=' <td align="right">';
			$tbl .= number_format( $other, 2 );
			$tbl .=' </td>';

			$tbl .=' <td align="right">';
			$tbl .= number_format( ($salary)+($salary_other), 2 );
			$tbl .=' </td>';

			$tbl .=' <td align="right">';
			$tbl .= number_format( ($salary_sso+$salary_cpk+$save+$cprt+$rice+$water+$elec+$other), 2 );
			$tbl .=' </td>';

			$tbl .=' <td align="right">';
			$tbl .= number_format( (($salary)+($salary_other))-(($salary_sso+$salary_cpk+$save+$cprt+$rice+$water+$elec+$other)), 2 );
			$tbl .=' </td>';

			$tbl .=' </tr>';

			$tbl .='</table>';

			//return $tbl;
		   
			$pdf->writeHTML( $tbl, true, false, false, false, '' );

		    $filename = storage_path() . '/report_receive1_day.pdf';
		    //return Response::download($filename);
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
	 * [salary_excel_home] พกส ชั่วคราว
	 * 
	 */
	public function salary_excel_home()
	{		
		if( Session::get('level') != '' )
		{
			$y = DB::Select( ' select year(order_date) as year1 from s_salary_detail group by year(order_date) order by year(order_date) desc ' );	
			return View::make( 'report.salary_excel_home', array( 'data' => $y ) );
		}
		else
		{
			return View::make('login.index');
		}  
	}
	/**
	*	function name salary_excel
	*	ส่งออกข้อมูล excel ธกส  พกส ชั่วคราว
	*/
	public function salary_excel()
	{			
		$m  	= Input::get( 'm40' );
	    $y 		= Input::get( 'y40' );

		$sql  = ' select s.cid, s.bank_acc, concat(n.pname,"",n.fname," ",n.lname) as name, s.salary';	
		$sql .= ' ,s.salary_other, s.salary_sso, s.salary_cpk, s.save, s.shop ';
		$sql .= ' ,s.rice, s.water, s.elec, s.cprt, s.other';
		$sql .= ' from s_salary_detail s';
		$sql .= ' left join n_datageneral n on n.cid=s.cid';
		$sql .= ' where  month(s.order_date)='.$m.' and year(s.order_date)='.$y;
		$sql .= '  and (';
		$sql .= '  ((select np1.level from n_position_salary np1 where np1.cid=s.cid order by np1.salaryID desc limit 1) = "พกส.(ปฏิบัติงาน)" ) or ';
		$sql .= '  ((select np2.level from n_position_salary np2 where np2.cid=s.cid order by np2.salaryID desc limit 1) = "ลูกจ้างชั่วคราว") ';
		$sql .= '  )';
		$sql .= ' and s.salary > 0 order by n.q_pts asc';

		$result = DB::select( $sql );					

		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial'); 
	    $objPHPExcel->getActiveSheet()->setTitle('Sheet1');
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'เลขที่บัญชี');	
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(18);		
		$objPHPExcel->getActiveSheet()->setCellValue('B1', 'ชื่อ-นามสกุล');
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(50);	
		$objPHPExcel->getActiveSheet()->setCellValue('C1', 'รับจริง');	
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(18);	
		$objPHPExcel->getActiveSheet()->getStyle('C')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1); 		

		$row = 0;
		$sumB = 0;
		foreach ($result as $key) {			
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (0, $row+2,  $key->bank_acc);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (1, $row+2, $key->name);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (2, $row+2, ( (($key->salary)+($key->salary_other))-(($key->salary_sso)+($key->salary_cpk)+($key->save)+($key->shop)+($key->rice)+($key->water)+($key->elec)+($key->cprt)+($key->other)) ) );			
			$row++;
			$sumB = $sumB + ( (($key->salary)+($key->salary_other))-(($key->salary_sso)+($key->salary_cpk)+($key->save)+($key->shop)+($key->rice)+($key->water)+($key->elec)+($key->cprt)+($key->other)) );
		}			

		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (1, $row+4, 'รวมเงินเดือน รับจริง');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (2, $row+4, $sumB );

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5'); // Set excel version 2007	  		
	    $objWriter->save(storage_path()."/excel/reportListName.xls");

	    return Response::download( storage_path()."/excel/reportListName.xls", "reportListName.xls");		    
	}




    /**
	 * 
	 * excel ธกส  รายวัน
	 */
	public function salary_excel_home_day()
	{
        if( Session::get('level') != '' )
		{
			$y = DB::Select( ' select year(order_date) as year1 from s_salary_detail group by year(order_date) order by year(order_date) desc ' );	
			return View::make( 'report.salary_excel_home_day', array( 'data' => $y ) );
		}
		else
		{
			return View::make('login.index');
		}  
	}
	/**
	 * 
	 * getdata excel ธกส  รายวัน
	 */
	public function salary_excel_day()
	{
		$m  	= Input::get( 'm40' );
		$y 		= Input::get( 'y40' );
		
		$mm=0;
		$yy=0;
		if($m == 01){
			$mm = 12;
			$yy = $y-1;
		}else{
			$mm = $m-1;
			$yy = $y;
		}

		$sql  = ' select s.cid, s.bank_acc, concat(n.pname,"",n.fname," ",n.lname) as name, s.salary';	
		$sql .= ' ,s.salary_other, s.salary_sso, s.salary_cpk, s.save, s.shop ';
		$sql .= ' ,s.rice, s.water, s.elec, s.cprt, s.other';
		$sql .= ' from s_salary_detail s';
		$sql .= ' left join n_datageneral n on n.cid=s.cid';
		$sql .= ' where  month(s.order_date)='.$m.' and year(s.order_date)='.$y;
		$sql .= '  and (';
		$sql .= '  ((select np1.level from n_position_salary np1 where np1.cid=s.cid order by np1.salaryID desc limit 1) = "ลูกจ้างรายวัน" ) ';
		$sql .= '  )';
		$sql .= ' and s.salary > 0  order by n.q_pts asc';

		$result = DB::select( $sql );					

		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial'); 
	    $objPHPExcel->getActiveSheet()->setTitle('Sheet1');
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'เลขที่บัญชี');	
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(18);		
		$objPHPExcel->getActiveSheet()->setCellValue('B1', 'ชื่อ-นามสกุล');
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(50);	
		$objPHPExcel->getActiveSheet()->setCellValue('C1', 'รับจริง');	
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(18);	
		$objPHPExcel->getActiveSheet()->getStyle('C')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1); 		

		$row = 0;
		$sumB = 0;
		foreach ($result as $key) {			
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (0, $row+2,  $key->bank_acc);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (1, $row+2, $key->name);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (2, $row+2, ( (($key->salary)+($key->salary_other))-(($key->salary_sso)+($key->salary_cpk)+($key->save)+($key->shop)+($key->rice)+($key->water)+($key->elec)+($key->cprt)+($key->other)) ) );			
			$row++;
			$sumB = $sumB + ( (($key->salary)+($key->salary_other))-(($key->salary_sso)+($key->salary_cpk)+($key->save)+($key->shop)+($key->rice)+($key->water)+($key->elec)+($key->cprt)+($key->other)) );
		}			

		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (1, $row+4, 'รวมเงินเดือน รับจริง');
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (2, $row+4, $sumB );

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5'); // Set excel version 2007	  		
	    $objWriter->save(storage_path()."/excel/reportListNameDay.xls");

	    return Response::download( storage_path()."/excel/reportListNameDay.xls", "reportListNameDay.xls");
	}





	/**
	 *  พกส ชั่วคราว  ส่งพี่ก้อย
	 * 
	 */
	public function salary_excel_2()
	{
		if( Session::get('level') != '' )
		{
			$y = DB::Select( ' select year(order_date) as year1 from s_salary_detail group by year(order_date) order by year(order_date) desc ' );	
			return View::make( 'report.report1', array( 'data' => $y ) );
		}
		else
		{
			return View::make('login.index');
		}  
	}
	/**
	 *  get data พกส ชั่วคราว  ส่งพี่ก้อย
	 * 
	 */
	public function salary_excel_2file()
	{
		$m  	= Input::get( 'm1' );
	    $y 		= Input::get( 'y1' );	   

	    $sql  = ' select s.cid, s.bank_acc, concat(n.pname,"",n.fname," ",n.lname) as name, sum(s.salary+s.salary_other) as salary';	
		$sql .= ' ,( select pp2.positionName from n_position_salary pp inner join n_position pp2 on pp2.position_id=pp.position_id where pp.cid=n.cid order by pp.location_id desc limit 1  ) as positionName ';	
		$sql .= ' from s_salary_detail s';
		$sql .= ' left join n_datageneral n on n.cid=s.cid';		
		$sql .= ' where  month(s.order_date)='.$m.' and year(s.order_date)='.$y;
		$sql .= ' and s.salary > 0 group by n.cid order by n.q_pts asc';

		$result = DB::select( $sql );			

		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial'); 
	    $objPHPExcel->getActiveSheet()->setTitle('Sheet1');
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'รหัสประชาชน');	
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(18);	
		$objPHPExcel->getActiveSheet()->getStyle('A')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER); 	
		$objPHPExcel->getActiveSheet()->setCellValue('B1', 'ชื่อ-นามสกุล');
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(50);	
		$objPHPExcel->getActiveSheet()->setCellValue('C1', 'ตำแหน่ง');
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(50);	
		$objPHPExcel->getActiveSheet()->setCellValue('D1', 'เงินเดือน');	
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(18);	
		$objPHPExcel->getActiveSheet()->getStyle('D')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1); 
		

		$row = 0;
		$sumB = 0;
		foreach ($result as $key) {			
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (0, $row+2,  $key->cid);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (1, $row+2, $key->name);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (2, $row+2, $key->positionName);	
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (3, $row+2, $key->salary);	
				
			$row++;			
		}	
	
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5'); // Set excel version 2007	  		
	    $objWriter->save(storage_path()."/excel/reportListData.xls");

	    return Response::download( storage_path()."/excel/reportListData.xls", "reportListData.xls");	
	}







	/**
	 * [salary_excel_pdf_home] พกส ชั่วคราว
	 * 
	 */
	public function salary_excel_pdf_home()
	{		
		if( Session::get('level') != '' )
		{
			$y = DB::Select( ' select year(order_date) as year1 from s_salary_detail group by year(order_date) order by year(order_date) desc ' );	
			return View::make( 'report.salary_excel_pdf_home', array( 'data' => $y ) );
		}
		else
		{
			return View::make('login.index');
		}  
	}
	/**
	*	function name salary_excel_pdf
	*	ส่งออกข้อมูล pdf ธกส พกส ชั่วคราว
	*/
	public function salary_excel_pdf()
	{		
		$m  	= Input::get( 'm41' );
	    $y 		= Input::get( 'y41' );	

		if( Session::get('level') != '' )
		{
			$n = DB::select( 'select * from s_general_data' );	
		    foreach ($n as $k) {
		      	$name = $k->name;
		      	$address = $k->address;
		      	$tax_id = $k->tax_id;
		    } 

			$pdf = new TCPDF();
			$pdf->SetPrintHeader(true);
		    $pdf->SetPrintFooter(true);	

		     $pdf->setHeaderFont(array('freeserif','B',11));
			$pdf->setFooterFont(array('freeserif','B',PDF_FONT_SIZE_DATA));

		    $pdf->SetHeaderData('', '', $name.' '.'- ลูกจ้างชั่วคราว/พกส.(ปฏิบัติงาน) เดือน '.$this->get_monthyearThai( $m, $y ), ' เลขที่บัญชี                                 ชื่อ-นามสกุล                                                                                       เงินเดือนรับจริง ');			
			 	   

			// set default monospaced font
			$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
			 
			// set margins
			$pdf->SetMargins(10, PDF_MARGIN_TOP, 10);
			$pdf->SetHeaderMargin(15);
			$pdf->SetFooterMargin(15);

			$pdf->SetFont('freeserif','',12,'',true);

			$pdf->AddPage('P', 'A4');

			$sql  = ' select s.cid, s.bank_acc, concat(n.pname,"",n.fname," ",n.lname) as name, s.salary';	
			$sql .= ' ,s.salary_other, s.salary_sso, s.salary_cpk, s.save, s.shop ';
			$sql .= ' ,s.rice, s.water, s.elec, s.cprt, s.other';
			$sql .= ' from s_salary_detail s';
			$sql .= ' left join n_datageneral n on n.cid=s.cid';
			$sql .= ' where  month(s.order_date)='.$m.' and year(s.order_date)='.$y;
			$sql .= '  and (';
			$sql .= '  ((select np1.level from n_position_salary np1 where np1.cid=s.cid order by np1.salaryID desc limit 1) = "พกส.(ปฏิบัติงาน)" ) or ';
			$sql .= '  ((select np2.level from n_position_salary np2 where np2.cid=s.cid order by np2.salaryID desc limit 1) = "ลูกจ้างชั่วคราว") ';
			$sql .= '  )';
			$sql .= ' and s.salary > 0  order by n.q_pts asc';
			
			$result = DB::select( $sql );

			$tbl  = ' <style> ';
			$tbl .= '  table.table-report tr td{ border-bottom:0px solid #000; height:30px; line-height: 30px; } ';	
			$tbl .= ' .text-bold { font-weight: bold; } ';		
			$tbl .= ' </style> ';

			$tbl  .= ' <table class="table-report"> ';		    
			 
			$r=0;	
			$salary = 0;
			
		    foreach ($result as $key) 		    
		    {	
		       $r++;
		       $salary 	= $salary + ( (($key->salary)+($key->salary_other))-(($key->salary_sso)+($key->salary_cpk)+($key->save)+($key->shop)+($key->rice)+($key->water)+($key->elec)+($key->cprt)+($key->other)) );		      

		    	$tbl .= ' <tr>';

			    $tbl .= ' <td width="120">';
			    $tbl .= $key->bank_acc;
			    $tbl .= ' </td>';
			    
			    $tbl .= ' <td width="170">';
			    $tbl .= $key->name;
			    $tbl .= ' </td>';

			    $tbl .= ' <td width="245" align="right">';
			    $tbl .= ( number_format( (($key->salary)+($key->salary_other))-(($key->salary_sso)+($key->salary_cpk)+($key->save)+($key->shop)+($key->rice)+($key->water)+($key->elec)+($key->cprt)+($key->other)), 2 ) );
			    $tbl .= ' </td>';		   

			    $tbl .= ' </tr>';			    	   				    	
			
			}
			$tbl .=' <tr class="text-bold">';

			$tbl .=' <td align="right">';
			$tbl .='';
			$tbl .=' </td>';
			$tbl .=' <td align="right">';
			$tbl .=' รวม';
			$tbl .=' </td>';
			$tbl .=' <td align="right" width="245">';
			$tbl .=  number_format( $salary, 2 );
			$tbl .=' </td>';			

			$tbl .=' </tr>';

			$tbl .='</table>';

		   
			$pdf->writeHTML( $tbl, true, false, false, false, '' );

		    $filename = storage_path() . '/reportListName.pdf';
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
	 * 
	 * pdf ธกส  รายวัน
	 */
	public function salary_excel_pdf_home_day()
	{
        if( Session::get('level') != '' )
		{
			$y = DB::Select( ' select year(order_date) as year1 from s_salary_detail group by year(order_date) order by year(order_date) desc ' );	
			return View::make( 'report.salary_excel_pdf_home_day', array( 'data' => $y ) );
		}
		else
		{
			return View::make('login.index');
		}  
	}
	/**
	 * 
	 * getdata pdf ธกส  รายวัน
	 */
	public function salary_excel_pdf_day()
	{
		$m  	= Input::get( 'm41' );
	    $y 		= Input::get( 'y41' );	

		if( Session::get('level') != '' )
		{
			$n = DB::select( 'select * from s_general_data' );	
		    foreach ($n as $k) {
		      	$name = $k->name;
		      	$address = $k->address;
		      	$tax_id = $k->tax_id;
			} 
			
			$mm=0;
			$yy=0;
			if($m == 01){
				$mm = 12;
				$yy = $y-1;
			}else{
				$mm = $m-1;
				$yy = $y;
			}


			$pdf = new TCPDF();
			$pdf->SetPrintHeader(true);
		    $pdf->SetPrintFooter(true);	

		    $pdf->setHeaderFont(array('freeserif','B',11));
			$pdf->setFooterFont(array('freeserif','B',PDF_FONT_SIZE_DATA));

		    $pdf->SetHeaderData('', '', $name.' '.'- ลูกจ้างรายวัน เดือน '.$this->get_monthyearThai( $mm, $yy ), ' เลขที่บัญชี                                 ชื่อ-นามสกุล                                                                                       เงินเดือนรับจริง ');			
			 	   

			// set default monospaced font
			$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
			 
			// set margins
			$pdf->SetMargins(10, PDF_MARGIN_TOP, 10);
			$pdf->SetHeaderMargin(15);
			$pdf->SetFooterMargin(15);

			$pdf->SetFont('freeserif','',12,'',true);

			$pdf->AddPage('P', 'A4');

			$sql  = ' select s.cid, s.bank_acc, concat(n.pname,"",n.fname," ",n.lname) as name, s.salary';	
			$sql .= ' ,s.salary_other, s.salary_sso, s.salary_cpk, s.save, s.shop ';
			$sql .= ' ,s.rice, s.water, s.elec, s.cprt, s.other';
			$sql .= ' from s_salary_detail s';
			$sql .= ' left join n_datageneral n on n.cid=s.cid';
			$sql .= ' where  month(s.order_date)='.$m.' and year(s.order_date)='.$y;
			$sql .= '  and (';
			$sql .= '  ((select np1.level from n_position_salary np1 where np1.cid=s.cid order by np1.salaryID desc limit 1) = "ลูกจ้างรายวัน" ) ';
			$sql .= '  )';
			$sql .= ' and s.salary > 0  order by n.q_pts asc';
			
			$result = DB::select( $sql );

			$tbl  = ' <style> ';
			$tbl .= '  table.table-report tr td{ border-bottom:0px solid #000; height:30px; line-height: 30px; } ';	
			$tbl .= ' .text-bold { font-weight: bold; } ';		
			$tbl .= ' </style> ';

			$tbl  .= ' <table class="table-report"> ';		    
			 
			$r=0;	
			$salary = 0;
			
		    foreach ($result as $key) 		    
		    {	
		       $r++;
		       $salary 	= $salary + ( (($key->salary)+($key->salary_other))-(($key->salary_sso)+($key->salary_cpk)+($key->save)+($key->shop)+($key->rice)+($key->water)+($key->elec)+($key->cprt)+($key->other)) );		      

		    	$tbl .= ' <tr>';

			    $tbl .= ' <td width="120">';
			    $tbl .= $key->bank_acc;
			    $tbl .= ' </td>';
			    
			    $tbl .= ' <td width="170">';
			    $tbl .= $key->name;
			    $tbl .= ' </td>';

			    $tbl .= ' <td width="245" align="right">';
			    $tbl .= ( number_format( (($key->salary)+($key->salary_other))-(($key->salary_sso)+($key->salary_cpk)+($key->save)+($key->shop)+($key->rice)+($key->water)+($key->elec)+($key->cprt)+($key->other)), 2 ) );
			    $tbl .= ' </td>';		   

			    $tbl .= ' </tr>';			    	   				    	
			
			}
			$tbl .=' <tr class="text-bold">';

			$tbl .=' <td align="right">';
			$tbl .='';
			$tbl .=' </td>';
			$tbl .=' <td align="right">';
			$tbl .=' รวม';
			$tbl .=' </td>';
			$tbl .=' <td align="right" width="245">';
			$tbl .=  number_format( $salary, 2 );
			$tbl .=' </td>';			

			$tbl .=' </tr>';

			$tbl .='</table>';

		   
			$pdf->writeHTML( $tbl, true, false, false, false, '' );

		    $filename = storage_path() . '/reportListNameDay.pdf';
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





	//============================================ 2 ========================================//


	/**
    * function name : salary_ocsc_all
    * get data รวมเงินเดือน ข้าราชการ-ลูกจ้างประจำ
    * 
    */
	public function salary_ocsc_all()
	{
		if( Session::get('level') != '' )
		{
			$pdf = new TCPDF();
		    $pdf->SetPrintHeader(false);
		    //$pdf->SetPrintFooter(false);	
			 
			// set header and footer fonts
			$pdf->setHeaderFont(array('freeserif','B',PDF_FONT_SIZE_MAIN));
			$pdf->setFooterFont(array('freeserif','B',PDF_FONT_SIZE_DATA));
			 
			// set default monospaced font
			$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
			 
			// set margins
			$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
			$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
			$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
	 
			$pdf->SetFont('freeserif','',14,'',true);

		    $pdf->AddPage();	
		    
		    $n = DB::select( 'select * from s_general_data' );	
		    foreach ($n as $k) {
		      	$name = $k->name;
		    }  

		    $pdf->MultiCell(155, 5, 'รายงานสรุปเงินเดือน ลูกจ้างประจำ/ข้าราชการ '.$name, 0, 'L', 0, 1, '', '', true);
		    $pdf->MultiCell(115, 5, 'ประจำเดือน '. $this->get_monthyearThai( $this->max_month(), $this->max_year() ), 0, 'C', 0, 1, '', '', true);
		    $pdf->MultiCell(155, 5, '_______________________________________________', 0, 'L', 0, 1, '', '', true);

		    $sql  = ' select sum(s.salary) as salary, sum(s.r_c) as r_c, sum(s.special) as special, sum(s.son) as son';
		    $sql .= ' , sum(s.r_other) as r_other, sum(s.kbk) as kbk, sum(s.tax) as tax, sum(s.cas)as cas';
		    $sql .= ' , sum(s.save_p) as save_p, sum(s.houseLoan) as houseLoan,';
		    $sql .= ' sum(s.save_h) as save_h, sum(s.p_other) as p_other';
		    $sql .= ' , sum(s.shop) as shop, sum(s.rice) as rice, sum(s.water) as water,';
		    $sql .= ' sum(s.elec) as elec, sum(s.pt) as pt, sum(s.bank_o) as bank_o, sum(s.fund_p) as fund_p';
		    $sql .= ' from n_datageneral n inner join  s_salary_ocsc_detail s on s.cid=n.cid';
		    $sql .= ' where  month(s.order_date)='.$this->max_month().' and year(s.order_date)='.$this->max_year() .' order by n.q_pts asc';
		    $result = DB::select( $sql );		
			foreach ( $result as $key ) {
				$pdf->SetFont('freeserif','',12,'',true);
			    $pdf->MultiCell(57, 5, 'เงินเดือน', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(50, 5, number_format( $key->salary, 2 ), 0, 'R', 0, 0, '', '', true);
				$pdf->MultiCell(10, 5, 'บาท', 0, 'R', 0, 1, '', '', true);

				$pdf->MultiCell(57, 5, 'เงินประจำตำแหน่ง', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(50, 5, number_format( $key->r_c, 2 ), 0, 'R', 0, 0, '', '', true);
				$pdf->MultiCell(10, 5, 'บาท', 0, 'R', 0, 1, '', '', true);

				$pdf->MultiCell(57, 5, 'เงินค่าตอบแทนพิเศษ', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(50, 5, number_format( $key->special, 2 ), 0, 'R', 0, 0, '', '', true);
				$pdf->MultiCell(10, 5, 'บาท', 0, 'R', 0, 1, '', '', true);

				$pdf->MultiCell(57, 5, 'เงินช่วยเหลือบุตร', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(50, 5, number_format( $key->son, 2 ), 0, 'R', 0, 0, '', '', true);
				$pdf->MultiCell(10, 5, 'บาท', 0, 'R', 0, 1, '', '', true);

				$pdf->MultiCell(57, 5, 'รับอื่น ๆ', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(50, 5, number_format( $key->r_other, 2 ), 0, 'R', 0, 0, '', '', true);
				$pdf->MultiCell(10, 5, 'บาท', 0, 'R', 0, 1, '', '', true);

				$pdf->Ln(2);
				$pdf->MultiCell(57, 5, 'รวมรับ', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(50, 5, number_format( $key->salary + $key->r_c + $key->special + $key->son + $key->r_other, 2 ), 0, 'R', 0, 0, '', '', true);
				$pdf->MultiCell(10, 5, 'บาท', 0, 'R', 0, 1, '', '', true);
				$linestyle1 = array('width' => 0.7, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(15, 79, 132, 79, $linestyle1);

				$pdf->Ln(2);
				$pdf->MultiCell(57, 5, 'กบข/กสจ', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(50, 5, number_format( $key->kbk, 2 ), 0, 'R', 0, 0, '', '', true);
				$pdf->MultiCell(10, 5, 'บาท', 0, 'R', 0, 1, '', '', true);

				$pdf->MultiCell(57, 5, 'ภาษี', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(50, 5, number_format( $key->tax, 2 ), 0, 'R', 0, 0, '', '', true);
				$pdf->MultiCell(10, 5, 'บาท', 0, 'R', 0, 1, '', '', true);

				$pdf->MultiCell(57, 5, 'ฌกส/ค่าบ้าน', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(50, 5, number_format( $key->cas, 2 ), 0, 'R', 0, 0, '', '', true);
				$pdf->MultiCell(10, 5, 'บาท', 0, 'R', 0, 1, '', '', true);

				$pdf->MultiCell(57, 5, 'ออมทรัพย์จังหวัด', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(50, 5, number_format( $key->save_p, 2 ), 0, 'R', 0, 0, '', '', true);
				$pdf->MultiCell(10, 5, 'บาท', 0, 'R', 0, 1, '', '', true);

				$pdf->MultiCell(57, 5, 'ธนาคารอาคารสงเคราะห์', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(50, 5, number_format( $key->houseLoan, 2 ), 0, 'R', 0, 0, '', '', true);
				$pdf->MultiCell(10, 5, 'บาท', 0, 'R', 0, 1, '', '', true);

				$pdf->Ln(2);
				$pdf->MultiCell(57, 5, 'หักจากจว.', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(50, 5, number_format( $key->kbk+$key->tax+$key->cas+$key->save_p+$key->houseLoan, 2 ), 0, 'R', 0, 0, '', '', true);
				$pdf->MultiCell(10, 5, 'บาท', 0, 'R', 0, 1, '', '', true);
				$linestyle2 = array('width' => 0.7, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(15, 115, 132, 115, $linestyle2);

				$pdf->Ln(3);
				$pdf->MultiCell(57, 5, 'เหลือมารพ.', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(50, 5, number_format( ($key->salary + $key->r_c + $key->special + $key->son + $key->r_other)-($key->kbk+$key->tax+$key->cas+$key->save_p+$key->houseLoan) , 2 ), 0, 'R', 0, 0, '', '', true);
				$pdf->MultiCell(10, 5, 'บาท', 0, 'R', 0, 1, '', '', true);
				$linestyle2 = array('width' => 0.7, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(15, 123, 132, 123, $linestyle2);

				$pdf->Ln(2);
				$pdf->MultiCell(57, 5, 'ค่าใช้จ่ายอื่น ๆ 1', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(50, 5, number_format( $key->save_h, 2 ), 0, 'R', 0, 0, '', '', true);
				$pdf->MultiCell(10, 5, 'บาท', 0, 'R', 0, 1, '', '', true);

				$pdf->MultiCell(57, 5, 'ค่าใช้จ่ายอื่น ๆ 2', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(50, 5, number_format( $key->p_other, 2 ), 0, 'R', 0, 0, '', '', true);
				$pdf->MultiCell(10, 5, 'บาท', 0, 'R', 0, 1, '', '', true);

				$pdf->MultiCell(57, 5, 'สหกรณ์ร้านค้า', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(50, 5, number_format( $key->shop, 2 ), 0, 'R', 0, 0, '', '', true);
				$pdf->MultiCell(10, 5, 'บาท', 0, 'R', 0, 1, '', '', true);

				$pdf->MultiCell(57, 5, 'ค่าข้าว', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(50, 5, number_format( $key->rice, 2 ), 0, 'R', 0, 0, '', '', true);
				$pdf->MultiCell(10, 5, 'บาท', 0, 'R', 0, 1, '', '', true);

				$pdf->MultiCell(57, 5, 'ค่าน้ำประปา', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(50, 5, number_format( $key->water, 2 ), 0, 'R', 0, 0, '', '', true);
				$pdf->MultiCell(10, 5, 'บาท', 0, 'R', 0, 1, '', '', true);

				$pdf->MultiCell(57, 5, 'ค่าไฟฟ้า', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(50, 5, number_format( $key->elec, 2 ), 0, 'R', 0, 0, '', '', true);
				$pdf->MultiCell(10, 5, 'บาท', 0, 'R', 0, 1, '', '', true);

				$pdf->MultiCell(57, 5, 'ค่ารักษา', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(50, 5, number_format( $key->pt, 2 ), 0, 'R', 0, 0, '', '', true);
				$pdf->MultiCell(10, 5, 'บาท', 0, 'R', 0, 1, '', '', true);

				$pdf->MultiCell(57, 5, 'ออมสิน', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(50, 5, number_format( $key->bank_o, 2 ), 0, 'R', 0, 0, '', '', true);
				$pdf->MultiCell(10, 5, 'บาท', 0, 'R', 0, 1, '', '', true);

				$pdf->Ln(2);
				$pdf->MultiCell(57, 5, 'หักที่รพ.', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(50, 5, number_format( $key->save_h+$key->p_other+$key->shop+$key->rice+$key->water+$key->elec+$key->pt+$key->bank_o, 2 ), 0, 'R', 0, 0, '', '', true);
				$pdf->MultiCell(10, 5, 'บาท', 0, 'R', 0, 1, '', '', true);
				$linestyle4 = array('width' => 0.7, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(15, 175, 132, 175, $linestyle4);

				$pdf->Ln(3);
				$pdf->MultiCell(57, 5, 'รับจริง', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(50, 5, number_format( (($key->salary + $key->r_c + $key->special + $key->son + $key->r_other)-($key->kbk+$key->tax+$key->cas+$key->save_p+$key->houseLoan))-($key->save_h+$key->p_other+$key->shop+$key->rice+$key->water+$key->elec+$key->pt+$key->bank_o) , 2 ), 0, 'R', 0, 0, '', '', true);
				$pdf->MultiCell(10, 5, 'บาท', 0, 'R', 0, 1, '', '', true);
				$linestyle4 = array('width' => 0.7, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(15, 183, 132, 183, $linestyle4);
			}

		    $filename = storage_path() . '/report_salaryAll2.pdf';
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
    * function name : salary_ocsc_all
    * get data รวมเงินเดือน ข้าราชการ-ลูกจ้างประจำ  รายคน
    * 
    */
	public function salary_emp_ocsc()
	{
		if( Session::get('level') != '' )
		{
			$pdf = new TCPDF();
		    $pdf->SetPrintHeader(false);
		    $pdf->SetPrintFooter(false);

		    $n = DB::select( 'select * from s_general_data' );	
		    foreach ($n as $k) {
		      	$name = $k->name;
		    } 	    
		    
		    $sql  = ' select concat(n.pname,"",n.fname," ",n.lname) as name, s.* ';	    
		    $sql .= ' from n_datageneral n ';
		    $sql .= ' inner join  s_salary_ocsc_detail s on s.cid=n.cid where  month(s.order_date)='.$this->max_month().' and year(s.order_date)='.$this->max_year().' order by n.q_pts asc';

		    $result = DB::select( $sql );

		    $i=0;
		    $n=0;		
			foreach ( $result as $key ) {
				$i++;
				$n++;
				if( $i == 5 ){
					$i=0;
					$i++;
				}

				if( $i == 1 ){
					$pdf->AddPage('L', 'letter');
				}

				$linestyle00 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '5,10,5,10', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(0.4, 210, 0.4,0, $linestyle00);

				$linestyle01 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '5,10,5,10', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(68, 210, 68,0, $linestyle01);

				$linestyle02 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '5,10,5,10', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(136, 210, 136,0, $linestyle02);

				$linestyle03 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '5,10,5,10', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(204, 210, 204,0, $linestyle03);

				$linestyle04 = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '5,10,5,10', 'phase' => 0, 'color' => array(0, 0, 0));
				$pdf->Line(272, 210, 272,0, $linestyle04);

				if( $i == 1 ){
					$pdf->SetY(9);
	 				$pdf->SetX(1);
				}
				if( $i == 2 ){
					$pdf->SetY(9);
	 				$pdf->SetX(69);
				}
				if( $i == 3 ){
					$pdf->SetY(9);
	 				$pdf->SetX(137);
				}
				if( $i == 4 ){
					$pdf->SetY(9);
	 				$pdf->SetX(205);
				}
				$pdf->SetFont('freeserif','',14,'',true);
				$pdf->MultiCell(66, 5, $name, 0, 'C', 0, 1, '', '', true);

				if( $i == 1 ){
					$pdf->SetY(15);
	 				$pdf->SetX(1);
				}
				if( $i == 2 ){
					$pdf->SetY(15);
	 				$pdf->SetX(69);
				}
				if( $i == 3 ){
					$pdf->SetY(15);
	 				$pdf->SetX(137);
				}
				if( $i == 4 ){
					$pdf->SetY(15);
	 				$pdf->SetX(205);
				}
			    $pdf->MultiCell(66, 5, 'ประจำเดือน '. $this->get_monthyearThai( $this->max_month(), $this->max_year() ), 0, 'L', 0, 1, '', '', true);

			    if( $i == 1 ){
					$pdf->SetY(18);
	 				$pdf->SetX(1);
				}
			    if( $i == 2 ){
					$pdf->SetY(18);
	 				$pdf->SetX(69);
				}
				if( $i == 3 ){
					$pdf->SetY(18);
	 				$pdf->SetX(137);
				}
				if( $i == 4 ){
					$pdf->SetY(18);
	 				$pdf->SetX(205);
				}
			    $pdf->MultiCell(70, 5, '__________________________', 0, 'L', 0, 1, '', '', true); 		  

			    $pdf->SetFont('freeserif','',12,'',true);

			    if( $i == 1 ){
					$pdf->SetY(25);
	 				$pdf->SetX(1);
				}
			    if( $i == 2 ){
					$pdf->SetY(25);
	 				$pdf->SetX(69);
				}
				if( $i == 3 ){
					$pdf->SetY(25);
	 				$pdf->SetX(137);
				}
				if( $i == 4 ){
					$pdf->SetY(25);
	 				$pdf->SetX(205);
				}
			    $pdf->MultiCell(66, 5, '('.$n.')'.' ชื่อ  '. $key->name, 0, 'L', 0, 1, '', '', true);
			    		    
			    $pdf->Ln(2);
			    if( $i == 1 ){
					$pdf->SetY(35);
	 				$pdf->SetX(1);
				}
			    if( $i == 2 ){
					$pdf->SetY(35);
	 				$pdf->SetX(69);
				}
				if( $i == 3 ){
					$pdf->SetY(35);
	 				$pdf->SetX(137);
				}
				if( $i == 4 ){
					$pdf->SetY(35);
	 				$pdf->SetX(205);
				}
			    $pdf->MultiCell(40, 5, 'เงินเดือนรวม', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(26, 5, number_format( $key->salary, 2 ), 0, 'R', 0, 1, '', '', true);

				if( $i == 1 ){
					$pdf->SetY(42);
	 				$pdf->SetX(1);
				}
			    if( $i == 2 ){
					$pdf->SetY(42);
	 				$pdf->SetX(69);
				}
				if( $i == 3 ){
					$pdf->SetY(42);
	 				$pdf->SetX(137);
				}
				if( $i == 4 ){
					$pdf->SetY(42);
	 				$pdf->SetX(205);
				}
			    $pdf->MultiCell(40, 5, 'เงินประจำตำแหน่ง', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(26, 5, number_format( $key->r_c, 2 ), 0, 'R', 0, 1, '', '', true);

				if( $i == 1 ){
					$pdf->SetY(49);
	 				$pdf->SetX(1);
				}
			    if( $i == 2 ){
					$pdf->SetY(49);
	 				$pdf->SetX(69);
				}
				if( $i == 3 ){
					$pdf->SetY(49);
	 				$pdf->SetX(137);
				}
				if( $i == 4 ){
					$pdf->SetY(49);
	 				$pdf->SetX(205);
				}
			    $pdf->MultiCell(40, 5, 'เงินช่วยเหลือบุตร', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(26, 5, number_format( $key->son, 2 ), 0, 'R', 0, 1, '', '', true);

				if( $i == 1 ){
					$pdf->SetY(56);
	 				$pdf->SetX(1);
				}
			    if( $i == 2 ){
					$pdf->SetY(56);
	 				$pdf->SetX(69);
				}
				if( $i == 3 ){
					$pdf->SetY(56);
	 				$pdf->SetX(137);
				}
				if( $i == 4 ){
					$pdf->SetY(56);
	 				$pdf->SetX(205);
				}
			    $pdf->MultiCell(40, 5, 'อื่น ๆ', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(26, 5, number_format( $key->r_other, 2 ), 0, 'R', 0, 1, '', '', true);

				if( $i == 1 ){
					$pdf->SetY(63);
	 				$pdf->SetX(1);
				}
			    if( $i == 2 ){
					$pdf->SetY(63);
	 				$pdf->SetX(69);
				}
				if( $i == 3 ){
					$pdf->SetY(63);
	 				$pdf->SetX(137);
				}
				if( $i == 4 ){
					$pdf->SetY(63);
	 				$pdf->SetX(205);
				}
				$pdf->SetFont('freeserif','BU',12,'',true);
			    $pdf->MultiCell(40, 5, 'รวมรับ', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(26, 5, number_format( $key->salary+$key->r_c+$key->son+$key->r_other, 2 ), 0, 'R', 0, 1, '', '', true);

				$pdf->SetFont('freeserif','',12,'',true);
				if( $i == 1 ){
					$pdf->SetY(70);
	 				$pdf->SetX(1);
				}
			    if( $i == 2 ){
					$pdf->SetY(70);
	 				$pdf->SetX(69);
				}
				if( $i == 3 ){
					$pdf->SetY(70);
	 				$pdf->SetX(137);
				}
				if( $i == 4 ){
					$pdf->SetY(70);
	 				$pdf->SetX(205);
				}			
			    $pdf->MultiCell(40, 5, 'ปกส', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(26, 5, number_format( $key->kbk, 2 ), 0, 'R', 0, 1, '', '', true);

				if( $i == 1 ){
					$pdf->SetY(77);
	 				$pdf->SetX(1);
				}
			    if( $i == 2 ){
					$pdf->SetY(77);
	 				$pdf->SetX(69);
				}
				if( $i == 3 ){
					$pdf->SetY(77);
	 				$pdf->SetX(137);
				}
				if( $i == 4 ){
					$pdf->SetY(77);
	 				$pdf->SetX(205);
				}			
			    $pdf->MultiCell(40, 5, 'ภาษี', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(26, 5, number_format( $key->tax, 2 ), 0, 'R', 0, 1, '', '', true);

				if( $i == 1 ){
					$pdf->SetY(84);
	 				$pdf->SetX(1);
				}
			    if( $i == 2 ){
					$pdf->SetY(84);
	 				$pdf->SetX(69);
				}
				if( $i == 3 ){
					$pdf->SetY(84);
	 				$pdf->SetX(137);
				}
				if( $i == 4 ){
					$pdf->SetY(84);
	 				$pdf->SetX(205);
				}			
			    $pdf->MultiCell(40, 5, 'ฌกส', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(26, 5, number_format( $key->cas, 2 ), 0, 'R', 0, 1, '', '', true);

				if( $i == 1 ){
					$pdf->SetY(91);
	 				$pdf->SetX(1);
				}
			    if( $i == 2 ){
					$pdf->SetY(91);
	 				$pdf->SetX(69);
				}
				if( $i == 3 ){
					$pdf->SetY(91);
	 				$pdf->SetX(137);
				}
				if( $i == 4 ){
					$pdf->SetY(91);
	 				$pdf->SetX(205);
				}			
			    $pdf->MultiCell(40, 5, 'ออมทรัพย์จังหวัด', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(26, 5, number_format( $key->save_p, 2 ), 0, 'R', 0, 1, '', '', true);

				if( $i == 1 ){
					$pdf->SetY(98);
	 				$pdf->SetX(1);
				}
			    if( $i == 2 ){
					$pdf->SetY(98);
	 				$pdf->SetX(69);
				}
				if( $i == 3 ){
					$pdf->SetY(98);
	 				$pdf->SetX(137);
				}
				if( $i == 4 ){
					$pdf->SetY(98);
	 				$pdf->SetX(205);
				}			
			    $pdf->MultiCell(40, 5, 'ธนาคารอาคารสงเคราะห์', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(26, 5, number_format( $key->houseLoan, 2 ), 0, 'R', 0, 1, '', '', true);

				if( $i == 1 ){
					$pdf->SetY(105);
	 				$pdf->SetX(1);
				}
			    if( $i == 2 ){
					$pdf->SetY(105);
	 				$pdf->SetX(69);
				}
				if( $i == 3 ){
					$pdf->SetY(105);
	 				$pdf->SetX(137);
				}
				if( $i == 4 ){
					$pdf->SetY(105);
	 				$pdf->SetX(205);
				}	
				$pdf->SetFont('freeserif','BU',12,'',true);		
			    $pdf->MultiCell(40, 5, 'หักจากจังหวัด', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(26, 5, number_format( $key->kbk+$key->tax+$key->cas+$key->save_p+$key->houseLoan, 2 ), 0, 'R', 0, 1, '', '', true);

				if( $i == 1 ){
					$pdf->SetY(112);
	 				$pdf->SetX(1);
				}
			    if( $i == 2 ){
					$pdf->SetY(112);
	 				$pdf->SetX(69);
				}
				if( $i == 3 ){
					$pdf->SetY(112);
	 				$pdf->SetX(137);
				}
				if( $i == 4 ){
					$pdf->SetY(112);
	 				$pdf->SetX(205);
				}	
				$pdf->SetFont('freeserif','BU',12,'',true);		
			    $pdf->MultiCell(40, 5, 'เหลือมาโรงพยาบาล', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(26, 5, number_format( ($key->salary+$key->r_c+$key->son+$key->r_other)-($key->kbk+$key->tax+$key->cas+$key->save_p+$key->houseLoan), 2 ), 0, 'R', 0, 1, '', '', true);

				$pdf->SetFont('freeserif','',12,'',true);
				if( $i == 1 ){
					$pdf->SetY(119);
	 				$pdf->SetX(1);
				}
			    if( $i == 2 ){
					$pdf->SetY(119);
	 				$pdf->SetX(69);
				}
				if( $i == 3 ){
					$pdf->SetY(119);
	 				$pdf->SetX(137);
				}
				if( $i == 4 ){
					$pdf->SetY(119);
	 				$pdf->SetX(205);
				}			
			    $pdf->MultiCell(40, 5, 'ค่าใช้จ่ายอื่น ๆ 1', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(26, 5, number_format( $key->save_h, 2 ), 0, 'R', 0, 1, '', '', true);

				if( $i == 1 ){
					$pdf->SetY(126);
	 				$pdf->SetX(1);
				}
			    if( $i == 2 ){
					$pdf->SetY(126);
	 				$pdf->SetX(69);
				}
				if( $i == 3 ){
					$pdf->SetY(126);
	 				$pdf->SetX(137);
				}
				if( $i == 4 ){
					$pdf->SetY(126);
	 				$pdf->SetX(205);
				}			
			    $pdf->MultiCell(40, 5, 'ค่าใช้จ่ายอื่น ๆ 2', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(26, 5, number_format( $key->p_other, 2 ), 0, 'R', 0, 1, '', '', true);

				if( $i == 1 ){
					$pdf->SetY(133);
	 				$pdf->SetX(1);
				}
			    if( $i == 2 ){
					$pdf->SetY(133);
	 				$pdf->SetX(69);
				}
				if( $i == 3 ){
					$pdf->SetY(133);
	 				$pdf->SetX(137);
				}
				if( $i == 4 ){
					$pdf->SetY(133);
	 				$pdf->SetX(205);
				}			
			    $pdf->MultiCell(40, 5, 'สหกรณ์ร้านค้า', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(26, 5, number_format( $key->shop, 2 ), 0, 'R', 0, 1, '', '', true);

				if( $i == 1 ){
					$pdf->SetY(140);
	 				$pdf->SetX(1);
				}
			    if( $i == 2 ){
					$pdf->SetY(140);
	 				$pdf->SetX(69);
				}
				if( $i == 3 ){
					$pdf->SetY(140);
	 				$pdf->SetX(137);
				}
				if( $i == 4 ){
					$pdf->SetY(140);
	 				$pdf->SetX(205);
				}			
			    $pdf->MultiCell(40, 5, 'ค่าข้าว', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(26, 5, number_format( $key->rice, 2 ), 0, 'R', 0, 1, '', '', true);

				if( $i == 1 ){
					$pdf->SetY(147);
	 				$pdf->SetX(1);
				}
			    if( $i == 2 ){
					$pdf->SetY(147);
	 				$pdf->SetX(69);
				}
				if( $i == 3 ){
					$pdf->SetY(147);
	 				$pdf->SetX(137);
				}
				if( $i == 4 ){
					$pdf->SetY(147);
	 				$pdf->SetX(205);
				}			
			    $pdf->MultiCell(40, 5, 'ค่าน้ำประปา', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(26, 5, number_format( $key->water, 2 ), 0, 'R', 0, 1, '', '', true);

				if( $i == 1 ){
					$pdf->SetY(154);
	 				$pdf->SetX(1);
				}
			    if( $i == 2 ){
					$pdf->SetY(154);
	 				$pdf->SetX(69);
				}
				if( $i == 3 ){
					$pdf->SetY(154);
	 				$pdf->SetX(137);
				}
				if( $i == 4 ){
					$pdf->SetY(154);
	 				$pdf->SetX(205);
				}			
			    $pdf->MultiCell(40, 5, 'ค่าไฟฟ้า', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(26, 5, number_format( $key->elec, 2 ), 0, 'R', 0, 1, '', '', true);

				if( $i == 1 ){
					$pdf->SetY(161);
	 				$pdf->SetX(1);
				}
			    if( $i == 2 ){
					$pdf->SetY(161);
	 				$pdf->SetX(69);
				}
				if( $i == 3 ){
					$pdf->SetY(161);
	 				$pdf->SetX(137);
				}
				if( $i == 4 ){
					$pdf->SetY(161);
	 				$pdf->SetX(205);
				}			
			    $pdf->MultiCell(40, 5, 'ค่ารักษา', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(26, 5, number_format( $key->pt, 2 ), 0, 'R', 0, 1, '', '', true);

				if( $i == 1 ){
					$pdf->SetY(168);
	 				$pdf->SetX(1);
				}
			    if( $i == 2 ){
					$pdf->SetY(168);
	 				$pdf->SetX(69);
				}
				if( $i == 3 ){
					$pdf->SetY(168);
	 				$pdf->SetX(137);
				}
				if( $i == 4 ){
					$pdf->SetY(168);
	 				$pdf->SetX(205);
				}			
			    $pdf->MultiCell(40, 5, 'ออมสิน', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(26, 5, number_format( $key->bank_o, 2 ), 0, 'R', 0, 1, '', '', true);

				if( $i == 1 ){
					$pdf->SetY(175);
	 				$pdf->SetX(1);
				}
			    if( $i == 2 ){
					$pdf->SetY(175);
	 				$pdf->SetX(69);
				}
				if( $i == 3 ){
					$pdf->SetY(175);
	 				$pdf->SetX(137);
				}
				if( $i == 4 ){
					$pdf->SetY(175);
	 				$pdf->SetX(205);
				}			
			    $pdf->MultiCell(40, 5, 'ค่าธรรมเนียมธนาคาร', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(26, 5, number_format( $key->fund_p, 2 ), 0, 'R', 0, 1, '', '', true);

				if( $i == 1 ){
					$pdf->SetY(182);
	 				$pdf->SetX(1);
				}
			    if( $i == 2 ){
					$pdf->SetY(182);
	 				$pdf->SetX(69);
				}
				if( $i == 3 ){
					$pdf->SetY(182);
	 				$pdf->SetX(137);
				}
				if( $i == 4 ){
					$pdf->SetY(182);
	 				$pdf->SetX(205);
				}	
				$pdf->SetFont('freeserif','BU',12,'',true);		
			    $pdf->MultiCell(40, 5, 'หักที่โรงพยาบาล', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(26, 5, number_format( $key->save_h+$key->p_other+$key->shop+$key->rice+$key->water+$key->elec+$key->pt+$key->bank_o+$key->fund_p , 2 ), 0, 'R', 0, 1, '', '', true);

				if( $i == 1 ){
					$pdf->SetY(190);
	 				$pdf->SetX(1);
				}
			    if( $i == 2 ){
					$pdf->SetY(190);
	 				$pdf->SetX(69);
				}
				if( $i == 3 ){
					$pdf->SetY(190);
	 				$pdf->SetX(137);
				}
				if( $i == 4 ){
					$pdf->SetY(190);
	 				$pdf->SetX(205);
				}	
				$pdf->SetFont('freeserif','BU',12,'',true);		
			    $pdf->MultiCell(40, 5, 'รับจริง', 0, 'L', 0, 0, '', '', true);
				$pdf->MultiCell(26, 5, number_format( (($key->salary+$key->r_c+$key->son+$key->r_other)-($key->kbk+$key->tax+$key->cas+$key->save_p+$key->houseLoan))-($key->save_h+$key->p_other+$key->shop+$key->rice+$key->water+$key->elec+$key->pt+$key->bank_o+$key->fund_p) , 2 ), 0, 'R', 0, 1, '', '', true);
			}

			$filename = storage_path() . '/report_salary_emp2.pdf';
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
	*
	* function name  salary_ocsc_receive
	* รายงานเซ็นรับเงินเดือน ข้าราชการ
	*/
	public function salary_ocsc_receive()
	{
		if( Session::get('level') != '' )
		{
			$n = DB::select( 'select * from s_general_data' );	
		    foreach ($n as $k) {
		      	$name = $k->name;
		      	$address = $k->address;
		      	$tax_id = $k->tax_id;
		    } 

			$pdf = new TCPDF();
		    $pdf->SetPrintHeader(true);
		    $pdf->SetPrintFooter(true);

		    $pdf->SetHeaderData('', '', 'รายงานเงินเดือน ลูกจ้างประจำ/ข้าราชการ '. $name .'  ประจำเดือน'.$this->get_monthyearThai( $this->max_month(), $this->max_year() ), ' รหัสประชาชน                     ชื่อ-สกุล                                                                       ลงชื่อผู้รับ');			
			 
		    $pdf->setHeaderFont(array('freeserif','',12));
			$pdf->setFooterFont(array('freeserif','',PDF_FONT_SIZE_DATA));

			// set default monospaced font
			$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
			 
			// set margins
			$pdf->SetMargins(10, PDF_MARGIN_TOP, 10);
			$pdf->SetHeaderMargin(15);
			$pdf->SetFooterMargin(15);

			$pdf->SetFont('freeserif','',12,'',true);

			$pdf->AddPage('P', 'A4');

			$sql  = ' select s.cid, concat(n.pname,"",n.fname," ",n.lname) as name';				
			$sql .= ' from s_salary_ocsc_detail s';
			$sql .= ' left join n_datageneral n on n.cid=s.cid';
			$sql .= ' where  month(s.order_date)='.$this->max_month().' and year(s.order_date)='.$this->max_year();
			$sql .= ' order by n.q_pts asc';
			
			$result = DB::select( $sql );

			$tbl  = ' <style> ';
			$tbl .= '  table.table-report tr td{ border-bottom:1px dashed #000; height:35px; line-height: 35px; } ';	
			$tbl .= ' .text-bold { font-weight: bold; } ';		
			$tbl .= ' </style> ';

			$tbl  .= ' <table class="table-report"> ';		    
			 			
		    foreach ($result as $key) 		    
		    {			       		       
		    	$tbl .= ' <tr>';

			    $tbl .= ' <td class="text-bold" width="120">';
			    $tbl .= $key->cid;
			    $tbl .= ' </td>';
			    
			    $tbl .= ' <td  width="210" align="left">';
			    $tbl .= $key->name;
			    $tbl .= ' </td>';

			    $tbl .= ' <td width="210" >';
			    $tbl .= '';
			    $tbl .= ' </td>';

			    $tbl .= ' </tr>';			    		   				    			
			}			

			$tbl .='</table>';		
		   
			$pdf->writeHTML( $tbl, true, false, false, false, '' );

		    $filename = storage_path() . '/report_salary_ocsc_receive.pdf';
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
	*
	* function name  salary_ocsc_ktb
	* นำส่ง กรุงไทย
	*/
	public function salary_ocsc_ktb()
	{
		if( Session::get('level') != '' )
		{
			$n = DB::select( 'select * from s_general_data' );	
		    foreach ($n as $k) {
		      	$name = $k->name;
		      	$address = $k->address;
		      	$tax_id = $k->tax_id;
		    } 

		    $sql  = ' select s.bank_acc, concat(n.pname,"",n.fname," ",n.lname) as name';
		    $sql .= ' , s.salary, s.r_c, s.special, s.son, s.r_pt, s.r_other, s.kbk, s.tax';
		    $sql .= ' , s.cas, s.save_p, s.houseLoan, s.save_h, s.p_other, s.shop, s.rice';
		    $sql .= ' , s.water, s.elec, s.pt, s.bank_o, s.fund_p';
		    $sql .= ' from s_salary_ocsc_detail s';
		    $sql .= ' left join n_datageneral n on n.cid=s.cid';
		    $sql .= ' where  month(s.order_date)='.$this->max_month().' and year(s.order_date)='.$this->max_year();
		    $sql .= ' order by n.q_pts asc';		  

		    $result = DB::select( $sql );

		    $pdf = new TCPDF();
		    $pdf->SetPrintHeader(true);
		    $pdf->SetPrintFooter(true);

		    $pdf->SetHeaderData('', '', 'รายการนำฝากเงินเดือน ลูกจ้างประจำ/ข้าราชการ '. $name , '  เข้าบัญชีธนาคารกรุงไทย    ประจำเดือน  '.$this->get_monthyearThai( $this->max_month(), $this->max_year() ));			
			 
		    $pdf->setHeaderFont(array('freeserif','',12));
			$pdf->setFooterFont(array('freeserif','',PDF_FONT_SIZE_DATA));

			// set default monospaced font
			$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
			 
			// set margins
			$pdf->SetMargins(10, PDF_MARGIN_TOP, 10);
			$pdf->SetHeaderMargin(10);
			$pdf->SetFooterMargin(10);

			$pdf->SetFont('freeserif','',12,'',true);

			$pdf->AddPage('P', 'A4');

			$tbl  = ' <style> ';
			$tbl .= '  table.table-report tr td { border-bottom:1px dashed #000; height:35px; line-height: 35px; } ';	
			$tbl .= '  table.table-report tr th { border-bottom:2px solid #000; border-top:2px solid #000; height:35px; line-height: 25px; }';
			$tbl .= ' .text-bold { font-weight: bold; } hr { border:2px solid #000; }';	
			$tbl .= ' table.table-sum tr td { font-weight: bold; font-size: 14px; }';
			$tbl .= ' table.table-textthai { font-weight: bold; font-size: 14px; border-bottom:2px solid #000; } ';	
			$tbl .= ' </style> ';

			$tbl  .= ' <table class="table-report"> ';

			$tbl  .= '<tr>';
			$tbl  .= '<th>เลขที่บัญชี</th> <th>ชื่อ-นามสกุล</th> <th align="right">จำนวนเงิน</th>';
			$tbl  .= '</tr>';		    
			 	
			 $r=0;	
			 $salary_sum = 0;		
		    foreach ($result as $key) 		    
		    {	
		    	$r++;		       		       
		    	$tbl .= ' <tr>';

			    $tbl .= ' <td class="text-bold" width="120">';
			    $tbl .= $key->bank_acc;
			    $tbl .= ' </td>';
			    
			    $tbl .= ' <td  width="210" align="left">';
			    $tbl .= $key->name;
			    $tbl .= ' </td>';

			    $tbl .= ' <td width="210" align="right" >';
			    $tbl .= number_format( (($key->salary+$key->r_c+$key->son+$key->r_other)-($key->kbk+$key->tax+$key->cas+$key->save_p+$key->houseLoan))-($key->save_h+$key->p_other+$key->shop+$key->rice+$key->water+$key->elec+$key->pt+$key->bank_o+$key->fund_p) , 2 );
			    $tbl .= ' </td>';

			    $tbl .= ' </tr>';	

			    $salary_sum = $salary_sum + ( (($key->salary+$key->r_c+$key->son+$key->r_other)-($key->kbk+$key->tax+$key->cas+$key->save_p+$key->houseLoan))-($key->save_h+$key->p_other+$key->shop+$key->rice+$key->water+$key->elec+$key->pt+$key->bank_o+$key->fund_p) );		    		   				    			
			}			

			$tbl .= '</table>';	

			$tbl .= ' <br /><br />';
			$tbl .= ' <table class="table-sum">';
			$tbl .= ' <tr>';
			$tbl .= ' <td width="70">รวม:</td><td>'.$r.'  ราย</td> <td width="200" align="right">รวมเป็นเงิน</td><td align="right">'. number_format( $salary_sum, 2 ) .'</td>';
			$tbl .= ' </tr>';
			$tbl .= ' </table>';			

			$tbl .= ' <br /><br />';
			$tbl .= ' <table class="table-textthai">';
			$tbl .= ' <tr>';
			$tbl .= ' <td width="540" align="right">'.$this->ThaiBahtConversion( $salary_sum ).'</td>';
			$tbl .= ' </tr>';
			$tbl .= ' </table>';

			$tbl .= ' <br /><br />';
			$tbl .= ' <table class="">';
			$tbl .= ' <tr>';
			$tbl .= ' <td width="240" height="30" align="left">ลงชื่อผู้นำส่ง.........................................</td> <td width="240" align="left">ลงชื่อผู้รับ.........................................</td>';
			$tbl .= ' </tr>';
			$tbl .= ' <tr>';
			$tbl .= ' <td width="240" align="left">วันที่.................................................</td> <td width="240" align="left">วันที่...............................................</td>';
			$tbl .= ' </tr>';
			$tbl .= ' </table>';
		   
			$pdf->writeHTML( $tbl, true, false, false, false, '' );

		    $filename = storage_path() . '/report_salary_ocsc_ktb.pdf';
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
	*
	* function name  special_excel
	* รายงานค่าตอบแทนแบบรวม Excel
	*/
	public function special_excel()
	{
		if( Session::get('level') != '' )
		{
			$y = DB::Select( ' select year(order_date) as year1 from s_salary_detail group by year(order_date) order by year(order_date) desc ' );	
			return View::make( 'report.report3', array( 'data' => $y ) );
		}
		else
		{
			return View::make('login.index');
		}  
	}

	/*
	*
	* function name  special_excel_export
	* รายงานค่าตอบแทนแบบรวม Excel Export
	*/
	public function special_excel_export()
	{
		$m  	= Input::get( 'm1' );
	    $y 		= Input::get( 'y1' );	
	    
	    $sql =' select * from (';	
	    $sql .=' (select concat(n_datageneral.pname,"",n_datageneral.fname," ",n_datageneral.lname) as namefull, n_datageneral.q_pts'; 
	    $sql .=' ,n_department.sort, n_datageneral.dep_id, bank_acc, water, elec, pts, ot, ch8, no_v, outpcu, sub_ot, ch11, (s_salary_detail.salary_id) as salary_id, (1) as type';
	    $sql .=' from s_salary_detail';
	    $sql .=' left join n_datageneral on n_datageneral.cid = s_salary_detail.cid';
	    $sql .=' inner join n_department on n_department.department_id = n_datageneral.dep_id';
	    $sql .=' where year(s_salary_detail.order_date) = '.$y.' ';   
	    $sql .=' and month(s_salary_detail.order_date) = '.$m.' ';	   
	    $sql .='  )';
	    $sql .=' union';
	    $sql .=' (select concat(n_datageneral.pname,"",n_datageneral.fname," ",n_datageneral.lname) as namefull, n_datageneral.q_pts, n_department.sort, n_datageneral.dep_id, ( select sba.bank_acc from s_bank_acc sba where sba.bank_id in (select ss.bank_id from s_bank ss where ss.bank_name like "%ธกส%") and sba.cid=n_datageneral.cid ) as bank_acc ';
	    $sql .=' , water, elec, pts, ot, ch8, no_v, outpcu, sub_ot, ch11, (s_salary_ocsc_detail.salary_ocsc_id) as salary_id, (2) as type';
	    $sql .=' from s_salary_ocsc_detail';
	    $sql .=' left join n_datageneral on n_datageneral.cid = s_salary_ocsc_detail.cid';
	    $sql .=' inner join n_department on n_department.department_id = n_datageneral.dep_id';
	    $sql .=' where year(s_salary_ocsc_detail.order_date) = '.$y.' ';
	    $sql .=' and month(s_salary_ocsc_detail.order_date) = '.$m.' ';	   
	    $sql .='  )) as a order by a.q_pts asc';	 	   

		$result = DB::select( $sql );			

		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial'); 
	    $objPHPExcel->getActiveSheet()->setTitle('Sheet1');
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'เลขที่บัญชี');	
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(18);	
		$objPHPExcel->getActiveSheet()->getStyle('A')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER); 	
		$objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

		$objPHPExcel->getActiveSheet()->setCellValue('B1', 'ชื่อ-นามสกุล');
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(50);	
		$objPHPExcel->getActiveSheet()->setCellValue('C1', 'พตส');
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(18);	
		$objPHPExcel->getActiveSheet()->getStyle('C')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1); 
		$objPHPExcel->getActiveSheet()->setCellValue('D1', 'OT');	
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(18);	
		$objPHPExcel->getActiveSheet()->getStyle('D')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1); 
		$objPHPExcel->getActiveSheet()->setCellValue('E1', 'ฉ8');	
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(18);	
		$objPHPExcel->getActiveSheet()->getStyle('E')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1); 
		$objPHPExcel->getActiveSheet()->setCellValue('F1', 'ไม่ทำเวช');	
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(18);	
		$objPHPExcel->getActiveSheet()->getStyle('F')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1); 
		$objPHPExcel->getActiveSheet()->setCellValue('G1', 'ออกหน่วย');	
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(18);	
		$objPHPExcel->getActiveSheet()->getStyle('G')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
		$objPHPExcel->getActiveSheet()->setCellValue('H1', 'ฉ 11');	
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(18);	
		$objPHPExcel->getActiveSheet()->getStyle('H')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);  
		
		/*$objPHPExcel->getActiveSheet()->setCellValue('I1', 'ค่าน้ำ');
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(18);	
		$objPHPExcel->getActiveSheet()->getStyle('I')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1); 
		
		$objPHPExcel->getActiveSheet()->setCellValue('J1', 'ค่าไฟ');	
		$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(18);	
		$objPHPExcel->getActiveSheet()->getStyle('J')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1); 
		
		$objPHPExcel->getActiveSheet()->setCellValue('K1', 'รวมหักน้ำ-ไฟ');	
		$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(18);	
		$objPHPExcel->getActiveSheet()->getStyle('K')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1); */

		$row = 0;
		$sumB = 0;
		foreach ($result as $key) {			
			//if($key->pts != 0 || $key->ot != 0 || $key->ch8 != 0 ||  $key->no_v != 0 || $key->outpcu != 0 || $key->ch11 != 0){
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (0, $row+2, $key->bank_acc);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (1, $row+2, $key->namefull);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (2, $row+2, $key->pts);	
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (3, $row+2, $key->ot);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (4, $row+2, $key->ch8);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (5, $row+2, $key->no_v);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (6, $row+2, $key->outpcu);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (7, $row+2, $key->ch11);
				/*$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (8, $row+2, $key->water);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (9, $row+2, $key->elec);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (10, $row+2, ($key->pts+$key->ot+$key->ch8+$key->no_v+$key->outpcu+$key->ch11)-($key->water+$key->elec));*/	
					
				$row++;	
			//}		
		}	
	
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5'); // Set excel version 2007	  		
	    $objWriter->save(storage_path()."/excel/reportSpecialData.xls");

	    return Response::download( storage_path()."/excel/reportSpecialData.xls", "reportSpecialData.xls");
	}

	/*
	*
	* function name  special_ot_excel
	* รายงานค่าตอบแทนแบบรวม Excel
	*/
	public function special_ot_excel()
	{
		if( Session::get('level') != '' )
		{
			$y = DB::Select( ' select year(order_date) as year1 from s_salary_detail group by year(order_date) order by year(order_date) desc ' );	
			return View::make( 'report.report4', array( 'data' => $y ) );
		}
		else
		{
			return View::make('login.index');
		}  
	}

	/*
	*
	* function name  special_ot_excel_export
	* รายงานค่าตอบแทนแบบรวม Excel OT Export
	*/
	public function special_ot_excel_export()
	{
		$m  	= Input::get( 'm1' );
	    $y 		= Input::get( 'y1' );	
	    
	    $sql =' select * from (';	
	    $sql .=' (select concat(n_datageneral.pname,"",n_datageneral.fname," ",n_datageneral.lname) as namefull,n_datageneral.q_pts '; 
	    $sql .=' ,n_department.sort, n_datageneral.dep_id, n_datageneral.cid, bank_acc, water, elec, pts, ot, ch8, no_v, outpcu, sub_ot, (s_salary_detail.salary_id) as salary_id, (1) as type';
	    $sql .=' from s_salary_detail';
	    $sql .=' left join n_datageneral on n_datageneral.cid = s_salary_detail.cid';
	    $sql .=' inner join n_department on n_department.department_id = n_datageneral.dep_id';
	    $sql .=' where year(s_salary_detail.order_date) = '.$y.' ';   
	    $sql .=' and month(s_salary_detail.order_date) = '.$m.' ';
	    $sql .=' and ot > 0';	   
	    $sql .=' order by n_department.sort asc )';
	    $sql .=' union';
	    $sql .=' (select concat(n_datageneral.pname,"",n_datageneral.fname," ",n_datageneral.lname) as namefull,  n_datageneral.q_pts, n_department.sort, n_datageneral.dep_id, n_datageneral.cid, ( select sba.bank_acc from s_bank_acc sba where sba.bank_id in (select ss.bank_id from s_bank ss where ss.bank_name like "%ธกส%") and sba.cid=n_datageneral.cid ) as bank_acc';
	    $sql .=' , water, elec, pts, ot, ch8, no_v, outpcu, sub_ot, (s_salary_ocsc_detail.salary_ocsc_id) as salary_id, (2) as type ';	
	    $sql .=' from s_salary_ocsc_detail';
	    $sql .=' left join n_datageneral on n_datageneral.cid = s_salary_ocsc_detail.cid';
	    $sql .=' inner join n_department on n_department.department_id = n_datageneral.dep_id';
	    $sql .=' where year(s_salary_ocsc_detail.order_date) = '.$y.' ';
	    $sql .=' and month(s_salary_ocsc_detail.order_date) = '.$m.' ';	 
	    $sql .=' and ot > 0';	  
	    $sql .=' order by n_department.sort asc )) as a order by a.q_pts asc';	 	   

		$result = DB::select( $sql );			

		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial'); 
	    $objPHPExcel->getActiveSheet()->setTitle('Sheet1');
		$objPHPExcel->setActiveSheetIndex(0);
		//$objPHPExcel->getActiveSheet()->setCellValue('A1', 'รหัสประชาชน');	
		//$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(18);	
		//$objPHPExcel->getActiveSheet()->getStyle('A')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER); 	
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'เลขที่บัญชี');
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(18);	
		$objPHPExcel->getActiveSheet()->getStyle('A')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER); 	
		$objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

		$objPHPExcel->getActiveSheet()->setCellValue('B1', 'ชื่อ-นามสกุล');
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(50);			
		$objPHPExcel->getActiveSheet()->setCellValue('C1', 'OT');	
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(18);	
		$objPHPExcel->getActiveSheet()->getStyle('C')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1); 
		
		$row = 0;
		$sumB = 0;
		foreach ($result as $key) {			
			
			//$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (0, $row+2, $key->cid);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (0, $row+2, $key->bank_acc);	
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (1, $row+2, $key->namefull);	
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (2, $row+2, $key->ot);
			
			$row++;			
		}	
	
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5'); // Set excel version 2007	  		
	    $objWriter->save(storage_path()."/excel/reportSpecialOTData.xls");

	    return Response::download( storage_path()."/excel/reportSpecialOTData.xls", "reportSpecialOTData.xls");
	}


	/*
	*
	* function name  sp_sa_excel
	* รายงานรวมเงินเดือนและค่าตอบแทน Excel
	*/
	public function sp_sa_excel()
	{
		if( Session::get('level') != '' )
		{
			$y = DB::Select( ' select year(order_date) as year1 from s_salary_detail group by year(order_date) order by year(order_date) desc ' );	
			return View::make( 'report.report5', array( 'data' => $y ) );
		}
		else
		{
			return View::make('login.index');
		}  
	}

	/*
	*
	* function name  sp_sa_excel_export
	* รายงานรวมเงินเดือนและค่าตอบแทน Excel Export
	*/
	public function sp_sa_excel_export()
	{		
	    $y 		= Input::get( 'y5' );	
	    
	    $sql =' select * from (';	
	    $sql .=' (select concat(n_datageneral.pname,"",n_datageneral.fname," ",n_datageneral.lname) as namefull, n_datageneral.q_pts'; 
	    $sql .=' ,n_department.sort, n_datageneral.dep_id, n_datageneral.cid, sum(salary+salary_other) as salary, sum(special+pts+ot+ch8+no_v+outpcu+ch11) as spsum, (s_salary_detail.salary_id) as salary_id, (1) as type';
	    $sql .=' from s_salary_detail';
	    $sql .=' left join n_datageneral on n_datageneral.cid = s_salary_detail.cid';
	    $sql .=' inner join n_department on n_department.department_id = n_datageneral.dep_id';
	    $sql .=' where year(s_salary_detail.order_date) = '.$y.' ';   	   	       
	    $sql .=' group by cid order by n_department.sort asc )';
	    $sql .=' union';
	    $sql .=' (select concat(n_datageneral.pname,"",n_datageneral.fname," ",n_datageneral.lname) as namefull, n_datageneral.q_pts';
	    $sql .=' ,n_department.sort, n_datageneral.dep_id, n_datageneral.cid, sum(salary+r_other) as salary, sum(special+pts+ot+ch8+no_v+outpcu+ch11) as spsum, (s_salary_ocsc_detail.salary_ocsc_id) as salary_id, (2) as type';
	    $sql .=' from s_salary_ocsc_detail';
	    $sql .=' left join n_datageneral on n_datageneral.cid = s_salary_ocsc_detail.cid';
	    $sql .=' inner join n_department on n_department.department_id = n_datageneral.dep_id';
	    $sql .=' where year(s_salary_ocsc_detail.order_date) = '.$y.' ';	   	    
	    $sql .=' group by cid order by n_department.sort asc )) as a order by a.q_pts asc';		 	   

		$result = DB::select( $sql );			

		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial'); 
	    $objPHPExcel->getActiveSheet()->setTitle('Sheet1');
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'รหัสประชาชน');	
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(18);	
		$objPHPExcel->getActiveSheet()->getStyle('A')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER); 	
		$objPHPExcel->getActiveSheet()->setCellValue('B1', 'ชื่อ-นามสกุล');
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(50);	
		$objPHPExcel->getActiveSheet()->setCellValue('C1', 'รวมเงินเดือน');
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(18);	
		$objPHPExcel->getActiveSheet()->getStyle('C')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1); 
		$objPHPExcel->getActiveSheet()->setCellValue('D1', 'รวมค่าตอบแทน');	
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(18);	
		$objPHPExcel->getActiveSheet()->getStyle('D')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1); 
		
		$row = 0;
		$sumB = 0;
		foreach ($result as $key) {			
			
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (0, $row+2, $key->cid);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (1, $row+2, $key->namefull);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (2, $row+2, $key->salary);	
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (3, $row+2, $key->spsum);
			
			$row++;			
		}	
	
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5'); // Set excel version 2007	  		
	    $objWriter->save(storage_path()."/excel/reportSpSaData.xls");

	    return Response::download( storage_path()."/excel/reportSpSaData.xls", "reportSpSaData.xls");
	}

	/*
	*
	* function name  salary_sso_home
	* หน้าหลัก แบบรายงานการแสดงการส่งเงินสมทบ
	*/
	public function salary_sso_home()
	{
		if( Session::get('level') != '' )
		{
			$y = DB::Select( ' select year(order_date) as year1 from s_salary_detail group by year(order_date) order by year(order_date) desc ' );	
			return View::make( 'report.report_sso_home', array( 'data' => $y ) );
		}
		else
		{
			return View::make('login.index');
		}  
	}

	/*
	*
	* function name  salary_sso_pdf_export
	* แบบรายงานการแสดงการส่งเงินสมทบ  ส่งออก PDF
	*/
	public function salary_sso_pdf_export()
	{
		$m 		= Input::get( 'm_sso_1' );
		$y 		= Input::get( 'y_sso_1' );

		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	    $pdf->SetPrintHeader(false);
		 
		// set header and footer fonts
		$pdf->setHeaderFont(array('freeserif','B',PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(array('freeserif','B',PDF_FONT_SIZE_DATA));
		 
		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		 
		// set margins
		$pdf->SetMargins(10, 15, 10);
		$pdf->SetHeaderMargin(15);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
 
		$pdf->SetFont('freeserif','',14,'',true);	  

	    $n = DB::select( 'select * from s_general_data' );	
	    foreach ($n as $k) {
	      	$name = $k->name;
	    }  		    
	   
	    $sql  = ' select s.cid, concat(n.pname,"",n.fname," ",n.lname) as name, (s.salary+s.salary_other) as salary, s.salary_sso ';
	    $sql .= ' from s_salary_detail s';
	    $sql .= ' left join n_datageneral n on n.cid=s.cid';
	    $sql .= ' where year(order_date)='.$y.' and month(order_date)='.$m.'  order by  n.q_pts asc';

	    $data = DB::Select( $sql );

	    $j=0;
	    $row=0;
	    $sum1=0;
	    $sum2=0;

	    foreach ($data as $k) 
	    {
	    	$row++;

	    	if( $j==30 )
    		{
    			$j=0;
    		}

    		if( $j == 0)
    		{
    			$pdf->AddPage('', 'A4');
   			
    			//header
			    $pdf->SetFont('freeserif','',12,'',true);
			    $pdf->SetY(5);
			 	$pdf->SetX(160);
			    $pdf->MultiCell(40, 5, 'สปส.1-10 (ส่วนที่ 2)', 0, 'R', 0, 1, '', '', true);

			    $pdf->SetFont('freeserif','B',14,'',true);
			    $pdf->MultiCell(190, 5, 'แบบรายงานการแสดงการส่งเงินสมทบ ', 0, 'C', 0, 1, '', '', true);

			    $pdf->SetFont('freeserif','',13,'',true);
			    $pdf->SetY(18);
			    $pdf->MultiCell(190, 5, 'การนำส่งเงินสมทบสำหรับค่าจ้างเดิอน '. $this->get_monthyearThai( $m, $y ), 0, 'C', 0, 1, '', '', true);

			    $pdf->SetFont('freeserif','B',13,'',true);
			    $pdf->SetY(30);
			 	$pdf->SetX(10);
			    $pdf->MultiCell(40, 5, 'ชื่อสถานประกอบการ ', 0, 'L', 0, 1, '', '', true);
			    
			    $pdf->SetFont('freeserif','',13,'',true);
			    $pdf->SetY(30);
			 	$pdf->SetX(50);
			    $pdf->MultiCell(70, 5, $name, 0, 'L', 0, 1, '', '', true);

			    $pdf->SetFont('freeserif','B',13,'',true);
			    $pdf->SetY(30);
			 	$pdf->SetX(140);
			    $pdf->MultiCell(22, 5, 'เลขที่บัญชี', 0, 'L', 0, 1, '', '', true);

			    $pdf->SetFont('freeserif','',13,'',true);
			    $pdf->SetY(30);
			 	$pdf->SetX(163);
			    $pdf->MultiCell(25, 5, '1090000219', 0, 'L', 0, 1, '', '', true);

			    $pdf->SetFont('freeserif','B',13,'',true);
			    $pdf->SetY(36);
			 	$pdf->SetX(140);
			    $pdf->MultiCell(22, 5, 'สาขา', 0, 'L', 0, 1, '', '', true);

			    $pdf->SetFont('freeserif','',13,'',true);
			    $pdf->SetY(36);
			 	$pdf->SetX(163);
			    $pdf->MultiCell(25, 5, '300311', 0, 'L', 0, 1, '', '', true);

			    $linever = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(0, 0, 0));

			    $pdf->Line(200, 43, 10,43, $linever);
			    $pdf->Line(200, 50, 10,50, $linever);

			    $pdf->SetFont('freeserif','',13,'',true);

			    $pdf->SetY(43);
			 	$pdf->SetX(10);
			    $pdf->MultiCell(20, 7, 'ลำดับที่', 0, 'C', 0, 1, '', '', true);

			    $pdf->SetY(43);
			 	$pdf->SetX(30);
			    $pdf->MultiCell(40, 7, 'เลขประจำตัวประชาชน', 0, 'C', 0, 1, '', '', true);

			    $pdf->SetY(43);
			 	$pdf->SetX(70);
			    $pdf->MultiCell(70, 7, 'ชื่อ-สกุล', 0, 'C', 0, 1, '', '', true);

			    $pdf->SetY(43);
			 	$pdf->SetX(140);
			    $pdf->MultiCell(30, 7, 'ค่าจ้าง', 0, 'R', 0, 1, '', '', true);

			    $pdf->SetY(43);
			 	$pdf->SetX(170);
			    $pdf->MultiCell(30, 7, 'เงินสมทบ', 0, 'R', 0, 1, '', '', true);

    		}//end add header

    		//detail
    		$pdf->SetY(50+($j*7.5));
		 	$pdf->SetX(10);
		    $pdf->MultiCell(20, 7, $row, 0, 'R', 0, 1, '', '', true);

		    $pdf->SetY(50+($j*7.5));
		 	$pdf->SetX(30);
		    $pdf->MultiCell(40, 7, $k->cid, 0, 'C', 0, 1, '', '', true);

		    $pdf->SetY(50+($j*7.5));
		 	$pdf->SetX(70);
		    $pdf->MultiCell(70, 7, $k->name, 0, 'L', 0, 1, '', '', true);

		    $pdf->SetY(50+($j*7.5));
		 	$pdf->SetX(140);
		    $pdf->MultiCell(30, 7, number_format( $k->salary, 2 ), 0, 'R', 0, 1, '', '', true);

		    $pdf->SetY(50+($j*7.5));
		 	$pdf->SetX(170);
		    $pdf->MultiCell(30, 7, number_format( $k->salary_sso, 2 ), 0, 'R', 0, 1, '', '', true);
		    //end detail
		    
		    $sum1 = $sum1 + $k->salary;
		    $sum2 = $sum2 + $k->salary_sso;
		   	    	
    		$j++;

	    }// end data

	    //sum all 
	    $pdf->Line(200, 50+($j*7.5), 10,50+($j*7.5), $linever);
	    $pdf->Line(200, 56+($j*7.5), 10,57+($j*7.5), $linever);

	    $pdf->SetFont('freeserif','B',13,'',true);
	    $pdf->SetY(50+($j*7.5));
	 	$pdf->SetX(70);
	    $pdf->MultiCell(70, 7, 'ยอดรวม', 0, 'C', 0, 1, '', '', true);

	    $pdf->SetY(50+($j*7.5));
	 	$pdf->SetX(140);
	    $pdf->MultiCell(30, 7, number_format( $sum1, 2 ), 0, 'R', 0, 1, '', '', true);

	    $pdf->SetY(50+($j*7.5));
	 	$pdf->SetX(170);
	    $pdf->MultiCell(30, 7, number_format( $sum2, 2 ), 0, 'R', 0, 1, '', '', true);
	    //end sum all
	    //
		
		$pdf->SetFont('freeserif','',13,'',true);	    
	    $pdf->SetY(62+($j*7.5));
	 	$pdf->SetX(70);
	    $pdf->MultiCell(90, 5, 'ลงชื่อ.............................................................', 0, 'R', 0, 1, '', '', true);

	    $pdf->SetY(62+($j*7.5));
	 	$pdf->SetX(160);
	    $pdf->MultiCell(43, 5, 'นายจ้าง/ผู้รับมอบอำนาจ', 0, 'R', 0, 1, '', '', true);
	      
	    $pdf->SetY(70+($j*7.5));
	 	$pdf->SetX(70);
	    $pdf->MultiCell(90, 5, '(.............................................................)', 0, 'R', 0, 1, '', '', true);

	    $pdf->SetY(82+($j*7.5));
	 	$pdf->SetX(70);
	    $pdf->MultiCell(120, 5, 'ยื่นแบบวันที่.............เดือน..............................พ.ศ..............', 0, 'C', 0, 1, '', '', true);

	  

	    $filename = storage_path() . '/salary_sso_pdf_export.pdf';
	    // Response::download($filename);
	    $contents = $pdf->output($filename, 'I');
		$headers = array(
		    'Content-Type' => 'application/pdf',
		);
		return Response::make($contents, 200, $headers);
	}
	
	
	
	
	
	/*
	*
	* function name  salary_sso_home_excel
	* หน้าหลัก แบบรายงานการแสดงการส่งเงินสมทบ
	*/
	public function salary_sso_home_excel()
	{
		if( Session::get('level') != '' )
		{
			$y = DB::Select( ' select year(order_date) as year1 from s_salary_detail group by year(order_date) order by year(order_date) desc ' );	
			return View::make( 'report.report_sso_home_excel', array( 'data' => $y ) );
		}
		else
		{
			return View::make('login.index');
		}  
	}
	
	
	
	
	/*
	*
	* function name  salary_sso_excel_export
	* แบบรายงานการแสดงการส่งเงินสมทบ  ส่งออก Excel
	*/
	public function salary_sso_excel_export()
	{
		$m 		= Input::get( 'm_sso_1' );
		$y 		= Input::get( 'y_sso_1' );	    
	   
	    $sql  = ' select s.cid, concat(n.pname,"",n.fname," ",n.lname) as name, (s.salary+s.salary_other) as salary, s.salary_sso ';
	    $sql .= ' from s_salary_detail s';
	    $sql .= ' left join n_datageneral n on n.cid=s.cid';
	    $sql .= ' where year(order_date)='.$y.' and month(order_date)='.$m.' order by n.q_pts asc ';

	    $data = DB::Select( $sql );

	    $objPHPExcel = new PHPExcel();
		$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial'); 
	    $objPHPExcel->getActiveSheet()->setTitle('Sheet1');
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'เลขประจำตัวประชาชน');	
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getStyle('A')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER); 
		$objPHPExcel->getActiveSheet()->setCellValue('B1', 'ชื่อ-นามสกุล');
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(40);	
		$objPHPExcel->getActiveSheet()->setCellValue('C1', 'ค่าจ้าง');	
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(18);	
		$objPHPExcel->getActiveSheet()->getStyle('C')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1); 		
		$objPHPExcel->getActiveSheet()->setCellValue('D1', 'เงินสมทบ');	
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(18);	
		$objPHPExcel->getActiveSheet()->getStyle('D')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1); 
	
		$row = 0;		
		foreach ($data as $key) {			
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow ( 0, $row+2, $key->cid );
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow ( 1, $row+2, $key->name );
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow ( 2, $row+2, $key->salary );	
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow ( 3, $row+2, $key->salary_sso );				
			$row++;			
		}			

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5'); // Set excel version 2007	  		
	    $objWriter->save(storage_path()."/excel/report_ssoall.xls");

	    return Response::download( storage_path()."/excel/report_ssoall.xls", "report_ssoall.xls");	
	}





	/*
	*
	* function name  support_excel
	* รายละเอียดเงินค่าจ้าง Excel พกส ชั่วคราว
	*/
	public function support_excel()
	{
		if( Session::get('level') != '' )
		{
			$y = DB::Select( ' select year(order_date) as year1 from s_salary_detail group by year(order_date) order by year(order_date) desc ' );	
			return View::make( 'report.report6', array( 'data' => $y ) );
		}
		else
		{
			return View::make('login.index');
		}  
	}

	/*
	*
	* function name  support_excel_export
	* รายละเอียดเงินค่าจ้าง Excel Export พกส ชั่วคราว
	*/
	public function support_excel_export()
	{	
		$m 		= Input::get( 'm6' );	
	    $y 		= Input::get( 'y6' );	    			

		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial'); 
	    $objPHPExcel->getActiveSheet()->setTitle('Sheet1');
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'ชื่อ-นามสกุล');	
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(50);			 	
		$objPHPExcel->getActiveSheet()->setCellValue('B1', 'ตำแหน่ง');
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(50);	
		$objPHPExcel->getActiveSheet()->setCellValue('C1', 'ค่าจ้าง (บริการ)');
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);	
		$objPHPExcel->getActiveSheet()->getStyle('C')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1); 
		$objPHPExcel->getActiveSheet()->setCellValue('D1', 'ค่าจ้าง (สนับสนุน)');	
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);	
		$objPHPExcel->getActiveSheet()->getStyle('D')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1); 		
		
		$objPHPExcel->getActiveSheet()->setCellValue('E1', 'เงินประกันสังคม');	
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);	
		$objPHPExcel->getActiveSheet()->getStyle('E')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1); 
		
		$sql0 = ' select n.cid, concat(n.pname,"",n.fname," ",n.lname) as name from n_datageneral n';
		$sql0 .= ' where n.cid in ( select s.cid from s_salary_detail s where s.salary > 0 and year(s.order_date)='.$y.' and month(s.order_date)='.$m.' ';
		$sql0 .= '  and (';
		$sql0 .= '  ((select np1.level from n_position_salary np1 where np1.cid=s.cid order by np1.salaryID desc limit 1) = "พกส.(ปฏิบัติงาน)" ) or ';
		$sql0 .= '  ((select np2.level from n_position_salary np2 where np2.cid=s.cid order by np2.salaryID desc limit 1) = "ลูกจ้างชั่วคราว") ';
		$sql0 .= '  )';
		$sql0 .= '  group by s.cid )';
	    $sql0 .= ' order by n.q_pts asc';
		$result0 = DB::select( $sql0 );

		$row = 0;
		$sum1 = 0;
		$sum2 = 0;
		$sum3 = 0;
		foreach ($result0 as $key0) {			
			
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (0, $row+2, $key0->name);


			$sql  = ' select s.salary, s.salary_other';	 
		    $sql .= ' , (select positionName from n_position where position_id=(select ps.position_id from n_position_salary ps where ps.cid=n.cid order by ps.salaryID desc limit 1)) as position';
		    $sql .= ' , (select support_type from n_position where position_id=(select ps.position_id from n_position_salary ps where ps.cid=n.cid order by ps.salaryID desc limit 1)) as support_type';
		    $sql .= ' , s.salary_sso';
		    $sql .= ' from s_salary_detail s';
		    $sql .= ' left join n_datageneral n on n.cid=s.cid';
		    $sql .= ' where year(s.order_date)='.$y.' and month(s.order_date)='.$m.' and n.cid='.$key0->cid.' ';
		    $sql .= ' order by n.datainfoID';	      

			$result = DB::select( $sql );

			foreach ($result as $key) {

				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (1, $row+2, $key->position);

				if( $key->support_type == 1 ){
					$type1 = $key->salary+$key->salary_other;
				}else {
					$type1="";
				}

				if( $key->support_type == 2 ){
					$type2 = $key->salary+$key->salary_other;
				} else {
					$type2="";
				}

				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (2, $row+2, $type1);	
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (3, $row+2, $type2);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (4, $row+2, $key->salary_sso);

				$sum1 += $type1;
				$sum2 += $type2;
				$sum3 += $key->salary_sso;
			}// end foreach result
			
			$row++;			
		}//end foreach result0	

		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (0, $row+3, 'รวม');	
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (2, $row+3, $sum1);	
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (3, $row+3, $sum2);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (4, $row+3, $sum3);
	
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5'); // Set excel version 2007	  		
	    $objWriter->save(storage_path()."/excel/reportSupportData.xls");

	    return Response::download( storage_path()."/excel/reportSupportData.xls", "reportSupportData.xls");
	}





	/*
	*
	* function name  support_ocsc_excel
	* รายละเอียดเงินค่าจ้าง Excel ข้าราชการ
	*/
	public function support_ocsc_excel()
	{
		if( Session::get('level') != '' )
		{
			$y = DB::Select( ' select year(order_date) as year1 from s_salary_ocsc_detail group by year(order_date) order by year(order_date) desc ' );	
			return View::make( 'report.report7', array( 'data' => $y ) );
		}
		else
		{
			return View::make('login.index');
		}  
	}

	/*
	*
	* function name  support_ocsc_excel_export
	* รายละเอียดเงินค่าจ้าง Excel Export ข้าราชการ
	*/
	public function support_ocsc_excel_export()
	{	
		$m 		= Input::get( 'm7' );	
	    $y 		= Input::get( 'y7' );	    			

		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial'); 
	    $objPHPExcel->getActiveSheet()->setTitle('Sheet1');
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'ชื่อ-นามสกุล');	
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(50);			 	
		$objPHPExcel->getActiveSheet()->setCellValue('B1', 'ตำแหน่ง');
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(50);	
		
		$objPHPExcel->getActiveSheet()->setCellValue('C1', 'เงินเดือน');
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);	
		$objPHPExcel->getActiveSheet()->getStyle('C')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1); 
		$objPHPExcel->getActiveSheet()->setCellValue('D1', 'เงินประจำตำแหน่ง');	
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);	
		$objPHPExcel->getActiveSheet()->getStyle('D')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1); 		
		$objPHPExcel->getActiveSheet()->setCellValue('E1', 'เงิน พตส.');	
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);	
		$objPHPExcel->getActiveSheet()->getStyle('E')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1); 
		$objPHPExcel->getActiveSheet()->setCellValue('F1', 'OT');	
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);	
		$objPHPExcel->getActiveSheet()->getStyle('F')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1); 
		$objPHPExcel->getActiveSheet()->setCellValue('G1', 'ฉ8');	
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);	
		$objPHPExcel->getActiveSheet()->getStyle('G')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1); 
		$objPHPExcel->getActiveSheet()->setCellValue('H1', 'ไม่ทำเวช');	
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);	
		$objPHPExcel->getActiveSheet()->getStyle('H')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1); 
		$objPHPExcel->getActiveSheet()->setCellValue('I1', 'ออกหน่วย');	
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);	
		$objPHPExcel->getActiveSheet()->getStyle('I')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1); 
		$objPHPExcel->getActiveSheet()->setCellValue('J1', 'เงินค่าตอบแทนรายเดือน');	
		$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);	
		$objPHPExcel->getActiveSheet()->getStyle('J')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
		$objPHPExcel->getActiveSheet()->setCellValue('K1', 'เงินค่าตอบแทนเต็มขั้น');	
		$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);	
		$objPHPExcel->getActiveSheet()->getStyle('K')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
		$objPHPExcel->getActiveSheet()->setCellValue('L1', 'ค่าเดินทาง');	
		$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(20);	
		$objPHPExcel->getActiveSheet()->getStyle('L')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1); 
		$objPHPExcel->getActiveSheet()->setCellValue('M1', 'เงินพิเศษ');	
		$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(20);	
		$objPHPExcel->getActiveSheet()->getStyle('M')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
		$objPHPExcel->getActiveSheet()->setCellValue('N1', 'ตกเบิก/ครองชีพ');	
		$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(20);	
		$objPHPExcel->getActiveSheet()->getStyle('N')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);


		$sql0 = ' select cid, concat(pname,"",fname," ",lname) as name from n_datageneral ';
	    $sql0 .= ' where cid in ( select cid from s_salary_ocsc_detail group by cid )';
	    $sql0 .= ' order by q_pts asc';
	    $result0 = DB::select( $sql0 );

		$row = 0;
		
		foreach ($result0 as $key0) {			
			
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (0, $row+2, $key0->name);

			$sql  = ' select s.salary, s.r_c, s.r_other, s.r_pt, s.pts2, s.ot, s.ch8, s.no_v, s.outpcu, s.special_m, s.u_travel, s.game_sp';	 
		    $sql .= ' , (select positionName from n_position where position_id=(select ps.position_id from n_position_salary ps where ps.cid=n.cid order by ps.salaryID desc limit 1)) as position';
		    $sql .= ' from s_salary_ocsc_detail s';
		    $sql .= ' left join n_datageneral n on n.cid=s.cid';
		    $sql .= ' where year(s.order_date)='.$y.' and month(s.order_date)='.$m.' and n.cid='.$key0->cid.' ';
		    $sql .= ' order by n.datainfoID';	    

			$result = DB::select( $sql );

			foreach ($result as $key) {

				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (1, $row+2, $key->position);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (2, $row+2, $key->salary);	
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (3, $row+2, $key->r_c);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (4, $row+2, $key->pts2);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (5, $row+2, $key->ot);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (6, $row+2, $key->ch8);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (7, $row+2, $key->no_v);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (8, $row+2, $key->outpcu);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (9, $row+2, $key->special_m);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (10, $row+2, $key->r_pt);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (11, $row+2, $key->u_travel);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (12, $row+2, $key->r_other);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (13, $row+2, $key->game_sp);

			}// end foreach result
			
			$row++;			
		}//end foreach result0	

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5'); // Set excel version 2007	  		
	    $objWriter->save(storage_path()."/excel/reportSupportData_ocsc.xls");

	    return Response::download( storage_path()."/excel/reportSupportData_ocsc.xls", "reportSupportData_ocsc.xls");
	}





	/*
	*
	* function name  support_excel
	* รายละเอียดเงินค่าจ้าง Excel รายวัน
	*/
	public function support_excel_day()
	{
		if( Session::get('level') != '' )
		{
			$y = DB::Select( ' select year(order_date) as year1 from s_salary_detail group by year(order_date) order by year(order_date) desc ' );	
			return View::make( 'report.report6_day', array( 'data' => $y ) );
		}
		else
		{
			return View::make('login.index');
		}  
	}

	/*
	*
	* function name  support_excel_export
	* รายละเอียดเงินค่าจ้าง Excel Export รายวัน
	*/
	public function support_excel_day_export()
	{	
		$m 		= Input::get( 'm6' );	
		$y 		= Input::get( 'y6' );	
		
		$mm=0;
		$yy=0;
		if($m == 01){
			$mm = 12;
			$yy = $y-1;
		}else{
			$mm = $m-1;
			$yy = $y;
		}

		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial'); 
	    $objPHPExcel->getActiveSheet()->setTitle('Sheet1');
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'ชื่อ-นามสกุล');	
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(50);			 	
		$objPHPExcel->getActiveSheet()->setCellValue('B1', 'ตำแหน่ง');
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(50);	
		$objPHPExcel->getActiveSheet()->setCellValue('C1', 'ค่าจ้าง (บริการ)');
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);	
		$objPHPExcel->getActiveSheet()->getStyle('C')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1); 
		$objPHPExcel->getActiveSheet()->setCellValue('D1', 'ค่าจ้าง (สนับสนุน)');	
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);	
		$objPHPExcel->getActiveSheet()->getStyle('D')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1); 		
		
		$objPHPExcel->getActiveSheet()->setCellValue('E1', 'เงินประกันสังคม');	
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);	
		$objPHPExcel->getActiveSheet()->getStyle('E')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1); 
		
		$sql0 = ' select n.cid, concat(n.pname,"",n.fname," ",n.lname) as name from n_datageneral n';
		$sql0 .= ' where n.cid in ( select s.cid from s_salary_detail s where s.salary > 0 ';
		$sql0 .= '  and (';
		$sql0 .= '  ((select np1.level from n_position_salary np1 where np1.cid=s.cid order by np1.salaryID desc limit 1) = "ลูกจ้างรายวัน" ) ';
		$sql0 .= '  )';
		$sql0 .= '  group by s.cid )';
	    $sql0 .= ' order by n.q_pts';
		$result0 = DB::select( $sql0 );

		$row = 0;
		$sum1 = 0;
		$sum2 = 0;
		$sum3 = 0;
		foreach ($result0 as $key0) {			
			
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (0, $row+2, $key0->name);

			$sql  = ' select s.salary, s.salary_other';	 
		    $sql .= ' , (select positionName from n_position where position_id=(select ps.position_id from n_position_salary ps where ps.cid=n.cid order by ps.salaryID desc limit 1)) as position';
		    $sql .= ' , (select support_type from n_position where position_id=(select ps.position_id from n_position_salary ps where ps.cid=n.cid order by ps.salaryID desc limit 1)) as support_type';
		    $sql .= ' , s.salary_sso';
		    $sql .= ' from s_salary_detail s';
		    $sql .= ' left join n_datageneral n on n.cid=s.cid';
		    $sql .= ' where year(s.order_date)='.$y.' and month(s.order_date)='.$m.' and n.cid='.$key0->cid.' ';
			$sql .= ' order by n.datainfoID';
		
			$result = DB::select( $sql );

			foreach ($result as $key) {

				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (1, $row+2, $key->position);

				if( $key->support_type == 1 ){
					$type1 = $key->salary+$key->salary_other;
				}else {
					$type1="";
				}

				if( $key->support_type == 2 ){
					$type2 = $key->salary+$key->salary_other;
				} else {
					$type2="";
				}

				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (2, $row+2, $type1);	
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (3, $row+2, $type2);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (4, $row+2, $key->salary_sso);

				$sum1 += $type1;
				$sum2 += $type2;
				$sum3 += $key->salary_sso;
			}// end foreach result
			
			$row++;			
		}//end foreach result0	

		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (0, $row+3, 'รวม');	
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (2, $row+3, $sum1);	
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (3, $row+3, $sum2);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow (4, $row+3, $sum3);
	
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5'); // Set excel version 2007	  		
	    $objWriter->save(storage_path()."/excel/reportSupportDataDay.xls");

	    return Response::download( storage_path()."/excel/reportSupportDataDay.xls", "reportSupportDataDay.xls");
	}




}
