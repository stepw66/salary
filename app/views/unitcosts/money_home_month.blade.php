
@extends('layouts.sidebar')
@section('content')
<ul class="breadcrumbs">
  <li><a href="{{ URL::to('home') }}">หน้าหลัก</a></li>
  <li class="unavailable"><a href="#">หน่วยต้นทุน</a></li> 
  <li class="current"><a href="#">เลือกช่วงข้อมูล</a></li>
</ul>

{{ Form::open( array( 'url' => 'unitcosts/money_month', 'data-abide' => '' ) ) }}
<fieldset>
<h2>สรุป LC รายเดือน EXCEL</h2>  

<div class="row">
	<div class="large-6 columns">
	{{ Form::label( 'level', 'เลือกปี :', array( 'class' => 'uk-form-label' ) ) }}		
	   <select name="y_unit" class=""> 
          @foreach( $data as $a )              
           <option  value="{{ $a->year1 }}">{{ $a->year1 }}</option>        
          @endforeach
        </select>  
	</div>
</div> 
<div class="row">
	<div class="large-6 columns">
	{{ Form::label( 'level', 'เลือกเดือน :', array( 'class' => 'uk-form-label' ) ) }}		
	    <select name="m_unit" class="">	
	    	<option value="1-มกราคม">มกราคม</option>	 	  				        
	        <option value="2-กุมภาพันธ์">กุมภาพันธ์</option>
	        <option value="3-มีนาคม">มีนาคม</option>	
	        <option value="4-เมษายน">เมษายน</option>	 	  				        
	        <option value="5-พฤษภาคม">พฤษภาคม</option>
	        <option value="6-มิถุนายน">มิถุนายน</option>	  
	        <option value="7-กรกฎาคม">กรกฎาคม</option>	 	  				        
	        <option value="8-สิงหาคม">สิงหาคม</option>
	        <option value="9-กันยายน">กันยายน</option>	  
	        <option value="10-ตุลาคม">ตุลาคม</option>	 	  				        
	        <option value="11-พฤศจิกายน">พฤศจิกายน</option>
	        <option value="12-ธันวาคม">ธันวาคม</option>	    
		</select>  
	</div>
</div> 
<hr />
<div class="row">
	<div class="large-12 columns">			
		{{ Form::submit( 'ส่งออกรายงาน', array( 'class'=>'small button' ) ) }}
	</div>
</div> 
 
</fieldset>
 {{ Form::close() }}
	
@stop