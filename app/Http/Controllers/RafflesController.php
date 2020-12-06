<?php

namespace App\Http\Controllers;
use App\Raffles;

class RafflesController extends Controller
{
  public function index() 
  {
    $raffles = Raffles::all();
    return $raffles;
  }
}