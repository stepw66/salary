@extends('layouts.sidebar')
@section('content')

<div class="">
	<div class="medium-6 columns">
		<ul class="pricing-table">
		  <li class="title">{{ HTML::image('assets/images/emp1.png', '', array('class' => '', 'height' => '', 'width' => '' ) ); }}</li>		 
		  <li class="price">เดือน : {{ $result5 }}</li>
		  @foreach( $result1 as $a )
		  <li class="description">ลูกจ้างชั่วคราว/พกส.(ปฏิบัติงาน)</li>	
		  <li class="bullet-item">จำนวน : {{ $a->num }} คน</li>
		  <li class="bullet-item">เงินเดือนรวม : <?php echo number_format( $a->salary, 2); ?> บาท</li>	
		   @endforeach   
		</ul>
	</div>
	<div class="medium-6 columns">
		<ul class="pricing-table">
		  <li class="title">{{ HTML::image('assets/images/emp2.png', '', array('class' => '', 'height' => '', 'width' => '' ) ); }}</li>
		  <li class="price">เดือน : {{ $result5 }}</li>
		   @foreach( $result2 as $a )
		  <li class="description">ลูกจ้างประจำ/ข้าราชการ</li>
		  <li class="bullet-item">จำนวน : {{ $a->num }} คน</li>
		  <li class="bullet-item">เงินเดือนรวม : <?php echo number_format( $a->salary, 2); ?> บาท</li>
		  @endforeach  		 
		</ul>
	</div>	
</div>
	
@stop