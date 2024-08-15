<?php

namespace Scrapping;

use Exception;
use Scrapping\websites\Pedrali;

require __DIR__ . "/../../website/vendor/autoload.php";

echo '***********************************' . "\n";
echo 'Checking arguments...' . "\n";

/*
 * List of valid arguments
 */
$authorizedFileArguments = [
    'pedrali',
    'iconchairs',
    'flexmob',
    'woodlabpoland',
    'misterwils',
    'nardioutdoor',
    'fenabel',
    'fameg',
    'euroterrasse'
];

/*
 * Arguments validity checking step
 */
$invalidArgument = [];

if ($argv && count($argv) > 1 && $argv[0] === 'scripts/scrapping/scrapping.php') {
    unset($argv[0]);

    foreach ($argv as $argument) {
        if (!in_array($argument, $authorizedFileArguments)) {
            echo 'invalid argument: ' . $argument  . "\n";
            $invalidArgument[] = $argument;
        }
    }

} else {
    echo "Something went wrong. \n";
    echo "Please make sure you called scripts/scrapping.php argument1 ... \n";
    echo "Or check that your argument(s) are valid";
    die();
}

$numnberOfvalidArguments = count($argv) - count($invalidArgument);

if ($numnberOfvalidArguments === 0) {
    echo count($invalidArgument) . ' invalid argument(s) found' . "\n";
    echo 'No valid arguments found' . "\n";
    echo '***********************************' . "\n";
    return false;
} else {
    echo count($invalidArgument) . ' invalid argument(s) found' . "\n";
    echo $numnberOfvalidArguments . ' valid argument(s) found' . "\n";
}
echo '***********************************' . "\n";

/*
 * End of arguments validity checking step
 */

/*
 * Scrapping
 */
echo "\n" . '****** Starting scrapping ******' . "\n";

// Todo : add for each on all argv
if (in_array('pedrali', $argv) ) {
    try {
        $scrappingPedrali = new Pedrali();
        $scrappingPedrali->scrapWebsite();
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage() . "\n";
    }
}

echo "\n" . '****** Scrapping is done ******' . "\n";