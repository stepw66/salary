<?php
  $urlcheck =  explode("/", Request::path());
  $urlpath = Request::path();
  $activeurl = $urlcheck[0];
?>

<dl id="menu-left-main" class="accordion off-canvas-list" data-accordion>

    <dd>
      <div class="title-menu">
        <label>เมนู</label>
      </div>
    </dd>

    <?php if( Session::get('c1') == 1 || Session::get('c4') == 1 ) { ?>
    <dd class="accordion-navigation">
      <a href="#panel1b" class="{{$activeurl == 'emptype5' ? 'active' : '';}}">ลูกจ้างรายวัน</a>
      <div id="panel1b" class="content {{$activeurl == 'emptype5' ? 'active' : '';}}  ">
          <ul class="side-nav">         
            <li><a href="{{ URL::to('emptype5/salary_insert') }}">- บันทึกเงินเดือน</a></li>  
            <li><a href="{{ URL::to('emptype5/salary') }}">- กรอกเงินเดือน</a></li>   
            <li><a href="{{ URL::to('emptype5/bank_acc') }}">- ข้อมูลบัญชีธนาคาร</a></li>                             
          </ul> 
      </div>
    </dd>
    <?php } ?>

    <?php if( Session::get('c1') == 1 || Session::get('c4') == 1 ) { ?>
    <dd class="accordion-navigation">
      <a href="#panel2b" class="{{$activeurl == 'emptype1' ? 'active' : '';}} {{$activeurl == 'emptype4' ? 'active' : '';}}">พกส.-ลูกจ้างชั่วคราว</a>
      <div id="panel2b" class="content {{$activeurl == 'emptype1' ? 'active' : '';}} {{$activeurl == 'emptype4' ? 'active' : '';}}">
        <ul class="side-nav"> 
          <!--<li><label>เพิ่มข้อมูลเงินเดือนอัตโนมัติ</label></li>
          <li><a href="{{ URL::to('emptype1/salary_auto') }}">- เพิ่มข้อมูล</a></li>-->
          <?php if( Session::get('c1') == 1 ) { ?>          
            <li><label>พกส.</label></li>
            <li><a href="{{ URL::to('emptype1/salary_insert') }}">- บันทึกเงินเดือน</a></li>  
            <li><a href="{{ URL::to('emptype1/salary') }}">- กรอกเงินเดือน</a></li>   
            <li><a href="{{ URL::to('emptype1/bank_acc') }}">- ข้อมูลบัญชีธนาคาร</a></li>                             
          <?php } ?>
          <li class="divider"></li>
          <?php if( Session::get('c4') == 1 ) { ?>
            <li><label>ลูกจ้างชั่วคราว</label></li> 
            <li><a href="{{ URL::to('emptype4/salary_insert') }}">- บันทึกเงินเดือน</a></li>
            <li><a href="{{ URL::to('emptype4/salary') }}">- กรอกเงินเดือน</a></li>   
            <li><a href="{{ URL::to('emptype4/bank_acc') }}">- ข้อมูลบัญชีธนาคาร</a></li>   
          <?php } ?>            
        </ul> 
      </div>
    </dd>
    <?php } ?>

    <?php if( Session::get('c2') == 1 || Session::get('c3') == 1 ) { ?>
    <dd class="accordion-navigation">
      <a href="#panel3b" class="{{$activeurl == 'emptype2' ? 'active' : '';}} {{$activeurl == 'emptype3' ? 'active' : '';}}">ลูกจ้างประจำ-ข้าราชการ</a>
      <div id="panel3b" class="content {{$activeurl == 'emptype2' ? 'active' : '';}} {{$activeurl == 'emptype3' ? 'active' : '';}}">
          <ul class="side-nav"> 
            <!--<li><label>เพิ่มข้อมูลเงินเดือนอัตโนมัติ</label></li>
            <li><a href="{{ URL::to('emptype2/salary_insert_auto') }}">- เพิ่มข้อมูล</a></li>-->
            <?php if( Session::get('c2') == 1 ) { ?>
              <li><label>ลูกจ้างประจำ</label></li>
              <li><a href="{{ URL::to('emptype2/salary_insert') }}">- บันทึกเงินเดือน</a></li>
              <li><a href="{{ URL::to('emptype2/salary') }}">- กรอกเงินเดือน</a></li> 
              <li><a href="{{ URL::to('emptype2/bank_acc') }}">- ข้อมูลบัญชีธนาคาร</a></li>                           
            <?php } ?>
            <li class="divider"></li>
            <?php if( Session::get('c3') == 1 ) { ?>  
              <li><label>ข้าราชการ</label></li> 
              <li><a href="{{ URL::to('emptype3/salary_insert') }}">- บันทึกเงินเดือน</a></li>
              <li><a href="{{ URL::to('emptype3/salary') }}">- กรอกเงินเดือน</a></li>
              <li><a href="{{ URL::to('emptype3/bank_acc') }}">- ข้อมูลบัญชีธนาคาร</a></li> 
            <?php } ?>                  
          </ul> 
      </div>
    </dd>
    <?php } ?>

    <?php if( Session::get('c6') == 1 ) { ?>
    <dd class="accordion-navigation">
      <a href="#panel9b" class="{{$activeurl == 'empsalaryAll' ? 'active' : '';}} ">เงินเดือนย้อนหลัง</a>
      <div id="panel9b" class="content {{$activeurl == 'empsalaryAll' ? 'active' : '';}} ">
          <ul class="side-nav"> 
            <li><a href="{{ URL::to('empsalaryAll') }}">- แก้ไขเงินเดือน</a></li>          
          </ul> 
      </div>
    </dd>
    <?php } ?>

    <?php if( Session::get('c1') == 1 || Session::get('c4') == 1 || Session::get('c2') == 1 || Session::get('c3') == 1 ) { ?>
    <dd class="accordion-navigation">
      <a href="#panel4b" class="{{$urlpath == 'special/add_special1' ? 'active' : '';}} {{$urlpath == 'special/add_water' ? 'active' : '';}} {{$urlpath == 'special/add_elec' ? 'active' : '';}} ">เงินรับอื่น ๆ-หักน้ำไฟ</a>
      <div id="panel4b" class="content  {{$urlpath == 'special/add_special1' ? 'active' : '';}} {{$urlpath == 'special/add_water' ? 'active' : '';}} {{$urlpath == 'special/add_elec' ? 'active' : '';}} ">
        <ul class="side-nav">                 
          <li><a href="{{ URL::to('special/add_special1') }}">- ลงค่าตอบแทน</a></li>
          <li><a href="{{ URL::to('special/add_water') }}">- ลงค่าน้ำ</a></li>
          <li><a href="{{ URL::to('special/add_elec') }}">- ลงค่าไฟ</a></li>                                                              
        </ul> 
      </div>
    </dd>
    <?php } ?>

    <?php if( (Session::get('c1') == 1) || (Session::get('c4') == 1) || (Session::get('c2') == 1) || (Session::get('c3') == 1) ) { ?>
    <dd class="accordion-navigation">
      <a href="#panel5b" class="{{$activeurl == 'tax1' ? 'active' : '';}} {{$activeurl == 'tax3' ? 'active' : '';}} {{$activeurl == 'tax2' ? 'active' : '';}}">รายการภาษี</a>
      <div id="panel5b" class="content  {{$activeurl == 'tax1' ? 'active' : '';}} {{$activeurl == 'tax3' ? 'active' : '';}} {{$activeurl == 'tax2' ? 'active' : '';}} ">
          
          <ul class="side-nav">         
            <?php if( (Session::get('c1') == 1) || (Session::get('c4') == 1) ) { ?>
              <li>
                <label>พกส./ลูกจ้างชั่วคราว</label>
              </li>           
              <li><a href="{{ URL::to('tax1/itpc_home1') }}" >- ภ.ง.ค.1 ก พกส./ลูกจ้างชั่วคราว</a></li> 
              <li><a href="{{ URL::to('tax1/recomend') }}">- หนังสือรับรองการหักภาษี</a></li>               
              <li><a href="{{ URL::to('tax1/continuous_home1') }}" >- พิมพ์หนังสือรับรอง</a></li> 
              <li><a href="{{ URL::to('tax1/sumsalary_tax1') }}">- ใบสรุปรายได้เพื่อหักภาษี พกส./ลูกจ้างชั่วคราว</a></li> 
              <li class="divider"></li>                      
            <?php } ?>
            <?php if( Session::get('c3') == 1 ) { ?>
              <li>
                <label>ข้าราชการ-ลูกจ้างประจำ</label>
              </li>         
              <li><a href="{{ URL::to('tax3/itpc_sp_home') }}">- พิมพ์ ภ.ง.ค.1 ก</a></li>                     
              <li><a href="{{ URL::to('tax3/continuous_sp_home') }}">- พิมพ์หนังสือรับรอง</a></li>  
              <!--<li class="divider"></li> 
              <li><a href="{{ URL::to('tax3/itpc_pts') }}" target="_blank">- พิมพ์ ภ.ง.ค.1 ก พตส</a></li>
              <li><a href="{{ URL::to('tax3/continuous_pts/null/null') }}" target="_blank">- พิมพ์หนังสือรับรอง พตส</a></li>-->  
              <li class="divider"></li>
              <li>
                <label>ข้าราชการ</label>
              </li>
              <!--<li><a href="{{ URL::to('tax3/itpc_home3') }}">- พิมพ์ ภ.ง.ค.1 ก ข้าราชการ</a></li>-->
              <li><a href="{{ URL::to('tax3/itpc_home3_pts') }}">- พิมพ์ ภ.ง.ค.1 ก ข้าราชการ (พตส)</a></li>
              <!--<li><a href="{{ URL::to('tax3/recomend') }}">- หนังสือรับรองการหักภาษี ข้าราชการ</a></li>                       
              <li><a href="{{ URL::to('tax3/continuous_home3') }}" >- พิมพ์หนังสือรับรอง ข้าราชการ</a></li>-->
              <li><a href="{{ URL::to('tax3/continuous_home3_pts') }}" >- พิมพ์หนังสือรับรอง ข้าราชการ (พตส)</a></li>
              <li><a href="{{ URL::to('tax3/sumsalarytax') }}">- ใบสรุปรายได้เพื่อหักภาษี ข้าราชการ</a></li>                  
              <li class="divider"></li>
              <label>พนักงานราชการ</label>
              <li><a href="{{ URL::to('tax4/itpc_home4') }}">- ภ.ง.ค.1 ก พนักงานราชการ</a></li>  
              <li><a href="{{ URL::to('tax4/continuous_home4') }}" >- พิมพ์หนังสือรับรอง พนักงานราชการ</a></li> 
              <li class="divider"></li>
            <?php } ?>
            <?php if( Session::get('c2') == 1 ) { ?>
              <li>
                <label>ลูกจ้างประจำ</label>
              </li>
              <li><a href="{{ URL::to('tax2/itpc_home2') }}">- ภ.ง.ค.1 ก ลูกจ้างประจำ</a></li>  
              <li><a href="{{ URL::to('tax2/recomend') }}">- หนังสือรับรองการหักภาษี ลูกจ้างประจำ</a></li>                
              <li><a href="{{ URL::to('tax2/continuous_home2') }}" >- พิมพ์หนังสือรับรอง ลูกจ้างประจำ</a></li>       
            <?php } ?>                
          </ul> 

      </div>
    </dd>
    <?php } ?>

    <dd class="accordion-navigation">
      <a href="#panel6b" class="{{$activeurl == 'report' ? 'active' : '';}}">รายงาน</a>
      <div id="panel6b" class="content  {{$activeurl == 'report' ? 'active' : '';}}">
          
        <ul class="side-nav">                     
          <?php if( (Session::get('c1') == 1) || (Session::get('c4') == 1) ) { ?>
          <li>
            <label>ลูกจ้างชั่วคราว/พกส.</label>
          </li>
          <li><a href="{{ URL::to('report/salary_sso_home') }}">- ส่งเงินสมทบ ส่งออก PDF</a></li>
          <li><a href="{{ URL::to('report/salary_sso_home_excel') }}">- ส่งเงินสมทบ ส่งออก Excel</a></li>              
          <li><a href="{{ URL::to('report/salary') }}" target="_blank">- รายงานสรุป-(ลูกจ้างชั่วคราว/พกส.(ปฏิบัติงาน))</a></li> 
          <li><a href="{{ URL::to('report/salary_emp') }}" target="_blank">- สลิป-(ลูกจ้างชั่วคราว/พกส.(ปฏิบัติงาน)) รวม</a></li>
          <li><a href="{{ URL::to('report/salary_emp_card') }}">- สลิป-(ลูกจ้างชั่วคราว/พกส.(ปฏิบัติงาน)) รายคน</a></li>
          <li><a href="{{ URL::to('report/salary_receivef1') }}" >- รายงานรับเงินเดือน (ลูกจ้างชั่วคราว/พกส)</a></li>         
          <li><a href="{{ URL::to('report/salary_excel_home') }}" >- ส่ง ธกส.(EXCEL) (ลูกจ้างชั่วคราว/พกส)</a></li> 
          <li><a href="{{ URL::to('report/salary_excel_2') }}" >- ส่งข้อมูลพี่ก้อย (EXCEL)</a></li>         
          <li><a href="{{ URL::to('report/special_excel') }}" >- รายงานค่าตอบแทน (EXCEL)</a></li> 
          <!--<li><a href="{{ URL::to('report/special_ot_excel') }}" >- รายงานค่า OT (EXCEL)</a></li>-->
          <li><a href="{{ URL::to('report/sp_sa_excel') }}" >- รายงานรวมเงินเดือนและค่าตอบแทน (EXCEL)</a></li>
          <li><a href="{{ URL::to('report/support_excel') }}" >- รายละเอียดเงินค่าจ้าง (EXCEL) (ลูกจ้างชั่วคราว/พกส)</a></li>
          <li><a href="{{ URL::to('report/salary_excel_pdf_home') }}" >- ส่ง ธกส.(PDF) (ลูกจ้างชั่วคราว/พกส)</a></li>    

          <li class="divider"></li>

          <li>
            <label>ลูกจ้างรายวัน</label>
          </li> 
          <li><a href="{{ URL::to('report/salary_receivef1_day') }}" >- รายงานรับเงินเดือน (ลูกจ้างรายวัน)</a></li>  
          <li><a href="{{ URL::to('report/salary_excel_home_day') }}" >- ส่ง ธกส.(EXCEL) (ลูกจ้างรายวัน)</a></li>  
          <li><a href="{{ URL::to('report/salary_excel_pdf_home_day') }}" >- ส่ง ธกส.(PDF) (ลูกจ้างรายวัน)</a></li>   
          <li><a href="{{ URL::to('report/support_excel_day') }}" >- รายละเอียดเงินค่าจ้าง (EXCEL) (ลูกจ้างรายวัน)</a></li>

          <?php } ?>

         <li class="divider"></li>
          
          <?php if( (Session::get('c2') == 1) || (Session::get('c3') == 1) ) { ?>
          <li>
            <label>ลูกจ้างประจำ/ข้าราชการ</label>
          </li>
          <li><a href="{{ URL::to('report/salary_ocsc') }}" target="_blank">- รายงานสรุป-(ลูกจ้างประจำ/ข้าราชการ)</a></li>  
          <li><a href="{{ URL::to('report/salary_emp_ocsc') }}" target="_blank">- สลิป-(ลูกจ้างประจำ/ข้าราชการ) รายคน</a></li> 
          <li><a href="{{ URL::to('report/salary_ocsc_receive') }}" target="_blank">- รายงานเซ็นรับเงินเดือน</a></li> 
          <li><a href="{{ URL::to('report/salary_ocsc_ktb') }}" target="_blank">- ส่ง กรุงไทย</a></li> 
          <li><a href="{{ URL::to('report/support_ocsc_excel') }}" >- รายละเอียดเงินค่าจ้าง (EXCEL)</a></li>
          <?php } ?>

        </ul> 

      </div>
    </dd>

    <?php if( Session::get('level') == 1 ) { ?>
    <dd class="accordion-navigation">
      <a href="#panel7b" class="{{$activeurl == 'user' ? 'active' : '';}} {{$activeurl == 'bank' ? 'active' : '';}} {{$activeurl == 'userdep' ? 'active' : '';}} {{$activeurl == 'usersort' ? 'active' : '';}} {{$activeurl == 'sortrepay' ? 'active' : '';}} {{$activeurl == 'upexcel' ? 'active' : '';}} {{$activeurl == 'upexcelnth' ? 'active' : '';}} {{$urlpath == 'special/unit_water' ? 'active' : '';}} {{$urlpath == 'special/add_meter' ? 'active' : '';}} {{$urlpath == 'special/add_emp_meter' ? 'active' : '';}} {{$urlpath == 'special/add_home' ? 'active' : '';}} {{$urlpath == 'special/add_emp_home' ? 'active' : '';}}">ข้อมูลพื้นฐาน</a>
      <div id="panel7b" class="content  {{$activeurl == 'user' ? 'active' : '';}} {{$activeurl == 'bank' ? 'active' : '';}} {{$activeurl == 'userdep' ? 'active' : '';}} {{$activeurl == 'usersort' ? 'active' : '';}} {{$activeurl == 'sortrepay' ? 'active' : '';}} {{$activeurl == 'upexcel' ? 'active' : '';}} {{$activeurl == 'upexcelnth' ? 'active' : '';}} {{$urlpath == 'special/unit_water' ? 'active' : '';}} {{$urlpath == 'special/add_meter' ? 'active' : '';}} {{$urlpath == 'special/add_emp_meter' ? 'active' : '';}} {{$urlpath == 'special/add_home' ? 'active' : '';}} {{$urlpath == 'special/add_emp_home' ? 'active' : '';}} ">
          <ul class="side-nav"> 
            <li><a href="{{ URL::to('user') }}">- ผู้ใช้งาน</a></li>  
            <li><a href="{{ URL::to('bank') }}">- ธนาคาร</a></li>   
            <li><a href="{{ URL::to('userdep') }}">- จัดฝ่ายเจ้าหน้าที่</a></li> 
            <li><a href="{{ URL::to('usersort') }}">- จัดการเรียงลำดับฝ่าย</a></li> 
            <li><a href="{{ URL::to('sortrepay') }}">- จัดการเรียงลำดับค่าตอบแทน</a></li> 
            <li><a href="{{ URL::to('upexcelnth') }}">- อัพไฟล์ข้าราชการพี่นก</a></li>
            <li><a href="{{ URL::to('upexcelnth2') }}">- อัพไฟล์ลูกจ้างประจำพี่นก</a></li>
            <li><a href="{{ URL::to('upexcelnth3') }}">- อัพไฟล์พนักงานราชการพี่นก</a></li>
            <li><a href="{{ URL::to('upexcelnth4') }}">- อัพไฟล์ข้อมูลรายได้ พตส. พี่นก</a></li>
            <li><a href="{{ URL::to('upexceltravel') }}">- อัพไฟล์ค่าใช้จ่ายเเดินทาง</a></li> 
            <li><a href="{{ URL::to('upexcel') }}">อัพไฟล์ข้อมูลเงินเดือนข้าราชการจาก สสจ.</a></li>
            <li><a href="{{ URL::to('special/unit_water') }}">- จัดการค่าน้ำหน่วยคิดค่าน้ำ</a></li>
            <li><a href="{{ URL::to('special/add_meter') }}">- จัดการค่าน้ำหมายเลขมิเตอร์น้ำ</a></li>
            <li><a href="{{ URL::to('special/add_emp_meter') }}">- จัดการค่าน้ำจับคู่คนกับมิเตอร์น้ำ</a></li>       
            <li><a href="{{ URL::to('special/add_home') }}">- จัดการค่าไฟบ้านพัก</a></li>
            <li><a href="{{ URL::to('special/add_emp_home') }}">- จัดการค่าไฟจับคู่คนกับบ้านพัก</a></li>    
            <li><a href="{{ URL::to('user/general_data') }}">- ข้อมูลทั่วไป</a></li>
          </ul> 
      </div>
    </dd>
    <?php } ?>  

    <?php if( Session::get('c5') == 1 ) { ?>
    <dd class="accordion-navigation">
      <a href="#panel8b" class="{{$activeurl == 'unitcosts' ? 'active' : '';}}">Unit Costs</a>
      <div id="panel8b" class="content {{$activeurl == 'unitcosts' ? 'active' : '';}} ">
        <ul class="side-nav"> 
          <li><a href="{{ URL::to('unitcosts') }}">- หน่วยต้นทุน</a></li>   
          <li><a href="{{ URL::to('unitcosts/add') }}">- จัดคนลงหน่วยต้นทุน</a></li> 
          <li><a href="{{ URL::to('unitcosts/range_ot_sso') }}">- กำหนดช่วง OT, ประกันสังคม</a></li>
          <li><a href="{{ URL::to('unitcosts/manager') }}">- ตรวจสอบข้อมูลในหน่วยต้นทุน</a></li> 
          <li><a href="{{ URL::to('unitcosts/money_home_month') }}">- LC รายเดือน</a></li>  
          <li><a href="{{ URL::to('unitcosts/money_home') }}">- LC ปันส่วน</a></li>
          <li><a href="{{ URL::to('unitcosts/money_home_lc') }}">- LC สมบูรณ์</a></li>          
        </ul> 
      </div>
    </dd>
    <?php } ?>  

     
    <div class="logout-main">
      <a href="{{ URL::to('logout') }}">ออกระบบ</a>
    </div>
    
    
</dl>