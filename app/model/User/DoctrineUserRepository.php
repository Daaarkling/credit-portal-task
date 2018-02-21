<?php
declare(strict_types=1);


namespace App\Model\User;

use Kdyby\Doctrine\EntityManager;
use Kdyby\Doctrine\EntityRepository;
use Nette\SmartObject;
use Ramsey\Uuid\Uuid;

class DoctrineUserRepository implements IUserRepository
{
	use SmartObject;

	/** @var EntityManager */
	private $em;

	/** @var EntityRepository */
	private $repository;


	public function __construct(EntityManager $em)
	{
		$this->em = $em;
		$this->repository = $this->em->getRepository(User::class);
	}


	public function getById(Uuid $id): User
	{
		$user = $this->repository->find($id);
		if ($user === null) {
			throw new UserNotFoundException('User: ' . $id->toString());
		}
		return $user;
	}



	public function getByEmail(string $email): User
	{
		$user = $this->repository->findOneBy(['email' => $email, 'deleted' => null]);
		if ($user === null) {
			throw new UserNotFoundException('User: ' . $email);
		}
		return $user;
	}


	public function getDataSource()
	{
		return $this->repository->createQueryBuilder('qb');
	}
}