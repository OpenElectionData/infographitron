        <div class="footer"></div>
    </div><!-- close class="wrapper" -->

    <!-- JS -->
    <script src="<?php echo Config::get('URL'); ?>js/jquery.min.js"></script>
    <script src="<?php echo Config::get('URL'); ?>js/bootstrap.min.js"></script>

    <?php if (View::checkForActiveController($filename, "custom")) { ?>
    	<script src="<?php echo Config::get('URL'); ?>js/customFormAjax.js"></script>
    	<script src="<?php echo Config::get('URL'); ?>js/rangeslider.min.js"></script>
    	<script src="<?php echo Config::get('URL'); ?>js/customFormRange.js"></script>
    <?php }
        elseif (View::checkForActiveController($filename, "uploads")) { ?>
            <script src="<?php echo Config::get('URL'); ?>js/uploader.js"></script>
    <?php } ?>
</body>
</html>