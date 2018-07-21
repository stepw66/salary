<?php

class Water extends Eloquent  {

  protected $table  = "s_water_meter";

  protected $fillable = array( 'meter_id', 'name_meter', 'cid' );
 
}
