<?php
declare(strict_types=1);


namespace App\Model\User;


use App\Model\ActionNotAllowedException;
use Ramsey\Uuid\Uuid;

interface IUserFacade
{
	/**
	 * @param Uuid $id
	 * @throws ActionNotAllowedException
	 */
	public function delete(Uuid $id): void;

	/**
	 * @param User $user
	 * @return User
	 * @throws ActionNotAllowedException
	 * @throws UserDuplicateNameException
	 */
	public function save(User $user): User;


	/**
	 * @param string $oldPassword
	 * @param string $newPassword
	 * @throws ActionNotAllowedException
	 * @throws PasswordNotSameException
	 */
	public function changePassword(string $oldPassword, string $newPassword): void;
}