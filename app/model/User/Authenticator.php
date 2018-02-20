<?php
declare(strict_types=1);

namespace App\Model\User;

use App\Model\Db\IPersister;
use Nette;
use Nette\Security\Passwords;



class Authenticator implements Nette\Security\IAuthenticator
{
	use Nette\SmartObject;

	/** @var IUserRepository */
	private $userRepository;

	/** @var IPersister */
	private $persister;

	public function __construct(IUserRepository $userRepository, IPersister $persister)
	{
		$this->userRepository = $userRepository;
		$this->persister = $persister;
	}


	/**
	 * Performs an authentication.
	 *
	 * @param array $credentials
	 * @return Nette\Security\Identity
	 * @throws Nette\Security\AuthenticationException
	 */
	public function authenticate(array $credentials)
	{
		[$email, $password] = $credentials;

		try {
			$user = $this->userRepository->getByEmail($email);
		} catch (UserNotFoundException $e) {
			throw new Nette\Security\AuthenticationException('The username is incorrect.', self::IDENTITY_NOT_FOUND);
		}

		if (!Passwords::verify($password, $user->getPassword())) {
			throw new Nette\Security\AuthenticationException('The password is incorrect.', self::INVALID_CREDENTIAL);

		} elseif (Passwords::needsRehash($user->getPassword())) {
			$user->setPassword($password);
			$this->persister->persist($user);
		}

		return new Nette\Security\Identity($user->getId(), $user->getRole(), $user->toArray());
	}
}

