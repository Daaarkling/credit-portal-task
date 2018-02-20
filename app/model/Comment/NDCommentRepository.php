<?php
declare(strict_types=1);


namespace App\Model\Comment;


use App\Model\Thread\Thread;
use App\Model\User\User;
use Nette\Database\Context;
use Nette\Database\Table\ActiveRow;
use Nette\SmartObject;
use Ramsey\Uuid\Uuid;

class NDCommentRepository implements ICommentRepository
{
	use SmartObject;

	/** @var Context */
	private $context;


	public function __construct(Context $context)
	{
		$this->context = $context;
	}


	public function getById(Uuid $id): Comment
	{
		$activeRow = $this->context->table(Comment::TABLE_NAME)->get($id->toString());
		if (!$activeRow) {
			throw new CommentNotFoundException('Comment: ' . $id->toString());
		}

		return $this->createEntity($activeRow);
	}

	public function findByThread(Uuid $threadId): array
	{
		$selection = $this->context->table(Comment::TABLE_NAME)->where('thread_id = ?', $threadId->toString())->order('posted ASC');

		$comments = [];
		foreach ($selection as $activeRow) {
			$comments[] = $this->createEntity($activeRow);
		}
		return $comments;
	}




	private function createEntity(ActiveRow $activeRow): Comment
	{
		$author = User::fromArray($activeRow->author);
		$thread = Thread::fromArray($activeRow->thread);
		$comment = new Comment($author, $thread, $activeRow->text);
		$comment->setId(Uuid::fromString($activeRow->id));
		$comment->setPosted($activeRow->posted);
		return $comment;
	}
}