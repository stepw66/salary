@extends('layouts.sidebar')
@section('content')
<?php 
	$error_message = Session::get('error_message');
	$success_message = Session::get('success_message');
?>
<ul class="breadcrumbs">
  <li><a href="{{ URL::to('home') }}">หน้าหลัก</a></li>
  <li class="unavailable"><a href="#">ข้อมูลพื้นฐาน</a></li>
  <li class="unavailable"><a href="#">ข้อมูลทั่วไป</a></li>
  <li class="current"><a href="#">จัดการข้อมูลทั่วไป</a></li>
</ul>

<?php if( count($data) == 0 ){ $name=''; $address=''; $address2=''; $tax_id=''; $tax_id2=''; $director=''; ?>
	{{ Form::open( array( 'url' => 'user/general_add', 'data-abide' => '' ) ) }}
<?php } else {  $url = 'user/general_update/'.$data->generalID; $name=$data->name; $address=$data->address; $address2=$data->address2; $tax_id=$data->tax_id; $tax_id2=$data->tax_id2; $director=$data->director; ?>
	{{ Form::open( array( 'url' => $url, 'data-abide' => '' ) ) }}
<?php } ?>

<fieldset>
<h2>จัดการข้อมูลทั่วไป</h2>  

@if(!empty($success_message))
  <div data-alert="" class="alert-box success">{{ $success_message }}<a class="close" href="#">×</a></div>
@endif
 @if(!empty($error_message))
  <div data-alert="" class="alert-box alert">{{ $error_message }}<a class="close" href="#">×</a></div>
@endif

<div class="">
	<div class="large-12 columns">
		 <label>ชื่อโรงพยาบาล :
			<input class="" name="name" type="text" value="<?php echo $name; ?>" placeholder="ชื่อโรงพยาบาล">
		</label>
		<label>ที่อยู่ โรงพยาบาล :
			<input class="" name="address" type="text" value="<?php echo $address; ?>" placeholder="ที่อยู่">
		</label>
		<label>เลขที่ภาษี โรงพยาบาล :
			<input class="" name="tax_id" type="text" value="<?php echo $tax_id; ?>" placeholder="เลขที่ภาษี">
		</label>
		<label>ที่อยู่ สสจ :
			<input class="" name="address2" type="text" value="<?php echo $address2; ?>" placeholder="ที่อยู่">
		</label>
		<label>เลขที่ภาษี สสจ :
			<input class="" name="tax_id2" type="text" value="<?php echo $tax_id2; ?>" placeholder="เลขที่ภาษี">
		</label>		
		<label>ชื่อ-นามสกุล ผู้อำนวยการ :
			<input class="" name="director" type="text" value="<?php echo $director; ?>" placeholder="ผู้อำนวยการ">
		</label>
	</div>
</div>
<div class="">
	<div class="large-12 columns">
		{{ Form::submit( 'บันทึก', array( 'class'=>'small button' ) ) }}		
	</div>
</div>

</fieldset>
{{ Form::close() }}

@stop