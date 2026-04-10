<?php

namespace Modules\CustomerConnect\Http\Controllers;

use App\Http\Controllers\AccountBaseController;
use Illuminate\Http\Request;
use Modules\CustomerConnect\Database\Seeders\Recipes\QuoteFollowupRecipeSeeder;
use Modules\CustomerConnect\Database\Seeders\Recipes\InvoiceOverdueRecipeSeeder;
use Modules\CustomerConnect\Services\Recipes\RecipeInstaller;

class RecipeController extends AccountBaseController
{
    public function index()
    {
        $recipes = [
            QuoteFollowupRecipeSeeder::recipe(),
            InvoiceOverdueRecipeSeeder::recipe(),
        ];

        return view('customerconnect::recipes.index', compact('recipes'));
    }

    public function install(Request $request, RecipeInstaller $installer)
    {
        $request->validate(['key' => 'required|string']);

        $map = [
            'quote_followup' => QuoteFollowupRecipeSeeder::recipe(),
            'invoice_overdue' => InvoiceOverdueRecipeSeeder::recipe(),
        ];

        if (!isset($map[$request->key])) {
            return redirect()->back()->with('status', 'Unknown recipe.');
        }

        $installer->install(company()->id, $map[$request->key]);

        return redirect()->route('customerconnect.campaigns.index')->with('status', 'Recipe installed as a draft campaign.');
    }
}
