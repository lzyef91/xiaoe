<?php

namespace Nldou\Xiaoe\Contracts;

interface MessageHandlerInterface
{
    /**
     * @param mixed $msg
     */
    public function handle($msg = null);
}
