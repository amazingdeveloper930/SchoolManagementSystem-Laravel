@extends('layouts.app')

@section('title','Configuraciones')

@section('content')

	<div class="m-portlet m-portlet--mobile">
	
        <!--ENCABEZADO =======================================================================================================================-->
        <!--==================================================================================================================================-->
    	<div class="m-portlet__head">
			<div class="m-portlet__head-caption">
				<div class="m-portlet__head-title">
					<h1 class="m-portlet__head-text">
						Configuraciones
					</h1>
				</div>
			</div>
			<div class="m-portlet__head-tools">
				<ul class="m-portlet__nav">
					<li class="m-portlet__nav-item">
                        <button v-if="!edit_data" type="button" @click="edit_data=true" class="btn btn-accent m-btn m-btn--pill m-btn--custom m-btn--icon m-btn--air">
	                        <span>
	                            <span>Modificar</span>
	                        </span>
                        </button>

                        <button v-if="edit_data" type="button" :disabled="!active_save" @click="edit_data=false" class="btn btn-danger btn-sm">
	                        <span>
	                            <span>Cancelar</span>
	                        </span>
                        </button>
					</li>
				</ul>
			</div>
		</div>
        <!--===================================================================================================================================-->
        <!--FINAL DE ENCABEZADO ===============================================================================================================-->



        <div class="m-portlet__body">
			<div class="row justify-content-between">

				<div class="col-3">
					<h4>Firma:</h4>
					<img :src="'app/config/' + direct_firma" style="width: 100%" alt="Firma" class="img-responsive">
				</div>

				<div v-if="edit_data">
					<div class="form-group">
						<label class="control-label" for="firma">Cambiar Firma</label>
						<br>
						<input type="file" @change="onImageChange" id="firma" placeholder="Firma" >
					</div>
				</div>

			</div>
        </div>


        <div class="m-portlet__body">
        	<div class="row justify-content-between">
        		<div>
        			<h4>Nombre: <span v-text="show_name"></span></h4>
        		</div>

        		<div v-if="edit_data" class="form-group">

					<label class="control-label" for="firma">Cambiar Nombre</label>
					<input type="text" v-model="name" id="firma" placeholder="Nombre" class="form-control">
				</div>
        	</div>
        </div>


        <div v-if="edit_data" class="m-portlet__body">
        	<div class="row justify-content-end">
        		<button type="button" :disabled="!active_save" @click="update()" class="btn btn-primary">
        			<span v-text="active_save ? 'Actualizar' : 'Cargando...'"></span>
        		</button>
        	</div>
        </div>

	</div>


@endsection

@section('scripts')
	
	<script type="text/javascript">
		
		const app = new Vue({

			el: '#app',

			data:{
				firma: '',
				direct_firma: '',
				show_name: '',
				name: '',
				edit_data: false,
				active_save: true,
			},

			methods:{

				onImageChange(event){
	                console.log(event.target.files[0]);
	                this.firma = event.target.files[0];
	            },

				getData(){

					let me = this;

					axios
						.get('/config/getData/')
						.then(function(response){
							me.direct_firma = response.data.firma.content;
							me.show_name = response.data.name.content;
							me.name = response.data.name.content;
						})
						.catch(function(error){
							console.log(error);
						});
				},

				update(){

					let me = this;

					const config = {
	                    headers: { 'content-type': 'multipart/form-data' }
	                }

					let formData = new FormData;
	                formData.append('firma', this.firma);
	                formData.append('name', this.name);

	                console.log(me.name);

	                me.active_save = false;

					axios
						.post('/config/update',
							formData
						)
						.then(function(response){
							me.getData();
							me.active_save = true;
						}).catch(function(error){
							console.log(error);
							me.active_save = true;
						});
				},
			},

			mounted(){
				this.getData();
			}
		});
	</script>
@endsection