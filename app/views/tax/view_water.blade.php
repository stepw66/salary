<?php if( count($data) > 0 ){ ?>

<input type="hidden" id="unitwater" value="{{ $unitwater->unitwater }}" />
<input type="hidden" id="y" value="{{ $y }}" />
<input type="hidden" id="m" value="{{ $m }}" />

<table class="responsive">
  <tr>
    <th width="80">เลขมิเตอร์น้ำ</th>      
    <th width="150">ชื่อ-สกุล</th>
    <th width="80">จดก่อน</th>
    <th width="80">จดหลัง</th>
    <th width="80">หน่วยที่ใช้</th>
    <th width="80">จำนวนเงิน</th>
  </tr>

  <?php $i=0; ?>
    @foreach( $data as $a )    
    <tr>      
      <td>
        <input type="hidden" name="name_meter[]" id="name_meter<?php echo $i; ?>" value="{{ $a->name_meter }}" />
        {{ $a->name_meter }}
      </td>                  
      <td>
        <input type="hidden" name="cidwater[]" id="cidwater<?php echo $i; ?>" value="{{ $a->cid }}" />
        {{ $a->pname }}{{ $a->fname }} {{ $a->lname }}
      </td> 
      <td><input name="water_start[]" id="water_start<?php echo $i; ?>" type="text" value="<?php echo $a->water_start; ?>" ></td>
      <td><input name="water_end[]" id="water_end<?php echo $i; ?>" type="text" value="<?php echo $a->water_end; ?>" ></td>
      <td><input name="unit[]" id="unit<?php echo $i; ?>" type="text" value="<?php echo $a->unit; ?>" ></td>
      <td><input name="money[]" id="money<?php echo $i; ?>" type="text" value="<?php echo $a->money; ?>" ></td>            
    </tr> 
    <?php $i++; ?>
    @endforeach   
</table>

<div class="row">
  <div class="large-16 columns">
     <center> <a href="#" id="addwater" class="button  [tiny small large]">บันทึก</a> </center>
  </div>
</div>

<?php } ?>  

<script type="text/javascript">

  $( 'input[name="water_end[]"]' ).keyup(function(){
      var element_id = $(this).attr('id'); 
      var code = element_id.substring(9,element_id.length);

      var unit = $( '#water_end'+code ).val() - $( '#water_start'+code ).val();
      var money = unit * $( '#unitwater' ).val();

      $( '#unit'+code ).val('');
      $( '#money'+code ).val( '' );

      $( '#unit'+code ).val( unit );
      $( '#money'+code ).val( money );
  });

  $( '#addwater' ).click(function(){
      
      var i=0;
      var name_meter = [];
      var water_start = [];
      var water_end = [];
      var unit = [];
      var money = [];
      var cidwater = [];
      var y = $( '#y' ).val();
      var m = $( '#m' ).val();

      for( i; i <= $('input[name="water_start[]"]').length-1; i++ )
      {
         if( $('#water_start'+i).val() != '' && $('#water_end'+i).val() != '' && $('#unit'+i).val() != '' && $('#money'+i).val() != '' )
         {
            name_meter.push( $('#name_meter'+i).val() );
            water_start.push( $('#water_start'+i).val() );
            water_end.push( $('#water_end'+i).val() );
            unit.push( $('#unit'+i).val() );
            money.push( $('#money'+i).val() );
            cidwater.push( $('#cidwater'+i).val() );
         }
      }

      if( name_meter.length > 0 )
      {
          $.ajax({
            type:"POST",
            url:"savewater",
            data:"name_meter="+ name_meter +"&water_start="+ water_start +"&water_end="+ water_end +"&unit="+ unit +"&money="+ money +"&cidwater="+ cidwater +"&y="+ y +"&m="+ m, 
            cache: false,     
            success:function( result ){                     
                location.reload();                         
            }
          }); 
      }
      else
      {
         alert( 'กรุณรกรอกข้อมูล' );
      }

  });

</script>