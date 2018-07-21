<div class="row">
	<div class="large-3 columns"><a href="#" onclick="opentax3()" class="button small">ใบรับรองการหักภาษี</a></div>
</div>
<div class="row">
	<div class="large-12 columns">
      <label>เลือกปี พศ.
        <select name="taxyear3" id="taxyear3">
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
		<div id="showyear3" class="textrad"><?php echo 'ปีพศ. '.(date('Y')+543); ?></div>
	</div>
</div>
<div class="row">
	<input type="hidden" name="cidtax3" id="cidtax3" value="<?php echo $id; ?>" />  
	<input type="hidden" name="ytax3" id="ytax3" />
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
        <input type="hidden" name="orderdate3[]" id="orderdate3<?php echo $i; ?>" value="<?php echo $a->order_date; ?>" />              
        <tr>  
          <td>{{ $a->ordermonth }}</td>     
          <td><?php echo number_format( $a->salary, 2 ) ?></td>  
          <td><?php echo number_format( $a->r_other, 2 ) ?></td>          
          <td>                  
            <input name="taxTax3[]" id="taxTax3<?php echo $i; ?>" type="text" value="<?php echo $a->tax; ?>" >           
          </td>               
          <td>                  
            <input name="specialTax3[]" id="specialTax3<?php echo $i; ?>" type="text" value="<?php echo $a->special_m; ?>" >           
          </td> 
          <td> 
            <input name="rotherTax3[]" id="rotherTax3<?php echo $i; ?>" type="text" value="<?php echo $a->r_other; ?>" >
          </td>
          <td> 
            <input name="rptTax3[]" id="rptTax3<?php echo $i; ?>" type="text" value="<?php echo $a->r_pt; ?>" >
          </td>
          <td><a href="#" onclick="updateTax3( '{{ $a->cid }}' )" class="button success small">บันทึก</a></td>                                            
        </tr>  
        <?php $i++; ?>    
        @endforeach   
  </table>
   </div>
</div>

<script>
	
    function opentax3()
    {
    	var cid = $( '#cidtax3' ).val();
    	if( $("#ytax3").val() == '' )
    	{
			var y = null;
    	}
    	else
    	{
    		var y = $("#ytax3").val();
    	}
		 

		window.open('continuous/'+cid+'/'+y,'_blank','',false)
    }

	$("#taxyear3").change(function() {
		//alert( $("#taxyear1 option:selected").val() );
		//alert( $( '#cidtax' ).val() );
		var cid = $( '#cidtax3' ).val();
		var y = $("#taxyear3 option:selected").val();

		$.ajax({
			type:"GET",
			url:"fromTax/"+cid+'/'+y,
			data:"", 
			cache: false,     
			success:function( result ){				   			   
			   $( '#fromTax3' ).html( result );	
			   $( '#showyear3' ).html( 'ปีพศ. '+ ( eval(y)+eval(543) ) );	
			   $( '#cidtax3' ).val( cid )  
			   $( '#ytax3' ).val( y );
			}
	    });
	});
	
</script>