 $conexion = new Conexion();
 $conn = $conexion->CrearConexion();
 
 $sql = "SELECT a.Codigo,a.Ubicacion FROM tbl_herramientaelectrica a, tbl_trasladotemporal b WHERE a.Codigo = b.Codigo;";
		
		$rs_traslado = mysqli_query($conn,$sql);
	    while ($row = mysqli_fetch_array($rs_traslado)) {
		$Codigo = $row['Codigo'];
		$Ubicacion = $row['Ubicacion'];
		
		$sql = "Insert into tbl_historialherramientas(Codigo,Ubicacion,Destino,NumBoleta,Fecha) 
		values('$Codigo','$Ubicacion','"
		        . $Traslado->Destino . "','"
                . $Traslado->NumBoleta . "','"
				. $Traslado->FechaFinal . "')";
		$resultado = $conn->query($sql);
		return $resultado;
		
		}
		
		
        $conn->close();