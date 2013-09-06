<?php
    require "html.class.php";
//    echo html::img()->on('change',function(){echo "HUHU";});


    html::$debug = true;

//    $test = html::tag('item')->append(html::div('test'));
//    echo $test->pushToString();
//        echo html::tag('wp:term')->append(
//                        html::tag('wp:term_id')->text("")
//                        , html::tag('wp:term_taxonomy')->text("")
//                        , html::tag('wp:term_slug')->text("")
//                        , html::tag('wp:term_parent')->text(0)
//                        , html::tag('wp:term_name')->cdata()
//        )->pushToString();
    echo html::tag('wp:term_id')->text(null);
//    print_r($test);

    exit;

    $art = html::article(
      html::h1('$title'),
      html::time('$date'),
      html::img()->src('$img'),
      html::p('$main_content')
    );
    echo $art;

    echo html::atag()->append(html::sometag());
    echo html::btag()->prepend(html::sometag());


    echo html::ctag()->append(html::whatever())->html(html::etag())->append(html::b('useful'))->prepend(html::i('just before'));
    echo html::dtag()->text(html::ftag());

    $var1 = "Hallo Welt";
    $var2 = "dsfdsf";
//    echo html::img()->on('src',function(){return "NO";})->src($var1);
//    echo html::ifNotAnyEmpty($var1)->img()->src($var1);
//    echo html::ifNotAnyEmpty($var1)->img('src2','test')->src($var1)->ifNotEmpty($var1)->test()->somedepth('dsfdsf');
//    echo html::ifNotAnyEmpty($var1,$var2)->img()->src($var2)->src2($var1)->append(html::div('blahblah'));
//    echo html::ifNotAllEmpty($var1,$var2)->img()->src($var2)->src2($var1)->append(html::div('blahblah'));
//    echo html::tag('ifNotAnyEmpty')->img()->src($var2)->src2($var1)->append(html::div('blahblah'));
    
//    echo html::div('Worktest');
//    echo html::ifNotAnyEmpty()->test("entry")->src($var2)->src2($var1)->append(html::div('blahblah'));
//    echo html::ifNotAnyEmpty()->test()->src($var2)->src2($var1)->text("dsfdsfdsf");
	exit;

    /*
Hi Carlos,

I've already digged into my sources and a quite well working prototype... Unfortunally a client called and I had to do some boring work.

Anyways. Your code snippets helped me well to see how you use it.
Thats great to see as it works even if the way you use it is close to perfect. I've some comments on that to give you a little more background right on your snippets.

Your snippet:
$art = html::article()->appendDimension(
    html::h1($title),
    html::time($date),
    html::img()->src($img),
    html::p($main_content)
  );
  echo $art;

I would suggest you that:
    $art = html::article(
      html::h1($title),
      html::time($date),
      html::img()->src($img),
      html::p($main_content)
    );
    echo $art;

Explained: You do not need to declare a "dimension".
Whenever you just append a html tag to another, simply put it right into in the node tag.

html::tag(html::anothertag());

This is pretty much the same as:
html::tag()->append(html::anothertag());

appendDimension() sets internally a "level-flag" just for the case you want to "explode" an array into a prepared "control". If you haven't used controls yet, just dont mind... they work but I found them even myself to hard to master.

Just to round it up.
Append-/Prepend-/Inserttools are:
html::tag()->append(html::tag());
html::tag()->prepend(html::tag());

If you would just replace the content try:
html::tag()->html(html::tag());
html::tag()->text(html::tag());

     */


	/*
	
	
	//IDEEEEEEN
	echo html::div('schmitz')->on('change', new action(function($this){return 'blahblah';}));
	
	$test1 = '<div class="sdfdjsgfjdsbj">Simon</div>';
	
	$test2 = html('<div>')->text('Simon');
	
	
	echo $test1."\n";
	
	$test3 = html('span')->append($test2);
	
	echo $test3;
	$json = html((string) $test3)->append(html('b'));
	
	echo json_encode($json);
	
	$testx = html::control('input')->lib('standard')
					->append(html('<label>')->for('#name'))
					->append(html('<input>')->type('text')->name('#name')->jdshfgjdsfgdkjf('sdfsdfsdf'));
	
	print_r($testx);
	
	exit;
	*/
	
	/*
	$a = html::tag('a')
			->class('test')
			->addClass('onemore')
			->href("mailto:hallo@echterhoff.it")
			->html("Lars Echterhoff Medientechnik")
			->toArray();
	
	//echo
	html::tag('a')
				->class('mail')
				->href("mailto:hallo@echterhoff.it")
				->html("Lars Echterhoff Medientechnik")."\n";
	
	$div = html::tag("div")->class("container");
	//echo
	html::tag('a')
				->class('itsme')
				->href("mailto:hallo@echterhoff.it")
				->html("Lars Echterhoff Medientechnik")
				->appendTo($div)."\n";
	
	$h1 = html::tag('h1')
					->html("WORKS!");
	$span = html::tag('span')
					->html("Lars Echterhoff Medientechnik");
	$out = html::tag('a')
				->href("mailto:hallo@echterhoff.it")
				->class("mailme")
				->addClass("formore")
				->html("Text")
				->addHtml(" me!")
				->append($h1)
				->append($span);
				
	//print_r($out->toArray());
	//print_r($out->toString());
	
	$test = html::tag('div')->class("test")->addclass("testharder");
	//print_r($test);
	//echo
	$test."\n\n";

	$form = html::tag('form')->class('login_form')->append(
		#Benutzername becomes Array
	html::tag('label')->html('Benutzername')
	)->addhtml('<br>')->append(
		html::tag('input')->type('text')->id('username')
	)->append(
		html::tag('label')->html('Kennwort')
	);

	//echo "\n\n";
	//echo
	$form;
	
	//echo html::meta()->{'http-equiv'}('test');
	*/
	//echo html::meta()->{'http-equiv="blahblah" content="utf-8"'}();
	//echo "\n";
	/* */
	//html::$debug = true;
	
	//$test = html('<div class="wooopwooop"><a href="#anchor">TEX<meta />T!<br>AFTERSINGLEbutBEFOREend</a>SubText</div>MOSTendSUFFIX');
	//print_r($test);
	//echo $test."\n";
	
	
	//echo html::div()->append(html::span(),html::b(),"Plaintext");
	
	//exit;
	
	//echo html::span(html::b(html::u('Bold an Underlined!')));
	
	//echo '<div><span>Ich</span><span>Du</span></div>';
	
	//echo a('Link1','href','#');
	//echo "\n";
	//echo "<div><span>TEST</span><u><span>test</span><span>more</span></u></div>";
	//echo html::tag('<div><span>Ich</span><span>Du</span></div>')->class('greatshit');
	
	/*
	html::control('schmitz')
		->append(
			html::div('#frage')
					->class('question'))
		->append(
			html::div('#antwort')
					->class('answer'))
	;
	

	echo html::control('schmitz')
							->frage('Was will der Schmitz?')
						->antwort('Ne Kippe!')."\n";
	
	echo html::control('schmitz')
							->frage('Was will der Schmitz noch?')
						->antwort('Und: PURE HTML-ACTION!!!')."\n";

	echo html::control('schmitz')
							->frage('Was ist gr�n?')
						->antwort('Ein Blatt!')."\n";
		
	exit;
	*/
	html::control('textinput')->append(
		html::div('class','control textinput',
			html::span()->append(html::label('#label','for','#name'))->addtext(': '),
			html::input('id','#name','value','#value')
		)
	);
	//print_r($atg);
	
	echo html::control("textinput")->label('Name')->name('name')->value('')."\n";
	echo html::control("textinput")->label('Phone')->name('phone')->value('+49');
	
	echo html::textinput()->label('Lars test');
	//$out2 = html::control("test");
	//echo $out2;
	exit;
	
	$test1 = html("div")->class("test1")->text("Inside!");
	$test1->addtext("Lars");
	//print_r($test1);
	echo $test1."\n";
	$test2 = html("<div>")->text("Inside")->class("test2")->appendTo($test1);
	echo $test1."\n";
	$test2->addClass("schmitz");
	echo $test1."\n";
	html::u('Baaam','class','nounderline')->appendTo($test2);
	echo $test1."\n";
	
	//echo "Cache is: ".html::cache(true)."\n";
	
	//echo html('<span class="lars"></span>')->class("another")->addclass("one")."\n";
	//echo html('<span class="lars more"></span>')->class("another")->addclass("one")."\n";
	
	//exit;
	
	html::cache(true);
	$tags = array('span','div','a','b');
	//$tags = array('div');
	
	$time_start = microtime(true);
	for($x=1;$x<1000;$x++){
		$key = array_rand($tags);
		$test = '<'.$tags[$key].' class="lars more"></'.$tags[$key].'>';
		$test = html($test);
	}
	$time_end = microtime(true);
	$time = $time_end - $time_start;

	echo html('div')->html("In $time Sekunden $x ".htmlentities($test)." Elemente erzeugt");

	$time_start = microtime(true);
	for($x=1;$x<1000;$x++){
		$key = array_rand($tags);
		$test = html($tags[$key])->class("lars")->addclass("more");
	}
	$time_end = microtime(true);
	$time = $time_end - $time_start;

	echo html('div')->html("In $time Sekunden $x ".htmlentities($test)." Elemente erzeugt");

	$time_start = microtime(true);
	for($x=1;$x<1000;$x++){
		$key = array_rand($tags);
		$test = html('<'.$tags[$key].'>')->class("lars")->addclass("more");
	}
	$time_end = microtime(true);
	$time = $time_end - $time_start;

	echo html('div')->html("In $time Sekunden $x ".htmlentities($test)." Elemente erzeugt");
	
	exit;
	$test1 = html('span')->class("appendMe")->html("Testinside AppendMe");
	echo $test1;
	
	echo "\n";
	
	$test2 = html('<div>')->class("container")->text("Some Text")->append($test1);
	echo $test2;
	
	echo "\n";
	
	$test1 = html('<div>')->class('container');
	$test2 = html('<span>')->class("set")->addclass("bold")->text("I am span!")->appendTo($test1);
	echo $test1;
	
	echo "\n";
	echo html::tag('<div class="test">')->addclass("nr2")->text("Booooya!");
	echo "\n";
	$control1 = html::control("wooohoo")->lib("common")
				->append(html('<label>')->for('#name'))
				->append(html('input')->type('text')->name('#name'));
				
	echo html((string) $control1)->to('label')->addtext("Boooohm!");
	
	//print_r($test2);
//	echo $test1;
	
	//print_r($test1);
	//print_r($test2);
	
	//echo html::input()->test('entry')->type('text');
	
	//echo "\n\n\n\n\n";
	/*
	echo html::control('input')->lib('system')->append(
					html::label()->for('#name')->html('#label')
				)->append(
					html::input()->type('text')->id('#name')->name('#name')->value('#value')
				);
	echo "\n";
	
	echo html::control('input')->name('blahblah')->label("mehrblah");
	*/
