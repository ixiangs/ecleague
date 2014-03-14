<?php
namespace Toys\Log;

abstract class BaseAppender
{
    abstract function append($conent);
}