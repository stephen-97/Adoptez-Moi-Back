<?php

namespace App\Service;

interface FileUploaderServiceInterface
{
    /**
     * @param string $data
     * @return string
     * @throws \Exception
     */
    public function upload(string $data): string;
}