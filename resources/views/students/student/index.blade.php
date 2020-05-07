@extends('layouts.app')

@section('title','Estudiante | ' . $student->name)

@push('styles')
	<style>
		@media print{
			.print-ps{
				z-index: 1200;
				position: absolute;
				width:100%; top: 0;
				height: 100vh
			}
		}

		.m-header--fixed .m-body {
			padding-top: 40px !important;
		}

		.btn-client {
			background-color: rgb(25, 59, 100) !important;
		}
	</style>
@endpush

@section('content')
		
		<!--ENCABEZADO ===============================================================================-->
		<!--==========================================================================================-->
	    <div class="row mt-5">

	        <div class="col-2 col-sm-3 col-md-1">
	            	
	            <a href="#" @click.prevent="openModal(3)">
	            	<img 
	            		:src="student.image.length ? '/app/images-students/' + student.image : '/app/images-default/student.jpg'" 
	            		class="img img-responsive" 
	            		style="width: 100%">
	            </a>

	        </div>
	        <div class="row col-8 col-sm-8 col-md-11">
	        	<div class="col-sm-12 col-md-4">
	        	    <p><strong>{{ __("Estudiante: ") }}</strong> {{ $student->name }}</p>
	        	    <p><strong>{{ __("ID personal: ") }}</strong> {{ $student->personal_id }}</p>
	        	    <p><strong>{{ __("Acudiente: ") }}</strong> {{ $student->attendant }}</p>
	        	</div>
	        	<div class="col-sm-12 col-md-4">
	        	    <p><strong>{{ __("Teléfono: ") }}</strong> {{ $student->phone }}</p>
	        	    <p><strong>{{ __("Email: ") }}</strong> {{ $student->email }}</p>
	        	</div>
	        	<div class="col-md-4" style="overflow: hidden">
	        		<div class="pull-right">
	        			<h3 style="text-align: right;">Paz y salvo 
	        				<span>
		        				<a v-if="student.peace_save" href="#" id="click-1" @click.prevent="printPeaceSAve">
		        					<i  class="fa fa-check"
		        						style="color:#00AA00;">
		        					</i>
		        				</a>
	        					<i v-if="!student.peace_save"
	        						class="fa fa-times"
	        						style="color:#AA0000">
	        					</i>
		        			</span>
		        		</h3>

							<div v-if="Object.keys(contract).length">

								<button
									v-if="student.status == 2"
									class="mb-2 btn mb-2 btn-client btn-sm ml-2 text-white" 			
									style="overflow:hidden"
									@click="printSuspension()">
										Aviso Suspendido
								</button>

								<button
									v-if="isDefeated()"
									class="mb-2 btn btn-client btn-sm ml-2 text-white" 			
									style="overflow:hidden"
									@click="printDefeated()">
										Recordatorio
								</button>

								{{-- <button
									v-if="isAviso()"
									class="btn btn-client btn-sm ml-2 text-white" 			
									style="overflow:hidden"
									@click="printReminer()">
										Aviso
								</button> --}}

								@hasrole('super_admin')
									@if(!$student->peace_save)
										<button
										v-if="student.status != 4"
										class="mb-2 btn btn-danger btn-sm ml-2 text-white" 			
										data-toggle="modal" data-target="#m_modal_6"
										>
											Retirar
										</button>

										<button 
												v-if="!edit_cuotes"
												class="mb-2 btn btn-danger btn-sm ml-2" 			
												style="overflow:hidden"
												@click="edit_cuotes = true">
													Editar Cuotas
										</button>
				
										<button 
												v-if="edit_cuotes"
												class="mb-2 btn btn-danger btn-sm ml-2" 			
												style="overflow:hidden"
												@click="edit_cuotes = false">
													Cancelar
										</button>
									@endif
								@endhasrole

								@can('students.cancel')
									<button v-if="chekPays" class="btn btn-danger btn-sm ml-2" 			
									style="overflow:hidden"
									v-if="contract_has_actual_year"
									:disabled="!active_button_cancel_active"
									@click="delet()">
										<span v-text="active_button_cancel_active ? 'Cancelar Contrato' : 'Cargando...'"></span>
									</button>
								@endcan

							</div>

	        		</div>
	        	</div>
	        </div>
	    </div>
		<!--FIN ENCABEZADO ===========================================================================-->
		<!--==========================================================================================-->


		<!--AQUI SE POSICIONAN LOS CONTRATOS =========================================================-->
		<!--==========================================================================================-->
	    <div class="m-portlet m-portlet--mobile mt-4 p-1">
    		<div class="row mt-3 justify-content-around">

				<div 
					v-if="student.status == 0"
					class="col-12 row justify-content-center mb-3">
					
					@can('students.store_edit')
					    <button 
						class="btn btn-primary" 
						@click="allEnrollments" 
						style="overflow:hidden">
							Crear Contrato
						</button>
					@endcan
					
					<br>
				</div>
			
				<div 
					v-if="verifyContract"
					class="col-12 row justify-content-around">

					<div class="col-sm-12 col-md-6">
						<h6>Bachillerato: 
							<span v-text="contract.enrollment_bachelor + ' ' + contract.enrollment_grade"></span>
						</h6>
						<p>Solicitud: 
							<span v-text="contract.request"></span> | 
							<span v-text="dateFormat(contract.created_at)"></span>
							Creado por: <span v-text="contract.user.name"></span>
						</p>
						
					</div>
					
					<div class="col-sm-12 col-md-6">
						<div class="pull-right d-flex">
							<h5 class="align-self-center mr-3">
								Año <span v-text="contract.year"></span>
							</h5>
							
							<button class="btn btn-primary btn-sm" 			
									style="overflow:hidden"
									v-if="data_for_years.length > 1"
									@click="getDataContracts(1)">
										Cambiar año
							</button>
						</div>
					</div>

					<p class="col-12" v-if="contract.observation">
						Observación: <span v-text="contract.observation"></span>
					</p>

					<div v-if="student.status == 4" class="alert alert-warning alert-dismissible fade show   m-alert m-alert--air m-alert--outline m-alert--outline-2x" role="alert">
						<button type="button" class="close ml-1" data-dismiss="alert" aria-label="Close">
						</button>
						<strong>Nota!</strong> <p class="mr-4 d-inline"> Estudiante retirado.</p>
					</div>
					
					<table class="col-12 table table-striped m-table">
						
						<thead>
							<tr>
							 <th>Descripcion</th>
							 <th>Total</th>
							 <th>R15</th>
							 <th>R1</th>
							 <th>Pagos</th>
							 <th>Fecha</th>
							 <th>Recibo</th>
							 <th>N° Operacion</th>
							 <th>Saldo</th>
							</tr>
						</thead>
						
						<tbody>

							<!--MATRICULA-->
							<tr>
								<th scope="row">Matricula</td>
								<td v-text="contract.enrollment_cost"></td>
								<td></td>
								<td></td>
								<td>
									<span 
										v-if="JSON.parse(contract.receipt)[0] != undefined"
										v-for="receipt in JSON.parse(contract.receipt)">
											@{{ parseFloat(receipt.total).toFixed(2) }}<br>
									</span>
								</td>
								<td>
									<span 
										v-if="JSON.parse(contract.receipt)[0] != undefined"
										v-for="receipt in JSON.parse(contract.receipt)">
											@{{ receipt.date }}<br>
									</span>
								</td>
								<td>
									<a
										v-if="JSON.parse(contract.receipt)[0] != undefined"
										v-for="receipt in JSON.parse(contract.receipt)"
										href="#" 
										@click.prevent="showReceipt(receipt.payment_id)">
											@{{ receipt.receipt }}<br>
									</a>
								</td>
								<td>
									<span
										v-if="JSON.parse(contract.receipt)[0] != undefined" 
										v-for="receipt in JSON.parse(contract.receipt)">
											@{{ receipt.operation_number }}<br>
									</span>
								</td>
								<td v-text="parseFloat(contract.enrollment_cost - contract.paid_out).toFixed(2)"></td>
							</tr>

							<!--SERVICIOS OBLIGATORIOS-->
							<tr v-for="(service_contract, index) in services_contract_required">
								<th v-text="service_contract.description"></th>
								<td v-text="service_contract.cost"></td>
								<td></td>
								<td></td>
								<td>
									<span v-for="receipt in JSON.parse(service_contract.receipt)">
										@{{ parseFloat(receipt.total).toFixed(2) }}<br>
									</span>
								</td>
								<!--<td v-if="!JSON.parse(service_contract.receipt).length"></td>-->
								<td>
									<span  v-for="receipt in JSON.parse(service_contract.receipt)">
										@{{ receipt.date }}<br>
									</span>
								</td>
								<!--<td v-if="!JSON.parse(service_contract.receipt).length"></td>-->
								<td>
									<a 
										href="#" 
										v-for="receipt in JSON.parse(service_contract.receipt)"
										@click.prevent="showReceipt(receipt.payment_id)">
											@{{ receipt.receipt }}<br>
									</a>
								</td>
								<td>
									<span v-for="receipt in JSON.parse(service_contract.receipt)">
										@{{ receipt.operation_number }}<br>
									</span>
								</td>
								<!--<td v-if="!JSON.parse(service_contract.receipt).length"></td>-->
								<!-- Total servicios obligatorios -->
								<td v-text="SaldoServices(index)"></td>
							</tr>

							<!--CUOTAS-->
							<tr v-for="(fee,index) in fees">
								<th>Cuota <span v-text="fee.order"></span></th>
								<td>
									<input
										v-if="edit_cuotes"
										type="number" 
										style="width: 60px;"
										v-model="fee.cost"
										:disabled = "!edit_cuotes || fee.paid_out != 0 || fee.status == 2 || fee.order == 1"
										@change="changeFee(fee, index)">
									<span v-else v-text="fee.cost"></span>
								</td>
								<td :style="fee.r15_status == 3 ? 'text-decoration:line-through' : '' ">
									<template 
										v-if="fee.r15_status != 0">

											<span 
												v-if="fee.r15_status != 3" 
												v-text="fee.r15">
											</span>
											<a 
												v-else 
												href="#"
												@click.prevent="rechargeCancel(index,'r15')"
												v-text="fee.r15">
											</a>
									</template>
								</td>
								<td :style="fee.r1_status == 3 ? 'text-decoration:line-through' : '' ">
									<template 
										v-if="fee.r1_status != 0">

											<span
												v-if="fee.r1_status != 3"
												v-text="fee.r1">
											</span>
											<a 
												v-else
												href="#"
												@click.prevent="rechargeCancel(index,'r1')"
												v-text="fee.r1">
											</a>
									</template>
								</td>
								<td>
									<span v-for="receipt in JSON.parse(fee.receipt)">
										@{{ parseFloat(receipt.total).toFixed(2) }}<br>
									</span>
								</td>
								<td>
									<span v-for="receipt in JSON.parse(fee.receipt)">
										@{{ receipt.date }}<br>
									</span>
								</td>
								<td>
									<a 
										href="#" 
										v-for="receipt in JSON.parse(fee.receipt)"
										@click.prevent="showReceipt(receipt.payment_id)">
										@{{ receipt.receipt }}<br>
									</a>
								</td>
								<td>
									<span v-for="receipt in JSON.parse(fee.receipt)">
										@{{ receipt.operation_number }}<br>
									</span>
								</td>
								<!-- Columna pagos fee-->
								<td v-text="saldoFee(index)"></td>
							</tr>

							<!--TOTALES-->
							<tr>
								<th>Total</th>
								<td v-text="totalPrincipal()"></td>
								<td v-text="saldoR15()"></td>
								<td v-text="saldoR1()"></td>
								<td v-text="saldoPagos()"></td>
								<td></td>
								<td></td>
								<td></td>
								{{-- <td v-text="totals.balance"></td> --}}
								<td v-text="saldoFee(10,1)"></td>
							</tr>
						</tbody>
					</table>
				</div>

				<div 
					v-else 
					class="col-12 row justify-content-center mb-3 justify-content-center">
					
					<h3 v-text="mensaje_contrato"><!--Este estudiante no posee contratos--></h3>
				</div>
			</div>
	    </div>
		<!--AQUI FINALIZA POSICIONAMIENTO DE CONTRATOS ===============================================-->
		<!--==========================================================================================-->



		<!--AQUI SE POSICIONAN LOS PAGOS VARIOS ===================================================================-->
		<!--=======================================================================================================-->
		<div class="m-portlet m-portlet--mobile mt-4 p-1"
			v-if="extra_payments.length > 0">

    		<div class="row mt-3 justify-content-around">
			
				<div class="col-12 row justify-content-around">

					<h4 class="col-12">Pagos Varios</h4>
					
					<table class="col-12 table table-striped m-table">
						<thead>
							<tr>
								<th>Descripcion</th>
								<th>Costo</th>
								<th>Fecha</th>
								<th>Recibo</th>
								<th>N° Operación</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="extra_payment in extra_payments">
								<td v-text="extra_payment.description"></td>
								<td v-text="extra_payment.cost"></td>
								<td>
									<span v-for="receipt in JSON.parse(extra_payment.receipt)">
										@{{ receipt.date }}
									</span>
								</td>
								<td>
									<a 
										href="#" 
										v-for="receipt in JSON.parse(extra_payment.receipt)"
										@click.prevent="showReceipt(receipt.payment_id)">
											@{{ receipt.receipt }}
									</a>
								</td>
								<td>
									<span v-for="receipt in JSON.parse(extra_payment.receipt)">
										@{{ receipt.operation_number }}
									</span>
								</td>
							</tr>
							<tr>
								<th>Total</th>
								<td v-text="extraPaymentsTotal()"></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
	    </div>
		<!--CEERAR POSICIONAMIENTO DE LOS PAGOS VARIOS ============================================================-->
		<!--=======================================================================================================-->



		<!--MODAL PARA AGREGAR UN NUEVO CONTRATO ==================================================================-->
		<!--=======================================================================================================-->
		<div class="modal fade" id="m_modal_1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header row justify-content-between" style="overflow: hidden;">
						<h5 class="modal-title col-5" id="exampleModalLabel" v-text="'Crear Contrato'"></h5>
						
						<div class="col-4">
							@hasrole('super_admin')
								<button 
									@click="edit_values = !edit_values"
									type="button" 
									:class="'btn btn-sm ' + (!edit_values ? 'btn-danger' : 'btn-success')"
									v-text="!edit_values ? 'Editar Valores' : 'Listo'"
									:disabled="!enrollment.id">
								</button>
							@endhasrole
							
							<button type="button" @click="closeModal(1)" class="close close1" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
					</div>
					<div class="modal-body">
						
						
						<form>
							
							<div class="row control-label container">
								<label for="bachelor_grade" class="control-label col-6">Escoger Bachiller y grado</label>
								
								<select 
									@change="createContracts"
									v-model="enrollment.id"
									class="col-6 form-control m-input m-input--air m-input--pill"
									id="bachelor_grade">
									
									<option 
										v-for="enrollment_ in enrollments" 
										:value="enrollment_.id" 
										v-text="enrollment_.bachelor + ' | ' + enrollment_.grade">
									</option>
								</select>
							</div>



							<table class="col-12 table table-striped m-table">
								<thead>
									<tr>
										<th>Descripcion</th>
										<th>Monto</th>
									</tr>
								</thead>
								<tbody>
									<tr v-for="service_required in services_required">
										<td v-text="service_required.description"></td>
										<td v-if="!edit_values" v-text="service_required.cost"></td>
										<td v-else>
											<input 
												type="number" 
												v-model="service_required.cost" 
												style="width: 60px;">
										</td>
									</tr>
									<tr>
										<td>Matricula</td>
										<td v-if="!edit_values" v-text="enrollment.cost"></td>
										<td v-else>
											<input 
												type="number" 
												v-model="enrollment.cost" 
												style="width: 60px;">
										</td>
									</tr>
									<tr>
										<td>Anualidad</td>
										<td v-if="!edit_values" v-text="annuity.cost"></td>
										<td v-else>
											<input 
												type="number" 
												v-model="annuity.cost" 
												style="width: 60px;">
										</td>
									</tr>
									<tr>
										<td>Descuento</td>
										<td v-if="!edit_values" v-text="annuity.discount"></td>
										<td v-else>
											<input 
												type="number" 
												v-model="annuity.discount" 
												style="width: 60px;">
										</td>
									</tr>
									<tr>
										<td>Total</td>
										<td v-text="costTotal().toFixed(2)"></td>
									</tr>
								</tbody>
							</table>

							<div class="form-group">
								<label class="control-label" for="observacion">Observaciones</label>
								<textarea row=7 id="observacion" class="form-control" v-model="observation">
									
								</textarea>
							</div>
						</form>


						<!--ERRORES DE VALIDACION-->
                        <div v-show="error" class="form-group row div-error">
                            <div class="text-center text-error" style="margin:0 auto">
                                <div class="text-danger" v-for="error in errors" :key="error" v-text="error">
                                    
                                </div>
                            </div>
                        </div>
					</div>

					<div class="modal-footer">
						<button type="button" @click="closeModal" :disabled="!active_button_cancel_active" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
						<button type="button" @click="registre" :disabled="!active_button_cancel_active || !active_registre" class="btn btn-primary">
							<span v-text="active_button_cancel_active ? 'Crear' : 'Cargando...'"></span>
						</button>
					</div>
				</div>
			</div>
		</div>
		<!--=======================================================================================================-->
		<!--CERRAR MODAL PARA AGREGAR NUEVO CONTRATO=======================================-->




		<!--SELECCIONAR QUE AÑO QUIERE VER ============================================================================-->
		<!--=======================================================================================================-->
		<div class="modal fade" id="m_modal_2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel" v-text="'Seleccionar Año'"></h5>
						<button type="button" @click="closeModal(2)" class="close close2" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						
						<table class="col-12 table table-striped m-table">
							<thead>
								<tr>
									<th>Año</th>
									<th>Descripción</th>
									<th>Grado</th>
									<th>Acciones</th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="data_year in data_for_years">
									<td v-text="data_year.year"></td>
									<td v-text="data_year.description"></td>
									<td v-text="data_year.grade"></td>
									<td>
										<button title="Ver" @click="getContract(data_year.year)" class="btn btn-success btn-sm">
											<i class="fa fa-eye"></i>
										</button>
									</td>
								</tr>
							</tbody>
						</table>
						
					</div>

					<div class="modal-footer">
						<button type="button" @click="closeModal" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
						<!--<button type="button" @click="registre" class="btn btn-primary">Crear</button>-->
					</div>
				</div>
			</div>
		</div>
		<!--=======================================================================================================-->
		<!--CERRAR MODAL PARA SELECCIONAR AÑO =====================================================================-->




		<!--MODAL PARA MOSTRAR IMAGEN ============================================================================-->
		<!--=======================================================================================================-->
		<div class="modal fade" id="m_modal_3" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" @click="closeModal(3)" class="close close3" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						
						<div style="width: 100%">
							<img
								style="width:100%; height: auto"
								:src="student.image == null ? '/app/images-students/' + student.image : '/app/images-default/student.jpg'">
						</div>
					</div>
				</div>
			</div>
		</div>
		<!--=======================================================================================================-->
		<!--CERRAR MODAL PARA MOSTRAR IMAGEN =====================================================================-->

		


		<!--MODAL PARA MOSTRAR RECIBO DE PAGO =====================================================================-->
		<!--=======================================================================================================-->
		<div class="modal fade" id="m_modal_4" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel" v-text="'Detalles del recibo'"></h5>
						<button type="button" @click="closeModal(4)" class="close close4" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body" v-if="payment != null">

						<div class="row justify-content-between">
							<div class="col-6">
								<span style="font-size:16px"><strong>Recibo <span v-text="payment.receipt"></span></strong></span>
								<a v-if="payment!=null" href="#" @click.prevent="printReceipt()"><i class="fa fa-print"></i></a>
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
									<tr v-if="payment.refund != 0 && ind == 0">
										<td>Reembolso</td>
										<td v-text="payment.refund"></td>
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
		</div>
		<!--=======================================================================================================-->
		<!--CERRAR MODAL PARA RECIBO DE PAGO ======================================================================-->




		<!--MODAL MOSTRAR CANCELACION DE RECARGO =================================================================-->
		<!--======================================================================================================-->
		<div class="modal fade" id="m_modal_5" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header" style="background: #A22; color: white">
						
						<h5 
							class="modal-title" 
							id="exampleModalLabel" 
							style="color: white"
							v-if="modal_recharge_cancel.data_cancel != null">
								@{{ 'Recargo del ' + modal_recharge_cancel.type + '% cancelado' }} <br> 
								@{{ 'Fecha: ' + modal_recharge_cancel.data_cancel.date }} <br> 
								@{{ 'Por: ' + modal_recharge_cancel.data_cancel.user }}
						</h5>
						
						<button type="button" @click="closeModal(5)" class="close close5" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body" v-if="modal_recharge_cancel.data_cancel != null">
						<h5>
							Motivo de cancelacion:
						</h5>
						<p v-if="modal_recharge_cancel != null">
							<span v-text="modal_recharge_cancel.data_cancel.comment"></span>	
						</p>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal" @click="closeModal()">Cerrar</button>
					</div>
				</div>
			</div>
		</div>
		<!--=======================================================================================================-->
		<!--CERRAR CANCELACION DE RECARGO =========================================================================-->

		<!--MODAL RETIRAR ESTUDIANTE =================================================================-->
		<!--======================================================================================================-->

			<!--begin::Modal-->
			<div class="modal fade" id="m_modal_6" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-sm" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel">Retirar estudiante</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<h4>¿Seguro que desaa retirar a este estudiante.?</h4>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary close6" data-dismiss="modal">Cancelar</button>
							<button type="button" @click="removeStudent()" class="btn btn-client text-white">Aceptar</button>
						</div>
					</div>
				</div>
			</div>
			<!--end::Modal-->

		<!--=======================================================================================================-->
		<!--CERRAR MODAL RETIRAR ESTUDIANTE =========================================================================-->

		<!--MODAL INFO =================================================================-->
		<!--======================================================================================================-->

			<!--begin::Modal-->
			<div class="modal fade" id="m_modal_7" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-sm" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel" v-text="infoTitle"></h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<h4 v-text="infoMessage"></h4>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-client close7 text-white" data-dismiss="modal">Aceptar</button>
						</div>
					</div>
				</div>
			</div>
			<!--end::Modal-->

		<!--=======================================================================================================-->
		<!--CERRAR MODAL INFO =========================================================================-->

		<!--IMPRIMIR EL PAZ Y SALVO ==============================================================================-->
		<!--======================================================================================================-->
		@if($student->peace_save)
			<div id="print_peace_save" style="display:none; z-index: 1200; position: absolute; width:100%; top: 0; height: 100vh">
				@include('students.student.peace_save')
			</div>
		@endif
		<!--======================================================================================================-->
		<!--CERRAR IMPRIMIR PAZ Y SALVO ==========================================================================-->

		@include('payments.print_receipt')

		{{-- Imprimir recordatorio de pago --}}
		<iframe src="" id="PDFtoPrint" class="d-none" width="100%" height="100%"></iframe>
		{{-- Fin imprimir recordatorio de pago --}}

	<!--</div>-->
@endsection

@section('scripts')

	<script>
		const app = new Vue({
			
			el: '#app',


			data: {
				student: {
					id: {!! $student->id !!},
					peace_save: {!! $student->peace_save !!},
					status: {!! $student->status !!},
					image: "{!! $student->image !!}"
				},

				//MENSAJE INDICATIVO DE SI HAY CONTRATO O NO (CARGA DE CONTRATO)
				mensaje_contrato: '',

				user_id: 1, //ESTO SE DEBE MODIFICAR CUANDO SE CREE EL MODULO DE AUTENTICACION
				contract: {},
				date_contract: '',
				contract_has_actual_year: false,
				contractActive: 0,
				//num_contracts: 0,

				select_year: 0,

				fees: [],
				fees_aux: [],
				verifyContract: 0,

				btn_print_peace_save: false,
				
				enrollments: [],

				enrollment: {
					id: 0,
					grade: '',
					cost: 0,
					bachelor: '',
				},

				data_contracts: [],
				data_extra_payments: [],

				annuity: {
					cost: 0,
					discount: 0,
					year: 0,
					max_date: '',
					second_month: 0,
				},

				//=====================
				data_for_years: [],
				//=====================

				edit_cuotes: false,
				fee_aux: null,

				//Editar valores de crear contrato
				edit_values: false,

				total: 0,

				payment: null,
				pays: [],
				pays_sort: [],

				totals :{
					total: 0,
					r15: 0,
					r1: 0,
					balance: 0,
				},

				services: [],
				services_required: [],
				services_contract_required: [],

				//MODAL RECHARGE
				modal_recharge_cancel: {
					type: "",
					data_cancel: null,
				},

				extra_payments: [],

				observation: '',

				active_button_cancel_active: true,
				active_registre: false,

				error: 0,
	            errors: [],

	            infoTitle: '',
	            infoMessage: '',
			},

			computed: {
				chekPays: function () {
					var aux1 = true;
					var aux2 = true;
					if (this.contract.paid_out > 0 || this.contract.annuity_paid_out > 0) { aux1 = false }
					this.services_contract_required.forEach((element) => {
						if (element.paid_out > 0) {
							aux2 = false;
						}
					});

					if (aux1 && aux2) {
						return true;
					} else {
						return false;
					}
				}
			},

			methods: {

				checkForm(){
	                this.error = 0;
	                this.errors = [];

	               if (!this.enrollment.id) this.errors.push("Grado Requerido");

	                if(this.errors.length) this.error = 1;

	                return this.error;
	            },

	            //VALIDAR NUMEROS REALES
	            validateNumber(number){
	                var aux=0;

	                for(i=0;i<number.length;i++){
	                    if(number.charAt(i).charCodeAt(0)!=46){
	                        if(number.charAt(i).charCodeAt(0)<48 || number.charAt(i).charCodeAt(0)>58){
	                            return 0;       
	                        }
	                    }
	                    else if(number.charAt(i).charCodeAt(0)==46){
	                        aux++;
	                    }
	                    if(aux>1){
	                        return 0;
	                    }
	                }

	                return 1;
	            },


				costTotal(){
					let aux = 0;

					for(i=0; i<this.services_required.length; i++){
						aux += parseFloat(this.services_required[i].cost);
					}

					return aux + parseFloat(this.enrollment.cost) 
								+ parseFloat(this.annuity.cost) 
								- parseFloat(this.annuity.discount);
				},


				calcTotals(){
					let aux_total = 0;
					let aux_r15 = 0;
					let aux_r1 = 0;

					for(i=0; i<this.services_contract_required.length; i++){
						aux_total += parseFloat(this.services_contract_required[i].cost);
					}

					for(i=0; i<this.fees.length; i++){
						aux_total += parseFloat(this.fees[i].cost);
						aux_r15 += parseFloat(this.fees[i].r15);
						aux_r1 += parseFloat(this.fees[i].r1);
					}

					this.totals.total = aux_total
										+ parseFloat(this.contract.enrollment_cost);

					this.totals.r15 = aux_r15;
					this.totals.r1 = aux_r1;
				},


				getContract(year = null){

	            	let me = this;
	            	let url = '';

	            	if(year == null) url = '/students/contract/show/' + me.student.id;
	            	else url = '/students/contract/show/' + me.student.id + '/' + year;

	            	me.mensaje_contrato = 'Cargando...';

	            	axios
	            		.get(url)
	            		.then(function(response){

	            			//DEVOLVER DATOS DEL CONTRATO
	            			let respuesta = response.data;
		            		
		            		if(respuesta.contract != undefined){
		            			me.contract = respuesta.contract;
		            			me.date_contract = respuesta.date_contract;
		            			//me.totals.balance = respuesta.total.balance;
		            			me.verifyContract = 1;
		            			me.fees = JSON.parse(JSON.stringify(respuesta.fees));
		            			me.fees_aux = JSON.parse(JSON.stringify(respuesta.fees));
		            			me.services_contract_required = JSON.parse(JSON.stringify(respuesta.services_contract_required));
		            			//VARIABLE QUE DICE SI EL CONTRATO VIGENTE ES DEL AÑO ACTUAL ============
		            			me.contract_has_actual_year = respuesta.contract_has_actual_year;
		            		}else{
		            			me.mensaje_contrato = 'Este estudiante no posee contratos';
		            			me.contract = {};
		            			me.date_contract = '';
		            			me.verifyContract = 0;
		            			me.services_contract_required = [];
		            			me.fees = [];
		            			me.student.status = 0;
		            		}

	            			//DEVOLVER DATOS DE PAGOS VARIOS DEL AÑO ESPECIFICADO ===================
	            			if(respuesta.extra_payments != undefined) me.extra_payments = respuesta.extra_payments;
	            			else me.extra_payments = [];

	            			//AXIONES
	            			me.closeModal(1);
	            			me.closeModal(2);
	            			//me.calcTotals();

	            		}).catch(function(error){
	            			console.log("ERROR");

	            			me.contract = {};
	            			me.date_contract = '';
	            			me.verifyContract = 0;
	            			me.services_contract_required = [];
	            			me.fees = [];
	            			me.student.status = 0;

	            			me.contract_has_actual_year = false;
	            			me.mensaje_contrato = 'Este estudiante no posee contratos';
	            		});
	            },


	            getDataContracts(abrir_modal = 0){

	            	let me = this;

	            	me.active_registre = false;

	            	axios
	            		.get('/students/contract/getData/' + me.student.id)
	            		.then(function(response){

	            			//LIMPIAR ARREGLO DE DATOS POR AÑO
	            			me.data_for_years = [];
	            			
	            			//DEVOLVER TODOS LOS CONTRATOS DEL ESTUDIANTE
	            			me.data_contracts = response.data.contracts;

	            			//DEVOLVER TODOS LOS PAGOS VARIOS DE ESTE ESTUDIANTE =================
	            			me.data_extra_payments = response.data.extra_payments;
	            			
	            			me.verifyActivesYears(me.data_contracts, me.data_extra_payments);

	            			if(abrir_modal == 0) return;

	            			me.openModal(1);
	            		}).catch(function(error){

	            			console.log("ERROR", error);
	            		});
	            },


	            verifyActivesYears(contratos, pagos_extras){

	            	var last_year = 0;
	            	var first_year = 0;

	            	if(contratos.length > 0 && pagos_extras.length > 0){
		            	
		            	first_year = contratos[0].year <= pagos_extras[0].year ? contratos[0].year : pagos_extras[0].year;

						last_year = contratos[contratos.length - 1].year <= pagos_extras[pagos_extras.length - 1].year ? pagos_extras[pagos_extras.length - 1].year : contractos[contratos.length - 1].year;
					}
					else if(contratos.length > 0){
						
						first_year = contratos[0].year;
						last_year = contratos[contratos.length - 1].year;
					}
					else if(pagos_extras.length > 0){
						
						first_year = pagos_extras[0].year;
						last_year = pagos_extras[pagos_extras.length - 1].year;
					}
					else {

						this.data_for_years = [];

						return 0;
					}

	            	let aux_object = {};

	            	for(i = first_year; i <= last_year; i++){

	            		var aux_description = '';
	            		var aux_grade = '--';

	            		for(j=0; j < contratos.length; j++){
	            			if(contratos[j] != undefined){
		            			if(contratos[j].year == i){
		            				aux_description = contratos[j].enrollment_bachelor;
		            				aux_grade = contratos[j].enrollment_grade;
		            				break;
		            			}
		            		}
	            		}

	            		for(k=0; k < pagos_extras.length; k++){
		            		if(pagos_extras[k] != undefined){
		            			if(pagos_extras[k].year == i){
		            				if(aux_description == ''){
		            					aux_description = 'Pagos Varios';
		            				}
		            				else{
		            					aux_description += ' + Pagos Varios';
		            				}	

		            				break;
		            			}
		            		}
	            		}

	            		if(aux_description != ''){
	            			
	            			aux_object = {
	            				'description': aux_description,
	            				'year': i,
	            				'grade': aux_grade,
	            			}

	            			this.data_for_years.push(aux_object);
	            		}
	            	}
	            	this.data_for_years.sort(function (a, b) {
					  if (a.year > b.year) {
					    return -1;
					  }
					  if (a.year < b.year) {
					    return 1;
					  }
					  // a must be equal to b
					  return 0;
					});
	            	return 0;
	            },


	            allEnrollments(){

	            	let me = this;
	            	me.active_registre = false;
	            	
	            	axios.
		            	get('/costos/enrollment/getAll')
		            	.then(function(response){
		            		me.enrollments = response.data.enrollments;
		            		console.log(response.data);
		            		me.openModal(0);
		            	}).catch(function(error){
		            		console.log('Error de solicitud');
		            	});
	            },

	            
	            createContracts(){

	            	let me = this;
	            	me.active_registre = false;
	            	
	            	axios
	            	.get('/students/contract/create/' + this.enrollment.id)
	            	.then(function(response){
	            		let respuesta = response.data.enrollment;

	            		me.enrollment.cost = respuesta.cost;
	            		me.enrollment.grade = respuesta.grade;
	            		me.enrollment.bachelor = respuesta.bachelor;

	            		respuesta = response.data.annuity;
	            		
	            		me.annuity.discount = respuesta.discount;

	            		me.annuity.cost = respuesta.cost;
	            		me.annuity.year = respuesta.year;
	            		me.annuity.max_date = respuesta.maximum_date;
	            		me.annuity.second_month = respuesta.second_month;
	            		console.log(me.annuity.max_date);

	            		me.services_required = response.data.services_required;

	            		me.total = me.costTotal();

	            		me.active_registre = true;

	            	}).catch(function(error){
	            		console.log(error);

	            		me.enrollment.cost = 0;
	            		me.enrollment.grade = '';
	            		me.enrollment.bachelor = '';

	            		me.annuity.cost = 0;
	            		me.annuity.discount = 0;
	            		me.annuity.year = 0;
	            		me.services_required = []

	            		me.total = me.costTotal();
	            	});
	            },


	            printPeaceSAve(){
	            	//app.$forceUpdate();
					//var contenido= document.getElementById('print_peace_save').innerHTML;
					//var contenidoOriginal= document.body.innerHTML;
					//document.body.innerHTML = contenido;
					window.scrollBy(0, 0);
					//window.print();
					//window.print();
					//document.body.innerHTML = contenidoOriginal;
            		//app.$forceUpdate();
            		//this.btn_print_peace_save = false;
            		//location.reload();

            		$('#print_peace_save').show(function(){
						window.print();
						
						setTimeout(function(){$('#print_peace_save').hide();},100);
					});
	            },

	            isAviso() {
	            	for(fee of this.fees) {
	            		if (fee.order > 1 && fee.status == 1) {
	            			return true;
	            		}
	            	}
	            },

	            printReminer() {
	            	url = '/students/reminder/' + this.student.id;
	            	window.open(url);
					$('#PDFtoPrint').attr('src',url);
					setTimeout(function() {
						$('#PDFtoPrint').get(0).contentWindow.focus();
					},2000);
	            },

	            printSuspension() {
	            	url = '/students/suspension/' + this.student.id + '/' + this.contract.id;
					$('#PDFtoPrint').attr('src',url);
					setTimeout(function() {
						$('#PDFtoPrint').get(0).contentWindow.focus();
					},2000);
	            },

	            isDefeated() {
	            	let fees = this.fees;
	            	let nowMoth = new Date().getMonth() + 1;
	            	let dateAux;
	            	for (var i = 0; i < fees.length; i++) {
	            		fee = fees[i];
	            		d = new Date(fees[i].date).getMonth() + 1;
	            		if (fee.order > 1 && fee.status == 0 && nowMoth == d) {
	            			return true;
	            		}
	            	}
	            },

	            printDefeated() {
	            	let fees = this.fees;
	            	let nowMoth = new Date().getMonth() + 1;
	            	let dateAux, auxFee, nro, date;
	            	for (var i = 0; i < fees.length; i++) {
	            		fee = fees[i];
	            		d = new Date(fees[i].date).getMonth() + 1;
	            		if (fee.order > 1 && fee.status == 0 && nowMoth == d) {
	            			auxFee = fee;
	            			date = new Date(fees[i].date).getDate();
	            		}
	            	}
	            	if (date < 15) {
	            		nro = 1
	            	}else if(date >= 15 && date < 25) {
	            		nro = 2;
	            	} else {
	            		nro = 3;
	            	}
	            	url = '/students/defeated/' + this.student.id + '/' + auxFee.id + '/' + nro;
					$('#PDFtoPrint').attr('src',url);
					setTimeout(function() {
						$('#PDFtoPrint').get(0).contentWindow.focus();
					},2000);
	            },

	            changeFee(fee, index){

	            	let me = this;

	            	if(!me.validateNumber(fee.cost)){
	            		alert("Debe introducir una cifra valida");
	            		fee.cost = me.fees_aux[index].cost;
	            		app.$forceUpdate();
	            		return;
	            	}


	            	if(parseFloat(fee.cost) < 0.00 || parseFloat(fee.cost) > parseFloat(me.fees_aux[index].cost)){

	            		alert("El monto introducido debe ser mayor que 0 y menor que " + me.fees_aux[index].cost);
	            		fee.cost = me.fees_aux[index].cost;
	            		app.$forceUpdate();
	            		return;
	            	}


	            	axios
	            		.post('/students/contract/changeFee', {
	            			'id': fee.id,
	            			'cost': fee.cost
	            		})
	            		.then(function(response){

	            			me.fees[index] = JSON.parse(JSON.stringify(response.data.fee));
	            			me.fees_aux[index] = JSON.parse(JSON.stringify(response.data.fee));
	            			me.fees[index].cost = parseFloat(me.fees[index].cost).toFixed(2);
	            			me.contract.annuity_cost = response.data.annuity_cost;
	            			app.$forceUpdate();
	            			console.log('satisfactorio');
	            		}).catch(function(error){
	            			console.log(error)
	            		});
	            },


	            registre(){

	            	if (this.checkForm()) {
		                return;
		            }

	            	let me = this;
	            	me.active_button_cancel_active= false;

	            	axios
	            		.post('/students/contract/store',{
	            			'enrollment_grade': me.enrollment.grade,
	            			'enrollment_cost': me.enrollment.cost,
	            			'enrollment_bachelor': me.enrollment.bachelor,
	            			'annuity_cost': me.annuity.cost,
	            			'annuity_discount': me.annuity.discount,
	            			'annuity_max_date': me.annuity.max_date,
	            			'annuity_second_month': me.annuity.second_month,
	            			'year': me.annuity.year,
	            			'student_id': me.student.id,
	            			'user_id': me.user_id,
	            			'observation' : me.observation,
	            			'services_required': me.services_required,
	            		}).then(function(response){
	            			me.student.status = 1;
	            			me.student.peace_save = false;
	            			me.getContract();
	            			me.closeModal(1);
	            			me.active_button_cancel_active = true;
	            		}).catch(function(error){
	            			console.log(error);
	            		});
	            },


	            /*delet(){

	            	let me = this;

	            	if (!confirm('¿Seguro desea eliminar?')) return;

	            	me.active_button_cancel_active = false;

            		axios
	            		.delete('/students/contract/delete/' + this.student.id)
		                .then(function(response){
		                	//me.student.peace_save = true;
		                	me.getContract();
		        			me.active_button_cancel_active = true;
		                })
		                .catch(function(error) {
		                    console.log(error);
		                    me.active_button_cancel_active = true;
		                })	
	            },*/

				delet(){

	            	let me = this;

	            	if (!confirm('¿Seguro desea eliminar?')) return;

	            	me.active_button_cancel_active = false;

            		axios
	            		.delete('/students/contract/delete/' + this.contract.id)
		                .then(function(response){
		                	//me.student.peace_save = true;
		                	me.getContract();
		        			me.active_button_cancel_active = true;
		                })
		                .catch(function(error) {
		                    console.log(error);
		                    me.active_button_cancel_active = true;
		                })	
	            },	            


	            showReceipt(indice){

					let me = this;
					var url = '/pagos/show/' + indice; //UBICADA EN EL CONTROLADOR PaymentController

					axios
						.get(url)
						.then(function(response){
							
							me.payment = response.data.payment;
							me.pays = JSON.parse(response.data.payment.info_str);
							
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

							me.openModal(2);
						}).catch(function(error){
							console.log(error);
						});
				},


				rechargeCancel(index, type){

					if(type == 'r15'){
						console.log('r15')
						this.modal_recharge_cancel.type = '15';
						this.modal_recharge_cancel.data_cancel = JSON.parse(this.fees[index].r15_cancel_data);
					}
					else{
						this.modal_recharge_cancel.type = '1';
						this.modal_recharge_cancel.data_cancel = JSON.parse(this.fees[index].r1_cancel_data);	
					}

					this.openModal(4);
				},

				printReceipt(){

					window.scrollBy(0, 0);

					$('#print_receipt').show(100,function(){
						window.print();
						
						setTimeout(function(){$('#print_receipt').hide();},100);
					});
				},


				removeStudent() {
					let me = this;
					axios
	            		.get('/students/remove/' + this.student.id)
		                .then(function(response){
		                	let stu = response.data.student;
		                	me.student.status = stu.status;
		                	
		                	// Solicitar Nueva info
		                	me.getContract();
            				me.getDataContracts();

            				// Mostrar Mensaje
		                	me.infoTitle = 'Estudiante Retirado';
		                	me.infoMessage = 'Estudiante retirado correctamente';
		                	$('#m_modal_7 .modal-header').addClass('bg-success');
		                	me.closeModal(6);
		                	me.openModal(7);
		                	setTimeout(() => {
		                		$('#m_modal_7').modal('hide');
		                		$('.close').click();
		                		me.infoTitle = '';
		                		me.infoMessage = '';
		                		$('#m_modal_7 .modal-header').removeClass('bg-success');
		                	},3000);
		                })
		                .catch(function(error) {
		                    console.log(error);
		                    me.infoTitle = 'Estudiante Retirado';
		                	me.infoMessage = 'Algo paso, por favor intente nuevamente';
		                	$('#m_modal_7 .modal-header').addClass('bg-danger');
		                	me.closeModal(6);
		                	me.openModal(7);
		                	setTimeout(() => {
		                		$('#m_modal_7').modal('hide');
		                		$('.close').click();
		                		me.infoTitle = '';
		                		me.infoMessage = '';
		                		$('#m_modal_7 .modal-header').removeClass('bg-danger');
		                	},3000);
		                });
				},


				SaldoServices(service){

					if (this.services_contract_required.length == 1) {
						aux = (this.contract.enrollment_cost - this.contract.paid_out) + (this.services_contract_required[0].cost - this.services_contract_required[0].paid_out)
						return parseFloat(aux).toFixed(2)
					} else {
						aux = this.contract.enrollment_cost - this.contract.paid_out
						acum = 0
						for (var i = 0; i <= service; i++) {
							acum += this.services_contract_required[i].cost - this.services_contract_required[i].paid_out
						}
						return parseFloat(aux + acum).toFixed(2)
					}
				},


				saldoFee(fee, t = null){
					
					let fees = this.fees
					let enrollment = this.contract.enrollment_cost - this.contract.paid_out
					let services = 0
					let saldoFeeNow = 0

					dateFee = new Date(fees[fee].date)
					dateNow = new Date()

					if (dateFee < dateNow || t != null) {
						
						for (service of this.services_contract_required) {
						services += service.cost - service.paid_out 
						}
					
						if (this.fees[fee].order == 1) {
								return parseFloat(enrollment + services + (fees[fee].cost - fees[fee].paid_out)).toFixed(2)
							} else {
								for (var i = 0; i <= fee; i++) {
									let auxR15 = 0
									let auxR1 = 0
									if (fees[i].r15_status == 1) auxR15 = fees[i].r15 - fees[i].r15_paid_out
									if (fees[i].r1_status == 1) auxR1 = fees[i].r1 - fees[i].r1_paid_out
									saldoFeeNow += (fees[i].cost - fees[i].paid_out) + auxR15 + auxR1
								}
							}
							return parseFloat(enrollment + services + saldoFeeNow).toFixed(2)

						} else {
							
							return ''
						}
				},


				saldoR15(){
					let fees = this.fees
					let saldo = 0

					for (fee of fees) {
						if (fee.r15_status != 3) {
							saldo += parseFloat(fee.r15)
						}
					}
					return parseFloat(saldo).toFixed(2)
				},


				saldoR1(){
					let fees = this.fees
					let saldo = 0

					for (fee of fees) {
						if (fee.r1_status != 3) {
							saldo += parseFloat(fee.r1)
						}
					}
					return parseFloat(saldo).toFixed(2)
				},


				totalPrincipal(){

					let aux = parseFloat(this.contract.annuity_cost) + parseFloat(this.contract.enrollment_cost);

					for(service of this.services_contract_required){
						aux += parseFloat(service.cost);
					}

					return  parseFloat(aux).toFixed(2);
				},


				saldoPagos(){
					let fees = this.fees
					let enrollment = this.contract.paid_out
					let services = 0
					let saldo = 0

					for (service of this.services_contract_required) {
						services += service.paid_out 
						}

					for (fee of fees) {
						for(receipt of JSON.parse(fee.receipt)){
							saldo += receipt.total
						}
					}

					let acum = parseFloat(enrollment) + parseFloat(services) + parseFloat(saldo)
					return acum.toFixed(2)
				},

				extraPaymentsTotal(){

					let me = this;
					let total = 0;

					for(extra_payment of me.extra_payments){
						total += parseFloat(extra_payment.cost);
					}

					return total.toFixed(2);
				},

				dateFormat(date){
					var d = new Date(date);
					return d.getDate() + '/' + (d.getMonth() + 1) + '/' + d.getFullYear() + ' ' + this.addZero(d.getHours()) +':' + this.addZero(d.getMinutes());
				},

				addZero(i) {
				  if (i < 10) {
				    i = "0" + i;
				  }
				  return i;
				},


	            openModal(num_modal){
	                if(num_modal == 0){
	                	$('#m_modal_1').modal('show')
	                }else if(num_modal == 1){
	                	$('#m_modal_2').modal('show')
	                }else if(num_modal == 3){
	                	$('#m_modal_3').modal('show')
	                }else if(num_modal == 2){
	                	$('#m_modal_4').modal('show')
	                }else if(num_modal == 4){
	                	$('#m_modal_5').modal('show')
	                }else if(num_modal == 7){
	                	$('#m_modal_7').modal('show')
	                }
	            },


	            closeModal(num_modal){	
	            	//this.services = []

	            	switch(num_modal){
	                	case 1 : $('.close1').click(); break;
	                	case 2 : $('.close2').click(); break;
	                	case 3 : $('.close3').click(); break;
	                	case 4 : $('.close4').click(); break;
	                	case 5 : $('.close5').click(); break;
	                	case 6 : $('.close6').click(); break;
	                	case 6 : $('.close7').click(); break;
	                }

	            	this.total = 0

	            	this.max_date = ''

	            	this.observation = ''

	            	this.enrollments = []
	            	
	            	this.enrollment = {
	            		id: 0,
						grade: '',
						cost: 0,
						bachelor: '',
	            	}

	            	this.annuity = {
		            	cost: 0,
						discount: 0,
	            	}

	            	this.payment = null;
	            	this.pays = [];
	            	this.pays_sort = [];

	            	this.error = 0
	                this.errors = []

	                this.data_contracts = []

	                //this.data_for_years = []


	                this.modal_recharge_cancel.type = '';
	            },
			},

			mounted(){

            	this.getContract();
            	this.getDataContracts();

            },
		});
	</script>

@endsection