<?php

namespace App\Http\Controllers;

use Specialtactics\L5Api\Http\Controllers\RestfulController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;

class BaseController extends RestfulController
{
    protected function badRequestException(?string $msg = null): void
    {
        throw new BadRequestHttpException($msg);
    }

    protected function resourceNotFoundException(?string $msg = null): void
    {
        throw new NotFoundHttpException($msg);
    }

    protected function serviceUnavailableException(?string $msg = null): void
    {
        throw new ServiceUnavailableHttpException(null, $msg);
    }
}
