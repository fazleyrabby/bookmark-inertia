<?php

namespace App\Http\Controllers;


use Inertia\Inertia;
use App\Models\Bookmark;
use Illuminate\Http\Request;
use OpenGraph;
use Illuminate\Support\Facades\Auth;

class BookmarkController extends Controller
{
    public function index(){
        $bookmarks = Bookmark::query()
        ->where('user_id', Auth::id())
        ->where('is_active', 1)
        ->orderByDesc('updated_at')
        ->get();

        return Inertia::render('Bookmark/List/index', [
            'bookmarks' => $bookmarks,
        ]); 
    }

    public function add(){
        return Inertia::render('Bookmark/Add/index');
    }

    public function getPreviewData(Request $request){
        $postData = $this->validate($request, [
            'link' => ['required'],
        ]);

        $data = OpenGraph::fetch($postData['link'], true);
        
        // return Inertia::render('Bookmark/Add/index',[
        //     'data' => $data,
        //     'link' => $postData['link'],
        // ]);

        $bookmark = Bookmark::create([
            'title' => $data['title'] ?? $data['description'] ?? 'Dummy Title',
            'description' => $data['description'] ?? '',
            'type' => $data['type'] ?? '',
            'url' => $postData['link'],
            'img_url' => $data['image'] ?? '',
            'user_id' => $request->user()->id,
        ]);

        return redirect()->route('bookmark.view', ['bookmark' => $bookmark]);
    }

    public function view(Bookmark $bookmark){
        abort_if(Auth::id() !== $bookmark->user_id,401,'You are not allowed to view this bookmark!');

        return Inertia::render('Bookmark/View/index', [
            'bookmark' => $bookmark
        ]);
    }

    public function makeActive(Request $request){
        $postData = $this->validate($request,[
            'id' => ['required', 'exists:bookmarks,id'],
        ]);

        $bookmark = Bookmark::findOrFail($postData['id']);

        abort_if(Auth::id() !== $bookmark->user_id,401,'You are not allowed to view this bookmark!');

        $bookmark->is_active = !$bookmark->is_active;
        $bookmark->save();

        return redirect()->route('bookmark.index');
    }
}