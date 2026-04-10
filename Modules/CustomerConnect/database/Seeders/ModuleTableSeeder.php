<?php

namespace Modules\CustomerConnect\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\CustomerConnect\Entities\NewsletterModule;

class ModuleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $sub_module = [
            'Invoice', 'Proposal'
        ];
        $content = [
            [

                'Invoice' => '{"field":[{"label":"Amount","placeholder":"e.g.enter amount","field_type":"number","field_name":"amount"},{"label":"status","placeholder":"e.g.Select status","field_type":"select","field_name":"status","model_name": "Invoice"}]}',
                'Proposal' => '{"field":[{"label":"Price","placeholder":"e.g.enter price","field_type":"number","field_name":"price"},{"label":"status","placeholder":"e.g.Select status","field_type":"select","field_name":"status","model_name": "Proposal"}]}',
            ]
        ];

        foreach ($sub_module as $sm) {
            foreach ($content as $key => $item) {
                $check = NewsletterModule::where('module', 'general')->where('submodule', $sm)->first();
                if (!$check) {
                    $new = new NewsletterModule();
                    $new->module = 'general';
                    $new->submodule = $sm;
                    $new->type = 'company';
                    if ($sm == 'Invoice') {
                        $new->field_json = $item[$sm];
                    }
                    if ($sm == 'Proposal') {
                        $new->field_json = $item[$sm];
                    }
                    $new->save();
                }
            }
        }

        $sub_module = [
            'Customer', 'Vendor', 'Bill'
        ];

        $content = [
            [
                'Customer' => '{"field":[{"label":"Country","placeholder":"e.g.enter country","field_type":"text","field_name":"country"},{"label":"State","placeholder":"e.g.enter state","field_type":"text","field_name":"state"},{"label":"City","placeholder":"e.g.enter city","field_type":"text","field_name":"city"}]}',
                'Vendor' => '{"field":[{"label":"Country","placeholder":"e.g.enter country","field_type":"text","field_name":"country"},{"label":"State","placeholder":"e.g.enter state","field_type":"text","field_name":"state"},{"label":"City","placeholder":"e.g.enter city","field_type":"text","field_name":"city"}]}',
                'Bill' => '{"field":[{"label":"Amount","placeholder":"e.g.enter amount","field_type":"number","field_name":"amount"},{"label":"status","placeholder":"e.g.enter status","field_type":"select","field_name":"status", "model_name": "Bill"}]}',

            ]
        ];

        foreach ($sub_module as $sm) {
            foreach ($content as $key => $item) {
                $check = NewsletterModule::where('module', 'Account')->where('submodule', $sm)->first();
                if (!$check) {
                    $new = new NewsletterModule();
                    $new->module = 'Account';
                    $new->submodule = $sm;
                    if ($sm == 'Customer') {
                        $new->field_json = $item[$sm];
                    }
                    if ($sm == 'Vendor') {
                        $new->field_json = $item[$sm];
                    }
                    if ($sm == 'Bill') {
                        $new->field_json = $item[$sm];
                    }
                    $new->save();
                }
            }
        }


        $sub_module = [
            'Assets'
        ];


        $content = [
            [
                'Assets' => '{"field":[{"label":"Amount","placeholder":"Please Enter Amount","field_type":"number","field_name":"amount"}]}',
            ]
        ];


        foreach ($sub_module as $sm) {
            foreach ($content as $key => $item) {
                $check = NewsletterModule::where('module', 'Assets')->where('submodule', $sm)->first();
                if (!$check) {
                    $new = new NewsletterModule();
                    $new->module = 'Assets';
                    $new->submodule = $sm;
                    if ($sm == 'Assets') {
                        $new->field_json = $item[$sm];
                    }
                    $new->save();
                }
            }
        }


        $sub_module = [
            'Contract'
        ];


        $content = [
            [
                'Contract' => '{"field":[{"label":"Project","placeholder":"Select Project","field_type":"select","field_name":"project_id","model_name": "Project"},{"label":"Type","placeholder":"Select Type","field_type":"select","field_name":"type","model_name": "Type"}]}',
            ]
        ];


        foreach ($sub_module as $sm) {
            foreach ($content as $key => $item) {
                $check = NewsletterModule::where('module', 'Contract')->where('submodule', $sm)->first();
                if (!$check) {
                    $new = new NewsletterModule();
                    $new->module = 'Contract';
                    $new->submodule = $sm;
                    if ($sm == 'Contract') {
                        $new->field_json = $item[$sm];
                    }
                    $new->save();
                }
            }
        }

        $sub_module = [
            'Employee', 'Leave', 'Award', 'Transfer', 'Resignation', 'Promotion', 'Termination', 'Announcement'
        ];

        $content = [
            [
                'Employee' => '{"field":[{"label":"Branch","placeholder":"Select Branch","field_type":"select","field_name":"branch_id","model_name":"Branch"},{"label":"Department","placeholder":"Select  Department","field_type":"select","field_name":"department_id","model_name":"Department"},{"label":"Designation","placeholder":"Select Designation","field_type":"select","field_name":"designation_id","model_name":"Designation"}]}',
                'Leave' => '{"field":[{"label":"Leave Type","placeholder":"Select Leave Type","field_type":"select","field_name":"type","model_name": "Leave"}]}',
                'Award' => '{"field":[{"label":"Type","placeholder":"e.g.enter type","field_type":"select","field_name":"type","model_name": "Award"}]}',
                'Transfer' => '{"field":[{"label":"Branch","placeholder":"Select Type","field_type":"select","field_name":"branch_id","model_name":"Branch"},{"label":"Department","placeholder":"Select Department","field_type":"select","field_name":"department_id","model_name":"Department"}]}',
                'Resignation' => '{"field":[{"label":"Resignation Date","placeholder":"e.g.enter type","field_type":"date","field_name":"resignation_date"},{"label":"Last Working Date","placeholder":"enter noticedate","field_type":"date","field_name":"last_working_date"}]}',
                'Promotion' => '{"field":[{"label":"Designation","placeholder":"select designation","field_type":"select","field_name":"designation" ,"model_name": "Promotion"},{"label":"Promotion Date","placeholder":"enter promotiondate","field_type":"date","field_name":"promotiondate"}]}',
                'Termination' => '{"field":[{"label":"Termination Type","placeholder":"Select Type","field_type":"select","field_name":"type","model_name": "Termination"},{"label":"Notice Date","placeholder":"enter noticedate","field_type":"date","field_name":"noticedate"},{"label":"Termination date","placeholder":"enter terminationdate","field_type":"date","field_name":"terminationdate"}]}',
                'Announcement' => '{"field":[{"label":"Branch","placeholder":"Select Type","field_type":"select","field_name":"branch_id","model_name":"Branch"},{"label":"Department","placeholder":"Select Department","field_type":"select","field_name":"department_id","model_name":"Department"}]}',
            ]
        ];


        foreach ($sub_module as $sm) {
            foreach ($content as $key => $item) {
                $check = NewsletterModule::where('module', 'Hrm')->where('submodule', $sm)->first();
                if (!$check) {
                    $new = new NewsletterModule();
                    $new->module = 'Hrm';
                    $new->submodule = $sm;
                    if ($sm == 'Employee') {
                        $new->field_json = $item[$sm];
                    }
                    if ($sm == 'Leave') {
                        $new->field_json = $item[$sm];
                    }
                    if ($sm == 'Award') {
                        $new->field_json = $item[$sm];
                    }
                    if ($sm == 'Transfer') {
                        $new->field_json = $item[$sm];
                    }
                    if ($sm == 'Resignation') {
                        $new->field_json = $item[$sm];
                    }
                    if ($sm == 'Promotion') {
                        $new->field_json = $item[$sm];
                    }
                    if ($sm == 'Termination') {
                        $new->field_json = $item[$sm];
                    }
                    if ($sm == 'Announcement') {
                        $new->field_json = $item[$sm];
                    }
                    $new->save();
                }
            }
        }




        $sub_module = [
            'Lead', 'Deal'
        ];

        $content = [
            [
                'Lead' => '{"field":[{"label":"Lead Stage","field_type":"select","field_name":"stage","placeholder":"Enter Stage", "model_name": "LeadStage"},
                {"label":"Pipeline","placeholder":"Select Pipeline","field_type":"select","field_name":"pipeline","model_name": "Pipeline"}
                ]}',
                'Deal' => '{"field":[{"label":"Deal Stage","field_type":"select","field_name":"stage","placeholder":"Enter Stage", "model_name": "DealStage"},
                {"label":"Pipeline","placeholder":"Select Pipeline","field_type":"select","field_name":"pipeline","model_name": "Pipeline"}
                ]}',
            ]
        ];
        foreach ($sub_module as $sm) {
            foreach ($content as $key => $item) {
                $check = NewsletterModule::where('module', 'Lead')->where('submodule', $sm)->first();
                if (!$check) {
                    $new = new NewsletterModule();
                    $new->module = 'Lead';
                    $new->submodule = $sm;
                    if ($sm == 'Lead') {
                        $new->field_json = $item[$sm];
                    }
                    if ($sm == 'Deal') {
                        $new->field_json = $item[$sm];
                    }
                    $new->save();
                }
            }
        }


        $sub_module = [
            'Purchase'
        ];


        $content = [
            [
                'Purchase' => '{"field":[{"label":"Warehouse","placeholder":"Select Project","field_type":"select","field_name":"warehouse_id","model_name": "Warehouse"},{"label":"Category","placeholder":"Select Type","field_type":"select","field_name":"category_id","model_name": "Category"}]}',
            ]
        ];


        foreach ($sub_module as $sm) {
            foreach ($content as $key => $item) {
                $check = NewsletterModule::where('module', 'Pos')->where('submodule', $sm)->first();
                if (!$check) {
                    $new = new NewsletterModule();
                    $new->module = 'Pos';
                    $new->submodule = $sm;
                    if ($sm == 'Purchase') {
                        $new->field_json = $item[$sm];
                    }
                    $new->save();
                }
            }
        }


        $sub_module = [
            'Interview Schedule',
            'Job Application'
        ];

        $content = [
            [

                'Interview Schedule' => '{"field":[{"label":"Date","placeholder":"e.g.enter type","field_type":"date","field_name":"date"},{"label":"Time","placeholder":"enter noticedate","field_type":"time","field_name":"time"}]}',
                'Job Application' => '{"field":[{"label":"Job","placeholder":"Select Job","field_type":"select","field_name":"job","model_name": "Job" },{"label":"Country","placeholder":"e.g.enter country","field_type":"text","field_name":"country"},{"label":"State","placeholder":"e.g.enter state","field_type":"text","field_name":"state"},{"label":"City","placeholder":"e.g.enter city","field_type":"text","field_name":"city"}]}',

            ]
        ];

        foreach ($sub_module as $sm) {
            foreach ($content as $key => $item) {
                $check = NewsletterModule::where('module', 'Recruitment')->where('submodule', $sm)->first();
                if (!$check) {
                    $new = new NewsletterModule();
                    $new->module = 'Recruitment';
                    $new->submodule = $sm;
                    if ($sm == 'Interview Schedule') {
                        $new->field_json = $item[$sm];
                    }
                    if ($sm == 'Job Application') {
                        $new->field_json = $item[$sm];
                    }
                    $new->save();
                }
            }
        }


        $sub_module = [
            'Retainer'
        ];

        $content = [
            [
                'Retainer' => '{"field":[{"label":"Amount","placeholder":"e.g.enter amount","field_type":"number","field_name":"amount"},{"label":"status","placeholder":"e.g.enter status","field_type":"select","field_name":"status","model_name": "Retainer"}]}',
            ]
        ];

        foreach ($sub_module as $sm) {
            foreach ($content as $key => $item) {
                $check = NewsletterModule::where('module', 'Retainer')->where('submodule', $sm)->first();
                if (!$check) {
                    $new = new NewsletterModule();
                    $new->module = 'Retainer';
                    $new->submodule = $sm;
                    if ($sm == 'Retainer') {
                        $new->field_json = $item[$sm];
                    }
                    $new->save();
                }
            }
        }

        $sub_module = [
            'Account', 'Contact', 'Sales Invoice', 'Sales Order', 'Meeting', 'Call'
        ];


        $content = [
            [
                'Account' => '{"field":[{"label":"Country","placeholder":"e.g.enter country","field_type":"text","field_name":"country"},{"label":"State","placeholder":"e.g.enter state","field_type":"text","field_name":"state"},{"label":"City","placeholder":"e.g.enter city","field_type":"text","field_name":"city"}]}',
                'Contact' => '{"field":[{"label":"Country","placeholder":"e.g.enter country","field_type":"text","field_name":"country"},{"label":"State","placeholder":"e.g.enter state","field_type":"text","field_name":"state"},{"label":"City","placeholder":"e.g.enter city","field_type":"text","field_name":"city"}]}',
                'Sales Invoice' => '{"field":[{"label":"Amount","placeholder":"e.g.enter amount","field_type":"number","field_name":"amount"},{"label":"status","placeholder":"e.g.enter status","field_type":"select","field_name":"status","model_name": "SalesInvoice"}]}',
                'Sales Order' => '{"field":[{"label":"Amount","placeholder":"e.g.enter amount","field_type":"number","field_name":"amount"},{"label":"status","placeholder":"e.g.enter status","field_type":"select","field_name":"status","model_name": "SalesOrder"}]}',
                'Meeting' => '{"field":[{"label":"Parent","placeholder":"Select Lead","field_type":"select","field_name":"parent","model_name": "Meeting" },{"label":"Attendees Lead","placeholder":"Select Lead","field_type":"select","field_name":"attendees_lead","model_name": "Lead" },{"label":"Start Date","placeholder":"e.g.enter type","field_type":"date","field_name":"start_date"},{"label":"End Date","placeholder":"e.g.enter type","field_type":"date","field_name":"end_date"}]}',
                'Call' => '{"field":[{"label":"Parent","placeholder":"Select Lead","field_type":"select","field_name":"parent","model_name": "Call" },{"label":"Attendees Lead","placeholder":"Select Lead","field_type":"select","field_name":"attendees_lead","model_name": "Lead" },{"label":"Start Date","placeholder":"e.g.enter type","field_type":"date","field_name":"start_date"},{"label":"End Date","placeholder":"e.g.enter type","field_type":"date","field_name":"end_date"}]}',
            ]
        ];


        foreach ($sub_module as $sm) {
            foreach ($content as $key => $item) {
                $check = NewsletterModule::where('module', 'Sales')->where('submodule', $sm)->first();
                if (!$check) {
                    $new = new NewsletterModule();
                    $new->module = 'Sales';
                    $new->submodule = $sm;
                    if ($sm == 'Account') {
                        $new->field_json = $item[$sm];
                    }
                    if ($sm == 'Contact') {
                        $new->field_json = $item[$sm];
                    }
                    if ($sm == 'Sales Invoice') {
                        $new->field_json = $item[$sm];
                    }
                    if ($sm == 'Sales Order') {
                        $new->field_json = $item[$sm];
                    }
                    if ($sm == 'Meeting') {
                        $new->field_json = $item[$sm];
                    }
                    if ($sm == 'Call') {
                        $new->field_json = $item[$sm];
                    }

                    $new->save();
                }
            }
        }

        $sub_module = [
            'Zoom Meeting'
        ];

        $content = [
            [
                'Zoom Meeting' => '{"field":[{"label":"Start Date/Time","placeholder":"Select Date/Time","field_type":"datetime-local","field_name":"start_date"}]}',

            ]
        ];


        foreach ($sub_module as $sm) {
            foreach ($content as $key => $item) {
                $check = NewsletterModule::where('module', 'ZoomMeeting')->where('submodule', $sm)->first();
                if (!$check) {
                    $new = new NewsletterModule();
                    $new->module = 'ZoomMeeting';
                    $new->submodule = $sm;
                    if ($sm == 'Zoom Meeting') {
                        $new->field_json = $item[$sm];
                    }
                    $new->save();
                }
            }
        }


    }
}
