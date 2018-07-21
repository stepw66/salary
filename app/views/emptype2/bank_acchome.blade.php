@extends('layouts.sidebar')
@section('content')

<ul class="breadcrumbs">
  <li><a href="{{ URL::to('home') }}">หน้าหลัก</a></li>
  <li class="unavailable"><a href="#">ลูกจ้างประจำ</a></li>
  <li class="current"><a href="#">ข้อมูลบัญชีธนาคาร</a></li>
</ul>

<div class="">
  <div class="medium-8 columns">
    <h3>ลูกจ้างประจำ : ข้อมูลบัญชีธนาคาร</h3>
  </div>
  <div class="medium-4 columns">
     {{ Form::open( array( 'url' => 'emptype2/search' ) ) }}
      <div class="row collapse">   
          <div class="large-8 small-9 columns">
            <input type="search" class="search" id="searchacc2" name="searchacc2" placeholder="ค้นหา CID, ชื่อ, นามสกุล"/>
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
        <th width="150">รหัสบัตรประชาชน</th>
        <th width="180">ชื่อ-นามสกุล</th>
        <th width="150">เลขที่บัญชี 1</th>
        <th width="150">เลขที่บัญชี 2</th>
        <th width="50">แก้ไข</th>
      </tr>

      <?php $i=0; ?>
        @foreach( $accall as $a )       
        <tr>      
          <td><?php echo $accall->getFrom()+$i; ?></td> 
          <td>{{ $a->cid }}</td>   
          <td>{{ $a->pname }}{{ $a->fname }} {{ $a->lname }}</td>  
          <td><span class="textacc1">{{ $a->acc1 }}</span></td>  
          <td><span class="textacc2">{{ $a->acc2 }}</span></td>        
          <td><a  title="แก้ไขข้อมูล" data-reveal-id="ModalAcc2" onclick="ModalAcc2( {{ $a->cid }}, '{{ $a->pname }}', '{{ $a->fname }}', '{{ $a->lname }}' )" href=""><i class="fi-page-edit small"></i></a></td>                        
        </tr> 
        <?php $i++; ?>
        @endforeach   
  </table>

  <?php echo $accall->links(); ?>

   </div>
</div>



<div id="ModalAcc2" class="reveal-modal" data-reveal>
  <h3>ข้อมูลบัญชีธนาคาร : <span id="ModalAccTitle2"></span></h3>

  <div id="fromAcc2"></div>
  
  <a class="close-reveal-modal">&#215;</a>
</div>



@stop
