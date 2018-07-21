@extends('layouts.sidebar')
@section('content')

<ul class="breadcrumbs">
  <li><a href="{{ URL::to('home') }}">หน้าหลัก</a></li>
  <li class="unavailable"><a href="#">เงินรับอื่น ๆ - หักน้ำไฟ</a></li>
  <li class="current"><a href="#">ค่าน้ำ</a></li>
</ul>

<fieldset>

  <h2>เพิ่มหมายเลขมิเตอร์น้ำ</h2>  

  <div class="row">
    <div class="large-12 columns"> 
      <label>หมายเลขมิเตอร์น้ำ :
        <input class="" name="meter" id="meter" type="text" placeholder="กรอกหมายเลขมิเตอร์น้ำ">
      </label>      
    </div>  
  </div>
  <hr />
  <div class="row">
    <div class="large-12 columns">
       <a href="#" id="addmeter" class="button  [tiny small large]">บันทึก</a>
    </div>
  </div>

  <div id="view-meter">
    <?php if( isset($data) ){ ?>
    <?php if( count($data) > 0 ){ ?>
    <table class="responsive">
      <tr>
        <th width="80">ลำดับ</th>
        <th>เลขมิเตอร์น้ำ</th>      
        <th width="50">ลบ</th>
      </tr>

      <?php $i=0; ?>
        @foreach( $data as $a )
        <?php $i++; ?>
        <tr>      
          <td>{{ $i }}</td>
          <td>{{ $a->name_meter }}</td>                   
          <td><a title="ลบข้อมูล"  onclick="del_meter({{ $a->meter_id }})" href="#"><i class="fi-x small"></i></a></td>              
        </tr> 
        @endforeach   
    </table>
     <?php } ?>
    <?php } ?>
  </div>

</fieldset>
@stop
