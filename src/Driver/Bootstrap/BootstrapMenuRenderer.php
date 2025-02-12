<?php
namespace wwaz\Favigation\Driver\Bootstrap;

use wwaz\Favigation\Interface\RendererInterface;
use wwaz\Favigation\Markup\MarkupCreator;

class BootstrapMenuRenderer implements RendererInterface
{
    public function build(MarkupCreator $nav, array | null $selected): MarkupCreator
    {
        $nav->setContent(function ($item, $info) use ($selected) {

            $toggleTarget = '';
            if (count($item->getChildren()) > 0) {
                $toggleTarget = '#tg-';
                $toggleTarget .= $item->getId() ? $item->getId() : '';
            }

            $active = '';
            if (
                $item->getParent()
                && $selected
                && in_array($selected['value'], $item->getParent()->getRecursive($selected['method']))
            ) {
                $active = ' class="active"';
            }

            $icon = false;

            if ($item->getIcon()) {
                if (substr($item->getIcon(), 0, 3) === '&#x') {
                    $icon = '<div class="icon"><i class="fa">' . $item->getIcon() . '</i></div>';
                } else {
                    // Glyph or html
                    $icon = '<div class="icon">' . $item->getIcon() . '</div>';
                }
            }

            $target = '';
            if (count($item->getChildren()) === 0) {
                $target = '';
                if ($item->getTarget()) {
                    $target = ' target="' . $item->getTarget() . '"';
                }
                return $icon . '<a' . $target . ' href="' . $item->getUrl() . '">' . $item->getTitle() . '</a>';
            }

            $open = false;
            if ($selected && in_array($selected['value'], $item->getRecursive($selected['method']))) {
                $open = true;
            }

            if ($open === true) {
                return $icon . '<button class="btn d-inline-flex align-items-center rounded" data-bs-toggle="collapse" data-bs-target="' . $toggleTarget . '" aria-expanded="true" aria-current="true">' . $item->getTitle() . '</button>';
            }

            return $icon . '<button class="btn d-inline-flex align-items-center rounded collapsed" data-bs-toggle="collapse" data-bs-target="' . $toggleTarget . '" aria-expanded="false">' . $item->getTitle() . '</button>';
        })

            ->setUlAttribute('class', function ($item, $info) use ($selected) {

                $open = false;
                if ($item->getParent() && $selected && in_array($selected['value'], $item->getParent()->getRecursive($selected['method']))) {
                    $open = true;
                }

                if ($open == true) {
                    return 'collapse show';
                }

                return 'collapse';
            })

            ->setUlAttribute('id', function ($item, $info) {
                // Toggle id
                return 'tg-' . $item->getParentId();
            })

            ->setLiAttribute('class', function ($item, $info) use ($selected) {
                // Active li item
                if ($selected) {
                    $method = $selected['method'];
                    if ($item->$method() == $selected['value']) {
                        return 'active';
                    }
                }
            })

            ->setLiAttribute('data-type', function ($item, $info) {
                return $item->isNode() ? 'node' : null;
            })

            ->setLiAttribute('data-id', function ($item, $info) {
                return $item->getId();
            })

            ->setLiAttribute('data-path', function ($item, $info) {
                if ($item->isNode()) {
                    return $item->getPath();
                }
            })

            ->setLiAttribute('data-level', function ($item, $info) {
                return $item->getLevel();
            });

        return $nav;
    }
}
