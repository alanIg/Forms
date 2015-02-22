<?php

namespace TomasVodrazka\Forms\ImageProcessors;

/**
 * Description of PhotoProcessor
 *
 * @author TomU
 */
class DefaultImageProcessor implements IImageProcessor {

	public $maxWidth;
	public $maxHeight;
	public $maxThumbWidth;
	public $maxThumbHeight;
	public $fullPath;
	public $publicPath;
	public $thumbPrefix;
	protected $patterns = array();

	function __construct($maxWidth, $maxHeight, $fullPath, $publicPath, $thumbPrefix = null, $maxThumbWidth = null, $maxThumbHeight = null) {
		$this->maxWidth = $maxWidth;
		$this->maxHeight = $maxHeight;
		$this->maxThumbWidth = $maxThumbWidth;
		$this->maxThumbHeight = $maxThumbHeight;
		$this->fullPath = $fullPath;
		$this->publicPath = $publicPath;
		$this->thumbPrefix = $thumbPrefix;
	}
	
	public function makesThumbnails(){
		return $this->thumbPrefix != null;
	}

	public function process(\Nette\Image $image, $id, $name) {
		$name = $this->createFileName($id, $name);
		if ($name == null) {
			throw new Exception('No pattern for this name');
		}
		try {
			$image->resize($this->maxWidth, $this->maxHeight, $this->getResizeStrategy());
			$image->save($this->fullPath . $name);
			if ($this->makesThumbnails()) {
				$image->resize($this->maxThumbWidth, $this->maxThumbHeight, $this->getResizeStrategy());
				$image->save($this->fullPath . $this->thumbPrefix . $name);
			}
		} catch (Exception $exc) {
			throw $exc;
		}
		return $name;
	}

	public function delete($fileName) {
		if ($fileName == '') {
			return false;
		}
		$this->deleteFile($fileName);
		if($this->makesThumbnails()){
			$this->deleteFile($this->thumbPrefix.$fileName);
		}
	}
	
	protected function deleteFile($fileName){
		if (is_file($this->fullPath . $fileName)) {
			unlink($this->fullPath . $fileName);
		}
	}

	protected function getResizeStrategy() {
		return \Nette\Image::FIT;
	}

	public function createFileName($id, $name) {
		$pattern = $this->getPattern($name);
		if ($pattern != null) {
			return preg_replace('#_ID_#', $id, $pattern);
		}
		return null;
	}

	public function getPattern($name) {
		if (array_key_exists($name, $this->patterns)) {
			return $this->patterns[$name];
		}
		return null;
	}

}

?>
