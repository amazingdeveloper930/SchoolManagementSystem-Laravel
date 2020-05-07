@extends('reports.exports.layout_report')

@section('title', $title)

@section('subtitle',$subtitle)

@section('content')
	
	<br>
	<br>

	<div class="container">
	
		@if($payments->where('status',1)->count() > 0)
			<table class="table table-striped" id="m_table_1">
				<thead>
					<tr><td colspan="6"></td></tr>
					<tr>
						<th colspan="6">Transacciones Activas <span class=""> Total: {{ number_format($total_active,2) }}</span></th>
					</tr>
					<tr>
	                    <th>Recibo</th>
						<th>No. Operacion</th>
						<th>Fecha</th>
						<th>Estudiante(s)</th>
						<th>Grado</th>
	                    <th>Monto</th>
					</tr>
				</thead>
				<tbody>
					@foreach($payments as $payment)
						@if($payment->status == 1)
							<tr>
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
										{{ !is_null($student['contract_id']) ? \App\Contract::find($student['contract_id'])->enrollment_grade : '-' }}
									@endforeach
								</td>
								<td>{{ $payment->amount }}</td>
							</tr>
						@endif
					@endforeach
				</tbody>
			</table>

			<br><br>
		@endif

		
		@if($payments->where('status',0)->count() > 0)
			<table class="table table-striped" id="m_table_1">
				<thead>
					<tr><td colspan="6"></td></tr>
					<tr>
						<th colspan="6">Transacciones Canceladas <span class="pull-right">Total: {{ number_format($total_cancel,2) }}</span></th>
					</tr>
					<tr>
	                    <th>Recibo</th>
						<th>No. Operacion</th>
						<th>Fecha</th>
						<th>Estudiante(s)</th>
						<th>Grado</th>
	                    <th>Monto</th>
					</tr>
				</thead>
				<tbody>
					@foreach($payments as $payment)
						@if($payment->status == 0)
							<tr>
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
						@endif
					@endforeach
				</tbody>
			</table>

			<br><br>
		@endif

		
		@if($payments->where('status',1)->where('refund','>',0)->count() > 0)
			<table class="table table-striped" id="m_table_1">
				<thead>
					<tr><td colspan="6"></td></tr>
					<tr>
						<th colspan="6">Reembolsos <span class="pull-right">Total: {{ number_format($total_refund,2) }}</span></th>
					</tr>
					<tr>
	                    <th>Recibo</th>
						<th>No. Operacion</th>
						<th>Fecha</th>
						<th>Estudiante(s)</th>
						<th>Grado</th>
	                    <th>Monto</th>
					</tr>
				</thead>
				<tbody>
					@foreach($payments as $payment)
						@if($payment->refund != 0 && $payment->status == 1)
							<tr>
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
								<td>{{ $payment->refund }}</td>
							</tr>
						@endif
					@endforeach
				</tbody>
			</table>
		@endif
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
