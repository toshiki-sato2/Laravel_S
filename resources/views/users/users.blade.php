@if (isset($users))
    <ul class="list-none">
        <div class = "mb-4">
            <form action="{{ route('users.index') }}" method="GET">
                @csrf
                <input type="text" name="keyword" placeholder = "ユーザ名を検索" class = "" id = "input_style">
                <input type="submit" value="検索" class = "bg-gradient-to-b from-blue-300 to-blue-800 hover:bg-gradient-to-l text-white rounded px-4 py-2">
            </form>
        </div>
        @if (!$users->isEmpty())
        @foreach ($users as $user)
            <li class="flex items-center gap-x-2 mb-4">
                {{-- ユーザーのメールアドレスをもとにGravatarを取得して表示 --}}
                <div class="avatar">
                    <div class="w-12 rounded">
                        <img src="{{ Gravatar::get($user->email) }}" alt="" />
                    </div>
                </div>
                <div>
                    <div>
                        {{ $user->name }}
                    </div>
                    <div>
                        {{-- ユーザー詳細ページへのリンク --}}
                        <p><a class="link link-hover text-info" href="{{ route('users.show', $user->id) }}">View profile</a></p>
                    </div>
                </div>
            </li>
        @endforeach

        @else
            <h1>Ops! there is no post.</h1>
        @endif
    </ul>
    {{-- ページネーションのリンク --}}
    {{ $users->links() }}
@endif