<?php

class User extends Eloquent  {

  protected $table  = "s_users";

  protected $fillable = array( 'cid', 'password', 'level', 'logintime', 'c1', 'c2', 'c3', 'c4' );

  public static $rules = array(
    'cid' => 'required',
    'password' => 'required',
    'level' => '',
    'logintime' => '',
    'c1' => '',
    'c2' => '',
    'c3' => '',
    'c4' => ''
  );

   public static $messages = array(
    'cid.required' => '** กรุณากรอกรหัสบัตรประชาชน **',
    'password.required' => '** กรุณากรอกรหัสผ่าน **'
  ); 
 
}
