<?php
declare(strict_types=1);


namespace App\Model\Thread;

use Ramsey\Uuid\Uuid;
use Ublaboo\DataGrid\DataSource\IDataSource;

interface IThreadRepository
{

	/**
	 * @return Thread[]
	 */
	public function findAll(): array;


	/**
	 * @param Uuid $id
	 * @return Thread
	 * @throws ThreadNotFoundException
	 */
	public function getById(Uuid $id): Thread;


	/**
	 * @param string $slug
	 * @return Thread
	 * @throws ThreadNotFoundException
	 */
	public function getBySlug(string $slug): Thread;


	/**
	 * For Grid component
	 * @return mixed
	 */
	public function getDataSource();
}