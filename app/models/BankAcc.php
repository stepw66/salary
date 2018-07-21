<?php

class BankAcc extends Eloquent  {

  protected $table  = "s_bank_acc";

  protected $fillable = array( 'acc_id', 'cid', 'bank_id', 'bank_acc' );

}
