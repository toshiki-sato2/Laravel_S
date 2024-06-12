<div class="mb-4 mt-4">
    <input type="text" name="keyword" placeholder="投稿検索" class="" id="input_style">
</div>

@if (isset($users))
    <ul class="list-none">
        <div class = "mb-4 {{ Request::routeIs('users.followers') ? 'hidden' : '' }} {{ Request::routeIs('users.followings') ? 'hidden' : '' }}">
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
                        @if ($user->avatar_path === "default.png")
                            <img src="{{ Gravatar::get($user->email, ['size' => 500]) }}" alt="">
                        @else
                            <img src="{{ asset('storage/' . $user->avatar_path) }}" alt="Avatar">
                        @endif
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
            <h1>Ops! there is no user.</h1>
        @endif
    </ul>
    {{-- ページネーションのリンク --}}
    {{ $users->links() }}
@endif


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    $('input[name="keyword"]').on('input', function() {
        var keyword = $(this).val();
        console.log("Keyword changed:", keyword); // この行を追加

        $.ajax({
            url: '{{ route('users.search') }}', // URLが正しいか確認
            type: 'GET',
            data: {keyword: keyword},
            dataType: 'json',
            success: function(data) {
                console.log("Data received:", data); // 受け取ったデータをログに出力
            },
            error: function(xhr, status, error) {
                console.error("Error:", status, error); // エラー情報をログに出力
            }
        });
    });
});



</script>