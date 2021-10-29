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
            <span>
                <a href="https://openstamanager.com">
                    @lang('Scheduled Exams')
                </a>
            </span>
        </div>
        <div class="right-footer">
            <b>@lang('Versione')</b> {{trim(file_get_contents(base_path('VERSION')))}}
        </div>
    </footer>
</top-app-bar>

@include('layouts.top-app-bar-menus')

@routes
@client

@vite('app')

<script>
    window.translations = JSON.parse('{{file_get_contents(resource_path('lang/'.app()->getLocale().'.json'))}}')
</script>
</body>
</html>
