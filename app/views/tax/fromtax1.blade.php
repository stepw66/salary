<div class="row">
	<div class="large-3 columns"><a href="#" onclick="opentax1()" class="button small">ใบรับรองการหักภาษี</a></div>
</div>
<div class="row">
	<div class="large-12 columns">
      <label>เลือกปี พศ.
        <select name="taxyear1" id="taxyear1">
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
		<div id="showyear" class="textrad"><?php echo 'ปีพศ. '.(date('Y')+543); ?></div>
	</div>
</div>
<div class="row">
	<input type="hidden" name="cidtax" id="cidtax" value="<?php echo $id; ?>" />  
	<input type="hidden" name="ytax" id="ytax" />
	<div class="large-2 columns">เงินเดือน : <?php echo number_format( $sumdata->salary_sum, 2 ); ?></div>
  <div class="large-2 columns">เงินอื่นๆ : <?php echo number_format( $sumdata->salary_other_sum, 2 ); ?></div>
	<div class="large-2 columns">ประกันสังคม : <?php echo number_format( $sumdata->salary_sso_sum, 2 ); ?></div>
	<div class="large-1 columns">ภาษี : <?php echo number_format( $sumdata->salary_tax_sum, 2 ); ?></div>
	<div class="large-2 columns">ค่าตอบแทน : <?php echo number_format( $sumdata->salary_special_sum, 2 ); ?></div>	
  <div class="large-1 columns">พตส : <?php echo number_format( $sumdata->salary_pts_sum, 2 ); ?></div>
  <div class="large-2 columns">OT : <?php echo number_format( $sumdata->salary_ot_sum, 2 ); ?></div>	
</div>
<div class="row"> 
    <div class="table-h">
    <table class="responsive">
      <tr>
        <th width="45">เดือน</th>
        <th width="100">เงินเดือน</th>
        <th width="100">เงินอื่นๆ</th>
        <th width="100">ประกันสังคม</th>
        <th width="100">ภาษี</th> 
        <th width="100">ค่าตอบแทน</th> 
        <th width="100">พตส</th> 
        <th width="100">OT</th>     
        <th width="100">#</th>    
      </tr>   

      <?php $i=0; ?>
        @foreach( $data as $a )          
        <input type="hidden" name="orderdate1[]" id="orderdate1<?php echo $i; ?>" value="<?php echo $a->order_date; ?>" />              
        <tr>  
          <td>{{ $a->ordermonth }}</td>     
          <td>{{ $a->salary }}</td>  
          <td>{{ $a->salary_other }}</td>  
          <td>{{ $a->salary_sso }}</td>   
          <td>                  
            <input name="taxTax1[]" id="taxTax1<?php echo $i; ?>" type="text" value="<?php echo $a->tax; ?>" >           
          </td>               
          <td>                  
            <input name="specialTax1[]" id="specialTax1<?php echo $i; ?>" type="text" value="<?php echo $a->special; ?>" >           
          </td> 
          <td>
            <input name="ptsTax1[]" id="ptsTax1<?php echo $i; ?>" type="text" value="<?php echo $a->pts; ?>" > 
          </td>
          <td>
            <input name="otTax1[]" id="otTax1<?php echo $i; ?>" type="text" value="<?php echo $a->ot; ?>" > 
          </td>         
          <td><a href="#" onclick="updateTax1( '{{ $a->cid }}' )" class="button success small">บันทึก</a></td>                                   
        </tr>  
        <?php $i++; ?>    
        @endforeach   
  </table>
  </div>
</div>

<script>
	
    function opentax1()
    {
    	var cid = $( '#cidtax' ).val();
    	if( $("#ytax").val() == '' )
    	{
			var y = null;
    	}
    	else
    	{
    		var y = $("#ytax").val();
    	}
		 

		window.open('continuous/'+cid+'/'+y,'_blank','',false)
    }

	$("#taxyear1").change(function() {
		//alert( $("#taxyear1 option:selected").val() );
		//alert( $( '#cidtax' ).val() );
		var cid = $( '#cidtax' ).val();
		var y = $("#taxyear1 option:selected").val();

		$.ajax({
			type:"GET",
			url:"fromTax/"+cid+'/'+y,
			data:"", 
			cache: false,     
			success:function( result ){				   			   
			   $( '#fromTax1' ).html( result );	
			   $( '#showyear' ).html( 'ปีพศ. '+ ( eval(y)+eval(543) ) );	
			   $( '#cidtax' ).val( cid )  
			   $( '#ytax' ).val( y );
			}
	    });
	});
	
</script>