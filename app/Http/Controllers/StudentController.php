<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\{Student, Enrollment, Contract};
use App\Fee;
use App\Config;
use Carbon\Carbon;

class StudentController extends Controller{


    public function index(){

        return view('students.index');
    }

    public function getData(Request $request){

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $mostrar = $request->mostrar;
        $grade = $request->grade;
        $state = $request->student_state_o;

        $enrrollements = Enrollment::all();

        if ($buscar == '')
            $students = Student::orderBy('id','desc');

        else if($buscar == 'desc' || $buscar == 'asc') {
            $students = Student::orderBy($criterio, $buscar);
        }

        else {
            
            //$students = Student::where($criterio, 'like', '%' . $buscar . '%')->orderBy('id','desc')->paginate($mostrar);

            $columns = ['name','attendant','personal_id'];
            $search = explode(' ',$buscar);

                // $students = Student::from('students as a')->where(function ($query) use ($columns,$search) {
                // foreach ($search as $palabra) {
                //     $query = $query->where(function ($query) use ($columns,$palabra) {
                //         foreach ($columns as $column) {
                //             $query->orWhere($column,'like',"%$palabra%");
                //         }
                //     });
                // }
                // });

            $students = Student::where(function ($q) use ($buscar) {
            $q->where('name', 'like', '%' . $buscar . '%')
                ->orWhere('attendant', 'like', '%' . $buscar . '%')
                ->orWhere('personal_id', 'like', '%' . $buscar . '%');
            });
        }

        if ($grade != '') {
            $g = Enrollment::find($grade);
            $students->whereHas('contracts', function($query) use ($g) {
                $query->where('enrollment_grade','like',"%$g->grade%");
                $query->where('enrollment_bachelor','like',"%$g->bachelor%");
            });
        }

        if ($state != 3) {
            $students->where('status',$state);
        }

        $students = $students->paginate($mostrar);

        return [
            'pagination' => [
                'total' => $students->total(),
                'current_page' => $students->currentPage(),
                'per_page' => $students->perPage(),
                'last_page' => $students->lastPage(),
                'from' => $students->firstItem(),
                'to' => $students->lastItem(),
            ],
            'students' => $students,
            'enrrollements' => $enrrollements,
        ];
    }



    public function show($id){
        $student = Student::find($id);
        $contracts = $student->contracts;
        //$contracts = Contract::where('student_id',$id)->orderBy('year','asc')->get();
        return view('students.student.index', compact('student', 'contracts'));
    }

    public function store(Request $request){
        $request->validate([
            'personal_id' => 'unique:students'
        ]);

        $student = new Student;

        $student->personal_id = $request->get('personal_id');
        $student->name = $request->get('name');
        $student->email = $request->get('email');
        $student->phone = $request->get('phone');
        $student->attendant = $request->get('attendant');

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time().'.'.$image->getClientOriginalExtension();
            $image->move(public_path('app/images-students/'), $imageName);
            $student->image = $imageName;
        }

        $student->save();

        return response()->json([
            'result' => 'Exito',
        ]);
    }

    public function edit($id){
        $student = student::find($id);

        return response()->json([
            'student' => $student
        ]);
    }

    public function update(Request $request){

        $student = Student::find($request->get('id'));

        if ($request->get('personal_id') != $student->personal_id) {
        	$request->validate([
            'personal_id' => 'unique:students'
        	]);
        }

        $student->name = $request->get('name');
        $student->personal_id = $request->get('personal_id');
        $student->email = $request->get('email');
        $student->phone = $request->get('phone');
        $student->attendant = $request->get('attendant');

        if($request->hasFile('image')){
            unlink(public_path('app/images-students/' . $student->image));

            $image = $request->file('image');
            $imageName = time().'.'.$image->getClientOriginalExtension();
            $image->move(public_path('app/images-students/'), $imageName);
            $student->image = $imageName;
        }
        
        
        $student->update();

        return response()->json([
            'result' => 'Exito',
        ]);
    }
    
    public function peaceSave($id){

        $student = Student::find($id);

        if($student->peace_save == 1){
            return view('students.student.peace_save', compact('student'));
        }

        return abort(403);
    }

    public function reminder(Student $student)
    {
        $fee = $student->contracts->last()->fees->where('status',1)->first();
        $date =  Carbon::parse($fee->date)->format('Y-m-d');
        $month_aux =  Carbon::parse($date)->format('m') == 1 ? 12 : Carbon::parse($date)->format('m') - 1;
        $month_ven = Carbon::createFromFormat('m', $month_aux);
        $director = Config::where('type','name')->first();
        $firma = Config::where('type','firma')->first();

        return view('students.student.reminder', compact('fee','date','month_aux','month_ven','director','firma','student'));
        
    }

    public function suspension(Student $student, Contract $contract)
    {
        $print = 1;
        $date = $contract->fees->where('status',0)->last()->date;
        $date = Carbon::parse($date)->addDay(2)->format('d/m/Y');
        $director = Config::where('type','name')->first();
        $firma = Config::where('type','firma')->first();

        return view('emails.students.suspension', compact('student','print','date','director','firma'));
    }

    public function defeated(Student $student, Fee $fee, $nro)
    {
        $print = 1;
        $contract = $student->contracts->last();
        $services = $contract->contract_services;
        $director = Config::where('type','name')->first();
        $firma = Config::where('type','firma')->first();

        $yearNow = Carbon::now()->addYear()->year;

        return view('emails.students.overdue_payment', compact('student','fee','contract','services','nro','print','yearNow','director','firma'));
    }

    public function removeStudent(Student $student) {
        $student->status = 4;
        $student->save();
        return [
            'student' => $student
        ];
    }

    public function destroy($id)
    {
        //
    }


}
