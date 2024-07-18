<?php

namespace App\Http\Controllers;

use App\Core\UseCases\File\StoreFileUseCase;
use App\Http\Requests\File\StoreFileRequest;
use App\Http\Responses\ApiErrorResponse;
use App\Http\Responses\ApiSuccessResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFileRequest $request, StoreFileUseCase $usecase)
    {
        $user = Auth::user();

        try {
            $file = $usecase->execute(
                file: $request->file('image'),
                user: $user
            );

            return new ApiSuccessResponse(
                data: $file
            );
        } catch (Exception $e) {
            return new ApiErrorResponse(
                message: "Something went wrong",
                exception: $e,
            );
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
