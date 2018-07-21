@extends('layouts.sidebar')
@section('content')
<?php 
	$error_message = Session::get('error_message');
?>
<ul class="breadcrumbs">
  <li><a href="{{ URL::to('home') }}">หน้าหลัก</a></li>
  <li class="unavailable"><a href="#">หน่วยต้นทุน</a></li>
  <li class="unavailable"><a href="#">หน่วยต้นทุน</a></li>
  <li class="current"><a href="#">แก้ไขหน่วยต้นทุน</a></li>
</ul>

<?php
	$url = 'unitcosts/edit/'.$data->unit_id;
?>
{{ Form::open( array( 'url' => $url, 'data-abide' => '' ) ) }}
<fieldset>
<h2>แก้ไขหน่วยต้นทุน</h2>  

    <div class="row">
		<?php if( $errors->has('unitcode') ){ ?>
		<div class="large-6 columns error">
		<?php } else { ?>
		<div class="large-6 columns">
		<?php } ?>							
			 <label>รหัสหน่วยต้นทุน :
			    <input class="" name="unitcode" id="unitcode" type="text" value="{{ $data->unitcode }}" placeholder="รหัสหน่วยต้นทุน">
			</label>			
			<small class="error"> @if ($errors->has('unitcode')) {{ $errors->first('unitcode') }} @endif </small>
		</div>	
	</div>
	<div class="row">
		<?php if( $errors->has('unitname') ){ ?>
		<div class="large-12 columns error">
		<?php } else { ?>
		<div class="large-12 columns">
		<?php } ?>							
			 <label>ชื่อหน่วยต้นทุน :
			    <input class="" name="unitname" id="unitname" type="text" value="{{ $data->unitname }}" placeholder="ชื่อหน่วยต้นทุน">
			</label>			
			<small class="error"> @if ($errors->has('unitname')) {{ $errors->first('unitname') }} @endif </small>
		</div>	
	</div>
	
	<hr />
	<div class="row">
		<div class="large-12 columns">
			{{ Form::submit( 'บันทึก', array( 'class'=>'small button' ) ) }}
			<a class="small button success" href="{{ URL::to('unitcosts') }}">กลับหน้าหลัก</a>
		</div>
	</div>
 
    </fieldset>
  {{ Form::close() }}

	
@stop