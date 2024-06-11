
<div class="mb-4 mt-4">
    <input type="text" name="keyword" placeholder="投稿検索" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" id="input_style">
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
                                {{-- 投稿削除ボタンのフォーム --
                                
                                <form class="delete-form inline" data-id="{{ $micropost->id }}" data-url="{{ route('microposts.destroy', $micropost->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-light btn-sm normal-case delete-btn">🗑</button>
                                </form>--}}
                                
                                <button type="button" class="btn btn-light btn-sm normal-case delete-btn" data-id="{{ $micropost->id }}" data-url="{{ route('microposts.destroy', $micropost->id) }}">🗑</button>
                                
                                
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
    var favoritesBaseUrl = "{{ route('favorites.favorite', ['id' => ':id']) }}";
</script>
<script>


$(document).off('submit', 'form[id^="favorite-form-"]').on('submit', 'form[id^="favorite-form-"]', function(event) {
    event.preventDefault(); // デフォルトの送信を防止
    var url = $(this).attr('action'); // フォームのアクションURLを取得
    var data = $(this).serialize(); // フォームのデータをシリアライズ
    var button = $(this).find('button'); // フォーム内のボタンを取得

    $.post(url, data).done(function(response) {
        // レスポンスに基づいてボタンのテキストとクラスを更新
        button.text('💓' + response.favoriteCount);
        if (response.status === 'favorited') {
            button.removeClass('btn-light').addClass('btn-error');
        } else {
            button.removeClass('btn-error').addClass('btn-light');
        }
    }).fail(function(error) {
        console.error('Error:', error); // エラーが発生した場合、コンソールにエラーを出力
    });
});



$(document).ready(function() {
    $('input[name="keyword"]').on('input', function() {
        var keyword = $(this).val();
        console.log("Keyword changed:", keyword); // この行を追加

        $.ajax({
            url: '{{ route('microposts.search', ['id' => $user->id]) }}', // URLが正しいか確認
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
    var csrfToken = '{{ csrf_token() }}';
    var loggedInUserId = @json(auth()->id());
    var baseUrl = "{{ asset('storage/') }}";
    microposts.forEach(function(micropost) {
        var avatarPath = micropost.user.avatar_path === 'default.png' ?
                         Gravatar.get(micropost.user.email, {size: 500}) :
                         baseUrl + '/' + micropost.user.avatar_path;
        var favoriteButtonClass = micropost.is_favoriting ? 'btn-error' : 'btn-light';
        var deleteUrl = `/microposts/${micropost.id}`;
        var favoriteUrl = favoritesBaseUrl.replace(':id', micropost.id);
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
                        ${micropost.user.id === loggedInUserId ? `<button data-id="${micropost.id}" data-url="${deleteUrl}" class="delete-btn btn btn-light btn-sm normal-case">🗑</button>` : ''}
                        <form id="favorite-form-${micropost.id}" method="POST" action="${favoriteUrl}" class="inline">
                            <input type="hidden" name="_token" value="${csrfToken}">
                            <button type="submit" class="btn ${favoriteButtonClass} btn-sm normal-case">
                                💓${micropost.favorite_count}
                            </button>
                        </form>
                    </div>
                </div>
            </li>
        `;
    });
    $('.list-none').html(html);
}

$(document).on('click', '.delete-btn', function() {
    var button = $(this);
    var micropostId = button.data('id');
    var url = button.data('url');
    var token = $('meta[name="csrf-token"]').attr('content');

    if (confirm('Delete id = ' + micropostId + ' ?')) {
        $.ajax({
            url: url,
            type: 'POST',  // LaravelでDELETEメソッドを模倣
            data: {
                _method: 'DELETE',
                _token: token
            },
            success: function(response) {
                if (response.status === 'success') {
                    button.closest('li').remove();
                    alert('Delete Successful');
                } else {
                    alert('Delete Failed');
                }
            },
            error: function(xhr) {
                alert('Delete Failed');
                console.error('Error:', xhr.responseText);
            }
        });
    }
});




</script>