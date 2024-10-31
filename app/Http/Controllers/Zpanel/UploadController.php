<?php

namespace Shopbox\Http\Controllers\Zpanel;

use Illuminate\Http\Request;
use Shopbox\Http\Requests\Upload\UploadRequest;
use Shopbox\Http\Controllers\Controller;
use Intervention\Image\ImageManager;

class UploadController extends Controller
{
    protected $imageManager;
    protected $defaultUploadOptions = ['allowedMimeTypes' => ['image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png', 'image/png', 'image/svg+xml']];

    public function __construct(ImageManager $imageManager)
    {
    	$this->imageManager = $imageManager;
    }

    protected function getList($folderPath, $thumbPath = null) {

      if (empty($thumbPath)) {
        $thumbPath = $folderPath;
      }

      // Array of image objects to return.
      $response = array();

      $absoluteFolderPath = $_SERVER['DOCUMENT_ROOT'] . $folderPath;

      // Image types.
      $image_types = $this->defaultUploadOptions['allowedMimeTypes'];

      // Filenames in the uploads folder.
      $fnames = scandir($absoluteFolderPath);

      // Check if folder exists.
      if ($fnames) {
        // Go through all the filenames in the folder.
        foreach ($fnames as $name) {
          // Filename must not be a folder.
          if (!is_dir($name)) {
            // Check if file is an image.

            if (in_array(mime_content_type($absoluteFolderPath . $name), $image_types)) {
              // Build the image.
              $img = new \StdClass;
              $img->url = $folderPath . $name;
              $img->thumb = $thumbPath . $name;
              $img->title = $name;

              // Add to the array of image.
              array_push($response, $img);
            }
          }
        }
      }

      // Folder does not exist, respond with a JSON to throw error.
      else {
        throw new Exception('Images folder does not exist!');
      }

        return $response;
    }

    public function store(UploadRequest $request)
    {
      $path = public_path('stores').'/'.$request->tenant()->domain.'/img/';
      $files = [];

      foreach ($request->file as $key => $uploadedFile) {
        $name = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $uploadedFile->getClientOriginalExtension();

        $this->imageManager->make($uploadedFile->getPathName())
                                ->save($path.'/'.$name.'.'.$extension);

        $files['file-'.$key] = array(
            'url' => asset('stores').'/'.$request->tenant()->domain.'/img/'.$name.'.'.$extension,
        );

      }

      echo stripslashes(json_encode($files));

    }

    public function show(Request $request)
    {
      $path = '/stores/'.$request->tenant()->domain.'/img/';

      try {
        $response = $this->getList($path);
        return stripslashes(json_encode($response));
      }
      catch (Exception $e) {
        http_response_code(404);
      }
    }

    public function destroy(Request $request)
    {
        try {
          $response = Image::delete($request->src);
          echo stripslashes(json_encode('Success'));
        }
        catch (Exception $e) {
          http_response_code(404);
        }
    }

}
