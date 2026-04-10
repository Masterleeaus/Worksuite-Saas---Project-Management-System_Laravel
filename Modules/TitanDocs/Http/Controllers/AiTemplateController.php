<?php

namespace Modules\TitanDocs\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\ApikeySetiings;
use Modules\TitanDocs\Entities\AiTemplate;
use Modules\TitanDocs\Entities\AiPromptHistory;
use Modules\TitanDocs\Entities\AiTemplateLanguage;
use Modules\TitanDocs\Entities\AiTemplateCategory;
use Modules\TitanDocs\Entities\AiTemplatePrompt;
use Modules\TitanDocs\Entities\AiPromptResponse;
use Modules\TitanDocs\Events\UpdateHistory;

class AiTemplateController extends Controller
{
    /**
     * PASS 1: TitanDocs must not call AI providers directly.
     * All generation/regeneration is routed through TitanZero Gateway.
     */
    private function titanZeroProposeDocument(array $envelope): array
    {
        try {
            $tenantId = data_get($envelope, 'tenant_id');
            /** @var \Modules\TitanZero\Services\ZeroGateway $gw */
            $gw = app(\Modules\TitanZero\Services\ZeroGateway::class);

            return $gw->proposeDocument($envelope, $tenantId);
        } catch (\Throwable $e) {
            return ['status' => 'error', 'message' => 'TitanZero gateway exception', 'details' => $e->getMessage()];
        }
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if(\Auth::user()->isAbleTo('ai document manage'))
        {
            $template=AiTemplate::where('status',true)->orderBy('id', 'asc')->get();
            $content = AiTemplate::where('category_id', '1')->where('status', true)->get();
            $blog = AiTemplate::where('category_id', '2')->where('status', true)->get();
            $website = AiTemplate::where('category_id', '3')->where('status', true)->get();
            $social = AiTemplate::where('category_id', '4')->where('status', true)->get();
            $email = AiTemplate::where('category_id', '6')->where('status', true)->get();
            $video = AiTemplate::where('category_id', '5')->where('status', true)->get();
            $other = AiTemplate::where('category_id', '7')->where('status', true)->get();

            return view('aidocument::document.index',compact('template', 'content', 'blog', 'website', 'social', 'email', 'video', 'other'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }

    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return redirect()->back()->with('error', __('Permission denied.'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        if(\Auth::user()->isAbleTo('ai document create'))
        {
            if(isset($request->template_id) && !empty($request->template_id))
            {
                $document_store=new AiPromptHistory();
                $document_store->template_id=$request->template_id;
                $template = AiTemplate::where('id', $request->template_id)->first();
                if($template)
                {
                    $document_store->doc_name=$template->name."_".time();
                    $document_store->workspace =getActiveWorkSpace();
                    $document_store->created_by=auth()->user()->id;
                    $document_store->save();


                    return response()->json(['status'=>1,"URL"=>route('aidocument.document.show',[$document_store->id,$request->template_id])]);
                }
                else{
                    return response()->json(['stutas' =>0,'error' => __('Document does not exist')]);
                }
            }
            else
            {
                 return response()->json(['stutas' =>0,'error' => __('Please select any document')]);
            }
        }
        else
        {
            return response()->json(['stutas' =>0,'error' => __('Permission Denied.')]);
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($doc_id,$id)
    {

        if(\Auth::user()->isAbleTo('ai document view'))
        {
            $languages = AiTemplateLanguage::where('status',1)->orderBy('id', 'asc')->get();

            $template = AiTemplate::where('id', $id)->first();



            $html="";
            if($template)
            {

                $history_data=AiPromptResponse::where("created_by",auth()->user()->id)->where("history_prompt_id",$doc_id)->orderBy('id', 'DESC')->get();

                $field_data=json_decode($template->form_fields);

                foreach ($field_data->field as  $value) {
                   $html.='<div class="form-group col-md-12">
                                <label class="form-label ">'.$value->label.'</label>';
                                if($value->field_type=="text_box")
                                {

                                    $html.='<input type="text" class="form-control" name="'.$value->field_name.'" value="" placeholder="'.$value->placeholder.'" required">';
                                }
                                if($value->field_type=="textarea")
                                {
                                    $html.='<textarea type="text" rows=5 class="form-control " id="description" name="'.$value->field_name.'" placeholder="'.$value->placeholder.'" required></textarea>';
                                }
                    $html.='</div>';
                }
                $tones=$this->tone_list();

                $document=AiPromptHistory::where('id',$doc_id)->first();
                if($document){
                    return view('aidocument::document.view', compact('languages','template','html','tones','document','history_data'));
                }
                else
                {
                    return redirect()->route('aidocument.index')->with('error', __('Something went wrong..'));
                }

            }
            else
            {
                return redirect()->back()->with('error', __('Some thing went wronge.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }

        //return view('aidocument::show');
    }
    public function tone_list()
    {
        $ai_tone=array('funny','casual','excited','professional','witty','sarcastic','feminine','masculine','bold','dramatic','gumpy','secretive');
        return $ai_tone;
    }
    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */


    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
       return redirect()->back()->with('error', __('Permission Denied.'));
    }
    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */

    public function AiGenerate(Request $request)
    {
        // PASS 1 LOCKDOWN: route generation via TitanZero (no direct provider calls here)
        $template = null;
        if ($request->filled('template')) {
            $template = AiTemplate::where('template_code', (string)$request->input('template'))->first();
        }
        $kbCollectionKey = $template && isset($template->kb_collection_key)
            ? (string)$template->kb_collection_key
            : 'kb_general_cleaning';

        $envelope = [
            'template' => $request->input('template'),
            'tone' => $request->input('tone'),
            'language' => $request->input('language'),
            'inputs' => $request->all(),
            'kb_collection_key' => $kbCollectionKey,
            'knowledge_scope' => 'docs',
        ];
        $zero = $this->titanZeroProposeDocument($envelope);
        // Normalize into legacy response shape expected by the UI
        if (($zero['status'] ?? null) !== 'ok') {
            return [
                'status' => 'error',
                'message' => $zero['message'] ?? 'TitanZero error',
                'details' => $zero['details'] ?? null,
            ];
        }
        return [
            'status' => 'success',
            'audit_id' => $zero['audit_id'] ?? null,
            'text' => data_get($zero, 'proposal.text', '[PASS1] TitanZero proposal missing'),
            'risk' => data_get($zero, 'proposal.risk', 'amber'),
        ];


        /*
         |--------------------------------------------------------------
         | LEGACY DIRECT-PROVIDER CODE (DISABLED)
         |--------------------------------------------------------------
         | This block used to call OpenAI directly. TitanDocs must never
         | bypass Titan Zero, so it is intentionally disabled.
         */

        if ($request->ajax())
        {
            
            if(\Auth::user()->isAbleTo('ai document generate'))
            {
                $post=$request->all();
                unset($post['_token']);
                unset($post['creativity']);
                unset($post['max_results']);
                unset($post['words']);
                unset($post['document_id']);
                unset($post['document_name']);
                $data=array();
                $key_data= ApikeySetiings::inRandomOrder()->limit(1)->first();
                if($key_data)
                {
                    $open_ai = new OpenAi($key_data->key);
                }
                else
                {
                    $data['status'] = 'error';
                    $data['message'] = __('Please set proper configuration for Api Key');
                    return $data;
                }
                $prompt = '';
                $model = '';
                $text = '';
                $ai_token = '';
                $counter = 1;
                $template = AiTemplate::where('template_code', $request->template)->first();

                if ($request->template)
                {
                    if ($template->template_code == $request->template)
                    {
                        $required_field=array();
                        $data_field=json_decode($template->form_fields);
                        foreach ($data_field->field as  $val)
                        {
                           request()->validate([$val->field_name => 'required|string']);
                        }

                        $temp_prompt=AiTemplatePrompt::where('template_id',$template->id)->where("created_by",auth()->user()->id)->where('key',$post['language'])->first();
                        if($temp_prompt)
                        {
                            $prompt=$temp_prompt->value;
                        }
                        else
                        {
                            $temp_prompt =AiTemplatePrompt::where('template_id',$template->id)->where("created_by",0)->where('key',$post['language'])->first();

                            $prompt=$temp_prompt->value;
                        }

                        foreach ($data_field->field as  $field)
                        {

                            $text_rep="##".$field->field_name."##";
                            if(strpos($prompt,$text_rep) !== false)
                            {
                                $field->value=$post[$field->field_name];
                                $prompt=str_replace($text_rep,$post[$field->field_name],$prompt);
                            }else
                            {
                                $field->value=$post[$field->field_name];
                            }
                            if($template->is_tone==1)
                            {

                                $ai_tone=$post["tone_language"];
                                $param="##tone_language##";
                                $prompt=str_replace($param,$ai_tone,$prompt);
                            }

                        }

                    }
                }
                $ai_token = (int)$request->words;
                $max_results = (int)$request->max_results;
                $ai_creativity = (float)$request->creativity;

                $complete = $open_ai->completion([
                    'model' => 'text-davinci-003',
                    'prompt' =>$prompt,
                    'temperature' => $ai_creativity,
                    'max_tokens' => $ai_token,
                    'n' => $max_results,
                ]);

                $response = json_decode($complete , true);
                
                if (isset($response['choices']))
                {

                    if (count($response['choices']) > 1)
                    {
                        foreach ($response['choices'] as $value)
                        {
                            $text .= $counter . '. ' . ltrim($value['text']) . "\r\n\r\n\r\n";
                            $counter++;
                        }
                    }
                    else
                    {
                        $text = $response['choices'][0]['text'];
                    }

                    $tokens = $response['usage']['completion_tokens'];

                    $flag = AiTemplateLanguage::where('code', $request->language)->first();


                    $data['text'] = trim($text);

                    $prompt_store=AiPromptHistory::where("id",$request->document_id)->where('created_by',auth()->user()->id)->first();

                    $prompt_store->template_id = $template->id;
                    $prompt_store->creativity = $ai_creativity;
                    $prompt_store->max_tokens = $ai_token;
                    $prompt_store->max_results = $max_results;
                    $prompt_store->model = $model;
                    $prompt_store->prompt = $prompt;
                    $prompt_store->language = $post["language"];
                    $prompt_store->prompt_fields = json_encode($data_field);
                    $prompt_store->created_by = auth()->user()->id;
                    $prompt_store->save();

                    $content = new AiPromptResponse();
                    $content->template_id = $template->id;
                    $content->history_prompt_id = $prompt_store->id;
                    $content->used_words=$tokens;
                    $content->content = $data['text'];
                    $content->created_by = auth()->user()->id;
                    $content->save();

                    $data['prompt'] = $prompt;
                    $data['status'] = 'success';
                    $data['id'] = $content->id;
                    return $data;

                }
                else
                {
                    $data['status'] = 'error';
                    $data['message'] = __('Text was not generated, please try again');
                    return $data;
                }
            }
            else
            {
                $data['status'] = 'error';
                $data['message'] =__('Permission Denied.');
                return $data;
            }
        }
    }
    public function regenerate_response(Request $request)
    {
        // PASS 1 LOCKDOWN: route regeneration via TitanZero (no direct provider calls here)
        $template = null;
        if ($request->filled('template')) {
            $template = AiTemplate::where('template_code', (string)$request->input('template'))->first();
        }
        $kbCollectionKey = $template && isset($template->kb_collection_key)
            ? (string)$template->kb_collection_key
            : 'kb_general_cleaning';

        $envelope = [
            'template' => $request->input('template'),
            'history_id' => $request->input('history_id'),
            'inputs' => $request->all(),
            'kb_collection_key' => $kbCollectionKey,
            'knowledge_scope' => 'docs',
            'mode' => 'regenerate',
        ];
        $zero = $this->titanZeroProposeDocument($envelope);

        if (($zero['status'] ?? null) !== 'ok') {
            return response()->json([
                'status' => 'error',
                'message' => $zero['message'] ?? 'TitanZero error',
                'details' => $zero['details'] ?? null,
            ]);
        }

        return response()->json([
            'status' => 'success',
            'audit_id' => $zero['audit_id'] ?? null,
            'text' => data_get($zero, 'proposal.text', '[PASS1] TitanZero proposal missing'),
            'risk' => data_get($zero, 'proposal.risk', 'amber'),
        ]);


        if ($request->ajax())
        {
            if(\Auth::user()->isAbleTo('ai document generate'))
            {
                $data=array();
                $key_data= ApikeySetiings::inRandomOrder()->limit(1)->first();

                if($key_data)
                {

                    $open_ai = new OpenAi($key_data->key);
                }
                else
                {
                    $data['status'] = 'error';
                    $data['message'] = __('Please set proper configuration for Api Key');
                    return $data;
                }
                $prompt = '';
                $model = '';
                $text = '';
                $ai_token = '';
                $counter = 1;
                $prompt=AiPromptHistory::where('id',$request->prompt_id)->where('created_by',auth()->user()->id)->first();
                $ai_token = (int)$prompt->max_tokens;

                $max_results = (int)$prompt->max_results;
                $ai_creativity = (float)$prompt->creativity;
                $model=$prompt->model;

                $complete = $open_ai->completion([
                    'model' => 'text-davinci-003',
                    'prompt' => $prompt->prompt,
                    'temperature' => $ai_creativity,
                    'max_tokens' => $ai_token,
                    'n' => $max_results,
                ]);

                $response = json_decode($complete , true);
                if (isset($response['choices']))
                {

                    if (count($response['choices']) > 1)
                    {
                        foreach ($response['choices'] as $value)
                        {
                            $text .= $counter . '. ' . ltrim($value['text']) . "\r\n\r\n\r\n";
                            $counter++;
                        }
                    }
                    else
                    {
                        $text = $response['choices'][0]['text'];
                    }


                    $tokens = $response['usage']['completion_tokens'];

                    $flag = AiTemplateLanguage::where('code', $request->language)->first();


                    $data['text'] = trim($text);


                    $content = new AiPromptResponse();
                    $content->template_id = $request->template_id;
                    $content->history_prompt_id = $request->prompt_id;
                    $content->used_words=$tokens;
                    $content->content = $data['text'];
                    $content->created_by = auth()->user()->id;
                    $content->save();

                    $data['prompt'] = $prompt->prompt;
                    $data['status'] = 'success';
                    $data['id'] = $content->id;
                    return $data;

                }
                else
                {
                    $data['status'] = 'error';
                    $data['message'] = __('Text was not generated, please try again');
                    return $data;
                }
            }
            else
            {
                $data['status'] = 'error';
                $data['message'] =__('Permission Denied.');
                return $data;
            }
        }
    }
    public function exportallresponsecontent(Request $request)
    {
        $template=AiPromptResponse::select('content')->where('history_prompt_id',$request->history_prompt_id)->where('created_by',Auth::user()->id)->get();
        $html="";
        foreach ($template as  $value) {
            $html.=$value->content."<br><br><br>";
        }
        return $html;
    }
    public function exportresponsecontent(Request $request)
    {
        $template=AiPromptResponse::select('content')->where('id',$request->response_id)->where('created_by',Auth::user()->id)->get();
        $html="";
         foreach ($template as  $value) {
            $html.=$value->content;
        }
        return $html;
    }
    public function edit($doc_id,$id)
    {
        if(\Auth::user()->isAbleTo('document history edit'))
        {
            $languages = AiTemplateLanguage::where('status',1)->orderBy('id', 'asc')->get();

            $template = AiTemplate::where('id', $id)->first();
            $html="";
            if($template)
            {
                $document=AiPromptHistory::where('id',$doc_id)->first();

                $history_data=AiPromptResponse::where("created_by",auth()->user()->id)->where("history_prompt_id",$doc_id)->orderBy('id', 'DESC')->get();
                if(!empty($document->prompt_fields))
                {
                    $field_data=json_decode($document->prompt_fields);
                    foreach ($field_data->field as  $value) {
                       $html.='<div class="form-group col-md-12">
                                    <label class="form-label ">'.$value->label.'</label>';
                                    if($value->field_type=="text_box")
                                    {
                                        $html.='<input type="text" class="form-control" name="'.$value->field_name.'" value="'.$value->value.'" placeholder="'.$value->placeholder.'">';
                                    }
                                    if($value->field_type=="textarea")
                                    {
                                        $html.='<textarea type="text" rows=5 class="form-control " id="description" name="'.$value->field_name.'" placeholder="'.$value->placeholder.'" required>'.$value->value.'</textarea>';
                                    }
                        $html.='</div>';
                    }
                }
                else
                {
                    $field_data=json_decode($template->form_fields);
                    foreach ($field_data->field as  $value) {
                       $html.='<div class="form-group col-md-12">
                        <label class="form-label ">'.$value->label.'</label>';
                        if($value->field_type=="text_box")
                        {

                            $html.='<input type="text" class="form-control" name="'.$value->field_name.'" placeholder="'.$value->placeholder.'">';
                        }
                        if($value->field_type=="textarea")
                        {
                            $html.='<textarea type="text" rows=5 class="form-control " id="description" name="'.$value->field_name.'" placeholder="'.$value->placeholder.'" required></textarea>';
                        }
                        $html.='</div>';
                    }
                }
                $tones=$this->tone_list();

                return view('aidocument::document.edit', compact('languages', 'template','html','history_data','tones','document'));
            }
            else
            {
                return redirect()->back()->with('error', __('Document does not exist'));
            }
         }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }
    public function save(Request $request)
    {
        $store=AiPromptHistory::where('id',$request->doc_id)->where('created_by',Auth::user()->id)->where('workspace',getActiveWorkSpace())->first();
        if($store)
        {
            $store->doc_name=$request->document_name;
            $store->save();

            event(new UpdateHistory($request));

            return 1;
        }
        else
        {
          return 0;
        }

    }
    public function destroy($id)
    {
       
        if(\Auth::user()->isAbleTo('document history delete'))
        {
            $data=AiPromptHistory::where('id',$id)->first();
            if($data)
            {
                $response=AiPromptResponse::where('history_prompt_id',$id)->delete();

                $data->delete();
                return redirect()->route('aidocument.document.history')->with('success', __('Document successfully deleted .'));
            } else {
                return redirect()->back()->with('error', __('Something is wrong.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }
    public function history(Request $request)
    {
        if(\Auth::user()->isAbleTo('document history manage'))
        {


            $document=AiPromptHistory::where('workspace',getActiveWorkSpace())->where('created_by',auth()->user()->id)->orderBy('id','desc')->get();
            foreach ($document as  $value) {
                $value->category="";
                $value->category_id="";
                $template=AiTemplate::where('id',$value->template_id)->first();
                if($template)
                {
                    $category=AiTemplateCategory::where('id',$template->category_id)->first();
                    if($category)
                    {
                        $value->category_id=$category->id;

                        $value->category=$category->name;
                    }
                }
                $value->created_on=date('M j, Y H:i', strtotime($value->created_at));
                if($value->language =='' &&empty($value->language))
                {
                    $value->language ='en-US';
                }
                if($value->max_tokens =='' &&empty($value->max_tokens))
                {
                    $value->max_tokens =0;
                }
            }
            return view('aidocument::document.history', compact('document'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }


}
