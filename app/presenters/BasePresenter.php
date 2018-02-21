<?php
declare(strict_types=1);

namespace App\Presenters;

use Nette;
use Nextras\Application\UI\SecuredLinksPresenterTrait;
use WebLoader\Nette\CssLoader;
use WebLoader\Nette\JavaScriptLoader;
use WebLoader\Nette\LoaderFactory;


abstract class BasePresenter extends Nette\Application\UI\Presenter
{
	use SecuredLinksPresenterTrait;

	/** @var LoaderFactory @inject */
	public $webLoader;





	public function handleSignOut()
	{
		$this->user->logout(true);
		$this->flashMessage('Byl jste úspěšně odhlášen.', 'warning');
		$this->redirect('this');
	}


	protected function createComponentJs(): JavaScriptLoader
	{
		return $this->webLoader->createJavaScriptLoader('frontend');
	}

	protected function createComponentCss(): CssLoader
	{
		return $this->webLoader->createCssLoader('frontend');
	}
}
