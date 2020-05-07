<template>

	<div style="margin-bottom: 20px;">
		<div style="width: 50%; margin: o auto;">
			<img src="{{ asset('app/images-email/logo.jpg') }}" style="width: 100%" alt="ICA">
		</div>
	</div>
		
	<h3 style="text-align: center;">AVISO DE SUSPENCIÓN</h3>

	<br>

	<h4>Estimado(a) Jane Doe</h4>

	<br>

	<p>
		Por medio de la presente se le comunica que se refleja morosidad en el estado de cuenta de su acudida {{ $student->student }}, detallado de la siguiente manera:
	</p>

	<div style="text-align: center;">
		<table 
			border="1" 
			style="border-collapse: collapse; 
			margin: 0 auto;">
			
				<thead>
					<tr>
						<th style="padding: 2px 70px">
							Descripcion
						</th>
						<th style="padding: 2px 70px">
							Total
						</th>
					</tr>
				</thead>
				<tbody>
					@php $total = 0 @endphp
					
					@foreach($students->contracts->last()->fees as $fee)
						
						@if($fee->status == App\Fee::INACTIVE)
			
							@php $total += $fee->cost - $fee->paid_out @endphp
			
							<tr>
								<td style="padding: 2px 70px">
									Cuota No. {{ $fee->order }}
								</td>
								<td style="padding: 2px 70px">
									N/. {{ $fee->cost - $fee->paid_out }} 
								</td>
							</tr>
						@endif

						@if($fee->r15_status == App\Fee::RECHARGE_ACTIVE)
			
							@php $total += $fee->r15 - $fee->r15_paid_out @endphp
			
							<tr>
								<td style="padding: 2px 70px">
									Recargo 15% Cuota No {{ $fee->order }}
								</td>
								<td style="padding: 2px 70px">
									N/. {{ $fee->r15 - $fee->r15_paid_out }} 
								</td>
							</tr>
						@endif

						@if($fee->r1_status == App\Fee::RECHARGE_ACTIVE)
			
							@php $total += $fee->r1 - $fee->r1_paid_out @endphp
			
							<tr>
								<td style="padding: 2px 70px">
									Recargo 1% Cuota No {{ $fee->order }}
								</td>
								<td style="padding: 2px 70px">
									N/. {{ $fee->r1 - $fee->r1_paid_out }} 
								</td>
							</tr>
						@endif
					@endforeach
					
					<tr>
						<th style="padding: 2px 70px">
							Total
						</th>
						<th style="padding: 2px 70px">
							{{ $total }}
						</th>
					</tr>
				</tbody>
		</table>
	</div>

	<p>
		E haber cancelado esta deuda, se le agradece presentar el comprobante en el departamento de contabilidad. De lo contrario su acudido no podra entrar al colegio a partir del dia __.
		
		<br>
		
		Suspensión estipulada en el contraro de servicio de enseñanza

		<br>

		Clausula Sexta:  “La prestación del servicio de enseñanza se suspenderá en los siguientes casos, sin que dicha suspensión genere o acarre responsabilidad alguna a EL INSTITUTO:

		<br>

		<i>
			De acuerdo a lo establecido en el reglamento interno del Colegio, se suspenderá el servicio luego que se tenga do (2) cuotas mensuales, previa notificación a El/LA Acudiente hasta que éste realice un arreglo de pago, el cual no deberá exceder el año escolar lectivo”.
		</i> 
	</p>


	<div>
		
		<p>Atentamente 
			<img src="{{ asset('app/images-email/firma.png') }}" style="width: 70px" alt="Firma">
			<br> ____________________ 
			<br> Juan Perez
			<br>Departamento de Contabilidad
		</p>
		
		<br>
		
		<p style="text-align: center">
			Si tiene preguntas sobre este aviso nos puede contactar respondiendo este correo o llamandonos al 264-9346
		</p>
	</div>


</template>