<?php
declare(strict_types=1);

namespace App\Presenters;


use App\Model\Thread\IThreadRepository;
use App\Model\User\User;
use Ublaboo\DataGrid\DataGrid;

class ThreadAdminPresenter extends BasePresenter
{
	/** @var IThreadRepository */
	private $threadRepository;




	public function __construct(
		IThreadRepository $threadRepository
	){
		parent::__construct();
		$this->threadRepository = $threadRepository;
	}

	protected function startup()
	{
		parent::startup();
		if (!$this->user->isInRole(User::ROLE_ADMIN)) {
			$this->error('', 403);
		}
	}


	protected function createComponentGrid(): DataGrid
	{
		$grid = new DataGrid();
		$grid->setDataSource($this->threadRepository->getDataSource());
		$grid->addColumnText('name', 'Název')
			->setSortable()
			->setFilterText();

		$grid->addColumnText('description', 'Popis')
			->setFilterText();

		$grid->addColumnDateTime('deleted', 'Zobrazeno')
			->setAlign('center')
			->setRenderer(function ($thread) {
				if ($thread->deleted) {
					return 'NE';
				}
				return 'ANO';
			});

		$grid->addAction('edit', 'Upravit', 'ThreadAdminEdit:edit')
			->setClass('btn btn-warning');


		return $grid;
	}

}
