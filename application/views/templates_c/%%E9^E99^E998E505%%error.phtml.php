<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => '../common/header.phtml', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<div class="error-page">
<header class="site-title color">
        <div class="wrap">
                <div class="container">
                        <h1>Error 404</h1>
                        <nav role="navigation" class="breadcrumbs">
                                <ul>
                                        <li><a href="/" title="Home">Home</a></li>
                                        <li>Error 404</li>
                                </ul>
                        </nav>
                </div>
        </div>
</header>
<!-- //Page info -->

<div class="wrap">
        <div class="row">
                <!--- Content -->
                <div class="content one-half right textongreyRight">
                        <h2>404 PAGE NOT FOUND</h2>
                        <p>The page you’ve requested could not be found or it was already moved to another address.</p>
                        <p>If you believe that this is an error, please kindly <a href="/contact">contact us</a> or head straight to our <a href="/">homepage</a>. Thank you!</p>
                </div>
                <!--- //Content -->
        </div>
</div>
</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => '../common/footer.phtml', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>