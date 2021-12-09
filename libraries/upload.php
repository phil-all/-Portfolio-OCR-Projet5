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
     * Integer part of image filre name
     *
     * @var int $imgName
     */
    private $imgName;

    /**
     * Article model object
     *
     * @var object $article
     */
    private $article;

    /**
     * Allowed upload file MIME type
     *
     * @var array $allowed
     */
    private $allowed;

    /**
     * Uploaded message: can have following values :
     *                   - 0: upload success
     *                   - 1: upload failed
     *                   - 2: file too big
     *                   - 3: no file exist or upload failed
     *
     * @var int $message
     */
    private $message = 3;

    /**
     * Upload a new article image and return an array with message and the
     * inetger part of img name.
     * img rerturn name example: **0015** (part of article-0015.jpg)
     *
     * @param int $name inetger part of img name.
     *
     * @return array with two keys.
     */
    public function uploadArticleImg(string $imgName = null): array
    {
        if ((null !== $this->getFILES('image')) && ($this->getFILES('image')['error'] === 0)) {
            $message = 'format de fichier incorrect';

            if ($this->isMimeAllowed && $this->isExtensionAllowed) {
                $message = 2; // file too big

                $this->uploadProcess($imgName);
            }
        }

        return array(
            'message'  => $this->message,
            'img_name' => $this->imgName
        );
    }

    /**
     * Upload image process.
     *
     * @param string|null $imgName image to upload
     *
     * @return void
     */
    private function uploadProcess(?string $imgName): void
    {
        if ($this->getFILES('image')['size'] < 500000) { // 500 Ko
            $this->message = 1; // upload failed

            $this->article = new ArticlesModel();

            $this->setImgName($imgName);

            $newFileName = UPLOADS_PATH . 'article-' . $this->imgName . '.' . $this->getExtension();

            $move = move_uploaded_file($this->getFILES('image')['tmp_name'], $newFileName);

            $this->message = (!$move) ? $this->message : 0;

            $this->noExec($newFileName);
        }
    }

    /**
     * Prevent execution of a file by changing permissions
     *
     * @param string $file file to prevent execution
     *
     * @return void
     */
    private function noExec(string $file)
    {
        chmod($file, 0444);
    }

    /**
     * Sets allowed upload extensions and file MIME types.
     *
     * @return void
     */
    private function setAllowed(): void
    {
        $this->allowed = [
            'jpg'  => 'image/jpeg',
            'jpeg' => 'image/jpeg'
        ];
    }

    /**
     * Returns extension of uploaded file.
     *
     * @return string
     */
    private function getExtension(): string
    {
        return strtolower((pathinfo($this->getFILES('image')['name'], PATHINFO_EXTENSION)));
    }

    /**
     * Checks if uploaded file Mime type is allowed.
     *
     * @return boolean
     */
    private function isMimeAllowed(): bool
    {
        $fileType = $this->getFILES('image')['type'];

        return in_array($fileType, $this->allowed);
    }

    /**
     * Checks if uploaded file extension is allowed.
     *
     * @return boolean
     */
    private function isExtensionAllowed(): bool
    {
        return array_key_exists($this->getExtension(), $this->allowed);
    }

    /**
     * Sets interger part of image file name.
     *
     * @param string|null $imgName
     *
     * @return void
     */
    private function setImgName(?string $imgName): void
    {
        $this->imgName = ($imgName === null) ? $this->setDefaulName() : $imgName;
    }

    /**
     * Returns interger part of image file name.
     *
     * Used by default in article creation (then image name not specified).
     *
     * @return string
     */
    private function setDefaulName(): string
    {
        $imgName = (int)$this->article->biggestImg() + 1;

        return substr_replace('0000', $imgName, -strlen($imgName));
    }
}
