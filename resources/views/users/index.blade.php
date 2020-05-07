@extends('layouts.app')

@section('title','Usuarios')

@section('content')
	<div class="m-portlet m-portlet--mobile">
		<div class="m-portlet__head">
			<div class="m-portlet__head-caption">
				<div class="m-portlet__head-title">
					<h3 class="m-portlet__head-text">
						Usuarios
					</h3>
				</div>
			</div>
			<div class="m-portlet__head-tools">
				<ul class="m-portlet__nav">
					<li class="m-portlet__nav-item">
						<button type="button" class="btn btn-accent m-btn m-btn--pill m-btn--custom m-btn--icon m-btn--air" @click="openModal(1)">
		                    <span>
								<i class="la la-plus"></i>
								<span>Agregar Usuario</span>
							</span>
		                </button>
					</li>
				</ul>
			</div>
		</div>

		<div class="m-portlet__body">

			<!--begin: Datatable -->
			<table class="table table-striped- table-bordered table-hover table-checkable" id="m_table_1">
				<div class="form-group m-form__group">
					<span>Mostrar</span>
					<select v-model="mostrar" class="m-input m-input--air m-input--pill" id="exampleSelect1">
						<option v-for="show in shows" v-bind:value="show" v-text="show">
                        </option>
					</select>
					<span>Registros</span>
					
					<div class="pull-right">
						<select v-model="criterio" class="m-input m-input--air m-input--pill" id="exampleSelect1">
							<option v-for="option in options" v-bind:value="option.value" v-text="option.text">
                            </option>
						</select>

						<input v-model="search" type="text" class="m-input m-input--air m-input--pill" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Buscar">
					</div>
				</div>
				<thead>
					<tr>
                        <th>Nombre</th>
						<th>Usuario</th>
						<th>Fecha de Creación</th>
                        <th>Acciones</th>
					</tr>
				</thead>
				<tbody>
					<tr v-for="dato in datos" :key="dato.id">
						<td v-text="dato.name"></td>
                        <td v-text="dato.email"></td>
						<td v-text="dateFormat(dato.created_at)"></td>
						<td>
							<button type="button"  style="background-color: #193B64" title="Actualizar" @click="edit(dato.id)" class="btn btn-primary btn-sm">
								<i class="fa fa-edit"></i>
							</button>
							<button type="button"title="Eliminar" @click="destroy(dato.id)" class="btn btn-danger btn-sm">
								<i class="fa fa-trash"></i>
							</button>
						</td>
					</tr>
				</tbody>
			</table>
			
			<div
				style="margin-right: 0px; margin-left: 0px; padding-right: 0px; padding-left: 0px;" 
				class="m-portlet__head-tools row justify-content-between">

                <p v-text="'Mostrando registros del ' + pagination.from + ' al ' + pagination.to + ' de un total de ' + pagination.total">
                </p>
            

                <!--<ul class="nav nav-pills nav-pills--brand m-nav-pills--align-right m-nav-pills--btn-pill m-nav-pills--btn-sm" role="tablist">
					<li class="nav-item m-tabs__item" v-if="pagination.current_page > 1">
						<a class="nav-link m-tabs__link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1)">
							<i class="la la-angle-left"></i>
						</a>
					</li>
					<li class="nav-item m-tabs__item" v-for="page in pagesNumber" :key="page">
						<a class="btn m-btn--square  btn-primary btn-sm" href="#" :class="[page == isActived ? 'active' : '']" @click.prevent="cambiarPagina(page)" v-text="page">
						</a>
					</li>
					<li class="nav-item m-tabs__item" v-if="pagination.current_page < pagination.last_page">
						<a class="nav-link m-tabs__link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1)">
							<i class="la la-angle-right"></i>
						</a>
					</li>
				</ul>-->

				<nav aria-label="...">
                    <ul class="pagination">
                        <li class="page-item" 
                            v-if="pagination.current_page > 1">
                                <a  class="page-link" 
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
                                <a  href="#"
                                    class="page-link "
                                    @click.prevent="cambiarPagina(page)">
                                        <span 
                                            :style="page == isActived ? 'color:white' : ''"
                                            v-text="page"></span>
                                </a>
                        </li>

                        <li class="page-item" 
                            v-if="pagination.current_page < pagination.last_page">
                                <a  class="page-link" 
                                    href="#"
                                    @click.prevent="cambiarPagina(pagination.current_page + 1)">
                                        <i class="la la-angle-right" style="font-size:12px"></i>
                                </a>
                        </li>
                    </ul>
                </nav>
            </div>
		</div>
	
	
	<!--MODAL DE CREAR USUARIO-->
		<div class="modal fade" id="m_modal_1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel" v-text="titleModal"></h5>
						<button type="button" @click="closeModal()" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">

						<!--begin::Form-->
						<form class="m-form m-form--fit m-form--label-align-right">
							<div class="m-portlet__body">

								<div class="row">
									
									<div class="col-md-6">

										<div class="form-group m-form__group">
											<div class="row">
												<div class="col-3"><label for="costo">Nombre Completo</label></div>
												<div class="col-9"><input type="text" v-model="name" class="form-control m-input m-input--air m-input--pill" id="costo" placeholder="Ingrese nombre"></div>
											</div>
										</div>

										<div class="form-group m-form__group">
											<div class="row">
												<div class="col-3"><label for="descuento">Email</label></div>
												<div class="col-9"><input type="text" v-model="email" class="form-control m-input m-input--air m-input--pill" id="descuento" placeholder="Ingrese el Email"></div>
											</div>
										</div>

										<div class="form-group m-form__group">
											<div class="row">
												<div class="col-3"><label for="descuento">Contraseña</label></div>
												<div class="col-9"><input type="password" v-model="password" class="form-control m-input m-input--air m-input--pill" id="descuento" placeholder="Ingrese Contraseña"></div>
											</div>
										</div>

										<div class="form-group m-form__group">
											<div class="row">
												<div class="col-3"><label for="descuento">Repetir Contraseña</label></div>
												<div class="col-9"><input type="password" v-model="confirm_password" class="form-control m-input m-input--air m-input--pill" id="descuento" placeholder="Confirme Contraseña"></div>
											</div>
										</div>

										<div class="form-group m-form__group">
											<div class="m-checkbox-list">
												<label class="m-checkbox">
													<input type="checkbox" v-model="super_admin"> Super Administrador
													<span></span>
												</label>
											</div>
										</div>
										
									</div>
									<div class="col-md-6">

										<table v-if="!super_admin" class="table table-bordered m-table">
											<thead>
												<tr>
													<th>Modulo</th>
													<th>Consultar</th>
													<th>Crear/Editar</th>
													<th>Cancelar</th>
												</tr>
											</thead>
											<tbody>
												{{-- Tablero --}}
												<tr>
													<td>Tablero</td>

													<td>
													<label class="m-checkbox">
													<input v-model="permisions" value="board.index" type="checkbox">
													<span></span>
													</label>
													</td>

													<td>
													<label class="m-checkbox">
													<input v-model="permisions" value="board.store_edit" type="checkbox">
													<span></span>
													</label>
													</td>

													<td>
													<label class="m-checkbox">
													<input v-model="permisions" value="board.cancel" type="checkbox">
													<span></span>
													</label>
													</td>
												</tr>

												{{-- Estudiantes --}}
												<tr>
													<td>Estudiantes</td>

													<td>
													<label class="m-checkbox">
													<input v-model="permisions" value="students.index" type="checkbox">
													<span></span>
													</label>
													</td>

													<td>
													<label class="m-checkbox">
													<input v-model="permisions" value="students.store_edit" type="checkbox">
													<span></span>
													</label>
													</td>

													<td>
													<label class="m-checkbox">
													<input v-model="permisions" value="students.cancel" type="checkbox">
													<span></span>
													</label>
													</td>
												</tr>

												{{-- Pagos --}}
												<tr>
													<td>Pagos</td>

													<td>
													<label class="m-checkbox">
													<input v-model="permisions" value="payments.index" type="checkbox">
													<span></span>
													</label>
													</td>

													<td>
													<label class="m-checkbox">
													<input v-model="permisions" value="payments.store_edit" type="checkbox">
													<span></span>
													</label>
													</td>

													<td>
													<label class="m-checkbox">
													<input v-model="permisions" value="payments.cancel" type="checkbox">
													<span></span>
													</label>
													</td>
												</tr>

												{{-- Reportes --}}
												<tr>
													<td>Reportes</td>

													<td>
													<label class="m-checkbox">
													<input v-model="permisions" value="reports.index" type="checkbox">
													<span></span>
													</label>
													</td>

													<td>
													<label class="m-checkbox">
													<input v-model="permisions" value="reports.store_edit" type="checkbox">
													<span></span>
													</label>
													</td>

													<td>
													<label class="m-checkbox">
													<input v-model="permisions" value="reports.cancel" type="checkbox">
													<span></span>
													</label>
													</td>
												</tr>

												{{-- Gestion de Costos --}}
												<tr>
													<td>Gestion de Costos</td>

													<td>
													<label class="m-checkbox">
													<input v-model="permisions" value="costs.index" type="checkbox">
													<span></span>
													</label>
													</td>

													<td>
													<label class="m-checkbox">
													<input v-model="permisions" value="costs.store_edit" type="checkbox">
													<span></span>
													</label>
													</td>

													<td>
													<label class="m-checkbox">
													<input v-model="permisions" value="costs.cancel" type="checkbox">
													<span></span>
													</label>
													</td>
												</tr>
												
											</tbody>
										</table>
										
									</div>

								</div>

                                <!--ERRORES DE VALIDACION-->
                                <div v-show="error" class="form-group row div-error">
                                    <div class="text-center text-error" style="margin:0 auto">
                                        <div class="text-danger" v-for="error in errors" :key="error" v-text="error">
                                            
                                        </div>
                                    </div>
                                </div>

							</div>
						</form>
					</div>
					<div class="modal-footer">
						<!--<button type="button" class="btn btn-secondary" @click="closeModal" data-dismiss="modal">Cerrar</button>
						<button type="button" v-if="action==1" @click="registre()" class="btn btn-primary">Guardar</button>
						<button type="button" v-if="action==2" @click="update()" class="btn btn-primary">Actualizar</button>-->
						<button type="button" class="btn btn-secondary" :disabled="!active_save_update" @click="closeModal" data-dismiss="modal">Cerrar</button>
						<button type="button" v-if="action==1" :disabled="!active_save_update" @click="registre()" class="btn btn-primary">
                            <span v-text="active_save_update ? 'Guardar' : 'Cargando...'"></span>
                        </button>
						<button type="button" v-if="action==2" :disabled="!active_save_update" @click="update()" class="btn btn-primary">
                            <span v-text="active_save_update ? 'Actualizar' : 'Cargando...'"></span>
                        </button>
					</div>
				</div>
			</div>
		</div>
	<!--MODAL DE CREAR AÑO-->

	</div>
@endsection


@section('scripts')

    {{-- <script src="{{asset('metronic/assets3/vendors/base/vendors.bundle.js')}}" type="text/javascript"></script>
    <script src="{{asset('metronic/assets3/demo/default/base/scripts.bundle.js')}}" type="text/javascript"></script>
    <script src="{{asset('metronic/assets3/demo/default/custom/crud/forms/widgets/bootstrap-datepicker.js')}}" type="text/javascript"></script> --}}
		
	<script type="text/javascript">
		
		const app = new Vue({
		  el: '#app',

		  data: {
		  	id: 0,
            name: '',
		    email: '',
		    password: '',
		    confirm_password: '',

		    super_admin: false,

		    permisions: [],

		    datos: [],

		    error : 0,
		    errors : [],

		    search: '',
            mostrar: 10,

            active_save_update: true,

            shows: [
                10,
                20,
                50
            ],

            criterio: '',
            options: [
                { text: 'Filtrar Por:' , value: ''},
                { text: 'Nombre', value: 'name'},
                { text: 'Usuario', value: 'email' },
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

            validEmail: function (email) {
              var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
              return re.test(email);
            },

		  	cambiarPagina(page)
            {
                let me = this;
                //Actualizar pagina actual
                me.pagination.current_page = page;
                me.getData(page);
            },

		  	checkForm(){
                this.error = 0;
                this.errors = [];

                //Validar nombre
                if (!this.name) this.errors.push("Nombre Requerido");

                //Validacion de correo
               if (!this.email) {
                    this.errors.push('Correo Requerido.');
                } else if (!this.validEmail(this.email)) {
                    this.errors.push('Correo Invalido.');
                }

                if (this.password != this.confirm_password) this.errors.push("Contráseñas no son iguales");

                if(this.errors.length) this.error = 1;

                return this.error;
            },

            getData(page)
            {
                let me = this
                var url = '/users/getData?page=' + page + '&criterio=' + this.criterio + '&mostrar=' + this.mostrar + '&buscar=' + this.search

                axios.get(url)
                  .then(function (response) {
                    var respuesta = response.data;
                    me.datos = respuesta.users.data;
                    me.pagination = respuesta.pagination;
                  })
                  .catch(function (error) {
                    // handle error
                    console.log(error);
                  });
            },


            registre(){

                if (this.checkForm()) {
                    return;
                }

                let me = this;
                me.active_save_update = false;

                axios
                    .post('/users/store',{
                        'name': this.name,
                        'email' : this.email,
                        'password': this.password,
                        'permisions': this.permisions,
                        'super_admin': this.super_admin,
                    })
                    .then(function (response){
                        me.closeModal();
                        me.getData(1);
                        me.active_save_update = true;
                    })
                    .catch(function (error) {
                        console.log(error);
                        me.active_save_update = true;
                    })
            },

            edit(id){

            	let me = this;
            	let objeto;

            	axios
                	.get('/users/edit/' + id)
                	.then(function(response){
                		var respuesta = response.data;
                		objeto = respuesta.user;
                		me.permisions = respuesta.permissions;
                		me.super_admin = respuesta.super_admin;
                		me.openModal(2,objeto);
                	}).catch(function(error){
                		console.log(error);
                	})
            },

            update(){

                if (this.checkForm()) {
                    return;
                }

                let me = this;
                me.active_save_update = false;

                axios
                    .put('/users/update',{
                    	'id': this.id,
                        'name': this.name,
                        'email' : this.email,
                        'password': this.password,
                        'permisions': this.permisions,
                        'super_admin': this.super_admin,
                    })
                    .then(function (response){
                        me.closeModal();
                        me.getData(1);
                        me.active_save_update = true;
                    })
                    .catch(function (error) {
                        console.log(error);
                        me.active_save_update = true;
                    })
            },

            destroy(id){
            	let me = this;

            	if(!confirm('¿Seguro que desea eliminar este registro?')) return 0;

            	axios
                	.delete('/users/delete/' + id)
                	.then(function(response){
                		var respuesta = response.data;
                        me.getData(1);
                		alert(respuesta.result);
                	}).catch(function(error){
                		console.log(error);
                	})
            },

            dateFormat(date){
					var d = new Date(date);
					return d.getDate() + '/' + d.getMonth() + 1 + '/' + d.getFullYear();
			},

            openModal(action, data = [])
            {

            	if (action == 1) {
            		this.closeModal();
                    this.action = 1;
                    this.titleModal = "Registrar Usuario"
                }else if (action == 2) {
                    this.action = 2
                    this.titleModal = "Actualizar Usuario"
                    this.id = data['id']
                    this.name = data['name']
                    this.email = data['email']
                }
                
                $('#m_modal_1').modal('show')
            },

            closeModal()
            {
                this.name = ''
                this.email = ''
                this.password = ''
                this.confirm_password = ''
                this.permisions = []
                this.errors = []
                this.super_admin = false

                $('.close').click()
                $('.close').click()
            },
		  },

            mounted(){
               	this.getData(1);

                //$('#m_datepicker_3').datepicker();
            },

		  watch: {

            mostrar: function(){
                this.getData(1)
            },
            search: function(){
                this.getData(1)
            },
            

          }

		});



        Vue.component('my-date-picker',{
            template: '<input type="text" v-datepicker class="form-control m-input m-input--air m-input--pill" :value="value" @input="update($event.target.value)" id="datapicker">',
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