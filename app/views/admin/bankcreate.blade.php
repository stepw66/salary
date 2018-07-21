@extends('layouts.sidebar')
@section('content')
<?php 
	$error_message = Session::get('error_message');
?>
<ul class="breadcrumbs">
  <li><a href="{{ URL::to('home') }}">หน้าหลัก</a></li>
  <li class="unavailable"><a href="#">ข้อมูลพื้นฐาน</a></li>
  <li class="unavailable"><a href="#">ธนาคาร</a></li>
  <li class="current"><a href="#">เพิ่มธนาคาร</a></li>
</ul>

{{ Form::open( array( 'url' => 'bank/create', 'data-abide' => '' ) ) }}
<fieldset>
<h2>เพิ่มธนาคาร</h2>  

    <div class="row">
		<?php if( $errors->has('bank_name') ){ ?>
		<div class="large-12 columns error">
		<?php } else { ?>
		<div class="large-12 columns">
		<?php } ?>							
			 <label>ชื่อธนาคาร :
			    <input class="" name="bank_name" id="bank_name" type="text" placeholder="กรอกชื่อธนาคาร">
			</label>			
			<small class="error"> @if ($errors->has('bank_name')) {{ $errors->first('bank_name') }} @endif </small>
		</div>	
	</div>
	<hr />
	<div class="row">
		<div class="large-12 columns">
			{{ Form::submit( 'บันทึก', array( 'class'=>'small button' ) ) }}
			<a class="small button success" href="{{ URL::to('bank') }}">กลับหน้าหลัก</a>
		</div>
	</div>
 
    </fieldset>
  {{ Form::close() }}

	
@stop