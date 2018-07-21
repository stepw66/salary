
<?php if( isset($datasalary) ){ $url = 'emptype3/salary3/'.$datasalary->cid; ?>
    {{ Form::open(array( 'url' => $url, 'id'=>'form-addSalary3')) }}
<?php } else { ?>
    {{ Form::open(array( 'url' => 'emptype3/addSalary', 'id'=>'form-addSalary3')) }}
<?php } ?>
  <fieldset> 
   <input type="hidden" name="cidSalary3" id="cidSalary3" />

    <div class="row">     
      <div id="bank_acc_Salarytype3_error" class="large-6 columns">                   
        <label>ธนาคาร :
        <?php if( count($dataacc) > 0 ) {?>     
          @foreach( $dataacc as $a )  
             <?php if( isset($datasalary) ){ ?>
                <input type="radio" name="salarybanktype3" <?php if($datasalary->bank_acc_id == $a->acc_id) {echo "checked='true' ";}else{echo "";}  ?> value="{{ $a->acc_id }}" id="salarybanktype3-{{ $a->acc_id }}"><label for="salarybanktype3-{{ $a->acc_id }}">{{ $a->bank_name }}:{{ $a->bank_acc }}</label> 
           <?php } else { ?>
                <input type="radio" name="salarybanktype3" value="{{ $a->acc_id }}" id="salarybanktype3-{{ $a->acc_id }}"><label for="salarybanktype3-{{ $a->acc_id }}">{{ $a->bank_name }}:{{ $a->bank_acc }}</label> 
           <?php } ?>              
             
          @endforeach   
        <?php } else { ?>
            ยังไม่ได้เพิ่มบัญชีธนาคาร
        <?php } ?>  
        </label>     
        <small id="bank_acc_Salarytype3_d_error" class=""></small>
      </div>  
    </div>

     <div class="row">                            
        <div id='salary3_error' class="large-6 columns">           
           <label>เงินเดือน :
           <?php if( isset($datasalary) ){ ?>
                <input class="" name="salary3" id="salary3" type="text" value="{{ $datasalary->salary }}" placeholder="">
           <?php } else { ?>
                <input class="" name="salary3" id="salary3" type="text" placeholder="">
           <?php } ?>           
          </label>  
          <small id="salary3_d_error" class=""></small>
        </div>                     
        <div class="large-6 columns">           
           <label>เงินประจำตำแหน่ง ๆ :
            <?php if( isset($datasalary) ){ ?>
                <input class="" name="r_c3" id="r_c3" type="text" value="{{ $datasalary->r_c }}" placeholder="">
           <?php } else { ?>
                <input class="" name="r_c3" id="r_c3" type="text" placeholder="">
           <?php } ?>             
          </label>  
        </div>         
    </div>

    <div class="row">                 
        <div class="large-6 columns">           
           <label>เงินค่าตอบแทนพิเศษ :
           <?php if( isset($datasalary) ){ ?>
                <input class="" name="special3" id="special3" type="text" value="{{ $datasalary->special }}" placeholder="">
           <?php } else { ?>
                <input class="" name="special3" id="special3" type="text" placeholder="">
           <?php } ?>              
          </label>  
        </div>              
        <div class="large-6 columns">           
           <label>เงินช่วยเหลือบุตร :
           <?php if( isset($datasalary) ){ ?>
                <input class="" name="son3" id="son3" type="text" value="{{ $datasalary->son }}" placeholder="">
           <?php } else { ?>
                <input class="" name="son3" id="son3" type="text" placeholder="">
           <?php } ?>               
          </label>  
        </div>        
    </div>
      
    <div class="row"> 
      <div  class="large-6 columns">           
         <label>กบข/กสจ :
            <?php if( isset($datasalary) ){ ?>
                <input class="" name="kbk3" id="kbk3" type="text" value="{{ $datasalary->kbk }}" placeholder="">
           <?php } else { ?>
                <input class="" name="kbk3" id="kbk3" type="text" placeholder="">
           <?php } ?>          
        </label>  
      </div>   
      <div class="large-6 columns">           
           <label>เงินภาษี :
           <?php if( isset($datasalary) ){ ?>
                <input class="" name="tax3" id="tax3" type="text" value="{{ $datasalary->tax }}" placeholder="">
           <?php } else { ?>
                <input class="" name="tax3" id="tax3" type="text" placeholder="">
           <?php } ?>              
          </label>  
      </div>              
    </div>

    <div class="row">  
      <div class="large-6 columns">  
          <label>เลขที่เสียภาษี :
            <?php if( isset($datasalary) ){ ?>
                <input class="" name="tax_id3" id="tax_id3" type="text" value="{{ $datasalary->tax_id }}" placeholder="">
           <?php } else { ?>
                <input class="" name="tax_id3" id="tax_id3" type="text" placeholder="">
           <?php } ?>          
        </label>  
      </div>
    </div>

    <div class="row">
      <div class="large-6 columns">
        {{ Form::button( 'บันทึก', array( 'class'=>'small button', 'id' => 'btnSalaryAdd3' ) ) }}    
      </div>
    </div>
   
</fieldset>

{{ Form::close() }}

<script type="text/javascript">

  $( 'input[name="salary3"], input[name="r_c3"], input[name="special3"], input[name="son3"], input[name="kbk3"], input[name="tax3"], input[name="tax_id3"]' ).keydown( function(event) {
      var key = event.charCode || event.keyCode || 0;
     return (
     key == 8 || 
     key == 9 ||
     key == 46 ||
     key == 190 ||
     key == 110 ||
     (key >= 37 && key <= 40) ||
     (key >= 48 && key <= 57) ||
     (key >= 96 && key <= 105));
   }); 

  //-------------- add Acc ----------------//
  $("#btnSalaryAdd3").click(function(){    
      var $form = $( '#form-addSalary3' ), data = $form.serialize(), url = $form.attr( "action" );

      var posting = $.post( url, { formData: data } );

      posting.done(function( data ) {
          if( data.fail ) 
          {         
              $( '#salary3_error' ).removeClass( 'error' );
              $( '#bank_acc_Salarytype3_error' ).removeClass( 'error' );
              $( '#salary3_d_error' ).fadeOut(); 
              $( '#bank_acc_Salarytype3_d_error' ).fadeOut();  

              $.each(data.errors, function( index, value ) {             

                var errorDiv = '#'+index+'3_d_error';    
                var errorDiv2 = '#'+index+'_Salarytype3_d_error';  
                
                $( '#'+index+'3_error' ).addClass( 'error' );
                $( '#'+index+'_Salarytype3_error' ).addClass( 'error' );

                $( errorDiv ).fadeIn();
                $( errorDiv2 ).fadeIn();

                $( errorDiv ).addClass( 'error' );
                $( errorDiv ).empty().append( value );

                $( errorDiv2 ).addClass( 'error' );
                $( errorDiv2 ).empty().append( value );

              });                                 
          } 
          if( data.success == true ) 
          {    
              $( '#salary3_error' ).removeClass( 'error' );
              $( '#bank_acc_Salarytype3_error' ).removeClass( 'error' );
              $( '#salary3_d_error' ).fadeOut(); 
              $( '#bank_acc_Salarytype3_d_error' ).fadeOut();  

              $( "#form-addSalary3" ).get( 0 ).reset();  
              //alert( data.msg );  

              location.reload(true);       
          }
          if( data.success == false )
          {
            //alert( data.msg ); 
            location.reload(true); 
          }
      });   
  });


</script>