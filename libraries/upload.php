<?php

namespace Over_Code\Libraries;

use Over_Code\Models\ArticlesModel;

/**
 * Trait containg methods to upload files
 */
trait Upload
{
    use \Over_Code\Libraries\Helpers;

    /**
     * Upload a new article image and return an array with message and the
     * inetger part of img name.
     * img rerturn name example: **0015** (part of article-0015.jpg)
     * 
     * @param int $name inetger part of img name.
     *
     * @return array with two keys.
     *
     * 'message': can have following values :
     * - 0: upload success
     * - 1: upload failed
     * - 2: file too big
     * - 3: no file exist or upload failed
     *
     * 'img_name': **null** if unseccessed upload, or not defined.
     */
    public function uploadArticleImg(string $img_name = null): array
    {
        $message = 3; // no file exist or upload failed

        if ((null !== $this->getFILES('image')) && ($this->getFILES('image')['error'] === 0)) {
            $message = 'format de fichier incorrect';

            $allowed = [
                'jpg'  => 'image/jpeg',
                'jpeg' => 'image/jpeg'
            ];

            $fileName = $this->getFILES('image')['name'];
            $fileType = $this->getFILES('image')['type'];
            $fileSize = $this->getFILES('image')['size'];

            $extension = strtolower((pathinfo($fileName, PATHINFO_EXTENSION)));

            if (array_key_exists($extension, $allowed) || !in_array($fileType, $allowed)) {
                $message = 2; // file too big

                if ($fileSize < 500000) { // 500 Ko
                    $message = 1; // upload failed

                    $article = new ArticlesModel();

                    if ($img_name === null) { // if no $img_name specified, case of new image
                        $img_name = intval($article->biggestImg()) + 1;
                        $img_name = substr_replace('0000', $img_name, -strlen($img_name));
                    }

                    $newName = 'article-' . $img_name;

                    $newFileName = UPLOADS_PATH . $newName . '.' . $extension;

                    $move = move_uploaded_file($this->getFILES('image')['tmp_name'], $newFileName);

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
