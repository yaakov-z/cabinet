<?php

namespace Cabinet;

use Cabinet\Services\Actions;
use Cabinet\Services\Directories;
use Cabinet\Services\Files;
use Cabinet\Services\References;
use Cabinet\Sources\SpatieMediaSource;
use Cabinet\Types\Document;
use Cabinet\Types\Image;
use Cabinet\Types\Other;
use Cabinet\Types\PDF;
use Cabinet\Types\Video;
use Illuminate\Support\Collection;

class Cabinet
{
    use Actions, Directories, Files, References;

    protected $sources = [
        SpatieMediaSource::TYPE => SpatieMediaSource::class,
    ];

    protected $fileTypes = [
        Image::class,
        Video::class,
        Document::class,
        PDF::class,
        Other::class
    ];

    public function registerSource(string $name, string $className): self
    {
        // Check if class implements Source interface
        if (!in_array(Source::class, class_implements($className))) {
            throw new \Exception("{$className} must implement " . Source::class);
        }

        $this->sources[$name] = $className;

        app()->singleton($className);

        return $this;
    }

    public function getSource(string $source): Source
    {
        if (!isset($this->sources[$source])) {
            throw new \Exception("Source {$source} is not registered");
        }

        return app($this->sources[$source]);
    }

    public function validSources(): Collection
    {
        return collect($this->sources)->keys();
    }

    protected function mapSources(?array $sourceNames = null): Collection
    {
        $sourceNames = $sourceNames ?? array_keys($this->sources);

        return collect($sourceNames)
            ->map(fn (string $source) => $this->getSource($source));
    }



    /**
     * @return Collection<FileType>
     */
    public function validFileTypes(): Collection
    {
        return collect($this->fileTypes)
            ->map(fn (string $classPath) => app($classPath));
    }

    public function registerFileType(string $classPath): self
    {
        if (!in_array(FileType::class, class_implements($classPath))) {
            throw new \Exception("{$classPath} must implement " . FileType::class);
        }

        $this->fileTypes[] = $classPath;

        return $this;
    }

    public function determineFileTypeFromMime(string $mime): FileType
    {
        foreach ($this->fileTypes as $fileType) {
            if (array_search($mime, $fileType::supportedMimeTypes()) !== false) {
                return app($fileType);
            }
        }

        return app(Other::class);
    }
}
