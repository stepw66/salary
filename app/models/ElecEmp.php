<?php

class ElecEmp extends Eloquent  {

  protected $table  = "s_elec_home_emp";

  protected $fillable = array( 'home_emp_id', 'home', 'cid' );
 
}
