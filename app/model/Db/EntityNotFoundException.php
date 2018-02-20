<?php
declare(strict_types=1);

namespace App\Model\Db;


use Nette\Application\BadRequestException;
use Nette\Http\IResponse;
use Ramsey\Uuid\Uuid;

class EntityNotFoundException extends BadRequestException
{
	/** @var string */
	protected $identifiers;


	public function __construct(string $identifiers, $httpCode = IResponse::S404_NOT_FOUND, \Exception $previous = null)
	{
		parent::__construct(sprintf('Entity %s not found', $identifiers), $httpCode, $previous);
		$this->identifiers = $identifiers;
	}

	/**
	 * @return string
	 */
	public function getIdentifiers(): string
	{
		return $this->identifiers;
	}
}