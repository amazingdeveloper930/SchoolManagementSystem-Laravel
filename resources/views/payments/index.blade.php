@extends('layouts.app')

@section('title','Pagos')

@push('styles')

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" media="print" crossorigin="anonymous">
<style>
    a {
        color: rgb(25, 59, 100) !important;
    }
</style>
@endpush

@section('content')
	<div class="m-portlet m-portlet--mobile">

		<!--ENCABEZADO ====================================================================================================-->
		<!--===============================================================================================================-->
		<div class="m-portlet__head">
			<div class="m-portlet__head-caption">
				<div class="m-portlet__head-title">
					<h3 class="m-portlet__head-text">
						Pagos
					</h3>
				</div>
			</div>
			<div class="m-portlet__head-tools">
				<ul class="m-portlet__nav">
					<li class="m-portlet__nav-item">
						@can('payments.store_edit')
						    <a 	href="{{ route('payment.create') }}">
		                    <button
		                    	type="button"
								class="btn btn-accent m-btn m-btn--pill m-btn--custom m-btn--icon m-btn--air">
			                    	<span>
										<i class="la la-plus"></i>
										<span>Agregar Pago</span>
									</span>
							</button>
							</a>
						@endcan
					</li>
				</ul>
			</div>
		</div>
		<!--FIN DEL ENCABEZADO ============================================================================================-->
		<!--===============================================================================================================-->


		<div class="m-portlet__body">


			<!--AQUI SE POSICIONAN LOS PAGOS REALIZADOS ====================================================================-->
			<!--============================================================================================================-->
			<table class="table table-striped- table-bordered table-hover table-checkable" id="m_table_1">
				<div class="form-group m-form__group row justify-content-between">
					<div>
						<span>Mostrar</span>
						<select autocomplete="off" v-model="mostrar" class="m-input m-input--air m-input--pill" id="exampleSelect1">
							<option v-for="show in shows" v-bind:value="show" v-text="show">
						                        </option>
						</select>
						<span>Registros</span>
					</div>

					<div class="row justify-content-around" v-if="active_datapickers">
						<my-date-picker
							class="col-4"
							id="datapicker"
							v-model="since_date"
							:placeholder="'Desde'">
						</my-date-picker>
						<my-date-picker
							class="col-4"
							id="datapicker"
							v-model="until_date"
							:placeholder="'Hasta'">
						</my-date-picker>
					</div>

					<div class="">
						<span>Filtrar por:</span>
						<select autocomplete="off" v-model="pay_state" class="m-input m-input--air m-input--pill" id="exampleSelect1">
							<option v-for="pay in pay_states" v-bind:value="pay.value" v-text="pay.text">
						    </option>
						</select>
					</div>

					<div class="pull-right">

						<input v-model="search" type="text" class="m-input m-input--air m-input--pill" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Buscar">
					</div>
				</div>
				<thead>
					<tr>
                        <th>Fecha
							<a href="#" @click.prevent="order('created_at')">
                                <span>
                                    <i class="fa fa-long-arrow-alt-down"></i>
                                    <i class="fa fa-long-arrow-alt-up"></i>
                                </span>
                            </a>
                        </th>
						<th>Fecha deposito
							<a href="#" @click.prevent="order('deposit_at')">
                                <span>
                                    <i class="fa fa-long-arrow-alt-down"></i>
                                    <i class="fa fa-long-arrow-alt-up"></i>
                                </span>
                            </a>
						</th>
						<th>Recibo
							<a href="#" @click.prevent="order('receipt')">
                                <span>
                                    <i class="fa fa-long-arrow-alt-down"></i>
                                    <i class="fa fa-long-arrow-alt-up"></i>
                                </span>
                            </a>
						</th>
						<th>Estudiante</th>
						<th>Acudiente
							<a href="#" @click.prevent="order('attendant')">
                                <span>
                                    <i class="fa fa-long-arrow-alt-down"></i>
                                    <i class="fa fa-long-arrow-alt-up"></i>
                                </span>
                            </a>
						</th>
						{{-- <th>No. Operación</th> --}}
						<th>Monto
							<a href="#" @click.prevent="order('amount')">
                                <span>
                                    <i class="fa fa-long-arrow-alt-down"></i>
                                    <i class="fa fa-long-arrow-alt-up"></i>
                                </span>
                            </a>
						</th>
						<th>Acciones</th>
					</tr>
				</thead>
				<tbody>
					<tr v-for="(dato, index) in datos" :key="dato.id">
						<td v-text="dateFormat(dato.created_at)"></td>
                        <td v-text="dato.deposit_at"></td>
						<td v-text="dato.receipt"></td>

						<td>
							<a
								v-for="student in JSON.parse(dato.info_str)"
								:href="'/students/' + student.id">
									@{{ student.name }}<br>
							</a>
						</td>

						<td v-text="dato.attendant"></td>
						{{-- <td v-text="dato.operation_number"></td> --}}
						<td v-text="dato.amount"></td>
						<td>

							<button
								title="Ver"
								class="btn btn-success btn-sm"
								@click="showReceipt(index)">
									<i class="fa fa-eye"></i>
							</button>

							@can('payments.cancel')
							    <button
								v-if="dato.status == 1"
								title="Cancelar"
								class="btn btn-danger btn-sm"
								@click="openModal(2, index)">
									<i class="fa fa-times"></i>
								</button>

								<span
									v-if="dato.status == 0"
									class="btn-danger"
									style="border-radius: 25%;
											padding: 5px;
											color: #fff;">
										Cancelado
								</span>
							@endcan
						</td>
					</tr>
				</tbody>
			</table>
			<!--FIN POSICION DE LOS PAGOS REALIZADOS =======================================================================-->
			<!--============================================================================================================-->



			<!--AQUI SE POSICIONAN LOS BOTONES DE CONTROL DE LA TABLA ======================================================-->
			<!--============================================================================================================-->
			<div style="margin-right: 0px; margin-left: 0px; padding-right: 0px; padding-left: 0px;"
				class="m-portlet__head-tools row justify-content-between">

                <p v-text="'Mostrando registros del ' + pagination.from + ' al ' + pagination.to + ' de un total de ' + pagination.total">
                </p>


                <!--<ul
                	class="nav nav-pills nav-pills--brand m-nav-pills--align-right m-nav-pills--btn-pill m-nav-pills--btn-sm"
                	role="tablist">

						<li class="nav-item m-tabs__item" v-if="pagination.current_page > 1">
							<a
								class="nav-link m-tabs__link"
								href="#"
								@click.prevent="cambiarPagina(pagination.current_page - 1)">
									<i class="la la-angle-left"></i>
							</a>
						</li>
						<li class="nav-item m-tabs__item" v-for="page in pagesNumber" :key="page">
							<a
								class="btn m-btn--square  btn-primary btn-sm"
								href="#"
								:class="[page == isActived ? 'active' : '']"
								@click.prevent="cambiarPagina(page)" v-text="page"
								style="background-color: #193B64">
							</a>
						</li>
						<li class="nav-item m-tabs__item" v-if="pagination.current_page < pagination.last_page">
							<a
								class="nav-link m-tabs__link"
								href="#"
								@click.prevent="cambiarPagina(pagination.current_page + 1)"
								style="background-color: #193B64">
									<i class="la la-angle-right"></i>
							</a>
						</li>
				</ul>-->



				<nav aria-label="...">
					<ul class="pagination">
						<li class="page-item" 
							v-if="pagination.current_page > 1">
								<a 	class="page-link" 
									href="#" 
									tabindex="-1" 
									aria-disabled="true"
									@click.prevent="cambiarPagina(pagination.current_page - 1)">
										<i class="la la-angle-left" style="font-size:12px"></i>
								</a>
						</li>

						<li v-for="page in pagesNumber" 
							:key="page"
							:class="'page-item ' + [page == isActived ? 'active' : '']">
								<a 	href="#"
									class="page-link "
									@click.prevent="cambiarPagina(page)">
										<span 
											:style="page == isActived ? 'color:white' : ''"
											v-text="page"></span>
								</a>
						</li>

						<li class="page-item" 
							v-if="pagination.current_page < pagination.last_page">
								<a 	class="page-link" 
									href="#"
									@click.prevent="cambiarPagina(pagination.current_page + 1)">
										<i class="la la-angle-right" style="font-size:12px"></i>
								</a>
						</li>
					</ul>
				</nav>
            </div>
			<!--FIN POSICION DE LOS BOTONES DE CONTROL DE LA TABLA =========================================================-->
			<!--============================================================================================================-->



			<!--MOSTRAR RECIBO DE CIERTO PAGO =================================================================================-->
			<!--===============================================================================================================-->
			<div class="modal fade" id="m_modal_1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">


				<div class="modal-dialog modal-lg" role="document">
					<div class="modal-content">

						<div
							class="modal-header"
							:style="payment != null && payment.status == 0 ? 'background: #A22; color: white' : ''">
								<h5
									v-if="payment != null && payment.status == 1"
									class="modal-title"
									id="exampleModalLabel"
									v-text="'Detalles del recibo'">
								</h5>
								<h5
									v-else-if="payment != null && payment.status == 0"
									class="modal-title"
									id="exampleModalLabel"
									:style="payment.status == 0 ? 'color: white' : ''"
									v-text="'Pago cancelado por ' +
											JSON.parse(payment.cancel_info).user +
											' el ' +
											JSON.parse(payment.cancel_info).date +
											' - Comentario: ' +
											JSON.parse(payment.cancel_info).comment">
								</h5>

								<button type="button" @click="closeModal()" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
						</div>
						<div class="modal-body" v-if="payment != null">

							<div class="row justify-content-between">
								<div class="col-6">
									<span style="font-size:16px"><strong>Recibo <span v-text="payment.receipt"></span></strong></span>
									<a v-if="payment!=null && payment.status != 0" @click.prevent="printReceipt()" href="#"><i class="fa fa-print"></i></a>
								</div>
								<div class="col-6" style="text-justify: right">
									<span style="font-size:16px"><strong>Total: <span v-text="payment.amount"></span></strong></span>
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
									N° de Operacion <span v-text="payment.operation_number"></span>
									<br>
									Método de pago: <span v-text="payment.pay_method == 1 ? 'Depósito o AHC' : 'Slip Bancario'"></span>
									<br>
									Acudiente: <span v-text="payment.attendant"></span>
								</div>
							</div>

							<br>

							<div v-if="pays.length > 0" v-for="(student,ind) in pays_sort">

								<p><strong style="font-size: 16px" v-text="student.name"></strong></p>

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
										<tr v-if="recharge != 0">
											<td>Recargos</td>
											<td v-text="recharge"></td>
										</tr>
										<tr v-if="payment.refund > 0 && ind == 0">
											<td>Reembolso</td>
											<td v-text="payment.refund"></td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" @click="closeModal()">Cerrar</button>
						</div>
					</div>
				</div>
			</div>
			<!--FIN MOSTRAR RECIBO ============================================================================================-->
			<!-- ==============================================================================================================-->



			<!--MODAL CANCELAR PAGO====================================================================================-->
			<!--=======================================================================================================-->
			<div class="modal fade" id="m_modal_2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel" v-text="'Cancelar pago'"></h5>
							<button type="button" @click="closeModal" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<label class="label-control" for="comentario">Comentario de cancelacion</label>
							<div class="row">
								<textarea v-model="comment_cancel" class="form-control" id="comentario" style="width: 100%"></textarea>
							</div>
						</div>

						<div class="modal-footer">
							<button type="button" class="btn btn-primary" :disabled="!active_cancel_payment" @click="cancelPayment()">
								<span v-text="active_cancel_payment ? 'Aceptar' : 'Cargando...'"></span>
							</button>
							<button type="button" class="btn btn-secondary" :disabled="!active_cancel_payment" data-dismiss="modal" @click="closeModal()">Cancelar</button>
						</div>
					</div>
				</div>
			</div>
			<!--=======================================================================================================-->
			<!--CERRAR MODAL CANCELAR PAGO ============================================================================-->



			@include('payments.print_receipt')
			
		</div>
	</div>


@endsection



@section('scripts')

	<script src="{{asset('metronic/assets3/vendors/base/vendors.bundle.js')}}" type="text/javascript"></script>
    <script src="{{asset('metronic/assets3/demo/default/base/scripts.bundle.js')}}" type="text/javascript"></script>
    <script src="{{asset('metronic/assets3/demo/default/custom/crud/forms/widgets/bootstrap-datepicker.js')}}" type="text/javascript"></script>

	<script>

		const app = new Vue({

			el: '#app',

			data:{

				datos: [],
				payment: {!! session('payment') ? session('payment') : 'null' !!},
				recharge: 0,

				error : 0,
				errors : [],

				search: '',
				mostrar: 10,
				pays: [],
				pays_sort: [],
				since_date: '',
				until_date: '',

				active_datapickers: 0,

				payment_select: null,
				comment_cancel: '',

				shows: [
				    10,
				    20,
				    50
				],
				pay_state: 2,
				pay_states : [
				    { text: 'Activos:' , value: 1},
				    { text: 'Cancelados:' , value: 0},
				    { text: 'Todos:' , value: 2},
				],

				active_cancel_payment: true,

				criterio: '',
				options: [
				    { text: 'Filtrar Por:' , value: ''},
				    /*{ text: 'Fecha', value: 'created_at'},
				    { text: 'Fecha de deposito', value: 'deposit_at' },*/
				    { text: 'Recibo', value: 'receipt'},
				    { text: 'Acudiente', value: 'attendant'},
				    { text: 'N° Operacion', value: 'operation_number'},
				    { text: 'Monto', value:'amount'}
				],

				pagination: {
				        'total': 0,
				        'current_page': 0,
				        'per_page': 0,
				        'last_page': 0,
				        'from': 0,
				        'to': 0,
				},
				offset : 3,

				action: 0,
				titleModal: '',
			},


			computed: {

				isActived: function(){

				    return this.pagination.current_page;
				},

				//Calcular los elementos de la paginación
				pagesNumber: function(){
				    if (!this.pagination.to) {
				        return [];
				    }

				    var from = this.pagination.current_page - this.offset;
				    if (from < 1) {
				        from = 1;
				    }

				    var to = from + (this.offset * 2)
				    if (to >= this.pagination.last_page) {
				        to = this.pagination.last_page;
				    }

				    var pagesArray = [];
				    while(from <= to){
				        pagesArray.push(from);
				        from++;
				    }
				    return pagesArray;
				},
			},


			methods: {

				cambiarPagina(page){
					let me = this;
					//Actualizar pagina actual
					me.pagination.current_page = page;
					me.getData(page);
				},


				getData(page){

					let me = this
					var url = '/pagos/getData?page=' + page +
								'&criterio=' + this.criterio +
								'&mostrar=' + this.mostrar +
								'&buscar=' + this.search +
								'&desde=' + this.since_date +
								'&hasta=' + this.until_date +
								'&pay_state=' + this.pay_state;

					axios.get(url)
						.then(function (response) {

							var respuesta = response.data;
							me.datos = respuesta.payments.data;
							me.pagination = respuesta.pagination;
						})
						.catch(function (error){
							console.log(error);
						});
				},


				showReceipt(indice){

					let me = this;
					var url = '/pagos/show/' + me.datos[indice].id;

					axios
						.get(url)
						.then(function(response){
							me.payment = response.data.payment;
							me.pays = JSON.parse(response.data.payment.info_str);

							/**
							 * Reembolso
							 */
							var remb = JSON.parse(response.data.payment.info_str)[0].payments;
							var remb_t = 0;
							for(rem of remb) {
								if (rem.type == 'r1' || rem.type == 'r15') {
									remb_t += rem.total;
								}
							}
							me.recharge = parseFloat(remb_t).toFixed(2);

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

									if(me.pays[i].payments[j].type == 'fee' && aux == 0){

										student_aux.payments.push({
											'total': parseFloat(me.pays[i].payments[j].total).toFixed(2),
											'description': 'cuotas',
											'type': 'fees',
											'fees': [],
										});

										student_aux.payments[aux3].fees.push({
											'total': parseFloat(me.pays[i].payments[j].total).toFixed(2),
											'description': me.pays[i].payments[j].description,
										});

										aux++;
										aux2 = aux3;
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

										if(me.pays[i].payments[j].type == 'r1' || me.pays[i].payments[j].type == 'r15'){
											continue;
										}

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

							console.log(me.pays_sort);

							//me.pays = JSON.parce(JSON.stringify(me.pays_sort));

							me.openModal(1);
						}).catch(function(error){
							console.log(error);
						});
				},


				cancelPayment(){

					let me = this;

					var url = '/pagos/cancelPayment';
					me.active_cancel_payment = false;

					axios
						.post(url,{
							'payment_id': me.datos[me.payment_select].id,
							'cancel_comment': me.comment_cancel,
						})
						.then(function(response){

							console.log(response.data.result);
							me.getData(me.pagination.current_page);
							me.closeModal();
							me.active_cancel_payment = true;
						}).catch(function(error){

							console.log('Error');
							me.active_cancel_payment = true;
						});

					return;
				},

				printReceipt(){

					window.scrollBy(0, 0);

					$('#print_receipt').show(100,function(){
						setTimeout(function() {
							w = window.open();
							w.document.write($('#print_receipt').html());
							w.print();
							w.close();
							// window.print();
						}, 100);
						setTimeout(function(){
							$('#print_receipt').hide();
						}, 200);
					});

					//this.closeModal();
				},

				order(criterio_){
	                let me = this;

	                me.criterio = criterio_;
	                if(me.search == 'desc'){
	                    me.search = 'asc';
	                }
	                else{
	                    me.search = 'desc';
	                }
	            },


				openModal(num_modal, pay = null){
					if(num_modal == 1){
						$('#m_modal_1').modal('show');
					}
					else if(num_modal == 2){
						$('#m_modal_2').modal('show');
						this.payment_select = pay;
					}
				},


				closeModal(){
					$('.close').click();

					this.pays_sort = [];
					this.pays = [];
					this.payment = null;
					this.comment_cancel = '';
				},

				dateFormat(date){
					var d = new Date(date);
					//console.log(d);
					return d.getDate() + '/' + (d.getMonth() + 1) + '/' + d.getFullYear() + ' ' + this.addZero(d.getHours()) + ':' + this.addZero(d.getMinutes());
				},

				addZero(i) {
				  if (i < 10) {
				    i = "0" + i;
				  }
				  return i;
				},

			},

			mounted(){
                let me = this;
				this.getData(1);
                this.active_datapickers = 1;

                $('#m_modal_1').on('hide.bs.modal', function (e) {
                    me.pays_sort = [];
					me.pays = [];
					me.payment = null;
					me.comment_cancel = '';
                });

				if(this.payment != null){

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

							if(me.pays[i].payments[j].type == 'fee' && aux == 0){
								
								student_aux.payments.push({
									'total': parseFloat(me.pays[i].payments[j].total).toFixed(2),
									'description': 'cuotas',
									'type': 'fees',
									'fees': [],
								});

								student_aux.payments[aux3].fees.push({
									'total': parseFloat(me.pays[i].payments[j].total).toFixed(2),
									'description': me.pays[i].payments[j].description,
								});

								aux++;
								aux2 = aux3;
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

								if(me.pays[i].payments[j].type == 'r1' || me.pays[i].payments[j].type == 'r15'){
									continue;
								}

								student_aux.payments.push({
									'total': parseFloat(me.pays[i].payments[j].total).toFixed(2),
									'description': me.pays[i].payments[j].description,
									'type': me.pays[i].payments[j].type,
								});

								aux3++;
							}
						}

						//me.pays_sort.push(student_aux);
					}


					//this.openModal(1);
					setTimeout(function(){
						me.printReceipt();	
					}, 500);
					
				}
			},

			watch: {

				mostrar: function(){
					this.getData(1)
				},

				search: function(){
					this.getData(1)
				},

				until_date: function(){
					this.getData(1)
				},

				since_date: function(){
					this.getData(1)
				},

				pay_state: function(){
					this.getData(1)
				},
			}
		});

		//DATA PICKER (COMPONENTE)
		Vue.component('my-date-picker',{
		    template: '<input type="text" autocomplete="off" v-datepicker class="m-input m-input--air m-input--pill" :placeholder="placeholder" :value="value" @input="update($event.target.value)">',
		    directives: {
		        datepicker: {
		            inserted (el, binding, vNode) {
		                $(el).datepicker({
		                    autoclose: true,
		                    format: 'dd/mm/yyyy'
		                }).on('changeDate',function(e){
		                    vNode.context.$emit('input', e.format(0))
		                })
		            }
		        }
		    },
		    props: ['value', 'placeholder'],
		    methods: {
		        update (v){
		            this.$emit('input', v)
		        }
		    }
		});

	</script>

@endsection
