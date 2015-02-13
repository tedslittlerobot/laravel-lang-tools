<?php

if ( ! isset($level) )
{
	$level = 0;
}

$z = '';

for ( $xi=0; $xi < $level; $xi++ )
{
	$z .= "\t";
}

?>
<?php echo $z; ?>array(
<?php foreach( $items as $key => $item ): ?>
<?php echo $z; ?>	"<?php echo e($key) ?>" => <?php echo  is_array($item) ? View::make('laravel-lang-tools::array', array('level' => $level + 1, 'items' => $item))->render() : '"'.e($item).'"' ; ?>,
<?php endforeach ?>
<?php echo $z; ?>)
