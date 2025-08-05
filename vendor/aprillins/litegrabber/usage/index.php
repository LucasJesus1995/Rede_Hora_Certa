<?php namespace Aprillins\LiteGrabber\Usage;

require __DIR__.'/../vendor/autoload.php';

use Aprillins\LiteGrabber\LiteGrabber;

?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Lite Grabber Usage Example</title>
</head>
<body>
    <?php
        //My Localhost example
        $liteGrabber = new LiteGrabber('http://www.collections.com/php-packages/aprillins/litegrabber/samplepage/sample-2.html');
        $query = $liteGrabber
            ->article([], true)
            ->h2(['class' => 'post-title'])
            ->a()
            ->getQuery();
        $result = $liteGrabber->getResult();
        print_r($result);
        echo '<br>';

        //ALDOVEGA online example
        $liteGrabber->initGrabber('http://www.aldovega.com');
        $query = $liteGrabber->p(['class' => 'pr hci lh0'], true)->img()->atSrc()->getQuery();
        echo '<pre>';
        print_r($liteGrabber->getResult());
        echo '</pre>';

        $liteGrabber->getNumberOfCall();
    ?>
</body>
</html>