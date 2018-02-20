<?php

namespace App\Controls;

use Nette\Application\UI\Control;
use Nette\Security\User;
use Nextras\Application\UI\SecuredLinksControlTrait;


abstract class BaseControl extends Control {

	use SecuredLinksControlTrait;

	/** @var User */
	protected $user;

	public function setUser(User $user)
	{
		$this->user = $user;
	}

	public function getUser(): User
	{
		return $this->user;
	}
}
