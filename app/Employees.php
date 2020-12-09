<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employees extends Model
{
  protected $fillable = [
    'employee_code',
    'first_name',
    'middle_name', 
    'last_name',
    'name_extension',
    'position',
    'department', 
    'category',
    'winner',
    'enabled'
  ];

  public function raffle_winners()
  {
    return $this->hasMany('App\RaffleWinners', 'employee_id');
  }
}
