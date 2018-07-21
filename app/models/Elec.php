<?php

class Elec extends Eloquent  {

  protected $table  = "s_elec_home";

  protected $fillable = array( 'home_id', 'elec_number', 'elec_home', 'cid' );
 
}
