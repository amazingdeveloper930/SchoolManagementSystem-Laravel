<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*Route::get('/', function () {
    return view('users.index');
})->name('users')->middleware(['auth','role:super_admin']);*/
Route::get('/','UserController@redirect_user')->middleware(['auth']);
//Route::get('error','HomeController@error')->middleware(['auth']);

/*Route::get('/gestion', function(){
	return view('cost_management.index');
})->name('management');*/

/*Route::get('/pagos',function(){
	return view('payments.index');
})->name('payments');*/

// Route::get('/costos/anualidad','AnnuityController@index')->name('annuity.index');

// Route::get('/costos/matricula','EnrollmentController@index')->name('enrollment.index');

//Route::get('test','UserController@test');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => ['auth']], function () {

	/**
	 * Usuarios
	 */
	Route::middleware(['role:super_admin'])->prefix('users')->group(function () {
		Route::get('/','UserController@index')->name('users');
		Route::get('/getData','UserController@getData')->name('users.getData');
	    Route::post('/store','UserController@store')->name('users.store');
	    Route::get('/edit/{id}','UserController@edit')->name('users.edit');
	    Route::put('/update','UserController@update')->name('users.update');
	    Route::delete('/delete/{id}','UserController@destroy')->name('users.destroy');

	});

	/**
     * Costos
     */
    Route::middleware(['permission:costs.index'])->prefix('costos')->group(function () {
    
	    Route::prefix('services')->group(function () {

	    	Route::get('/','ServiceController@index')->name('service.index');
		    Route::get('/getData','ServiceController@getData')->name('service.getData');
		    Route::post('/store','ServiceController@store')->name('service.store');
		    Route::put('/update','ServiceController@update')->name('service.update'); 
		    Route::get('/edit/{id}','ServiceController@edit')->name('service.edit');
		    Route::delete('/delete/{id}','ServiceController@destroy')->name('service.destroy');
	    });
    
	    Route::prefix('enrollment')->group(function(){

	    	Route::get('/','EnrollmentController@index')->name('enrollment.index');
	    	Route::get('/getData','EnrollmentController@getData')->name('enrollment.getData');
	    	Route::post('/store','EnrollmentController@store')->name('enrollment.store');
		    Route::put('/update','EnrollmentController@update')->name('enrollment.update'); 
		    Route::get('/edit/{id}','EnrollmentController@edit')->name('enrollment.edit');
		    Route::delete('/delete/{id}','EnrollmentController@destroy')->name('enrollment.destroy');

		    Route::get('/getAll','EnrollmentController@getAll')->name('enrollment.getAll');
	    });

	    Route::prefix('annuity')->group(function(){

	    	Route::get('/','AnnuityController@index')->name('annuity.index');
	    	Route::get('/getData','AnnuityController@getData')->name('annuity.getData');
	    	Route::post('/store','AnnuityController@store')->name('annuity.store');
		    Route::put('/update','AnnuityController@update')->name('annuity.update'); 
		    Route::get('/edit/{id}','AnnuityController@edit')->name('annuity.edit');
		    Route::delete('/delete/{id}','AnnuityController@destroy')->name('annuity.destroy');
	    });
    });

    /**
     * Estudiantes
     */
    Route::middleware(['permission:students.index'])->prefix('students')->group(function(){

    	Route::get('/','StudentController@index')->name('student.index');
	    Route::get('/getData','StudentController@getData')->name('student.getData');
	    Route::post('/store','StudentController@store')->name('student.store')->middleware('permission:students.store_edit');
	    Route::get('/edit/{id}','StudentController@edit')->name('student.edit');
	    Route::get('/{id}','StudentController@show')->name('student.show');
	   	Route::post('/update','StudentController@update')->name('student.update')->middleware('permission:students.store_edit');
	   	Route::get('/peaceSave/{id}','StudentController@peaceSave')->name('student.peaceSave');
	   	Route::get('/reminder/{student}','StudentController@reminder')->name('student.reminder');
	   	Route::get('/suspension/{student}/{contract}','StudentController@suspension')->name('student.suspension');
	   	Route::get('/defeated/{student}/{fee}/{nro}','StudentController@defeated')->name('student.defeated');
	   	Route::get('/remove/{student}','StudentController@removeStudent')->name('student.remove');

	   	Route::prefix('/contract')->group(function(){

	   		//Route::get('/getData','ContractController@getData')->name('contract.getData');

	   		Route::get('/create/{enrollment_id}','ContractController@create')->name('contract.create');//"year" es una prueba
	   		Route::post('/store','ContractController@store')->name('contract.store');
	   		Route::get('/show/{student_id}/{contract_year?}','ContractController@show')->name('contract.show');
	   		//Route::delete('delete/{student_id}','ContractController@destroy')->name('contract.delete')->middleware('permission:students.cancel');
	   		Route::delete('delete/{contract_id}','ContractController@destroy')->name('contract.delete')->middleware('permission:students.cancel');
	   		Route::get('/getData/{student_id}','ContractController@getData')->name('contract.getData');

	   		Route::post('/changeFee','ContractController@changeFee')->name('contract.changeFee');
	   	});
	   		 
    });

    /**
     * Pagos
     */
	Route::middleware(['permission:payments.index'])->prefix('/pagos')->group(function(){

		Route::get('/','PaymentController@index')->name('payment.index');
		//Route::get('/auxiliar/{id}, PaymentController@auxiliar')->name('payment.auxiliar');
		Route::get('/getData','PaymentController@getData')->name('payment.getData');
		Route::get('/create','PaymentController@create')->name('payment.create');
		Route::get('/create/getStudents','PaymentController@getStudents')->name('payment.getStudents');
		Route::get('/create/addStudent/{id}','PaymentController@addStudent')->name('payment.addStudent');
		Route::get('/create/getStudentsSelect','PaymentController@getSetudentsSelect')->name('payment.getStudents');
		Route::get('/create/getActivesServices/{student_id}','PaymentController@getActivesServices')->name('payment.getActivesServices');

		//Route::post('/store/extraPayments','PaymentController@storeExtraPayments')->name('payment.storeExtraPayments');

		Route::post('/store', 'PaymentController@store')->name('payment.store');

		//Route::post('/destroy/extraPayment', 'PaymentController@destroyExtraPayment')->name('payment.destroyExtraPayment');

		Route::get('/show/{id}', 'PaymentController@showReceipt')->name('payment.showReceipt');

		Route::post('/cancelPayment','PaymentController@cancelPayment')->name('payment.cancelPayment');

		Route::get('/printReceipt/{id}','PaymentController@printReceipt')->name('payment.printReceipt');
	});

	/**
	 * Reportes
	 */
	Route::prefix('/reports')->group(function(){

		Route::get('/','ReportsController@index')->name('reports.index');
		//Route::post('/bankConciliation','ReportsController@bankConciliation')->name('reports.bankConciliation');
		Route::get('/bankConciliation','ReportsController@bankConciliation')->name('reports.bankConciliation');
		//Route::post('/dailyReports','ReportsController@dailyReports')->name('reports.dailyReports');
		Route::get('/dailyReports','ReportsController@dailyReports')->name('reports.dailyReports');
		//Route::post('/transactionsMade','ReportsController@transactionsMade')->name('reports.transactionsMade');
		Route::get('/transactionsMade','ReportsController@transactionsMade')->name('reports.transactionsMade');
		//Route::post('/defaultersStudents','ReportsController@defaultersStudents')->name('reports.defaultersStudents');
		Route::get('/defaultersStudents','ReportsController@defaultersStudents')->name('reports.defaultersStudents');
		//Route::post('/accountReceivable','ReportsController@accountReceivable')->name('reports.accountReceivable');
		Route::get('/accountReceivable','ReportsController@accountReceivable')->name('reports.accountReceivable');
		Route::get('test','ReportsController@testPdf')->name('reports.test');
	});


	Route::prefix('/balance')->group(function(){

		Route::get('/','BalanceController@index')->name('balance.index');
		Route::get('/getData/{year?}','BalanceController@getData')->name('balance.getData');
		Route::get('/createBank/{month?}/{year?}','BalanceController@createBank')->name('balance.createBank');
		Route::post('/registreBank','BalanceController@registreBank')->name('balance.registreBank');
		//Route::get('/getDataStudents','BalanceController@getData2')->name('balance.getDataStudents');
		//Route::get('/students/{year?}','BalanceController@balanceStudents')->name('balance.balanceStudents');
	});


	Route::prefix('/config')->group(function(){

		Route::get('','ConfigController@index')->name('config.index');
		Route::get('/getData','ConfigController@getData')->name('config.getData');
		Route::post('/update','ConfigController@update')->name('config.update');
	});

});

