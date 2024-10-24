<div class="sticky-order-header">
    <div class="order-details-tabs">
        <a class="{{ menuActive('user.profile.setting') }}" href="{{ route('user.profile.setting') }}"> <i class="las la-user-circle"></i>
            @lang('Basic Info')</a>
        <a class="{{ menuActive('user.profile.educations') }}" href="{{ route('user.profile.educations') }}"><i class="las la-graduation-cap"></i>
            @lang('Educations/Certifications')</a>
        <a class="{{ menuActive('user.profile.portfolio') }}" href="{{ route('user.profile.portfolio') }}"><i class="las la-briefcase"></i>
            @lang('Portfolio')</a>
        <a class="{{ menuActive('user.change.password') }}" href="{{ route('user.change.password') }}"> <i class="la la-lock"></i> @lang('Password')</a>
        <a class="{{ menuActive('user.twofactor') }}" href="{{ route('user.twofactor') }}"> <i class="la la-key"></i> @lang('2FA Security')</a>
    </div>
</div>
