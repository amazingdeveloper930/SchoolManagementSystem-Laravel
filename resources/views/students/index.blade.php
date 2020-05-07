@extends('layouts.app')

@section('title','Estudiantes')

@section('content')

    <div class="m-portlet m-portlet--mobile">
	
        <!--ENCABEZADO =======================================================================================================================-->
        <!--==================================================================================================================================-->
    	<div class="m-portlet__head">
			<div class="m-portlet__head-caption">
				<div class="m-portlet__head-title">
					<h3 class="m-portlet__head-text">
						Estudiantes
					</h3>
				</div>
			</div>
			<div class="m-portlet__head-tools">
				<ul class="m-portlet__nav">
					<li class="m-portlet__nav-item">
                        @can('students.store_edit')
                            <button type="button" class="btn btn-accent m-btn m-btn--pill m-btn--custom m-btn--icon m-btn--air" @click="openModal(1)">
                            <span>
                                <i class="la la-plus"></i>
                                <span>Agregar Estudiante</span>
                            </span>
                            </button>
                        @endcan
					</li>
				</ul>
			</div>
		</div>
        <!--===================================================================================================================================-->
        <!--FINAL DE ENCABEZADO ===============================================================================================================-->

		
        <!--AQUI SE POSICIONAN LOS ESTUDIANTES================================================================================================-->
        <!--==================================================================================================================================-->
        <div class="m-portlet__body">

			<!--begin: Datatable -->
			<table class="table table-striped- table-bordered table-hover table-checkable " id="m_table_1">

				<div class="form-group m-form__group">

					<span>Mostrar</span>
					<select v-model="mostrar" class="m-input m-input--air m-input--pill" id="exampleSelect1">                        
                        <option v-for="show in shows" :value="show" v-text="show">

                        </option>
					</select>

					<span>Registros</span>

					<div class="pull-right">

                        <span>Estado</span>
                        <select v-model="student_state_o" class="m-input m-input--air m-input--pill mr-4" id="exampleSelect1">
                            <option v-for="state in student_state" :value="state.value" v-text="state.text" class="">
                            </option>
                        </select>

                        <span>Grado</span>
                        <select v-model="grade" class="m-input m-input--air m-input--pill mr-4" id="exampleSelect1">
                            <option :value="''" v-text="'Todos'"></option>
                            <option v-for="enrrollement in enrrollements" :value="enrrollement.id" v-text="enrrollement.grade + ' ' + enrrollement.bachelor" class="">
                            </option>
                        </select>

						<!--<select v-model="criterio" @change="criterio == 'name' ? search='Estudiante' : search=''" class="m-input m-input--air m-input--pill" id="exampleSelect1">	
                            <option v-for="option in options" :value="option.value" v-text="option.text">
                            </option>
						</select>-->

						<input v-model="search" type="text" class="m-input m-input--air m-input--pill" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Buscar">

                        <!--<select v-if="criterio == 'status'" v-model="search">
                            <option value="">Todos</option>
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                            <option value="2">Suspendido</option>
                        </select>-->
					</div>
				</div>

				<thead>
					<tr>
						<th>Estudiante
                            <a href="#" @click.prevent="order('name')">
                                <span>
                                    <i class="fa fa-long-arrow-alt-down"></i>
                                    <i class="fa fa-long-arrow-alt-up"></i>
                                </span>
                            </a>
                        </th>
						<th>ID personal
                            <a href="#" @click.prevent="order('personal_id')">
                                <span>
                                    <i class="fa fa-long-arrow-alt-down"></i>
                                    <i class="fa fa-long-arrow-alt-up"></i>
                                </span>
                            </a>
                        </th>
						<th>Acudiente
                            <a href="#" @click.prevent="order('attendant')">
                                <span>
                                    <i class="fa fa-long-arrow-alt-down"></i>
                                    <i class="fa fa-long-arrow-alt-up"></i>
                                </span>
                            </a>
                        </th>
						<th>Paz y Salvo
                            <a href="#" @click.prevent="order('peace_save')">
                                <span>
                                    <i class="fa fa-long-arrow-alt-down"></i>
                                    <i class="fa fa-long-arrow-alt-up"></i>
                                </span>
                            </a>
                        </th>
						<th>Estado</th>
						<th>Acciones</th>
					</tr>
				</thead>
				<tbody>
					<tr v-for="dato in datos" :key="dato.id">
						<td v-text="dato.name"></td>
						<td v-text="dato.personal_id"></td>
						<td v-text="dato.attendant"></td>
						<td :style="'color:' + dato.peace_save == 1 ? '#00AA00' : 'AA0000'">
                            <span >
                                <i :class="dato.peace_save == 1 ? 'fa fa-check' : 'fa fa-times'"></i>
                            </span>
                        </td>
						<td>
                            <span v-if="dato.status==0">Inactivo</span>
                            <span v-else-if="dato.status==1">Activo</span>
                            <span v-else-if="dato.status==4">Retirado</span>
                            <span v-else>Suspendido</span>
                        </td>
						<td>
							<a title="Ver" :href="'/students/' + dato.id" class="btn btn-success btn-sm">
								<i class="fa fa-eye"></i>
							</a>
                            @can('students.store_edit')
                                <button type="button"  style="background-color: #193B64" title="Actualizar" @click="edit(dato.id)" class="btn btn-primary btn-sm">
                                    <i class="fa fa-edit"></i>
                                </button>
                            @endcan
						</td>
					</tr>
				</tbody>
			</table>

			<div
                style="margin-right: 0px; margin-left: 0px; padding-right: 0px; padding-left: 0px;"
                class="m-portlet__head-tools row justify-content-between">

				<p
                    v-text="'Mostrando registros del ' + pagination.from + ' al ' + pagination.to + ' de un total de ' + pagination.total"
                    class="d-inline-block">
                </p>

                <!--<ul
                    class="nav nav-pills nav-pills--brand m-nav-pills--align-right m-nav-pills--btn-pill m-nav-pills--btn-sm"
                    role="tablist">

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
        <!--FINAL DE POSICIONAMIENTO DE LOS ESTUDIANTES ======================================================================================-->
        <!--==================================================================================================================================-->


        <!--MODAL PARA CREAR ESTUDIANTES ======================================================================================-->
        <!--===================================================================================================================-->
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
						<form class="m-form m-form--fit m-form--label-align-right" enctype="multipart/form-data">
							<div class="m-portlet__body">

                                <div class="form-group m-form__group row">
                                    <div class="col-2"><label for="personal_id">Id Personal</label></div>
                                    <div class="col-10"><input type="text" v-model="personal_id" class="form-control m-input m-input--air m-input--pill" id="personal_id" placeholder="Ingrese ID Personal"></div>
                                </div>

								<div class="form-group m-form__group row">
									<div class="col-2"><label for="nombre">Nombre</label></div>
                                    <div class="col-10"><input type="text" v-model="name" class="form-control m-input m-input--air m-input--pill" id="nombre" aria-describedby="emailHelp" placeholder="Ingrese el Nombre"></div>
								</div>

								<div class="form-group m-form__group row">
									<div class="col-2"><label for="correo">Email</label></div>
                                    <div class="col-10"><input type="email" v-model="email" class="form-control m-input m-input--air m-input--pill" id="correo" placeholder="Ingrese el Correo"></div>
								</div>

								<div class="form-group m-form__group row">
									<div class="col-2"><label for="telefono">Teléfono</label></div>
                                    <div class="col-10"><input type="text" v-model="phone" class="form-control m-input m-input--air m-input--pill" id="telefono" placeholder="Ingrese el numero de telefono"></div>
								</div>

								<div class="form-group m-form__group row">
									<div class="col-2"><label for="acudiente">Acudiente</label></div>
                                    <div class="col-10"><input type="text" v-model="attendant" class="form-control m-input m-input--air m-input--pill" id="acudiente" placeholder="Ingrese el Acudiente"></div>
								</div>

                                <div class="form-group m-form__group row">
                                    <div class="col-2"><label for="acudiente">Imagen</label></div>
                                    <div class="col-10"><input type="file" @change="onImageChange" class=" m-input m-input--air m-input--pill" id="iamgen" placeholder="Cargue la imagen"></div>
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
						
                        <button type="button" v-if="action == 1" :disabled="!active_save_update" @click="registre()" class="btn btn-primary">
                            <span v-text="active_save_update ? 'Guardar' : 'Cargando...'"></span>
                        </button>
						
                        <button type="button" v-if="action == 2" :disabled="!active_save_update" @click="update()" class="btn btn-primary">
                            <span v-text="active_save_update ? 'Actualizar' : 'Cargando...'"></span>
                        </button>
					</div>
				</div>
			</div>
		</div>
        <!--===================================================================================================================-->
        <!--FINAL MODAL CREAR ESTUDIANTES =====================================================================================-->
	</div>
@endsection

@section('scripts')

	<script>
		const app = new Vue({
		  el: '#app',

		  data: {
		  	id: 0,
		    name : '',
		    email : '',
		    phone : '',
		    attendant : '',
            image : '',
            personal_id: '',

		    datos: [],
            enrrollements: [],

		    error : 0,
		    errors : [],

            grade: 0,
		    search: '',
            mostrar: 10,

            shows: [
                10,
                20,
                50
            ],

            //DESACTIVAR BOTON DE GUARDAR (UBICADO EN EL MODAL) CUANDO SE REALICE LA SOLICITUD
            active_save_update: true,

            criterio: '',
            grade: '',
            options: [
                { text: 'Filtrar Por:' , value: ''},
                { text: 'Estudiante', value: 'name' },
                { text: 'Id Personal', value: 'personal_id' },
                { text: 'Acudiente', value: 'attendant'},
                { text: 'Paz y salvo', value: 'peace_save' },
                { text: 'Estado', value: 'status'},
            ],

            student_state_o: 3,
            student_state: [
                { text: 'Todos:' , value: 3},
                { text: 'Activos', value: 1 },
                { text: 'Inactivos', value: 0 },
                { text: 'Suspendidos', value: 2 },
                { text: 'Retirados', value: 4 },
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

            validEmail: function (email){
                var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                return re.test(email);
            },


            onImageChange(event){
                console.log(event.target.files[0]);
                this.image = event.target.files[0];
            },

		  	cambiarPagina(page){
                let me = this;
                //Actualizar pagina actual
                me.pagination.current_page = page;
                me.getData(page);
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

		  	checkForm(){
                this.error = 0;
                this.errors = [];

                //Validacion de nombre
                if (!this.name) this.errors.push("Nombre Requerido");
                //Validacion de email
                if (!this.email) this.errors.push("Correo Requerido");
                else if(!this.validEmail(this.email)) this.errors.push('Por favor introduzca un correo valido');
                //Validacion de telefono
                if (!this.phone) this.errors.push("Telefono Requerido");
                //Acudiente requerido
                if (!this.attendant) this.errors.push("Acudiente Requerido");
                //if (!this.image && this.action==1) this.errors.push("La imagen es requerida");


                if(this.errors.length) this.error = 1;

                return this.error;
            },

            getData(page){
                let me = this
                var url = '/students/getData?page=' + page +'&criterio=' + this.criterio + '&mostrar=' + this.mostrar + '&buscar=' + this.search + '&grade=' + this.grade + '&student_state_o=' + this.student_state_o

                axios.get(url)
                  .then(function (response) {
                    var respuesta = response.data;
                    me.datos = respuesta.students.data;
                    me.enrrollements = respuesta.enrrollements;
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

                const config = {
                    headers: { 'content-type': 'multipart/form-data' }
                }

                let me = this;

                me.active_save_update = false;

                let formData = new FormData;
                formData.append('personal_id', this.personal_id);
                formData.append('image', this.image);
                formData.append('name',this.name);
                formData.append('email',this.email);
                formData.append('phone',this.phone);
                formData.append('attendant',this.attendant);

                //console.log(formData);

                axios.post('/students/store',
                    formData
                )
                .then(function(response){
                    me.closeModal()
                    me.active_save_update = true;
                    me.getData(1)
                })
                .catch(function(error) {
                    if(error.response.data.errors){
                        me.errors.push(error.response.data.errors.personal_id[0]);
                        me.error = 1;
                    };

                    me.active_save_update = true;
                })
            },

            edit(id){

            	let me = this;
            	let objeto;

            	axios
            	.get('/students/edit/' + id)
            	.then(function(response){
            		var respuesta = response.data;
            		objeto = respuesta.student;
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

                const config = {
                    headers: { 'content-type': 'multipart/form-data' }
                }

                let me = this;
                me.active_save_update = false;

                let formData = new FormData;

                formData.append('personal_id',this.personal_id);
                formData.append('image', this.image);
                formData.append('id', this.id);
                formData.append('name',this.name);
                formData.append('email',this.email);
                formData.append('phone',this.phone);
                formData.append('attendant',this.attendant);
                /**/

                //console.log(this.image);
                //return 0;

                axios
                .post('/students/update',
                	formData
                )
                .then(function (response){
                    console.log(response);
                    me.active_save_update = true;
                    me.closeModal();
                    me.getData(1)
                })
                .catch(function (error) {
                    if(error.response.data.errors){
                        me.errors.push(error.response.data.errors.personal_id[0]);
                        me.error = 1;
                    };
                    me.active_save_update = true;
                });
            },

            openModal(action, data = []){

            	if (action == 1) {
                    this.action = 1;
                    this.titleModal = "Registrar Estudiante"
                }else if (action == 2) {
                    this.action = 2
                    this.titleModal = "Actualizar Estudiante"
                    this.personal_id = data['personal_id'];
                    this.image = data['image']
                    this.id = data['id']
                    this.name = data['name']
                    this.email = data['email']
                    this.phone = data['phone']
                    this.attendant = data['attendant']
                }

                $('#m_modal_1').modal('show')
            },

            closeModal(){
                this.personal_id = ''
                this.name = ''
                this.email = ''
                this.phone = ''
                this.attendant = ''
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

            grade: function(){
                this.getData(1)
            },

            student_state_o: function(){
                this.getData(1)
            }

          }

		})
	</script>

@endsection

@push('styles')

    <style type="text/css" media="screen">

        .form-control.m-input--pill
        {
            border-radius: 0px;
        }

        a {
            color: rgb(25, 59, 100);
        }

    </style>

@endpush
