<?php
declare(strict_types=1);

namespace Test\Model\Thread;

use App\Model\Comment\Comment;
use App\Model\Thread\Thread;
use App\Model\User\User;
use Ramsey\Uuid\Uuid;
use Tester;
use Tester\Assert;

$container = require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
class ThreadTest extends Tester\TestCase
{
	/** @var Thread */
	private $thread;

	/** @var string */
	private $threadId;

	/** @var Comment */
	private $comment;


	public function setUp()
	{
		$threadUuid = Uuid::uuid4();
		$this->thread = new Thread('thread', 'slug');
		$this->thread->setId($threadUuid);
		$this->threadId = $threadUuid->toString();

		$author = new User('user', 'email@email.email', 'heslo', 'role');

		$comment = new Comment($author, $this->thread, 'text1');
		$this->comment = new Comment($author, $this->thread, 'text2');
		$this->comment->setDeleted(new \DateTime());
	}


	public function testToArray()
	{
		$expected = [
			'id' => $this->threadId,
			'name' => 'thread',
			'description' => null,
			'slug' => 'slug',
			'deleted' => null,
		];
		Assert::same($expected, $this->thread->toArray());
	}


	public function testFromArray()
	{
		$data = [
			'id' => $this->threadId,
			'name' => 'thread',
			'slug' => 'slug',
		];
		$this->thread->clearComments();
		Assert::equal(Thread::fromArray($data), $this->thread);
	}


	public function testClearComments()
	{
		$this->thread->clearComments();
		Assert::same([], $this->thread->getComments());
	}

	public function testGetNumOfComments()
	{
		Assert::same(1, $this->thread->getNumOfComments());
		Assert::same(2, $this->thread->getNumOfComments(false));
	}

	public function testGetLastComment()
	{
		Assert::same($this->comment, $this->thread->getLastComment());
	}


}

$test = new ThreadTest();
$test->run();
