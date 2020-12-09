<?php

namespace App\Http\Controllers;
use App\Employees;
use Illuminate\Http\Request;
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

  public function saveEmployees(Request $request) 
  {
    if($request->isMethod('post')) {
      $employeeRequests = $request->all();
      foreach($employeeRequests as $employees) {
        $code = $employees['Code'];
        $firstName = $employees['FirstName'];
        $middleName = $employees['MiddleName'] ?? null;
        $lastName = $employees['LastName'];
        $nameExtension = $employees['NameExtension'] ?? null;
        $position = $employees['Position'];
        $department = $employees['Department'];
        $category = $employees['Category'];
        $return_value = DB::transaction(function() use ($code, $firstName, $middleName, $lastName, $nameExtension, $department, $position, $category) {
          $setWinners = DB::select('call sp_AddEmployee(?, ?, ?, ?, ?, ?, ?, ?, @success)', array(
            $code,
            $firstName,
            $middleName,
            $lastName,
            $nameExtension, 
            $department,
            $position, 
            $category
          ));

          $select_error_code = DB::select('select @success as error_code');

          if ($select_error_code) {
            $error_code = $select_error_code[0]->error_code;
            if ($error_code == 0) {
              DB::rollBack();
              throw new \Exception('Error saving Raffle. Error code is ' . $error_code); // Throwing exception rolls back the transaction
              return json_encode('Error saving Employee, already exist!');
            } else {
              DB::commit();
              return json_encode('Success');
            }
          } else {
            DB::rollBack();
            throw new \Exception('Error saving Employee.'); // Throwing exception rolls back the transaction
          }
        }); 
      }

      return $return_value;
    }
  }
}