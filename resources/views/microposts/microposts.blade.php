<div class="mb-4">
    <input type="text" name="keyword" placeholder="投稿を検索" class="bg-gradient-to-b from-blue-300 to-blue-800 hover:bg-gradient-to-l text-white rounded px-4 py-2" id="input_style">
</div>

<div class="mt-4">
    @if (!empty($microposts))
        <ul class="list-none">
            @foreach ($microposts as $micropost)
                <li class="flex items-start gap-x-2 mb-4">
                    {{-- 投稿の所有者のメールアドレスをもとにGravatarを取得して表示 --}}
                    <div class="avatar">
                        <div class="w-12 rounded">
                            @if ($micropost->user->avatar_path === "default.png")
                                <img src="{{ Gravatar::get($user->email, ['size' => 500]) }}" alt="">
                            @else
                                <img src="{{ asset('storage/' . $micropost->user->avatar_path) }}" alt="Avatar">
                            @endif
                        </div>
                    </div>
                    <div>
                        <div>
                            {{-- 投稿の所有者のユーザー詳細ページへのリンク --}}
                            <a class="link link-hover text-info" href="{{ route('users.show', $micropost->user->id) }}">{{ $micropost->user->name }}</a>
                            <span class="text-muted text-gray-500">posted at {{ $micropost->created_at }}</span>
                        </div>
                        <div>
                            {{-- 投稿内容 --}}
                            <p id = "content-{{ $micropost->id }}" class="mb-0">{!! nl2br(e($micropost->content)) !!}</p>
                        </div>
                        <div>
                            @if (Auth::id() == $micropost->user_id)
                                {{-- 投稿削除ボタンのフォーム --}}
                                <form method="POST" action="{{ route('microposts.destroy', $micropost->id) }}" class = "inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-light btn-sm normal-case" 
                                        onclick="return confirm('Delete id = {{ $micropost->id }} ?')">🗑</button>
                                </form>
                                @if (!Auth::user()->is_favoriting($micropost->id))
                                {{-- お気に入り追加ボタンのフォーム --}}
                                <form id="favorite-form-{{ $micropost->id }}" method="POST" action="{{ route('favorites.favorite', $micropost->id) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="btn btn-light btn-sm normal-case">💓{{ $micropost->favorite_count }}</button>
                                </form>    
                                @else
                                 {{-- お気に入り追加ボタンのフォーム --}}
                                <form id="favorite-form-{{ $micropost->id }}" method="POST" action="{{ route('favorites.favorite', $micropost->id) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="btn btn-error btn-sm normal-case">💓{{ $micropost->favorite_count }}</button>
                                </form>
                                @endif
                            @else
                                @if (!Auth::user()->is_favoriting($micropost->id))
                                {{-- お気に入り追加ボタンのフォーム --}}
                                <form id="favorite-form-{{ $micropost->id }}" method="POST" action="{{ route('favorites.favorite', $micropost->id) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="btn btn-light btn-sm normal-case">💓{{ $micropost->favorite_count }}</button>
                                </form>    
                                @else
                                 {{-- お気に入り追加ボタンのフォーム --}}
                                <form id="favorite-form-{{ $micropost->id }}" method="POST" action="{{ route('favorites.favorite', $micropost->id) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="btn btn-error btn-sm normal-case">💓{{ $micropost->favorite_count }}</button>
                                </form>
                                @endif
                            @endif
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
        {{-- ページネーションのリンク --}}
        {{ $microposts->links() }}
    @endif
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    @foreach ($microposts as $micropost)
    $('#favorite-form-{{ $micropost->id }}').on('submit', function(event) {
        event.preventDefault();

        var url = $(this).attr('action');
        var data = $(this).serialize();
        var button = $(this).find('button');

        $.post(url, data).done(function(response) {
            // ボタンのテキストを更新
            button.text('💓' + response.favoriteCount);

            // ボタンのクラスを更新
            if (response.status == 'favorited') {
                button.removeClass('btn-light');
                button.addClass('btn-error');
            } else {
                button.removeClass('btn-error');
                button.addClass('btn-light');
            }
        }).fail(function(error) {
            console.log(error);
        });
    });
    @endforeach
});



$(document).ready(function() {
    $('input[name="keyword"]').on('input', function() {
        var keyword = $(this).val();
        console.log("Keyword changed:", keyword); // この行を追加

        $.ajax({
            url: '{{ route('microposts.search') }}', // URLが正しいか確認
            type: 'GET',
            data: {keyword: keyword},
            dataType: 'json',
            success: function(data) {
                console.log("Data received:", data); // 受け取ったデータをログに出力
                updateMicroposts(data.microposts);
            },
            error: function(xhr, status, error) {
                console.error("Error:", status, error); // エラー情報をログに出力
            }
        });
    });
});


function updateMicroposts(microposts) {
    var html = '';
    var baseUrl = "{{ asset('storage/') }}"; 
    microposts.forEach(function(micropost) {
        var avatarPath = micropost.user.avatar_path === 'default.png' ? 
                         Gravatar.get(micropost.user.email, {size: 500}) : 
                         baseUrl + '/' +micropost.user.avatar_path;
        console.log(avatarPath);
        html += `
            <li class="flex items-start gap-x-2 mb-4">
                <div class="avatar">
                    <div class="w-12 rounded">
                        <img src="${avatarPath}" alt="Avatar">
                    </div>
                </div>
                <div>
                    <div>
                        <a class="link link-hover text-info" href="/users/${micropost.user.id}">${micropost.user.name}</a>
                        <span class="text-muted text-gray-500">posted at ${micropost.created_at}</span>
                    </div>
                    <div>
                        <p id="content-${micropost.id}" class="mb-0">${micropost.content}</p>
                    </div>
                    <div>
                        <button onclick="toggleFavorite(${micropost.id})" class="btn ${micropost.is_favorited ? 'btn-error' : 'btn-light'} btn-sm normal-case">
                            💓${micropost.favorite_count}
                        </button>
                    </div>
                </div>
            </li>
        `;
    });
    $('.list-none').html(html); // 既存のリストを新しいHTMLで置き換え
}

function toggleFavorite(micropostId) {
    // お気に入りの追加/削除のためのAPIを呼び出す関数
    // この関数の具体的な実装はサーバーサイドのAPIと連携する必要があります。
}






</script>