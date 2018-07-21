@extends('layouts.sidebar')
@section('content')
<?php 
	$error_message = Session::get('error_message');
?>
<ul class="breadcrumbs">
  <li><a href="{{ URL::to('home') }}">หน้าหลัก</a></li>
  <li class="unavailable"><a href="#">หน่วยต้นทุน</a></li>
  <li class="unavailable"><a href="#">หน่วยต้นทุน</a></li>
  <li class="current"><a href="#">เพิ่มคนเข้าหน่วยต้นทุน</a></li>
</ul>

{{ Form::open( array( 'url' => '', 'data-abide' => '' ) ) }}
<fieldset>
<h2>เพิ่มคนเข้าหน่วยต้นทุน</h2> 
	
	<div class="row">
		<div class="large-6 columns">
		   
			<label>ชื่อเจ้าหน้าที่ :
			    <input class="" name="empunit" id="empunit" type="text" placeholder="ค้นหาด้วยชื่อหรือนามสกุลหรือรหัสบัตรประชาชน">			   
			</label>	
		</div>
	</div>
    <div class="row">		
		<div class="large-6 columns">							
			{{ Form::label( 'level', 'ชื่อหน่วยต้นทุน :', array( 'class' => '' ) ) }}		
		    <select id="unit" name="unit" class="">	
		    	<option value="0">*------ กรุณาเลือก ------*</option>	 	  				        
		         @foreach( $unitcosts as $a )		      
		  			<option value="{{ $a->unit_id }}">{{ $a->unitname }}</option>		        
		   		@endforeach
			</select>  						
		</div>
		<div class="large-3 columns left">							
			<label>เปอร์เซ็นต่อหนวยต้นทุน :
			    <input class="" name="cal" id="cal" type="text" value="100" placeholder="เปอร์เซ็นต่อหนวยต้นทุน">			   
			</label>								
		</div>						
	</div>
	<div class="row">		
		<div class="large-6 columns">
			 <a class="small button" href="#" id="add-emp-unit" >เพิ่มรายชื่อ</a>	
		</div>
	</div>	

	<h4>รายชื่อเจ้าหน้าที่ <span id="name-unit-show"></span></h4>
	<hr />
		<div id="listname-emp-unitcosts">
		- ไม่มี
		</div>	
 
    </fieldset>
  {{ Form::close() }}

	
@stop

