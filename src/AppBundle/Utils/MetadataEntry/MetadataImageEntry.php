<?php
namespace AppBundle\Utils\MetadataEntry;

use AppBundle\Utils\MetadataEntry\MetadataEntry;

class MetadataImageEntry extends MetadataEntry{
	/**
	 * le mimeType de l'image
	 * @var string
	 */
	protected $mime;
	/**
	 * les données brutes de l'image
	 * @var string
	 */
	protected $raw;
	/**
	 * la largeur de l'image
	 * @var integer
	 */
	protected $width;
	/**
	 * la hauteur de l'image
	 * @var integer
	 */
	protected $height;
	/**
	 * determine si c'est une image carré
	 * @var boolean
	 */
	protected $square;
	/**
	 * determine si c'est une image en paysage
	 * @var boolean
	 */
	protected $landscape;
	/**
	 * determine si c'est une image en portrait
	 * @var boolean
	 */
	protected $portrait;
	
	public function __construct(array $data){ 
		$this->raw = $data['raw'];
		$this->width = $data['width'];
		$this->height = $data['height'];
		$this->mime = $data['mime'];
		$this->square = $data['square'];
		$this->landscape = $data['landscape'];
		$this->portrait = $data['portrait'];
	}

	public function getRaw(){
		return $this->raw;
	}
	public function getWidth(){
		return $this->width;
	}
	public function getHeight(){
		return $this->height;
	}
	public function isSquare(){
		return $this->square;
	}
	public function isLandscape(){
		return $this->landscape;
	}
	public function isPortrait(){
		return $this->portrait;
	}
}