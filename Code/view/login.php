<?php

ob_start();
$title="Login";
?>

<section class="loginform cf">
    <form name="login" action="index.php?action=login" method="post" accept-charset="utf-8">
        <ul>
            <li><label for="usermail">Email</label>
                <input type="email" name="user_mail" placeholder="yourname@email.com" required></li>
            <li><label for="password">Password</label>
                <input type="password" name="user_password" placeholder="password" required></li>
            <li>
                <input type="submit" value="Login"></li>
        </ul>
    </form>
</section>

<?php
$content = ob_get_clean();
require "gabarit.php";


?>