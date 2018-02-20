<?php
declare(strict_types=1);


namespace App\Model;

use Nette\Application\ForbiddenRequestException;

class ActionNotAllowedException extends ForbiddenRequestException
{

}