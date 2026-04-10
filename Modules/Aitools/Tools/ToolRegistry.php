<?php

namespace Modules\Aitools\Tools;

use Illuminate\Support\Arr;
use Modules\Aitools\Tools\Contracts\AiToolInterface;

class ToolRegistry
{
    /** @var array<string,class-string<AiToolInterface>> */
    protected array $tools = [];

    /**
     * Register a tool class.
     *
     * @param class-string<AiToolInterface> $toolClass
     */
    public function register(string $toolClass): void
    {
        if (!is_subclass_of($toolClass, AiToolInterface::class)) {
            throw new \InvalidArgumentException("Tool must implement AiToolInterface: {$toolClass}");
        }

        $name = $toolClass::name();
        $this->tools[$name] = $toolClass;
    }

    /**
     * @return array<int,array<string,mixed>>
     */
    public function all(): array
    {
        $out = [];
        foreach ($this->tools as $name => $cls) {
            $out[] = [
                'name' => $name,
                'description' => $cls::description(),
                'schema' => $cls::schema(),
                'class' => $cls,
            ];
        }
        return $out;
    }

    /**
     * @return class-string<AiToolInterface>|null
     */
    public function resolve(string $name): ?string
    {
        return $this->tools[$name] ?? null;
    }
}
