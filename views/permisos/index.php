<div class="row justify-content-center p-3">
    <div class="col-lg-10">
        <div class="card custom-card shadow-lg" style="border-radius: 10px; border: 1px solid #007bff;">
            <div class="card-body p-3">
                <div class="row mb-3">
                    <h3 class="text-center mb-2">BIENVENIDO</h3>
                    <h4 class="text-center mb-2 text-primary">Registro de Permisos</h4>
                </div>

                <div class="row justify-content-center p-5 shadow-lg">

                    <form id="FormPermisos">
                        <input type="hidden" id="per_id" name="per_id">

                        <div class="row mb-3 justify-content-center">
                            <div class="col-lg-6">
                                <label for="per_aplicacion" class="form-label">Seleccione la aplicación:</label>
                                <select name="per_aplicacion" id="per_aplicacion" class="form-select" required>
                                    <option value="" selected disabled>Seleccione una aplicación...</option>
                                    <?php foreach ($aplicaciones as $aplicacion): ?>
                                        <?php if ($aplicacion->ap_situacion == 1): ?>
                                            <option value="<?= $aplicacion->ap_id ?>"><?= $aplicacion->ap_nombre_largo ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-lg-6">
                                <label for="per_nombre" class="form-label">Nombre del Permiso</label>
                                <input type="text" class="form-control" id="per_nombre" name="per_nombre" placeholder="Ingrese el nombre del permiso">
                            </div>
                        </div>

                        <div class="row mb-3 justify-content-center">
                            <div class="col-lg-6">
                                <label for="per_clave" class="form-label">Clave del Permiso</label>
                                <input type="text" class="form-control" id="per_clave" name="per_clave" placeholder="Ej: USERS_CREATE, REPORTS_VIEW">
                            </div>
                            <div class="col-lg-6">
                                <label for="per_descripcion" class="form-label">Descripción</label>
                                <input type="text" class="form-control" id="per_descripcion" name="per_descripcion" placeholder="Descripción del permiso">
                            </div>
                        </div>

                        <div class="row justify-content-center mt-5">
                            <div class="col-auto">
                                <button class="btn btn-success" type="submit" id="BtnGuardar"><i class="bi bi-floppy me-2"></i>
                                    Guardar
                                </button>
                            </div>

                            <div class="col-auto ">
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
                <h3 class="text-center">PERMISOS REGISTRADOS</h3>

                <div class="table-responsive p-2">
                    <table class="table table-striped table-hover table-bordered w-100 table-sm" id="TablePermisos">
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

<script src="<?= asset('build/js/permisos/index.js') ?>"></script>