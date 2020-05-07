@extends('layouts.app')

@section('title','Balance general')

@section('styles')
	
	<style>
		.m-body {
			padding-top: 40px !important;
		}
	</style>

@endsection

@section('content')
	<div class="m-portlet">
		<!--ENCABEZADO ====================================================================================================-->
		<!--===============================================================================================================-->
		<div class="m-portlet__head">
			<div class="m-portlet__head-caption">
				<div class="m-portlet__head-title">
					<h3 class="m-portlet__head-text">
						<span v-text="'Balance ' + (year == null ? '' : year) + ' ICA'"></span>
					</h3>
				</div>
			</div>
			<div class="m-portlet__head-tools">
				<ul class="m-portlet__nav">
					<li class="m-portlet__nav-item">
						@can('payments.store_edit')
								<select id="year" v-model="year" @change="getData(year)" class="form-control">
									@foreach(App\Annuity::all() as $annuity)
										<option value="{{ $annuity->year }}">{{ $annuity->year }}</option>
									@endforeach
								</select>
						@endcan
					</li>
				</ul>
			</div>
		</div>
		<!--FIN DEL ENCABEZADO ============================================================================================-->
		<!--===============================================================================================================-->


		<div class="m-portlet__body">

			<!--begin::Section-->
			<div class="m-section">
				<div class="m-section__content">
					

					<!--<div style="margin-bottom: 100px">
						<line-chart v-if="data.Deposito != undefined" :deposito ="data.Deposito"></line-chart>
					</div>-->
					


					<div v-if="graph_active" class="m-portlet m-portlet--tab">
						
						<div class="m-portlet__body">
							<div id="m_flotcharts_5" style="height:350px;">
							</div>
						</div>
					</div>




					<table class="table table-striped m-table table-responsive" style="text-align: center">
						<thead>
							<th></th>
							<th>Enero</th>
							<th>Febrero</th>
							<th>Marzo</th>
							<th>Abril</th>
							<th>Mayo</th>
							<th>Junio</th>
							<th>Julio</th>
							<th>Agosto</th>
							<th>Septiembre</th>
							<th>Octubre</th>
							<th>Noviembre</th>
							<th>Diciembre</th>
						</thead>

						<tbody>
							<tr v-for="(value,key) in data">
								
								<th v-text="key"></th>

								<td v-for="(value2, key2) in value"
									:style="key == 'Banco' ? 'text-align: end' : ''">
									
										<span	v-text="parseFloat(value2).toFixed(2)"
												v-if="value2 != 0"
												:style="(key=='Balance' && parseFloat(value2).toFixed(2) < 0) ? 'color: red' : ''">
										</span>
										
										@hasrole('super_admin')
											<a	v-if="key == 'Banco'" 
												href="#"
												style="font-size: 10px; color: rgb(25, 59, 100)"
												@click.prevent="createBank(key2,year)">
													<i class="fa fa-edit"></i>
											</a>
										@endhasrole
								</td>
							</tr>
						</tbody>
						<!--<thead>
							<tr>
								<th></th>
								<th v-for="(value,key) in data" v-text="convertMonth(key)"></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th>Matricula</th>
								<td v-for="(month) in data" v-text="month.enrollment"></td>
							</tr>
							
							<template v-for="month in data">
								
								<tr v-for="(vlue,key) in month" 
									v-if="key != 'enrollment' && key != 'fees' && key != 'enrollment' && key != 'extra_payment' && key != 'recharge'">
										<th v-text="key"></th>
								</tr>
							</template>
							
							<tr>
								<th>Servicios</th>
								<td v-for="(month) in data" v-text="month.extra_payment"></td>
							</tr>
							<tr>
								<th>Recargos</th>
								<td v-for="(month) in data" v-text="month.recharge"></td>
							</tr>
							<tr>
								<th>Cuotas</th>
								<td v-for="(month) in data" v-text="month.fees"></td>
							</tr>
						</tbody>-->
						<!--<tbody>
							<tr>
								<td>Sigueduc</td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
							<tr>
								<td>Matricula</td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
							<tr>
								<td>Cuotas</td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
							<tr>
								<td>Servicios</td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
							<tr>
								<td>Reembolsos</td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
							<tr>
								<td>Banco</td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
							<tr>
								<td>Balance</td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
						</tbody>-->

						<!--<tbody>
							<tr v-for="(value,key) in data" v-text="convertMonth(key)">
							</tr>
						</tbody>-->

					</table>

					<hr>

					<br>

					<div class="row">
						<div class="col-md-6" v-if="students != null">
							<div class="m-portlet__head"  style="overflow: hidden">
								<div class="m-portlet__head-caption">
									<div class="m-portlet__head-title row">
										<h3 class="m-portlet__head-text">
											1 cuota
										</h3>
									</div>
								</div>
								
								<div class="m-portlet__head-caption" style="float: right;">
									<div class="m-portlet__head-title row">
										<h6 v-text="one_cuote + ' estudiante(s)'">
										</h6>
									</div>
								</div>
							</div>
							<div class="m-portlet__body">
								<div class="m-section">
									<div class="m-section__content">
										<table class="table table-striped m-table">
											<thead>
												<tr>
													<th>Estudiante</th>
													<th>Grado</th>
													<th>Bachiller</th>
													<th>Cuota</th>
													<th>Total</th>
												</tr>
											</thead>
											<tbody>
												<tr v-for="student in students" v-if="student.contracts[0] != undefined && student.contracts[0].fees.length == 1">
													<td v-text="student.name"></td>
													<td v-text="student.contracts[0].enrollment_grade"></td>
													<td v-text="student.contracts[0].enrollment_bachelor"></td>
													<td v-text="student.contracts[0].fees[0].order"></td>
													<td v-text="totalsStudents(student.contracts[0].fees,1)"></td>
												</tr>
												<tr>
													<th>Total</th>
													<td></td>
													<td></td>
													<td></td>
													<td v-text="totalsStudents(null,2)">
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>

						



						<div class="col-md-6" v-if="students != null">
							<div class="m-portlet__head" style="overflow: hidden;">
								<div class="m-portlet__head-caption">
									<div class="m-portlet__head-title">
										<h3 class="m-portlet__head-text">
											2 cuotas o más
										</h3>
									</div>
								</div>
								<div class="m-portlet__head-caption" style="float: right;">
									<div class="m-portlet__head-title row">
										<h6 v-text="more_cuote + ' estudiante(s)'">
										</h6>
									</div>
								</div>
							</div>
							<div class="m-portlet__body">
								<div class="m-section">
									<div class="m-section__content">
										<table class="table table-striped m-table">
											<thead>
												<tr>
													<th>Estudiante</th>
													<th>Grado</th>
													<th>Bachiller</th>
													<th>Cuotas</th>
													<th>Total</th>
												</tr>
											</thead>
											<tbody>
												<tr v-for="student in students" v-if="student.contracts[0] != undefined && student.contracts[0].fees.length > 1">
													<td v-text="student.name"></td>
													<td v-text="student.contracts[0].enrollment_grade"></td>
													<td v-text="student.contracts[0].enrollment_bachelor"></td>
													<td>
														<span v-for="cuota,index in student.contracts[0].fees">
															@{{  cuota.order }} 
															<span v-if="index+1 != student.contracts[0].fees.length">-</span>
														</span>
													</td>
													<td v-text="totalsStudents(student.contracts[0].fees,1)"></td>
												</tr>
												<tr>
													<th>Total</th>
													<td></td>
													<td></td>
													<td></td>
													<td v-text="totalsStudents(null, null)"></td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>


	<!--AGREGAR MONTO AL BANCO ============================================================================-->
	<!--=======================================================================================================-->
	<div class="modal fade" id="m_modal_1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel" v-text="''"></h5>
					<button type="button" @click="closeModal()" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					
					<div class="form-group row justify-content-center">
						<label for="monto" class="label-control col-4">Ingrese el monto</label>
						<input type="number" v-model="amount_bank" class="form-control col-6" id="monto">
					</div>
					
					<table class="col-12 table table-striped m-table">
						<thead>
							<tr>
								<th>Monto</th>
								<th>Fecha</th>
								<th>Usuario</th>
							</tr>
						</thead>
						<tbody>
							<tr v-if="banks != []" v-for="bank in banks">
								<td v-text="bank.amount"></td>
								<td v-text="dateFormat(bank.date)"></td>
								<td v-text="bank.user"></td>
							</tr>
						</tbody>
					</table>
					
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal" @click="closeModal()">Cancelar</button>
					<button type="button" class="btn btn-custom" data-dismiss="modal" @click="registreBank()">Guardar</button>
				</div>
			</div>
		</div>
	</div>
	<!--=======================================================================================================-->
	<!--CERRAR MODAL AGREGAR MONTO AL BANCO =====================================================================-->





@endsection

@section('scripts')

	<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery.flot/0.8.3/jquery.flot.min.js"></script>
	<!-- Load c3.css -->
	<link href="/path/to/c3.css" rel="stylesheet">

	<!-- Load d3.js and c3.js -->
	<script src="/path/to/d3-5.4.0.min.js" charset="utf-8"></script>
	<script src="/path/to/c3.min.js"></script>
	{{-- CANVAS --}}
	<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
	
	<script>
		
		const app = new Vue({

			el : '#app',

			data: {
				data: null,
				year: null,
				month: null,
				banks: [],
				amount_bank: 0.00,
				array_aux: [],
				graph_active: true,
				students: null,
				one_cuote: 0,
				more_cuote: 0
			},

			methods: {

				getData(year = null){

					//console.log(year);

					let me = this;

					let url = year == null ? '/balance/getData/' : ('/balance/getData/' + year);

					console.log(url);

					console.log(url);

					axios
						.get(url)
						.then(function(response){
							console.log(response.data.datos);
							me.data = response.data.datos;
							me.year = response.data.year;
							me.students = response.data.students;
							//me.balance_act = response.data.balanceAct;
							console.log(response.data.balanceAct)
							//me.allData = response.data;
							//me.orderData();
							//me.bank_graph = [];
							//me.deposit_graph = [];

							//me.array_aux = [[]];

							var aux = [];
							var aux2 = [];
							var data_aux = [];
							var data_aux2 = [];
							//console.log(keys);

							for(let obj of Object.keys(response.data.datos)){
								
								if(obj != 'Deposito' && obj != 'Banco' && obj != 'Balance'){
									data_aux=[];
									for(let obj2 of Object.keys(response.data.datos[obj])){
										data_aux.push([parseInt(obj2),response.data.datos[obj][obj2]])
									}

									aux.push({
										label:obj,
										data:data_aux,
										lines:{
											lineWidth:1
										},
										shadowSize:0,
									});
								}
							}

							console.log(aux);

							for (var i = 0; i < aux.length; i++) {
								var axx = [];
			
								for (var j = 0; j < aux[i].data.length; j++) {
									var dataAux =  aux[i].data[j];
									axx.push({
										y: dataAux[1],
										label: me.convertMonth(dataAux[0])
									})
								}
								var ax = {
									type: "stackedColumn",
									name: aux[i].label,
									showInLegend: true,
									// xValueFormatString: "YYYY",
									// yValueFormatString: "#,##0\"%\"",
									dataPoints: axx
								};
								aux2.push(ax);
							}
							console.log(aux2);
							
							/*for(i=0; i<12; i++){
								me.bank_graph.push([(i+1),parseFloat(me.data.Banco[i+1]).toFixed(2)]);
								me.deposit_graph.push([(i+1),parseFloat(me.data.Deposito[i+1]).toFixed(2)]);	
							}*/

							var i=1,r=!0,l=!1,n=!1;

							// $.plot($('#m_flotcharts_5'),
							// 	aux,
							// 	{
							// 		series: {
							// 			stack: i,
							// 			lines: {
							// 				show:l,
							// 				fill:!0,
							// 				steps:n,
							// 				lineWidth:0
							// 			},
							// 			bars: {
							// 				show:r,
							// 				barWidth:0.5,
							// 				lineWidth:0,
							// 				shadowSize:0,
							// 				align:"center"
							// 			}
							// 		},
							// 		grid: {
							// 			tickColor:"#eee",
							// 			borderColor:"#eee",
							// 			borderWidth:1
							// 		}, 
							// 		xaxis: {
							// 			ticks:[
							// 				[1,'Enero'],
							// 				[2,'Febrero'],
							// 				[3,'Marzo'],
							// 				[4,'Abril'],
							// 				[5,'Mayo'],
							// 				[6,'Junio'],
							// 				[7,'Julio'],
							// 				[8,'Agosto'],
							// 				[9,'Septiembre'],
							// 				[10,'Octubre'],
							// 				[11,'Noviembre'],
							// 				[12,'Diciembre']
							// 			]
							// 		}
							// 		});

							var chart = new CanvasJS.Chart("m_flotcharts_5", {
								animationEnabled: true,
								title:{
									text: "Balance"
								},
								// axisX: {
								// 	interval: 1,
								// 	intervalType: "year",
								// 	valueFormatString: "YYYY"
								// },
								axisY: {
									suffix: " $",
									//valueFormatString: "#0.#,.",
								},
								toolTip: {
									shared: true
								},
								legend: {
									reversed: true,
									verticalAlign: "center",
									horizontalAlign: "right"
								},
								data: aux2
							});
							chart.render();


						})
						.catch(function(error){
							console.log(error);
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

				createBank(mes,anho){

					let me = this;

					me.month = mes;

					axios
						.get('balance/createBank/' + mes + '/' + anho)
						.then(function(response){
							me.banks = response.data.banks;
							me.openModal();
						}).catch(function(error){
							console.log(error);
						});
				},


				registreBank(){

					let me = this;

					axios
						.post('balance/registreBank',{
							'amount':me.amount_bank,
							'year': me.year,
							'month': me.month
						})
						.then(function(response){
							console.log(response);
							me.data = null;
							me.getData(me.year);
						}).catch(function(error){
							console.log(error);
						});
				},

				totalsStudents(datos = null, flat){

					var me = this;

					var aux = parseFloat(0).toFixed(2);

					if(flat == 1){

						for(let key of Object.keys(datos)){
							aux = (parseFloat(aux) + parseFloat(datos[key].cost) - parseFloat(datos[key].paid_out)).toFixed(2);
						}

						return aux;
					}
					else if(flat == 2){

						me.one_cuote = 0;

						for(let stud of me.students){
							if(stud.contracts[0] != undefined && stud.contracts[0].fees.length == 1){
								aux = (parseFloat(aux) + parseFloat(stud.contracts[0].fees[0].cost) - parseFloat(stud.contracts[0].fees[0].paid_out));

								me.one_cuote++;
							}
						}

						return aux;
					}
					else{

						me.more_cuote = 0;

						for(let stud of me.students){
							
							if(stud.contracts[0] != undefined && stud.contracts[0].fees.length > 1){
								for(let fee of stud.contracts[0].fees){
									aux = (parseFloat(aux) + parseFloat(fee.cost) - parseFloat(fee.paid_out)).toFixed(2);
								}
								me.more_cuote++;
							}

						}

						return aux;
					}
				},

				dateFormat(date){
					var d = new Date(date);
					return d.getDate() + '/' + parseFloat(d.getMonth() + 1) + '/' + d.getFullYear() + ' ' + this.addZero(d.getHours()) +':' + this.addZero(d.getMinutes());
				},

				addZero(i) {
				  if (i < 10) {
				    i = "0" + i;
				  }
				  return i;
				},

				openModal(){

					$('#m_modal_1').modal('show');
				},

				closeModal(){

					this.banks = [];
					this.month = null;
					this.amount_bank = 0;

					$('.close').click();
				}
			},

			mounted(){
				console.log('hola mundo');
				this.getData();
				/*$.plot($('#m_flotcharts_5'),[[1,10],[20,10]]);
				this.graph_active = true;*/
				
			}
		});


		/*Vue.component('line-chart',{
			extends: VueChartJs.Bar,

			props:{
				deposito: undefined,
			},

			mounted () {
				this.renderChart({
					
					labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'Agosto', 'Septiembre','Octubre','Noviembre','Diciembre'],
					
					datasets: [
						{
							label: 'Data One',
							backgroundColor: '#f87979',
							data :deposito
						}
					]
				},{responsive: true, maintainAspectRatio: false})}
		})*/
	</script>

@endsection