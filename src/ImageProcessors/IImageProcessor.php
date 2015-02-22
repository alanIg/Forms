<?php

namespace TomasVodrazka\Forms\ImageProcessors;

/**
 *
 * @author TomU
 */
interface IImageProcessor {

	public function process(\Nette\Image $image, $id, $name);

	public function delete($name);
	
	public function createFileName($id, $name);

	public function getPattern($name);
	
	public function makesThumbnails();
}

?>
