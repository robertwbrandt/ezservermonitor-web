<?php
require 'autoload.php';
$Config = new Config();
$update = $Config->checkUpdate();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1" />

    <title><?= $Config->format('esm:title') ?></title>
   
    <link rel="stylesheet" href="web/css/utilities.css" type="text/css">
    <link rel="stylesheet" href="web/css/frontend.css" type="text/css">
    <link rel="icon" type="image/x-icon" href="<?= $Config->format('esm:favicon') ?>">
    <!--[if IE]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <script src="js/plugins/jquery-2.1.0.min.js" type="text/javascript"></script>
    <script src="js/plugins/jquery.knob.js" type="text/javascript"></script>
    <script src="js/esm.js" type="text/javascript"></script>
    <script>
    $(function(){
        $('.gauge').knob({
            'fontWeight': 'normal',
            'format' : function (value) {
                return value + '%';
            }
        });

        $('a.reload').click(function(e){
            e.preventDefault();
        });

        esm.getAll();

        <?php if ($Config->get('esm:auto_refresh') > 0): ?>
            setInterval(function(){ esm.getAll(); }, <?php echo $Config->get('esm:auto_refresh') * 1000; ?>);
        <?php endif; ?>
    });
    </script>
</head>

<body class="theme-<?= $Config->get('esm:theme') ?>">

<nav role="main">
    <div id="logo">
        <?php
        if ($Config->get('esm:logo') == "esm-gauge") {
            echo '<a href="' . $Config->format('esm:logo_href') . '"><span class="icon-gauge"></span>' . $Config->format('esm:logo_text') . '</a>';
            echo '<a href="' . $Config->format('esm:sublogo_href') . '"><span class="subtitle">' . $Config->format('esm:sublogo_text') . '</span></a>';
        } else {
            echo '<a href="' . $Config->format('esm:logo_href') . '"><img class="logo" src="' . $Config->format('esm:logo') . '"></img>' . $Config->format('esm:logo_text') . '</a>';
            echo '<a href="' . $Config->format('esm:sublogo_href') . '"><span class="subtitle">' . $Config->format('esm:sublogo_text') . '</span></a>';
        }
        ?>
    </div>

    <div id="banner">
        <span id="banner-vcenter">
            <span id="banner-top"><?= $Config->format('esm:banner_top') ?></span>
            <span id="banner-bottom"><?= $Config->format('esm:subbanner_bottom') ?></span>
        </span>
    </div>

    <?php if (!is_null($update)): ?>
        <div id="update">
            <a href="<?php echo $update['fullpath']; ?>">New version available (<?php echo $update['availableVersion']; ?>) ! Click here to download</a>
        </div>
    <?php endif; ?>

    <ul>
        <li><a href="#" class="reload" onclick="esm.reloadBlock('all');"><span class="icon-cycle"></span></a></li>
    </ul>
</nav>

<div id="main-container">

    <?php
        foreach ($Config->get('esm:layout') as $line) {
            switch (count($line)) {
            case 1:
                $type = [''];
                break;
            case 2:
                $type = ['column-left','column-right'];
                break;
            case 3:
                $type = ['column-left column-33','t-center column-33','column-right column-33'];
                break;
            default:
                $type = [];
            }
            echo "\n\n<div class=\"cls\"></div>\n\n";
            echo "<div class=\"t-center\">\n";
            for ($c = 0; $c < count($type); $c++) {
    ?>
        <div class="box <?=$type[$c]?>" id="esm-<?=$line[$c]?>">
            <div class="box-header">
                <h1><?=$Config->format($line[$c].":title")?></h1>
                <ul>
                    <li><a href="#" class="reload" onclick="esm.reloadBlock('<?=$line[$c]?>');"><span class="icon-cycle"></span></a></li>
                </ul>
            </div>
    <?php
                require './plugins/'.$line[$c].'/'.$line[$c].'-html.php';
                echo "\n</div>\n";
            }     
            echo "\n</div>\n";
        }
    ?>


</div>



</body>
</html>
