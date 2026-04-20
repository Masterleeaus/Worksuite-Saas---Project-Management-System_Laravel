<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Helper\Reply;
use App\Http\Requests\SuperAdmin\Register\StoreRequest;
use App\Models\Company;
use App\Models\Currency;
use App\Models\EmployeeDetails;
use App\Models\GlobalSetting;
use App\Models\Role;
use App\Models\SuperAdmin\GlobalCurrency;
use App\Models\SuperAdmin\SeoDetail;
use App\Models\SuperAdmin\SignUpSetting;
use App\Models\SuperAdmin\TrFrontDetail;
use App\Models\UniversalSearch;
use App\Models\User;
use App\Models\UserAuth;
use App\Notifications\NewUser;
use App\Scopes\ActiveScope;
use App\Scopes\CompanyScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Symfony\Component\Mailer\Exception\TransportException;

class CompanyRegisterController extends FrontBaseController
{

    public function index()
    {
        $this->global = GlobalSetting::first();

        $user = user();

        if ($user) {
            if (!is_null($user->company_id)) {
                return redirect(getDomainSpecificUrl(route('login'), $user->company));
            }

            // Redirect superadmin
            return redirect(getDomainSpecificUrl(route('login')));
        }

        $this->seoDetail = SeoDetail::where('page_name', 'home')->first();
        $this->pageTitle = __('app.signup');

        $view = ($this->setting->front_design == 1) ? 'super-admin.saas.register' : 'super-admin.front.register';


        if ($this->global->frontend_disable || $this->global->setup_homepage == 'custom') {
            $view = 'super-admin.register';
        }


        $this->trFrontDetail = TrFrontDetail::where('language_setting_id', $this->localeLanguage->id)->first();
        $this->trFrontDetail = $this->trFrontDetail ?: TrFrontDetail::where('language_setting_id', $this->enLocaleLanguage->id)->first();

        $signUpCount = SignUpSetting::select('id', 'language_setting_id')->where('language_setting_id', $this->localeLanguage ? $this->localeLanguage->id : null)->count();
        $this->signUpMessage = SignUpSetting::where('language_setting_id', $signUpCount > 0 ? ($this->localeLanguage ? $this->localeLanguage->id : null) : null)->first();

        $this->registrationStatus = $this->global;

        return view($view, $this->data);
    }

    public function store(StoreRequest $request)
    {

        $global = GlobalSetting::first();

        if (!$global->registration_open) {
            abort_403('Registration Disabled');
        }

        if ($global->google_recaptcha_status == 'active' && !$this->recaptchaValidate($request)) {
            return Reply::error('Recaptcha not validated.');
        }

        DB::beginTransaction();

        try {
            $company = new Company();
            $company->company_name = $request->company_name;
            $company->company_email = $request->email;
            $company->address = $request->company_name;
            $company->app_name = $request->company_name;

            if (module_enabled('Subdomain')) {
                $company->sub_domain = strtolower($request->sub_domain);
            }

            $company->save();
            $this->ensureCompanyCurrency($company);

            $user = $this->addUser($company, $request, $global);

            DB::commit();

            if (!$global->company_need_approval) {
                if (!module_enabled('Subdomain')) {
                    Auth::loginUsingId($user->user_auth_id);
                }
            } else {
                session()->flash('company_approval_pending', __('auth.failedCompanyUnapproved'));

                return Reply::redirect(route('front.signup.index'));
            }
        } catch (TransportException $e) {
            DB::rollback();

            return Reply::error('Please contact administrator to set SMTP details to add company.<br>' . $e->getMessage(), 'smtp_error');
        } catch (\Exception $e) {
            DB::rollback();

            return Reply::error('Some error occurred when inserting the data. Please try again or contact support: ' . $e->getMessage());
        }

        return Reply::redirect(getDomainSpecificUrl(route('login'), $company), __('superadmin.signUpThankYou'));
    }

    public function getEmailVerification($code)
    {
        $this->pageTitle = 'modules.accountSettings.emailVerification';
        $this->message = User::emailVerify($code);

        return view('auth.email-verification', $this->data);
    }

    public function addUser($company, $request, $global)
    {
        // Save Admin
        $user = User::withoutGlobalScopes([CompanyScope::class, ActiveScope::class])
            ->where('company_id', $company->id)
            ->where('email', $request->email)
            ->first();

        if (is_null($user)) {
            $user = new User();
        }

        $userAuth = UserAuth::createUserAuthCredentials($request->email);

        $countryId = User::firstSuperAdmin()?->country_id ?? null;
        $user->company_id = $company->id;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->status = 'active';
        $user->is_superadmin = 0;
        $user->user_auth_id = $userAuth->id;
        $user->locale = $company->locale;
        $user->country_id = $countryId;
        $user->save();

        if ($global->email_verification && !$global->company_need_approval && !module_enabled('Subdomain')) {
            $userAuth->sendEmailVerificationNotification();
        }

        if ($request->password != '') {
            UserAuth::where('id', $user->user_auth_id)->update(['password' => bcrypt($request->password)]);
            $user->notify(new NewUser($user, $request->password, signup: true));
        }

        $adminRole = $this->ensureCompanyRole($company, 'admin', 'App Administrator', 'Admin is allowed to manage everything of the app.');
        $employeeRole = $this->ensureCompanyRole($company, 'employee', 'Employee', 'Employee can see tasks and projects assigned to him.');

        $hasCompanyAdminRole = $user->roles()
            ->withoutGlobalScope(CompanyScope::class)
            ->where('roles.name', 'admin')
            ->where('roles.company_id', $company->id)
            ->exists();

        $user->roles()->syncWithoutDetaching([$employeeRole->id]);
        $this->addEmployeeDetails($user, $company->id);

        if (!$hasCompanyAdminRole) {
            $user->roles()->syncWithoutDetaching([$adminRole->id]);
            $user->assignUserRolePermission($adminRole->id);
        }

        return $user;
    }

    private function addEmployeeDetails($user, $companyId)
    {
        $employee = EmployeeDetails::firstOrNew(['user_id' => $user->id]);
        $employee->company_id = $companyId;
        $employee->employee_id = $employee->employee_id ?: ('EMP-' . $user->id);
        $employee->save();

        $search = UniversalSearch::firstOrNew([
            'searchable_id' => $user->id,
            'company_id' => $companyId,
            'route_name' => 'employees.show',
        ]);
        $search->title = $user->name;
        $search->save();
    }

    private function ensureCompanyCurrency(Company $company): void
    {
        $currentCurrency = null;

        if ($company->currency_id) {
            $currentCurrency = Currency::withoutGlobalScope(CompanyScope::class)
                ->where('id', $company->currency_id)
                ->where('company_id', $company->id)
                ->first();
        }

        if ($currentCurrency) {
            return;
        }

        $globalCurrencyCode = optional(global_setting()->currency)->currency_code;
        $currencyQuery = Currency::withoutGlobalScope(CompanyScope::class)->where('company_id', $company->id);

        if ($globalCurrencyCode) {
            $currencyQuery->where('currency_code', $globalCurrencyCode);
        }

        $currency = $currencyQuery->first();

        if (!$currency) {
            $globalCurrency = $globalCurrencyCode ? GlobalCurrency::where('currency_code', $globalCurrencyCode)->first() : null;
            $globalCurrency = $globalCurrency ?: GlobalCurrency::first();

            if ($globalCurrency) {
                $currency = Currency::withoutGlobalScope(CompanyScope::class)->firstOrCreate(
                    [
                        'company_id' => $company->id,
                        'currency_code' => $globalCurrency->currency_code,
                    ],
                    [
                        'currency_name' => $globalCurrency->currency_name,
                        'currency_symbol' => $globalCurrency->currency_symbol,
                        'exchange_rate' => $globalCurrency->exchange_rate,
                        'currency_position' => $globalCurrency->currency_position,
                        'no_of_decimal' => $globalCurrency->no_of_decimal,
                        'thousand_separator' => $globalCurrency->thousand_separator,
                        'decimal_separator' => $globalCurrency->decimal_separator,
                        'is_cryptocurrency' => $globalCurrency->is_cryptocurrency,
                        'usd_price' => $globalCurrency->usd_price,
                    ]
                );
            }
            else {
                $currency = Currency::withoutGlobalScope(CompanyScope::class)->where('company_id', $company->id)->first();
            }
        }

        if ($currency) {
            $company->currency_id = $currency->id;
            $company->saveQuietly();
        }
    }

    private function ensureCompanyRole(Company $company, string $name, string $displayName, string $description): Role
    {
        $role = Role::withoutGlobalScope(CompanyScope::class)
            ->where('name', $name)
            ->where('company_id', $company->id)
            ->first();

        if ($role) {
            return $role;
        }

        $role = new Role();
        $role->name = $name;
        $role->company_id = $company->id;
        $role->display_name = $displayName;
        $role->description = $description;
        $role->saveQuietly();

        return $role;
    }

    public function recaptchaValidate($request)
    {
        $global = global_setting();

        if ($global->google_recaptcha_status == 'active') {

            $gRecaptchaResponseInput = global_setting()->google_recaptcha_v3_status == 'active' ? 'g_recaptcha' : 'g-recaptcha-response';
            $gRecaptchaResponse = $request[$gRecaptchaResponseInput];

            $validateRecaptcha = GlobalSetting::validateGoogleRecaptcha($gRecaptchaResponse);

            if (!$validateRecaptcha) {
                return $this->googleRecaptchaMessage();
            }
        }

        return true;
    }

    /**
     * @throws ValidationException
     */
    public function googleRecaptchaMessage()
    {
        throw ValidationException::withMessages([
            'g-recaptcha-response' => [__('auth.recaptchaFailed')],
        ]);
    }
}
