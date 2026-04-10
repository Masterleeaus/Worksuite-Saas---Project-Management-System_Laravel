<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class NoExecutableFilesValidateRule implements Rule
{
    public function passes($attribute, $value)
    {
        // List of dangerous MIME types
        $dangerousMimes = [
            'application/x-php',
            'text/x-php',
            'text/html',
            'application/javascript',
            'application/x-executable',
            'application/x-sh',
            'application/x-msdownload',
        ];

        // Check MIME type of uploaded file
        if ($value && in_array($value->getMimeType(), $dangerousMimes)) {
            return false;
        }

        return true;
    }

    public function message()
    {
        return 'Executable or script files are not allowed for upload.';
    }
}
