@extends('layouts.sidebar')
@section('content')

<ul class="breadcrumbs">
  <li><a href="{{ URL::to('home') }}">หน้าหลัก</a></li>
  <li class="unavailable"><a href="#">ข้อมูลพื้นฐาน</a></li>
  <li class="current"><a href="#">จัดฝ่ายเจ้าหน้าที่</a></li>
</ul>

<fieldset>
  <h2>จัดฝ่ายเจ้าหน้าที่</h2> 
  <div class="">
    <div class="medium-12 columns">
          
          <div class="">   
            <div class="large-12 columns">             
              {{ Form::label( 'level', 'ชื่อฝ่าย :', array( 'class' => '' ) ) }}   
                <select id="dep" name="dep" class=""> 
                  <option value="0">*------ กรุณาเลือก ------*</option>                     
                     @foreach( $dep as $d )         
                    <option value="{{ $d->department_id }}">{{ $d->departmentName }}</option>           
                  @endforeach
              </select>             
            </div>                   
          </div>
          <div class="">
            <div class="large-12 columns">               
              <label>ชื่อเจ้าหน้าที่ :
                  <input class="" name="empdep" id="empdep" type="text" placeholder="ค้นหาด้วยชื่อหรือนามสกุลหรือรหัสบัตรประชาชน">         
              </label>  
            </div>
          </div>
          <div class="">   
            <div class="large-12 columns">
               <a class="small button" href="#" id="add-emp" >เพิ่มรายชื่อ</a> 
            </div>
          </div>  

          <h4>รายชื่อเจ้าหน้าที่ <span id="name-emp-show"></span></h4>
          <hr />
          <div id="listname-emp">
          - ไม่มี
          </div>  
    </div>
  </div>	
</fieldset>


@stop