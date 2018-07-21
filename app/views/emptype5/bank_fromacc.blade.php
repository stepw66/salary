
  {{ Form::open(array( 'url'=>'emptype5/addAcc', 'id'=>'form-addAcc5')) }}
  <fieldset>
  <h4>เพิ่มข้อมูล</h4>  
   <input type="hidden" name="cidAcc5" id="cidAcc5" />
    <div class="row">
      <div class="large-6 columns">
      {{ Form::label( 'level', 'ธนาคาร', array( 'class' => 'uk-form-label' ) ) }}    
        <select id="bank5" name="bank5" class=""> 
          @foreach( $bank as $a )              
           <option  value="{{ $a->bank_id }}">{{ $a->bank_name }}</option>        
          @endforeach
        </select>  
      </div>
    </div>

    <div class="row">     
      <div id='acc_error5' class="large-6 columns">           
         <label>เลขที่บัญชี :
            <input class="" maxlength="20" name="bank_acc5" id="bank_acc5" type="text" placeholder="เลขที่บัญชี">
        </label>      
        <small id="bank_acc_error5" class=""></small>
      </div>  
    </div>

    <div class="row">
      <div class="large-6 columns">
        {{ Form::button( 'บันทึก', array( 'class'=>'small button', 'id' => 'btnAccAdd5' ) ) }}    
      </div>
    </div>
   
</fieldset>

{{ Form::close() }}

<div id="view-acc5">  
    <?php
        if( isset($dataacc) )
        {
          if( count($dataacc) > 0 )
          {
            $a = '<table  class="responsive" >';        
            $a .= '<tbody>';
            $ai=0;
            
            $a .= '<tr> <th  width="40" >ลำดับ</th> <th width="250" >ธนาคาร</th> <th width="250" >เลขที่บัญชี</th> <th width="40" >ลบ</th> </tr>';
            
            foreach ($dataacc as $dacc) {
              $ai++;
              $a .= '<tr>';
              $a .= '<td>'.$ai.'</td>';           
              $a .= '<td>'.$dacc->bank_name.'</td>';
              $a .= '<td>'.$dacc->bank_acc.'</td>';
              $a .= '<td><a title="ลบข้อมูล" onclick="delAcc5('.$dacc->cid.','.$dacc->acc_id.');" href="#"><i class="fi-x small"></i></a></td>';
              $a .= '</tr>';
            }       
            $a .= '</tbody>';
            $a .= '</table>';

            echo $a;
          }
        }
    ?>
  </div>


<script type="text/javascript">

  //-------------- add Acc ----------------//
  $( "#btnAccAdd5" ).click(function(){    
      var $form = $( '#form-addAcc5' ), data = $form.serialize(), url = $form.attr( "action" );

      var posting = $.post( url, { formData: data } );

      posting.done(function( data ) {
          if( data.fail ) 
          {   
              $( '#acc_error5' ).addClass( 'error' );

              $( '#bank_acc_error5' ).fadeIn();
              $.each(data.errors, function( index, value ) {
                var errorDiv = '#'+index+'_error5';             
                $( errorDiv ).addClass( 'error' );
                $( errorDiv ).empty().append( value );
              });                                
          } 
          if( data.success == true ) 
          {    
            $( '#acc_error5' ).removeClass( 'error' );
            $( '#bank_acc_error5' ).fadeOut();   

            $( "#form-addAcc5" ).get( 0 ).reset();
            $( '#view-acc5' ).html( data.w );                 
          }
          if( data.success == false )
          {
            alert( data.msg );  
          }
      });   
  });

  function delAcc5( cid, acc_id )
  { 
    $.ajax({
      type:"GET",
      url:"deleteAcc5/"+acc_id+'/'+cid,
      data:"", 
      cache: false,     
      success:function( result ){ 
          if( result.success == true ) {
            $( '#view-acc5' ).html( result.w ); 
          }   
          if( result.success == false ){
             alert( result.msg );
          }                       
      }
    });
  }

</script>