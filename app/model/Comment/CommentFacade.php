<?php
declare(strict_types=1);


namespace App\Model\Comment;


use App\Model\ActionNotAllowedException;
use App\Model\Db\IPersister;
use Nette\Security\User;
use Nette\SmartObject;
use Ramsey\Uuid\Uuid;

class CommentFacade implements ICommentFacade
{
	use SmartObject;

	/** @var IPersister */
	private $persister;

	/** @var User */
	private $user;

	/** @var ICommentRepository */
	private $commentRepository;


	public function __construct(
		IPersister $persister,
		User $user,
		ICommentRepository $commentRepository
	){
		$this->persister = $persister;
		$this->user = $user;
		$this->commentRepository = $commentRepository;
	}

	public function delete(Uuid $id): void
	{
		if (!$this->user->isLoggedIn()) {
			throw new ActionNotAllowedException();
		}

		$comment = $this->commentRepository->getById($id);
		if ($this->user->isInRole(\App\Model\User\User::ROLE_ADMIN) || (($author = $comment->getAuthor()) && $author->getId()->equals($this->user->getId()) && $comment->canBeStillModified())) {
			//$this->persister->remove($comment);
			$comment->setDeleted(new \DateTime());
			$this->persister->persist($comment);
		} else {
			throw new ActionNotAllowedException();
		}
	}

	public function save(Comment $comment): Comment
	{
		// Add
		if ($comment->isNew()) {
			if (!$this->user->isLoggedIn()) {
				throw new ActionNotAllowedException();
			}
		// Edit
		} else {
			if (!($this->user->isInRole(\App\Model\User\User::ROLE_ADMIN) || (($author = $comment->getAuthor()) && $author->getId()->equals($this->user->getId()) && $comment->canBeStillModified()))) {
				throw new ActionNotAllowedException();
			}
		}
		$this->persister->persist($comment);
		return $comment;
	}


}