<?php if( count($data) > 0 ){ ?>

<input type="hidden" id="y" value="{{ $y }}" />
<input type="hidden" id="m" value="{{ $m }}" />

<table class="responsive">
  <tr>
    <th width="80">เลขผู้ใช้ไฟ</th>      
    <th width="150">บ้านพัก</th>
    <th width="150">ชื่อ-สกุล</th>
    <th width="80">จำนวนเงิน</th>
  </tr>

  <?php $i=0; ?>
    @foreach( $data as $a )    
    <tr>      
      <td>
        <input type="hidden" name="elec_number[]" id="elec_number<?php echo $i; ?>" value="{{ $a->elec_number }}" />
        {{ $a->elec_number }}
      </td> 
      <td>
        <input type="hidden" name="elec_home[]" id="elec_home<?php echo $i; ?>" value="{{ $a->elec_home }}" /> 
        {{ $a->elec_home }} 
      </td>                 
      <td>
        <input type="hidden" name="cidelec[]" id="cidelec<?php echo $i; ?>" value="{{ $a->cid }}" />
        {{ $a->pname }}{{ $a->fname }} {{ $a->lname }}
      </td>     
      <td><input name="moneyelec[]" id="moneyelec<?php echo $i; ?>" type="text" value="<?php echo $a->money; ?>" ></td>            
    </tr> 
    <?php $i++; ?>
    @endforeach   
</table>

<div class="row">
  <div class="large-16 columns">
     <center> <a href="#" id="addelec" class="button  [tiny small large]">บันทึก</a> </center>
  </div>
</div>

<?php } ?>

<script type="text/javascript">
  $( '#addelec' ).click(function(){

      var i=0;
      var elec_number = [];
      var elec_home = [];
      var cidelec = [];     
      var moneyelec = [];
     
      var y = $( '#y' ).val();
      var m = $( '#m' ).val();

      for( i; i <= $('input[name="elec_number[]"]').length-1; i++ )
      {
         if( $('#moneyelec'+i).val() != ''  )
         {
            elec_number.push( $('#elec_number'+i).val() );          
            elec_home.push( $('#elec_home'+i).val() );
            cidelec.push( $('#cidelec'+i).val() );
            moneyelec.push( $('#moneyelec'+i).val() );
         }
      }

      if( elec_number.length > 0 )
      {
          $.ajax({
            type:"POST",
            url:"saveelec",
            data:"elec_number="+elec_number+"&elec_home="+ elec_home +"&cidelec="+ cidelec +"&moneyelec="+ moneyelec +"&y="+ y +"&m="+ m, 
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