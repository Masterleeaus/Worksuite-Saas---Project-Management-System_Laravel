<?php

namespace Modules\Accountings\Traits;

/**
 * Alias for CompanyScoped; provides automatic company_id scoping.
 */
trait HasCompany
{
    use CompanyScoped;
}
