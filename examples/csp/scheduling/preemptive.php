#!/usr/bin/env php
<?php
/**
 * In this example, while the first coroutine keeps busy running all the time, the second coroutine still has a chance
 * getting executed after a while, which throws an exception out and terminates the execution.
 *
 * How to run this script:
 *     docker exec -t $(docker ps -qf "name=client") bash -c "./csp/scheduling/preemptive.php"
 */

ini_set("swoole.enable_preemptive_scheduler", 1);

co\run(
    function () {
        go(function () {
            $i = 0;
            while (true) {
                echo $i++, "\n";
            }
        });

        go(function () {
            throw new Exception("Quitting.");
        });
    }
);
