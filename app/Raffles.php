<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Raffles extends Model
{
  protected $fillable = [
    'name',
    'price',
    'expected_winners', 
    'enabled',
  ];

  public function raffle_winners()
  {
    return $this->hasMany('App\RaffleWinners', 'raffle_id');
  }
}
