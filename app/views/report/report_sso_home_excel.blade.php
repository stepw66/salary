@extends('layouts.sidebar')
@section('content')
<ul class="breadcrumbs">
  <li><a href="{{ URL::to('home') }}">หน้าหลัก</a></li>
  <li class="unavailable"><a href="#">รายงาน</a></li>
  <li class="unavailable"><a href="#">แบบรายงานการแสดงการส่งเงินสมทบ</a></li>
  <li class="current"><a href="#">(PDF)</a></li>
</ul>

{{ Form::open( array( 'url' => 'report/salary_sso_excel_export', 'data-abide' => '' ) ) }}
<fieldset>
<h2>แบบรายงานการแสดงการส่งเงินสมทบ เลือกช่วงข้อมูล</h2>  

<div class="row">
	<div class="large-12 columns">
	{{ Form::label( 'level', 'เลือกเดือน :', array( 'class' => 'uk-form-label' ) ) }}		
	    <select id="m_sso_1" name="m_sso_1" class="">	
	    	<option value="1">มกราคม</option>	 	  				        
	        <option value="2">กุมภาพันธ์</option>
	        <option value="3">มีนาคม</option>	
	        <option value="4">เมษายน</option>	 	  				        
	        <option value="5">พฤษภาคม</option>
	        <option value="6">มิถุนายน</option>	  
	        <option value="7">กรกฎาคม</option>	 	  				        
	        <option value="8">สิงหาคม</option>
	        <option value="9">กันยายน</option>	  
	        <option value="10">ตุลาคม</option>	 	  				        
	        <option value="11">พฤศจิกายน</option>
	        <option value="12">ธันวาคม</option>	    
		</select>  
	</div>
</div> 
<div class="row">
	<div class="large-12 columns">
	{{ Form::label( 'level', 'เลือกปี :', array( 'class' => 'uk-form-label' ) ) }}		
	   <select id="y_sso_1" name="y_sso_1" class=""> 
          @foreach( $data as $a )              
           <option  value="{{ $a->year1 }}">{{ $a->year1 }}</option>        
          @endforeach
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