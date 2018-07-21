<?php

class Bank extends Eloquent  {

  protected $table  = "s_bank";

  protected $fillable = array( 'bank_name' );

  public static $rules = array(
    'bank_name' => 'required'
  );

   public static $messages = array(
    'bank_name.required' => 'กรุณากรอกชื่อธนาคาร',
  ); 
 
}
