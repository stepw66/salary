@extends('layouts.sidebar')
@section('content')

<ul class="breadcrumbs">
  <li><a href="{{ URL::to('home') }}">หน้าหลัก</a></li>
  <li class="unavailable"><a href="#">ข้อมูลพื้นฐาน</a></li>
  <li class="current"><a href="#">อัพไฟล์ข้อมูลรายได้ พตส. พี่นก</a></li>
</ul>

{{ Form::open( array( 'url' => 'upexcelnth4/upload', 'files'=>true ) ) }}
<fieldset>
<h2>อัพไฟล์ข้อมูลรายได้ พตส. พี่นก</h2>  

  <div class="row">
    <div class="large-6 columns">
      <label>
        เลือกปี :
        <select name="pts_y" class=""> 
          <option value="0">*------ กรุณาเลือก ------*</option>                     
            @foreach( $y as $year )         
            <option value="{{ $year->year1 }}">{{ $year->year1 }}</option>            
          @endforeach
      </select> 
      </label>
    </div>
  </div>
  <div class="row">
    <div class="large-6 columns">        
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