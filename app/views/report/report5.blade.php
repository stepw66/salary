@extends('layouts.sidebar')
@section('content')
<ul class="breadcrumbs">
  <li><a href="{{ URL::to('home') }}">หน้าหลัก</a></li>
  <li class="unavailable"><a href="#">รายงาน</a></li>
  <li class="unavailable"><a href="#">รายงานรวมเงินเดือนและค่าตอบแทน (EXCEL)</a></li>
  <li class="current"><a href="#">เลือกช่วงข้อมูล</a></li>
</ul>

{{ Form::open( array( 'url' => 'report/sp_sa_excel_export', 'data-abide' => '' ) ) }}
<fieldset>
<h2>รายงานรวมเงินเดือนและค่าตอบแทน เลือกช่วงข้อมูล</h2>  

<div class="row">
	<div class="large-12 columns">
	{{ Form::label( 'level', 'เลือกปี :', array( 'class' => 'uk-form-label' ) ) }}		
	   <select id="y5" name="y5" class=""> 
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