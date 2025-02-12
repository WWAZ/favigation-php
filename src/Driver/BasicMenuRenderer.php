<?php
namespace wwaz\Favigation\Driver;

use wwaz\Favigation\Interface\RendererInterface;
use wwaz\Favigation\Markup\MarkupCreator;

class BasicMenuRenderer implements RendererInterface
{
    public function build(MarkupCreator $nav, array | null $selected): MarkupCreator
    {
        $nav->setContent(function ($item) {
            if (count($item->getChildren()) == 0 && $item->getUrl()) {
                $target = '';
                if ($item->getTarget()) {
                    $target = ' target="' . $item->getTarget() . '"';
                }
                return '<a' . $target . ' href="' . $item->getUrl() . '">' . $item->getTitle() . '</a>';
            }
            return '<span>' . $item->getTitle() . '</span>';
        });

        return $nav;
    }
}
