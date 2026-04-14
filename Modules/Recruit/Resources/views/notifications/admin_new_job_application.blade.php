@php
    try {

    $notificationUser = App\Models\User::where('id', $notification->data['user_id'])
    ->orderByDesc('id')
    ->first();

    } catch (\Exception $e) {
        // Table may not exist yet
    }
@endphp
<x-cards.notification :notification="$notification"
                      :link="route('job-applications.show', $notification->data['jobApp_id'])"
                      :image="$notificationUser->image_url"
                      :title="__('recruit::modules.adminMail.newJobApplicationSubject')"
                      :text="$notification->data['heading']"
                      :time="$notification->created_at"/>
