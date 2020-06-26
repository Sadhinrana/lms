<?php

use Illuminate\Support\Facades\{Storage};

/**
 * Upload file to AWS.
 *
 * @param  string $file
 * @param  string $directory
 * @param  boolean $isPrivate
 * @return \Illuminate\Http\Response
 */
if (! function_exists('fileUpload')) {
    function fileUpload( $file, $directory = null, $isPrivate = false ) {
        try {
            $base64_image = $file;
            $ext = explode(';base64',$base64_image);
            $ext = explode('/',$ext[0]);
            $ext = $ext[1];

            if (preg_match('/^data:image\/(\w+);base64,/', $base64_image)) {
                $data = substr($base64_image, strpos($base64_image, ',') + 1);

                $data = base64_decode($data);

                // Handle File Upload
                if ($base64_image) {
                    $filePath = $directory ? $directory . "/" . time() . str_random(10) . '.' . $ext : time() . str_random(10) . '.' . $ext;
                    if ($isPrivate) {
                        Storage::disk('s3')->put($filePath, $data);
                    } else {
                        Storage::disk('s3')->put($filePath, $data, 'public');
                    }

                    return fileUrl($filePath);
                } else {
                    return 'File doesn\'t exist';
                }
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}


/**
 * Get file URL from AWS.
 *
 * @param  string $path
 * @param  boolean $isPrivate
 * @return \Illuminate\Http\Response
 */
if (! function_exists('fileUrl')) {
    function fileUrl($path, $isPrivate = false)
    {
        $disk = Storage::disk('s3');

        if (!$disk->exists($path)) {
            return 'File doesn\'t exist!';
        }

        // This code for generate new signed url of your file
        return $isPrivate ? $disk->temporaryUrl($path, now()->addHour()) : $disk->url($path);
    }
}
