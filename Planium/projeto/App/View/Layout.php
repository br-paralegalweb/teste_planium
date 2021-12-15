<!DOCTYPE html>
<html lang="pt-BR">
<?php
if (isset($_SESSION["msg"]) || isset($_GET['msg'])) {
?>
    <script>
        window.onload = function() {
            M.toast({
                html: '<?= isset($_SESSION["msg"]) ? $_SESSION["msg"] : $_GET['msg']; ?>',
                classes: 'rounded'
            });
        }
    </script>
<?php
}
?>

<head>
    <?php /* $this->googleAnalytics(); */ ?>
    <?php /* $this->head() */ ?>
    <meta charset="UTF-8">
    <!-- <link rel="shortcut icon" href="https://www.paralegalweb.com.br/App/View/img/favicon.ico"/>
    <link rel="icon" href="https://www.paralegalweb.com.br/App/View/img/images/site/ico.png"> -->
    <!-- <meta name="robots" content="<?php /* $this->getIndexOrNoIndex() */ ?>"> -->
    <!-- <title><?php /* $this->getTitle() */ ?></title>     -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css"> -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <?= $this->scriptCss(); ?>
</head>

<body class="vertical-layout register-bg vertical-menu-collapsible page-header-dark vertical-modern-menu preload-transitions 1-column login-bg   blank-page blank-page" data-open="click" data-menu="vertical-modern-menu" data-col="1-column">
    <!-- Menu -->
    <header>
        <!-- <nav> -->
        <?php
        // var_dump($this->origem());
        // if ($this->origem() == "Dashboard") {
        // include_once("adm/mnAdminUser.php");
        // } else {
        include_once('paginas/menu.php');
        // }
        ?>
        <!-- </nav> -->
    </header>
    <!-- PARTE PRINCIPAL -->
    <!-- <main> -->
    <?php $this->addMain();
    // var_dump($this->getDir());
    ?>
    <!-- </main> -->
    <!-- FIM DA PARTE PRINCIPAL -->
    <!-- rodapé -->
    <?php
    // unset($_SESSION["msg"]);
    // unset($_SESSION["lms"]);
    // if ($this->origem() != "Dashboard") {
    // include_once('paginas/footer.php');
    // }
    // if (file_exists("App/View/materialize/js/{$this->getDir()}.php")) {
    //include_once("App/View/materialize/js/ArquivosJS/{$this->getDir()}.php");
    // } else {
    echo $this->scriptJs();
    // }
    ?>
</body>

</html>