<!--
<?php foreach ($colors as $color): ?>
	<div style="width: 30px; height: 30px; background-color: rgb(<?= $color["red"] ?>,<?= $color["green"] ?>,<?= $color["blue"] ?>); display: inline-block;"></div>
<? endforeach ?>
-->

<!--
<?php foreach ($colors as $key => $value): ?>
	<?php if($value >= 30): ?>
	<div style="width: 30px; height: 30px; background-color: rgb(<?= $key ?>); display: inline-block;"></div>
	<?php endif; ?>
<? endforeach ?>
-->


<?php foreach ($colors as $color): ?>
	<div style="width: 30px; height: 30px; background-color: rgb(<?= $color ?>); display: inline-block;"></div>
<? endforeach ?>

<img src="<?= $img ?>" />
