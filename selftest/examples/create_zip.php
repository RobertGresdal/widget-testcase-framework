<?PHP


// create object
$zip = new ZipArchive();
$filename = tempnam(dirname(__FILE__).'/tmp', 'tc');

// open output file for writing
// archive will open the tempfile and overwrite instead of trying to read it
if ($zip->open($filename, ZIPARCHIVE::OVERWRITE) !== TRUE) {
    die ("Could not open archive");
}

// add file from disk
$zip->addFile('test.txt', 'should-contain-pass.txt') or die ("ERROR: Could not add file");        

// add text file as string
$str = "<?PHP die('Access denied'); ?>";
$zip->addFromString('webroot/index.php', $str) or die ("ERROR: Could not add file");        

/**/// add binary file as string*/
// todo: add a gif here
$str = file_get_contents('test.txt');
$zip->addFromString('webroot/new_test.txt', $str) or die ("ERROR: Could not add file");        

// close and save archive
$zip->close();
echo "Archive created successfully.";    
?>