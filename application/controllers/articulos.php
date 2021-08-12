<?php
@session_start();
class Articulos extends CI_Controller {
   
     public function __construct()
    {
   	 parent:: __construct();
   	 $this->load->model('login_model');
   	 $this->load->library('session');
	 $this->db = $this->load->database('default', TRUE); 
   	 $this->load->library('pagination');
   	 $this->load->model('paginador_model', 'modelo');
   	 $this->load->helper(array('url'));
   	 date_default_timezone_set('America/Argentina/Buenos_Aires');
    }
	
	
	function index($idioma=null)
   {
       /*if(($idioma == '') || ($idioma == NULL)){
   	   	header("Location: https://caemasivo.com/mantenimiento.php");
   	   	exit();
   	   }*/
       $maco = ""; 
	   $data = ""; 
      //   $this->config->set_item('language', 'spanish');      //   Setear dinámicamente el idioma que deseamos que ejecute nuestra aplicación
       $logged = $this->login_model->isLogged();
      if(!$logged){
	  	$maco['error'] = "Debes loguearte para comenzar!";  
        $this->load->view('login', $maco);
	  }else{    
	  	                   
        header("location: ".site_url('articulos/login'));
			   //echo "Validacion Ok<br><br><a href=''>Volver</a>";   //   Si el usuario ingresó datos de acceso válido, imprimos un mensaje de validación exitosa en pantalla
      }
        
   }
   
   
   
   function info(){
   	phpinfo();
   }
   
   
   
  
   
   
   
    function escritorio($offset=''){
   	   @session_start();
   	  //compruebo que esté logueado!!
	 
	
      $logged = $this->login_model->isLogged();
      if(!$logged){
	  	$data['error'] = "Debes loguearte para comenzar!!";  
        $this->load->view('login', $data);
	  }else{ 
	  	$data["usuarioTop"]=$_SESSION["usuarioTop22"];
		$data["mensaje1"] = "";
		if($_SESSION["habilMm"]=="N") {
			$data["mensaje1"] = "ACCESO DE SOLO LECTURA";
		}
		$data["mensaje2"] = "";
		if($_SESSION["coleSoloLec"]=="S") {
			$data["mensaje2"] = $_SESSION["msgColeSoloLec"];
		}
		$data["muestro_disponibilidad"] = false;
		
		$config['hostname'] = $_SESSION["host"];
		 $config['username'] = $_SESSION["mysql"];
		 $config['password'] = $_SESSION["passmysql"];
		 $config['database'] = $_SESSION["database"];
		 $config['dbdriver'] = "mysql";
		 $config['dbprefix'] = "";
		 $config['pconnect'] = TRUE;
		 $config['db_debug'] = TRUE;
		 $config['cache_on'] = FALSE;
		 $config['cachedir'] = "";
		 $config['char_set'] = "utf8";
		 $config['dbcollat'] = "utf8_general_ci"; 
		 $this->db = $this->load->database($config, TRUE);
		 $this->load->model('Articulo_model');
		 $data["parametros"] = $this->Articulo_model->tomar_registro_bd("tbl_parametros","parametros_id","1");
		 
		  $this->db = $this->load->database('default', TRUE);
		  $this->db->where('EMPR_PIN', $this->session->userdata['pin']);
		  $query = $this->db->get("TBL_EMPRESA");
		  $data["data_empresa"] = $query->row();
		  
		  //INICIO PORCENTAJE
		  $data["porcentaje"] = 100;
		  /*$caecomp = 0;
		  if($_SESSION["testing"]=="N"){
		  	//obtengo los caes comprados
			$this->db = $this->load->database('default', TRUE); 
			$caes_facts = $this->Articulo_model->tomarRegistros("TBL_FACTURAS", "FACTURAS_ID", "ASC", "FACTURAS_PIN", $this->session->userdata['pin']);			
			if(count($caes_facts)>0){
				foreach($caes_facts as $cf){
					$caecomp = $caecomp + $cf->FACTURAS_CANT;
				}//foreach
			}
			if($caecomp>0){
				//si tiene caes comprados, compruebo la cantidad de disponibles
				//busco cuantos caes hay usados
				 //vuelvo a la db actual
				 $config['hostname'] = $_SESSION["host"];
				 $config['username'] = $_SESSION["mysql"];
				 $config['password'] = $_SESSION["passmysql"];
				 $config['database'] = $_SESSION["database"];
				 $config['dbdriver'] = "mysql";
				 $config['dbprefix'] = "";
				 $config['pconnect'] = TRUE;
				 $config['db_debug'] = TRUE;
				 $config['cache_on'] = FALSE;
				 $config['cachedir'] = "";
				 $config['char_set'] = "utf8";
				 $config['dbcollat'] = "utf8_general_ci"; 
				 $this->db = $this->load->database($config, TRUE); 
				 $total_caes_usados = 0;
				 $sql = "gencae_modo = 'P' AND (gencae_cae_estado = '1' OR gencae_cae_estado = '2')";
		 		 $this->db->where($sql); 
		 		 $total_caes_usados = $this->db->count_all_results("tbl_gencae_historial");
				 				 
				 if($total_caes_usados <= $caecomp){
				 	$caes_disp = $caecomp - $total_caes_usados;
					$data["porcentaje"] = $caes_disp * 100 / $caecomp;
				 }else{
				 	//el total de caes usados es mayor al comprado, algo anda mal, asi que mando aviso
					$data["porcentaje"] = 0;
				 }
			}
		  }		  	
		  /*if($this->session->userdata['pin']=="9999"){
		  	$data["porcentaje_verdadero"] = $data["porcentaje"];
			$data["porcentaje"] = 14;
		  }else{
		  	$data["porcentaje"] = 100;
		  }*/
		  //FIN PORCENTAJE
		  
		  //INICIO MAYOR A 4 MESES
		  $caecomp = 0;
		  $data["mejor_consumo"] = '';
		  $data["caes_disp"] = '';
		  $muestro = false; //variable que hara que muestre o no el cartelito de caemasivo
		  $muestro2 = false; //variable que hara que muestre o no el cartelito de paquete de envios
		  if($_SESSION["testing"]=="N"){
		  	//obtengo los caes comprados
			$this->db = $this->load->database('default', TRUE); 
			$caes_facts = $this->Articulo_model->tomarRegistros("TBL_FACTURAS", "FACTURAS_ID", "ASC", "FACTURAS_PIN", $this->session->userdata['pin']);			
			if(count($caes_facts)>0){
				foreach($caes_facts as $cf){
					$caecomp = $caecomp + $cf->FACTURAS_CANT;
				}//foreach
			}
			
			//INICIO CAES COMPRADOS Y DISPONIBLES
			/////////////////////////////////////
			if($caecomp>0){
				//si tiene caes comprados, compruebo la cantidad de disponibles
				//busco cuantos caes hay usados
				 //vuelvo a la db actual
				 $config['hostname'] = $_SESSION["host"];
				 $config['username'] = $_SESSION["mysql"];
				 $config['password'] = $_SESSION["passmysql"];
				 $config['database'] = $_SESSION["database"];
				 $config['dbdriver'] = "mysql";
				 $config['dbprefix'] = "";
				 $config['pconnect'] = TRUE;
				 $config['db_debug'] = TRUE;
				 $config['cache_on'] = FALSE;
				 $config['cachedir'] = "";
				 $config['char_set'] = "utf8";
				 $config['dbcollat'] = "utf8_general_ci"; 
				 $this->db = $this->load->database($config, TRUE); 
				 $total_caes_usados = 0;
				 $sql = "gencae_modo = 'P' AND (gencae_cae_estado = '1' OR gencae_cae_estado = '2')";
		 		 $this->db->where($sql); 
		 		 $total_caes_usados = $this->db->count_all_results("tbl_gencae_historial");
				 $caes_disp = $caecomp - $total_caes_usados;
				 
				 //ultimos 4 meses
				 $mes_actual = date('n');
				 $ano_actual = date('Y');
				 $meses = array();
				 $count_meses = 0;
				 do{					 
				 	if($mes_actual == 1){
				 		$mes_actual = 12;
						$ano_actual = $ano_actual - 1;		
				 	}else{
						$mes_actual = $mes_actual - 1;
					}
					if($mes_actual<10){ $mes_actual_format = "0".$mes_actual; }else{ $mes_actual_format = $mes_actual; }
					$meses[$count_meses] = $ano_actual.'-'.$mes_actual_format.'-01';
					$count_meses++;
				 }while($count_meses < 4);
				 //calculo gasto de meses
				 $textito = '';
				 $mas_grande = 0;
				 $fecha_mas_grande = '';
				 $indice_siguiente = 0;
				 foreach($meses as $mes):						
				 	if($indice_siguiente == 0){
				 		$fecha_inicio = $mes;
				 		$fecha_final = date("Y-m")."-01";
				 	}else{							
				 		$fecha_inicio = $mes;	
				 		$fecha_final = $meses[$indice_siguiente-1];
				 	}		
				 	$consulta = "SELECT COUNT(*) AS totali_cae  FROM tbl_gencae_historial WHERE gencae_fecha_cae >= '".$fecha_inicio."' AND gencae_fecha_cae < '".$fecha_final."' AND gencae_modo = 'P' AND (gencae_cae_estado = '1' OR gencae_cae_estado = '2')";	
				 	$textito.= '<br>'.$consulta;								
				 	$consulta_caes = $this->db->query($consulta);
				 	$consulta_caes = $consulta_caes->row();
				 	$total_usados =  $consulta_caes->totali_cae;
				 	if($total_usados > $mas_grande){ $mas_grande = $total_usados; $fecha_mas_grande = $fecha_inicio; }						
				 	$caes_usados[$indice_siguiente] = $total_usados;
				 	$indice_siguiente++;
				 endforeach;
				 
				 if($mas_grande>0){
				 	$cantidad_15porc = $mas_grande * 0.15 + $mas_grande;
					 if($caes_disp < $cantidad_15porc) { $muestro = true; }
					 $data["caes_disp"] = $caes_disp;
					 $fecha_mas_grande = explode("-",$fecha_mas_grande);
					 $mes_grande = $fecha_mas_grande[1];
					 $ano_grande = $fecha_mas_grande[0];
					 $data["mejor_consumo"] = $mes_grande.'-'.$ano_grande.' | '.$mas_grande;
					 if((isset($_GET["testsql"])) && ($_GET["testsql"]=="9999")){				 	
						//
						echo '<pre>';
						print_r($meses);
						echo '</pre>';
						echo '<pre>';
						print_r($caes_usados);
						echo '</pre>';
						echo $textito;
						echo '<br><br>El mes que mas se consumio fue una cantidad de '.$mas_grande;
						echo '<br>La cantidad disponible de caes es: '.$caes_disp;
						echo '<br>La cantidad + 15% es: '.$cantidad_15porc;
						echo '<br>Mejor consumo: '.$data["mejor_consumo"];
						if($muestro){ echo '<br><br>MUESTRO cartel'; }else{ echo '<br><br>NO muestro cartel'; }
						$muestro = true;
						//exit();
					 }
				}//if(mas_grande>0)	 
				 //fin ultimos 4 meses 	 
			}//if $caecomp >0	
			//FIN CAES COMPRADOS Y DISPONIBLES
			//////////////////////////////////
			

			//ENVIO DE MAILS DISPONIBLES
			/////////////////////////////////////
			if(($data["data_empresa"]->EMPR_ENVIOS == "S") && ($data["data_empresa"]->EMPR_ENVIOS_INICIO != '0000-00-00')){
				$data["muestro_disponibilidad"] = true;
				//si envios habilitados y tiene paquete de mails agregados
				$this->db = $this->load->database('default', TRUE); 
				$envios_facts = $this->Articulo_model->tomarRegistros("TBL_FACTURAS_ENVIOS", "FACTURASE_ID", "ASC", "FACTURASE_PIN", $data["data_empresa"]->EMPR_PIN);
				$envioscomp = 0;
				if(count($envios_facts)>0){
						foreach($envios_facts as $ef){
								$envioscomp = $envioscomp + $ef->FACTURASE_CANT;
						}//foreach
				}

				//busco cuantos envios hizo el mes actual
				 //vuelvo a la db actual
				 $config['hostname'] = $_SESSION["host"];
				 $config['username'] = $_SESSION["mysql"];
				 $config['password'] = $_SESSION["passmysql"];
				 $config['database'] = $_SESSION["database"];
				 $config['dbdriver'] = "mysql";
				 $config['dbprefix'] = "";
				 $config['pconnect'] = TRUE;
				 $config['db_debug'] = TRUE;
				 $config['cache_on'] = FALSE;
				 $config['cachedir'] = "";
				 $config['char_set'] = "utf8";
				 $config['dbcollat'] = "utf8_general_ci"; 
				 $this->db = $this->load->database($config, TRUE); 
				 
				 /*//envios realizados mes actual
				 $fecha_desde = date("Y-m-01");
				 $mes_actual = date("n");
				 if($mes_actual == 12){
				 	$mes_siguiente = '01';
				 }else{
				 	$mes_siguiente = $mes_actual + 1;
				 	if($mes_siguiente < 10){
				 		$mes_siguiente = "0".$mes_siguiente;
				 	}
				 }
				 $fecha_hasta = date("Y")."-".$mes_siguiente."-01";
				 $total_envios = 0;
				 // $sql	     = "gencae_cae != '' OR gencae_cae IS NOT NULL";
				 $sql	     = "envios_fecha >= '".$fecha_desde."' AND envios_fecha < '".$fecha_hasta."'";
				 $this->db->where($sql); 
				 $total_envios = $this->db->count_all_results("tbl_envios");
				 */
				 $fecha_desde = $data["data_empresa"]->EMPR_ENVIOS_INICIO;
				 $total_envios = 0;
				 $sql	     = "envios_fecha >= '".$fecha_desde."' ";
				 $this->db->where($sql); 
				 $total_envios = $this->db->count_all_results("tbl_envios");
				 //fin envios realizados mes actual
				 
				 $envios_disp = $envioscomp - $total_envios;
				 //fin envios realizados mes actual	 
				 
				 /*$paquete_mas_10_porciento = $data["data_empresa"]->EMPR_CANT_MAILS * 1.1; //110% del total
				 if($total_envios > $paquete_mas_10_porciento){
				 	$muestro2 = true;
				 }*/
				 $data["total_correos_enviados"] = '';
				 $data["limite_envios"] = $envioscomp;
				 $paquete_al_90_porciento = $envioscomp * 0.9; //110% del total
				 if($total_envios > $paquete_al_90_porciento){
				 	$data["total_correos_enviados"] = $total_envios;
				 	$muestro2 = true;
				 }
				 
			}//if $data["data_empresa"]->EMPR_ENVIOS == "S") && ($data["data_empresa"]->EMPR_CANT_MAILS > 0)
			//FIN ENVIO DE MAILS DISPONIBLES
			//////////////////////////////////
			else{
				$data["muestro_disponibilidad"] = false;
			}


		  }		  	
		  /*if($this->session->userdata['pin']=="9999"){
		  	$data["porcentaje_verdadero"] = $data["porcentaje"];
			$data["porcentaje"] = 14;
		  }else{
		  	$data["porcentaje"] = 100;
		  }*/
		  //FIN MAYOR A 4 MESES
		
		
		//$this->load->view('header', $data);
		$data["muestro"] = $muestro;
		$data["muestro2"] = $muestro2;
		$_SESSION["mejor_consumo"] = $data["mejor_consumo"];
		$_SESSION["caes_disp"] = $data["caes_disp"];
	   	$this->load->view('escritorio', $data);
	  }
   }
   
   
   
   
   
   
   function escritoriotest($offset=''){
   	   @session_start();
   	  //compruebo que esté logueado!!
	 
	
      $logged = $this->login_model->isLogged();
      if(!$logged){
	  	$data['error'] = "Debes loguearte para comenzar!!";  
        $this->load->view('login', $data);
	  }else{ 
	  	$data["usuarioTop"]=$_SESSION["usuarioTop22"];
		$data["mensaje1"] = "";
		if($_SESSION["habilMm"]=="N") {
			$data["mensaje1"] = "ACCESO DE SOLO LECTURA";
		}
		$data["mensaje2"] = "";
		if($_SESSION["coleSoloLec"]=="S") {
			$data["mensaje2"] = $_SESSION["msgColeSoloLec"];
		}
		
		$config['hostname'] = $_SESSION["host"];
		 $config['username'] = $_SESSION["mysql"];
		 $config['password'] = $_SESSION["passmysql"];
		 $config['database'] = $_SESSION["database"];
		 $config['dbdriver'] = "mysql";
		 $config['dbprefix'] = "";
		 $config['pconnect'] = TRUE;
		 $config['db_debug'] = TRUE;
		 $config['cache_on'] = FALSE;
		 $config['cachedir'] = "";
		 $config['char_set'] = "utf8";
		 $config['dbcollat'] = "utf8_general_ci"; 
		 $this->db = $this->load->database($config, TRUE);
		 $this->load->model('Articulo_model');
		 $data["parametros"] = $this->Articulo_model->tomar_registro_bd("tbl_parametros","parametros_id","1");
		 
		  $this->db = $this->load->database('default', TRUE);
		  $this->db->where('EMPR_PIN', $this->session->userdata['pin']);
		  $query = $this->db->get("TBL_EMPRESA");
		  $data["data_empresa"] = $query->row();
		
		
		//$this->load->view('header', $data);
	   	$this->load->view('escritoriotest', $data);
	  }
   }
   
   
   
   function cuenta() {
 	@session_start();
   	  //compruebo que esté logueado!!
	  $logged = $this->login_model->isLogged();
      if(!$logged){
	  	//$maco['error'] = "Debes loguearte para comenzar..!";  
        header("location: ".site_url('articulos/login'));
	  }else{ 
		$data["usuarioTop"]=$_SESSION["usuarioTop22"];
		$data["tituloSeccion"] = "Mi Cuenta";
		$data["tabla"]="TBL_USUARIOS";
		$this->load->model('Articulo_model');
		$data["cuentaLogin"]=$this->Articulo_model->tomar_cuenta($_SESSION["id_login"]);
		$this->load->view('header2', $data);
		$this->load->view('cuenta', $data);
	 }	
   }//ffcion
   
   
    function cuenta_update($id){
   	//recogemos los datos obtenidos por POST
 		//$data['mas_codigo'] = $_POST['mas_codigo'];
 		$data['HABIL_USU'] = $_POST['HABIL_USU'];
 		$data['HABIL_PASS'] = $_POST['HABIL_PASS'];
		$data['repite'] = $_POST['repite'];
		$data['HABIL_ID'] = $_SESSION["id_login"];
		$this->load->model('Articulo_model');
		if($data['HABIL_PASS']==$data['repite']) {
			$this->Articulo_model->actualizar_cuenta($data);
			header("location: ".site_url('articulos/cuenta?error=Registro actualizado. En el próximo ingreso deberá colocar los nuevos datos.!'));
		}else{
			header("location: ".site_url('articulos/cuenta?error=Las contraseñas no coinciden. Por favor, por su seguridad, vuelva a introducir los nuevos datos nuevamente.'));
		}	
	}	
   
   
   
    function generar(){
   	   @session_start();
   	  //compruebo que esté logueado!!
	 
	
      $logged = $this->login_model->isLogged();
      if(!$logged){
	  	$maco['error'] = "Debes loguearte para comenzar!";  
        $this->load->view('login', $maco);
	  }else{ 
	  	$this->load->helper('url');
		$referencia="";
		//$total = $this->modelo->count_facturas($referencia);
		//$data['facturas'] = $this->modelo->list_facturas($total, "", $referencia);
      	$data["titulo"]="Ingrese por favor el número de factura para generar el PDF.";
      	//$this->load->view('lista_vista', $data);
      	$this->load->view('generar', $data);
	  }
   }
   
   
    
   function login($idioma=null)
   {
   	   	
       $maco = array();  
	   $data = array();
      //   $this->config->set_item('language', 'spanish');      //   Setear dinámicamente el idioma que deseamos que ejecute nuestra aplicación
      if(!isset($_POST['maillogin'])){   //   Si no recibimos ningún valor proveniente del formulario, significa que el usuario recién ingresa.   
      	/*if(($idioma == '') || ($idioma == NULL)){
			header("Location: https://caemasivo.com/mantenimiento.php");
			exit();
		}*/
        if($idioma=='check'){
        	$maco["error2"] = 'Para proseguir, debes volver a ingresar...';
     	}
        $this->load->view('login', $maco);      //   Por lo tanto le presentamos la pantalla del formulario de ingreso. 
      }
      else{   
		
                           //   Si el usuario ya pasó por la pantalla inicial y presionó el botón "Ingresar"
         //$this->form_validation->set_rules('maillogin','e-mail','required|valid_email');      //   Configuramos las validaciones ayudandonos con la librería form_validation del Framework Codeigniter
         $this->form_validation->set_rules('passwordlogin','password','required');
         if(($this->form_validation->run()==FALSE)){            //   Verificamos si el usuario superó la validación
		    $maco["error"]="Datos incorrectos";
            $this->load->view('login', $maco);                     //   En caso que no, volvemos a presentar la pantalla de login
         }
         else{                                       //   Si ambos campos fueron correctamente rellanados por el usuario,
            $this->load->model('Articulo_model');
            $ExisteUsuarioyPassoword=$this->Articulo_model->ValidarUsuario($_POST['maillogin'],$_POST['passwordlogin'],$_POST['pin']);   //   comprobamos que el usuario exista en la base de datos y la password ingresada sea correcta
            if($ExisteUsuarioyPassoword){   // La variable $ExisteUsuarioyPassoword recibe valor TRUE si el usuario existe y FALSE en caso que no. Este valor lo determina el modelo.
				
				  //compruebo que esté habilitado el cliente
				  $estaHabilitado = $this->Articulo_model->Habilitado($_POST['maillogin'],$_POST['passwordlogin'],$_POST['pin']);
				  if($estaHabilitado) {
				  	//compruebo si el colegio se encuentra habilitado
					$colegio = $this->Articulo_model->eHabilitada($_POST['pin']);
					if($colegio->EMPR_HABIL=="S") {
						//compruebo que la fecha no haya caducado
						if($colegio->EMPR_HASTA>=date("Y-m-d")){
							//compruebo si pueden ingresar al colegio
							if($colegio->EMPR_ABIERTO=="S") {
								$ttop = $ExisteUsuarioyPassoword->HABIL_APEYNOM;
								$habilMm = $ExisteUsuarioyPassoword->HABIL_MODIFI;
				  				$sesion_data = array(
                                    'username' => $_POST['maillogin'],
                                    'password' => $_POST['passwordlogin'],
									'pin' => $_POST['pin']
                                        );
								$_SESSION["pin_ocho"] = $_POST['pin'];
								$_SESSION["usuarioTop22"] = $ttop;
								$_SESSION["habilMm"] = $ExisteUsuarioyPassoword->HABIL_MODIFI;	
								$_SESSION["coleSoloLec"] = $colegio->EMPR_SOLO_LEC;	
								$_SESSION["msgColeSoloLec"]=$colegio->EMPR_MSG_SOLOLEC;
								$_SESSION["host"]=$colegio->EMPR_IP;
								$_SESSION["database"]=$colegio->EMPR_BASE;
								$_SESSION["puerto"]=$colegio->EMPR_PUERTO;
								$_SESSION["mysql"]=$ExisteUsuarioyPassoword->HABIL_USUMYSQL;
								$_SESSION["passmysql"]=$ExisteUsuarioyPassoword->HABIL_PASSMYSQL;
								$_SESSION["denom"]=$colegio->EMPR_DENOM;
								$_SESSION["colegio_cp"]=$colegio->EMPR_CP;
								$_SESSION["empresa_cuit"]=$colegio->EMPR_CUIT;
								$_SESSION["testing"]=$colegio->EMPR_TESTING;
								$_SESSION["colegio_dir"]=$colegio->EMPR_DIR;
								$_SESSION["colegio_prov"]=$colegio->EMPR_PROV;
								$_SESSION["colegio_ciudad"]=$colegio->EMPR_CIUDAD;
								$_SESSION["id_login"]=$ExisteUsuarioyPassoword->HABIL_ID;
								$_SESSION["EMPR_ENVIOS"]=$colegio->EMPR_ENVIOS;
								$_SESSION["EMPR_VMF"]=$colegio->EMPR_VMF;
								$_SESSION["EMPR_ENVIOS"]=$colegio->EMPR_ENVIOS;
								$_SESSION["EMPR_ENVIOS_PDF_SUELDOS"]=$colegio->EMPR_ENVIOS_PDF_SUELDOS;
								$_SESSION["EMPR_ACEPTO_REC_SUELDOS"]=$colegio->EMPR_ACEPTO_REC_SUELDOS;
                    			$this->session->set_userdata($sesion_data);

                    			//LOGS
                    			$config['hostname'] = $_SESSION["host"];
								 $config['username'] = $_SESSION["mysql"];
								 $config['password'] = $_SESSION["passmysql"];
								 $config['database'] = $_SESSION["database"];
								 $config['dbdriver'] = "mysql";
								 $config['dbprefix'] = "";
								 $config['pconnect'] = TRUE;
								 $config['db_debug'] = TRUE;
								 $config['cache_on'] = FALSE;
								 $config['cachedir'] = "";
								 $config['char_set'] = "utf8";
								 $config['dbcollat'] = "utf8_general_ci"; 
								 $this->db = $this->load->database($config, TRUE); 
								$data_log = array();
								$data_log["EMPR_ULT_LOG_CAEM_U"] = $_POST['maillogin'];
								$data_log["EMPR_PIN"] = $_POST['pin'];

								$data_log["logs_ip"] = $this->Articulo_model->get_client_ip();
								$data_log["logs_usuario"] = $_POST['maillogin'];
								$data_log["logs_sistema"] = "caemasivo";
								$data_log["logs_tipo"] = "login";
								$data_log["logs_detalle"] = '';

								//inserto el ultimo log en la base del pin
								$this->Articulo_model->insertar_log($data_log);	

								//elimino los logs viejos para mantener el tamaño de las bases
								$fecha_limite_logs = date("Y-m-d", strtotime("-30 days"));
								$this->Articulo_model->elminar_logs_antiguos($fecha_limite_logs);

								//doy aviso de la ultima fecha de log en la tabla de empresas maestras
								$this->db = $this->load->database('default', TRUE); 
								$this->Articulo_model->actualizar_log_maestra($data_log);
								//FIN LOGS

								// $this->load->view('table',$data);
								if($idioma=='check'){
									header("location: ".site_url('articulos/login_check_ok'));
								}else{	
									header("location: ".site_url('articulos/escritorio'));
								}	
			 					// header("location: ".site_url('articulos/escritorio?user='.$_SESSION["mysql"].'&pass='.$_SESSION["passmysql"].'&host='.$_SESSION["host"].'&bd='.$_SESSION["database"]));
			   					//echo "Validacion Ok<br><br><a href=''>Volver</a>";   //   Si el usuario ingresó datos de acceso válido, imprimos un mensaje de validación exitosa en pantalla
							}else{
								//nadie puede ingresar al colegio
								$data['error']= $colegio->EMPR_MSG_NO_ABIERTO;
								$this->load->view('login',$data);
							}	
						}else{
							//caduco la fecha
							$data['error']= "La fecha ha caducado, consulte con el administrador del sistema";
							$this->load->view('login',$data);							
						}	
					}else{
						//no está habilitado colegio
						$data['error']= $colegio->EMPR_MSG_INHABIL;
						$this->load->view('login',$data);
					}		
			
              		
				  }else{
				  	//no está habilitado el cliente
					$data['error']="Momentaneamente no puede acceder al sistema por trabajos de mantenimiento.";
              		$this->load->view('login',$data);
				  }	
            }
            else{   //   Si no logró validar
               $data['error']="Datos incorrectos, por favor vuelva a intentar";
               $this->load->view('login',$data);   //   Lo regresamos a la pantalla de login y pasamos como parámetro el mensaje de error a presentar en pantalla
            }
         }
      }
   }//ffcion


   function login_check_ok(){
		$data = array();
   		$this->load->view('login_check_ok',$data);
   }//ffcion
   
   
   
   
   
   
   function login_test54kmeio($idioma=null)
   {
       $maco = "";  
	   $data = "";
      //   $this->config->set_item('language', 'spanish');      //   Setear dinámicamente el idioma que deseamos que ejecute nuestra aplicación
      if(!isset($_POST['maillogin'])){   //   Si no recibimos ningún valor proveniente del formulario, significa que el usuario recién ingresa.   
         $this->load->view('login_backup', $maco);      //   Por lo tanto le presentamos la pantalla del formulario de ingreso. 
      }
      else{                        //   Si el usuario ya pasó por la pantalla inicial y presionó el botón "Ingresar"
         //$this->form_validation->set_rules('maillogin','e-mail','required|valid_email');      //   Configuramos las validaciones ayudandonos con la librería form_validation del Framework Codeigniter
         $this->form_validation->set_rules('passwordlogin','password','required');
         if(($this->form_validation->run()==FALSE)){            //   Verificamos si el usuario superó la validación
		    $maco["error"]="Datos incorrectos";
            $this->load->view('login_backup', $maco);                     //   En caso que no, volvemos a presentar la pantalla de login
         }
         else{                                       //   Si ambos campos fueron correctamente rellanados por el usuario,
            $this->load->model('Articulo_model');
            $ExisteUsuarioyPassoword=$this->Articulo_model->ValidarUsuario($_POST['maillogin'],$_POST['passwordlogin'],$_POST['pin']);   //   comprobamos que el usuario exista en la base de datos y la password ingresada sea correcta
            if($ExisteUsuarioyPassoword){   // La variable $ExisteUsuarioyPassoword recibe valor TRUE si el usuario existe y FALSE en caso que no. Este valor lo determina el modelo.
				
				  //compruebo que esté habilitado el cliente
				  $estaHabilitado = $this->Articulo_model->Habilitado($_POST['maillogin'],$_POST['passwordlogin'],$_POST['pin']);
				  if($estaHabilitado) {
				  	//compruebo si el colegio se encuentra habilitado
					$colegio = $this->Articulo_model->eHabilitada($_POST['pin']);
					if($colegio->EMPR_HABIL=="S") {
						//compruebo que la fecha no haya caducado
						if($colegio->EMPR_HASTA>=date("Y-m-d")){
							//compruebo si pueden ingresar al colegio
							if($colegio->EMPR_ABIERTO=="S") {
								$ttop = $ExisteUsuarioyPassoword->HABIL_APEYNOM;
								$habilMm = $ExisteUsuarioyPassoword->HABIL_MODIFI;
				  				$sesion_data = array(
                                    'username' => $_POST['maillogin'],
                                    'password' => $_POST['passwordlogin'],
									'pin' => $_POST['pin']
                                        );
								$_SESSION["usuarioTop22"] = $ttop;
								$_SESSION["habilMm"] = $ExisteUsuarioyPassoword->HABIL_MODIFI;	
								$_SESSION["coleSoloLec"] = $colegio->EMPR_SOLO_LEC;	
								$_SESSION["msgColeSoloLec"]=$colegio->EMPR_MSG_SOLOLEC;
								$_SESSION["host"]=$colegio->EMPR_IP;
								$_SESSION["database"]=$colegio->EMPR_BASE;
								$_SESSION["puerto"]=$colegio->EMPR_PUERTO;
								$_SESSION["mysql"]=$ExisteUsuarioyPassoword->HABIL_USUMYSQL;
								$_SESSION["passmysql"]=$ExisteUsuarioyPassoword->HABIL_PASSMYSQL;
								$_SESSION["denom"]=$colegio->EMPR_DENOM;
								$_SESSION["colegio_cp"]=$colegio->EMPR_CP;
								$_SESSION["empresa_cuit"]=$colegio->EMPR_CUIT;
								$_SESSION["testing"]=$colegio->EMPR_TESTING;
								$_SESSION["colegio_dir"]=$colegio->EMPR_DIR;
								$_SESSION["colegio_prov"]=$colegio->EMPR_PROV;
								$_SESSION["colegio_ciudad"]=$colegio->EMPR_CIUDAD;
								$_SESSION["id_login"]=$ExisteUsuarioyPassoword->HABIL_ID;
								$_SESSION["EMPR_ENVIOS"]=$colegio->EMPR_ENVIOS;
								$_SESSION["EMPR_VMF"]=$colegio->EMPR_VMF;
                    			$this->session->set_userdata($sesion_data);
								// $this->load->view('table',$data);
								header("location: ".site_url('articulos/escritoriotest'));
			 					// header("location: ".site_url('articulos/escritorio?user='.$_SESSION["mysql"].'&pass='.$_SESSION["passmysql"].'&host='.$_SESSION["host"].'&bd='.$_SESSION["database"]));
			   					//echo "Validacion Ok<br><br><a href=''>Volver</a>";   //   Si el usuario ingresó datos de acceso válido, imprimos un mensaje de validación exitosa en pantalla
							}else{
								//nadie puede ingresar al colegio
								$data['error']= $colegio->EMPR_MSG_NO_ABIERTO;
								$this->load->view('login_backup',$data);
							}	
						}else{
							//caduco la fecha
							$data['error']= "La fecha ha caducado, consulte con el administrador del sistema";
							$this->load->view('login_backup',$data);							
						}	
					}else{
						//no está habilitado colegio
						$data['error']= $colegio->EMPR_MSG_INHABIL;
						$this->load->view('login_backup',$data);
					}		
			
              		
				  }else{
				  	//no está habilitado el cliente
					$data['error']="Momentaneamente no puede acceder al sistema por trabajos de mantenimiento.";
              		$this->load->view('login_backup',$data);
				  }	
            }
            else{   //   Si no logró validar
               $data['error']="Datos incorrectos, por favor vuelva a intentar";
               $this->load->view('login_backup',$data);   //   Lo regresamos a la pantalla de login y pasamos como parámetro el mensaje de error a presentar en pantalla
            }
         }
      }
   }//ffcion
   
   
   
   
   
   
   
   
   
    function olvido($idioma=null){
	 $data["error"] = "Ingrese los datos para recuperar la contraseña.";
      //   $this->config->set_item('language', 'spanish');      //   Setear dinámicamente el idioma que deseamos que ejecute nuestra aplicación
      if(!isset($_POST['maillogin'])){   //   Si no recibimos ningún valor proveniente del formulario, significa que el usuario recién ingresa.   
         $this->load->view('olvido', $data);      //   Por lo tanto le presentamos la pantalla del formulario de ingreso. 
      }
      else{                        //   Si el usuario ya pasó por la pantalla inicial y presionó el botón "Ingresar"
         //$this->form_validation->set_rules('maillogin','e-mail','required|valid_email');      //   Configuramos las validaciones ayudandonos con la librería form_validation del Framework Codeigniter
		 $this->form_validation->set_rules('maillogin','e-mail','required|valid_email');
         $this->form_validation->set_rules('pin','password','required');
         if(($this->form_validation->run()==FALSE)){            //   Verificamos si el usuario superó la validación
		    $data["error"]="Por favor, llene los campos correctamente";
            $this->load->view('olvido', $data);                     //   En caso que no, volvemos a presentar la pantalla de login
         }
         else{                                       //   Si ambos campos fueron correctamente rellanados por el usuario,
            $this->load->model('Articulo_model');
            $existeRecupera=$this->Articulo_model->validarRecupera($_POST['maillogin'],$_POST['pin']);  
			if($existeRecupera){
				//existe mando un mail
				$this->load->library('email');
				$config['mailtype'] = "html";
				$this->email->initialize($config);
				$this->email->from('soporte@sql-system.com.ar', 'SQL System');
				$this->email->to($_POST['maillogin']); 
				//$this->email->cc('otro@otro-ejemplo.com'); 
				//$this->email->bcc('ellos@su-ejemplo.com'); 

				$this->email->subject('Tus datos de usuario');
				$this->email->message('Estimado '.$existeRecupera->HABIL_APEYNOM.',<br> Tus datos de acceso son: <br>Usuario: '.$existeRecupera->HABIL_USU.'<br>PIN: '.$existeRecupera->HABIL_PIN.'<br>Contrase&ntilde;a: '.$existeRecupera->HABIL_PASS.'<br><br>atte.<br>SQL SYSTEM');	

				if(!($this->email->send())){
					$data["error"]="Ha ocurrido un problema al enviar el e-mail. Por favor intente en un instante nuevamente.";
				}else{
					$data["error"]="Se ha enviado un e-mail con sus datos de acceso.";
				}
				$this->load->view('olvido', $data); 

			}else{
				//no existe
				$data["error"]="Los datos ingresados son incorrectos.";
				$this->load->view('olvido', $data); 
			}//existe
		 }//datos vacios	   
	  
	  }	//post
	}//fcion


   function esta_logueado(){
		$logged = $this->login_model->isLogged();
	    if(!$logged){
			echo "no";
		}else{
			echo "si";
		}    	
   }//ffcion
   
   
   function logout($idioma=NULL)
   {
   		/*if(($idioma == '') || ($idioma == NULL)){
			header("Location: https://caemasivo.com/mantenimiento.php");
			exit();
		}*/
   		$this->session->sess_destroy();
		$data["error"] = "Ha salido correctamente.";
		$this->load->view('login',$data); 
		
   }//fcion
   
   
   
   

   
   
   
   
   
    

   
   
   
}


?>