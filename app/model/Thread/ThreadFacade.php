<?php
declare(strict_types=1);


namespace App\Model\Thread;


use App\Model\ActionNotAllowedException;
use App\Model\Db\IPersister;
use Nette\Security\User;
use Nette\SmartObject;
use Ramsey\Uuid\Uuid;

class ThreadFacade implements IThreadFacade
{
	use SmartObject;

	/** @var IPersister */
	private $persister;

	/** @var User */
	private $user;

	/** @var IThreadRepository */
	private $threadRepository;


	public function __construct(
		IPersister $persister,
		User $user,
		IThreadRepository $threadRepository
	){
		$this->persister = $persister;
		$this->user = $user;
		$this->threadRepository = $threadRepository;
	}

	public function delete(Uuid $id): void
	{
		if (!$this->user->isInRole(\App\Model\User\User::ROLE_ADMIN)) {
			throw new ActionNotAllowedException();
		}

		$thread = $this->threadRepository->getById($id);
		//$this->persister->remove($thread);
		$thread->setDeleted(new \DateTime());
		$this->persister->persist($thread);
	}

	public function save(Thread $thread): Thread
	{
		try {
			if (!$this->user->isInRole(\App\Model\User\User::ROLE_ADMIN)) {
				throw new ActionNotAllowedException();
			}
			$this->persister->persist($thread);
			return $thread;
		} catch (\Nette\Database\UniqueConstraintViolationException | \Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
			throw new ThreadDuplicateSlugException();
		}
	}


}