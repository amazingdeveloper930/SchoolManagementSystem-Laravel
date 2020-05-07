<template>

	<div style="margin-bottom: 20px;">
		<div style="width: 50%; margin: 0 auto;">
			<img src="{{ asset('app/images-email/logo.jpg') }}" style="width: 100%" alt="ICA">
		</div>
	</div>
	
	<h4 style="text-align: center;">PAZ Y SALVO <br> {{ $date->format('MMMM Do YYYY') }}</h4>
	
	<br>
	
	<h4>Estimado(a) {{ $student->attendant }}</h4>
	
	<br>
	
	<p>
		Por este medio certifico que el/la estudiante <strong>{{ $student->name }}</strong> de {{ $student->contracts->last()->enrollment_grade}} grado, se encuentra  PAZ Y SALVO en el año escolar {{ $student->contracts->last()->year}}. 
	</p>
	
	<br>

	<div>
		<img src="{{ asset('app/images-email/firma.png') }}" style="width: 70px" alt="Firma">

		<p>Atentamente 
			<br> ____________________ 
			<br> Mgstr. Gabriela Garcia Carranza
			<br> Instituto de Ciencias Aplicadas
		</p>
		
		<br>
		
		<p style="text-align: center">
			Si tiene preguntas sobre este aviso nos puede contactar respondiendo este correo o llamandonos al 264-9346
		</p>
	</div>

</template>
