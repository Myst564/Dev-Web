<?php

namespace App\Service;

use App\Entity\Intervention;
use App\Helper\Randomizer;
use App\Service\ImagineOptimizer\ImageOptimizer;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

readonly class Uploader
{
    public function __construct(
        private ParameterBagInterface $parameterBag,
        private ImageOptimizer        $imageOptimizer,
    )
    {
    }

    final public function uploadInterventionPhoto(
        UploadedFile    $uploadedFile,
        Intervention     $intervention,
    ): ?string
    {
        $photoSlug = Randomizer::generateSlug();
        $planSlug = $intervention->getElement()->getLayer()->getPlan()->getSlug();
        $interventionSlug = $intervention->getSlug();

        if (file_exists($uploadedFile)) {
            $ext = $uploadedFile->getClientOriginalExtension();
            $newFilename = "{$photoSlug}.{$ext}";

            $file = new File($uploadedFile, true);
            $dir = $this->parameterBag->get('app.path.intervention_photo');

            $dir = str_replace("__plan_slug__", $planSlug, $dir);
            $dir = str_replace("__intervention_slug__", $interventionSlug, $dir);

            $file->move($dir, $newFilename);
            $pathForImageOptimizer = $dir . $newFilename;
            $this->imageOptimizer->optimizeImage($pathForImageOptimizer, false);
        }

        return $newFilename ?? null;
    }
}