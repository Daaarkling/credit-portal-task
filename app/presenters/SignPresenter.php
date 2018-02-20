<?php
declare(strict_types=1);

namespace App\Presenters;

use App\Forms;
use Nette\Application\UI\Form;


class SignPresenter extends BasePresenter
{
	/** @var Forms\SignInFormFactory */
	private $signInFactory;

	/** @persistent */
	public $backlink = '';


	public function __construct(Forms\SignInFormFactory $signInFactory)
	{
		parent::__construct();
		$this->signInFactory = $signInFactory;
	}


	/**
	 * Sign-in form factory.
	 * @return Form
	 */
	protected function createComponentSignInForm()
	{
		return $this->signInFactory->create(function () {
			$this->flashMessage('Byl jste úspěšně přihlášen.', 'warning');
			$this->restoreRequest($this->backlink);
			$this->redirect('Homepage:');
		});
	}


	public function actionOut()
	{
		$this->getUser()->logout();
	}
}
