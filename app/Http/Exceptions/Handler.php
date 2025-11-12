<?php

namespace App\Http\Exceptions;
use Illuminate\Auth\AuthenticationException;

class Handler
{
	protected function unauthenticated($request, AuthenticationException $exception)
	{
		if ($request->expectsJson()) {
			return response()->json(['message' => 'Unauthenticated'], 401);
		}

		return response()->json(['message' => 'Unauthenticated'], 401);
	}
}