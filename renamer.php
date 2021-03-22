<?php

if (array_key_exists("dir", $_GET))
{
	renamer($_GET["dir"], array_key_exists("recursive", $_GET));
	$status = "S";
}

function find_padding_digits_needed(DirectoryIterator $iterator)
{
	$highest_number = 0;

	foreach ($iterator as $fileinfo)
	{
		if (!$fileinfo->isDot())
		{
			$file_name = $fileinfo->getFilename();
			$name_peices =  explode(".", $file_name);
			
			if (ctype_digit($name_peices[0]) && $name_peices[0] > $highest_number)
			{
				$highest_number = $name_peices[0];
			}
		}
	}

	return strlen($highest_number);
}

function renamer(string $path, bool $go_deep)
{
	$iterator = new DirectoryIterator($path);
	$total_digits_needed = find_padding_digits_needed($iterator);

	foreach ($iterator as $fileinfo)
	{
		if (!$fileinfo->isDot())
		{
			if ($fileinfo->isDir() && $go_deep)
			{
				renamer($fileinfo->getPathname(), true);
			}

			$file_name = $fileinfo->getFilename();
			$name_peices =  explode(".", $file_name);
			
			if (ctype_digit($name_peices[0]))
			{
				$name_peices[0] = sprintf('%0' . $total_digits_needed . 'd', $name_peices[0]);
				$new_file_name = implode(".", $name_peices);

				$old_file = $fileinfo->getPath() . "/" . $file_name;
				$new_file = $fileinfo->getPath() . "/" . $new_file_name;

				echo "$file_name => $new_file_name <br>";

				rename($old_file, $new_file);
			}
	    }
	}
}

?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
	<style>
		input[type=text],
		input[type=number]
		{
			display: block;
			height: 25px;
			margin-bottom: 10px;
		}
	</style>
</head>
<body>
	<?php if (isset($status)): ?>
		<br><br>Success!
		<a href="<?=  $_SERVER["PHP_SELF"] ?>">Do Again</a>
	<?php else: ?>
		<form>
			<label>
				Directory
				<input type="text" name="dir">
			</label>
			<label>
				Recursive
				<input type="checkbox" name="recursive">
			</label><br><br>
			<input type="submit" value="Submit">
		</form>
	<?php endif ?>
</body>
</html>