<?php
declare(strict_types=1);

namespace Test\Model\Comment;

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
class CommentTest extends Tester\TestCase
{
	/** @var Comment */
	private $comment;

	/** @var string */
	private $authorId;

	/** @var string */
	private $threadId;

	/** @var string */
	private $commentId;

	/** @var \DateTime */
	private $posted;


	public function setUp()
	{
		$authorUuid = Uuid::uuid4();
		$author = new User('user', 'email@email.email', 'heslo', 'role');
		$author->setId($authorUuid);
		$this->authorId = $authorUuid->toString();

		$threadUuid = Uuid::uuid4();
		$thread = new Thread('thread', 'slug');
		$thread->setId($threadUuid);
		$this->threadId = $threadUuid->toString();

		$commentUuid = Uuid::uuid4();
		$this->comment = new Comment($author, $thread, 'text');
		$this->comment->setId($commentUuid);
		$this->posted = new \DateTime();
		$this->comment->setPosted($this->posted);
		$this->commentId = $commentUuid->toString();
	}


	public function testToArray()
	{
		$expected = [
			'id' => $this->commentId,
			'author_id' => $this->authorId,
			'thread_id' => $this->threadId,
			'posted' => $this->posted,
			'text' => 'text',
			'deleted' => null,
		];
		Assert::same($expected, $this->comment->toArray());
	}

	public function testGetAuthorName()
	{
		Assert::same('user', $this->comment->getAuthorName());
		Assert::same('user', $this->comment->getAuthor()->getName());
	}


	public function testCanBeStillModified()
	{
		Assert::true($this->comment->canBeStillModified());

		$this->comment->getPosted()->sub(new \DateInterval('PT31M'));
		Assert::false($this->comment->canBeStillModified());
	}
}

$test = new CommentTest();
$test->run();
