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
        $salary_cprt  = $a->salary_cprt;
        $save         = $a->save;
        $shop         = $a->shop;
        $rice         = $a->rice;
        $water        = $a->water;
        $elec         = $a->elec;        
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
        $salary_cprt  = $a->salary_cprt;

        $save         = '0.00';
        $shop         = '0.00';
        $rice         = '0.00';
        $water        = '0.00';
        $elec         = '0.00';       
        $other        = '0.00';
        $comment      = '';
      }
  }
?>

<?php if( isset($datasalary) ){ $url = 'emptype1/salary_insert1/'.$cid.'/'.$order_date; ?>
    {{ Form::open(array( 'url' => $url, 'id'=>'form-addsalary_insert1')) }}
<?php } else { ?>
    {{ Form::open(array( 'url' => 'emptype1/salary_add', 'id'=>'form-addsalary_insert1')) }}
<?php } ?>
  <fieldset> 
   <input type="hidden" name="cidsalary_insert1" id="cidsalary_insert1" value="<?php echo $cid; ?>" />
   <input type="hidden" name="banksalary_insert1" id="banksalary_insert1" value="<?php echo $bank; ?>" />
   <input type="hidden" name="bank_acc_idsalary_insert1" id="bank_acc_idsalary_insert1" value="<?php echo $bank_acc_id; ?>" />
   <input type="hidden" name="bank_accsalary_insert1" id="bank_accsalary_insert1" value="<?php echo $bank_acc; ?>" />
   <input type="hidden" name="salarysalary_insert1" id="salarysalary_insert1" value="<?php echo $salary; ?>" />
   <input type="hidden" name="salary_othersalary_insert1" id="salary_othersalary_insert1" value="<?php echo $salary_other; ?>" />
   <input type="hidden" name="salary_ssosalary_insert1" id="salary_ssosalary_insert1" value="<?php echo $salary_sso; ?>" />
   <input type="hidden" name="salary_cpksalary_insert1" id="salary_cpksalary_insert1" value="<?php echo $salary_cpk; ?>" />
   <input type="hidden" name="salary_cprtsalary_insert1" id="salary_cprtsalary_insert1" value="<?php echo $salary_cprt; ?>" />
   <input type="hidden" name="tax_idsalary_insert1" id="tax_idsalary_insert1" value="<?php echo $tax_id; ?>" />

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
                <input type="hidden" name="salaryAll" id="salaryAll" value="<?php echo ($salary + $salary_other); ?>" />               

                <label>รวมรับ : <span class="textblue" id="sumSalary"><?php echo number_format( ($salary + $salary_other), 2 ); ?></span></label>
                <label>รวมหัก : 
                  <span class="textblue" id="sum1">
                     <?php echo number_format( ($salary_sso)+($salary_cpk)+($salary_cprt)+($save)+($shop)+($rice)+($water)+($elec)+($other), 2 ); ?>
                  </span>
                </label>
                <label>รับจริง : 
                    <span class="textblue" id="sumAll">
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
                  <span class="textrad" id="sso1"><?php echo number_format( $salary_sso, 2 ); ?></span>       
                </label>        
            </div>  
          </div>
          <div class="row">                                   
              <div class="large-6 columns">           
                 <label>ฌกส :
                  <span class="textrad" id="cpk1"><?php echo number_format( $salary_cpk, 2 ); ?></span>         
                </label>  
              </div>        
          </div>
          <div class="row">                                   
              <div class="large-6 columns">           
                 <label>สหกรณ์ :
                  <span class="textrad" id="cprt1"><?php echo number_format( $salary_cprt, 2 ); ?></span>         
                </label>  
              </div>        
          </div>
          <hr />
          <div class="row">                                   
              <div class="large-6 columns"> 
                  <label>ค่าธรรมเนียมธนาคาร :          
                    <input name="save1" id="save1" type="text" value="<?php echo $save; ?>" placeholder="">
                  </label>  
                 <label>ลากิจ :          
                    <input  name="rice1" id="rice1" type="text" value="<?php echo $rice; ?>" placeholder="">
                  </label> 
                 <label>ค่าไฟฟ้า :          
                    <input name="elec1" id="elec1" type="text" value="<?php echo $elec; ?>" placeholder="">
                 </label>  
              </div>
              <div class="large-6 columns"> 
                <label>สหกรณ์ร้านค้า :          
                    <input  name="shop1" id="shop1" type="text" value="<?php echo $shop; ?>" placeholder="">
                </label> 
                 <label>ค่าน้ำ :          
                    <input  name="water1" id="water1" type="text" value="<?php echo $water; ?>" placeholder="">
                </label> 
                 <label>ค่าใช้จ่ายอื่น ๆ :          
                    <input  name="other1" id="other1" type="text" value="<?php echo $other; ?>" placeholder="">
                </label>
              </div>
          </div>  
          <div class="row">
            <div class="large-12 columns"> 
                <label>หมายเหตุ :          
                    <input  name="comment1" id="comment1" type="text" value="<?php echo $comment; ?>" placeholder="">
                </label>               
            </div>
          </div>
          <div class="row">
            <div class="large-6 columns">
              {{ Form::button( 'บันทึก', array( 'class'=>'small button', 'id' => 'btnsalary_insertAdd1' ) ) }}    
            </div>
          </div>  
     </div>

   </div>   
   
</fieldset>

{{ Form::close() }}

<script type="text/javascript">

  $( 'input[name="save1"], input[name="rice1"], input[name="elec1"], input[name="shop1"], input[name="water1"], input[name="other1"]' ).keydown( function(event) {
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

  $( 'input[name="save1"], input[name="rice1"], input[name="elec1"], input[name="shop1"], input[name="water1"], input[name="other1"]' ).keyup( function(event) {  
      if( $( 'input[name="save1"]' ).val() != '' ){       
        var sum = eval( $( '#save1' ).val() ) + eval( $( '#rice1' ).val() ) + eval( $( '#elec1' ).val() ) + eval( $( '#shop1' ).val() ) + eval( $( '#water1' ).val() ) + eval( $( '#other1' ).val() ) + eval( $( '#sso1' ).html() ) + eval( $( '#cpk1' ).html() ) + eval( $( '#cprt1' ).html().replace(/[,]/, "") );

        $( '#sum1' ).html( ( eval( sum ) ).toFixed(2).replace(/\.?0+$/, "") );
        $( '#sumAll' ).html(  (eval( $( '#salaryAll' ).val() ) - eval( $( '#sum1' ).html() )).toFixed(2).replace(/\.?0+$/, "")  );
      }
   });


  //-------------- add Acc ----------------//
  $("#btnsalary_insertAdd1").click(function(){    
      var $form = $( '#form-addsalary_insert1' ), data = $form.serialize(), url = $form.attr( "action" );

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