<?php
declare(strict_types=1);

namespace App\Presenters;

use App\Forms\PasswordFormFactory;
use Nette\Application\UI\Form;


class PasswordPresenter extends BasePresenter
{
	/** @var PasswordFormFactory */
	private $passwordFormFactory;


	public function __construct(
		PasswordFormFactory $passwordFormFactory
	){
		parent::__construct();
		$this->passwordFormFactory = $passwordFormFactory;
	}

	protected function startup()
	{
		parent::startup();
		if (!$this->user->isLoggedIn()) {
			$this->redirect('Homepage:');
		}
	}



	protected function createComponentPasswordForm(): Form
	{
		$form = $this->passwordFormFactory->create(function () {
			$this->flashMessage('Heslo bylo úspěšně změněno.', 'warning');
			$this->redirect('Homepage:');
		});
		$form->onError[] = function () {
			$this->redrawControl('form');
		};
		return $form;
	}
}
