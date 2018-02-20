<?php
declare(strict_types=1);

namespace App\Model\Db;


use Kdyby\Doctrine\EntityManager;

class TransactionManager
{
	/** @var EntityManager */
	private $em;


	public function __construct(EntityManager $em)
	{
		$this->em = $em;
	}


	public function beginTransaction(): void
	{
		$this->em->getConnection()->beginTransaction();
	}


	public function commit(): void
	{
		$this->em->getConnection()->commit();
	}


	public function rollback(): void
	{
		$this->em->getConnection()->rollBack();
	}

}