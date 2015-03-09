<?php
class UploaderPdfResposta extends UploaderPdf {

	public $idPrazo = 0;
	
	/**
	 * Retorna o valor de upload_max_filesize, do arquivo php.ini, em Mb
	 */
	private function getUploadMaxFilesize() {
		$setting = ini_get('upload_max_filesize');
		
		if (!is_numeric($setting)) {
			$type = strtoupper(substr($setting, -1));
			$setting = (integer) substr($setting, 0, -1);
			switch ($type) {
				case 'K' :
					return ($setting * 1024);
				break;
		
				case 'M' :
					return ($setting);
				break;
		
				case 'G' :
					return ($setting / 1024);
				break;
			}
		}
		return ($setting * 1024 * 1024); 
		
	}
	
	public function upload() {
		try {
			$tamUploadIni = $this->getUploadMaxFilesize();
			
			$arqTemp = $this->tmpName;
			
			if (empty($arqTemp)) {
				throw new Exception("O tamanho máximo do arquivo é $tamUploadIni Mb.");
			}
			
			if (!file_exists($arqTemp)) {
				throw new Exception("O tamanho máximo do arquivo é $tamUploadIni Mb.");
			}
			/*
			 * Essa validação é feita na class mãe
			$finfoTipo = finfo_open(FILEINFO_MIME_TYPE);
			$mimeType = finfo_file($finfoTipo, $arqTemp);
			finfo_close($finfo);
			if ($mimeType != 'application/pdf') {
				throw new Exception("Somente arquivos PDF são permitidos.");
			}
			*/
			$this->img_name = md5($this->img_name);
		
			if (!move_uploaded_file($this->tmpName, $this->uploadPath . $this->img_name . '.pdf')) {
				throw new Exception("Indefinido: {$this->tmpName}, {$this->uploadPath}, {$this->img_name}");
			}
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
		return $this;
	}
	
}