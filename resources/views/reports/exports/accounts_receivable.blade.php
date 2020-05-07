@extends('reports.exports.layout_report')

@section('title', $title)

@section('subtitle', $subtitle)

@section('content')
	
	<br>

	<div class="container">

		<div class="row">
			
			<div class="col-xs-12">
				<span style="padding: 10px 0px; font-size: 16px">
					<strong style="font-size: 1.2em;">Total por cobrar: {{ number_format($total,2) }}</strong>
				</span>
				<span style="padding: 10px 20px; font-size: 16px; margin-top: -12px;" class="pull-right">
					<strong style="font-size: 1.2em;">Total de estudiantes: {{ $total_students }}</strong>
				</span>
			</div>
		</div>
		
		<br>
		
			@foreach($enrollments_ as $enrollment)
				@if(count($enrollment['students']) > 0)
				<table class="table table-striped">
					<thead class="">
						<tr>
							<th colspan="5"></th>
						</tr>
						<tr class="cabecera">
							<th colspan="2">
								{{ $enrollment['grade'] }}  {{ $enrollment['bachelor'] }} 
								<span class="small">- {{ count($enrollment['students']) }} Estudiante(s)</span>
							</th>
							<th></th>
							<th></th>
							<th colspan="2" style="text-align: right; font-size: 16px">
								Total: {{ number_format($enrollment['total'],2) }}
							</th>
						</tr>
						<tr>
		                    <th>Estudiante</th>
		                    <th>Contacto</th>
							<th>Cuotas</th>
							<th>Recargos</th>
							<th>Cuotas #</th>
							<th>Total</th>
						</tr>
					</thead>

					<tbody>

						@foreach($enrollment['students'] as $student)
								<tr>
									<td>{{ $student['name'] }}</td>

									<td>
										@foreach($student['contact'] as $contact)
											{{ $contact }} <br>
										@endforeach
									</td>

									<td>{{ number_format($student['fee_cost'],2) }}</td>
									
									<td>{{ number_format($student['recharge_cost'],2) }}</td>
									
				                    <td>{{ $student['fees'] }}</td>
									
									<td style="font-size: 16px"><strong>{{ number_format($student['total'],2)}}</strong></td>
								</tr>
						@endforeach
					</tbody>
				</table>
				@endif
			@endforeach
		
	</div>

	<script type="text/javascript">

		(function() {
		   // your page initialization code here
		   // the DOM will be available here
		   	setTimeout(function(){},2000);
		 	window.print();
			// window.close();

		})();
	</script>

	<style>

		.cabecera {
			background-color: #ABB2B9;
		}

		@media print {
		    /* Aquí irían tus reglas CSS específicas para imprimir */
		    .cabecera {
			background-color: #ABB2B9;
			}
		}

	</style>

@endsection
