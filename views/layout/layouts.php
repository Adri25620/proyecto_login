<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="<?= asset('build/js/app.js') ?>"></script>
    <link rel="shortcut icon" href="<?= asset('images/cit.png') ?>" type="image/x-icon">
    <link rel="stylesheet" href="<?= asset('build/styles.css') ?>">
    <title>DemoApp</title>
</head>
<body>
<nav class="navbar navbar-expand-lg bg-dark navbar-dark">
    <div class="container-fluid">

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item me-4">
            <a class="nav-link active" aria-current="page" href="/app_login/bienvenida"><i class="bi bi-house"> INICIO</i></a>
          </li>
          <li class="nav-item me-4">
            <a class="nav-link active" aria-current="page" href="/app_login/usuarios"><i class="bi bi-house"> USUARIOS</i></a>
          </li>
          <li class="nav-item me-4">
            <a class="nav-link active" aria-current="page" href="/app_login/aplicacion"><i class="bi bi-house"> APLICACIONES</i></a>
          </li>
          <li class="nav-item me-4">
            <a class="nav-link active" aria-current="page" href="/app_login/asignacion"><i class="bi bi-house"> ASIGNACION PERMISO</i></a>
          </li>
          <li class="nav-item me-4">
            <a class="nav-link active" aria-current="page" href="/app_login/permisos"><i class="bi bi-house"> PERMISOS</i></a>
          </li>
          
        </ul>
      </div>
    </div>
  </nav>
    <div class="progress fixed-bottom" style="height: 6px;">
        <div class="progress-bar progress-bar-animated bg-danger" id="bar" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
    </div>
    <div class="container-fluid pt-5 mb-4" style="min-height: 85vh">
        
        <?php echo $contenido; ?>
    </div>
    <div class="container-fluid " >
        <div class="row justify-content-center text-center">
            <div class="col-12">
                <p style="font-size:xx-small; font-weight: bold;">
                        Comando de Informática y Tecnología, <?= date('Y') ?> &copy;
                </p>
            </div>
        </div>
    </div>


</body>
</html>