<?php
namespace Toy\Orm\Db;

use Toy\Db;
use Toy\Util\ArrayUtil;

class SqliteProvider extends PdoProvider
{

    public function __construct($settings)
    {
        parent::__construct($settings);
    }


}
