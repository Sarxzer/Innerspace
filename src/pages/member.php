<?php

$parts = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
echo $parts[0]; // should be "members"
echo $parts[1]; // should be the member name (e.g. "skye")