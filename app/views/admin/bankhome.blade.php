@extends('layouts.sidebar')
@section('content')

<ul class="breadcrumbs">
  <li><a href="{{ URL::to('home') }}">หน้าหลัก</a></li>
  <li class="unavailable"><a href="#">ข้อมูลพื้นฐาน</a></li>
  <li class="current"><a href="#">ธนาคาร</a></li>
</ul>

<?php 
  $success_message = Session::get('success_message');
  $error_message = Session::get('error_message');
?>
@if(!empty($success_message))
  <div data-alert="" class="alert-box success">{{ $success_message }}<a class="close" href="#">×</a></div>
@endif
 @if(!empty($error_message))
  <div data-alert="" class="alert-box alert">{{ $error_message }}<a class="close" href="#">×</a></div>
@endif

<div class="">
	<div class="medium-8 columns">
		<a href="{{ URL::to('bank/create') }}" class="small button">เพิ่มธนาคาร</a>
	</div>
	<div class="medium-4 columns">
     {{ Form::open( array( 'url' => 'bank/search' ) ) }}
  		<div class="row collapse">   
    			<div class="large-8 small-9 columns">
    				<input type="search" class="search" id="search" name="search" placeholder="ค้นหา"/>
    			</div>
    			<div class="large-4 small-3 columns">   				
            {{ Form::submit( 'ค้นหา', array( 'class'=>'success postfix button' ) ) }} 
    			</div>   
  		</div>
     {{ Form::close() }}
	</div>
</div>

<div class="">
<div class="medium-12 columns">
  <table class="responsive">
   	  <tr>
        <th width="50">ลำดับ</th>
        <th width="550">ชื่อธนาคาร</th>
        <th width="50">แก้ไข</th>
        <th width="50">ลบ</th>
      </tr>

      <?php $i=0; ?>
        @foreach( $bankall as $a )
        <?php $i++; ?>
        <tr>      
          <td width="50">{{ $i }}</td>
          <td>{{ $a->bank_name }}</td>         
          <td><a  title="แก้ไขข้อมูล"  href="{{ URL::to('bank/edit') }}/{{ $a->bank_id }}"><i class="fi-pencil small"></i></a></td>  
          <td><a title="ลบข้อมูล"  onclick="if(!confirm('ต้องการลบข้อมูลหรือไม่?')){return false;};" href="{{ URL::to('bank/delete') }}/{{ $a->bank_id }}"><i class="fi-x small"></i></a></td>              
        </tr> 
        @endforeach   
  </table>
</div>
</div>	
@stop