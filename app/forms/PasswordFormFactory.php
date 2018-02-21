<?php

namespace App\Forms;

use App\Model\User\IUserFacade;
use App\Model\User\PasswordNotSameException;
use App\Model\User\User;
use App\Model\User\UserDuplicateNameException;
use Nette\Application\UI\Form;
use Nette\SmartObject;


class PasswordFormFactory
{
	use SmartObject;

	const PASSWORD_MIN_LENGTH = 8;

	/** @var FormFactory */
	private $factory;

	/** @var IUserFacade */
	private $userFacade;

	/** @var \Nette\Security\User */
	private $user;


	public function __construct(
		FormFactory $factory,
		IUserFacade $userFacade,
		\Nette\Security\User $user
	){
		$this->factory = $factory;
		$this->userFacade = $userFacade;
		$this->user = $user;
	}


	/**
	 * @param callable $onSuccess
	 * @return Form
	 */
	public function create(callable $onSuccess): Form
	{
		$form = $this->factory->create();

		$form->addPassword('oldPassword', '*Stávající heslo')
			->setRequired('Zadejte prosím své stávající heslo.')
			->addRule($form::MIN_LENGTH, 'Heslo musí být minimálně %d znaků dlouhé.', self::PASSWORD_MIN_LENGTH);


		$form->addPassword('newPassword', '*Nové heslo')
			->setRequired('Zadejte prosím své nové heslo.')
			->addRule($form::MIN_LENGTH, 'Heslo musí být minimálně %d znaků dlouhé.', self::PASSWORD_MIN_LENGTH);

		$form->addPassword('verify', '*Heslo pro kontrolu')
			->setOmitted()
			->setRequired('Zadejte prosím nové heslo ještě jednou pro kontrolu.')
			->addRule(Form::EQUAL, 'Hesla se neshodují', $form['newPassword']);

		$form->addSubmit('send', 'Změnit heslo');

		$form->onSuccess[] = function (Form $form, $values) use ($onSuccess) {
			try {
				$this->userFacade->changePassword($values['oldPassword'], $values['newPassword']);
			} catch (PasswordNotSameException $e) {
				$form->addError('Stavající heslo není správné.');
				return;
			}
			$onSuccess();
		};

		return $form;
	}
}
