<?php
class Layout
{

    /**
     * Writes a single div across the entire page
     *
     * @param  string  $plugin  Name of plugin
     * @return none
     */
	private static function singleBox($plugin, $class="box", $style="")
	{
		$class = 'class="'.$class.'"';
		if ($style) $class .= ' style="'.$style.'"';

		$filename = './plugins/'.$plugin.'/'.$plugin.'.html.php';
		if (file_exists($filename)) {
			global $Config;
			$title = $Config->format($plugin.":title");
			$reload = 'onclick="esm.reloadBlock(\''.$plugin.'\');"';
		} else {
			$title = 'Error';
			$reload = '';
		}
		echo '<div '.$class.' id="esm-'.$plugin.'">'."\n";
		echo "\t".'<div class="box-header">'."\n";
  		echo "\t\t".'<h1>'.$title.'</h1>'."\n";
		echo "\t\t".'<ul>'."\n";
		echo "\t\t\t".'<li><a href="#" class="reload" '.$reload.'>';
		echo '<span class="icon-cycle"></span></a></li>'."\n";
		echo "\t\t".'</ul>'."\n";
		echo "\t".'</div>'."\n";
		if (file_exists($filename)) {
			require $filename;
		} else {
			echo 'Error: The plugin ('.$plugin.') does not appear to exist in the plugin directory!';
		}
		echo '</div>'."\n";
	}


    /**
     * Writes a single div across the entire page
     *
     * @param  string  $plugin  Name of plugin
     * @return none
     */
	public static function colSingle($plugin)
	{
        echo '<div class="t-center">'."\n";
        Layout::singleBox($plugin, "box");
		echo '</div>'."\n";
	}

	public static function col50_50($left,$right)
	{
        echo '<div class="t-center">'."\n";

        Layout::singleBox($left, "box column-left");
        Layout::singleBox($right, "box column-right");

		echo '</div>'."\n";
	}

	public static function col33_33_33($left,$center,$right)
	{
        echo '<div class="t-center">'."\n";

        Layout::singleBox($left, "box column-left column-33");
        Layout::singleBox($right, "box column-right column-33");
        Layout::singleBox($center, "box t-center", "margin: 0 33%;");

		echo '</div>'."\n";
	}

	public static function col33_66($left,$right)
	{
        echo '<div class="t-center">'."\n";

        Layout::singleBox($left, "box column-left column-33");
        Layout::singleBox($right, "box column-right column-66");

		echo '</div>'."\n";
	}

	public static function col66_33($left,$right)
	{
        echo '<div class="t-center">'."\n";

        Layout::singleBox($left, "box column-left column-66");
        Layout::singleBox($right, "box column-right column-33");

		echo '</div>'."\n";
	}

}