<?php
declare(strict_types=1);

namespace App\Presenters;


use App\Forms\UserFormFactory;
use App\Model\User\IUserRepository;
use App\Model\User\User;
use Nette\Application\UI\Form;
use Ramsey\Uuid\Uuid;

class UserAdminEditPresenter extends BasePresenter
{
	/** @var IUserRepository */
	private $userRepository;

	/** @var User */
	private $userEdit;

	/** @var UserFormFactory */
	private $formFactory;


	public function __construct(
		IUserRepository $userRepository,
		UserFormFactory $formFactory
	){
		parent::__construct();
		$this->userRepository = $userRepository;
		$this->formFactory = $formFactory;
	}

	protected function startup()
	{
		parent::startup();
		if (!$this->user->isInRole(User::ROLE_ADMIN)) {
			$this->error('', 403);
		}
	}



	public function actionEdit($id)
	{
		$this->userEdit = $this->userRepository->getById(Uuid::fromString($id));
		$this['userForm']->setDefaults($this->userEdit->toArray());
		$this['userForm-show']->setDefaultValue($this->userEdit->getDeleted() === null);
	}


	protected function createComponentUserForm(): Form
	{
		$form = $this->formFactory->create(function () {
			$this->flashMessage('Uživatel byl uložen.', 'warning');
			$this->redirect('UserAdmin:');
		}, $this->userEdit);
		$form->onError[] = function () {
			$this->redrawControl('form');
		};
		return $form;
	}

}
