
<?php if( isset($datasalary) ){ $url = 'emptype1/salary1/'.$datasalary->cid; ?>
    {{ Form::open(array( 'url' => $url, 'id'=>'form-addSalary1')) }}
<?php } else { ?>
    {{ Form::open(array( 'url' => 'emptype1/addSalary', 'id'=>'form-addSalary1')) }}
<?php } ?>
  <fieldset> 
   <input type="hidden" name="cidSalary1" id="cidSalary1" />

    <div class="row">     
      <div id="bank_acc_Salarytype1_error" class="large-6 columns">                   
        <label>ธนาคาร :
        <?php if( count($dataacc) > 0 ) {?>     
          @foreach( $dataacc as $a )  
             <?php if( isset($datasalary) ){ ?>
                <input type="radio" name="salarybanktype1" <?php if($datasalary->bank_acc_id == $a->acc_id) {echo "checked='true' ";}else{echo "";}  ?> value="{{ $a->acc_id }}" id="salarybanktype1-{{ $a->acc_id }}"><label for="salarybanktype1-{{ $a->acc_id }}">{{ $a->bank_name }}:{{ $a->bank_acc }}</label> 
           <?php } else { ?>
                <input type="radio" name="salarybanktype1" value="{{ $a->acc_id }}" id="salarybanktype1-{{ $a->acc_id }}"><label for="salarybanktype1-{{ $a->acc_id }}">{{ $a->bank_name }}:{{ $a->bank_acc }}</label> 
           <?php } ?>              
             
          @endforeach   
        <?php } else { ?>
            ยังไม่ได้เพิ่มบัญชีธนาคาร
        <?php } ?>  
        </label>     
        <small id="bank_acc_Salarytype1_d_error" class=""></small>
      </div>  
    </div>

     <div class="row">                            
        <div id='salary1_error' class="large-6 columns">           
           <label>เงินเดือน :
           <?php if( isset($datasalary) ){ ?>
                <input class="" name="salary1" id="salary1" type="text" value="{{ $datasalary->salary }}" placeholder="">
           <?php } else { ?>
                <input class="" name="salary1" id="salary1" type="text" placeholder="">
           <?php } ?>           
          </label>  
          <small id="salary1_d_error" class=""></small>
        </div>                     
        <div class="large-6 columns">           
           <label>เงินอื่น ๆ :
            <?php if( isset($datasalary) ){ ?>
                <input class="" name="salaryother1" id="salaryother1" type="text" value="{{ $datasalary->salary_other }}" placeholder="">
           <?php } else { ?>
                <input class="" name="salaryother1" id="salaryother1" type="text" placeholder="">
           <?php } ?>             
          </label>  
        </div>         
    </div>

    <div class="row">                 
        <div class="large-6 columns">           
           <label>เงินประกันสังคอม :
           <?php if( isset($datasalary) ){ ?>
                <input class="" name="salarysso1" id="salarysso1" type="text" value="{{ $datasalary->salary_sso }}" placeholder="">
           <?php } else { ?>
                <input class="" name="salarysso1" id="salarysso1" type="text" placeholder="">
           <?php } ?>              
          </label>  
        </div>              
        <div class="large-6 columns">           
           <label>ฌกส :
           <?php if( isset($datasalary) ){ ?>
                <input class="" name="salarycpk1" id="salarycpk1" type="text" value="{{ $datasalary->salary_cpk }}" placeholder="">
           <?php } else { ?>
                <input class="" name="salarycpk1" id="salarycpk1" type="text" placeholder="">
           <?php } ?>               
          </label>  
        </div>        
    </div>
      
    <div class="row">    
       <div class="large-6 columns">           
           <label>สหกรณ์ :
           <?php if( isset($datasalary) ){ ?>
                <input class="" name="salarycprt1" id="salarycprt1" type="text" value="{{ $datasalary->salary_cprt }}" placeholder="">
           <?php } else { ?>
                <input class="" name="salarycprt1" id="salarycprt1" type="text" placeholder="">
           <?php } ?>               
          </label>  
        </div>         
       <div  class="large-6 columns">           
         <label>เลขที่เสียภาษี :
            <?php if( isset($datasalary) ){ ?>
                <input class="" name="salarytaxid1" id="salarytaxid1" type="text" value="{{ $datasalary->tax_id }}" placeholder="">
           <?php } else { ?>
                <input class="" name="salarytaxid1" id="salarytaxid1" type="text" placeholder="">
           <?php } ?>          
        </label>  
       </div>  
    </div>

    <div class="row">
      <div class="large-6 columns">
        {{ Form::button( 'บันทึก', array( 'class'=>'small button', 'id' => 'btnSalaryAdd1' ) ) }}    
      </div>
    </div>
   
</fieldset>

{{ Form::close() }}

<script type="text/javascript">

  $( 'input[name="salary1"], input[name="salaryother1"], input[name="salarysso1"], input[name="salarycpk1"], input[name="salarycprt1"], input[name="salarytaxid1"]' ).keydown( function(event) {
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

  $( '#salary1, #salaryother1' ).keyup(function(){
      if( $( '#salary1' ).val() != '' )
      {
         var money = parseFloat( $( '#salary1' ).val() ) + parseFloat( $( '#salaryother1' ).val() );
        if( money >= 15000 ){
            var sso = ( parseFloat(15000) * 5 ) / 100;
        }else{
            var sso = ( parseFloat(money) * 5 ) / 100;
        }
       
        $( '#salarysso1' ).val( Math.round( sso ) );
      } 
  });

  //-------------- add Acc ----------------//
  $("#btnSalaryAdd1").click(function(){    
      var $form = $( '#form-addSalary1' ), data = $form.serialize(), url = $form.attr( "action" );

      var posting = $.post( url, { formData: data } );

      posting.done(function( data ) {
          if( data.fail ) 
          {         
              $( '#salary1_error' ).removeClass( 'error' );
              $( '#bank_acc_Salarytype1_error' ).removeClass( 'error' );
              $( '#salary1_d_error' ).fadeOut(); 
              $( '#bank_acc_Salarytype1_d_error' ).fadeOut();  

              $.each(data.errors, function( index, value ) {             

                var errorDiv = '#'+index+'1_d_error';    
                var errorDiv2 = '#'+index+'_Salarytype1_d_error';  
                
                $( '#'+index+'1_error' ).addClass( 'error' );
                $( '#'+index+'_Salarytype1_error' ).addClass( 'error' );

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
              $( '#salary1_error' ).removeClass( 'error' );
              $( '#bank_acc_Salarytype1_error' ).removeClass( 'error' );
              $( '#salary1_d_error' ).fadeOut(); 
              $( '#bank_acc_Salarytype1_d_error' ).fadeOut();  

              $( "#form-addSalary1" ).get( 0 ).reset();  
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