<?php

namespace Illuminate\Foundation\Exceptions;

use Exception;
use Throwable;
use Whoops\Run as Whoops;
use Illuminate\Support\Arr;
use Psr\Log\LoggerInterface;
use Illuminate\Http\Response;
use Illuminate\Routing\Router;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ViewErrorBag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\Debug\Exception\FlattenException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Console\Application as ConsoleApplication;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Debug\ExceptionHandler as SymfonyExceptionHandler;
use Illuminate\Contracts\Debug\ExceptionHandler as ExceptionHandlerContract;
use Symfony\Component\HttpFoundation\RedirectResponse as SymfonyRedirectResponse;

class Handler implements ExceptionHandlerContract
{
    /**
     * The container implementation.
     *
     * @var \Illuminate\Contracts\Container\Container
     */
    protected $container;

    /**
     * A list of the exception types tha