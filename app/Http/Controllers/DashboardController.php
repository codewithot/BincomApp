<?php

namespace App\Http\Controllers;

use App\Models\Lga;
use App\Models\Party;
use App\Models\Polling;
use App\Models\LgaResult;
use Illuminate\Http\Request;
use App\Models\PollingResult;

class DashboardController extends Controller
{
    public function index(){
        $polling_units = Polling::all('uniqueid','polling_unit_name');
        $lga = Lga::all('uniqueid','lga_name');
       
        return view('dashboard')->with(['units'=>$polling_units,'lga'=>$lga]);
    }
    public function show(Request $input){
       if(!$input['pu'] && $input['lga']){
        $result = LgaResult::where('lga_name',$input['lga'])->selectRaw("SUM(party_score) as score,party_abbreviation")->groupBy('party_abbreviation')->orderBy('score','DESC')->get(['party_abbreviation','party_score']);
    }else{
        if($input['pu'] == 'all'){
            $result = PollingResult::leftJoin('polling_unit', function($join) {
                $join->on('announced_pu_results.polling_unit_uniqueid', '=', 'polling_unit.uniqueid');
              })->where('lga_id',$input['lga'])->selectRaw("SUM(party_score) as score,party_abbreviation")->groupBy('party_abbreviation')->orderBy('score','DESC')->get('party_abbreviation','party_score');
            }else{
        $result = PollingResult::where('polling_unit_uniqueid',$input['pu'])->selectRaw("SUM(party_score) as score,party_abbreviation")->groupBy('party_abbreviation')->orderBy('score','DESC')->get(['party_abbreviation','party_score']);
           }

        }
        return view('results')->with(['resu'=>$result]);

}       

public function create(){
    $polling_units = Polling::all('uniqueid','polling_unit_name');
    $party = Party::all('id','partyname');
   
    return view('create')->with(['units'=>$polling_units,'party'=>$party]);
}
public function store(Request $request){
    $formfields =[];
    $input = $request->validate([
        'pu' => 'required',
        'party' => 'required',
        'score' => 'required',
        'entree' => 'required',
       ]);
       $formfields['date_entered'] = date('Y-m-d H:i:s');
       $formfields['user_ip_address'] =  $request->ip();
       $formfields['polling_unit_uniqueid'] =  $input['pu'];
       $formfields['party_abbreviation'] =  $input['party'];
       $formfields['party_score'] = $input['score'];
       $formfields['entered_by_user'] = $input['entree'];
       PollingResult::create($formfields);
       return redirect('/create')->with('message', 'Result entered successfully');

}
}
