@extends('layouts.sidebar')
@section('content')

<ul class="breadcrumbs">
  <li><a href="{{ URL::to('home') }}">หน้าหลัก</a></li>
  <li class="unavailable"><a href="#">เงินรับอื่น ๆ - หักน้ำไฟ</a></li>
  <li class="current"><a href="#">ค่าน้ำ</a></li>
</ul>

<fieldset>

<h2>ลงค่าน้ำ</h2>  
<div class="">       
  <div class="large-6 columns">
      <label>เลือกปี พศ.
        <select name="year_water" id="year_water">
          <option value="0">กรุณาเลือกปี พศ.</option>
          @foreach( $year as $y )
              <option value="<?php echo ($y->year)-543; ?>">{{ $y->year }}</option>
          @endforeach 
        </select>
      </label>
    </div>
  <div class="large-6 columns">
     <label>เลือกเดือน
        <select name="month_water" id="month_water">
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

<div id="view_water"></div>
  
</fieldset>
@stop
