@extends('layouts.sidebar')
@section('content')
<?php 
	$error_message = Session::get('error_message');
?>
<ul class="breadcrumbs">
  <li><a href="{{ URL::to('home') }}">หน้าหลัก</a></li>
  <li class="unavailable"><a href="#">ข้อมูลพื้นฐาน</a></li>
  <li class="unavailable"><a href="#">ผู้ใช้งาน</a></li>
  <li class="current"><a href="#">แก้ไขข้อมูลผู้ใช้งาน</a></li>
</ul>

<?php
	$url = 'user/edit/'.$user->cid;
?>
{{ Form::open( array( 'url' => $url, 'data-abide' => '' ) ) }}
<fieldset>
<h2>แก้ไขข้อมูลผู้ใช้งาน</h2>  

    <div class="row">
		<?php if( $errors->has('cid') ){ ?>
		<div class="large-12 columns error">
		<?php } else { ?>
		<div class="large-12 columns">
		<?php } ?>							
			 <label>ชื่อผู้ใช้ :
			    {{ $user->cid }} {{ $user->pname }}{{ $user->fname }} {{ $user->lname }}	
			</label>						
		</div>	
	</div>
	<br />
	<div class="row">
		<div class="large-12 columns">
		{{ Form::label( 'level', 'สถานะ :', array( 'class' => '' ) ) }}		
		    <select id="level" name="level" class="">	
		    	<option <?php if($user->level == 1) {echo "selected";}else{echo "";}  ?> value="1">ผู้ดูแลระบบ</option>
		        <option <?php if($user->level == 2) {echo "selected";}else{echo "";}  ?> value="2">เจ้าหน้าที่การเงิน</option>	
		        <option <?php if($user->level == 3) {echo "selected";}else{echo "";}  ?> value="3">เจ้าหน้าที่บัญชี</option>		    
			</select>  
		</div>
	</div>
	<div class="row">	    
	    <div class="large-12 columns">
	      <label>สิทธิ์การใช้งาน จัดการส่วน :</label>
	      <input <?php  echo (($user->c1 == 1) ? 'checked="checked"':''); ?> id="c1" name="c1" value="1" type="checkbox"><label for="c1">พกส.(ปฏิบัติงาน)</label>    	      
	    </div>
  	</div>
  	<div class="row">	    
	    <div class="large-12 columns">
	    	<input <?php  echo (($user->c2 == 1) ? 'checked="checked"':''); ?> id="c2" name="c2" value="1" type="checkbox"><label for="c2">ลูกจ้างประจำ</label>
	    </div>
	</div>
	<div class="row">	    
	    <div class="large-12 columns">
	    	<input <?php  echo (($user->c3 == 1) ? 'checked="checked"':''); ?> id="c3" name="c3" value="1" type="checkbox"><label for="c3">ข้าราชการ</label>
	    </div>
	</div>
	<div class="row">	    
	    <div class="large-12 columns">
	    	<input <?php  echo (($user->c4 == 1) ? 'checked="checked"':''); ?> id="c4" name="c4" value="1" type="checkbox"><label for="c4">ลูกจ้างชั่วคราว</label>
	    </div>
	</div>
	<div class="row">	    
	    <div class="large-12 columns">
	    	<input <?php  echo (($user->c5 == 1) ? 'checked="checked"':''); ?> id="c5" name="c5" value="1" type="checkbox"><label for="c5">หน่วยต้นทุน</label>
	    </div>
	</div>
	<div class="row">	    
	    <div class="large-12 columns">
	    	<input <?php  echo (($user->c6 == 1) ? 'checked="checked"':''); ?> id="c6" name="c6" value="1" type="checkbox"><label for="c6">แก้ไขข้อมูลเงินเดือน</label>
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