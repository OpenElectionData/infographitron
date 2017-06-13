<div class="container">
<?
if(is_array($this->fonts) && count($this->fonts) > 0) {
	echo "<div class=\"row\">";
	$i=1;
	foreach($this->fonts as $index => $value) {
		if($i>4) {
			echo "</div><div class=\"row\">";
			$i=1;
		}
		echo "<div class=\"col-sm-3\">";
			echo "<div class=\"thumbnail\">";
				echo "<a href=\"".Config::get('URL')."uploads/fonts/".$value->file."\"><img src=\"".Config::get('URL')."uploads/fonts/".$value->file."\" alt=\"".$value->title."\"></a>";
				echo "<div class=\"caption\">";
					echo "<h4>".$value->title."</h4>";
					echo "<kbd>".$value->file."</kbd>";
				echo "</div>";
				echo "</div>";
		echo "</div>";
		$i++;
	}
	echo "</div>";
} else {
	echo "<div class=\"alert alert-warning\" role=\"alert\">".TEXT::get("no_files_found")."</div>";
}
?>
</div>