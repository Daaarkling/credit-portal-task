<?php
declare(strict_types=1);


namespace App\Model\Thread;

use Kdyby\Doctrine\EntityManager;
use Kdyby\Doctrine\EntityRepository;
use Nette\SmartObject;
use Ramsey\Uuid\Uuid;

class DoctrineThreadRepository implements IThreadRepository
{
	use SmartObject;

	/** @var EntityManager */
	private $em;

	/** @var EntityRepository */
	private $repository;


	public function __construct(EntityManager $em)
	{
		$this->em = $em;
		$this->repository = $this->em->getRepository(Thread::class);
	}


	public function findAll(): array
	{
		return $this->repository->findBy([], ['name' => 'ASC']);
	}


	public function getById(Uuid $id): Thread
	{
		$entity = $this->repository->find($id);
		if ($entity === null) {
			throw new ThreadNotFoundException('Thread: ' . $id->toString());
		}
		return $entity;
	}

	public function getDataSource()
	{
		return $this->repository->createQueryBuilder('qb');
	}


	public function getBySlug(string $slug): Thread
	{
		$entity = $this->repository->findOneBy(['slug' => $slug]);
		if ($entity === null) {
			throw new ThreadNotFoundException('Thread: ' . $slug);
		}
		return $entity;
	}
}