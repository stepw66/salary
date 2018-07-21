@extends('layouts.sidebar')
@section('content')
<?php 
	$error_message = Session::get('error_message');
?>
<ul class="breadcrumbs">
  <li><a href="{{ URL::to('home') }}">หน้าหลัก</a></li>
  <li class="unavailable"><a href="#">หน่วยต้นทุน</a></li>
  <li class="unavailable"><a href="#">หน่วยต้นทุน</a></li>
  <li class="current"><a href="#">ตรวจสอบข้อมูลในหน่วยต้นทุน</a></li>
</ul>

<fieldset>
<h2>ตรวจสอบข้อมูลในหน่วยต้นทุน</h2> 
<form>	
	<div class="">
		<div class="large-4 columns">							
			{{ Form::label( 'level', 'ปีงบประมาณ :', array( 'class' => '' ) ) }}		
		    <select id="unitcost_y" name="unitcost_y" class="">	
		    	<option value="0">*------ กรุณาเลือก ------*</option>	 	  				        
		        @foreach( $y as $year )		      
		  			<option value="{{ $year->year1 }}">{{ $year->year1 }}</option>		        
		   		@endforeach
			</select>  						
		</div>
		<div class="large-4 columns">							
			<label>เดือน :
	        <select name="unitcost_m" id="unitcost_m">
	            <option value="0">*------ กรุณาเลือก ------*</option>         
	            <option value="01">มกราคม</option>
	            <option value="02">กุมภาพันธ์</option>
	            <option value="03">มีนาคม</option>
	            <option value="04">เมษายน</option>
	            <option value="05">พฤษภาคม</option>
	            <option value="06">มิถุนายน</option>
	            <option value="07">กรกฎาคม</option>
	            <option value="08">สิงหาคม</option>
	            <option value="09">กันยายน</option>
	            <option value="10">ตุลาคม</option>
	            <option value="11">พฤศจิกายน</option>
	            <option value="12">ธันวาคม</option>
	        </select>
	      </label>				
		</div>
		<div class="large-4 columns">							
			{{ Form::label( 'level', 'หน่วยต้นทุน :', array( 'class' => '' ) ) }}		
		    <select id="unitcost_u" name="unitcost_u" class="">	
		    	<option value="0">*------ กรุณาเลือก ------*</option>	 	  				        
		        @foreach( $u as $name )		      
		  			<option value="{{ $name->unitname }}">{{ $name->unitname }}</option>		        
		   		@endforeach
			</select>  						
		</div>
	</div>  
	<div class="">		
		<div class="small-2 small-centered columns">
			 <a class="small button" href="#" id="bt_manager" >ตกลง</a>	
		</div>
	</div>	
</form>
</fieldset>

<hr />

<div id="list-manager-unitcosts">
-
</div>	
 
	
@stop

