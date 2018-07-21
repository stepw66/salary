@extends('layouts.sidebar')
@section('content')

<ul class="breadcrumbs">
  <li><a href="{{ URL::to('home') }}">หน้าหลัก</a></li>
  <li class="unavailable"><a href="#">หน่วยต้นทุน</a></li>
  <li class="current"><a href="#">หน่วยต้นทุน</a></li>
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
		<a href="{{ URL::to('unitcosts/create') }}" class="small button">เพิ่มหน่วยต้นทุน</a>
	</div>
	<div class="medium-4 columns">
     {{ Form::open( array( 'url' => 'unitcosts/search' ) ) }}
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
        <th width="150">รหัสหน่วยต้นทุน</th>
        <th width="280">ชื่อหน่วยต้นทุน</th>             
        <th width="40">แก้ไข</th>
        <th width="40">ลบ</th>
      </tr>

      <?php $i=0; ?>
        @foreach( $data as $a )       
        <tr>      
          <td><?php echo $data->getFrom()+$i; ?></td>  
          <td>{{ $a->unitcode }}</td>
          <td>{{ $a->unitname }}</td>                  
          <td><a title="แก้ไขข้อมูล"  href="{{ URL::to('unitcosts/edit') }}/{{ $a->unit_id }}"><i class="fi-pencil small"></i></a></td>  
          <td><a title="ลบข้อมูล"  onclick="if(!confirm('ต้องการลบข้อมูลหรือไม่?')){return false;};" href="{{ URL::to('unitcosts/delete') }}/{{ $a->unit_id }}"><i class="fi-x small"></i></a></td>              
        </tr> 
        <?php $i++; ?>
        @endforeach   
  </table>
</div>
</div>
 <?php echo $data->links(); ?>	
@stop