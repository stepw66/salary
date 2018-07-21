@extends('layouts.sidebar')
@section('content')

<ul class="breadcrumbs">
  <li><a href="{{ URL::to('home') }}">หน้าหลัก</a></li>
  <li class="unavailable"><a href="#">ลูกจ้างประจำ</a></li>
  <li class="current"><a href="#">กรอกเงินเดือน</a></li>
</ul>

<div class="">
  <div class="medium-8 columns">
    <h3>ลูกจ้างประจำ : กรอกเงินเดือน</h3>
  </div>
  <div class="medium-4 columns">
     {{ Form::open( array( 'url' => 'emptype2/salary-search' ) ) }}
      <div class="row collapse">   
          <div class="large-8 small-9 columns">
            <input type="search" class="search" id="search_salary2" name="search_salary2" placeholder="ค้นหา CID, ชื่อ, นามสกุล"/>
          </div>
          <div class="large-4 small-3 columns">           
            {{ Form::submit( 'ค้นหา', array( 'class'=>'success postfix button' ) ) }} 
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
        <th width="140">เลขที่บัญชี</th>
        <th width="77">เงินเดือน</th>
        <th width="77">เงินประจำตำแหน่ง</th>
        <th width="95">เลขที่ภาษี</th>
        <th width="50">แก้ไข</th>
      </tr>

      <?php $i=0; ?>
        @foreach( $accall as $a )       
        <tr> 
          <td><?php echo $accall->getFrom()+$i; ?></td>             
          <td>{{ $a->cid }}</td>   
          <td>{{ $a->pname }}{{ $a->fname }} {{ $a->lname }}</td>  
          <td><span class="textacc1">{{ $a->bank_name }} {{ $a->bank_acc }}</span></td>  
          <td><span class="textsalary"><?php echo number_format( $a->salary, 2 ); ?></span></td> 
          <td><?php echo number_format( $a->r_c, 2 ); ?></td>           
          <td><span class="texttaxid">{{ $a->tax_id }}</span></td>    
          <td><a  title="แก้ไขข้อมูล" data-reveal-id="ModalSalary2" onclick="ModalSalary2( {{ $a->cid }}, '{{ $a->pname }}', '{{ $a->fname }}', '{{ $a->lname }}' )" href=""><i class="fi-page-edit small"></i></a></td>                        
        </tr> 
        <?php $i++;  ?>
        @endforeach   
  </table>

  <?php echo $accall->links(); ?>

   </div>
</div>



<div id="ModalSalary2" class="reveal-modal" data-reveal>
  <h3>กรอกเงินเดือน : <span id="ModalSalaryTitle2"></span></h3>

  <div id="fromSalary2"></div>
  
  <a class="close-reveal-modal">&#215;</a>
</div>



@stop
