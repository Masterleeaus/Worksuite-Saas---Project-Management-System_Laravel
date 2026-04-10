<?php

namespace App\Components;

use Exception;
use Illuminate\Support\Str;

class UploadAttachmentComponent
{
    public static function uploadAttachment($request, $field, $directory, $allowedFileExtensions)
    {
        if (!$request->hasFile($field)) {
            return null;
        }

        $file = $request->file($field);

        if ($errorMessage = self::NoExecutableFilesValidate($file)) {
            throw new Exception($errorMessage);
        }

        $extension = strtolower($file->getClientOriginalExtension());
        
        // Validate extension
        if (!in_array($extension, $allowedFileExtensions)) {
            $fieldLabel = str_replace('_', ' ', $field);
            throw new Exception('Please select a valid ' . $fieldLabel . ' file.');
        }

        $filename = time() . '_' . self::sanitizeFileNameHelper($file->getClientOriginalName());

        // Store the file using storage disk (default is local/app)
        // User requested storage/storeAs
        $path = $file->storeAs($directory, $filename, 'public');

        return $filename;
    }

    public static function NoExecutableFilesValidate($file)
    {
        $dangerousMimes = [
            'application/x-php',
            'text/x-php',
            'text/html',
            'application/javascript',
            'application/x-executable',
        ];

        if (in_array($file->getMimeType(), $dangerousMimes)) {
            return 'Executable or script files are not allowed.';
        }
    }

    public static function sanitizeFileNameHelper($fileName)
    {
        return preg_replace('/[^a-zA-Z0-9_.]/', '_', $fileName);
    }
}
