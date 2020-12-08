<?php

namespace App\Http\Controllers;
use App\RaffleWinners;
use Illuminate\Http\Request;
use DB;
use Log;


class RaffleWinnersController extends Controller
{
  public function getRandomWinners(Request $request)
  {
    $category = $request->category;
    $winnersCount = $request->winnersCount;
    $winners = DB::select(DB::raw(
      "SELECT * FROM employees
      WHERE category = '$category'
      and winner = 0
      ORDER BY RAND()
      LIMIT $winnersCount"
      ));
    return $winners;
  }
  public function setWinners(Request $request) 
  {
    if($request->isMethod('post')) {

      $raffles = $request->all();
      
      // foreach($raffles as $raffle_val) {
        // print_r($raffle_val);
        $raffle_encode = json_encode($raffles);
        $raffle_decode = json_decode($raffle_encode);
        
        $return_value = DB::transaction(function() use ($raffle_decode) {
          $setWinners = DB::select('call sp_setWinners(?, ?, @success)', array(
            $raffle_decode->employee_code,
            $raffle_decode->raffle_id
          ));

          $select_error_code = DB::select('select @success as error_code');

          if ($select_error_code) {
            $error_code = $select_error_code[0]->error_code;
            if ($error_code == 0) {
              DB::rollBack();
              throw new \Exception('Error saving Raffle. Error code is ' . $error_code); // Throwing exception rolls back the transaction
              return json_encode('Error saving Raffle, employee(s) for that categories already exist!');
            } else {
              DB::commit();
              return json_encode('Success');
            }
          } else {
            DB::rollBack();
            throw new \Exception('Error saving Raffle.'); // Throwing exception rolls back the transaction
          }
        }); 
      // }
        
      return $return_value;
      
    }
  }

  public function getCurrentWinners(Request $request)
  {
    $raffle_id = $request->raffleID;
    $expected_winners = DB::select(DB::raw(
      "select expected_winners from raffles where id = '$raffle_id'"
    ));

    $current_winners = DB::select(DB::raw(
      "select count(*) as current_winners from vw_rafflewinners where raffle_id = '$raffle_id'"
    ));

    if($expected_winners[0]->expected_winners <= $current_winners[0]->current_winners) {
      return response()->json([
        'status' => 'Completed', 
        'expectedWinners' => $expected_winners[0]->expected_winners, 
        'currentWinners' => $current_winners[0]->current_winners
      ]);
    } else {
      return response()->json([
        'expectedWinners' => $expected_winners[0]->expected_winners, 
        'currentWinners' => $current_winners[0]->current_winners
      ]);
    }
  }
}