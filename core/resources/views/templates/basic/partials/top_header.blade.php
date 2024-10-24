@php
    $conversationUnread = App\Models\ConversationMessage::where('receiver_id', auth()->id())
        ->where('read', Status::NO)
        ->count();
@endphp
<div class="top-header">
    <div class="container">
        <div class="d-flex justify-content-between flex-wrap gap-2">
            <div class="navbar-brand logo">
                <a href="{{ route('home') }}">
                    <img src="{{ siteLogo() }}" alt="@lang('logo')">
                </a>
            </div>
            <div class="user-info">
                <div class="user-info__right d-flex align-items-center">
                    <div class="notification">
                        <a class="notification-link @if ($conversationUnread) unread-message @endif" href="{{ route('user.conversation.index') }}"><i class="las la-envelope"></i></a>
                    </div>
                    <div class="user-info-user position-relative">
                        <span class="text-white dropdown-username">{{ auth()->user()->fullname }}</span>
                        <button class="user-profile-icon ">
                            <span class="user-profile"> <img src="{{ avatar(auth()->user()->image ? getFilePath('userProfile') . '/' . auth()->user()->image : null) }}" alt="@lang('image')"></span>
                        </button>
                        <ul class="user-info-dropdown d-none">
                            <li class="user-info-dropdown__item"><a class="user-info-dropdown__link" href="{{ route('user.profile.setting') }}">
                                    <span class="icon"><i class="las la-user-circle"></i></span>
                                    <span class="text">@lang('My Profile')</span>
                                </a>
                            </li>
                            <li class="user-info-dropdown__item"><a class="user-info-dropdown__link" href="{{ route('user.change.password') }}">
                                    <span class="icon"><i class="la la-lock"></i></span>
                                    <span class="text">@lang('Password')</span>
                                </a>
                            </li>
                            <li class="user-info-dropdown__item"><a class="user-info-dropdown__link" href="{{ route('user.twofactor') }}">
                                    <span class="icon"> <i class="la la-key"></i></span>
                                    <span class="text">@lang('2FA Security')</span>
                                </a>
                            </li>
                            <li class="user-info-dropdown__item"><a class="user-info-dropdown__link" href="{{ route('ticket.index') }}">
                                    <span class="icon"> <i class="la la-ticket-alt"></i></span>
                                    <span class="text">@lang('Support')</span>
                                </a>
                            </li>
                            <li class="user-info-dropdown__item"><a class="user-info-dropdown__link" href="{{ route('user.logout') }}">
                                    <span class="icon"><i class="las la-sign-out-alt"></i></span>
                                    <span class="text"> @lang('Logout')</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="menu-bar-icon">
                        <button class="menu-bar-btn"><i class="las la-bars"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
