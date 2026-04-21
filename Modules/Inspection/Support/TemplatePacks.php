<?php

namespace Modules\Inspection\Support;

/**
 * @deprecated Use Modules\QualityControl\Support\TemplatePacks (canonical source).
 *
 * Template pack definitions are now owned by QualityControl's template registry.
 * This class delegates to the canonical QC implementation so that the Inspection
 * module's existing code continues to work without change.
 *
 * @see Modules\QualityControl\Support\TemplatePacks::defaultPacks()
 */
class TemplatePacks
{
    /**
     * Returns the canonical set of default inspection template packs.
     *
     * @deprecated Delegates to Modules\QualityControl\Support\TemplatePacks::defaultPacks().
     */
    public static function defaultPacks(): array
    {
        return \Modules\QualityControl\Support\TemplatePacks::defaultPacks();
    }
}
