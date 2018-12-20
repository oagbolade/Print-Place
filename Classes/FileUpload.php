<?php
	 
	/**
	 * Author: Oyekunle Opeyemi
	   Date: May 29th, 2018
	 */
	class FileUpload
	{
		public $file;
		public $fileDirectory;
		public $extensions;
		public $size;
		public $newFileName;
		public $count;
		
		function __construct($file, $fileDirectory, $count, $newFileName = "")
		{
			$this->file = $file;
			$this->count = $count;
			$this->fileDirectory = $fileDirectory;
			if (!$newFileName) {
				$this->newFileName = time();
			}else{
				$this->newFileName = $newFileName;
			}
		}
		function setFileName($newFileName='')
		{
			if (!$newFileName) {
				$this->newFileName = time();
			}else{
				$this->newFileName = $newFileName;
			}
		}
		function getFileName()
		{
			return $this->newFileName;
		}
		function filepath()
		{	
			//the final path of the file
			$filePath = $this->fileDirectory.$this->newFileName.".".$this->getFileExtension();
			return $filePath;
		}
		function originalFilepath()
		{
			$baseName = basename($_FILES[$this->file]["name"][$this->count]);

			//the final path of the file
			$filePath = $this->fileDirectory.$baseName;
			return $filePath;
		}
		function uploadFile()
		{
			$filePath = $this->filepath();
	   		// move the file from the temporary location to the final location
	   		move_uploaded_file($_FILES[$this->file]["tmp_name"][$this->count], $filePath);

		}	
		function uploadFileWithValidation()
		{
			$filePath = $this->filepath();
			if (in_array("error", $this->validateFile())) {
				return $this->validateFile();
			}else{
		   		// move the file from the temporary location to the final location
		   		if (move_uploaded_file($_FILES[$this->file]["tmp_name"][$this->count], $filePath)) {
					return "Uploaded";
		   		}else{
		   			return "Not Uploaded";
		   		}
			}
		}	
		function getFileExtension()
		{
			$filePath = $this->originalFilePath();
			// the file type(extension)
			$imageFileType = pathinfo($filePath,PATHINFO_EXTENSION);
			return $imageFileType;
		}
		function setFileExtensionsAccepted($extensions)
		{
			$this->extensions = $extensions;
			if (is_array($this->extensions)) {
				return $this->extensions;
			}
			return false;
		}
		function checkFileAcceptance()
		{
			$result = false;
			if ($this->getFileExtension()) {
				if ($this->setFileExtensionsAccepted($this->extensions)) {
					$fileType =$this-> getFileExtension();
					$acceptedExtensions = $this->setFileExtensionsAccepted($this->extensions);
					$result = true;
				}
			}
			if ($result) {
				//returns true if file type is in the array of accepted file types
				$fileExtensionStatus = in_array($fileType, $acceptedExtensions); 
				return $fileExtensionStatus;	
			}
		}

		function checkFileExistence()
		{
			$filePath = $this->filepath();
			
			//returns true if file exists and false if it doesn't	   		
			$fileExistence = file_exists($filePath);
			return $fileExistence;
		}

		function getImageWidthHeight($dimension)
		{
			//array of info about the file
	   		$fileInfo = getimagesize($_FILES[$this->file]["tmp_name"][$this->count]); 
	   		//the file width
	   		$passportWidth = $fileInfo[0]; 
	   		
	   		//the file height
	   		$passportHeight = $fileInfo[1]; 
	   		if (strtolower($dimension) === "width") {
	   			return $passportWidth;
	   		}elseif(strtolower($dimension) === "height"){
	   			return $passportHeight;
	   		}elseif (strtolower($dimension) === "both") {
	   			$dimensionList = array(
	   					"width"=>$passportWidth, 
	   					"height"=>$passportHeight
	   				);
	   			return $dimensionList;
	   		}else{
	   			return false;
	   		}
		}
		function getFileSize()
		{
			//the passport size
			$fileSize = $_FILES[$this->file]["size"][$this->count]/1000;
			return $fileSize;	
		}
		function checkFileSize($size)
		{
			$this->size = $size;
			if ($this->size > $this->getFileSize()) {
				return true;
			}else{
				return false;
			}
		}
		function validateFile()
		{
			$fileStatus = array();
			if ($this->checkFileSize($this->size)) {
				$fileStatus["size"] = "File size okay to use";
			}else{
				$fileStatus["size"] = "File size too large";
				$fileStatus["error"] = "error";
			}
			if (!$this->checkFileExistence()) {
				$fileStatus["file_duplicate"] = "New file not a duplicate";
			}else{
				$fileStatus["file_duplicate"] = "File already exist";	
				$fileStatus["error"] = "error";
			}
			if ($this->checkFileAcceptance()) {
				$fileStatus["extension"] = "File type accepted";
			}else{
				$fileStatus["extension"] = "File type not accepted";
				$fileStatus["error"] = "error";
			}
			return $fileStatus;

		}
	}
