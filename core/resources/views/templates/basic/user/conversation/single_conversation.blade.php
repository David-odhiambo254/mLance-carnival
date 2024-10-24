@forelse($conversationList ?? [] as $conversation)
    @php
        $message = $conversation->messages->last();
        $unread = $message->read == Status::NO;
        $sendTo = $conversation->seller_id == auth()->id() ? $conversation->buyer : $conversation->seller;
    @endphp
    <li class="{{ menuActive('user.conversation.details', null, $conversation->id) }} conversation">
        <a data-id="{{ $conversation->id }}" href="{{ route('user.conversation.details', $conversation->id) }}">
            <span class="icon mx-1">
                <img src="{{ avatar($sendTo->image ? getFilePath('userProfile') . '/' . $sendTo->image : null) }}"
                    alt="@lang('image')" />
            </span>
            <p class="chat-item">
                <b class="title">{{ $sendTo->fullname }}</b>
                <span class="time @if ($unread) fw-bold font-monospace @endif recent-message">
                    {{ strLimit(@$message->message, 45) }}
                </span>
            </p>
        </a>
    </li>
@empty
    @include($activeTemplate . 'partials.empty', ['message' => 'Chat not found'])
@endforelse

@push('style')
    <style>
        .empty-slip-message {
            display: grid;
            place-content: center;
            height: 30vh;
            color: #cfcfcf;
            font-size: 0.8754rem;
            font-family: inherit;
        }

        .empty-slip-message img {
            width: 75px;
            margin-bottom: 0.875rem;
        }
    </style>
@endpush
