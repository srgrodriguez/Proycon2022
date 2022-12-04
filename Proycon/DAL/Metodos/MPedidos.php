<?php


class MPedidos implements IPedidos {
    var $conn;
    public function __construct(){
       $conexion  = new Conexion();
       $this->conn = $conexion ->CrearConexion();
    }
    
    public function ContarHerramientaDisponible($consulta) {
       $sql ="SELECT tt.ID_Tipo,tt.Descripcion,COUNT(*) as Total from tbl_herramientaelectrica th,tbl_tipoherramienta tt where tt.Descripcion LIKE'%".$consulta."%' and th.Disposicion = 1 and th.ID_Tipo = tt.ID_Tipo  and tt.TipoEquipo = 'H' GROUP by tt.Descripcion,tt.ID_Tipo";
       $result =  $this->conn->query($sql);
       $this->conn->close();
       return $result;    
    }


    public function ContarMaquinariaDisponible($consulta) {
        $sql ="SELECT tt.ID_Tipo,tt.Descripcion,COUNT(*) as Total from tbl_herramientaelectrica th,tbl_tipoherramienta tt where tt.Descripcion LIKE'%".$consulta."%' and th.Disposicion = 1 and th.ID_Tipo = tt.ID_Tipo and tt.TipoEquipo = 'M' GROUP by tt.Descripcion,tt.ID_Tipo";
        $result =  $this->conn->query($sql);
        $this->conn->close();
        return $result;    
     }
    
    public function ObternerCosecutivoPedido() {
       $sql ="select Consecutivo from tbl_pedidoproveeduria order by Consecutivo desc limit 1;"; 
       $result =  $this-> conn->query($sql);
        $this-> conn->close();
        return $result; 
    }
        public function ListarProyectos() {
        $sql = "SELECT ID_Proyecto,Nombre FROM tbl_proyectos where Estado = 1 and ID_Proyecto != 1";
        $result =  $this-> conn->query($sql);
        $this-> conn->close();
        return $result;
    }

    public function ListarPedidosProyecto($ID_Proyecto) {
        $sql ="SELECT p.Consecutivo,p.Fecha,u.Nombre FROM tbl_pedidoproveeduria p, tbl_usuario u WHERE ID_Proyecto = $ID_Proyecto and p.ID_Usuario = u.ID_Usuario order by Consecutivo desc";
       // $sql ="SELECT *, FROM tbl_pedidoproveeduria WHERE ID_Proyecto = $ID_Proyecto order by Consecutivo desc;";
        $result =  $this-> conn->query($sql);
        $this-> conn->close();
        return $result;
    }

    public function GenerarPedido(HeaderPedido $headerPedido) {
        $sqlNotificacion ="INSERT INTO tbl_notificaciones(UsuarioBodega,ID_Proyecto,NBoleta) VALUES (1,$headerPedido->ID_Proyecto,$headerPedido->Consecutivo);";
        $sqlPedido ="INSERT INTO tbl_pedidoproveeduria(Consecutivo,ID_Proyecto,ID_Usuario,Fecha,Comentarios) VALUES(".$headerPedido->Consecutivo.",".$headerPedido->ID_Proyecto.",".$headerPedido->ID_Usuairo.",'".$headerPedido->Fecha."','".$headerPedido->Comentarios."')";
        $result =  $this-> conn->query($sqlPedido);
        $this-> conn->query($sqlNotificacion);
        $this-> conn->close(); 
        $pd = new MPedidos();
        return $result;
    }
   

    public function ContenidoPedido(CuerpoPedido $contenido) {
        if ($contenido->Tipo==1) {          
        $sql ="INSERT INTO tbl_pedidomaterialesproveeduria(Consecutivo,CodigoMaterial,Cantidad) VALUES(".$contenido->Consecutivo.",'".$contenido->Codigo."',".$contenido->Cantidad.")";
        $result =  $this-> conn->query($sql);
        $this-> conn->close();
        return $result; 
        }
        else{                                           
        $sql ="INSERT INTO tbl_pedidoherramientasproveeduria(Consecutivo,CodigoHerramienta,Cantidad) VALUES(".$contenido->Consecutivo.",'".$contenido->Codigo."',".$contenido->Cantidad.")";
        $result =  $this-> conn->query($sql);
        $this-> conn->close();
        return $result; 
        }    
        }

    public function BuscarCorreoElectronico($busqueda) {
        $sql ="SELECT ID_Usuario,Usuario,Adjuntarcorreo FROM tbl_usuario WHERE Usuario like'%".$busqueda."%';";
        $result =  $this-> conn->query($sql);
        $this-> conn->close();
        return $result;
    }

    public function AdjuntarCorreoSiempre($ID_Usario) {
        $sql ="Update tbl_usuario set Adjuntarcorreo = 1 where ID_Usuario = $ID_Usario";
        $result =  $this-> conn->query($sql);
        $this-> conn->close();
        return $result;  
    }

    public function ObtenerCorreosAdjuntadosSiempre() {
        $sql ="Select ID_Usuario,Usuario from tbl_usuario where Adjuntarcorreo = 1";
        $result =  $this-> conn->query($sql);
        $this-> conn->close();
        return $result;    
    }

    public function DesAdjuntarCorreo($ID_Usario) {
        $sql ="Update tbl_usuario set Adjuntarcorreo = 0 where ID_Usuario = $ID_Usario";
        $result =  $this-> conn->query($sql);
        $this-> conn->close();
        return $result;   
    }

    public function VerPedido($ID_Boleta) {
        $sql1 ="select b.Descripcion,a.Cantidad  from tbl_pedidoherramientasproveeduria a,tbl_tipoherramienta b where a.CodigoHerramienta = b.ID_Tipo and a.Consecutivo = $ID_Boleta;";
        $sql2 ="select a.CodigoMaterial,b.Nombre,a.Cantidad from tbl_pedidomaterialesproveeduria a,tbl_materiales b where a.CodigoMaterial = b.Codigo and a.Consecutivo = $ID_Boleta ";
        $sql3="SELECT Comentarios from tbl_pedidoproveeduria WHERE Consecutivo = $ID_Boleta ";
        $result =  $this-> conn->query($sql1);
        $concatenar = "<table class='tablasG'>"
                . "<thead><tr><th>Codigo</th><th>Tipo</th><th>Cantidad</th></tr></thead><tbody>";
        if (mysqli_num_rows($result)>0) {
            while ($fila = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
               $concatenar.="<tr><td></td><td>".$fila['Descripcion']."</td><td>".$fila['Cantidad']."</td></tr>" ;
            } 
        }
        $result =  $this-> conn->query($sql2);
        if (mysqli_num_rows($result)>0) {
             while ($fila = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
               $concatenar.="<tr><td>".$fila['CodigoMaterial']."</td><td>".$fila['Nombre']."</td><td>".$fila['Cantidad']."</td></tr>" ;
            }  
        }
        $concatenar.="</tbody></table>";
        $result =  $this-> conn->query($sql3);
        if (mysqli_num_rows($result)>0) {
           $fila = mysqli_fetch_array($result,MYSQLI_ASSOC);
          $concatenar.=" <section id='comentarios'><p>".$fila['Comentarios']."</p></section>";
        }
        
        $this-> conn->close();
        return $concatenar; 
    }

    public function AnularBoletaPedido($ID_Boleta) {
      //SE ESATAN HACIENDO 3 DELETE CON ESTA CONSULTA
      $sql1 ="Delete from tbl_pedidoherramientasproveeduria where Consecutivo =$ID_Boleta;";
      $sql2="Delete from tbl_pedidomaterialesproveeduria where Consecutivo = $ID_Boleta";
      $sql3="Delete from tbl_pedidoproveeduria where Consecutivo = $ID_Boleta;";
      $sql4="Delete from tbl_notificaciones where NBoleta = $ID_Boleta;";
     $this-> conn->query($sql1);
     $this-> conn->query($sql2);
     $this-> conn->query($sql3);
     $result=  $this-> conn->query($sql4);
     $this-> conn->close();
      return $result;           
    }
//;
    public function BuscarPedido($ID_Boleta) {
        $sql ="Select * from tbl_pedidoproveeduria where Consecutivo = $ID_Boleta";
        $result =  $this-> conn->query($sql);
        $this-> conn->close();
        return $result;  
    }

    public function VerificarProcesoDeBoleta($ID_Boleta) {
        $sql ="Select * from tbl_notificaciones where NBoleta = $ID_Boleta";
        $result =  $this-> conn->query($sql);
        return $result;  
    }

}

