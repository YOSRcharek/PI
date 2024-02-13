<?php
// src/Twig/Base64EncodeExtension.php
namespace App\Twig;

use App\Service\FileReadService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class Base64EncodeExtension extends AbstractExtension
{
    private $fileReadService;

    public function __construct(FileReadService $fileReadService)
    {
        $this->fileReadService = $fileReadService;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('readfile', [$this, 'readfile']),
        ];
    }

    public function readfile($filePath)
    {
        return $this->fileReadService->readfile($filePath);
    }
}
