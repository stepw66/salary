@extends('layouts.sidebar')
@section('content')

<ul class="breadcrumbs">
  <li><a href="{{ URL::to('home') }}">หน้าหลัก</a></li>
  <li class="unavailable"><a href="#">ข้อมูลพื้นฐาน</a></li>
  <li class="current"><a href="#">จัดการเรียงลำดับค่าตอบแทน</a></li>
</ul>

<fieldset>
  <h2>จัดการเรียงลำดับค่าตอบแทน</h2> 
  <div class="">
    <div class="medium-12 columns">
          
          <div class="">   
            <div class="large-12 columns">             
              {{ Form::label( 'level', 'ชื่อค่าตอบแทน-ค่าใช้จ่าย :', array( 'class' => '' ) ) }}   
                <select id="paylist" name="paylist" class=""> 
                  <option value="0">*------ กรุณาเลือก ------*</option>                                              
                  <option value="1">ค่า พตส.เงินนอกงบประมาณ</option>           
                  <option value="2">ค่า OT</option>
                  <option value="3">ค่า ฉ 8</option>
                  <option value="4">ค่า ไม่ทำเวช</option>
                  <option value="5">ค่า ออกหน่วย</option>
                  <option value="6">ค่า พตส.เงินงบประมาณ</option>
                  <option value="7">ค่า ฉ 11 เงินนอกงบประมาณ</option>
                  <option value="8">ค่า ฉ 11 เงินงบประมาณ</option>
              </select>             
            </div>                   
          </div>         

          <h4>รายชื่อเจ้าหน้าที่ <span id="name-has-show"></span></h4>
          <hr />
          <div id="listname-has">
          - ไม่มี
          </div>  
    </div>
  </div>	
</fieldset>


@stop