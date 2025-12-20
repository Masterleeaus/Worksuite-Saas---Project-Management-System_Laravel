<?php

namespace Illuminate\Http;

interface Request
{
    /**
     * @return \App\Models\UserAuth|null
     */
    public function user($guard = null);
}