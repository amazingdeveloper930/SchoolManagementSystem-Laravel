@extends('reports.exports.layout_report')

@section('title', $title)

@section('subtitle', $subtitle)

@section('content')
	
	<br>
	<br>
	<br>

	<div class="container">

		<div class="row">
			
			<div class="col-XS-12">
				<span style="padding: 10px 20px, border: 1px solid black">
					<strong style="font-size: 1.2em;">Total Recibos ICA: {{ number_format($total_ica,2) }}</strong>
				</span>
				<span style="padding: 10px 20px, border: 1px solid #888" class="pull-right">
					<strong style="font-size: 1.2em;">Total gestion de cobros: {{ number_format($total_gc,2) }}</strong>
				</span>
			</div>
		</div>
	
		<br>
	
		<div class="row">
			
			<div class="col-XS-12">
				<span style="padding: 10px 20px, border: 1px solid #888">
					<strong style="font-size: 1.2em;">Total meses anteriores: {{ number_format($total_previous_monthos,2) }}</strong>
				</span>
				<span style="padding: 10px 20px, border: 1px solid #888" class="pull-right">
					<strong style="font-size: 1.2em;">Total Gestion de cobros y recibos: {{ number_format($payments->where('status',1)->sum('amount'),2) }}</strong>
				</span>
			</div>
		</div>
		
		<br><br>

		<table class="table table-striped" id="m_table_1">
			<thead>
				<tr>
                    <th>Recibo</th>
					<th>No. Operacion</th>
					<th>Fecha de deposito</th>
					<th>Estudiante(s)</th>
					<th>Grado</th>
                    <th>Monto</th>
				</tr>
			</thead>
			<tbody>
				@php
					$total = 0;
				@endphp
				@foreach($payments as $payment)
					<tr>
						@php
							$total += $payment->amount;
						@endphp
						<td>{{ $payment->receipt }}</td>
	                    <td>{{ $payment->operation_number }}</td>
						<td>{{ $payment->deposit_at }}</td>
						<td>
							@foreach(json_decode($payment->info_str,true) as $student)
								{{ $student['name'] }} <br>
							@endforeach
						</td>
						<td>
							@foreach(json_decode($payment->info_str,true) as $student)
								{{ $student['description'] }} <br>
							@endforeach
						</td>
						<td>{{ $payment->amount }}</td>
					</tr>
				@endforeach
				@if ($total > 0)
					<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><strong>Total:</strong></td>
					<td>
						@php
							$total = number_format($total, 2, '.', '');
						@endphp
						{{ $total }}
					</td>
					</tr>
				@endif
			</tbody>
		</table>
	</div>

	<script type="text/javascript">

		(function() {
		   // your page initialization code here
		   // the DOM will be available here
		   	setTimeout(function(){
		   		window.print();
		   	},1000);
			// window.close();

		})();
	</script>

@endsection
