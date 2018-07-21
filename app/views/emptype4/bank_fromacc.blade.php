
  {{ Form::open(array( 'url'=>'emptype4/addAcc', 'id'=>'form-addAcc4')) }}
  <fieldset>
  <h4>เพิ่มข้อมูล</h4>  
   <input type="hidden" name="cidAcc4" id="cidAcc4" />
    <div class="row">
      <div class="large-6 columns">
      {{ Form::label( 'level', 'ธนาคาร', array( 'class' => 'uk-form-label' ) ) }}    
        <select id="bank4" name="bank4" class=""> 
          @foreach( $bank as $a )              
           <option  value="{{ $a->bank_id }}">{{ $a->bank_name }}</option>        
          @endforeach
        </select>  
      </div>
    </div>

    <div class="row">     
      <div id='acc_error4' class="large-6 columns">           
         <label>เลขที่บัญชี :
            <input class="" maxlength="20" name="bank_acc4" id="bank_acc4" type="text" placeholder="เลขที่บัญชี">
        </label>      
        <small id="bank_acc_error4" class=""></small>
      </div>  
    </div>

    <div class="row">
      <div class="large-6 columns">
        {{ Form::button( 'บันทึก', array( 'class'=>'small button', 'id' => 'btnAccAdd4' ) ) }}    
      </div>
    </div>
   
</fieldset>

{{ Form::close() }}

<div id="view-acc4">  
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
              $a .= '<td><a title="ลบข้อมูล" onclick="delAcc4('.$dacc->cid.','.$dacc->acc_id.');" href="#"><i class="fi-x small"></i></a></td>';
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

 /*$( 'input[name="bank_acc4"]' ).keydown( function(event) {
      var key = event.charCode || event.keyCode || 0;
     return (
     key == 8 || 
     key == 9 ||
     key == 46 ||
     (key >= 37 && key <= 40) ||
     (key >= 48 && key <= 57) ||
     (key >= 96 && key <= 105));
   });*/ 


  //-------------- add Acc ----------------//
  $( "#btnAccAdd4" ).click(function(){    
      var $form = $( '#form-addAcc4' ), data = $form.serialize(), url = $form.attr( "action" );

      var posting = $.post( url, { formData: data } );

      posting.done(function( data ) {
          if( data.fail ) 
          {   
              $( '#acc_error4' ).addClass( 'error' );

              $( '#bank_acc_error4' ).fadeIn();
              $.each(data.errors, function( index, value ) {
                var errorDiv = '#'+index+'_error4';             
                $( errorDiv ).addClass( 'error' );
                $( errorDiv ).empty().append( value );
              });                                
          } 
          if( data.success == true ) 
          {    
            $( '#acc_error4' ).removeClass( 'error' );
            $( '#bank_acc_error4' ).fadeOut();   

            $( "#form-addAcc4" ).get( 0 ).reset();
            $( '#view-acc4' ).html( data.w );                 
          }
          if( data.success == false )
          {
            alert( data.msg );  
          }
      });   
  });

  function delAcc4( cid, acc_id )
  { 
    $.ajax({
      type:"GET",
      url:"deleteAcc4/"+acc_id+'/'+cid,
      data:"", 
      cache: false,     
      success:function( result ){ 
          if( result.success == true ) {
            $( '#view-acc4' ).html( result.w ); 
          }   
          if( result.success == false ){
             alert( result.msg );
          }                       
      }
    });
  }

</script>