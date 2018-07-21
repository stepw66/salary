<?php

class HomeController extends BaseController {

	private function monthyearThai()
	{
		$thaiweek=array("วันอาทิตย์","วันจันทร์","วันอังคาร","วันพุธ","วันพฤหัส","วันศุกร์","วันเสาร์");

     	$thaimonth=array("มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","      มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");

     	//echo $thaiweek[date("w")] ,"ที่",date(" j "), $thaimonth[date(" m ")-1] , " พ.ศ. ",date(" Y ")+543;
     	// ผลลัพธ์จะได้ดังนี้ครับ วันเสาร์ที่ 26 กันยายน พ.ศ. 2552
     	return $thaimonth[date(" m ")-1].' '.( date(" Y ")+543 );
	}

	public function showHome()
	{
		if( Session::get('level') != '' )
		{		
			$max_month = date("m", strtotime( DB::table('s_salary_detail')->max('order_date') ));
			$max_year = date("Y", strtotime( DB::table('s_salary_detail')->max('order_date') ));

			$result1 = DB::select( ' select count(s.cid) as num,sum(s.salary) as salary  from s_salary_detail s join n_datageneral n on n.cid=s.cid where n.status=0 and  month(s.order_date)='.($max_month).' and year(s.order_date)='.($max_year).'  ' );
			$result2 = DB::select( ' select count(s.cid) as num,sum(s.salary) as salary  from s_salary_ocsc_detail s join n_datageneral n on n.cid=s.cid where n.status=0 and month(s.order_date)='.($max_month).' and year(s.order_date)='.($max_year).'  ' );			    
			$result5 = $this->monthyearThai();

			return View::make( 'home.index', array( 'result1'=>$result1, 'result2'=>$result2, 'result5'=>$result5 ) );
		}
		else
		{
			return View::make('login.index');
		}	
	}

}
