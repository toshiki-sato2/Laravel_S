<div class="mb-4 mt-4">
    <input type="text" name="keyword" placeholder="ユーザ検索" class="" id="input_style">
</div>

@if (isset($users))
    <ul class="list-none" id="user-list">
        {{-- <div class="mb-4 {{ Request::routeIs('users.followers') ? 'hidden' : '' }} {{ Request::routeIs('users.followings') ? 'hidden' : '' }}">
            <form action="{{ route('users.index') }}" method="GET">
                @csrf
                <input type="text" name="keyword" placeholder="ユーザ名を検索" class="" id="input_style">
                <input type="submit" value="検索" class="bg-gradient-to-b from-blue-300 to-blue-800 hover:bg-gradient-to-l text-white rounded px-4 py-2">
            </form>
        </div> --}}
        @if (!$users->isEmpty())
            @foreach ($users as $user)
                <li class="flex items-center gap-x-2 mb-4">
                    {{-- ユーザーのメールアドレスをもとにGravatarを取得して表示 --}}
                    <div class="avatar">
                        <div class="w-12 rounded">
                            @if ($user->avatar_path === "default.png")
                                <img src="{{ asset('avator/'.$user->avatar_path) }}" alt="Avatar Image">
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
    var searchUrl = '{{ route('users.search') }}';
    console.log("Search URL:", searchUrl); // URLをログに出力

    $('input[name="keyword"]').on('input', function() {
        var keyword = $(this).val();
        console.log("Keyword changed:", keyword);

        $.ajax({
            url: searchUrl,
            type: 'GET',
            data: {keyword: keyword, _token: '{{ csrf_token() }}'},
            dataType: 'json',
            success: function(data) {
                console.log("Data received:", data); // 受け取ったデータをログに出力
                updateUserLists(data.users.data); // data.users.dataを渡す
            },
            error: function(xhr, status, error) {
                console.error("Error:", status, error);
            }
        });
    });
});

function updateUserLists(users) {
    console.log("Users data:", users); // デバッグ用のログを追加

    var userList = $('#user-list');
    userList.empty();
    
    var unbaseUrl='{{ asset("avator/") }}/'

    if (!users || users.length === 0) {
        userList.append('<h1>Ops! there is no user.</h1>');
    } else {
        users.forEach(function(user) {
            var avatarPath = user.avatar_path === "default.png" ? 
                unbaseUrl + '/' +  user.avatar_path : 
                '{{ asset("storage/") }}/' + user.avatar_path;

            var userItem = `
                <li class="flex items-center gap-x-2 mb-4">
                    <div class="avatar">
                        <div class="w-12 rounded">
                            <img src="${avatarPath}" alt="Avatar">
                        </div>
                    </div>
                    <div>
                        <div>${user.name}</div>
                        <div>
                            <p><a class="link link-hover text-info" href="/users/${user.id}">View profile</a></p>
                        </div>
                    </div>
                </li>
            `;
            userList.append(userItem);
        });
    }
}

function md5(string) {
    return CryptoJS.MD5(string).toString();
}
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/crypto-js.js"></script>