<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\Post\CreatePostRequest;
use App\Http\Requests\Post\DetailsPostRequest;
use App\Http\Requests\Post\GetPostsRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Http\Requests\Post\DeletePostRequest;
use App\Http\Resources\PostResource;
use App\Helpers\CustomResponse;
use App\Http\Controllers\Controller;
use App\Services\PostServiceInterface;
use Illuminate\Http\JsonResponse;

/**
 * Class PostApiController
 * @package App\Http\Controllers\API
 */
class PostApiController extends Controller
{
    /**
     * @var PostServiceInterface
     */
    private PostServiceInterface $postService;

    public function __construct(PostServiceInterface $postService)
    {
        $this->postService = $postService;
    }

    /**
     * @param GetPostsRequest $request
     * @return JsonResponse
     */
    public function list(GetPostsRequest $request): JsonResponse
    {
        return CustomResponse::successResponse(__('success'), PostResource::collection($this->postService->list($request->validated())));
    }

    /**
     * @param CreatePostRequest $request
     * @return JsonResponse
     */
    public function store(CreatePostRequest $request): JsonResponse
    {
       return CustomResponse::successResponse(__('success'), new PostResource($this->postService->store($request->validated())));
    }

    /**
     * @param DetailsPostRequest $request
     * @return JsonResponse
     */
    public function details(DetailsPostRequest $request): JsonResponse
    {
        return CustomResponse::successResponse(__('success'), new PostResource($this->postService->getOneBy($request->validated()['id'])));
    }

    /**
     * @param UpdatePostRequest $request
     * @return JsonResponse
     */
    public function update(UpdatePostRequest $request): JsonResponse
    {
        return CustomResponse::successResponse(__('success'), new PostResource($this->postService->update($request->validated())));
    }

    /**
     * @param DeletePostRequest $request
     * @return JsonResponse
     */
    public function delete(DeletePostRequest $request): JsonResponse
    {
        $this->postService->delete($request->validated());
        return CustomResponse::successResponse(__('success'));
    }
}
