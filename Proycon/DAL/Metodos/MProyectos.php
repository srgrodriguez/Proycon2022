<?php

class MProyectos implements IProyectos {

    var $conn;

    public function __construct() {

        $conexion = new Conexion();
        $this->conn = $conexion->CrearConexion();
    }

    public function RegistrarProyecto(Proyectos $proyecto) {
        $proyecto->Encargado = LimpiarCadenaCaracter($this->conn, $proyecto->Encargado);
        $proyecto->Nombre = LimpiarCadenaCaracter($this->conn, $proyecto->Nombre);
        $proyecto->FechaCreacion = LimpiarCadenaCaracter($this->conn, $proyecto->FechaCreacion);
        $proyecto->TipoProyecto = LimpiarCadenaCaracter($this->conn, $proyecto->TipoProyecto);
        $sql = "insert into tbl_proyectos(Nombre,DirectorProyecto,FechaCreacion,TipoProyecto) values(?,?,?,?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssi", $proyecto->Nombre, $proyecto->Encargado, $proyecto->FechaCreacion,$proyecto->TipoProyecto);
        $ok = $stmt->execute();
        $stmt->close();
        $this->conn->close();
        return $ok ? 1 : 0;
    }

    public function ModificarProyecto(Proyectos $proyecto, $ID) {
        $proyecto->Nombre = LimpiarCadenaCaracter($this->conn, $proyecto->Nombre);
        $proyecto->Encargado = LimpiarCadenaCaracter($this->conn, $proyecto->Encargado);
        $proyecto->FechaCreacion = LimpiarCadenaCaracter($this->conn, $proyecto->FechaCreacion);
        $proyecto->TipoProyecto = LimpiarCadenaCaracter($this->conn, $proyecto->TipoProyecto);
        $ID = LimpiarCadenaCaracter($this->conn, $ID);
        $sql = "update tbl_proyectos set Nombre = ?,FechaCreacion=?,DirectorProyecto=?,TipoProyecto=? where ID_Proyecto = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssii", $proyecto->Nombre, $proyecto->FechaCreacion, $proyecto->Encargado,$proyecto->TipoProyecto, $ID);
        $ok = $stmt->execute();
        $stmt->close();
        $this->conn->close();
        return $ok ? 1 : 0;
    }

    public function CerrarProyecto() {
        
    }

    public function BuscarProyecto($nombre) {
        $like = "%" . $nombre . "%";
        $sql = "SELECT * FROM tbl_proyectos WHERE NOMBRE LIKE ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $like);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $stmt->close();
        $this->conn->close();
        return $resultado;
    }

    public function ListarProyectos() {
        $conexion = new Conexion();
        $conn = $conexion->CrearConexion();
        $sql = "SELECT ID_Proyecto,Nombre,TipoProyecto,(SELECT DISTINCT ID_Proyecto FROM tbl_notificaciones WHERE ID_Proyecto = tp.ID_Proyecto ) as noti FROM tbl_proyectos tp where Estado = 1 order by noti desc;";
        $result = $this->conn->query($sql);
        $this->conn->close();
        return $result;
    }

    public function ListaHerramientaProyecto($idProyecto) {
        $idProyecto = LimpiarCadenaCaracter($this->conn, $idProyecto);
        $sql = "SELECT tp.Codigo,tt.Descripcion,tp.FechaSalida, th.Estado, tp.NBoleta FROM tbl_prestamoherramientas tp, tbl_herramientaelectrica th, tbl_tipoherramienta tt where 
            th.Ubicacion = ? and tp.ID_Tipo = tt.ID_Tipo and tp.Codigo =  th.Codigo and tt.TipoEquipo = 'H';
            ;";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $idProyecto);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $stmt->close();
        $this->conn->close();
        return $resultado;
    }

    public function ListaMaquinariaProyecto($idProyecto) {
        $idProyecto = LimpiarCadenaCaracter($this->conn, $idProyecto);
        $sql = "SELECT tp.Codigo,tt.Descripcion,tp.FechaSalida, th.Estado, tp.NBoleta FROM tbl_prestamoherramientas tp, tbl_herramientaelectrica th, tbl_tipoherramienta tt where 
            th.Ubicacion = ? and tp.ID_Tipo = tt.ID_Tipo and tp.Codigo =  th.Codigo and tt.TipoEquipo = 'M';
            ;";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $idProyecto);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $stmt->close();
        $this->conn->close();
        return $resultado;
    }




    public function ListaMaterialesProyecto($idProyecto) {
        $sql = "SELECT m.Codigo, m.Nombre,m.Devolucion,pm.Cantidad,pm.Pendiente,bp.Fecha,bp.Consecutivo FROM tbl_boletaspedido bp, tbl_prestamomateriales pm,tbl_materiales m where bp.Consecutivo = pm.NBoleta and bp.ID_Proyecto = ? and pm.ID_Material = m.Codigo ORDER BY bp.Consecutivo DESC ;";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $idProyecto);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $stmt->close();
        $this->conn->close();
        return $resultado;
    }

    public function ObternerCosecutivoPedido() {
        $sql = " select Consecutivo from tbl_boletaspedido order by Consecutivo desc limit 1;";
        $result = $this->conn->query($sql);
        $this->conn->close();
        return $result;
    }

    public function RegistrarPedido($consecutivo, $idProyecto, $fecha, $idUsuario, $TipoPedido) {
        $conexion = new Conexion(); //En este metodo no cerramoas la conexion a la base de datos Tiene que quedar asi
        $conn = $conexion->CrearConexion();
        $consecutivo = LimpiarCadenaCaracter($conn, $consecutivo);
        $fecha = LimpiarCadenaCaracter($conn, $fecha);
        $sql = "Insert into tbl_boletaspedido(Consecutivo,ID_Proyecto,Fecha,ID_Usuario ,TipoPedido) values (?,?,?,?,?);";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iisii", $consecutivo, $idProyecto, $fecha, $idUsuario, $TipoPedido);
        $ok = $stmt->execute();
        $stmt->close();
        $this->conn->close();
        return $ok ? 1 : 0;
    }

    public function RegistrarPedidoMaterial($val) {
        $values = substr($val, 0, -1);
        $values .= ";";
        $conexion = new Conexion();
        $conn = $conexion->CrearConexion();
        $sql = "Insert into tbl_prestamomateriales (ID_Material,Cantidad,Pendiente,Devolver,NBoleta) values " . $values . "";
        $result = $conn->query($sql);
        $conn->close();
        return $result;
    }

    public function MostrarPedidos($TipoPedido, $ID_Proyecto) {
        $TipoPedido = LimpiarCadenaCaracter($this->conn, $TipoPedido);
        $ID_Proyecto = LimpiarCadenaCaracter($this->conn, $ID_Proyecto);
        $sql = "SELECT Consecutivo,TipoPedido,Nombre,Fecha FROM tbl_boletaspedido tb, tbl_usuario tu WHERE tb.ID_Proyecto = ? AND TipoPedido= ? and tb.ID_Usuario = tu.ID_Usuario  ORDER BY CONSECUTIVO DESC;";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $ID_Proyecto, $TipoPedido);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $stmt->close();
        $this->conn->close();
        return $resultado;
    }

    public function VerPeidido($NumPedido, $TipoPedido) {
        if ($TipoPedido == 1) {
            $sql = "Select P.ID_Material,P.Cantidad,M.Nombre,M.Devolucion FROM tbl_prestamomateriales P , tbl_materiales M WHERE P.NBoleta = ? AND P.ID_Material = M.Codigo ;";
        } else {
            $sql = "SELECT tph.Codigo,tt.Descripcion, th.Marca from tbl_prestamoherramientas tph ,tbl_tipoherramienta tt, tbl_herramientaelectrica th where 
                        tph.NBoleta=? and tph.Codigo = th.Codigo and th.ID_Tipo = tt.ID_Tipo;";
        }
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $NumPedido);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $stmt->close();
        $this->conn->close();
        return $resultado;
    }

    public function RegistrarPedidoHerramientas($val) {
        $conexion = new Conexion();
        $conn = $conexion->CrearConexion();
        $values = substr($val, 0, -1);
        $values .= ";";
        $sql = "Insert into tbl_prestamoherramientas(NBoleta,ID_Proyecto,Codigo,Estado,FechaSalida,ID_Tipo)"
                . "values " . $values . "";
        $result = $conn->query($sql);
        $conn->close();
        return $result;
    }


    public function RegistrarPedidoMaquinaria($val) {
        $conexion = new Conexion();
        $conn = $conexion->CrearConexion();
        $values = substr($val, 0, -1);
        $values .= ";";
        $sql = "Insert into tbl_prestamoherramientas(NBoleta,ID_Proyecto,Codigo,Estado,FechaSalida,ID_Tipo)"
                . "values " . $values . "";
        $result = $conn->query($sql);
        $conn->close();
        return $result;
    }

    public function EliminarBoletaPedido($NBoleta, $Tipo) {

        $sql = "Call EliminarPedido(?,?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $NBoleta, $Tipo);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $stmt->close();
        $this->conn->close();
        return $resultado;
    }

    public function FitrosMaterialesProyecto($sql) {
        $result = $this->conn->query($sql);
        $this->conn->close();
        return $result;
    }

    public function FiltrosHerramientasProyecto($sql) {
        $result = $this->conn->query($sql);
        $this->conn->close();
        return $result;
    }

    public function FiltrosMaquinariaProyecto($sql) {
        $result = $this->conn->query($sql);
        $this->conn->close();
        return $result;
    }

    public function AnularBoletaMaterial($NBoleta) {
      /*  $sql = "CALL SP_ANULARBOLETAMATERIAL($NBoleta)";
        $result = $this->conn->query($sql);
        $this->conn->close();
        return $result;*/

        

        $sql = "SELECT tpm.ID_Material,tpm.Cantidad from  tbl_prestamomateriales tpm WHERE tpm.NBoleta= $NBoleta";
        $result1 = $this->conn->query($sql);

        if (mysqli_num_rows($result1) > 0) {
            while ($fila = mysqli_fetch_array($result1, MYSQLI_ASSOC)) {
                
                $cantidad = $fila['Cantidad'];
                $codMater = $fila['ID_Material'];

                $sql2 = "UPDATE tbl_materiales SET Cantidad = (Cantidad + $cantidad) WHERE codigo = '" . $codMater  ."'";

                $result2 = $this->conn->query($sql2);



            }


            $sql3 = "DELETE FROM tbl_prestamomateriales WHERE NBoleta =  $NBoleta";
            $sql4 = "DELETE from tbl_boletaspedido WHERE Consecutivo = $NBoleta";

            $result3 = $this->conn->query($sql3);
            $result4 = $this->conn->query($sql4);

        }else{
            $this->conn->close();
            return false;
        }

        $this->conn->close();
        return true;




    }

    public function ColaPedidos($ID_Proyecto) {
        $sql = "SELECT n.NBoleta as Consecutivo,u.Nombre,pp.Fecha FROM tbl_notificaciones n,tbl_usuario u, tbl_pedidoproveeduria pp WHERE n.NBoleta = pp.Consecutivo and pp.ID_Usuario = u.ID_Usuario and n.ID_Proyecto = $ID_Proyecto";
        $result = $this->conn->query($sql);
        $this->conn->close();
        return $result;
    }

    public function MostrarPedidoProeveeduria($Boleta) {
        $Boleta = LimpiarCadenaCaracter($this->conn, $Boleta);
        $sql = "SELECT tt.ID_Tipo,tp.Cantidad,tt.Descripcion FROM tbl_pedidoherramientasproveeduria tp, tbl_tipoherramienta tt WHERE tp.Consecutivo = $Boleta and tp.CodigoHerramienta = tt.ID_Tipo ";
        $sql2 = "SELECT tp.CodigoMaterial,tp.Cantidad,m.Nombre FROM tbl_pedidomaterialesproveeduria tp,tbl_materiales m WHERE tp.Consecutivo = $Boleta and tp.CodigoMaterial = m.Codigo ";
        $sql3 = "SELECT Comentarios from tbl_pedidoproveeduria WHERE Consecutivo = $Boleta ";
        $result1 = $this->conn->query($sql);
        $result2 = $this->conn->query($sql2);
        $result3 = $this->conn->query($sql3);
        $concatenar = "<table class = 'tablaPedidos' id ='tblPeidoProveeduriaSolicita'>
                            <thead>
                                <tr>
                                    <th>Codigo</th>                                     
                                    <th>Cantidad</th>
                                    <th>Material</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>";
        if (mysqli_num_rows($result1) > 0) {
            while ($fila = mysqli_fetch_array($result1, MYSQLI_ASSOC)) {
                $concatenar .= "<tr>
                                <td>" . $fila['ID_Tipo'] . "</td>
                                <td>" . $fila['Cantidad'] . "</td>
                                <td>" . $fila['Descripcion'] . "</td>
                               <td></td>
                            </tr>";
            }
        }
        if (mysqli_num_rows($result2) > 0) {
            while ($fila = mysqli_fetch_array($result2, MYSQLI_ASSOC)) {
                $concatenar .= "<tr>
                                <td>" . $fila['CodigoMaterial'] . "</td>
                                <td>" . $fila['Cantidad'] . "</td>
                                <td>" . $fila['Nombre'] . "</td>
                                 <td><img id='imgPasarDepedidos' title='Agregar al pedido' onclick='AgregarDeProveduPedidoBodega(this)' width='15px' src='../resources/imagenes/add_icon.png' /></td>
                            </tr>";
            }
        }
        $concatenar .= "</tbody>
                      </table>";
        if (mysqli_num_rows($result3) > 0) {
            $fila = mysqli_fetch_array($result3, MYSQLI_ASSOC);
            $concatenar .= " <section id='comentarios'><p>" . $fila['Comentarios'] . "</p></section>";
        }
        $this->conn->close();
        return $concatenar;
    }

    public function EliminarNotificacion($Bolea) {
        $Bolea = LimpiarCadenaCaracter($this->conn, $Bolea);
        $sql = "DELETE FROM tbl_notificaciones WHERE NBoleta = $Bolea";
        $result = $this->conn->query($sql);
        $this->conn->close();
        return $result;
    }

    public function DevolucionMaterial(\DevolucionMateriales $devolucion) {
        $sql = "Insert into tbl_devolucionmateriales(Codigo,Cantidad,fecha ,NBoleta ,ID_Proyecto ) values('$devolucion->Codigo',$devolucion->Cantidad,'$devolucion->fecha',$devolucion->NBoleta,$devolucion->ID_Proyecto)";


        $sql2 = "Update tbl_materiales set cantidad = cantidad + $devolucion->Cantidad where  Codigo = '$devolucion->Codigo'";
        $result = $this->conn->query($sql);

        $this->conn->query($sql2);
        $this->conn->close();
        return $result;
    }

    public function ListarDevolucionesPorMaterial($ID_Material, $ID_Proyecto) {
        $ID_Material = LimpiarCadenaCaracter($this->conn, $ID_Material);
        $ID_Proyecto = LimpiarCadenaCaracter($this->conn, $ID_Proyecto);
        $sql = "SELECT Cantidad,fecha,NBoleta FROM tbl_devolucionmateriales WHERE ID_Proyecto =  ? and Codigo = ? ";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("is", $ID_Proyecto, $ID_Material);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $stmt->close();
        $this->conn->close();
        return $resultado;
    }

    public function ObtenerTotalMaterialPrestadoProyecto($ID_Material, $ID_Proyecto) {
        $conexion = new Conexion();
        $conn = $conexion->CrearConexion();
        $ID_Material = LimpiarCadenaCaracter($conn, $ID_Material);
        $ID_Proyecto = LimpiarCadenaCaracter($conn, $ID_Proyecto);
        $sql = "SELECT sum(Cantidad) as CantidadIngreso from tbl_boletaspedido tb , tbl_prestamomateriales tpm WHERE tb.ID_Proyecto = ? and tpm.ID_Material = ? AND tb.Consecutivo = tpm.NBoleta ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $ID_Proyecto, $ID_Material);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $stmt->close();
        $conn->close();
        return $resultado;
    }

    public function BuscarBoletaPedido($numBoleta, $idProyecto) {
        $numBoleta = LimpiarCadenaCaracter($this->conn, $numBoleta);
        $idProyecto = LimpiarCadenaCaracter($this->conn, $idProyecto);
        $sql = "SELECT Consecutivo,TipoPedido,Nombre,Fecha FROM tbl_boletaspedido tb, tbl_usuario tu WHERE tb.ID_Usuario = tu.ID_Usuario and tb.Consecutivo = ? and tb.ID_Proyecto =  ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $numBoleta, $idProyecto);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $stmt->close();
        $this->conn->close();
        return $resultado;
    }

    public function AnularBoletaHerramientas($NBoleta) {
        $NBoleta = LimpiarCadenaCaracter($this->conn, $NBoleta);
        $sqlSelect = "SELECT Codigo FROM tbl_prestamoherramientas WHERE NBoleta = $NBoleta ";
        $result = $this->conn->query($sqlSelect);
        $where = "(";
        while ($fila = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $where .= "'" . $fila['Codigo'] . "',";
        }
        $values = substr($where, 0, -1);
        $values .= ")";
        $sqlUpdate = "UPDATE tbl_herramientaelectrica set Ubicacion = 1,Disposicion = 1 WHERE Codigo IN $values ";
        $this->conn->query($sqlUpdate);
        $sqlDelete1 = "Delete from tbl_prestamoherramientas where NBoleta = $NBoleta";
        $sqlDelete2 = "Delete from tbl_boletaspedido where Consecutivo = $NBoleta";

        $this->conn->query($sqlDelete1);
        $this->conn->query($sqlDelete2);
        $this->conn->close();
    }

    public function ObtenerNombreProyecto($ID_Proyecto) {
        $conexion = new Conexion();
        $conn = $conexion->CrearConexion();
        $sql = "SELECT Nombre FROM tbl_proyectos WHERE ID_Proyecto =  ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $ID_Proyecto);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $stmt->close();
        $conn->close();
        return $resultado;
    }

    public function NombreProyecto($id) {
        $sql = "SELECT nombre from tbl_proyectos where ID_Proyecto = $id";
        $result = $this->conn->query($sql);
        $this->conn->close();
        return $result;
    }

    public function FinalizarProyecto($ID_Proyecto) {
        $conexion = new Conexion();
        $conn = $conexion->CrearConexion();
        $sql = "Call spFinalizarProyecto($ID_Proyecto)";
        $result = $conn->query($sql);
        $conn->close();
        return $result;
    }

    public function ListarMaterialesPendientesProyecto($idProyecto) {
        $sql = "SELECT m.Codigo, m.Nombre,pm.Cantidad FROM tbl_boletaspedido bp, tbl_prestamomateriales pm,tbl_materiales m where bp.Consecutivo = pm.NBoleta and bp.ID_Proyecto = ? and pm.ID_Material = m.Codigo and m.Devolucion = 1 ORDER BY bp.Consecutivo DESC ";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $idProyecto);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $stmt->close();
        $this->conn->close();
        return $resultado;
    }

    public function BuscarProyectoID($ID) {
        $sql = "SELECT * from tbl_proyectos where ID_Proyecto = $ID";
        $result = $this->conn->query($sql);
        $this->conn->close();
        return $result;
    }

    public function HerramientaPorId($idProyecto, $idHerramienta) {

        $sql = "SELECT tp.Codigo,tt.Descripcion,tp.FechaSalida, th.Estado, tp.NBoleta FROM tbl_prestamoherramientas tp, tbl_herramientaelectrica th, tbl_tipoherramienta tt where 
            tp.ID_Proyecto = ? and tp.ID_Tipo = tt.ID_Tipo and tp.Codigo =  th.Codigo and tp.Codigo = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("is", $idProyecto, $idHerramienta);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $stmt->close();
        $this->conn->close();
        return $resultado;
    }

    public function ListarSoloHerramienta($idProyecto) {

        $sql = "SELECT tp.Codigo,tt.Descripcion,tp.FechaSalida, th.Estado, tp.NBoleta FROM tbl_prestamoherramientas tp, tbl_herramientaelectrica th, tbl_tipoherramienta tt where 
            tp.ID_Proyecto = ? and tp.ID_Tipo = tt.ID_Tipo and tp.Codigo =  th.Codigo";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $idProyecto);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $stmt->close();
        $this->conn->close();
        return $resultado;
    }

}
