<?php

namespace BenjaminBrant\DeleteOrderComments\Logger\Handler;

use Magento\Framework\Logger\Handler\System;
use Monolog\Logger;

class DeleteCommentError extends System
{
    protected $loggerType = Logger::ERROR;
    protected $fileName = 'var/log/delete_comment/delete_comment_error.log';
}