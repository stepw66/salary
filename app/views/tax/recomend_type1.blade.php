@extends('layouts.sidebar')
@section('content')

<ul class="breadcrumbs">
  <li><a href="{{ URL::to('home') }}">หน้าหลัก</a></li>
  <li class="unavailable"><a href="#">พกส.(ปฏิบัติงาน)/พนักงานชั่วคราว</a></li>
  <li class="current"><a href="#">หนังสือรับรองการหักภาษี</a></li>
</ul>

<div class="">
  <div class="medium-8 columns">
    <h3>พกส.(ปฏิบัติงาน)/พนักงานชั่วคราว : หนังสือรับรองการหักภาษี</h3>
  </div>
  <div class="medium-4 columns">
     {{ Form::open( array( 'url' => 'tax1/search' ) ) }}
      <div class="row collapse">   
          <div class="large-8 small-9 columns">
            <input type="search" class="search" id="search_tax1" name="search_tax1" placeholder="ค้นหา CID, ชื่อ, นามสกุล"/>
          </div>
          <div class="large-4 small-3 columns">           
            {{ Form::submit( 'ค้นหา', array( 'class'=>'small success button expand' ) ) }} 
          </div>   
      </div>
     {{ Form::close() }}
  </div>
</div>

<div class="">
   <div class="large-12 columns">
      <table class="responsive">
      <tr>   
        <th width="40">#</th>    
        <th width="135">รหัสบัตรประชาชน</th>
        <th width="200">ชื่อ-นามสกุล</th>       
        <th width="50">ดูข้อมูล</th>
      </tr>

      <?php $i=0; ?>
        @foreach( $data as $a )       
        <tr> 
          <td><?php echo $data->getFrom()+$i; ?></td>             
          <td>{{ $a->cid }}</td>   
          <td>{{ $a->pname }}{{ $a->fname }} {{ $a->lname }}</td>           
          <td><a  title="แก้ไขข้อมูล" data-reveal-id="ModalTax1" onclick="ModalTax1( {{ $a->cid }}, '{{ $a->pname }}', '{{ $a->fname }}', '{{ $a->lname }}' )" href=""><i class="fi-magnifying-glass small"></i></a></td>                        
        </tr> 
        <?php $i++;  ?>
        @endforeach   
  </table>

  <?php echo $data->links(); ?>

   </div>
</div>



<div id="ModalTax1" class="reveal-modal full" data-reveal>
  <h3>ข้อมูลเงินเดือน : <span id="ModalTaxTitle1"></span></h3>
   
  <div id="fromTax1"></div>
  
  <a class="close-reveal-modal">&#215;</a>
</div>



@stop
