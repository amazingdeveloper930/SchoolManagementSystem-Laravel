@extends('layouts.app')

@section('title','Costo de Anualidad')

@section('content')
	<div class="m-portlet m-portlet--mobile">
		<div class="m-portlet__head">
			<div class="m-portlet__head-caption">
				<div class="m-portlet__head-title">
					<h3 class="m-portlet__head-text">
						Costos de anualidad
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
                                <span>Agregar año</span>
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
                        <th>Año</th>
						<th>Costo</th>
						<th>Descuento</th>
						<th>Fecha Maxima</th>
						<th>Segundo Mes</th>
                        <th>Acciones</th>
					</tr>
				</thead>
				<tbody>
					<tr v-for="dato in datos" :key="dato.id">
						<td v-text="dato.year"></td>
                        <td v-text="dato.cost"></td>
						<td v-text="dato.discount"></td>
						<td v-text="dato.maximum_date"></td>
						<td v-text="months[dato.second_month-1].name"></td>
						<td>
                            @can('costs.store_edit')
                                <button type="button"  style="background-color: #193B64" title="Actualizar" @click="edit(dato.id)" class="btn btn-primary btn-sm">
                                <i class="fa fa-edit"></i>
                                </button>
                            @endcan
							
                            @can('costs.cancel')
                                <button type="button"title="Eliminar" @click="destroy(dato.id)" class="btn btn-danger btn-sm">
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
                                        <div class="col-3"><label for="anho">Año</label></div>
                                        <div class="col-9">
                                            <Select v-model="year" class="form-control m-input m-input--air m-input--pill" id="anho" placeholder="Seleccione el año">
                                                <option 
                                                    v-for="n in 10" 
                                                    :key="n" 
                                                    :value="2016 + n - 1" 
                                                    v-text="2016 + n - 1">
                                                </option>
                                            </Select>
                                        </div>
                                    </div>                                


    								<div class="form-group m-form__group row">
    									<div class="col-3"><label for="costo">Costo</label></div>
    									<div class="col-9"><input type="text" v-model="cost" class="form-control m-input m-input--air m-input--pill" id="costo" placeholder="Ingrese el costo"></div>
    								</div>

    								<div class="form-group m-form__group row">
    									<div class="col-3"><label for="descuento">Descuento</label></div>
    									<div class="col-9"><input type="text" v-model="discount" class="form-control m-input m-input--air m-input--pill" id="descuento" placeholder="Ingrese el descuento"></div>
    								</div>

    								<div class="form-group m-form__group row">
    									<div class="col-3"><label for="datapicker">Fecha maxima</label></div>
    									<!--<div class="col-9"><input type="date" v-model="maximum_date" class="form-control m-input m-input--air m-input--pill"  placeholder="Fecha maxima"></div>-->
                                        <my-date-picker class="col-9" id="datapicker" v-model="maximum_date"></my-date-picker>
    								</div>

    								<div class="form-group m-form__group row">
    									<div class="col-3"><label for="segundo_mes">Mes de 2da cuota</label></div>
    									<div class="col-9">
                                            <select v-model="second_month" class="form-control m-input m-input--air m-input--pill" id="segundo_mes" placeholder="Segundo mes">
                                                <option 
                                                    v-for="month in months" 
                                                    :value="month.value" 
                                                    v-text="month.name">
                                                </option>
                                            </select>
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


    <script src="{{asset('metronic/assets3/vendors/base/vendors.bundle.js')}}" type="text/javascript"></script>
    <script src="{{asset('metronic/assets3/demo/default/base/scripts.bundle.js')}}" type="text/javascript"></script>
    <script src="{{asset('metronic/assets3/demo/default/custom/crud/forms/widgets/bootstrap-datepicker.js')}}" type="text/javascript"></script>
		
	<script type="text/javascript">
		
		const app = new Vue({
		  el: '#app',

		  data: {
		  	id: 0,
            year: 0,
		    cost : 0.0,
		    discount : 0.0,
		    maximum_date : '',
		    second_month : '',

            //DESACTIVAR GUARDAR/ACTUALIZAR DURANTE UNA SOLICITUD
            active_save_update: true,


            toYear: 0,

            months: [
                {value:1, name: 'Enero'},
                {value:2, name: 'Febrero'},
                {value:3, name: 'Marzo'},
                {value:4, name: 'Abril'},
                {value:5, name: 'Mayo'},
                {value:6, name: 'Junio'},
                {value:7, name: 'Julio'},
                {value:8, name: 'Agosto'},
                {value:9, name: 'Septiembre'},
                {value:10, name: 'Octubre'},
                {value:11, name: 'Noviembre'},
                {value:12, name: 'Diciembre'},
            ],

		    datos: [],
            enrollments : [],

		    error : 0,
		    errors : [],

		    search: '',
            mostrar: 10,

            shows: [
                10,
                20,
                50
            ],

            criterio: '',
            options: [
                { text: 'Filtrar Por:' , value: ''},
                { text: 'Año', value: 'year'},
                { text: 'Costo', value: 'cost' },
                { text: 'Descuento', value: 'discount'},
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

            validateInteger: function(number){
                var aux=0;

                for(i=0;i<number.length;i++){
                    if(number.charAt(i).charCodeAt(0)<48 || number.charAt(i).charCodeAt(0)>58){
                        return 0;       
                    }
                }

                return 1;
            },

		  	cambiarPagina(page){
                let me = this;
                //Actualizar pagina actual
                me.pagination.current_page = page;
                me.getData(page);
            },

		  	checkForm(){
                this.error = 0;
                this.errors = [];

                //Validar año
                if(!this.year) this.errors.push("El año es requerido");
                else if(!this.validateInteger(this.year)) this.errors.push("El año debe ser un valor entero positivo");

                //Validacion de costo
                if (!this.cost) this.errors.push("Costo Requerido");
                else if(!this.validateNumber(this.cost)) this.errors.push('Costo debe ser un valor numerico')
                
                //Validacion de descuento
                if (this.discount=='') this.errors.push("Descuento Requerido");
                else if(!this.validateNumber(this.discount)) this.errors.push('Descuento debe ser un valor numerico')

                //Validacion de fechas
                if(!this.maximum_date) this.errors.push('Fecha maxima requerida');
                
                //Validacion de mes de segunda cuota
                if(!this.second_month) this.errors.push('Mes de segunda cuota es requerido');

                if(this.errors.length) this.error = 1;

                return this.error;
            },

            getData(page){
                let me = this
                var url = '/costos/annuity/getData?page=' + page + '&criterio=' + this.criterio + '&mostrar=' + this.mostrar + '&buscar=' + this.search

                axios.get(url)
                  .then(function (response) {
                    var respuesta = response.data;
                    me.datos = respuesta.annuitys.data;
                    me.pagination = respuesta.pagination;
                    me.toYear = parseInt(respuesta.toYear);
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
                    .post('/costos/annuity/store',{
                        'year': this.year,
                        'cost' : this.cost,
                        'discount': this.discount,
                        'maximum_date' : this.maximum_date,
                        'second_month' : this.second_month
                    })
                    .then(function (response){
                        me.closeModal()
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
                	.get('/costos/annuity/edit/' + id)
                	.then(function(response){
                		var respuesta = response.data;
                		objeto = respuesta.annuity;
                		console.log(objeto);
                		me.openModal(2,objeto);
                	}).catch(function(error){
                		console.log(error);
                	})
            },

            update(){

                /*console.log(this.maximum_date);

                return;*/

                if (this.checkForm()) {
                    return;
                }

                let me = this;

                me.active_save_update = false;

                axios
                    .put('/costos/annuity/update',{
                    	'id': this.id,
                        'year': this.year,
                        'cost' : this.cost,
                        'discount': this.discount,
                        'maximum_date' : this.maximum_date,
                        'second_month' : this.second_month
                    })
                    .then(function (response){
                        me.closeModal()
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
                	.delete('/costos/annuity/delete/' + id)
                	.then(function(response){
                		var respuesta = response.data;
                        me.getData(1);
                		alert(respuesta.result);
                	}).catch(function(error){
                		console.log(error);
                	})
            },

            openModal(action, data = []){
                this.getAllEnrollments();

            	if (action == 1) {
            		this.closeModal();
                    this.action = 1;
                    this.titleModal = "Registrar año"
                }else if (action == 2) {
                    this.action = 2
                    this.titleModal = "Actualizar año"
                    this.id = data['id']
                    this.year = data['year']
                    this.cost = data['cost']
                    this.discount = data['discount']
                    this.maximum_date = data['maximum_date']
                    this.second_month = data['second_month']
                }
                
                $('#m_modal_1').modal('show')
            },

            getAllEnrollments(){

                let me = this;
                
                axios.
                    get('/costos/enrollment/getAll')
                    .then(function(response){
                        me.enrollments = response.data.enrollments;
                    }).catch(function(error){
                        console.log('Error de solicitud');
                    });
            },

            closeModal(){

                this.year = 0,
                this.cost = 0.0
                this.discount = 0.0
                this.maximum_date = ''
                this.second_month = ''
                this.errors = []

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