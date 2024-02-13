<?php
// src/Twig/Base64EncodeExtensionService.php
namespace App\Twig;

use App\Service\FileReadService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class Base64EncodeExtensionService extends AbstractExtension
{
    private $fileReadService;

    public function __construct(FileReadService $fileReadService)
    {
        $this->fileReadService = $fileReadService;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('base64_encode', [$this, 'base64Encode']),
        ];
    }

    public function base64Encode($input)
    {
        return base64_encode($input);
    }

    public function readfile($filePath)
    {
        return $this->fileReadService->readfile($filePath);
    }
}
