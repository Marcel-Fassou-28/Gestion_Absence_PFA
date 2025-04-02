<?php

$secretKey = bin2hex(random_bytes(32));

file_put_contents('.env', "SECRET_KEY=" . $secretKey);