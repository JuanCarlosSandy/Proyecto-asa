<template>
    <main class="main">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
        </ol>
        <div class="container-fluid">
            <!-- Ejemplo de tabla Listado -->
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Donadores
                    <button type="button" @click="abrirModal('persona', 'registrar')" class="btn btn-secondary">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                    <button type="button" @click="cargarReporteUsuariosExcel()" class="btn btn-info">
                        <i class="icon-doc"></i>&nbsp;Reporte
                    </button>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <select class="form-control col-md-3" v-model="criterio">
                                    <option value="nombre">Nombre</option>
                                    <option value="num_documento">Documento</option>
                                    <option value="email">Email</option>
                                    <option value="telefono">Teléfono</option>
                                    <option value="nombre">Sucursal</option>
                                </select>
                                <input type="text" v-model="buscar" @keyup="listarPersona(1, buscar, criterio)"
                                    class="form-control" placeholder="Texto a buscar">
                                <!--button type="submit" @click="listarPersona(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button-->
                            </div>
                        </div>
                    </div>
                <div class="table-responsive">

                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                
                                <th>Nombre</th>
                                <th>Tipo Documento</th>
                                <th>Número</th>
                                <th>Dirección</th>
                                <th>Teléfono</th>
                                <th>Email</th>
                                
                                <th>Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="persona in arrayPersona" :key="persona.id">
                                
                                
                                <td v-text="persona.nombre"></td>
                                <td v-text="persona.tipo_documento"></td>
                                <td v-text="persona.num_documento"></td>
                                <td v-text="persona.direccion"></td>
                                <td v-text="persona.telefono"></td>
                                <td v-text="persona.email"></td>
                                
                                <td>
                                    <button type="button" @click="abrirModal('persona', 'actualizar', persona)"
                                        class="btn btn-warning btn-sm">
                                        <i class="icon-pencil"></i>
                                    </button> &nbsp;
                                    <template v-if="persona.condicion">
                                        <button type="button" class="btn btn-danger btn-sm"
                                            @click="desactivarUsuario(persona.id)">
                                            <i class="icon-trash"></i>
                                        </button>
                                    </template>
                                    <template v-else>
                                        <button type="button" class="btn btn-info btn-sm"
                                            @click="activarUsuario(persona.id)">
                                            <i class="icon-check"></i>
                                        </button>
                                    </template>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    </div>
                    <nav>
                        <ul class="pagination">
                            <li class="page-item" v-if="pagination.current_page > 1">
                                <a class="page-link" href="#"
                                    @click.prevent="cambiarPagina(pagination.current_page - 1, buscar, criterio)">Ant</a>
                            </li>
                            <li class="page-item" v-for="page in pagesNumber" :key="page"
                                :class="[page == isActived ? 'active' : '']">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(page, buscar, criterio)"
                                    v-text="page"></a>
                            </li>
                            <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                <a class="page-link" href="#"
                                    @click.prevent="cambiarPagina(pagination.current_page + 1, buscar, criterio)">Sig</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>
        <!--Inicio del modal agregar/actualizar-->
        <div class="modal fade" tabindex="-1" :class="{ 'mostrar': modal }" role="dialog" aria-labelledby="myModalLabel"
     style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-primary modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" v-text="tituloModal"></h4>
                <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                    <div class="row">
                        <div class="col-md-6">
                            <!-- Columna izquierda -->
                            <div class="form-group">
                                <label class="form-control-label" for="text-input">Nombre(*)</label>
                                <input type="text" v-model="nombre" class="form-control" placeholder="Nombre de la persona">
                            </div>
                            <div class="form-group">
                                <label class="form-control-label" for="text-input">Tipo documento</label>
                                <select v-model="tipo_documento" class="form-control">
                                    <option value="CEDULA">CEDULA</option>
                                    <option value="PASAPORTE">PASAPORTE</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-control-label" for="email-input">Teléfono</label>
                                <input type="number" v-model="telefono" class="form-control" placeholder="Teléfono">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- Columna derecha -->
                            <div class="form-group">
                                <label class="form-control-label" for="email-input">Dirección</label>
                                <input type="email" v-model="direccion" class="form-control" placeholder="Dirección">
                            </div>
                            <div class="form-group">
                                <label class="form-control-label" for="email-input">Número documento</label>
                                <input type="email" v-model="num_documento" class="form-control" placeholder="Número de documento">
                            </div>
                            <div class="form-group">
                                <label class="form-control-label" for="email-input">Email</label>
                                <input type="email" v-model="email" class="form-control" placeholder="Email">
                            </div>
                        </div>
                    </div>
                    <div v-show="errorPersona" class="form-group div-error">
                        <div class="text-center text-error">
                            <div v-for="error in errorMostrarMsjPersona" :key="error" v-text="error"></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                <button type="button" v-if="tipoAccion == 1" class="btn btn-primary" @click="registrarPersona()">Guardar</button>
                <button type="button" v-if="tipoAccion == 2" class="btn btn-primary" @click="actualizarPersona()">Actualizar</button>
            </div>
        </div>
    </div>
</div>

        <!--Fin del modal-->
    </main>
</template>

<script>
export default {
    data() {
        return {
            idDonador: 0,
            persona_id: 0,
            nombre: '',
            tipo_documento: '',
            num_documento: '',
            direccion: '',
            telefono: 0,
            email: '',
            arrayPersona: [],
            modal: 0,
            tituloModal: '',
            tipoAccion: 0,
            errorPersona: 0,
            errorMostrarMsjPersona: [],
            pagination: {
                'total': 0,
                'current_page': 0,
                'per_page': 0,
                'last_page': 0,
                'from': 0,
                'to': 0,
            },
            offset: 3,
            criterio: 'nombre',
            buscar: '',
            validEmail: true,
            validTelefono: true
        }
    },
    computed: {
        isActived: function () {
            return this.pagination.current_page;
        },
        //Calcula los elementos de la paginación
        pagesNumber: function () {
            if (!this.pagination.to) {
                return [];
            }

            var from = this.pagination.current_page - this.offset;
            if (from < 1) {
                from = 1;
            }

            var to = from + (this.offset * 2);
            if (to >= this.pagination.last_page) {
                to = this.pagination.last_page;
            }

            var pagesArray = [];
            while (from <= to) {
                pagesArray.push(from);
                from++;
            }
            return pagesArray;

        },
        imagen() {
            console.log(this.fotoMuestra);
            return this.fotoMuestra;
        }
    },
    methods: {
        listarPersona(page, buscar, criterio) {
            let me = this;
            var url = '/donador?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayPersona = respuesta.personas.data;
                me.pagination = respuesta.pagination;
            })
                .catch(function (error) {
                    console.log(error);
                });
            
        },
        selectRol() {
            let me = this;
            var url = '/rol/selectRol';
            axios.get(url).then(function (response) {
                //console.log(response);
                var respuesta = response.data;
                me.arrayRol = respuesta.roles;
            })
                .catch(function (error) {
                    console.log(error);
                });
        },
        
        selectSucursal() {
            let me = this;
            var url = '/sucursal/selectSucursal';
            axios.get(url).then(function (response) {
                //console.log(response);
                var respuesta = response.data;
                me.arraySucursal = respuesta.sucursales;
            })
                .catch(function (error) {
                    console.log(error);
                });
        },

        cambiarPagina(page, buscar, criterio) {
            let me = this;
            //Actualiza la página actual
            me.pagination.current_page = page;
            //Envia la petición para visualizar la data de esa página
            me.listarPersona(page, buscar, criterio);
        },
        obtenerFotografia(event) {

            let file = event.target.files[0];

            let fileType = file.type;
                // Validar si el archivo es una imagen en formato PNG o JPG
                if (fileType !== 'image/png' && fileType !== 'image/jpeg') {
                    alert('Por favor, seleccione una imagen en formato PNG o JPG.');
                    return;
                }
            this.fotografia = file;
            this.mostrarFoto(file);

        },
        mostrarFoto(file) {

            let reader = new FileReader();

            reader.onload = (file) => {
                this.fotoMuestra = file.target.result;
            }
            reader.readAsDataURL(file);
        },
        registrarPersona() {
            if (this.validarPersona()) {
                return;
            }

            let me = this;
            let formData = new FormData();
            formData.append('nombre', this.nombre);
            formData.append('tipo_documento', this.tipo_documento);
            formData.append('num_documento', this.num_documento);
            formData.append('direccion', this.direccion);
            formData.append('telefono', this.telefono);
            formData.append('email', this.email);

            /*for (let [key, value] of formData.entries()) 
            {
                console.log(key, value);
            }*/

            axios.post('/donador/registrar', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).then(function (response) {
                me.cerrarModal();
                me.listarPersona(0, '', 'nombre');
            }).catch(function (error) {
                console.log(error);
            });
            console.log('Datos en el FormData:');
            console.log('ID DONADOR: ' + formData.get('id') +typeof this.idDonador);
            console.log('Nombre: ' + formData.get('nombre')+typeof this.nombre);
            console.log('Tipo de documento: ' + formData.get('tipo_documento')+ typeof this.tipo_documento);
            console.log('Número de documento: ' + formData.get('num_documento') + typeof this.num_documento);
            console.log('direccion '+formData.get('direccion')+ typeof this.direccion);
            console.log('telefono '+formData.get('telefono')+ typeof this.telefono);
            console.log('email ',formData.get('email') +typeof this.email);
            console.log('ID PERSONA: '+ this.persona_id +typeof this.persona_id)
        },
        actualizarPersona() {
            if (this.validarPersona()) {
                return;
            }
            let me = this;
            let formData = new FormData();
            formData.append('nombre', this.nombre );
            formData.append('tipo_documento', this.tipo_documento );
            formData.append('num_documento', this.num_documento );
            formData.append('direccion', this.direccion );
            formData.append('telefono', this.telefono );
            formData.append('email', this.email );
            formData.append('id', this.idDonador );
            formData.append('idPersona',this.persona_id)

            axios.post('/donador/actualizar', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).then(function (response) {
                alert("Datos actualizados con éxito");
                me.cerrarModal();
                me.listarPersona(1, '', 'nombre');
            }).catch(function (error) {
                console.log(error);
            });
            console.log('Datos en el FormData:');
            console.log('ID DONADOR: ' + formData.get('id') +typeof this.idDonador);
            console.log('Nombre: ' + formData.get('nombre')+typeof this.nombre);
            console.log('Tipo de documento: ' + formData.get('tipo_documento')+ typeof this.tipo_documento);
            console.log('Número de documento: ' + formData.get('num_documento') + typeof this.num_documento);
            console.log('direccion '+formData.get('direccion')+ typeof this.direccion);
            console.log('telefono '+formData.get('telefono')+ typeof this.telefono);
            console.log('email ',formData.get('email') +typeof this.email);
            console.log('ID PERSONA: '+ this.persona_id +typeof this.persona_id)
        },
        validarPersona() {
            this.errorPersona = 0;
            this.errorMostrarMsjPersona = [];
            this.validateEmail();
            this.validatePhoneNumber();
            if (!this.nombre) this.errorMostrarMsjPersona.push("El nombre de la pesona no puede estar vacío.");
            if(!this.validEmail) this.errorMostrarMsjPersona.push("El correo debe contener @");
            if(!this.validTelefono) this.errorMostrarMsjPersona.push("El numero de telefono debe tener 8 digitos y ser un numero valido");
            if (this.errorMostrarMsjPersona.length) this.errorPersona = 1;

            return this.errorPersona;
        },
        validateEmail() {
        const re =/^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        this.validEmail = re.test(this.email);
        },
        validatePhoneNumber() {
            const pattern = /^[67]\d{7}$/;
            this.validTelefono = pattern.test(this.telefono);
        },
        cerrarModal() {
            //Usando referencia en el file para limpiarlo al cerrar el modal
            this.modal = 0;
            this.tituloModal = '';
            this.nombre = '';
            this.tipo_documento = 'DNI';
            this.num_documento = '';
            this.direccion = '';
            this.telefono = '';
            this.email = '';
            this.errorPersona = 0;
        },
        abrirModal(modelo, accion, data = []) {
            this.selectRol();
            //this.selectSucursal();
            switch (modelo) {
                case "persona":
                    {
                        switch (accion) {
                            case 'registrar':
                                {
                                    this.modal = 1;
                                    this.tituloModal = 'Registrar Donador';
                                    this.nombre = '';
                                    this.tipo_documento = 'DNI';
                                    this.num_documento = '';
                                    this.direccion = '';
                                    this.telefono = '';
                                    this.email = '';
                                    this.usuario = '';
                                    this.password = '';
                                    this.fotografia = '';
                                    this.idrol = 0;
                                    this.idsucursal = 0;
                                    this.tipoAccion = 1;
                                    break;
                                }
                            case 'actualizar':
                                {
                                    console.log("persona "+data['id']);
                                    console.log("nombre"+ data['nombre']);
                                    console.log("id persona "+data['idrol']);
                                    console.log("id donador "+ data ['idDonador'])
                                    this.modal = 1;
                                    this.tituloModal = 'Actualizar Usuario';
                                    this.tipoAccion = 2;
                                    this.persona_id = data['id'];
                                    this.nombre = data['nombre'];
                                    this.tipo_documento = data['tipo_documento'];
                                    this.num_documento = data['num_documento'];
                                    this.direccion = data['direccion'];
                                    this.telefono = data['telefono'];
                                    this.email = data['email'];
                                    this.idDonador = data ['idDonador'];
                                    break;
                                }
                        }
                    }
            }
        },
        desactivarUsuario(id) {
            swal({
                title: 'Esta seguro de desactivar este usuario?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar!',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    let me = this;

                    axios.put('/user/desactivar', {
                        'id': id
                    }).then(function (response) {
                        me.listarPersona(1, '', 'nombre');
                        swal(
                            'Desactivado!',
                            'El registro ha sido desactivado con éxito.',
                            'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });


                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {

                }
            })
        },
        activarUsuario(id) {
            swal({
                title: 'Esta seguro de activar este usuario?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar!',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    let me = this;

                    axios.put('/user/activar', {
                        'id': id
                    }).then(function (response) {
                        me.listarPersona(1, '', 'nombre');
                        swal(
                            'Activado!',
                            'El registro ha sido activado con éxito.',
                            'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });


                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {

                }
            })
        },
        cargarReporteUsuariosExcel()
        {
            window.open('/user/listarReporteUsuariosExcel', '_blank');
        }
    },
    mounted() {
        this.listarPersona(1, this.buscar, this.criterio);
    }
}
</script>
<style>    .modal-content {
        width: 100% !important;
        position: absolute !important;
    }

    .mostrar {
        display: list-item !important;
        opacity: 1 !important;
        position: absolute !important;
        background-color: #3c29297a !important;
    }

    .div-error {
        display: flex;
        justify-content: center;
    }

    .text-error {
        color: red !important;
        font-weight: bold;
    }
</style>
