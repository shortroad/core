<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\Api\V1\CreateUrlPath;
use App\Helpers\Api\V1\HeaderData;
use App\Helpers\Api\V1\JsonResponse;
use App\Helpers\JwtToken;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Url\CreateShortUrlRequest;
use App\Models\Url;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UrlController extends Controller
{
    public int $urlPerPage = 50;

    public function create(CreateShortUrlRequest $request)
    {
        $path = CreateUrlPath::getPath();
        $data = $this->getUrlCreateData($request, $path);

        do {
            try {
                Url::create($data);
                break;
            } catch (\Exception $e) {
                // TODO if just unique exceptions add extra number otherwise show error to user and log it
                $data['path'] = CreateUrlPath::getPath(rand(0, 9));
            }
        } while (true);

        return JsonResponse::successResponse(
            [
                'target' => $data['target'],
                'path' => \url($data['path'])
            ],
            'Short url is ready.',
            201);
    }

    protected function getUrlCreateData($request, string $path): array
    {
        return [
            'user_id' => HeaderData::getAuthTokenDecodedData($request, 'user_id'),
            'path' => $path,
            'target' => $request->input('target'),
        ];
    }

    public function getAll(Request $request)
    {
        $urls = Url::select(['path', 'target'])
            ->where(['user_id' => HeaderData::getAuthTokenDecodedData($request, 'user_id')])
            ->paginate($this->urlPerPage);
        return response()->json($urls, Response::HTTP_OK);
    }

    public function getSingle(string $path)
    {
        $requested_user_id = HeaderData::getAuthTokenDecodedData(\request(), 'user_id');

        $url = Url::where(['path' => $path])->first();

        if (is_null($url))
            return JsonResponse::failedResponse(
                [
                    'path' => 'This path not found'
                ],
                'This path not found',
                Response::HTTP_NOT_FOUND
            );

        return $url->user_id == $requested_user_id ?
            JsonResponse::successResponse(
                [
                    'target' => $url->target,
                    'path' => $url->path
                ],
                'Your data is ready!',
                Response::HTTP_OK
            ) :
            JsonResponse::failedResponse(
                [
                    'path' => 'This path not accessible for you'
                ],
                'This path not accessible for you',
                Response::HTTP_UNAUTHORIZED
            );
    }

    public function deleteSingle(string $path)
    {
        $requested_user_id = HeaderData::getAuthTokenDecodedData(\request(), 'user_id');

        $url = Url::where(['path' => $path])->first();

        if (is_null($url))
            return JsonResponse::failedResponse(
                [
                    'path' => 'This path not found'
                ],
                'This path not found',
                Response::HTTP_NOT_FOUND
            );

        $url->delete();

        return $url->user_id == $requested_user_id ?
            JsonResponse::successResponse(
                [
                    'target' => $url->target,
                    'path' => $url->path
                ],
                'Requested Url has been deleted!',
                Response::HTTP_OK
            ) :
            JsonResponse::failedResponse(
                [
                    'path' => 'This path not accessible for you'
                ],
                'This path not accessible for you',
                Response::HTTP_UNAUTHORIZED
            );
    }
}
