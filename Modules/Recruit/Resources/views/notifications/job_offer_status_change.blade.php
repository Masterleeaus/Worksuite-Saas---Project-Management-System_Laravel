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
                      :link="route('job-offer-letter.show', $notification->data['offer_id'])"
                      :image="$notificationUser && $notificationUser ? $notificationUser->image_url : ''"
                      :title="__('recruit::modules.email.jobOffer')" :text="$notification->data['heading']"
                      :time="$notification->created_at"/>
