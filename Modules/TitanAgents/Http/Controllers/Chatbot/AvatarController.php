<?php

namespace Modules\TitanAgents\Http\Controllers\Chatbot;

use App\Http\Controllers\AccountBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Modules\TitanAgents\Models\Chatbot;
use Modules\TitanAgents\Models\ChatbotAvatar;

class AvatarController extends AccountBaseController
{
    public function store(Request $request, Chatbot $chatbot)
    {
        $request->validate(['avatar' => 'required|image|max:2048']);

        $file     = $request->file('avatar');
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();

        Storage::disk('local')->putFileAs('chatbot-avatars', $file, $filename);

        ChatbotAvatar::updateOrCreate(
            ['chatbot_id' => $chatbot->id],
            [
                'filename'      => $filename,
                'original_name' => $file->getClientOriginalName(),
                'mime_type'     => $file->getMimeType(),
                'file_size'     => $file->getSize(),
            ]
        );

        return back()->with('success', __('Avatar uploaded.'));
    }

    public function destroy(Chatbot $chatbot)
    {
        $avatar = $chatbot->avatar;

        if ($avatar) {
            Storage::disk('local')->delete('chatbot-avatars/' . $avatar->filename);
            $avatar->delete();
        }

        return back()->with('success', __('Avatar removed.'));
    }
}
