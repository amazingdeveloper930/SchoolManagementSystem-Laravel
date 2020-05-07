<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Annuity;
use Carbon\Carbon;

class AnnuityController extends Controller
{
    
    public function index()
    {
        return view('cost_management.annuity.index');
    }

    
    public function getData(Request $request){

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $mostrar = $request->mostrar;
        $toYear = Carbon::now()->format('Y');

        if ($buscar == '') {
            $annuitys = Annuity::orderBy('id','desc')->paginate($mostrar);
            //$annuitys = Annuity::with('enrollment')->orderBy('id','desc')->paginate($mostrar);
        } else {
            $annuitys = Annuity::where($criterio, 'like', '%' . $buscar . '%')->orderBy('id','desc')->paginate($mostrar);
            /*$annuitys = Annuity::with('enrollment')
                ->where($criterio, 'like', '%' . $buscar . '%')
                ->orderBy('id','desc')
                ->paginate($mostrar);*/
        }

        foreach($annuitys as $annuity){

            $annuity->maximum_date = Carbon::createFromFormat('Y-m-d',$annuity->maximum_date)->format('d/m/Y');
        }

        return [
            'pagination' => [
                'total' => $annuitys->total(),
                'current_page' => $annuitys->currentPage(),
                'per_page' => $annuitys->perPage(),
                'last_page' => $annuitys->lastPage(),
                'from' => $annuitys->firstItem(),
                'to' => $annuitys->lastItem(),
            ],
            'annuitys' => $annuitys,
            'toYear' => $toYear,
        ];
    }
    


    public function store(Request $request)
    {
        /*$validate = $request->validate([
            'grade' => 'required|string',
            'cost' => 'required|numeric',
            'discount' => 'required|numeric',
            'maximum_date' => 'required|date',
            'second_month' => 'required|date',
        ]);*/
        
        $annuity = new Annuity;
        $annuity->year = $request->year;
        $annuity->cost = $request->cost;
        $annuity->discount = $request->discount;
        $annuity->maximum_date = Carbon::createFromFormat('d/m/Y', $request->maximum_date);
        $annuity->second_month = $request->second_month;

        $annuity->save();

        return response()->json([
            'result' => 'Exito',
        ]);
    }
    



    public function edit($id)
    {        
        $annuity = Annuity::find($id);

        $annuity->maximum_date = Carbon::createFromFormat('Y-m-d',$annuity->maximum_date)->format('d/m/Y');

        return response()->json([
            'annuity' => $annuity
        ]);
    }

    



    public function update(Request $request){
        /*$validate = $request->validate([
            'cost' => 'required|numeric',
            'discount' => 'required|numeric',
            'maximum_date' => 'required|date',
            'second_month' => 'required|date',
        ]);*/

        $annuity = Annuity::find($request->id);
        
        $annuity->year = $request->year;
        $annuity->cost = $request->cost;
        $annuity->discount = $request->discount;
        $annuity->maximum_date = Carbon::createFromFormat('d/m/Y', $request->maximum_date);
        $annuity->second_month = $request->second_month;

        $annuity->save();

        return response()->json([
            'result' => 'Exito',
        ]);
    }

    


    
    public function destroy($id)
    {
        $annuity = Annuity::find($id);

        $annuity->delete();

        return response()->json([
            'result' => 'Exito',
        ]);
    }
}
