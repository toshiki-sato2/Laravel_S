@if (Auth::id() == $user->id)

    <div class = "mb-4">
        <form action="{{ route('microposts.index') }}" method="GET">
            @csrf
            <input type="text" name="keyword" placeholder = "投稿を検索" class = "" id = "input_style">
            <input type="submit" value="検索" class = "bg-gradient-to-b from-blue-300 to-blue-800 hover:bg-gradient-to-l text-white rounded px-4 py-2">
        </form>
    </div>
    
    <div class="mt-4">
        <form method="POST" action="{{ route('microposts.store') }}">
            @csrf
        
            <div class="form-control mt-4">
                <textarea rows="2" name="content" class="input input-bordered w-full"></textarea>
            </div>
        
            <button type="submit" class="btn btn-primary btn-block normal-case">Post</button>
        </form>
    </div>
@endif