<?php 
	header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
	header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
	header("Access-Control-Allow-Origin: *");
	require_once "../modelos/Ligapillaro.php";
	$ligapillaro=new Ligapillaro();
	
	switch ($_GET["op"]){

	//variable input guarda todo lo que trae de ionic	
            
	case 'validarUsuario':
		$obj = json_decode(file_get_contents('php://input'));
			$rspta=$ligapillaro->validarUsuario($obj->usuario,$obj->clave);
			while($reg=$rspta->fetch_object()){
			   $resp[]=$reg;
			}
			if(empty($resp)){
				$reeturn=array("status"=>"error",
								"mensaje"=>'Error');
					echo json_encode($reeturn);
			}else{
				$reeturn=array("status"=>'Ok',
								"usuarioArbi"=>$resp,
								"mensaje"=>"Datos correctos");
				echo json_encode($reeturn);
			}
	break;

	case 'ping':

		$return=array(
			'status'=>"Correcto",
			'message'=>"Existe Conexion"
		);

		echo json_encode($return);

	break;

	case 'perfilArbi':

		$obj = json_decode(file_get_contents('php://input'));

			$rspta=$ligapillaro->perfilArbi();

			while($reg=$rspta->fetch_object()){
			   $resp[]=$reg;
			}
			if(empty($resp)){
				$reeturn=array("status"=>"error",
								"mensaje"=>'Error');
					echo json_encode($reeturn);
			}else{
				$reeturn=array("status"=>'Ok',
								"pArbi"=>$resp,
								"mensaje"=>"Datos correctos");
				echo json_encode($reeturn);
			}

	break;

	case 'listaSerie':

		$obj = json_decode(file_get_contents('php://input'));

			$rspta=$ligapillaro->listaSerie();

			while($reg=$rspta->fetch_object()){
			   $resp[]=$reg;
			}
			if(empty($resp)){
				$reeturn=array("status"=>"error",
								"mensaje"=>'Error');
					echo json_encode($reeturn);
			}else{
				$reeturn=array("status"=>'Ok',
								"serieE"=>$resp,
								"mensaje"=>"Datos correctos");
				echo json_encode($reeturn);
			}

	break;

	case 'listaCategoria':

		$obj = json_decode(file_get_contents('php://input'));

			$rspta=$ligapillaro->listaCategoria($obj->idserie);

			while($reg=$rspta->fetch_object()){
			   $resp[]=$reg;
			}
			if(empty($resp)){
				$reeturn=array("status"=>"error",
								"mensaje"=>'Error');
					echo json_encode($reeturn);
			}else{
				$reeturn=array("status"=>'Ok',
								"cateE"=>$resp,
								"mensaje"=>"Datos correctos");
				echo json_encode($reeturn);
			}

	break; 

    case 'listaEquipos':
                $obj = json_decode(file_get_contents('php://input'));
                $rspta=$ligapillaro->listaEquipos($obj->idserie, $obj->idcategoria);
                while($reg=$rspta->fetch_object()){
                   $resp[]=$reg;
                }
                if(empty($resp)){
                    $reeturn=array("status"=>"error",
                    				"mensaje"=>'Error');
                    	echo json_encode($reeturn);
                }else{
                    $reeturn=array("status"=>'Ok',
									"equipos"=>$resp,
									"mensaje"=>"Datos correctos");
                    echo json_encode($reeturn);
                }
	break;

	case 'faltasEquipos':
			$obj = json_decode(file_get_contents('php://input'));

			$rspta=$ligapillaro->faltasEquipo($obj->idequipo);

			while($reg=$rspta->fetch_object()){
			   $resp[]=$reg;
			}
			if(empty($resp)){
				$reeturn=array("status"=>"error",
								"mensaje"=>'El equipo no tiene faltas');
					echo json_encode($reeturn);
			}else{
				$reeturn=array("status"=>'Ok',
								"faltasequipo"=>$resp,
								"mensaje"=>"Datos correctos");
				echo json_encode($reeturn);
			}
	break;
		
	case 'listaJugadores':
            
			$obj = json_decode(file_get_contents('php://input'));

			$rspta=$ligapillaro->listaJugadores($obj->idequipo);

			while($reg=$rspta->fetch_object()){
		
			   $resp[]=$reg;
  			
			}
			if(empty($resp)){
				$reeturn=array("status"=>"error",
								"mensaje"=>'Error');
					echo json_encode($reeturn);
			}else{
				$reeturn=array("status"=>'Ok',
								"jugadores"=>$resp,
								"mensaje"=>"Datos correctos");
				echo json_encode($reeturn);
			}
	break;

	case 'golesJugador':

		$obj = json_decode(file_get_contents('php://input'));
		

		$rspta=$ligapillaro->golesJugador($obj->idjugador);

		while($reg=$rspta->fetch_object()){
			$resp[]=$reg;
			
		}
		if (empty($resp)){
			$reeturn=array("status"=>"error",
							"mensaje"=>'Error');
				echo json_encode($reeturn);			
		}else{
			$reeturn=array("status"=>'Ok',
							"golJ"=>$resp,
							"mensaje"=>"Datos correctos");
				echo json_encode($reeturn);
		}

	break;

	case 'carnetJugador':

		$obj = json_decode(file_get_contents('php://input'));
	
		$rspta=$ligapillaro->carnetJugador($obj->idjugador);
		while($reg=$rspta->fetch_object()){
			$resp[]=$reg;	
		}
		if (empty($resp)){
			$reeturn=array("status"=>"error",
							"mensaje"=>'Error');
				echo json_encode($reeturn);			
		}else{
			$reeturn=array("status"=>'Ok',
							"carnetJ"=>$resp,
							"mensaje"=>"Datos correctos");
				echo json_encode($reeturn);
		}

	break;

	case 'carnets':

		$obj = json_decode(file_get_contents('php://input'));
	
		$rspta=$ligapillaro->carnets($obj->idequipo);
		while($reg=$rspta->fetch_object()){
			$texto=$reg->cedula.' '.$reg->nombres.' '.$reg->N_camiseta.' '.$reg->nombreequipo;

			$reg->qr = $ligapillaro->generarQR( $texto);
			$resp[]=$reg;	
		}
		if (empty($resp)){
			$reeturn=array("status"=>"error",
							"mensaje"=>'Error');
				echo json_encode($reeturn);			
		}else{
			$reeturn=array("status"=>'Ok',
							"carnetE"=>$resp,
							"mensaje"=>"Datos correctos");
				echo json_encode($reeturn);
		}

	break;


	case 'comboSerie':

		$obj = json_decode(file_get_contents('php://input'));

			$rspta=$ligapillaro->listaSerie();

			while($reg=$rspta->fetch_object()){
			   $resp[]=$reg;
			}
			if(empty($resp)){
				$reeturn=array("status"=>"error",
								"mensaje"=>'Error');
					echo json_encode($reeturn);
			}else{
				$reeturn=array("status"=>'Ok',
								"serieT"=>$resp,
								"mensaje"=>"Datos correctos");
				echo json_encode($reeturn);
			}

	break;

	case 'comboCategoria':

		$obj = json_decode(file_get_contents('php://input'));

			$rspta=$ligapillaro->listaCategoria($obj->idserie);

			while($reg=$rspta->fetch_object()){
			   $resp[]=$reg;
			}
			if(empty($resp)){
				$reeturn=array("status"=>"error",
								"mensaje"=>'Error');
					echo json_encode($reeturn);
			}else{
				$reeturn=array("status"=>'Ok',
								"cateT"=>$resp,
								"mensaje"=>"Datos correctos");
				echo json_encode($reeturn);
			}

	break; 

	case 'topGoleadores':

		$obj = json_decode(file_get_contents('php://input'));

			$rspta=$ligapillaro->topGoleadores($obj->idserie, $obj->idcategoria);

			while($reg=$rspta->fetch_object()){
			   $resp[]=$reg;
			}
			if(empty($resp)){
				$reeturn=array("status"=>"error",
								"mensaje"=>'Error');
					echo json_encode($reeturn);
			}else{
				$reeturn=array("status"=>'Ok',
								"Goleadores"=>$resp,
								"mensaje"=>"Datos correctos");
				echo json_encode($reeturn);
			}

	break;

	/*################CALENDARIO###############*/

	case 'listTemporadas':

		$obj = json_decode(file_get_contents('php://input'));

			$rspta=$ligapillaro->listTemporadas();

			while($reg=$rspta->fetch_object()){
			   $resp[]=$reg;
			}
			if(empty($resp)){
				$reeturn=array("status"=>"error",
								"mensaje"=>'Error');
					echo json_encode($reeturn);
			}else{
				$reeturn=array("status"=>'Ok',
								"temporadas"=>$resp,
								"mensaje"=>"Datos correctos");
				echo json_encode($reeturn);
			}

	break;

	case 'listSeries':

		$obj = json_decode(file_get_contents('php://input'));
			$rspta=$ligapillaro->listSeries($obj->temporada);
			while($reg=$rspta->fetch_object()){
			   $resp[]=$reg;
			}
			if(empty($resp)){
				$reeturn=array("status"=>"error",
								"mensaje"=>'Error');
					echo json_encode($reeturn);
			}else{
				$reeturn=array("status"=>'Ok',
								"series"=>$resp,
								"mensaje"=>"Datos correctos");
				echo json_encode($reeturn);
			}

	break;

	case 'listCategorias':

		$obj = json_decode(file_get_contents('php://input'));
			$rspta=$ligapillaro->listCategorias($obj->temporada,$obj->serie);
			while($reg=$rspta->fetch_object()){
			   $resp[]=$reg;
			}
			if(empty($resp)){
				$reeturn=array("status"=>"error",
								"mensaje"=>'Error');
					echo json_encode($reeturn);
			}else{
				$reeturn=array("status"=>'Ok',
								"categorias"=>$resp,
								"mensaje"=>"Datos correctos");
				echo json_encode($reeturn);
			}

	break;

	case 'listFecha':

		$obj = json_decode(file_get_contents('php://input'));
			$rspta=$ligapillaro->listCategorias($obj->temporada,$obj->serie);
			while($reg=$rspta->fetch_object()){
			   $resp[]=$reg;
			}
			if(empty($resp)){
				$reeturn=array("status"=>"error",
								"mensaje"=>'Error');
					echo json_encode($reeturn);
			}else{
				$reeturn=array("status"=>'Ok',
								"categorias"=>$resp,
								"mensaje"=>"Datos correctos");
				echo json_encode($reeturn);
			}

	break;

	case 'listCalendario':
		$obj = json_decode(file_get_contents('php://input'));
			$rspta=$ligapillaro->listCalendario($obj->temporada,$obj->serie,$obj->idcategoria);
			while($reg=$rspta->fetch_object()){
			   $resp[]=$reg;
			}
			if(empty($resp)){
				$reeturn=array("status"=>"error",
								"mensaje"=>'Error');
					echo json_encode($reeturn);
			}else{
				$reeturn=array("status"=>'Ok',
								"calendarios"=>$resp,
								"mensaje"=>"Datos correctos");
				echo json_encode($reeturn);
			}
	break;

	case 'golesEquipo':
		$obj = json_decode(file_get_contents('php://input'));
			$rspta=$ligapillaro->golesEquipo($obj->idcalendario,$obj->idequipo1,$obj->idequipo2);
			$reg=$rspta->fetch_object();
			
			if(empty($reg)){
				$reeturn=array("status"=>"error",
								"mensaje"=>'Error');
					echo json_encode($reeturn);
			}else{
				$reeturn=array("status"=>'Ok',
								"gEquipo"=>$reg,
								"mensaje"=>"Datos correctos");
				echo json_encode($reeturn);
			}
	break;

	case 'faltasEquipo1':
		$obj = json_decode(file_get_contents('php://input'));
			$rspta=$ligapillaro->faltasEquipo1($obj->idcalendario,$obj->idequipo);
			while($reg=$rspta->fetch_object()){
				$resp[]=$reg;
			 }
			 if(empty($resp)){
								$reeturn=array("status"=>"OK",
								"faltas"=>"SIN FALTAS",
								"mensaje"=>'No existen faltas en el quipo2');
					echo json_encode($reeturn);
			 }else{
				 $reeturn=array("status"=>'Ok',
								 "faltas"=>$resp,
								 "mensaje"=>"Datos correctos");
				 echo json_encode($reeturn);
			 }
	break;

	case 'faltasEquipo2':
		$obj = json_decode(file_get_contents('php://input'));
			$rspta=$ligapillaro->faltasEquipo2($obj->idcalendario,$obj->idequipo);
			while($reg=$rspta->fetch_object()){
				$resp[]=$reg;
			 }
			 if(empty($resp)){
				 $reeturn=array("status"=>"OK",
				 				"faltas"=>"SIN FALTAS",
								"mensaje"=>'No existen faltas en el quipo2');
					 echo json_encode($reeturn);
			 }else{
				 $reeturn=array("status"=>'Ok',
								 "faltas"=>$resp,
								 "mensaje"=>"Datos correctos");
				 echo json_encode($reeturn);
			 }
	break;

	case 'listarEquiposCaleE1':
		$obj = json_decode(file_get_contents('php://input'));
			$rspta=$ligapillaro->listarEquiposCaleE1($obj->idcalendario);
			while($reg=$rspta->fetch_object()){
			   $resp[]=$reg;
			}
			if(empty($resp)){
				$reeturn=array("status"=>"error",
								"mensaje"=>'Error');
					echo json_encode($reeturn);
			}else{
				$reeturn=array("status"=>'Ok',
								"lEquipos"=>$resp,
								"mensaje"=>"Datos correctos");
				echo json_encode($reeturn);
			}
	break;

	case 'listarEquiposCaleE2':
		$obj = json_decode(file_get_contents('php://input'));
			$rspta=$ligapillaro->listarEquiposCaleE2($obj->idcalendario);
			while($reg=$rspta->fetch_object()){
			   $resp[]=$reg;
			}
			if(empty($resp)){
				$reeturn=array("status"=>"error",
								"mensaje"=>'Error');
					echo json_encode($reeturn);
			}else{
				$reeturn=array("status"=>'Ok',
								"lEquipos"=>$resp,
								"mensaje"=>"Datos correctos");
				echo json_encode($reeturn);
			}
	break;
	case 'listarEquiposInf1':
		$obj = json_decode(file_get_contents('php://input'));
			$rspta=$ligapillaro->listarEquiposInf1($obj->idcalendario);
			while($reg=$rspta->fetch_object()){
			   $resp[]=$reg;
			}
			if(empty($resp)){
				$reeturn=array("status"=>"error",
								"mensaje"=>'Error');
					echo json_encode($reeturn);
			}else{
				$reeturn=array("status"=>'Ok',
								"lEquiposInf"=>$resp,
								"mensaje"=>"Datos correctos");
				echo json_encode($reeturn);
			}
	break;

	case 'listarEquiposInf2':
		$obj = json_decode(file_get_contents('php://input'));
			$rspta=$ligapillaro->listarEquiposInf2($obj->idcalendario);
			while($reg=$rspta->fetch_object()){
			   $resp[]=$reg;
			}
			if(empty($resp)){
				$reeturn=array("status"=>"error",
								"mensaje"=>'Error');
					echo json_encode($reeturn);
			}else{
				$reeturn=array("status"=>'Ok',
								"lEquiposInf"=>$resp,
								"mensaje"=>"Datos correctos");
				echo json_encode($reeturn);
			}
	break;

	case 'listarAlineacion':
		$obj = json_decode(file_get_contents('php://input'));
			$rspta=$ligapillaro->listarAlineacion($obj->idcalendario, $obj->idequipo);
			while($reg=$rspta->fetch_object()){
			   $resp[]=$reg;
			}
			if(empty($resp)){
				$reeturn=array("status"=>"error",
								"mensaje"=>'Error');
					echo json_encode($reeturn);
			}else{
				$reeturn=array("status"=>'Ok',
								"lAlineacion"=>$resp,
								"mensaje"=>"Datos correctos");
				echo json_encode($reeturn);
			}
	break;

	case 'listarAlineacionCombo':
		$obj = json_decode(file_get_contents('php://input'));
			$rspta=$ligapillaro->listarAlineacionCombo($obj->idcalendario, $obj->idequipo);
			
			while($reg=$rspta->fetch_object()){
			   $resp[]=$reg;
			}

			if(empty($resp)){
				$reeturn=array("status"=>"error",
								"mensaje"=>'Datos Cargados ');
					echo json_encode($reeturn);
			}else{
				$reeturn=array("status"=>'Ok',
								"lAlineacion"=>$resp,
								"mensaje"=>"Datos correctos");
				echo json_encode($reeturn);
			}
	break;

	case 'listarCambios':
		$obj = json_decode(file_get_contents('php://input'));
			$rspta=$ligapillaro->listarCambios($obj->idcalendario, $obj->idequipo);
			while($reg=$rspta->fetch_object()){
			   $resp[]=$reg;
			}
			if(empty($resp)){
				$reeturn=array("status"=>"error",
								"mensaje"=>'Error');
					echo json_encode($reeturn);
			}else{
				$reeturn=array("status"=>'Ok',
								"lCambios"=>$resp,
								"mensaje"=>"Datos correctos");
				echo json_encode($reeturn);
			}
	break;

	case 'listarCambiosCombo':
		$obj = json_decode(file_get_contents('php://input'));
			$rspta=$ligapillaro->listarCambiosCombo($obj->idcalendario, $obj->idequipo);
			while($reg=$rspta->fetch_object()){
			   $resp[]=$reg;
			}
			if(empty($resp)){
				$reeturn=array("status"=>"error",
								"mensaje"=>'Error');
					echo json_encode($reeturn);
			}else{
				$reeturn=array("status"=>'Ok',
								"lCambios"=>$resp,
								"mensaje"=>"Datos correctos");
				echo json_encode($reeturn);
			}
	break;

	case 'listarCambiosRealizados':
		$obj = json_decode(file_get_contents('php://input'));
			$rspta=$ligapillaro->listarCambiosRealizados($obj->idcalendarios, $obj->entra, $obj->sale);
			while($reg=$rspta->fetch_object()){
			   $resp[]=$reg;
			}
			if(empty($resp)){
				$reeturn=array("status"=>"error",
								"mensaje"=>'NO HAY CAMBIOS REALIZADOS');
					echo json_encode($reeturn);
			}else{
				$reeturn=array("status"=>'Ok',
								"lCambiosR"=>$resp,
								"mensaje"=>"Datos correctos");
				echo json_encode($reeturn);
			}
	break;

	/*################TABLA DE POSICIONES#######################*/

	case 'partidosJugados':
		$obj = json_decode(file_get_contents('php://input'));
			$rspta=$ligapillaro->partidosJugados();
			while($reg=$rspta->fetch_object()){
			   $resp[]=$reg;
			}
			if(empty($resp)){
				$reeturn=array("status"=>"error",
								"mensaje"=>'Error');
					echo json_encode($reeturn);
			}else{
				$reeturn=array("status"=>'Ok',
								"pJ"=>$resp,
								"mensaje"=>"Datos correctos");
				echo json_encode($reeturn);
			}
	break;

	case 'partidosGanados':
		$obj = json_decode(file_get_contents('php://input'));
			$rspta=$ligapillaro->partidosGanados($obj->idequipo1, $obj->idequipo2);
			while($reg=$rspta->fetch_object()){
			   $resp[]=$reg;
			}
			if(empty($resp)){
				$reeturn=array("status"=>"error",
								"mensaje"=>'Error');
					echo json_encode($reeturn);
			}else{
				$reeturn=array("status"=>'Ok',
								"pG"=>$resp,
								"mensaje"=>"Datos correctos");
				echo json_encode($reeturn);
			}
	break;

	case 'partidosPerdidos':
		$obj = json_decode(file_get_contents('php://input'));
			$rspta=$ligapillaro->partidosPerdidos($obj->idequipo1, $obj->idequipo2);
			while($reg=$rspta->fetch_object()){
			   $resp[]=$reg;
			}
			if(empty($resp)){
				$reeturn=array("status"=>"error",
								"mensaje"=>'Error');
					echo json_encode($reeturn);
			}else{
				$reeturn=array("status"=>'Ok',
								"pP"=>$resp,
								"mensaje"=>"Datos correctos");
				echo json_encode($reeturn);
			}
	break;

	case 'partidosEmpatados':
		$obj = json_decode(file_get_contents('php://input'));
			$rspta=$ligapillaro->partidosEmpatados($obj->idequipo1, $obj->idequipo2);
			while($reg=$rspta->fetch_object()){
			   $resp[]=$reg;
			}
			if(empty($resp)){
				$reeturn=array("status"=>"error",
								"mensaje"=>'Error');
					echo json_encode($reeturn);
			}else{
				$reeturn=array("status"=>'Ok',
								"pE"=>$resp,
								"mensaje"=>"Datos correctos");
				echo json_encode($reeturn);
			}
	break;

	case 'golesFavor':
		$obj = json_decode(file_get_contents('php://input'));
			$rspta=$ligapillaro->golesFavor($obj->idequipo);
			while($reg=$rspta->fetch_object()){
			   $resp[]=$reg;
			}
			if(empty($resp)){
				$reeturn=array("status"=>"error",
								"mensaje"=>'Error');
					echo json_encode($reeturn);
			}else{
				$reeturn=array("status"=>'Ok',
								"gF"=>$resp,
								"mensaje"=>"Datos correctos");
				echo json_encode($reeturn);
			}
	break;

	case 'golesContra':
		$obj = json_decode(file_get_contents('php://input'));
			$rspta=$ligapillaro->golesContra($obj->idequipo1, $obj->idequipo2);
			while($reg=$rspta->fetch_object()){
			   $resp[]=$reg;
			}
			if(empty($resp)){
				$reeturn=array("status"=>"error",
								"mensaje"=>'Error');
					echo json_encode($reeturn);
			}else{
				$reeturn=array("status"=>'Ok',
								"gC"=>$resp,
								"mensaje"=>"Datos correctos");
				echo json_encode($reeturn);
			}
	break;
		
	
		case 'insertarArbitro':

			$obj = json_decode(file_get_contents('php://input'));
				$rspta=$ligapillaro->insertarArbitro( $obj->cedula, $obj->nombre,$obj->apellido,  $obj->direccion, $obj->telefono,  $obj->celular,  $obj->correo);
	
	
				if($rspta){
					$reeturn=array("status"=>'Ok',
									"mensaje"=>"Datos correctos");
					echo json_encode($reeturn);
				}else{
					$reeturn=array("status"=>'ERROR',
									"mensaje"=>"Datos incorrectos");
					echo json_encode($reeturn);
					
				}
	
		break;

		case 'insertarLogin':

			$obj = json_decode(file_get_contents('php://input'));
				$rspta=$ligapillaro->insertarArbitro( $obj->usuario, $obj->nombre,$obj->apellido,  $obj->direccion, $obj->telefono,  $obj->celular,  $obj->correo);
	
	
				if($rspta){
					$reeturn=array("status"=>'Ok',
									"mensaje"=>"Datos correctos");
					echo json_encode($reeturn);
				}else{
					$reeturn=array("status"=>'ERROR',
									"mensaje"=>"Datos incorrectos");
					echo json_encode($reeturn);
					
				}
	
		break;

		case 'insertarFaltas':

			/* $obj = json_decode(file_get_contents('php://input')); */
			$nombrefalta= $_POST['nombrefalta'];
			$idjugadors=$_POST['idjugadors'];
			$idcalendarios= $_POST['idcalendarios'];
			$equipo= $_POST['equipo'];
				$rspta=$ligapillaro->insertarFaltas( $nombrefalta, $idjugadors,  $idcalendarios, $equipo);
				if($rspta){
					$reeturn=array("status"=>'Ok',
									"mensaje"=>"Datos correctos");
					echo json_encode($reeturn);
				}else{
					$reeturn=array("status"=>'ERROR',
									"mensaje"=>"EL JUGADOR YA ESTA EXPULSADO");
					echo json_encode($reeturn);
					
				}
	
		break;

		case 'insertarGol':

			/* $obj = json_decode(file_get_contents('php://input')); */
			
			$idjugadores=$_POST['idjugadores'];
			$idcalendarios= $_POST['idcalendarios'];
			$equipo= $_POST['equipo'];
				$rspta=$ligapillaro->insertarGol( $idjugadores,  $idcalendarios, $equipo);
	
				
				if($rspta){
					$reeturn=array("status"=>'Ok',
									"mensaje"=>"Datos correctos");
					echo json_encode($reeturn);
				}else{
					$reeturn=array("status"=>'ERROR',
									"mensaje"=>"EL JUGADOR YA ESTA EXPULSADO");
					echo json_encode($reeturn);
					
				}
	
		break;

		case 'insertarCambio':

			$idcalendarios= $_POST['idcalendarios'];	
			$entra= $_POST['entra']; 
			$sale = $_POST['sale'];
			$observacion= $_POST['observacion'];

				$rspta=$ligapillaro->insertarCambio($idcalendarios, $entra, $sale, $observacion);
	
				
				if($rspta){
					$reeturn=array("status"=>'Ok',
									"mensaje"=>"Datos correctos");
					echo json_encode($reeturn);
				}else{
					$reeturn=array("status"=>'ERROR',
									"mensaje"=>"Datos incorrectos");
					echo json_encode($reeturn);
					
				}
	
		break;


		case 'insertarInforme':
			$obj = json_decode(file_get_contents('php://input'));
				$rspta=$ligapillaro->insertarInforme($obj->idcalendarioss ,  $obj->informe,
				$obj->equipo1, $obj->resultado1, $obj->puntos1, $obj->equipo2, $obj->resultado2, $obj->puntos2);
	
				if($rspta){
					$reeturn=array("status"=>'Ok',
									"mensaje"=>"INFORME GUARDADO CORRECTAMENTE");
					echo json_encode($reeturn);
				}else{
					$reeturn=array("status"=>'ERROR',
									"mensaje"=>"Datos incorrectos");
					echo json_encode($reeturn);
					
				}
		break;

		case 'generarQR':
		//	$obj = json_decode(file_get_contents('php://input'));

		$rspta=$ligapillaro->generarQR( $_POST['idjugador']);
			
			echo json_encode( $rspta);
			 
		break;
		

		case 'verificarJugador':

		
			$rspta=$ligapillaro->verificarJugador(				
				$_POST['textoQR']);
				if($rspta==0){
					echo json_encode("Datos incorrectos: ");
				}else{
					echo json_encode("Datos correctos: ");
				} 
		break;


		///////////////////////////////////////////////////////////////////////////////////////////
		default:
		echo 'ENVIAR LA VARIABLE OP POR METODO GET';
		break;
	}

	
?>