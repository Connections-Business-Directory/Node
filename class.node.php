<?php
/**
 * phpCreateHtml - This is a simple but powerful way to create
 * html tags from php similar to the way you create html with jQuery
 *
 * http://code.google.com/p/phpcreatehtml/
 *
 * @author Lars Echterhoff <lars@echterhoff-medientechnik.de>
 * @license GNU GPLv2
 * @version 0.5
 * @copyright Lars Echterhoff 2012
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation in version 2 of the License.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

class cnNode {

	public $element;
	public $elements;

	/**
	 * This is an array of objects of (self).
	 *
	 * @access public
	 * @var (array)
	 */
	public static $tag;

	/**
	 * Self closing tags.
	 *
	 * @access public
	 * @var (array)
	 */
	public  static $singleTags             = array( 'area', 'base', 'br', 'col', 'hr', 'img', 'input', 'link', 'meta', 'param' );

	public  static $uncloseTags            = array( 'link', 'meta' );
	public  static $debug                  = false;
	private static $magicSpecialIdentifier = "__special__";
	private static $magicSpecialMethods;
	private static $aliasCommandTable = array();
	private static $tagStore;
	private static $variable = array();
	public  static $event;

	public function __construct() {

	}

	/**
	 * Outputs a debug message.
	 *
	 * @access public
	 * @param  (string) $function [description]
	 * @param  (string) $string   [description]
	 * @return (string)           Debug message.
	 */
	public static function debug( $function = "", $string = "" ) {

		if ( ! cnNode::$debug ) return;

		echo $function . ": " . trim ( $string ) . "\n";
	}

	/**
	 * Seems to be an unfinished method that doesn't do anything.
	 *
	 * @access public
	 */
	public static function clean() {

		self::$form = NULL;
	}

	/**
	 * Seems to be an unfinished method that doesn't do anything.
	 *
	 * @access public
	 * @param (string) $name
	 * @return (void)
	 */
	public static function delete( $name ) {

		$name = self::getOrigin( $name );

		if ( isset( self::$form[ $name ] ) ) unset( self::$form[ $name ] );
	}

	/**
	 *
	 * @access public
	 * @return html
	 */
	static public function notag() {

		return self::tag()->html( func_get_args() );
	}

	/**
	 * Create a node, optionally, with attributes and content.
	 *
	 * NOTES: Method takes n-number of params.
	 * - The first param will be the node type.
	 * - If a second param is supplied, that will be the node content; <node>param-2</node>
	 * - If a third param is supplied, the output will be <node param-2="param-3">/node>
	 * - If a forth param is supplied, the output will be <node param-3="param-4">param-2</node>
	 * - If an even number of params are supplied, param-2 will always be the node content.
	 *   The remaining params pairs will be added as node attributes where the first will be
	 *   the attribute name and the second will be the attribute value.
	 * - If an odd number of params are supplied, each param pair will be added as node attributes
	 *   where the first will be the attribute name and the second will be the attribute value.
	 *
	 * Example 1:
	 * <code>
	 * html::tag( 'div' );
	 * </code>
	 *
	 * Result:
	 * <code>
	 * <div></div>
	 * </code>
	 *
	 * Example 2:
	 * <code>
	 * html::tag( 'div', 'Container content.' );
	 * </code>
	 *
	 * Result:
	 * <code>
	 * <div>Container content.</div>
	 * </code>
	 *
	 * Example 3:
	 * <code>
	 * html::tag( 'div', 'class', 'container' );
	 * </code>
	 *
	 * Result:
	 * <code>
	 * <div class="container"></div>
	 * </code>
	 *
	 * Example 4:
	 * <code>
	 * html::tag( 'div', 'Container content.', 'class', 'container' );
	 * </code>
	 *
	 * Result:
	 * <code>
	 * <div class="container">Container content.</div
	 * </code>
	 *
	 * @todo Rename method to: node
	 * @todo Rename _createTag to: createNode
	 * @todo Rename $args to: $params
	 *
	 * @access public
	 * @param (string) Node to create
	 * @return (string)
	 */
	public static function tag() {

		$args = self::__prepareArguments( func_get_args() );
		$tag  = self::_createTag( $args["nodename"] );

		if ( isset( $args["attributes"] ) ) {

			foreach ( $args["attributes"] as $id => $attr ) {

				if ( $attr["type"] === "attribute" ) {

					$attribute = $attr["key"];
					if ( ! $attribute ) $attribute = "+";
					if ( is_numeric( $attribute ) ) $attribute = "+".$attribute;
					$tag->{ $attribute }( (string) $attr["data"] );

				} elseif ( $attr["type"] === "append" ) {

					if ( ! is_object( $attr["data"] ) ) $tag->append( (string) $attr["data"] );
					else $tag->append( $attr["data"] );
				}
			}
		}

		return $tag;
	}

	/**
	 *
	 *
	 * NOTE: This method is called only by: self::tag()
	 *
	 * Example of array returned by <code>html::div('Text in a container.', 'class','container');</code>
	 * array(2) {
	 *  ["nodename"]=> string(3) "div"
	 *  ["attributes"]=> array(2) {
	 *      [0]=> array(2) {
	 *          ["type"]=>
	 *          string(6) "append"
	 *          ["data"]=>
	 *          string(20) "Text in a container."
	 *          }
	 *      [1]=> array(3) {
	 *          ["type"]=>
	 *          string(9) "attribute"
	 *          ["key"]=>
	 *          string(5) "class"
	 *          ["data"]=>
	 *          string(9) "container"
	 *          }
	 *      }
	 *  }
	 *
	 * @access private
	 * @param (array) $argData
	 * @return (array)
	 */
	private static function __prepareArguments( array $argData ) {

		$argData = self::__flattenArguments( $argData );
		$argData = self::__reorderArguments( $argData );
		$argData = self::__argumentGuesser( $argData );

		return $argData;
	}

	/**
	 * [__reorderArguments description]
	 * @param  [type] $argData [description]
	 * @return (array)         [description]
	 */
	private static function __reorderArguments( $argData ) {

		for ( $tc = 2; $tc >= 1; $tc-- ) {

			if ( $argData && count( $argData ) === ( $tc + 1 ) && strpos( $argData[ $tc ], "=" ) !== FALSE ) {

				$equal  = substr_count( $argData[ $tc ], "='" ) + substr_count( $argData[ $tc ], '="' );
				$sepGap = substr_count( $argData[ $tc ], "' " ) + substr_count( $argData[ $tc ], '" ' );
				$quote  = substr_count( $argData[ $tc ], "'" )  + substr_count( $argData[ $tc ], '"' );

				if ( $equal === 1 && $sepGap === 0 && $quote === 2 || $equal > 1 && $equal === ( $sepGap * 2 ) && $quote === ( $equal * 2 ) ) {

					$attr = parse::__attribute_tokenizer( $argData[ $tc ] );

					if ( $tc === 1 ) {

						return array_merge( array( $argData[0], '' ), $attr );

					} elseif ( $tc === 2 ) {

						return array_merge( array( $argData[0], $argData[1] ), $attr);
					}
				}
			}
		}

		return $argData;
	}

	/**
	 * [__argumentGuesser description]
	 *
	 * @access private
	 * @param  (array)  $argData
	 * @return (array)
	 */
	private static function __argumentGuesser( array $argData ) {

		$typ                   = '';
		$typSig                = '';
		$descArray             = array();
		$skip                  = 0;
		$skipTo                = 0;
		$even                  = FALSE;
		$sCount                = 0;
		$p                     = 0;
		$nodeElement           = array_shift( $argData );
		$descArray['nodename'] = $nodeElement;

		foreach ( $argData as $key => $val ) {

			$typ .= ( is_object( $val ) === TRUE ? "O" : "S");
		}

		if ( $typ ) $descArray['attributes'] = array();

		if ( $typ ) foreach ( $argData as $key => $val ) {

			if ( $skip !== 0 ) {

				$skipTo+=$skip;
				$skip = 0;
			}

			if ( $skipTo === $key ) {

				$sCount = substr_count( $typ, "S" );
				$even   = ! ( $sCount % 2 );

				if ( $key === 0 && ( $typLen = strlen( $typ ) ) >= 3 ) {

					$typSig = $typ[ $p ].$typ[ $p + 1 ].$typ[ $p + 2 ];

					if ( $skip === 0 && $typSig === "SSS" ) {

						if ( $typLen === 3 ) {

							$descArray["attributes"][] = array("type" => "append", "data" => $argData[$key]);
							$descArray["attributes"][] = array("type" => "attribute", "key" => $argData[$key + 1], "data" => $argData[$key + 2]);
							$skip = 3;

						} elseif ( $even === TRUE ) { /* I $typLen>3 */

							$descArray["attributes"][] = array("type" => "attribute", "key" => $argData[$key], "data" => $argData[$key + 1]);
							$skip = 2;

						} elseif ( $even === FALSE ) { /* I $typLen>3 */

							$descArray["attributes"][] = array("type" => "append", "data" => $argData[$key]);
							$descArray["attributes"][] = array("type" => "attribute", "key" => $argData[$key + 1], "data" => $argData[$key + 2]);
							$skip = 3;
						}

					} elseif ($skip === 0 && $typSig === "SSO") {

						if ($even === true) {

							$descArray["attributes"][] = array("type" => "attribute", "key" => $argData[$key], "data" => $argData[$key + 1]);
							$skip = 2;

						} else { /* I $even===false */

							$descArray["attributes"][] = array("type" => "append", "data" => $argData[$key]);
							$descArray["attributes"][] = array("type" => "attribute", "key" => $argData[$key + 1], "data" => $argData[$key + 2]);
							$skip = 3;
						}

					} elseif ($skip === 0 && ($typSig === "SOS" || $typSig === "OOO")) {

						if ($typLen === 3) { /* I $even===true */

							$descArray["attributes"][] = array("type" => "append", "data" => $argData[$key]);
							$descArray["attributes"][] = array("type" => "append", "data" => $argData[$key + 1]);
							$descArray["attributes"][] = array("type" => "append", "data" => $argData[$key + 2]);
							$skip = 3;

						} elseif ($typLen >= 3) {

							$descArray["attributes"][] = array("type" => "append", "data" => $argData[$key]);
							$descArray["attributes"][] = array("type" => "append", "data" => $argData[$key + 1]);
							$skip = 2;
						}

					} elseif ($skip === 0 && ($typSig === "SOO" || $typSig === "OSO")) {

						$descArray["attributes"][] = array("type" => "append", "data" => $argData[$p]);
						$skip = 1;

					} elseif ($skip === 0 && $typSig === "OOS") {

						$descArray["attributes"][] = array("type" => "append", "data" => $argData[$p]);
						$descArray["attributes"][] = array("type" => "append", "data" => $argData[$p + 1]);
						$skip = 2;

					} elseif ($skip === 0 && $typSig === "OSS") {

						if ($typLen === 3) {

							$descArray["attributes"][] = array("type" => "append", "data" => $argData[$key]);
							$skip = 1;

						} elseif ($typLen > 3) {

							$descArray["attributes"][] = array("type" => "append", "data" => $argData[$key]);
							$descArray["attributes"][] = array("type" => "attribute", "key" => $argData[$key + 1], "data" => $argData[$key + 2]);
							$skip = 3;
						}

					}

				} elseif ($skip === 0 && $typ && strlen($typ) >= 2) {

					$typSig = $typ[$p].$typ[$p + 1];

					if ($skip === 0 && $typSig === "SS") {

						$descArray["attributes"][] = array("type" => "attribute", "key" => $argData[$key], "data" => $argData[$key + 1]);
						$skip = 2;

					} elseif ($skip === 0 && $typSig === "SO") {

						$descArray["attributes"][] = array("type" => "append", "data" => $argData[$key]);
						$descArray["attributes"][] = array("type" => "append", "data" => $argData[$key + 1]);
						$skip = 2;

					} elseif ($skip === 0 && $typSig === "OO") {

						$descArray["attributes"][] = array("type" => "append", "data" => $argData[$key]);
						$descArray["attributes"][] = array("type" => "append", "data" => $argData[$key + 1]);
						$skip = 2;

					} elseif ($skip === 0 && $typSig === "OS") {

						$descArray["attributes"][] = array("type" => "append", "data" => $argData[$key]);
						$skip = 1;
					}

				} elseif ($skip === 0) { /* I L===1 */

					$typSig = $typ[$p];

					if ($skip === 0/* && ($typSig==="S" || $typSig==="O") */) {

						$descArray["attributes"][] = array("type" => "append", "data" => $argData[$key]);
						$skip = 1;
					}
				}

				if ($skip !== 0) {

					$typ = substr($typ, $skip);
					$p = 0;
				}
			}
		}

		return $descArray;
	}

	/**
	 *
	 * This method is called only by: self::__prepareArguments()
	 *
	 * @todo Return $argOut instead of resetting $argData and returning it.
	 * @todo Clean up the if statement so it is more easily readable.
	 *
	 * @access private
	 * @param (array) $argData
	 * @return (array)
	 */
	private static function __flattenArguments( array $argData ) {

		$argOut = array();

		foreach ($argData as $key => $val) {

			//if(is_numeric($key) && is_string($val) && parse::__has_attributes($val)===true){$argOut=array_merge($argOut,parse::__attribute_tokenizer($val));}
			if (is_numeric($key) && !is_array($val)) $argOut[] = $val;
			elseif (!is_numeric($key)) {
				$argOut[] = $key;
				$argOut[] = $val;

			} elseif (is_numeric($key) && is_array($val)) {

				$argOut = array_merge($argOut, self::__flattenArguments($val));
			}
		}

		$argData = array();
		$argData = $argOut;

		return $argData;
	}

	/**
	 *
	 * This method is called only by: self::tag()
	 *
	 * @todo Rename to: createNode
	 * @todo Rename param to: $name
	 *
	 * @access private
	 * @param (string) $nodename
	 * @return (object)
	 */
	private static function _createTag($nodename = false) {

		$id = uniqid();
		if (!isset(self::$tag[$id])) self::$tag[$id] = new self();
		self::$tag[$id]->setElement($id);
		cnNode::debug(__METHOD__, "Create node from $nodename");

		if (!self::$magicSpecialMethods) {
			self::__selfInvestigate();
		}
		if (isset($nodename) && !is_object($nodename) && isset(self::$magicSpecialMethods[$nodename])) {
			$callOperation = self::$magicSpecialIdentifier.$nodename;
			//Special Tags always start with namespace "special"
			return self::$callOperation($id, "special:".$nodename);
		}
		return self::$tag[$id]->_createNewTag($nodename);
	}

	/**
	 *
	 * @return boolean
	 */
	private static function __selfInvestigate()
	{
		$self = new ReflectionClass(__CLASS__);
		foreach ($self->getMethods() as $id => $methodData)
		{
			// cnNode::debug(__METHOD__, "Check registered methods $methodData");
			if (strpos($methodData->name, self::$magicSpecialIdentifier) !== false) {
				self::$magicSpecialMethods[substr($methodData->name, strlen(self::$magicSpecialIdentifier))] = $methodData->name;
			}
		}
		return true;
	}

	/**
	 *
	 * @access private
	 * @param (string) $function
	 * @return (string)
	 */
	private static function __createSpecialName($function) {

		$name   = substr( $function, strlen( self::$magicSpecialIdentifier ) );
		$prefix = strtolower( str_replace( "_", '', self::$magicSpecialIdentifier ) );

		return $prefix . ':' . $name;
	}

	/**
	 * Definition of special commands.
	 *
	 * @access private
	 * @param [type] $command
	 * @param [type] $alias
	 * @return (boolean)
	 */
	private static function __special__alias( $command, $alias ) {

		self::$aliasCommandTable[ $alias ] = $command;

		return TRUE;
	}

	/**
	 * Special function to check a bunch of variables if there is any empty variable before creating a node.
	 *
	 * @access private
	 * @return (html)
	 */
	private static function __special__ifNotAnyEmpty() {

		$args = func_get_args();

		if ( $args ) {

			foreach ($args as $arg) {

				if ( empty( $arg ) ) {

//                    return call_user_func_array(array('html','tag'), array('[nooutput]'));
					return cnNode::tag('[nooutput]');
//                    return cnNode::{'[nooutput]'}();
				}
			}
		}

//        return cnNode::{'__special__treat_next_attribute_as_nodename'}()->{'*incheckifnotanyemptymode'}(true);
		$tag = self::tag(self::__createSpecialName(__FUNCTION__));
		$tag->{'_name'}($args);
		$tag->setMode(__FUNCTION__);

		return $tag;
	}

	/**
	 * Special function to check a bunch of variables if they are all empty before creating a node.
	 *
	 * @access private
	 * @return (html)
	 */
	private static function __special__ifNotAllEmpty() {

		$args = func_get_args();

		if ( $args ) {

			for ( $x = 1; $x <= 1; $x++ ) {

				foreach ($args as $arg) {

					if ( ! empty( $arg ) ) {

						break(2);

					} else {

//                    return call_user_func_array(array('html','tag'), array('[nooutput]'));
						$newNodeInCharge = cnNode::tag('[nooutput]');
//                    return cnNode::{'[nooutput]'}();
//                        $newNodeInCharge = cnNode::{'[nooutput]'}();
					}
				}

				return $newNodeInCharge;
			}
		}
//        return cnNode::{'__special__treat_next_attribute_as_nodename'}()->{'*incheckifnotallemptymode'}(true);
		$tag = self::tag( self::__createSpecialName( __FUNCTION__ ) );
		$tag->{'_name'}( $args );
		$tag->setMode( __FUNCTION__ );

		return $tag;
	}

	/**
	 *
	 * @access private
	 * @param type $on
	 * @param type $function
	 * @param type $callback
	 * @param type $ownerid
	 */
	private static function __registerEvent( $on, $function, $callback, $ownerid ) {

		self::$event[ $function ][ $on ][ $ownerid ] = $callback;
	}

	/**
	 *
	 * @access private
	 * @param string $name
	 * @param string $var
	 */
	private static function __registerState( $name, $var = NULL ) {

		self::$variable [$name ] = $var;
	}

	/**
	 *
	 * @access public
	 * @param (string) $name
	 * @return (string)
	 */
	public static function __getState( $name ) {

		if ( isset( self::$variable[ $name ] ) ) return self::$variable[ $name ];
	}

	/**
	 * [__hasEvent description]
	 *
	 * @access public
	 * @param  [type] $on       [description]
	 * @param  [type] $function [description]
	 * @param  [type] $object   [description]
	 * @return [type]           [description]
	 */
	public static function __hasEvent( $on, $function, &$object ) {

		if ( isset( self::$event[ $function ] [$on ]) === TRUE ) {

			foreach ( self::$event[ $function ][ $on ] as $ownerid => $callback )
				$callback($object);
		}
	}

	/**
	 * [__special__setNamespace description]
	 *
	 * @note This method will require PHP >= 5.3
	 * @note Doesn't appear that this function is used.
	 *
	 * @access private
	 * @param  (bool) $ns Namespace
	 * @return [type]      [description]
	 */
	private static function __special__setNamespace( $ns = FALSE ) {

		self::__registerState( 'namespace', $ns );

		$callback = function( &$object ) {

					$ns = cnNode::__getState('namespace');
					if ( $object->nodename && $ns && strpos( $object->nodename, ':' ) === FALSE ) $object->{'&__namespace'} = $ns;
				};

		self::__registerEvent( 'on', '__toString', $callback, __FUNCTION__ );
	}

	/**
	 * [__special__control description]
	 *
	 * @access private
	 * @param  [type] $args [description]
	 * @return [type]       [description]
	 */
	private static function __special__control( $args ) {

		if (isset(self::$tagStore["control"][$args])) {

			if (isset(self::$tagStore["control"][$args]->{'*registeredevents'})) {

				$events = self::$tagStore["control"][$args]->{'*registeredevents'};
				unset(self::$tagStore["control"][$args]->{'*registeredevents'});
			}

			$clone = unserialize(serialize(self::$tagStore["control"][$args]));

			if (isset($events)) {

				self::$tagStore["control"][$args]->{'*registeredevents'} = $events;
				$clone->{'*registeredevents'}($events);
			}

			return $clone;
		}

		$tag = self::tag(self::__createSpecialName(__FUNCTION__));
		$tag->{'_name'}($args);
		//This tag is in replacemode means. After restoring it, it should replace given values and attributes instead of create attributes
		$tag->inReplaceMode(true);
		self::$tagStore["control"][$args] = $tag;

		return $tag;
	}

	//End definition

	/**
	 * [setElement description]
	 *
	 * @access public
	 * @param [type] $id [description]
	 */
	final public function setElement( $id ) {

		$this->element = $id;
	}

	/**
	 * Magic method statically called to create object node.
	 *
	 * @access public
	 * @param  (string) $name The node tag.
	 * @param  (mixed) $args [array | string]
	 * @return [type]       [description]
	 */
	public static function __callStatic($name, $args) {

		cnNode::debug( __METHOD__, "$name" );
		cnNode::commandAlias( $name );

		if ( ! self::$magicSpecialMethods ) {

			self::__selfInvestigate();
		}

		if (isset(self::$magicSpecialMethods[$name])) {
			$name = self::$magicSpecialMethods[$name];
			cnNode::debug(__METHOD__, "Call function $name");
			return call_user_func_array(array(__CLASS__, $name), $args);
		}

		if (isset($args)) return cnNode::tag($name, $args);
		return cnNode::tag($name);
	}

	/**
	 * [__call description]
	 *
	 * @access public
	 * @param  (string) $method The method name to call.
	 * @param  (array)  $args   [description]
	 * @return [type]         [description]
	 */
	public function __call( $method, $args ) {

		cnNode::debug( __METHOD__, "$method" );

		$this->elements[ $this->element ] = new cnNode_Attribute;
		$this->elements[ $this->element ]->__parentElement( $this->elements[ $this->element ] );

		return $this->elements[ $this->element ]->$method( $args[0] );
	}

	/**
	 * [commandIsAlias description]
	 *
	 * @access private
	 * @param  (string) $name
	 * @return (bool)
	 */
	private static function commandIsAlias( $name ) {

		if ( isset( self::$aliasCommandTable[ $name ] ) ) return TRUE;

		return FALSE;
	}

	/**
	 * [commandAlias description]
	 *
	 * @access public
	 * @param  [type] $name [description]
	 * @return (bool)
	 */
	public static function commandAlias( &$name ) {

		if ( ! cnNode::commandIsAlias( $name ) ) return FALSE;

		$alias = self::$aliasCommandTable[ $name ];
		$name  = $alias;

		return TRUE;
	}

	/**
	 * [__array_depth description]
	 *
	 * @access public
	 * @param  (array)  $a
	 * @return (int)
	 */
	public static function __array_depth( array $a ) {

		$m = 0;
		$x = explode(',', json_encode( $a, JSON_FORCE_OBJECT ) . "\n\n" );

		foreach ( $x as $r )
			$m = ( substr_count( $r, ':' ) > $m ) ? substr_count( $r, ':' ) : $m;

		return $m;
	}

}

class cnNode_Attribute
{
	private $tag;

	function _createNewTag($string)
	{
		if ($this->__braceCheck($string) === false) {
			$tag = $this->_nodename($string);
			return $tag;
		} else {
			//R:$tag = $this->_parseFromString($string);
			$this->_parseFromString($string);
			//R:if( is_array($tag->_content) && count($tag->_content) == 1 && is_object($tag->_content[0]) ) return $tag->_content[0];
			if (isset($this->_content) && is_array($this->_content) && count($this->_content) == 1 && is_object($this->_content[0])) return $this->_content[0];
			//R:return $tag;
			return $this->tag();
		}
	}

	public function inReplaceMode($state = true)
	{
		$this->{"*inreplacemode"} = $state;
		return $this->tag();
	}

	public function setMode($name, $state = true)
	{
		$this->{"*".$name} = $state;
		return $this->tag();
	}

	public function appendTo(cnNode_Attribute $object)
	{
		$element = $object->tag();
		$element->addhtml($this->tag());
		return $this->tag();
	}

	public function appendToEmpty(cnNode_Attribute $object)
	{
		return $this->appendTo($object->empty());
	}

	public function on($attribute, $callback)
	{
		$this->__registerCallbackEvent($attribute, $callback);
		return $this->tag();
	}

	private function __registerCallbackEvent($attribute, $callback)
	{
		$this->{'*registeredevents'}(array("event" => $attribute, "closure" => $callback));
		return $this->tag();
	}

	public function appendDimension()
	{
		cnNode::debug(__FUNCTION__, "Append Dimension");
		$this->{'*newdimension'}(true);
		return call_user_func_array(array($this, "append"), func_get_args());
	}

	/**
	 *
	 * @return html_attribute
	 */
	public function append()
	{
		if (func_get_args()) foreach (func_get_args() as $object)
			{
				$element = $this->tag();
				if ($object instanceOf cnNode_Attribute) {
					$element->addhtml($object->tag());
				} else {
					$element->addhtml($object);
				}
			}
		return $this->tag();
	}

	/**
	 * This function will end any chainability and return the html as string before it free up the memory.
	 * I implemented this function for those how create fragments in a loop.
	 * @return string
	 */
	public function pushToString()
	{
		$out = ''.$this;
		$this->__dropFromMemory();
//        $this->tag = null;
//        array_walk_recursive($this->_content, function($item, $key)
//                {
//                    $item->__dropFromMemory();
//                });
//        $this->_content = null;
//        gc_collect_cycles();
//        print_r($this);
		return $out;
	}

	public function __dropFromMemory()
	{
		unset($this->tag);
		if (isset($this->_content) && is_array($this->_content)) {
			foreach ($this->_content as $sub_element)
			{
				if($sub_element instanceOf cnNode_Attribute){
					$sub_element->__dropFromMemory();
				}
			}
		}
		if(isset($this->_content)){
			unset($this->_content);
		}
		unset($this->nodename);
	}

	function __parentElement(cnNode_Attribute $object)
	{
		$this->tag = $object;
	}

	function removeReferrence()
	{
		$this->tag = null;
	}

	function _intoTag($string)
	{
		if ($this->__braceCheck($string) === false) {
			return $this->html($string);
		} else {
			return $this->_parseFromString($string);
		}
	}

	function _nodename($value)
	{
		return $this->setElementAttribute("nodename", $value);
	}

	function _parseFromString($string)
	{
		$this->tag()->__parse($string);

		return $this->tag();
	}

	private function __parse($string)
	{
		parse::html($string, $this);
	}

	private function __braceCheck($string)
	{
		$string = trim($string);
		if (strpos($string, "<") !== false && strpos($string, ">") !== false) {
			return true;
		}
		else return false;
	}

	/**
	 *
	 * @param type $attribute
	 * @param type $value
	 * @return \html_attribute
	 */
	function __call($attribute, $value)
	{
		if ($attribute == "*registeredevents" && isset($this->{'*registeredevents'}) && is_array($value[0]) && isset($value[0]["event"])) {
			$closureConstruct = array($value[0]["event"] => $value[0]["closure"]);
			$this->$attribute = array_merge($this->$attribute, $closureConstruct);
			return $this->tag();
		}
		if (isset($this->{'*registeredevents'}[$attribute])) {
			$callback = $this->{'*registeredevents'}[$attribute];
			$callback($this->tag);
		}
		if ($attribute == "explode" && is_array($value) && $this->tag()->nodename != "special:control") {
			if (strpos(json_encode($this, JSON_FORCE_OBJECT), '#') !== false) {
				$pseudoWrap = cnNode::control('internalCreatedGenericControlElement'.uniqid())->append($this);
				return call_user_func_array(array($pseudoWrap, 'explode'), $value);
			} else {
				return $this;
			}
		}

		cnNode::debug(__METHOD__, "$attribute");
		if (cnNode::commandAlias($attribute)) {
			return call_user_func_array(array($this, $attribute), $value);
		}
		return $this->setElementAttribute($attribute, $value);
	}

	function __set($attribute, $value)
	{
		cnNode::debug(__METHOD__, "$attribute");
		cnNode::commandAlias($attribute);
		if (is_array($value) && isset($value["event"]) && isset($value["closure"]) && $value["closure"] instanceOf Closure) {
			$closureConstruct = array($value["event"] => $value["closure"]);
			if (isset($this->{$attribute}) && is_array($this->{$attribute})) {
				$this->$attribute = array_merge($this->$attribute, $closureConstruct);
			} else {
				$this->$attribute = array();
				$this->$attribute = array_merge($this->$attribute, $closureConstruct);
			}
			return $this->tag();
		}
		if ($this->parseAttr($attribute)) {
			if (is_string($value)) {
				$this->tag()->addtext($value);
			}
			if (is_object($value)) {
				$this->tag()->append($value);
			}
			return $this->tag();
		}
		if (!isset($value)) $value = "";
		if (!is_array($value) && !is_object($value)) cnNode::debug(__METHOD__, $attribute." = ".gettype($value)."(".$value.")");
		$attribute = strtolower($attribute);
		foreach (array("add", "remove") as $pre)
		{
			if (stripos($attribute, $pre) === 0) {
				$attribute = substr($attribute, strlen("add"));
				break;
			}
			$pre = "set";
		}
		$createdElement = $this->{$pre."ElementAttribute"}($attribute, $value);
		return $createdElement;
	}

	private function addElementAttribute($attribute, $value)
	{
		$attrName = null;
		if (isset($this->$attribute)) cnNode::debug(__METHOD__, "Attribute \$attrName==\"".$attribute."\" to append is: ".gettype($this->$attribute)."(".$attribute.")");
		cnNode::debug(__METHOD__, "Value to append is: ".gettype($value)."(".$value.")");

		if ($attribute == "html" || $attribute == "text") {
			$attrName = $attribute = "_content";
		}

		if (( $attrName == "cdata" && is_string($value)) || ( $attrName == "cdata" && is_array($value) && implode("", $value) === "" )) {
			if (is_array($value)) $value = "";

			$value = "<![CDATA[".$value."]]>";
			$attrName = $attribute = "_content";
		}

		$attrName = $attribute;
		if (isset($this->$attribute)) $attrType = gettype($this->$attribute);
		else $attrType = "string";
		$addType = gettype($value);

		if (is_object($value)) {
			$this->__pushToArray($attribute, $value);
		} elseif ($addType == "string" && $attrType == "array" && $attrName == "_content") {
			cnNode::debug(__METHOD__, "Push STRING to _content ARRAYPUSH");
			$this->__pushToArray($attribute, cnNode::tag()->{$attribute}($value));
		} elseif ($addType == "string" && $attrType == "string") {
			cnNode::debug(__METHOD__, "Append STRING to _content String");
			if (!isset($this->$attribute)) $this->$attribute = "";
			$this->$attribute .= ($this->$attribute && $value[strlen($value) - 1] != ">" && $attrName != "_content" ? " " : "").$value;
		}
		elseif ($attrType == "NULL" && $addType == "string") {
			cnNode::debug(__METHOD__, "Overwrite STRING in _content");
			$this->$attribute = $value;
		} else {
			trigger_error("Unhandled operation $attrName $attrType $addType", E_USER_ERROR);
		}
		return $this->tag();
	}

	private function __pushToArray($attribute, $element)
	{
		if (!isset($this->$attribute)) $this->$attribute = array();
		if (!is_array($this->$attribute)) $this->$attribute = array(cnNode::tag()->text($this->$attribute));
		array_push($this->$attribute, $element);
	}

	private function __contentReplace($search, $replace)
	{
		$currentObject = new ArrayIterator($this);

		foreach ($currentObject as $key => $val)
		{
			if ($key[0] != "*" && $key[0] != "&" && !is_array($val) && strpos($val, "#".$search) !== false) {
				if ($replace instanceOf cnNode_Attribute) {
					$this->empty()->append($replace);
				} else {
					$this->$key = str_replace("#".$search, $replace, $this->$key);
				}
			} elseif ($key[0] != "*" && $key[0] != "&" && is_array($val)) {
				foreach ($val as $subObjects)
				{
					$subObjects->__contentReplace($search, $replace);
				}
			}
		}
	}

	private function __contentRepeatReplace($search, $replaceArray, $parent = false)
	{

		$currentObject = new ArrayIterator($this);
		foreach ($currentObject as $key => $val)
		{
			if (!is_array($val) && strpos($val, "#".$search) !== false) {
				$masterNode = unserialize(serialize($this));
				if ($parent instanceOf cnNode_Attribute) $parent->empty();
				foreach ($replaceArray as $replaceId => $replaceData)
				{
					$copyNode = unserialize(serialize($masterNode));
					$parent->append($copyNode);
					$copyNode->$key = str_replace("#".$search, $replaceData, $copyNode->$key);
				}
			} elseif ($key[0] != "*" && $key[0] != "&" && is_array($val)) {
				foreach ($val as $subObjects)
				{
					$subObjects->__contentRepeatReplace($search, $replaceArray, $this);
				}
			}
		}
	}

	private static function is_assoc(array $a)
	{
		return (count(array_filter(array_keys($a), 'is_string')) == count($a));
	}

	private static function is_index(array $a)
	{
		return (count(array_filter(array_keys($a), 'is_numeric')) >= 1 ? true : false);
	}

	private function __collectArrayInformation(array $array)
	{
		$this->__investigateArray($array, $dim = 0, $info);
		$return["dimension"] = 0;
		$return["datalayer"] = 0;
		if ($info) foreach ($info as $dim => $data)
			{
				if (!$data["is_assoc"]) {
					$return["dimension"]++;
				} else {
					$return["datalayer"]++;
				}
				if (isset($data["itemcount"]) && $data["itemcount"]) $return["itemcount"] = $data["itemcount"];
				if ($data["is_dimension"]) $return["is_dimension"][$dim] = true;
			}
		return $return;
	}

	private function __investigateArray(array $a, $dim = 0, &$info = array())
	{
		$dim++;
		cnNode::__array_depth($a);
		$info[$dim]["is_assoc"] = self::is_assoc($a);
		$info[$dim]["is_dimension"] = self::is_index($a);
		if ($info[$dim]["is_assoc"]) {
			$info[$dim]["itemcount"] = count($a);
		}
		if ($info[$dim]["is_dimension"]) {
			$info[$dim]["is_dimension"] = true;
		}

		if (is_array($next = $this->__getNextDimensionArray($a))) {
			$this->__investigateArray($next, $dim, $info);
		}
		return true;
	}

	private function __getNextDimensionArray($array)
	{
		if (!is_array($array)) return false;
		foreach ($array as $key => $value)
		{
			if (is_numeric($key)) return $value;
		}
		return false;
	}

	private function __countDimensions(&$count, &$dimensionObjects, &$onlyNext)
	{
		if (isset($this->{'*newdimension'}) && $this->{'*newdimension'} === true) {
			$count++;
			$dimensionObjects[$count] = $this;
			if ($onlyNext) {
				return true;
			}
		}
		if (isset($this->_content) && is_array($this->_content)) {
			foreach ($this->_content as $sub)
			{
				$sub->__countDimensions($count, $dimensionObjects, $onlyNext);
			}
		}
		return true;
	}

	private function __getDimensions($onlyNext = false)
	{
		$this->__countDimensions($count, $objectsHoldingDimensions, $onlyNext);
		$return["dimension"] = $count;
		$return["references"] = $objectsHoldingDimensions;
		if ($onlyNext && count($objectsHoldingDimensions)) {
			return current($objectsHoldingDimensions);
		}
		return $return;
	}

	private function __getDimensionContainerRecurse(&$depth, &$return)
	{
		if (isset($this->_content) && is_array($this->_content)) {
			foreach ($this->_content as $sub)
			{
				$sub->__getDimensionContainerRecurse($depth, $return);
			}
		}
		if (isset($this->{'*newdimension'}) && $this->{'*newdimension'} === true) {
			$depth++;
			$return[$depth] = $this->_content;
			$this->_content = "#__appenddimensiondata";
		}
		return true;
	}

	private function __getDimensionContainer()
	{
		$depth = 0;
		$return = array();
		$extractFrom = $this->__copyObject($this);
		$extractFrom->__getDimensionContainerRecurse($depth, $return);
		$return = array_reverse($return);
		array_unshift($return, array());
		unset($return[0]);
		if ($return) foreach ($return as $level => $dataObjects)
			{
				$dimTag = cnNode::tag();
				if ($dataObjects) foreach ($dataObjects as $doId => $dataObject)
					{
						$dimTag->append($dataObject);
					}
				$return[$level] = $dimTag;
			}
		return $return;
	}

	private function __extendDimensions(array $data, $dimensionInfo)
	{
		$currentDimension = 1;
		$return = array();
		return $this->__createDimensionFromPattern($data, $dimensionInfo, $this, $currentDimension, $return);
	}

	private function __createDimensionFromPattern(array $dimensionData, $dimensionInfo, &$rootObject, &$currentDimension, &$return)
	{
		$dimensionDatasets = $this->__getNumericArrayData($dimensionData);
		$dimensionVariables = $this->__getStringArrayData($dimensionData);
		if ($dimensionVariables) foreach ($dimensionVariables as $searchKey => $replaceData)
			{
				$this->__contentReplace($searchKey, $replaceData);
			}

		if ($currentDimension == 1) $rootObject->{'&__dimensionobjects'}[$currentDimension]->empty();

		$patternCollection = cnNode::tag();
		if ($dimensionDatasets) foreach ($dimensionDatasets as $dataID => $data)
			{
				$pattern = $this->__copyObject($rootObject->{'&__dimensionpattern'}[$currentDimension]);

				if ($data) foreach ($data as $searchKey => $replaceData)
					{
						$pattern->__contentReplace($searchKey, $replaceData);
					}

				if (isset($rootObject->{'&__dimensionpattern'}[$currentDimension + 1])) {
					$currentDimension++;
					$createOut = $pattern->__createDimensionFromPattern($data, $dimensionInfo, $rootObject, $currentDimension, $return);
					if (($currentDimension - 1) == 1) $rootObject->{'&__dimensionobjects'}[$currentDimension - 1]->append($createOut);
					$currentDimension--;
				}
				$patternCollection->append($pattern);
			}
		$this->__contentReplace("__appenddimensiondata", $patternCollection);

		return $this;
	}

	private function __copyObject($object)
	{
		return unserialize(serialize($object));
	}

	private function __getNumericArrayData($dimensionData)
	{
		if ($dimensionData) foreach ($dimensionData as $key => $val)
			{
				if (is_numeric($key)) $out[$key] = $val;
			}
		if (!isset($out)) return false;
		return $out;
	}

	private function __getStringArrayData($dimensionData)
	{
		if ($dimensionData) foreach ($dimensionData as $key => $val)
			{
				if (is_string($key)) $out[$key] = $val;
			}
		if (isset($out)) return $out;
	}

	function setElementAttribute($attribute, $value)
	{
		if (!is_array($value) && !is_object($value)) cnNode::debug(__METHOD__, "Set attribute $attribute to $value");
		//echo "--> Set $attribute\n";
//        print_r($this);
//        if (isset($this->nodename) && $this->nodename === "__special__treat_next_attribute_as_nodename") {
//            cnNode::debug(__METHOD__, "Exchange preudo node for $attribute");
//            return cnNode::{$attribute}($value);
//        }


		if (isset($this->{'*inreplacemode'}) && $this->{'*inreplacemode'}) {
			if (is_string($value)) {
				//echo $attribute." - ".$value."\n";
				$this->__contentReplace($attribute, $value);
			} elseif (is_array($value) && count($value) > 1 && cnNode::__array_depth($value) == 1) {
				$this->__contentRepeatReplace($attribute, $value);
			}
			//To solve it, Im gonna do it ugly. This should be rewritten for better detection of multiparameter or multidimensional array.
			//The problem is: This function gets called twice. First with given array, then with each data seperatly.
			//Currently, Dimensions are restricted to cascading dimensions. It is not possible to feed to separate dimensions asyncron.
			elseif ($attribute == "explode" && is_array($value) && count($value) >= 1 && cnNode::__array_depth($value) >= 1) {
				if (isset($this->{'&__called'}) && $this->{'&__called'}) {
					$objectDimensions = $this->__getDimensions();
					$objectDimensionPattern = $this->__getDimensionContainer();
					$objectDimension = count($objectDimensionPattern); //$objectDimensions["dimension"];
					$objectDimensionReferences = $objectDimensions["references"];
					$arrayDimensions = $this->__collectArrayInformation($value);
					$arrayDimensions["is_dimension"];
					$dataDimension = $arrayDimensions["dimension"];
					$dataLayer = $arrayDimensions["datalayer"];

					if ($objectDimension == $dataDimension && $dataLayer) {

						//ob_start();
						$this->{'&__dimensionobjects'} = $objectDimensionReferences;
						$this->{'&__dimensionpattern'} = $objectDimensionPattern;
						//echo "(Object) Expected Dimension: ".$objectDimension."\n";
						//echo "(Explode) Data Dimension: ".$dataDimension."\n";
						//echo "(Explode) Attributes: ".$dataLayer."\n";
						$this->__extendDimensions($value, $arrayDimensions);
						//ob_end_clean();
					} elseif ($objectDimension > $dataDimension) {
						echo "Expect more dimensions in given data!\n";
					}
					$this->{'&__called'} = false;
				}
				if (isset($this->{'&__called'}) && $this->{'&__called'} === false) unset($this->{'&__called'});
				else $this->{'&__called'}++;
			}
		}

		if ($attribute == "empty") return call_user_func(array($this, "__empty"));

		if ($value instanceOf cnNode_Attribute) {
			return $value->appendTo($this)->tag();
		}

		$attrName = $attribute;
		$addType = gettype($value);
		if ($attrName == "text" || $attrName == "html") $attrName = $attribute = "_content";

		if (( $attrName == "cdata" && is_string($value)) || ( $attrName == "cdata" && is_array($value) && implode("", $value) === "" )) {
			if (is_array($value)) $value = "";
			$value = "<![CDATA[".$value."]]>";
			$attrName = $attribute = "_content";
		}


		if (isset($value) && is_array($value) && count($value) !== 0) {
			$value = (isset($value) && is_array($value) && isset($value[0]) ? $value[0] : $value);
			$value = $this->__selfStateAttribute($attribute, $value);
		}




		//Try to intercept node creation in special case ifEmpty
//        if(isset($this->nodename) && $this->nodename === "special:ifNotAnyEmpty" && $attribute[0] !== "*" && $attribute !== "_name"){
////            print_r($attribute);
////            echo "\n";
////            print_r($value);
////            echo "\n";
//            $this->nodename = $attribute;
//            unset($this->_name);
////            $attribute = $value;
//            $value = array();
//            return $this->tag();
//        }
//
//        if (
//                isset($this->{'*__special__ifnotanyempty'})
//                && $this->{'*__special__ifnotanyempty'}
//                && (is_array($value) && implode("",$value) === "")
//                ) {
////            print_r($this);
////            print_r($attribute);
////            print_r($value);
//            cnNode::debug(__METHOD__, "Check if $attribute is_empty (".(!count($value)).")");
//            $this->nodename = "[nooutput]";
//            return $this->tag();
////            cnNode::debug(__METHOD__, "Check node ".$this->nodename);
//        }




		if (!$attribute && $value) {
			$attribute = preg_replace("/[^a-z]/", "_", $value);
			trigger_error("Probably wrong count of given attributes. You try to assign $value to a non assigned attribute!", E_USER_WARNING);
			$this->$attribute = $value;
		} elseif (!$attribute && !$value) {
			//trigger_error("Probably wrong count of given attributes. Neither attribute nor value is given. Drop assignment.",E_USER_WARNING);
		} else {
			$this->$attribute = $value;
		}

		return $this->tag();
	}

	private function __selfStateAttribute($attribute, $value)
	{
		$selfStates = array("disabled", "checked");
		if (in_array($attribute, $selfStates) && $value != false) {
			return $attribute;
		}
		return $value;
	}

	function __invoke()
	{
		$args = func_get_args();
		//print_r($args);
		//echo function_exists($args[0]);
		$obj = call_user_func_array(array($this, 'append'), $args);
		return $obj->tag();
	}

	public function find()
	{
		$args = func_get_args();
		//$selector = new selector();
		//print_r($args);
		$selector = element_selector::parser($args[0]);
		print_r($selector);
		if ($selector->getTargetType() === "MULTI") {
			foreach ($selector->getNextRule() as $id => $subSelector)
			{
				$this->__findInElements($subSelector);
			}
		} else {
			$this->__findInElements($selector);
		}
	}

	private function __findInElements(element_selector $selector)
	{
		echo $selector."\n";
		//echo "Look for ".$selector->getSelector()." ".$selector->getTargetType()." in nodes ".$trs[$selector->getLookupDirection()]." this in ".$trs[$selector->getLookupLevel()]." Level\n";
	}

	public function __empty()
	{
		$this->tag()->html = "";
		return $this->tag();
	}

	private function __hasEvent($on, $function)
	{
		cnNode::__hasEvent($on, $function, $this);
		return $this->tag();
	}

	public function toString()
	{
		return (string) $this->tag();
	}

	private static function __isSingeTag($nodeName)
	{
		return (bool) in_array($nodeName, cnNode::$singleTags); #<==== new (some changes)
	}

	private static function __isUncloseTag($nodeName)
	{
		return (bool) in_array($nodeName, cnNode::$uncloseTags); #<==== new
	}

	function __toString()
	{
		$this->__hasEvent('on', __FUNCTION__);

		$tag = $this->tag()->toArray();
		$nodeName = $tag["nodename"];
		$tagContent = null;

		$isSingleTag = self::__isSingeTag($nodeName) | (bool) isset($tag["issingle"]);
		$isUncloseTag = self::__isUncloseTag($nodeName) | (bool) isset($tag["isunclose"]);

		if (strpos($nodeName, "special:") === 0) {
			foreach ($tag as $key => $val)
				if ($key != "_content") unset($tag[$key]);
			$nodeName = "";
		}

		if (strpos($nodeName, "[nooutput]") === 0) {
			return '';
		}

		if (isset($tag["nodename"])) unset($tag["nodename"]);
		if (isset($tag["issingle"])) unset($tag["issingle"]);
		if (isset($tag["isunclose"])) unset($tag["isunclose"]);#<==== new

		// var_dump($tag["_content"]);

		if (!$isSingleTag && isset($tag["_content"])) {
			if (isset($tag["_content"]) && !$tag["_content"]) {
				$tagContent = (string) $tag["_content"];
			} elseif (isset($tag["_content"])) {
				$tagContent = $tag["_content"];
			}

			if (is_array($tagContent)) {
				$subNodes = $tagContent;
				$tagContent = "";
				foreach ($subNodes as $elNr => $subNode)
					if ($subNode instanceOf cnNode_Attribute) {
						$tagContent .= $subNode->toString();
					} else {
						$tagContent .= $subNode;
					}
			}

			if (!isset($tagContent)) {
				$isSingleTag = true;
			}
			if (isset($tag["_content"])) unset($tag["_content"]);
		}
		if (isset($tag["_content"])) unset($tag["_content"]);

		if ($nodeName) $strTag = '<'.$nodeName.' ';
		if ($tag) foreach ($tag as $attribute => $value)
			{
				if ($attribute[0] === "+") $attribute = substr($attribute, 1);
				if (is_null($value)) {
					$strTag .= $attribute.'="'.$attribute.'"';
				} elseif ($attribute[0] != "*") {
					if (is_string($value)) {
						$value = addcslashes(stripcslashes($value), '"');
					} elseif (is_array($value)) {
						$value = implode(" ", $value);
						$value = addcslashes(stripcslashes($value), '"');
					}
					$strTag .= ($attribute ? $attribute.'="' : $attribute.'"').$value.'" ';
				}
			}

		if ( $isSingleTag ) {

			if ($nodeName) $strTag .= "/>";

		} else {

			if ( ! isset ( $strTag ) ) $strTag = "";

			if ( $nodeName ) $strTag = rtrim( $strTag ).">";#<==== fixed (war an einer falschen stelle)

			if ( ! $isUncloseTag ) { #<==== new

				$strTag .= $tagContent;

				if ( $nodeName ) $strTag .= "</".$nodeName.">";
			}
		}

		if( empty( $strTag ) ) $strTag = '';
//        unset($this->tag);
//        $this->tag = null;
		return $strTag;
	}

	public function parseAttr($string)
	{
		cnNode::debug(__METHOD__, $string);
		$regex = '([^ =]+)\\ *?=\\ *?("|\')((?:\\\\\\2+|[^\\2]*?)*?)\\2';
		$regex_full = '@'.$regex.'@s';

		while (preg_match($regex_full, $string, $mt)) {
			$this->{$mt[1]} = $mt[3];
			$string = preg_replace($regex_full, '', $string, 1);
			$hasModded = true;
		}

		$foo = preg_split('@\ +@s', trim($string));

		foreach ($foo as $attr)
		{
			if (preg_match('@^([^=]+)=([^ ]+)$@s', $attr, $mt)) {
				$this->{$mt[1]} = $mt[2];
			} else {
				if (property_exists($this, $attr)) $this->{$attr} = null;
			}
		}
		if (!isset($hasModded)) $hasModded = false;
		return $hasModded;
	}

	function toArray()
	{

		/* Causes errors an problems...
		  //Dirty hack to just clean up special-controls, will be more generic in future
		  if(substr($this->tag()->nodename,0,7) == "special" ) $tag = $this->tag()->_content; else $tag = $this->tag();
		  //Cleanup should be applied before conversion
		  $array = json_decode(json_encode($tag), true);
		  return $array;
		 */

		if (isset($this->tag()->nodename) && isset($this->tag()->{'&__namespace'}) && $this->tag()->{'&__namespace'}) {
			$this->tag()->nodename = $this->tag()->{'&__namespace'}.":".$this->tag()->nodename;
			unset($this->tag()->{'&__namespace'});
		}

		//Not in work... must be a recursion
		foreach ($this->tag as $key => $val)
		{
			if (!is_object($val) && $key[0] != "*" && $key[0] != '&') $copy[$key] = $val;

			//elseif($val instanceOf cnNode_Attribute) $copy[$key] = $val->toArray();
			//elseif(is_array($val)) foreach($val as $subValId => $subVal) $copy[$key][$subValId] = $subVal;
		}
		return $copy;
	}

	/**
	 *
	 * @return cnNode_Attribute
	 */
	public function tag()
	{
		return $this->tag;
	}

}

class parse
{

	private static $S;
	private static $A;
	private static $T;

	private static function _init()
	{
		self::$S = " \t\r\n\f"; //Valid Whitespace description
		self::$A = "[a-zA-Z_:][a-zA-Z0-9_:.-]*"; //Valid Attribute description
		self::$T = "[a-zA-Z]{1}[-a-zA-Z0-9:_]*"; //Valid Tagname description
	}

	public static function html($string = "", $toTag)
	{

		if (!self::$A) self::_init();
		$S = self::$S;
		$A = self::$A;
		$T = self::$T;
		$rx = "<"; //Look for an opener
		$rx.= "([".$S."]*?[/]?".$T."[".$S."]*?)"; //Find a valid start or ending tag and store it
		$rx.= "("; //Start recording the attributes
		$rx.= "(?:"; //Take all attributes
		$rx.= "(?:"; //Loop through attributes
		$rx.= "(?:"; //There are fullfeatured attributes and singlekey attribute decide which one to take
		$rx.= "".$A."[".$S."]*?=[".$S."]*?('|\")(?:(?:\\\\\\3|[^\\3])*?)(\\3)"; //Find fullfeatured attributes
		$rx.= "|(?:".$A."?(?:=[^ >]*)?)"; //Or be fine with a valid singlekey attribute/unquoted attribute
		$rx.= ")"; //Decision for an attribute finished
		$rx.= "[".$S."]*"; //Next attribute might be spaced by valid space characters
		$rx.= ")*"; //Finish attribute loop
		$rx.= ")"; //Take as long attributes as there is not the ending sign >
		$rx.= ")"; //And store them in a string
		$rx.= "(?:>|(/)[".$S."]*>)"; //The end is reached if there is a closing sign found >

		preg_match_all("!".$rx."!sm", $string, $matched_tags, PREG_OFFSET_CAPTURE);

		return self::tokenizer($matched_tags, $string, $toTag);
	}

	private static function tokenizer($matchArray, $string, $toTag)
	{

		unset($matchArray[3]);
		unset($matchArray[4]);
		$ELEMENTS = 0;
		$TAG = 0;
		$TAGNAME = 1;
		$ATTRIBUTES = 2;
		$SINGLETAG = 5;

		$VALUE = 0;
		$OFFSET = 1;

		$last = false;

		$elementCount = count($matchArray[$ELEMENTS]);
		$depth = 0;
		if ($matchArray[$ELEMENTS]) foreach ($matchArray[$ELEMENTS] as $ID => $foo)
			{
				$tag = self::getTagInfo($matchArray, $ID, $depth);
				$depth = $tag["depth"];

				if ($tag["isEnd"] || $tag["isSingle"]) {
					$afterTag = self::getTagInfo($matchArray, $ID + 1, $depth);
				}

				if (!$last) {
					$firstTagID = $ID;
				}

				if ($depth == 0 || $elementCount == 1) {
					$newTag[$ID] = cnNode::tag();

					$firstTag = self::getTagInfo($matchArray, $firstTagID);
					$newTag[$ID]->_nodename($firstTag["nodeName"]);

					if ($firstTag["attributes"]) {
						self::__attribute_tokenizer($firstTag["attributes"], $newTag[$ID]);
					}

					if ($firstTagID == 0) {
						$preNode = substr($string, 0, $firstTag["tagStartPos"]);
					} else {
						$preFirstTag = self::getTagInfo($matchArray, $firstTagID - 1);
						$preNode = substr($string, $preFirstTag["tagEndPos"], $firstTag["tagStartPos"] - $preFirstTag["tagEndPos"]);
					}

					if ($preNode) {
						$toTag->addhtml($preNode);
					}

					$newTag[$ID]->appendTo($toTag);
					unset($last);

					$subString = substr($string, $firstTag["tagEndPos"], $tag["tagStartPos"] - $firstTag["tagEndPos"]);
					if ($subString) {
						$newTag[$ID]->_intoTag($subString);
					}

					$nextTag = self::getTagInfo($matchArray, $ID + 1);
					if (!$nextTag["nodeName"]) {
						if ($sufString = substr($string, $tag["tagEndPos"])) {
							$toTag->addhtml($sufString);
						}
					}
				} else {
					$last = $tag;
				}
			}

		return $toTag->tag();
	}

	private static function getTagInfo($matchArray, $ID, $depth = false)
	{
		$ELEMENTS = 0;
		$TAG = 0;
		$TAGNAME = 1;
		$ATTRIBUTES = 2;
		$SINGLETAG = 5;
		$VALUE = 0;
		$OFFSET = 1;

		if (isset($matchArray[$TAGNAME][$ID][$VALUE])) $nodeName = trim($matchArray[$TAGNAME][$ID][$VALUE]);
		if (isset($nodeName) && $nodeName[0] == "/") {
			$isEnd = true;
			$nodeName = trim(substr($nodeName, 1));
		} else {
			$isEnd = false;
		}

		if (isset($matchArray[$SINGLETAG][$ID]) && isset($matchArray[$TAGNAME][$ID][$VALUE])) {
			$isSingle = (bool) $matchArray[$SINGLETAG][$ID] || (bool) self::isSingleTag($matchArray[$TAGNAME][$ID][$VALUE]);
		} else {
			$isSingle = false;
		}
		if (!$isSingle && !$isEnd) {
			$depth++;
		}
		if ($isEnd) {
			$depth--;
		}
		if (!isset($matchArray[$ELEMENTS][$ID][$OFFSET])) $matchArray[$ELEMENTS][$ID][$OFFSET] = 0;
		$tagStartPos = $matchArray[$ELEMENTS][$ID][$OFFSET];

		if (!isset($matchArray[$ELEMENTS][$ID][$VALUE])) $matchArray[$ELEMENTS][$ID][$VALUE] = 0;
		$tagEndPos = $tagStartPos + strlen($matchArray[$ELEMENTS][$ID][$VALUE]);

		if (!isset($matchArray[$ATTRIBUTES][$ID][$VALUE])) $matchArray[$ATTRIBUTES][$ID][$VALUE] = "";

		$tag["nodeName"] = (isset($nodeName) ? $nodeName : "");
		$tag["isSingle"] = $isSingle;
		$tag["isEnd"] = $isEnd;
		$tag["depth"] = $depth;
		$tag["tagStartPos"] = $tagStartPos;
		$tag["tagEndPos"] = $tagEndPos;
		$tag["attributes"] = $matchArray[$ATTRIBUTES][$ID][$VALUE];
		return $tag;
	}

	private static function plotTag($tagData)
	{
		$line = str_pad($tagData["nodeName"], 7);
		$line.= " single:".(int) $tagData["isSingle"];
		$line.= " end:".(int) $tagData["isEnd"];
		$line.= " depth:".(int) $tagData["depth"];
		$line.= " sP:".$tagData["tagStartPos"];
		$line.= " sE:".$tagData["tagEndPos"];
		$line.= "\n";
		return $line;
	}

	private static function isSingleTag($nodeName)
	{
		return (bool) in_array($nodeName, cnNode::$singleTags); #<==== new (some changes)
	}

	private static function braceCheck($string)
	{
		$string = trim($string);
		if (strpos($string, "<") !== false || strpos($string, ">") !== false) {
			return strpos($string, "<");
		}
		return false;
	}

	public static function __has_attributes($attributeString)
	{
		self::_init();
		$S = self::$S;
		$A = self::$A;
		$T = self::$T;
		$ATTRIBUTEPAIR = 0;
		$ATTRIBUTEDELIMITER = 1;

		//echo $attributeString."\n";

		$rx = "";
		$rx.= "".$A."[".$S."]*?=[".$S."]*?('|\")(?:(?:\\\\\\1|[^\\1])*?)(\\1)"; //Find fullfeatured attributes
		$rx.= "|(?:".$A.")[".$S."]*?=[^ ]*|[a-zA-Z]{1}[a-zA-Z0-9:-]*"; //Or be fine with a valid singlekey attribute
		if (isset($attributeString) && $attributeString) if (preg_match("!".$rx."!sm", $attributeString, $match)) return true;
		return false;
	}

	/**
	 * This function traces/splits the attributes out of a string containing all attributes in a raw form
	 * @param string $attributeString Is the attribute string given from the tag_tokenizer/create
	 */
	static function __attribute_tokenizer($attributeString, $htmlElement = NULL)
	{
		self::_init();
		$S = self::$S;
		$A = self::$A;
		$T = self::$T;
		$ATTRIBUTEPAIR = 0;
		$ATTRIBUTEDELIMITER = 1;

		$attributeName = "";
		$attributeValue = "";
		$toOutput = array();

		$rx = "";
		$rx.= "".$A."[".$S."]*?=[".$S."]*?('|\")(?:(?:\\\\\\1|[^\\1])*?)(\\1)"; //Find fullfeatured attributes
		$rx.= "|(?:".$A.")[".$S."]*?=[^ ]*|[a-zA-Z]{1}[a-zA-Z0-9:-]*"; //Or be fine with a valid singlekey attribute
		if (isset($attributeString) && $attributeString) while (preg_match("!".$rx."!sm", $attributeString, $match)) {
				if (isset($match) && $match) {
					@list($attributeName, $attributeValue) = explode("=", $match[0], 2);
				}
				if (isset($match[1]) && $match[1]) {
					$attributeValue = substr(trim($attributeValue), 1, strlen(trim($attributeValue)) - 2);
				}
				if (is_object($htmlElement) && $htmlElement instanceOf cnNode_Attribute) {
					$htmlElement->{trim($attributeName)}(trim($attributeValue));
				} else {
					if (isset($attributeName)) $toOutput[] = $attributeName;
					if (isset($attributeValue)) $toOutput[] = $attributeValue;
				}
				if (isset($match[$ATTRIBUTEPAIR]) && $match[$ATTRIBUTEPAIR] && isset($attributeString) && $attributeString) {
					$pos = strpos($attributeString, $match[$ATTRIBUTEPAIR]);
					$attributeString = substr_replace($attributeString, "", $pos, strlen($match[$ATTRIBUTEPAIR]));
				}
			}

		return $toOutput;
	}

}

class element_selector
{

	private $selector;
	private $level;
	private $lookupFilter;
	private $matchTargetType;
	private $remainingSelector;
	private $parentObject;
	private $subSelector;
	private $nextRule;
	private static $definedConstants;

	/**
	 * Constructor. Initializes some used constants and assigns the given object as parent to the new created object. Then it starts analyzing the given selector statement.
	 * @param type $selector
	 * @param type $parentObject
	 */
	function __construct($selector, &$parentObject = null)
	{
		self::defineConstants();
		$selector = self::removeGlitches($selector);
		if ($parentObject) $this->setParent($parentObject);

		if (!$parentObject && strpos($selector, ",") === false) {
			$this->setLevel(1);
		} elseif ($parentObject) {
			$this->setLevel($parentObject->getLevel() + 1);
		}

		$this->parseSelector($selector);
	}

	/**
	 * Definition of constants used within this class
	 * @return void
	 */
	private static function defineConstants()
	{
		if (self::$definedConstants) return;
		define("OP_NEXT", " ");
		define("OP_FOLLOWEDBY", ">");
		define("OP_DIRECTSIBLING", "+");
		define("OP_SIBLING", "~");
		define("OP_CLASS", ".");
		define("OP_ID", "#");
		define("OP_PSEUDO", ":");
		define("OP_ATTRIBUTE", "[");
		define("OP_SUBSELECT", "(");
		define("PARENTS", "<");
		define("PARENT", "<");
		define("CHILDREN", ">");
		define("CHILD", ">");
		define("SIBLINGS", "=");
		define("SIBLING", "=");
		define("ELEMENT", "0");
		define("LOOK_AT_NEXT", "1");
		define("LOOK_AT_ALL", "*");
		define("LOOK_AT_THIS", "=");

		self::$definedConstants = true;
	}

	function __toString()
	{
		$trs["lvl"][LOOK_AT_ALL] = "all";
		$trs["lvl"][LOOK_AT_THIS] = "this";
		$trs["lvl"][LOOK_AT_NEXT] = "only next";

		$trs["dir"][ELEMENT] = "this";
		$trs["dir"][CHILDREN] = "below";
		$trs["dir"][PARENTS] = "above";
		$trs["dir"][SIBLING] = "beside";

		return "Look for ".$this->getSelector()."-".$this->getTargetType()." in nodes ".$trs['dir'][$this->getLookupDirection()]." this in ".$trs['lvl'][$this->getLookupLevel()]." Level";
	}

	/**
	 * The igniter. This static function calls the internalParser
	 * @param type $string
	 * @return thisClass
	 */
	public static function parser($string)
	{
		return self::internalParser($string);
	}

	/**
	 * Function validates the given parameter and creates a new object of it self.
	 * @param type $string
	 * @param type $parentObject
	 * @return thisClass
	 */
	private static function internalParser($string, &$parentObject = null)
	{
		if (!trim($string)) return false;
		$thisClass = get_called_class();
		$selectorObject = new $thisClass($string, $parentObject); //== setSelector($string)
		return $selectorObject;
	}

	/**
	 * Function to clean up senseless formatting character within a selector
	 * @param type $string
	 * @return string
	 */
	private static function removeGlitches($string)
	{
		$string = preg_replace('!\s*([>+~])\s*!', "\$1", $string);
		return $string;
	}

	/**
	 * Pre-check a given selector statement to check if it consists of more than one statement delimited by (,)
	 * @param type $string
	 * @return boolean
	 */
	private function checkMultiSelector($string)
	{
		if (strpos($string, ",") !== false) {
			$selectorArray = preg_split("!\s*,\s*!sm", $string);
			if ($selectorArray) {
				$this->setTargetType("MULTI");
				$this->setLookupFilter("*");
				foreach ($selectorArray as $selector)
				{
					$nextrule = self::internalParser(" ".$selector, $this);
					if ($nextrule) {
						$this->nextRule($nextrule);
					}
				}
			}
			return true;
		}
		return false;
	}

	/**
	 * If given string is only one statement, start parsing it
	 * @param type $string
	 */
	private function checkSingleSelector($string)
	{
//      echo $string."\n";
		$selectorInfo = $this->getSelectorType($string);
		$this->setRemaining($selectorInfo["nextrule"]);
//      print_r($selectorInfo);
		if (!$selectorInfo) return false;

		$this->setSelector($selectorInfo["thisselector"]);
		$this->setLookupFilter($selectorInfo["lookupFilter"]);

		$this->setTargetType($selectorInfo["thistyp"]);
		if ($selectorInfo["nextrule"]) {
			$nextrule = self::internalParser($selectorInfo["nextrule"], $this);
			if ($nextrule) {
				$this->nextRule($nextrule);
			}
		}
	}

	/**
	 * Validate the given string and determine wheter it is a simple single statement or an multi statement. Otherwise do nothing else than return.
	 * @param type $string
	 */
	private function parseSelector($string)
	{
		if (!trim($string) || !is_string($string)) return false;

		$this->setSelector($string);

		if (!$this->checkMultiselector($string)) {
			$this->checkSingleSelector($string);
		}
	}

	private function nextRule($selectorObject)
	{
		$this->nextRuleArray[] = $selectorObject;
	}

	private function subSelector($selectorObject)
	{
		$this->subSelector[] = $selectorObject;
	}

	private function setRemaining($selector)
	{
		$this->remainingSelector = $selector;
	}

	public function getRemaining()
	{
		return $this->remainingSelector;
	}

	private function setParent(&$object)
	{
		$this->parentObject = & $object;
	}

	private function getParent()
	{
		return $this->parentObject;
	}

	public function getSelector()
	{
		return $this->selector;
	}

	private function getSubSelector()
	{
		return $this->subSelector;
	}

	private function setSelector($string)
	{
		$this->selector = $string;
	}

	private function setTargetType($string)
	{
		$this->matchTargetType = $string;
	}

	public function getTargetType()
	{
		return $this->matchTargetType;
	}

	private function setLookupFilter($string)
	{
		$this->lookupFilter = $string;
	}

	public function getLookupFilter()
	{
		return $this->lookupFilter;
	}

	public function getLookupLevel()
	{
		return $this->lookupFilter[0];
	}

	public function getLookupDirection()
	{
		return $this->lookupFilter[1];
	}

	private function setLevel($level)
	{
		$this->level = $level;
	}

	public function getLevel()
	{
		return $this->level;
	}

	public function getNextRule()
	{
		return $this->nextRuleArray;
	}

	public function hasNextRule()
	{
		if ($this->nextRuleArray) return true;
	}

	public static function readLookupFilter($code)
	{
		return $code." means ".self::translateLookupFilter($code);
	}

	public static function translateLookupFilter($code)
	{
		$k[0]["1"] = "LOOK_AT_NEXT";
		$k[0]["*"] = "LOOK_AT_ALL";
		$k[0]["="] = "LOOK_AT_THIS";
		$k[1]["<"] = "PARENTS";
		$k[1][">"] = "CHILDREN";
		$k[1]["="] = "SIBLINGS";
		$k[1]["0"] = "ELEMENT";
		return $k[0][$code[0]].".".$k[1][$code[1]];
	}

	private static function progressOne($string)
	{
		return substr($string, 1);
	}

	private function getSelectorType($string, $precastedLookup = null)
	{
//      echo "TRACE: (".$string.")\n";

		if (!trim($string)) return;
		$operator = substr($string, 0, 1);

//      echo "OPERATOR: (".$operator.")\n";
//      if($precastedLookup)
//          echo "precastedLookup: ".$precastedLookup."\n";
//      echo "Test Operator: '{$operator}'\n";
		//TODO more convenient operator parser
		switch ($operator)
		{
			case OP_NEXT:
				$return = $this->getSelectorType(self::progressOne($string), LOOK_AT_ALL.CHILDREN);
				break;
			case OP_FOLLOWEDBY:
				$return = $this->getSelectorType(self::progressOne($string), LOOK_AT_NEXT.CHILDREN);
				break;
			case OP_SIBLING:
				$return = $this->getSelectorType(self::progressOne($string), LOOK_AT_ALL.SIBLING);
				break;
			case OP_DIRECTSIBLING:
				$return = $this->getSelectorType(self::progressOne($string), LOOK_AT_NEXT.SIBLING);
				break;
			case OP_CLASS:
				$return = $this->traceCLASS($string);
				$return["lookupFilter"] = LOOK_AT_THIS.ELEMENT;
				break;
			case OP_ID:
				$return = $this->traceID($string);
				if (!$precastedLookup) $return["lookupFilter"] = LOOK_AT_THIS.ELEMENT;
				break;
			case OP_PSEUDO:
				$return = $this->tracePSEUDO($string);
				if (!$this->getParent()) $precastedLookup = LOOK_AT_ALL.CHILDREN;
				if (!$precastedLookup) $return["lookupFilter"] = LOOK_AT_THIS.ELEMENT;
				break;
			case OP_ATTRIBUTE:
				$return = $this->traceATTRIBUTE($string);
				if (!$precastedLookup) $return["lookupFilter"] = LOOK_AT_THIS.ELEMENT;
				break;
			case OP_SUBSELECT:
				$return = $this->traceSUBSELECT($string);
				$this->subSelector(self::internalParser($return["thisselector"]));
				if (!$precastedLookup) $return["lookupFilter"] = LOOK_AT_THIS.ELEMENT;
				break;
			default:
				$return = $this->traceTAG($string);
				if (!$precastedLookup) $return["lookupFilter"] = LOOK_AT_ALL.CHILDREN;
				break;
		}
		if ($precastedLookup) $return["lookupFilter"] = $precastedLookup;
		return $return;
	}

	private function traceResult($typ, $match)
	{
		$return = array("thistyp" => $typ,
			"thisselector" => $match["thisselector"],
			"nextrule" => $match["nextrule"]);
//      print_r($return);
		return $return;
	}

	private function traceTAG($string)
	{
		if (preg_match('/(?<thisselector>^[a-z]+[a-z0-9]*)(?<nextrule>.*)/ism', $string, $match)) {
			return $this->traceResult("TAG", $match);
		}
		return false;
	}

	private function traceCLASS($string)
	{
		if (preg_match('/\.(?<thisselector>(?:[a-z.]+[a-z0-9]*)*)(?<nextrule>.*)/ism', $string, $match)) {
			return $this->traceResult("CLASS", $match);
		}
		return false;
	}

	private function traceID($string)
	{
		if (preg_match('/\#(?<thisselector>[a-z]+[a-z0-9]*)(?<nextrule>.*)/ism', $string, $match)) {
			return $this->traceResult("ID", $match);
		}
		return false;
	}

	private function traceDIRECTSIBLING($string)
	{
		if (preg_match('/\+(?<thisselector>[a-z]+[a-z0-9]*)(?<nextrule>.*)/ism', $string, $match)) {
			return $this->traceResult("DIRECTSIBLING", $match);
		}
		return false;
	}

	private function traceSIBLING($string)
	{
		if (preg_match('/\~(?<thisselector>[a-z]+[a-z0-9]*)(?<nextrule>.*)/ism', $string, $match)) {
			return $this->traceResult("SIBLING", $match);
		}
		return false;
	}

	private function tracePSEUDO($string)
	{
		if (preg_match('/\:(?<thisselector>[a-z]+[a-z0-9]*)(?<nextrule>.*)/ism', $string, $match)) {
			return $this->traceResult("PSEUDO", $match);
		}
		return false;
	}

	private function traceATTRIBUTE($string)
	{
		if (preg_match('/\[(?<thisselector>[^\]]*)\](?<nextrule>.*)/ism', $string, $match)) {
			return $this->traceResult("ATTRIBUTE", $match);
		}
		return false;
	}

	private function traceSUBSELECT($string)
	{
		if (preg_match('/\((?<thisselector>[^\)]*)\)(?<nextrule>.*)/ism', $string, $match)) {
			return $this->traceResult("SUBSELECT", $match);
		}
		return false;
	}

}
