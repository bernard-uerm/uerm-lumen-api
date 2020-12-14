<?php

namespace App\Http\Controllers;
use DB;
class AdminController extends Controller
{
  public function clearWinners() 
  {
    $setWinners = DB::select('call sp_clearWinners(@success)');
    $select_error_code = DB::select('select @success as error_code');
    if ($select_error_code) {
      $error_code = $select_error_code[0]->error_code;
      if ($error_code == 0) {
        DB::rollBack();
        throw new \Exception('Error ' . $error_code); // Throwing exception rolls back the transaction
        return json_encode('Error clearing winners!');
      } else {
        DB::commit();
        return json_encode('Success');
      }
    } else {
      DB::rollBack();
      throw new \Exception('Error clearing winners!'); // Throwing exception rolls back the transaction
    }
  }
}