# Inventory (Worksuite SaaS Module) — v1.5
Baseline + Transfers, Suppliers, Purchasing (PO → GRN), Stock Levels, and Reorder mins/max.
- Sidebar is plan-gated via `inventory` module flag and permissions.
- New tables: suppliers, stock_levels, purchase_orders (+ items), goods_receipts (+ items), transfers.
- Simple GRN receive updates StockLevel + Movement IN; Transfer approves as OUT+IN pair.

## Install / Upgrade
1. Upload to `Modules/Inventory` or install via Super Admin → Modules → Install.
2. Run:
   php artisan optimize:clear
   php artisan migrate --force
   php artisan db:seed --class="Modules\Inventory\Database\Seeders\InventorySeeder"
3. Grant permissions in Roles & Permissions:
   - inventory.view / inventory.manage
   - inventory.suppliers.*
   - inventory.purchasing.*
   - inventory.transfer.*

## Notes
- Costing: the current build records costs on GRN lines; valuation reports are a future step.
- Reorder: set min/max on stock_levels; later we’ll add a “Replenish” suggestion screen.
