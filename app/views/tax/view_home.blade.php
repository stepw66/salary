<?php if( count($data) > 0 ){ ?>

<table class="responsive">
  <tr>
    <th width="80">เลขผู้ใช้ไฟ</th>      
    <th width="150">บ้านพัก</th>
    <th width="150">ชื่อ-สกุล</th>
    <th width="30"></th>   
  </tr>

  <?php $i=0; ?>
    @foreach( $data as $a )    
    <tr>      
      <td> {{ $a->elec_number }} </td>                  
      <td> {{ $a->elec_home }} </td> 
      <td> {{ $a->namefull }} </td>
      <td> <a href="#" title="ลบ" onclick="del_emphome( {{ $a->home_id }}, {{ $a->cid }} )"><i class="fi-x small"></i></a> </td>             
    </tr> 
    <?php $i++; ?>
    @endforeach   
</table>

<?php } ?> 