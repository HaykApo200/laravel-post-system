<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\PostRequest;
use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use App\Helpers\FieldChecker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

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

        $imagePaths = [];
        $videoPaths = [];

        // Handle images upload
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('images', 'public');
                $imagePaths[] = $path;
            }
        }

        // Handle videos upload
        if ($request->hasFile('videos')) {
            foreach ($request->file('videos') as $video) {
                $path = $video->store('videos', 'public');
                $videoPaths[] = $path;
            }
        }

        if (!empty($imagePaths)) {
            $post['images'] = $imagePaths;
        }

        if (!empty($videoPaths)) {
            $post['videos'] = $videoPaths;
        }

        Auth::user()->posts()->create($post);

        return response()->json([
            'message' => 'Media uploaded successfully.'
        ]);
    }

    public function commentOnPost(Request $request,int $postId): JsonResponse
    {
          $comment = $request->validate([
              'comment' => 'required'
          ]);

          $comment['user_id'] = Auth::id();
          $comment['post_id'] = $postId;

          Comment::create($comment);
          return response()->json([
              'message' => 'Comment posted successfully.'
          ],
              201
          );
    }
}
