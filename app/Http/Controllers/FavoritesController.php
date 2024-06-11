<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Micropost; 

class FavoritesController extends Controller
{
    
    public function store(string $id){
        $user = \Auth::user();
        $micropost = Micropost::find($id);
        // お気に入り数を取得
        
        if ($user->is_favoriting($id)) {
            $user->unfavorite(intval($id));
            $status = 'unfavorited';
        } else {
            $user->favorite(intval($id));
            $status = 'favorited';
        }
        
        // お気に入り数を取得
        $favoriteCount = $micropost->favoriteCounts();

        return response()->json(['status' => $status, 'favoriteCount' => $favoriteCount]);
    }
    
    /*
    public function destroy(string $id){
        
        \Auth::user()->unfavorite(intval($id));
        return back();
    }*/
}
