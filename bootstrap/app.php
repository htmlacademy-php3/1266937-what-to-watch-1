<?php

use App\Http\Responses\FailResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Middleware\EnsureUserHasRole;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => EnsureUserHasRole::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn(Request $request) => $request->is('api/*'),
        );

        $exceptions->render(
            fn(AuthenticationException $e) =>
            new FailResponse(
                message: 'Запрос требует аутентификации.',
                statusCode: 401
            )
        );

        $exceptions->render(
            fn(AuthorizationException $e) =>
            new FailResponse(
                message: 'Доступ запрещен.',
                statusCode: 403
            )
        );

        $exceptions->render(
            fn(NotFoundHttpException $e) =>
            new FailResponse(
                message: 'Запрашиваемая страница не существует.',
                statusCode: 404
            )
        );

        $exceptions->render(
            fn(AccessDeniedException $e) =>
            new FailResponse(
                message: 'Доступ запрещен.',
                statusCode: 403
            )
        );

        $exceptions->render(
            fn(ValidationException $e) =>
            new FailResponse(
                data: $e->errors(),
                message: 'Переданные данные не корректны.',
                statusCode: 422
            )
        );

        $exceptions->render(
            fn(HttpExceptionInterface $e) =>
            new FailResponse(
                message: $e->getMessage() ?: 'Ошибка запроса.',
                statusCode: $e->getStatusCode()
            )

        );

        $exceptions->render(
            fn(\Throwable $e) =>
            new FailResponse(
                message: 'Внутренняя ошибка сервера.',
                statusCode: 500
            )
        );

    })->create();
