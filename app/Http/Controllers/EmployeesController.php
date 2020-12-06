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
    return $categories;
  }
}