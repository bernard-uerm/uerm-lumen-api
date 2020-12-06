<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Raffles extends Model
{
  protected $fillable = [
    'employee_id',
    'raffle_id'
  ];

  public function employees()
    {
        return $this->belongsTo('App\Employees', 'employee_id');
    }

    public function raffles()
    {
        return $this->belongsTo('App\Raffles', 'raffle_id');
    }
}
