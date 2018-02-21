<?php

namespace App\Forms;

use App\Model\User\IUserFacade;
use App\Model\User\User;
use App\Model\User\UserDuplicateNameException;
use App\Model\User\UserDuplicateSlugException;
use Nette\Application\UI\Form;
use Nette\SmartObject;


class UserFormFactory {

	use SmartObject;


	/** @var IUserFacade */
	private $userFacade;

	/** @var FormFactory */
	private $formFactory;

	public function __construct(
		FormFactory $formFactory,
		IUserFacade $userFacade
	){
		$this->formFactory = $formFactory;
		$this->userFacade = $userFacade;
	}



	public function create(callable $onSuccess, User $user): Form
	{
		$form = $this->formFactory->create();

		$form->addCheckbox('show', 'Umožnit přihlášení')
			->setAttribute('class', 'happy');

		$form->addCheckbox('disabled', 'Zakázat psaní příspěvků')
			->setAttribute('class', 'happy');

		$form->addText('name', '*Jméno')
			->setRequired('Zadejte prosím jméno.');

		$form->addText('email', 'E-mail')
			->setType('email')
			->setRequired('Zadejte prosím e-mail.')
			->addRule(Form::EMAIL, 'Zadejte prosím platný tvar e-mailu.');

		$form->addSubmit('send', 'Uložit');

		$form->onSuccess[] = function (Form $form, $values) use ($onSuccess, $user) {
			try {
				$user->setName($values['name']);
				$user->setEmail($values['email']);

				$user->setDisabled($values['disabled']);
				if ($values['show']) {
					$user->setDeleted(null);
				} else {
					$user->setDeleted(new \DateTime());
				}
				$this->userFacade->save($user);

			} catch (UserDuplicateNameException $e) {
				$form->addError('Tento e-mail je již zaregistrován.');
				return;
			}
			$onSuccess();
		};

		return $form;
	}


}
