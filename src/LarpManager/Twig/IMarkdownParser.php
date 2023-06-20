<?php

namespace LarpManager\Twig;

interface IMarkdownParser
{
    public function transform(string $text): string;
}