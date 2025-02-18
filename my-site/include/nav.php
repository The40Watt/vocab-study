
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

<nav>
    <a href="/my-site/" <?php aria_current('my-site/index.php') ?> >Home</a>
    <a href="/my-site/input.php"<?php aria_current('/input.php') ?> >Add Vocabulary</a>
    <a href="/my-site/show-data.php"<?php aria_current('/show-data.php') ?> >Vocabulary List</a>
    <a href="/my-site/test.php"<?php aria_current('/test.php') ?> >Vocabulary Test</a>
    <a href="/my-site/badges.php"<?php aria_current('/badges.php') ?> >Badges</a>
    <a href="/my-site/about.php"<?php aria_current('/about.php') ?> >Contact</a>
    <a href="/my-site/logout.php"<?php aria_current('/logout.php') ?> >Logout</a>
</nav>