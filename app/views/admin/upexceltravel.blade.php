@extends('layouts.sidebar')
@section('content')

<ul class="breadcrumbs">
  <li><a href="{{ URL::to('home') }}">หน้าหลัก</a></li>
  <li class="unavailable"><a href="#">ข้อมูลพื้นฐาน</a></li>
  <li class="current"><a href="#">ค่าใช้จ่ายเดินทาง</a></li>
</ul>

{{ Form::open( array( 'url' => 'upexceltravel/upload', 'files'=>true ) ) }}
<fieldset>
<h2>ค่าใช้จ่ายเดินทาง</h2>  

  <div class="row">
    <div class="large-12 columns">        
       <label>ไฟล์ Excel :
         {{ Form::file('file','',array('id'=>'upexcelfile','class'=>'')) }}
      </label>      
    </div>  
  </div>

  <?php if( isset( $status ) ){ ?>
  <hr />
  <span class="[success alert secondary] label"><?php echo $status; ?></span>
  <?php } ?>

  <hr />
  <div class="row">
    <div class="large-12 columns">
      {{ Form::submit( 'บันทึก', array( 'class'=>'small button' ) ) }}
      <a class="small button success" href="{{ URL::to('home') }}">กลับหน้าหลัก</a>
    </div>
  </div>
 
</fieldset>
{{ Form::close() }}

@stop