<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Enrollment;

class EnrollmentController extends Controller
{
    
    public function index()
    {
        return view('cost_management.enrollment.index');
    }

    public function getData(Request $request){

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $mostrar = $request->mostrar;

        if ($buscar == '') {
            $enrollments = Enrollment::orderBy('id','desc')->paginate($mostrar);
        } else {
            $enrollments = Enrollment::where($criterio, 'like', '%' . $buscar . '%')->orderBy('id','desc')->paginate($mostrar);
        }

        return [
            'pagination' => [
                'total' => $enrollments->total(),
                'current_page' => $enrollments->currentPage(),
                'per_page' => $enrollments->perPage(),
                'last_page' => $enrollments->lastPage(),
                'from' => $enrollments->firstItem(),
                'to' => $enrollments->lastItem(),
            ],
            'enrollments' => $enrollments
        ];

    }



    public function getAll(){

        return response()->json([
            'enrollments' => Enrollment::all(),
        ]);
    }




    public function store(Request $request)
    {
        /*$validate = $request->validate([
            'grade' => 'required|string|',
            'bachelor' => 'required|string',
            'cost' => 'required|numeric'
        ]);
        
        $enrollment = new Enrollment($request->all());*/

        $enrollment = new Enrollment;
        $enrollment->grade = $request->grade;
        $enrollment->bachelor = $request->bachelor;
        $enrollment->cost = $request->cost;

        $enrollment->save();

        return response()->json([
            'result' => 'Exito',
        ]);
    }


    
    public function edit($id)
    {
        $enrollment = Enrollment::find($id);

        return response()->json([
            'enrollment' => $enrollment
        ]);
    }

    
    public function update(Request $request)
    {
        $validate = $request->validate([
            'grade' => 'required|string|',
            'bachelor' => 'required|string',
            'cost' => 'required|numeric'
        ]);

        $enrollment = Enrollment::find($request->id);

        $enrollment->update($request->all());

        return response()->json([
            'result' => 'Exito',
        ]);
    }

    
    public function destroy($id)
    {
        $enrollment = Enrollment::find($id);

        $enrollment->delete();

        return response()->json([
            'result'=>'Exito',
        ]);
    }
}
