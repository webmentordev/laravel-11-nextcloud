<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/upload', function (Request $request) {
    $request->validate([
        'image' => 'required|image|max:2048',
    ]);
    $uploadedFile = $request->file('image');
    $fileContents = file_get_contents($uploadedFile->getRealPath());
    $folderName = "images";
    $fileName = $folderName . '/' . uniqid() . '-' . str_replace(' ', '-', strtolower($uploadedFile->getClientOriginalName()));
    Storage::disk('nextcloud')->put($fileName, $fileContents);
    return response()->json([
        'message' => 'Image uploaded successfully!',
        'file_name' => config('app.next_cloud_file_url') . $fileName,
    ]);
})->name('upload');
