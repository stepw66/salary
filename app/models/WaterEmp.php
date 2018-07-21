<?php

class WaterEmp extends Eloquent  {

  protected $table  = "s_water_meter_emp";

  protected $fillable = array( 'meter_emp_id', 'meter', 'cid' );
 
}
