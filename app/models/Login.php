<?php

class Login extends Eloquent  {

  protected $table  = "users";

  public static $rules = array(
   'username' => 'required',
   'password' => 'required'
  );

   public static $messages = array(
    'username.required' => 'กรุณากรอกชื่อผู้ใช้.',
    'password.required' => 'กรุณากรอกรหัสผ่าน.',
  ); 
  

}
