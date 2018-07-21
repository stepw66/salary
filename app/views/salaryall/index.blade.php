@extends('layouts.sidebar')
@section('content')

<ul class="breadcrumbs">
  <li><a href="{{ URL::to('home') }}">หน้าหลัก</a></li>
  <li class="unavailable"><a href="#">เงินเดือนย้อนหลัง</a></li>
  <li class="current"><a href="#">รายการ</a></li>
</ul>

<fieldset>

<div class="">
  <div class="">
    <h3>เลือกข้อมูลที่ต้องการแก้ไข</h3>
  </div>
</div>
<div class="row">
  <div class="large-6 columns">               
    <label>ชื่อเจ้าหน้าที่ :
        <input class="" name="empallget" id="empallget" type="text" placeholder="ค้นหาด้วยชื่อหรือนามสกุลหรือรหัสบัตรประชาชน">         
    </label>  
  </div>
</div>
<div class="row">   
  <div class="large-6 columns">   
    <input type="radio" name="empallgettype" value="new" id="salary_add_new"><label for="salary_add_new">เพิ่มรายการเงินเดือน</label>
    <input type="radio" name="empallgettype" value="edit" id="salary_edit_old"><label for="salary_edit_old">แก้ไขรายการเงินเดือน</label>
  </div>
</div> 

<div class="">
  <div class="large-5 columns">  
      <select id="salary_edit_old_m" name="m" class=""> 
        <option value="1">มกราคม</option>                     
          <option value="2">กุมภาพันธ์</option>
          <option value="3">มีนาคม</option> 
          <option value="4">เมษายน</option>                     
          <option value="5">พฤษภาคม</option>
          <option value="6">มิถุนายน</option>   
          <option value="7">กรกฎาคม</option>                      
          <option value="8">สิงหาคม</option>
          <option value="9">กันยายน</option>    
          <option value="10">ตุลาคม</option>                      
          <option value="11">พฤศจิกายน</option>
          <option value="12">ธันวาคม</option>     
    </select>  
  </div>
  <div class="large-5 columns">   
     <select id="salary_edit_old_y" name="y" class=""> 
          @foreach( $data as $a )              
           <option  value="{{ $a->year1 }}">{{ $a->year1 }}</option>        
          @endforeach
        </select>  
  </div>
  <div class="large-2 columns">
    {{ Form::button( 'แก้ไข', array( 'class'=>'small button', 'id' => 'btnsalaryedit' ) ) }} 
  </div>
</div> 

<div class="">
  <h4>
    <div id="h-empallgettype"></div>
  </h4>
</div>

<div id="f-empallgettype">
  <div class="">
    <div class="large-12 columns">
    
        <!--  ==================== พกส ชั้วคราว ===========================  -->
        <div id="t-emp1">

        {{ Form::open(array('id'=>'form_salary_add_new')) }}

        <input type="hidden" id="cidemp" />
        <input type="hidden" id="typeaction" />

        <div class="">
          <div class="large-3 columns">
            <label>เลขที่บัญชี:
              <input type="text" id="bank_acc" placeholder="เลขที่บัญชี">
            </label>
          </div>
          <div class="large-3 columns">
            <label>เงินเดือน:
              <input type="text" id="salary" placeholder="เงินเดือน">
            </label>
          </div>
          <div class="large-3 columns">
            <label>เงินพิเศษ:
              <input type="text" id="salary_other" placeholder="เงินพิเศษ">
            </label>
          </div>
          <div class="large-3 columns">
            <label>ประกันสังคม:
              <input type="text" id="salary_sso" placeholder="ประกันสังคม">
            </label>
          </div>
        </div>
        <div class="">
          <div class="large-3 columns">
            <label>ค่าน้ำ:
              <input type="text" id="water" placeholder="ค่าน้ำ">
            </label>
          </div>
          <div class="large-3 columns">
            <label>ค่าไฟ:
              <input type="text" id="elec" placeholder="ค่าไฟ">
            </label>
          </div>
          <div class="large-3 columns">
            <label>เงินเดือนวันที่:(ป-ด-ว) 2015-01-31
              <input type="text" id="order_date" placeholder="ป-ด-ว">
            </label>
          </div>
          <div class="large-3 columns">
            <label>พตส.:
              <input type="text"  id="pts" placeholder="พตส.">
            </label>
          </div>
        </div>
        <div class="">
          <div class="large-3 columns">
            <label>OT:
              <input type="text" id="ot" placeholder="OT">
            </label>
          </div>
          <div class="large-3 columns">
            <label>ฉ8:
              <input type="text" id="ch8" placeholder="ฉ8">
            </label>
          </div>
          <div class="large-3 columns">
            <label>ออกหน่วย:
              <input type="text" id="outpcu" placeholder="ออกหน่วย">
            </label>
          </div>
          <div class="large-3 columns">
            <label>เดินทาง:
              <input type="text" id="u_travel" placeholder="เดินทาง">
            </label>
          </div>
        </div>
        <div class="">
           <div class="large-3 columns">
            <label>สหกรณ์ออมทรัพย์:
              <input type="text" id="cprt" placeholder="หักสหกรณ์">
            </label>
          </div>
          <div class="large-3 columns"></div>
          <div class="large-3 columns"></div>
          <div class="large-3 columns"></div>
        </div>

        <div class="">
          <div class="small-3 columns">
            {{ Form::button( 'บันทึก', array( 'class'=>'small button', 'id' => 'btnsalarysave' ) ) }}    
          </div>
        </div>

        {{ Form::close() }}

      </div> <!-- end t-emp1 -->

      <!--  ==================== ข้าราชการ ลูกจ้างประจำ ===========================  -->

      <div id="t-emp2">

        {{ Form::open(array('id'=>'form_salary_add_new')) }}
        <input type="hidden" id="cidemp" />
        <input type="hidden" id="typeaction" />
        
        <div class="">
          <div class="large-3 columns">
            <label>เลขที่บัญชี:
              <input type="text" id="bank_acc2" placeholder="เลขที่บัญชี">
            </label>
          </div>
          <div class="large-3 columns">
            <label>เงินเดือน:
              <input type="text" id="salary2" placeholder="เงินเดือน">
            </label>
          </div>
          <div class="large-3 columns">
            <label>เงินตำแหน่ง:
              <input type="text" id="r_c2" placeholder="เงินตำแหน่ง">
            </label>
          </div>
          <div class="large-3 columns">
            <label>เงินพิเศษ:
              <input type="text" id="r_other2" placeholder="เงินพิเศษ">
            </label>
          </div>
        </div>
        <div class="">
          <div class="large-3 columns">
            <label>ค่าน้ำ:
              <input type="text" id="water2" placeholder="ค่าน้ำ">
            </label>
          </div>
          <div class="large-3 columns">
            <label>ค่าไฟ:
              <input type="text" id="elec2" placeholder="ค่าไฟ">
            </label>
          </div>
          <div class="large-3 columns">
            <label>เงินเดือนวันที่:(ป-ด-ว) 2015-01-31
              <input type="text" id="order_date2" placeholder="ป-ด-ว">
            </label>
          </div>
          <div class="large-3 columns">
            <label>พตส.:
              <input type="text"  id="pts2" placeholder="พตส.">
            </label>
          </div>
        </div>
        <div class="">
          <div class="large-3 columns">
            <label>OT:
              <input type="text" id="ot2" placeholder="OT">
            </label>
          </div>
          <div class="large-3 columns">
            <label>ฉ8:
              <input type="text" id="ch82" placeholder="ฉ8">
            </label>
          </div>
          <div class="large-3 columns">
            <label>ไม่ทำเวช:
              <input type="text" id="no_v2" placeholder="ไม่ทำเวช">
            </label>
          </div>
          <div class="large-3 columns">
            <label>ออกหน่วย:
              <input type="text" id="outpcu2" placeholder="ออกหน่วย">
            </label>
          </div>
        </div>
        <div class="">
          <div class="large-3 columns">
            <label>พิเศษรายเดือน:
              <input type="text" id="special_m2" placeholder="พิเศษรายเดือน">
            </label>
          </div>
          <div class="large-3 columns">
            <label>เดินทาง:
              <input type="text" id="u_travel2" placeholder="เดินทาง">
            </label>
          </div>
          <div class="large-6 columns">
            <label>ตกเบิก+ค่าครองชีพ:
              <input type="text" id="game_sp" placeholder="ตกเบิก+ค่าครองชีพ">
            </label>
          </div>
        </div>

         <div class="">
          <div class="small-3 small-centered columns">
            {{ Form::button( 'บันทึก', array( 'class'=>'small button', 'id' => 'btnsalarysave_ocsc' ) ) }}    
          </div>
        </div>

        {{ Form::close() }}
       
      </div><!-- end t-emp2 -->


      

    </div>
  </div>
</div>

</fieldset>


@stop
