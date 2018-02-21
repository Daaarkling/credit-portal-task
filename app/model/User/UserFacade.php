<?php
declare(strict_types=1);


namespace App\Model\User;


use App\Model\ActionNotAllowedException;
use App\Model\Db\IPersister;
use Nette\Security\Passwords;
use Nette\Security\User;
use Nette\SmartObject;
use Ramsey\Uuid\Uuid;

class UserFacade implements IUserFacade
{
	use SmartObject;

	/** @var IPersister */
	private $persister;

	/** @var User */
	private $user;

	/** @var IUserRepository */
	private $userRepository;


	public function __construct(
		IPersister $persister,
		User $user,
		IUserRepository $userRepository
	){
		$this->persister = $persister;
		$this->user = $user;
		$this->userRepository = $userRepository;
	}

	public function delete(Uuid $id): void
	{
		if (!$this->user->isInRole(\App\Model\User\User::ROLE_ADMIN)) {
			throw new ActionNotAllowedException();
		}

		$user = $this->userRepository->getById($id);
		$user->setDeleted(new \DateTime());
		$this->persister->persist($user);
	}

	public function save(\App\Model\User\User $user): \App\Model\User\User
	{
		try {
		if ($user->isNew() && $this->user->isInRole(\App\Model\User\User::ROLE_ADMIN)) {
			$user->setRole(\App\Model\User\User::ROLE_ADMIN);
		}
		$this->persister->persist($user);
		return $user;
		} catch (\Nette\Database\UniqueConstraintViolationException | \Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
			throw new UserDuplicateNameException();
		}
	}

	public function changePassword(string $oldPassword, string $newPassword): void
	{
		if (!$this->user->isLoggedIn()) {
			throw new ActionNotAllowedException();
		}

		$user = $this->userRepository->getById($this->user->getId());
		if (!Passwords::verify($oldPassword, $user->getPassword())) {
			throw new PasswordNotSameException();
		}
		$user->setPassword($newPassword);
		$this->persister->persist($user);
	}


}