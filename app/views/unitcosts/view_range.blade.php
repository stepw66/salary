@extends('layouts.sidebar')
@section('content')
<?php 
	$error_message = Session::get('error_message');
?>
<ul class="breadcrumbs">
  <li><a href="{{ URL::to('home') }}">หน้าหลัก</a></li>
  <li class="unavailable"><a href="#">หน่วยต้นทุน</a></li>
  <li class="unavailable"><a href="#">หน่วยต้นทุน</a></li>
  <li class="current"><a href="#">กำหนดช่วง OT, ประกันสังคม</a></li>
</ul>

<h2>กำหนดช่วง OT, ประกันสังคม ในการดึงข้อมูล</h2> 
<div class="">
   <div class="large-12 columns">
        <table class="responsive">
        <tr>	
            <th width="170">#</th>			
            <th width="85">เริ่ม(เดือน)</th> 			
            <th width="85">ถึง(เดือน)</th> 		
        </tr> 
        <?php $i=0; ?>  
        @foreach( $data as $d )		      
        <tr>
            <input type="hidden" name="r_name[]" id="r_name<?php echo $i; ?>" value="<?php echo $d->name; ?>" />
            <td>{{ $d->name }}</td>						
            <td>                  
                <input name="r_start[]" id="r_start<?php echo $i; ?>" type="text" value="<?php echo $d->r_start; ?>" >           
            </td>               
            <td>                  
                <input name="r_end[]" id="r_end<?php echo $i; ?>" type="text" value="<?php echo $d->r_end; ?>" >           
            </td> 
        </tr>	
        <?php $i++; ?>	        
        @endforeach
        </table>	
    </div>
</div>
<div class="">
    <div class="small-2 small-centered columns">			
        <a href="#" onclick="update_range()" class="button success small">บันทึก</a>			
    </div>
</div>


@stop
