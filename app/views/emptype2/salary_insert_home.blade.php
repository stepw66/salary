@extends('layouts.sidebar')
@section('content')

<ul class="breadcrumbs">
  <li><a href="{{ URL::to('home') }}">หน้าหลัก</a></li>
  <li class="unavailable"><a href="#">ลูกจ้างประจำ</a></li>
  <li class="current"><a href="#">บันทึกเงินเดือน</a></li>
</ul>

<div class="">
  <div class="medium-8 columns">
    <h3>ลูกจ้างประจำ : บันทึกเงินเดือน</h3>
  </div>
  <div class="medium-4 columns">
     {{ Form::open( array( 'url' => 'emptype2/salary_insert-search' ) ) }}
      <div class="row collapse">   
          <div class="large-8 small-9 columns">
            <input type="search" class="search" id="search_salary_insert2" name="search_salary_insert2" placeholder="ค้นหา CID, ชื่อ, นามสกุล"/>
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
        <th width="50">#</th>    
        <th width="100">รหัสบัตรประชาชน</th>
        <th width="300">ชื่อ-นามสกุล</th>
        <th width="100">วันที่ล่าสุด</th>
        <th width="50">แก้ไข</th>
      </tr>

      <?php $i=0; ?>
        @foreach( $accall as $a )       
        <tr> 
          <td><?php echo $accall->getFrom()+$i; ?></td>             
          <td>{{ $a->cid }}</td>   
          <td>{{ $a->pname }}{{ $a->fname }} {{ $a->lname }}</td>  
          
          <?php if( date("Y", strtotime($a->order_date)) == date('Y') && date("m", strtotime($a->order_date)) == date('m') && $a->saralyck > 0 ) { ?>
          <td class="td-green">
          <?php }else if(  date("Y", strtotime($a->order_date)) == date('Y') && date("m", strtotime($a->order_date)) == date('m') && $a->saralyck == 0 && $a->otck > 0 ){ ?>
          <td class="td-blue">
          <?php } else { ?>
          <td>
          <?php } ?>
              <span class="textrad"><?php echo (($a->order_date == '0000-00-00' || $a->order_date == '') ? '-':date("d-m", strtotime($a->order_date)).'-'.(date("Y", strtotime($a->order_date))+543)); ?></span>
          </td> 

          <?php if( $a->salary != '' ) { ?>
          <td><a  title="แก้ไขข้อมูล" data-reveal-id="Modalsalary_insert2" onclick="Modalsalary_insert2( {{ $a->cid }}, '{{ $a->pname }}', '{{ $a->fname }}', '{{ $a->lname }}', '{{ $a->bank }}', '{{ $a->bank_acc_id }}', '{{ $a->bank_acc }}', '{{ $a->salary }}', '{{ $a->r_c }}', '{{ $a->special }}', '{{ $a->son }}', '{{ $a->kbk }}', '{{ $a->tax }}', '{{ $a->tax_id }}' )" href=""><i class="fi-page-edit small"></i></a></td>                        
          <?php } ?>
          <?php if( $a->salary == '') { ?>
          <td>-</td>
          <?php } ?> 
        </tr> 
        <?php $i++;  ?>
        @endforeach   
  </table>

  <?php echo $accall->links(); ?>

   </div>
</div>



<div id="Modalsalary_insert2" class="reveal-modal" data-reveal>
  <h3>บันทึกเงินเดือน : <span id="Modalsalary_insertTitle2"></span></h3>

  <div id="fromsalary_insert2"></div>
  
  <a class="close-reveal-modal">&#215;</a>
</div>



@stop
