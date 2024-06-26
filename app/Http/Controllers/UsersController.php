<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UsersController extends Controller
{
    public function destroy($id)
    {
        $user = Auth::user();

        if ($user->id == $id) {
            $user->delete(); // ユーザーを論理削除
            Auth::logout(); // ユーザーをログアウトさせる

            return redirect('/')->with('status', 'アカウントが削除されました。');
        } else {
            return redirect()->back()->with('error', '不正な操作です。');
        }
}
    
    public function index(request $request)
    {
        //$users = User::orderBy("id", "desc")->paginate(10);
        $users = User::orderBy("id", "desc");
        
        $keywords = $request->input("keyword");
        
        if(!empty($keywords)){
            $users->where("name", "like", "%{$keywords}%");
        }
        
        $users = $users->paginate(100);
        
        return view("users.index",[
            "users" => $users]);
    }
    
    public function users_search(Request $request)
    {
        Log::info('users_search method called');
        Log::info('Request data: ', $request->all());

        $users = User::orderBy("id", "desc");

        $keywords = $request->input("keyword");

        if (!empty($keywords)) {
            $users->where("name", "like", "%{$keywords}%");
        }

        $users = $users->paginate(100);

        Log::info('Users found: ', $users->toArray());

        return response()->json(["users" => $users]);
    }
    
    
    
    public function show(string $id)
    {
        $user = User::findOrFail($id);
        \Log::info('users_search method called');
        // 関係するモデルの件数をロード
        $user->loadRelationshipCounts();
        
        // ユーザーの投稿一覧を作成日時の降順で取得
        $microposts = $user->microposts()->orderBy('created_at', 'desc')->paginate(100);
        
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
        
        $microposts= $user->favorites()->paginate(100);

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
        $followings = $user->followings()->paginate(100);

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
        $followers = $user->followers()->paginate(100);

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
            'name' => ['required', 'string', 'max:255','regex:/^[^<>]+$/'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $request->user()->id],
        ]);
        
        
        
        //パスワード以外の設定変更
        $user = $request->user();
        $user->name = $request->name;
        $user->email = $request->email;
        
        $user->save();
        
        return back()->with("status", "Profile Updated!");
    }

    
    public function edit_profile(request $request){
        $request->validate([
            'new_password' => ['confirmed','max:128','min:8'],
        ]);
        
        if (!Hash::check($request->current_password, $request->user()->password)) {
            // 現在のパスワードが一致しない場合の処理
            return back()->withErrors(['current_password' => '現在のパスワードが間違っています。']);
        }
        $user = $request->user();
        // 新しいパスワードが入力されている場合だけパスワードを更新
        if ($request->new_password) {
            $user->password = Hash::make($request->new_password);
        }
        
        $user->save();
        
        return back()->with("status", "Profile Updated!");
        
    }
    
    
    public function update_image(request $request){
        //validate処理を何か考える
    $request->validate([
        'avator_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
    ]);
    if ($request->hasFile('avator_image')) {
        $image_path = $request->file("avator_image")->store("public/avator");
        $user = $request->user();
        //$user->avatar_path = $image_path;
        $user->avatar_path = str_replace('public/', '', $image_path);
        $user->save();
        return back()->with("status", "profile image updated");
    } else {
        return back()->with("status", "profile image failed");
    }
        
    }

    
}



/*
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
*/    