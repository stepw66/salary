
<?php if( isset($datasalary) ){ $url = 'emptype2/salary2/'.$datasalary->cid; ?>
    {{ Form::open(array( 'url' => $url, 'id'=>'form-addSalary2')) }}
<?php } else { ?>
    {{ Form::open(array( 'url' => 'emptype2/addSalary', 'id'=>'form-addSalary2')) }}
<?php } ?>
  <fieldset> 
   <input type="hidden" name="cidSalary2" id="cidSalary2" />

    <div class="row">     
      <div id="bank_acc_Salarytype2_error" class="large-6 columns">                   
        <label>ธนาคาร :
        <?php if( count($dataacc) > 0 ) {?>     
          @foreach( $dataacc as $a )  
             <?php if( isset($datasalary) ){ ?>
                <input type="radio" name="salarybanktype2" <?php if($datasalary->bank_acc_id == $a->acc_id) {echo "checked='true' ";}else{echo "";}  ?> value="{{ $a->acc_id }}" id="salarybanktype2-{{ $a->acc_id }}"><label for="salarybanktype2-{{ $a->acc_id }}">{{ $a->bank_name }}:{{ $a->bank_acc }}</label> 
           <?php } else { ?>
                <input type="radio" name="salarybanktype2" value="{{ $a->acc_id }}" id="salarybanktype2-{{ $a->acc_id }}"><label for="salarybanktype2-{{ $a->acc_id }}">{{ $a->bank_name }}:{{ $a->bank_acc }}</label> 
           <?php } ?>              
             
          @endforeach   
        <?php } else { ?>
            ยังไม่ได้เพิ่มบัญชีธนาคาร
        <?php } ?>  
        </label>     
        <small id="bank_acc_Salarytype2_d_error" class=""></small>
      </div>  
    </div>

     <div class="row">                            
        <div id='salary2_error' class="large-6 columns">           
           <label>เงินเดือน :
           <?php if( isset($datasalary) ){ ?>
                <input class="" name="salary2" id="salary2" type="text" value="{{ $datasalary->salary }}" placeholder="">
           <?php } else { ?>
                <input class="" name="salary2" id="salary2" type="text" placeholder="">
           <?php } ?>           
          </label>  
          <small id="salary2_d_error" class=""></small>
        </div>                     
        <div class="large-6 columns">           
           <label>เงินประจำตำแหน่ง ๆ :
            <?php if( isset($datasalary) ){ ?>
                <input class="" name="r_c2" id="r_c2" type="text" value="{{ $datasalary->r_c }}" placeholder="">
           <?php } else { ?>
                <input class="" name="r_c2" id="r_c2" type="text" placeholder="">
           <?php } ?>             
          </label>  
        </div>         
    </div>

    <div class="row">                 
        <div class="large-6 columns">           
           <label>เงินค่าตอบแทนพิเศษ :
           <?php if( isset($datasalary) ){ ?>
                <input class="" name="special2" id="special2" type="text" value="{{ $datasalary->special }}" placeholder="">
           <?php } else { ?>
                <input class="" name="special2" id="special2" type="text" placeholder="">
           <?php } ?>              
          </label>  
        </div>              
        <div class="large-6 columns">           
           <label>เงินช่วยเหลือบุตร :
           <?php if( isset($datasalary) ){ ?>
                <input class="" name="son2" id="son2" type="text" value="{{ $datasalary->son }}" placeholder="">
           <?php } else { ?>
                <input class="" name="son2" id="son2" type="text" placeholder="">
           <?php } ?>               
          </label>  
        </div>        
    </div>
      
    <div class="row"> 
      <div  class="large-6 columns">           
         <label>กบข/กสจ :
            <?php if( isset($datasalary) ){ ?>
                <input class="" name="kbk2" id="kbk2" type="text" value="{{ $datasalary->kbk }}" placeholder="">
           <?php } else { ?>
                <input class="" name="kbk2" id="kbk2" type="text" placeholder="">
           <?php } ?>          
        </label>  
      </div>   
      <div class="large-6 columns">           
           <label>เงินภาษี :
           <?php if( isset($datasalary) ){ ?>
                <input class="" name="tax2" id="tax2" type="text" value="{{ $datasalary->tax }}" placeholder="">
           <?php } else { ?>
                <input class="" name="tax2" id="tax2" type="text" placeholder="">
           <?php } ?>              
          </label>  
      </div>              
    </div>

    <div class="row">  
      <div class="large-6 columns">  
          <label>เลขที่เสียภาษี :
            <?php if( isset($datasalary) ){ ?>
                <input class="" name="tax_id2" id="tax_id2" type="text" value="{{ $datasalary->tax_id }}" placeholder="">
           <?php } else { ?>
                <input class="" name="tax_id2" id="tax_id2" type="text" placeholder="">
           <?php } ?>          
        </label>  
      </div>
    </div>

    <div class="row">
      <div class="large-6 columns">
        {{ Form::button( 'บันทึก', array( 'class'=>'small button', 'id' => 'btnSalaryAdd2' ) ) }}    
      </div>
    </div>
   
</fieldset>

{{ Form::close() }}

<script type="text/javascript">

  $( 'input[name="salary2"], input[name="r_c2"], input[name="special2"], input[name="son2"], input[name="kbk2"], input[name="tax2"], input[name="tax_id2"]' ).keydown( function(event) {
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
  $("#btnSalaryAdd2").click(function(){    
      var $form = $( '#form-addSalary2' ), data = $form.serialize(), url = $form.attr( "action" );

      var posting = $.post( url, { formData: data } );

      posting.done(function( data ) {
          if( data.fail ) 
          {         
              $( '#salary2_error' ).removeClass( 'error' );
              $( '#bank_acc_Salarytype2_error' ).removeClass( 'error' );
              $( '#salary2_d_error' ).fadeOut(); 
              $( '#bank_acc_Salarytype2_d_error' ).fadeOut();  

              $.each(data.errors, function( index, value ) {             

                var errorDiv = '#'+index+'2_d_error';    
                var errorDiv2 = '#'+index+'_Salarytype2_d_error';  
                
                $( '#'+index+'2_error' ).addClass( 'error' );
                $( '#'+index+'_Salarytype2_error' ).addClass( 'error' );

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
              $( '#salary2_error' ).removeClass( 'error' );
              $( '#bank_acc_Salarytype2_error' ).removeClass( 'error' );
              $( '#salary2_d_error' ).fadeOut(); 
              $( '#bank_acc_Salarytype2_d_error' ).fadeOut();  

              $( "#form-addSalary2" ).get( 0 ).reset();  
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