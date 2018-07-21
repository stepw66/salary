<?php

class SalaryDetail extends Eloquent  {

  protected $table  = "s_salary_detail";

  protected $fillable = array( 'cid', 'bank', 'bank_acc_id', 'bank_acc', 'salary', 'salary_other', 'salary_sso', 'salary_cpk', 'tax_id', 'save', 'shop', 'rice', 'water', 'elec', 'other', 'order_date', 'sys_user', 'special', 'tax', 'pts', 'ot' );
 
}
