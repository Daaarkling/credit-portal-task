<?php
declare(strict_types=1);


namespace App\Model\Thread;


use App\Model\ActionNotAllowedException;
use Ramsey\Uuid\Uuid;

interface IThreadFacade
{
	/**
	 * @param Uuid $id
	 * @throws ActionNotAllowedException
	 */
	public function delete(Uuid $id): void;

	/**
	 * @param Thread $thread
	 * @return Thread
	 * @throws ActionNotAllowedException
	 * @throws ThreadDuplicateSlugException
	 */
	public function save(Thread $thread): Thread;
}