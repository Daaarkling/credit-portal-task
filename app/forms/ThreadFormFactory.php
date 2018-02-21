<?php

namespace App\Forms;

use App\Model\Thread\IThreadFacade;
use App\Model\Thread\Thread;
use App\Model\Thread\ThreadDuplicateSlugException;
use Nette\Application\UI\Form;
use Nette\SmartObject;


class ThreadFormFactory {

	use SmartObject;


	/** @var IThreadFacade */
	private $threadFacade;

	/** @var FormFactory */
	private $formFactory;

	public function __construct(
		FormFactory $formFactory,
		IThreadFacade $threadFacade
	){
		$this->formFactory = $formFactory;
		$this->threadFacade = $threadFacade;
	}



	public function create(callable $onSuccess, Thread $thread = null): Form
	{
		$form = $this->formFactory->create();

		$form->addCheckbox('show', 'Zobrazit na webu')
			->setAttribute('class', 'happy');

		$form->addText('name', '*Název')
			->setRequired('Zadejte prosím název.');

		$form->addText('description', 'Popis');

		$form->addText('slug', '*Slug');

		$form->addSubmit('send', 'Uložit');

		$form->onSuccess[] = function (Form $form, $values) use ($onSuccess, $thread) {
			try {
				if ($thread !== null) {
					$thread->setName($values['name']);
					$thread->setSlug($values['slug']);
				} else {
					$thread = new Thread($values['name'], $values['slug']);
				}
				$thread->setDescription($values['description']);
				if ($values['show']) {
					$thread->setDeleted(null);
				} else {
					$thread->setDeleted(new \DateTime());
				}
				$this->threadFacade->save($thread);

			} catch (ThreadDuplicateSlugException $e) {
				$form->addError('Tento slug je již použit.');
				return;
			}
			$onSuccess();
		};

		return $form;
	}


}
