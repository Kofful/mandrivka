<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function add(Request $request) {
        $comment = Comment::create([
            'user_id' => Auth::user()->id,
            'hotel_id' => $request->hotel_id,
            'comm' => $request->comm,
            'grade' => $request->grade
        ]);
        return $comment;
    }
}
