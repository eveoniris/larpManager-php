<?php

namespace LarpManager\Twig;

use Twig\TwigFilter;

class MarkdownExtension extends \Twig_Extension
{
    protected $parser;

    public function __construct(IMarkdownParser $parser)
    {
        $this->parser = $parser;
    }

    public function getFilters(): array
    {
        return [
            $this->getName() => new TwigFilter(
                $this->getName(),
                [$this, 'markdown'],
                ['is_safe' => ['html']]
            ),
        ];
    }

    public function markdown(?string $txt): string
    {
        if (null === $txt) {
            return '';
        }

        return $this->parser->transform($txt);
    }

    public function getName(): string
    {
        return 'markdown';
    }
}