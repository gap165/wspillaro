<?php

 require "../config/Conexion.php";
 include('../vendor/autoload.php');//Llamare el autoload de la clase que genera el QR
use Endroid\QrCode\QrCode;

Class Ligapillaro
 {
  public function _construct(){
  }
 //#############VALIDAR USUARIO#############################
  public function validarUsuario($usuario, $clave){
  $sql="SELECT `idarbitro`, `cedula`, `nombre`, `apellido`, `direccion`, `telefono`, `celular`, `correo`, `password`, `estadoarbi`, `fotoss` FROM `arbitros` where cedula='$usuario' and password ='$clave'  and estadoarbi='1'";
  
  return ejecutarConsulta($sql);
  }
  //#############LISTAR DATOS DE ARBITRO#############################
  public function perfilArbi(){
    $sql="SELECT cedula, nombre, apellido,direccion,telefono,celular,correo,fotoss FROM `arbitros` WHERE idarbitro='2'";
    
    return ejecutarConsulta($sql);
    }
   //#############lISTAR SERIE COMBO-##############################
  public function listaSerie(){
    $sql="SELECT series.`idserie`, series.`nombreserie` FROM `series`";
    return ejecutarConsulta($sql);
  }
  //#############lISTAR CATEGORIA COMBO-##############################
  public function listaCategoria($idserie){
    $sql="SELECT categorias.idcategoria, categorias.nombre_cate, categorias.idserie from categorias
    inner join series on categorias.idserie = series.idserie where series.idserie = '$idserie'";
    return ejecutarConsulta($sql);
  }
  //#############lISTAR EQUIPOS-##############################
  public function listaEquipos($idserie,$idcategoria ){

        $sql="SELECT  equipo.idequipo,equipo.nombreequipo, equipo.nombredueno, equipo.nombreentrenador, equipo.foto,
        concat(jugadores.nombre1,'',jugadores.nombre2,' ',jugadores.apellido1,' ',jugadores.apellido2)as nombreJ,
        jugadores.N_camiseta,jugadores.cedula,jugadores.idjugador from equipo 
                inner join categorias on equipo.idcategoria = categorias.idcategoria
                inner join series on categorias.idserie = series.idserie
                inner join jugadores on equipo.idequipo =jugadores.idequipo
        where   series.idserie= '$idserie' and categorias.idcategoria= '$idcategoria'
        GROUP BY equipo.idequipo";
      // die( $sql);  IMPRIMIR LA CONSULTA ANTES DE EJECUTAR PARA REVISAR
        return ejecutarConsulta($sql);
    }
  //#############lISTAR JUGADORES##############################
  public function listaJugadores($idequipo){

      $sql="SELECT equipo.nombreequipo, idjugador, concat(representante.Nombre,' ',representante.Apellido) as nombreR,
      concat(jugadores.nombre1,' ',jugadores.nombre2,' ',jugadores.apellido1,' ',jugadores.apellido2) as nombreJ,
      jugadores.cedula, jugadores.direccion, jugadores.correo,jugadores.celular,jugadores.telefono,jugadores.fotos, jugadores.N_camiseta
      ,' ' as qr from jugadores 
      inner join representante on jugadores.idrepresentante=representante.idrepresentante 
      inner join equipo on jugadores.idequipo=equipo.idequipo
     
      

      where equipo.idequipo='$idequipo'";
      return ejecutarConsulta($sql);
    }
 //#############lISTAR TEMPORADAS COMBO##############################
  public function listTemporadas(){
        $sql="SELECT `idtemporada`, `nombre_temporada`, `inicio_tem`, `fin_tem` FROM `temporadas`";
        return ejecutarConsulta($sql);
      }
 //#############lISTAR SERIES COMBO CALENDARIO##############################
  public function listSeries($idtemporada){
        $sql="SELECT series.`idserie`, series.`nombreserie`, series.`idtemporada` FROM `series`  
        INNER JOIN temporadas ON temporadas.idtemporada=series.idtemporada WHERE temporadas.idtemporada= '$idtemporada'";
        return ejecutarConsulta($sql);
      }
 //#############lISTAR CATEGORIAS COMBO CALENDARIO##############################
  public function listCategorias($idtemporada,$idserie){
        $sql="SELECT categorias.`idcategoria`, categorias.`nombre_cate`, categorias.`idserie` FROM `categorias`
        INNER JOIN series ON series.idserie=categorias.idserie
        INNER JOIN temporadas ON temporadas.idtemporada=series.idtemporada WHERE temporadas.idtemporada='$idtemporada'
        AND series.idserie='$idserie'";
        return ejecutarConsulta($sql);
      }



     
 //#############lISTAR CALEMDARIO##############################
  public function listCalendario($idtemporada,$idserie,$idcategoria){
        $sql="SELECT calendarios.`idcalendario`,calendarios.fecha,
        (SELECT equipo.nombreequipo FROM equipo WHERE equipo.idequipo=calendarios.`idequipo1` ) AS 'equipo1',
        (SELECT equipo.nombreequipo FROM equipo WHERE equipo.idequipo=calendarios.`idequipo2` ) AS 'equipo2',
        calendarios.`idequipo1`, calendarios.`idequipo2`, calendarios.`idtemporadas`, calendarios.`idcanchas`,
        calendarios.`idetapas`,calendarios.`Marcadorequi1`, calendarios.`Marcadorequi2`, calendarios.`fecha`,
        calendarios.`hora` 
        FROM `calendarios` 
        WHERE calendarios.idtemporadas='$idtemporada' AND calendarios.idseries='$idserie' AND calendarios.idcategorias='$idcategoria' AND calendarios.estadocalen=1
        GROUP by calendarios.`idcalendario`";
        return ejecutarConsulta($sql);
      }
    
   
      public function golesEquipo($idcalendario,$idequipo1,$idequipo2){
        $sql="SELECT (select COUNT(idjugadores) from goles where equipo='$idequipo1' and idcalendarios='$idcalendario') as 'equipo1',
        (select COUNT(idjugadores) from goles where equipo='$idequipo2' and idcalendarios='$idcalendario') as 'equipo2' 
        from goles where idcalendarios='$idcalendario' limit 1";
        
        return ejecutarConsulta($sql);
      }
      public function faltasEquipo1($idcalendario,$idequipo){
        $sql="SELECT `idfalta`, `nombrefalta`, `idjugadors`, `idcalendarios`, `equipo`, `hora`, `fecha`, `estadopago`,
        (SELECT CONCAT(jugadores.nombre1,' ',jugadores.apellido1) FROM jugadores WHERE jugadores.idjugador=faltas.idjugadors) AS 'nombre'
        FROM `faltas` WHERE equipo='$idequipo' and idcalendarios='$idcalendario'";
        
        return ejecutarConsulta($sql);
      }
      public function faltasEquipo2($idcalendario,$idequipo){
        $sql="SELECT `idfalta`, `nombrefalta`, `idjugadors`, `idcalendarios`, `equipo`, `hora`, `fecha`, `estadopago`,
        (SELECT CONCAT(jugadores.nombre1,' ',jugadores.apellido1) FROM jugadores WHERE jugadores.idjugador=faltas.idjugadors) AS 'nombre'
        FROM `faltas` WHERE equipo='$idequipo' and idcalendarios='$idcalendario'";
     
        
        return ejecutarConsulta($sql);
      }

    //########################lISTAR EQUIPOS CALENDARIO
    
    public function listarEquiposCaleE1($idcalendario){
      $sql="SELECT (SELECT equipo.nombreequipo FROM equipo WHERE equipo.idequipo=calendarios.`idequipo1` ) AS 'equipo1',
      (SELECT equipo.nombreequipo FROM equipo WHERE equipo.idequipo=calendarios.`idequipo2` ) AS 'equipo2',
      calendarios.idequipo1,calendarios.idequipo2 from calendarios where calendarios.idcalendario='$idcalendario'";
      return ejecutarConsulta($sql);
    }

    public function listarEquiposCaleE2($idcalendario){
      $sql="SELECT (SELECT equipo.nombreequipo FROM equipo WHERE equipo.idequipo=calendarios.`idequipo2` ) AS 'equipo1',
      (SELECT equipo.nombreequipo FROM equipo WHERE equipo.idequipo=calendarios.`idequipo1` ) AS 'equipo2',
      calendarios.idequipo2 as 'idequipo1' ,calendarios.idequipo1 as 'idequipo2' from calendarios where calendarios.idcalendario='$idcalendario'";
      return ejecutarConsulta($sql);
    }

    //########################lISTAR EQUIPOS PARA INGRESAR INFORME
    
    public function listarEquiposInf1($idcalendario){
      $sql="SELECT (SELECT equipo.nombreequipo FROM equipo WHERE equipo.idequipo=calendarios.`idequipo1` ) AS 'equipo1',
      (SELECT equipo.nombreequipo FROM equipo WHERE equipo.idequipo=calendarios.`idequipo2` ) AS 'equipo2',
      calendarios.idequipo1,calendarios.idequipo2 from calendarios where calendarios.idcalendario='$idcalendario'";
      return ejecutarConsulta($sql);
    }

    public function listarEquiposInf2($idcalendario){
      $sql="SELECT (SELECT equipo.nombreequipo FROM equipo WHERE equipo.idequipo=calendarios.`idequipo2` ) AS 'equipo1',
      (SELECT equipo.nombreequipo FROM equipo WHERE equipo.idequipo=calendarios.`idequipo1` ) AS 'equipo2',
      calendarios.idequipo2 as 'idequipo1' ,calendarios.idequipo1 as 'idequipo2' from calendarios where calendarios.idcalendario='$idcalendario'";
      return ejecutarConsulta($sql);
    }

    //###################LISTAR ALINEACION 
    public function listarAlineacion($idcalendario, $idequipo){
      $sql=" SELECT alineaciones.`idalineacion`, alineaciones.`idcalendario`, alineaciones.`idjugador`
      , alineaciones.`equipo_idequipo`, jugadores.cedula,jugadores.N_camiseta,jugadores.nombre1, jugadores.apellido1,jugadores.fotos ,
       (SELECT CONCAT(jugadores.nombre1,' ',jugadores.nombre2,' ',jugadores.apellido1,' ',jugadores.apellido2)
       FROM jugadores WHERE jugadores.idjugador=alineaciones.idjugador ) AS 'NOMJUGADOR'
       FROM `alineaciones`
       inner join jugadores on alineaciones.idjugador=jugadores.idjugador
       WHERE alineaciones.idcalendario='$idcalendario' AND alineaciones.equipo_idequipo='$idequipo' ";
     
     return ejecutarConsulta($sql);
    }

    public function listarAlineacionCombo($idcalendario, $idequipo){
      $sql=" SELECT alineaciones.`idalineacion`, alineaciones.`idcalendario`, alineaciones.`idjugador`,
      jugadores.`N_camiseta`, alineaciones.`equipo_idequipo`, jugadores.cedula,jugadores.nombre1, jugadores.apellido1,jugadores.fotos , (SELECT CONCAT(jugadores.nombre1,' ',jugadores.nombre2,' ',jugadores.apellido1,' ',jugadores.apellido2)
       FROM jugadores WHERE jugadores.idjugador=alineaciones.idjugador ) AS 'NOMJUGADOR'
       FROM `alineaciones`
       inner join jugadores on alineaciones.idjugador=jugadores.idjugador
       WHERE alineaciones.idcalendario='$idcalendario' AND alineaciones.equipo_idequipo='$idequipo'
       and jugadores.idjugador NOT IN (select cambios.entra from cambios where cambios.entra= jugadores.idjugador) 
       and jugadores.idjugador not in  (SELECT `idjugadors` FROM `faltas` WHERE idcalendarios='$idcalendario' and nombrefalta='ROJA' )
       ";
     return ejecutarConsulta($sql);
    }


    
    
     //###################LISTAR CAMBIOS

     public function listarCambios($idcalendario, $idequipo){
      $sql="SELECT jugadores.* FROM jugadores LEFT JOIN alineaciones ON alineaciones.idjugador = jugadores.idjugador where jugadores.idjugador
      not in  (select alineaciones.idjugador from alineaciones where  alineaciones.idcalendario='$idcalendario')
      and jugadores.idequipo='$idequipo' and jugadores.esta=1";
      return ejecutarConsulta($sql);
    }

    public function listarCambiosCombo($idcalendario, $idequipo){
      $sql="SELECT jugadores.* FROM jugadores LEFT JOIN alineaciones ON alineaciones.idjugador = jugadores.idjugador where jugadores.idjugador
      not in  (select alineaciones.idjugador from alineaciones where  alineaciones.idcalendario='$idcalendario') 
      and jugadores.idjugador NOT IN (select cambios.sale from cambios where cambios.sale= jugadores.idjugador)

      and jugadores.idequipo='$idequipo' and jugadores.esta=1";
  
      return ejecutarConsulta($sql);
    }

     //###################LISTAR CAMBIOS REALIZADOS

     public function listarCambiosRealizados($idcalendarios, $entra, $sale){
      $sql="SELECT `idcambios`, `idcalendarios`, 
      (SELECT concat(jugadores.nombre1,' ',jugadores.nombre2,' ',jugadores.apellido1,' ',jugadores.apellido2)
      FROM jugadores WHERE jugadores.idjugador=entra)as 'ENTRA', 
      (SELECT  jugadores.fotos FROM jugadores WHERE jugadores.idjugador=entra) as 'fotoentra', 
      (SELECT  jugadores.fotos FROM jugadores WHERE jugadores.idjugador=sale ) as 'fotosale', 
      (SELECT concat(jugadores.nombre1,' ',jugadores.nombre2,' ',jugadores.apellido1,' ',jugadores.apellido2)
       FROM jugadores WHERE jugadores.idjugador=sale)as 'SALE', `entra`, `sale`, `observacion` 
       FROM `cambios` WHERE `idcalendarios`='$idcalendarios'";
      return ejecutarConsulta($sql);
    }

 //#############SACAR NOMBRE EQUIPO PARA EL CALENDARIO##############################
  public function nombreEquipo($idequipo1,$idequipo2){
        $sql=" SELECT `idequipo`, `idcategoria`, `nombreequipo`, `Numjugadores`, `nombredueno`, `ceduladueno`, `nombreentrenador`, `cedulaentrenador`, `estado`
        FROM `equipo` WHERE idequipo in ('$idequipo1','$idequipo2')";
        return ejecutarConsulta($sql);
      }

   //#############LISTAR FALTAS DE EQUIPO##############################
  public function faltasEquipo($idequipo){
         $sql="SELECT jugadores.nombre1, jugadores.nombre2, jugadores.apellido1,jugadores.apellido2, faltas.nombrefalta, 
         faltas.hora, calendarios.fecha, jugadores.fotos FROM faltas 
         inner join calendarios on faltas.idcalendarios = calendarios.idcalendario 
         inner join jugadores on faltas.idjugadors = jugadores.idjugador 
         where jugadores.idequipo ='$idequipo' order by idfalta desc";

         return ejecutarConsulta($sql);
       }

  //#############SACAR GOLES DE JUGADOR POR EQUIPO#############################
  public function golesJugador($idjugador){
    $sql="SELECT jugadores.nombre1, jugadores.apellido1, jugadores.apellido2, calendarios.fecha, goles.hora,jugadores.fotos
    FROM goles 
    inner join jugadores on goles.idjugadores = jugadores.idjugador
    inner join calendarios on goles.idcalendarios = calendarios.idcalendario
    where jugadores.idjugador = '$idjugador' order by idgoles desc";
    
    return ejecutarConsulta($sql);
  }
  //#############lISTAR SERIE COMBO PARA TOP GOLEADORES-##############################
  public function comboSerie(){
      $sql="SELECT series.`idserie`, series.`nombreserie` FROM `series`";
      return ejecutarConsulta($sql);
    }
     //#############lISTAR CATEGORIA COMBO PARA TOP GOLEADORES-##############################
     public function comboCategoria($idserie){
      $sql="SELECT categorias.idcategoria, categorias.nombre_cate, categorias.idserie from categorias
      inner join series on categorias.idserie = series.idserie where series.idserie = '$idserie'";
      return ejecutarConsulta($sql);
    }

  //#############SACAR TOP DE GOLEADORES#############################
  public function topGoleadores($idserie, $idcategoria){
    $sql="SELECT equipo.nombreequipo, count(idgoles) as Ngoles,
    idjugadores, jugadores.nombre1, jugadores.apellido1, jugadores.apellido2 , 
    calendarios.fecha, goles.hora,jugadores.fotos
    from goles
    INNER join jugadores on goles.idjugadores = jugadores.idjugador
    inner join equipo on jugadores.idequipo = equipo.idequipo 
    inner join categorias on equipo.idcategoria = categorias.idcategoria
    inner join series on categorias.idserie = series.idserie
    inner join calendarios on goles.idcalendarios = calendarios.idcalendario
    where   series.idserie= '$idserie' and categorias.idcategoria= '$idcategoria'
    group by idjugadores order by Ngoles desc LIMIT 10 ";
    
    return ejecutarConsulta($sql);
  }
 
  //#############CARNET JUGADORES#############################
  public function carnetJugador($idjugador){
  $sql="SELECT equipo.nombreequipo, jugadores.nombre1, jugadores.nombre2, jugadores.apellido1, 
  jugadores.apellido2, jugadores.cedula, jugadores.fechadenacimiento, categorias.nombre_cate, series.nombreserie,jugadores.N_camiseta, jugadores.fotos
  FROM jugadores 
  inner join equipo on jugadores.idequipo = equipo.idequipo
  inner join categorias on equipo.idcategoria = categorias.idcategoria
  inner join series on categorias.idserie = series.idserie 
  WHERE jugadores.idjugador='$idjugador'";
  return ejecutarConsulta($sql);
  } 

//######################CARNETS JUGADORES POR EQUIPO####################
public function carnets($idequipo){
  $sql="SELECT equipo.nombreequipo, jugadores.nombre1, jugadores.nombre2, jugadores.apellido1, 
  jugadores.apellido2,concat(jugadores.nombre1,' ',jugadores.nombre2,' ', jugadores.apellido1,' ',
  jugadores.apellido2)as nombres, jugadores.cedula, jugadores.fechadenacimiento, categorias.nombre_cate,
   series.nombreserie,N_camiseta, jugadores.fotos
 , ' 'as qr, jugadores.idjugador FROM jugadores 
  inner join equipo on jugadores.idequipo = equipo.idequipo
  inner join categorias on equipo.idcategoria = categorias.idcategoria
  inner join series on categorias.idserie = series.idserie 
  where equipo.idequipo='$idequipo'";

  return ejecutarConsulta($sql);
}

 //##################TABLA DE POSICIONES########################
 
 //##################PARTIDOS JUGADOS########################
  public function partidosJugados(){
  $sql="SELECT count(*)  as partidosjugados, idequipo1, equipo.nombreequipo from 
  (SELECT idequipo1 from calendarios where estadocalen = 0
  UNION ALL select idequipo2 from calendarios where estadocalen = 0) as equiposs
  INNER JOIN equipo on equipo.idequipo = equiposs.idequipo1 GROUP by idequipo1";
  return ejecutarConsulta($sql);
}
//##################PARTIDOS GANADOS########################
public function partidosGanados($idequipo1, $idequipo2){
  $sql="SELECT Marcadorequi1,Marcadorequi2,idequipo1,idequipo2 from calendarios 
  where idequipo1='$idequipo1' and Marcadorequi1 > Marcadorequi2 
  union all select Marcadorequi1,Marcadorequi2,idequipo1,idequipo2 
  from calendarios where idequipo2='$idequipo2' and Marcadorequi2 > Marcadorequi1";
  return ejecutarConsulta($sql);
}

//##################PARTIDOS PERDIDOS########################
public function partidosPerdidos($idequipo1, $idequipo2){
  $sql="SELECT Marcadorequi1,Marcadorequi2,idequipo1,idequipo2 from calendarios where idequipo1='$idequipo1' and Marcadorequi1 < Marcadorequi2
  union all select Marcadorequi1,Marcadorequi2,idequipo1,idequipo2 from calendarios where idequipo2='$idequipo2' and Marcadorequi2 < Marcadorequi1";
  return ejecutarConsulta($sql);
}

//##################PARTIDOS EMPATADOS########################
public function partidosEmpatados($idequipo1, $idequipo2){
  $sql="SELECT Marcadorequi1,Marcadorequi2,idequipo1,idequipo2 from calendarios where idequipo1='$idequipo1' and Marcadorequi1= Marcadorequi2 
  union all select Marcadorequi1,Marcadorequi2,idequipo1,idequipo2 from calendarios where idequipo2='$idequipo2' and Marcadorequi2 = Marcadorequi1";
  return ejecutarConsulta($sql);
}

//##################GOLES A FAVOR########################
public function golesFavor($idequipo){
  $sql="SELECT count(*) as favor from goles INNER join jugadores on goles.idjugadores= jugadores.idjugador 
  WHERE jugadores.idequipo = '$idequipo'";
  return ejecutarConsulta($sql);
}

//##################GOLES EN CONTRA########################
public function golesContra($idequipo1, $idequipo2){
  $sql="SELECT SUM(Marcadorequi1) as golescon from ( SELECT Marcadorequi1 FROM calendarios 
  WHERE idequipo2='$idequipo2' UNION ALL SELECT Marcadorequi2 FROM calendarios WHERE idequipo1='$idequipo1') as golescontra";
  return ejecutarConsulta($sql);
}




//CONSULTAR COMOBO EQUIPOS DE CALENDARIO PARA ALINEACION

public function verEquipos($idcalendario){
  $sql="SELECT calendarios.`idcalendario`,
  (SELECT equipo.nombreequipo FROM equipo WHERE equipo.idequipo=calendarios.`idequipo1` ) AS 'equipo1',
  (SELECT equipo.nombreequipo FROM equipo WHERE equipo.idequipo=calendarios.`idequipo2` ) AS 'equipo2',calendarios.`idequipo1`, 
  calendarios.`idequipo2`
  FROM `calendarios` WHERE calendarios.idcalendario='$idcalendario'";
  return ejecutarConsulta($sql);
}


//##################################INSERTAR#########################################################3

////##################REGISTRAR########################
public function insertarArbitro($cedula,$nombre,$apellido,$direccion,$telefono,$celular,$correo){
  //no inserto el id calendario por que es autoincremet
  $sql="INSERT INTO `arbitros`(`cedula`, `nombre`, `apellido`, `direccion`, `telefono`, `celular`, `correo`, `estadoarbi`) 
  VALUES ( '$cedula','$nombre','$apellido','$direccion','$telefono','$celular','$correo','0')";

  return ejecutarConsulta($sql);
}

////##################INSERTAR FALTAS########################
public function insertarFaltas($nombrefalta,$idjugadors,$idcalendarios,$equipo){

  $faltas="SELECT COUNT(idfalta) as 'faltas' from faltas where idjugadors='$idjugadors' AND idcalendarios = '$idcalendarios' 
  AND nombrefalta ='AMARILLA' AND estadopago = '1'";

  $resp=ejecutarConsulta($faltas)->fetch_object();

  if($resp->faltas==2){
    return false;
  }else{
    $rojas="SELECT COUNT(idfalta) as 'faltas' from faltas where idjugadors='$idjugadors' AND idcalendarios = '$idcalendarios' AND estadopago = '1'
    AND nombrefalta ='ROJA'";
    $resp=ejecutarConsulta($rojas)->fetch_object();
    if($resp->faltas==1){
      return false;
    }else{
      $sql="INSERT INTO `faltas`(`nombrefalta`, `idjugadors`, `idcalendarios`,equipo, `hora`,`fecha`, `estadopago`) 
      VALUES ('$nombrefalta',$idjugadors,$idcalendarios,$equipo,now(),now(),'1')";
      return ejecutarConsulta($sql);
    }
  }
}

////##################INSERTAR GOLES EN ALINEACION########################
public function insertarGol($idjugadores,$idcalendarios, $equipo){

  $faltas="SELECT COUNT(idfalta) as 'faltas' from faltas where idjugadors='$idjugadores' AND idcalendarios = '$idcalendarios' 
  AND nombrefalta ='AMARILLA' AND estadopago = '1'";
  
  $resp=ejecutarConsulta($faltas)->fetch_object();

  if($resp->faltas==2){
    return false;
  }else{
    $rojas="SELECT COUNT(idfalta) as 'faltas' from faltas where idjugadors='$idjugadores' AND idcalendarios = '$idcalendarios' AND estadopago = '1'
    AND nombrefalta ='ROJA'";
    $resp=ejecutarConsulta($rojas)->fetch_object();
    if($resp->faltas==1){
      return false;
    }else{
      $sql="INSERT INTO `goles`(`idjugadores`, `idcalendarios`, `hora`, equipo)
      VALUES ('$idjugadores','$idcalendarios',now(), '$equipo')";
     
      return ejecutarConsulta($sql);
    }
  }
}

////##################INSERTAR CAMBIOS########################
public function insertarCambio($idcalendarios, $entra, $sale, $observacion){

  
  $sql="INSERT INTO `cambios`( `idcalendarios`, `entra`, `sale`, `observacion`)
   VALUES ('$idcalendarios','$entra','$sale','$observacion')";
 
  return ejecutarConsulta($sql);
}


public function listarDatosInforme($idcalendarios){

}

////##################INSERTAR INFORME########################
public function insertarInforme(/* $idarbitros, */ $idcalendarioss, $informe, $equipo1, $resultado1, $puntos1, $equipo2, $resultado2, $puntos2)
{
  $sql="INSERT INTO `calearbi`(`idarbitros`, `idcalendarioss`, `informe`, `equipo1`, 
  `resultado1`, `puntos1`, `equipo2`, `resultado2`, `puntos2`, `estado`) 
  VALUES ('16', '$idcalendarioss', '$informe', '$equipo1', '$resultado1', '$puntos1','$equipo2', '$resultado2',  '$puntos2', '1')";
  ejecutarConsulta($sql);

  $sql="UPDATE `calendarios` SET estadocalen=0 WHERE idcalendario='$idcalendarioss'";
  return ejecutarConsulta($sql);
}


/////###########################GENERAR QR#########################
public function generarQR($idjugador)
{

$sizeqr=100;
$qrCode = new QrCode($idjugador); 

$qrCode->setSize($sizeqr);   
$image= $qrCode->writeString(); 
 $imageData = base64_encode($image); 
return $imageData;

}
/////###########################GENERAR QR equipo#########################
public function generarQRE($idequipo)
{

$sizeqr=100;
$qrCode = new QrCode($idequipo); 

$qrCode->setSize($sizeqr);   
$image= $qrCode->writeString(); 
 $imageData = base64_encode($image); 
return $imageData;

}

////////#######################VERIFICAR JUGADOR EN ALINEACION############################

public function verificarJugador($textoQR )
{
  
  $sql="SELECT count(*) FROM jugadores inner join equipo on jugadores.idequipo = equipo.idequipo 
  WHERE concat( cedula,' ',nombre1,' ',nombre2 , ' ',apellido1,' ',apellido2,' ', N_camiseta, ' ' , nombreequipo)  = '$textoQR'";
  
  return ejecutarConsultaEscalar($sql);
 }


 }
?>