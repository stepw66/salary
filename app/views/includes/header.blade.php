<?php
	$urlcheck =  explode("/", Request::path());
	$urlpath = Request::path();
	$activeurl = $urlcheck[0];
?>
<div class="fixed">
<div class="name-system">SALARY SYSTEMS.</div>
<div class="top-line"></div>
<nav class="top-bar" data-topbar role="navigation"  data-options="sticky_on: large"> 
<ul class="title-area"> 
	<li class="name"/>
	<!-- Remove the class "menu-icon" to get rid of menu icon. Take out "Menu" to just have icon alone --> 
	<li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li> 
</ul> 
<section class="top-bar-section"> 
	<!-- Right Nav Section --> 
	<ul class="left"> 
		<li class="{{$activeurl == 'home' ? 'active' : '';}}"><a href="{{ URL::to('home') }}">หน้าหลัก</a></li> 

		<?php if( Session::get('c1') == 1 || Session::get('c4') == 1 ) { ?>
		<li class="has-dropdown  {{$activeurl == 'emptype5' ? 'active' : '';}}  "> <a href="#">ลูกจ้างรายวัน</a> 
			<ul class="dropdown"> 				
				<li><label>รายวัน.</label></li>
				<li><a href="{{ URL::to('emptype5/salary_insert') }}">กรอกเงินเดือน</a></li>	
				<li><a href="{{ URL::to('emptype5/salary') }}">ข้อมูลเงินเดือน</a></li> 	
				<li><a href="{{ URL::to('emptype5/bank_acc') }}">ข้อมูลบัญชีธนาคาร</a></li>															
			</ul> 
		</li>
		<?php } ?>

		<?php if( Session::get('c1') == 1 || Session::get('c4') == 1 ) { ?>
		<li class="has-dropdown  {{$activeurl == 'emptype1' ? 'active' : '';}} {{$activeurl == 'emptype4' ? 'active' : '';}} "> <a href="#">พกส.-ลูกจ้างชั่วคราว</a> 
			<ul class="dropdown"> 
				<li><label>เพิ่มข้อมูลเงินเดือนอัตโนมัติ</label></li>
				<!--<li><a href="{{ URL::to('emptype1/salary_auto') }}">เพิ่มข้อมูล</a></li>-->
				<?php if( Session::get('c1') == 1 ) { ?>					
					<li><label>พกส.</label></li>
					<li><a href="{{ URL::to('emptype1/salary_insert') }}">กรอกเงินเดือน</a></li>	
					<li><a href="{{ URL::to('emptype1/salary') }}">ข้อมูลเงินเดือน</a></li> 	
					<li><a href="{{ URL::to('emptype1/bank_acc') }}">ข้อมูลบัญชีธนาคาร</a></li>															
				<?php } ?>
				<li class="divider"/>
				<?php if( Session::get('c4') == 1 ) { ?>
					<li><label>ลูกจ้างชั่วคราว</label></li>	
					<li><a href="{{ URL::to('emptype4/salary_insert') }}">กรอกเงินเดือน</a></li>
					<li><a href="{{ URL::to('emptype4/salary') }}">ข้อมูลเงินเดือน</a></li> 	
					<li><a href="{{ URL::to('emptype4/bank_acc') }}">ข้อมูลบัญชีธนาคาร</a></li>		
				<?php } ?>						
			</ul> 
		</li>
		<?php } ?>

		<?php if( Session::get('c2') == 1 || Session::get('c3') == 1 ) { ?>
		<li class="has-dropdown {{$activeurl == 'emptype2' ? 'active' : '';}} {{$activeurl == 'emptype3' ? 'active' : '';}} "> <a href="#">ลูกจ้างประจำ-ข้าราชการ</a> 
			<ul class="dropdown"> 
				<li><label>เพิ่มข้อมูลเงินเดือนอัตโนมัติ</label></li>
				<li><a href="{{ URL::to('emptype2/salary_insert_auto') }}">เพิ่มข้อมูล</a></li>
				<?php if( Session::get('c2') == 1 ) { ?>
					<li><label>ลูกจ้างประจำ</label></li>
					<li><a href="{{ URL::to('emptype2/salary_insert') }}">กรอกเงินเดือน</a></li>
					<li><a href="{{ URL::to('emptype2/salary') }}">ข้อมูลเงินเดือน</a></li> 
					<li><a href="{{ URL::to('emptype2/bank_acc') }}">ข้อมูลบัญชีธนาคาร</a></li>														
				<?php } ?>
				<li class="divider"/>
				<?php if( Session::get('c3') == 1 ) { ?>	
					<li><label>ข้าราชการ</label></li>	
					<li><a href="{{ URL::to('emptype3/salary_insert') }}">กรอกเงินเดือน</a></li>
					<li><a href="{{ URL::to('emptype3/salary') }}">ข้อมูลเงินเดือน</a></li>
					<li><a href="{{ URL::to('emptype3/bank_acc') }}">ข้อมูลบัญชีธนาคาร</a></li>	
				<?php } ?>				 					
			</ul> 
		</li>
		<?php } ?>

		<?php if( Session::get('c1') == 1 || Session::get('c4') == 1 || Session::get('c2') == 1 || Session::get('c3') == 1 ) { ?>
		<li class="has-dropdown {{$urlpath == 'special/add_special1' ? 'active' : '';}} {{$urlpath == 'special/add_water' ? 'active' : '';}} {{$urlpath == 'special/add_elec' ? 'active' : '';}} "> <a href="#">เงินรับอื่น ๆ-หักน้ำไฟ</a> 
			<ul class="dropdown"> 								
				<li><a href="{{ URL::to('special/add_special1') }}">ลงค่าตอบแทน</a></li>
				<li><a href="{{ URL::to('special/add_water') }}">ลงค่าน้ำ</a></li>
				<li><a href="{{ URL::to('special/add_elec') }}">ลงค่าไฟ</a></li>																										 					
			</ul> 
		</li>
		<?php } ?>

		<?php if( (Session::get('c1') == 1) || (Session::get('c4') == 1) || (Session::get('c2') == 1) || (Session::get('c3') == 1) ) { ?>
		<li class="has-dropdown {{$activeurl == 'tax1' ? 'active' : '';}} {{$activeurl == 'tax3' ? 'active' : '';}} {{$activeurl == 'tax2' ? 'active' : '';}} "> <a href="#">รายการภาษี</a> 
			<ul class="dropdown"> 				
				<?php if( (Session::get('c1') == 1) || (Session::get('c4') == 1) ) { ?>
					<li>
						<label>พกส./ลูกจ้างชั่วคราว</label>
					</li>						
					<li><a href="{{ URL::to('tax1/itpc_home1') }}" >ภ.ง.ค.1 ก พกส./ลูกจ้างชั่วคราว</a></li>	
					<li class="has-dropdown">
						<a href="#">หนังสือรับรองการหักภาษี</a>
						<ul class="dropdown"> 
							<li><label>หนังสือรับรอง</label></li>					
							<li><a href="{{ URL::to('tax1/recomend') }}">หนังสือรับรองการหักภาษี</a></li>								
							<li><a href="{{ URL::to('tax1/continuous_home1') }}" >พิมพ์หนังสือรับรอง</a></li>	
						</ul>
					</li>	
					<li class="divider"/>													
				<?php } ?>
				<?php if( Session::get('c3') == 1 ) { ?>
					<li>
						<label>ภาษีค่าตอบแทน ข้าราชการ-ลูกจ้างประจำ</label>
					</li>					
					<li class="has-dropdown">
						<a href="#">ภาษีค่าตอบแทน</a>
						<ul class="dropdown"> 
							<li><label>ภาษีค่าตอบแทน</label></li>	
							<li><a href="{{ URL::to('tax3/itpc_sp_home') }}">พิมพ์ ภ.ง.ค.1 ก</a></li>											
							<li><a href="{{ URL::to('tax3/continuous_sp_home') }}">พิมพ์หนังสือรับรอง</a></li>	
							<li class="divider"/>	
							<li><label>ภาษีค่า พตส</label></li>	
							<li><a href="{{ URL::to('tax3/itpc_pts') }}" target="_blank">พิมพ์ ภ.ง.ค.1 ก</a></li>
							<li><a href="{{ URL::to('tax3/continuous_pts/null/null') }}" target="_blank">พิมพ์หนังสือรับรอง</a></li>	
						</ul>
					</li>
					<li class="divider"/>	
					<li>
						<label>ข้าราชการ</label>
					</li>
					<li class="has-dropdown">
						<a href="#">ภ.ง.ค.1 ก ข้าราชการ</a>
						<ul class="dropdown"> 
							<li><label>ภ.ง.ค.1 ก</label></li>	
							<li><a href="{{ URL::to('tax3/itpc_home3') }}">พิมพ์ ภ.ง.ค.1 ก ข้าราชการ</a></li>
							<li><a href="{{ URL::to('tax3/itpc_home3_pts') }}">พิมพ์ ภ.ง.ค.1 ก ข้าราชการ (พตส)</a></li>
						</ul>
					</li>		
					<li class="has-dropdown">
						<a href="#">หนังสือรับรองการหักภาษี</a>
						<ul class="dropdown"> 
							<li><label>หนังสือรับรอง</label></li>					
							<li><a href="{{ URL::to('tax3/recomend') }}">หนังสือรับรองการหักภาษี ข้าราชการ</a></li>												
							<li><a href="{{ URL::to('tax3/continuous_home3') }}" >พิมพ์หนังสือรับรอง ข้าราชการ</a></li>
							<li><a href="{{ URL::to('tax3/continuous_home3_pts') }}" >พิมพ์หนังสือรับรอง ข้าราชการ (พตส)</a></li>
						</ul>
					</li>	
					<li><a href="{{ URL::to('tax3/sumsalarytax') }}">ใบสรุปรายได้เพื่อหักภาษี ข้าราชการ</a></li>									
					<li class="divider"/>
				<?php } ?>
				<?php if( Session::get('c2') == 1 ) { ?>
					<li>
						<label>ลูกจ้างประจำ</label>
					</li>
					<li><a href="{{ URL::to('tax2/itpc_home2') }}">ภ.ง.ค.1 ก ลูกจ้างประจำ</a></li>	
					<li class="has-dropdown">
						<a href="#">หนังสือรับรองการหักภาษี</a>
						<ul class="dropdown"> 
							<li><label>หนังสือรับรอง</label></li>					
							<li><a href="{{ URL::to('tax2/recomend') }}">หนังสือรับรองการหักภาษี ลูกจ้างประจำ</a></li>								
							<li><a href="{{ URL::to('tax2/continuous_home2') }}" >พิมพ์หนังสือรับรอง ลูกจ้างประจำ</a></li>
						</ul>
					</li>						
				<?php } ?>								
			</ul> 
		</li>
		<?php } ?>	

		<li class="has-dropdown"> <a href="#">รายงาน</a> 
			<ul class="dropdown"> 										
				<?php if( (Session::get('c1') == 1) || (Session::get('c4') == 1) ) { ?>
				<li>
					<label>ลูกจ้างชั่วคราว/พกส.</label>
				</li>
				<li class="has-dropdown">
					<a href="#">แบบรายงานการแสดงการส่งเงินสมทบ</a>
					<ul class="dropdown"> 
						<li><a href="{{ URL::to('report/salary_sso_home') }}">ส่งออก PDF</a></li>
						<li><a href="{{ URL::to('report/salary_sso_home_excel') }}">ส่งออก Excel</a></li>
					</ul>
				</li>								
				<li><a href="{{ URL::to('report/salary') }}" target="_blank">รายงานสรุป-(ลูกจ้างชั่วคราว/พกส.(ปฏิบัติงาน))</a></li> 
				<li><a href="{{ URL::to('report/salary_emp') }}" target="_blank">สลิป-(ลูกจ้างชั่วคราว/พกส.(ปฏิบัติงาน)) รายคน</a></li>
				<li><a href="{{ URL::to('report/salary_receivef1') }}" >รายงานรับเงินเดือน</a></li> 				
				<li class="has-dropdown">
					<a href="#">รายงาน Excel</a>
					<ul class="dropdown"> 
						<li><label>Excel</label></li>	
						<li><a href="{{ URL::to('report/salary_excel_home') }}" >ส่ง ธกส.(EXCEL)</a></li> 
						<li><a href="{{ URL::to('report/salary_excel_2') }}" >ส่งข้อมูลพี่ก้อย (EXCEL)</a></li> 				
						<li><a href="{{ URL::to('report/special_excel') }}" >รายงานค่าตอบแทน (EXCEL)</a></li> 
						<li><a href="{{ URL::to('report/special_ot_excel') }}" >รายงานค่า OT (EXCEL)</a></li>
						<li><a href="{{ URL::to('report/sp_sa_excel') }}" >รายงานรวมเงินเดือนและค่าตอบแทน (EXCEL)</a></li>
						<li><a href="{{ URL::to('report/support_excel') }}" >รายละเอียดเงินค่าจ้าง (EXCEL)</a></li>
					</ul>
				</li>
				<li><a href="{{ URL::to('report/salary_excel_pdf_home') }}" >ส่ง ธกส.(PDF)</a></li> 		
				<?php } ?>

				<li class="divider"/>
				
				<?php if( (Session::get('c2') == 1) || (Session::get('c3') == 1) ) { ?>
				<li>
					<label>ลูกจ้างประจำ/ข้าราชการ</label>
				</li>
				<li><a href="{{ URL::to('report/salary_ocsc') }}" target="_blank">รายงานสรุป-(ลูกจ้างประจำ/ข้าราชการ)</a></li> 	
				<li><a href="{{ URL::to('report/salary_emp_ocsc') }}" target="_blank">สลิป-(ลูกจ้างประจำ/ข้าราชการ) รายคน</a></li> 
				<li><a href="{{ URL::to('report/salary_ocsc_receive') }}" target="_blank">รายงานเซ็นรับเงินเดือน</a></li> 
				<li><a href="{{ URL::to('report/salary_ocsc_ktb') }}" target="_blank">ส่ง กรุงไทย</a></li> 
				<?php } ?>

			</ul> 
		</li>

		<?php if( Session::get('level') == 1 ) { ?>
			<li class="has-dropdown {{$activeurl == 'user' ? 'active' : '';}} {{$activeurl == 'bank' ? 'active' : '';}} {{$activeurl == 'userdep' ? 'active' : '';}} {{$activeurl == 'usersort' ? 'active' : '';}} {{$activeurl == 'sortrepay' ? 'active' : '';}} {{$activeurl == 'upexcel' ? 'active' : '';}} {{$activeurl == 'upexcelnth' ? 'active' : '';}} {{$urlpath == 'special/unit_water' ? 'active' : '';}} {{$urlpath == 'special/add_meter' ? 'active' : '';}} {{$urlpath == 'special/add_emp_meter' ? 'active' : '';}} {{$urlpath == 'special/add_home' ? 'active' : '';}} {{$urlpath == 'special/add_emp_home' ? 'active' : '';}} "> <a href="#">ข้อมูลพื้นฐาน</a> 
				<ul class="dropdown"> 
					<li><a href="{{ URL::to('user') }}">ผู้ใช้งาน</a></li> 	
					<li><a href="{{ URL::to('bank') }}">ธนาคาร</a></li> 	
					<li><a href="{{ URL::to('userdep') }}">จัดฝ่ายเจ้าหน้าที่</a></li> 
					<li><a href="{{ URL::to('usersort') }}">จัดการเรียงลำดับฝ่าย</a></li> 
					<li><a href="{{ URL::to('sortrepay') }}">จัดการเรียงลำดับค่าตอบแทน</a></li> 
					<li class="has-dropdown">
						<a href="#">อัพข้อมูล</a>
						<ul class="dropdown"> 
							<li><label>อัพข้อมูล</label></li>					
							<li><a href="{{ URL::to('upexcelnth') }}">อัพไฟล์ข้าราชการพี่นก</a></li>
							<li><a href="{{ URL::to('upexcelnth2') }}">อัพไฟล์ลูกจ้างประจำพี่นก</a></li>
							<li><a href="{{ URL::to('upexcelnth3') }}">อัพไฟล์พนักงานราชการพี่นก</a></li>
							<li><a href="{{ URL::to('upexcelnth4') }}">อัพไฟล์ข้อมูลรายได้ พตส. พี่นก</a></li>
							<li><a href="{{ URL::to('upexceltravel') }}">ค่าใช้จ่ายเเดินทาง</a></li>														
						</ul>
					</li>	
					<li class="has-dropdown">
						<a href="#">จัดการค่าน้ำ</a>
						<ul class="dropdown"> 
							<li><label>จัดการค่าน้ำ</label></li>
							<li><a href="{{ URL::to('special/unit_water') }}">หน่วยคิดค่าน้ำ</a></li>
							<li><a href="{{ URL::to('special/add_meter') }}">หมายเลขมิเตอร์น้ำ</a></li>
							<li><a href="{{ URL::to('special/add_emp_meter') }}">จับคู่คนกับมิเตอร์น้ำ</a></li>														
						</ul>
					</li>
					<li class="has-dropdown">
						<a href="#">จัดการค่าไฟ</a>
						<ul class="dropdown"> 
							<li><label>จัดการค่าไฟ</label></li>					
							<li><a href="{{ URL::to('special/add_home') }}">บ้านพัก</a></li>
							<li><a href="{{ URL::to('special/add_emp_home') }}">จับคู่คนกับบ้านพัก</a></li>														
						</ul>
					</li>			
					<li><a href="{{ URL::to('user/general_data') }}">ข้อมูลทั่วไป</a></li>
					<!--<li><a href="{{ URL::to('upexcel') }}">อัพไฟล์ข้อมูลเงินเดือนข้าราชการจาก สสจ.</a></li>-->
				</ul> 
			</li>
		<?php } ?>		

		<?php if( Session::get('c5') == 1 ) { ?>
			<li class="has-dropdown {{$activeurl == 'unitcosts' ? 'active' : '';}} "> <a href="#">Unit Costs</a> 
				<ul class="dropdown"> 
					<li><a href="{{ URL::to('unitcosts') }}">หน่วยต้นทุน</a></li> 	
					<li><a href="{{ URL::to('unitcosts/add') }}">จัดคนลงหน่วยต้นทุน</a></li> 
                    <li><a href="{{ URL::to('unitcosts/range_ot_sso') }}">กำหนดช่วง OT, ประกันสังคม</a></li>
					<li><a href="{{ URL::to('unitcosts/manager') }}">ตรวจสอบข้อมูลในหน่วยต้นทุน</a></li> 
					<li><a href="{{ URL::to('unitcosts/money_home_month') }}">LC รายเดือน</a></li>	
					<li><a href="{{ URL::to('unitcosts/money_home') }}">LC ปันส่วน</a></li>
					<li><a href="{{ URL::to('unitcosts/money_home_lc') }}">LC สมบูรณ์</a></li>					
				</ul> 
			</li>
		<?php } ?>		

		<li><a href="{{ URL::to('logout') }}">ออกจากระบบ</a></li> 
	</ul> 
</section> 
</nav>
</div>
