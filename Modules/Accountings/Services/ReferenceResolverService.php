<?php

namespace Modules\Accountings\Services;

use Illuminate\Support\Facades\Log;

/**
 * Maps string reference keys (visit_ref, site_ref, contract_ref, job_ref) used by
 * the accounting engine to their canonical integer IDs in upstream modules.
 *
 * All lookups are guarded against missing module classes so the accounting engine
 * continues to function even when optional modules (FSMProject, ManagedPremises,
 * ServiceAgreements) are not installed.
 *
 * Usage:
 *   $resolver = app(ReferenceResolverService::class);
 *   $visitId  = $resolver->resolveVisitId('VISIT-ABC-123', $companyId);
 */
class ReferenceResolverService
{
    // ------------------------------------------------------------------
    // Visit / Job resolution
    // ------------------------------------------------------------------

    /**
     * Resolve a visit_ref string to the upstream FSM visit / order ID.
     * Returns null if the upstream module is not installed or the record
     * cannot be found.
     */
    public function resolveVisitId(string $visitRef, int $companyId): ?int
    {
        return $this->tryResolve(function () use ($visitRef, $companyId): ?int {
            // FSMProject stores visits as fsm_orders with a `visit_ref` or `order_number`.
            $classes = [
                'Modules\\FSMProject\\Entities\\FsmVisit',
                'Modules\\FSMProject\\Entities\\FsmOrder',
            ];

            foreach ($classes as $class) {
                if (!class_exists($class)) {
                    continue;
                }

                /** @var \Illuminate\Database\Eloquent\Model $model */
                $model = new $class();
                $refColumn = in_array('visit_ref', array_keys($model->getCasts()))
                    ? 'visit_ref'
                    : 'order_number';

                $record = $class::query()
                    ->where('company_id', $companyId)
                    ->where($refColumn, $visitRef)
                    ->value('id');

                if ($record) {
                    return (int) $record;
                }
            }

            return null;
        }, 'resolveVisitId', $visitRef);
    }

    /**
     * Resolve a job_ref string to the upstream project / job ID.
     */
    public function resolveJobId(string $jobRef, int $companyId): ?int
    {
        return $this->tryResolve(function () use ($jobRef, $companyId): ?int {
            // Core worksuite projects table uses numeric IDs; job_ref may be a UUID or slug.
            if (class_exists(\App\Models\Project::class)) {
                $id = \App\Models\Project::query()
                    ->where('company_id', $companyId)
                    ->where(function ($q) use ($jobRef) {
                        $q->where('id', $jobRef)
                          ->orWhere('project_short_code', $jobRef);
                    })
                    ->value('id');

                if ($id) {
                    return (int) $id;
                }
            }

            return null;
        }, 'resolveJobId', $jobRef);
    }

    // ------------------------------------------------------------------
    // Site resolution
    // ------------------------------------------------------------------

    /**
     * Resolve a site_ref string to the upstream ManagedPremises / client ID.
     */
    public function resolveSiteId(string $siteRef, int $companyId): ?int
    {
        return $this->tryResolve(function () use ($siteRef, $companyId): ?int {
            $candidates = [
                'Modules\\ManagedPremises\\Entities\\ManagedPremise',
                'Modules\\ManagedPremises\\Entities\\Premise',
            ];

            foreach ($candidates as $class) {
                if (!class_exists($class)) {
                    continue;
                }

                $id = $class::query()
                    ->where('company_id', $companyId)
                    ->where(function ($q) use ($siteRef) {
                        $q->where('id', $siteRef)
                          ->orWhere('reference', $siteRef)
                          ->orWhere('premise_ref', $siteRef);
                    })
                    ->value('id');

                if ($id) {
                    return (int) $id;
                }
            }

            return null;
        }, 'resolveSiteId', $siteRef);
    }

    // ------------------------------------------------------------------
    // Contract / service-agreement resolution
    // ------------------------------------------------------------------

    /**
     * Resolve a contract_ref string to the upstream contract or service-agreement ID.
     */
    public function resolveContractId(string $contractRef, int $companyId): ?int
    {
        return $this->tryResolve(function () use ($contractRef, $companyId): ?int {
            // Core app Contract model
            if (class_exists(\App\Models\Contract::class)) {
                $id = \App\Models\Contract::query()
                    ->where('company_id', $companyId)
                    ->where(function ($q) use ($contractRef) {
                        $q->where('id', $contractRef)
                          ->orWhere('contract_number', $contractRef);
                    })
                    ->value('id');

                if ($id) {
                    return (int) $id;
                }
            }

            // ServiceAgreements module
            $saClasses = [
                'Modules\\ServiceAgreements\\Entities\\ServiceAgreement',
                'Modules\\CleaningContracts\\Entities\\CleaningContract',
            ];

            foreach ($saClasses as $class) {
                if (!class_exists($class)) {
                    continue;
                }

                $id = $class::query()
                    ->where('company_id', $companyId)
                    ->where(function ($q) use ($contractRef) {
                        $q->where('id', $contractRef)
                          ->orWhere('agreement_ref', $contractRef)
                          ->orWhere('contract_ref', $contractRef);
                    })
                    ->value('id');

                if ($id) {
                    return (int) $id;
                }
            }

            return null;
        }, 'resolveContractId', $contractRef);
    }

    // ------------------------------------------------------------------
    // Batch enrichment
    // ------------------------------------------------------------------

    /**
     * Enrich an accounting payload array with resolved upstream IDs.
     * Only adds keys that could be resolved; does NOT overwrite existing keys.
     */
    public function enrich(array $payload, int $companyId): array
    {
        if (!empty($payload['visit_ref']) && empty($payload['visit_id'])) {
            $id = $this->resolveVisitId($payload['visit_ref'], $companyId);
            if ($id) {
                $payload['visit_id'] = $id;
            }
        }

        if (!empty($payload['job_ref']) && empty($payload['job_id'])) {
            $id = $this->resolveJobId($payload['job_ref'], $companyId);
            if ($id) {
                $payload['job_id'] = $id;
            }
        }

        if (!empty($payload['site_ref']) && empty($payload['site_id'])) {
            $id = $this->resolveSiteId($payload['site_ref'], $companyId);
            if ($id) {
                $payload['site_id'] = $id;
            }
        }

        if (!empty($payload['contract_ref']) && empty($payload['contract_id'])) {
            $id = $this->resolveContractId($payload['contract_ref'], $companyId);
            if ($id) {
                $payload['contract_id'] = $id;
            }
        }

        return $payload;
    }

    // ------------------------------------------------------------------
    // Internal helpers
    // ------------------------------------------------------------------

    private function tryResolve(callable $fn, string $method, string $ref): ?int
    {
        try {
            return $fn();
        } catch (\Throwable $e) {
            Log::debug("[ReferenceResolverService::{$method}] Could not resolve ref '{$ref}'", [
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }
}
