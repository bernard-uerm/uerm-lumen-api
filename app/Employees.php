<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employees extends Model
{
  protected $fillable = [
    'employee_code',
    'department_code',
    'full_name', 
    'category',
    'winner',
    'enabled'
  ];

  public function raffle_winners()
  {
    return $this->hasMany('App\RaffleWinners', 'employee_id');
  }
}
