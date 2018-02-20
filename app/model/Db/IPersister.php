<?php
declare(strict_types=1);

namespace App\Model\Db;


interface IPersister
{

	/**
	 * @param mixed $entity
	 * @return mixed
	 */
	public function persist($entity);

	/**
	 * @param mixed $entity
	 */
	public function remove($entity): void;
}