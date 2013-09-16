<?php

// Reuire the main class.
require('../class.node.php');

// Alias the orginal class name to the new class name so the tests do not need
// editted to call the new class name.
class_alias('cnNode', 'html');

if (!function_exists("html")) {

	function html()
	{
		return call_user_func_array(array('html', 'tag'), func_get_args());
	}

}

if (!function_exists("create")) {

	function create()
	{
		return call_user_func_array(array('html', 'tag'), func_get_args());
	}

}

if (!function_exists("ctrl")) {

	function ctrl()
	{
		return call_user_func_array(array('html', 'control'), func_get_args());
	}

}

if (!function_exists("control")) {

	function control()
	{
		return call_user_func_array(array('html', 'control'), func_get_args());
	}

}

if (file_exists(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'html.shortcuts.php')) require('html.shortcuts.php');

class tests{
	static function is_equal_string($reference,$testCode){
		$result = unit::runCode($testCode);
		$passed = ((string) $reference === (string) $result ? true : false );
		$resultElement = html::div()->class('testunit')->addClass(($passed?"ok":"failed"))
		->append(
			html::div()->class('code')
				->append(
					html::span("Processed code: ")->class('label'),
					html::span("\r\n<pre>\r\n".htmlentities($testCode)."\r\n</pre>\r\n")->class('element')
				),
			html::div()->class('reference')
				->append(
					html::span("Correct result: ")->class('label'),
					html::span("\r\n<pre>\r\n".htmlentities($reference)."\r\n</pre>\r\n")->class('element')
				),
			html::div()->class('function')
				->append(
					html::span("Validation method: ")->class('label'),
					html::span(__FUNCTION__."\n")->class('element')
				)
		)
		->append(
			html::div()->class('result')
				->append(
					html::span("Unit result: ")->class('label'),
					html::span(($passed?"ok":"failed")."\n")->class('element')
				)
		)
		->append(
			html::hr()
		);
		if(!$passed) unit::output("Code return was unexpected result [RAW(".htmlentities($result).") => Quoted(".htmlentities($result).")]");
		unit::result_output($resultElement,$passed);
		// unit::result_output("Reference[".htmlentities($reference)."] ".__FUNCTION__." Result[".htmlentities(unit::runCode($testCode))."]",$passed);
	}
}


class unit{
	private static $chapterNumber = 0; //Init value
	private static $resultChapter = array(); //Init value
	private static $testPassed = array(); //Init value
	private static $testFailed = array(); //Init value
	private static $testCount = array(); //Init value
	private static $buffer = ""; //Init value
	private static $outputChannel = array("html","google","text","autobuilder");
	private static $outputSyntax = "html";
	private static $autobuildlog;
	//private static $outputSyntax = "google";
	//private static $outputSyntax = "text";

	static function getbuildlog(){
		return self::$autobuildlog;
	}

	static function chapter($msg){
		self::output(html::h2("\n==".(++self::$chapterNumber).". ".$msg."==\n"));
	}
	public static function runCode($code){
		$result = eval($code);
		return $result;
	}
	public static function output($string){
		self::send_output($string."\n");
	}
	public static function result_output($data,$ok=false){
		self::test_result($ok);
		if(is_object($data)){
			self::send_output($data);
		}
		else {
			self::send_output(str_pad($data,80,"·").($ok?" ==> OK":" ==> FAILED!")."\n");
		}
	}
	private static function convertto_html($string){
		// $string = str_replace("{{{","<pre>",$string);
		// $string = str_replace("}}}","</pre>",$string);
		// $string = preg_replace_callback("#{{{(.*?)}}}#is",function($m){return "<pre>".htmlentities($m[1])."</pre>";},$string);
		return $string;
	}
	private static function convertto_text($string){
		//$string = str_replace("{{{","<pre>",$string);
		//$string = str_replace("}}}","</pre>",$string);
		$string = preg_replace_callback("#\{\{\{(.*?)\}\}\}#is",function($m){return htmlentities($m[1]);},$string);
		$string = strip_tags($string);
		$string = html_entity_decode($string);
		return $string;
	}
	private static function convertto_google($string){
		return $string;
	}
	private static function convertto_autobuilder($string){
		self::$autobuildlog .= self::convertto_html($string);
		return "";
	}
	private static function send_output($string){
		if(method_exists('unit','convertto_'.self::$outputSyntax))
		$string = call_user_func_array(array('unit','convertto_'.self::$outputSyntax),array($string));
		channel::output($string);
	}
	private static function test_result($ok=false){
		if(!isset(self::$testCount[self::$chapterNumber])) self::$testCount[self::$chapterNumber] = 0;
		if(!isset(self::$testPassed[self::$chapterNumber])) self::$testPassed[self::$chapterNumber] = 0;
		if(!isset(self::$testFailed[self::$chapterNumber])) self::$testFailed[self::$chapterNumber] = 0;
		self::$testCount[self::$chapterNumber]++;
		if($ok){
			self::$testPassed[self::$chapterNumber]++;
		}
		else {
			self::$testFailed[self::$chapterNumber]++;
		}
	}
	public static function loadTests($path=".",$format_output="html",$chapterselection=false){
		$chapter=0;
		if(!in_array($format_output,self::$outputChannel)) die("Wrong output channel!");
		self::$outputSyntax = $format_output;
		if(glob($path."/*.test.unit")) foreach(glob($path."/*.test.unit") as $filename){
			if($chapterselection===false || $chapterselection!==false && ($chapter++)==$chapterselection-1){
				$testSet = self::parseTestScript($filename);
				self::runTestSet($testSet);
			}
		}
		self::overallResults();
		if(self::$outputSyntax=="autobuilder"){
			return array(
				"chapters"=>count(self::$testCount),
				"tests"=>array_sum(self::$testCount),
				"passed"=>array_sum(self::$testPassed),
				"failed"=>array_sum(self::$testFailed),
				"buildlog"=>self::getbuildlog()
			);
		}

		echo channel::get("output");
	}

	private static function overallResults(){
		//foreach(self::$testCount as $tests){$tests
		$overallResults = html::div('class','overallResults')
			->append(
				html::h4("\n====Overall results====\n")->append("\n")
			)
			->append(
				html::div("Chapters tested: ".count(self::$testCount))->append("\n"),
				html::div("Test count: ".array_sum(self::$testCount))->append("\n"),
				html::div("Tests passed: ".array_sum(self::$testPassed))->append("\n"),
				html::div("Tests failed: ".array_sum(self::$testFailed))->append("\n")
			)
			->style(
				(array_sum(self::$testFailed)?'background-color: red':'background-color: #80FF00')
			);
		//self::$testCount
		//self::$testPassed
		//self::$testFailed
		$buffer = ob_get_clean();
		self::output($overallResults);
		echo $buffer;
	}
	private static function parseTestScript($filename){
		$stream = file_get_contents($filename);
		preg_match("/(.*?)<<</is",$stream,$match);
		$chapterName = trim($match[1]);
		$chapterId = md5($chapterName);
		$testSet[$chapterId]["title"] = $chapterName;
		preg_match_all("/<<<(.*?)>>>/is",$stream,$match);
		if($match[1]) foreach($match[1] as $testId => $testData){
			$lines = explode("\n",trim($testData));
			$processor = trim(array_shift($lines));
			list($processor,$processorComment) = explode("**",$processor);
			$processor = trim($processor);
			$processorComment = trim($processorComment);
			$parts = explode("<==>",implode("\n",$lines));
			if($parts) foreach($parts as $id => $partString){$parts[$id] = trim($partString);}
			$testSet[$chapterId]["tests"][] = array(
																							'processor' => $processor,
																							'comment' => $processorComment,
																							'parts' => $parts
																							);
		}
		return $testSet;
	}
	private static function runTestSet($testSet){
		if($testSet) foreach($testSet as $testData){
			self::chapter($testData["title"]);
			if($testData["tests"]) foreach($testData["tests"] as $testSetup){
				if(method_exists('tests',$testSetup["processor"])){
					if($testSetup["comment"]) self::output(html::h3("\n===".$testSetup["comment"]."===\n"));
					call_user_func_array(array('tests',$testSetup["processor"]),$testSetup["parts"]);
				}
			}
			unit::chapter_results();
		}
	}
	public static function chapter_results(){
		$resultElement = html::div()->class('chapter results')
			->append(html::h4("\n====Chapter results====\n"))->append("\n")
			->append(html::div()
				->append(html::span("Tests: ")->class('label'))
				->append(html::span((int) self::$testCount[self::$chapterNumber]))
				->append("\n")
			)
			->append(html::div()
				->append(html::span("Passed: ")->class('label'))
				->append(html::span((int) self::$testPassed[self::$chapterNumber]))
				->append("\n")
			)
			->append(html::div()
				->append(html::span("Failed: ")->class('label'))
				->append(html::span((int) self::$testFailed[self::$chapterNumber]))
				->append("\n")
			)
			->append(html::hr(),html::hr());

		self::output($resultElement);
		//self::output("Results => Tested: ".(int) self::$testCount[self::$chapterNumber]." - Passed: ".(int) self::$testPassed[self::$chapterNumber]." - Failed: ".(int) self::$testFailed[self::$chapterNumber]."\n");
	}
}

class channel{
	private static $stream = array();

	public static function __callStatic($name,$attributes){
		if(!isset(self::$stream[$name])) self::$stream[$name] = "";
		self::$stream[$name] .= implode("\n",$attributes);
	}
	public static function get($name){
		if(!isset(self::$stream[$name])) return "";
		return self::$stream[$name];
	}
}

?>