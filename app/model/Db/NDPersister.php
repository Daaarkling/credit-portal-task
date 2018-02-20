<?php
declare(strict_types=1);

namespace App\Model\Db;


use Nette\Database\Context;
use Ramsey\Uuid\Uuid;

class NDPersister implements IPersister
{
	/** @var Context */
	private $context;

	public function __construct(Context $context)
	{
		$this->context = $context;
	}


	public function persist($object)
	{
		$data = $object->toArray();
		if ($object->isNew()) {
			$uuid = Uuid::uuid4();
			$data['id'] = $uuid->toString();
			$object->setId($uuid);
		}
		$this->context->table($object::TABLE_NAME)->insert($data);
		return $object;
	}


	public function remove($object): void
	{
		$this->context->table($object::TABLE_NAME)
			->where('id', $object->getId()->toString())
			->delete();
	}

}