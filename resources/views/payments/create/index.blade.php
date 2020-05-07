	@extends('layouts.app')

	@section('title','Agregar Pago')

	@push('styles')
		<link href="{{ asset('css/select2.css') }}" rel="stylesheet"/>

		<style>
			input[type=number]::-webkit-inner-spin-button, 
			input[type=number]::-webkit-outer-spin-button { 
			  -webkit-appearance: none; 
			  margin: 0; 
			}

			input[type=number] { -moz-appearance:textfield; }

			.btn-client {
				background-color: rgb(25, 59, 100) !important;
			}
		</style>
	@endpush

	@section('content')
		
		<div>
			<div class="m-portlet m-portlet--mobile">

				<!-- ENCABEZADO DE PAGINA ===========================================================================================-->
				<!-- ================================================================================================================-->
				<div class="m-portlet__head mt-4">
					<div class="m-portlet__head-caption row">
						<div class="m-portlet__head-title">
							<h3 class="m-portlet__head-text">
								Agregar Pago
							</h3>
						</div>

						<div>
							<form class="col-12" 
								style="position:relative">
								
								<div 
									class="input-group m-input-group"
									v-if="type_asignation == 0">
										<div class="input-group-prepend">
											<span class="input-group-text">
												<i class="fa fa-search"></i>
											</span>
										</div>
										
										<input 
											type="text" 
											class="form-control" 
											v-model="search">
								</div>
								
								<div 
									v-if="search != ''" style="position: absolute; width: 100%; margin-left: 42.8px">
										<select
											style="position: relative;  width: 70%; z-index:1000"
											v-if="options.length != 0"
											multiple="" 
											v-model="selected"
											class="form-control m-input" 
											id="exampleSelect2">
												<option
													v-for="option in options"
													@click.prevent="addStudent()"
													v-text="option.name"
													:value="option.id"
													style="padding: 10px 5px 10px 5px">
														<a href="#"></a>
												</option>
										</select>
										<h6 v-else>0 resultados</h6>
								</div>
							</form>
						</div>
					</div>
					<div class="m-portlet__head-tools">
				
						<button
							type="button"
							v-if="type_asignation == 0 && students.length > 0"
							class="btn btn-client text-white mr-2 btn-sm"
							@click="selfAssignment()"
							:disabled="!save_active">
								Autoasignación
						</button>
				
						<button 
							type="button" 
							v-if="type_asignation == 0 && students.length > 0"
							@click="activatedManualAsignation()"
							class="btn btn-client text-white mr-2 btn-sm"
							:disabled="!save_active">
								Asignacion Manual
						</button>

						<button
							type="button"
							v-if="type_asignation == 1 || type_asignation == 2"
							@click="resetInputs()"
							class="btn btn-danger mr-2 btn-sm"
							:disabled="!save_active">
								Cancelar Asignacion
						</button>
				
						<ul class="m-portlet__nav">
							<li class="m-portlet__nav-item">
								<button 
									type="button" 
									@click="registre" 
									class="btn btn-accent m-btn m-btn--pill m-btn--custom m-btn--icon m-btn--air"
									:disabled="!save_active">
					                    <span>
											<i class="la la-save"></i>
											<span v-if="save_active">Guardar</span>
											<span v-if="!save_active">Cargando...</span>
										</span>
				                </button>
							</li>
						</ul>
					</div>
				</div>
				<!-- FIN ENCABEZADO DE PAGINA =======================================================================================-->
				<!-- ================================================================================================================-->
			


				<div class="m-portlet__body">


					<!--FORMULARIO DE AGREGAR PAGO ===================================================================================-->
					<!--==============================================================================================================-->
					<form class="row justify-content-between m-form m-form--fit m-form--label-align-right">
			
						<div class="col-4">
							<div class="row input-group m-input-group" v-if="active_datapicker==1">
								<label for="fecha_deposito" class="col-6">Fecha de deposito</label>
								<my-date-picker class="col-6" id="datapicker" v-model="deposit_date"></my-date-picker>
							</div>
			
							<br>
			
							<div class="row input-group m-input-group">
								<label for="operation_number" class="col-6">N° de operación</label>
								<input autocomplete="off" type="text" id="operation_number" v-model="operation_number" placeholder="N° de operación" class="col-6 form-control m-input">
							</div>
			
							<br>

							<div class="row input-group m-input-group">
								<label for="metodo_pago" disable="disable" class="col-6">Metodo de pago</label>
								
								<select id="metodo_pago" v-model="pay_method" placeholder="Metodo de pago" class="col-6 form-control m-input">
									<option>Seleccione una opcion</option>
									<option value="1">Depósito o AHC</option>
									<option value="2">Slip Bancario</option>
								</select>
							</div>
						</div>
			
			
						<div class="col-5">
							<div class="row input-group m-input-group justify-content-end">
								<label for="monto_depositado" class="col-6">Monto Depositado</label>
								<input autocomplete="off" type="text" :disabled="type_asignation == 1 || type_asignation == 2" id="monto_depositado" v-model="amount_deposited" @blur="upPending()" placeholder="Monto depositado" class="col-6 form-control m-input">
							</div>
			
							<br>
			
							<div class="row input-group m-input-group justify-content-end">
								<label for="pendiente_asignar" disable="disable" class="col-6">Pendiente de asignar</label>
								<input autocomplete="off" type="text" disabled id="pendiente_asignar" v-model="pending_assign" placeholder="Pendiente de asignar" class="col-6 form-control m-input">
							</div>
			
							<br>
						</div>
					</form>
					<!--FIN FORMULARIO DE AGREGAR PAGO ===============================================================================-->
					<!--==============================================================================================================-->



					<!--ERRORES DE VALIDACION ========================================================================================-->
					<!--==============================================================================================================-->
	                <div v-show="error" class="form-group row div-error">
	                    <div class="text-center text-error" style="margin:0 auto">
	                        <div class="text-danger" v-for="error in errors" :key="error" v-text="error">
	                            
	                        </div>
	                    </div>
	                </div>
	                <!--FIN ERRORES DE VALIDACION ====================================================================================-->
					<!--==============================================================================================================-->
					
			
					
					<!--EN ESTA PARTE SE DEBEN POSICIONAR LOS ESTUDIANTES ============================================================-->
					<!--==============================================================================================================-->
					<div 
						style="border-top: 1px solid #CCC"
						v-for="(student, index) in students">
						
						<div class="m-portlet__head">

							<div class="m-portlet__head-caption">
								<div class="m-portlet__head-title">
									<h3 
										v-if="student.contracts[0] != undefined"
										class="m-portlet__head-text"
										v-text="'Estudiante: ' + student.name + ' | ' + student.contracts[0].enrollment_bachelor + ' ' + student.contracts[0].enrollment_grade">
									</h3>

									<h3 
										v-if="student.contracts[0] == undefined"
										class="m-portlet__head-text"
										v-text="'Estudiante: ' + student.name">
									</h3>
								</div>
							</div>

							<div class="m-portlet__head-tools">
								<ul class="m-portlet__nav">
									<li class="m-portlet__nav-item">
										
										<button
											v-if="type_asignation != 0 && pending_assign > 0"
											type="button" 
											class="btn btn-primary btn-sm mr-1" 
											@click="reembolso()">
						                    	<span><i class="fa fa-plus"></i><span> Reembolso</span></span>
						                </button>

						                <button
						                	v-if="type_asignation == 0"
						                	type="button" 
						                	class="btn btn-client text-white btn-sm mr-1" 
						                	@click="getActiveServices(index)">
						                    	<span><i class="fa fa-plus"></i><span> Servicios</span></span>
						                </button>

						                <button 
						                	type="button" 
						                	class="btn btn-danger btn-sm mr-1" 
						                	@click="activeRemove(index)"
						                	v-if="type_asignation == 0">
						                    	<span><i class="fa fa-minus"></i><span> Remover</span></span>
						                </button>
									</li>
								</ul>

								<button
									v-if="type_asignation == 0"
									type="button" 
									class="btn btn-danger btn-sm" 
									title="Quitar estudiante" 
									data-dismiss="alert" 
									aria-label="Close" 
									@click="deleteStudent(index)">
										<span aria-hidden="true">&times;</span>
								</button>

							</div>
						</div>
									
						<div style="padding: 30px 5px;">
							
							<!--TABLA DE ESTUDIANTES ACTIVOS Y SU RESPECTIVO CONTRATO==================================================-->
							<!--=======================================================================================================-->
							<table 
								class="table table-striped- table-responsive table-bordered table-hover table-checkable" 
								id="m_table_1">
								
								<!--ENCABEZADO-->
								<thead>
									<tr>
				                        <th>Descripción</th>
										<th>Total</th>
										<th>Pago</th>
										<template v-if="datos[index].contracts[0] != undefined">
											<th v-if="	datos[index].contracts[0].fees[index2].status != 2 ||
					                        			datos[index].contracts[0].fees[index2].r15_status == 1 ||
					                        			datos[index].contracts[0].fees[index2].r1_status == 1"
					                        	v-for="(fee, index2) in student.contracts[0].fees" 
												v-text="'Cuota ' + fee.order">
											</th>
										</template>
									</tr>
								</thead>
								
								<!--CUERPO-->
								<tbody>
									
									<template v-if="datos[index].contracts[0] != undefined">
										<!--SERVICIOS OBLIGATORIOS-->
										<tr 
											v-for="(contract_service, index2) in student.contracts[0].contract_services"
											v-if="datos[index].contracts[0].contract_services[index2].cost !=  
													datos[index].contracts[0].contract_services[index2].paid_out">
											
											<td v-text="contract_service.description"></td>
													                        <td v-text="(parseFloat(contract_service.cost)-parseFloat(datos[index].contracts[0].contract_services[index2].paid_out)).toFixed(2)"></td>
													                        <td>
													                        	
													                        	<input 
													                        		type="number"
													                        		style="width: 50px;" 
													                        		v-model="contract_service.paid_out"
													                        		:disabled="type_asignation != 2"
													                        		@click="reverseValueAssignament({
														'ref': 'contract_service',
														'dato': datos[index].contracts[0].contract_services[index2],
														'value': contract_service
													                        		})"
													                        		@change="manualAssingment({
													                        			'ref': 'contract_service',
														'dato': datos[index].contracts[0].contract_services[index2],
														'value': contract_service
													                        		})">
													                        </td>
													                        <td 
													                        	v-for="(fee, index3) in student.contracts[0].fees"
													                        	v-if="datos[index].contracts[0].fees[index3].status != 2 ||
													                        			datos[index].contracts[0].fees[index3].r15_status == 1 ||
													                        			datos[index].contracts[0].fees[index3].r1_status == 1">
													                        </td>
										</tr>
										
										<!--MATRICULA-->
										<tr v-if="datos[index].contracts[0].enrollment_cost != datos[index].contracts[0].paid_out">
											<td>Matricula</td>
											<td v-text="(parseFloat(student.contracts[0].enrollment_cost) - parseFloat(datos[index].contracts[0].paid_out)).toFixed(2)"></td>
											<td>
												<input 
													style="width: 50px"
													type="text" 
													v-model="student.contracts[0].paid_out" 
													:disabled="type_asignation != 2"
													@click="reverseValueAssignament({
														'ref': 'enrollment',
														'dato': datos[index].contracts[0],
														'value': student.contracts[0]
													                        		})"
													@change="manualAssingment({
													                        			'ref': 'enrollment',
														'dato': datos[index].contracts[0],
														'value': student.contracts[0]
													                        		})">
											</td>
											<td 
												v-for="(fee, index2) in student.contracts[0].fees"
												v-if="datos[index].contracts[0].fees[index2].status != 2 ||
													                        			datos[index].contracts[0].fees[index2].r15_status == 1 ||
													                        			datos[index].contracts[0].fees[index2].r1_status == 1">
											</td>
										</tr>
									</template>
									
									<!--SERVICIOS (PAGOS EXTRAS)-->
									<tr v-for="(extra_payment, index2) in student.extra_payments"
										v-if="datos[index].extra_payments[index2].cost != datos[index].extra_payments[index2].paid_out">
										<td v-text="extra_payment.description"></td>
										<td v-text="extra_payment.cost"></td>
										<td>
											<input 
												style="width: 50px;"
												type="text" 
												v-model="extra_payment.paid_out" 
												:disabled="type_asignation != 2"
												@click="reverseValueAssignament({
													'ref': 'extra_payment',
													'dato': datos[index].extra_payments[index2],
													'value': extra_payment
				                        		})"
												@change="manualAssingment({
				                        			'ref': 'extra_payment',
													'dato': datos[index].extra_payments[index2],
													'value': extra_payment
				                        		})">
										</td>
										<template v-if="datos[index].contracts[0] != undefined">
											<td v-if="datos[index].contracts[0].fees[index2].status != 2 ||
					                        			datos[index].contracts[0].fees[index2].r15_status == 1 ||
					                        			datos[index].contracts[0].fees[index2].r1_status == 1"
					                        	v-for="(fee, index3) in student.contracts[0].fees">
											</td>
										</template>
										<td v-if="student_select == index && remove_button">
											<button class="btn btn-danger btn-sm" @click="deleteExtraPayment(index2)"><i class="fa fa-minus"></i></button>
										</td>
									</tr>
									
									<template v-if="datos[index].contracts[0] != undefined">
										<!--RECARGO DEL 15%-->
										{{-- <tr v-if="datos[index].contracts[0].r15_paid_out != datos[index].contracts[0].r15_total"> --}}
										<tr v-if="isR15(datos[index].contracts[0])">
											<td v-text="'Recargo 15%'"></td>
											<td v-text="(datos[index].contracts[0].r15_total - datos[index].contracts[0].r15_paid_out).toFixed(2)"></td>
											<td>
												<input 
													style="width: 50px"
													type="text"
													v-model = "student.contracts[0].r15_paid_out"
													:disabled="type_asignation != 2"
													@click="reverseValueAssignament({
														'ref': 'r15',
														'dato': datos[index].contracts[0],
														'value': student.contracts[0],
													})"
													@change="manualAssingment({
														'ref': 'r15',
														'dato': datos[index].contracts[0],
														'value': student.contracts[0],
													})
													">
											</td>
											<td 
												v-for="(fee,index2) in student.contracts[0].fees"
												v-if="datos[index].contracts[0].fees[index2].status != 2 ||
													                        			datos[index].contracts[0].fees[index2].r15_status == 1 ||
													                        			datos[index].contracts[0].fees[index2].r1_status == 1">
													
													<span
														v-if="datos[index].contracts[0].fees[index2].r15_status == 1"
														v-text="fee.r15_paid_out" 
														:style="fee.r15_status == 3 ? 'text-decoration:line-through' : '' ">
													</span>
											</td>
											<td v-if="student_select == index && remove_button">
												<button 
													class="btn btn-danger btn-sm" 
													@click="modalR15(index)">
														<i class="fa fa-minus"></i>
												</button>
											</td>
										</tr>
										
										<!--RECARGO DEL 1%-->
										{{-- <tr v-if="datos[index].contracts[0].r1_paid_out != datos[index].contracts[0].r1_total"> --}}
										<tr v-if="isR1(datos[index].contracts[0])">
											<td v-text="'Recargo 1%'"></td>
											<td v-text="datos[index].contracts[0].r1_total - datos[index].contracts[0].r1_paid_out"></td>
											<td><input 
													style="width: 50px"
													type="text"
													v-model="student.contracts[0].r1_paid_out"
													:disabled="type_asignation != 2"
													@click="reverseValueAssignament({
														'ref': 'r1',
														'dato': datos[index].contracts[0],
														'value': student.contracts[0],
													})"
													@change="manualAssingment({
														'ref': 'r1',
														'dato': datos[index].contracts[0],
														'value': student.contracts[0],
													})
													">
											</td>
											<td 
												v-for="(fee, index2) in student.contracts[0].fees"
												v-if="datos[index].contracts[0].fees[index2].status != 2 ||
				                        			datos[index].contracts[0].fees[index2].r15_status == 1 ||
				                        			datos[index].contracts[0].fees[index2].r1_status == 1">
												
													<span
														v-if="datos[index].contracts[0].fees[index2].r1_status == 1"
														v-text="fee.r1_paid_out" 
														:style="fee.r1_status == 3 ? 'text-decoration:line-through' : '' ">
													</span>
											</td>
											<td v-if="student_select == index && remove_button">
												<button 
													class="btn btn-danger btn-sm" 
													@click="modalR1(index)">
														<i class="fa fa-minus"></i>
												</button>
											</td>
										</tr>
										
										<!--ANUALIDAD (CUOTAS)-->
										<tr v-if="datos[index].contracts[0].annuity_paid_out != datos[index].contracts[0].annuity_cost">
											<td v-text="student.contracts[0].enrollment_bachelor + ' ' + student.contracts[0].enrollment_grade"></td>
											<td v-text="(parseFloat(datos[index].contracts[0].annuity_cost) - parseFloat(datos[index].contracts[0].annuity_paid_out)).toFixed(2)"></td>
											<td>
												<input 
													style="width: 50px"
													type="text" 
													v-model="student.contracts[0].annuity_paid_out" 
													:disabled="type_asignation != 2"
													@click="reverseValueAssignament({
					                        			'ref': 'annuity',
														'dato': datos[index].contracts[0],
														'value': student.contracts[0]
					                        		})"
													@change="manualAssingment({
													    'ref': 'annuity',
														'dato': datos[index].contracts[0],
														'value': student.contracts[0]
						                    		})">
											</td>
											<td 
												v-for="(fee, index2) in student.contracts[0].fees"
												v-if="datos[index].contracts[0].fees[index2].status != 2 ||
				                        			datos[index].contracts[0].fees[index2].r15_status == 1 ||
				                        			datos[index].contracts[0].fees[index2].r1_status == 1">
				
												<span 
													v-text="fee.paid_out"
													v-if="datos[index].contracts[0].fees[index2].status != 2">
												</span>
											</td>
										</tr>
									</template>

									<!--REEMBOLSO-->
									<tr v-if="refund != 0">
										<td>Reembolso</td>
										<td v-text="refund"></td>
									</tr>
								</tbody>
							</table>
							<!--FIN TABLA DE ESTUDIANTES ACTIVOS Y SU RESPECTIVO CONTRATO==================================================-->
							<!--=======================================================================================================-->
						</div>
					</div>
					<!--FIN POSICIONAR ESTUDIANTES ====================================================================================-->
					<!--===============================================================================================================-->
				</div>
			</div>		
		</div>
		<!--FIN ENCABEZADO DE PAGINA ====================================================================================-->			
		<!--=============================================================================================================-->
		


		<!--MODAL AGREGAR SERVICIO ==========================================================================================-->
		<!--=======================================================================================================-->
		<div class="modal fade" id="m_modal_1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel" v-text="'Agregar Servicio'"></h5>
						<button type="button" @click="closeModal()" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						
						<table class="col-12 table table-striped m-table">
							<thead>
								<tr>
									<th></th>
									<th>Descripción</th>
									<th>Total</th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="(active_service, index) in active_services">
									<td>
										<input type="checkbox" :value="active_service" v-model="extra_payments">
									</td>
									<td v-text="active_service.description"></td>
									<td v-text="active_service.cost"></td>
								</tr>
							</tbody>
						</table>
						
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-primary" @click="addExtraPayments()">Guardar</button>
						<button type="button" class="btn btn-secondary" @click="closeModal" data-dismiss="modal">Cancelar</button>
					</div>
				</div>
			</div>
		</div>
		<!--=======================================================================================================-->
		<!--CERRAR MODAL PARA SELECCIONAR AÑO =====================================================================-->



		<!--MODAL REEMBOLSO ==========================================================================================-->
		<!--=======================================================================================================-->
		<!--<div class="modal fade" id="m_modal_2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel" v-text="'Reembolso'"></h5>
						<button type="button" @click="closeModal" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<label class="label-control" for="comentario">Comentario de reembolso</label>
						<div class="row">
							<textarea v-model="comment_refund" class="form-control" id="comentario" style="width: 100%"></textarea>
						</div>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-primary" @click="reembolso()">Aceptar</button>
						<button type="button" class="btn btn-secondary" data-dismiss="modal" @click="closeModal()">Cancelar</button>
					</div>
				</div>
			</div>
		</div>-->
		<!--=======================================================================================================-->
		<!--CERRAR MODAL PARA REALIZAR REEMBOLSO =====================================================================-->



		<!--MODAL CANCELAR RECARGO R15===============================================================================-->
		<!--=======================================================================================================-->
		<div class="modal fade" id="m_modal_3" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel" v-text="'Cancelacion de recargo'"></h5>
						<button type="button" @click="closeModal" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<label class="label-control" for="m_table_fee_columns_r15">cancelacion del recargo</label>
						<table 
								class="table table-striped- table-responsive table-bordered table-hover table-checkable" 
								id="m_table_fee_columns_r15">
								<!-- This is for eastern -->
								<template  v-if="student_select != undefined &&
										datos != undefined &&
										datos.length > student_select && 
										datos[student_select].contracts != undefined &&
										r15_fee_data.length > 0
										">
								<thead>
									<tr>
										
										<th v-for="(fee, index) in datos[student_select].contracts[0].fees" 
											v-if="fee.r15_status == 1"
										    v-text="'Cuota ' + fee.order"></th>
										
									</tr>
								</thead>
								<tbody>
									<tr>
										<td v-for="(fee, index) in datos[student_select].contracts[0].fees"
										v-if="fee.r15_status == 1"
										>
											<span v-text="r15_fee_data[student_select].contracts[0].fees[index].r15"
											:style="r15_fee_data[student_select].contracts[0].fees[index].r15_status == 3 ? 'text-decoration:line-through' : '' "></span>
											<button class="btn btn-danger btn-sm" v-on:click="removefee_r15( index )"
											 >
												<i class="fa fa-minus" v-if="r15_fee_data[student_select].contracts[0].fees[index].r15_status == 1"></i>
												<i class="fa fa-ban" v-if="r15_fee_data[student_select].contracts[0].fees[index].r15_status == 3" ></i>
											</button>
										</td>

										
									</tr>
								</tbody>
								</template>
						</table>
						<label class="label-control" for="comentario_r15">Comentario</label>
						<div class="row">
							<textarea
								v-model="comment_cancel_r15" 
								class="form-control" 
								id="comentario_r15" 
								style="width: 100%"
								placeholder="Motivo de cancelacion del recargo">
							</textarea>
						</div>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-primary" @click="removeR15()">Aceptar</button>
						<button type="button" class="btn btn-secondary" data-dismiss="modal" @click="closeModal()">Cancelar</button>
					</div>
				</div>
			</div>
		</div>
		<!--=======================================================================================================-->
		<!--CERRAR MODAL PARA CANCELAR R15 =====================================================================-->



		<!--MODAL CANCELAR RECARGO R1===============================================================================-->
		<!--=======================================================================================================-->
		<div class="modal fade" id="m_modal_4" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel" v-text="'Cancelacion de recargo'"></h5>
						<button type="button" @click="closeModal" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<label class="label-control" for="m_table_fee_columns_r1">cancelacion del recargo</label>
						<table 
								class="table table-striped- table-responsive table-bordered table-hover table-checkable" 
								id="m_table_fee_columns_r1">
								<!-- This is for eastern -->
								<template  v-if="student_select != undefined &&
										datos != undefined &&
										datos.length > student_select && 
										datos[student_select].contracts != undefined &&
										r1_fee_data.length > 0
										">
								<thead>
									<tr>
										
										<th v-for="(fee, index) in datos[student_select].contracts[0].fees" 
											v-if="fee.r1_status == 1"
										    v-text="'Cuota ' + fee.order"></th>
										
									</tr>
								</thead>
								<tbody>
									<tr>
										<td v-for="(fee, index) in datos[student_select].contracts[0].fees"
										v-if="fee.r1_status == 1"
										>
											<span v-text="r1_fee_data[student_select].contracts[0].fees[index].r1"
											:style="r1_fee_data[student_select].contracts[0].fees[index].r1_status == 3 ? 'text-decoration:line-through' : '' "></span>
											<button class="btn btn-danger btn-sm" v-on:click="removefee_r1( index )"
											 >
												<i class="fa fa-minus" v-if="r1_fee_data[student_select].contracts[0].fees[index].r1_status == 1"></i>
												<i class="fa fa-ban" v-if="r1_fee_data[student_select].contracts[0].fees[index].r1_status == 3" ></i>
											</button>
										</td>

										
									</tr>
								</tbody>
								</template>
						</table>

						<label class="label-control" for="comentario_r1">Comentario</label>
						<div class="row">
							<textarea 
								v-model="comment_cancel_r1"
								class="form-control" 
								id="comentario_r1" 
								style="width: 100%"
								placeholder="Motivo de cancelacion del recargo">
							</textarea>
						</div>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-primary" @click="removeR1()">Aceptar</button>
						<button type="button" class="btn btn-secondary" data-dismiss="modal" @click="closeModal()">Cancelar</button>
					</div>
				</div>
			</div>
		</div>
		<!--=======================================================================================================-->
		<!--CERRAR MODAL PARA CANCELAR R1 =====================================================================-->
	@endsection


	@section('scripts')	 
		
		<script src="{{asset('metronic/assets3/vendors/base/vendors.bundle.js')}}" type="text/javascript"></script>
	    <script src="{{asset('metronic/assets3/demo/default/base/scripts.bundle.js')}}" type="text/javascript"></script>
	    <script src="{{asset('metronic/assets3/demo/default/custom/crud/forms/widgets/bootstrap-datepicker.js')}}" type="text/javascript"></script>

		<script type="text/javascript">

			const app = new Vue({
				
				el: '#app',

				data:{

					super_admin: true,//VERIFICA QUE EL USUARIO ES SUPER ADMINISTRADOR
					
					//automatic_asignation: false,
					//interval: false,
					//count: 0,


					//BANDERAS DE ASIGNACION
					//manual_asignation: false,
					//auto_asignation: false,
					type_asignation: 0,


					search: '',//PERMITE REALIZAR BUSQUEDA DEL ESTUDIANTE EN EL SERVER

					operation_number: 0,//CAMPO DE FORMULARIO 
					amount_deposited: 0,//CAMPO DE FORMULARIO
					pending_assign: 0,//CAMPO DE FORMULARIO
					deposit_date: '',//CAMPO DE FORMULARIO
					refund: 0,//CAMPO DE FORMULARIO
					pay_method: '',
					//comment_refund: '',//REEMBOLSO


					comment_cancel_r15: '',
					comment_cancel_r1: '',

					active_datapicker: 0,//ACTIVAR DATAPICKER AL CARGAR PAGINA

					students: [],//ALMACENA DATOS DE ESTUDIANTES PARA SER MODIFICADOS
					aux: 0,

					datos: [],//ALMACENA DATOS DE ESTUDIANTES DIRECTO DEL SERVER

					//students_aux: [],
					student_select: 0, //ESTUDIANTE SELECCIONADO

					active_services: [],//ALMACENA LOS SERVICIOS ACTIVOS DIRECTO DEL SERVIDOR

					to_year: 0,

					selected: 0,//ESTUDIANTE SELECCIONADO
					options: [],

					extra_payments: [],//ALMACENA LOS SERVICIOS ACTIVOS QUE SE ANEXARAN A UN ESTUDIANTE
					remove_button: false,//ACTIVAR O DESACTIVAR EL BOTON DE REMOVER

					students_pay: [],//ALMACENA LO QUE SE VA A PAGAR
					info_str: '',

					save_active: true,//DESACTIVAR BOTON DE GUARDAR

					error: 0,//INDICA SI HAY ERRORES DE VALIDACION
					errors: [],//ALMACENA LOS ERRORES DE VALIDACION

					r15_fee_data: [],//DATATABLE FOR FEE
					r1_fee_data: [],//DATATABLE FOR FEE
				},

				computed: {

				},

				methods:{

					//REALIZAR TODAS LAS VALIDACIONES
					checkForm(){

						let me = this;

		                this.error = 0;
		                this.errors = [];	                
		                
		                //Validacion de numero de operacion
		                if (!this.operation_number) this.errors.push("Número de operación requerido");

		                //Validacion de telefono
		                if (this.amount_deposited == 0) this.errors.push("Monto de depósito requerido");
		                if (!this.validateNumber(this.amount_deposited)) this.errors.push("Monto de deposito debe ser numerico")
		                
		                //Acudiente requerido
		                if (this.pending_assign != 0) this.errors.push("Cantidad pendiente por asignar debe ser cero");
		                
		                //Validacion de fecha de deposito
		                if (!me.deposit_date) this.errors.push("Fecha de deposito requerida");

		                if(me.students.length == 0) this.errors.push("No ha agregado ningun estudiante al pago");

		                if(me.pay_method == '') this.errors.push('No ha seleccionado el metodo de pago');

		                this.validateRegistre();

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

		            //VALIDAR REGISTRO
		            validateRegistre(){

		            	let me = this;

		            	for(i=0; i < me.students.length; i++){
		            		
		            		//PREGUNTAR SI SE REALIZARON CAMBIOS EN LOS PAGOS DEL ESTUDIANTE
		            		if(JSON.stringify(me.students[i]) == JSON.stringify(me.datos[i])){
		            			me.errors.push("No realizo ningun cambio en el estudiante " + me.students[i].name);
		            			continue;
		            		}

		            		/*if(parseFloat(me.students[i].contracts[0].enrollment_cost) > parseFloat(me.students[i].contracts[0].paid_out)){
		            			me.errors.push(me.students[i].name + " - El pago completo de la matricula es obligatorio");
		            		}*/

		            		/*for(j=0; j < me.students[i].contracts[0].contract_services.length; j++){
		            			if(parseFloat(me.students[i].contracts[0].contract_services[j].cost) > parseFloat(me.students[i].contracts[0].contract_services[j].paid_out)){
		            				me.errors.push(me.students[i].name + ' - El pago del servicio "' + 
		            								me.students[i].contracts[0].contract_services[j].description + '" es obligatorio');
		            			}
		            		}*/

		            		for(j=0; j < me.students[i].extra_payments.length; j++){
		            			if(parseFloat(me.students[i].extra_payments[j].cost) > parseFloat(me.students[i].extra_payments[j].paid_out)){
		            				me.errors.push(me.students[i].name + ' - El pago completo del servicio "' +
		            							me.students[i].extra_payments[j].description + '" es obligatorio');
		            			}
		            		}

		            		/*if(parseFloat(me.students[i].contracts[0].fees[0].cost) > parseFloat(me.students[i].contracts[0].fees[0].paid_out)){
		            			me.errors.push(me.students[i].name + ' - El pago completo de la "cuota 1" es obligatorio');
		            		}*/
		            	}
		            },

		            //REALIZA LA BUSQUEDA DE LOS ESTUIDANTES DISPONIBLES PARA AGREGAR A TRAVES DEL INPUT
					getOptions(){
						let me = this;

						axios
							.get('/pagos/create/getStudents?buscar=' + me.search)
							.then(function(response){
								var respuesta = response.data;
								me.options = respuesta.students;
								console.log(me.options);
							}).catch(function(error){
								console.log(error);
							});
					},

					upPending(){

						this.pending_assign = this.amount_deposited;
					},

					//AGREGAR UN ESTUDIANTE LUEGO DE HABER REALIZADO LA BUSQUEDA
					addStudent(){

						let me = this;

						me.remove_button = false;

						let url = '/pagos/create/addStudent/' + me.selected;

						for(i=0; i<me.students.length; i++){
							if(me.students[i].id == me.selected){
								alert('El estudiante ya fue agregado');
								return 0;
							}
						}

						axios
							.get(url)
							.then(function(response){
								let respuesta = response.data;
								me.datos.push(JSON.parse(JSON.stringify(respuesta.student)));//DATOS QUE NO SE MODIFICARAN
								
								console.log(respuesta.student);

								if(respuesta.student.contracts[0] != undefined){
									respuesta.student.contracts[0].paid_out = (0).toFixed(2);

									for(let contract_service of respuesta.student.contracts[0].contract_services){
										contract_service.paid_out = (0).toFixed(2);
									}

									

									respuesta.student.contracts[0].annuity_paid_out = (0).toFixed(2);
									respuesta.student.contracts[0].r15_paid_out = (0).toFixed(2);
									respuesta.student.contracts[0].r1_paid_out = (0).toFixed(2);

									for(i=0; i < respuesta.student.contracts[0].fees.length; i++){
										if(respuesta.student.contracts[0].fees[i].status != 2){
											respuesta.student.contracts[0].fees[i].paid_out = (0).toFixed(2);
										}

										if(respuesta.student.contracts[0].fees[i].r15_status == 1){
											respuesta.student.contracts[0].fees[i].r15_paid_out = (0).toFixed(2);
										}

										if(respuesta.student.contracts[0].fees[i].r1_status == 1){
											respuesta.student.contracts[0].fees[i].r1_paid_out = (0).toFixed(2);
										}

										console.log(respuesta.student.contracts[0].fees[i].paid_out);
									}
								}

								me.students.push(JSON.parse(JSON.stringify(respuesta.student)));//DATOS QUE SON POSIBLES DE MODIFICAR
								me.search = '';
								console.log(me.students);
								console.log(me.datos);
							}).catch(function(error){
								console.log(error);
							});
					},


					//TRAER LOS SERVICIOS ACTIVOS DISPONIBLES DESDE EL SERVER
					getActiveServices(indice){

						let me = this;

						me.remove_button = false;

						axios
							.get('/pagos/create/getActivesServices/' + me.students[indice].id)
							.then(function(response){
								
								let respuesta = response.data;

								me.active_services = respuesta.active_services;
								me.to_year = respuesta.to_year;
								me.student_select = indice;
								console.log(me.active_services);

								/*for(i=0; i < me.active_services.length; i++){
																	
									for(j=0; j < me.students[me.student_select].extra_payments.length; j++){
										if(me.active_services[i].id == me.students[me.student_select].extra_payments[j].service_id && me.to_year == me.students[me.student_select].extra_payments[j].year){
												me.active_services.splice(i,1);
										}
									}
								}*/
								
								me.openModal(1);
							}).catch(function(error){
								console.log("ERROR");
							});
					},


					//ELIMINAR ESTUDIANTE PREVIAMENTE AGREGADO
					deleteStudent(index){

						if(!confirm('Seguro que desea no realizar cambios en el estudiante seleccionado')) return 0;

						let me = this;

						me.students.splice(index, 1);
						me.datos.splice(index, 1);
					},


					//ALMACENAR SERVICIOS ACTIVO A UN ESTUDIANTE ESPECIFICO
					addExtraPayments(){

						console.log(this.extra_payments);

						let me = this;

						console.log(me.extra_payments.length);

						for(i=0; i < me.extra_payments.length; i++){
							
							var extra_payment = {
								'description': me.extra_payments[i].description,
								'cost': parseFloat(me.extra_payments[i].cost).toFixed(2),
								'paid_out': (0).toFixed(2),
								'receipt': '[]',
								'state': me.extra_payments[i].state,
								'year': me.to_year,
								'student_id': me.students[me.student_select].id,
								'service_id': me.extra_payments[i].id,
							}
							console.log("AQUI");

							me.students[me.student_select].extra_payments.push(JSON.parse(JSON.stringify(extra_payment)));
							me.datos[me.student_select].extra_payments.push(JSON.parse(JSON.stringify(extra_payment)));
						}

						me.closeModal();
					},


					//REGISTRAR LOS CAMBIOS REALIZADOS A LOS ESTUDIANTES (PAGO DE CUOTAS Y OTROS)
					registre(){

						if(this.checkForm()) return;

						let me = this;

						me.formulateArrayPayments();

						me.save_active = false;

						let url = '/pagos/store';

						axios
							.post(url,{
								'students': me.students,
								'deposit_at': me.deposit_date,
								'operation_number': me.operation_number,
								'refund': me.refund,
								'amount': me.amount_deposited,
								'c_r1': me.comment_cancel_r1,
								'c_r15': me.comment_cancel_r15,
								//'comment_cancel_r15:': me.comment_cancel_r15,
								//'comment_cancel_r1:': me.comment_cancel_r1,
								'info_str': me.info_str,
								'pay_method': me.pay_method,
								
							})
							.then(function(response){
								console.log(response.data.result);
								location.href = '/pagos/';
							})
							.catch(function(error){
								/*me.errors.push('El numero de operacion utilizado ya existe');
								me.error = 1;
								me.save_active = true;
								me.info_str = '';
								me.students_pay = [];
								me.save_active = true;*/
								me.save_active = true;

								for(let err of Object.keys(error.response.data.errors)){
									me.errors.push(error.response.data.errors[err][0]);
									me.error = 1;
								}

								me.info_str = '';
								//console.log(error);
							});
					},


					//BORRAR PAGOS EXTRAS (SERVICIOS ACTIVOS) A UN ESTUDIANTE ESPECIFICO
					deleteExtraPayment(indice){

						let me = this;

						me.students[me.student_select].extra_payments.splice(indice,1);
						me.datos[me.student_select].extra_payments.splice(indice,1);
					},


					//REALIZAR ASIGNACION AUTOMATICA DEL DINERO DEPOSITADO, A LOs ESTUDIANTES
					selfAssignment(){

						let me = this;

						if(me.amount_deposited == 0){
							alert("No es posible autoasignar con un monto de deposito de 0");
							return;
						}
						
						let aux = 0;
						
						me.pending_assign = me.amount_deposited;
						me.type_asignation = 1; //AUTOASIGNACION SELECCIONADA


						//AUTOASIGNACION DE MATRICULA
						for(i=0; i < me.students.length; i++){
							aux = 0;


							if(me.students[i].contracts[0] != undefined){//VERIFICA QUE UN ESTUDIANTE TIENE CONTRATO

								if(parseFloat(me.students[i].contracts[0].enrollment_cost) > parseFloat(me.datos[i].contracts[0].paid_out)){
									if(parseFloat(me.pending_assign) > parseFloat(me.datos[i].contracts[0].enrollment_cost) - parseFloat(me.datos[i].contracts[0].paid_out)){

										//CALCULOS DEL ESTUDIANTE
										//aux = parseFloat(me.students[i].contracts[0].paid_out);

										me.students[i].contracts[0].paid_out = (parseFloat(me.datos[i].contracts[0].enrollment_cost) - parseFloat(me.datos[i].contracts[0].paid_out)).toFixed(2);

										me.pending_assign = (parseFloat(me.pending_assign) - (parseFloat(me.datos[i].contracts[0].enrollment_cost) - parseFloat(me.datos[i].contracts[0].paid_out))).toFixed(2);
									}
									else{

										//CALCULOS DEL ESTUDIANTE
										me.students[i].contracts[0].paid_out = parseFloat(me.pending_assign).toFixed(2);

										me.pending_assign = (0).toFixed(2);

										return 0;
									}
								}
							}
						}					


						//AUTOASIGNACION DE SERVICIOS OBLIGATORIOS
						for(i=0; i < me.students.length; i++){

							if(me.students[i].contracts[0] != undefined){//VERIFICA QUE UN ESTUDIANTE TIENE CONTRATO
								for(j=0; j < me.students[i].contracts[0].contract_services.length; j++){
									
									aux = 0;

									if(parseFloat(me.students[i].contracts[0].contract_services[j].cost) > parseFloat(me.datos[i].contracts[0].contract_services[j].paid_out)){
										if(parseFloat(me.pending_assign) > (parseFloat(me.datos[i].contracts[0].contract_services[j].cost) - parseFloat(me.datos[i].contracts[0].contract_services[j].paid_out))){

											//CALCULOS DEL ESTUDIANTE
											//aux = parseFloat(me.students[i].contracts[0].contract_services[j].paid_out);

											me.students[i].contracts[0].contract_services[j].paid_out = (parseFloat(me.datos[i].contracts[0].contract_services[j].cost) - parseFloat(me.datos[i].contracts[0].contract_services[j].paid_out)).toFixed(2);

											me.pending_assign = (parseFloat(me.pending_assign) - (parseFloat(me.datos[i].contracts[0].contract_services[j].cost) - parseFloat(me.datos[i].contracts[0].contract_services[j].paid_out))).toFixed(2);
										}
										else{

											//CALCULOS DEL ESTUDIANTE
											me.students[i].contracts[0].contract_services[j].paid_out = parseFloat(me.pending_assign).toFixed(2);

											me.pending_assign = 0.00;

											return 0;
										}
									}
								}
							}
						}


						//AUTOASIGNACION DE RECARGOS
						for(j=0; j < 11; j++){

							for(i=0; i < me.students.length; i++){

								if(me.students[i].contracts[0] != undefined){//VERIFICA QUE UN ESTUDIANTE TIENE CONTRATO


									if(me.students[i].contracts[0].fees[j].r15_status == 1){
										if(parseFloat(me.pending_assign) >= (parseFloat(me.students[i].contracts[0].fees[j].r15) - parseFloat(me.datos[i].contracts[0].fees[j].r15_paid_out))){

											//CALCULOS DEL ESTUDIANTE
											me.students[i].contracts[0].fees[j].r15_paid_out = (parseFloat(me.students[i].contracts[0].fees[j].r15) - parseFloat(me.datos[i].contracts[0].fees[j].r15_paid_out)).toFixed(2);

											me.pending_assign = (parseFloat(me.pending_assign) - parseFloat(me.students[i].contracts[0].fees[j].r15_paid_out)).toFixed(2);

											me.students[i].contracts[0].r15_paid_out = (parseFloat(me.students[i].contracts[0].r15_paid_out) + parseFloat(me.students[i].contracts[0].fees[j].r15_paid_out)).toFixed(2);
										}
										else{

											//CALCULOS DEL ESTUDIANTE
											me.students[i].contracts[0].fees[j].r15_paid_out = parseFloat(me.pending_assign).toFixed(2);

											me.students[i].contracts[0].r15_paid_out = (parseFloat(me.students[i].contracts[0].r15_paid_out) + parseFloat(me.pending_assign)).toFixed(2);

											me.pending_assign = 0.00;

											return 0;
										}
									}

									if(me.students[i].contracts[0].fees[j].r1_status == 1){
										if(parseFloat(me.pending_assign) >= (parseFloat(me.students[i].contracts[0].fees[j].r1) - parseFloat(me.datos[i].contracts[0].fees[j].r1_paid_out))){

											//CALCULOS DEL ESTUDIANTE
											me.students[i].contracts[0].fees[j].r1_paid_out = (parseFloat(me.students[i].contracts[0].fees[j].r1) - parseFloat(me.datos[i].contracts[0].fees[j].r1_paid_out)).toFixed(2);

											me.pending_assign = (parseFloat(me.pending_assign) - parseFloat(me.students[i].contracts[0].fees[j].r1_paid_out)).toFixed(2);

											me.students[i].contracts[0].r1_paid_out = (parseFloat(me.students[i].contracts[0].r1_paid_out) + parseFloat(me.students[i].contracts[0].fees[j].r1_paid_out)).toFixed(2);
										}
										else{

											//CALCULOS DEL ESTUDIANTE
											me.students[i].contracts[0].fees[j].r1_paid_out = parseFloat(me.pending_assign).toFixed(2);

											me.students[i].contracts[0].r1_paid_out = (parseFloat(me.students[i].contracts[0].r1_paid_out) + parseFloat(me.pending_assign)).toFixed(2);

											me.pending_assign = 0.00;

											return 0;
										}
									}
								}
							}
						}


						//AUTOASIGNACION DE CUOTAS VENCIDAS
						for(j = 0; j < 11; j++){

							for(i=0; i < me.students.length; i++){

								if(me.students[i].contracts[0] != undefined){//VERIFICA QUE UN ESTUDIANTE TIENE CONTRATO
									if(me.students[i].contracts[0].fees[j].status == 0){

										if(parseFloat(me.pending_assign) > (parseFloat(me.students[i].contracts[0].fees[j].cost) - parseFloat(me.datos[i].contracts[0].fees[j].paid_out))){

											//CALCULOS DEL ESTUDIANTE
											me.students[i].contracts[0].fees[j].paid_out = (parseFloat(me.students[i].contracts[0].fees[j].cost) - parseFloat(me.datos[i].contracts[0].fees[j].paid_out)).toFixed(2);

											me.pending_assign = (parseFloat(me.pending_assign) - (parseFloat(me.students[i].contracts[0].fees[j].cost) -parseFloat(me.datos[i].contracts[0].fees[j].paid_out))).toFixed(2);

											me.students[i].contracts[0].annuity_paid_out = (parseFloat(me.students[i].contracts[0].annuity_paid_out) + (parseFloat(me.students[i].contracts[0].fees[j].cost) - parseFloat(me.datos[i].contracts[0].fees[j].paid_out))).toFixed(2);
										}
										else{

											//CALCULOS DEL ESTUDIANTE
											me.students[i].contracts[0].fees[j].paid_out = parseFloat(me.pending_assign).toFixed(2);

											me.students[i].contracts[0].annuity_paid_out = (parseFloat(me.students[i].contracts[0].annuity_paid_out) + parseFloat(me.pending_assign)).toFixed(2);

											me.pending_assign = 0.00;

											return 0;
										}
									}
								}
							}
						}


						//AUTOASIGNACION DE PAGOS VARIOS (SERVICIOS NO OBLIGATORIOS)
						for(i=0; i < me.students.length; i++){

							for(j=0; j < me.students[i].extra_payments.length; j++){

								aux = 0;

								if(me.students[i].extra_payments[j].cost > me.students[i].extra_payments[j].paid_out){
									if(me.pending_assign > (parseFloat(me.students[i].extra_payments[j].cost) - parseFloat(me.students[i].extra_payments[j].paid_out))){

										//CALCULOS DEL ESTUDIANTE
										aux = parseFloat(me.students[i].extra_payments[j].paid_out).toFixed(2);

										me.students[i].extra_payments[j].paid_out = parseFloat(me.students[i].extra_payments[j].cost).toFixed(2);

										me.pending_assign = (parseFloat(me.pending_assign) - (parseFloat(me.students[i].extra_payments[j].cost) - aux)).toFixed(2);
									}
									else{

										//CALCULOS DEL ESTUDIANTE
										me.students[i].extra_payments[j].paid_out = (parseFloat(me.pending_assign)).toFixed(2);

										me.pending_assign = 0.00;

										return 0;
									}
								}
							}
						}


						//AUTOASIGNACION DE CUOTAS NO VENCIDAS
						for(j=0; j < 11; j++){

							for(i=0; i < me.students.length; i++){

								if(me.students[i].contracts[0] != undefined){//VERIFICA QUE UN ESTUDIANTE TIENE CONTRATO


									if(me.students[i].contracts[0].fees[j].status == 1){
										
										if(parseFloat(me.pending_assign) > (parseFloat(me.students[i].contracts[0].fees[j].cost) - parseFloat(me.datos[i].contracts[0].fees[j].paid_out))){

											//CALCULOS DEL ESTUDIANTE
											me.students[i].contracts[0].fees[j].paid_out = (parseFloat(me.students[i].contracts[0].fees[j].cost) - parseFloat(me.datos[i].contracts[0].fees[j].paid_out)).toFixed(2);

											me.pending_assign = (parseFloat(me.pending_assign) - (parseFloat(me.students[i].contracts[0].fees[j].cost) - parseFloat(me.datos[i].contracts[0].fees[j].paid_out))).toFixed(2);

											me.students[i].contracts[0].annuity_paid_out = (parseFloat(me.students[i].contracts[0].annuity_paid_out) + (parseFloat(me.students[i].contracts[0].fees[j].cost) - parseFloat(me.datos[i].contracts[0].fees[j].paid_out))).toFixed(2);
										}
										else{

											//CALCULOS DEL ESTUDIANTE
											me.students[i].contracts[0].fees[j].paid_out = (parseFloat(me.pending_assign)).toFixed(2);

											me.students[i].contracts[0].annuity_paid_out = (parseFloat(me.students[i].contracts[0].annuity_paid_out) + parseFloat(me.pending_assign)).toFixed(2);

											me.pending_assign = 0.00;

											return 0;
										}
									}
								}
							}
						}
					},


					//ACTIVAR ASIGNACION MANUAL
					activatedManualAsignation(){

						if(this.amount_deposited == 0) {
							alert("No es posible realizar una asignación manual con un monto de deposito de 0");
							return;
						}

						this.type_asignation = 2; 
						this.pending_assign = this.amount_deposited
					},


					//DEVOLVER VALORES REALES EN CASO DE CAMBIAR CAMPO (PERTENECE A ASIGNACION MANUAL)
					reverseValueAssignament(objeto){

						let me = this;

						if(objeto.ref == 'contract_service' || objeto.ref == 'extra_payment'){
							me.pending_assign = (parseFloat(me.pending_assign) + parseFloat(objeto.value.paid_out)).toFixed(2);
							objeto.value.paid_out = (0).toFixed(2);
							return;
						}

						if(objeto.ref == 'enrollment'){
							me.pending_assign = (parseFloat(me.pending_assign) + parseFloat(objeto.value.paid_out)).toFixed(2);
							objeto.value.paid_out = (0).toFixed(2);
							return;
						}

						if(objeto.ref == 'annuity' || objeto.ref == 'r15' || objeto.ref == 'r1'){

							if(objeto.ref == 'annuity'){
								me.pending_assign = parseFloat(me.pending_assign) + parseFloat(objeto.value.annuity_paid_out);
								
								objeto.value.annuity_paid_out = (0).toFixed(2);
							}

							if(objeto.ref == 'r15'){
								me.pending_assign = parseFloat(me.pending_assign) + parseFloat(objeto.value.r15_paid_out);
								
								objeto.value.r15_paid_out = (0).toFixed(2);	
							}

							if(objeto.ref == 'r1'){
								me.pending_assign = parseFloat(me.pending_assign) + parseFloat(objeto.value.r1_paid_out);
								
								objeto.value.r1_paid_out = (0).toFixed(2);	
							}

							for(i=0; i < objeto.value.fees.length; i++){
								
								if(objeto.ref == 'annuity'){
									if(objeto.value.fees[i].status != 2){
										objeto.value.fees[i].paid_out = (0).toFixed(2);
									}
								}

								if(objeto.ref == 'r15' && i > 0){
									/*if(objeto.value.fees[i].r15_status == 3 && ){
										continue;
									}*/

									if(objeto.value.fees[i].r15_status == 1){
										objeto.value.fees[i].r15_paid_out = (0).toFixed(2);
									}
									
									/*if(objeto.dato.fees[i].r15 != objeto.dato.fees[i].r15_paid_out){
										objeto.value.fees[i].r15_status = 1; //devolver R15 a estado activo
									}*/
								}

								if(objeto.ref == 'r1' && i > 0){
									/*if(objeto.value.fees[i].r1_status == 3){
										continue;
									}*/

									if(objeto.value.fees[i].r1_status == 1){
										objeto.value.fees[i].r1_paid_out = parseFloat(objeto.dato.fees[i].r1_paid_out).toFixed(2);
									}

									/*if(objeto.dato.fees[i].r1 != objeto.dato.fees[i].r1_paid_out){
										objeto.value.fees[i].r1_status = 1; //R1 activo
									}*/
								}
							}

							return;
						}
					},


					//REALIZAR ASIGNACION MANUAL DEL DINERO DEPOSITADO
					manualAssingment(objeto){

						let me = this;

						me.errors = [];
						me.error = 1;


						if(objeto.ref == 'contract_service' || objeto.ref == "extra_payment"){
							if(parseFloat(objeto.value.paid_out) > parseFloat(objeto.dato.cost) - parseFloat(objeto.dato.paid_out) || 
								parseFloat(objeto.value.paid_out) < 0 ){
									me.errors.push('El valor debe estar entre ' + 
													'0' + 
													' y ' +
													((parseFloat(objeto.dato.cost))-parseFloat(objeto.dato.paid_out)).toFixed(2));
									me.error = 1;

									objeto.value.paid_out = (0).toFixed(2);
									return;
							}
							else if(parseFloat(me.pending_assign) >= parseFloat(objeto.value.paid_out)){

									me.pending_assign = parseFloat(me.pending_assign) - parseFloat(objeto.value.paid_out).toFixed(2);
									objeto.value.paid_out = parseFloat(objeto.value.paid_out).toFixed(2);
							}
							else{
								me.errors.push('El total de los pagos realizados no pueden exceder el monto depositado');
								objeto.value.paid_out = (0).toFixed(2);
									return;	
							}
						}

						if(objeto.ref == 'enrollment'){
							if(parseFloat(objeto.value.paid_out) > parseFloat(objeto.dato.enrollment_cost) - parseFloat(objeto.dato.paid_out) || 
								parseFloat(objeto.value.paid_out) < 0){
									me.errors.push('El valor debe estar entre ' + 
													'0' + 
													' y ' + 
													(parseFloat(objeto.dato.enrollment_cost )-parseFloat(objeto.dato.paid_out)).toFixed(2));
									me.error = 1;
									objeto.value.paid_out = parseFloat(objeto.dato.paid_out).toFixed(2);
									return;
							}
							else if(parseFloat(me.pending_assign) >= parseFloat(objeto.value.paid_out)){
									me.pending_assign = parseFloat(me.pending_assign) - parseFloat(objeto.value.paid_out).toFixed(2);
									objeto.value.paid_out = parseFloat(objeto.value.paid_out).toFixed(2);
							}
							else{
								me.errors.push('El total de los pagos realizados no pueden exceder el monto depositado');
								objeto.value.paid_out = parseFloat(objeto.dato.paid_out).toFixed(2);
								return;	
							}
						}

						if(objeto.ref == 'annuity'){
							if(parseFloat(objeto.value.annuity_paid_out) < 0 ||	
								parseFloat(objeto.value.annuity_paid_out) > parseFloat(objeto.dato.annuity_cost) - parseFloat(objeto.dato.annuity_paid_out)){
										me.errors.push('El valor debe estar entre ' +  
													'0' +
													' y ' + 
													(parseFloat(objeto.dato.annuity_cost) - parseFloat(objeto.dato.annuity_paid_out)).toFixed(2));
										me.error = 1;
										objeto.value.annuity_paid_out = 0;
										return;
							}
							else if(parseFloat(me.pending_assign) >= parseFloat(objeto.value.annuity_paid_out)){

								var aux = parseFloat(objeto.value.annuity_paid_out);

								me.pending_assign = parseFloat(parseFloat(me.pending_assign) - aux).toFixed(2);

								objeto.value.annuity_paid_out = parseFloat(objeto.value.annuity_paid_out).toFixed(2);

								for(i=0; i < 11; i++){
									if(parseFloat(objeto.value.fees[i].paid_out) < parseFloat(objeto.dato.fees[i].cost) - parseFloat(objeto.dato.fees[i].paid_out)){
										
										if(aux > parseFloat(objeto.dato.fees[i].cost) - parseFloat(objeto.dato.fees[i].paid_out)){
											aux2 = parseFloat(objeto.dato.fees[i].paid_out);
											objeto.value.fees[i].paid_out = (parseFloat(objeto.dato.fees[i].cost) - parseFloat(objeto.dato.fees[i].paid_out)).toFixed(2);
											aux = aux - (parseFloat(objeto.value.fees[i].cost) - aux2);
										}
										else{
											aux2 = parseFloat(objeto.value.fees[i].paid_out);
											objeto.value.fees[i].paid_out = (aux2 + aux).toFixed(2);
											aux = 0;
										}							
									}
								}

								return;
							}
							else{
								me.errors.push('El total de los pagos realizados no pueden exceder el monto depositado');
								objeto.value.annuity_paid_out = (0).toFixed(2);//parseFloat(objeto.dato.annuity_paid_out).toFixed(2);
								return;	
							}
						}

						if(objeto.ref == 'r15'){
							if(parseFloat(objeto.value.r15_paid_out) < 0 || 
								parseFloat(objeto.value.r15_paid_out) > (parseFloat(objeto.dato.r15_total) - parseFloat(objeto.dato.r15_paid_out))){
										
									me.errors.push('El valor debe estar entre ' + 
												'0' + 
												' y ' + 
												(parseFloat(objeto.dato.r15_total) - parseFloat(objeto.dato.r15_paid_out)).toFixed(2));
									me.error = 1;
									objeto.value.r15_paid_out = (0).toFixed(2);
									return;
							}
							else if(parseFloat(me.pending_assign) >= objeto.value.r15_paid_out){

								var aux = parseFloat(objeto.value.r15_paid_out);

								me.pending_assign = (parseFloat(me.pending_assign) - aux).toFixed(2);

								objeto.value.r15_paid_out = parseFloat(objeto.value.r15_paid_out).toFixed(2);

								for(i=0; i < 11; i++){

									if(objeto.value.fees[i].r15_status == 1){

										if(aux >= parseFloat(objeto.dato.fees[i].r15) - parseFloat(objeto.dato.fees[i].r15_paid_out)){
											
											objeto.value.fees[i].r15_paid_out = (parseFloat(objeto.dato.fees[i].r15) - parseFloat(objeto.dato.fees[i].r15_paid_out)).toFixed(2);

											aux = aux - objeto.value.fees[i].r15_paid_out;
										}
										else{
											objeto.value.fees[i].r15_paid_out = (parseFloat(objeto.dato.fees[i].r15_paid_out) + aux).toFixed(2);

											aux = 0;
										}							
									}
								}

								return;
							}
							else{
								me.errors.push('El total de los pagos realizados no pueden exceder el monto depositado');
								objeto.value.r15_total = (0).toFixed(2);
								return;	
							}
						}

						if(objeto.ref == 'r1'){
							if(parseFloat(objeto.value.r1_paid_out) < 0 || 
								parseFloat(objeto.value.r1_paid_out) > (parseFloat(objeto.dato.r1_total) - parseFloat(objeto.dato.r1_paid_out))){
									
									me.errors.push('El valor debe estar entre ' + 
												'0' + 
												' y ' + 
												(parseFloat(objeto.dato.r1_total) - parseFloat(objeto.dato.r1_paid_out)).toFixed(2));
									me.error = 1;
									objeto.value.r1_paid_out = (0).toFixed(2);
									return;
							}
							else if(parseFloat(me.pending_assign) >= objeto.value.r1_paid_out){

								var aux = parseFloat(objeto.value.r1_paid_out);

								me.pending_assign = (parseFloat(me.pending_assign) - aux).toFixed(2);

								objeto.value.r1_paid_out = parseFloat(objeto.value.r1_paid_out).toFixed(2);

								for(i=0; i < 11; i++){
									if(objeto.value.fees[i].r1_status == 1){

										if(aux >= parseFloat(objeto.dato.fees[i].r1) - parseFloat(objeto.dato.fees[i].r1_paid_out)){
											
											objeto.value.fees[i].r1_paid_out = (parseFloat(objeto.dato.fees[i].r1) - parseFloat(objeto.dato.fees[i].r1_paid_out)).toFixed(2);

											aux = aux - objeto.value.fees[i].r1_paid_out;
										}
										else{
											objeto.value.fees[i].r1_paid_out = (parseFloat(objeto.dato.fees[i].r1_paid_out) + aux).toFixed(2);

											aux = 0;
										}						
									}
								}

								return;
							}
							else{
								me.errors.push('El total de los pagos realizados no pueden exceder el monto depositado');
								objeto.value.r1_paid_out = (0).toFixed(2);
								return;	
							}
						}
					},


					//FORMULAR ARREGLO CON PAGOS REALIZADOS PARA ENVIAR AL SERVIDOR
					formulateArrayPayments(){

						let me = this;

						var type_payment = {
							'type': '',
							'description': '',
							'total': '',
							'id': ''
						}

						var type_payment_aux = {
							'type': '',
							'description': '',
							'total': '',
							'service_id': '',
							'year': 0,
						}

						var student_aux = {
							'id': 0,
							'name': '',
							'payments': [],
							'description': ''
						}


						for(i=0; i < me.students.length; i++){
							student_aux.id = me.students[i].id;
							student_aux.name = me.students[i].name;
							student_aux.description = me.students[i].contracts[0] != undefined ? me.students[i].contracts[0].enrollment_bachelor + ' ' +
														me.students[i].contracts[0].enrollment_grade : '';
							student_aux.contract_id = me.students[i].contracts[0] != undefined ? me.students[i].contracts[0].id : null;
							me.students_pay.push(JSON.parse(JSON.stringify(student_aux)));
						}

						for(i=0; i < me.students.length; i++){

							if(me.students[i].contracts[0] != undefined){
								//MATRICULA
								if(parseFloat(me.students[i].contracts[0].paid_out) > 0){

									type_payment.type = 'enrollment';
									type_payment.description = 'Matricula';
									type_payment.total = parseFloat(me.students[i].contracts[0].paid_out);
									type_payment.id = me.students[i].contracts[0].id;
									me.students_pay[i].payments.push(JSON.parse(JSON.stringify(type_payment)));
								}

								//SERVICIOS OBLIGATORIOS
								for(j=0; j < me.students[i].contracts[0].contract_services.length; j++){

									if(parseFloat(me.students[i].contracts[0].contract_services[j].paid_out) > 0){

										type_payment.type = 'contract_service';
										type_payment.description = me.students[i].contracts[0].contract_services[j].description;
										type_payment.total = parseFloat(me.students[i].contracts[0].contract_services[j].paid_out);
										type_payment.id = me.students[i].contracts[0].contract_services[j].id;
										me.students_pay[i].payments.push(JSON.parse(JSON.stringify(type_payment)));
									}
								}

								//CUOTAS Y RECARGOS
								for(j=0; j < me.students[i].contracts[0].fees.length; j++){

									//CUOTAS
									if(me.students[i].contracts[0].fees[j].status != 2 && me.students[i].contracts[0].fees[j].paid_out != 0){

										type_payment.type = 'fee';
										type_payment.description = 'cuota ' + me.students[i].contracts[0].fees[j].order;
										type_payment.total = parseFloat(me.students[i].contracts[0].fees[j].paid_out)//parseFloat(me.students[i].contracts[0].fees[j].paid_out) - parseFloat(me.datos[i].contracts[0].fees[j].paid_out);
										type_payment.id = me.students[i].contracts[0].fees[j].id;
										me.students_pay[i].payments.push(JSON.parse(JSON.stringify(type_payment)));
									}

									//RECARGO R15
									if(me.students[i].contracts[0].fees[j].r15_status == 1 && me.students[i].contracts[0].fees[j].r15_paid_out != 0){

										type_payment.type = 'r15';
										type_payment.description = 'r15 de cuota ' + me.students[i].contracts[0].fees[j].order;
										type_payment.total = parseFloat(me.students[i].contracts[0].fees[j].r15_paid_out);
										type_payment.id = me.students[i].contracts[0].fees[j].id;
										me.students_pay[i].payments.push(JSON.parse(JSON.stringify(type_payment)));
									}

									//RECARGO R1
									if(me.students[i].contracts[0].fees[j].r1_status == 1 && me.students[i].contracts[0].fees[j].r1_paid_out != 0){

										type_payment.type = 'r1';
										type_payment.description = 'r1 de cuota ' + me.students[i].contracts[0].fees[j].order;
										type_payment.total = parseFloat(me.students[i].contracts[0].fees[j].r1_paid_out);
										type_payment.id = me.students[i].contracts[0].fees[j].id;
										me.students_pay[i].payments.push(JSON.parse(JSON.stringify(type_payment)));
									}
								}
							}


							//PAGOS EXTRA
							for(j=0; j < me.students[i].extra_payments.length; j++){

								if(parseFloat(me.students[i].extra_payments[j].paid_out) != parseFloat(me.datos[i].extra_payments[j].paid_out)){

									type_payment_aux.type = 'extra_payment';
									type_payment_aux.description = me.students[i].extra_payments[j].description;
									type_payment_aux.total = parseFloat(me.students[i].extra_payments[j].paid_out) - parseFloat(me.datos[i].extra_payments[j].paid_out);
									type_payment_aux.service_id = me.students[i].extra_payments[j].service_id;
									type_payment_aux.year = me.students[i].extra_payments[j].year;
									me.students_pay[i].payments.push(JSON.parse(JSON.stringify(type_payment_aux)));
								}
							}
						}

						me.info_str = JSON.stringify(me.students_pay);
						me.students_pay = [];
					},


					//REINICIAR INPUTS AL CANCELAR AUTOASIGNACION
					resetInputs(){
						let me = this;
						//console.log(me.datos);
						me.students = JSON.parse(JSON.stringify(me.datos));

						for(i=0; i < me.students.length; i++){
							
							if(me.students[i].contracts[0] != undefined){//VERIFICA QUE UN ESTUDIANTE TIENE CONTRATO

								me.students[i].contracts[0].paid_out = (0).toFixed(2);

								for(let contract_service of me.students[i].contracts[0].contract_services){
									contract_service.paid_out = (0).toFixed(2);
								}

								me.students[i].contracts[0].annuity_paid_out = (0).toFixed(2);
								me.students[i].contracts[0].r15_paid_out = (0).toFixed(2);
								me.students[i].contracts[0].r1_paid_out = (0).toFixed(2);						

								for(j=0; j < me.students[i].contracts[0].fees.length; j++){
									
									if(me.students[i].contracts[0].fees[j].status != 2){
										me.students[i].contracts[0].fees[j].paid_out = (0).toFixed(2);
									}

									if(me.students[i].contracts[0].fees[j].r15_status == 1){
										me.students[i].contracts[0].fees[j].r15_paid_out = (0).toFixed(2);
									}

									if(me.students[i].contracts[0].fees[j].r1_status == 1){
										me.students[i].contracts[0].fees[j].r1_paid_out = (0).toFixed(2);
									}
								}
							}
						}

						//console.log(me.datos);

						me.errors = [],
						me.error = 0;
						me.students_pay = [];
						me.info_str = '';
						me.type_asignation = 0;
						//me.amount_deposited = 0;
						me.pending_assign = 0;
						me.refund = 0;
						me.comment_refund = '';
						return;
					},


					//REEMBOLSO CUANDO EL PENDIENTE POR ASIGNAR ES DISTINTO DE CERO
					reembolso(){
						this.refund = this.pending_assign;
						this.pending_assign = 0;
						//this.closeModal();
					},
					

					//ACTIVAR FUNCIONALIDAD DE REMOVER PAGOS EXTRA (SERVICIOS ACTIVOS DEL ESTUDIANTE) y/o RECARGOS
					activeRemove(indice){

						console.log(this.student_select);
						console.log(indice);

						if(this.student_select != indice){
							this.remove_button = true;
							this.student_select = indice;
							return;
						}

						this.remove_button = !this.remove_button;
					},


					//REMOVER R15
					removeR15(){

						let me = this;

						for(i=1; i < me.students[me.student_select].contracts[0].fees.length; i++){
							if(me.r15_fee_data[me.student_select].contracts[0].fees[i].r15_status == 3){
								me.students[me.student_select].contracts[0].fees[i].r15_status = 3;
							}
							else
								if(me.r15_fee_data[me.student_select].contracts[0].fees[i].r15_status == 1){
									me.students[me.student_select].contracts[0].fees[i].r15_status = 1;
								}
						}

						me.closeModal();
					},

					removeR1(){

						let me = this;

						for(i=1; i < me.students[me.student_select].contracts[0].fees.length; i++){
							if(me.r1_fee_data[me.student_select].contracts[0].fees[i].r1_status == 3){
								me.students[me.student_select].contracts[0].fees[i].r1_status = 3;
							}
							else
								if(me.r1_fee_data[me.student_select].contracts[0].fees[i].r1_status == 1){
									me.students[me.student_select].contracts[0].fees[i].r1_status = 1;
								}
						}

						me.closeModal();
					},

					modalR15(indice){

						this.student_select = indice;

						this.openModal(3);
					},

					modalR1(indice){

						this.student_select = indice;
						
						this.openModal(4);
					},

					isR1(contrac){
						let is = false
						let fees = contrac.fees
						let isContract = contrac.r1_paid_out != contrac.r1_total
						for (fee of fees) {
							if (fee.r1_status == 1) is = true
						}
						return (is && isContract)
					},

					isR15(contrac){
						let is = false
						let fees = contrac.fees
						let isContract = contrac.r15_paid_out != contrac.r15_total
						for (fee of fees) {
							if (fee.r15_status == 1) is = true
						}
						return (is && isContract)
					},

					//CERRAR MODALES
					closeModal(){
						this.active_services = [];
						this.extra_payments= [];

						$('.close').click();
					},


					//ABRIR MODALES
					openModal(num_modal){

						if(num_modal == 1) $('#m_modal_1').modal('show');
						
						if(num_modal == 2 && this.pending_assign > this.refund){
							$('#m_modal_2').modal('show');
						}

						if(num_modal == 3){
							// This is for eastern
							this.r15_fee_data = this.students.slice();
							$('#m_modal_3').modal('show');
						}

						if(num_modal == 4){
							this.r1_fee_data = this.students.slice();
							$('#m_modal_4').modal('show');
						}
		            },

		            removefee_r15(index){
		            	

		            	let me = this;

		            	var temp = me.r15_fee_data[me.student_select].contracts[0].fees[index].r15_status;
		            	if(temp == 1)
		            		me.r15_fee_data[me.student_select].contracts[0].fees[index].r15_status = 3;
		            	else if(temp == 3)
		            	{
		            		me.r15_fee_data[me.student_select].contracts[0].fees[index].r15_status = 1;
		            	}
		            	debugger;

		            },

		            removefee_r1(index){
		            	

		            	let me = this;

		            	var temp = me.r1_fee_data[me.student_select].contracts[0].fees[index].r1_status;
		            	if(temp == 1)
		            		me.r1_fee_data[me.student_select].contracts[0].fees[index].r1_status = 3;
		            	else if(temp == 3)
		            	{
		            		me.r1_fee_data[me.student_select].contracts[0].fees[index].r1_status = 1;
		            	}
		            	debugger;

		            },
				},

				watch: {
					//BUSCAR ESTUDIANTES CON CADA CAMBIO EN EL CAMPO DE BUSQUEDA
					search: function(){
						this.getOptions();
						this.selected = 0;
					},
				},

				mounted(){

					this.active_datapicker = 1;	
				}
			});
			


			//DATA PICKER (COMPONENTE)
			Vue.component('my-date-picker',{
	            template: '<input type="text" autocomplete="off" v-datepicker class="form-control" :value="value" @input="update($event.target.value)" id="datapicker">',
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
	            props: ['value'],
	            methods: {
	                update (v){
	                    this.$emit('input', v)
	                }
	            }
	        });

		</script>
	@endsection