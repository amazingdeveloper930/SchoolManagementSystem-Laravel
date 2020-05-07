@extends('reports.exports.layout_report')

@section('title', $title)

@section('subtitle',$subtitle)

@section('content')
	
	<br>
	<br>

	<div class="container">
		@if($total_1fee > 0)
			<table class="table table-striped" id="m_table_1">
				<thead>
					<tr><td colspan="5"></td></tr>
					<tr>
						<th colspan="5">1 Cuota<span class="pull-right">{{ $total_1fee }} Estudiante(s)</span></th>
					</tr>
					<tr>
	                    <th>Estudiante</th>
						<th>Grado</th>
						<th>Bachiller</th>
						<th>Cuotas</th>
						<th>Total</th>
					</tr>
				</thead>
				<tbody>
					@php
						$total = 0;
					@endphp
					@foreach($students as $student)
						@if($student->contracts->last()->fees()->where('status',0)->count() == 1)
							<tr>
								<td>{{ $student->name }}</td>
			                    <td>{{ $student->contracts->last()->enrollment_grade }}</td>
								<td>{{ $student->contracts->last()->enrollment_bachelor }}</td>
								<td>
									{{ $student->contracts->last()->fees()->where('status',0)->first()->order }}
								</td>
								<td>
									@php
										$services_cost = 0;
										$student_contract = $student->contracts->last();
										$services = $student_contract->contract_services;

										$enrollment = $student_contract->enrollment_cost - $student_contract->paid_out;
										foreach ($services as $service) {
											$services_cost += $service->cost - $service->paid_out;
										}
										$aux = $student->contracts->last()->fees()->where('status',0)->first();
										$fee_1 = $student->contracts->last()->fees()->where('order',1)->first();

										$fees = ($aux->cost - $aux->paid_out) +
											($aux->r15 - $aux->r15_paid_out) +
											($aux->r1 - $aux->r1_paid_out) + 
											($fee_1->cost - $fee_1->paid_out);

										$total += $fees + $enrollment + $services_cost;
									@endphp
								
									{{ 
										number_format(
											$fees + $enrollment + $services_cost,
										2)
									}}
								</td>
							</tr>
						@endif

						@if ($student->contracts->last()->fees()->where('status',0)->count() == 0 && $student->contracts->last()->fees()->where('order',1)->whereColumn('cost','>','paid_out')->count() >= 1)
							<tr>
								<td>{{ $student->name }}</td>
			                    <td>{{ $student->contracts->last()->enrollment_grade }}</td>
								<td>{{ $student->contracts->last()->enrollment_bachelor }}</td>
								<td>
									{{ $student->contracts->last()->fees()->where('order',1)->first()->order }}
								</td>
								<td>
									@php
										$services_cost = 0;
										$student_contract = $student->contracts->last();
										$services = $student_contract->contract_services;

										$enrollment = $student_contract->enrollment_cost - $student_contract->paid_out;
										foreach ($services as $service) {
											$services_cost += $service->cost - $service->paid_out;
										}

										$fee_1 = $student->contracts->last()->fees()->where('order',1)->first();

										$fees = ($fee_1->cost - $fee_1->paid_out);

										$total += $fees + $enrollment + $services_cost;

									@endphp
								
									{{ 
										number_format(
											$fees + $enrollment + $services_cost,
										2)
									}}
								</td>
							</tr>
						@endif
					@endforeach
					@if ($total > 0)
						<tr>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td>
								Total: {{ number_format($total,2) }}
							</td>
						</tr>
					@endif
				</tbody>
			</table>

			<br><br>
		@endif
		
		@if($total_2fee > 0)
			<table class="table table-striped" id="m_table_1">
				<thead>
					<tr><td colspan="5"></td></tr>
					<tr>
						<th colspan="5">2 Cuota o más <span class="pull-right">{{ $total_2fee }} Estudiante(s)</span></th>
					</tr>
					<tr>
	                    <th>Estudiante</th>
						<th>Grado</th>
						<th>Bachiller</th>
						<th>Cuotas</th>
						<th>Total</th>
					</tr>
				</thead>
				<tbody>
					@php
						$total = 0;
					@endphp
					@foreach($students as $student)
						@if($student->contracts->last()->fees()->where('status',0)->count() > 1)
							<tr>
								<td>{{ $student->name }}</td>
			                    <td>{{ $student->contracts->last()->enrollment_grade }}</td>
								<td>{{ $student->contracts->last()->enrollment_bachelor }}</td>
								<td>
									@foreach( $student->contracts->last()->fees()->where('status',0)->get() as $fee)
										{{ $fee->order }} <span> - </span>
									@endforeach
								</td>
								<td>
									@php

										$acum = 0;
										$services_cost = 0;
										$student_contract = $student->contracts->last();
										$services = $student_contract->contract_services;

										$enrollment = $student_contract->enrollment_cost - $student_contract->paid_out;
										foreach ($services as $service) {
											$services_cost += $service->cost - $service->paid_out;
										}

									@endphp
									@foreach($student->contracts->last()->fees()->where('status',0)->get() as $fee)
										@php
											$acum += ($fee->cost - $fee->paid_out) +
													($fee->r15 - $fee->r15_paid_out) +
													($fee->r1 - $fee->r1_paid_out);
										@endphp
									@endforeach
									@php
										$total += $acum + $enrollment + $services_cost;
									@endphp
									{{ number_format($acum + $enrollment + $services_cost,2) }}
								</td>
							</tr>
						@endif
					@endforeach
					@if ($total > 0)
						<tr>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td>
								Total: {{ number_format($total,2) }}
							</td>
						</tr>
					@endif
				</tbody>
			</table>
		@endif

		@if ($other > 0)
			
			<table class="table table-striped" id="m_table_1">
				<thead>
					<tr><td colspan="5"></td></tr>
					<tr>
						<th colspan="5">Otros <span class="pull-right">{{ $other }} Estudiante(s)</span></th>
					</tr>
					<tr>
	                    <th>Estudiante</th>
						<th>Grado</th>
						<th>Bachiller</th>
						<th>Cuotas</th>
						<th>Total</th>
					</tr>
				</thead>
				<tbody>
					@php
						$total = 0;
					@endphp
					@foreach($other_student as $student)

						@if ($student->contracts->last()->fees()->where('status',0)->count() == 0 && $student->contracts->last()->fees()->where('order',1)->whereColumn('cost','=','paid_out')->count() >= 1 && $student->contracts->last()->contract_services()->whereColumn('cost','>','paid_out')->count() >= 1)

							@if ($student->contracts->last()->whereColumn('enrollment_cost','>','paid_out')->count() > 0 || $student->contracts->last()->contract_services()->whereColumn('cost','>','paid_out')->count() >= 1)
								
								<tr>
								<td>{{ $student->name }}</td>
			                    <td>{{ $student->contracts->last()->enrollment_grade }}</td>
								<td>{{ $student->contracts->last()->enrollment_bachelor }}</td>
								<td>
									
								</td>
								<td>
									@php
										$services_cost = 0;
										$student_contract = $student->contracts->last();
										$services = $student_contract->contract_services;

										$enrollment = $student_contract->enrollment_cost - $student_contract->paid_out;
										foreach ($services as $service) {
											$services_cost += $service->cost - $service->paid_out;
										}

										$total += $enrollment + $services_cost;

									@endphp
								
									{{ 
										number_format(
											$enrollment + $services_cost,
										2)
									}}
								</td>
							</tr>

							@endif
						
						@endif
					@endforeach
					@if ($total > 0)
						<tr>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td>
								Total: {{ number_format($total,2) }}
							</td>
						</tr>
					@endif
				</tbody>
			</table>

		@endif

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
