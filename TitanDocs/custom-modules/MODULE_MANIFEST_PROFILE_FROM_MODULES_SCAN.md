# Modules ZIP manifest profile

Deep scan source: `modules.zip`

## Findings
- `module.json` count: 78
- Canonical target root: `/Modules/<ModuleName>`
- Typical keys: `name`, `alias`, `priority`, `providers`, `files`
- Real-world variations confirmed:
  - `priority` may be `null`
  - `files` may be `[]`, omitted, or `null`
  - `alias` may contain hyphens
  - some ZIPs contain nested module roots and require path resolution beyond the first folder

## Installer policy from scan
- Require `module.json` or legacy `Config/config.php`
- Prefer `module.json` when both exist
- Treat provider lists as required for nWidart validation
- Accept `files: null` as valid
- Compare extracted folder and manifest `name` case-insensitively
- Reject `extension.json`-only packages
- Resolve nested module roots by manifest match before falling back to first discovered folder
