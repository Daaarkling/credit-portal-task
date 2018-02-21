<?php
declare(strict_types=1);


namespace App\Model\User;

use Nette\Database\Context;
use Nette\Database\Table\ActiveRow;
use Nette\SmartObject;
use Ramsey\Uuid\Uuid;

class NDUserRepository implements IUserRepository
{
	use SmartObject;

	/** @var Context */
	private $context;


	public function __construct(Context $context)
	{
		$this->context = $context;
	}


	public function getById(Uuid $id): User
	{
		$activeRow = $this->context->table(User::TABLE_NAME)->get($id->toString());
		if (!$activeRow) {
			throw new UserNotFoundException('User: ' . $id->toString());
		}

		return $this->createEntity($activeRow);
	}



	public function getByEmail(string $email): User
	{
		$activeRow = $this->context->table(User::TABLE_NAME)->where('email ? AND deleted ?', $email, null)->fetch();
		if (!$activeRow) {
			throw new UserNotFoundException('User: ' . $email);
		}

		return $this->createEntity($activeRow);
	}


	public function getDataSource()
	{
		return $this->context->table(User::TABLE_NAME);
	}


	private function createEntity(ActiveRow $activeRow): User
	{
		$user = User::fromArray($activeRow);
		return $user;
	}
}