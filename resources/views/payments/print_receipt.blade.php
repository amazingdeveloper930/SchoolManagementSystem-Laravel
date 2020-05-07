<!--ESTILOS IMPRIMIR ====================================================================================-->
<style>
	
</style>
<!--=====================================================================================================-->

<!--IMPRIMIR EL RECIBO ====================================================================================-->
<!--=======================================================================================================-->
<div v-if="payment != null" id="print_receipt" style="z-index:1200; position: absolute; width: 100%; top: 0; left: 0; height: 100vh; background-color: white; display:none; font-family: Consolas !important">
	<div style="margin: 0 auto; width: 8.5in; height: 5in;" >
		<div style="height: 1in;"></div>
		
		<p style="font-family: Consolas !important; overflow: hidden; width: 7.5in; margin: 20px 0 0 50px;">
			<span style="padding-left: 20px; font-size: 20px" v-text="dateFormat(payment.created_at) + ' | Fecha de depósito: ' + payment.deposit_at"></span>
			<span style="float: right; font-size: 20px" v-text="payment.receipt"></span>
		</p>

		<div style="font-family: Consolas !important; height: 3.2in; width: 7.5in; margin: 0; margin-bottom: -30px;">
			<p style="padding: 10px 5px 0 20px; height: .5in ; font-size: 20px; overflow:hidden; margin-left: 100px">
				<!--_________________  
				<span style="float: right">GRUPO: __________________</span>-->
				<span style="padding-left: 1.2in; font-size: 20px" v-text="payment.attendant"></span>
				<!--<span style="float:right"></span>-->
				{{-- <template style="float:right" v-if="pays.length > 0">
                	<span style="float:right" v-for="(student,ind) in pays" v-text="student.description + ', '"></span>
                </template> --}}
			</p>
			{{-- <p style="padding: 15px 5px; height: .4in ; font-size: 10px; overflow:hidden;">
				<!--DESCRIPCION: __________________-->
			</p>
			<p style="padding: 15px 5px; height: .4in ; font-size: 10px; overflow:hidden;">
				<!--DETALLE: ___________________
				<span style="float: right">_________</span>-->
			</p> --}}
			<div style="height: 1.5in; padding: 0px 0px;">
				<div style="margin-bottom: 20px" v-if="pays.length > 0" v-for="(student,ind) in pays_sort">

					<p style="font-family: Consolas !important; font-size: 20px; margin-left: 100px; margin-top: -10px;" v-text="'Estudiante: ' + student.name + ' | ' + pays[ind].description"></p>

					<table style="font-family: Consolas !important; margin-left: -5px; margin-top: -10px;">
						
						<thead>
							<tr>
								<th style="padding: 0 0x">Descripción</th>
								{{-- <th style="padding: 0 15px;">Total</th> --}}
								<template 
									v-for="payment in student.payments"
									v-if="payment.type == 'fees'">
										<th style="padding: 0 30px">
											<span
												v-if="	payment.fees[1] != undefined &&
														payment.fees[0].total != payment.fees[1].total">
															@{{  payment.fees[0].description }}
											</span>
										</th>
										<th style="padding: 0 30px">
											<template 
												v-for="(fee, index) in payment.fees">
													<template 
														v-if="	index == 0 &&
																payment.fees[1] != undefined &&
																fee.total != payment.fees[1].total">
													</template>
													<span 
														v-else-if="	index == 0">
															@{{ fee.description }}
													</span>
													<span 
														v-else-if="	index == 1 &&
																	payment.fees[1] != undefined &&
																	payment.fees[0].total != payment.fees[1].total">
																		@{{ fee.description }}
													</span>
													<span
														v-else-if="index != 0 && 
																	index != payment.fees.length - 1 &&
																	payment.fees[index + 1].total != fee.total">
																		@{{ ' - ' + fee.description }}
													</span>
													<span
														v-else-if="index == payment.fees.length - 1 &&
																	payment.fees[index - 1].total == fee.total">
																		@{{ ' - ' + fee.description }}
													</span>
											</template>
										</th>
										<th style="padding: 0 30px">
											<span
												v-if="	
													payment.fees.length > 2 &&
													payment.fees[payment.fees.length - 2] != undefined &&
													payment.fees[payment.fees.length - 1].total != 
													payment.fees[payment.fees.length - 2].total">
														@{{ payment.fees[payment.fees.length - 1].description }}
											</span>	
										</th>
								</template>
								<th style="padding: 0 30px">Total</th>
							</tr>
						</thead>
						<tbody style="text-align: center;">
							<tr 
								v-for="payment in student.payments"
								v-if="payment.type != 'fees'">
									<td v-text="payment.description"></td>
									{{-- <td v-text="payment.total"></td> --}}
									<template 
										v-for="payment in student.payments"
										v-if="payment.type == 'fees'">
											<td>
												<span
													v-if="	payment.fees[1] != undefined &&
															payment.fees[0].total != payment.fees[1].total">
												</span>
											</td>
											<td>
												<template 
													v-for="(fee, index) in payment.fees">
														<template 
															v-if="	index == 0 &&
																	payment.fees[1] != undefined &&
																	fee.total != payment.fees[1].total">
														</template>
														<span 
															v-else-if="	index==0">
														</span>
														<span 
															v-else-if="	index == 1 &&
																		payment.fees[1] != undefined &&
																		payment.fees[0].total != payment.fees[1].total">
														</span>
														<span
															v-else-if="index != 0 && 
																		index != payment.fees.length - 1 &&
																		payment.fees[index + 1].total != fee.total">
														</span>
														<span
															v-else-if="index == payment.fees.length - 1 &&
																		payment.fees[index - 1].total == fee.total">
														</span>
												</template>
											</td>
											<td>
												<span
													v-if="	
														payment.fees.length > 2 &&
														payment.fees[payment.fees.length - 2] != undefined &&
														payment.fees[payment.fees.length - 1].total != 
														payment.fees[payment.fees.length - 2].total">
												</span>	
											</td>
									</template>
									<td v-text="payment.total"></td>
							</tr>
							<tr 
								v-for="payment in student.payments"
								v-if="payment.type == 'fees'">
									<td v-text="payment.description"></td>
									{{-- <td v-text="payment.total"></td> --}}
									<template 
										v-for="payment in student.payments"
										v-if="payment.type == 'fees'">
											<td>
												<span
													v-if="	payment.fees[1] != undefined &&
															payment.fees[0].total != payment.fees[1].total">
																@{{  payment.fees[0].total }}
												</span>
											</td>
											<td>
												<template 
													v-for="(fee, index) in payment.fees">
														<template 
															v-if="	index == 0 &&
																	payment.fees[1] != undefined &&
																	fee.total != payment.fees[1].total">
														</template>
														<span 
															v-else-if="	index==0">
																@{{ fee.total }}
														</span>
														<span 
															v-else-if="	index == 1 &&
																		payment.fees[1] != undefined &&
																		payment.fees[0].total != payment.fees[1].total">
																			@{{ fee.total }}
														</span>
														<span
															v-else-if="index != 0 && 
																		index != payment.fees.length - 1 &&
																		payment.fees[index + 1].total != fee.total">
														</span>
														<span
															v-else-if="index == payment.fees.length - 1 &&
																		payment.fees[index - 1].total == fee.total">
														</span>
												</template>
											</td>
											<td>
												<span
													v-if="	
														payment.fees.length > 2 &&
														payment.fees[payment.fees.length - 2] != undefined &&
														payment.fees[payment.fees.length - 1].total != 
														payment.fees[payment.fees.length - 2].total">
																@{{ payment.fees[payment.fees.length - 1].total }}
												</span>	
											</td>
									</template>
									<td v-text="payment.total"></td>
							</tr>
							<tr v-if="payment.refund != 0 && ind == 0">
								<td>Reembolso</td>
								<td v-text="payment.refund"></td>
							</tr>
							<tr v-if="recharge > 0">
								<td>Recargo</td>
								<td v-text="recharge"></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		
		<p style="font-family: Consolas !important; background-color: white; padding-left: 35px; padding-right: 20px; margin-top: -100px; width: 7.5in; height: .2in; margin: 0 auto; overflow: hidden">
		    <strong>
				<span style="float: left; font-size: 18px;" v-text="payment.user">
				</span>
			</strong>
			<strong>
				<span style="float: right; font-size: 18px;" v-text="payment.amount">
				</span>
			</strong>
		</p>
	</div>
</div>
<!--=======================================================================================================-->
<!--CERRAR IMPRIMIR RECIBO ================================================================================-->