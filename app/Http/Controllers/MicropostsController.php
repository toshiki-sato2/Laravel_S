<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Micropost;
use App\Models\User;


class MicropostsController extends Controller
{

    //ダッシュボードが開かれたらまずこいつらが読み込まれるぞ
    public function index(Request $request)
    {
        $data = [];
        if (\Auth::check()) { // 認証済みの場合
            // 認証済みユーザーを取得
            $user = \Auth::user();
            // ユーザーとフォロー中ユーザーの投稿の一覧を作成日時の降順で取得
            $microposts = $user->feed_microposts()->orderBy('created_at', 'desc');
            
            $keywords = $request->input("keyword");
        
            if(!empty($keywords)){
                $microposts->where("content", "like", "%{$keywords}%");
            }
        
            $microposts = $microposts->paginate(10);
            
            
            foreach($microposts as $micropost){
                $micropost->favorite_count = $micropost->favoriteCounts();
            }

            $data = [
                'user' => $user,
                'microposts' => $microposts,
            ];
        }
        
        // dashboardビューでそれらを表示
        return view('dashboard', $data);
    }
    
    public function keyword_search(Request $request, $id){
        $user = User::findOrFail($id);
    
        if(\Auth::user()->id !== intval($id)){
            $query = $user->microposts()->with("user")->orderBy('created_at', 'desc');}
        else{
            #自分がログインしていると自分のマイプロフィールに他の投稿も表示されてしまう
            $query = $user->feed_microposts()->with("user")->orderBy('created_at', 'desc');
        }
        //$query = $user->microposts->orderBy('created_at', 'desc');

        $keywords = $request->input("keyword");
        if (!empty($keywords)) {
            $query->where("content", "like", "%{$keywords}%");
        }

        $microposts = $query->paginate(10);

        foreach ($microposts as $micropost) {
            $micropost->favorite_count = $micropost->favoriteCounts();
            $micropost->is_favoriting = \Auth::user()->is_favoriting($micropost->id);
        }

        if ($microposts->isEmpty()) {
            return response()->json(['status' => 'failed', 'message' => 'No posts found', 'microposts' => $microposts->items()]);
        }

        return response()->json([
            'status' => 'success',
            'microposts' => $microposts->items(),
        ]);
    }
        
        #ダッシュボード用のサーチ
        public function keyword_search_for_dashboard(Request $request, $id){
        
        $user = User::findOrFail($id);
    
        $query = $user->feed_microposts()->with("user")->orderBy('created_at', 'desc');


        $keywords = $request->input("keyword");
        if (!empty($keywords)) {
            $query->where("content", "like", "%{$keywords}%");
        }

        $microposts = $query->paginate(10);

        foreach ($microposts as $micropost) {
            $micropost->favorite_count = $micropost->favoriteCounts();
            $micropost->is_favoriting = \Auth::user()->is_favoriting($micropost->id);
        }

        if ($microposts->isEmpty()) {
            return response()->json(['status' => 'failed', 'message' => 'No posts found', 'microposts' => $microposts->items()]);
        }

        return response()->json([
            'status' => 'success',
            'microposts' => $microposts->items(),
        ]);
    }


    public function store(Request $request)
    {
        // バリデーション
        $request->validate([
            'content' => 'required|max:255',
        ]);
        
        // 認証済みユーザー（閲覧者）の投稿として作成（リクエストされた値をもとに作成）
        $request->user()->microposts()->create([
            'content' => $request->content,
        ]);
        
        // 前のURLへリダイレクトさせる
        return back();
    }

    public function destroy(string $id)
    {
        $micropost = Micropost::findOrFail($id);
    
        if (\Auth::id() === $micropost->user_id) {
            $micropost->delete();
            return response()->json(['status' => 'success']);
        }
    
        return back()->with('error', 'Delete Failed');
    }
    
    

    public function show($id)
    {
        // idの値でユーザーを検索して取得
        $user = User::findOrFail($id);
        
        // 関係するモデルの件数をロード
        $user->loadRelationshipCounts();
        
        // ユーザーの投稿一覧を作成日時の降順で取得
        $microposts = $user->microposts()->orderBy('created_at', 'desc')->paginate(10);
        
        foreach($microposts as $micropost){
            $micropost->favorite_count = $micropost->favoriteCounts();
        }


        // ユーザー詳細ビューでそれを表示
        return view('users.show', [
            'user' => $user,
            'microposts' => $microposts,
        ]);
    }


    

}



/*
    //ダッシュボードが開かれたらまずこいつらが読み込まれるぞ
    public function index()
    {
        $data = [];
        if (\Auth::check()) { // 認証済みの場合
            // 認証済みユーザーを取得
            $user = \Auth::user();
            // ユーザーとフォロー中ユーザーの投稿の一覧を作成日時の降順で取得
            $microposts = $user->feed_microposts()->orderBy('created_at', 'desc')->paginate(10);
            
            
            
            foreach($microposts as $micropost){
                $micropost->favorite_count = $micropost->favoriteCounts();
            }

            $data = [
                'user' => $user,
                'microposts' => $microposts,
            ];
        }
        
        // dashboardビューでそれらを表示
        return view('dashboard', $data);
    }



*/