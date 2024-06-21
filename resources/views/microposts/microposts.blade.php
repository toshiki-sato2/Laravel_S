
<div class="mb-4 mt-4  {{ Request::routeIs('dashboard') ? 'hidden' : '' }}">
    <input type="text" name="keyword" placeholder="投稿検索" class="" id="input_style">
</div>

<div class="mb-4 mt-4 {{ Request::routeIs('dashboard') ? '' : 'hidden' }}">
    <input type="text" name="keyword_search_for_dashboard" placeholder="投稿検索" class="" id="input_style">
</div>


<div class="mt-4">
    @if (!$microposts->isEmpty())
        <ul class="list-none">
            @foreach ($microposts as $micropost)
                <li class="flex items-start gap-x-2 mb-4">
                    {{-- 投稿の所有者のメールアドレスをもとにGravatarを取得して表示 --}}
                    <div class="avatar">
                        <div class="w-12 rounded">
                            @if ($micropost->user->avatar_path === "default.png")
                                {{-- <img src="{{ Gravatar::get($user->email, ['size' => 500]) }}" alt=""> --}}
                                <img src="{{ asset('avator/'.$micropost->user->avatar_path) }}" alt="Avatar Image">
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
                            {{-- <div id = "content-{{ $micropost->id }}" class="mb-0">{!! nl2br(e($micropost->content)) !!}</div> --}}
                            <div id = "content-{{ $micropost->id }}" class="mb-0 markdown-content">{!! nl2br(e( $micropost->content )) !!}</div>
                        </div>
                        <div>
                            @if (Auth::id() == $micropost->user_id)
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
        @else
            <h2>Oppps! there is no posts yet.</h2>
        {{-- ページネーションのリンク --}}
        {{ $microposts->links() }}
    @endif
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/marked@3.0.7/marked.min.js"></script>

<script>
    var favoritesBaseUrl = "{{ route('favorites.favorite', ['id' => ':id']) }}";
</script>

<script>
// marked.jsのオプションを設定
marked.setOptions({
    renderer: new marked.Renderer(),
    gfm: true,
    tables: true,
    breaks: true,
    pedantic: false,
    sanitize: true, // サニタイズを有効にする
    smartLists: true,
    smartypants: false,
    xhtml: false
});

    $(document).ready(function() {
        // marked.jsが正しく読み込まれているか確認するためのテスト
        try {
            var testMarkdown = "# Test Heading\n\nThis is a test.";
            var testHtml = marked(testMarkdown);
            console.log("marked.js is working:", testHtml);
        } catch (error) {
            console.error("marked.js is not working:", error);
        }

        // 各投稿内容をMarkdownからHTMLに変換
        @foreach ($microposts as $micropost)
            var contentElement = document.getElementById("content-{{ $micropost->id }}");
            contentElement.innerHTML = marked.parse(contentElement.textContent);
        @endforeach
    });
    
    function convertMarkdownToHtml(elementId) {
        var contentElement = document.getElementById(elementId);
        if (contentElement) {
            var rawHtml = marked.parse(contentElement.innerHTML);
            contentElement.innerHTML = rawHtml;
        }
    }
    
</script>


<script>

$(document).off('submit', 'form[id^="favorite-form-"]').on('submit', 'form[id^="favorite-form-"]', function(event) {
    event.preventDefault(); // デフォルトの送信を防止
    var url = $(this).attr('action'); // フォームのアクションURLを取得
    var data = $(this).serialize(); // フォームのデータをシリアライズ
    var button = $(this).find('button'); // フォーム内のボタンを取得

    $.post(url, data).done(function(response) {
        // レスポンスに基づいてボタンのテキストとクラスを更新
        console.log(response);
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
                // 新しい投稿内容をMarkdownからHTMLに変換
                data.microposts.forEach(function(micropost) {
                convertMarkdownToHtml("content-" + micropost.id);
        });
            },
            error: function(xhr, status, error) {
                console.error("Error:", status, error); // エラー情報をログに出力
            }
        });
    });
});

$(document).ready(function() {
    $('input[name="keyword_search_for_dashboard"]').on('input', function() {
        var keyword = $(this).val();
        console.log("Keyword changed:", keyword); // この行を追加

        $.ajax({
            url: '{{ route('microposts.searchfordash', ['id' => $user->id]) }}', // URLが正しいか確認
            type: 'GET',
            data: {keyword: keyword},
            dataType: 'json',
            success: function(data) {
                console.log("Data received:", data); // 受け取ったデータをログに出力
                updateMicroposts(data.microposts);
                // 新しい投稿内容をMarkdownからHTMLに変換
                data.microposts.forEach(function(micropost) {
                convertMarkdownToHtml("content-" + micropost.id);
        });
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
    var unbaseUrl = "{{ asset('avator/') }}";
    if(microposts.length === 0){
        $('.list-none').html('<p>Not Found Posts.</p>');
        return;
    }
    microposts.forEach(function(micropost) {
        var avatarPath = micropost.user.avatar_path === 'default.png' ?
                         unbaseUrl + '/' +  micropost.user.avatar_path:
                         baseUrl + '/' + micropost.user.avatar_path;
        var favoriteButtonClass = micropost.is_favoriting ? 'btn-error' : 'btn-light';
        var deleteUrl = `/microposts/${micropost.id}`;
        var favoriteUrl = favoritesBaseUrl.replace(':id', micropost.id);
        var formattedDate = moment(micropost.created_at).format('YYYY-MM-DD HH:mm:ss');
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
                        <span class="text-muted text-gray-500">posted at ${formattedDate}</span>
                    </div>
                    <div>
                        <div id="content-${micropost.id}" class="mb-0 markdown-content">${micropost.content}</div>
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
    // Set text content safely to avoid XSS
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