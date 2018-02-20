<?php

namespace App\Controls\Comment;

use App\Model\Thread\Thread;

interface ICommentListControlFactory {

	public function create(Thread $thread): CommentListControl;
}
