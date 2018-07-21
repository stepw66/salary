
<?php if( isset($datasalary) ){ $url = 'emptype5/salary5/'.$datasalary->cid; ?>
    {{ Form::open(array( 'url' => $url, 'id'=>'form-addSalary5')) }}
<?php } else { ?>
    {{ Form::open(array( 'url' => 'emptype5/addSalary', 'id'=>'form-addSalary5')) }}
<?php } ?>
  <fieldset> 
   <input type="hidden" name="cidSalary5" id="cidSalary5" />

    <div class="row">     
      <div id="bank_acc_Salarytype5_error" class="large-6 columns">                   
        <label>ธนาคาร :
        <?php if( count($dataacc) > 0 ) {?>     
          @foreach( $dataacc as $a )  
             <?php if( isset($datasalary) ){ ?>
                <input type="radio" name="salarybanktype5" <?php if($datasalary->bank_acc_id == $a->acc_id) {echo "checked='true' ";}else{echo "";}  ?> value="{{ $a->acc_id }}" id="salarybanktype5-{{ $a->acc_id }}"><label for="salarybanktype5-{{ $a->acc_id }}">{{ $a->bank_name }}:{{ $a->bank_acc }}</label> 
           <?php } else { ?>
                <input type="radio" name="salarybanktype5" value="{{ $a->acc_id }}" id="salarybanktype5-{{ $a->acc_id }}"><label for="salarybanktype5-{{ $a->acc_id }}">{{ $a->bank_name }}:{{ $a->bank_acc }}</label> 
           <?php } ?>              
             
          @endforeach   
        <?php } else { ?>
            ยังไม่ได้เพิ่มบัญชีธนาคาร
        <?php } ?>  
        </label>     
        <small id="bank_acc_Salarytype5_d_error" class=""></small>
      </div>  
    </div>

     <div class="row">                            
        <div id='salary5_error' class="large-6 columns">           
           <label>เงินเดือน :
           <?php if( isset($datasalary) ){ ?>
                <input class="" name="salary5" id="salary5" type="text" value="{{ $datasalary->salary }}" placeholder="">
           <?php } else { ?>
                <input class="" name="salary5" id="salary5" type="text" placeholder="">
           <?php } ?>           
          </label>  
          <small id="salary5_d_error" class=""></small>
        </div>                     
        <div class="large-6 columns">           
           <label>เงินอื่น ๆ :
            <?php if( isset($datasalary) ){ ?>
                <input class="" name="salaryother5" id="salaryother5" type="text" value="{{ $datasalary->salary_other }}" placeholder="">
           <?php } else { ?>
                <input class="" name="salaryother5" id="salaryother5" type="text" placeholder="">
           <?php } ?>             
          </label>  
        </div>         
    </div>

    <div class="row">                 
        <div class="large-6 columns">           
           <label>เงินประกันสังคอม :
           <?php if( isset($datasalary) ){ ?>
                <input class="" name="salarysso5" id="salarysso5" type="text" value="{{ $datasalary->salary_sso }}" placeholder="">
           <?php } else { ?>
                <input class="" name="salarysso5" id="salarysso5" type="text" placeholder="">
           <?php } ?>              
          </label>  
        </div>              
        <div class="large-6 columns">           
           <label>ฌกส :
           <?php if( isset($datasalary) ){ ?>
                <input class="" name="salarycpk5" id="salarycpk5" type="text" value="{{ $datasalary->salary_cpk }}" placeholder="">
           <?php } else { ?>
                <input class="" name="salarycpk5" id="salarycpk5" type="text" placeholder="">
           <?php } ?>               
          </label>  
        </div>        
    </div>
      
    <div class="row">     
      <div  class="large-6 columns">           
         <label>เลขที่เสียภาษี :
            <?php if( isset($datasalary) ){ ?>
                <input class="" name="salarytaxid5" id="salarytaxid5" type="text" value="{{ $datasalary->tax_id }}" placeholder="">
           <?php } else { ?>
                <input class="" name="salarytaxid5" id="salarytaxid5" type="text" placeholder="">
           <?php } ?>          
        </label>  
      </div>  
    </div>

    <div class="row">
      <div class="large-6 columns">
        {{ Form::button( 'บันทึก', array( 'class'=>'small button', 'id' => 'btnSalaryAdd5' ) ) }}    
      </div>
    </div>
   
</fieldset>

{{ Form::close() }}

<script type="text/javascript">

  $( 'input[name="salary5"], input[name="salaryother5"], input[name="salarysso5"], input[name="salarycpk5"], input[name="salarytaxid5"]' ).keydown( function(event) {
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

   $( '#salary5, #salaryother5' ).keyup(function(){
      if( $( '#salary5' ).val() != '' )
      {
         var money = parseFloat( $( '#salary5' ).val() ) + parseFloat( $( '#salaryother5' ).val() );
        if( money >= 15000 ){
            var sso = ( parseFloat(15000) * 5 ) / 100;
        }else{
            var sso = ( parseFloat(money) * 5 ) / 100;
        }
       
        $( '#salarysso5' ).val( Math.round( sso ) );
      } 
  });

  //-------------- add Acc ----------------//
  $("#btnSalaryAdd5").click(function(){    
      var $form = $( '#form-addSalary5' ), data = $form.serialize(), url = $form.attr( "action" );

      var posting = $.post( url, { formData: data } );

      posting.done(function( data ) {
          if( data.fail ) 
          {         
              $( '#salary5_error' ).removeClass( 'error' );
              $( '#bank_acc_Salarytype5_error' ).removeClass( 'error' );
              $( '#salary5_d_error' ).fadeOut(); 
              $( '#bank_acc_Salarytype5_d_error' ).fadeOut();  

              $.each(data.errors, function( index, value ) {             

                var errorDiv = '#'+index+'5_d_error';    
                var errorDiv2 = '#'+index+'_Salarytype5_d_error';  
                
                $( '#'+index+'5_error' ).addClass( 'error' );
                $( '#'+index+'_Salarytype5_error' ).addClass( 'error' );

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
              $( '#salary5_error' ).removeClass( 'error' );
              $( '#bank_acc_Salarytype5_error' ).removeClass( 'error' );
              $( '#salary5_d_error' ).fadeOut(); 
              $( '#bank_acc_Salarytype5_d_error' ).fadeOut();  

              $( "#form-addSalary5" ).get( 0 ).reset();  
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