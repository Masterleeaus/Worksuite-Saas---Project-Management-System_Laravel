<?php

namespace App\Mcp\Contracts;

interface McpTool
{
    /**
     * Unique tool name exposed to the AI (snake_case).
     */
    public function name(): string;

    /**
     * Human-readable description of what this tool does.
     */
    public function description(): string;

    /**
     * JSON Schema object describing the tool's input parameters.
     *
     * @return array<string, mixed>
     */
    public function inputSchema(): array;

    /**
     * Execute the tool with the given validated arguments.
     *
     * @param  array<string, mixed>  $arguments
     * @return array<string, mixed>  MCP content array
     */
    public function handle(array $arguments): array;
}
