@extends('layouts.app')

@section('title','Balance general')

@section('content')
	<div class="m-portlet">
		<div class="m-portlet__body">

			<!--begin::Section-->
			<div class="m-section">
				<div class="m-section__content">
					

					<!--<div style="margin-bottom: 100px">
						<line-chart v-if="data.Deposito != undefined" :deposito ="data.Deposito"></line-chart>
					</div>-->
					


					<!--<div class="m-portlet m-portlet--tab">
						
						<div class="m-portlet__body">
							<div id="m_flotcharts_5" style="height:350px;">
							</div>
						</div>
					</div>-->




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
								</td>
							</tr>
						</tbody>
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
											2 cuotas o mas
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

	<!--<button @click="openModal()">ABRIR</button>-->
@endsection

@section('scripts')

	<script type="text/javascript" src="http://cdn.jsdelivr.net/jquery.flot/0.8.3/jquery.flot.min.js"></script>
	
	<script>
		
		const app = new Vue({

			el : '#app',

			data: {
				students: null,
				month: null,
				data: null,
				one_cuote: 0,
				more_cuote: 0
			},

			methods: {
				getDataStudents(){
					let me = this;

					axios
						.get('/balance/getDataStudents')
						.then(function(response){
							me.students = response.data.students;
							me.data = response.data.datos;
							console.log(response.data.datos);
							console.log(response.data.students);

							var aux = [];
							var data_aux = [];
							//console.log(keys);

							for(let obj of Object.keys(response.data.datos)){
								
								if(obj != 'Balance'){
									data_aux=[];

									for(let obj2 of Object.keys(response.data.datos[obj])){
										data_aux.push([parseInt(obj2),response.data.datos[obj][obj2]])
									}
									
									aux.push({
										label:obj,
										data:data_aux,
										lines:{lineWidth:1},
										shadowSize:0,
									});
								}
							}

							console.log(aux);

							
							/*for(i=0; i<12; i++){
								me.bank_graph.push([(i+1),parseFloat(me.data.Banco[i+1]).toFixed(2)]);
								me.deposit_graph.push([(i+1),parseFloat(me.data.Deposito[i+1]).toFixed(2)]);	
							}*/

							var i=0,r=!0,l=!1,n=!1;

							$.plot($('#m_flotcharts_5'),aux,{series:{stack:i,lines:{show:l,fill:!0,steps:n,lineWidth:0},bars:{show:r,barWidth:.5,lineWidth:0,shadowSize:0,align:"center"}},grid:{tickColor:"#eee",borderColor:"#eee",borderWidth:1}, xaxis: {ticks:[[1,'Enero'],[2,'Febrero'],[3,'Marzo'],[4,'Abril'],[5,'Mayo'],[6,'Junio'],[7,'Julio'],[8,'Agosto'],[9,'Septiembre'],[10,'Octubre'],[11,'Noviembre'],[12,'Diciembre']]}});


						}).catch(function(error){
							console.log(error);
						});
				},

				totalsStudents(datos = null, flat){

					var me = this;

					var aux = parseFloat(0);

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
									aux = (parseFloat(aux) + parseFloat(fee.cost) - parseFloat(fee.paid_out));
								}
								me.more_cuote++;
							}

						}

						return aux;								
					}
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
			},

			mounted(){
				console.log('hola mundo');
				this.getDataStudents();
			}
		});
	</script>

@endsection