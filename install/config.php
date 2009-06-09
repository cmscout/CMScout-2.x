<?php
    error_reporting(E_ALL);
    $phpEx = substr(strrchr(__FILE__, '.'), 1);
    $data = stripslashes(html_entity_decode($_GET['data']));
    $database = unserialize($data);
    $config =
    "<?php
    /********************************************************************
       Auto Generated config file for CMScout
       DO NOT CHANGE THIS FILE!!
    *********************************************************************/
       \$dbhost = \"{$database['hostname']}\";
       \$dbusername = \"{$database['username']}\";
       \$dbpassword = \"{$database['password']}\";
       \$dbport = \"{$database['port']}\";
       \$dbname = \"{$database['name']}\";
       \$dbprefix = \"{$database['prefix']}\";
    \$phpex = \".$phpEx\";
    ?>";
    header("Content-Type: text/x-delimtext; name=\"config.$phpEx\"");
    header("Content-disposition: attachment; filename=config.$phpEx");
    echo $config;
    exit;
?>