<launchnotes-embed
    token="public_PmygEcoyVxYZFALtlfheHm9l"
    project="pro_3dqgGLD1mcOgb"
    categories='["scheduled-exams"]'
    toggle-selector="#navbar-announcements"
    heading="@lang('Latest updates')"
    heading-color="#FFF"
    subheading="@lang('The latest features, improvements, and bug fixes')"
    subheading-color="rgba(255, 255, 255, 0.8)"
    primary-color="#8000ff"
    widget-placement="auto"
    widget-offset-skidding="0"
    widget-offset-distance="0"
    show-unread
    anonymous-user-id="{{sha1(auth()->user()->uuid)}}"
    unread-placement="right-start"
    unread-offset-skidding="-4"
    unread-offset-distance="4"
    unread-background-color="#0080ff"
    unread-text-color="#ffffff"
></launchnotes-embed>

<mwc-menu activatable corner="BOTTOM_RIGHT" id="theme-switcher" trigger="change-theme-btn">
    @foreach([
            'white' => [
                'icon' => 'weather-sunny',
                'text' => __('White')
            ],
            'dark' => [
                'icon' => 'weather-night',
                'text' => __('Black')
            ]
        ] as $theme => $theme_details)
        <mwc-list-item graphic="icon" @if(session('theme', 'white') === $theme) selected activated @endif value="{{$theme}}">
            <i class="mdi mdi-{{$theme_details['icon']}}" slot="graphic"></i>
            <span>{{$theme_details['text']}}</span>
        </mwc-list-item>
    @endforeach
</mwc-menu>

<mwc-menu activatable corner="BOTTOM_RIGHT" id="lang-switcher" trigger="change-language-btn">
    @foreach(config('app.locales') as $locale)
        <mwc-list-item graphic="icon" @if(app()->getLocale() === $locale) selected activated @endif value="{{$locale}}">
            <i class="flag-icon flag-icon-{{$locale === 'en' ? 'gb' : $locale}}" slot="graphic"></i>
            <span>{{ucfirst(Locale::getDisplayLanguage($locale, app()->getLocale()))}}</span>
        </mwc-list-item>
    @endforeach
</mwc-menu>

<mwc-menu corner="BOTTOM_LEFT" id="user-info" trigger="user-info-btn">
    @if (Auth::hasUser())
        <img class="mdc-elevation--z2" src="{{auth()->user()->picture}}" alt="{{auth()->user()->username}}"
             style="border-radius: 50%;">
    @else
        <i class="mdi mdi-account-outline mdc-elevation--z2"></i>
    @endif
    <br>
    <b style="margin-top: 16px;">{{auth()->user()?->name}}</b>
    <br>
    <span>{{auth()->user()?->email}}</span>
    <br>
    <a href="">
        <mwc-button outlined label="@lang('Your profile')" class="mwc-button--rounded" style="margin-top: 16px;">
            <i class="mdi mdi-account-circle-outline" slot="icon"></i>
        </mwc-button>
    </a>
    <br>
    <a href="{{route('oidc.logout')}}">
        <mwc-button outlined type="submit" label="@lang('Logout')" style="margin-top: 16px;">
            <i class="mdi mdi-logout-variant" slot="icon"></i>
        </mwc-button>
    </a>
    <hr>
        <mwc-button id="app-info-btn" dense label="@lang('About Scheduled Exams')">
            <i class="mdi mdi-information-outline" slot="icon"></i>
        </mwc-button>
</mwc-menu>

<div id="app-info-container" data-version="{{trim(file_get_contents(base_path('VERSION')))}}"></div>
