

<header class="header">
        <div class="contenedor contenido-header">
            <div class="celular">
                <a href="principal.php?carrera=<?php echo $carrera; ?>"><img src="build/img/LogoUPG.png" alt="LogoUPG"></a>
                <div class="barras">
                  <span class="br-1"></span>
                  <span class="br-2"></span>
                  <span class="br-3"></span>
                </div>
            </div>
    
            <nav class="nav">
                
                <ul class="lista">
                    <li class="links">
                        <a href="<?php echo 'noticias.php?carrera=' . $carrera;?>">Noticias</a>
                    </li>
                    <li class="links">
                        <a href="<?php echo 'seccion.php?carrera=' . $carrera . '&seccion=estancias';?>">Estancias</a>
                    </li>
                    <li class="links">
                        <a href="<?php echo 'seccion.php?carrera=' . $carrera . '&seccion=estadias';?>">Estadías</a>
                    </li>
                    <li class="links">
                        <a href="<?php echo 'seccion.php?carrera=' . $carrera . '&seccion=titulacion';?>">Titulación</a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>