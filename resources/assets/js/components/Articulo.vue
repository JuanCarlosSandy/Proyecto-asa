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
                <i class="fa fa-align-justify"></i> Productos
                <button type="button" @click="abrirModal('producto','registrar')" class="btn btn-secondary">
                    <i class="icon-plus"></i>&nbsp;Nuevo
                </button>
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-md-6">
                        <div class="input-group">
                            <select class="form-control col-md-3" v-model="criterio">
                              <option value="nombre_p">Nombre</option>
                            </select>
                            <input type="text" v-model="buscar" @keyup.enter="listarProducto(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                            <button type="submit" @click="listarProducto(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                        </div>
                    </div>
                </div>
                <table class="table table-bordered table-striped table-sm">
                    <thead>
                        <tr>
                            
                            <th>N°</th>
                            <th>Nombre</th>
                            <th>Cantidad</th>
                            <th>Categoría</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="producto in arrayProducto" :key="producto.id">
                           
                            <td v-text="producto.id"></td>
                            <td v-text="producto.nombre_producto"></td>
                            <td v-text="producto.cantidad"></td>
                            <td v-text="producto.categoria"></td>
                             <td>
                                <button type="button" @click="abrirModal('producto','actualizar',producto)" class="btn btn-warning btn-sm">
                                  <i class="icon-pencil"></i>
                                </button> &nbsp;                                
                                <button type="button" class="btn btn-danger btn-sm" @click="eliminarProducto(producto.id)">
                                    <i class="icon-trash"></i>
                                </button>
                                        
                                    
                            </td>
                        </tr>                                
                    </tbody>
                </table>
                <nav>
                    <ul class="pagination">
                        <li class="page-item" v-if="pagination.current_page > 1">
                            <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,criterio)">Ant</a>
                        </li>
                        <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                            <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,criterio)" v-text="page"></a>
                        </li>
                        <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                            <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,criterio)">Sig</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
        <!-- Fin ejemplo de tabla Listado -->
    </div>
    <!--Inicio del modal agregar/actualizar-->
    <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
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
                            <div class="form-group row">
                            <div class="col-md-9">
                                    <label for="num_documento"><strong>DONADOR</strong></label>
                                    <div class="form-group">
                                    <v-select :on-search="buscarPersonasCI" label="num_documento" :options="arrayDonador"
                                        placeholder="Buscar Clientes..." :onChange="seleccionarPersona">
                                    </v-select>
                                </div>
                                </div>
                            </div>

                        
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Nombre Producto</label>
                            <div class="col-md-9">
                                <input type="text" v-model="nombre_p" class="form-control" placeholder="Nombre del Producto" @input="buscarProducto" :disabled="opcionSeleccionada">
                                <ul v-if="!opcionSeleccionada">
                                        <li v-for="productos in arrayProductosBuscador" @click="selectProducto(productos)" >{{ productos.nombre_producto }}</li>
                                        <li v-if="arrayProductosBuscador.length === 0 || nombre_producto.length === 0">No se encontraron resultados</li>
                                    </ul>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="email-input">Cantidad</label>
                            <div class="col-md-9">
                                <input type="number" v-model="cantidad" class="form-control" placeholder="Ingrese cantidad en números">
                            </div>
                        </div>
                    </div>


                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Nombre Donador</label>
                            <div class="col-md-9">
                                <input type="text" v-model="nombre_donador" class="form-control" placeholder="Nombre del donador" :disabled="selectedDonante">                   
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="tipo_producto"><strong>Categoria</strong></label>
                            <select id="idCategoria_Alimentos" v-model="idCategoria_Alimentos" class="form-control">
                                <option value="" disabled>Selecciona una categoria</option>
                                <option v-for="categorias in arrayCategorias" :key="categorias.id" :value="categorias.id">{{ categorias.tipo_producto }}</option>
                            </select>
                        </div>
                    </div>

                        <div v-show="errorProducto" class="form-group row div-error">
                            <div class="text-center text-error">
                                <div v-for="error in errorMostrarMsjProducto" :key="error" v-text="error">
                                </div>
                            </div>
                        </div>
                    </div>


                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                    <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarProducto()">Guardar</button>
                    <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarProducto()">Actualizar</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!--Fin del modal-->

    <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal2}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
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
                        <div class="row" >
                            <div class="col-md-6">

                                <div class="form-group">
                            <label  for="tipo_producto"><strong>Nombre Producto</strong></label>
                            
                                <input type="text" v-model="nombre_p" class="form-control" placeholder="Nombre del producto" >    
                            
                                </div>
                                <div class="form-group">
                                    <label for="tipo_producto"><strong>Categoria</strong></label>
                                    <select id="idCategoria_Alimentos" v-model="idCategoria_Alimentos" class="form-control">
                                        <option value="" disabled>Selecciona una categoria</option>
                                        <option v-for="categorias in arrayCategorias" :key="categorias.id" :value="categorias.id">{{ categorias.tipo_producto }}</option>
                                    </select>
                                </div>
                        
                    </div>
                        </div>
                        <div v-show="errorProducto" class="form-group row div-error">
                            <div class="text-center text-error">
                                <div v-for="error in errorMostrarMsjProducto" :key="error" v-text="error">

                                </div>
                            </div>
                        </div>

                    </form>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                    <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarProducto()">Actualizar</button>
                </div>
                
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

</main>
</template>

<script>
import vSelect from 'vue-select';
export default {
data (){
    return {
        id: 0,
        idProducto: 0,
        nombre_producto : '',
        nombre_p : '',
        buscarProductos : '',
        opcionSeleccionada: false,
        cantidad : '',
        arrayProducto : [],
        arrayProductosBuscador : [],
        arrayCategorias : [],
        num_documento: '', // Campo de texto para el CI
        arrayDonador : [],
        nombre_donador : '',
        selectedDonante: false,
        idDonador : 0,
        idCategoria_Alimentos : 0,
        modal : 0,
        modal2 : 0,
        tituloModal : '',
        tipoAccion : 0,
        errorProducto : 0,
        errorMostrarMsjProducto : [],
        pagination : {
            'total' : 0,
            'current_page' : 0,
            'per_page' : 0,
            'last_page' : 0,
            'from' : 0,
            'to' : 0,
        },
        offset : 3,
        criterio : 'nombre',
        buscar : ''
    }
},
components: {
        vSelect
    },
computed:{
    isActived: function(){
        return this.pagination.current_page;
    },
    //Calcula los elementos de la paginación
    pagesNumber: function() {
        if(!this.pagination.to) {
            return [];
        }
        
        var from = this.pagination.current_page - this.offset; 
        if(from < 1) {
            from = 1;
        }

        var to = from + (this.offset * 2); 
        if(to >= this.pagination.last_page){
            to = this.pagination.last_page;
        }  

        var pagesArray = [];
        while(from <= to) {
            pagesArray.push(from);
            from++;
        }
        return pagesArray;             

    }
},
methods : {
    listarProducto (page,buscar,criterio){
        let me=this;
        var url= '/producto?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
        axios.get(url).then(function (response) {
            var respuesta= response.data;
            me.arrayProducto = respuesta.productos.data;
            me.pagination= respuesta.pagination;
        })
        .catch(function (error) {
            console.log(error);
        });
    },
    cambiarPagina(page,buscar,criterio){
        let me = this;
        //Actualiza la página actual
        me.pagination.current_page = page;
        //Envia la petición para visualizar la data de esa página
        me.listarProducto(page,buscar,criterio);
    },
    registrarProducto(){
        if (this.validarProducto()){
                return;
            }
            this.convertText();
            let me = this;
            axios.post('/entradaProducto/registrar',{
                'idDonador':this.idDonador,
                'idProducto' :this.idProducto,
                'nombre_producto' : this.nombre_p,
                'cantidad' : this.cantidad,
                'idCategoria_Alimentos' : this.idCategoria_Alimentos
            }).then(function (response) {
                me.cerrarModal();
                me.listarProducto(1,'','nombre');
            }).catch(function (error) {
                console.log("error"+error);
            });
    },
    actualizarProducto(){
       if (this.validarProducto()){
            return;
        }       
        this.convertText();
        let me = this;

        axios.put('/producto/actualizar',{
            'nombre_p': this.nombre_p,
            'cantidad': this.cantidad,
            'id': this.id,
            'idCategoria_Alimentos' : this.idCategoria_Alimentos
        }).then(function (response) {
            me.cerrarModal();
            me.listarProducto(1,'','nombre');
        }).catch(function (error) {
            console.log(error);
        }); 
    },
    convertText() {
            let text = this.nombre_p.trim();
            this.nombre_p = text.charAt(0).toUpperCase() + text.slice(1).toLowerCase();
            
        },
    obtenerDatosCategoria (){
                let me = this;
                var url = '/producto/obtenerDatosCategoria';
                axios.get(url).then(function(response) {
                    var respuesta = response.data;
                    me.arrayCategorias = respuesta.categorias;
                    console.log("CATEGORIAS", me.arrayCategorias);
            })
            .catch(function(error) {
                console.log(error);
            });
            },

    /*buscarPersonasCI(){
                if (this.num_documento === '') {
                    this.arrayDonador =[];
                    this.num_documento =''; 
                    return;
                }
                let me = this;
                axios.get('/donador/buscarDonador',{ params: { ci: this.num_documento } }).then(function(response){
                    var respuesta = response.data;
                    me.arrayDonador= respuesta.resultados;             
                })
                .catch(function(error){
                    console.log(error);
                });
                
            },*/
            async buscarPersonasCI(search,loading){
                console.log("ingresando a buscar personas");
                let me = this;
                console.log("search ", search);
                console.log("loading ", loading);
                loading(true)
                var url = '/donador/selectDonador?filtro=' + search;
                try {
                    const response = await axios.get(url);
                    let respuesta = response.data;
                    me.arrayDonador = respuesta.resultados;
                    console.log(me.arrayDonador);
                    
                    q:search;
                    loading(false);
                    // Puedes devolver los resultados si es necesario
                } catch (error) {
                    console.error(error);
                    // Manejar el error según sea necesario
                }       

                
            },
    buscarProducto(){         
            let me = this;
            axios.get('/producto/buscarProducto',{ params: { nombre_producto: this.nombre_p } }).then(function(response){
                var respuesta = response.data;               
                me.arrayProductosBuscador= respuesta.resultados;
                console.log("PRODUCTOS", me.arrayProductosBuscador);

            })
            .catch(function(error){
                console.log(error);
                console.log("PRODUCTOS", me.arrayProductosBuscador);
            });
            
        },

    selectProducto(producto){
            this.idProducto =  producto.id;
            this.nombre_p=producto.nombre_producto
            this.opcionSeleccionada = true;
        },

    seleccionarPersona(donante) {
            this.num_documento='';
            this.nombre_donador = donante.nombre;
            this.idDonador=donante.idDonador;
            this.num_documento = donante.num_documento; // Autocompletar el campo de texto
            this.selectedDonante=true;
            this.arrayDonadores =[];
        },


    eliminarProducto(id){
        swal({
            title: '¿Está seguro de eliminar este producto?',
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

                axios.delete('/producto/eliminar/' + id)
                .then(function (response) {
                    me.listarProducto(1, '', 'nombre');
                    swal(
                        'Eliminado',
                        response.data.message,
                        'success'
                    )
                }).catch(function (error) {
                    console.log(error);
                });

            } else if (result.dismiss === swal.DismissReason.cancel) {
            }
        }) 
    },


    validarProducto(){
        this.errorProducto=0;
        this.errorMostrarMsjProducto =[];

        if (!this.nombre_p) this.errorMostrarMsjProducto.push("El nombre del producto no puede estar vacío.");

        if (this.errorMostrarMsjProducto.length) this.errorProducto = 1;

        return this.errorProducto;
    },

    cerrarModal(){
        this.modal=0;
        this.modal2=0;
        this.tituloModal='';
        this.nombre_p='';
        this.cantidad='';
        this.nombre_donador='';
        this.selectedDonante = false;
        this.opcionSeleccionada=false;
    },

    abrirModal(modelo, accion, data = []){
        switch(modelo){
            case "producto":
            {
                switch(accion){
                    case 'registrar':
                    {
                        this.nombre_donador='';
                        this.arrayDonador=[];
                        this.modal = 1;
                        this.tituloModal = 'Registrar Producto';
                        this.nombre_p= '';
                        this.nombre_donador='';
                        this.cantidad = '';
                        this.tipoAccion = 1;
                        break;
                    }
                    case 'actualizar':
                    {
                        //console.log(data);
                        this.modal2=1;
                        this.tituloModal='Actualizar Producto';
                        this.tipoAccion=2;
                        this.id=data['id'];
                        this.nombre_p = data['nombre_producto'];
                        this.cantidad= data['cantidad'];
                        this.idCategoria_Alimentos= data['idCategoria_Alimentos'];

                        break;
                    }
                }
            }
        }
    }
},
mounted() {
    this.listarProducto(1,this.buscar,this.criterio);
    this.obtenerDatosCategoria();
}
}
</script>
<style>    
.modal-content{
width: 100% !important;
position: absolute !important;
}
.mostrar{
display: list-item !important;
opacity: 1 !important;
position: absolute !important;
background-color: #3c29297a !important;
}
.div-error{
display: flex;
justify-content: center;
}
.text-error{
color: red !important;
font-weight: bold;
}
</style>
