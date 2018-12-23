<?php
function tail($filename,$lines_view) {
	if (!$open_file = fopen($filename,'r')) {
		return false;
	}

	$pointer = 0;	
	$char = '';
	$beginning_of_file = false;
	$lines = '';

	for ($i=1;$i<=$lines_view;$i++) {

		if ($beginning_of_file == true) {
			continue;
		}

		while ($char != "\n") {

			// If the beginning of the file is passed
			if(fseek($open_file,$pointer,SEEK_END) < 0) {
				$beginning_of_file = true;
				rewind($open_file);
				break;
			}
			$pointer--;
			fseek($open_file,$pointer,SEEK_END);
			$char = fgetc($open_file);
		}

		$lines = fgets($open_file) . $lines;
		$char = '';
	}
	fclose($open_file);
	return $lines;
}
