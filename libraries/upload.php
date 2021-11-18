<?php

namespace Over_Code\Libraries;

use Over_Code\Models\ArticlesModel;

/**
 * Trait containg methods to upload files
 */
trait Upload
{
    /**
     * Upload a new article image and return an array with message and the
     * inetger part of img name.
     * img rerturn name example: **0015** (part of article-0015.jpg)
     *
     * @return array with two keys.
     * 
     * 'message': can have following values :
     * - 0: upload success
     * - 1: upload failed
     * - 2: file too big
     * - 3: no file exist or upload failed
     * 
     * 'img_name': **null** if unseccessed upload.
     */
    public function uploadArticleImg(): array
    {
        $message = 3; // no file exist or upload failed
        $img_name = null;

        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $message = 'format de fichier incorrect';

            $allowed = [
                'jpg'  => 'image/jpeg',
                'jpeg' => 'image/jpeg'
            ];

            $fileName = $_FILES['image']['name'];
            $fileType = $_FILES['image']['type'];
            $fileSize = $_FILES['image']['size'];

            $extension = strtolower((pathinfo($fileName, PATHINFO_EXTENSION)));

            if(array_key_exists($extension, $allowed) || !in_array($fileType, $allowed)) {
                $message = 2; // file too big

                if($fileSize < 60000) {
                    $message = 1; // upload failed

                    $article = new ArticlesModel();

                    $img_name = intval($article->biggestImg()) + 1;
                    $img_name = substr_replace('0000', $img_name, -strlen($img_name));
                    $newName = 'article-' . $img_name;

                    $newFileName = UPLOADS_PATH . $newName . $extension;

                    $move = move_uploaded_file($_FILES['image']['tmp_name'], $newFileName);

                    $message = (!$move) ? $message : 0;

                    chmod($newFileName, 0444); // file couldn't be executed
                }
            }
        }

        return array(
            'message'  => $message,
            'img_name' => $img_name
        );
    }
}