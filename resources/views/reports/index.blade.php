@extends('layouts.app')

@section('title','Reportes')

@section('content')

	<div class="m-portlet">
		
		<!--ENCABEZADO ====================================================================================================-->
		<!--===============================================================================================================-->
		<div class="m-portlet__head">
			<div class="m-portlet__head-caption">
				<div class="m-portlet__head-title">
					<h3 class="m-portlet__head-text">
						Reportes
					</h3>
				</div>
			</div>
		</div>
		<!--FIN DEL ENCABEZADO ============================================================================================-->
		<!--===============================================================================================================-->


		<div class="m-portlet__body">

			<br>

			<div class="row">
				<div class="col-md-12">
					<div class="m-section">
						<div class="m-section__content">

							<div v-if="!active_export">
								<div style="margin: 0 auto; text-align: center;">
									<img src="/app/config/loading.gif" style="width: 80px; height: auto;">
								</div>
								<h4 style="text-align: center;">
									Cargando reporte...
								</h4>
								<br><br>
							</div>


							<table class="table table-striped m-table">
								
								<tbody style="text-align: center;">
									<tr>
										<td>Informe diario de caja</td>
										<td style="overflow: hidden;" v-if="active_datepickers">
											<my-date-picker 
												id="datapicker" 
												v-model="report_date"
												:placeholder="'Seleccionar fecha'">
											</my-date-picker>			
										</td>
										<td>
											<button type="button" 
													class="pull-right btn btn-primary" 
													@click="dailyReport()" 
													:disabled="report_date == '' || !active_export"
													style="background-color: rgb(25,59,100)">
														Exportar
											</button>
										</td>
									</tr>

									<tr>
										<td>Estudiantes morosos</td>
										<td></td>
										<td>
											<button type="button" 
													class="pull-right btn btn-primary" 
													@click="defaulterStudents()" 
													:disabled="!active_export"
													style="background-color: rgb(25,59,100)">
														Exportar
											</button>
										</td>
									</tr>
									
									<tr>
										<td>Conciliacion Bancaria</td>
										<td>
											<select class="m-imput m-imput--air m-imput--pill form-control-sm" style="border-radius: 0" v-model="conciliation_month" id="exampleSelect1">
												<option value="">Escoger Mes</option>
												<option v-for="mes in 12" :key="mes" :value="mes" v-text="convertMonth(mes)"></option>
											</select>
										</td>			
										<td>
											<button type="button" 
													:disabled="conciliation_month == ''" 
													@click="openModal(1)" 
													class="pull-right btn btn-primary"
													style="background-color: rgb(25,59,100)">
														Exportar
											</button>
										</td>	
									</tr>
									
									<tr>
										<td>Transacciones Realizadas</td>
										<td v-if="active_datepickers">
											<my-date-picker 
												id="datapicker" 
												v-model="transaction_since_date"
												:placeholder="'Fecha Inicial'">
											</my-date-picker>
											<my-date-picker 
												id="datapicker" 
												v-model="transaction_final_date"
												:placeholder="'Fecha final'">
											</my-date-picker>
										</td>
										
										<td>
											<button type="button" 
													@click="transactionMade()" 
													:disabled="transaction_final_date == '' || 
														transaction_since_date == '' || 
														!active_export" 
													class="pull-right btn btn-primary"
													style="background-color: rgb(25,59,100)">
														Exportar
											</button>
										</td>										
									</tr>

									<tr>
										<td>Cuentas por cobrar</td>
										<td></td>
										<td>
											<button type="button" 
													class="pull-right btn btn-primary"
													style="background-color: rgb(25,59,100)"
													@click="accountReceivable()"
													:disabled="!active_export">
														Exportar
											</button>
										</td>
									</tr>
								</tbody>
							</table>
					
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>


	<!--CONCILIACION BANCARIA =================================================================================-->
	<!--===============================================================================================================-->
	<div class="modal fade" id="m_modal_1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">


		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">

				<div class="modal-header">
					<h3>Conciliacion Bancaria - <span v-text="convertMonth(conciliation_month)"></span></h3>
					<button type="button" @click="closeModal(1)" class="close close_1" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				
				<div class="modal-body">

					<!--ERRORES DE VALIDACION-->
                    <div v-show="error" class="form-group row div-error">
                        <div class="text-center text-error" style="margin:0 auto">
                            <div class="text-danger" v-for="error in errors" :key="error" v-text="error">
                                
                            </div>
                        </div>
                    </div>

					<!--FORMULARIO-->
					<div class="row justify-content-around">
						<div class="col-6 form-group">
							<label for="total_banco" class="control-label">Total en Banco</label>
						</div>
						<div class="col-4 form-group">
							<input class="form-control" 
									v-model="conciliation.total_bank" 
									id="total_banco">
						</div>
					</div>
					
					<hr>

					<div class="row justify-content-around">
						<div class="col-6 form-group">
							<label for="estado_cuenta" class="control-label">Estado de cuenta de banco</label>
						</div>
						<div class="col-4 form-group" >
							<input class="form-control" 
									v-model="conciliation.bank_account_state" 
									id="estado_cuenta">
						</div>
					</div>
					
					<div class="row justify-content-around">
						<div class="col-6 form-group">
							<label for="transferencia_entre_cuenta" class="control-label">Mas transferencia entre Cuenta:</label>
						</div>
						
						<div class="col-4 form-group">													
							<input class="form-control" 
									v-model="conciliation.account_transfer"
									id="transferencia_entre_cuenta">
						</div>
					</div>

					<div class="row justify-content-around">
						<div class="col-6 form-group">
							<label for="transferencia_cuenta_ahorro" class="control-label">Transferencia de CTA. Ahorro:</label>
						</div>

						<div class="col-4 form-group">
							<input class="form-control"
									v-model="conciliation.account_transfer_saving"
									id="transferencia_cuenta_ahorro">
						</div>
					</div>

					<div class="row justify-content-around">
						<div class="col-6 form-group">
							<label for="total" class="control-label">Total</label>
						</div>

						<div class="col-4 form-group">
							<input class="form-control"
									v-model="conciliation.total"
									id="total">
						</div>
					</div>

					<hr>

					<div class="row justify-content-around">
						<div class="col-6 form-group">
							<label for="recibos_meses_anteriores" class="control-label">Recibo de meses anteriores</label>
						</div>

						<div class="col-4 form-group">
							<input class="form-control" 
									v-model="conciliation.receipt_after_month"
									id="recibos_meses_anteriores">
						</div>
					</div>

					<div class="row justify-content-around">
						<div class="col-6 form-group">
							<label for="saldo_disponible_banco" class="control-label">Saldo disponible del banco (DB + CR)</label>
						</div>

						<div class="col-4 form-group">
							<input class="form-control" 
									v-model="conciliation.available_balance"
									id="saldo_disponible_banco">
						</div>
					</div>

					<div class="row justify-content-around">
						<div class="col-6 form-group">
							<label for="saldo_disponible_libros" class="control-label">Saldo disponible segun libro</label>
						</div>

						<div class="col-4 form-group">
							<input class="form-control" 
									v-model="conciliation.available_balance_books"
									id="saldo_disponible_libros">
						</div>
					</div>
				</div>


				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" @click="closeModal(1)">Cerrar</button>
					<button type="button" class="btn btn-primary" :disabled="!active_conciliation_month" @click="bankConciliation()">
						<span v-text="active_conciliation_month ? 'Exportar' : 'Cargando...'"></span>
					</button>
				</div>
			</div>
		</div>
	</div>
	<!--FIN CONCILIACION BANCARIA ============================================================================================-->
	<!-- ==============================================================================================================-->

	<iframe src="" id="PDFtoPrint" class="d-none" width="100%" height="100%"></iframe>

@endsection

@section('scripts')

	<script src="{{asset('metronic/assets3/vendors/base/vendors.bundle.js')}}" type="text/javascript"></script>
    <script src="{{asset('metronic/assets3/demo/default/base/scripts.bundle.js')}}" type="text/javascript"></script>
    <script src="{{asset('metronic/assets3/demo/default/custom/crud/forms/widgets/bootstrap-datepicker.js')}}" type="text/javascript"></script>

	<script type="text/javascript">
		
		const app = new Vue({

			el: '#app',

			data:{
				active_datepickers: false,
				//final_date: '',
				//since_date: '',
				conciliation_month: '',
				report_date: '',
				transaction_since_date: '',
				transaction_final_date: '',
				refund_sice_date: '',
				refund_final_date: '',

				errors: [],
				error: 0,

				active_conciliation_month: true,
				active_export: true,


				conciliation:{
					total_bank: 0,
					bank_account_state: 0,
					account_transfer: 0,
					account_transfer_saving: 0,
					total: 0,
					receipt_after_month: 0,
					available_balance: 0,
					available_balance_books: 0,
				}
			},

			methods:{

				validateNumber: function(number){
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

				checkForm(){
	                this.error = 0;
	                this.errors = [];

	                //Validar total del banco
	                if(!this.conciliation.total_bank) this.errors.push("Total en banco es requerido");
	                //else if(!this.validateNumber(this.conciliation.total)) this.errors.push("Conciliacion debe ser un valor numerico");

	                //Validacion de costo
	                if (!this.conciliation.bank_account_state) this.errors.push("Estado de cuenta de banco es requerido");
	                //else if(!this.validateNumber(this.cost)) this.errors.push('Costo debe ser un valor numerico')
	                
	                //Validacion de descuento
	                if (!this.conciliation.account_transfer) this.errors.push("Transferencia entre cuenta es requerido");
	                //else if(!this.validateNumber(this.discount)) this.errors.push('Descuento debe ser un valor numerico')

	                //Validacion de fechas
	                if(!this.conciliation.account_transfer_saving) this.errors.push('Transferencia de CTA. Ahorros es requerido');
	                
	                //Validacion de mes de segunda cuota
	                if(!this.conciliation.total) this.errors.push('Total es requerido');

	                //Validacion de descuento
	                if (!this.conciliation.receipt_after_month) this.errors.push("Recibos de meses anteriores es requerido");
	                //else if(!this.validateNumber(this.discount)) this.errors.push('Descuento debe ser un valor numerico')

	                //Validacion de fechas
	                if(!this.conciliation.available_balance) this.errors.push('Saldo disponible del banco es requerido');
	                
	                //Validacion de mes de segunda cuota
	                if(!this.conciliation.available_balance_books) this.errors.push('Saldo disponible segun libro');


	                if(this.errors.length) this.error = 1;

	                return this.error;
	            },

				bankConciliation(){

					if(this.checkForm()) return;

					let me = this;

					me.active_conciliation_month = false;

					// axios
					// 	.post('/reports/bankConciliation/',
					// 		{
					// 			data: {
					// 				'total_bank': me.conciliation.total_bank,
					// 				'bank_account_state': me.conciliation.bank_account_state,
					// 				'account_transfer': me.conciliation.account_transfer,
					// 				'account_transfer_saving': me.conciliation.account_transfer_saving,
					// 				'total': me.conciliation.total,
					// 				'receipt_after_month': me.conciliation.receipt_after_month,
					// 				'available_balance': me.conciliation.available_balance,
					// 				'available_balance_books': me.conciliation.available_balance_books,
					// 				'mes': me.conciliation_month,
					// 			}
					// 		},
					// 		{
					// 			responseType: 'arraybuffer',
					// 			headers: {
					//                 'Content-Type': 'application/json',
					//                 'Accept': 'application/pdf'
					//             },
					//         })
					// 	.then(function(response){
					// 		let blob = new Blob([response.data], { type: 'application/pdf' }),
					// 	    url = window.URL.createObjectURL(blob)
					// 	    window.open(url);					
					// 	    me.active_conciliation_month = true;

					// 	})
					// 	.catch(function(error){
					// 		console.log('error');
					// 		me.active_conciliation_month = true;							
					// 	});
					
					var data = {
		 				'total_bank': me.conciliation.total_bank,
		 				'bank_account_state': me.conciliation.bank_account_state,
		 				'account_transfer': me.conciliation.account_transfer,
		 				'account_transfer_saving': me.conciliation.account_transfer_saving,
		 				'total': me.conciliation.total,
		 				'receipt_after_month': me.conciliation.receipt_after_month,
		 				'available_balance': me.conciliation.available_balance,
		 				'available_balance_books': me.conciliation.available_balance_books,
		 				'mes': me.conciliation_month,
		 			}
		 			var str = jQuery.param(data);

		 			url = '/reports/bankConciliation?' + str;
		 			
					$('#PDFtoPrint').attr('src',url);
					setTimeout(function() {
						$('#PDFtoPrint').get(0).contentWindow.focus(); 
						me.active_export = true;
					},2000);

		 			me.active_conciliation_month = true;

				},

				defaulterStudents(){

					axios
						.post()
						.then(function(response){
							let blob = new Blob([response.data], { type: 'application/pdf' }),
						    url = window.URL.createObjectURL(blob)
						    window.open(url);
						})
						.catch(function(error){

						});
				},

				dailyReport(){

					let me = this;

					me.active_export = false;

					// axios
					// 	.post('/reports/dailyReports',
					// 		{
					// 			data: {
					// 				'report_date': me.report_date,
					// 			}
					// 		},
					// 		{
					// 			responseType: 'arraybuffer',
					// 			headers: {
					//                 'Content-Type': 'application/json',
					//                 'Accept': 'application/pdf'
					//             },
					//         })
					// 	.then(function(response){
					// 		let blob = new Blob([response.data], { type: 'application/pdf' }),
					// 	    url = window.URL.createObjectURL(blob)
					// 	    window.open(url);
					// 	    me.active_export = true;
					// 	    //console.log(response.data.result);
					// 	})
					// 	.catch(function(error){
					// 		me.active_export = true;
					// 	});
					
					url = '/reports/dailyReports?report_date=' + me.report_date;
					$('#PDFtoPrint').attr('src',url);
					setTimeout(function() {
						$('#PDFtoPrint').get(0).contentWindow.focus(); 
						me.active_export = true;
					},2000);

				},

				transactionMade(){

					let me = this;

					me.active_export = false;

					// axios
					// 	.post('/reports/transactionsMade',
					// 		{
					// 			data: {
					// 				'transaction_since_date': me.transaction_since_date,
					// 				'transaction_final_date': me.transaction_final_date,
					// 			}
					// 		},
					// 		{
					// 			responseType: 'arraybuffer',
					// 			headers: {
					//                 'Content-Type': 'application/json',
					//                 'Accept': 'application/pdf'
					//             },
					//         })
					// 	.then(function(response){
					// 		let blob = new Blob([response.data], { type: 'application/pdf' }),
					// 	    url = window.URL.createObjectURL(blob)
					// 	    window.open(url);
					// 	    me.active_export = true;
					// 	})
					// 	.catch(function(error){
					// 		me.active_export = false;
					// 	});

					url = '/reports/transactionsMade?transaction_since_date=' + me.transaction_since_date + '&transaction_final_date=' + me.transaction_final_date;
					$('#PDFtoPrint').attr('src',url);
					setTimeout(function() {
						$('#PDFtoPrint').get(0).contentWindow.focus(); 
						me.active_export = true;
					},2000);

				},

				defaulterStudents(){

					let me = this;

					me.active_export = false;

					// axios
					// 	.post('/reports/defaultersStudents',{data:{'dato':''}},
					// 		{
					// 			responseType: 'arraybuffer',
					// 			headers: {
					//                 'Content-Type': 'application/json',
					//                 'Accept': 'application/pdf'
					//             },
					//         })
					// 	.then(function(response){
					// 		let blob = new Blob([response.data], { type: 'application/pdf' }),
					// 	    url = window.URL.createObjectURL(blob)
					// 	    window.open(url);
					// 	    me.active_export = true;
					// 	})
					// 	.catch(function(error){
					// 		me.active_export = false;
					// 	});

						axios.get('/reports/defaultersStudents')
						.then(function (response) {
						    
						    $('#PDFtoPrint').attr('src','/reports/defaultersStudents');
						    setTimeout(function(){},300);
						    $('#PDFtoPrint').get(0).contentWindow.focus(); 
							me.active_export = true;

						})
						.catch(function (error){
							console.log(error);
							me.active_export = true;
						});
				},

				accountReceivable(){
					let me = this;

					me.active_export = true;

					// axioss
					// 	.post('/reports/accountReceivable',{data:{'dato':''}},
					// 		{
					// 			responseType: 'arraybuffer',
					// 			headers: {
					//                 'Content-Type': 'application/json',
					//                 'Accept': 'application/pdf'
					//             },
					//         })
					// 	.then(function(response){
					// 		let blob = new Blob([response.data], { type: 'application/pdf' }),
					// 	    url = window.URL.createObjectURL(blob)
					// 	    window.open(url);
					// 	    me.active_export = true;
					// 	 	console.log(response);
					// 	    // var printContents = response.data;
					// 	    // var originalContents = document.body.innerHTML;

					// 	    // document.body.innerHTML = printContents;

					// 	    // window.print();

					// 	    // document.body.innerHTML = originalContents;
					// 	})
					// 	.catch(function(error){
					// 		me.active_export = false;
					// 	});
					// window.open('/reports/accountReceivable');
					// return;
					axios.get('/reports/accountReceivable')
						.then(function (response) {
						    $('#PDFtoPrint').attr('src','/reports/accountReceivable');

						    //$('#PDFtoPrint').get(0).contentWindow.focus(); 
				          	//Ejecutamos la impresion sobre ese control
				          	//$("#PDFtoPrint").get(0).contentWindow.print(); 
							me.active_export = true;
						})
						.catch(function (error){
							console.log(error);
							me.active_export = true;
						});
				},

				convertMonth(month){
					switch (month){
						case 1 : return 'Enero';
						case 2 : return 'Febrero';
						case 3 : return 'Marzo';
						case 4 : return 'Abril';
						case 5 : return 'Mayo';
						case 6 : return 'Junio';
						case 7 : return 'Julio';
						case 8 : return 'Agosto';
						case 9 : return 'Septiembre';
						case 10 : return 'Octubre';
						case 11 : return 'Noviembre';
						case 12 : return 'Diciembre';
					}
				},

				openModal(num_modal){
					switch(num_modal){
						case 1:	$('#m_modal_1').modal('show'); break;
					}
				},

				closeModal(num_modal){
					switch(num_modal){
						case 1:	$('.close_1').click(); break;
					}	
					this.errors = [],
					this.error = 0;

					this.conciliation = {
						total_bank: 0,
						bank_account_state: 0,
						account_transfer: 0,
						account_transfer_saving: 0,
						total: 0,
						receipt_after_month: 0,
						available_balance: 0,
						available_balance_books: 0,
					}
				},
			},

			mounted(){
				this.active_datepickers = true;
			},
		});

		//DATA PICKER (COMPONENTE)
		Vue.component('my-date-picker',{
		    template: '<input type="text" autocomplete="off" v-datepicker class="m-input m-input--air m-input--pill" :placeholder="placeholder" :value="value" @input="update($event.target.value)" style="padding-left:10px">',
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