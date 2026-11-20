<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Middleware alias untuk role-based access
        $middleware->alias([
            'role'           => \App\Http\Middleware\EnsureRole::class,
            'admin'          => \App\Http\Middleware\EnsureAdmin::class,
            'instructor'     => \App\Http\Middleware\EnsureInstructor::class,
            'student'        => \App\Http\Middleware\EnsureStudent::class,
            'student.active' => \App\Http\Middleware\EnsureStudentActive::class,
        ]);

        // Append global performance middleware to the web stack
        $middleware->web(append: [
            \App\Http\Middleware\CompressResponse::class,
            \App\Http\Middleware\SetCacheHeaders::class,
        ]);

        // Redirect setelah login berdasarkan role
        $middleware->redirectUsersTo(function ($request) {
            $user = $request->user();
            if ($user) {
                return match($user->role) {
                    'admin'      => route('admin.dashboard'),
                    'instructor' => route('instructor.dashboard'),
                    'student'    => route('student.dashboard'),
                    default      => '/',
                };
            }
            return '/';
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
