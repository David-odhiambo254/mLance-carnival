<div class="single-message @if (auth()->id() == $message->sender_id) message--right @else message--left @endif">
    <div class="message-content-outer">
        <div class="message-content">
            <p class="message-text" data-conversation-id="{{ @$message->conversation_id }}">{{ __($message->message) }}
            </p>
        </div>
        <span class="message-time d-block text-end mt-2">{{ diffForHumans($message->created_at) }}</span>
    </div>
</div>
