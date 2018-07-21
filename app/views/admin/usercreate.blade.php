@extends('layouts.sidebar')
@section('content')
<?php 
	$error_message = Session::get('error_message');
?>
<ul class="breadcrumbs">
  <li><a href="{{ URL::to('home') }}">หน้าหลัก</a></li>
  <li class="unavailable"><a href="#">ข้อมูลพื้นฐาน</a></li>
  <li class="unavailable"><a href="#">ผู้ใช้งาน</a></li>
  <li class="current"><a href="#">เพิ่มผู้ใช้งาน</a></li>
</ul>

{{ Form::open( array( 'url' => 'user/create', 'data-abide' => '' ) ) }}
<fieldset>
<h2>เพิ่มข้อมูล</h2>  

    <div class="row">
		<?php if( $errors->has('cid') ){ ?>
		<div class="large-12 columns error">
		<?php } else { ?>
		<div class="large-12 columns">
		<?php } ?>							
			 <label>ชื่อผู้ใช้ :
			    <input class="" name="cid" id="cid" type="text" placeholder="ค้นหาด้วยชื่อหรือนามสกุลหรือรหัสบัตรประชาชน">
			</label>			
			<small class="error"> @if ($errors->has('cid')) {{ $errors->first('cid') }} @endif </small>
		</div>	
	</div>
	<div class="row">
		<div class="large-12 columns">
		{{ Form::label( 'level', 'สถานะ :', array( 'class' => 'uk-form-label' ) ) }}		
		    <select id="level" name="level" class="">	
		    	<option value="1">ผู้ดูแลระบบ</option>	 	  				        
		        <option value="2">เจ้าหน้าที่การเงิน</option>
		        <option value="3">เจ้าหน้าที่บัญชี</option>	  
			</select>  
		</div>
	</div>
	<div class="row">	    
	    <div class="large-12 columns">
	      <label>สิทธิ์การใช้งาน จัดการส่วน :</label>
	      <input id="c1" name="c1" value="1" type="checkbox"><label for="c1">พกส.(ปฏิบัติงาน)</label>	            
	    </div>
  	</div>
  	<div class="row">	    
	    <div class="large-12 columns">
	    	<input id="c2" name="c2" value="1" type="checkbox"><label for="c2">ลูกจ้างประจำ</label>	
	    </div>
	</div>
	<div class="row">	    
	    <div class="large-12 columns">
	    	<input id="c3" name="c3" value="1" type="checkbox"><label for="c3">ข้าราชการ</label>
	    </div>
	</div>
  	<div class="row">	
  		<div class="large-12 columns">		  
	      <input id="c4" name="c4" value="1" type="checkbox"><label for="c4">ลูกจ้างชั่วคราว</label>
  		</div>
  	</div>
  	<div class="row">	
  		<div class="large-12 columns">		  
	      <input id="c5" name="c5" value="1" type="checkbox"><label for="c5">หน่วยต้นทุน</label>
  		</div>
  	</div>
	<div class="row">	
  		<div class="large-12 columns">		  
	      <input id="c6" name="c6" value="1" type="checkbox"><label for="c6">แก้ไขข้อมูลเงินเดือน</label>
  		</div>
  	</div>
	<hr />
	<div class="row">
		<div class="large-12 columns">
			{{ Form::submit( 'บันทึก', array( 'class'=>'small button' ) ) }}
			<a class="small button success" href="{{ URL::to('user') }}">กลับหน้าหลัก</a>
		</div>
	</div>
 
    </fieldset>
  {{ Form::close() }}

	
@stop