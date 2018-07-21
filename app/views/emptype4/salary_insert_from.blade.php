<?php
  if( isset($datasalary) )
  {
      foreach ($datasalary as $a ) {
        $cid          = $a->cid;
        $bank_name    = $a->bank_name;
        $bank_acc     = $a->bank_acc;
        $bank         = $a->bank;
        $bank_acc_id  = $a->bank_acc_id;
        $tax_id       = $a->tax_id;
        $salary       = $a->salary;
        $salary_other = $a->salary_other;
        $salary_sso   = $a->salary_sso;
        $salary_cpk   = $a->salary_cpk;
        $salary_cprt   = $a->salary_cprt;
        $save         = $a->save;
        $shop         = $a->shop;
        $rice         = $a->rice;
        $water        = $a->water;
        $elec         = $a->elec;
        $cprt         = $a->cprt;
        $other        = $a->other;
        $order_date   = $a->order_date;
        $comment      = $a->comment;
      }
  }
  else
  {
      foreach ($dataacc as $a ) {
        $cid          = $a->cid;
        $bank_name    = $a->bank_name;
        $bank_acc     = $a->bank_acc;
        $bank         = $a->bank;
        $bank_acc_id  = $a->bank_acc_id;
        $tax_id       = $a->tax_id;
        $salary       = $a->salary;
        $salary_other = $a->salary_other;
        $salary_sso   = $a->salary_sso;
        $salary_cpk   = $a->salary_cpk;
        $salary_cprt   = $a->salary_cprt;

        $save         = '0.00';
        $shop         = '0.00';
        $rice         = '0.00';
        $water        = '0.00';
        $elec         = '0.00';
        $cprt         = '0.00';
        $other        = '0.00';
        $comment      = '';
      }
  }
?>

<?php if( isset($datasalary) ){ $url = 'emptype4/salary_insert4/'.$cid.'/'.$order_date; ?>
    {{ Form::open(array( 'url' => $url, 'id'=>'form-addsalary_insert4')) }}
<?php } else { ?>
   {{ Form::open(array( 'url' => 'emptype4/salary_add', 'id'=>'form-addsalary_insert4')) }}
<?php } ?>
  <fieldset> 
   <input type="hidden" name="cidsalary_insert4" id="cidsalary_insert4" value="<?php echo $cid; ?>" />
   <input type="hidden" name="banksalary_insert4" id="banksalary_insert4" value="<?php echo $bank; ?>" />
   <input type="hidden" name="bank_acc_idsalary_insert4" id="bank_acc_idsalary_insert4" value="<?php echo $bank_acc_id; ?>" />
   <input type="hidden" name="bank_accsalary_insert4" id="bank_accsalary_insert4" value="<?php echo $bank_acc; ?>" />
   <input type="hidden" name="salarysalary_insert4" id="salarysalary_insert4" value="<?php echo $salary; ?>" />
   <input type="hidden" name="salary_othersalary_insert4" id="salary_othersalary_insert4" value="<?php echo $salary_other; ?>" />
   <input type="hidden" name="salary_ssosalary_insert4" id="salary_ssosalary_insert4" value="<?php echo $salary_sso; ?>" />
   <input type="hidden" name="salary_cpksalary_insert4" id="salary_cpksalary_insert4" value="<?php echo $salary_cpk; ?>" />
   <input type="hidden" name="salary_cprtsalary_insert4" id="salary_cprtsalary_insert4" value="<?php echo $salary_cprt; ?>" />
   <input type="hidden" name="tax_idsalary_insert4" id="tax_idsalary_insert4" value="<?php echo $tax_id; ?>" />

   <div class="row">

      <!-- Left -->
     <div class="large-6 columns"> 
        <div class="row">     
          <div class="large-12 columns">                   
            <label>ธนาคาร :
              <span class="textrad"><?php echo $bank_name; ?></span>
            </label>           
          </div>  
        </div>
        <div class="row">     
          <div class="large-12 columns">                   
            <label>หมายเลขบัญชี :
              <span class="textrad"><?php echo $bank_acc; ?></span>
            </label>           
          </div>  
        </div>
        <div class="row">     
          <div  class="large-12 columns">           
             <label>เลขที่เสียภาษี :
                <span class="textrad"><?php echo $tax_id; ?></span> 
            </label>  
          </div>  
        </div>
        <hr />
        <div class="row">     
          <div  class="large-12 columns">           
            <div class="panel">
                <input type="hidden" name="salaryAll4" id="salaryAll4" value="<?php echo ($salary + $salary_other); ?>" />               

                <label>รวมรับ : <span class="textblue" id="sumSalary4"><?php echo number_format( ($salary + $salary_other), 2 ); ?></span></label>
                <label>รวมหัก : 
                  <span class="textblue" id="sum4">
                     <?php echo number_format( ($salary_sso)+($salary_cpk)+($salary_cprt)+($save)+($shop)+($rice)+($water)+($elec)+($other), 2 ); ?>
                  </span>
                </label>
                <label>รับจริง : 
                    <span class="textblue" id="sumAll4">
                      <?php echo number_format( ($salary + $salary_other) - (($salary_sso)+($salary_cpk)+($salary_cprt)+($save)+($shop)+($rice)+($water)+($elec)+($other)), 2 ); ?>
                    </span>
                </label>
            </div>
          </div>  
        </div>
     </div>

     <!-- Right -->
     <div class="large-6 columns">  
          <div class="row">                            
            <div class="large-6 columns">           
               <label>เงินเดือน :
                <span class="textrad"><?php echo number_format( $salary, 2 ); ?></span>   
              </label>          
            </div>                                
          </div>
          <div class="row">     
            <div class="large-6 columns">                   
                <label>เงินอื่น ๆ :
                  <span class="textrad"><?php echo number_format( $salary_other, 2 ); ?></span>        
                </label>        
            </div>  
          </div>
           <div class="row">     
            <div class="large-6 columns">                   
                <label>เงินประกันสังคม :
                  <span class="textrad" id="sso4"><?php echo number_format( $salary_sso, 2 ); ?></span>       
                </label>        
            </div>  
          </div>
          <div class="row">                                   
              <div class="large-6 columns">           
                 <label>ฌกส :
                  <span class="textrad" id="cpk4"><?php echo number_format( $salary_cpk, 2 ); ?></span>         
                </label>  
              </div>        
          </div>
          <div class="row">                                   
              <div class="large-6 columns">           
                 <label>สหกรณ์ :
                  <span class="textrad" id="cprt4"><?php echo number_format( $salary_cprt, 2 ); ?></span>         
                </label>  
              </div>        
          </div>
          <hr />
          <div class="row">                                   
              <div class="large-6 columns"> 
                  <label>ค่าธรรมเนียมธนาคาร :          
                    <input name="save4" id="save4" type="text" value="<?php echo $save; ?>" placeholder="">
                  </label>  
                 <label>ลากิจ :          
                    <input  name="rice4" id="rice4" type="text" value="<?php echo $rice; ?>" placeholder="">
                  </label> 
                 <label>ค่าไฟฟ้า :          
                    <input name="elec4" id="elec4" type="text" value="<?php echo $elec; ?>" placeholder="">
                 </label> 
              </div>
              <div class="large-6 columns"> 
                <label>สหกรณ์ร้านค้า :          
                    <input  name="shop4" id="shop4" type="text" value="<?php echo $shop; ?>" placeholder="">
                </label> 
                 <label>ค่าน้ำ :          
                    <input  name="water4" id="water4" type="text" value="<?php echo $water; ?>" placeholder="">
                </label>  
                <label>ค่าใช้จ่ายอื่น ๆ :          
                    <input  name="other4" id="other4" type="text" value="<?php echo $other; ?>" placeholder="">
                </label> 
              </div>
          </div> 
           <div class="row">
            <div class="large-12 columns"> 
                <label>หมายเหตุ :          
                    <input  name="comment4" id="comment4" type="text" value="<?php echo $comment; ?>" placeholder="">
                </label>               
            </div>
          </div> 
          <div class="row">
            <div class="large-6 columns">
              {{ Form::button( 'บันทึก', array( 'class'=>'small button', 'id' => 'btnsalary_insertAdd4' ) ) }}    
            </div>
          </div>  
     </div>

   </div>   
   
</fieldset>

{{ Form::close() }}

<script type="text/javascript">

  $( 'input[name="save4"], input[name="rice4"], input[name="elec4"], input[name="shop4"], input[name="water4"], input[name="other4"]' ).keydown( function(event) {
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

  $( 'input[name="save4"], input[name="rice4"], input[name="elec4"], input[name="shop4"], input[name="water4"],input[name="other4"]' ).keyup( function(event) {  
      if( $( 'input[name="save4"]' ).val() != '' ){
        var sum = eval( $( '#save4' ).val() ) + eval( $( '#rice4' ).val() ) + eval( $( '#elec4' ).val() ) + eval( $( '#shop4' ).val() ) + eval( $( '#water4' ).val() ) + eval( $( '#other4' ).val() ) + eval( $( '#sso4' ).html() ) + eval( $( '#cpk4' ).html() ) + eval( $( '#cprt4' ).html().replace(/[,]/, "") );

        $( '#sum4' ).html( ( eval( sum ) ).toFixed(2).replace(/\.?0+$/, "") );
        $( '#sumAll4' ).html(  (eval( $( '#salaryAll4' ).val() ) - eval( $( '#sum4' ).html() )).toFixed(2).replace(/\.?0+$/, "")  );
      }
   });

  //-------------- add Acc ----------------//
  $("#btnsalary_insertAdd4").click(function(){    
      var $form = $( '#form-addsalary_insert4' ), data = $form.serialize(), url = $form.attr( "action" );

      var posting = $.post( url, { formData: data } );

      posting.done(function( data ) {
          if( data.fail ) 
          {         
                                             
          } 
          if( data.success == true ) 
          {                 
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