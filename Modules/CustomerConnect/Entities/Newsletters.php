<?php

namespace Modules\CustomerConnect\Entities;


use App\Mail\CommonEmailTemplate;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\CustomerConnect\Traits\CompanyScoped;

class Newsletters extends Model
{
    use CompanyScoped;
    use HasFactory;

    protected $fillable = [
        'module',
        'sub_module',
        'from',
        'emails_list',
        'subject',
        'content',
        'status',
        'workspace_id',
        'created_by'
    ];

    protected static function newFactory()
    {
        return \Modules\CustomerConnect\Database\factories\NewslettersFactory::new();
    }


    public static function newsletterEmailTemplate($mailTo,$templates)
    {

        $mailTo = array_values($mailTo);
        if(isset($templates) && !empty($templates))
        {

            // get email content language base
            $content = (object)$templates;

            if(count($templates) > 0)
            {

                // send email
                if(!empty(company_setting('mail_from_address',creatorId(),getActiveWorkSpace())))
                {
                    if(!empty(creatorId())&& !empty(getActiveWorkSpace()))
                    {
                        $setconfing =  SetConfigEmail(creatorId());
                    }elseif(!empty(creatorId())&& !empty(getActiveWorkSpace()))
                    {
                        $setconfing =  SetConfigEmail(creatorId(),getActiveWorkSpace());
                        }else{
                            $setconfing =  SetConfigEmail();
                        }
                        if($setconfing ==  true)
                        {
                            try
                            {
                                Mail::to($mailTo)->send(new CommonEmailTemplate($content,creatorId(),getActiveWorkSpace()));
                            }
                            catch(\Exception $e)
                            {
                                $error = $e->getMessage();
                            }
                         }
                         else
                         {
                            $error = __('Something went wrong please try again ');
                         }
                    }
                    else
                    {
                        $error = __('E-Mail has been not sent due to SMTP configuration');
                    }

                    if(isset($error))
                    {
                        $arReturn = [
                            'is_success' => false,
                            'error' => $error,
                        ];
                    }
                    else
                    {
                        $arReturn = [
                            'is_success' => true,
                            'error' => false,
                        ];
                    }
                }
                else
                {
                    $arReturn = [
                        'is_success' => false,
                        'error' => __('Mail not send, email is empty'),
                    ];
                }
                return $arReturn;

            }
            else
            {
                return [
                    'is_success' => false,
                    'error' => __('Mail not send, email not found'),
                ];
            }
    }

}

