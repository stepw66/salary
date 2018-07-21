@extends('layouts.sidebar')
@section('content')

<ul class="breadcrumbs">
  <li><a href="{{ URL::to('home') }}">หน้าหลัก</a></li>
  <li class="unavailable"><a href="#">ข้อมูลพื้นฐาน</a></li>
  <li class="current"><a href="#">ผู้ใช้งาน</a></li>
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
		<a href="{{ URL::to('user/create') }}" class="small button">เพิ่มผู้ใช้งาน</a>
	</div>
	<div class="medium-4 columns">
     {{ Form::open( array( 'url' => 'user/search' ) ) }}
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
        <th width="150">รหัสบัตรประชาชน</th>
        <th width="280">ชื่อ-นามสกุล</th>    
        <th width="180">ตำแหน่ง</th>
        <th width="50">แก้ไข</th>
        <th width="50">ลบ</th>
      </tr>

      <?php $i=0; ?>
        @foreach( $userall as $a )
        <?php $i++; ?>
        <tr>      
              <td width="50">{{ $i }}</td>
              <td>{{ $a->cid }}</td>
          <td>{{ $a->pname }} {{ $a->fname }} {{ $a->lname }}</td>
          <td>
            <?php 
              if( $a->level == '1'){
                echo 'ผู้ดูแลระบบ';
              }else if( $a->level == '2'){
                echo 'เจ้าหน้าที่การเงิน';
              }else{
                echo 'เจ้าหน้าที่บัญชี';
              } 
            ?>
          </td>       
          <td><a  title="แก้ไขข้อมูล"  href="{{ URL::to('user/edit') }}/{{ $a->cid }}"><i class="fi-pencil small"></i></a></td>  
          <td><a title="ลบข้อมูล"  onclick="if(!confirm('ต้องการลบข้อมูลหรือไม่?')){return false;};" href="{{ URL::to('user/delete') }}/{{ $a->cid }}"><i class="fi-x small"></i></a></td>              
        </tr> 
        @endforeach   
  </table>
</div>
</div>	
@stop