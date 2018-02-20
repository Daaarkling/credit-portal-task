<?php
declare(strict_types=1);

namespace App\Model\Thread;


use App\Model\Comment\Comment;
use App\Model\Db\TRamseyUuidIdentifier;
use App\Model\InvalidArgumentException;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Nette\SmartObject;
use Nette\Utils\Strings;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity
 */
class Thread
{
	use TRamseyUuidIdentifier;
	use SmartObject;

	const TABLE_NAME = 'thread';

	/**
	 * @ORM\Column(type="string")
	 * @var string
	 */
	private $name;

	/**
	 * @ORM\Column(type="string", nullable=true)
	 * @var string
	 */
	private $description;

	/**
	 * @ORM\Column(type="string", unique=true)
	 * @var string
	 */
	private $slug;

	/**
	 * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="thread")
	 * @ORM\OrderBy({"posted" = "ASC"})
	 * @var Comment
	 */
	private $comments;

	/**
	 * Thread constructor.
	 * @param string $name
	 * @param string $slug
	 */
	public function __construct(string $name, string $slug, string $description = null)
	{
		$this->name = $name;
		$this->setSlug($slug);
		$this->comments = new ArrayCollection();
		$this->description = $description;
	}

	public function addComment(Comment $comment)
	{
		$this->comments[] = $comment;
		$comment->setThread($this);
	}


	public static function fromArray(iterable $data): Thread
	{
		try {
			$thread = new self($data['name'], $data['slug']);
			$thread->setId(Uuid::fromString($data['id']));
			return $thread;
		} catch (\Exception $e) {
			throw new InvalidArgumentException('Array must contains: name and slug key.');
		}
	}

	public function toArray(): array
	{
		$result = [
			'id' => $this->id ? $this->id->toString() : null,
			'name' => $this->name,
			'description' => $this->description,
			'slug' => $this->slug,
		];
		return $result;
	}

	public function getNumOfComments(): int
	{
		return $this->comments->count();
	}


	public function getLastComment(): ?Comment
	{
		$comment = $this->comments->last();
		if ($comment instanceof Comment) {
			return $comment;
		}
		return null;
	}



	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName(string $name)
	{
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getSlug(): string
	{
		return $this->slug;
	}

	/**
	 * @param string $slug
	 */
	public function setSlug(string $slug)
	{
		$this->slug = Strings::webalize($slug);
	}

	/**
	 * @return Comment[]
	 */
	public function getComments(): array
	{
		return $this->comments->toArray();
	}

	/**
	 * @param Comment[] $comments
	 */
	public function setComments(array $comments)
	{
		foreach ($comments as $comment) {
			$this->comments[] = $comment;
		}
	}


	public function getDescription(): ?string
	{
		return $this->description;
	}

	public function setDescription(string $description = null)
	{
		$this->description = $description;
	}
}