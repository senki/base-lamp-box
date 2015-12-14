<?php
function interpret_php_uname(){
    $release_info["os_name"] = php_uname('s');
    $release_info["uname_version_info"] = php_uname('v');
    $release_info["machine_type"] = php_uname('m');
    $release_info["kernel"] = php_uname('r');
    $release_info["php_uname"] = php_uname();

    $distribution["4.10"]=array("Warty Warthog", "2.6.8");
    $distribution["5.04"]=array("Hoary Hedgehog", "2.6.10");
    $distribution["5.10"]=array("Breezy Badger", "2.6.12");
    $distribution["6.06"]=array("Dapper Drake", "2.6.15");
    $distribution["6.10"]=array("Edgy Eft", "2.6.17");
    $distribution["7.04"]=array("Feisty Fawn", "2.6.20");
    $distribution["7.10"]=array("Gutsy Gibbon", "2.6.22");
    $distribution["8.04"]=array("Hardy Heron", "2.6.24");
    $distribution["8.10"]=array("Intrepid Ibex", "2.6.27");
    $distribution["9.04"]=array("Jaunty Jackalope", "2.6.28");
    $distribution["9.10"]=array("Karmic Koala", "2.6.31");
    $distribution["10.04"]=array("Lucid Lynx", "2.6.32");
    $distribution["10.10"]=array("Maverick Meerkat", "2.6.35");
    $distribution["11.04"]=array("Natty Narwhal", "2.6.38");
    $distribution["11.10"]=array("Oneiric Ocelot", "3.0");
    $distribution["12.04"]=array("Precise Pangolin", "3.2");
    $distribution["12.10"]=array("Quantal Quetzal", "3.5");
    $distribution["13.04"]=array("Raring Ringtail", "3.8");
    $distribution["13.10"]=array("Saucy Salamander", "3.11");
    $distribution["14.04"]=array("Trusty Tahr", "3.13");
    $distribution["14.10"]=array("Utopic Unicorn", "3.16");
    $distribution["15.04"]=array("Vivid Vervet", "3.19.3");
    $distribution["15.10"]=array("Wily Werewolf", "4.2");

    foreach($distribution as $distribution=>$name_kernel){
        list($name,$kernel)=$name_kernel;
        if(version_compare($release_info["kernel"],$kernel,'>=')) {
            $release_info["ubuntu_distribution"]=$distribution;
            $release_info["ubuntu_distribution_name"]=$name;
        }
    }

    return $release_info;
}
$release_info=interpret_php_uname();

$mysqli = new mysqli("localhost", "root", "vagrant");
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
$mysql_serverinfo=$mysqli->server_info;
$mysql_clientinfo=$mysqli->client_info;;
$mysqli->close();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?php echo $_SERVER['HTTP_HOST']; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Basic LAMP Vagrant machine with PHP <?php echo phpversion(); ?>">
    <meta name="author" content="Csaba Maulis">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/skeleton.css">
    <!--[if lt IE 9]> HTML5Shiv
        <script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <style type="text/css">
    body {
        margin-top: 4%;
    }
    code{
        white-space: pre-wrap !important;
    }
    .multicolumn-two {
        -webkit-column-count: 2; /* Chrome, Safari, Opera */
        -moz-column-count: 2; /* Firefox */
        column-count: 2;
    }
    .multicolumn-three {
        -webkit-column-count: 3; /* Chrome, Safari, Opera */
        -moz-column-count: 3; /* Firefox */
        column-count: 3;
    }
    </style>
</head>
<body>
<div class="container">
  <div class="row">
    <div class="twelve columns">
        <h1>Vagrant Box Info </h1>
    </div>
  </div>
 <div class="row">
    <div class="twelve columns">
        <pre><code><a href="linfo/" target="_blank">Linfo</a><br><strong>Hostname:</strong> <?php echo gethostname(); ?><br><strong>URL:</strong> http://<?php echo $_SERVER['HTTP_HOST']; ?><br><strong>Vagrant Box:</strong> <?php echo file_get_contents("/var/provision/version"); ?></code></pre>
    </div>
  </div>

  <div class="row">
    <div class="one-half column">
        <h4>OS</h4>
        <pre><code><?php
                foreach ($release_info as $key => $value) {
                    echo "<strong>$key:</strong> $value<br>";
                }
            ?></code></pre>
    </div>
    <div class="one-half column">
        <h4>MySQL</h4>
        <pre><code><strong>MySQL Server:</strong> <?php echo $mysql_serverinfo; ?><br><strong>MySQL Client:</strong> <?php echo $mysql_clientinfo; ?></code></pre>
        <?php
            $handle = curl_init("http://{$_SERVER['HTTP_HOST']}/phpmyadmin/");
            curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);

            /* Get the HTML or whatever is linked in $url. */
            $response = curl_exec($handle);

            /* Check for 404 (file not found). */
            $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
            if($httpCode != 404) { ?>
        <h4>phpMyAdmin</h4>
        <pre><code><?php echo str_replace("Version", "<strong>Version</strong>", exec('dpkg -s phpmyadmin | grep Version')); ?> • <a href="phpmyadmin/" target="_blank">phpMyAdmin</a></code></pre>
            <?php }

            curl_close($handle);
        ?>
        <h4>Adminer</h4>
        <pre><code><strong>Version</strong>: v4.2.3+php7-fix • <a href="adminer.php" target="_blank">Adminer</a></code></pre>
    </div>
  </div>
<div class="row">
    <div class="one-half column">
        <h4>Apache</h4>
        <pre><code><strong>Version:</strong> <?php echo apache_get_version(); ?></code></pre>
        <h5>Loaded modules:</h5>
        <pre><code  class="multicolumn-two"><?php echo implode("\n", apache_get_modules());?></code></pre>
    </div>
    <div class="one-half column">
        <h4>PHP</h4>
        <pre><code><strong>Version:</strong> <?php echo phpversion(); ?> • <a href="info.php" target="_blank">phpinfo()</a></code></pre>
        <h5>Enabled Extension:</h5>
        <pre><code class="multicolumn-three"><?php echo implode("\n", get_loaded_extensions());?></code></pre>

    </div>
  </div>
<div class="row">
    <div class="twelwe columns">
        <p><strong>Boilerplate</strong> © 2015 Csaba Maulis</p>
        <p><em>Intentionally PHP errors:</em>
        <?php ecce; homo;?>
    </div>
  </div>

</div>

</body>
</html>
