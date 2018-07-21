@extends('layouts.sidebar')
@section('content')

<ul class="breadcrumbs">
  <li><a href="{{ URL::to('home') }}">หน้าหลัก</a></li>
  <li class="unavailable"><a href="#">ลูกจ้างประจำ-ข้าราชการ</a></li>
  <li class="current"><a href="#">เพิ่มข้อมูลอัตโนมัติ</a></li>
</ul>

<div class="row">
  <div class="small-6 small-centered columns">
    <div class="panel callout radius">
      <h5>รายละเอียดการทำงาน</h5>
      <p>
          ระบบจะทำการเพิ่มข้อมูลเงินเดือนเดือนล่าสุดเข้าไปให้ทุกคนโดยอัตโนมัติ
      </p>
      
      <kbd>**หมายเหตุ ข้อมูลจะเพิ่มเข้าระบบแค่เดือนปัจจุบันและจะยึดเงินเดือน เดือนล่าสุดมา</kbd>
    </div>
    <hr />
    <a href="#" onclick="addAuto()" class="button small">เพิ่มข้อมูลอัตโนมัติ</a>

    <div id="status_addauto"></div>
  </div>
</div>

@stop
