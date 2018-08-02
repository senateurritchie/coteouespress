<?php

namespace AppBundle\Repository;

use Doctrine\ORM\QueryBuilder;

use AppBundle\Entity\Movie;
use AppBundle\Entity\MovieGenre;
use AppBundle\Entity\MovieLanguage;
use AppBundle\Entity\MovieCreator;
use AppBundle\Entity\MovieDirector;
use AppBundle\Entity\MovieProducer;
use AppBundle\Entity\MovieCountry;
use AppBundle\Entity\Language;

/**
 * MovieRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MovieRepository extends \Doctrine\ORM\EntityRepository
{
	public function search($params = array(),$limit = 20,$offset=0){
		$qb = $this->_em->createQueryBuilder();

		if(@$params["q"]){
    		$params["name"] = $params["q"];
    	}

		$params = array_filter($params,function($el){
			return strip_tags(trim($el));
		});

		$qb->select("m")
		->from(Movie::class,"m")
		->leftJoin("m.category","category")
		->addSelect("category");

        // recherche par id
        if(@$params["id"]){
            $this->whereId($qb,@$params["id"]);
        }

		// recherche par terms
		if(@$params["name"]){
			$this->whereTerms($qb,@$params["name"]);
		}

		// recherche par mention
		if(@$params["mention"]){
			$this->whereMention($qb,@$params["mention"]);
		}

		// recherche par category
		if(@$params["category"]){
			$this->whereCategory($qb,@$params["category"]);
		}

		// recherche par genre
		if(@$params["genre"]){
			$this->whereGenre($qb,@$params["genre"]);
		}

		// recherche par language
		if(@$params["language"]){
			$this->whereLanguage($qb,@$params["language"],@$params["locale"]);
		}

		// recherche par createur
		if(@$params["creator"]){
			$this->whereCreator($qb,@$params["creator"]);
		}

		// recherche par director
		if(@$params["director"]){
			$this->whereDirector($qb,@$params["director"]);
		}

		// recherche par producteur
		if(@$params["producer"]){
			$this->whereProducer($qb,@$params["producer"]);
		}

		// recherche par pays
		if(@$params["country"]){
			$this->whereCountry($qb,@$params["country"]);
		}

		// recherche par année
		if(@$params["year"]){
			$this->whereYear($qb,@$params["year"]);
		}

        // ordre d'affichage par id
        if(@$params['order_id']){
            $order = strtoupper(trim($params['order_id'])) == "ASC" ? "ASC" : "DESC";
            $qb->orderBy("m.id",$order);
        }

		if(!@$params['order_id'] && !@$params["order_name"]){
    		$params["order_name"] = "asc";
    	}


		// ordre d'affichage par nom de programme
		if(@$params['order_name']){
			$order = strtoupper(trim($params['order_name'])) == "ASC" ? "ASC" : "DESC";
			$qb->orderBy("m.name",$order);
		}

		// ordre d'affichage par date e production
		if(@$params['order_year']){
			$order = strtoupper(trim($params['order_year'])) == "ASC" ? "ASC" : "DESC";
			$qb->orderBy("m.yearStart",$order);
		}

	    // limit et offset
	    $qb->setFirstResult( $offset )
   		->setMaxResults( $limit );

   		$query = $qb->getQuery();

        $query->setHint(
            \Doctrine\ORM\Query::HINT_CUSTOM_OUTPUT_WALKER,
            'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker'
        );


   		/*if(@$params["language"]){
			$query->setHint(
    			\Doctrine\ORM\Query::HINT_CUSTOM_OUTPUT_WALKER,
    			'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker'
			);
		}*/

	    return $query->getResult();
	}

    public function whereId(QueryBuilder $qb,$value){
        $qb->andWhere($qb->expr()->eq("m.id",":id"))
        ->setParameter("id",$value);
    }

	public function whereTerms(QueryBuilder $qb,$value){

		$qb->andWhere($qb->expr()->orX(
			"(MATCH_AGAINST(m.slug,m.synopsis, :terms_1) > 0",
			"m.slug LIKE :terms_2)"
		))
	    ->setParameter("terms_1",$value)
	    ->setParameter("terms_2","%$value%");
	}

	public function whereYear(QueryBuilder $qb,$value,$end=null){

		if($end){
			$qb->andWhere(
				$qb->expr()->orX(
					$qb->expr()->eq("m.yearStart",":year"),
					$qb->expr()->between("m.yearStart",":year",":yearEnd")
				)
			)
			->setParameter("yearEnd",$end);
		}
		else{
			$qb->andWhere(
				$qb->expr()->eq("m.yearStart",":year")
			);
		}

		$value = substr($value,0,4)."-01-01";
		$value = new \Datetime($value);
		$qb->setParameter("year",$value->format("Y-m-d H:i:s"));
  	}

  	public function whereMention(QueryBuilder $qb,$value){
      	$qb->andWhere("m.mention = :mention")
		->setParameter("mention",$value);
  	}

	public function whereCategory(QueryBuilder $qb,$value){

        /*$qb->leftJoin("m.category","category")
        ->addSelect("category")*/
        $qb->andWhere("category.slug = :category")
		->setParameter("category",$value);
  	}

  	public function whereGenre(QueryBuilder $qb,$value){
  		$qb2 = $this->_em->createQueryBuilder();

      	$qb->andWhere(
      		$qb->expr()->exists(
      			$qb2->select("x.id")
      			->from(MovieGenre::class,"x")
      			->innerJoin("x.movie","xm")
      			->innerJoin("x.genre","xg")
      			->where("m.id = xm.id")
      			->andWhere("xg.slug = :genre")
      		)
      	)
      	->setParameter("genre",$value);
  	}

  	public function whereLanguage(QueryBuilder $qb,$value,$locale="en"){
  		$qb2 = $this->_em->createQueryBuilder();

      	$qb->andWhere(
      		$qb->expr()->exists(
      			$qb2->select("ml.id")
      			->from(MovieLanguage::class,"ml")
      			->innerJoin("ml.movie","mlm")
      			->innerJoin("ml.language","mll")
      			->where("m.id = mlm.id")
      			->andWhere("mll.slug = :language")
      		)
      	)
      	->setParameter("language",$value);
  	}

  	public function whereCreator(QueryBuilder $qb,$value){
  		$qb2 = $this->_em->createQueryBuilder();

      	$qb->andWhere(
      		$qb->expr()->exists(
      			$qb2->select("x2.id")
      			->from(MovieCreator::class,"x2")
      			->innerJoin("x2.movie","xm2")
      			->innerJoin("x2.creator","xc2")
      			->where("m.id = xm2.id")
      			->andWhere("xc2.slug = :creator")
      		)
      	)
      	->setParameter("creator",$value);
  	}

  	public function whereDirector(QueryBuilder $qb,$value){
  		$qb2 = $this->_em->createQueryBuilder();

      	$qb->andWhere(
      		$qb->expr()->exists(
      			$qb2->select("x3.id")
      			->from(MovieDirector::class,"x3")
      			->innerJoin("x3.movie","xm3")
      			->innerJoin("x3.director","xc3")
      			->where("m.id = xm3.id")
      			->andWhere("xc3.slug = :director")
      		)
      	)
      	->setParameter("director",$value);
  	}

  	public function whereProducer(QueryBuilder $qb,$value){
  		$qb2 = $this->_em->createQueryBuilder();

      	$qb->andWhere(
      		$qb->expr()->exists(
      			$qb2->select("x4.id")
      			->from(MovieProducer::class,"x4")
      			->innerJoin("x4.movie","xm4")
      			->innerJoin("x4.producer","xc4")
      			->where("m.id = xm4.id")
      			->andWhere("xc4.slug = :producer")
      		)
      	)
      	->setParameter("producer",$value);
  	}

  	public function whereCountry(QueryBuilder $qb,$value){
  		$qb2 = $this->_em->createQueryBuilder();

      	$qb->andWhere(
      		$qb->expr()->exists(
      			$qb2->select("x5.id")
      			->from(MovieCountry::class,"x5")
      			->innerJoin("x5.movie","xm5")
      			->innerJoin("x5.country","xc5")
      			->where("m.id = xm5.id")
      			->andWhere("xc5.code = :country")
      		)
      	)
      	->setParameter("country",$value);
  	}

  	public function count(){
		return $this->createQueryBuilder('m')
        ->select('count(m.id)')
        ->getQuery()
        ->getSingleScalarResult();
	}
}
