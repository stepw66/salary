@extends('layouts.sidebar')
@section('content')

<ul class="breadcrumbs">
  <li><a href="{{ URL::to('home') }}">หน้าหลัก</a></li>
  <li class="unavailable"><a href="#">เงินรับอื่น ๆ - หักน้ำไฟ</a></li>
  <li class="current"><a href="#">ค่าไฟ</a></li>
</ul>

<fieldset>

  <h2>เพิ่มบ้านพัก</h2>  

  <div class="row">
    <div class="large-12 columns"> 
      <label>หมายเลขผู้ใช้ไฟ :
        <input class="" name="elec_number" id="elec_number" type="text" placeholder="กรอกหมายเลขผู้ใช้ไฟ">
      </label>      
    </div>  
     <div class="large-12 columns"> 
      <label>ชื่อบ้านพัก :
        <input class="" name="elec_home" id="elec_home" type="text" placeholder="กรอกชื่อบ้านพัก">
      </label>      
    </div>  
  </div>
  <hr />
  <div class="row">
    <div class="large-12 columns">
       <a href="#" id="addhome" class="button  [tiny small large]">บันทึก</a>
    </div>
  </div>

  <div id="view-home">
      <?php if( isset($home) ){ ?>
      <?php if( count($home) > 0 ){ ?>
      <table class="responsive">
        <tr>
          <th width="80">ลำดับ</th>
          <th>หมายเลขผู้ใช้ไฟ</th>  
          <th>ชื่อบ้านพัก</th>     
          <th width="50">ลบ</th>
        </tr>

        <?php $i=0; ?>
          @foreach( $home as $a )
          <?php $i++; ?>
          <tr>      
            <td>{{ $i }}</td>
            <td>{{ $a->elec_number }}</td>  
            <td>{{ $a->elec_home }}</td>                 
            <td><a title="ลบข้อมูล"  onclick="del_home({{ $a->home_id }})" href="#"><i class="fi-x small"></i></a></td>              
          </tr> 
          @endforeach   
      </table>
       <?php } ?>
      <?php } ?>
  </div>

</fieldset>
@stop
