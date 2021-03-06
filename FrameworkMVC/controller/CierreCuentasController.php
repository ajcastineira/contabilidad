<?php

class CierreCuentasController extends ControladorBase{

	public function __construct() {
		parent::__construct();
	}

//maycol

	public function index(){
	
		session_start();
		$_id_usuarios= $_SESSION['id_usuarios'];
		$resultSet="";
		if (isset(  $_SESSION['usuario_usuarios']) )
		{
			$resultMenu=array(1=>'AÑO');
			
			
			$tipo_cierre = new TipoCierreModel();	
			
		    $columnas_enc = "tipo_cierre.nombre_tipo_cierre, tipo_cierre.id_tipo_cierre, entidades.id_entidades";
		    $tablas_enc ="public.tipo_cierre, 
						  public.entidades, 
						  public.usuarios";
		    $where_enc ="entidades.id_entidades = tipo_cierre.id_entidades AND
  						usuarios.id_entidades = entidades.id_entidades AND usuarios.id_usuarios='$_id_usuarios'";
		    $id_enc="tipo_cierre.nombre_tipo_cierre";
		    $resultTipCierre=$tipo_cierre->getCondiciones($columnas_enc ,$tablas_enc ,$where_enc, $id_enc);
		   
		    
		  
				
		    $permisos_rol = new PermisosRolesModel();
			$nombre_controladores = "CierreCuentas";
			$id_rol= $_SESSION['id_rol'];
			$resultPer = $permisos_rol->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
				
			if (!empty($resultPer))
			{
				
				
				if (isset ($_POST["criterio"])  && isset ($_POST["contenido"])  )
				{
					
						
					$cuentas_cierre_mes= new CuentasCierreMesModel();
					$entidades = new EntidadesModel();
					$usuarios= new UsuariosModel();
					$resultEntidades = $usuarios->getBy("id_usuarios='$_id_usuarios'");
					$_id_entidades=$resultEntidades[0]->id_entidades;
					
					
					
					
					$columnas="entidades.nombre_entidades,
						  usuarios.nombre_usuarios,
						  cierre_mes.id_cierre_mes,
						  cuentas_cierre_mes.id_cuentas_cierre_mes,
						  cuentas_cierre_mes.fecha_ene_cuentas_cierre_mes,
						  cuentas_cierre_mes.cerrado_ene_cuentas_cierre_mes,
						  cuentas_cierre_mes.fecha_feb_cuentas_cierre_mes,
						  cuentas_cierre_mes.cerrado_feb_cuentas_cierre_mes,
						  cuentas_cierre_mes.fecha_mar_cuentas_cierre_mes,
						  cuentas_cierre_mes.cerrado_mar_cuentas_cierre_mes,
						  cuentas_cierre_mes.fecha_abr_cuentas_cierre_mes,
						  cuentas_cierre_mes.cerrado_abr_cuentas_cierre_mes,
						  cuentas_cierre_mes.fecha_may_cuentas_cierre_mes,
						  cuentas_cierre_mes.cerrado_may_cuentas_cierre_mes,
						  cuentas_cierre_mes.fecha_jun_cuentas_cierre_mes,
						  cuentas_cierre_mes.cerrado_jun_cuentas_cierre_mes,
						  cuentas_cierre_mes.fecha_jul_cuentas_cierre_mes,
						  cuentas_cierre_mes.cerrado_jul_cuentas_cierre_mes,
						  cuentas_cierre_mes.fecha_ago_cuentas_cierre_mes,
						  cuentas_cierre_mes.cerrado_ago_cuentas_cierre_mes,
						  cuentas_cierre_mes.fecha_sep_cuentas_cierre_mes,
						  cuentas_cierre_mes.cerrado_sep_cuentas_cierre_mes,
						  cuentas_cierre_mes.fecha_oct_cuentas_cierre_mes,
						  cuentas_cierre_mes.cerrado_oct_cuentas_cierre_mes,
						  cuentas_cierre_mes.fecha_nov_cuentas_cierre_mes,
						  cuentas_cierre_mes.cerrado_nov_cuentas_cierre_mes,
						  cuentas_cierre_mes.fecha_dic_cuentas_cierre_mes,
						  cuentas_cierre_mes.cerrado_dic_cuentas_cierre_mes,
						  plan_cuentas.codigo_plan_cuentas, 
                          plan_cuentas.nombre_plan_cuentas,
						   cuentas_cierre_mes.year";
					
					$tablas=" public.cierre_mes,
						  public.cuentas_cierre_mes,
						  public.entidades,
						  public.usuarios,
							public.plan_cuentas";
					
					$where=" cierre_mes.id_usuario_creador = usuarios.id_usuarios AND
						     cuentas_cierre_mes.id_cierre_mes = cierre_mes.id_cierre_mes AND
						     entidades.id_entidades = cierre_mes.id_entidades AND
						     entidades.id_entidades = usuarios.id_entidades AND
  							 plan_cuentas.id_plan_cuentas = cuentas_cierre_mes.id_plan_cuentas AND usuarios.id_usuarios='$_id_usuarios' AND entidades.id_entidades='$_id_entidades'";
					$id=" plan_cuentas.codigo_plan_cuentas";
					
					
					
					
				
					$criterio = $_POST["criterio"];
					$contenido = $_POST["contenido"];
				
						
					//$resultSet=$usuarios->getCondiciones($columnas ,$tablas ,$where, $id);
				
					if ($contenido !="")
					{
							
						
						$where_1 = "";
						
						
							
						switch ($criterio) {
							
							case 1:
								//Ruc Cliente/Proveedor
								$where_1 = "AND cuentas_cierre_mes.year ='$contenido'";
								break;
							
						
						}
							
							
							
						$where_to  = $where . $where_1;
							
							
						$resul = $where_to;
				
						
						
						$resultSet = $cuentas_cierre_mes->getCondiciones($columnas ,$tablas ,$where_to, $id);
							
							
							
					}
				}
				
				
				
				
					
					$this->view("CierreCuentas",array(
							
							"resultTipCierre"=>$resultTipCierre, "resultEdit"=>"", "resultSet"=>$resultSet, "resultMenu"=>$resultMenu
					));
			
			
			}else{
				
				$this->view("Error",array(
						"resultado"=>"No tiene Permisos a Cierre de Cuentas"
				
					
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
	 
	
   public function InsertaCierreCuentas(){
   
   	session_start();
   
   	$resultado = null;
   	$permisos_rol=new PermisosRolesModel();
    $cierre_mes = new CierreMesModel();
    $cuentas_cierre_mes = new CuentasCierreMesModel();
    $entidades = new EntidadesModel(); 
    $plan_cuentas = new PlanCuentasModel();
    $mayor = new MayorModel();
    
   	$nombre_controladores = "CierreCuentas";
   	$id_rol= $_SESSION['id_rol'];
   	$resultPer = $cuentas_cierre_mes->getPermisosEditar("   nombre_controladores = '$nombre_controladores' AND id_rol = '$id_rol' " );
   
   	if (!empty($resultPer))
   	{
   		$resultFechaCierre = "";
   		$_fecha_cierre = "";
   		$_id_usuarios = $_SESSION['id_usuarios'];
   		$_id_entidades =$_POST['id_entidades'];
   		$_id_tipo_cierre =$_POST['id_tipo_cierre'];
   		$_fecha_cierre_mes=$_POST['fecha_cierre_mes'];
   		
   		$date = new DateTime($_fecha_cierre_mes);
   		$anio = $date->format("Y");
   		$mes = $date->format("m");
   		
   		
   		$resultAnioCierre = $cierre_mes->getBy(" EXTRACT(YEAR FROM fecha_cierre_mes) = '$anio' AND id_entidades='$_id_entidades'");
   		 

   		if (isset ($_POST["id_tipo_cierre"]))
   		{
   			
   			$columnas_mayor="mayor.id_plan_cuentas,
						  		SUM(mayor.debe_mayor) as suma_debe,
						  		SUM(mayor.haber_mayor) as suma_haber";
   				
   			$tablas_mayor="public.mayor,
						  		public.plan_cuentas,
						 		public.entidades,
						  		public.usuarios";
   				
   			$where_mayor="plan_cuentas.id_plan_cuentas = mayor.id_plan_cuentas AND
   			entidades.id_entidades = plan_cuentas.id_entidades AND
   			usuarios.id_entidades = entidades.id_entidades AND usuarios.id_usuarios='$_id_usuarios'
   			AND entidades.id_entidades='$_id_entidades'  AND TO_CHAR(fecha_mayor,'MM') = '$mes' AND
   			EXTRACT(YEAR FROM fecha_mayor) = '$anio'";
   				
   			$grupo = "mayor.id_plan_cuentas";
   			$id="mayor.id_plan_cuentas";
   				
   			$resultMovimientos = $mayor->getCondiciones_GrupBy_OrderBy($columnas_mayor ,$tablas_mayor ,$where_mayor, $grupo, $id);
   				
   			
   			if(!empty($resultMovimientos)){
   				
   				
   				if(!empty($resultAnioCierre))
   				{
   				
   					$id_cierre_mes = $resultAnioCierre[0]->id_cierre_mes;
   					
   					$debe=(float)0;
   					$haber=(float)0;
   					$saldo=(float)0;
   					
   					//set_time_limit(60);
   					$funcion_cuentas_cierre_mes = "";
   					$columna = "";
   					$mes = (int)$mes;
   					
   					switch ($mes)
   					{
   						case 1:
   							$funcion_cuentas_cierre_mes="ins_cuentas_cierre_mes_ene";
   							$columna="cerrado_ene_cuentas_cierre_mes";
   							$mes_letras="ENERO";
   							break;
   						case 2:
   							$funcion_cuentas_cierre_mes="ins_cuentas_cierre_mes_feb";
   							$columna="cerrado_feb_cuentas_cierre_mes";
   							$mes_letras="FEBRERO";
   							break;
   						case 3:
   							$funcion_cuentas_cierre_mes="ins_cuentas_cierre_mes_mar";
   							$columna="cerrado_mar_cuentas_cierre_mes";
   							$mes_letras="MARZO";
   							break;
   						case 4:
   							$funcion_cuentas_cierre_mes="ins_cuentas_cierre_mes_abr";
   							$columna="cerrado_abr_cuentas_cierre_mes";
   							$mes_letras="ABRIL";
   							break;
   						case 5:
   							$funcion_cuentas_cierre_mes="ins_cuentas_cierre_mes_may";
   							$columna="cerrado_may_cuentas_cierre_mes";
   							$mes_letras="MAYO";
   							break;
   						case 6:
   							$funcion_cuentas_cierre_mes="ins_cuentas_cierre_mes_jun";
   							$columna="cerrado_jun_cuentas_cierre_mes";
   							$mes_letras="JUNIO";
   					
   							break;
   						case 7:
   							$funcion_cuentas_cierre_mes="ins_cuentas_cierre_mes_jul";
   							$columna="cerrado_jul_cuentas_cierre_mes";
   							$mes_letras="JULIO";
   							break;
   						case 8:
   							$funcion_cuentas_cierre_mes="ins_cuentas_cierre_mes_ago";
   							$columna="cerrado_ago_cuentas_cierre_mes";
   							$mes_letras="AGOSTO";
   							break;
   						case 9:
   							$funcion_cuentas_cierre_mes="ins_cuentas_cierre_mes_sep";
   							$columna="cerrado_sep_cuentas_cierre_mes";
   							$mes_letras="SEPTIEMBRE";
   							break;
   						case 10:
   							$funcion_cuentas_cierre_mes="ins_cuentas_cierre_mes_oct";
   							$columna="cerrado_oct_cuentas_cierre_mes";
   							$mes_letras="OCTUBRE";
   							break;
   						case 11:
   							$funcion_cuentas_cierre_mes="ins_cuentas_cierre_mes_nov";
   							$columna="cerrado_nov_cuentas_cierre_mes";
   							$mes_letras="NOVIEMBRE";
   							break;
   						case 12:
   							$funcion_cuentas_cierre_mes="ins_cuentas_cierre_mes_dic";
   							$columna="cerrado_dic_cuentas_cierre_mes";
   							$mes_letras="DICIEMBRE";
   							break;
   						default:
   							$funcion_cuentas_cierre_mes="ins_cuentas_cierre_mes_ene";
   							$columna="cerrado_ene_cuentas_cierre_mes";
   							$mes_letras="ENERO";
   							break;
   					}
   					
   					
   					
   					$resultCuentas_cierre = $cuentas_cierre_mes->getBy("id_cierre_mes='$id_cierre_mes'");
   					//parametros
   					
   					if(!empty($resultCuentas_cierre))
   					{
   						$cta_cerrada = $resultCuentas_cierre[0]->$columna;
   							
   						if($cta_cerrada=='t')
   						{
   							//echo $cta_cerrada;
   					
   					
   							$this->view("Error",array(
   									"resultado"=>"NO PUDIMOS PROCESAR SU REQUERIMIENTO YA EXISTE UN CIERRE EN EL MES DE ".$mes_letras.
   									" INTENTELO NUEVAMENTE USUANDO UN MES DIFERENTE."
   					
   							));
   					
   							die();
   						}else{
   					
   					
   							$columnas_mayor="mayor.id_plan_cuentas,
						  		SUM(mayor.debe_mayor) as suma_debe,
						  		SUM(mayor.haber_mayor) as suma_haber";
   					
   							$tablas_mayor="public.mayor,
						  		public.plan_cuentas,
						 		public.entidades,
						  		public.usuarios";
   					
   							$where_mayor="plan_cuentas.id_plan_cuentas = mayor.id_plan_cuentas AND
   							entidades.id_entidades = plan_cuentas.id_entidades AND
   							usuarios.id_entidades = entidades.id_entidades AND usuarios.id_usuarios='$_id_usuarios'
   							AND entidades.id_entidades='$_id_entidades'  AND TO_CHAR(fecha_mayor,'MM') = '$mes' AND
   							EXTRACT(YEAR FROM fecha_mayor) = '$anio'";
   					
   							$grupo = "mayor.id_plan_cuentas";
   							$id="mayor.id_plan_cuentas";
   					
   							$resultCuentasMayor = $mayor->getCondiciones_GrupBy_OrderBy($columnas_mayor ,$tablas_mayor ,$where_mayor, $grupo, $id);
   					
   					
   							if($mes=='1'){
   									
   								foreach($resultCuentasMayor as $res)
   								{
   									try
   									{
   											
   										$_id_plan_cuentas_mayor = $res->id_plan_cuentas;
   										$resultSaldo = $mayor->getBy("id_plan_cuentas = '$_id_plan_cuentas_mayor'  ORDER BY id_mayor DESC LIMIT 1");
   										$_saldo_mayor=$resultSaldo[0]->saldo_mayor;
   										$_suma_debe_mayor = (float)$res->suma_debe;
   										$_suma_haber_mayor = (float)$res->suma_haber;
   											
   										$colval = "debe_ene='$_suma_debe_mayor' , haber_ene='$_suma_haber_mayor',saldo_final_ene='$_saldo_mayor', fecha_ene_cuentas_cierre_mes='$_fecha_cierre_mes', cerrado_ene_cuentas_cierre_mes='TRUE'";
   										$tabla = "cuentas_cierre_mes";
   										$where = "id_plan_cuentas = '$_id_plan_cuentas_mayor' AND id_cierre_mes='$id_cierre_mes'";
   										$resultado=$cuentas_cierre_mes->UpdateBy($colval, $tabla, $where);
   											
   											
   									} catch (Exception $e)
   									{
   										echo $e;
   									}
   					
   								}
   									
   								$colval = "fecha_ene_cuentas_cierre_mes='$_fecha_cierre_mes', cerrado_ene_cuentas_cierre_mes='TRUE'";
   								$tabla = "cuentas_cierre_mes";
   								$where = "id_cierre_mes='$id_cierre_mes'";
   								$resultado=$cuentas_cierre_mes->UpdateBy($colval, $tabla, $where);
   									
   									
   									
   							}elseif($mes=='2'){
   									
   								foreach($resultCuentasMayor as $res)
   								{
   									try
   									{
   					
   										$_id_plan_cuentas_mayor = $res->id_plan_cuentas;
   										$resultSaldo = $mayor->getBy("id_plan_cuentas = '$_id_plan_cuentas_mayor'  ORDER BY id_mayor DESC LIMIT 1");
   										$_saldo_mayor=$resultSaldo[0]->saldo_mayor;
   										$_suma_debe_mayor = (float)$res->suma_debe;
   										$_suma_haber_mayor = (float)$res->suma_haber;
   					
   										$colval = "debe_feb='$_suma_debe_mayor' , haber_feb='$_suma_haber_mayor',saldo_final_feb='$_saldo_mayor', fecha_feb_cuentas_cierre_mes='$_fecha_cierre_mes', cerrado_feb_cuentas_cierre_mes='TRUE'";
   										$tabla = "cuentas_cierre_mes";
   										$where = "id_plan_cuentas = '$_id_plan_cuentas_mayor' AND id_cierre_mes='$id_cierre_mes'";
   										$resultado=$cuentas_cierre_mes->UpdateBy($colval, $tabla, $where);
   					
   					
   									} catch (Exception $e)
   									{
   										echo $e;
   									}
   					
   								}
   									
   								$colval = "fecha_feb_cuentas_cierre_mes='$_fecha_cierre_mes', cerrado_feb_cuentas_cierre_mes='TRUE'";
   								$tabla = "cuentas_cierre_mes";
   								$where = "id_cierre_mes='$id_cierre_mes'";
   								$resultado=$cuentas_cierre_mes->UpdateBy($colval, $tabla, $where);
   									
   							}
   							elseif($mes=='3'){
   					
   								foreach($resultCuentasMayor as $res)
   								{
   									try
   									{
   					
   										$_id_plan_cuentas_mayor = $res->id_plan_cuentas;
   										$resultSaldo = $mayor->getBy("id_plan_cuentas = '$_id_plan_cuentas_mayor'  ORDER BY id_mayor DESC LIMIT 1");
   										$_saldo_mayor=$resultSaldo[0]->saldo_mayor;
   										$_suma_debe_mayor = (float)$res->suma_debe;
   										$_suma_haber_mayor = (float)$res->suma_haber;
   					
   										$colval = "debe_mar='$_suma_debe_mayor' , haber_mar='$_suma_haber_mayor',saldo_final_mar='$_saldo_mayor', fecha_mar_cuentas_cierre_mes='$_fecha_cierre_mes', cerrado_mar_cuentas_cierre_mes='TRUE'";
   										$tabla = "cuentas_cierre_mes";
   										$where = "id_plan_cuentas = '$_id_plan_cuentas_mayor' AND id_cierre_mes='$id_cierre_mes'";
   										$resultado=$cuentas_cierre_mes->UpdateBy($colval, $tabla, $where);
   					
   					
   									} catch (Exception $e)
   									{
   										echo $e;
   									}
   					
   								}
   									
   								$colval = "fecha_mar_cuentas_cierre_mes='$_fecha_cierre_mes', cerrado_mar_cuentas_cierre_mes='TRUE'";
   								$tabla = "cuentas_cierre_mes";
   								$where = "id_cierre_mes='$id_cierre_mes'";
   								$resultado=$cuentas_cierre_mes->UpdateBy($colval, $tabla, $where);
   					
   							}
   							elseif($mes=='4'){
   					
   								foreach($resultCuentasMayor as $res)
   								{
   									try
   									{
   					
   										$_id_plan_cuentas_mayor = $res->id_plan_cuentas;
   										$resultSaldo = $mayor->getBy("id_plan_cuentas = '$_id_plan_cuentas_mayor'  ORDER BY id_mayor DESC LIMIT 1");
   										$_saldo_mayor=$resultSaldo[0]->saldo_mayor;
   										$_suma_debe_mayor = (float)$res->suma_debe;
   										$_suma_haber_mayor = (float)$res->suma_haber;
   					
   										$colval = "debe_abr='$_suma_debe_mayor' , haber_abr='$_suma_haber_mayor',saldo_final_abr='$_saldo_mayor', fecha_abr_cuentas_cierre_mes='$_fecha_cierre_mes', cerrado_abr_cuentas_cierre_mes='TRUE'";
   										$tabla = "cuentas_cierre_mes";
   										$where = "id_plan_cuentas = '$_id_plan_cuentas_mayor' AND id_cierre_mes='$id_cierre_mes'";
   										$resultado=$cuentas_cierre_mes->UpdateBy($colval, $tabla, $where);
   					
   					
   									} catch (Exception $e)
   									{
   										echo $e;
   									}
   					
   								}
   									
   								$colval = "fecha_abr_cuentas_cierre_mes='$_fecha_cierre_mes', cerrado_abr_cuentas_cierre_mes='TRUE'";
   								$tabla = "cuentas_cierre_mes";
   								$where = "id_cierre_mes='$id_cierre_mes'";
   								$resultado=$cuentas_cierre_mes->UpdateBy($colval, $tabla, $where);
   					
   							}
   							elseif($mes=='5'){
   					
   								foreach($resultCuentasMayor as $res)
   								{
   									try
   									{
   					
   										$_id_plan_cuentas_mayor = $res->id_plan_cuentas;
   										$resultSaldo = $mayor->getBy("id_plan_cuentas = '$_id_plan_cuentas_mayor'  ORDER BY id_mayor DESC LIMIT 1");
   										$_saldo_mayor=$resultSaldo[0]->saldo_mayor;
   										$_suma_debe_mayor = (float)$res->suma_debe;
   										$_suma_haber_mayor = (float)$res->suma_haber;
   					
   										$colval = "debe_may='$_suma_debe_mayor' , haber_may='$_suma_haber_mayor',saldo_final_may='$_saldo_mayor', fecha_may_cuentas_cierre_mes='$_fecha_cierre_mes', cerrado_may_cuentas_cierre_mes='TRUE'";
   										$tabla = "cuentas_cierre_mes";
   										$where = "id_plan_cuentas = '$_id_plan_cuentas_mayor' AND id_cierre_mes='$id_cierre_mes'";
   										$resultado=$cuentas_cierre_mes->UpdateBy($colval, $tabla, $where);
   					
   					
   									} catch (Exception $e)
   									{
   										echo $e;
   									}
   					
   								}
   									
   								$colval = "fecha_may_cuentas_cierre_mes='$_fecha_cierre_mes', cerrado_may_cuentas_cierre_mes='TRUE'";
   								$tabla = "cuentas_cierre_mes";
   								$where = "id_cierre_mes='$id_cierre_mes'";
   								$resultado=$cuentas_cierre_mes->UpdateBy($colval, $tabla, $where);
   					
   							}
   							elseif($mes=='6'){
   					
   								foreach($resultCuentasMayor as $res)
   								{
   									try
   									{
   					
   										$_id_plan_cuentas_mayor = $res->id_plan_cuentas;
   										$resultSaldo = $mayor->getBy("id_plan_cuentas = '$_id_plan_cuentas_mayor'  ORDER BY id_mayor DESC LIMIT 1");
   										$_saldo_mayor=$resultSaldo[0]->saldo_mayor;
   										$_suma_debe_mayor = (float)$res->suma_debe;
   										$_suma_haber_mayor = (float)$res->suma_haber;
   					
   										$colval = "debe_jun='$_suma_debe_mayor' , haber_jun='$_suma_haber_mayor',saldo_final_jun='$_saldo_mayor', fecha_jun_cuentas_cierre_mes='$_fecha_cierre_mes', cerrado_jun_cuentas_cierre_mes='TRUE'";
   										$tabla = "cuentas_cierre_mes";
   										$where = "id_plan_cuentas = '$_id_plan_cuentas_mayor' AND id_cierre_mes='$id_cierre_mes'";
   										$resultado=$cuentas_cierre_mes->UpdateBy($colval, $tabla, $where);
   					
   					
   									} catch (Exception $e)
   									{
   										echo $e;
   									}
   					
   								}
   									
   								$colval = "fecha_jun_cuentas_cierre_mes='$_fecha_cierre_mes', cerrado_jun_cuentas_cierre_mes='TRUE'";
   								$tabla = "cuentas_cierre_mes";
   								$where = "id_cierre_mes='$id_cierre_mes'";
   								$resultado=$cuentas_cierre_mes->UpdateBy($colval, $tabla, $where);
   					
   							}
   							elseif($mes=='7'){
   					
   					
   								foreach($resultCuentasMayor as $res)
   								{
   									try
   									{
   					
   										$_id_plan_cuentas_mayor = $res->id_plan_cuentas;
   										$resultSaldo = $mayor->getBy("id_plan_cuentas = '$_id_plan_cuentas_mayor'  ORDER BY id_mayor DESC LIMIT 1");
   										$_saldo_mayor=$resultSaldo[0]->saldo_mayor;
   										$_suma_debe_mayor = (float)$res->suma_debe;
   										$_suma_haber_mayor = (float)$res->suma_haber;
   					
   										$colval = "debe_jul='$_suma_debe_mayor' , haber_jul='$_suma_haber_mayor',saldo_final_jul='$_saldo_mayor', fecha_jul_cuentas_cierre_mes='$_fecha_cierre_mes', cerrado_jul_cuentas_cierre_mes='TRUE'";
   										$tabla = "cuentas_cierre_mes";
   										$where = "id_plan_cuentas = '$_id_plan_cuentas_mayor' AND id_cierre_mes='$id_cierre_mes'";
   										$resultado=$cuentas_cierre_mes->UpdateBy($colval, $tabla, $where);
   					
   					
   									} catch (Exception $e)
   									{
   										echo $e;
   									}
   					
   								}
   									
   								$colval = "fecha_jul_cuentas_cierre_mes='$_fecha_cierre_mes', cerrado_jul_cuentas_cierre_mes='TRUE'";
   								$tabla = "cuentas_cierre_mes";
   								$where = "id_cierre_mes='$id_cierre_mes'";
   								$resultado=$cuentas_cierre_mes->UpdateBy($colval, $tabla, $where);
   							}
   							elseif($mes=='8'){
   					
   								foreach($resultCuentasMayor as $res)
   								{
   									try
   									{
   					
   										$_id_plan_cuentas_mayor = $res->id_plan_cuentas;
   										$resultSaldo = $mayor->getBy("id_plan_cuentas = '$_id_plan_cuentas_mayor'  ORDER BY id_mayor DESC LIMIT 1");
   										$_saldo_mayor=$resultSaldo[0]->saldo_mayor;
   										$_suma_debe_mayor = (float)$res->suma_debe;
   										$_suma_haber_mayor = (float)$res->suma_haber;
   					
   										$colval = "debe_ago='$_suma_debe_mayor' , haber_ago='$_suma_haber_mayor',saldo_final_ago='$_saldo_mayor', fecha_ago_cuentas_cierre_mes='$_fecha_cierre_mes', cerrado_ago_cuentas_cierre_mes='TRUE'";
   										$tabla = "cuentas_cierre_mes";
   										$where = "id_plan_cuentas = '$_id_plan_cuentas_mayor' AND id_cierre_mes='$id_cierre_mes'";
   										$resultado=$cuentas_cierre_mes->UpdateBy($colval, $tabla, $where);
   					
   					
   									} catch (Exception $e)
   									{
   										echo $e;
   									}
   					
   								}
   									
   								$colval = "fecha_ago_cuentas_cierre_mes='$_fecha_cierre_mes', cerrado_ago_cuentas_cierre_mes='TRUE'";
   								$tabla = "cuentas_cierre_mes";
   								$where = "id_cierre_mes='$id_cierre_mes'";
   								$resultado=$cuentas_cierre_mes->UpdateBy($colval, $tabla, $where);
   					
   							}
   							elseif($mes=='9'){
   					
   								foreach($resultCuentasMayor as $res)
   								{
   									try
   									{
   					
   										$_id_plan_cuentas_mayor = $res->id_plan_cuentas;
   										$resultSaldo = $mayor->getBy("id_plan_cuentas = '$_id_plan_cuentas_mayor'  ORDER BY id_mayor DESC LIMIT 1");
   										$_saldo_mayor=$resultSaldo[0]->saldo_mayor;
   										$_suma_debe_mayor = (float)$res->suma_debe;
   										$_suma_haber_mayor = (float)$res->suma_haber;
   					
   										$colval = "debe_sep='$_suma_debe_mayor' , haber_sep='$_suma_haber_mayor',saldo_final_sep='$_saldo_mayor', fecha_sep_cuentas_cierre_mes='$_fecha_cierre_mes', cerrado_sep_cuentas_cierre_mes='TRUE'";
   										$tabla = "cuentas_cierre_mes";
   										$where = "id_plan_cuentas = '$_id_plan_cuentas_mayor' AND id_cierre_mes='$id_cierre_mes'";
   										$resultado=$cuentas_cierre_mes->UpdateBy($colval, $tabla, $where);
   					
   					
   									} catch (Exception $e)
   									{
   										echo $e;
   									}
   					
   								}
   									
   								$colval = "fecha_sep_cuentas_cierre_mes='$_fecha_cierre_mes', cerrado_sep_cuentas_cierre_mes='TRUE'";
   								$tabla = "cuentas_cierre_mes";
   								$where = "id_cierre_mes='$id_cierre_mes'";
   								$resultado=$cuentas_cierre_mes->UpdateBy($colval, $tabla, $where);
   					
   							}
   							elseif($mes=='10'){
   					
   								foreach($resultCuentasMayor as $res)
   								{
   									try
   									{
   					
   										$_id_plan_cuentas_mayor = $res->id_plan_cuentas;
   										$resultSaldo = $mayor->getBy("id_plan_cuentas = '$_id_plan_cuentas_mayor'  ORDER BY id_mayor DESC LIMIT 1");
   										$_saldo_mayor=$resultSaldo[0]->saldo_mayor;
   										$_suma_debe_mayor = (float)$res->suma_debe;
   										$_suma_haber_mayor = (float)$res->suma_haber;
   					
   										$colval = "debe_oct='$_suma_debe_mayor' , haber_oct='$_suma_haber_mayor',saldo_final_oct='$_saldo_mayor', fecha_oct_cuentas_cierre_mes='$_fecha_cierre_mes', cerrado_oct_cuentas_cierre_mes='TRUE'";
   										$tabla = "cuentas_cierre_mes";
   										$where = "id_plan_cuentas = '$_id_plan_cuentas_mayor' AND id_cierre_mes='$id_cierre_mes'";
   										$resultado=$cuentas_cierre_mes->UpdateBy($colval, $tabla, $where);
   					
   					
   									} catch (Exception $e)
   									{
   										echo $e;
   									}
   					
   								}
   									
   									
   								$colval = "fecha_oct_cuentas_cierre_mes='$_fecha_cierre_mes', cerrado_oct_cuentas_cierre_mes='TRUE'";
   								$tabla = "cuentas_cierre_mes";
   								$where = "id_cierre_mes='$id_cierre_mes'";
   								$resultado=$cuentas_cierre_mes->UpdateBy($colval, $tabla, $where);
   					
   							}elseif($mes=='11'){
   									
   								foreach($resultCuentasMayor as $res)
   								{
   									try
   									{
   					
   										$_id_plan_cuentas_mayor = $res->id_plan_cuentas;
   										$resultSaldo = $mayor->getBy("id_plan_cuentas = '$_id_plan_cuentas_mayor'  ORDER BY id_mayor DESC LIMIT 1");
   										$_saldo_mayor=$resultSaldo[0]->saldo_mayor;
   										$_suma_debe_mayor = (float)$res->suma_debe;
   										$_suma_haber_mayor = (float)$res->suma_haber;
   					
   										$colval = "debe_nov='$_suma_debe_mayor' , haber_nov='$_suma_haber_mayor',saldo_final_nov='$_saldo_mayor', fecha_nov_cuentas_cierre_mes='$_fecha_cierre_mes', cerrado_nov_cuentas_cierre_mes='TRUE'";
   										$tabla = "cuentas_cierre_mes";
   										$where = "id_plan_cuentas = '$_id_plan_cuentas_mayor' AND id_cierre_mes='$id_cierre_mes'";
   										$resultado=$cuentas_cierre_mes->UpdateBy($colval, $tabla, $where);
   					
   					
   									} catch (Exception $e)
   									{
   										echo $e;
   									}
   					
   								}
   									
   								$colval = "fecha_nov_cuentas_cierre_mes='$_fecha_cierre_mes', cerrado_nov_cuentas_cierre_mes='TRUE'";
   								$tabla = "cuentas_cierre_mes";
   								$where = "id_cierre_mes='$id_cierre_mes'";
   								$resultado=$cuentas_cierre_mes->UpdateBy($colval, $tabla, $where);
   									
   							}else{
   									
   								foreach($resultCuentasMayor as $res)
   								{
   									try
   									{
   					
   										$_id_plan_cuentas_mayor = $res->id_plan_cuentas;
   										$resultSaldo = $mayor->getBy("id_plan_cuentas = '$_id_plan_cuentas_mayor'  ORDER BY id_mayor DESC LIMIT 1");
   										$_saldo_mayor=$resultSaldo[0]->saldo_mayor;
   										$_suma_debe_mayor = (float)$res->suma_debe;
   										$_suma_haber_mayor = (float)$res->suma_haber;
   					
   										$colval = "debe_dic='$_suma_debe_mayor' , haber_dic='$_suma_haber_mayor',saldo_final_dic='$_saldo_mayor', fecha_dic_cuentas_cierre_mes='$_fecha_cierre_mes', cerrado_dic_cuentas_cierre_mes='TRUE'";
   										$tabla = "cuentas_cierre_mes";
   										$where = "id_plan_cuentas = '$_id_plan_cuentas_mayor' AND id_cierre_mes='$id_cierre_mes'";
   										$resultado=$cuentas_cierre_mes->UpdateBy($colval, $tabla, $where);
   					
   					
   									} catch (Exception $e)
   									{
   										echo $e;
   									}
   					
   								}
   									
   									
   								$colval = "fecha_dic_cuentas_cierre_mes='$_fecha_cierre_mes', cerrado_dic_cuentas_cierre_mes='TRUE'";
   								$tabla = "cuentas_cierre_mes";
   								$where = "id_cierre_mes='$id_cierre_mes'";
   								$resultado=$cuentas_cierre_mes->UpdateBy($colval, $tabla, $where);
   									
   							}
   					
   					
   					
   					
   					
   							try {
   					
   								$result="";
   								$result = $cuentas_cierre_mes->CierrePlanCuentas($_id_entidades, $anio);
   							} catch (Exception $e)
   							{
   								echo "Erro al Cuadrar Balances: " + $e;
   							}
   						}
   					
   					
   					
   					
   					
   					
   				}
   				else
   				{
   					
   					
   					
   				}
   			}else 
   				
   			{
   				



   				$columnas="plan_cuentas.id_plan_cuentas,
					  plan_cuentas.id_entidades,
					  plan_cuentas.codigo_plan_cuentas,
					  plan_cuentas.nombre_plan_cuentas";
   				
   				$tablas=" public.plan_cuentas,
					  public.entidades,
					  public.usuarios";
   				$where="entidades.id_entidades = plan_cuentas.id_entidades AND
   				usuarios.id_entidades = entidades.id_entidades AND usuarios.id_usuarios='$_id_usuarios' AND entidades.id_entidades='$_id_entidades'";
   				$id=" plan_cuentas.codigo_plan_cuentas";
   				
   				$resultCuentas = $plan_cuentas->getCondiciones($columnas ,$tablas ,$where, $id);
   				
   				
   				$debe=(float)0;
   				$haber=(float)0;
   				$saldo=(float)0;
   				
   				
   				try
   				{
   				
   					$funcion = "ins_cierre_mes";
   					$parametros = "'$_id_entidades', '$_id_usuarios', '$_fecha_cierre_mes', '$_id_tipo_cierre'";
   					$cierre_mes->setFuncion($funcion);
   					$cierre_mes->setParametros($parametros);
   					$resultado=$cierre_mes->Insert();
   				
   					$resultCierre = $cierre_mes->getBy("id_entidades ='$_id_entidades' AND id_usuario_creador='$_id_usuarios' AND fecha_cierre_mes='$_fecha_cierre_mes'");
   					$_id_cierre_mes=$resultCierre[0]->id_cierre_mes;
   				
   					//set_time_limit(60);
   				
   					$funcion_cuentas_cierre_mes = "";
   					$mes = (int)$mes;
   				
   					switch ($mes)
   					{
   						case 1:
   							$funcion_cuentas_cierre_mes="ins_cuentas_cierre_mes_ene";
   							break;
   						case 2:
   							$funcion_cuentas_cierre_mes="ins_cuentas_cierre_mes_feb";
   							break;
   						case 3:
   							$funcion_cuentas_cierre_mes="ins_cuentas_cierre_mes_mar";
   							break;
   						case 4:
   							$funcion_cuentas_cierre_mes="ins_cuentas_cierre_mes_abr";
   							break;
   						case 5:
   							$funcion_cuentas_cierre_mes="ins_cuentas_cierre_mes_may";
   							break;
   						case 6:
   							$funcion_cuentas_cierre_mes="ins_cuentas_cierre_mes_jun";
   							break;
   						case 7:
   							$funcion_cuentas_cierre_mes="ins_cuentas_cierre_mes_jul";
   							break;
   						case 8:
   							$funcion_cuentas_cierre_mes="ins_cuentas_cierre_mes_ago";
   							break;
   						case 9:
   							$funcion_cuentas_cierre_mes="ins_cuentas_cierre_mes_sep";
   							break;
   						case 10:
   							$funcion_cuentas_cierre_mes="ins_cuentas_cierre_mes_oct";
   							break;
   						case 11:
   							$funcion_cuentas_cierre_mes="ins_cuentas_cierre_mes_nov";
   							break;
   						case 12:
   							$funcion_cuentas_cierre_mes="ins_cuentas_cierre_mes_dic";
   							break;
   						default:
   							$funcion_cuentas_cierre_mes="ins_cuentas_cierre_mes_ene";
   							break;
   					}
   				
   					foreach($resultCuentas as $res)
   					{
   						try
   						{
   							$_id_plan_cuentas = $res->id_plan_cuentas;
   							//parametros
   							//_id_cierre_mes integer, _id_plan_cuentas integer, _debe_dic numeric, _haber_dic numeric, _saldo_final_dic numeric, _year character varying,
   							//_fecha_dic_cuentas_cierre_mes date, _cerrado_dic_cuentas_cierre_mes boolean
   							$parametros = "'$_id_cierre_mes','$_id_plan_cuentas', '$debe', '$haber', '$saldo', '$anio','$_fecha_cierre_mes','TRUE'";
   							$cuentas_cierre_mes->setFuncion($funcion_cuentas_cierre_mes);
   							$cuentas_cierre_mes->setParametros($parametros);
   							$resultado=$cuentas_cierre_mes->Insert();
   				
   						} catch (Exception $e)
   						{
   							$this->view("Error",array(
   									"resultado"=>"Eror al Insertar Cierre de Cuentas ->".$e
   							));
   							exit();
   						}
   				
   					}
   				
   					$columnas_mayor="mayor.id_plan_cuentas,
						  SUM(mayor.debe_mayor) as suma_debe,
						  SUM(mayor.haber_mayor) as suma_haber";
   				
   					$tablas_mayor="public.mayor,
						  public.plan_cuentas,
						  public.entidades,
						  public.usuarios";
   				
   					$where_mayor="plan_cuentas.id_plan_cuentas = mayor.id_plan_cuentas AND
   					entidades.id_entidades = plan_cuentas.id_entidades AND
   					usuarios.id_entidades = entidades.id_entidades AND usuarios.id_usuarios='$_id_usuarios'
   					AND entidades.id_entidades='$_id_entidades'  AND TO_CHAR(fecha_mayor,'MM') = '$mes' AND
   					EXTRACT(YEAR FROM fecha_mayor) = '$anio'";
   				
   					$grupo = "mayor.id_plan_cuentas";
   					$id="mayor.id_plan_cuentas";
   				
   					$resultCuentasMayor = $mayor->getCondiciones_GrupBy_OrderBy($columnas_mayor ,$tablas_mayor ,$where_mayor, $grupo, $id);
   				
   				
   					if($mes=="1"){
   				
   						foreach($resultCuentasMayor as $res)
   						{
   							try
   							{
   				
   				
   								$_id_plan_cuentas_mayor = $res->id_plan_cuentas;
   								$resultSaldo = $mayor->getBy("id_plan_cuentas = '$_id_plan_cuentas_mayor'  ORDER BY id_mayor DESC LIMIT 1");
   								$_saldo_mayor=$resultSaldo[0]->saldo_mayor;
   								$_suma_debe_mayor = (float)$res->suma_debe;
   								$_suma_haber_mayor = (float)$res->suma_haber;
   				
   								$colval = "debe_ene='$_suma_debe_mayor' , haber_ene='$_suma_haber_mayor',saldo_final_ene='$_saldo_mayor'";
   								$tabla = "cuentas_cierre_mes";
   								$where = "id_plan_cuentas = '$_id_plan_cuentas_mayor' AND id_cierre_mes='$_id_cierre_mes'";
   								$resultado=$cuentas_cierre_mes->UpdateBy($colval, $tabla, $where);
   				
   				
   							} catch (Exception $e)
   							{
   								$this->view("Error",array(
   										"resultado"=>"Eror al Insertar Cierre de Cuentas ->".$e
   								));
   								exit();
   							}
   				
   						}
   				
   					}elseif($mes=="2"){
   				
   						foreach($resultCuentasMayor as $res)
   						{
   							try
   							{
   				
   				
   								$_id_plan_cuentas_mayor = $res->id_plan_cuentas;
   								$resultSaldo = $mayor->getBy("id_plan_cuentas = '$_id_plan_cuentas_mayor'  ORDER BY id_mayor DESC LIMIT 1");
   								$_saldo_mayor=$resultSaldo[0]->saldo_mayor;
   								$_suma_debe_mayor = (float)$res->suma_debe;
   								$_suma_haber_mayor = (float)$res->suma_haber;
   				
   								$colval = "debe_feb='$_suma_debe_mayor' , haber_feb='$_suma_haber_mayor',saldo_final_feb='$_saldo_mayor'";
   								$tabla = "cuentas_cierre_mes";
   								$where = "id_plan_cuentas = '$_id_plan_cuentas_mayor' AND id_cierre_mes='$_id_cierre_mes'";
   								$resultado=$cuentas_cierre_mes->UpdateBy($colval, $tabla, $where);
   				
   				
   							} catch (Exception $e)
   							{
   								$this->view("Error",array(
   										"resultado"=>"Eror al Insertar Cierre de Cuentas ->".$e
   								));
   								exit();
   							}
   				
   						}
   				
   					}elseif($mes=="3"){
   				
   				
   						foreach($resultCuentasMayor as $res)
   						{
   							try
   							{
   				
   				
   								$_id_plan_cuentas_mayor = $res->id_plan_cuentas;
   								$resultSaldo = $mayor->getBy("id_plan_cuentas = '$_id_plan_cuentas_mayor'  ORDER BY id_mayor DESC LIMIT 1");
   								$_saldo_mayor=$resultSaldo[0]->saldo_mayor;
   								$_suma_debe_mayor = (float)$res->suma_debe;
   								$_suma_haber_mayor = (float)$res->suma_haber;
   				
   								$colval = "debe_mar='$_suma_debe_mayor' , haber_mar='$_suma_haber_mayor',saldo_final_mar='$_saldo_mayor'";
   								$tabla = "cuentas_cierre_mes";
   								$where = "id_plan_cuentas = '$_id_plan_cuentas_mayor' AND id_cierre_mes='$_id_cierre_mes'";
   								$resultado=$cuentas_cierre_mes->UpdateBy($colval, $tabla, $where);
   				
   				
   							} catch (Exception $e)
   							{
   								$this->view("Error",array(
   										"resultado"=>"Eror al Insertar Cierre de Cuentas ->".$e
   								));
   								exit();
   							}
   				
   						}
   				
   					}elseif($mes=="4"){
   				
   						foreach($resultCuentasMayor as $res)
   						{
   							try
   							{
   				
   				
   								$_id_plan_cuentas_mayor = $res->id_plan_cuentas;
   								$resultSaldo = $mayor->getBy("id_plan_cuentas = '$_id_plan_cuentas_mayor'  ORDER BY id_mayor DESC LIMIT 1");
   								$_saldo_mayor=$resultSaldo[0]->saldo_mayor;
   								$_suma_debe_mayor = (float)$res->suma_debe;
   								$_suma_haber_mayor = (float)$res->suma_haber;
   				
   								$colval = "debe_abr='$_suma_debe_mayor' , haber_abr='$_suma_haber_mayor',saldo_final_abr='$_saldo_mayor'";
   								$tabla = "cuentas_cierre_mes";
   								$where = "id_plan_cuentas = '$_id_plan_cuentas_mayor' AND id_cierre_mes='$_id_cierre_mes'";
   								$resultado=$cuentas_cierre_mes->UpdateBy($colval, $tabla, $where);
   				
   				
   							} catch (Exception $e)
   							{
   								$this->view("Error",array(
   										"resultado"=>"Eror al Insertar Cierre de Cuentas ->".$e
   								));
   								exit();
   							}
   				
   						}
   				
   				
   					}elseif ($mes=="5"){
   				
   						foreach($resultCuentasMayor as $res)
   						{
   							try
   							{
   				
   				
   								$_id_plan_cuentas_mayor = $res->id_plan_cuentas;
   								$resultSaldo = $mayor->getBy("id_plan_cuentas = '$_id_plan_cuentas_mayor'  ORDER BY id_mayor DESC LIMIT 1");
   								$_saldo_mayor=$resultSaldo[0]->saldo_mayor;
   								$_suma_debe_mayor = (float)$res->suma_debe;
   								$_suma_haber_mayor = (float)$res->suma_haber;
   				
   								$colval = "debe_may='$_suma_debe_mayor' , haber_may='$_suma_haber_mayor',saldo_final_may='$_saldo_mayor'";
   								$tabla = "cuentas_cierre_mes";
   								$where = "id_plan_cuentas = '$_id_plan_cuentas_mayor' AND id_cierre_mes='$_id_cierre_mes'";
   								$resultado=$cuentas_cierre_mes->UpdateBy($colval, $tabla, $where);
   				
   				
   							} catch (Exception $e)
   							{
   								$this->view("Error",array(
   										"resultado"=>"Eror al Insertar Cierre de Cuentas ->".$e
   								));
   								exit();
   							}
   				
   						}
   				
   					}elseif ($mes=="6"){
   				
   						foreach($resultCuentasMayor as $res)
   						{
   							try
   							{
   				
   				
   								$_id_plan_cuentas_mayor = $res->id_plan_cuentas;
   								$resultSaldo = $mayor->getBy("id_plan_cuentas = '$_id_plan_cuentas_mayor'  ORDER BY id_mayor DESC LIMIT 1");
   								$_saldo_mayor=$resultSaldo[0]->saldo_mayor;
   								$_suma_debe_mayor = (float)$res->suma_debe;
   								$_suma_haber_mayor = (float)$res->suma_haber;
   				
   								$colval = "debe_jun='$_suma_debe_mayor' , haber_jun='$_suma_haber_mayor',saldo_final_jun='$_saldo_mayor'";
   								$tabla = "cuentas_cierre_mes";
   								$where = "id_plan_cuentas = '$_id_plan_cuentas_mayor' AND id_cierre_mes='$_id_cierre_mes'";
   								$resultado=$cuentas_cierre_mes->UpdateBy($colval, $tabla, $where);
   				
   				
   							} catch (Exception $e)
   							{
   								$this->view("Error",array(
   										"resultado"=>"Eror al Insertar Cierre de Cuentas ->".$e
   								));
   								exit();
   							}
   				
   						}
   				
   					}elseif ($mes=="7"){
   				
   						foreach($resultCuentasMayor as $res)
   						{
   							try
   							{
   				
   				
   								$_id_plan_cuentas_mayor = $res->id_plan_cuentas;
   								$resultSaldo = $mayor->getBy("id_plan_cuentas = '$_id_plan_cuentas_mayor'  ORDER BY id_mayor DESC LIMIT 1");
   								$_saldo_mayor=$resultSaldo[0]->saldo_mayor;
   								$_suma_debe_mayor = (float)$res->suma_debe;
   								$_suma_haber_mayor = (float)$res->suma_haber;
   				
   								$colval = "debe_jul='$_suma_debe_mayor' , haber_jul='$_suma_haber_mayor',saldo_final_jul='$_saldo_mayor'";
   								$tabla = "cuentas_cierre_mes";
   								$where = "id_plan_cuentas = '$_id_plan_cuentas_mayor' AND id_cierre_mes='$_id_cierre_mes'";
   								$resultado=$cuentas_cierre_mes->UpdateBy($colval, $tabla, $where);
   				
   				
   							} catch (Exception $e)
   							{
   								$this->view("Error",array(
   										"resultado"=>"Eror al Insertar Cierre de Cuentas ->".$e
   								));
   								exit();
   							}
   				
   						}
   				
   					}elseif ($mes=="8"){
   				
   						foreach($resultCuentasMayor as $res)
   						{
   							try
   							{
   				
   				
   								$_id_plan_cuentas_mayor = $res->id_plan_cuentas;
   								$resultSaldo = $mayor->getBy("id_plan_cuentas = '$_id_plan_cuentas_mayor'  ORDER BY id_mayor DESC LIMIT 1");
   								$_saldo_mayor=$resultSaldo[0]->saldo_mayor;
   								$_suma_debe_mayor = (float)$res->suma_debe;
   								$_suma_haber_mayor = (float)$res->suma_haber;
   				
   								$colval = "debe_ago='$_suma_debe_mayor' , haber_ago='$_suma_haber_mayor',saldo_final_ago='$_saldo_mayor'";
   								$tabla = "cuentas_cierre_mes";
   								$where = "id_plan_cuentas = '$_id_plan_cuentas_mayor' AND id_cierre_mes='$_id_cierre_mes'";
   								$resultado=$cuentas_cierre_mes->UpdateBy($colval, $tabla, $where);
   				
   				
   							} catch (Exception $e)
   							{
   								$this->view("Error",array(
   										"resultado"=>"Eror al Insertar Cierre de Cuentas ->".$e
   								));
   								exit();
   							}
   				
   						}
   				
   				
   					}elseif ($mes=="9"){
   				
   						foreach($resultCuentasMayor as $res)
   						{
   							try
   							{
   				
   				
   								$_id_plan_cuentas_mayor = $res->id_plan_cuentas;
   								$resultSaldo = $mayor->getBy("id_plan_cuentas = '$_id_plan_cuentas_mayor'  ORDER BY id_mayor DESC LIMIT 1");
   								$_saldo_mayor=$resultSaldo[0]->saldo_mayor;
   								$_suma_debe_mayor = (float)$res->suma_debe;
   								$_suma_haber_mayor = (float)$res->suma_haber;
   				
   								$colval = "debe_sep='$_suma_debe_mayor' , haber_sep='$_suma_haber_mayor',saldo_final_sep='$_saldo_mayor'";
   								$tabla = "cuentas_cierre_mes";
   								$where = "id_plan_cuentas = '$_id_plan_cuentas_mayor' AND id_cierre_mes='$_id_cierre_mes'";
   								$resultado=$cuentas_cierre_mes->UpdateBy($colval, $tabla, $where);
   				
   				
   							} catch (Exception $e)
   							{
   								$this->view("Error",array(
   										"resultado"=>"Eror al Insertar Cierre de Cuentas ->".$e
   								));
   								exit();
   							}
   				
   						}
   				
   					}elseif ($mes=="10"){
   				
   						foreach($resultCuentasMayor as $res)
   						{
   							try
   							{
   				
   				
   								$_id_plan_cuentas_mayor = $res->id_plan_cuentas;
   								$resultSaldo = $mayor->getBy("id_plan_cuentas = '$_id_plan_cuentas_mayor'  ORDER BY id_mayor DESC LIMIT 1");
   								$_saldo_mayor=$resultSaldo[0]->saldo_mayor;
   								$_suma_debe_mayor = (float)$res->suma_debe;
   								$_suma_haber_mayor = (float)$res->suma_haber;
   				
   								$colval = "debe_oct='$_suma_debe_mayor' , haber_oct='$_suma_haber_mayor',saldo_final_oct='$_saldo_mayor'";
   								$tabla = "cuentas_cierre_mes";
   								$where = "id_plan_cuentas = '$_id_plan_cuentas_mayor' AND id_cierre_mes='$_id_cierre_mes'";
   								$resultado=$cuentas_cierre_mes->UpdateBy($colval, $tabla, $where);
   				
   				
   							} catch (Exception $e)
   							{
   								$this->view("Error",array(
   										"resultado"=>"Eror al Insertar Cierre de Cuentas ->".$e
   								));
   								exit();
   							}
   				
   						}
   				
   					}elseif ($mes=="11"){
   				
   						foreach($resultCuentasMayor as $res)
   						{
   							try
   							{
   				
   				
   								$_id_plan_cuentas_mayor = $res->id_plan_cuentas;
   								$resultSaldo = $mayor->getBy("id_plan_cuentas = '$_id_plan_cuentas_mayor'  ORDER BY id_mayor DESC LIMIT 1");
   								$_saldo_mayor=$resultSaldo[0]->saldo_mayor;
   								$_suma_debe_mayor = (float)$res->suma_debe;
   								$_suma_haber_mayor = (float)$res->suma_haber;
   				
   								$colval = "debe_nov='$_suma_debe_mayor' , haber_nov='$_suma_haber_mayor',saldo_final_nov='$_saldo_mayor'";
   								$tabla = "cuentas_cierre_mes";
   								$where = "id_plan_cuentas = '$_id_plan_cuentas_mayor' AND id_cierre_mes='$_id_cierre_mes'";
   								$resultado=$cuentas_cierre_mes->UpdateBy($colval, $tabla, $where);
   				
   				
   							} catch (Exception $e)
   							{
   								$this->view("Error",array(
   										"resultado"=>"Eror al Insertar Cierre de Cuentas ->".$e
   								));
   								exit();
   							}
   				
   						}
   				
   				
   					}else{
   				
   						foreach($resultCuentasMayor as $res)
   						{
   							try
   							{
   				
   				
   								$_id_plan_cuentas_mayor = $res->id_plan_cuentas;
   								$resultSaldo = $mayor->getBy("id_plan_cuentas = '$_id_plan_cuentas_mayor'  ORDER BY id_mayor DESC LIMIT 1");
   								$_saldo_mayor=$resultSaldo[0]->saldo_mayor;
   								$_suma_debe_mayor = (float)$res->suma_debe;
   								$_suma_haber_mayor = (float)$res->suma_haber;
   				
   								$colval = "debe_dic='$_suma_debe_mayor' , haber_dic='$_suma_haber_mayor',saldo_final_dic='$_saldo_mayor'";
   								$tabla = "cuentas_cierre_mes";
   								$where = "id_plan_cuentas = '$_id_plan_cuentas_mayor' AND id_cierre_mes='$_id_cierre_mes'";
   								$resultado=$cuentas_cierre_mes->UpdateBy($colval, $tabla, $where);
   				
   				
   							} catch (Exception $e)
   							{
   								$this->view("Error",array(
   										"resultado"=>"Eror al Insertar Cierre de Cuentas ->".$e
   								));
   								exit();
   							}
   				
   						}
   				
   					}
   				
   				
   				
   				}catch (Exception $e)
   				{
   						
   				}
   					
   					
   				try {
   						
   					$result="";
   					$result = $cuentas_cierre_mes->CierrePlanCuentas($_id_entidades, $anio);
   				} catch (Exception $e)
   				{
   					echo "Erro al Cuadrar Balances: " + $e;
   				}
   					
   					
   				
   			}
   				
   			
   			}else{
   				
   					$this->view("Error",array(
   							"resultado"=>"NO PUDIMOS PROCESAR SU REQUERIMIENTO NO EXISTEN MOVIMIENTOS EN EL MES SELECCIONADO, INTENTELO NUEVAMENTE USUANDO UN MES DIFERENTE."
   				
   					));
   				
   					die();
   				
   		  }
   			
   			
   			
   	    	}else {
   			
   			$this->view("Error",array(
   					"resultado"=>"No pudimos procesar su requerimiento"
  
   			));
   			
   		}
   	  		
   		
   		
   		$this->redirect("CierreCuentas","index");
   	}
   	else
   	{
   		$this->view("Error",array(
   				"resultado"=>"No tiene Permisos de Guardar Cierre de Cuentas"
   
   		));
   
   
   	}
   
   
   
   }
   
   
}
?>