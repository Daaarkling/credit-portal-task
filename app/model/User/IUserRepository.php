<?php
declare(strict_types=1);


namespace App\Model\User;

use Ramsey\Uuid\Uuid;

interface IUserRepository
{
	/**
	 * @param Uuid $id
	 * @return User
	 * @throws UserNotFoundException
	 */
	public function getById(Uuid $id): User;


	/**
	 * @param string $email
	 * @return User
	 * @throws UserNotFoundException
	 */
	public function getByEmail(string $email): User;
}