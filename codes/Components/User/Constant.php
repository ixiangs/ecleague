<?php
namespace Components\User;


class Constant
{
    const TABLE_ACCOUNT = '{t}user_account';
    const TABLE_ROLE = '{t}user_role';
    const TABLE_BEHAVIOR = '{t}user_behavior';

    const ERROR_ACCOUNT_NOT_FOUND = 1;
    const ERROR_ACCOUNT_PASSWORD = 2;
    const ERROR_ACCOUNT_DISABLED = 3;
    const ERROR_ACCOUNT_NONACTIVATED = 4;
    const ERROR_ACCOUNT_REPEATED = 5;
    const ERROR_UNKNOW = 99;

    const STATUS_ACCOUNT_ACTIVATED = 1;
    const STATUS_ACCOUNT_NONACTIVATED = 2;
    const STATUS_ACCOUNT_DISABLED = 3;
} 