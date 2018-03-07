<?php
#setup xml reader and writer
$xml = new XMLReader();
$doc = new XMLWriter();

#setup input file names
$files=array("brislington.xml","fishponds.xml","parson_st.xml","rupert_st.xml","wells_rd.xml","newfoundland_way.xml");
#setup output file names
$outfiles=array("brislington_no2.xml","fishponds_no2.xml","parson_st_no2.xml","rupert_st_no2.xml","wells_rd_no2.xml","newfoundland_way_no2.xml");
#setup location names
$locations=array("brislington","fishponds","parson street","rupert street","wells road","newfoundland way");
#get length of files list
$arr_len=count($files);
#loop over files
echo("Processing<br/><br/>");
for($i=0; $i<$arr_len; $i++){
	#open file
	$xml->open($files[$i]);
	#setup writer
	$doc->openURI($outfiles[$i]);
	$doc->startDocument('1.0','UTF-8');
	$doc->setIndent(4);
	$doc->startElement('data');
	$doc->writeAttribute('type','nitrogen dioxide');
	$doc->startElement('location');
	$doc->writeAttribute('id',$locations[$i]);
	$counter=0;
	while($xml->read()){
		##PROCESSING##
		#check if element name is row
		if($xml->nodeType == XMLReader::ELEMENT && 'row'===$xml->name){
			#move to child
			#only match 5 times to force reader to next record
			$x=0;
			while($xml->read() && $x<5){
				#check if node matches type and set var
				if('lat'===$xml->name && $xml->nodeType == XMLReader::ELEMENT){
					$lat=$xml->getAttribute("val");
					$x++;
				}else if('long'===$xml->name && $xml->nodeType == XMLReader::ELEMENT){
					$long=$xml->getAttribute("val");
					$x++;
				}else if('date'===$xml->name && $xml->nodeType == XMLReader::ELEMENT){
					$date=$xml->getAttribute("val");
					$x++;
				}else if('time'===$xml->name && $xml->nodeType == XMLReader::ELEMENT){
					$time=$xml->getAttribute("val");
					$x++;
				}else if('no2'===$xml->name && $xml->nodeType == XMLReader::ELEMENT){
					$val=$xml->getAttribute("val");
					$x++;
				}
			}
			#if first record then write out lat and long
			if($counter==0){
				$doc->writeAttribute('lat',$lat);
				$doc->writeAttribute('long',$long);
				$counter=1;
			}
			#write reading element
			$doc->startElement('reading');
			$doc->writeAttribute('date',$date);
			$doc->writeAttribute('time',$time);
			$doc->writeAttribute('val',$val);
			$doc->endElement();
		}
	}
	#end location and data tags
	$doc->endElement();
	$doc->endElement();
	$doc->endDocument();
	$xml->close();
	echo("Finished Processing ".$files[$i]."<br/><br/>");
}
echo("Finished");
?>