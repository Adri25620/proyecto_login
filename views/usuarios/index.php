<div class="row justify-content-center p-3">
    <div class="col-lg-10">
        <div class="card custom-card shadow-lg" style="border-radius: 10px; border: 1px solid #007bff;">
            <div class="card-body p-3">
                <div class="row mb-3">
                    <h3 class="text-center mb-2">BIENVENIDO</h3>
                    <h4 class="text-center mb-2 text-primary">Registro de Usuarios</h4>
                </div>

                <div class="row justify-content-center p-5 shadow-lg">

                    <form id="FormUsuarios" enctype="multipart/form-data">
                        <input type="hidden" id="us_id" name="us_id">

                        <div class="row mb-3 justify-content-center">
                            <div class="col-lg-6">
                                <label for="us_nom1" class="form-label">Primer Nombre</label>
                                <input type="text" class="form-control" id="us_nom1" name="us_nom1" placeholder="Ingrese primer nombre">
                            </div>
                            <div class="col-lg-6">
                                <label for="us_nom2" class="form-label">Segundo Nombre</label>
                                <input type="text" class="form-control" id="us_nom2" name="us_nom2" placeholder="Ingrese segundo nombre">
                            </div>
                        </div>

                        <div class="row mb-3 justify-content-center">
                            <div class="col-lg-6">
                                <label for="us_ape1" class="form-label">Primer Apellido</label>
                                <input type="text" class="form-control" id="us_ape1" name="us_ape1" placeholder="Ingrese primer apellido">
                            </div>
                            <div class="col-lg-6">
                                <label for="us_ape2" class="form-label">Segundo Apellido</label>
                                <input type="text" class="form-control" id="us_ape2" name="us_ape2" placeholder="Ingrese segundo apellido">
                            </div>
                        </div>

                        <div class="row mb-3 justify-content-center">
                            <div class="col-lg-4">
                                <label for="us_tel" class="form-label">Teléfono</label>
                                <input type="text" class="form-control" id="us_tel" name="us_tel" placeholder="Ingrese su número de teléfono">
                            </div>

                            <div class="col-lg-4">
                                <label for="us_dpi" class="form-label">DPI</label>
                                <input type="text" class="form-control" id="us_dpi" name="us_dpi" placeholder="Ingrese su DPI">
                            </div>

                            <div class="col-lg-4">
                                <label for="us_correo" class="form-label">Correo</label>
                                <input type="email" class="form-control" id="us_correo" name="us_correo" placeholder="ejemplo@correo.com">
                            </div>
                        </div>

                        <div class="row mb-3 justify-content-center">
                            <div class="col-lg-6">
                                <label for="us_contra" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" id="us_contra" name="us_contra" placeholder="Ingrese su contraseña">
                            </div>
                            <div class="col-lg-6">
                                <label for="us_confirmar_contra" class="form-label">Confirmar Contraseña</label>
                                <input type="password" class="form-control" id="us_confirmar_contra" name="us_confirmar_contra" placeholder="Confirme su contraseña">
                            </div>
                        </div>

                        <div class="row mb-3 justify-content-center">
                            <div class="col-lg-6">
                                <label for="us_direc" class="form-label">Dirección</label>
                                <input type="text" class="form-control" id="us_direc" name="us_direc" placeholder="Ingrese su dirección">
                            </div>
                            <div class="col-lg-6">
                                <label for="us_fotografia" class="form-label">Fotografía</label>
                                <input type="file" class="form-control form-control-lg" id="us_fotografia" name="us_fotografia" accept="image/*">
                                <div class="form-text">Formatos permitidos: JPG, PNG. Máximo 2MB</div>
                            </div>
                        </div>

                        <div class="row justify-content-center mt-5">
                            <div class="col-auto">
                                <button class="btn btn-success" type="submit" id="BtnGuardar"><i class="bi bi-floppy me-2"></i>
                                    Guardar
                                </button>
                            </div>

                            <div class="col-auto">
                                <button class="btn btn-warning d-none" type="button" id="BtnModificar"><i class="bi bi-pencil me-2"></i>
                                    Modificar
                                </button>
                            </div>

                            <div class="col-auto">
                                <button class="btn btn-secondary" type="reset" id="BtnLimpiar"><i class="bi bi-arrow-clockwise me-2"></i>
                                    Limpiar
                                </button>
                            </div>

                            <div class="col-auto">
                                <button class="btn btn-info" type="button" id="BtnMostrarRegistros">
                                    <i class="bi bi-eye me-2"></i>Mostrar Registros
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center p-3" id="seccionTablaRegistros" style="display: none;">
    <div class="col-lg-12">
        <div class="card custom-card shadow-lg" style="border-radius: 10px; border: 1px solid #007bff;">
            <div class="card-body p-3">
                <h3 class="text-center">USUARIOS REGISTRADOS</h3>

                <div class="table-responsive p-2">
                    <table class="table table-striped table-hover table-bordered w-100 table-sm" id="TableUsuarios">
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

<script src="<?= asset('build/js/usuarios/index.js') ?>"></script>
