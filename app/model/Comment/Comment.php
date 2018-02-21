<?php
declare(strict_types=1);

namespace App\Model\Comment;


use App\Model\Db\TRamseyUuidIdentifier;
use App\Model\Thread\Thread;
use App\Model\User\User;
use Doctrine\ORM\Mapping as ORM;
use Nette\SmartObject;

/**
 * @ORM\Entity
 */
class Comment
{
	use TRamseyUuidIdentifier;
	use SmartObject;

	const TABLE_NAME = 'comment';

	/**
	 * @ORM\ManyToOne(targetEntity=User::class, inversedBy="comments")
	 * @var User
	 */
	private $author;

	/**
	 * @ORM\Column(type="datetime")
	 * @var \Datetime
	 */
	private $posted;

	/**
	 * @ORM\Column(type="text", length=60)
	 * @var string
	 */
	private $text;

	/**
	 * @ORM\ManyToOne(targetEntity=Thread::class, inversedBy="comments")
	 * @var Thread
	 */
	private $thread;

	/**
	 * @ORM\Column(type="datetime", nullable=true)
	 * @var \DateTime|null
	 */
	private $deleted;

	/**
	 * Comment constructor.
	 * @param User $author
	 * @param Thread $thread
	 * @param string $text
	 */
	public function __construct(User $author, Thread $thread, string $text)
	{
		$this->posted = new \DateTime();
		$this->text = $text;
		$thread->addComment($this);
		$author->addComment($this);
	}


	public function toArray(): array
	{
		$result = [
			'id' => $this->id ? $this->id->toString() : null,
			'author_id' => $this->author ? $this->author->getId()->toString() : null,
			'thread_id' => $this->thread ? $this->thread->getId()->toString() : null,
			'posted' => $this->posted,
			'text' => $this->text,
			'deleted' => $this->deleted,
		];
		return $result;
	}

	public function getAuthorName(): string
	{
		return $this->author ? $this->author->getName() : '';
	}


	public function canBeStillModified(): bool
	{
		$posted = clone $this->getPosted();
		return $posted->add(new \DateInterval('PT30M')) > new \DateTime();
	}


	public function getAuthor(): ?User
	{
		return $this->author;
	}

	public function setAuthor(User $author)
	{
		$this->author = $author;
	}

	public function getPosted(): \Datetime
	{
		return $this->posted;
	}

	public function setPosted(\Datetime $posted)
	{
		$this->posted = $posted;
	}

	public function getText(): string
	{
		return $this->text;
	}

	public function setText(string $text)
	{
		$this->text = $text;
	}

	public function getThread(): Thread
	{
		return $this->thread;
	}

	public function setThread(Thread $thread)
	{
		$this->thread = $thread;
	}


	public function getDeleted(): ?\DateTime
	{
		return $this->deleted;
	}

	public function setDeleted(\DateTime $deleted = null)
	{
		$this->deleted = $deleted;
	}
}