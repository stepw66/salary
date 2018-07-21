@extends('layouts.sidebar')
@section('content')

<ul class="breadcrumbs">
  <li><a href="{{ URL::to('home') }}">หน้าหลัก</a></li>
  <li class="unavailable"><a href="#">เงินรับอื่น ๆ - หักน้ำไฟ</a></li>
  <li class="current"><a href="#">ค่าน้ำ</a></li>
</ul>

<fieldset>

  <h2>จับคู่คนกับมิเตอร์น้ำ</h2>  

  <div class="row">
    <div class="large-6 columns">             
      {{ Form::label( 'level', 'มิเตอร์ :', array( 'class' => '' ) ) }}   
      <select id="meter_s" name="meter_s" class=""> 
            <option value="0">*------ กรุณาเลือก ------*</option>                     
             @foreach( $meter as $a )         
            <option value="{{ $a->meter_id }}">{{ $a->name_meter }}</option>           
            @endforeach
      </select>             
    </div>
  </div>
  <div class="row">
    <div class="large-6 columns">    
      <label>ชื่อเจ้าหน้าที่ :
          <input class="" name="empmeter_s" id="empmeter_s" type="text" placeholder="ค้นหาด้วยชื่อหรือนามสกุลหรือรหัสบัตรประชาชน">         
      </label>  
    </div>
  </div>
  <hr />
  <div class="row">
    <div class="large-12 columns">
       <a href="#" id="addempmeter" class="button  [tiny small large]">บันทึก</a>
    </div>
  </div>

  <div id="view-empmeter">
    
  </div>

</fieldset>
@stop
