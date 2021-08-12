<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Sube tu pdf carnal</title>
</head>
<body>
	<?php if (isset($error)) echo $error ?>

	<?php echo form_open_multipart('mec/handle_upload_pdf') ?>

	<input type="file" name="pdf_file" accept="application/pdf" />

	<br /><br />

	<input type="submit" value="upload" />

	</form>

</body>
</html>