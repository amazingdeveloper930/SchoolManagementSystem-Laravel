@extends('reports.exports.layout_report')

@section('title', $title)

@section('subtitle',$mes)

@section('content')
	
	<br>
	<br>
	<br>

	<div class="container">
		<h5>Total del mes registrado <span class="small pull-right"></span></h5>

		<br>
		
		<h5>Total del mes en banco <span class="small pull-right">{{ $datos['total_bank'] }}</span></h5>

		<br>

		<h5>Pagos no ingresados <span class="small pull-right"></span></h5>
		
		<br>

		<hr>

		<br>
		
		<h5>Estado de cuenta banco (débito) <span class="small pull-right">{{ $datos['bank_account_state'] }}</span></h5>

		<br>

		<h5>Transferencia entre cuentas <span class="small pull-right">{{ $datos['account_transfer'] }}</span></h5>

		<br>

		<h5>Transferencia de cuenta de ahorro <span class="small pull-right">{{ $datos['account_transfer_saving']}}</span></h5>
		
		<br>

		<h5>Total <span class="small pull-right">{{ $datos['total'] }}</span></h5>

		<br>

		<hr>

		<br>

		<h5>Recibo de meses anteriores <span class="small pull-right"></span></h5>

		<br>

		<h5>Saldo disponible del banco <span class="small pull-right">{{ $datos['available_balance'] }}</span></h5>

		<br>

		<h5>Saldo disponible segun libro <span class="small pull-right">{{ $datos['available_balance_books'] }}</span></h5>
	</div>

	<script type="text/javascript">

		(function() {
		   // your page initialization code here
		   // the DOM will be available here
		   	setTimeout(function(){},20000);
		 	window.print();
			// window.close();

		})();
	</script>

@endsection
