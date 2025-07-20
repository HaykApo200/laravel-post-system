<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\PostRequest;
use Illuminate\Http\JsonResponse;
use App\Helpers\FieldChecker;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function uploadMedia(PostRequest $request): JsonResponse
    {
        $post = $request->validated();

        if (FieldChecker::allFieldsAreEmpty($post)) {
            return response()->json([
                'message' => 'All fields are empty.'
            ],
                400
            );
        }

        $post = FieldChecker::removeNulls($post);
//        if ($post->type === 'image') {
//            $post['url'] = $request->file('file')->store('public/images');
//        } else if ($post->type === 'video') {
//            $post['url'] = $request->file('file')->store('public/videos');
//        } else {}

        Auth::user()->posts()->create($post);

        return response()->json([
            'message' => 'Media uploaded successfully.'
        ]);
    }
}
