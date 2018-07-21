@extends('layouts.sidebar')
@section('content')

<ul class="breadcrumbs">
  <li><a href="{{ URL::to('home') }}">หน้าหลัก</a></li>
  <li class="unavailable"><a href="#">เงินรับอื่น ๆ - หักน้ำไฟ</a></li>
  <li class="current"><a href="#">ค่าไฟ</a></li>
</ul>

<fieldset>

  <h2>จับคู่คนกับบ้านพัก</h2>  

  <div class="row">
    <div class="large-6 columns">             
      {{ Form::label( 'level', 'บ้านพัก :', array( 'class' => '' ) ) }}   
      <select id="home_s" name="home_s" class=""> 
            <option value="0">*------ กรุณาเลือก ------*</option>                     
             @foreach( $home as $a )         
            <option value="{{ $a->home_id }}">{{ $a->name }}</option>           
            @endforeach
      </select>             
    </div>
  </div>
  <div class="row">
    <div class="large-6 columns">    
      <label>ชื่อเจ้าหน้าที่ :
          <input class="" name="emphome_s" id="emphome_s" type="text" placeholder="ค้นหาด้วยชื่อหรือนามสกุลหรือรหัสบัตรประชาชน">         
      </label>  
    </div>
  </div>
  <hr />
  <div class="row">
    <div class="large-12 columns">
       <a href="#" id="addemphome_s" class="button  [tiny small large]">บันทึก</a>
    </div>
  </div>

  <div id="view-emphome">
    
  </div>

</fieldset>
@stop
