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
        <mwc-button outlined label="@lang('Il tuo profilo')" class="mwc-button--rounded" style="margin-top: 16px;">
            <i class="mdi mdi-account-circle-outline" slot="icon"></i>
        </mwc-button>
    </a>
    <br>
    <form action="" method="post">
        @csrf
        <mwc-button outlined type="submit" label="@lang('Esci')" style="margin-top: 16px;">
            <i class="mdi mdi-logout-variant" slot="icon"></i>
        </mwc-button>
    </form>
    <hr>
</mwc-menu>
