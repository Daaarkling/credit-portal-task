<?php
declare(strict_types=1);

namespace App\Presenters;


use App\Forms\ThreadFormFactory;
use App\Model\Thread\IThreadRepository;
use App\Model\Thread\Thread;
use App\Model\User\User;
use Nette\Application\UI\Form;
use Ramsey\Uuid\Uuid;

class ThreadAdminEditPresenter extends BasePresenter
{
	/** @var IThreadRepository */
	private $threadRepository;

	/** @var Thread */
	private $thread;

	/** @var ThreadFormFactory */
	private $formFactory;


	public function __construct(
		IThreadRepository $threadRepository,
		ThreadFormFactory $formFactory
	){
		parent::__construct();
		$this->threadRepository = $threadRepository;
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
		$this->thread = $this->threadRepository->getById(Uuid::fromString($id));
		$this['threadForm']->setDefaults($this->thread->toArray());
		$this['threadForm-show']->setDefaultValue($this->thread->getDeleted() === null);
		$this->setView('add');
	}


	protected function createComponentThreadForm(): Form
	{
		$form = $this->formFactory->create(function () {
			$this->flashMessage('Vlákno bylo uloženo.', 'warning');
			$this->redirect('ThreadAdmin:');
		}, $this->thread);
		$form->onError[] = function () {
			$this->redrawControl('form');
		};
		return $form;
	}

}
