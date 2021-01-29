<?php

$path = "/mnt/main/Downloads/xyz/bkag/course-files";
$total_digits_needed = 3;

$dir = new DirectoryIterator($path);

foreach ($dir as $fileinfo)
{
	if (!$fileinfo->isDot())
	{
		$file_name = $fileinfo->getFilename();
		$name_peices =  explode(".", $file_name);
		
		$first = $name_peices[0];
		$lesson_peices =  explode(" ", $first);
		
		$number = end($lesson_peices);

		if (ctype_digit($number))
		{
			$padded_number = sprintf('%0' . $total_digits_needed . 'd', $number);

			$lesson_peices[count($lesson_peices) - 1] = $padded_number;

			$name_peices[0] =  implode(" ", $lesson_peices);
			$new_file_name = implode(".", $name_peices);

			rename($path . $file_name, $path . $new_file_name);
		}
    }
}
