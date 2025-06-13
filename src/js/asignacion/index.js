import Swal from "sweetalert2";
import { validarFormulario } from '../funciones';
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";

const FormAsigPermisos = document.getElementById('FormAsigPermisos');
const BtnGuardar = document.getElementById('BtnGuardar');
const BtnModificar = document.getElementById('BtnModificar');
const BtnLimpiar = document.getElementById('BtnLimpiar');
const BtnMostrarRegistros = document.getElementById('BtnMostrarRegistros');
const SeccionTabla = document.getElementById('seccionTablaRegistros');
const SelectApp = document.getElementById('asig_app');
const SelectPermiso = document.getElementById('asig_permiso');

const MostrarRegistros = () => {
    const estaOculto = SeccionTabla.style.display === 'none';
    
    if (estaOculto) {
        SeccionTabla.style.display = 'block';
        BtnMostrarRegistros.innerHTML = '<i class="bi bi-eye-slash me-2"></i>Ocultar Registros';
        BtnMostrarRegistros.classList.remove('btn-info');
        BtnMostrarRegistros.classList.add('btn-warning');
        BuscarAsignaciones(false);
    } else {
        SeccionTabla.style.display = 'none';
        BtnMostrarRegistros.innerHTML = '<i class="bi bi-eye me-2"></i>Mostrar Registros';
        BtnMostrarRegistros.classList.remove('btn-warning');
        BtnMostrarRegistros.classList.add('btn-info');
    }
}

const limpiarTodo = () => {
    FormAsigPermisos.reset();
    BtnGuardar.classList.remove('d-none');
    BtnModificar.classList.add('d-none');
    
    // Limpiar select de permisos
    SelectPermiso.innerHTML = '<option value="" selected disabled>Primero seleccione una aplicación...</option>';
}

// Cargar permisos cuando se selecciona una aplicación
const CargarPermisos = async () => {
    const appId = SelectApp.value;
    
    if (!appId) {
        SelectPermiso.innerHTML = '<option value="" selected disabled>Primero seleccione una aplicación...</option>';
        return;
    }

    try {
        SelectPermiso.innerHTML = '<option value="" selected disabled>Cargando permisos...</option>';
        
        const url = `/app_login/asignacion/obtenerPermisosAPI?app_id=${appId}`;
        const config = { method: 'GET' };
        
        const respuesta = await fetch(url, config);
        const permisos = await respuesta.json();

        SelectPermiso.innerHTML = '<option value="" selected disabled>Seleccione un permiso...</option>';
        
        if (Array.isArray(permisos) && permisos.length > 0) {
            permisos.forEach(permiso => {
                const option = document.createElement('option');
                option.value = permiso.per_id;
                option.textContent = `${permiso.per_nombre} (${permiso.per_clave})`;
                SelectPermiso.appendChild(option);
            });
        } else {
            SelectPermiso.innerHTML = '<option value="" selected disabled>No hay permisos disponibles para esta aplicación</option>';
        }

    } catch (error) {
        console.error('Error al cargar permisos:', error);
        SelectPermiso.innerHTML = '<option value="" selected disabled>Error al cargar permisos</option>';
        
        Swal.fire({
            position: "center",
            icon: "error",
            title: "Error",
            text: "No se pudieron cargar los permisos de la aplicación",
            showConfirmButton: true,
        });
    }
}

const GuardarAsignacion = async (event) => {
    event.preventDefault();
    BtnGuardar.disabled = true;

    if (!validarFormulario(FormAsigPermisos, ['asig_id'])) {
        Swal.fire({
            position: "center",
            icon: "info",
            title: "FORMULARIO INCOMPLETO",
            text: "Debe completar todos los campos requeridos",
            showConfirmButton: true,
        });
        BtnGuardar.disabled = false;
        return;
    }

    const body = new FormData(FormAsigPermisos);
    const url = '/app_login/asignacion/guardarAPI';
    const config = {
        method: 'POST',
        body
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje } = datos;

        if (codigo == 1) {
            await Swal.fire({
                position: "center",
                icon: "success",
                title: "Éxito",
                text: mensaje,
                showConfirmButton: true,
            });
            limpiarTodo();
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
        console.error('Error:', error);
        await Swal.fire({
            position: "center",
            icon: "error",
            title: "Error de conexión",
            text: "No se pudo conectar con el servidor",
            showConfirmButton: true,
        });
    }
    
    BtnGuardar.disabled = false;
}

const BuscarAsignaciones = async (mostrarMensaje = false) => {
    const url = '/app_login/asignacion/buscarAPI';
    const config = { method: 'GET' };

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();

        if (Array.isArray(datos)) {
            if (mostrarMensaje) {
                await Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "Éxito",
                    text: `Se cargaron ${datos.length} asignación(es) correctamente`,
                    showConfirmButton: true,
                    timer: 2000
                });
            }

            datatable.clear().draw();
            datatable.rows.add(datos).draw();

        } else {
            if (mostrarMensaje) {
                await Swal.fire({
                    position: "center",
                    icon: "info",
                    title: "Error",
                    text: "No se pudieron cargar las asignaciones",
                    showConfirmButton: true,
                });
            }
        }

    } catch (error) {
        console.error('Error al cargar asignaciones:', error);
        
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

const datatable = new DataTable('#TableAsigPermisos', {
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
            data: 'asig_id',
            width: '5%',
            render: (data, type, row, meta) => meta.row + 1
        },
        { 
            title: 'Usuario', 
            data: null,
            render: (data, type, row) => `${row.us_nom1} ${row.us_ape1}<br><small class="text-muted">${row.us_correo}</small>`
        },
        { title: 'Aplicación', data: 'ap_nombre_largo' },
        { 
            title: 'Permiso', 
            data: null,
            render: (data, type, row) => `${row.per_nombre}<br><small class="text-muted">${row.per_clave}</small>`
        },
        { title: 'Motivo', data: 'asig_motivo' },
        { title: 'Fecha Asignación', data: 'asig_fecha' },
        {
            title: 'Acciones',
            data: 'asig_id',
            searchable: false,
            orderable: false,
            render: (data, type, row) => {
                return `
                 <div class='d-flex justify-content-center'>
                     <button class='btn btn-warning modificar mx-1' 
                         data-id="${data}" 
                         data-usuario="${row.asig_usuario}"
                         data-app="${row.asig_app}"
                         data-permiso="${row.asig_permiso}"
                         data-motivo="${row.asig_motivo}">    
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

const llenarFormulario = async (event) => {
    const datos = event.currentTarget.dataset;

    document.getElementById('asig_id').value = datos.id;
    document.getElementById('asig_usuario').value = datos.usuario;
    document.getElementById('asig_app').value = datos.app;
    document.getElementById('asig_motivo').value = datos.motivo;

    // Cargar permisos de la aplicación seleccionada
    await CargarPermisos();
    
    // Después de cargar los permisos, seleccionar el permiso correspondiente
    setTimeout(() => {
        document.getElementById('asig_permiso').value = datos.permiso;
    }, 100);

    BtnGuardar.classList.add('d-none');
    BtnModificar.classList.remove('d-none');

    window.scrollTo({ top: 0 });
}

const ModificarAsignacion = async (event) => {
    event.preventDefault();
    BtnModificar.disabled = true;

    if (!validarFormulario(FormAsigPermisos)) {
        Swal.fire({
            position: "center",
            icon: "info",
            title: "FORMULARIO INCOMPLETO",
            text: "Debe completar todos los campos requeridos",
            showConfirmButton: true,
        });
        BtnModificar.disabled = false;
        return;
    }

    const body = new FormData(FormAsigPermisos);
    const url = '/app_login/asignacion/modificarAPI';
    const config = {
        method: 'POST',
        body
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje } = datos;

        if (codigo == 1) {
            await Swal.fire({
                position: "center",
                icon: "success",
                title: "Éxito",
                text: mensaje,
                showConfirmButton: true,
            });

            limpiarTodo();
            BuscarAsignaciones(true);

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
        console.error('Error:', error);
        await Swal.fire({
            position: "center",
            icon: "error",
            title: "Error de conexión",
            text: "No se pudo conectar con el servidor",
            showConfirmButton: true,
        });
    }
    
    BtnModificar.disabled = false;
}

const EliminarAsignacion = async (e) => {
    const idAsignacion = e.currentTarget.dataset.id;

    const AlertaConfirmarEliminar = await Swal.fire({
        position: "center",
        icon: "warning",
        title: "¿Desea ejecutar esta acción?",
        text: '¿Está completamente seguro de que desea eliminar esta asignación?',
        showConfirmButton: true,
        confirmButtonText: 'Sí, Eliminar',
        confirmButtonColor: '#d33',
        cancelButtonText: 'No, Cancelar',
        showCancelButton: true
    });

    if (AlertaConfirmarEliminar.isConfirmed) {
        const url = `/app_login/asignacion/eliminarAPI?id=${idAsignacion}`;
        const config = { method: 'GET' };

        try {
            const consulta = await fetch(url, config);
            const respuesta = await consulta.json();
            const { codigo, mensaje } = respuesta;

            if (codigo == 1) {
                await Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "Éxito",
                    text: mensaje,
                    showConfirmButton: true,
                });
                
                BuscarAsignaciones(true);
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
            console.error('Error:', error);
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

// Event Listeners
SelectApp.addEventListener('change', CargarPermisos);
FormAsigPermisos.addEventListener('submit', GuardarAsignacion);
BtnLimpiar.addEventListener('click', limpiarTodo);
BtnModificar.addEventListener('click', ModificarAsignacion);
BtnMostrarRegistros.addEventListener('click', MostrarRegistros);

// Event listeners de la tabla
datatable.on('click', '.eliminar', EliminarAsignacion);
datatable.on('click', '.modificar', llenarFormulario);

// Cargar datos iniciales
BuscarAsignaciones();