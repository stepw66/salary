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
        $r_c          = $a->r_c;
        $special      = $a->special;
        $son          = $a->son;
        $r_pt         = $a->r_pt;
        $r_other      = $a->r_other;
        $kbk          = $a->kbk;
        $tax          = $a->tax;
        $cas          = $a->cas;
        $save_p       = $a->save_p;
        $houseLoan    = $a->houseLoan;
        $save_h       = $a->save_h;
        $p_other      = $a->p_other;
        $shop         = $a->shop;
        $rice         = $a->rice;
        $water        = $a->water;
        $elec         = $a->elec;
        $pt           = $a->pt;
        $bank_o       = $a->bank_o;
        $fund_p       = $a->fund_p;      
        $order_date   = $a->order_date;
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
        $r_c          = $a->r_c;
        $special      = $a->special;
        $son          = $a->son;
        $kbk          = $a->kbk;
        $tax          = $a->tax;

        $r_pt         = '0.00';
        $r_other      = '0.00';
        $cas          = '0.00';
        $save_p       = '0.00';
        $houseLoan    = '0.00';
        $save_h       = '0.00';
        $p_other      = '0.00';
        $shop         = '0.00';
        $rice         = '0.00';
        $water        = '0.00';
        $elec         = '0.00';
        $pt           = '0.00';
        $bank_o       = '0.00';
        $fund_p       = '0.00';
      }
  }
?>

<?php if( isset($datasalary) ){ $url = 'emptype3/salary_insert3/'.$cid.'/'.$order_date; ?>
    {{ Form::open(array( 'url' => $url, 'id'=>'form-addsalary_insert3')) }}
<?php } else { ?>
    {{ Form::open(array( 'url' => 'emptype3/salary_add', 'id'=>'form-addsalary_insert3')) }}
<?php } ?>
  <fieldset> 
   <input type="hidden" name="cidsalary_insert3" id="cidsalary_insert3" value="<?php echo $cid; ?>" />
   <input type="hidden" name="banksalary_insert3" id="banksalary_insert3" value="<?php echo $bank; ?>" />
   <input type="hidden" name="bank_acc_idsalary_insert3" id="bank_acc_idsalary_insert3" value="<?php echo $bank_acc_id; ?>" />
   <input type="hidden" name="bank_accsalary_insert3" id="bank_accsalary_insert3" value="<?php echo $bank_acc; ?>" />
   <input type="hidden" name="salarysalary_insert3" id="salarysalary_insert3" value="<?php echo $salary; ?>" />
   <input type="hidden" name="r_csalary_insert3" id="r_csalary_insert3" value="<?php echo $r_c; ?>" />
   <input type="hidden" name="specialsalary_insert3" id="specialsalary_insert3" value="<?php echo $special; ?>" />
   <input type="hidden" name="sonsalary_insert3" id="sonsalary_insert3" value="<?php echo $son; ?>" />
   <input type="hidden" name="kbksalary_insert3" id="kbksalary_insert3" value="<?php echo $kbk; ?>" />
   <input type="hidden" name="taxsalary_insert3" id="taxsalary_insert3" value="<?php echo $tax; ?>" />
   <input type="hidden" name="tax_idsalary_insert3" id="tax_idsalary_insert3" value="<?php echo $tax_id; ?>" />

   <div class="row">

      <!-- Left -->
     <div class="large-4 columns"> 
        <div class="panel">
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
        </div>    

        <label>เงินเดือน :
        <span class="textrad"><?php echo number_format( $salary, 2 ); ?></span>   
       </label>                                          
        <label>เงินประจำตำแหน่ง :
          <span class="textrad"><?php echo number_format( $r_c, 2 ); ?></span>        
        </label>                                         
        <label>เงินค่าตอบแทนพิเศษ :
          <span class="textrad" id="special3"><?php echo number_format( $special, 2 ); ?></span>       
        </label>                                 
         <label>เงินช่วยเหลือบุตร :
          <span class="textrad" id="son3"><?php echo number_format( $son, 2 ); ?></span>         
        </label>                      
        <label>คืนค่ารักษา :          
            <input name="r_pt3" id="r_pt3" type="text" value="<?php echo $r_pt; ?>" placeholder="">
        </label>                                     
        <label>รับอื่น ๆ :          
            <input  name="r_other3" id="r_other3" type="text" value="<?php echo $r_other; ?>" placeholder="">
        </label> 

        <hr />                                           
        <div class="panel">
            <input type="hidden" name="salaryAll3" id="salaryAll3" value="<?php echo ($salary)+($r_c)+($special)+($son)+($r_pt)+($r_other); ?>" />               
            <label>รวมรับ : <span class="textblue" id="sumSalary3"><?php echo number_format( ($salary)+($r_c)+($special)+($son)+($r_pt)+($r_other), 2 ); ?></span></label>                    
        </div>

     </div>

     <!-- Right -->
     <div class="large-8 columns"> 

        <div class="row">
            <div class="large-4 columns">
                <label>กบข/กสจ :          
                    <input name="kbk3" id="kbk3" type="text" value="<?php echo $kbk; ?>" placeholder="">
                 </label> 
                  <label>ภาษี :                             
                    <span class="textrad" name="tax3" id="tax3"><?php echo $tax; ?></span>
                 </label>                  
                 <label>ฌกส :          
                    <input name="cas3" id="cas3" type="text" value="<?php echo $cas; ?>" placeholder="">
                 </label>  
                  <label>ออมทรัพย์จังหวัด :          
                    <input name="save_p3" id="save_p3" type="text" value="<?php echo $save_p; ?>" placeholder="">
                 </label> 
                  <label>ธนาคารอาคารสงเคราะห์ :          
                    <input name="houseLoan3" id="houseLoan3" type="text" value="<?php echo $houseLoan; ?>" placeholder="">
                 </label>  

                 <hr />                                           
                <div class="panel">                              
                    <label>หักจากจังหวัด : <span class="textblue" id="sub_p3"><?php echo number_format( ($kbk)+($tax)+($cas)+($save_p)+($houseLoan), 2 ); ?></span></label>                    
                </div>                                                           
                <div class="panel">                              
                    <label>เหลือมาโรงพยาบาล : <span class="textblue" id="sum_to_h3"><?php  echo number_format( (($salary)+($r_c)+($special)+($son)+($r_pt)+($r_other))-(($kbk)+($tax)+($cas)+($save_p)+($houseLoan)), 2 ); ?></span></label>                    
                </div>                                                  
            </div>
             <div class="large-4 columns">
                  <label>ค่าใช้จ่ายอื่น ๆ 1 :          
                    <input name="save_h3" id="save_h3" type="text" value="<?php echo $save_h; ?>" placeholder="">
                 </label> 
                 <label>ค่าใช้จ่ายอื่น ๆ 2 :          
                    <input name="p_other3" id="p_other3" type="text" value="<?php echo $p_other; ?>" placeholder="">
                 </label> 
                <label>สหกรณ์ร้านค้า :          
                    <input  name="shop3" id="shop3" type="text" value="<?php echo $shop; ?>" placeholder="">
                </label> 
                 <label>ค่าข้าว :          
                    <input  name="rice3" id="rice3" type="text" value="<?php echo $rice; ?>" placeholder="">
                </label>  
                 <label>ค่าน้ำประปา :          
                    <input  name="water3" id="water3" type="text" value="<?php echo $water; ?>" placeholder="">
                </label>     
            </div>
             <div class="large-4 columns">              
                 <label>ค่าไฟฟ้า :          
                    <input name="elec3" id="elec3" type="text" value="<?php echo $elec; ?>" placeholder="">
                 </label> 
                  <label>ค่ารักษา :          
                    <input name="pt3" id="pt3" type="text" value="<?php echo $pt; ?>" placeholder="">
                 </label>                  
                 <label>ออมสิน :          
                    <input name="bank_o3" id="bank_o3" type="text" value="<?php echo $bank_o; ?>" placeholder="">
                 </label>  
                 <label>ค่าธรรมเนียมธนาคาร :          
                    <input name="fund_p3" id="fund_p3" type="text" value="<?php echo $fund_p; ?>" placeholder="">
                 </label>

                 <hr />                                           
                <div class="panel">                              
                    <label>หักที่โรงพยาบาล : <span class="textblue" id="sub_h3"><?php echo number_format( ($save_h)+($p_other)+($shop)+($rice)+($water)+($elec)+($pt)+($bank_o)+($fund_p), 2 ); ?></span></label>                    
                </div>                                                           
                <div class="panel">                              
                    <label>รับจริง : <span class="textrad" id="sum_true3"><?php echo number_format( ((($salary)+($r_c)+($special)+($son)+($r_pt)+($r_other))-(($kbk)+($tax)+($cas)+($save_p)+($houseLoan))) - (($save_h)+($p_other)+($shop)+($rice)+($water)+($elec)+($pt)+($bank_o)+($fund_p)), 2 ); ?></span></label>                    
                </div>    
            </div>
        </div>    

        <div class="row">
          <div class="large-6 columns">
            {{ Form::button( 'บันทึก', array( 'class'=>'small button', 'id' => 'btnsalary_insertAdd3' ) ) }}    
          </div>
        </div> 

    </div>

   </div>   
   
</fieldset>

{{ Form::close() }}

<script type="text/javascript">

  $( 'input[name="r_pt3"], input[name="r_other3"], input[name="kbk3"], input[name="tax3"], input[name="cas3"], input[name="save_p3"], input[name="save_h3"], input[name="save_h3"], input[name="p_other3"], input[name="shop3"], input[name="rice3"], input[name="water3"], input[name="elec3"], input[name="pt3"], input[name="bank_o3"], input[name="fund_p3"]' ).keydown( function(event) {
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

  //=======แถว 1
  $( 'input[name="r_pt3"], input[name="r_other3"]' ).keyup( function(event) {  
      if( $( 'input[name="r_pt3"]' ).val() != '' ){
        var sum =  eval(  $( '#salaryAll3' ).val() )+ eval( $( '#r_pt3' ).val() ) + eval( $( '#r_other3' ).val() );
        $( '#sumSalary3' ).html(  sum  );
        $( '#sum_to_h3' ).html( eval( sum ) - eval( $( '#sub_p3' ).html().replace(/[^\d\.\-\ ]/g, '') ) );
        $( '#sum_true3' ).html( eval( $( '#sum_to_h3' ).html().replace(/[^\d\.\-\ ]/g, '') ) - eval( $( '#sub_h3' ).html().replace(/[^\d\.\-\ ]/g, '') ) );
      }else{
        
      }    
   });

  //=======แถว 2
  $( 'input[name="kbk3"], input[name="cas3"], input[name="save_p3"], input[name="houseLoan3"]' ).keyup( function(event) {  
      if( $( 'input[name="kbk3"]' ).val() != '' ){        
        var sum =  eval(  $( '#kbk3' ).val() ) + eval( $( '#cas3' ).val() ) + eval( $( '#save_p3' ).val() ) + eval( $( '#houseLoan3' ).val() ) + eval( $( '#tax3' ).html() );             
        $( '#sub_p3' ).html(  sum  );
        $( '#sum_to_h3' ).html( eval( $( '#sumSalary3' ).html().replace(/[^\d\.\-\ ]/g, '') ) - eval( $( '#sub_p3' ).html().replace(/[^\d\.\-\ ]/g, '') ) );
        $( '#sum_true3' ).html( eval( $( '#sum_to_h3' ).html().replace(/[^\d\.\-\ ]/g, '') ) - eval( $( '#sub_h3' ).html().replace(/[^\d\.\-\ ]/g, '') ) );
      }else{

      }
  });

  //=======แถว 3
  $( 'input[name="save_h3"], input[name="p_other3"], input[name="shop3"], input[name="rice3"], input[name="water3"], input[name="elec3"], input[name="pt3"], input[name="bank_o3"], input[name="fund_p3"]' ).keyup( function(event) {  
      if( $( 'input[name="save_h3"]' ).val() != '' ){        
        var sum =  eval(  $( '#save_h3' ).val() ) + eval( $( '#p_other3' ).val() ) + eval( $( '#shop3' ).val() ) + eval( $( '#rice3' ).val() ) + eval( $( '#water3' ).val() ) + eval( $( '#elec3' ).val() ) + eval( $( '#pt3' ).val() ) + eval( $( '#bank_o3' ).val() ) + eval( $( '#fund_p3' ).val() );                    
        $( '#sub_h3' ).html(  sum  );
        $( '#sum_true3' ).html( eval( $( '#sum_to_h3' ).html().replace(/[^\d\.\-\ ]/g, '') ) - eval( $( '#sub_h3' ).html().replace(/[^\d\.\-\ ]/g, '') ) );
      }else{

      }
  });


  //-------------- add Acc ----------------//
  $("#btnsalary_insertAdd3").click(function(){    
      var $form = $( '#form-addsalary_insert3' ), data = $form.serialize(), url = $form.attr( "action" );

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