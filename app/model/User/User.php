<?php
declare(strict_types=1);

namespace App\Model\User;


use App\Model\Comment\Comment;
use App\Model\Db\TRamseyUuidIdentifier;
use App\Model\InvalidArgumentException;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Nette\Security\Passwords;
use Nette\SmartObject;
use Nette\Utils\Validators;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity
 * @ORM\Table(indexes={@ORM\Index(name="search_idx", columns={"name", "email"})})
 * @property \Datetime $deleted
 */
class User
{
	use TRamseyUuidIdentifier;
	use SmartObject;

	const TABLE_NAME = 'user';

	const ROLE_ADMIN = 'admin';
	const ROLE_MEMBER = 'member';

	/**
	 * @ORM\Column(type="string", length=100)
	 * @var string
	 */
	private $name;

	/**
	 * @ORM\Column(type="string", unique=true)
	 * @var string
	 */
	private $email;

	/**
	 * @ORM\Column(type="string", length=60)
	 * @var string
	 */
	private $password;

	/**
	 * @ORM\Column(type="string", length=60)
	 * @var string
	 */
	private $role;

	/**
	 * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="author")
	 * @ORM\OrderBy({"posted" = "ASC"})
	 * @var Comment
	 */
	private $comments;

	/**
	 * @ORM\Column(type="datetime", nullable=true)
	 * @var \DateTime|null
	 */
	private $deleted;


	/**
	 * @ORM\Column(type="boolean")
	 * @var bool
	 */
	private $disabled;


	/**
	 * User constructor.
	 * @param string $name
	 * @param string $email
	 * @param string $password
	 * @param string $role
	 * @throws InvalidArgumentException
	 */
	public function __construct(string $name, string $email, string $password, string $role)
	{
		$this->name = $name;
		$this->setEmail($email);
		$this->setPassword($password);
		$this->role = $role;
		$this->comments = new ArrayCollection();
		$this->disabled = false;
	}


	public static function fromArray(Iterable $data): User
	{
		try {
			$user = new self($data['name'], $data['email'], $data['password'], $data['role']);
			$user->setId(Uuid::fromString($data['id']));
			$user->setPassword($data['password'], false);
			$user->setDisabled((bool) $data['disabled']);
			$user->setDeleted($data['deleted']);
			return $user;
		} catch (\Exception $e) {
			throw new InvalidArgumentException('Array must contains: name, email, password and role key.');
		}
	}

	public function toArray(): array
	{
		$result = [
			'id' => $this->id ? $this->id->toString() : null,
			'name' => $this->name,
			'email' => $this->email,
			'role' => $this->role,
			'disabled' => $this->disabled,
			'deleted' => $this->deleted,
			'password' => $this->password,
		];
		return $result;
	}


	public function addComment(Comment $comment)
	{
		$this->comments[] = $comment;
		$comment->setAuthor($this);
	}


	public function getName(): string
	{
		return $this->name;
	}

	public function setName(string $name)
	{
		$this->name = $name;
	}

	public function getEmail(): string
	{
		return $this->email;
	}

	public function setEmail(string $email)
	{
		if (Validators::isEmail($email)) {
			$this->email = $email;
		} else {
			throw new InvalidArgumentException(sprintf('%s is not a valid email address.', $email));
		}
	}

	public function getPassword(): string
	{
		return $this->password;
	}

	public function setPassword(string $password, bool $hash = true)
	{
		if ($hash) {
			$this->password = Passwords::hash($password);
		} else {
			$this->password = $password;
		}
	}

	public function getRole(): string
	{
		return $this->role;
	}

	public function setRole(string $role)
	{
		$this->role = $role;
	}

	public function getDeleted(): ?\DateTime
	{
		return $this->deleted;
	}

	public function setDeleted(\DateTime $deleted = null)
	{
		$this->deleted = $deleted;
	}

	/**
	 * @return bool
	 */
	public function isDisabled(): bool
	{
		return $this->disabled;
	}

	public function setDisabled(bool $disabled)
	{
		$this->disabled = $disabled;
	}
}