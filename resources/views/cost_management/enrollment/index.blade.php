@extends('layouts.app')

@section('title','Costo de Matricula')

@section('content')
	<div class="m-portlet m-portlet--mobile">
		<div class="m-portlet__head">
			<div class="m-portlet__head-caption">
				<div class="m-portlet__head-title">
					<h3 class="m-portlet__head-text">
						Costo de matricula
					</h3>
				</div>
			</div>
			<div class="m-portlet__head-tools">
				<ul class="m-portlet__nav">
					<li class="m-portlet__nav-item">
                        @can('costs.store_edit')
                            <button type="button" class="btn btn-accent m-btn m-btn--pill m-btn--custom m-btn--icon m-btn--air" @click="openModal(1)">
                            <span>
                                <i class="la la-plus"></i>
                                <span>Agregar Grado</span>
                            </span>
                            </button>
                        @endcan
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
						<th>Grado</th>
						<th>Bachiller</th>
						<th>Costo</th>
						<th>Acciones</th>
					</tr>
				</thead>
				<tbody>
					<tr v-for="dato in datos" :key="dato.id">
						<td v-text="dato.grade"></td>
						<td v-text="dato.bachelor"></td>
						<td v-text="dato.cost"></td>
						<td>
                            @can('costs.store_edit')
                                <button type="button"  style="background-color: #193B64" title="Actualizar" @click="edit(dato.id)" class="btn btn-primary btn-sm">
                                    <i class="fa fa-edit"></i>
                                </button>
                            @endcan
                            @can('costs.cancel')
                                <button type="button" title="Eliminar" @click="destroy(dato.id)" class="btn btn-danger btn-sm">
                                    <i class="fa fa-trash"></i>
                                </button>
                            @endcan
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
                        <a 
                            class="nav-link m-tabs__link" 
                            href="#" 
                            @click.prevent="cambiarPagina(pagination.current_page - 1)"
                            style="background-color: #193B64">
                                <i class="la la-angle-left"></i>
                        </a>
                    </li>
                    <li class="nav-item m-tabs__item" v-for="page in pagesNumber" :key="page">
                        <a 
                            class="btn m-btn--square  btn-primary btn-sm" 
                            href="#" 
                            :class="[page == isActived ? 'active' : '']" 
                            @click.prevent="cambiarPagina(page)" 
                            v-text="page"
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
		</div>
	
	
	<!--MODAL DE CREAR AÑO-->
		<div class="modal fade" id="m_modal_1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
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
								
								<div class="form-group m-form__group row">
									<div class="col-2"><label for="grado">Grado</label></div>
									<div class="col-10"><input type="text" v-model="grade" class="form-control m-input m-input--air m-input--pill" id="grado" aria-describedby="emailHelp" placeholder="Ingrese el grado"></div>
								</div>

								<div class="form-group m-form__group row">
									<div class="col-2"><label for="bachiller">Bachiller</label></div>
									<div class="col-10"><input type="text" v-model="bachelor" class="form-control m-input m-input--air m-input--pill" id="bachiller" placeholder="Ingrese tipo de bachiller"></div>
								</div>
								
                                <div class="form-group m-form__group row">
									<div class="col-2"><label for="costo">Costo</label></div>
									<div class="col-10"><input type="text" v-model="cost" class="form-control m-input m-input--air m-input--pill" id="costo" placeholder="Ingrese el costo"></div>
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
	</div>
@endsection

@section('scripts')
	
	<script>
		const app = new Vue({
		  el: '#app',

		  data: {
		  	id: 0,
		    grade : '',
		    bachelor : '',
		    cost : 0,

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
                { text: 'Grado', value: 'grade' },
                { text: 'Bachiller', value: 'bachelor' },
                { text: 'Costo', value: 'cost'}
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
            }

		  },

		  methods: {

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

                //Validacion de grado
                if (!this.grade) this.errors.push("Grado Requerido");

                //validacion de bachiller
                if (!this.bachelor) this.errors.push("Bachiller Requerido");
                
                //Validacion de costo
                if (!this.cost) this.errors.push("Costo Requerido");
                else if(!this.validateNumber(this.cost)) this.errors.push("Costo debe ser un valor numerico")

                if(this.errors.length) this.error = 1;

                return this.error;
            },

            getData(page)
            {
                let me = this
                var url = '/costos/enrollment/getData?page=' + page +'&criterio=' + this.criterio + '&mostrar=' + this.mostrar + '&buscar=' + this.search

                axios.get(url)
                  .then(function (response) {
                    var respuesta = response.data;
                    me.datos = respuesta.enrollments.data;
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

                axios.post('/costos/enrollment/store',{
                    'grade': this.grade,
                    'bachelor' : this.bachelor,
                    'cost': this.cost,
                })
                .then(function (response){
                    me.closeModal();
                    me.active_save_update = true;
                    me.getData(1);
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
            	.get('/costos/enrollment/edit/' + id)
            	.then(function(response){
            		var respuesta = response.data;
            		objeto = respuesta.enrollment;
            		console.log(objeto);
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
                .put('/costos/enrollment/update',{
                	'id': this.id,
                    'grade': this.grade,
                    'bachelor' : this.bachelor,
                    'cost': this.cost,
                })
                .then(function (response){
                    me.closeModal();
                    me.active_save_update = true;
                    me.getData(1)
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
            	.delete('/costos/enrollment/delete/' + id)
            	.then(function(response){
            		var respuesta = response.data;
                    me.getData(1);
            		alert(respuesta.result);
            	}).catch(function(error){
            		console.log(error);
            	})
            },

            openModal(action, data = [])
            {

            	if (action == 1) {
                    this.action = 1;
                    this.titleModal = "Registrar Matricula"
                }else if (action == 2) {
                    this.action = 2
                    this.titleModal = "Actualizar Matricula"
                    this.id = data['id']
                    this.grade = data['grade']
                    this.bachelor = data['bachelor']
                    this.cost = data['cost']
                }
                
                $('#m_modal_1').modal('show')

            },

            closeModal()
            {
                this.grade = ''
                this.bachelor = ''
                this.cost = 0.00
                this.errors = []

                $('.close').click()
                $('.close').click()
            }

		  },

		  mounted(){
		  	this.getData(1)
		  },

		  watch: {

            mostrar: function(){
                this.getData(1)
            },
            search: function(){
                this.getData(1)
            },
            
          }

		})
	</script>

@endsection