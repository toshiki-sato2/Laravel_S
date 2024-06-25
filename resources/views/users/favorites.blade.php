@extends('layouts.app')

@section('content')
    <div class="sm:grid sm:grid-cols-3 sm:gap-10">
        <aside class="mt-4">
            {{-- ユーザー情報 --}}
            @include('users.card')
        </aside>
        <div class="sm:col-span-2 mt-4">
            {{-- タブ --}}
            @include('users.navtabs')
            <div class="mt-4">
                {{-- ポスト一覧 --}}
                {{-- @include('microposts.microposts') --}}
                @if(Auth::id() == $user->id)
                    @include("microposts.favoriteposts")
                @else
                    @include("microposts.others")
                @endif
            </div>
        </div>
    </div>
@endsection