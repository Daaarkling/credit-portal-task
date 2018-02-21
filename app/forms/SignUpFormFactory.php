<?php

namespace App\Forms;

use App\Model\User\IUserFacade;
use App\Model\User\User;
use App\Model\User\UserDuplicateNameException;
use Nette\Application\UI\Form;
use Nette\SmartObject;


class SignUpFormFactory
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
		$form->addText('name', '*Jméno')
			->setRequired('Zadejte prosím své jméno.');

		$form->addEmail('email', '*E-mail')
			->setType('email')
			->setRequired('Zadejte prosím svůj e-mail.')
			->addRule(Form::EMAIL, 'Zadejte prosím platný tvar e-mailu.');

		$form->addPassword('password', '*Heslo')
			->setRequired('Zadejte prosím heslo.')
			->addRule($form::MIN_LENGTH, 'Heslo musí být minimálně %d znaků dlouhé.', self::PASSWORD_MIN_LENGTH);

		$form->addPassword('verify', '*Heslo pro kontrolu')
			->setOmitted()
			->setRequired('Zadejte prosím heslo ještě jednou pro kontrolu.')
			->addRule(Form::EQUAL, 'Hesla se neshodují', $form['password']);

		$form->addSubmit('send', 'Registrovat');

		$form->onSuccess[] = function (Form $form, $values) use ($onSuccess) {
			try {
				$user = new User($values['name'], $values['email'], $values['password'], User::ROLE_MEMBER);
				$user =$this->userFacade->save($user);
				if (!$this->user->isInRole(User::ROLE_ADMIN)) {
					$this->user->login($user->getEmail(), $values['password']);
				}
			} catch (UserDuplicateNameException $e) {
				$form['email']->addError('Tento e-mail je již zaregistrován.');
				return;
			}
			$onSuccess();
		};

		return $form;
	}
}
