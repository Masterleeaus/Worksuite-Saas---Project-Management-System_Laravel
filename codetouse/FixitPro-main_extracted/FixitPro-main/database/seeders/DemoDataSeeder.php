<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tenant;
use App\Models\Subscription;
use App\Models\Job;
use Carbon\Carbon;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Seeding demo tenants...');
        
        $businesses = [
            ['name' => 'Smith Plumbing Co.', 'owner' => 'John Smith', 'plan' => 'professional', 'mrr' => 99.00],
            ['name' => 'Elite HVAC Services', 'owner' => 'Sarah Johnson', 'plan' => 'enterprise', 'mrr' => 249.00],
            ['name' => 'Quick Fix Electrical', 'owner' => 'Mike Davis', 'plan' => 'starter', 'mrr' => 49.00],
            ['name' => 'ProTech Handyman', 'owner' => 'David Wilson', 'plan' => 'professional', 'mrr' => 99.00],
            ['name' => 'AllStar Appliance Repair', 'owner' => 'Jennifer Lee', 'plan' => 'professional', 'mrr' => 99.00],
            ['name' => 'GreenLeaf Landscaping', 'owner' => 'Robert Green', 'plan' => 'starter', 'mrr' => 49.00],
            ['name' => 'Crystal Clear Windows', 'owner' => 'Amanda White', 'plan' => 'starter', 'mrr' => 49.00],
            ['name' => 'Comfort Zone HVAC', 'owner' => 'Chris Martinez', 'plan' => 'enterprise', 'mrr' => 249.00],
            ['name' => 'Bright Spark Electrical', 'owner' => 'Emily Brown', 'plan' => 'professional', 'mrr' => 99.00],
            ['name' => 'FlowMaster Plumbing', 'owner' => 'Tom Anderson', 'plan' => 'professional', 'mrr' => 99.00],
            ['name' => 'HomeTech Solutions', 'owner' => 'Lisa Taylor', 'plan' => 'enterprise', 'mrr' => 249.00],
            ['name' => 'Rapid Response Repairs', 'owner' => 'Kevin Thompson', 'plan' => 'starter', 'mrr' => 49.00],
        ];

        $jobTypes = ['repair', 'maintenance', 'installation', 'inspection', 'emergency'];
        $jobStatuses = ['pending', 'scheduled', 'in_progress', 'completed', 'canceled'];
        $priorities = ['low', 'medium', 'high', 'urgent'];

        $customerNames = ['Alice Cooper', 'Bob Builder', 'Carol Williams', 'Dan Murphy', 'Eva Schmidt', 'Frank Miller', 'Grace Chen', 'Henry Ford'];

        foreach ($businesses as $index => $business) {
            // Create with different signup dates for realistic trend
            $createdAt = Carbon::now()->subDays(rand(1, 45));
            
            $tenant = Tenant::create([
                'business_name' => $business['name'],
                'owner_name' => $business['owner'],
                'email' => strtolower(str_replace(' ', '.', $business['owner'])) . '@example.com',
                'phone' => '(555) ' . rand(100, 999) . '-' . rand(1000, 9999),
                'address' => rand(100, 9999) . ' Main Street',
                'city' => ['Los Angeles', 'New York', 'Chicago', 'Houston', 'Phoenix'][rand(0, 4)],
                'state' => ['CA', 'NY', 'IL', 'TX', 'AZ'][rand(0, 4)],
                'zip_code' => (string) rand(10000, 99999),
                'country' => 'USA',
                'plan_type' => $business['plan'],
                'status' => $index < 10 ? 'active' : 'trial',
                'trial_ends_at' => $index >= 10 ? Carbon::now()->addDays(14) : null,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);

            // Create subscription for active tenants
            if ($tenant->status === 'active') {
                Subscription::create([
                    'tenant_id' => $tenant->ID,
                    'plan_name' => ucfirst($business['plan']),
                    'monthly_amount' => $business['mrr'],
                    'status' => 'active',
                    'billing_cycle' => 'monthly',
                    'current_period_start' => Carbon::now()->startOfMonth(),
                    'current_period_end' => Carbon::now()->endOfMonth(),
                    'created_at' => $createdAt->addDay(),
                ]);
            }

            // Create 5-15 jobs per tenant
            $jobCount = rand(5, 15);
            for ($j = 0; $j < $jobCount; $j++) {
                $jobCreatedAt = Carbon::now()->subDays(rand(0, 30));
                $status = $jobStatuses[array_rand($jobStatuses)];
                
                Job::create([
                    'tenant_id' => $tenant->ID,
                    'title' => ['Water heater repair', 'AC maintenance', 'Electrical inspection', 'Pipe installation', 'Emergency leak fix', 'Thermostat replacement', 'Outlet installation', 'Drain cleaning'][rand(0, 7)],
                    'description' => 'Service request for customer property.',
                    'customer_name' => $customerNames[array_rand($customerNames)],
                    'customer_phone' => '(555) ' . rand(100, 999) . '-' . rand(1000, 9999),
                    'customer_email' => 'customer' . rand(1, 100) . '@example.com',
                    'service_address' => rand(1, 999) . ' Oak Avenue, Suite ' . rand(1, 20),
                    'job_type' => $jobTypes[array_rand($jobTypes)],
                    'status' => $status,
                    'priority' => $priorities[array_rand($priorities)],
                    'estimated_cost' => rand(50, 500),
                    'final_cost' => $status === 'completed' ? rand(50, 600) : null,
                    'scheduled_at' => $jobCreatedAt->addHours(rand(1, 48)),
                    'started_at' => in_array($status, ['in_progress', 'completed']) ? $jobCreatedAt->addHours(rand(2, 24)) : null,
                    'completed_at' => $status === 'completed' ? $jobCreatedAt->addHours(rand(3, 72)) : null,
                    'created_at' => $jobCreatedAt,
                    'updated_at' => $jobCreatedAt,
                ]);
            }
        }

        $this->command->info('Demo data seeded successfully!');
        $this->command->info('- ' . Tenant::count() . ' tenants created');
        $this->command->info('- ' . Subscription::count() . ' subscriptions created');
        $this->command->info('- ' . Job::count() . ' jobs created');
    }
}
