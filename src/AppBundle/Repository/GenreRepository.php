<?php

namespace AppBundle\Repository;
use Doctrine\ORM\QueryBuilder;

/**
 * GenreRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class GenreRepository extends \Doctrine\ORM\EntityRepository
{
	public function search($params = array(),$limit = 50,$offset=0){
		$qb = $this->createQueryBuilder("u");

		$params = array_filter($params,function($el){
			return strip_tags(trim($el));
		});

		// recherche par terms
		if(@$params["q"]){
			$this->whereTerms($qb,@$params["q"]);
		}

		// recherche par id
		if(@$params["id"]){
			$this->whereId($qb,@$params["id"]);
		}

		// recherche par with_program_category
		if(@$params["with_program_category"]){
			$this->whereWithProgramCategory($qb,@$params["with_program_category"]);
		}
		
		$qb->orderBy("u.id","DESC");

	    // limit et offset
	    $qb->setFirstResult( $offset )
   		->setMaxResults( $limit );

   		$query = $qb->getQuery();

	    return $query->getResult();
	}


	public function whereTerms(QueryBuilder $qb,$value){
		$qb->andWhere($qb->expr()->orX(
			$qb->expr()->like("u.slug", ":q")
		))
	    ->setParameter("q","%$value%");
	}

	public function whereId(QueryBuilder $qb,$value){
		$qb->andWhere($qb->expr()->eq("u.id", ":id"))
	    ->setParameter("id",$value);
	}

	public function whereWithProgramCategory(QueryBuilder $qb,$value){

		$qb2 = $this->_em->createQueryBuilder();

        $qb2->select("mg.id")
        ->from(\AppBundle\Entity\MovieGenre::class,"mg")
        ->innerJoin("mg.movie","m")
        ->innerJoin("mg.genre","g")
        ->innerJoin("m.sectionCategory","c")
        ->where($qb->expr()->andX(
        	"g.id = u.id",
        	"c.slug = :with_program_category"
        ));
       
        $qb->andWhere(
      		$qb->expr()->exists($qb2)
      	)
      	->setParameter("with_program_category",$value);
	}


	public function count(){
		return $this->createQueryBuilder('u')
        ->select('count(u.id)')
        ->getQuery()
        ->getSingleScalarResult();
	}
}
