<?php
declare(strict_types=1);

namespace App\Router;

use App\Model\Thread\IThreadRepository;
use App\Model\Thread\Thread;
use Nette\Application\IRouter;
use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;
use Nette\SmartObject;


class RouterFactory
{
	use SmartObject;

	/** @var IThreadRepository */
	private $threadRepository;



	public function __construct(IThreadRepository $threadRepository)
	{
		$this->threadRepository = $threadRepository;
	}


	public function createRouter(): IRouter
	{
		$router = new RouteList;

		$router[] = new Route('prihlaseni', 'Sign:in');

		// Thread
		$router[] = new Route('vlakno/<id>', [
			'presenter' => 'Thread',
			'action' => 'default',
			'id' => [
				Route::FILTER_IN => function (string $slug) {
					return $this->threadRepository->getBySlug($slug);
				},
				Route::FILTER_OUT => function (Thread $thread) {
					return $thread->getSlug();
				}
			]
		]);

		$router[] = new Route('<presenter>/<action>', 'Homepage:default');
		return $router;
	}
}
