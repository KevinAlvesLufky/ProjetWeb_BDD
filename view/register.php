<?php

ob_start();
$title="Register";
?>

    <section class="loginform cf">
        <form name="login" action="index.php?action=register" method="post" accept-charset="utf-8">
            <ul>
                <li><label for="usermail">Email</label>
                    <input type="email" name="user_mail" placeholder="yourname@email.com" required></li>
                <li><label for="usermail">Pseudo</label>
                    <input type="text" name="user_pseudo" placeholder="Toto" ></li>
                <li><label for="password">Password</label>
                    <input type="password" name="user_password" placeholder="password" required></li>
                <li>
                <li><label for="password">check Password</label>
                    <input type="password" name="user_password2" placeholder="password" required></li>
                <li>
                    <input type="submit" value="Login"></li>
            </ul>
        </form>
    </section>

<?php
$content = ob_get_clean();
require "gabarit.php";


?>