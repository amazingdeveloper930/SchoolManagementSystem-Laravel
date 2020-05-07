<template>
	
	<div style="margin-bottom: 20px;">
		<div style="width: 50%; margin: 0 auto;">
			<img src="{{ asset('app/images-email/logo.jpg') }}" style="width: 100%" alt="ICA">
		</div>
	</div>

	<h4 style="text-align: center;">Recordatorio de pago</h4>
	
	<br>

	<h4>Estimado(a) {{ $student->name }}</h4>

	<br>
	
	@php
		$date = Carbon\Carbon::createFromFormat('Y-m-d', $fee->date);
		$month_aux= $date->format('M') == 1 ? 12 : $date->format(m) - 1;
		$month_ven = Carbon\Carbon::createFromFormat('m', $month_axu);
	@endphp

	<p>
		Le recordamos que su cuota de {{ $month_ven->format('MMMM') }} vence el {{ $date->format('d') }} de {{ $date->format('MMMMM') }}.	Por favor realice el pago a tiempo para evitar recargos por pago atrasado.	
	</p>


	<div>
		<p>Atentamente</p>
		
		<p>
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