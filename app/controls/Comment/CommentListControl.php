<?php

namespace App\Controls\Comment;

use App\Controls\BaseControl;
use App\Model\Comment\Comment;
use App\Model\Comment\ICommentFacade;
use App\Model\Comment\ICommentRepository;
use App\Model\Thread\Thread;
use Ramsey\Uuid\Exception\InvalidUuidStringException;
use Ramsey\Uuid\Uuid;


class CommentListControl extends BaseControl {

	/** @var ICommentFacade */
	private $commentFacade;

	/** @var Comment[] */
	private $comments;

	/** @var ICommentRepository */
	private $commentRepository;

	/** @var Thread */
	private $thread;


	public function __construct(
		Thread $thread,
		ICommentFacade $commentFacade,
		ICommentRepository $commentRepository
	){
		parent::__construct();
		$this->commentFacade = $commentFacade;
		$this->commentRepository = $commentRepository;
		$this->comments = $this->commentRepository->findByThread($thread->getId());
		$this->thread = $thread;
	}


	/**
	 * @secured
	 */
	public function handleDelete($id)
	{
		try {
			$uuid = Uuid::fromString($id);
			$this->commentFacade->delete($uuid);
			$this->flashMessage('Příspěvek byl smazán.');
			if ($this->isControlInvalid()) {
				$this->redirect('this');
			}
			$this->comments = $this->commentRepository->findByThread($this->thread->getId());
			$this->redrawControl('comments');
			$this->redrawControl('flashes');
		} catch (InvalidUuidStringException $e) {
			$this->presenter->error();
		}
	}



	public function render()
	{
		$this->template->setFile(__DIR__ . '/template/comment_list.latte');
		$this->template->setParameters([
			'comments' => $this->comments,
		]);
		$this->template->render();
	}

}
