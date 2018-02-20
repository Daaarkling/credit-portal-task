<?php

namespace App\Controls\Comment;

use App\Controls\BaseControl;
use App\Forms\FormFactory;
use App\Forms\SignInFormFactory;
use App\Model\ActionNotAllowedException;
use App\Model\Comment\Comment;
use App\Model\Comment\ICommentFacade;
use App\Model\Comment\ICommentRepository;
use App\Model\Thread\Thread;
use App\Model\User\IUserRepository;
use Nette\Application\UI\Form;
use Nette\Utils\ArrayHash;
use Ramsey\Uuid\Uuid;


class CommentFormControl extends BaseControl {

	const KEY = 'Kukacka';

	/** @var Thread */
	private $thread;

	/** @var FormFactory */
	private $factory;

	/** @var ICommentFacade */
	private $commentFacade;

	/** @var SignInFormFactory */
	private $signInFormFactory;

	/** @var array */
	public $onPosted;

	/** @var array */
	public $onError;

	/** @var array */
	public $onLoggedIn;

	/** @var ICommentRepository */
	private $commentRepository;

	/** @var IUserRepository */
	private $userRepository;


	public function __construct(
		Thread $thread,
		FormFactory $factory,
		ICommentFacade $commentFacade,
		SignInFormFactory $signInFormFactory,
		IUserRepository $userRepository,
		ICommentRepository $commentRepository
	){
		parent::__construct();
		$this->factory = $factory;
		$this->commentFacade = $commentFacade;
		$this->thread = $thread;
		$this->signInFormFactory = $signInFormFactory;
		$this->userRepository = $userRepository;
		$this->commentRepository = $commentRepository;
	}


	/**
	 * Sign-in form factory.
	 * @return Form
	 */
	protected function createComponentSignInForm(): Form
	{
		$form = $this->signInFormFactory->create(function () {
			$this->flashMessage('Byl jste úspěšně přihlášen.', 'warning');
			if ($this->isControlInvalid()) {
				$this->redirect('this');
			}
			$this->onLoggedIn();
		});
		$form->onError[] = function () {
			$this->redrawControl('commentSign');
		};
		return $form;
	}


	/**
	 * @return Form
	 */
	protected function createComponentCommentForm(): Form
	{
		$form = $this->factory->create();
		/*
		$form->addText('author', '*Jméno')
			->setRequired('Zadejte prosím své jméno.');

		$form->addText('email', '*E-mail')
			->setRequired('Zadejte prosím svůj e-mail.')
			->addRule(Form::EMAIL, 'Zadejte prosím platný e-mail.');
		*/
		$form->addHidden('comment');

		$form->addTextArea('text', '*Zpráva')
			->setRequired('Napište prosím text zprávy.');


		$form->addSubmit('preview', 'Náhled');
		$form->addSubmit('send', 'Poslat');

		// Protection
		$form->addText('firstname')
			->setRequired(false)
			->addRule(Form::BLANK)
			->setAttribute('class', 'hidden');

		// Protection
		$form->addText('phone')
			->setRequired(true)
			->addRule(Form::EQUAL, 'Zadejte prosím slovo %s', self::KEY)
			->setAttribute('data-phone', self::KEY);

		$form->onSuccess[] = [$this, 'formPosted'];

		return $form;
	}

	public function formPosted(Form $form, ArrayHash $values)
	{
		try {
			if ($values['comment']) {
				$comment = $this->commentRepository->getById(Uuid::fromString($values['comment']));
				$comment->setText($values['text']);
				$comment = $this->commentFacade->save($comment);
				$this->flashMessage('Příspěvek byl upraven.', 'warning');
			} else {
				$entityUser = $this->userRepository->getById($this->user->getId());
				$comment = new Comment($entityUser, $this->thread, $values['text']);
				$comment = $this->commentFacade->save($comment);
				$this->flashMessage('Příspěvek byl přidán.', 'warning');
			}
			if (!$this->isControlInvalid()) {
				$this['commentForm']->setValues([], true);
				$this->redrawControl('flashes');
				$this->redrawControl('commentForm');
			}

			$this->onPosted($comment);
		} catch (ActionNotAllowedException $e) {
			$this->onError();
		}
	}


	public function render()
	{
		$this->template->setFile(__DIR__ . '/template/comment_form.latte');
		$this->template->render();
	}


	public function editComment(Comment $comment = null)
	{
		if ($comment !== null) {
			$this['commentForm-text']->setDefaultValue($comment->getText());
			$this['commentForm-comment']->setDefaultValue($comment->getId()->toString());
		}
	}
}
