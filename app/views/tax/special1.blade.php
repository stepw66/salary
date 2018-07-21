@extends('layouts.sidebar')
@section('content')

<ul class="breadcrumbs">
  <li><a href="{{ URL::to('home') }}">หน้าหลัก</a></li>
  <li class="unavailable"><a href="#">ค่าตอบแทน</a></li>
  <li class="current"><a href="#">พกส. - ลูกจ้างชั่วคราว - ข้าราชการ - ลูกจ้างประจำ</a></li>
</ul>

<fieldset>
<div class="">
  <div class="large-4 columns">             
    {{ Form::label( 'level', 'ชื่อค่าตอบแทน-ค่าใช้จ่าย :', array( 'class' => '' ) ) }}   
      <select id="paylist1" name="paylist1" class=""> 
        <option value="0">กรุณาเลือก ค่าตอบแทน-ค่าใช้จ่าย</option>                                              
        <option value="1">ค่า พตส.เงินนอกงบประมาณ</option>           
        <option value="2">ค่า OT</option>
        <option value="3">ค่า ฉ 8</option>
        <option value="4">ค่า ไม่ทำเวช</option>
        <option value="5">ค่า ออกหน่วย</option>
        <option value="6">ค่า พตส.เงินงบประมาณ</option>
        <option value="8">ค่า ฉ 11 เงินนอกงบประมาณ</option>
        <option value="9">ค่า ฉ 11 เงินงบประมาณ</option>
        <option value="7">ทั้งหมด</option>
    </select>             
  </div>         
  <div class="large-4 columns">
      <label>เลือกปี พศ.
        <select name="speyear1" id="speyear1">
          <option value="0">กรุณาเลือกปี พศ.</option>
          @foreach( $year as $y )
              <option value="<?php echo ($y->year)-543; ?>">{{ $y->year }}</option>
              <?php $yy=$y->year; ?>
          @endforeach 
          <option value="<?php echo ($yy+1)-543; ?>">{{ $yy+1 }}</option>
        </select>
      </label>
    </div>
  <div class="large-4 columns">
     <label>เลือกเดือน
        <select name="spemonth1" id="spemonth1">
          <option value="0">กรุณาเลือกเดือน</option>         
            <option value="01">มกราคม</option>
            <option value="02">กุมภาพันธ์</option>
            <option value="03">มีนาคม</option>
            <option value="04">เมษายน</option>
            <option value="05">พฤษภาคม</option>
            <option value="06">มิถุนายน</option>
            <option value="07">กรกฎาคม</option>
            <option value="08">สิงหาคม</option>
            <option value="09">กันยายน</option>
            <option value="10">ตุลาคม</option>
            <option value="11">พฤศจิกายน</option>
            <option value="12">ธันวาคม</option>
        </select>
      </label>
  </div>
</div>
<div class="">
  <div class="large-12 columns">
    <div id="view-data-special"></div>
  </div>
</div>
</fieldset>
@stop
