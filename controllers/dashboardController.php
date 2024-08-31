<?php

require_once './models/dashboardModel.php';
require_once './models/UsuarioModel.php';

class dashboardController
{

        #estableciendo las vistas
        public function inicio()
        {

                session_start();

                if (isset($_SESSION['user_id'])) {
                        $id_usuario = $_SESSION['user_id'];

                        $modelUsuario = new UsuarioModel();
                        $resultado = $modelUsuario->obtenerUsuario($id_usuario);

                        if ($resultado) {
                                foreach ($resultado as $resultado) {
                                        $id_bd = $resultado['id'];
                                        $usuario_bd = $resultado['usuario'];
                                }

                                $_SESSION['usuario'] = $usuario_bd;

                                require_once('./views/includes/cabecera.php');
                                require_once('./views/paginas/dashboard/inicio.php');
                                require_once('./views/includes/pie.php');
                        } else {
                                header('Location: index.php?page=inicioUsuario');
                        }
                }
        }

        public function inicioProfile()
        {
                /*HEADER */
                require_once('./views/includes/cabecera.php');

                require_once('./views/paginas/usuarios/inicioProfile.php');

                /* FOOTER */
                require_once('./views/includes/pie.php');
        }
}
