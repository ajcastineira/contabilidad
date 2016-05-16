<?php

class RecaudacionController extends ControladorBase{

	public function __construct() {
		parent::__construct();
	}



	public function index(){
	
		//Creamos el objeto usuario
     	$recaudacion_cabeza = new RecaudacionCabezaModel(); 
		$recaudacion_institucion = new RecaudacionInstitucionModel();
		
		$columnas = "recaudacion_cabeza.id_recaudacion_cabeza, recaudacion_cabeza.id_recaudacion_institucion, recaudacion_institucion.nombre_recaudacion_institucion, recaudacion_cabeza.fecha_creacion_recaudacion_cabeza, recaudacion_cabeza.hora_creacion_recaudacion_cabeza,  recaudacion_cabeza.cantidad_registros_recaudacion_cabeza, recaudacion_cabeza.valor_total_dolares_recaudacion_cabeza,  recaudacion_cabeza.creado";
		$tablas   = "public.recaudacion_institucion, public.recaudacion_cabeza";
		$where    = "recaudacion_cabeza.id_recaudacion_institucion = recaudacion_institucion.id_recaudacion_institucion";
		$id = "fecha_creacion_recaudacion_cabeza , hora_creacion_recaudacion_cabeza";
	    $id_dos = "nombre_recaudacion_institucion";
     	
	    $resultSet=$recaudacion_cabeza->getCondiciones($columnas, $tablas, $where, $id);
		$resultInsRec = $recaudacion_institucion->getAll($id_dos);
		
		
		
		$resultEdit = "";	
		
		
		session_start();

	
		if (isset(  $_SESSION['usuario_usuarios']) )
		{


			$nombre_controladores = "Recaudacion";
			$id_rol= $_SESSION['id_rol'];
			$resultPer = $recaudacion_cabeza->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
			
			if (!empty($resultPer))
			{
				if (isset ($_POST["procesar"]) )
				{
					$recaudacion_cabeza = new RecaudacionCabezaModel();
					
					$_id_recaudacion_institucion = $_POST["recaudacion_institucion"];
					
					$directorio = $_SERVER['DOCUMENT_ROOT'].'/recaudacion/';
						
					$nombre = $_FILES['archivo']['name'];
					$tipo = $_FILES['archivo']['type'];
					$tamano = $_FILES['archivo']['size'];
					// temporal al directorio definitivo
					move_uploaded_file($_FILES['archivo']['tmp_name'],$directorio.$nombre);
					$file = fopen($directorio.$nombre, "r") or exit("Unable to open file!");
		
					$contador = 0;
					$contador_linea = 0;
					
					$encabezado_linea = "";
					$contenido_linea = "";
					$pie_linea = "";
					
					$lectura_linea = "";
					/*
					while(!feof($file))
					{
					    $contador_linea = $contador_linea + 1;
					}
					*/
					 
					while(!feof($file))
					{
						$contador = $contador + 1;
						$lectura_linea =  fgets($file) ;
						
						if ($contador == 1) ///INSERTO EL ENCABEZADO
						{
							$funcion = "ins_recaudacion_cabeza";
		
							//$_id_recaudacion_institucion; 
							$_tipo_linea_recaudacion_cabeza = substr($lectura_linea,0,3);
							$_tipo_nuc_recaudacion_cabeza   = substr($lectura_linea,3,1);
							$_numero_nuc_recaudacion_cabeza = substr($lectura_linea,4,14);
							//$my_date = date('m/d/y', strtotime($date));
							$_fecha_creacion_recaudacion_cabeza = substr($lectura_linea,18,4) .".". substr($lectura_linea,22,2) .".". substr($lectura_linea,24,2);
							$_hora_creacion_recaudacion_cabeza =  substr($lectura_linea,26,2) . ":". substr($lectura_linea,28,2) . ".". substr($lectura_linea,30,2);
							$_cantidad_registros_recaudacion_cabeza =  intval(substr($lectura_linea,32,6));
							$_valor_total_sucres_recaudacion_cabeza =  floatval(substr($lectura_linea,38,13). "." . substr($lectura_linea,51,2));
							$_valor_total_dolares_recaudacion_cabeza =  floatval(substr($lectura_linea,53,13) . ":".substr($lectura_linea,66,2));
							
							
							$parametros = " '$_id_recaudacion_institucion' ,'$_tipo_linea_recaudacion_cabeza' , '$_tipo_nuc_recaudacion_cabeza' , '$_numero_nuc_recaudacion_cabeza' , '$_fecha_creacion_recaudacion_cabeza, '$_hora_creacion_recaudacion_cabeza', '$_cantidad_registros_recaudacion_cabeza' , '$_valor_total_sucres_recaudacion_cabeza' , '$_valor_total_dolares_recaudacion_cabeza' ";
							$recaudacion_cabeza->setFuncion($funcion);
							$recaudacion_cabeza->setParametros($parametros);
							
							$this->view("Error",array(
									"resultado"=>$_fecha_creacion_recaudacion_cabeza. "---" . "$_hora_creacion_recaudacion_cabeza"
							
							));
							/*
							try {
									
								$resultado=$recaudacion_cabeza->Insert();
									
									
							} catch (Exception $e) {
									
								$this->view("Error",array(
										"resultado"=>$e
								));
									
									
							}
								*/	
												
						} 
						elseif ($contador == $contador_linea ) 
						{
							//$pie_linea = $line;
						} 
						else 
						{
							//$pie_linea = $line;
						}
					}
							
					fclose($file);
					
					
				}
					
					
				
				
				
				
				
				
				$this->view("Recaudacion",array(
						"resultSet"=>$resultSet, "resultEdit" =>$resultEdit, "resultInsRec" =>$resultInsRec
							
				));
			}
			else
			{
				$this->view("Error",array(
						"resultado"=>"No tiene Permisos de Acceso a Controladores"
				
				));
				
				exit();	
			}
				
		}
		else 
		{
				$this->view("ErrorSesion",array(
						"resultSet"=>""
			
				));
		
		}
	
	}
	
	
	public function InsertaEncabezadoLinea($lectura_linea, $_id_recaudacion_institucion)
	{
		
	}
	
	
	
	
	
	
		
	public function InsertaFirmasDigitales(){
			
		
				$this->redirect("FirmasDigitales", "index");
		
		
	}
	
	public function borrarId()
	{
				
	}
	
	
	
}
?>