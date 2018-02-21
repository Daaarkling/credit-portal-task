<?php
declare(strict_types=1);

namespace Test\Model\User;

use App\Model\InvalidArgumentException;
use App\Model\User\User;
use Nette\Security\Passwords;
use Ramsey\Uuid\Uuid;
use Tester;
use Tester\Assert;

$container = require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
class UserTest extends Tester\TestCase
{
	/** @var User */
	private $user;

	/** @var string */
	private $userId;


	public function setUp()
	{
		$userUuid = Uuid::uuid4();
		$this->user = new User('user', 'email@email.email', 'heslo', 'role');
		$this->user->setId($userUuid);
		$this->userId = $userUuid->toString();
	}


	public function testToArray()
	{
		$expected = [
			'id' => $this->userId,
			'name' => 'user',
			'email' => 'email@email.email',
			'role' => 'role',
			'disabled' => false,
			'deleted' => null,
			'password' => $this->user->getPassword(),
		];
		Assert::same($expected, $this->user->toArray());
	}


	public function testFromArray()
	{
		$data = [
			'id' => $this->userId,
			'name' => 'user',
			'email' => 'email@email.email',
			'role' => 'role',
			'disabled' => false,
			'deleted' => null,
			'password' => 'heslo',
		];
		$this->user->setPassword('heslo', false);
		Assert::equal(User::fromArray($data), $this->user);
	}


	public function testSetPassword()
	{
		Assert::true(Passwords::verify('heslo', $this->user->getPassword()));
	}

	public function testSetEmail()
	{
		Assert::exception(function () {
			$this->user->setEmail('aaaa');
		}, InvalidArgumentException::class);
		$this->user->setEmail('email@email.email');
		Assert::same('email@email.email', $this->user->getEmail());
	}

}

$test = new UserTest();
$test->run();
