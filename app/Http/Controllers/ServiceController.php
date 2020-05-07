<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Service;
use Carbon\Carbon;

class ServiceController extends Controller
{

    public function index()
    {
        return view('cost_management.service.index');
    }

    public function getData(Request $request)
    {

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $mostrar = $request->mostrar;

        if ($buscar == '') {
            $services = Service::orderBy('id','desc')->paginate($mostrar);
        } else {
            $services = Service::where($criterio, 'like', '%' . $buscar . '%')->orderBy('id','desc')->paginate($mostrar);
        }

        return [
            'pagination' => [
                'total' => $services->total(),
                'current_page' => $services->currentPage(),
                'per_page' => $services->perPage(),
                'last_page' => $services->lastPage(),
                'from' => $services->firstItem(),
                'to' => $services->lastItem(),
            ],
            'services' => $services
        ];

    }

    public function store(Request $request)
    {

        $service = new Service;
        $service->description = $request->description;
        $service->cost = $request->cost;
        $service->state = $request->state;
        //$service->receipt = '[]';

        $service->save();

        return response()->json([
            'result' => 'Exito',
        ]);
    }




    public function show($id)
    {
        //
    }




    public function edit($id)
    {
        $service = Service::find($id);

        return response()->json([
            'service' => $service
        ]);
    }




    public function update(Request $request)
    {
        $validate = $request->validate([
            'description' => 'required|string',
            'cost' => 'required|numeric',
            'state' => 'required|integer',
        ]);

        $service = Service::find($request->id);

        $service->update($request->all());

        return response()->json([
            'result' => 'Exito',
        ]);
    }




    public function destroy($id)
    {
        $service = Service::find($id);

        $service->delete();

        return response()->json([
            'result' => 'Exito',
        ]);
    }
}
