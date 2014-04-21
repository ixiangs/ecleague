<?php
namespace Toy\Web\Interfaces;

interface IRouter
{

    function route($url = null);

    function buildUrl($url = "", $params = NULL);

    function parseUrl($url);
}
