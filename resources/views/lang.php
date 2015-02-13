<?php echo '<?php'; ?>

return <?php echo View::make('laravel-lang-tools::array')->with('items', $items)->render(); ?>;
