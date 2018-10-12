<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\GenreResource;
use App\Models\Genre;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GenreController extends Controller
{

    /**
     * Получаем список существующих жанров
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $genres = Genre::orderBy('id');

        return GenreResource::collection($genres->get())
            ->additional([
                'total' => $genres->get()->count(),
            ])
        ;
    }
}
