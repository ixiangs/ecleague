<?php
namespace Toy\Log;

abstract class BaseAppender
{
    abstract function append($conent);
}