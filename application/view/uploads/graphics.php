<div class="container">
<?
if(is_array($this->graphics) && count($this->graphics) > 0) {
	echo "<div class=\"row\">";
	$i=1;
	foreach($this->graphics as $index => $value) {
		$tags = explode(",",$value->tags);
		if($i>4) {
			echo "</div><div class=\"row\">";
			$i=1;
		}
		echo "<div class=\"col-sm-3\">";
			echo "<div class=\"thumbnail\">";
				echo "<a href=\"".Config::get('URL')."uploads/graphics/".$value->file."\"><img src=\"".Config::get('URL')."uploads/graphics/".$value->file."\" alt=\"".$value->title."\"></a>";
				echo "<div class=\"caption\">";
					echo "<h4>".$value->title."</h4>";
					echo "<kbd>".$value->file."</kbd><br />";
					foreach($tags as $tag) {
						echo '<span class="label label-primary">'.$tag.'</span>';
					}
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