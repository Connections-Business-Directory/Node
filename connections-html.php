<?php
/**
 * Plugin Name: Connections HTML Class Testing
 * Plugin URI: http://connections-pro.com/
 * Description: None.
 * Version: 1.0
 * Author: Steven A. Zahm
 * Author URI: http://connections-pro.com/
 * License: GPL-2.0+
 * License URI: http://www.opensource.org/licenses/gpl-license.php
 * Text Domain: connections-html
 * Domain Path: /languages/
 *
 * Copyright (c) 2012-2013 ZAHMit.design
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 */
// require_once( 'class.html.php' );
add_shortcode( 'cnhtml', 'CN_HTML' );

function CN_HTML ( $atts , $content = NULL ) {

	// Create a <div>
	// $html = cnHTML::tag( 'div' );
	// $html->append( h1( 'This is a <h1> tag.' ) );

	clearstatcache();
	include 'html.class.php';

	//If we really wanna freak out on the syntax, we can try something like that:
	// $container = html::div('This is green text in a container.')->{'class="container" style="color: green"'}( html::span('<- Freaky! ;-)') );

	  //Now lets move to a more complex construct.
	  //Create your html construct
	  $control = html::div('class','mycontrol textinput')
				   ->append(
						 html::label('#caption')->for('#name'),
						 html::input()->type('text')->name('#name')->id('#name')->value('#value'),
						 html::input()->type('button')->title('#command')->value('#command')
				   );
	  //Draft your control if you want to:
	  //echo $control;

	  //Assign your control code to a named html::control
	  html::control('textinput')->append($control);

	  //Open a container
	  $container = div('class','container');

	  //Reuse your control where ever you need it:
	  html::control('textinput')
			 ->name('name')
		  ->caption('Type your name: ')
		  ->command('Send')
			->value('')
		 ->appendTo($container);

	  html::control('textinput')
			 ->name('age')
		  ->caption('Type your age: ')
		  ->command('Really?')
			->value('')
		 ->appendTo($container);

	return esc_html( $container );
}