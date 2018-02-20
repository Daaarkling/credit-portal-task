<?php
declare(strict_types=1);


namespace App\Model\Comment;

use Ramsey\Uuid\Uuid;

interface ICommentRepository
{
	/**
	 * @param Uuid $id
	 * @return Comment
	 * @throws CommentNotFoundException
	 */
	public function getById(Uuid $id): Comment;


	/**
	 * @param Uuid $threadId
	 * @return Comment[]
	 * @throws CommentNotFoundException
	 */
	public function findByThread(Uuid $threadId): array;
}