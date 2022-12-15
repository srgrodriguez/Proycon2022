<?php
header("Content-Type: text/html;charset=utf-8");
function llamarFuncion($Destino, $NumBoleta, $FechaFinal, $ID_Usuario) {
    try{
        $conexion = new Conexion();
        $conn = $conexion->CrearConexion();
        $sql = "SELECT a.Codigo,a.Ubicacion,c.ID_Tipo as Tipo FROM tbl_herramientaelectrica a, tbl_trasladotemporal b, tbl_tipoherramienta c WHERE a.Codigo = b.Codigo and c.ID_Tipo = a.ID_Tipo;";
        $rs_traslado = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_array($rs_traslado)) {
            $Codigo = $row['Codigo'];
            $Ubicacion = $row['Ubicacion'];
            $ID_Tipo = $row['Tipo'];
            //$Tipo1 = $row['Tipo'];			
            //$sql3 = "Insert into tbl_prestamoherramientas(NBoleta,ID_Proyecto,Codigo,Estado,FechaSalida,ID_Tipo) values
            //('$NumBoleta','$Destino','$Codigo','1','$FechaFinal','$ID_Tipo')";
            //$conn->query($sql3);
            $existe = "Select * from tbl_prestamoherramientas where Codigo = '$Codigo'";
            $res = $conn->query($existe);
            $sql3 = "";
            if (mysqli_num_rows($res) > 0) {
                $sql3 = "UPDATE tbl_prestamoherramientas SET ID_Proyecto=$Destino, NBoleta =$NumBoleta 
            WHERE Codigo = '$Codigo';";

                $sql = "Insert into tbl_historialherramientas(Codigo,Ubicacion,Destino,NumBoleta,Fecha) 
            values('$Codigo','$Ubicacion','$Destino','$NumBoleta','$FechaFinal')";
                $conn->query($sql);
            } else {
                $sql3 = "Insert into tbl_prestamoherramientas(NBoleta,ID_Proyecto,Codigo,Estado,FechaSalida,ID_Tipo)"
                        . "Values($NumBoleta,$Destino,'$Codigo','1','$FechaFinal',$ID_Tipo)";
            }
            $resultado = $conn->query($sql3);

            $sql4 = "Insert into tbl_boletaspedido(Consecutivo,ID_Proyecto,TipoPedido,ID_Usuario,Fecha) values ('$NumBoleta','$Destino',2,'$ID_Usuario','$FechaFinal')";
            $conn->query($sql4);
            $sql5 = "Delete from tbl_trasladotemporal where idTrasladoT = '1'";
            $conn->query($sql5);

            if ($Destino == 1) {
                $sql2 = "UPDATE tbl_herramientaelectrica SET Ubicacion='$Destino', Disposicion = '1' WHERE Codigo = '$Codigo'";
            } else {
                $sql2 = "UPDATE tbl_herramientaelectrica SET Ubicacion='$Destino', Disposicion = 0 WHERE Codigo = '$Codigo'";
            }

            $conn->query($sql2);
        }

        $conn->close();

        } catch (Exception $ex) {
            echo  json_encode(Log::GuardarEvento($ex, "llamarFuncion"));
        }
    }

?>