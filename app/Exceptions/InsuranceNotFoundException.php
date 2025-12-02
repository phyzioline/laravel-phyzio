<?php

namespace App\Exceptions;

use Exception;

class InsuranceNotFoundException extends Exception
{
    public function render($request)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => $this->getMessage() ?: 'Not found',
                'type' => 'error',
                'code' => $this->getCode() ?: 400,
            ], $this->getCode() ?: 400);
        }

        return response()->view('errors.insurance-not-found', [
            'message' => $this->getMessage() ?: 'Insurance not found'
        ], 400);
    }
}
