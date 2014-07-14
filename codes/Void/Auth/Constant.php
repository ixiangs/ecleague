<?php
namespace Void\Auth;


class Constant
{
    const TABLE_ACCOUNT = '{t}auth_account';
    const TABLE_ROLE = '{t}auth_role';
    const TABLE_BEHAVIOR = '{t}auth_behavior';
    const TABLE_GROUP = '{t}auth_group';

    const ERROR_ACCOUNT_NOT_FOUND = 1;
    const ERROR_ACCOUNT_PASSWORD = 2;
    const ERROR_ACCOUNT_DISABLED = 3;
    const ERROR_ACCOUNT_NONACTIVATED = 4;
    const ERROR_ACCOUNT_REPEATED = 5;
    const ERROR_UNKNOW = 99;

    const STATUS_ACCOUNT_ACTIVATED = 1;
    const STATUS_ACCOUNT_NONACTIVATED = 2;
    const STATUS_ACCOUNT_DISABLED = 3;

    const EVENT_ACCOUNT_LOGIN = 'authOnLogin';
} 