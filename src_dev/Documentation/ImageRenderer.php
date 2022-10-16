<?php

namespace Graphviz\Dev\Documentation;

use Graphviz\AbstractGraph;
use Symfony\Component\Process\Process;

class ImageRenderer
{
    private string $documentationDirectory;
    private string $gifDirectoryName;

    public function __construct(string $documentationDirectory, string $relativeGifDirectory)
    {
        $this->documentationDirectory = rtrim($documentationDirectory, '/');
        $this->gifDirectoryName = trim($relativeGifDirectory, '/');
    }

    public function renderImages(string $file): void
    {
        if (!str_starts_with($file, $this->documentationDirectory . '/')) {
            throw new \InvalidArgumentException(sprintf(
                'The file "%s" is not in directory "%s".',
                $file,
                $this->documentationDirectory
            ));
        }

        // File path, relative to the documentation directory
        $relative = trim(substr($file, strlen($this->documentationDirectory) + 1), '/');
        // Filename, without the ".md" extension
        $name = substr(basename($relative), 0, -3);
        // Directory, relative to the documentation directory
        $dirname = dirname($relative) === '.' ? '' : dirname($relative).'/';
        // Relative path of the images prefix, without the extension
        $relativePath = $this->gifDirectoryName.'/'.$name;
        // Absolute path of the images prefix, without the extension
        $absolutePath = $this->documentationDirectory.'/'.$dirname.$relativePath;
        // File content
        $content = file_get_contents($file);
        $lines = explode("\n", $content);

        echo "- $relative\n";

        // Render and insert the images in the markdown
        $lines = $this->insertInMarkdown($lines, $absolutePath, $relativePath);
        $outcome = implode("\n", $lines);
        if ($content !== $outcome) {
            file_put_contents($file, $outcome);
        }
    }

    /**
     * @param string[] $lines
     * @return string[]
     */
    private function insertInMarkdown(array $lines, string $absolutePath, string $relativePath): array
    {
        $counter = 0;
        $isGraph = false;
        $isCode = false;
        $pending = [];
        $image = null;
        $output = [];
        foreach ($lines as $line) {
            if ($image !== null) {
                $output[] = $image;
                $image = null;

                if (str_starts_with($line, '![')) {
                    continue;
                }
            }

            $output[] = $line;

            if ($line === '<!-- Graph -->') {
                $isGraph = true;
            }

            if (!$isGraph) {
                continue;
            }


            if ($line === '```php') {
                $isCode = true;

                continue;
            }

            if ($isCode && $line === '```') {
                $image = $this->renderGraphCode($pending, $absolutePath.'.'.$counter, $relativePath.'.'.$counter);
                $counter++;
                $isCode = $isGraph = false;
                $pending = [];

                continue;
            }

            if ($isCode) {
                $pending[] = $line;
            }
        }

        return $output;
    }

    /**
     * @param string[] $lines
     * @param string $absolutePath
     * @param string $relativePath
     * @return string
     */
    private function renderGraphCode(array $lines, string $absolutePath, string $relativePath): string
    {
        $relativePngFile = $relativePath.'.png';
        $absolutePngPath = $absolutePath.'.png';
        $sumFile = $absolutePath.'.sum';

        $title = substr($absolutePngPath, strlen($this->documentationDirectory) + 1);
        $dir = dirname($absolutePngPath);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $sum = file_exists($sumFile) ? file_get_contents($sumFile) : '';
        $actualSum = sha1(json_encode($lines, JSON_THROW_ON_ERROR));
        $returnValue = '![Rendering of the code above]('.$relativePngFile.')';

        if ($actualSum === $sum && file_exists($absolutePngPath)) {
            echo "  - SKIP - $title\n";

            return $returnValue;
        }

        echo "  - RENDER - $title\n";

        // Render
        $this->renderWithGraphviz($lines, $absolutePngPath);

        file_put_contents($sumFile, $actualSum);

        return $returnValue;
    }

    /**
     * @param string[] $lines
     * @param string $absolutePngPath
     */
    private function renderWithGraphviz(array $lines, string $absolutePngPath): void
    {
        $fileBase = tempnam(sys_get_temp_dir(), 'graphviz_');
        unlink($fileBase);
        $phpFile = $fileBase.'.php';

        file_put_contents($phpFile, "<?php \n".implode("\n", $lines));
        $graph = null;
        /** @var AbstractGraph $graph */
        require_once $phpFile;

        try {
            $process = new Process(['dot', '-Tpng']);
            $process->setInput($graph->render());
            $process->mustRun();
        } catch (\Throwable $e) {
            throw new \RuntimeException(sprintf("%s\n\nGraph data==========\n%s", $e->getMessage(), $graph->render()));
        } finally {
            unlink($phpFile);
        }

        file_put_contents($absolutePngPath, $process->getOutput());
    }
}
