<?php
	//Include the html.class
	include "html.class.php";
	
	/* 
	 * Skip this part. It is my html.class-way to show case these examples.
	*/
	html::control('chapter')->append(h2('#name'))
		->on('echo',function($this){echo $this; ob_start();})
		->on('code',function($this){echo htmlentities($this);});
	$referer = (isset($_SERVER["HTTP_REFERER"])?$_SERVER["HTTP_REFERER"]:"http://code.google.com/p/phpcreatehtml/");
	html::control('example')->append(
	div('class','example')->append(
					h3('#title'),
					div('class','description')->append(span('#description')),
					div('This is the result','class','startopen collapsible'),
					div('#example','class','outputbox'),
					div('See the PHP source (click to expand)','class','startclosed collapsible'),
					div(pre('#sourcecode','class','outputbox phpcode')),
					div('The HTML source for that example (click to expand)','class','startclosed collapsible'),
					div(pre('#result','class','outputbox source htmlcode'))
		)->append(br(),div(a('Return to '.$referer,'href',$referer))))
		->on('echo',function($this){ob_end_clean(); echo $this;})
		->on('code',function($this){echo htmlentities($this);});
	
	
	/*The examples start right here!*/ html::control('chapter')->name('Simple Textcreation')->echo();
	
	
	/*Simple tag creation example*/
	
	//Lets start with a div container...
	$container = html::div('class','container');
	
	//...and add a heading to it...
	html::h3("Example")->appendTo($container);
	
	//...and another one.
	html::h4("Create and appendTo.")->appendTo($container);
	
	//Done, lets get the output.
	echo $container;
	
	//Feel free to examine the object that has been created, just uncomment
	//print_r($container);
	
	
	html::control('example')->sourcecode(snipFrom(__LINE__))->result(formatExampleCode($container))->example($container->toString())
		->title('Lets create a div container')
		->description('Create some elements and append them to the container.')
		->echo(); html::control('chapter')->name('')->echo();
		
	
	//Once again, almost the same output. Simple creation but another style.
	$out = html::div(html::h3('Example'),html::h4('Append inline.'));
	echo $out;
	//print_r($out);
	
	//And now the really short way. (Enabled "function alias" needed)
	$out = div(h3('Example'),h4('Append inline short.'));
	echo $out;
	//print_r($out);
	
	
	
	html::control('example')->sourcecode(snipFrom(__LINE__))->result(formatExampleCode($container))->example($container->toString())
		->title('Another simple text creation')
		->description('This example makes use of another code style.')
		->echo(); html::control('chapter')->name('')->echo();
	
	
	html::h4("Lets add some attributes.")->appendTo($container);
	
	//Lets create another div as container and adding a class 'container', some fanzy colors and some plain text.
	$container = html::div('I am a container and contain nodes.')->class("container")->style('color: green;');
	
	//Yeah, another style has already applied, so this one will replace the style.
	$container->style('color: blue;');
	
	//Append a break for the nicer output
	$container->append(html::br());
	
	//Now lets create a span tag in list style and append it to the container.
	$descriptionText = html::span('Like the creation of new nodes, there are several ways to create attributes. ','style','color: red;','class','description')->appendTo($container);
	
	//...add another piece of text...
	$descriptionText->addText('There are simply two rules.');
	
	//...and some list items.
	html::ol('style','color: black;',
		html::li("If you provide attributes as list and the given count of attributes is odd, the first element is interpreted as node content."),
		html::li("Attributes overwrite existing attributes. Last in, last out.")
	)->appendTo($descriptionText);
	
	//Here we go.
	echo $container;
	//print_r($container);
	
	
	html::control('example')->sourcecode(snipFrom(__LINE__))->result(formatExampleCode($container))->example($container->toString())
		->title('Based on the $container we already build, we extend the container a bit')
		->description('Find some more advanced things like append in this example.')
		->echo(); html::control('chapter')->name('Freak out on syntax!')->echo();
	
	
	//If we really wanna freak out on the syntax, we can try something like that:
	$container = html::div('This is green text in a container.')
						 ->{'class="container" style="color: green"'}(html::span('<- Freaky! ;-)'));
	echo $container;
	

	html::control('example')->sourcecode(snipFrom(__LINE__))->result(formatExampleCode($container))->example($container->toString())
		->title('It is up to you. How do you like to write your code?')
		->description('I tried everything to make use of every syntactic constellation that is some how to realize. Just freak out on a valid syntax and try it your self.')
		->echo(); html::control('chapter')->name('Lets move the focus on attributes')->echo();
	
	
	echo html::style('.in.a.box{border: 1px solid black;}');
	
	//Lets focus on attributes
	$container = html::div('Container')->class('in a box');
	echo $container;
	
	html::control('example')->sourcecode(snipFrom(__LINE__))->result(formatExampleCode($container))->example($container->toString())
		->title('Just add a class')
		->description('This is the probably most readable way to define an attribute')
		->echo(); html::control('chapter')->name('')->echo();

	
	//You will get the same result writing
	$container = html::div('Container','class','in a box');
	echo $container;


	html::control('example')->sourcecode(snipFrom(__LINE__))->result(formatExampleCode($container))->example($container->toString())
		->title('Add content and attributes by providing a list of paramter')
		->description('This is also very readable if you write code by hand')
		->echo(); html::control('chapter')->name('')->echo();


	//...and if you are really in the need of adding things, prepend add to your attribute
	$container = html::div('Container','class','in a');
	$container->addClass('box');
	echo $container;
	

	html::control('example')->sourcecode(snipFrom(__LINE__))->result(formatExampleCode($container))->example($container->toString())
		->title('Add content and attributes by providing a list of paramter')
		->description('This is also very readable if you write code by hand')
		->echo(); html::control('chapter')->name('')->echo();

	
	//You want to get rid of an attribute? Unset it!
	$container = html::div('Container','class','in a box');
	unset($container->class);
	echo $container;


	html::control('example')->sourcecode(snipFrom(__LINE__))->result(formatExampleCode($container))->example($container->toString())
		->title('Use common php commands to remove the attribute once you appended')
		->description('As long as you got a "connection" to an element, it is up to you to remove already set attributes. Just unset them.')
		->echo(); html::control('chapter')->name('')->echo();


	//Oh, you know jQuery? Create nodes from strings will also work
	//This way of creating nodes works but is not as efficient as "building" tags, anyways: Do it the way you want.
	$container = html::tag('<div class="in a box">Container</div>');
	echo $container;
	//print_r($container);


	html::control('example')->sourcecode(snipFrom(__LINE__))->result(formatExampleCode($container))->example($container->toString())
		->title('Create nodes from html code')
		->description('This might look comfortable but is very inefficient and hey: Thats not the reason I wrote this class... so this is only a way. Not to be prefered! ...but possible anyways.')
		->echo(); html::control('chapter')->name('')->echo();


	//Last but not least:
	$container = html('<div>')->append(html('<span>')->append('Container'));
	$container->{'class="in a box"'}();
	//Nothing special, but appended node and this weird attribute creation.
	echo $container;
	

	html::control('example')->sourcecode(snipFrom(__LINE__))->result(formatExampleCode($container))->example($container->toString())
		->title('Create nodes from html code')
		->description('This might look comfortable but is very inefficient and hey: Thats not the reason I wrote this class... so this is only a way. Not to be prefered! ...but possible anyways.')
		->echo(); html::control('chapter')->name('')->echo();

	
	
	//Let me explain
	$container = html('div')->class('container');
	html::h3('The reasons why I created this class')->appendTo($container);
	$explanation = html::span('class','explained')->appendTo($container);
	$explanation->text('
	I know, there are some good classes out there to create html code.
	They were all build with a specific idea in mind. Some of them do great job in assisting you writing correct html, tidying html, tweaking or modifying existing code or even automatic correction and nicing of ugly written code. These libs have to come to work, when everything else is to late.
	Some other classes assist you in html creation. They offer special short syntax for common tasks on common tags like e.g. echo img($src); They are, in their nature, limited to a set of functionality. Beyond this limit, there is again, plain html.
	A third genre are templating engines like the well known smarty. These template engines are specialized in letting you combine logics with html fragments in a very simple manner. In case of smarty in a special syntax (PHP alike) that brings some pro\'s and some con\'s.
	');
	$list = html::ul()->appendTo($container);
	$bulletpoint[] = 'I assume, users of my class know what they are doing and know how to write html. I do not want to restict your use of marked up code within the sgml world. Dont expect auto correction or automatic correction of misspelled tags or attributes.';
	$bulletpoint[] = 'I would give (and get for me of course) as much freedom in sytax as possible without creating a new parser. Almost everything you write and use for tag and attribute creation is written in PHP syntax but as flexible as possible.';
	$bulletpoint[] = 'I neither like eval, nor wanted to create another full featured parser. Mixing string crafted html concats are difficult to maintain. Template engines are a kind of extra abstraction and need some more setup and an extra interface. Its okay but not always the right thing.';
	$bulletpoint[] = 'I just thought about html as an object serialized from a string. So why mixing datas from object with strings through helper function/methods, templating with concatination instead of threating it what it is?';
	$bulletpoint[] = 'To be honest: I am a very lazy person. I do not want to write repeated pattern, repeating pattern or tons of code. Reusable and easy to maintainable code that is flexible and easy to understand but fully in control of the person who has written it. With or without mixing of data and structure. With or without mixing of structure and design. Do it the way you want and never regret things you have already written. Create controls.';
	foreach($bulletpoint as $text)
		html::li($text)->appendTo($list);
	echo $container;
	
	
	
	html::control('example')->sourcecode(snipFrom(__LINE__))->result(formatExampleCode($container))->example($container->toString())
		->title('Get a clue how efficient it can be to use this class')
		->description('Programatic repetition of elements. Just take your stuff an append it to nodes.')
		->echo(); html::control('chapter')->name('Controls')->echo();

	
	//Here comes the: Control!
	//Lets start with a simple example.
	//Create your control as you would create a tag.
	html::control('simpleexample')->append(html::button('#caption'));
	
	//Open a div as "output collector"
	$container = div('class','container');
	//Now fill in data. Use the value with hash as key and assign your desired value.
	html::control('simpleexample')->caption('My Button!')->appendTo($container);
	//Feel free to reuse your control whenever you need a button.
	html::control('simpleexample')->caption('Second Button.')->appendTo($container);
	
	echo $container;
	

	html::control('example')->sourcecode(snipFrom(__LINE__))->result(formatExampleCode($container))->example($container->toString())
		->title('Controls are reusable constructs')
		->description('You would like to create consistent html code fragments for recurring tasks/output. Controls should fit that need.')
		->echo(); html::control('chapter')->name('')->echo();

	
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
	
	
	html::control('example')->sourcecode(snipFrom(__LINE__))->result(formatExampleCode($container))->example($container->toString())
		->title('Quite nice, isnt it?')
		->description('Lets create some more complex controls')
		->echo(); html::control('chapter')->name('Repeatable controls')->echo();


	//Lets dig a bit more into the difference between "repeatable controls" and "multi-dimensional controls"
	//Lets create a repeatable control
	html::control('repeatable')->append(
		html::li('Point -> #item')
	);
	
	//Now lets fill it with an array of values
	$items[] = "Milk";
	$items[] = "Sugar";
	
	
	html::control('repeatable')->item($items)->appendToEmpty($container);
	
	
	html::control('example')->sourcecode(snipFrom(__LINE__))->result(formatExampleCode($container))->example($container->toString())
		->title('We start with a simple repeated list')
		->description('This is the easiest and smallest way for repeating html code framents')
		->echo(); html::control('chapter')->name('')->echo();

	//Another way is to fill it with a list of values
	html::control('repeatable')->item('Butter', 'Bread')->appendToEmpty($container);
	

	html::control('example')->sourcecode(snipFrom(__LINE__))->result(formatExampleCode($container))->example($container->toString())
		->title('Just another way to fill in data.')
		->description('This example is more useful for handcrafted items as it utilizes a list of parameters.')
		->echo(); html::control('chapter')->name('')->echo();

	
	//With these given examples, you probably dont see why this type is very simple. It does
	// simply not know what part of the code you want to repeat.
	// As of this it simply repeats the tag that has the hash including all sub nodes.
	
	//As soon as you add another dimension you will get (unwanted?) duplicates.
	html::control('beawareof')->append(html::li('#text1 #text2'),html::hr());
	
	
	//So lets expand it:
	html::control('beawareof')->text1('Value','Is the key')->text2('Added')->appendToEmpty($container);
	//What happend?
	//First: The extra hr tag got ignored, cause it is "outside" of any repeated element.
	//Second: text1 got repeated and filled driven by the given list. text2 got filled by the second value after
	//				the variable has be blind copied to all tag copies. In further releases I will maybe extend this
	//				behavior to a "top town" fill so DONT RELY ON THIS "feature". Feel free to play with it as it might
	//				give a great advantage and if you think it should stay Im gonna introduce another command for explicit
	//				"top down" fill.
	html::control('beawareof')->text1('Value','Is the key','#nevertolate')->text2('Added')->nevertolate('Do you see any use in this?')->appendTo($container);

	
	html::control('example')->sourcecode(snipFrom(__LINE__))->result(formatExampleCode($container))->example($container->toString())
		->title('Be aware of things that cant be handled.')
		->description('This example is just to show you the limits of this simple technic.')
		->echo(); html::control('chapter')->name('More complex and powerful controls')->echo();

	
	//Multi dimensional controls are currently the most complex and powerful tools to create html/objects from your code
	//As soon as you see how to create and fill them, you will miss them everytime you create code without the html.class. I promise!
	//Lets start building a control in well formatted chaining style.
	
	html::control('chainstyle')->append(
		html::table()->border(1)->appendDimension(
			html::tr()->appendDimension(
				html::td('#data')
			)
		)
	);
	
	//As you can see, this is a plain table. Lets go on with the sample data
	for($row = 0; $row<=9; $row++)
		for($cell = 0; $cell<=5; $cell++)
			$tabledata[$row][$cell]["data"] = $row."-".$cell;
	
	//And explode it into the pattern
	html::control('chainstyle')->explode($tabledata)->appendToEmpty($container);
	
	
	html::control('example')->sourcecode(snipFrom(__LINE__))->result(formatExampleCode($container))->example($container->toString())
		->title('Creation of a simple table as example for multi dimensional patterns')
		->description('As soon as you got how it works, you will ask yourself: Why the hell has it never been so easy to create html from php?')
		->echo(); html::control('chapter')->name('')->echo();
	
	//Now to the part what happend.
	//As you know, you had to name the control and append a node to it.
	html::control('chainstyle')->append(
		//The node you appended ist the base node. Thats also the reason why you just "appended" it instead of
		// appending plus assigning it as as new dimension. Each sub node from here should repeat for each first dimension.
		// Means: each <tr></tr> should copy with each element in an array. (First Dimension)
		html::table()->border(1)->appendDimension(
			//Now append a second dimension to each tr element...
			html::tr()->appendDimension(
				//..the td. And of course. Each td consists of some "data".
				//Those data reside in the virtually third dimension but will get applied to each "last" in this case 2nd dimension.
				html::td('#data')
			)
		)
	);
	
	//As last step, we append it to the container
	html::control('chainstyle')->explode($tabledata)->appendToEmpty($container);
	
	//...and send it to the audience.
	echo $container;
	
	//Keep in mind: The control will only be written once!
	
	html::control('example')->sourcecode(snipFrom(__LINE__))->result(formatExampleCode($container))->example($container->toString())
		->title('Do you missed code in the last example?')
		->description('There was realy everything in it that was needed to create this table! (And not more!) Here is the code more commented.')
		->echo(); html::control('chapter')->name('Dont take this example to serious.')->echo();

	
	//You feel uncomfortable with commands like appendDimension? You do not like readable code?
	//Hard to craft, so it should be hard to read. ;-) No problem, create your own aliases.
	//Let us shrink the table example to less than the half of code and fit it in one line.
	
	//Start with shortening commands (CASESENSITIVE)
	$H=new html;
	$H::alias('append','x');
	$H::alias('appendDimension','D');
	$H::alias('control','C');
	$H::alias('explode','X');
	function T(){return call_user_func_array('table',func_get_args());};
	function D(){return call_user_func_array('td',func_get_args());};
	function R(){return call_user_func_array('tr',func_get_args());};
	define('r','#r');
	define('R','r');
	define('d','-');
	
	$h = div('class','container');
	
	//This is short, aint it?
	$H::C(0)->x(T()->D(R()->D(D(r))));                             // <- Definion
	for($r=0;$r<=9;$r++)for($c=0;$c<=5;$c++)$d[$r][$c][R]=$r.d.$c; // <- Create example data
	$h->x($H::C(0)->X($d));                                        // <- Fill in and output to container
	
	//But, as i mentioned: This example is just to showcase the flexibility and should not be used in any real scenario
	
	
	html::control('example')->sourcecode(snipFrom(__LINE__))->result(formatExampleCode($h))->example($h->toString())
		->title('Define your own commands as alias')
		->description('Your feel uncomfortable with my predefined commands? Create aliases. I used this to shorten the syntax to the max.')
		->echo(); html::control('chapter')->name('Lets get real again')->echo();

	
	//Repeating dimension currently MUST be completly consist of enumerated keys. (Or at least start with a 0 as first element key.)
	//The last dimension must completly consist of associative keys, otherwise it will not be identified as last dimension.
	
	
	//Lets repeat this example with another construction style and an extra dimension.
	
	html::control('controlstyle_listitem')->append(html::li('#name'));
	html::control('controlstyle_orderedlist')->append(html::ol()->appendDimension(html::control('controlstyle_listitem')));
	
	html::control('controlstyle_tabledata')->append(html::td()->append(html::control('controlstyle_orderedlist')));
	html::control('controlstyle_tablerow')->append(html::tr()->appendDimension(html::control('controlstyle_tabledata')));
	html::control('controlstyle_table')->append(html::table()->border(1)->appendDimension(html::control('controlstyle_tablerow')));
	
	$tabledata = 
	array(
		array(
			array(
				array('name'=>"Lars"),
				array('name'=>"Janet"),
			),
			array(
				array('name'=>"Simon"),
				array('name'=>"Sandra"),
			),
			array(
				array('name'=>"Dieter"),
				array('name'=>"Anne"),
				array('name'=>"Nero"),
			),
		),
	);
	
	html::control('controlstyle_table')->explode($tabledata)->appendToEmpty($container);
	
	html::control('example')->sourcecode(snipFrom(__LINE__))->result(formatExampleCode($container))->example($container->toString())
		->title('Lets just advance and forget about this perl golf styles.')
		->description('The following example adds an extra dimension to the pattern.')
		->echo(); html::control('chapter')->name('')->echo();

	
	html::control('listitem2')->appendDimension(
		html::li('Point -> #item')->style('color: #color;')
	);
	html::control('list2')->append(
		html::ul('class','mylist')->appendDimension(
			html::control('listitem2')->append(html::control('textinput')),
			html::span('Item Footer')
		)
	);

	$item = array();$items = array();
	
	for($x=1;$x<=5;$x++){
		$items[0] = array("color"=>"red","item"=>"Test Text","caption"=>"Blah","name"=>"textinput_red$x","value"=>"Enter something!","command"=>"Start it");
		$items[1] = array("color"=>"green","item"=>"Another Test","caption"=>"Blahblah","name"=>"textinput_green$x","value"=>"Preset?","command"=>"Click me");
		$item[$x] = $items;
	}

	$container=div('class','container');
	
	html::control('list2')->explode($item)->appendTo($container);

	html::control('example')->sourcecode(snipFrom(__LINE__))->result(formatExampleCode($container))->example($container->toString())
		->title('Here is an quite complex example for the use of control within controls and pattern')
		->description('This is currently one of the most "complicated" things you can do. But also very effective!')
		->echo(); html::control('chapter')->name('')->echo();


	html::control('lientry')->append(html::span(' * ')->style('color: red;')->class('gender #gender')->title('Persons gender is: #gender')->append(html::a('#name')->href('#page_#gender')));
	html::control('litem')->append(html::li()->appendDimension(html::control('lientry')));
	html::control('olist')->append(html::ol()->appendDimension(html::control('litem')));
	
	$row = array(); $user = array();
	
	$user[0]["name"] = "Lars";
	$user[0]["gender"] = "male";
	$user[1]["name"] = "Thorsten";
	$user[1]["gender"] = "male";
	$row[] = $user;
	unset($user);
	$user[0]["name"] = "Denny";
	$user[0]["gender"] = "male";
	$user[1]["name"] = "Hannah";
	$user[1]["gender"] = "female";
	$row[] = $user;
	unset($user);
	$user[0]["name"] = "Wolfgang";
	$user[0]["gender"] = "male";
	$user[1]["name"] = "Andrea";
	$user[1]["gender"] = "female";
	$user[2]["name"] = "Reinhard";
	$user[2]["gender"] = "male";
	$row[] = $user;
	unset($user);
	for($x=1;$x<=20;$x++){
		$user[0]["name"] = "Denny".$x;
		$user[0]["gender"] = "male";
		$user[1]["name"] = "Hannah".$x;
		$user[1]["gender"] = "female";
		$row[] = $user;
		unset($user);
	}
	
	html::control('olist')->explode($row)->appendToEmpty($container);
	
	
	html::control('example')->sourcecode(snipFrom(__LINE__))->result(formatExampleCode($container))->example($container->toString())
		->title('Just another example code.')
		->description('')
		->echo(); html::control('chapter')->name('')->echo();



	function formatExampleCode($code){
		$code = str_replace("\n","<br>",htmlentities(html::tidy($code)));
		return str_replace(" ","&nbsp;",$code);
	}

	function snipFrom($linenr){
		$lines = file(__FILE__);
		krsort($lines);
		foreach($lines as $nr => $line){
			if($nr < $linenr){
				if(strpos($line,str_replace("!!","","html::control!!('example')"))!==false) $record = true;
				if($record==true && (
						 strpos($line,str_replace("!!","","html::control!!('chapter')"))!==false
					|| strpos($line,str_replace("!!","","->echo!!()"))!==false
					)
				) break;
				if($record) $snipped[$nr]=htmlentities(str_replace("\t","  ",$line));
			}
		}
		array_shift($snipped);
		$snipped = array_trim($snipped);
		array_push($snipped,"include 'html.class.php';\n\n");
		array_push($snipped,"&lt;?php\n");
		array_unshift($snipped,"\n?&gt;");
		return implode("",array_reverse($snipped));
	}
	function array_trim($array){
		while(trim(current($array)) == "") array_shift($array);
		$array = array_reverse($array); reset($array);
		while(trim(current($array)) == "") array_shift($array);
		return array_reverse($array);
	}
?>