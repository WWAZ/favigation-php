<?php
namespace wwaz\Favigation\Interface;

use wwaz\Favigation\Markup\MarkupCreator;

interface RendererInterface
{
    public function build(MarkupCreator $nav, array | null $selected): MarkupCreator;
}
