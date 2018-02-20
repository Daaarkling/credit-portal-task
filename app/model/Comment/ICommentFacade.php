<?php
declare(strict_types=1);


namespace App\Model\Comment;


use App\Model\ActionNotAllowedException;
use Ramsey\Uuid\Uuid;

interface ICommentFacade
{
	/**
	 * @param Uuid $id
	 * @throws ActionNotAllowedException
	 */
	public function delete(Uuid $id): void;

	/**
	 * @param Comment $comment
	 * @return Comment
	 * @throws ActionNotAllowedException
	 */
	public function save(Comment $comment): Comment;
}