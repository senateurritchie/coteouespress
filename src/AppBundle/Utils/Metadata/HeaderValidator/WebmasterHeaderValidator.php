<?php
namespace AppBundle\Utils\Metadata\HeaderValidator;
use AppBundle\Utils\Metadata\HeaderValidator\HeaderValidator;

class WebmasterHeaderValidator extends HeaderValidator{
	
	public function __construct(){
		$fields = [
		"Name","Category","EpisodeNbr","Duration","Mention","Trailer","Episodes","Synopsis","Tagline","Logline","Rewards","Awards","Audiences","Languages","Genres","Countries","Casting","Producers","Directors","In Theather","Exclusivity","Published","@CoverImg","@LandscapeImg","@PortraitImg","@Gallery"];
		
		parent::__construct($fields);
	}
}