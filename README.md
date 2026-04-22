# Favigation PHP

**Favigation** turns any navigation data into clean HTML markup — without wrestling with recursion, nested loops, or edge cases.

```bash
composer require wwaz/favigation-php
```

---

## What it does

You give Favigation a flat list of menu items. It automatically builds a correctly nested `<ul>` structure — including sorting, active state, and fully customizable HTML output.

**Drivers** add out-of-the-box support for different data sources (e.g. WordPress) and UI frameworks (e.g. Bootstrap).

---

## Examples

### 1. Render a WordPress menu in 3 lines

Got raw WordPress menu data? Just pass it in:

```php
$menudata = [
    ['ID' => 1, 'post_parent' => 0, 'menu_order' => 2, 'url' => '/about', 'title' => 'About us'],
    ['ID' => 2, 'post_parent' => 1, 'menu_order' => 3, 'url' => '/team',  'title' => 'Team'],
    ['ID' => 3, 'post_parent' => 0, 'menu_order' => 1, 'url' => '/',      'title' => 'Home'],
];

$favigation = new wwaz\Favigation\Driver\Wordpress\Builder($menudata);
echo $favigation->toHtml();
```

**Output:**
```html
<ul>
    <li><a href="/">Home</a></li>
    <li><a href="/about">About us</a>
        <ul>
            <li><a href="/team">Team</a></li>
        </ul>
    </li>
</ul>
```

Sorting by `menu_order`, nesting via `post_parent` — all handled automatically.

---

### 2. Bootstrap navigation with active state

Want Bootstrap-compatible markup with the current page highlighted?

```php
$favigation = (new wwaz\Favigation\Builder(
    new wwaz\Favigation\Collection($data),
    wwaz\Favigation\Driver\Bootstrap\BootstrapMenuRenderer::class
))
    ->tag('ul')
    ->id('main-nav')
    ->selected('getId', 3)  // Marks the item with ID 3 as active
    ->getBuild()
    ->toHtml();
```

The builder handles setting `active` classes — no need to traverse the tree yourself.

---

### 3. Fully custom HTML — icons, attributes, your own logic

Need total control over the markup?

```php
$favigation
    ->setContent(function($item) {
        $icon = $item->getIcon()
            ? '<img class="icon" src="' . $item->getIcon() . '">'
            : '';

        return $item->getUrl()
            ? $icon . '<a href="' . $item->getUrl() . '">' . $item->getTitle() . '</a>'
            : $icon . '<span>' . $item->getTitle() . '</span>';
    })
    ->setLiAttribute('data-id', function($item) {
        return $item->getId();
    })
    ->toHtml();
```

**Output:**
```html
<ul>
    <li data-id="3"><a href="/">Home</a></li>
    <li data-id="1">
        <img class="icon" src="about.svg"><a href="/about">About us</a>
        <ul>
            <li data-id="2"><a href="/team">Team</a></li>
        </ul>
    </li>
</ul>
```

Every element, every attribute — fully under your control, without managing the tree yourself.

---

## Why Favigation?

| Without Favigation | With Favigation |
|---|---|
| Write recursive functions yourself | `toHtml()` |
| Implement sorting manually | Automatic via `menu_order` |
| Set active classes by hand | `->selected('getId', $currentId)` |
| Hard-wire framework-specific markup | Swap out a driver |

---

## Custom Drivers

Favigation is extensible — write your own driver to support any data structure. Documentation available in the repository.

---

## License

MIT License — see [LICENSE](LICENSE) for details.
