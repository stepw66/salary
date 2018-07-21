<div class="">
   <div class="large-12 columns">
		<table class="responsive">
		<tr>	
			<th width="170">ชื่อ</th>
			<th width="85">เงินเดือน</th>
			<th width="85">เงินตำแหน่ง</th>
			<th width="85">OT</th> 
			<th width="80">ไม่ทำเวชฯ</th> 
			<th width="80">ฉ.8</th>
			<th width="85">ตอบแทนอื่น</th> 
			<th width="78">รักษา/บุตร</th>  
			<th width="80">เดินทาง</th>   
			<th width="80">อื่น ๆ</th>    
		</tr> 
		<?php $i=0; ?>  
		@foreach( $data as $d )		      
		<tr>
			<input type="hidden" name="u_y1[]" id="u_y1<?php echo $i; ?>" value="<?php echo $d->y; ?>" />
			<input type="hidden" name="u_m1[]" id="u_m1<?php echo $i; ?>" value="<?php echo $d->m; ?>" />
			<input type="hidden" name="u_type1[]" id="u_type1<?php echo $i; ?>" value="<?php echo $d->type; ?>" />
			<input type="hidden" name="u_cid1[]" id="u_cid1<?php echo $i; ?>" value="<?php echo $d->cid; ?>" /> 
			<td>{{ $d->fullname }}</td>
			<td>{{ $d->salary }}</td>
			<td>{{ $d->r_c }}</td>
			<td>{{ $d->ot }}</td>
			<td>{{ $d->no_v }}</td>
			<td>{{ $d->ch8 }}</td>
			<td>{{ $d->sp_other }}</td>
			<td>{{ $d->son }}</td>
			<td>                  
            	<input name="u_travel1[]" id="u_travel1<?php echo $i; ?>" type="text" value="<?php echo $d->u_travel; ?>" >           
	        </td>               
	        <td>                  
	            <input name="u_other1[]" id="u_other1<?php echo $i; ?>" type="text" value="<?php echo $d->u_other; ?>" >           
	        </td> 
		</tr>	
		<?php $i++; ?>	        
		@endforeach
		</table>	
	</div>
	<div class="">
		<div class="small-2 small-centered columns">
			<?php if( count($data) > 0 ) {?>
			<a href="#" onclick="update_unitcosts()" class="button success small">บันทึก</a>
			<?php } ?>
		</div>
	</div>
</div>