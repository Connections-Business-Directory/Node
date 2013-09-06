<?php
function stopwatch()
// stopwatch([$limit], [$callback], [$args ... ])
// $limit - # of iterations to run
// $callback - function to time
// $args - arguments for function
//
{
  static $zero = 0;
  static $limit = 399999;

  $args = func_get_args() + Array(null);
  
  // Check for # iterations, assign for future calls
  if (is_integer($args[0])) $max = min(array_shift($args), 999999); else $max = $limit;
  if ($max != $limit)
  {
    $limit = $max;
    $zero = 0;
  }
  if (isset($args[0])) $callback = array_shift($args); else $callback = null;

  if (is_null($callback))
  {
    // Use a very small function as a reference point
    $callback = 'pi';
    $zero = null;
  }  

  // Ensure a valid callback
  if (!is_callable($callback, false, $name)) return null;
  if ($name == __FUNCTION__) return null;

  // The actual loop
  $st = explode(' ',microtime());
  for($cx=0;  $cx<$max;  $cx++)
  {
    call_user_func_array($callback, $args);
  }

  // Final calculations
  $t = explode(' ', microtime());
  $t[0] -= $st[0];
  $t[1] -= $st[1];
  
  if (is_array($zero)) // Use previous reference point
  {
    $t[0] -= $zero[0];
    $t[1] -= $zero[1];
  }
  elseif (is_null($zero)) // or establish a new one
  {
    $zero = $t;
    return;
  }
  
  if ($t[0] < 0) // Ensure microseconds are always positive
  {
    $t[0]++; $t[1]--;
  } 

  // Done
  return ($t[1] + $t[0])."s";  
}

function faster()
{
	$x=TRUE;
	$y=($x===true);
}

function slower()
{
	$x=TRUE;
	$y=($x==true);
}

echo "Comparing two functions...\n";
echo "faster: ".stopwatch('faster')."\n";
echo "slower: ".stopwatch('slower')."\n";

/*
echo "Comparing 100,000 iterations of two functions...\n";
var_dump(stopwatch(100000, 'faster'));
var_dump(stopwatch('slower'));

echo "Run a single function 500 times...\n";
var_dump(stopwatch(500));
var_dump(stopwatch('max', 1, 2, 3, 4, 5));

echo "Run a single function 555,555 times...\n";
var_dump(stopwatch(555555));
var_dump(stopwatch('max', 1, 2, 3, 4, 5));

echo "Fail sanity test...\n";
var_dump(stopwatch('stopwatch')); // Attempt to time itself!
*/
?>