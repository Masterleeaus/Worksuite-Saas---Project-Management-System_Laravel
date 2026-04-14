# Worksuite Module Manager Spec

Reference source: Worksuite-Site.zip deep scan.

## Canonical build rules
- Install path: `/Modules/<ModuleName>`
- Primary manifest: `module.json`
- Legacy compatibility manifest: `Config/config.php`
- Runtime toggle: `Nwidart\Modules\Facades\Module`
- Company/package access: native `modules` and `module_settings` tables
- Admin surface: existing custom module + module settings UI

## Installer rules
1. Accept nWidart module ZIPs.
2. Reject MagicAI `extension.json`-only ZIPs.
3. Validate folder name against `module.json` name.
4. Require at least one provider in `module.json`.
5. Run cache clear + module migrate after successful install when enabled.
6. Do not create a parallel marketplace runtime.

## Explicit non-goals
- No MagicAI extension registry.
- No ProjectHub marketplace dependency.
- No `resources/extensions/*` install target.
- No external extension billing runtime in this package.
