<?php
	error_reporting(E_ALL);
	ini_set("display_errors", 1);

	$status="";
	$msisdn=""; 
	$session=""; 
	$text="";
	
	/*
	* Se capturan los apr�metros por defecto de los llamados USSD
	*/
	
	if (isset($_GET['status']))
		$status = $_GET['status'];
	if (isset($_GET['msisdn']))
		$msisdn = $_GET['msisdn'];
	if (isset($_GET['session']))
		$session = $_GET['session'];
	if (isset($_GET['text']))
		$text = $_GET['text'];

	session_id($session);
	session_start();
	
	/*echo $session;*/
	$level = isset($_SESSION['level']) ? $_SESSION['level'] : 0;
	
	/* Respuesta por defecto*/
	$response = '<?xml version="1.0" encoding="UTF8" ?>';
	$response.= '<doc>';
	/* El status 0 indica la ocurrencia de un error */
	$response.= '<param name="status" value="0" />';
	$response.= '<param name="mensaje" value="Ha ocurrido un error" />';
	$response.= '<param name="level" value="'.$level.'" />';
	$response.= '</doc>';
	
	//echo $level;
	
	/*
	* El estatus 'begin' representa el nivel 0 del men�
	* En la salida de este condicional, el men� deber� encontrarse en el nivel 1
	*/
	if ($status == 'begin' && $level == '0')
	{
		$_SESSION['level'] = '1';
		$response = '<?xml version="1.0" encoding="UTF8" ?>';
		$response.= '<doc>';
		/* El status 1 indica que es un nodo de interaccion*/
		$response.= '<param name="status" value="1" />';
		$response.= '<param name="mensaje" value="Primer Menu:\n1. Investigador\n2. Organizaciones\n3. Ciudadano\n4. Actor armado" />';
		$response.= '<param name="curLevel" value="'.$_SESSION['level'].'" />';
		$response.= '</doc>';		
	}
	/*
	* El estatus de continueidad de un men� se indicara con el valor "continue"
	*/
	if ($status == 'continue' && $level == '1')
	{	
		/*Fue seleccionada la opcion '1' en el men� anterior*/
		if ($text == '1')
		{
			$_SESSION['level'] = '1.1';
			$response = '<?xml version="1.0" encoding="UTF8" ?>';
			$response.= '<doc>';
			/* El status 1 indica que es un nodo de interaccion*/
			$response.= '<param name="status" value="1" />';
			$response.= '<param name="mensaje" value="Segundo nivel - (1.1):\n1. Opcion C\n2. Opcion D" />';
			$response.= '<param name="curLevel" value="'.$_SESSION['level'].'" />';
			$response.= '</doc>';
		}
		else if ($text == '2')
		{
			$_SESSION['level'] = '1.2';
			$response = '<?xml version="1.0" encoding="UTF8" ?>';
			$response.= '<doc>';
			/* El status 1 indica que es un nodo de interaccion*/
			$response.= '<param name="status" value="1" />';
			$response.= '<param name="mensaje" value="Segundo nivel - (1.2):\n1. Opcion E\n2. Opcion F" />';
			$response.= '<param name="curLevel" value="'.$_SESSION['level'].'" />';
			$response.= '</doc>';
		}	
	}
	
	else if ($status == 'continue' && $level == '1.1')
	{	
		/*Fue seleccionada la opcion '1' en el men� anterior*/
		if ($text == '1')
		{
			$_SESSION['level'] = '1.1.1';
			$response = '<?xml version="1.0" encoding="UTF8" ?>';
			$response.= '<doc>';
			/* El status 3 indica que es un nodo de finalizacion*/
			$response.= '<param name="status" value="3" />';
			$response.= '<param name="mensaje" value="Tercer nivel - (1.1.1)\nNodo Final" />';
			$response.= '<param name="curLevel" value="'.$_SESSION['level'].'" />';
			$response.= '</doc>';
		}
		else if ($text == '2')
		{
			$_SESSION['level'] = '1.1.2';
			$response = '<?xml version="1.0" encoding="UTF8" ?>';
			$response.= '<doc>';
			/* El status 3 indica que es un nodo de finalizacion*/
			$response.= '<param name="status" value="3" />';
			$response.= '<param name="mensaje" value="Tercer nivel - (1.1.2)\nNodo Final" />';
			$response.= '<param name="curLevel" value="'.$_SESSION['level'].'" />';
			$response.= '</doc>';
		}
	}
	
	/*
		La misma estructura podr�a aplicarse para el arbol:
			1.2
				1.2.1
				1.2.2
		2 
			2.1
				2.1.1
				2.1.2
			2.2
				2.2.1
				2.2.2
	*/
	
	header("Content-type: text/xml;charset=utf-8");
	$xml = new SimpleXMLElement($response);
	echo $xml->asXML();
	
	
?>
