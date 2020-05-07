<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfExeption;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;
use Carbon\Carbon;
use App\Payment;
use App\Student;
use App\Enrollment;

class ReportsController extends Controller
{
    public function index(){

        return view('reports.index');
    }

    
    public function bankConciliation(Request $request){
        $title = 'Conciliación Bancaria';

        $datos = $request->all();

        $mes = '';

        switch ($datos['mes']){
            case 1 : $mes = 'Enero'; break;
            case 2 : $mes = 'Febrero'; break;
            case 3 : $mes = 'Marzo'; break;
            case 4 : $mes = 'Abril'; break;
            case 5 : $mes = 'Mayo'; break;
            case 6 : $mes = 'Junio'; break;
            case 7 : $mes = 'Julio'; break;
            case 8 : $mes = 'Agosto'; break;
            case 9 : $mes = 'Septiembre'; break;
            case 10 : $mes = 'Octubre'; break;
            case 11 : $mes = 'Noviembre'; break;
            case 12 : $mes = 'Diciembre'; break;
        }

        return view('reports.exports.bank_conciliation',compact('title','datos', 'mes'));

        // $pdf = PDF::loadView('reports.exports.bank_conciliation',compact('title','datos', 'mes'));

        // return $pdf->download('conciliacion_bancaria.pdf');
    }


    public function dailyReports(Request $request){

        $dia = Carbon::createFromFormat('d/m/Y',$request->report_date)->format('d');
        $month = (int)Carbon::createFromFormat('d/m/Y',$request->report_date)->format('m');

        switch ((int)Carbon::createFromFormat('d/m/Y',$request->report_date)->format('m')){
            case 1 : $mes = 'Enero'; break;
            case 2 : $mes = 'Febrero'; break;
            case 3 : $mes = 'Marzo'; break;
            case 4 : $mes = 'Abril'; break;
            case 5 : $mes = 'Mayo'; break;
            case 6 : $mes = 'Junio'; break;
            case 7 : $mes = 'Julio'; break;
            case 8 : $mes = 'Agosto'; break;
            case 9 : $mes = 'Septiembre'; break;
            case 10 : $mes = 'Octubre'; break;
            case 11 : $mes = 'Noviembre'; break;
            case 12 : $mes = 'Diciembre'; break;
        }

        $anho = Carbon::createFromFormat('d/m/Y',$request->report_date)->format('Y');
        

        $title = 'Informe de Caja';

        $subtitle = $dia . ' de ' . $mes . ' de ' . $anho;

        $fecha = Carbon::createFromFormat('d/m/Y',$request->report_date)->format('Y-m-d');

        $payments = Payment::where('created_at','like','%'.$fecha.'%')->where('status',1)->orderBy('id','desc')->get();
        $payments_totals = Payment::where('created_at','like','%'.$fecha.'%')->where('status',1);
        $payments_p = Payment::where('created_at','like','%'.$fecha.'%')->where('status',1);

        $total_ica = $payments_totals->where('pay_method',1)->whereMonth('deposit_at',$month)->get()->sum('amount');
        $total_gc = $payments->where('pay_method',2)->sum('amount');

        $total_previous_monthos = $payments_p->where('pay_method',1)->whereMonth('deposit_at','<',$month)->sum('amount');

        $total = $payments_totals->where('status',1)->sum('amount');

        foreach($payments as $payment){
            $payment->deposit_at = Carbon::createFromFormat('Y-m-d',$payment->deposit_at)->format('d/m/Y');
        }

        return view('reports.exports.daily_cash_report',compact('title','subtitle','mes','payments','total_ica','total_gc','total_previous_monthos','total'));

        // $pdf = PDF::loadView('reports.exports.daily_cash_report',compact('title','subtitle','mes','payments','total_ica','total_gc','total_previous_monthos','total'));

        // return $pdf->download('Reporte_diario_caja.pdf');
    }


    public function transactionsMade(Request $request){

        $title = 'Historial de Transacciones';

        $subtitle = $request->transaction_since_date . ' - ' . $request->transaction_final_date;

        $since = Carbon::createFromFormat('d/m/Y',$request->transaction_since_date)->format('Y-m-d');
        $final = Carbon::createFromFormat('d/m/Y',$request->transaction_final_date)->format('Y-m-d');
        //$payments = Payment::where('created_at','like','%'.$fecha.'%')->orderBy('id','desc')->get();

        $payments = Payment::where('deposit_at','>=',$since)->where('deposit_at','<=', $final)->orderBy('id','desc')->get();

        $total_active = 0;
        $total_cancel = 0;
        $total_refund = 0;

        foreach($payments as $payment){
            
            $payment->deposit_at = Carbon::createFromFormat('Y-m-d',$payment->deposit_at)->format('d/m/Y');

            if($payment->status == 1) $total_active += $payment->amount;
            if($payment->status == 0) $total_cancel += $payment->amount;
            if($payment->status == 1 && $payment->refund != 0) $total_refund += $payment->refund;
        }

        return view('reports.exports.transactions_made',compact('title','subtitle','payments','total_active','total_cancel','total_refund'));

        // $pdf = PDF::loadView('reports.exports.transactions_made',compact('title','subtitle','payments','total_active','total_cancel','total_refund'));

        // return $pdf->download('Transacciones_realizadas.pdf');
    }


    public function defaultersStudents(){

        $title = 'Informe De Morosos';

        $subtitle = '';

        $total_1fee = 0;
        $total_2fee = 0;
        $other = 0;

        $students = Student::where('status','!=',4)->whereHas('contracts', function($query){
            $query->orderBy('id','desc')->take(1)->whereHas('fees',function($query2){
                $query2->where('status',0);
                $query2->orWhere('order',1)->whereColumn('cost','>','paid_out');
            });
        })->get();

        $other_student = Student::where('status','!=',4)->whereHas('contracts', function($query){
            $query->orWhereColumn('enrollment_cost','>','paid_out')->orderBy('id','desc')->take(1)->whereHas('fees',function($query2){
                $query2->where('status',1);
                $query2->orWhere('status',2);
                $query2->where('order',1)->whereColumn('cost','paid_out');
            });
        })->whereHas('contracts', function($q){
            $q->whereHas('contract_services', function($q2){
                $q2->orWhere('cost','>','paid_out');
            });
        })->get();

        foreach($students as $student){
            
            if($student->contracts->last()->fees()->where('status',0)->count() == 1) $total_1fee++;
            if($student->contracts->last()->fees()->where('status',0)->count() > 1) $total_2fee++;
            if($student->contracts->last()->fees()->where('status',0)->count() == 0 && $student->contracts->last()->fees()->where('order',1)->whereColumn('cost','>','paid_out')->count() >= 1) $total_1fee++;
            // if($student->contracts->last()->fees()->where('status',0)->count() == 0 && $student->contracts->last()->fees()->where('order',1)->whereColumn('cost','=','paid_out')->count() >= 1 && $student->contracts->last()->contract_services()->whereColumn('cost','>','paid_out')->count() >= 1) $other++;
        }

        foreach($other_student as $student){
            if($student->contracts->last()->fees()->where('status',0)->count() == 0 && $student->contracts->last()->fees()->where('order',1)->whereColumn('cost','=','paid_out')->count() >= 1 && $student->contracts->last()->contract_services()->whereColumn('cost','>','paid_out')->count() >= 1) {
                if ($student->contracts->last()->whereColumn('enrollment_cost','>','paid_out')->count() > 0 || $student->contracts->last()->contract_services()->whereColumn('cost','>','paid_out')->count() >= 1) {
                $other++;
            }
            }
        }

        return view('reports.exports.defaulters_students',compact('title','subtitle','students','total_1fee','total_2fee','other_student','other'));

        // $pdf = PDF::loadView('reports.exports.defaulters_students',compact('title','subtitle','students','total_1fee','total_2fee'));

        // return $pdf->download('Morosos.pdf');
    }


    public function accountReceivable(){

        $title = 'Cuentas por cobrar';

        $subtitle = '';

        $students = Student::where('status','!=',4)->where('peace_save',0)->whereHas('contracts',function($query){
            $query->orderBy('id','desc')->take(1);
        })->get();

        $enrollments = Enrollment::orderBy('grade','asc')->get();

        $enrollments_ = [];

        $total = 0;
        $total_students = 0;

        foreach($enrollments as $enrollment){

            $enrollment_ = [
                'grade'     => $enrollment->grade,
                'bachelor'  => $enrollment->bachelor,
                'total'     => 0,
                'students'  => [],
            ];

            $total_enrollment = 0;

            foreach($students as $student){

                if($student->contracts->last()->enrollment_grade == $enrollment->grade && $student->contracts->last()->enrollment_bachelor == $enrollment->bachelor){

                    $student_ = [
                        'name'          => '',
                        'contact'       => [
                                'attendant'     => $student->attendant,
                                'email'         => $student->email,
                                'phone'         => $student->phone
                        ],
                        'fee_cost'      => 0,
                        'recharge_cost' => 0,
                        'fees'          => '',
                        'total'         => 0,
                    ];


                    $fees_aux = $student->contracts->last()->fees()
                                    ->where(function($query){
                                        $query->where('status',0);
                                        $query->orWhere('order',1)->whereColumn('cost','>','paid_out');
                                    })->get();


                    $student_['name'] = $student->name;
                    
                    $student_['fee_cost'] = $fees_aux->sum('cost') - $fees_aux->sum('paid_out');
                    
                    $student_['recharge_cost'] =    $fees_aux->sum('r15') - $fees_aux->sum('r15_paid_out') +
                                                    $fees_aux->sum('r1') - $fees_aux->sum('r1_paid_out') ;

                    foreach($fees_aux as $fee){                         
                        $student_['fees'] .= $fee->order . '-';
                    }

                    $student_['total'] =    $student_['fee_cost'] + $student_['recharge_cost'] +
                                            $student->contracts->last()->enrollment_cost - 
                                            $student->contracts->last()->paid_out;

                    if($student->contracts->last()->contract_services->count() > 0){
                        $student_['total'] +=   $student->contracts->last()->contract_services->sum('cost') - 
                                                $student->contracts->last()->contract_services->sum('paid_out');
                    }

                    $total_enrollment += $student_['total'];
                    $total += $student_['total'];
                    
                    if($student_['total'] != 0){
                        $total_students++;
                        array_push($enrollment_['students'],$student_);
                    }
                }
            }

            $enrollment_['total'] = $total_enrollment;

            array_push($enrollments_, $enrollment_);
        }

        // $pdf = PDF::loadView('reports.exports.accounts_receivable',compact('title','subtitle','enrollments_','total','total_students'));

        // return $pdf->download('Cuentas_por_cobrar.pdf');
        
        return view('reports.exports.accounts_receivable',compact('title','subtitle','enrollments_','total','total_students'));
    }

    public function testPdf() {
        $html2pdf = new Html2Pdf();
        $html2pdf->writeHTML('<h1>HelloWorld</h1>This is my first test');
        $html2pdf->output();
    }


    public function create(){

        //
    }

    
    public function store(Request $request){

        //
    }

   
    public function show($id){
        
        //
    }


    public function edit($id){
       
        //
    }
    

    public function update(Request $request, $id){
        
        //
    }
    

    public function destroy($id){
        
        //
    }
}
