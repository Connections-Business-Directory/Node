<?php

	include "html.class.php";
	//html::$debug = true;
	//html::$debug=true;
	
/*	
	ctrl('chapter')->append(h2('#name'))->on('echo',function($this){echo $this;});
	print_r(ctrl('chapter')->name('Simple Textcreation')->toArray());


	echo a()."\n";

	echo a('in')."\n";

	echo a()->text('in')."\n";

	echo a()->html('in')."\n";

	echo a()->append('in')."\n";

	echo a()->append('in')->addtext('to')."\n";

	echo a()->append('in')->addtext('to')->append('in')."\n";

	echo b('class','c')."\n";

	echo b('class','c')->addClass('d')."\n";

	echo b('bold','class','c')->addClass('d')."\n";

	echo b('bold','class','c','attr','to')->addClass('d')."\n";

	echo b('bold','class','c','attr','to')->addClass('d')->addtext('text')."\n";

	echo b('bold','class','c',u('underlined'),'attr','to')->addClass('d')->addtext('text')."\n";

	echo b('bold','class','c',u('underlined'),'attr','to',i('italic'))->addClass('d')->addtext('text')."\n";

	echo b('bold','class','c',array(u('underlined')),'attr','to',i('italic'))->addClass('d')->addtext('text')."\n";

	echo b('bold','class','c',array(u('underlined'),'and',i('italic')),'attr','to')->addClass('d')->addtext('text')."\n";

	echo b(array('class','myclass'))->text('text')."\n";

	echo b(array('class'=>'myclass'))->text('text')."\n";

	echo b('class="myclass"')->text('text')."\n";

	echo b('class="myclass"')->text('text')->addClass('two')."\n";

	echo b('class="myclass" text="start"')->addText('end')->addClass('two')."\n";

	echo b('class="myclass" text="start"')->addText('end')->addClass('two')->append(u('italic'))."\n";

	echo b('class','c','d')."\n";

	echo b('class','c','d','e')."\n";

	echo b('class','c','d','e','f')."\n";

	echo b('class','c','d','e','f','g')."\n";

	echo b('class','c','d','e','f','g','h')."\n";

	echo b('class','c','d','e','f','g','h','i')."\n";

	echo b('class','c','d','e','f','g','h','i','j')."\n";

	echo b('class',u(),'c','d','e','f','g','h','i','j')."\n";

	echo b('class',u(),'c','d',i(),'e','f','g','h','i','j')."\n";

	echo b('class',u(),'c','d',i(),'e','f','g',array(span(),div(),hr()),'h','i','j')."\n";

	

	exit;
	echo a('Text')."Y\n";
	echo a('href','link')."Y\n";
	
	echo a('bullshit','href','link')."Y\n";
	echo a('bullshit','href','link','addone')."Y\n";
	echo a('bullshit','href','link','addone','addtwo')."Y\n";
	echo a('bullshit','href','link','addone','addtwo','addthree')."Y\n";
	
	echo a('Link',array('href','#target'),array('target','_new'))."Y\n";
	echo a('Link',array('href'=>'#target'),array('target'=>'_new'))."Y\n";
	echo a('Link',array('href','#target'),array('target'=>'_new'))."Y\n";
	
	echo a('Text','href','link',u('Underline'),'Additional')."Y\n";
	echo a('Text','href','link','Additional')."Y\n";
	echo a(u('Text'),'href','link','Additional')."Y\n";
	echo a('href','link',u('Additional'))."Y\n";
	echo a(u('Additional'),'href','link')."Y\n";
	echo a('Text','href','link',u('Additional'))."Y\n";
	
	echo a(u('Underlined'),'setText','href','link',u('Additional'))."Y\n";
	echo a(u('Underlined'),b('Bold'),'setText','href','link',u('Additional'))."Y\n";
	echo a(1,'href',2,3)."Y\n";
	echo a(1.4,'href','#target',2,3.5)."Y\n";
	
	
	echo span('text','class','yes','style','font-weight:bold;')."Y\n";
	echo html('span','class','yes','style','font-weight:bold;')."Y\n";
	echo html::tag('span','class','yes','style','font-weight:bold;')."Y\n";

	//echo a('testmore!','href','#target',u('Lars'),"shitappens",'everyday');


	exit;
	*/
	
	//html::setNamespace('ns');
	
	$test=html('my','Element',h3('Headline'));
	
	$test('h3');
	
	$test->find('h3>h2,h2[class]');
	
	echo $test;
	
	//html::setNamespace('new');
	
	//echo $test(h1())."\n";
	exit;
	html::setNamespace();

$php=create('html')
->append(
  $head = html::tag('head')->append(
    html("<title>")->text('My Page')
  ),
  body(
    $header = div()->class('header'),
    $body = div('class','main'),
    $footer = html::div()->{'class="footer"'}()
  )
);

h1('This example')->appendTo($body);

$body->append(
  h2('might be confusing!'),div('class','main',p('It uses several different technics to code.','class','mytext'))
);

//echo $php;
	
//echo html::tidy($php,array('show-body-only'=>false));


$php=create('html',
  $head = head(title('My Page')),
  body(
    $header = div('class','header'),
    $body = div('class','main'),
    $footer = div('class','footer')
  )
);

$body->append(h1('This example'));
$body->append(
  h2('is more convenient!'),
  div('class','main')->append(
    p('It uses less confusing technics to code.')
  )
);

//echo $php;
	
//echo html::tidy($php,array('show-body-only'=>false));

ctrl('mypage')->append(
  create('html')->
    appendDimension(
      head(title('#title')),
      body(
        div('class','main')->
          appendDimension(
            div('class','#entryid')->append(
              h3('#headline'),p('#content')
            )
          )
      )
    )
);

//$php = ctrl('mypage')
//    ->title('My Page')
//  ->entryid('entry1')
// ->headline('This is a control example')
//  ->content('Controls are easy to setup and very easy to reuse.');
//
//
////echo $php;
//	
//echo html::tidy($php,array('show-body-only'=>false));


//print_r($data);
//echo html::control('mypage')->explode($data);
$php = ctrl('mypage')->explode( 
  array(
    array(
      'title'=>'My Page',
      array('entryid'=>'entry1','headline'=>'Output1',
            'content'=>'This is the first entry'),
      array('entryid'=>'entry2','headline'=>'Output two',
            'content'=>'This is the second entry'),
      array('entryid'=>'entry3','headline'=>'Output #three',
            'content'=>'This is the last entry'),
    ),
  )
);

//echo $php;

echo html::tidy($php,array('show-body-only'=>false));

	exit;
	
	
	echo div(a('Text','class',$sdkfg));
	exit;
	
	html::control('listitem2')->appendDimension(
		html::li('Point -> #item')->style('color: #color;')
	);
	html::control('list2')->append(
		html::ul('class','mylist')->appendDimension(
			html::control('listitem2'),
			html::span('Item Footer')
		)
	);

	
	$items[] = array("color"=>"red","item"=>"Test Text","caption"=>"Blah");
	$items[] = array("color"=>"green","item"=>"Another Test","caption"=>"Blahblah");
	$item[] = $items;
	$item[] = $items;
	$item[] = $items;
	$item[] = $items;
	$item[] = $items;
	
//	$item = array(
//			array(
//				array("color"=>"red","item"=>"xy")
//			),
//			array(
//				array("color"=>"red","item"=>"zx")
//			),
//	);
	
	$container=div();
	
	$container->append(pre(print_r($item,true)));
	
	echo html::control('list2')->explode($item);
	
	//echo $container;
	exit;
	
	html::control('chapter')->append(h2('#name'))->on('echo',function($this){echo $this;});
	print_r(html::control('chapter')->name('Simple Textcreation')->toArray());
	

//echo html('ul',array(li('1'),li('2'),li('3')))."\n";
//echo ul(array(li('1'),li('2'),li('3')))."\n";

exit;
echo create('html','My Content','tag',array('class'=>'highlight',array('more','evenmore',array('deeper'))));
echo html::tag("Test2",'class','xy',html::span('class','myspan'),html::div());
echo html::tag("Test3",html('inside'));
$php = new html;
echo $php->tag('this')->create('html');
echo create('html','class','test');
echo html::tag('html','class','test','key','node');

exit;

$table = html::table('class','#tableclass')->appendDimension(
	html::tr()->class('#trclass')->appendDimension(
		html::td('#number')
	)
);

html::control('test1')->append(
	html::table('class','#tableclass')->appendDimension(
		html::tr()->class('#trclass')->appendDimension(
			html::td('#number')
		)
	)
);

$tabledata = array(
	'tableclass'=>'mytable',
	array(
		'trclass'=>'tablerow',
		array(
			'number'=>'1',
		),
	),
);
for($x=1;$x<=50;$x++){
	//echo $table->explode($tabledata)."\n";
	//echo html::control('test1')->explode($tabledata)."\n";
}
exit;

$tabledata = array(
	'tableclass'=>'mytable',
	array(
		'trclass'=>'tablerow',
		array(
			'number'=>'2',
		),
	),
);
	echo $table->explode($tabledata)."\n";
	echo $table."\n";
	echo html::control('test1')->explode($tabledata)."\n";
exit;

$table = html::table('class','#tableclass')->appendDimension(
	html::tr()->appendDimension(
		html::td('#number')
	)
);
$li = html::ol()->appendDimension(
	html::li()->appendDimension(
		html::span('#number')
	)
);
$tabledata = array(
	'tableclass'=>'mytable',
	array(
		'trclass'=>'tablerow',
		array(
			'number'=>'1',
		),
	),
);

echo $table->explode($tabledata);
echo $li->explode($tabledata);

exit;

	html::control('table')->append(
		html::table('class','#tableclass')->appendDimension(
			html::tr('class','#trclass')->appendDimension(
				html::td('#data')->class('#tdclass'),
				html::span('#data')
			)
		)
	);
	
	$data = 
	array(
		'tableclass'=>'myTable',
		//TR Dimension
		array(
			'trclass'=>'trclassname1',
			//TD Dimension
			array('data'=>'Test1','tdclass'=>'Testclass'),
			//TD Dimension
			array('data'=>'Test2','tdclass'=>'Testclassmore'),
		),
		//TR Dimension
		array(
			'trclass'=>'trclassname2',
			//TD Dimension
			array('data'=>'Test3','tdclass'=>'Testclass1'),
			//TD Dimension
			array('data'=>'Test4','tdclass'=>'Testclassmore2'),
		),

	);
	
	echo html::control('table')->explode($data);
	
	//exit;
	
	$data = 
	array(
		'class1'=>'tableclass',
		array(
			'class2'=>'rowclass',
			'data'=>'Sometosay',
			array(
				'class3'=>'insubdimension',
				array('spandata'=>'Name1'),
				array('spandata'=>'Name2'),
			),
			array(
				'class3'=>'insubdimension2',
				'data'=>'Sometosay',
				array('spandata'=>'Name1'),
				array('spandata'=>'Name2'),
			)
		)
	);
	
	html::control('table2')->append(
		html::table()->appendDimension(
			html::tr()->appendDimension(
				html::td()->text('#data')->class('#class3')->appendDimension(
					html::span('#spandata')
				)
			)
		)
	);
	
	echo html::control('table2')->explode($data);


?>