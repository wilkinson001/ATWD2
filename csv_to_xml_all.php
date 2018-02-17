<?php
echo "working .. wait";
ob_flush();
flush();
#setup arrays for multiple files and station ids
$ids = array(3,6,8,9,10,11);
$file_names = array("brislington.xml","fishponds.xml","parson_st.xml","rupert_st.xml","wells_rd.xml","newfoundland_way.xml");
$arr_len=count($ids);
#loop over each file
for($i=0; $i<$arr_len; $i++){
	#open raw data file
	if (($handle = fopen("air_quality.csv", "r")) !== FALSE) {
	
		# define the tags - last col value in csv file is derived so ignore
		$header = array('id', 'desc', 'date', 'time', 'nox', 'no', 'no2', 'lat', 'long');
	
		# throw away the first line - field names
		fgetcsv($handle, 200, ",");
		
		# count the number of items in the $header array so we can loop using it
		$cols = count($header);
		#set record count to 1
		$count = 1;
		# set row count to 2 - this is the row in the original csv file
		$row = 2;
		$out = '<records>';
		
		while (($data = fgetcsv($handle, 200, ",")) !== FALSE) {
			#if data id matches id for output
			if ($data[0] == $ids[$i]) {
				#write output row
				$rec = '<row count="' . $count . '" id="' . $row . '">';
				for ($c=0; $c < $cols; $c++) {
					$rec .= '<' . trim($header[$c]) . ' val="' . trim($data[$c]) . '"/>';
				}
				$rec .= '</row>';
				$count++;
				$out .= $rec;
			}
			$row++;
		}
		$out .= '</records>';
		# write out file
		file_put_contents($file_names[$i], $out);
		echo $file_names[$i]."\n\n ";
	}
	fclose($handle);
}
echo "....all done!";
?>