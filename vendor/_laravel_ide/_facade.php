<?php

namespace Illuminate\Support\Facades;

interface Auth
{
    /**
     * @return \App\Models\UserAuth|false
     */
    public static function loginUsingId(mixed $id, bool $remember = false);

    /**
     * @return \App\Models\UserAuth|false
     */
    public static function onceUsingId(mixed $id);

    /**
     * @return \App\Models\UserAuth|null
     */
    public static function getUser();

    /**
     * @return \App\Models\UserAuth
     */
    public static function authenticate();

    /**
     * @return \App\Models\UserAuth|null
     */
    public static function user();

    /**
     * @return \App\Models\UserAuth|null
     */
    public static function logoutOtherDevices(string $password);

    /**
     * @return \App\Models\UserAuth
     */
    public static function getLastAttempted();
}