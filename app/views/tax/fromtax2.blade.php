<div class="row">
	<div class="large-3 columns"><a href="#" onclick="opentax2()" class="button small">ใบรับรองการหักภาษี</a></div>
</div>
<div class="row">
	<div class="large-12 columns">
      <label>เลือกปี พศ.
        <select name="taxyear2" id="taxyear2">
        	<option value="0">กรุณาเลือกปี พศ.</option>
        	@foreach( $year as $y )
          		<option value="<?php echo ($y->year)-543; ?>">{{ $y->year }}</option>
          	@endforeach 
        </select>
      </label>
    </div>
</div>
<div class="row">
	<div class="large-12 columns">
		<div id="showyear2" class="textrad"><?php echo 'ปีพศ. '.(date('Y')+543); ?></div>
	</div>
</div>
<div class="row">
	<input type="hidden" name="cidtax2" id="cidtax2" value="<?php echo $id; ?>" />  
	<input type="hidden" name="ytax2" id="ytax2" />
	<div class="large-2 columns">เงินเดือน : <?php echo number_format( $sumdata->salary_sum, 2 ); ?></div>	
  <div class="large-2 columns">เงินอื่นๆ : <?php echo number_format( $sumdata->r_other_sum, 2 ); ?></div>
	<div class="large-2 columns">ภาษี : <?php echo number_format( $sumdata->salary_tax_sum, 2 ); ?></div>
	<div class="large-2 columns">ค่าตอบแทนรายเดือน : <?php echo number_format( $sumdata->salary_special_sum, 2 ); ?></div>
  <div class="large-2 columns">รับอื่น ๆ 1 : <?php echo number_format( $sumdata->salary_rother_sum, 2 ); ?></div>
  <div class="large-2 columns">รับอื่น ๆ 3: <?php echo number_format( $sumdata->salary_rpt_sum, 2 ); ?></div>
</div>
<div class="row">
   <div class="table-h">
      <table class="responsive">
      <tr>
        <th width="40">เดือน</th>
        <th width="100">เงินเดือน</th>  
        <th width="100">เงินอื่นๆ</th>     
        <th width="100">ภาษี</th> 
        <th width="100">ค่าตอบแทนรายเดือน</th>  
        <th width="100">รับอื่น ๆ หมวด 1</th> 
        <th width="100">รับอื่น ๆ หมวด 3</th> 
        <th width="100">#</th>    
      </tr>   

      <?php $i=0; ?>
        @foreach( $data as $a )  
        <input type="hidden" name="orderdate2[]" id="orderdate2<?php echo $i; ?>" value="<?php echo $a->order_date; ?>" />              
        <tr>  
          <td>{{ $a->ordermonth }}</td>     
          <td><?php echo number_format( $a->salary, 2 ) ?></td>   
          <td><?php echo number_format( $a->r_other, 2 ) ?></td>         
          <td>                  
            <input name="taxTax2[]" id="taxTax2<?php echo $i; ?>" type="text" value="<?php echo $a->tax; ?>" >           
          </td>               
          <td>                  
            <input name="specialTax2[]" id="specialTax2<?php echo $i; ?>" type="text" value="<?php echo $a->special_m; ?>" >           
          </td> 
          <td> 
            <input name="rotherTax2[]" id="rotherTax2<?php echo $i; ?>" type="text" value="<?php echo $a->r_other; ?>" >
          </td>
          <td> 
            <input name="rptTax2[]" id="rptTax2<?php echo $i; ?>" type="text" value="<?php echo $a->r_pt; ?>" >
          </td>
          <td><a href="#" onclick="updateTax2( '{{ $a->cid }}' )" class="button success small">บันทึก</a></td>                                            
        </tr>  
        <?php $i++; ?>    
        @endforeach   
  </table>
   </div>
</div>

<script>
	
    function opentax2()
    {
    	var cid = $( '#cidtax2' ).val();
    	if( $("#ytax2").val() == '' )
    	{
			var y = null;
    	}
    	else
    	{
    		var y = $("#ytax2").val();
    	}
		 

		window.open('continuous/'+cid+'/'+y,'_blank','',false)
    }

	$("#taxyear2").change(function() {
		//alert( $("#taxyear1 option:selected").val() );
		//alert( $( '#cidtax' ).val() );
		var cid = $( '#cidtax2' ).val();
		var y = $("#taxyear2 option:selected").val();

		$.ajax({
			type:"GET",
			url:"fromTax/"+cid+'/'+y,
			data:"", 
			cache: false,     
			success:function( result ){				   			   
			   $( '#fromTax2' ).html( result );	
			   $( '#showyear2' ).html( 'ปีพศ. '+ ( eval(y)+eval(543) ) );	
			   $( '#cidtax2' ).val( cid )  
			   $( '#ytax2' ).val( y );
			}
	    });
	});
	
</script>