@extends('layouts.app')

@section('title','Imprimir recibo')

@section('content')
	
	<!--MOSTRAR RECIBO DE CIERTO PAGO =================================================================================-->
	<!--===============================================================================================================-->
	<!--<div class="modal fade" id="m_modal_1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">-->
						

		<div v-if="activate" class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div 
					class="modal-header"
					:style="payment != null && !payment.status ? 'background: #A22; color: white' : ''">
						<h5 
							v-if="payment != null && payment.status"
							class="modal-title" 
							id="exampleModalLabel" 
							v-text="'Detalles del recibo'">
						</h5>
						<h5 
							v-else-if="payment != null && !payment.status"
							class="modal-title" 
							id="exampleModalLabel" 
							:style="!payment.status ? 'color: white' : ''"
							v-text="'Pago cancelado por ' + 
									JSON.parse(payment.cancel_info).user +
									' el ' +
									JSON.parse(payment.cancel_info).date +
									' - Comentario: ' +
									JSON.parse(payment.cancel_info).comment">
						</h5>
				</div>
				<div class="modal-body" v-if="payment != null">

					<div class="row justify-content-between">
						<div class="col-6">
							<strong>Recibo <span v-text="payment.receipt"></span></strong>
							<a v-if="payment!=null" :href="'/pagos/printReceipt/' + payment.id" target="_black"><i class="fa fa-print"></i></a>
						</div>
						<div class="col-6" style="text-justify: right">
							<strong>Total: <span v-text="payment.amount"></span></strong>
						</div>
					</div>
					<div class="row justify-content-between">
						<div class="col-6">
							Fecha de deposito: <span v-text="payment.deposit_at"></span> 
							<br>
							Fecha: <span v-text="dateFormat(payment.created_at)"></span>
							<br>
							Usuario: <span v-text="payment.user"></span>
						</div>
						<div class="col-6" style="text-justify: right">
							<template v-if="payment.refund != 0">
								Reembolso: <span v-text="payment.refund"></span>
								<br>
							</template>
							N° de Operacion <span v-text="payment.operation_number"></span>
							<br>
							Acudiente: <span v-text="payment.attendant"></span>
						</div>
					</div>

					<br>
					
					<div v-if="pays.length > 0" v-for="student in pays_sort">

						<h6 v-text="student.name"></h6>

						<table 
							class="col-12 table table-striped m-table" 
							style="text-justify: center; margin-bottom: 40px">
							
							<thead>
								<tr>
									<th>Descripción</th>
									<th>Total</th>
									<template 
										v-for="payment in student.payments"
										v-if="payment.type == 'fees'">
											<th>
												<span
													v-if="	payment.fees[1] != undefined &&
															payment.fees[0].total != payment.fees[1].total">
																@{{  payment.fees[0].description }}
												</span>
											</th>
											<th>
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
											<th>
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
								</tr>
							</thead>
							<tbody>
								<tr 
									v-for="payment in student.payments"
									v-if="payment.type != 'fees'">
										<td v-text="payment.description"></td>
										<td v-text="payment.total"></td>
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
								</tr>
								<tr 
									v-for="payment in student.payments"
									v-if="payment.type == 'fees'">
										<td v-text="payment.description"></td>
										<td v-text="payment.total"></td>
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
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" @click="closeModal" data-dismiss="modal">Cerrar</button>
				</div>
			</div>
		</div>
	<!--</div>-->
	<!--FIN MOSTRAR RECIBO ============================================================================================-->
	<!-- ==============================================================================================================-->

@endsection

@section('scripts')
	<script>
		
		const app = new Vue({

			el: '#app',

			data: {
				payment: @json($payment),
				pays_sort: [],
				pays: [],
				activate: false,
			},

			methods:{

				dateFormat(date){
					var d = new Date(date);
					return d.getDate() + '/' + d.getMonth() + 1 + '/' + d.getFullYear() + ' ' + this.addZero(d.getHours()) +':' + this.addZero(d.getMinutes());
				},

				addZero(i) {
				  if (i < 10) {
				    i = "0" + i;
				  }
				  return i;
				},

			},

			created(){
				let me = this;
				console.log(this.payment);
				console.log(this.payment.info_str)
				me.pays = JSON.parse(me.payment.info_str);

				var student_aux = new Object();

				for(i=0; i < me.pays.length; i++){

					var aux = 0;
					var aux2 = 0;
					var aux3 = 0;
					
					student_aux = {
						'name': '',
						'payments': [],
					}


					student_aux.name = me.pays[i].name;

					for(j=0; j < me.pays[i].payments.length; j++){

						if(me.pays[i].payments[j].type == 'r1' || me.pays[i].payments[j].type == 'r15'){
							continue;
						}
						if(me.pays[i].payments[j].type == 'fee' && aux == 0){
							
							student_aux.payments.push({
								'total': parseFloat(me.pays[i].payments[j].total).toFixed(2),
								'description': 'cuotas',
								'type': 'fees',
								'fees': [],
							});

							student_aux.payments[j].fees.push({
								'total': parseFloat(me.pays[i].payments[j].total).toFixed(2),
								'description': me.pays[i].payments[j].description,
							});

							aux++;
							aux2 = j;
							aux3++;
						}
						else if(me.pays[i].payments[j].type == 'fee'){

							student_aux.payments[aux2].total = (parseFloat(student_aux.payments[aux2].total) + parseFloat(me.pays[i].payments[j].total)).toFixed(2);

							student_aux.payments[aux2].fees.push({
								'total': parseFloat(me.pays[i].payments[j].total).toFixed(2),
								'description': me.pays[i].payments[j].description,
							});
						}
						else{

							student_aux.payments.push({
								'total': parseFloat(me.pays[i].payments[j].total).toFixed(2),
								'description': me.pays[i].payments[j].description,
								'type': me.pays[i].payments[j].type,
							});

							aux3++;
						}
					}

					me.pays_sort.push(student_aux);
				}
				me.activate = true;
				console.log(me.pays_sort);
				window.print();
			},

			
		});

	</script>
@endsection