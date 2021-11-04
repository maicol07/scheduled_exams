<!DOCTYPE html>
<html lang="{{app()->getLocale()}}">
@include('layouts.head')
<body class="mdc-typography">

<top-app-bar>
    @include('layouts.top-app-bar')
    <material-drawer @mobile type="modal" @elsenotmobile type="dismissible" open @endmobile>
        @include('layouts.drawer')
        <div slot="appContent">
            <main>
                @inertia
            </main>
        </div>
    </material-drawer>
    <footer class="@if(session('high-contrast')) mdc-high-contrast @endif">
        <div class="left-footer">
            <span>@lang('Copyright © 2019-:current_year Maicol07', ['current_year' => today()->format('Y')])</span>
            @notmobile
            <span>&nbsp;- @lang('Sviluppato da Maicol07')</span>
        </div>
        <div class="right-footer">
            <a href="https://docs.maicol07.it/en/legal/privacy-cookie">
                <mwc-button label="@lang('Privacy')" dense>
                    <i class="mdi mdi-file-document-outline" slot="icon"></i>
                </mwc-button>
            </a>
            <a href="https://docs.maicol07.it/en/legal/terms">
                <mwc-button label="@lang('Termini')" dense>
                    <i class="mdi mdi-gavel" slot="icon"></i>
                </mwc-button>
            </a>
            @endnotmobile
        </div>
    </footer>
</top-app-bar>

@include('layouts.top-app-bar-menus')

@routes
@client

@vite('app')
@php
    /** @var string $translations */
    $translations = Cache::get('translations');
@endphp
<script type="module" async="" src="https://embed.launchnotes.io/latest/dist/esm/launchnotes-embed.js"></script>
<script nomodule="" async="" src="https://embed.launchnotes.io/latest/dist/esm/launchnotes-embed.js"></script>
<script>
    window.translations = JSON.parse('{!! $translations !!}')
</script>
</body>
</html>
