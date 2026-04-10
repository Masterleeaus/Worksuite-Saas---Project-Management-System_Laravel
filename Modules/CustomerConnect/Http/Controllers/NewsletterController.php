<?php

namespace Modules\CustomerConnect\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use App\Models\Invoice;
use App\Models\User;
use Modules\Lead\Entities\Lead;
use App\Models\Proposal;
use Modules\CustomerConnect\Entities\NewsletterModule;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Account\Entities\Bill;
use Modules\Account\Entities\Customer;
use Modules\Hrm\Entities\Transfer;
use Modules\Account\Entities\Vender;
use Modules\Assets\Entities\Asset;
use Modules\Contract\Entities\Contract;
use Modules\Contract\Entities\ContractType;
use Modules\Hrm\Entities\Award;
use Modules\Hrm\Entities\AwardType;
use Modules\Hrm\Entities\Branch;
use Modules\Hrm\Entities\Department;
use Modules\Hrm\Entities\Designation;
use Modules\Hrm\Entities\Employee;
use Modules\Hrm\Entities\Leave;
use Modules\Hrm\Entities\LeaveType;
use Modules\Hrm\Entities\Promotion;
use Modules\Hrm\Entities\Resignation;
use Modules\Hrm\Entities\Termination;
use Modules\Hrm\Entities\TerminationType;
use Modules\Lead\Entities\DealStage;
use Modules\Lead\Entities\LeadStage;
use Modules\Lead\Entities\Pipeline;
use Modules\CustomerConnect\Entities\Newsletters;
use Modules\Pos\Entities\Purchase;
use Modules\Pos\Entities\Warehouse;
use Modules\Recruitment\Entities\InterviewSchedule;
use Modules\Recruitment\Entities\Job;
use Modules\Recruitment\Entities\JobApplication;
use Modules\Retainer\Entities\Retainer;
use Modules\Sales\Entities\AccountIndustry;
use Modules\Sales\Entities\Call;
use Modules\Sales\Entities\Contact;
use Modules\Sales\Entities\Meeting;
use Modules\Sales\Entities\SalesAccount;
use Modules\Sales\Entities\SalesInvoice;
use Modules\Sales\Entities\SalesOrder;
use Modules\Taskly\Entities\Project;
use Modules\ZoomMeeting\Entities\ZoomMeeting;
use SebastianBergmann\Template\Template;

class NewsletterController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $modules = NewsletterModule::groupBy('module')->pluck('module');
        $notify = NewsletterModule::get();


        return view('newsletter::index', compact('modules','notify'));
    }


    public function filter(Request $request)
    {
        if ($request->ajax()) {
            $news_module = NewsletterModule::find($request->additionalField);
            $module = $news_module->module;
            $moduleName = $news_module->submodule;
            $news = new Newsletters();
            $news->module = $module;
            $news->sub_module = $moduleName;
            $news->from = \Auth::user()->name;
            $news->subject = $request->subject;
            $news->content = $request->content;
            $news->workspace_id = getActiveWorkSpace();
            $news->created_by = creatorId();
            $news->save();




            if ($module == 'general') {
                if ($moduleName == 'Invoice') {
                    $users = \DB::table('invoices')
                        ->join('users', 'users.id', '=', 'invoices.user_id')
                        ->select(['users.id', 'users.email', 'invoices.user_id'])
                        ->pluck('users.email', 'invoices.user_id');
                    $invoices = Invoice::where('workspace', '=', getActiveWorkSpace())->where('status', $request->status)->get();
                    $uArr = [];
                    foreach ($users as $user_id => $email) {
                        foreach ($invoices as $invoice) {
                            $invoice->dueAmount = currency_format_with_sym($invoice->getDue());
                            if ($invoice->getDue() >= $request->amount && $invoice->user_id == $user_id) {
                                $use = User::where('id', $invoice->user_id)->first();
                                $uArr[] = $use->email;
                            }
                        }
                    }
                    $templates = [
                        'subject' => $request->subject,
                        'content' => $request->content,
                        'from'    => (!empty(company_setting('company_name'))) ? company_setting('company_name') : \Auth::user()->name,
                    ];

                    if (!empty($uArr)) {


                        $user_id = creatorId();
                        $workspace_id = getActiveWorkSpace();
                        $resp = Newsletters::newsletterEmailTemplate($uArr, $templates);
                        $status = $resp['is_success'] ? 1 : 0;
                        if (!empty($moduleName)) {
                            Newsletters::where('sub_module', $moduleName)->delete();
                            $news = new Newsletters();
                            $news->module = $module;
                            $news->sub_module = $moduleName;
                            $news->from = \Auth::user()->name;
                            $news->subject = $request->subject;
                            $news->emails_list = json_encode($uArr);
                            $news->content = $request->content;
                            $news->workspace_id = getActiveWorkSpace();
                            $news->created_by = creatorId();
                            $news->status = $status;
                            $news->save();
                        }
                    } else {
                        $message = response()->json([
                            'html' => false,
                            'response' => __('Mail not found'),
                        ]);
                        return $message;
                    }
                } elseif ($moduleName == 'Proposal') {
                    $users = \DB::table('proposals')->join('users', 'users.id', '=', 'proposals.customer_id')->select(['users.id', 'users.email', 'proposals.customer_id'])->pluck('proposals.customer_id');
                    $proposals = Proposal::where('workspace', '=', getActiveWorkSpace())->where('status', $request->status)->get();

                    $uArr = [];
                    foreach ($users as $key => $user) {
                        foreach ($proposals as  $proposal) {
                            if ($proposal->getTotal() >= $request->amount && $proposal->customer_id == $user) {
                                $proposal = Proposal::where('customer_id', $user)->first();
                                $use = User::where('id', $proposal->customer_id)->first();
                                $uArr[] = $use->email;
                            }
                        }
                    }
                    $templates = [
                        'subject' => $request->subject,
                        'content' => $request->content,
                        'from'    => (!empty(company_setting('company_name'))) ? company_setting('company_name') : \Auth::user()->name,
                    ];

                    if (!empty($uArr)) {


                        $user_id = creatorId();
                        $workspace_id = getActiveWorkSpace();
                        $resp = Newsletters::newsletterEmailTemplate($uArr, $templates);
                        $status = $resp['is_success'] ? 1 : 0;
                        if (!empty($moduleName)) {
                            Newsletters::where('sub_module', $moduleName)->delete();
                            $news = new Newsletters();
                            $news->module = $module;
                            $news->sub_module = $moduleName;
                            $news->from = \Auth::user()->name;
                            $news->subject = $request->subject;
                            $news->emails_list = json_encode($uArr);
                            $news->content = $request->content;
                            $news->workspace_id = getActiveWorkSpace();
                            $news->created_by = creatorId();
                            $news->status = $status;
                            $news->save();
                        }
                    } else {
                        $message = response()->json([
                            'html' => false,
                            'response' => __('Mail not found'),
                        ]);
                        return $message;
                    }
                }
            } elseif ($module == 'Account') {
                if ($moduleName == 'Customer') {
                    $users = \DB::table('customers')->join('users', 'users.id', '=', 'customers.user_id')->select(['users.id', 'users.email', 'customers.user_id'])->pluck('customers.user_id');

                    $customers = Customer::where('workspace', '=', getActiveWorkSpace())
                        ->where('billing_country', '=', $request->country)
                        ->where('billing_state', '=', $request->state)
                        ->where('billing_city', '=', $request->city)
                        ->get();

                    $uArr = [];
                    foreach ($users as $key => $user) {
                        foreach ($customers as $customer) {
                            if ($user == $customer->user_id) {
                                $uArr[] = $customer->email;
                            }
                        }
                    }
                    $templates = [
                        'subject' => $request->subject,
                        'content' => $request->content,
                        'from'    => (!empty(company_setting('company_name'))) ? company_setting('company_name') : \Auth::user()->name,
                    ];

                    if (!empty($uArr)) {


                        $user_id = creatorId();
                        $workspace_id = getActiveWorkSpace();
                        $resp = Newsletters::newsletterEmailTemplate($uArr, $templates);
                        $status = $resp['is_success'] ? 1 : 0;
                        if (!empty($moduleName)) {
                            Newsletters::where('sub_module', $moduleName)->delete();
                            $news = new Newsletters();
                            $news->module = $module;
                            $news->sub_module = $moduleName;
                            $news->from = \Auth::user()->name;
                            $news->subject = $request->subject;
                            $news->emails_list = json_encode($uArr);
                            $news->content = $request->content;
                            $news->workspace_id = getActiveWorkSpace();
                            $news->created_by = creatorId();
                            $news->status = $status;
                            $news->save();
                        }
                    } else {
                        $message = response()->json([
                            'html' => false,
                            'response' => __('Mail not found'),
                        ]);
                        return $message;
                    }
                } elseif ($moduleName == 'Vendor') {
                    $users = \DB::table('vendors')->join('users', 'users.id', '=', 'vendors.user_id')->select(['users.id', 'users.email', 'vendors.user_id'])->pluck('vendors.user_id');
                    $vendors = Vender::where('workspace', '=', getActiveWorkSpace())
                        ->where('billing_country', '=', $request->country)
                        ->where('billing_state', '=', $request->state)
                        ->where('billing_city', '=', $request->city)
                        ->get();

                    $uArr = [];
                    foreach ($users as $key => $user) {
                        foreach ($vendors as $vendor) {
                            if ($user == $vendor->user_id) {
                                $uArr[] = $vendor->email;
                            }
                        }
                    }
                    $templates = [
                        'subject' => $request->subject,
                        'content' => $request->content,
                        'from'    => (!empty(company_setting('company_name'))) ? company_setting('company_name') : \Auth::user()->name,
                    ];

                    if (!empty($uArr)) {


                        $user_id = creatorId();
                        $workspace_id = getActiveWorkSpace();
                        $resp = Newsletters::newsletterEmailTemplate($uArr, $templates);
                        $status = $resp['is_success'] ? 1 : 0;
                        if (!empty($moduleName)) {
                            Newsletters::where('sub_module', $moduleName)->delete();
                            $news = new Newsletters();
                            $news->module = $module;
                            $news->sub_module = $moduleName;
                            $news->from = \Auth::user()->name;
                            $news->subject = $request->subject;
                            $news->emails_list = json_encode($uArr);
                            $news->content = $request->content;
                            $news->workspace_id = getActiveWorkSpace();
                            $news->created_by = creatorId();
                            $news->status = $status;
                            $news->save();
                        }
                    } else {
                        $message = response()->json([
                            'html' => false,
                            'response' => __('Mail not found'),
                        ]);
                        return $message;
                    }
                } elseif ($moduleName == 'Bill') {
                    $users = \DB::table('bills')->join('users', 'users.id', '=', 'bills.user_id')->select(['users.id', 'users.email', 'bills.user_id'])->pluck('bills.user_id');
                    $bills = Bill::where('workspace', '=', getActiveWorkSpace())->where('status', $request->status)->get();

                    $uArr = [];
                    foreach ($users as $key => $user) {
                        foreach ($bills as  $bill) {
                            if ($bill->getTotal() >= $request->amount && $bill->user_id == $user) {
                                $bill = Bill::where('user_id', $user)->first();
                                $use = User::where('id', $bill->user_id)->first();
                                $uArr[] = $use->email;
                            }
                        }
                    }
                    $templates = [
                        'subject' => $request->subject,
                        'content' => $request->content,
                        'from'    => (!empty(company_setting('company_name'))) ? company_setting('company_name') : \Auth::user()->name,
                    ];

                    if (!empty($uArr)) {


                        $user_id = creatorId();
                        $workspace_id = getActiveWorkSpace();
                        $resp = Newsletters::newsletterEmailTemplate($uArr, $templates);
                        $status = $resp['is_success'] ? 1 : 0;
                        if (!empty($moduleName)) {
                            Newsletters::where('sub_module', $moduleName)->delete();
                            $news = new Newsletters();
                            $news->module = $module;
                            $news->sub_module = $moduleName;
                            $news->from = \Auth::user()->name;
                            $news->subject = $request->subject;
                            $news->emails_list = json_encode($uArr);
                            $news->content = $request->content;
                            $news->workspace_id = getActiveWorkSpace();
                            $news->created_by = creatorId();
                            $news->status = $status;
                            $news->save();
                        }
                    } else {
                        $message = response()->json([
                            'html' => false,
                            'response' => __('Mail not found'),
                        ]);
                        return $message;
                    }
                }
            } elseif ($module == 'Contract') {
                if ($moduleName == 'Contract') {
                    $users = \DB::table('contracts')->join('users', 'users.id', '=', 'contracts.user_id')->select(['users.id', 'users.email', 'contracts.user_id'])->pluck('contracts.user_id');
                    $contracts = Contract::where('workspace', '=', getActiveWorkSpace())
                        ->where('project_id', $request->project_id)
                        ->where('type', $request->type)
                        ->get();

                    $uArr = [];

                    foreach ($users as $key => $user) {
                        foreach ($contracts as  $contract) {
                            if ($contract->user_id == $user) {
                                $use = User::where('id', $contract->user_id)->first();
                                $uArr[] = $use->email;
                            }
                        }
                    }
                    $templates = [
                        'subject' => $request->subject,
                        'content' => $request->content,
                        'from'    => (!empty(company_setting('company_name'))) ? company_setting('company_name') : \Auth::user()->name,
                    ];

                    if (!empty($uArr)) {


                        $user_id = creatorId();
                        $workspace_id = getActiveWorkSpace();
                        $resp = Newsletters::newsletterEmailTemplate($uArr, $templates);
                        $status = $resp['is_success'] ? 1 : 0;
                        if (!empty($moduleName)) {
                            Newsletters::where('sub_module', $moduleName)->delete();
                            $news = new Newsletters();
                            $news->module = $module;
                            $news->sub_module = $moduleName;
                            $news->from = \Auth::user()->name;
                            $news->subject = $request->subject;
                            $news->emails_list = json_encode($uArr);
                            $news->content = $request->content;
                            $news->workspace_id = getActiveWorkSpace();
                            $news->created_by = creatorId();
                            $news->status = $status;
                            $news->save();
                        }
                    } else {
                        $message = response()->json([
                            'html' => false,
                            'response' => __('Mail not found'),
                        ]);
                        return $message;
                    }
                }
            } elseif ($module == 'Hrm') {
                if ($moduleName == 'Employee') {
                    $users = \DB::table('employees')->join('users', 'users.id', '=', 'employees.user_id')->select(['users.id', 'users.email', 'employees.user_id'])->pluck('employees.user_id');
                    $employees = Employee::where('workspace', '=', getActiveWorkSpace())
                        ->where('branch_id', $request->branch_id)
                        ->where('department_id', $request->department_id)
                        ->where('designation_id', $request->designation_id)
                        ->get();
                    $uArr = [];

                    foreach ($users as $key => $user) {
                        foreach ($employees as  $employee) {
                            if ($employee->user_id == $user) {
                                $use = User::where('id', $employee->user_id)->first();
                                $uArr[] = $use->email;
                            }
                        }
                    }
                    $templates = [
                        'subject' => $request->subject,
                        'content' => $request->content,
                        'from'    => (!empty(company_setting('company_name'))) ? company_setting('company_name') : \Auth::user()->name,
                    ];

                    if (!empty($uArr)) {


                        $user_id = creatorId();
                        $workspace_id = getActiveWorkSpace();
                        $resp = Newsletters::newsletterEmailTemplate($uArr, $templates);
                        $status = $resp['is_success'] ? 1 : 0;
                        if (!empty($moduleName)) {
                            Newsletters::where('sub_module', $moduleName)->delete();
                            $news = new Newsletters();
                            $news->module = $module;
                            $news->sub_module = $moduleName;
                            $news->from = \Auth::user()->name;
                            $news->subject = $request->subject;
                            $news->emails_list = json_encode($uArr);
                            $news->content = $request->content;
                            $news->workspace_id = getActiveWorkSpace();
                            $news->created_by = creatorId();
                            $news->status = $status;
                            $news->save();
                        }
                    } else {
                        $message = response()->json([
                            'html' => false,
                            'response' => __('Mail not found'),
                        ]);
                        return $message;
                    }
                } elseif ($moduleName == 'Leave') {
                    $users = \DB::table('leaves')->join('users', 'users.id', '=', 'leaves.user_id')->select(['users.id', 'users.email', 'leaves.user_id'])->pluck('leads.user_id');
                    $leaves = Leave::where('workspace', '=', getActiveWorkSpace())->where('leave_type_id', $request->type)->get();
                    $uArr = [];

                    foreach ($users as $key => $user) {
                        foreach ($leaves as  $leave) {
                            if ($leave->user_id == $user) {
                                $use = User::where('id', $leave->user_id)->first();
                                $uArr[] = $use->email;
                            }
                        }
                    }
                    $templates = [
                        'subject' => $request->subject,
                        'content' => $request->content,
                        'from'    => (!empty(company_setting('company_name'))) ? company_setting('company_name') : \Auth::user()->name,
                    ];

                    if (!empty($uArr)) {


                        $user_id = creatorId();
                        $workspace_id = getActiveWorkSpace();
                        $resp = Newsletters::newsletterEmailTemplate($uArr, $templates);
                        $status = $resp['is_success'] ? 1 : 0;
                        if (!empty($moduleName)) {
                            Newsletters::where('sub_module', $moduleName)->delete();
                            $news = new Newsletters();
                            $news->module = $module;
                            $news->sub_module = $moduleName;
                            $news->from = \Auth::user()->name;
                            $news->subject = $request->subject;
                            $news->emails_list = json_encode($uArr);
                            $news->content = $request->content;
                            $news->workspace_id = getActiveWorkSpace();
                            $news->created_by = creatorId();
                            $news->status = $status;
                            $news->save();
                        }
                    } else {
                        $message = response()->json([
                            'html' => false,
                            'response' => __('Mail not found'),
                        ]);
                        return $message;
                    }
                } elseif ($moduleName == 'Award') {
                    $users = \DB::table('awards')->join('users', 'users.id', '=', 'awards.user_id')->select(['users.id', 'users.email', 'awards.user_id'])->pluck('awards.user_id');
                    $awards = Award::where('workspace', '=', getActiveWorkSpace())->where('award_type', $request->type)->get();
                    $uArr = [];

                    foreach ($users as $key => $user) {
                        foreach ($awards as  $award) {
                            if ($award->user_id == $user) {
                                $use = User::where('id', $award->user_id)->first();
                                if (!in_array($use->email, $uArr)) {
                                    $uArr[] = $use->email;
                                }
                            }
                        }
                    }
                    $templates = [
                        'subject' => $request->subject,
                        'content' => $request->content,
                        'from'    => (!empty(company_setting('company_name'))) ? company_setting('company_name') : \Auth::user()->name,
                    ];

                    if (!empty($uArr)) {


                        $user_id = creatorId();
                        $workspace_id = getActiveWorkSpace();
                        $resp = Newsletters::newsletterEmailTemplate($uArr, $templates);
                        $status = $resp['is_success'] ? 1 : 0;
                        if (!empty($moduleName)) {
                            Newsletters::where('sub_module', $moduleName)->delete();
                            $news = new Newsletters();
                            $news->module = $module;
                            $news->sub_module = $moduleName;
                            $news->from = \Auth::user()->name;
                            $news->subject = $request->subject;
                            $news->emails_list = json_encode($uArr);
                            $news->content = $request->content;
                            $news->workspace_id = getActiveWorkSpace();
                            $news->created_by = creatorId();
                            $news->status = $status;
                            $news->save();
                        }
                    } else {
                        $message = response()->json([
                            'html' => false,
                            'response' => __('Mail not found'),
                        ]);
                        return $message;
                    }
                } elseif ($moduleName == 'Transfer') {
                    $users = \DB::table('transfers')->join('users', 'users.id', '=', 'transfers.user_id')->select(['users.id', 'users.email', 'transfers.user_id'])->pluck('transfers.user_id');
                    $transfers = Transfer::where('workspace', '=', getActiveWorkSpace())
                        ->where('branch_id', $request->branch_id)
                        ->where('department_id', $request->department_id)
                        ->get();
                    $uArr = [];

                    foreach ($users as $key => $user) {
                        foreach ($transfers as  $transfer) {
                            if ($transfer->user_id == $user) {
                                $use = User::where('id', $transfer->user_id)->first();
                                $uArr[] = $use->email;
                            }
                        }
                    }
                    $templates = [
                        'subject' => $request->subject,
                        'content' => $request->content,
                        'from'    => (!empty(company_setting('company_name'))) ? company_setting('company_name') : \Auth::user()->name,
                    ];

                    if (!empty($uArr)) {


                        $user_id = creatorId();
                        $workspace_id = getActiveWorkSpace();
                        $resp = Newsletters::newsletterEmailTemplate($uArr, $templates);
                        $status = $resp['is_success'] ? 1 : 0;
                        if (!empty($moduleName)) {
                            Newsletters::where('sub_module', $moduleName)->delete();
                            $news = new Newsletters();
                            $news->module = $module;
                            $news->sub_module = $moduleName;
                            $news->from = \Auth::user()->name;
                            $news->subject = $request->subject;
                            $news->emails_list = json_encode($uArr);
                            $news->content = $request->content;
                            $news->workspace_id = getActiveWorkSpace();
                            $news->created_by = creatorId();
                            $news->status = $status;
                            $news->save();
                        }
                    } else {
                        $message = response()->json([
                            'html' => false,
                            'response' => __('Mail not found'),
                        ]);
                        return $message;
                    }
                } elseif ($moduleName == 'Termination') {
                    $users = \DB::table('terminations')->join('users', 'users.id', '=', 'terminations.user_id')->select(['users.id', 'users.email', 'terminations.user_id'])->pluck('terminations.user_id');
                    $terminations = Termination::where('workspace', '=', getActiveWorkSpace())
                        ->where('termination_type', $request->type)
                        ->where('notice_date', $request->noticedate)
                        ->where('termination_date', $request->terminationdate)
                        ->get();
                    $uArr = [];

                    foreach ($users as $key => $user) {
                        foreach ($terminations as  $termination) {
                            if ($termination->user_id == $user) {
                                $use = User::where('id', $termination->user_id)->first();
                                $uArr[] = $use->email;
                            }
                        }
                    }
                    $templates = [
                        'subject' => $request->subject,
                        'content' => $request->content,
                        'from'    => (!empty(company_setting('company_name'))) ? company_setting('company_name') : \Auth::user()->name,
                    ];

                    if (!empty($uArr)) {


                        $user_id = creatorId();
                        $workspace_id = getActiveWorkSpace();
                        $resp = Newsletters::newsletterEmailTemplate($uArr, $templates);
                        $status = $resp['is_success'] ? 1 : 0;
                        if (!empty($moduleName)) {
                            Newsletters::where('sub_module', $moduleName)->delete();
                            $news = new Newsletters();
                            $news->module = $module;
                            $news->sub_module = $moduleName;
                            $news->from = \Auth::user()->name;
                            $news->subject = $request->subject;
                            $news->emails_list = json_encode($uArr);
                            $news->content = $request->content;
                            $news->workspace_id = getActiveWorkSpace();
                            $news->created_by = creatorId();
                            $news->status = $status;
                            $news->save();
                        }
                    } else {
                        $message = response()->json([
                            'html' => false,
                            'response' => __('Mail not found'),
                        ]);
                        return $message;
                    }
                } elseif ($moduleName == 'Promotion') {
                    $users = \DB::table('promotions')->join('users', 'users.id', '=', 'promotions.user_id')->select(['users.id', 'users.email', 'promotions.user_id'])->pluck('promotions.user_id');
                    $promotions = Promotion::where('workspace', '=', getActiveWorkSpace())
                        ->where('designation_id', $request->designation)
                        ->where('promotion_date', $request->promotiondate)
                        ->get();
                    $uArr = [];

                    foreach ($users as $key => $user) {
                        foreach ($promotions as  $promotion) {
                            if ($promotion->user_id == $user) {
                                $use = User::where('id', $promotion->user_id)->first();
                                $uArr[] = $use->email;
                            }
                        }
                    }
                    $templates = [
                        'subject' => $request->subject,
                        'content' => $request->content,
                        'from'    => (!empty(company_setting('company_name'))) ? company_setting('company_name') : \Auth::user()->name,
                    ];

                    if (!empty($uArr)) {


                        $user_id = creatorId();
                        $workspace_id = getActiveWorkSpace();
                        $resp = Newsletters::newsletterEmailTemplate($uArr, $templates);
                        $status = $resp['is_success'] ? 1 : 0;
                        if (!empty($moduleName)) {
                            Newsletters::where('sub_module', $moduleName)->delete();
                            $news = new Newsletters();
                            $news->module = $module;
                            $news->sub_module = $moduleName;
                            $news->from = \Auth::user()->name;
                            $news->subject = $request->subject;
                            $news->emails_list = json_encode($uArr);
                            $news->content = $request->content;
                            $news->workspace_id = getActiveWorkSpace();
                            $news->created_by = creatorId();
                            $news->status = $status;
                            $news->save();
                        }
                    } else {
                        $message = response()->json([
                            'html' => false,
                            'response' => __('Mail not found'),
                        ]);
                        return $message;
                    }
                } elseif ($moduleName == 'Resignation') {
                    $users = \DB::table('resignations')->join('users', 'users.id', '=', 'resignations.user_id')->select(['users.id', 'users.email', 'resignations.user_id'])->pluck('resignations.user_id');
                    $resignations = Resignation::where('workspace', '=', getActiveWorkSpace())
                        ->where('resignation_date', $request->resignation_date)
                        ->where('last_working_date', $request->last_working_date)
                        ->get();
                    $uArr = [];

                    foreach ($users as $key => $user) {
                        foreach ($resignations as  $resignation) {
                            if ($resignation->user_id == $user) {
                                $use = User::where('id', $resignation->user_id)->first();
                                $uArr[] = $use->email;
                            }
                        }
                    }
                    $templates = [
                        'subject' => $request->subject,
                        'content' => $request->content,
                        'from'    => (!empty(company_setting('company_name'))) ? company_setting('company_name') : \Auth::user()->name,
                    ];

                    if (!empty($uArr)) {


                        $user_id = creatorId();
                        $workspace_id = getActiveWorkSpace();
                        $resp = Newsletters::newsletterEmailTemplate($uArr, $templates);
                        $status = $resp['is_success'] ? 1 : 0;
                        if (!empty($moduleName)) {
                            Newsletters::where('sub_module', $moduleName)->delete();
                            $news = new Newsletters();
                            $news->module = $module;
                            $news->sub_module = $moduleName;
                            $news->from = \Auth::user()->name;
                            $news->subject = $request->subject;
                            $news->emails_list = json_encode($uArr);
                            $news->content = $request->content;
                            $news->workspace_id = getActiveWorkSpace();
                            $news->created_by = creatorId();
                            $news->status = $status;
                            $news->save();
                        }
                    } else {
                        $message = response()->json([
                            'html' => false,
                            'response' => __('Mail not found'),
                        ]);
                        return $message;
                    }
                } elseif ($moduleName == 'Announcement') {
                    $uArr = \DB::table('employees')
                        ->join('announcement_employees', 'employees.id', '=', 'announcement_employees.employee_id')
                        ->join('announcements', 'announcement_employees.employee_id', '=', 'announcements.id')
                        ->where('announcements.branch_id', $request->branch_id)
                        ->where('announcements.department_id', $request->department_id)
                        ->pluck('employees.email')
                        ->toArray();


                    $templates = [
                        'subject' => $request->subject,
                        'content' => $request->content,
                        'from'    => (!empty(company_setting('company_name'))) ? company_setting('company_name') : \Auth::user()->name,
                    ];

                    if (!empty($uArr)) {


                        $user_id = creatorId();
                        $workspace_id = getActiveWorkSpace();
                        $resp = Newsletters::newsletterEmailTemplate($uArr, $templates);
                        $status = $resp['is_success'] ? 1 : 0;
                        if (!empty($moduleName)) {
                            Newsletters::where('sub_module', $moduleName)->delete();
                            $news = new Newsletters();
                            $news->module = $module;
                            $news->sub_module = $moduleName;
                            $news->from = \Auth::user()->name;
                            $news->subject = $request->subject;
                            $news->emails_list = json_encode($uArr);
                            $news->content = $request->content;
                            $news->workspace_id = getActiveWorkSpace();
                            $news->created_by = creatorId();
                            $news->status = $status;
                            $news->save();
                        }
                    } else {
                        $message = response()->json([
                            'html' => false,
                            'response' => __('Mail not found'),
                        ]);
                        return $message;
                    }
                }
            } elseif ($module == 'Lead') {
                if ($moduleName == 'Deal') {
                    $uArr = \DB::table('users')
                        ->join('client_deals', 'users.id', '=', 'client_deals.client_id')
                        ->join('deals', 'client_deals.deal_id', '=', 'deals.id')
                        ->where('deals.stage_id', $request->stage)
                        ->where('deals.pipeline_id', $request->pipeline)
                        ->pluck('users.email')
                        ->toArray();
                    $templates = [
                        'subject' => $request->subject,
                        'content' => $request->content,
                        'from'    => (!empty(company_setting('company_name'))) ? company_setting('company_name') : \Auth::user()->name,
                    ];

                    if (!empty($uArr)) {


                        $user_id = creatorId();
                        $workspace_id = getActiveWorkSpace();
                        $resp = Newsletters::newsletterEmailTemplate($uArr, $templates);
                        $status = $resp['is_success'] ? 1 : 0;
                        if (!empty($moduleName)) {
                            Newsletters::where('sub_module', $moduleName)->delete();
                            $news = new Newsletters();
                            $news->module = $module;
                            $news->sub_module = $moduleName;
                            $news->from = \Auth::user()->name;
                            $news->subject = $request->subject;
                            $news->emails_list = json_encode($uArr);
                            $news->content = $request->content;
                            $news->workspace_id = getActiveWorkSpace();
                            $news->created_by = creatorId();
                            $news->status = $status;
                            $news->save();
                        }
                    } else {
                        $message = response()->json([
                            'html' => false,
                            'response' => __('Mail not found'),
                        ]);
                        return $message;
                    }
                } elseif ($moduleName == 'Lead') {
                    $users = \DB::table('leads')->join('users', 'users.id', '=', 'leads.user_id')->select(['users.id', 'users.email', 'leads.user_id'])->pluck('leads.user_id');
                    $leads = Lead::where('workspace_id', '=', getActiveWorkSpace())
                        ->where('stage_id', $request->stage)
                        ->where('pipeline_id', $request->pipeline)
                        ->get();
                    $uArr = [];

                    foreach ($users as $key => $user) {
                        foreach ($leads as  $lead) {
                            if ($lead->user_id == $user) {
                                $use = User::where('id', $lead->user_id)->first();
                                $uArr[] = $use->email;
                            }
                        }
                    }
                    $templates = [
                        'subject' => $request->subject,
                        'content' => $request->content,
                        'from'    => (!empty(company_setting('company_name'))) ? company_setting('company_name') : \Auth::user()->name,
                    ];

                    if (!empty($uArr)) {


                        $user_id = creatorId();
                        $workspace_id = getActiveWorkSpace();
                        $resp = Newsletters::newsletterEmailTemplate($uArr, $templates);
                        $status = $resp['is_success'] ? 1 : 0;
                        if (!empty($moduleName)) {
                            Newsletters::where('sub_module', $moduleName)->delete();
                            $news = new Newsletters();
                            $news->module = $module;
                            $news->sub_module = $moduleName;
                            $news->from = \Auth::user()->name;
                            $news->subject = $request->subject;
                            $news->emails_list = json_encode($uArr);
                            $news->content = $request->content;
                            $news->workspace_id = getActiveWorkSpace();
                            $news->created_by = creatorId();
                            $news->status = $status;
                            $news->save();
                        }
                    } else {
                        $message = response()->json([
                            'html' => false,
                            'response' => __('Mail not found'),
                        ]);
                        return $message;
                    }
                }
            } elseif ($module == 'Pos') {
                if ($moduleName == 'Purchase') {
                    $users = \DB::table('purchases')->join('users', 'users.id', '=', 'purchases.user_id')->select(['users.id', 'users.email', 'purchases.user_id'])->pluck('purchases.user_id');
                    $purchases = Purchase::where('workspace', '=', getActiveWorkSpace())
                        ->where('warehouse_id', $request->warehouse_id)
                        ->where('category_id', $request->category_id)
                        ->get();

                    foreach ($users as $key => $user) {
                        foreach ($purchases as  $purchase) {
                            if ($purchase->user_id == $user) {
                                $use = User::where('id', $purchase->user_id)->first();
                                $uArr[] = $use->email;
                            }
                        }
                    }

                    $templates = [
                        'subject' => $request->subject,
                        'content' => $request->content,
                        'from'    => (!empty(company_setting('company_name'))) ? company_setting('company_name') : \Auth::user()->name,
                    ];

                    if (!empty($uArr)) {


                        $user_id = creatorId();
                        $workspace_id = getActiveWorkSpace();
                        $resp = Newsletters::newsletterEmailTemplate($uArr, $templates);
                        $status = $resp['is_success'] ? 1 : 0;
                        if (!empty($moduleName)) {
                            Newsletters::where('sub_module', $moduleName)->delete();
                            $news = new Newsletters();
                            $news->module = $module;
                            $news->sub_module = $moduleName;
                            $news->from = \Auth::user()->name;
                            $news->subject = $request->subject;
                            $news->emails_list = json_encode($uArr);
                            $news->content = $request->content;
                            $news->workspace_id = getActiveWorkSpace();
                            $news->created_by = creatorId();
                            $news->status = $status;
                            $news->save();
                        }
                    } else {
                        $message = response()->json([
                            'html' => false,
                            'response' => __('Mail not found'),
                        ]);
                        return $message;
                    }
                }
            } elseif ($module == 'Recruitment') {
                if ($moduleName == 'Interview Schedule') {
                    $users = \DB::table('interview_schedules')->join('users', 'users.id', '=', 'interview_schedules.user_id')->select(['users.id', 'users.email', 'interview_schedules.user_id'])->pluck('interview_schedules.user_id');
                    $interviews = InterviewSchedule::where('workspace', '=', getActiveWorkSpace())
                        ->where('date', $request->date)
                        ->where('time', $request->time)
                        ->get();

                    $uArr = [];

                    foreach ($users as $key => $user) {
                        foreach ($interviews as  $interview) {
                            if ($interview->user_id == $user) {
                                $use = User::where('id', $interview->user_id)->first();
                                $uArr[] = $use->email;
                            }
                        }
                    }
                    $templates = [
                        'subject' => $request->subject,
                        'content' => $request->content,
                        'from'    => (!empty(company_setting('company_name'))) ? company_setting('company_name') : \Auth::user()->name,
                    ];

                    if (!empty($uArr)) {


                        $user_id = creatorId();
                        $workspace_id = getActiveWorkSpace();
                        $resp = Newsletters::newsletterEmailTemplate($uArr, $templates);
                        $status = $resp['is_success'] ? 1 : 0;
                        if (!empty($moduleName)) {
                            Newsletters::where('sub_module', $moduleName)->delete();
                            $news = new Newsletters();
                            $news->module = $module;
                            $news->sub_module = $moduleName;
                            $news->from = \Auth::user()->name;
                            $news->subject = $request->subject;
                            $news->emails_list = json_encode($uArr);
                            $news->content = $request->content;
                            $news->workspace_id = getActiveWorkSpace();
                            $news->created_by = creatorId();
                            $news->status = $status;
                            $news->save();
                        }
                    } else {
                        $message = response()->json([
                            'html' => false,
                            'response' => __('Mail not found'),
                        ]);
                        return $message;
                    }
                } elseif ($moduleName == 'Job Application') {
                    $uArr = JobApplication::where('workspace', '=', getActiveWorkSpace())
                        ->where('job', '=', $request->job)
                        ->where('country', $request->country)
                        ->where('state', $request->state)
                        ->where('city', $request->city)
                        ->pluck('email')
                        ->toArray();

                    $templates = [
                        'subject' => $request->subject,
                        'content' => $request->content,
                        'from'    => (!empty(company_setting('company_name'))) ? company_setting('company_name') : \Auth::user()->name,
                    ];

                    if (!empty($uArr)) {


                        $user_id = creatorId();
                        $workspace_id = getActiveWorkSpace();
                        $resp = Newsletters::newsletterEmailTemplate($uArr, $templates);
                        $status = $resp['is_success'] ? 1 : 0;
                        if (!empty($moduleName)) {
                            Newsletters::where('sub_module', $moduleName)->delete();
                            $news = new Newsletters();
                            $news->module = $module;
                            $news->sub_module = $moduleName;
                            $news->from = \Auth::user()->name;
                            $news->subject = $request->subject;
                            $news->emails_list = json_encode($uArr);
                            $news->content = $request->content;
                            $news->workspace_id = getActiveWorkSpace();
                            $news->created_by = creatorId();
                            $news->status = $status;
                            $news->save();
                        }
                    } else {
                        $message = response()->json([
                            'html' => false,
                            'response' => __('Mail not found'),
                        ]);
                        return $message;
                    }
                }
            } elseif ($module == 'Retainer') {
                if ($moduleName == 'Retainer') {
                    $users = \DB::table('retainers')
                        ->join('users', 'users.id', '=', 'retainers.user_id')
                        ->select(['users.id', 'users.email', 'retainers.user_id'])
                        ->pluck('users.email', 'retainers.user_id');

                    $retainers = Retainer::where('workspace', '=', getActiveWorkSpace())->where('status', $request->status)->get();
                    $uArr = [];
                    foreach ($users as $user_id => $email) {
                        foreach ($retainers as $retainer) {
                            $retainer->dueAmount = currency_format_with_sym($retainer->getDue());
                            if ($retainer->getDue() >= $request->amount && $retainer->user_id == $user_id) {
                                $use = User::where('id', $retainer->user_id)->first();
                                $uArr[] = $use->email;
                            }
                        }
                    }
                    $templates = [
                        'subject' => $request->subject,
                        'content' => $request->content,
                        'from'    => (!empty(company_setting('company_name'))) ? company_setting('company_name') : \Auth::user()->name,
                    ];

                    if (!empty($uArr)) {


                        $user_id = creatorId();
                        $workspace_id = getActiveWorkSpace();
                        $resp = Newsletters::newsletterEmailTemplate($uArr, $templates);
                        $status = $resp['is_success'] ? 1 : 0;
                        if (!empty($moduleName)) {
                            Newsletters::where('sub_module', $moduleName)->delete();
                            $news = new Newsletters();
                            $news->module = $module;
                            $news->sub_module = $moduleName;
                            $news->from = \Auth::user()->name;
                            $news->subject = $request->subject;
                            $news->emails_list = json_encode($uArr);
                            $news->content = $request->content;
                            $news->workspace_id = getActiveWorkSpace();
                            $news->created_by = creatorId();
                            $news->status = $status;
                            $news->save();
                        }
                    } else {
                        $message = response()->json([
                            'html' => false,
                            'response' => __('Mail not found'),
                        ]);
                        return $message;
                    }
                }
            } elseif ($module == 'Sales') {
                if ($moduleName == 'Account') {
                    $users = \DB::table('sales_accounts')->join('users', 'users.id', '=', 'sales_accounts.user_id')->select(['users.id', 'users.email', 'sales_accounts.user_id'])->pluck('sales_accounts.user_id');

                    $accounts = SalesAccount::where('workspace', '=', getActiveWorkSpace())
                        ->where('billing_country', '=', $request->country)
                        ->where('billing_state', '=', $request->state)
                        ->where('billing_city', '=', $request->city)
                        ->get();

                    $uArr = [];
                    foreach ($users as $key => $user) {
                        foreach ($accounts as $account) {
                            if ($user == $account->user_id) {
                                $emailaddress = User::find($user);
                                $emails = $emailaddress->email;
                                $uArr[] = $emails;
                            }
                        }
                    }
                    $templates = [
                        'subject' => $request->subject,
                        'content' => $request->content,
                        'from'    => (!empty(company_setting('company_name'))) ? company_setting('company_name') : \Auth::user()->name,
                    ];

                    if (!empty($uArr)) {


                        $user_id = creatorId();
                        $workspace_id = getActiveWorkSpace();
                        $resp = Newsletters::newsletterEmailTemplate($uArr, $templates);
                        $status = $resp['is_success'] ? 1 : 0;
                        if (!empty($moduleName)) {
                            Newsletters::where('sub_module', $moduleName)->delete();
                            $news = new Newsletters();
                            $news->module = $module;
                            $news->sub_module = $moduleName;
                            $news->from = \Auth::user()->name;
                            $news->subject = $request->subject;
                            $news->emails_list = json_encode($uArr);
                            $news->content = $request->content;
                            $news->workspace_id = getActiveWorkSpace();
                            $news->created_by = creatorId();
                            $news->status = $status;
                            $news->save();
                        }
                    } else {
                        $message = response()->json([
                            'html' => false,
                            'response' => __('Mail not found'),
                        ]);
                        return $message;
                    }
                } elseif ($moduleName == 'Contact') {

                    $users = \DB::table('contacts')->join('users', 'users.id', '=', 'contacts.user_id')->select(['users.id', 'users.email', 'contacts.user_id'])->pluck('contacts.user_id');

                    $contacts = Contact::where('workspace', '=', getActiveWorkSpace())
                        ->where('contact_country', '=', $request->country)
                        ->where('contact_state', '=', $request->state)
                        ->where('contact_city', '=', $request->city)
                        ->get();

                    $uArr = [];
                    foreach ($users as $key => $user) {
                        foreach ($contacts as $contact) {
                            if ($user == $contact->user_id) {
                                $emailaddress = User::find($user);
                                $emails = $emailaddress->email;
                                $uArr[] = $emails;
                            }
                        }
                    }
                    $templates = [
                        'subject' => $request->subject,
                        'content' => $request->content,
                        'from'    => (!empty(company_setting('company_name'))) ? company_setting('company_name') : \Auth::user()->name,
                    ];

                    if (!empty($uArr)) {


                        $user_id = creatorId();
                        $workspace_id = getActiveWorkSpace();
                        $resp = Newsletters::newsletterEmailTemplate($uArr, $templates);
                        $status = $resp['is_success'] ? 1 : 0;
                        if (!empty($moduleName)) {
                            Newsletters::where('sub_module', $moduleName)->delete();
                            $news = new Newsletters();
                            $news->module = $module;
                            $news->sub_module = $moduleName;
                            $news->from = \Auth::user()->name;
                            $news->subject = $request->subject;
                            $news->emails_list = json_encode($uArr);
                            $news->content = $request->content;
                            $news->workspace_id = getActiveWorkSpace();
                            $news->created_by = creatorId();
                            $news->status = $status;
                            $news->save();
                        }
                    } else {
                        $message = response()->json([
                            'html' => false,
                            'response' => __('Mail not found'),
                        ]);
                        return $message;
                    }
                } elseif ($moduleName == 'Sales Invoice') {
                    $users = \DB::table('sales_invoices')
                        ->join('users', 'users.id', '=', 'sales_invoices.user_id')
                        ->select(['users.id', 'users.email', 'sales_invoices.user_id'])
                        ->pluck('users.email', 'sales_invoices.user_id');
                    $invoices = SalesInvoice::where('workspace', '=', getActiveWorkSpace())->where('status', $request->status)->get();
                    $uArr = [];
                    foreach ($users as $user_id => $email) {
                        foreach ($invoices as $invoice) {
                            $invoice->dueAmount = currency_format_with_sym($invoice->getdue());
                            if ($invoice->getTotal() >= $request->amount && $invoice->user_id == $user_id) {
                                $use = User::where('id', $invoice->user_id)->first();
                                $uArr[] = $use->email;
                            }
                        }
                    }
                    $templates = [
                        'subject' => $request->subject,
                        'content' => $request->content,
                        'from'    => (!empty(company_setting('company_name'))) ? company_setting('company_name') : \Auth::user()->name,
                    ];

                    if (!empty($uArr)) {


                        $user_id = creatorId();
                        $workspace_id = getActiveWorkSpace();
                        $resp = Newsletters::newsletterEmailTemplate($uArr, $templates);
                        $status = $resp['is_success'] ? 1 : 0;
                        if (!empty($moduleName)) {
                            Newsletters::where('sub_module', $moduleName)->delete();
                            $news = new Newsletters();
                            $news->module = $module;
                            $news->sub_module = $moduleName;
                            $news->from = \Auth::user()->name;
                            $news->subject = $request->subject;
                            $news->emails_list = json_encode($uArr);
                            $news->content = $request->content;
                            $news->workspace_id = getActiveWorkSpace();
                            $news->created_by = creatorId();
                            $news->status = $status;
                            $news->save();
                        }
                    } else {
                        $message = response()->json([
                            'html' => false,
                            'response' => __('Mail not found'),
                        ]);
                        return $message;
                    }
                } elseif ($moduleName == 'Sales Order') {
                    $users = \DB::table('sales_orders')->join('users', 'users.id', '=', 'sales_orders.user_id')->select(['users.id', 'users.email', 'sales_orders.user_id'])->pluck('sales_orders.user_id');
                    $orders = SalesOrder::where('workspace', '=', getActiveWorkSpace())->where('status', $request->status)->get();

                    $uArr = [];
                    foreach ($users as $key => $user) {
                        foreach ($orders as  $order) {
                            if ($order->getTotal() >= $request->amount && $order->user_id == $user) {
                                $order = SalesOrder::where('user_id', $user)->first();
                                $use = User::where('id', $order->user_id)->first();
                                $uArr[] = $use->email;
                            }
                        }
                    }
                    $templates = [
                        'subject' => $request->subject,
                        'content' => $request->content,
                        'from'    => (!empty(company_setting('company_name'))) ? company_setting('company_name') : \Auth::user()->name,
                    ];

                    if (!empty($uArr)) {


                        $user_id = creatorId();
                        $workspace_id = getActiveWorkSpace();
                        $resp = Newsletters::newsletterEmailTemplate($uArr, $templates);
                        $status = $resp['is_success'] ? 1 : 0;
                        if (!empty($moduleName)) {
                            Newsletters::where('sub_module', $moduleName)->delete();
                            $news = new Newsletters();
                            $news->module = $module;
                            $news->sub_module = $moduleName;
                            $news->from = \Auth::user()->name;
                            $news->subject = $request->subject;
                            $news->emails_list = json_encode($uArr);
                            $news->content = $request->content;
                            $news->workspace_id = getActiveWorkSpace();
                            $news->created_by = creatorId();
                            $news->status = $status;
                            $news->save();
                        }
                    } else {
                        $message = response()->json([
                            'html' => false,
                            'response' => __('Mail not found'),
                        ]);
                        return $message;
                    }
                } elseif ($moduleName == 'Meeting') {

                    $users = \DB::table('meetings')->join('users', 'users.id', '=', 'meetings.user_id')->select(['users.id', 'users.email', 'meetings.user_id'])->pluck('meetings.user_id');
                    $meetings = Meeting::where('workspace', '=', getActiveWorkSpace())
                        ->where('parent', $request->parent)
                        ->where('attendees_lead', $request->attendees_lead)
                        ->where('start_date', $request->start_date)
                        ->where('end_date', $request->end_date)
                        ->get();
                    $uArr = [];

                    foreach ($users as $key => $user) {
                        foreach ($meetings as  $meeting) {
                            if ($meeting->user_id == $user) {
                                $use = User::where('id', $meeting->user_id)->first();
                                if (!in_array($use->email, $uArr)) {
                                    $uArr[] = $use->email;
                                }
                            }
                        }
                    }
                    $templates = [
                        'subject' => $request->subject,
                        'content' => $request->content,
                        'from'    => (!empty(company_setting('company_name'))) ? company_setting('company_name') : \Auth::user()->name,
                    ];

                    if (!empty($uArr)) {


                        $user_id = creatorId();
                        $workspace_id = getActiveWorkSpace();
                        $resp = Newsletters::newsletterEmailTemplate($uArr, $templates);
                        $status = $resp['is_success'] ? 1 : 0;
                        if (!empty($moduleName)) {
                            Newsletters::where('sub_module', $moduleName)->delete();
                            $news = new Newsletters();
                            $news->module = $module;
                            $news->sub_module = $moduleName;
                            $news->from = \Auth::user()->name;
                            $news->subject = $request->subject;
                            $news->emails_list = json_encode($uArr);
                            $news->content = $request->content;
                            $news->workspace_id = getActiveWorkSpace();
                            $news->created_by = creatorId();
                            $news->status = $status;
                            $news->save();
                        }
                    } else {
                        $message = response()->json([
                            'html' => false,
                            'response' => __('Mail not found'),
                        ]);
                        return $message;
                    }
                } elseif ($moduleName == 'Call') {
                    $users = \DB::table('calls')->join('users', 'users.id', '=', 'calls.user_id')->select(['users.id', 'users.email', 'calls.user_id'])->pluck('calls.user_id');
                    $calls = Call::where('workspace', '=', getActiveWorkSpace())
                        ->where('parent', $request->parent)
                        ->where('attendees_lead', $request->attendees_lead)
                        ->where('start_date', $request->start_date)
                        ->where('end_date', $request->end_date)
                        ->get();
                    $uArr = [];

                    foreach ($users as $key => $user) {
                        foreach ($calls as  $call) {
                            if ($call->user_id == $user) {
                                $use = User::where('id', $call->user_id)->first();
                                if (!in_array($use->email, $uArr)) {
                                    $uArr[] = $use->email;
                                }
                            }
                        }
                    }
                    $templates = [
                        'subject' => $request->subject,
                        'content' => $request->content,
                        'from'    => (!empty(company_setting('company_name'))) ? company_setting('company_name') : \Auth::user()->name,
                    ];

                    if (!empty($uArr)) {


                        $user_id = creatorId();
                        $workspace_id = getActiveWorkSpace();
                        $resp = Newsletters::newsletterEmailTemplate($uArr, $templates);
                        $status = $resp['is_success'] ? 1 : 0;
                        if (!empty($moduleName)) {
                            Newsletters::where('sub_module', $moduleName)->delete();
                            $news = new Newsletters();
                            $news->module = $module;
                            $news->sub_module = $moduleName;
                            $news->from = \Auth::user()->name;
                            $news->subject = $request->subject;
                            $news->emails_list = json_encode($uArr);
                            $news->content = $request->content;
                            $news->workspace_id = getActiveWorkSpace();
                            $news->created_by = creatorId();
                            $news->status = $status;
                            $news->save();
                        }
                    } else {
                        $message = response()->json([
                            'html' => false,
                            'response' => __('Mail not found'),
                        ]);
                        return $message;
                    }
                }
            } elseif ($module == 'ZoomMeeting') {
                if ($moduleName == 'Zoom Meeting') {
                    $uArr = \DB::table('users')
                        ->join('general_meeting', 'users.id', '=', 'general_meeting.user_id')
                        ->join('zoom_meeting', 'general_meeting.m_id', '=', 'zoom_meeting.id')
                        ->where('zoom_meeting.start_date', $request->start_date)
                        ->pluck('users.email')
                        ->toArray(); // Convert the collection to an array


                    $templates = [
                        'subject' => $request->subject,
                        'content' => $request->content,
                        'from'    => (!empty(company_setting('company_name'))) ? company_setting('company_name') : \Auth::user()->name,
                    ];

                    if (!empty($uArr)) {


                        $user_id = creatorId();
                        $workspace_id = getActiveWorkSpace();
                        $resp = Newsletters::newsletterEmailTemplate($uArr, $templates);
                        $status = $resp['is_success'] ? 1 : 0;
                        if (!empty($moduleName)) {
                            Newsletters::where('sub_module', $moduleName)->delete();
                            $news = new Newsletters();
                            $news->module = $module;
                            $news->sub_module = $moduleName;
                            $news->from = \Auth::user()->name;
                            $news->subject = $request->subject;
                            $news->emails_list = json_encode($uArr);
                            $news->content = $request->content;
                            $news->workspace_id = getActiveWorkSpace();
                            $news->created_by = creatorId();
                            $news->status = $status;
                            $news->save();
                        }
                    } else {
                        $message = response()->json([
                            'html' => false,
                            'response' => __('Mail not found'),
                        ]);
                        return $message;
                    }
                }
            } elseif ($module == 'Assets') {
                if ($moduleName == 'Assets') {
                    $uArr = User::join('assets', 'users.id', '=', 'assets.user_id')
                        ->where('assets.amount', '>=', $request->amount)
                        ->where('assets.workspace_id', '=', getActiveWorkSpace())
                        ->pluck('users.email')
                        ->toArray();

                    $templates = [
                        'subject' => $request->subject,
                        'content' => $request->content,
                        'from'    => (!empty(company_setting('company_name'))) ? company_setting('company_name') : \Auth::user()->name,
                    ];

                    if (!empty($uArr)) {

                        $user_id = creatorId();
                        $workspace_id = getActiveWorkSpace();
                        $resp = Newsletters::newsletterEmailTemplate($uArr, $templates);
                        $status = $resp['is_success'] ? 1 : 0;
                        if (!empty($moduleName)) {
                            Newsletters::where('sub_module', $moduleName)->delete();
                            $news = new Newsletters();
                            $news->module = $module;
                            $news->sub_module = $moduleName;
                            $news->from = \Auth::user()->name;
                            $news->subject = $request->subject;
                            $news->emails_list = json_encode($uArr);
                            $news->content = $request->content;
                            $news->workspace_id = getActiveWorkSpace();
                            $news->created_by = creatorId();
                            $news->status = $status;
                            $news->save();
                        }
                    } else {
                        $message = response()->json([
                            'html' => false,
                            'response' => __('No emails found'),
                        ]);
                        return $message;
                    }
                }
            }
            $message =  response()->json([
                'html' => false,
                'response' => __('Newsletter Created Successfully!') . ((isset($resp) && $resp != 1) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''),
            ]);

            return $message;
        }
    }

    public function getcondition(Request $request)
    {
        $news_module = NewsletterModule::find($request->workmodule_id);
        if($news_module != null)
        {
            $field_data = json_decode($news_module->field_json);
            $data = null;
            foreach ($field_data->field as $value) {

                if ($value->field_type == "select") {
                    if ($value->model_name == 'LeadStage') {
                        $data['LeadStage'] = LeadStage::where('workspace_id', getActiveWorkSpace())->get()->pluck('name', 'id');
                    } elseif ($value->model_name == 'DealStage') {
                        $data['DealStage'] = DealStage::where('workspace_id', getActiveWorkSpace())->get()->pluck('name', 'id');
                    } elseif ($value->model_name == 'Pipeline') {
                        $data['Pipeline'] = Pipeline::where('workspace_id', getActiveWorkSpace())->get()->pluck('name', 'id');
                    } elseif ($value->model_name == 'Invoice') {
                        $data['Invoice'] = \App\Models\Invoice::$statues;
                    } elseif ($value->model_name == 'Proposal') {
                        $data['Proposal'] = \App\Models\Proposal::$statues;
                    } elseif ($value->model_name == 'Bill') {
                        $data['Bill'] = Bill::$statues;
                    } elseif ($value->model_name == 'Leave') {
                        $data['Leave'] = LeaveType::where('workspace', getActiveWorkSpace())->get()->pluck('title', 'id');
                    } elseif ($value->model_name == 'Award') {
                        $data['Award'] = AwardType::where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
                    } elseif ($value->model_name == 'Termination') {
                        $data['Termination'] = TerminationType::where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
                    } elseif ($value->model_name == 'Promotion') {
                        $data['Promotion'] = Designation::where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
                    } elseif ($value->model_name == 'SalesInvoice') {
                        $data['SalesInvoice'] = SalesInvoice::$status;
                    } elseif ($value->model_name == 'SalesOrder') {
                        $data['SalesOrder']  = SalesOrder::$status;
                    } elseif ($value->model_name == 'Project') {
                        $data['Project'] = Project::where('workspace', getActiveWorkSpace())->projectonly()->get()->pluck('name', 'id');
                    } elseif ($value->model_name == 'Type') {
                        $data['Type'] = ContractType::where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
                    } elseif ($value->model_name == 'Meeting') {
                        $data['Meeting'] = Meeting::$parent;
                    } elseif ($value->model_name == 'Call') {
                        $data['Call'] = Call::$parent;
                    } elseif ($value->model_name == 'Lead') {
                        $data['Lead'] = Lead::where('workspace_id', getActiveWorkSpace())->get()->pluck('name', 'id');
                    } elseif ($value->model_name == 'Warehouse') {
                        $data['Warehouse'] = Warehouse::where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
                    } elseif ($value->model_name == 'Category') {
                        $data['Category']     = \Modules\ProductService\Entities\Category::where('created_by', creatorId())->where('workspace_id', getActiveWorkSpace())->where('type', 2)->get()->pluck('name', 'id');
                    } elseif ($value->model_name == 'Department') {
                        $data['Department'] = Department::where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
                    } elseif ($value->model_name == 'Branch') {
                        $data['Branch'] = Branch::where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
                    } elseif ($value->model_name == 'Designation') {
                        $data['Designation'] = Designation::where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
                    } elseif ($value->model_name == 'Retainer') {
                        $data['Retainer']  = Retainer::$statues;
                    } elseif ($value->model_name == 'Job') {
                        $data['Job'] = Job::where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('title', 'id');
                    } else {
                        return redirect()->back()->with('error', __('Permission denied.'));
                    }
                }
            }
            $returnHTML = view('newsletter::input', compact('news_module', 'data', 'request', 'field_data'))->render();
            $response = [
                'is_success' => true,
                'message' => '',
                'html' => $returnHTML,
            ];
            return response()->json($response);
        }
    }


      /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('newsletter::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('newsletter::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }

}
