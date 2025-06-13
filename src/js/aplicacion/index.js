import Swal from "sweetalert2";
import { validarFormulario } from '../funciones';
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";

const FormAplicaciones = document.getElementById('FormAplicaciones');
const BtnGuardar = document.getElementById('BtnGuardar');
const BtnModificar = document.getElementById('BtnModificar');
const BtnLimpiar = document.getElementById('BtnLimpiar');
const BtnMostrarRegistros = document.getElementById('BtnMostrarRegistros');
const SeccionTabla = document.getElementById('seccionTablaRegistros');

const MostrarRegistros = () => {
    const estaOculto = SeccionTabla.style.display === 'none';
    
    if (estaOculto) {
        SeccionTabla.style.display = 'block';
        BtnMostrarRegistros.innerHTML = '<i class="bi bi-eye-slash me-2"></i>Ocultar Registros';
        BtnMostrarRegistros.classList.remove('btn-info');
        BtnMostrarRegistros.classList.add('btn-warning');
        BuscarAplicaciones(true);
    } else {
        SeccionTabla.style.display = 'none';
        BtnMostrarRegistros.innerHTML = '<i class="bi bi-eye me-2"></i>Mostrar Registros';
        BtnMostrarRegistros.classList.remove('btn-warning');
        BtnMostrarRegistros.classList.add('btn-info');
    }
}

const limpiarTodo = () => {
    FormAplicaciones.reset();
    BtnGuardar.classList.remove('d-none');
    BtnModificar.classList.add('d-none');
}

const GuardarAplicacion = async (event) => {
    event.preventDefault();
    BtnGuardar.disabled = true;

    if (!validarFormulario(FormAplicaciones, ['ap_id'])) {
        Swal.fire({
            position: "center",
            icon: "info",
            title: "FORMULARIO INCOMPLETO",
            text: "Debe de validar todos los campos",
            showConfirmButton: true,
        });
        BtnGuardar.disabled = false;
        return;
    }

    const body = new FormData(FormAplicaciones);

    const url = '/app_login/aplicacion/guardarAPI';
    const config = {
        method: 'POST',
        body
    }

    try {

        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();

        const { codigo, mensaje } = datos

        if (codigo == 1) {

            await Swal.fire({
                position: "center",
                icon: "success",
                title: "Exito",
                text: mensaje,
                showConfirmButton: true,
            });

            limpiarTodo();

        } else {

            await Swal.fire({
                position: "center",
                icon: "info",
                title: "Error",
                text: mensaje,
                showConfirmButton: true,
            });

        }

    } catch (error) {
        console.log(error)
    }
    BtnGuardar.disabled = false;

}

const BuscarAplicaciones = async (mostrarMensaje = false) => {
    const url = '/app_login/aplicacion/buscarAPI';
    const config = {
        method: 'GET'
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();

        const { codigo, mensaje, data } = datos

        if (codigo == 1) {
            
            if (mostrarMensaje) {
                await Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "Exito",
                    text: `Se cargaron ${data.length} aplicación(es) correctamente`,
                    showConfirmButton: true,
                    timer: 2000
                });
            }

            datatable.clear().draw();
            datatable.rows.add(data).draw();

        } else {
            
            if (mostrarMensaje) {
                await Swal.fire({
                    position: "center",
                    icon: "info",
                    title: "Error",
                    text: mensaje,
                    showConfirmButton: true,
                });
            }
        }

    } catch (error) {
        console.log('Error al cargar aplicaciones:', error);
        
        if (mostrarMensaje) {
            await Swal.fire({
                position: "center",
                icon: "error",
                title: "Error de conexión",
                text: "No se pudo conectar con el servidor",
                showConfirmButton: true,
            });
        }
    }
}

const datatable = new DataTable('#TableAplicaciones', {
    dom: `
        <"row mt-3 justify-content-between" 
            <"col" l> 
            <"col" B> 
            <"col-3" f>
        >
        t
        <"row mt-3 justify-content-between" 
            <"col-md-3 d-flex align-items-center" i> 
            <"col-md-8 d-flex justify-content-end" p>
        >
    `,
    language: lenguaje,
    data: [],
    columns: [
        {
            title: 'No.',
            data: 'ap_id',
            width: '%',
            render: (data, type, row, meta) => meta.row + 1
        },
        { title: 'Nombre Largo', data: 'ap_nombre_largo' },
        { title: 'Nombre Medio', data: 'ap_nombre_medium' },
        { title: 'Nombre Corto', data: 'ap_nombre_corto' },
        {
            title: 'Acciones',
            data: 'ap_id',
            searchable: false,
            orderable: false,
            render: (data, type, row, meta) => {
                return `
                 <div class='d-flex justify-content-center'>
                     <button class='btn btn-warning modificar mx-1' 
                         data-id="${data}" 
                         data-nombre_largo="${row.ap_nombre_largo}"
                         data-nombre_medium="${row.ap_nombre_medium}"
                         data-nombre_corto="${row.ap_nombre_corto}">    
                         <i class='bi bi-pencil-square me-1'></i> Modificar
                     </button>
                     <button class='btn btn-danger eliminar mx-1' 
                         data-id="${data}">
                        <i class="bi bi-trash3 me-1"></i>Eliminar
                     </button>
                 </div>`;
            }
        }
    ]
});

const llenarFormulario = (event) => {

    const datos = event.currentTarget.dataset

    document.getElementById('ap_id').value = datos.id
    document.getElementById('ap_nombre_largo').value = datos.nombre_largo
    document.getElementById('ap_nombre_medium').value = datos.nombre_medium
    document.getElementById('ap_nombre_corto').value = datos.nombre_corto

    BtnGuardar.classList.add('d-none');
    BtnModificar.classList.remove('d-none');

    window.scrollTo({
        top: 0
    });
}

const ModificarAplicacion = async (event) => {

    event.preventDefault();
    BtnModificar.disabled = true;

    if (!validarFormulario(FormAplicaciones, [''])) {
        Swal.fire({
            position: "center",
            icon: "info",
            title: "FORMULARIO INCOMPLETO",
            text: "Debe de validar todos los campos",
            showConfirmButton: true,
        });
        BtnModificar.disabled = false;
        return;
    }

    const body = new FormData(FormAplicaciones);

    const url = '/app_login/aplicacion/modificarAPI';
    const config = {
        method: 'POST',
        body
    }

    try {

        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje } = datos

        if (codigo == 1) {

            await Swal.fire({
                position: "center",
                icon: "success",
                title: "Exito",
                text: mensaje,
                showConfirmButton: true,
            });

            limpiarTodo();
            BuscarAplicaciones(true);

        } else {

            await Swal.fire({
                position: "center",
                icon: "info",
                title: "Error",
                text: mensaje,
                showConfirmButton: true,
            });

        }

    } catch (error) {
        console.log(error)
    }
    BtnModificar.disabled = false;

}

const EliminarAplicaciones = async (e) => {

    const idAplicacion = e.currentTarget.dataset.id

    const AlertaConfirmarEliminar = await Swal.fire({
        position: "center",
        icon: "info",
        title: "¿Desea ejecutar esta acción?",
        text: 'Esta completamente seguro que desea eliminar este registro',
        showConfirmButton: true,
        confirmButtonText: 'Si, Eliminar',
        confirmButtonColor: 'red',
        cancelButtonText: 'No, Cancelar',
        showCancelButton: true
    });

    if (AlertaConfirmarEliminar.isConfirmed) {

        const url = `/app_login/aplicacion/eliminarAPI?id=${idAplicacion}`;
        const config = {
            method: 'GET'
        }

        try {

            const consulta = await fetch(url, config);
            const respuesta = await consulta.json();
            const { codigo, mensaje } = respuesta;

            if (codigo == 1) {

                await Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "Exito",
                    text: mensaje,
                    showConfirmButton: true,
                });
                
                BuscarAplicaciones(true);
            } else {
                await Swal.fire({
                    position: "center",
                    icon: "error",
                    title: "Error",
                    text: mensaje,
                    showConfirmButton: true,
                });
            }

        } catch (error) {
            console.log(error)
        }

    }
}


BuscarAplicaciones(); 
datatable.on('click', '.eliminar', EliminarAplicaciones);
datatable.on('click', '.modificar', llenarFormulario);
FormAplicaciones.addEventListener('submit', GuardarAplicacion);
BtnLimpiar.addEventListener('click', limpiarTodo);
BtnModificar.addEventListener('click', ModificarAplicacion);
BtnMostrarRegistros.addEventListener('click', MostrarRegistros);