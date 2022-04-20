<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\Api\V1\CreateUrlPath;
use App\Helpers\Api\V1\JsonResponse;
use App\Helpers\JwtToken;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Url\CreateShortUrlRequest;
use App\Models\Url;
use Illuminate\Http\Request;

class UrlController extends Controller
{

    public function create(CreateShortUrlRequest $request)
    {
        $path = CreateUrlPath::getPath();
        $data = $this->getUrlCreateData($request, $path);

        do {
            try {
                $created_url = Url::create($data);
                break;
            } catch (\Exception $e) {
                // TODO if just unique exceptions add extra number otherwise show error to user and log it
                $data['path'] = CreateUrlPath::getPath(rand(0, 9));
            }
        } while (true);

        return JsonResponse::successResponse(
            [
                'target' => $data['target'],
                'url' => $created_url->shortUrl
            ],
            'Short url is ready.',
            201);
    }

    protected function getUrlCreateData($request, string $path): array
    {
        return [
            'user_id' => $this->getUserId($request),
            'path' => $path,
            'target' => $request->input('target'),
        ];
    }

    protected function getUserId(Request $request)
    {
        $token = $request->header('Authorization');
        $jwtTokenData = JwtToken::decode($token);
        return $jwtTokenData->user_id;
    }
}
