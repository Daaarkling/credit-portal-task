<?php
declare(strict_types=1);

namespace App\Presenters;


use App\Model\Thread\IThreadRepository;

class HomepagePresenter extends BasePresenter
{
	/** @var IThreadRepository */
	private $threadRepository;


	public function __construct(IThreadRepository $threadRepository)
	{
		parent::__construct();
		$this->threadRepository = $threadRepository;
	}


	public function renderDefault()
	{
		$this->template->setParameters([
			'threads' => $this->threadRepository->findAll(),
		]);
	}
}
