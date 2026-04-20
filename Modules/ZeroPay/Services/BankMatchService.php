<?php

namespace Modules\ZeroPay\Services;

use Modules\ZeroPay\Actions\SuggestBankMatchAction;
use Modules\ZeroPay\Models\ZeroPayBankMatch;
use Modules\ZeroPay\Models\ZeroPaySession;

class BankMatchService
{
    public function __construct(private readonly ?SuggestBankMatchAction $suggestAction = null)
    {
    }

    public function queued()
    {
        return ZeroPayBankMatch::query()->where('status', 'queued');
    }

    public function accept(ZeroPayBankMatch $match): ZeroPayBankMatch
    {
        $match->status = 'accepted';
        $match->matched_at = now();
        $match->save();

        return $match;
    }

    public function suggest(ZeroPaySession $session, array $meta = []): ZeroPayBankMatch
    {
        $action = $this->suggestAction ?? app(SuggestBankMatchAction::class);

        return $action->execute($session, null, $meta);
    }
}
