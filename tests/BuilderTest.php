<?php
namespace wwaz\Favigation\Tests;

use PHPUnit\Framework\TestCase;
use wwaz\Favigation\Builder;
use wwaz\Favigation\Collection;
use wwaz\Favigation\Driver\Backend\BackendArrayAdapter;
use wwaz\Favigation\Driver\Bootstrap\BootstrapMenuRenderer;
use wwaz\Favigation\Driver\Wordpress\WordpressBuilder;

class BuilderTest extends TestCase
{
    public function testBasicBuildOrdersAndRendersNestedMarkup(): void
    {
        $items = [
            new BackendArrayAdapter([
                'id' => 1,
                'parentId' => 0,
                'order' => 2,
                'name' => 'About',
                'slug' => '/about',
                'icon' => null,
            ]),
            new BackendArrayAdapter([
                'id' => 2,
                'parentId' => 1,
                'order' => 3,
                'name' => 'Team',
                'slug' => '/team',
                'icon' => null,
            ]),
            new BackendArrayAdapter([
                'id' => 3,
                'parentId' => 0,
                'order' => 1,
                'name' => 'Home',
                'slug' => '/',
                'icon' => null,
            ]),
        ];

        $html = (new Builder(new Collection($items), \wwaz\Favigation\Driver\BasicMenuRenderer::class))
            ->getBuild()
            ->toHtml();

        $this->assertStringContainsString('<a href="/">Home</a>', $html);
        $this->assertStringContainsString('<span>About</span>', $html);
        $this->assertStringContainsString('<a href="/team">Team</a>', $html);
        $this->assertTrue(strpos($html, 'Home') < strpos($html, 'About'));
    }

    public function testBootstrapRendererMarksSelectedElementAsActive(): void
    {
        $items = [
            new BackendArrayAdapter([
                'id' => 'root',
                'parentId' => 0,
                'order' => 1,
                'name' => 'Root',
                'slug' => '/root',
                'icon' => null,
            ]),
            new BackendArrayAdapter([
                'id' => 'reports',
                'parentId' => 'root',
                'order' => 2,
                'name' => 'Reports',
                'slug' => '/reports',
                'icon' => null,
            ]),
        ];

        $html = (new Builder(new Collection($items), BootstrapMenuRenderer::class))
            ->selected('getId', 'reports')
            ->getBuild()
            ->toHtml();

        $this->assertStringContainsString('class="active"', $html);
        $this->assertStringContainsString('data-id="reports"', $html);
    }

    public function testWordpressBuilderAliasClassIsBackwardCompatible(): void
    {
        $data = [
            [
                'ID' => 1,
                'menu_item_parent' => 0,
                'menu_order' => 1,
                'title' => 'Home',
                'url' => '/',
                'target' => null,
            ],
        ];

        $legacy = new \wwaz\Favigation\Driver\Wordpress\Builder(
            $data,
            \wwaz\Favigation\Driver\BasicMenuRenderer::class
        );

        $current = new WordpressBuilder(
            $data,
            \wwaz\Favigation\Driver\BasicMenuRenderer::class
        );

        $this->assertSame($current->toHtml(), $legacy->toHtml());
    }
}
