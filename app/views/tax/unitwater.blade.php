@extends('layouts.sidebar')
@section('content')
<?php 
  $error_message = Session::get('error_message');
  $success_message = Session::get('success_message');
?>

<ul class="breadcrumbs">
  <li><a href="{{ URL::to('home') }}">หน้าหลัก</a></li>
  <li class="unavailable"><a href="#">เงินรับอื่น ๆ - หักน้ำไฟ</a></li>
  <li class="current"><a href="#">ค่าน้ำ</a></li>
</ul>

<?php $url = 'special/general_update/'.$data->generalID; $unitwater = $data->unitwater; ?>
{{ Form::open( array( 'url' => $url, 'data-abide' => '' ) ) }}
<fieldset>

<h2>หน่วยคิดค่าน้ำ</h2> 

@if(!empty($success_message))
  <div data-alert="" class="alert-box success">{{ $success_message }}<a class="close" href="#">×</a></div>
@endif
 @if(!empty($error_message))
  <div data-alert="" class="alert-box alert">{{ $error_message }}<a class="close" href="#">×</a></div>
@endif 

  <div class="row">
    <div class="large-12 columns"> 
      <label>หน่วยคิดค่าน้ำ(ใส่ตัวเลข) :
        <input class="" name="unitwater" id="unitwater"  value="<?php echo $unitwater; ?>"  type="text" placeholder="กรอกหน่วยคิดค่าน้ำ">      
      </label>      
    </div>  
  </div>
  <hr />
  <div class="row">
    <div class="large-12 columns">
       {{ Form::submit( 'บันทึก', array( 'class'=>'button  [tiny small large]' ) ) }} 
    </div>
  </div>

</fieldset>
{{ Form::close() }}

@stop
