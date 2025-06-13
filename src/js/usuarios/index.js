// Importar las librerías necesarias para el funcionamiento
import Swal from "sweetalert2";
import { validarFormulario } from '../funciones';
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";

// Aquí se capturan los elementos del formulario y botones del HTML
const FormUsuarios = document.getElementById('FormUsuarios');
const BtnGuardar = document.getElementById('BtnGuardar');
const BtnModificar = document.getElementById('BtnModificar');
const BtnLimpiar = document.getElementById('BtnLimpiar');
const BtnMostrarRegistros = document.getElementById('BtnMostrarRegistros');
const SeccionTabla = document.getElementById('seccionTablaRegistros');

// Aquí se capturan los campos específicos que necesitan validación
const us_tel = document.getElementById('us_tel');
const us_dpi = document.getElementById('us_dpi');
const us_correo = document.getElementById('us_correo');
const us_contra = document.getElementById('us_contra');
const us_confirmar_contra = document.getElementById('us_confirmar_contra');

// Aquí se muestra u oculta la tabla de registros
const MostrarRegistros = () => {
    // Aquí se pregunta si la tabla está oculta
    const estaOculto = SeccionTabla.style.display === 'none';
    
    // Aquí se pregunta si está oculta para mostrarla
    if (estaOculto) {
        // Aquí se muestra la tabla
        SeccionTabla.style.display = 'block';
        // Aquí se cambia el texto del botón
        BtnMostrarRegistros.innerHTML = '<i class="bi bi-eye-slash me-2"></i>Ocultar Registros';
        // Aquí se cambia el color del botón
        BtnMostrarRegistros.classList.remove('btn-info');
        BtnMostrarRegistros.classList.add('btn-warning');
        // Aquí se cargan los usuarios
        BuscarUsuarios(false);
    } else {
        // Aquí se oculta la tabla
        SeccionTabla.style.display = 'none';
        // Aquí se cambia el texto del botón
        BtnMostrarRegistros.innerHTML = '<i class="bi bi-eye me-2"></i>Mostrar Registros';
        // Aquí se cambia el color del botón
        BtnMostrarRegistros.classList.remove('btn-warning');
        BtnMostrarRegistros.classList.add('btn-info');
    }
}

// Aquí se valida el teléfono - debe tener exactamente 8 dígitos
const ValidarTelefono = () => {
    // Aquí se obtiene el valor del teléfono y se quitan espacios
    const CantidadDigitos = us_tel.value.trim();

    // Aquí se pregunta si el campo está vacío
    if (CantidadDigitos.length < 1) {
        // Aquí se quitan las clases de validación si está vacío
        us_tel.classList.remove('is-valid', 'is-invalid');
        return true; // Aquí se dice que está bien si está vacío
    } else {
        // Aquí se pregunta si NO tiene exactamente 8 dígitos
        if (CantidadDigitos.length !== 8) {
            // Aquí se pone la clase roja (error)
            us_tel.classList.add('is-invalid');
            us_tel.classList.remove('is-valid');
            return false; // Aquí se dice que la validación falló
        } else {
            // Aquí se pone la clase verde (correcto) porque tiene 8 dígitos
            us_tel.classList.remove('is-invalid');
            us_tel.classList.add('is-valid');
            return true; // Aquí se dice que la validación pasó
        }
    }
}

// Aquí se valida el DPI - debe tener exactamente 13 dígitos
const ValidarDpi = () => {
    // Aquí se obtiene el valor del DPI y se quitan espacios
    const CantidadDigitos = us_dpi.value.trim();

    // Aquí se pregunta si el campo está vacío
    if (CantidadDigitos.length < 1) {
        // Aquí se quitan las clases de validación si está vacío
        us_dpi.classList.remove('is-valid', 'is-invalid');
        return true; // Aquí se dice que está bien si está vacío
    } else {
        // Aquí se pregunta si NO tiene exactamente 13 dígitos
        if (CantidadDigitos.length !== 13) {
            // Aquí se pone la clase roja (error)
            us_dpi.classList.add('is-invalid');
            us_dpi.classList.remove('is-valid');
            return false; // Aquí se dice que la validación falló
        } else {
            // Aquí se pone la clase verde (correcto) porque tiene 13 dígitos
            us_dpi.classList.remove('is-invalid');
            us_dpi.classList.add('is-valid');
            return true; // Aquí se dice que la validación pasó
        }
    }
}

// Aquí se valida que el correo tenga formato correcto
const ValidarCorreo = () => {
    // Aquí se define el patrón para validar correos
    const patron = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    // Aquí se obtiene el valor del correo y se quitan espacios
    const correo = us_correo.value.trim();

    // Aquí se pregunta si el campo está vacío
    if (correo.length < 1) {
        // Aquí se quitan las clases de validación si está vacío
        us_correo.classList.remove('is-valid', 'is-invalid');
        return true; // Aquí se dice que está bien si está vacío
    } else {
        // Aquí se pregunta si NO cumple con el patrón de correo
        if (!patron.test(correo)) {
            // Aquí se pone la clase roja (error)
            us_correo.classList.add('is-invalid');
            us_correo.classList.remove('is-valid');
            return false; // Aquí se dice que la validación falló
        } else {
            // Aquí se pone la clase verde (correcto) porque es un correo válido
            us_correo.classList.remove('is-invalid');
            us_correo.classList.add('is-valid');
            return true; // Aquí se dice que la validación pasó
        }
    }
}

// Aquí se valida que la contraseña sea segura
const ValidarContrasenaSegura = () => {
    // Aquí se obtiene el valor de la contraseña
    const password = us_contra.value;
    // Aquí se crea una lista para guardar qué le falta a la contraseña
    let errores = [];
    
    // Aquí se pregunta si el campo está vacío
    if (password.length < 1) {
        // Aquí se quitan las clases de validación si está vacío
        us_contra.classList.remove('is-valid', 'is-invalid');
        us_contra.title = '';
        return true; // Aquí se dice que está bien si está vacío
    }
    
    // Aquí se revisan todos los requisitos de la contraseña
    if (password.length < 8) errores.push("Mínimo 8 caracteres"); // Aquí se pregunta si es muy corta
    if (!/[A-Z]/.test(password)) errores.push("Al menos una mayúscula"); // Aquí se pregunta si tiene mayúscula
    if (!/[a-z]/.test(password)) errores.push("Al menos una minúscula"); // Aquí se pregunta si tiene minúscula
    if (!/[0-9]/.test(password)) errores.push("Al menos un número"); // Aquí se pregunta si tiene número
    if (!/[!@#$%^&*()_+\-=\[\]{};':\"\\|,.<>\/?]/.test(password)) errores.push("Al menos un carácter especial"); // Aquí se pregunta si tiene símbolo
    
    // Aquí se pregunta si encontró errores
    if (errores.length > 0) {
        // Aquí se pone la clase roja (error)
        us_contra.classList.add('is-invalid');
        us_contra.classList.remove('is-valid');
        // Aquí se muestra en el tooltip qué le falta
        us_contra.title = "Falta: " + errores.join(", ");
        return false; // Aquí se dice que la validación falló
    } else {
        // Aquí se pone la clase verde (correcto)
        us_contra.classList.remove('is-invalid');
        us_contra.classList.add('is-valid');
        // Aquí se muestra que la contraseña está bien
        us_contra.title = "Contraseña segura ✓";
        return true; // Aquí se dice que la validación pasó
    }
}

// Aquí se valida que las contraseñas coincidan
const ValidarConfirmarContrasena = () => {
    // Aquí se pregunta si el campo de confirmación está vacío
    if (us_confirmar_contra.value.length < 1) {
        // Aquí se quitan las clases de validación si está vacío
        us_confirmar_contra.classList.remove('is-valid', 'is-invalid');
        return true; // Aquí se dice que está bien si está vacío
    }

    // Aquí se pregunta si las dos contraseñas NO son iguales
    if (us_contra.value !== us_confirmar_contra.value) {
        // Aquí se pone la clase roja (error)
        us_confirmar_contra.classList.add('is-invalid');
        us_confirmar_contra.classList.remove('is-valid');
        return false; // Aquí se dice que la validación falló
    } else {
        // Aquí se pone la clase verde (correcto) porque coinciden
        us_confirmar_contra.classList.remove('is-invalid');
        us_confirmar_contra.classList.add('is-valid');
        return true; // Aquí se dice que la validación pasó
    }
}

// Aquí se validan todas las contraseñas juntas
const ValidarContraseñas = () => {
    const contrasenaValida = ValidarContrasenaSegura();
    const confirmarValida = ValidarConfirmarContrasena();
    return contrasenaValida && confirmarValida;
}

// Aquí se guarda un nuevo usuario
const GuardarUsuario = async (event) => {
    // Aquí se evita que el formulario se envíe de forma normal
    event.preventDefault();
    // Aquí se desactiva el botón para evitar clicks múltiples
    BtnGuardar.disabled = true;

    // Aquí se validan todos los campos antes de enviar
    const telefonoValido = ValidarTelefono();
    const dpiValido = ValidarDpi();
    const correoValido = ValidarCorreo();
    const contraseñasValidas = ValidarContraseñas();

    // Aquí se pregunta si alguna validación falló
    if (!telefonoValido || !dpiValido || !correoValido || !contraseñasValidas) {
        // Aquí se muestra mensaje de que el formulario está incompleto
        Swal.fire({
            position: "center",
            icon: "warning",
            title: "FORMULARIO INCOMPLETO",
            text: "Verifique todos los campos marcados en rojo",
            showConfirmButton: true,
        });
        // Aquí se reactiva el botón
        BtnGuardar.disabled = false;
        return; // Aquí se sale de la función sin enviar nada
    }

    // Aquí se valida que todos los campos obligatorios estén llenos
    if (!validarFormulario(FormUsuarios, ['us_id', 'us_nom2', 'us_ape2', 'us_fotografia'])) {
        // Aquí se muestra mensaje de formulario incompleto
        Swal.fire({
            position: "center",
            icon: "info",
            title: "FORMULARIO INCOMPLETO",
            text: "Debe de validar todos los campos obligatorios",
            showConfirmButton: true,
        });
        // Aquí se reactiva el botón
        BtnGuardar.disabled = false;
        return; // Aquí se sale de la función
    }

    // Aquí se preparan los datos del formulario para enviar
    const body = new FormData(FormUsuarios);

    // Aquí se define la URL donde se enviarán los datos
    const url = '/app_login/usuarios/guardarAPI';
    // Aquí se configura el método POST
    const config = {
        method: 'POST',
        body
    }

    try {
        // Aquí se envían los datos al servidor
        const respuesta = await fetch(url, config);
        // Aquí se convierte la respuesta a JSON
        const datos = await respuesta.json();
        // Aquí se extraen el código y mensaje de la respuesta
        const { codigo, mensaje } = datos

        // Aquí se pregunta si el guardado fue exitoso
        if (codigo == 1) {
            // Aquí se muestra mensaje de éxito
            await Swal.fire({
                position: "center",
                icon: "success",
                title: "¡Éxito!",
                text: mensaje,
                showConfirmButton: true,
            });

            // Aquí se limpia el formulario
            limpiarTodo();
        } else {
            // Aquí se muestra mensaje de error si algo salió mal
            await Swal.fire({
                position: "center",
                icon: "error",
                title: "Error",
                text: mensaje,
                showConfirmButton: true,
            });
        }

    } catch (error) {
        // Aquí se captura cualquier error de conexión
        console.log(error)
        // Aquí se muestra mensaje de error de conexión
        await Swal.fire({
            position: "center",
            icon: "error",
            title: "Error de conexión",
            text: "No se pudo conectar con el servidor",
            showConfirmButton: true,
        });
    }
    // Aquí se reactiva el botón al final
    BtnGuardar.disabled = false;
}

// Aquí se buscan y cargan todos los usuarios
const BuscarUsuarios = async (mostrarMensaje = false) => {
    // Aquí se define la URL para buscar usuarios
    const url = '/app_login/usuarios/buscarAPI';
    // Aquí se configura el método GET
    const config = {
        method: 'GET'
    }

    try {
        // Aquí se solicitan los datos al servidor
        const respuesta = await fetch(url, config);
        
        // Aquí se verifica si la respuesta es exitosa
        if (!respuesta.ok) {
            throw new Error(`HTTP error! status: ${respuesta.status}`);
        }
        
        // Aquí se convierte la respuesta a JSON
        const datos = await respuesta.json();
        
        // Aquí se muestran los datos en consola para debug
        console.log('Datos recibidos:', datos);
        
        // Aquí se extraen el código, mensaje y datos de la respuesta
        const { codigo, mensaje, data } = datos

        // Aquí se pregunta si se obtuvieron datos correctamente
        if (codigo == 1) {
            // Aquí se pregunta si se debe mostrar mensaje
            if (mostrarMensaje) {
                // Aquí se muestra mensaje de éxito con la cantidad de usuarios
                await Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "¡Usuarios cargados!",
                    text: `Se cargaron ${data.length} usuario(s) correctamente`,
                    showConfirmButton: true,
                    timer: 2000 // Aquí se cierra automáticamente en 2 segundos
                });
            }

            // Aquí se limpian los datos anteriores de la tabla
            datatable.clear().draw();
            // Aquí se agregan los nuevos datos a la tabla
            datatable.rows.add(data).draw();

        } else {
            // Aquí se muestra mensaje si no hay datos
            console.log('Error del servidor:', mensaje);
            await Swal.fire({
                position: "center",
                icon: "info",
                title: "Sin datos",
                text: mensaje,
                showConfirmButton: true,
            });
        }

    } catch (error) {
        // Aquí se captura cualquier error de conexión
        console.log('Error completo:', error);
        await Swal.fire({
            position: "center",
            icon: "error",
            title: "Error de conexión",
            text: "No se pudieron cargar los usuarios. Revisa la consola para más detalles.",
            showConfirmButton: true,
        });
    }
}

// Aquí se configura la tabla de usuarios con DataTables
const datatable = new DataTable('#TableUsuarios', {
    // Aquí se define el diseño de la tabla (buscador, paginación, etc.)
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
    // Aquí se define el idioma de la tabla
    language: lenguaje,
    // Aquí se inicia la tabla sin datos
    data: [],
    // Aquí se definen las columnas de la tabla
    columns: [
        {
            // Aquí se crea la columna de número correlativo
            title: 'No.',
            data: 'us_id',
            width: '5%',
            render: (data, type, row, meta) => meta.row + 1 // Aquí se numera automáticamente
        },
        { 
            // Aquí se muestra la columna de foto
            title: 'Foto', 
            data: 'foto_url',
            width: '8%',
            searchable: false, // Aquí se dice que no se puede buscar por foto
            orderable: false, // Aquí se dice que no se puede ordenar por foto
            render: (data, type, row) => {
                // Aquí se pregunta si tiene foto
                if (data) {
                    // Aquí se muestra la imagen
                    return `<img src="${data}" alt="Foto" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">`;
                } else {
                    // Aquí se muestra un ícono si no tiene foto
                    return '<i class="bi bi-person-circle" style="font-size: 30px;"></i>';
                }
            }
        },
        { 
            // Aquí se muestra la columna de primer nombre
            title: 'Primer Nombre', 
            data: 'us_nom1' 
        },
        { 
            // Aquí se muestra la columna de primer apellido
            title: 'Primer Apellido', 
            data: 'us_ape1' 
        },
        { 
            // Aquí se muestra la columna de segundo apellido
            title: 'Segundo Apellido', 
            data: 'us_ape2' 
        },
        { 
            // Aquí se muestra la columna de teléfono
            title: 'Teléfono', 
            data: 'us_tel' 
        },
        { 
            // Aquí se muestra la columna de DPI
            title: 'DPI', 
            data: 'us_dpi' 
        },
        { 
            // Aquí se muestra la columna de correo
            title: 'Correo', 
            data: 'us_correo' 
        },
        {
            // Aquí se crean los botones de acciones
            title: 'Acciones',
            data: 'us_id',
            searchable: false, // Aquí se dice que no se puede buscar por acciones
            orderable: false, // Aquí se dice que no se puede ordenar por acciones
            render: (data, type, row, meta) => {
                // Aquí se crean los botones de modificar y eliminar
                return `
                 <div class='d-flex justify-content-center'>
                     <button class='btn btn-warning modificar mx-1' 
                         data-id="${data}" 
                         data-nom1="${row.us_nom1}"  
                         data-nom2="${row.us_nom2 || ''}"  
                         data-ape1="${row.us_ape1}"  
                         data-ape2="${row.us_ape2 || ''}"  
                         data-tel="${row.us_tel}"  
                         data-dpi="${row.us_dpi}"  
                         data-correo="${row.us_correo}"  
                         data-direc="${row.us_direc}">   
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

// Aquí se llena el formulario con los datos del usuario a modificar
const llenarFormulario = (event) => {
    // Aquí se obtienen los datos del botón que se presionó
    const datos = event.currentTarget.dataset

    // Aquí se llenan todos los campos del formulario con los datos del usuario
    document.getElementById('us_id').value = datos.id
    document.getElementById('us_nom1').value = datos.nom1
    document.getElementById('us_nom2').value = datos.nom2 || ''
    document.getElementById('us_ape1').value = datos.ape1
    document.getElementById('us_ape2').value = datos.ape2 || ''
    document.getElementById('us_tel').value = datos.tel
    document.getElementById('us_dpi').value = datos.dpi
    document.getElementById('us_correo').value = datos.correo
    document.getElementById('us_direc').value = datos.direc

    // Aquí se ocultan los campos de contraseña en modificación
    document.getElementById('us_contra').parentElement.style.display = 'none';
    document.getElementById('us_confirmar_contra').parentElement.style.display = 'none';

    // Aquí se oculta el botón de guardar
    BtnGuardar.classList.add('d-none');
    // Aquí se muestra el botón de modificar
    BtnModificar.classList.remove('d-none');

    // Aquí se lleva al usuario al inicio de la página
    window.scrollTo({
        top: 0
    });
}

// Aquí se limpia todo el formulario y se regresa al estado inicial
const limpiarTodo = () => {
    // Aquí se limpian todos los campos del formulario
    FormUsuarios.reset();
    // Aquí se muestra el botón de guardar
    BtnGuardar.classList.remove('d-none');
    // Aquí se oculta el botón de modificar
    BtnModificar.classList.add('d-none');
    
    // Aquí se muestran los campos de contraseña
    document.getElementById('us_contra').parentElement.style.display = 'block';
    document.getElementById('us_confirmar_contra').parentElement.style.display = 'block';
    
    // Aquí se quitan todas las clases de validación de todos los campos
    FormUsuarios.querySelectorAll('.form-control, .form-select').forEach(element => {
        element.classList.remove('is-valid', 'is-invalid');
        element.title = ''; // Aquí se limpian los tooltips
    });
}

// Aquí se modifica un usuario existente
const ModificarUsuario = async (event) => {
    // Aquí se evita que el formulario se envíe de forma normal
    event.preventDefault();
    // Aquí se desactiva el botón para evitar clicks múltiples
    BtnModificar.disabled = true;

    // Aquí se valida que todos los campos obligatorios estén llenos (sin contraseñas y sin foto)
    if (!validarFormulario(FormUsuarios, ['us_contra', 'us_confirmar_contra', 'us_nom2', 'us_ape2', 'us_fotografia'])) {
        // Aquí se muestra mensaje de formulario incompleto
        Swal.fire({
            position: "center",
            icon: "info",
            title: "FORMULARIO INCOMPLETO",
            text: "Debe de validar todos los campos obligatorios",
            showConfirmButton: true,
        });
        // Aquí se reactiva el botón
        BtnModificar.disabled = false;
        return; // Aquí se sale de la función
    }

    // Aquí se preparan los datos del formulario para enviar
    const body = new FormData(FormUsuarios);
    
    // Aquí se remueven los campos de contraseña para modificación
    body.delete('us_contra');
    body.delete('us_confirmar_contra');

    // Aquí se define la URL donde se enviarán los datos de modificación
    const url = '/app_login/usuarios/modificarAPI';
    // Aquí se configura el método POST
    const config = {
        method: 'POST',
        body
    }

    try {
        // Aquí se envían los datos al servidor
        const respuesta = await fetch(url, config);
        // Aquí se convierte la respuesta a JSON
        const datos = await respuesta.json();
        // Aquí se extraen el código y mensaje de la respuesta
        const { codigo, mensaje } = datos

        // Aquí se pregunta si la modificación fue exitosa
        if (codigo == 1) {
            // Aquí se muestra mensaje de éxito
            await Swal.fire({
                position: "center",
                icon: "success",
                title: "¡Éxito!",
                text: mensaje,
                showConfirmButton: true,
            });

            // Aquí se limpia el formulario
            limpiarTodo();
            // Aquí se recargan los usuarios para mostrar los cambios
            BuscarUsuarios(true);

        } else {
            // Aquí se muestra mensaje de error si algo salió mal
            await Swal.fire({
                position: "center",
                icon: "error",
                title: "Error",
                text: mensaje,
                showConfirmButton: true,
            });
        }

    } catch (error) {
        // Aquí se captura cualquier error de conexión
        console.log('Error al modificar:', error)
        await Swal.fire({
            position: "center",
            icon: "error",
            title: "Error de conexión",
            text: "No se pudo completar la modificación",
            showConfirmButton: true,
        });
    }
    // Aquí se reactiva el botón al final
    BtnModificar.disabled = false;
}

// Aquí se elimina un usuario
const EliminarUsuarios = async (e) => {
    // Aquí se obtiene el ID del usuario desde el botón que se presionó
    const idUsuario = e.currentTarget.dataset.id

    // Aquí se pregunta al usuario si está seguro de eliminar
    const AlertaConfirmarEliminar = await Swal.fire({
        position: "center",
        icon: "question",
        title: "¿Desea ejecutar esta acción?",
        text: 'Está completamente seguro que desea eliminar este registro',
        showConfirmButton: true,
        confirmButtonText: 'Sí, Eliminar',
        confirmButtonColor: '#d33',
        cancelButtonText: 'No, Cancelar',
        showCancelButton: true
    });

    // Aquí se pregunta si el usuario confirmó la eliminación
    if (AlertaConfirmarEliminar.isConfirmed) {
        // Aquí se define la URL para eliminar el usuario
        const url = `/app_login/usuarios/eliminarAPI?id=${idUsuario}`;
        // Aquí se configura el método GET
        const config = {
            method: 'GET'
        }

        try {
            // Aquí se envía la petición de eliminación
            const consulta = await fetch(url, config);
            // Aquí se convierte la respuesta a JSON
            const respuesta = await consulta.json();
            // Aquí se extraen el código y mensaje de la respuesta
            const { codigo, mensaje } = respuesta;

            // Aquí se pregunta si la eliminación fue exitosa
            if (codigo == 1) {
                // Aquí se muestra mensaje de éxito
                await Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "¡Éxito!",
                    text: mensaje,
                    showConfirmButton: true,
                });
                
                // Aquí se recargan los usuarios para quitar el eliminado
                BuscarUsuarios(true);
            } else {
                // Aquí se muestra mensaje de error si algo salió mal
                await Swal.fire({
                    position: "center",
                    icon: "error",
                    title: "Error",
                    text: mensaje,
                    showConfirmButton: true,
                });
            }

        } catch (error) {
            // Aquí se captura cualquier error de conexión
            console.log(error)
            await Swal.fire({
                position: "center",
                icon: "error",
                title: "Error de conexión",
                text: "No se pudo completar la eliminación",
                showConfirmButton: true,
            });
        }
    }
}

// Aquí se configuran todos los eventos (qué pasa cuando se hace click o se cambia algo)
BuscarUsuarios(); // Aquí se cargan los usuarios al inicio
datatable.on('click', '.eliminar', EliminarUsuarios); // Aquí se dice qué hacer cuando se hace click en eliminar de la tabla
datatable.on('click', '.modificar', llenarFormulario); // Aquí se dice qué hacer cuando se hace click en modificar de la tabla
FormUsuarios.addEventListener('submit', GuardarUsuario); // Aquí se dice qué hacer cuando se envía el formulario
us_tel.addEventListener('input', ValidarTelefono); // Aquí se valida el teléfono mientras se escribe
us_dpi.addEventListener('input', ValidarDpi); // Aquí se valida el DPI mientras se escribe
us_correo.addEventListener('input', ValidarCorreo); // Aquí se valida el correo mientras se escribe
us_contra.addEventListener('input', ValidarContrasenaSegura); // Aquí se valida la contraseña mientras se escribe
us_confirmar_contra.addEventListener('input', ValidarConfirmarContrasena); // Aquí se valida la confirmación mientras se escribe
BtnLimpiar.addEventListener('click', limpiarTodo); // Aquí se dice qué hacer cuando se hace click en limpiar
BtnModificar.addEventListener('click', ModificarUsuario); // Aquí se dice qué hacer cuando se hace click en modificar
BtnMostrarRegistros.addEventListener('click', MostrarRegistros); // Aquí se dice qué hacer cuando se hace click en mostrar registros