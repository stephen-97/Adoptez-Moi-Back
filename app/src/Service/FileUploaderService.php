<?php
namespace App\Service;

use Doctrine\Common\Collections\Collection;
use Hshn\Base64EncodedFile\HttpFoundation\File\Base64EncodedFile;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploaderService
{
    public const FILE_EXTENSION_JPEG = 'jpeg';
    public const FILE_EXTENSION_JPG = 'jpg';
    public const FILE_EXTENSION_PNG = 'png';

    private string $targetDirectoryForImages;
    private string $targetDirectoryForAvatars;
    private SluggerInterface $slugger;

    public function __construct(string $targetDirectoryForImages,  string $targetDirectoryForAvatars, SluggerInterface $slugger)
    {
        $this->targetDirectoryForImages = $targetDirectoryForImages;
        $this->targetDirectoryForAvatars = $targetDirectoryForAvatars;
        $this->slugger = $slugger;
    }

    /**
     * @param string $data
     * @return string
     * @throws \Exception
     */
    public function upload(string $data): string
    {
        $file = new Base64EncodedFile($data);
        $uploadedFile = new UploadedFile($file->getPathname(), $this->getOriginalName($file->guessExtension()));

        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$uploadedFile->guessExtension();

        try {
            $file->move($this->targetDirectoryForImages, $fileName);
        } catch (FileException $e) {
            throw new FileException($e);
        }
        return $fileName;
    }

    /**
     * @param string $data
     * @return string
     * @throws \Exception
     */
    public function uploadAvatar(string $data): string
    {
        $file = new Base64EncodedFile($data);
        $uploadedFile = new UploadedFile($file->getPathname(), $this->getOriginalName($file->guessExtension()));

        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$uploadedFile->guessExtension();
        try {
            $file->move($this->targetDirectoryForAvatars, $fileName);
        } catch (FileException $e) {
            throw new FileException('problème upload');
        }
        return $fileName;
    }

    /**
     * @param string $fileName
     * @return string
     * @throws \Exception
     */
    public function deleteAvatar(string $fileName): string
    {
        $filesystem = new Filesystem();
        try {
            $filesystem->remove("avatar/" . $fileName);
        } catch (\Exception $e){
            dd($e);
        }
        return true;
    }

    /**
     * @param Collection $imagesArray
     * @return string
     * @throws \Exception
     */
    public function deleteImages(Collection $imagesArray): bool
    {
        $filesystem = new Filesystem();
        for($i=0; $i < count($imagesArray);$i++){
            try {
                $filesystem->remove("upload/" . $imagesArray[$i]->getName());
            } catch (\Exception $e){
                dd($e);
            }
        }
        return true;
    }

    /**
     * @return string
     */
    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }

    /**
     * @param string $extension
     * @return string
     * @throws \Exception
     */
    private function getOriginalName(string $extension): string
    {
        return match ($extension) {
            self::FILE_EXTENSION_JPEG => 'image.jpeg',
            self::FILE_EXTENSION_JPG => 'image.jpg',
            self::FILE_EXTENSION_PNG => 'image.png',
            default => throw new \Exception('Extension inconnue'),
        };
    }
}