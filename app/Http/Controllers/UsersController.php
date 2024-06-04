<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\User;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UsersController extends Controller
{
    public function index(request $request)
    {
        //$users = User::orderBy("id", "desc")->paginate(10);
        $users = User::orderBy("id", "desc");
        
        $keywords = $request->input("keyword");
        
        if(!empty($keywords)){
            $users->where("name", "like", "%{$keywords}%");
        }
        
        $users = $users->paginate(10);
        
        return view("users.index",[
            "users" => $users]);
    }
    
    
    
    public function show(string $id)
    {
        $user = User::findOrFail($id);
        
        // 関係するモデルの件数をロード
        $user->loadRelationshipCounts();
        
        // ユーザーの投稿一覧を作成日時の降順で取得
        $microposts = $user->microposts()->orderBy('created_at', 'desc')->paginate(10);
        
        //ここで代入がうまくいっている意味がわからないのかもしれない
        //そりゃそう、foreach使ったらそれぞれが表示されるに決まってるやんバカ！
        
        foreach($microposts as $micropost){
            $micropost->favorite_count = $micropost->favoriteCounts();
        }    

        
        // ユーザー詳細ビューでそれを表示
        return view('users.show', [
            'user' => $user,
            'microposts' => $microposts,
        ]);
    }
    
    public function favorites($id){
        $user = User::findOrFail($id);
        
        $user->loadRelationshipCounts();
        
        $microposts= $user->favorites()->paginate(10);

        foreach($microposts as $micropost){
            $micropost->favorite_count = $micropost->favoriteCounts();
        }  
        
        
        return view("users.favorites", [
            "user" => $user,
            "microposts" => $microposts]);
    }

    /**
     * ユーザーのフォロー一覧ページを表示するアクション。
     *
     * @param  $id  ユーザーのid
     * @return \Illuminate\Http\Response
     */
    public function followings($id)
    {
        // idの値でユーザーを検索して取得
        $user = User::findOrFail($id);

        // 関係するモデルの件数をロード
        $user->loadRelationshipCounts();

        // ユーザーのフォロー一覧を取得
        $followings = $user->followings()->paginate(10);

        // フォロー一覧ビューでそれらを表示
        return view('users.followings', [
            'user' => $user,
            'users' => $followings,
        ]);
    }

    /**
     * ユーザーのフォロワー一覧ページを表示するアクション。
     *
     * @param  $id  ユーザーのid
     * @return \Illuminate\Http\Response
     */
    public function followers($id)
    {
        // idの値でユーザーを検索して取得
        $user = User::findOrFail($id);

        // 関係するモデルの件数をロード
        $user->loadRelationshipCounts();

        // ユーザーのフォロワー一覧を取得
        $followers = $user->followers()->paginate(10);

        // フォロワー一覧ビューでそれらを表示
        return view('users.followers', [
            'user' => $user,
            'users' => $followers,
        ]);
    }
    
    
    public function create(){
        
        return view("users.create");
    }
    
    public function update(request $request){
        
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $request->user()->id],
            'new_password' => ['nullable', 'confirmed','max:128','min:8'],
        ]);
        
        

        if (!Hash::check($request->current_password, $request->user()->password)) {
            // 現在のパスワードが一致しない場合の処理
            return back()->withErrors(['current_password' => '現在のパスワードが間違っています。']);
        }
        
        //パスワード以外の設定変更
        $user = $request->user();
        $user->name = $request->name;
        $user->email = $request->email;

        // 新しいパスワードが入力されている場合だけパスワードを更新
        if ($request->new_password) {
            $user->password = Hash::make($request->new_password);
        }
        
        $user->save();
        
        return back()->with("status", "Profile Updated!");
    }

    
}


