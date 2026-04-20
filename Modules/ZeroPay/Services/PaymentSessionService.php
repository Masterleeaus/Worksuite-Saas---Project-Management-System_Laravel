<?php

namespace Modules\ZeroPay\Services;

use Modules\ZeroPay\Actions\ConfirmBankTransferAction;
use Modules\ZeroPay\Actions\CreatePaymentSessionAction;
use Modules\ZeroPay\Actions\MarkCashReceivedAction;
use Modules\ZeroPay\Actions\RecordGatewayCallbackAction;
use Modules\ZeroPay\Actions\ResendPaymentSessionAction;
use Modules\ZeroPay\Actions\SelectPaymentMethodAction;
use Modules\ZeroPay\Models\ZeroPayAttempt;
use Modules\ZeroPay\Models\ZeroPaySession;

class PaymentSessionService
{
    public function __construct(
        private readonly PaymentRailResolverService $resolver,
        private readonly DownloadTokenService $downloadTokens,
        private readonly FollowupOrchestratorService $followups,
        private readonly VoiceFollowupService $voiceFollowups,
        private readonly BankMatchService $bankMatch,
        private readonly \Modules\ZeroPay\Actions\PostPaymentToWorksuiteAction $postPayment,
    ) {
    }

    public function create(array $payload): ZeroPaySession
    {
        $session = app(CreatePaymentSessionAction::class)->execute($payload);

        $this->downloadTokens->create($session, 'invoice');
        $this->downloadTokens->create($session, 'receipt');

        return $session->fresh(['invoice', 'attempts', 'downloadTokens']);
    }

    public function selectMethod(ZeroPaySession $session, string $method): ZeroPayAttempt
    {
        return app(SelectPaymentMethodAction::class)->execute($session, $method);
    }

    public function resend(ZeroPaySession $session)
    {
        return app(ResendPaymentSessionAction::class)->execute($session);
    }

    public function markCash(ZeroPaySession $session)
    {
        return app(MarkCashReceivedAction::class)->execute($session);
    }

    public function confirmBank(ZeroPaySession $session)
    {
        return app(ConfirmBankTransferAction::class)->execute($session);
    }

    public function queueVoice(ZeroPaySession $session)
    {
        return $this->voiceFollowups->queue($session);
    }

    public function confirmAttempt(ZeroPayAttempt $attempt, array $payload = [])
    {
        return $this->postPayment->execute($attempt, $payload);
    }

    public function recordCallback(ZeroPayAttempt $attempt, string $gateway, array $payload): array
    {
        return app(RecordGatewayCallbackAction::class)->execute($attempt, $gateway, $payload);
    }

    public function queueEmail(ZeroPaySession $session, string $message)
    {
        return $this->followups->queueEmail($session, $message);
    }
}
