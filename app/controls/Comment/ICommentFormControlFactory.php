<?php

namespace App\Controls\Comment;

use App\Model\Thread\Thread;

interface ICommentFormControlFactory {

	public function create(Thread $thread): CommentFormControl;
}
