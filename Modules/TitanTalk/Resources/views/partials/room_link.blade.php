{{--
    Reusable sidebar room link partial.
    Variables: $room (TitanTalkRoom), $prefix (string — HTML prefix like '#' or icon)
--}}
<a href="{{ route('titan.talk.room.show', $room->id) }}"
   class="d-flex justify-content-between align-items-center px-2 py-1 rounded mb-1 text-decoration-none tt-room-link {{ isset($activeRoom) && $activeRoom->id == $room->id ? 'active' : 'text-dark' }}"
   data-room-id="{{ $room->id }}">
    <span>{!! $prefix !!} {{ $room->name }}</span>
    <span class="tt-unread-badge badge badge-danger badge-pill" data-room="{{ $room->id }}" style="display:none;"></span>
</a>
