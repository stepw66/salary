<?php

class UnitCostsEmp extends Eloquent  {

  protected $table  = "s_unit_costs_emp";

  protected $fillable = array( 'in_id', 'cid', 'unitcode', 'unitname', 'cal' );
 
}
