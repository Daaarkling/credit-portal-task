<?php
declare(strict_types=1);

namespace App\Presenters;

use App\Model\User\IUserRepository;
use App\Model\User\User;
use Ublaboo\DataGrid\DataGrid;

class UserAdminPresenter extends BasePresenter
{
	/** @var IUserRepository */
	private $userRepository;




	public function __construct(
		IUserRepository $userRepository
	){
		parent::__construct();
		$this->userRepository = $userRepository;
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
		$grid->setDataSource($this->userRepository->getDataSource());

		$grid->addColumnText('name', 'Název')
			->setSortable()
			->setFilterText();

		$grid->addColumnText('email', 'E-mail')
			->setSortable()
			->setFilterText();

		$grid->addColumnText('disabled', 'Povoleno komentování')
			->setAlign('center')
			->setReplacement([1 => 'NE', 0 => 'ANO']);

		$grid->addColumnDateTime('deleted', 'Povoleno přihlášení')
			->setAlign('center')
			->setRenderer(function ($user) {
				if ($user->deleted) {
					return 'NE';
				}
				return 'ANO';
			});

		$grid->addAction('edit', 'Upravit', 'UserAdminEdit:edit')
			->setClass('btn btn-warning');


		return $grid;
	}

}
