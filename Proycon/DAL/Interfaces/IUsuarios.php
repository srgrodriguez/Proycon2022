<?php


interface IUsuarios {
public function ObtenerDatosUsuario($ID_Usuario);
public function CambiarPassword($ID_Usuario,$ContraNueva);
public function CambiarNombre($ID_Usuario,$NuevoNombre);
public function RegistrarUsuario(Usuarios $usuarios);
public function ListarUsuarios();
public function ModificarUsuario(Usuarios $usuarios);
public function DesactivarUsuario($estado,$idUsuario);
public function ComboBox();
public function ValidarLogin($Usuario);
public function ValidarUsuarioRegistro($Usuario);

}
