<?php

namespace Modules\FSMSaleRecurringAgreement\Services;

use Illuminate\Support\Collection;
use Modules\FSMRecurring\Models\FSMRecurring;
use Modules\FSMServiceAgreement\Models\FSMServiceAgreement;

class RecurringAgreementService
{
    public function link(FSMRecurring $recurring, FSMServiceAgreement $agreement): void
    {
        $recurring->update(['agreement_id' => $agreement->id]);
    }

    public function unlink(FSMRecurring $recurring): void
    {
        $recurring->update(['agreement_id' => null]);
    }

    public function getRecurringsForAgreement(int $agreementId): Collection
    {
        return FSMRecurring::where('agreement_id', $agreementId)->get();
    }
}
