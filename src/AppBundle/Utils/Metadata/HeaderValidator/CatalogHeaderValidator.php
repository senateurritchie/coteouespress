<?php
namespace AppBundle\Utils\Metadata\HeaderValidator;
use AppBundle\Utils\Metadata\HeaderValidator\HeaderValidator;

class CatalogHeaderValidator extends HeaderValidator{
	
	public function __construct(){
		$fields = [
			"Cle_unique","DetailCatalogue","TitreVO","TitreExploitation","Categorie","Mention",
			"Format","Durée","NombreEpisodes","Producteur","Section","Genre","OrigineProduction",
			"AnneeProduction","Realisateur","Synopsis_fr","tagline_fr","logline_fr","Synopsis_en",
			"tagline_en","logline_en","Synopsis_arabe","tagline_ar","logline_ar","Casting",
			"Recompenses","Audience","PrixNomination","PlusInfos","@adresseImages","Langue",
			"Version","Territoire","FULL","Trailer","Extrait","Ep1","Ep2","Ep3","liens","FSA","ESA",
			"VOD","WORLDWIDE","ARABOPHONE","LUSOPHONE","CLOSED","CIRCUIT","SHORT","FORMAT","DOM","TOM",
			"EDAN","PAY","TV","CINEMA"
		];
		
		parent::__construct($fields);
	}
}

