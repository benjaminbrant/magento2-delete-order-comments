<?php

namespace BenjaminBrant\DeleteOrderComments\Logger\Handler;

use Magento\Framework\Logger\Handler\System;
use Monolog\Logger;

class DeleteCommentInfo extends System
{
    protected $loggerType = Logger::INFO;
    protected $fileName = 'var/log/delete_comment/delete_comment_info.log';
}