<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Validation\ValidationException;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Decode a JSON request field into an array, surfacing malformed input
     * as a validation error instead of silently swallowing it.
     *
     * A raw json_decode() returns null on malformed JSON, which callers tend
     * to treat as "empty" and report with a misleading message (or pass a
     * null straight through to a service). This distinguishes an absent value
     * from an invalid one so the real error is propagated to the user.
     *
     * @return array<mixed>
     */
    protected function decodeJsonArray(?string $json, string $field): array
    {
        if ($json === null || trim($json) === '') {
            return [];
        }

        $decoded = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw ValidationException::withMessages([
                $field => 'Invalid data submitted ('.json_last_error_msg().'). Please try again.',
            ]);
        }

        if (! is_array($decoded)) {
            throw ValidationException::withMessages([
                $field => 'Invalid data submitted. Expected a list of records.',
            ]);
        }

        return $decoded;
    }
}
