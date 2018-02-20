<?php
declare(strict_types=1);

namespace App\Presenters;

use Nette;
use Nextras\Application\UI\SecuredLinksPresenterTrait;



abstract class BasePresenter extends Nette\Application\UI\Presenter
{
	use SecuredLinksPresenterTrait;

	public function handleSignOut()
	{
		$this->user->logout(true);
		$this->flashMessage('Byl jste úspěšně odhlášen.', 'warning');
		$this->redirect('this');
	}
}
