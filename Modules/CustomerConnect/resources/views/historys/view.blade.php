<div class="modal-body">
    <div class="row">
        <div class="col-lg-12">
            @php
            $contents = json_decode($newsletter->emails_list,true);
            @endphp
            <table class="table modal-table">
                <tbody>
                    <tr>
                        <th>{{__('Send By')}}</th>
                        <td>{{ $newsletter->from }}</td>
                    </tr>
                    <tr>
                        <th>{{__('Email List')}}</th>
                        <td style="white-space: normal;">
                            @if (!empty($contents))
                                @foreach ($contents as $email)
                                    <span class="email-address">{{ $email }}</span>
                                @endforeach
                            @else
                                <span class="text-danger">{{__('Users not found')}}</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>{{__('Email Subject')}}</th>
                        <td style="white-space: normal;">{{$newsletter->subject}}</td>
                    </tr>
                    <tr>
                        <th>{{__('Email Content')}}</th>
                        <td style="white-space: normal;">{!! $newsletter->content !!}</td>
                    </tr>

                    <tr>
                        <th>{{ __('Total Emails Found') }}</th>
                        <td>
                            @if (!empty($contents))
                                {{ count($contents) }} <!-- Count the total number of emails in $contents array -->
                            @else
                               {{ __('0') }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>{{__('Created')}}</th>
                        <td>{{($newsletter->created_at)}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>


    .email-address::after {
        content: "\00a0"; /* Unicode character for a non-breaking space */
        margin-left: 4px;
    }


</style>

