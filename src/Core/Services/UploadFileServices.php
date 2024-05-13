<?php

namespace App\Core\Services;

use App\Core\Utils\DateUtils;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\KernelInterface;
use function PHPUnit\Framework\throwException;

class UploadFileServices
{
    const GENERATE_RANDOM_LENGTH = 10;
    public function __construct(
        private readonly string          $targetDirectory,
        private readonly Filesystem      $filesystem,
        private readonly KernelInterface $kernel,
    )
    {
    }

    public function getProjectDir(): string
    {
        return $this->kernel->getProjectDir() . '/public';
    }

    /**
     * Upload file with object class UploadedFile
     * @param UploadedFile $file
     * @return string
     */
    public function uploadFile(UploadedFile $file): string
    {
        $uploadDir = $this->targetDirectory.'/'.DateUtils::getYear().'/'.DateUtils::getMonth();

        $folder =  $this->getProjectDir() . $uploadDir;

        if(!$this->filesystem->exists($folder))
            $this->filesystem->mkdir($folder);
        $originalFileIcon = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

        $newFileIcon = $originalFileIcon .'-'.uniqid().'.'.$file->guessExtension();
        try{
            $file->move(
                $folder,
                $newFileIcon
            );
        }catch (FileException $fileException){
            throw new $fileException;
        }
        return $uploadDir.'/'.$newFileIcon;
    }

    /**
     * Upload base64 new
     * @param $base64
     * @return string|null
     */
    public function uploadBase64($base64): string | null
    {
        $exploreBase64 = $this->getRawBase64($base64);
        //get mineType || false
        $mineType = $this->detectMimeType($exploreBase64);

        //check mineType allowed
        if(!$mineType || !$this->checkAllowedUploadedFile($mineType)){
            return null;
        }
        $rndFile = $this->getRndFileName($mineType);

        $image = (base64_decode(trim($exploreBase64)));
        $this->filesystem->dumpFile($this->targetDirectory .$rndFile['file'], $image);

        return $rndFile['path'];
    }

    private function getRawBase64($base64_string): string
    {
        $data = explode(',', $base64_string);
        $length = count($data);
        if($length > 0){
            return $data[1];
        }
        return '';
    }

    private function detectMimeType(string $base64): bool|string
    {
        $signaturesForBase64 = [
            'JVBERi0'     => "application/pdf",
            'R0lGODdh'    => "image/gif",
            'R0lGODlh'    => "image/gif",
            'iVBORw0KGgo' => "image/png",
            '/9j/'        => "image/jpeg",
        ];
        foreach($signaturesForBase64 as $sign => $mimeType)
        {
            if(str_starts_with($base64, $sign)) {
                return $mimeType;
            }
        }
        return false;
    }

    private function checkAllowedUploadedFile($mineType): bool
    {
        $allowedType = ['image/gif','image/png','image/jpeg','image/jpg','image/svg+xml'];
        if(!$mineType)
            return false;
        if(in_array($mineType, $allowedType)){
            return true;
        }
        return false;
    }

    private function getRndFileName($mineType): array
    {
        $fileName = $this->generateRandomString() . $this->getFileExtension($mineType);
        $uploadDir = $this->targetDirectory . '/' . DateUtils::getYear() . '/' . DateUtils::getMonth();
        $fullDir = $this->getProjectDir() . $uploadDir;
        return [
            'path' =>  $fullDir.'/'.$fileName,
            'file' =>  $uploadDir . '/'. $fileName,
            'file_name' => $fileName
        ];
    }

    private function getFileExtension($mineType): false | string
    {
        $listExtension = [
            "application/pdf" => '.pdf',
            'image/gif'    => ".gif",
            'image/png' => ".png",
            'image/jpeg'        => ".jpeg",
            'image/svg+xml' => '.svg'
        ];
        if(!array_key_exists($mineType, $listExtension)){
            return false;
        }

        return $listExtension[$mineType];
    }

    private function generateRandomString(): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < self::GENERATE_RANDOM_LENGTH; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}