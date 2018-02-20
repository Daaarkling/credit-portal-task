<?php
declare(strict_types=1);


namespace App\Model\Db;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Doctrine\UuidGenerator;


trait TRamseyUuidIdentifier
{
	/**
	 * @var Uuid
	 * @ORM\Id
	 * @ORM\Column(type="uuid", unique=true)
	 * @ORM\GeneratedValue(strategy="CUSTOM")
	 * @ORM\CustomIdGenerator(class=UuidGenerator::class)
	 */
	private $id;


	/**
	 * @return Uuid
	 */
	final public function getId(): ?Uuid
	{
		return $this->id;
	}

	/**
	 * @param Uuid $id
	 */
	final public function setId(Uuid $id)
	{
		$this->id = $id;
	}


	public function isNew(): bool
	{
		return $this->id === null;
	}


	public function __clone()
	{
		$this->id = null;
	}
}