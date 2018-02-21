<?php
declare(strict_types=1);

namespace App\Presenters;

use App\Forms\SignInFormFactory;
use App\Forms\SignUpFormFactory;
use App\Model\User\User;
use Nette\Application\UI\Form;


class SignPresenter extends BasePresenter
{
	/** @var SignInFormFactory */
	private $signInFactory;

	/** @var SignUpFormFactory */
	private $signUpFactory;

	/** @persistent */
	public $backlink = '';


	public function __construct(
		SignInFormFactory $signInFactory,
		SignUpFormFactory $signUpFactory
	){
		parent::__construct();
		$this->signInFactory = $signInFactory;
		$this->signUpFactory = $signUpFactory;
	}


	public function actionIn()
	{
		if ($this->user->isLoggedIn()) {
			$this->redirect('Homepage:');
		}
	}

	public function actionUp()
	{
		if ($this->user->isLoggedIn() && !$this->user->isInRole(User::ROLE_ADMIN)) {
			$this->redirect('Homepage:');
		}
	}



	protected function createComponentSignInForm(): Form
	{
		$form = $this->signInFactory->create(function () {
			$this->flashMessage('Byl jste úspěšně přihlášen.', 'warning');
			$this->restoreRequest($this->backlink);
			$this->redirect('Homepage:');
		});
		$form->onError[] = function () {
			$this->redrawControl('form');
		};
		return $form;
	}


	protected function createComponentSignUpForm(): Form
	{
		$form = $this->signUpFactory->create(function () {
			if ($this->user->isInRole(User::ROLE_ADMIN)) {
				$this->flashMessage('Nový uživatel admin byl vytvořen.', 'warning');
			} else {
				$this->flashMessage('Byl jste úspěšně zaregistrován.', 'warning');
			}
			$this->restoreRequest($this->backlink);
			$this->redirect('Homepage:');
		});
		$form->onError[] = function () {
			$this->redrawControl('form');
		};
		return $form;
	}
}
