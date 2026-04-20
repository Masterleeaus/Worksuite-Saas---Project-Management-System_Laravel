<?php

namespace App\Traits;

trait UploadSizeHelperTrait
{
    protected int $maxUploadBytes = 0;

    protected function initUploadLimits(): void
    {
        $uploadLimit = $this->toBytes((string) ini_get('upload_max_filesize'));
        $postLimit = $this->toBytes((string) ini_get('post_max_size'));

        $this->maxUploadBytes = ($uploadLimit > 0 && $postLimit > 0)
            ? min($uploadLimit, $postLimit)
            : max($uploadLimit, $postLimit);
    }

    public function validateUploadedFile($request, array $fields, string $type = 'image')
    {
        if ($this->maxUploadBytes === 0) {
            $this->initUploadLimits();
        }

        foreach ($fields as $field) {
            if (! $request->hasFile($field)) {
                continue;
            }

            $files = $request->file($field);
            $files = is_array($files) ? $files : [$files];

            foreach ($files as $file) {
                if (! $file || ! $file->isValid()) {
                    return $this->uploadValidationError($request);
                }

                if ($type === 'image' && ! str_starts_with((string) $file->getMimeType(), 'image/')) {
                    return $this->uploadValidationError($request);
                }

                if ($this->maxUploadBytes > 0 && $file->getSize() > $this->maxUploadBytes) {
                    return $this->uploadValidationError($request);
                }
            }
        }

        return true;
    }

    protected function uploadValidationError($request)
    {
        $message = __('messages.fileFormatInvalid');

        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'fail',
                'message' => $message,
            ], 413);
        }

        return back()->withErrors(['file' => $message])->withInput();
    }

    protected function toBytes(string $size): int
    {
        $size = trim($size);

        if ($size === '') {
            return 0;
        }

        $unit = strtolower(substr($size, -1));
        $value = (float) $size;

        return match ($unit) {
            'g' => (int) ($value * 1024 * 1024 * 1024),
            'm' => (int) ($value * 1024 * 1024),
            'k' => (int) ($value * 1024),
            default => (int) $value,
        };
    }
}
