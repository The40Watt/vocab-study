<?php

function aria_current($url) {
    // If $url is an exact match for the current URL, return 'aria-current="page"'.
    if ($_SERVER['REQUEST_URI'] === $url) {
        echo 'aria-current="page"';
        return;
    }

    // Otherwise it might be a sub-page, so check if the current URL contains
    // $url. If it does, return 'aria-current="true"' to indicate that the link
    // is an ancestor of the current page. Ignore a lone slash.
    if ($url !== '/' && strpos($_SERVER['REQUEST_URI'], $url) !== false) {
        echo 'aria-current="true"';
        return;
    }
}
?>

<!--<nav>-->


    <h2 class="logo">Word <span class="high">Up</span> <span class="tiny">(BETA)</span></h2>
    <ul>
        <li class="list__item"><a href="/my-site/">Home</a></li>
        <li class="list__item"><a href="/my-site/input.php"<?php aria_current('/input.php') ?>>Add a Word</a></li>
        <li class="list__item"><a href="/my-site/show-data.php">Your Words</a></li>
       <!-- <li class="list__item"><a href="/my-site/test.php">Vocabulary Test</a></li> -->
        <li class="list__item"><a href="/my-site/badges.php">Badges</a></li>
        <li class="list__item"><a href="/my-site/about.php">Contact</a></li>
        <li class="list__item"><a href="/my-site/faq-page.php">FAQ</a></li>
        <li class="list__item"><a href="/my-site/logout.php">Logout</a></li>
        <!-- <li class="list__item"><a href="/my-site/column-style.php">Styles</a></li> -->
        <!-- <li class="list__item"><a href="/my-site/index-alt.php">ALT INDEX</a></li> -->


    </ul>
        <a  href="/my-site/test.php"><button>Test Now</button></a>
<!--</nav>-->