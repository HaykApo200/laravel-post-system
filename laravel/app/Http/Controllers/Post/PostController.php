<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\PostRequest;
use App\Models\Comment;
use App\Models\Like;
use Illuminate\Http\JsonResponse;
use App\Helpers\FieldChecker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    /**
    * Upload media files (images and videos) and create a post associated with the authenticated user.
    *
    * @param  PostRequest  $request  Validated request containing post data.
    * @return JsonResponse
    */
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

    /**
     * Add a comment to a specific post by the authenticated user.
     *
     * @param  Request  $request  Request object containing the comment data.
     * @param  int  $postId  ID of the post to comment on.
     * @return JsonResponse
     */
    public function commentOnPost(Request $request, int $postId): JsonResponse
    {
          $comment = $request->validate([
              'comment' => 'required'
          ]);

          $comment['user_id'] = Auth::id();
          $comment['post_id'] = $postId;

          if (!DB::table('posts')->where('id', $postId)->exists()) {
              return response()->json(
                  [
                    'message' => 'Post not found.'
                  ],
                  404
              );
          }

          if (!DB::table('posts')->select('comment_enabled')->where('id', $postId)) {
              return response()->json(
                  [
                    'message' => 'Comments are disabled for this post.'
                  ],
                  403
              );
          }

          Comment::create($comment);
          return response()->json([
              'message' => 'Comment posted successfully.'
          ],
              201
          );
    }

    /**
     * Delete post.
     *
     * @param  Request  $request  Request object.
     * @param  int  $postId  ID of the post to comment on.
     * @return JsonResponse
     */
    public function deletePost(Request $request, int $postId): JsonResponse
    {
        $post = Auth::user()->posts()->where('id', $postId)->first();

        if (! $post) {
            return response()->json([
                'message' => 'Post not found'
            ],
                404
            );
        }

        $post->delete();

        return response()->json([
            'message' => 'Post deleted successfully.'
        ],
             204
        );
    }

    /**
     * Like post.
     *
     * @param  Request  $request  Request object.
     * @param  int  $postId  ID of the post to like on.
     * @return JsonResponse
     */
    public function likeOnPost(Request $request, int $postId): JsonResponse
    {
        $like['user_id'] = Auth::id();
        $like['post_id'] = $postId;

        if (DB::table('likes')->where('user_id', $like['user_id'])->where('post_id', $postId)->exists()) {
            return response()->json(
                [
                    'message' => 'You already liked this post.'
                ]
            );
        }

        Like::create($like);
        return response()->json(
            [
                'message' => 'Like post added successfully.'
            ]
        );
    }
}
