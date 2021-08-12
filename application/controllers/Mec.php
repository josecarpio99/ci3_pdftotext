<?php

// require_once APPPATH.'../pdfparser-master/alt_autoload.php-dist';
// require_once APPPATH.'../pdfparser-0.14.0/vendor/autoload.php';
// require_once APPPATH.'../pdfparser-0.14.0/alt_autoload.php';
require_once APPPATH.'../pdfparser/alt_autoload.php-dist';
set_time_limit(120);
/**
 *
 */
class Mec extends CI_Controller
{
	public $encabezados = [];
	public $mec_mec = [];
	public $mec_mec2 = [];

	function __construct()
	{
		parent:: __construct();
		$this->load->model('mec_model');
		$this->load->helper(array('form', 'url'));
	}

	public function index()
	{
		return $this->load->view('subir_pdf');
	}

	public function mostrar()
	{
		$mecanizadas = $this->mec_model->get_all();

		foreach ($mecanizadas as $key => $mec) {
			$mecanizadas[$key]->mecs_2 = $this->mec_model->mec_2($mec->mec_doc, $mec->mec_sec, $mec->mec_afec);
		}

		echo "<pre>";
		// var_dump($this->mec_model->mec_2('13296691'));
		var_dump($mecanizadas);
		echo "</pre>";
	}

	public function handle_upload_pdf()
	{
		//guardar pdf
		$config = [
			'upload_path'   => './uploads',
			'allowed_types' => 'pdf',
			'file_name'     => 'planilla_haberes.pdf'
		];

		$this->load->library('upload', $config);

		if(! $this->upload->do_upload('pdf_file'))
		{
			$error = array('error' => $this->upload->display_errors());

			$this->load->view('subir_pdf', $error);
		}
		$file = APPPATH.'/../uploads/planilla_haberes.pdf';

		$parser = new \Smalot\PdfParser\Parser();

		$pdfParse = $parser->parseFile($file);

		$pages  = $pdfParse->getPages();
		$pages = array_slice($pages, 0, count($pages) - 2);

		if (empty($pages)) {
			echo "chalee";
		}

		$this->mec_model->truncateTablesIfNotEmpty();

		foreach ($pages as $key => $page) {
			$lineNumber = 1;
			$isSectionTable = false;
			$isSectionFooter = false;
			$lineTable = 1;
			$suple = false;
			$mec = [];
			$mec2 = [];


			file_put_contents('text.txt', $page->getText());
			$content = fopen("text.txt", "r");
			while (($line = fgets($content)) !== false) {
				$lineTrim = trim($line);
				$lineArray = explode(' ', $line);

				if($lineTrim === '') continue;
				echo $line.'<br>';
				/***
				Si es la primera pagina extraemos los valores de encanbezado, como: org, tipo de org, instituto, etc...
				**/
				if($key === 0) {
					// Validar formato del pdf
					if($lineNumber == 1) {
						$strToMatch = 'PROVINCIA DE BUENOS AIRES';
						if(strpos($lineTrim, $strToMatch) === false) {
							unlink($file);
							return $this->load->view('subir_pdf', ['error' => 'El pdf no es valido']);
							exit();
						}
					}
					// Fecha de planilla
					if($lineNumber == 4) $this->encabezados['mec_fecha_planilla'] = $lineTrim;

					// Nro Pesos
					if($lineNumber == 6) {
						// substr($lineTrim, 17, 35); Concepto B
						$lineArray = $this->removeEmptyValues($lineArray);
						$this->encabezados['mec_nro_pesos'] = $lineArray[2];

					}

					if($lineNumber == 7) {
						//Distrito num
						$this->encabezados['mec_distrito_nro'] = str_replace(':', '', $lineArray[1]);
						//Distrito dem
						$this->encabezados['mec_distrito_denom'] = trim(substr($lineTrim, 15, 20));
						//Org num
						$this->encabezados['mec_tiporg_nro'] = trim(substr($lineTrim, 45, 2));
						//Org denom
						$this->encabezados['mec_tiporg_denom'] = trim(substr($lineTrim, 47, 32));
						//Instituto num
						$this->encabezados['mec_nroinst'] = trim(substr($lineTrim, 89, 5));
						//Instituto denom
						$this->encabezados['mec_inst_denom'] = trim(substr($lineTrim, 94));

					}

					if($lineNumber == 8) {
						$lineArray = $this->removeEmptyValues($lineArray);

						$this->encabezados['mec_rural'] = $lineArray[1];

						$this->encabezados['mec_seccs'] = $lineArray[3];

						$this->encabezados['mec_turnos'] = $lineArray[5];

						$this->encabezados['mec_subvencion'] = $lineArray[7].trim($lineArray[8]);
					}
				}

				// Empieza el proceso de obtencion de datos de las tabla
				if($lineNumber === 14) $isSectionTable = true;

				if($isSectionTable) {
					//Final de seccion, guardar mec y mec2
					if (strpos($line, '*****') !== false ) {
						$this->mec_model->addMec($this->encabezados, $mec);
						$this->mec_model->addMec2($mec2,
							[
								'mec_mec_doc' => $mec['mec_doc'],
								'mec_mec_sec'=> $mec['mec_sec'],
								'mec_mec_afec'=> $mec['mec_afec'],
							]
						);
						$lineTable = 1;
						$suple = false;
						$mec = [];
						$mec2 = [];
						continue;
					}

					if($lineTable == 1) {
						// Get mec_cod, persona, rev, fun
						$lineArray = $this->removeEmptyValues($lineArray);
						$docSec = explode('/', $lineArray[0]);
						// Si es final de pagina
						if(!isset($docSec[1]) || intval($docSec[0]) == 0 ) {
							// Si es final de la ultima página
							if($key == count($pages) - 1) {
								$isSectionFooter = true;
								$isSectionTable = false;
								$lineArray = $this->removeEmptyValues($lineArray);
								trim(end($lineArray)).'<br>'; //docentes
							}
							continue;
						}
						$mec['mec_doc'] = $docSec[0];
						$mec['mec_sec'] = $docSec[1];

						$mec['mec_persona'] = trim(substr($line, 14, 23));
						$mec['mec_rev'] = substr($line, 37, 1);
						$mec['mec_fun'] = substr($line, 39, 1);
						$mec['mec_neto'] = trim(substr($line, 99, 12));
					}

					if($lineTable == 2) {
						// Extreamos mec_afec, mec_categ, mec_hscs(si existe)
						$mec['mec_afec'] = trim(substr($line, 6, 8));
						$mec['mec_categ'] = trim(substr($line, 21, 12));
						$mec['mec_hscs'] = trim(substr($line, 33, 9));
					}

					if($lineTable == 3) {
						// Extraemos mec_antig
						$mec['mec_antig'] = trim(substr($line, 5, 10));

					}

					if ($lineTable == 4) {
						if(strpos($line, 'SUPLE') !== false) {
							$suple = true;
						//extraemos mec_suple_doc, mec_suple_sec
							$docSec = explode('/',trim(substr($line, 9, 33)));
							$mec['mec_suple_doc'] = $docSec[0];
							$mec['mec_suple_sec'] = $docSec[1];
						}
					}

					if ($lineTable == 5 && $suple) {
				 		//extremos mec_suple_desde, mec_suple_hasta
						$string = $this->removeExtraSpace(substr($line, 0, 42));
						$fechaDesdeHasta = explode(' ', $string);
						$mec['mec_suple_desde'] = $fechaDesdeHasta[0];
						$mec['mec_suple_hasta'] = $fechaDesdeHasta[1];
					}

					// echo substr($line, 66, 12).'<br>';
					// echo floatval($this->removeSpace(substr($line, 66, 12))).'<br>';
					array_push($mec2, [
						'mec2_cod' => trim(substr($line, 42, 6)),
						'mec2_denom' => trim(substr($line, 48, 18)),
						'mec2_importe' => floatval($this->removeSpace(substr($line, 66, 12))),
						'mec2_importe2' => floatval($this->removeSpace(substr($line, 84, 12))),
					]);

					$lineTable++;
				}

				if($isSectionFooter) {
					if($lineTable == 1) {
						$lineArray = $this->removeEmptyValues($lineArray);
						trim(end($lineArray)).'<br>'; //c/aportes
					}
					if($lineTable == 2) {
						$lineArray = $this->removeEmptyValues($lineArray);
						trim(end($lineArray)).'<br>'; //s/aportes
					}
					if($lineTable == 3) {
						$lineArray = $this->removeEmptyValues($lineArray);
						trim(end($lineArray)).'<br>'; //SALARIO FAMILIAR
					}
					if($lineTable == 4) {
						$lineArray = $this->removeEmptyValues($lineArray);
						trim(end($lineArray)).'<br>'; //descuentos
					}
					$lineTable++;
				}

				$lineNumber++;

			}

			fclose($content);


		}

		unlink($file);

	}

	protected function removeEmptyValues($array)
	{
		$array = array_filter($array, function($var){
			return $var !== '';
		});

		return array_values($array);
	}

	protected function removeSpace($str)
	{
		return str_replace(' ', '', $str);
	}

	protected function removeExtraSpace($str)
	{
		return preg_replace('/( )+/', ' ', trim($str));
	}
}