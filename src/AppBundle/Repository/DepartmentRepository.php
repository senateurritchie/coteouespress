<?php

namespace AppBundle\Repository;
use Doctrine\ORM\QueryBuilder;

/**
 * DepartmentRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class DepartmentRepository extends \Doctrine\ORM\EntityRepository
{
	public function search($params = array(),$limit = 50,$offset=0){
		$qb = $this->createQueryBuilder("u");

		$params = array_filter($params,function($el){
            if(is_array($el)){
                return $el;
            }
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
		
		$qb->orderBy("u.createAt","DESC");

	    // limit et offset
	    $qb->setFirstResult( $offset )
   		->setMaxResults( $limit );

   		$query = $qb->getQuery();

	    return $query->getResult();
	}


	public function whereTerms(QueryBuilder $qb,$value){
		$qb->andWhere($qb->expr()->orX(
			$qb->expr()->like("u.slug", ":q"),
			$qb->expr()->like("u.email", ":q")
		))
	    ->setParameter("q","%$value%");
	}

	public function whereId(QueryBuilder $qb,$value){
		$qb->andWhere($qb->expr()->eq("u.id", ":id"))
	    ->setParameter("id",$value);
	}


	public function count(){
		return $this->createQueryBuilder('u')
        ->select('count(u.id)')
        ->getQuery()
        ->getSingleScalarResult();
	}
}
