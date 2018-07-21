
<?php if( isset($datasalary) ){ $url = 'emptype4/salary4/'.$datasalary->cid; ?>
    {{ Form::open(array( 'url' => $url, 'id'=>'form-addSalary4')) }}
<?php } else { ?>
    {{ Form::open(array( 'url' => 'emptype4/addSalary', 'id'=>'form-addSalary4')) }}
<?php } ?>
  <fieldset> 
   <input type="hidden" name="cidSalary4" id="cidSalary4" />

    <div class="row">     
      <div id="bank_acc_Salarytype4_error" class="large-6 columns">                   
        <label>ธนาคาร :
        <?php if( count($dataacc) > 0 ) {?>     
          @foreach( $dataacc as $a )  
             <?php if( isset($datasalary) ){ ?>
                <input type="radio" name="salarybanktype4" <?php if($datasalary->bank_acc_id == $a->acc_id) {echo "checked='true' ";}else{echo "";}  ?> value="{{ $a->acc_id }}" id="salarybanktype4-{{ $a->acc_id }}"><label for="salarybanktype4-{{ $a->acc_id }}">{{ $a->bank_name }}:{{ $a->bank_acc }}</label> 
           <?php } else { ?>
                <input type="radio" name="salarybanktype4" value="{{ $a->acc_id }}" id="salarybanktype4-{{ $a->acc_id }}"><label for="salarybanktype4-{{ $a->acc_id }}">{{ $a->bank_name }}:{{ $a->bank_acc }}</label> 
           <?php } ?>              
             
          @endforeach   
        <?php } else { ?>
            ยังไม่ได้เพิ่มบัญชีธนาคาร
        <?php } ?>  
        </label>     
        <small id="bank_acc_Salarytype4_d_error" class=""></small>
      </div>  
    </div>

     <div class="row">                            
        <div id='salary4_error' class="large-6 columns">           
           <label>เงินเดือน :
           <?php if( isset($datasalary) ){ ?>
                <input class="" name="salary4" id="salary4" type="text" value="{{ $datasalary->salary }}" placeholder="">
           <?php } else { ?>
                <input class="" name="salary4" id="salary4" type="text" placeholder="">
           <?php } ?>           
          </label>  
          <small id="salary4_d_error" class=""></small>
        </div>                     
        <div class="large-6 columns">           
           <label>เงินอื่น ๆ :
            <?php if( isset($datasalary) ){ ?>
                <input class="" name="salaryother4" id="salaryother4" type="text" value="{{ $datasalary->salary_other }}" placeholder="">
           <?php } else { ?>
                <input class="" name="salaryother4" id="salaryother4" type="text" placeholder="">
           <?php } ?>             
          </label>  
        </div>         
    </div>

    <div class="row">                 
        <div class="large-6 columns">           
           <label>เงินประกันสังคอม :
           <?php if( isset($datasalary) ){ ?>
                <input class="" name="salarysso4" id="salarysso4" type="text" value="{{ $datasalary->salary_sso }}" placeholder="">
           <?php } else { ?>
                <input class="" name="salarysso4" id="salarysso4" type="text" placeholder="">
           <?php } ?>              
          </label>  
        </div>              
        <div class="large-6 columns">           
           <label>ฌกส :
           <?php if( isset($datasalary) ){ ?>
                <input class="" name="salarycpk4" id="salarycpk4" type="text" value="{{ $datasalary->salary_cpk }}" placeholder="">
           <?php } else { ?>
                <input class="" name="salarycpk4" id="salarycpk4" type="text" placeholder="">
           <?php } ?>               
          </label>  
        </div>        
    </div>
      
    <div class="row">  
      <div class="large-6 columns">           
          <label>สหกรณ์ :
          <?php if( isset($datasalary) ){ ?>
              <input class="" name="salarycprt4" id="salarycprt4" type="text" value="{{ $datasalary->salary_cprt }}" placeholder="">
          <?php } else { ?>
              <input class="" name="salarycprt4" id="salarycprt4" type="text" placeholder="">
          <?php } ?>               
        </label>  
      </div>   
      <div  class="large-6 columns">           
         <label>เลขที่เสียภาษี :
            <?php if( isset($datasalary) ){ ?>
                <input class="" name="salarytaxid4" id="salarytaxid4" type="text" value="{{ $datasalary->tax_id }}" placeholder="">
           <?php } else { ?>
                <input class="" name="salarytaxid4" id="salarytaxid4" type="text" placeholder="">
           <?php } ?>          
        </label>  
      </div>  
    </div>

    <div class="row">
      <div class="large-6 columns">
        {{ Form::button( 'บันทึก', array( 'class'=>'small button', 'id' => 'btnSalaryAdd4' ) ) }}    
      </div>
    </div>
   
</fieldset>

{{ Form::close() }}

<script type="text/javascript">

  $( 'input[name="salary4"], input[name="salaryother4"], input[name="salarysso4"], input[name="salarycpk4"], input[name="salarycprt4"], input[name="salarytaxid4"]' ).keydown( function(event) {
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

   $( '#salary4, #salaryother4' ).keyup(function(){
      if( $( '#salary4' ).val() != '' )
      {
         var money = parseFloat( $( '#salary4' ).val() ) + parseFloat( $( '#salaryother4' ).val() );
        if( money >= 15000 ){
            var sso = ( parseFloat(15000) * 5 ) / 100;
        }else{
            var sso = ( parseFloat(money) * 5 ) / 100;
        }
       
        $( '#salarysso4' ).val( Math.round( sso ) );
      } 
  });

  //-------------- add Acc ----------------//
  $("#btnSalaryAdd4").click(function(){    
      var $form = $( '#form-addSalary4' ), data = $form.serialize(), url = $form.attr( "action" );

      var posting = $.post( url, { formData: data } );

      posting.done(function( data ) {
          if( data.fail ) 
          {         
              $( '#salary4_error' ).removeClass( 'error' );
              $( '#bank_acc_Salarytype4_error' ).removeClass( 'error' );
              $( '#salary4_d_error' ).fadeOut(); 
              $( '#bank_acc_Salarytype4_d_error' ).fadeOut();  

              $.each(data.errors, function( index, value ) {             

                var errorDiv = '#'+index+'4_d_error';    
                var errorDiv2 = '#'+index+'_Salarytype4_d_error';  
                
                $( '#'+index+'4_error' ).addClass( 'error' );
                $( '#'+index+'_Salarytype4_error' ).addClass( 'error' );

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
              $( '#salary4_error' ).removeClass( 'error' );
              $( '#bank_acc_Salarytype4_error' ).removeClass( 'error' );
              $( '#salary4_d_error' ).fadeOut(); 
              $( '#bank_acc_Salarytype4_d_error' ).fadeOut();  

              $( "#form-addSalary4" ).get( 0 ).reset();  
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