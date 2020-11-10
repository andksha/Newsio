<?php

namespace Newsio\Contract;

interface ApplicationException
{
    public function getMessage();
    public function getErrorData();
}
