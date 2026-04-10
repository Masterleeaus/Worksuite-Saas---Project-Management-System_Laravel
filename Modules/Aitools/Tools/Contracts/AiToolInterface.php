<?php

namespace Modules\Aitools\Tools\Contracts;

use Modules\Aitools\Tools\DTO\AitoolsContext;

interface AiToolInterface
{
    /**
     * Stable tool name, used for lookup/calling.
     */
    public static function name(): string;

    /**
     * Short description for the model / UI.
     */
    public static function description(): string;

    /**
     * JSON-schema-like input specification.
     * Keep it simple for MVP.
     *
     * @return array<string,mixed>
     */
    public static function schema(): array;

    /**
     * Execute the tool and return a serializable result array.
     *
     * @param array<string,mixed> $args
     * @return array<string,mixed>
     */
    public function execute(AitoolsContext $ctx, array $args): array;
}
