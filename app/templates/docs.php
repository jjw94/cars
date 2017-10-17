
<?php
	require_once("_Layouts.php");
	getHeader("Docs");
?>
    <div class="markdown-body module">
		<?php
			if(isset($sidebar)) {
				echo '<div class="markdown-body md-sidebar">'.$sidebar.'</div>';
			}
		?>

		<a href="./"><i class="fa fa-arrow-left"></i>Back</a>
        <?php echo $body; ?>
    </div>
<?php
	getFooter();
?>
