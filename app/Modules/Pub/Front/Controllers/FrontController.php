<?php

namespace App\Modules\Pub\Front\Controllers;

use App\Category;
use App\Comment;
use App\Game;
use App\Parsing;
use App\Site;
use App\TwitchVideo;
use App\User;
use App\VkPost;
use App\Modules\Admin\Back\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class FrontController extends Controller
{
    public function main()
    {
        $vkPosts = VkPost::getApi();
        $twitchVideo = TwitchVideo::getApi();
        return view('main', ['posts' => $vkPosts, 'video' => $twitchVideo]);
    }

    public function home()
    {
        $user = User::query()->where('id', '=', Auth::user()->id)->get();
        return view('home', ['userInf' => $user]);
    }

    public function search()
    {
        $categories = Category::all();
        $games = Game::query()->where('name', 'LIKE', 'remas')->paginate(3);
        return view('games.list', ['games' => $games, 'categories' => $categories]);
    }

    public function gamesList()
    {
        $categories = Category::all();
        $games = Game::paginate(3);
        return view('games.list', ['games' => $games, 'categories' => $categories]);
    }

    public function categoryList($id)
    {
        $categories = Category::all();
        $category = Category::query()->find($id);
        $games = Game::query()
            ->where('category', '=', $category->name)
            ->paginate(3);
        return view('games.list', ['games' => $games, 'categories' => $categories]);
    }

    public function single($game)
    {
        $categories = Category::all();
        $comments = Comment::all()->where('game_id', '=', $game);
        $game = Game::query()->find($game);
        $prices = Site::query()
            ->where('game_name', '=', $game->name)
            ->orderBy('created_at')
            ->limit(count(config('site.sites')))
            ->get();
        $table = Site::query()
            ->where('game_name', '=', $game->name)
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();
        $nameForLink = getUrlName($game->name);
        return view('games.single', ['categories' => $categories, 'game' => $game, 'prices' => $prices, 'nameForLink' => $nameForLink, 'comments' => $comments, 'table' => $table]);
    }

    public function link($siteName, $gameName, $gameUrl)
    {
        $sitesList = config('site.sites');
        $siteAttr = $sitesList[$siteName];
        return redirect(getFullAddress($siteName . $siteAttr['last_domen'] . $siteAttr['path'], $gameUrl));
    }

}
?>

                                                                 
