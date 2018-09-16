<?php
namespace AppBundle\Utils\Metadata\HeaderValidator;
use AppBundle\Utils\Metadata\HeaderValidator\HeaderValidator;

class WebmasterHeaderValidator extends HeaderValidator{
	
	public function __construct(){
		$fields = [
		"Name","OriginalName","Category","Language","EpisodeNbr","Duration","Mention","Year","Trailer","Episodes","Synopsis","Synopsis_en","Synopsis_ar","Tagline","Tagline_en","Tagline_ar","Logline","Logline_en","Logline_ar","Rewards","Awards","Audiences","Versions","Genres","Countries","Casting","Producers","Directors","InTheather","Exclusivity","Published","@CoverImg","@LandscapeImg","@PortraitImg","@Gallery","Catalogues","Section","Section Category"];
		
		parent::__construct($fields);
	}
}