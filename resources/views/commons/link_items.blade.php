@if (Auth::check())
{{-- ユーザー一覧ページへのリンク --}}
<li>
    <details class="dropdown dropdown-end">
        <summary>{{ Auth::user()->name }}
        </summary>
        <ul tabindex="0" class="dropdown-content z-[1] w-52 p-2 shadow">
            <li class="divider lg:hidden"></li>
            {{-- ログアウトへのリンク --}}
            <li><a class="text-black" href="#" onclick="event.preventDefault();this.closest('form').submit();">Logout</a></li>
        </ul>
    </details>
</li>
{{-- ユーザー詳細ページへのリンク --}}

@else
{{-- ユーザー登録ページへのリンク --}}
<li><a class="link link-hover" href="{{ route('register') }}">Signup</a></li>
<li class="divider lg:hidden"></li>
{{-- ログインページへのリンク --}}
<li><a class="link link-hover" href="{{ route('login') }}">Login</a></li>
@endif