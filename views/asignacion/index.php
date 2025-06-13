<div class="row justify-content-center p-3">
    <div class="col-lg-10">
        <div class="card custom-card shadow-lg" style="border-radius: 10px; border: 1px solid #007bff;">
            <div class="card-body p-3">
                <div class="row mb-3">
                    <h3 class="text-center mb-2">BIENVENIDO</h3>
                    <h4 class="text-center mb-2 text-primary">Asignación de Permisos</h4>
                </div>

                <div class="row justify-content-center p-5 shadow-lg">

                    <form id="FormAsigPermisos">
                        <input type="hidden" id="asig_id" name="asig_id">

                        <div class="row mb-3 justify-content-center">
                            <div class="col-lg-6">
                                <label for="asig_usuario" class="form-label">Seleccione el usuario:</label>
                                <select name="asig_usuario" id="asig_usuario" class="form-select" required>
                                    <option value="" selected disabled>Seleccione un usuario...</option>
                                    <?php foreach ($usuarios as $usuario): ?>
                                        <?php if ($usuario->us_situacion == 1): ?>
                                            <option value="<?= $usuario->us_id ?>">
                                                <?= $usuario->us_nom1 . ' ' . $usuario->us_ape1 . ' - ' . $usuario->us_correo ?>
                                            </option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-lg-6">
                                <label for="asig_app" class="form-label">Seleccione la aplicación:</label>
                                <select name="asig_app" id="asig_app" class="form-select" required>
                                    <option value="" selected disabled>Seleccione una aplicación...</option>
                                    <?php foreach ($aplicaciones as $aplicacion): ?>
                                        <?php if ($aplicacion->ap_situacion == 1): ?>
                                            <option value="<?= $aplicacion->ap_id ?>"><?= $aplicacion->ap_nombre_largo ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3 justify-content-center">
                            <div class="col-lg-6">
                                <label for="asig_permiso" class="form-label">Seleccione el permiso:</label>
                                <select name="asig_permiso" id="asig_permiso" class="form-select" required>
                                    <option value="" selected disabled>Primero seleccione una aplicación...</option>
                                </select>
                            </div>
                            <div class="col-lg-6">
                                <label for="asig_motivo" class="form-label">Motivo de la asignación:</label>
                                <input type="text" class="form-control" id="asig_motivo" name="asig_motivo" placeholder="Ingrese el motivo de la asignación" required>
                            </div>
                        </div>

                        <div class="row justify-content-center mt-5">
                            <div class="col-auto">
                                <button class="btn btn-success" type="submit" id="BtnGuardar"><i class="bi bi-floppy me-2"></i>
                                    Asignar Permiso
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
    <div class="col-lg-10">
        <div class="card custom-card shadow-lg" style="border-radius: 10px; border: 1px solid #007bff;">
            <div class="card-body p-3">
                <h3 class="text-center">PERMISOS ASIGNADOS</h3>


                <div class="table-responsive p-2">
                    <table class="table table-striped table-hover table-bordered w-100 table-sm" id="TableAsigPermisos">
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

<script src="<?= asset('build/js/asignacion/index.js') ?>"></script>