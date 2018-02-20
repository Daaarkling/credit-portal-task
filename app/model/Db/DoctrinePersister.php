<?php
declare(strict_types=1);

namespace App\Model\Db;

use Kdyby\Doctrine\EntityManager;

class DoctrinePersister implements IPersister
{
	/** @var EntityManager */
	private $em;


	public function __construct(EntityManager $em)
	{
		$this->em = $em;
	}


	public function persist($object)
	{
		$this->em->persist($object)->flush();
		return $object;
	}


	public function remove($object): void
	{
		$this->em->remove($object)->flush();
	}

}