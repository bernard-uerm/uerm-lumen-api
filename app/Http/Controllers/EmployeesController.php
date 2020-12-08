<?php

namespace App\Http\Controllers;
use App\Employees;
use DB;

class EmployeesController extends Controller
{
  public function index() 
  {
    $employees = Employees::all();
    return $employees;
  }

  public function categories()
  {
    $categories = DB::select(DB::raw(
      "select category from employees group by category"
    ));

    $employee_categories = DB::select(DB::raw(
      "select count(*) as winners from employees where winner = 1 and category = 'Nursing Service'"
    ));

    $raffle_winners = DB::select(DB::raw(
      "select expected_winners from raffles"
    ));

    return $categories;
  }
}