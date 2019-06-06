<?php

namespace think\exception;


use think\Exception;
use think\Log;

class IbnAjaxException extends Exception {

    /**
     * 返回给客户端的信息
     * @var string
     */
    private $clientMessage;

    public function __construct($clientMessage, $message = '', $code = 1, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
        $this->clientMessage = $clientMessage;
    }

    public function getClientMessage() {
        return $this->clientMessage;
    }
}