<?php

namespace App\Modules\Admin\Back\Controllers;

use App\Category;
use App\Game;
use App\Http\Requests\GameRequest;
use App\Mail\FreshPrice;
use App\Parsing;
use App\Site;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GameController extends Controller
{
    function create()
    {
        return view('admin.games.create');
    }

    function edit($game)
    {
        return view('admin.games.edit', ['game' => $game]);
    }

    function add(GameRequest $request)
    {
        $usersEmail = User::all();
        $sites = new SiteController();
        $hasCategory = Category::query()
            ->select('name')
            ->where('name', '=', $request->category)
            ->get()
            ->toArray();
        $sites->add($request);
        $game = new Game();
        $game->name = $request->name;
        $game->description = $request->description;
        if (empty($hasCategory)) {
            return 'нет такой категории';
        }
        $game->category = $request->category;
        $game->image = $request->file('image')->store('uploads', 'public');
        $game->save();
        $prices = Site::query()
            ->select()
            ->orderByDesc('created_at')
            ->limit(count(config('site.sites')))
            ->get();
//        foreach ($usersEmail as $userEmail) {
//            \Mail::to($userEmail->email)->send(new FreshPrice(['game'=>$request->name, 'sites'=>$prices]));
//        }
        return redirect()->route('admin.index');
    }

    function save(Request $request)
    {
        $game = Game::query()->find($request->id);
        $game->name = $request->name;
        $game->category = $request->category;
        $game->save();
        return redirect()->route('admin.index');
    }

    function delete(Request $request)
    {
        Game::destroy($request->id);
        return redirect()->route('admin.index');
    }
}
