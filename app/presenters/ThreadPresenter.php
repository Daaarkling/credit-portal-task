<?php
declare(strict_types=1);

namespace App\Presenters;


use App\Controls\Comment\CommentFormControl;
use App\Controls\Comment\CommentListControl;
use App\Controls\Comment\ICommentFormControlFactory;
use App\Controls\Comment\ICommentListControlFactory;
use App\Model\Comment\Comment;
use App\Model\Comment\ICommentFacade;
use App\Model\Comment\ICommentRepository;
use App\Model\Thread\Thread;
use Ramsey\Uuid\Exception\InvalidUuidStringException;
use Ramsey\Uuid\Uuid;

class ThreadPresenter extends BasePresenter
{
	/** @var ICommentRepository */
	private $commentRepository;

	/** @var ICommentFormControlFactory */
	private $commentFormControlFactory;

	/** @var ICommentFacade */
	private $commentFacade;

	/** @var ICommentListControlFactory */
	private $commentListControlFactory;

	/** @var Thread */
	private $thread;


	public function __construct(
		ICommentRepository $commentRepository,
		ICommentFormControlFactory $commentFormControlFactory,
		ICommentListControlFactory $commentListControlFactory,
		ICommentFacade $commentFacade
	){
		parent::__construct();
		$this->commentRepository = $commentRepository;
		$this->commentFormControlFactory = $commentFormControlFactory;
		$this->commentFacade = $commentFacade;
		$this->commentListControlFactory = $commentListControlFactory;
	}


	public function actionDefault(Thread $id)
	{
		$this->thread = $id;
	}


	public function renderDefault()
	{
		$this->template->setParameters([
			'thread' => $this->thread,
		]);
	}



	public function handleEdit($commentId)
	{
		try {
			$uuid = Uuid::fromString($commentId);
			$comment = $this->commentRepository->getById($uuid);
			$this['commentForm']->editComment($comment);
			$this['commentForm']->redrawControl('commentForm');
		} catch (InvalidUuidStringException $e) {
			$this->error();
		}
	}


	protected function createComponentCommentList(): CommentListControl
	{
		$control = $this->commentListControlFactory->create($this->thread);
		return $control;
	}



	protected function createComponentCommentForm(): CommentFormControl
	{
		$control = $this->commentFormControlFactory->create($this->thread);
		$control->onPosted[] = function (Comment $comment) {
			if (!$this->isAjax()) {
				$this->redirect('this#' . $comment->getId()->toString());
			}
			$this['commentList']->redrawControl('comments');
		};
		$control->onLoggedIn[] = function () {
			$this->redrawControl('menu');
			$this->redrawControl('content');
		};

		return $control;
	}
}
