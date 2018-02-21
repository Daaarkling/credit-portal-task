<?php
declare(strict_types=1);


namespace App\Model\Thread;

use App\Model\Comment\NDCommentRepository;
use Nette\Database\Context;
use Nette\Database\Table\ActiveRow;
use Nette\SmartObject;
use Ramsey\Uuid\Uuid;

class NDThreadRepository implements IThreadRepository
{
	use SmartObject;

	/** @var Context */
	private $context;

	/** @var NDCommentRepository */
	private $commentRepository;


	public function __construct(Context $context, NDCommentRepository $commentRepository)
	{
		$this->context = $context;
		$this->commentRepository = $commentRepository;
	}


	public function findAll(): array
	{
		$selection = $this->context->table(Thread::TABLE_NAME)->order('name ASC');

		$threads = [];
		foreach ($selection as $activeRow) {
			$threads[] = $this->createEntity($activeRow);
		}
		return $threads;
	}


	public function getById(Uuid $id): Thread
	{
		$activeRow = $this->context->table(Thread::TABLE_NAME)->get($id->toString());
		if (!$activeRow) {
			throw new ThreadNotFoundException('Thread: ' . $id->toString());
		}

		return $this->createEntity($activeRow);
	}


	public function getBySlug(string $slug): Thread
	{
		$activeRow = $this->context->table(Thread::TABLE_NAME)->where('slug', $slug)->fetch();
		if (!$activeRow) {
			throw new ThreadNotFoundException('Thread: ' . $slug);
		}

		return $this->createEntity($activeRow);
	}


	public function getDataSource()
	{
		return $this->context->table(Thread::TABLE_NAME);
	}


	private function createEntity(ActiveRow $activeRow): Thread
	{
		$comments = $this->commentRepository->findByThread(Uuid::fromString($activeRow->id));

		$thread = new Thread($activeRow->name, $activeRow->slug, $activeRow->description);
		$thread->setId(Uuid::fromString($activeRow->id));
		$thread->setDeleted($activeRow->deleted);
		$thread->setComments($comments);
		return $thread;
	}
}