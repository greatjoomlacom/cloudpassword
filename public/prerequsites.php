<?php

$config = new stdClass();
$config->passed = true;

// PHP >= 5.3.7
$config->php_version = phpversion();
$config->php_version_status = true;
if(!version_compare($config->php_version, '5.3.7', '>='))
{
    $config->php_version_status = false;
    $config->passed = false;
}

// MySQL support
$config->mysql_support = true;
if (!function_exists('mysql_connect'))
{
    $config->mysql_support = false;
    $config->passed = false;
}

// memory limit, >= 32M
$config->memory_limit = null;
$config->memory_limit_status = true;
if(function_exists('ini_get'))
{
    $config->memory_limit = ini_get('memory_limit');

    // lower then expected
    if(intval($config->memory_limit) <= 32)
    {
        $config->memory_limit_status = false;
        $config->passed = false;
    }
}

// mcrypt
$config->mcrypt = true;
$config->mcrypt_status = true;

if(!extension_loaded('mcrypt'))
{
    $config->mcrypt_status = false;
    $config->passed = false;
}

// writable dirs
if(function_exists('ini_get'))
{
    $save_path = ini_get('session.save_path');
    $upload_tmp_dir = ini_get('upload_tmp_dir');
}

// tmp directory
if(!isset($upload_tmp_dir) or !$upload_tmp_dir and function_exists('sys_get_temp_dir'))
{
    $upload_tmp_dir = sys_get_temp_dir();
}

$writable_dirs = array(
    dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'config',
    dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'lang',
    dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'storage',
    dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'cache',
    dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'logs',
    dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'meta',
    dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'sessions',
    dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'views',
    (isset($save_path) ? $save_path : ''),
    (isset($upload_tmp_dir) ? $upload_tmp_dir : ''),
);

foreach($writable_dirs as $key=>$dir)
{
    if(!@is_dir($dir))
    {
        unset($writable_dirs[$key]);
        continue;
    }

    if(@is_dir($dir))
    {
        if(is_writable($dir))
        {
            $writable_dirs[$dir] = 1;
        }
        else
        {
            $config->passed = false;
            $writable_dirs[$dir] = 0;
        }
    }
    else
    {
        $config->passed = false;
        $writable_dirs[$dir] = 0;
    }
    unset($writable_dirs[$key]);
}

$config->writable_dirs = $writable_dirs;

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Check Prerequisites</title>

        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet">

        <link href="data:image/x-icon;base64,AAABAAEAIBUAAAEAIAD8CgAAFgAAACgAAAAgAAAAKgAAAAEAIAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP//AAK5oQNMu6AGU7ujA0u8owRIuZ8ERbqcBEO7owRAvZ4AOr+AAAj///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wC2oAAju54FbLGdABr///8A////AP///wD///8A////AP///wD//wACu6IEPL+fACj///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8Av58AGLyhBHK5ngAdwqMAGbmXABa4nAAStpIADsyZAAqqqgAG//8AAv///wD///8AwaIAIb6jACf///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////ALunADG6oASGvJoANbucADG8lgAuvJgAKryaACa2mQAjvZwAH72hABu8mwAXs5kAFL+fABCqqgAMvp4AP72hABv///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AL+fAAi8nwSNvpwCeb2YA026mANKvZkERrqUBEO+mgQ/u5kEPLubADi8mgA1u5wAMbyWAC68mAAqvJoAJraZACO9nAAfvZcANrqcAEO/nwA4uKMAMqqqAAb///8A////AP///wD///8A////AP///wD///8AvqIGWrueBHy7mwSEvJwFm7qTBWi7lAVivJQFX7qWBly6kgZZupMGVbuVBlK7lAZPu5YDS7yYBEi5mARFvJkEQbmYBD69mgA6uZQAN76bADO6mgAwuZwALLmfACi6nwAlvaAAPricACT///8A////AP///wD///8AwZsAKbubA5m5kASPu48Ef7uOBHy5jwR5vI8EdrqQBHO7jwVwu5EFbbyQBWq8kgVnupEFZLuTBWG6lAVdu5MGWryVBle8lQZUvJYGUL2YA026mANKvZkERrqUBEO+mgQ/u5kEPLubADi8mgA1vZoAOrmZADf///8A////AL+fAAi7lgOovIwDlLuKA5K7iwSPvIwEjLuMBIq7jASHu40EhLyOBIG7jwR/u44EfLmRBHm8jwR2upIEc7uPBXC7kQVtvJAFarySBWe6kQVku5MFYbqUBV27kwZavJUGV7yVBlS8lgZQvZgDTbqYA0q9mQRGuZoDTLqfACX///8AupcFmLuIBaW7hwWiuokFoLuIBZ28iQObuooDmLuLA5a7iwOTvIwEkLuMBI67jQSLvI0EiLqNBIa7jgSDu40EgLyPBH28kAR6vZAEd7uRBHS7kwVxvJIFbryUBWu6kwVou5IFZbuUBWK8lAVfupYGXLqSBlm6kwZVvJcDW7a2AA67kgS3u4UEsbuGBK+7hgStvIcFqruHBai6hwWmvIgFo7uIBaG7iQWeuokFnLuKA5m6igOXvIwDlLuKA5K7iwSPu4wEjruNBIu8jQSIuo0EhruOBIO7jQSAvI8EfbyQBHq9kAR3u5EEdLuTBXG8kgVuvJQFa7qTBWi7kgVlu5sAQLqOBMm7ggS+u4QEvLqEBLq7hAS4u4QEtbuGBLO7hQSxu4YEr7uGBK28hwWqu4cFqLqHBaa8iAWju4oFobuJBZ66iQWcu4oDmbqKA5e8jAOUu4oDkruLBI+8jASMu4wEiruMBIe7jQSEvI4EgbuPBH+7jgR8uZEEebyPBHa4mANevI4EvbuBBMi7gATHuoAExbuABMO8gQTBu4IEv7yDBL27gwS7u4MEubyFBLa7hQS0uoUEsruFBLC6hQSuvIYEq7uIBam6iAWnvIkFpLuHBaK6iQWgu4gFnbyJA5u6igOYu4sDlruLA5O8igSQu4wEjruNBIu8jQSIuo0EhrqTBH28kQOgu30E0rx+BNC7fgTPu38Ezbt/BMu8fwTJu4AEx7uABMa7gQTEu4EEwruCBMC7ggS+u4QEvLqEBLq7hAS4u4QEtbuGBLO7hQSxu4YEr7uGBK28hwWqu4cFqLqHBaa8iAWju4gFobuJBZ66iQWcu4oDmbqKA5e8jAOUvJEEjbaSADG7hwTSu3sE2rt6BNm7fQTXu30E1bp9BNS7fQTSvH4E0Lt+BM+7fwTNu38Ey7yABMm7gATHu4EExruBBMS7gQTCu4IEwLuCBL67hAS8uoQEuruEBLi7hAS1u4YEs7uFBLG7hgSvu4YErbyHBaq7hwWouocFpryIBaO5kQR/////ALuWADi7hQTYu3kF4bx5Bd+7egXevHoF3Lt7A9u7ewTZu3wE2Lt8BNa7ewTVu3wE07x+BNG6fgTQu34EzrqABMy7gATKu4EEyLuABMe6gATFu4AEw7yBBMG7ggS/vIMEvbuDBLu7gwS5vIUEtruFBLS6hQSyu4UEsLqTAmj///8A////ALyWA1+7fQTmu3YE57t3BOW7dwTkungE47t5BeK7eQXhvHkF37t6Bd67egXcu3sE2rt6BNm7fQTXu30E1bp9BNS7fQTSvH4E0Lt+BM+7fwTNu38Ey7x/BMm7gATHu4AExruBBMS7gQTCu4IEwLuCBL67hwS3tpIAI////wD///8A////ALyPBIm6fgTpu3UE7Lx1BOu7dgTqu3YE6bt2BOi7dwTnu3cE5bt4BOS6eATju3kF4bt5BeC6eQXfu3oF3bt6Bdy7ewTau3oE2bt9BNe7fQTVun0E1Lt9BNK8fgTQu34Ez7t/BM27fwTLvIMEyrqPAFn///8A////AP///wD///8A////ALuTAEC7igSsu4QE0buBBOq7dQTwvHQE7rt0BO27dATsu3UE67p1BOq7dwTovHYE57p3BOa7dwTlvHgE47t5BeK7eQXhvHkF37t6Bd68egXcu3sD27t7BNm7fATYu3wE1rt+BNa8jASM////AP///wD///8A////AP///wD///8A////AP///wD///8AqqoABrySBHO7ggPgvHEE8rtyBPG7cwTwvHME77x0BO67dATtu3UE7Lx1BOu7dgTqu3YE6bt2BOi7dgTnu3cE5bt3BOS6eATju3kF4rt5BeG8hATOu5EAYf///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AMibABe8hwTSvHAE9rtwBPa7cAT1u3EE9LtxBPO8cQTyu3IE8byBA+i7hgTCu4IE1buAA+e6egTvu30E7LuBA9u8hQTCuooDl7+/AAj///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wD///8A////AP///wC7jASKu4QEvLt+A927fQTvvH4D6LqABNe8igSuv4AAEP///wD///8A////ALuXADG/nwAQ////AP///wD///8A////AP///wD///8A////AP///wD/////////////////v////v////n////P////gD///wAA//8AAAP/AAAAHwAAAAEAAAAAgAAAAcAAAAHgAAAB4AAAA/gAAAP/gAAP/8AAH//gP/8=" rel="shortcut icon" type="image/vnd.microsoft.icon" />

    </head>
    <body>
    <div class="well col-lg-offset-2 col-lg-8" style="margin-top: 2em; margin-bottom: 2em;">
        <h1>Prerequisities</h1>
        <p>There are some prerequisites your server has to match.</p>
        <?php if (!$config->passed): ?>
            <p class="alert alert-danger">We are sorry but your server does not match the minimal prerequirements. You can see the result bellow.<br />Please fix the configuration first, then reload this page.</p>
            <p><br /></p>
        <?php endif; ?>

        <div class="col-lg-8" style="margin: 0 auto; float: none;">
            <h2>Server</h2>
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Required</th>
                    <th>Your value</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>PHP version</td>
                    <td>>= 5.3.7</td>
                    <td><?php echo $config->php_version; ?></td>
                    <td>
                        <?php if ($config->php_version_status): ?>
                            <span class="label label-success">Passed</span>
                        <?php else: ?>
                            <span class="label label-danger">Failed</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td>MySQL support</td>
                    <td>recommended<br />>= 5.0.4</td>
                    <td>yes</td>
                    <td>
                        <?php if ($config->mysql_support): ?>
                            <span class="label label-success">Passed</span>
                        <?php else: ?>
                            <span class="label label-danger">Failed</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td>memory_limit</td>
                    <td>> 32M</td>
                    <td>
                        <?php if ($config->memory_limit === null): ?>
                            unknown
                        <?php else: ?>
                            <?php echo $config->memory_limit; ?>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($config->memory_limit_status and $config->memory_limit): ?>
                            <span class="label label-success">Passed</span>
                        <?php elseif(!$config->memory_limit_status and $config->memory_limit): ?>
                            <span class="label label-danger">Failed</span>
                        <?php else: ?>
                            <span class="label label-default">Unknown</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td>mcrypt</td>
                    <td>yes</td>
                    <td><?php echo ($config->mcrypt_status ? 'yes' : 'no'); ?></td>
                    <td>
                        <?php if ($config->mcrypt_status): ?>
                            <span class="label label-success">Passed</span>
                        <?php else: ?>
                            <span class="label label-danger">Failed</span>
                        <?php endif; ?>
                    </td>
                </tr>
                </tbody>
            </table>
            <p><br /></p>
            <h2>Directories & files</h2>
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>Directory - File</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($config->writable_dirs as $dir=>$status): ?>
                    <tr>
                        <td><?php echo $dir; ?></td>
                        <td>
                            <?php if ($status): ?>
                                <span class="label label-success">Writable</span>
                            <?php else: ?>
                                <span class="label label-danger">Unwritable</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php if ($config->passed): ?>
                <a href="public" class="btn btn-primary">Proceed installation</a>
            <?php endif; ?>
        </div>
    </div>
    </body>
</html>