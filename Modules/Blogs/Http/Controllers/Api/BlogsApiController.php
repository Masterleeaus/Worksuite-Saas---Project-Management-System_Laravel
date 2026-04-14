<?php

namespace Modules\Blogs\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Blogs\Http\Controllers\BlogsController;

class BlogsApiController extends BlogsController
{
    public function listBlogs(Request $request): JsonResponse
    {
        $request->merge(['is_mobile' => 'yes']);

        return $this->blogList($request);
    }

    public function showBlog(Request $request, string $slug): JsonResponse
    {
        $request->merge(['slug' => $slug, 'is_mobile' => 'yes']);

        return $this->blogDetails($request);
    }

    public function listBlogComments(Request $request, string $slug): JsonResponse
    {
        $request->merge(['slug' => $slug, 'is_mobile' => 'yes']);
        $blogDetail = $this->blogDetails($request)->getData();

        if (empty($blogDetail->data->id)) {
            return response()->json([
                'code' => 404,
                'message' => __('Blog not found.'),
                'data' => [],
            ], 404);
        }

        $request->merge(['post_id' => $blogDetail->data->id]);

        return $this->listComments($request);
    }

    public function createComment(Request $request, string $slug): JsonResponse
    {
        $request->merge(['slug' => $slug, 'is_mobile' => 'yes']);
        $blogDetail = $this->blogDetails($request)->getData();

        if (empty($blogDetail->data->id)) {
            return response()->json([
                'code' => 404,
                'message' => __('Blog not found.'),
            ], 404);
        }

        $request->merge(['post_id' => $blogDetail->data->id]);

        return $this->addComment($request);
    }

    public function listCategories(Request $request): JsonResponse
    {
        return $this->getCategory($request);
    }

    public function createCategory(Request $request): JsonResponse
    {
        $request->merge([
            'method' => 'add',
            'slug' => $request->slug ?: str($request->category_name)->slug()->value(),
        ]);

        return $this->store($request);
    }

    public function createBlogPost(Request $request): JsonResponse
    {
        $request->merge([
            'method' => 'add',
            'slug' => $request->slug ?: str($request->title)->slug()->value(),
            'status' => $request->status ?? 0,
            'popular' => $request->popular ?? 0,
        ]);

        return $this->savePost($request);
    }

    public function publishPost(Request $request): JsonResponse
    {
        $request->merge([
            'status' => 1,
        ]);

        return $this->postStatusChange($request);
    }
}
