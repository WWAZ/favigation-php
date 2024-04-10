# Favigation PHP

Creating HTML markup for navigation elements is straightforward. Favigation streamlines the process, handling the complexities and providing an easy-to-use API to render your navigation-data into HTML.

Through the use of drivers, you can transform virtually any data structure into navigation markup. Favigation provides predefined drivers for platforms like Wordpress and frameworks such as Bootstrap.

## Basic usage

Favigation is easy to use. Here's a simple example. Let's render navigation markup using (streamlined) WordPress menu data.

    $menudata = [
    	[
	    	'ID'  =>  1,
	    	'post_parent'  =>  0,
	    	'menu_order'  =>  2,
	    	'url'  =>  'about',
	    	'title'  =>  'About us',
    	],
    	[
	    	'ID'  =>  2,
	    	'post_parent'  =>  1,
	    	'menu_order'  =>  3,
	    	'url'  =>  'Team',
	    	'title'  =>  'team',
    	],
    	[
	    	'ID'  =>  3,
	    	'post_parent'  =>  0,
	    	'menu_order'  =>  1,
	    	'url'  =>  'homepage',
	    	'title'  =>  'Home',
    	],
    ];

Pass the menu-data to a Favigation builder.

    // Let's build a Favigation object from raw Wordpress menu-data
    $favigation = new wwaz\Favigation\Driver\Wordpress\Builder($menudata);
    
    // Render html
    echo $favigation->toHtml();

The builder renders the data in the following manner.

    <ul>
	    <li><a  href="homepage">Homepage</a></li>
	    <li><a  href="about">About us</a>
		    <ul>
			    <li><a  href="team">Team</a></li>
		    </ul>
		</li>
    </ul>


## Installation

Install Favigation via composer

    composer require wwaz/favigation-php

## Custom usage

What sets Favigation apart is the separation of data from the view, coupled with a highly customizable rendering process. The builder takes a 1) data collection and a 2) renderer. 

    $favigation = (new wwaz\Favigation\Builder(
	    new \wwaz\Favigation\Collection($data),
	    \wwaz\Favigation\Driver\Bootstrap\BootstrapMenuRenderer::class
    ))
	    ->tag('ul')
	    ->id('favigation')
	    ->selected('getId', 3)
	    ->getBuild();

Let's go further:

    $favigation
    
	  ->setContent(function($item){
		  $icon = ''; 
		  if( $item->getIcon() ){
				$icon = '<img class="icon" src="' . $icon . '">';
			}
			if( $item->getUrl() ){
				return $icon . '<a' href="' . $item->getUrl() . '">' . $item->getTitle() . '</a>';
			} 
			return $icon . '<span>' . $item->getTitle() . '</span>';
		})
		
		->setLiAttribute('data-id', function($item){
			return $item->getId();
		})
		
		->toHtml();


## Writing Drivers

...


## License

No license – All rights reserved, not open source or free. 
You cannot modify or redistribute this code without explicit permission from the copyright holder.