<!doctype html>
<html class="no-js" lang="en">
<head>
	@include('includes.head')
</head>
<body>
<?php 
	$error_message = Session::get('error_message');
?>

<div id="passport" class="login-main">	
 	{{ Form::open( array( 'url' => 'login', 'data-abide' => '' ) ) }}	  
	<div class="passport-left">
		<h2>ระบบเงินเดือน</h2>
	</div>
	<div class="passport-right">
		<div id="emailLogin">
			<legend>เข้าสู่ระบบ</legend>
			<div style="margin-top:15px;"></div>
			<div class="row">
				<?php if( $errors->has('username') ){ ?>
				<div class="large-12 columns error">
				<?php } else { ?>
				<div class="large-12 columns">
				<?php } ?>				
				    <label>ชื่อผู้ใช้งาน :
				      <input type="text" name="username" id="username" placeholder="ชื่อผู้ใช้งาน">
				    </label>				    
				    <small class="error"> @if ($errors->has('username')) {{ $errors->first('username') }} @endif </small>
				</div>
			</div>
			<div class="row">
				<?php if( $errors->has('password') ){ ?>
				<div class="large-12 columns error">
				<?php } else { ?>
				<div class="large-12 columns">
				<?php } ?>				
				    <label>รหัสผ่าน :
				      <input type="password" name="password" id="password" placeholder="รหัสผ่าน">
				    </label>
				    <small class="error"> @if ($errors->has('password')) {{ $errors->first('password') }} @endif </small> 
			  	</div>
		  	</div>
		  	<div class="row">
				<div class="large-12 columns">				
					{{ Form::submit( 'เข้าสู่ระบบ', array( 'class'=>'small button right' ) ) }}	
				</div>				
			</div>
			@if( !empty($error_message) )
			<div data-alert="" class="alert-box alert">
				{{ $error_message }}
				<a class="close" href="#">×</a>
			</div>
			@endif

			<p>© 2014 ThemeSanasang.</p>
		</div>
	</div>
	{{ Form::close() }}
</div>


 <script>
  $(document).foundation();
</script>
	
</body>
</html>