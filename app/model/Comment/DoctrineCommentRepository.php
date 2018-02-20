<?php
declare(strict_types=1);


namespace App\Model\Comment;

use Kdyby\Doctrine\EntityManager;
use Kdyby\Doctrine\EntityRepository;
use Nette\SmartObject;
use Ramsey\Uuid\Uuid;

class DoctrineCommentRepository implements ICommentRepository
{
	use SmartObject;

	/** @var EntityManager */
	private $em;

	/** @var EntityRepository */
	private $repository;


	public function __construct(EntityManager $em)
	{
		$this->em = $em;
		$this->repository = $this->em->getRepository(Comment::class);
	}


	public function getById(Uuid $id): Comment
	{
		$entity = $this->repository->find($id);
		if ($entity === null) {
			throw new CommentNotFoundException('Comment: ' . $id->toString());
		}
		return $entity;
	}

	public function findByThread(Uuid $threadId): array
	{
		return $this->repository->findBy(['thread.id' => $threadId], ['posted' => 'ASC']);
	}


}