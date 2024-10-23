<?php

namespace fastphp;

class UploadedFile
{
    private $name;
    private $mimeType;
    private $size;
    private $tmpName;
    private $error;

    public function __construct($name, $mimeType, $size, $tmpName, $error)
    {
        $this->name = $name;
        $this->mimeType = $mimeType;
        $this->size = $size;
        $this->tmpName = $tmpName;
        $this->error = $error;
    }

    public function getClientFilename()
    {
        return $this->name;
    }

    public function getClientMediaType()
    {
        return $this->mimeType;
    }

    public function getSize()
    {
        return $this->size;
    }

    public function getTempName()
    {
        return $this->tmpName;
    }

    public function getError()
    {
        return $this->error;
    }

    public function moveTo($targetPath)
    {
        if ($this->error !== UPLOAD_ERR_OK) {
            throw new \Exception($this->getUploadErrorMessage($this->error));
        }

        $isUploadedFile = is_uploaded_file($this->tmpName) === true;
        if (
            $isUploadedFile === true
            &&
            move_uploaded_file($this->tmpName, $targetPath) === false
        ) {
            throw new \Exception('Cannot move uploaded file'); // @codeCoverageIgnore
        } elseif ($isUploadedFile === false && getenv('PHPUNIT_TEST')) {
            rename($this->tmpName, $targetPath);
        }
    }

    protected function getUploadErrorMessage($error)
    {
        switch ($error) {
            case UPLOAD_ERR_INI_SIZE:
                return 'The uploaded file exceeds the upload_max_filesize directive in php.ini.';
            case UPLOAD_ERR_FORM_SIZE:
                return 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.';
            case UPLOAD_ERR_PARTIAL:
                return 'The uploaded file was only partially uploaded.';
            case UPLOAD_ERR_NO_FILE:
                return 'No file was uploaded.';
            case UPLOAD_ERR_NO_TMP_DIR:
                return 'Missing a temporary folder.';
            case UPLOAD_ERR_CANT_WRITE:
                return 'Failed to write file to disk.';
            case UPLOAD_ERR_EXTENSION:
                return 'A PHP extension stopped the file upload.';
            default:
                return 'An unknown error occurred. Error code: ' . $error;
        }
    }
}