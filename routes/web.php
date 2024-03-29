<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['guest']], function () {
    Route::get('/', 'Auth\LoginController@showLoginForm');
    Route::post('/', 'Auth\LoginController@login')->name('login');
});

Route::group(['middleware' => ['auth']], function () {

    Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
    Route::get('/dashboard', 'DashboardController');
    //Notificaciones
    Route::post('/notification/get', 'NotificationController@get');

    Route::get('/main', function () {
        return view('contenido/contenido');
    })->name('main');

    Route::group(['middleware' => ['Almacenero']], function () {
        Route::get('/categoria', 'CategoriaAlimentosController@index');
        Route::post('/categoria/registrar', 'CategoriaAlimentosController@store');
        Route::put('/categoria/actualizar', 'CategoriaAlimentosController@update');
        Route::put('/categoria/desactivar', 'CategoriaAlimentosController@desactivar');
        Route::put('/categoria/activar', 'CategoriaAlimentosController@activar');
        Route::get('/categoria/selectCategoria', 'CategoriaAlimentosController@selectCategoria');
        
        Route::get('/ropa', 'RopaController@index');
        Route::get('/ropa/obtenerDatosTalla', 'RopaController@obtenerDatosTallas');
        Route::get('/ropa/obtenerDatosCategoriaRopa', 'RopaController@obtenerDatosCategoriaRopa');
        Route::post('/ropa/registrar', 'RopaController@store');
        Route::put('/ropa/actualizar', 'RopaController@update');
        Route::delete('/ropa/eliminar/{id}', 'RopaController@eliminar');
        Route::get ('/ropa/buscarRopas','RopaController@buscarRopa');
        Route::get('/ropa/obtenerDetalles', 'RopaController@obtenerDetalles');
        Route::get('/ropa/buscarRopaVenta', 'RopaController@buscarRopaVenta');
        Route::get('/ropa/listarRopaVenta', 'RopaController@listarRopaVenta');
        Route::get ('/ropa/buscarRopasId','RopaController@buscarRopaId');
        Route::get ('/ropa/buscarRopasNombre','RopaController@buscarRopaNombre');
        Route::get('/ropas/ropasBajoStock', 'RopaController@ropasBajoStock');

        Route::get('/producto', 'ProductoController@index');
        Route::put('/producto/actualizar', 'ProductoController@update');
        Route::delete('/producto/eliminar/{id}', 'ProductoController@eliminar');
        Route::get('/producto/obtenerDatosCategoria', 'ProductoController@obtenerDatosCategoria');
        Route::get('/producto/buscarPersona', 'ProductoController@buscarPersona');
        Route::get('/producto/buscarProducto', 'ProductoController@buscarProducto');
        Route::get('/producto/obtenerDetalles', 'ProductoController@obtenerDetalles');
        Route::get('/productos/productosBajoStock', 'ProductoController@productosBajoStock');
        Route::get ('/productos/buscarProductosId','ProductoController@buscarProductoId');



        Route::get('/categoriaropa', 'CategoriaRopaController@index');
        Route::post('/categoriaropa/registrar', 'CategoriaRopaController@store');
        Route::put('/categoriaropa/actualizar', 'CategoriaRopaController@update');
        Route::get('/categoriaropa/selectCategoria', 'CategoriaRopaController@selectCategoria');

        Route::get('/tallaropa', 'TallaRopaController@index');
        Route::post('/tallaropa/registrar', 'TallaRopaController@store');
        Route::put('/tallaropa/actualizar', 'TallaRopaController@update');
        Route::get('/tallaropa/selectTalla', 'TallaRopaController@selectTalla');
        //Rutas donadores 
        Route::get('/donador','DonadorController@index');
        Route::post('/donador/registrar','DonadorController@store');
        Route::post('/donador/actualizar', 'DonadorController@update');
        Route::get('/donador/buscarDonador','DonadorController@buscarDonador');
        Route::get('/donador/selectDonador', 'DonadorController@selectDonador');
        //Rutas de configuracion de trabajo
        Route::get('/configuracion/editar', 'ConfiguracionTrabajoController@edit');
        Route::put('/configuracion/actualizar', 'ConfiguracionTrabajoController@update');
        Route::get('/backup', 'BackupDbController@createBackup');

        Route::get('/cliente', 'ClienteController@index');
        Route::post('/cliente/registrar', 'ClienteController@store');
        Route::put('/cliente/actualizar', 'ClienteController@update');
        Route::get('/cliente/selectCliente', 'ClienteController@selectCliente');
        Route::get('/cliente/listarReporteClienteExcel', 'ClienteController@listarReporteClienteExcel');

        Route::get('/rol', 'RolController@index');
        Route::get('/rol/selectRol', 'RolController@selectRol');

        Route::get('/user', 'UserController@index');
        Route::post('/user/registrar', 'UserController@store');
        Route::post('/user/actualizar', 'UserController@update');
        Route::put('/user/desactivar', 'UserController@desactivar');
        Route::put('/user/activar', 'UserController@activar');
        Route::get('/user/listarReporteUsuariosExcel', 'UserController@listarReporteUsuariosExcel');

        Route::post('/entradaRopa/registrar', 'EntradaRopacontroller@store');
        Route::get('/entradaRopa', 'EntradaRopaController@index');
        Route::put('/entradaRopa/actualizar', 'EntradaRopaController@update');
        //Route::delete('/entradaRopa/eliminar/{id}/idRopa/{idRopa}','EntradaRopaController@eliminar');

        Route::get('/entradaProducto', 'EntradaProductocontroller@index');
        Route::post('/entradaProducto/registrar', 'EntradaProductoController@store');
        Route::put('/entradaProducto/actualizar', 'EntradaProductoController@update');
        //Route::delete('/entradaProducto/eliminar/{id}/idProducto/{idProducto}', 'EntradaProductoController@eliminar');
        Route::get('/salidaProductos', 'SalidaProductosController@index');
        Route::get('/salidaProductos/obtenerCabecera', 'SalidaProductosController@obtenerCabecera');
        Route::get('/salidaProductos/obtenerDetalles', 'SalidaProductosController@obtenerDetalles');
        Route::post('/salidaProductos/registrar', 'SalidaProductosController@store');
        Route::get('/salidaProductos/obtenerDatosEvento', 'SalidaProductosController@obtenerDatosEvento');

        Route::post('/salidaRopas/registrar', 'SalidaRopasController@store');
        Route::get('/salidaRopas', 'SalidaRopasController@index');
        Route::get('/salidaRopas/obtenerCabecera', 'SalidaRopasController@obtenerCabecera');
        Route::get('/salidaRopas/obtenerDetalles', 'SalidaRopasController@obtenerDetalles');


        Route::get('/producto/listarProductoVenta', 'ProductoController@listarProductoVenta');
        Route::get('/producto/buscarProductoVenta', 'ProductoController@buscarProductoVenta');
        

        Route::get('/eventos', 'EventosController@index');
        Route::post('/eventos/registrar', 'EventosController@store');
        Route::put('/eventos/actualizar', 'EventosController@update');
        Route::get('/eventos/selectCategoria', 'EventosController@selectEvento');






        
        //Rura para que el usuario pueda editar su perfil
        Route::get('/user/editarpersona', 'UserController@editarPersona');
        //Route::put('/editarperfil', 'UserController@editarPerfil');

    });

    //RUTA PARA RECUPERAR LA SESSION CON EL ID DE LA PERSONA LOGUEADA
    Route::get('/api/session', function () {
        return response()->json([
            'id' => session('id')
        ]);
    });
});

//Route::get('/home', 'HomeController@index')->name('home');
