<mwc-icon-button slot="navigationIcon"><i class="mdi mdi-menu"></i></mwc-icon-button>
<div slot="title" style="display: flex; align-items: center;">
    <img src="{{vite_asset('images/favicon/favicon.ico')}}" alt="@lang('Scheduled Exams')" style="margin-right: 8px;">
    <span>@lang('Scheduled Exams')</span>
</div>

<mwc-icon-button id="navbar-announcements" slot="actionItems" label="@lang('Notifications')">
    <i class="mdi mdi-bell-outline"></i>
</mwc-icon-button>
<mwc-icon-button id="change-theme-btn" slot="actionItems" label="@lang('Cambia tema')">
    <i class="mdi mdi-invert-colors"></i>
</mwc-icon-button>
<mwc-icon-button id="change-language-btn" slot="actionItems" label="@lang('Cambia lingua')">
    <i class="mdi mdi-translate"></i>
</mwc-icon-button>
<mwc-icon-button id="user-info-btn" slot="actionItems" label="@lang('Your profile')">
    @if (Auth::hasUser())
        <img src="{{auth()->user()->picture}}" alt="{{auth()->user()->username}}" style="border-radius: 50%;">
    @else
        <i class="mdi mdi-account-outline"></i>
    @endif
</mwc-icon-button>
