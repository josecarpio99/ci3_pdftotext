<?php
/**
 * this file acts as vendor/autoload.php
 */

/*
Using PDFParser without Composer
Folder structure
================
webroot
  pdfdemos
    INV001.pdf # test PDF file to extract text from for demo
    test.php # our operational demo file
  vendor
    autoload.php
    smalot
      pdfparser # unpack from git master https://github.com/smalot/pdfparser/archive/master.zip release is 0.9.25 dated 2015-09-15
        docs # optional
        samples # optional
        src
          Smalot
            PdfParser
*/

$prerequisites = array();

/**
 * TODO: ADAPT THIS PATH TO pdfparser
 */
$pdfparser = 'src/Smalot/PdfParser';

$prerequisites['pdfparser'] = array (
    $pdfparser.'/Parser.php',
    $pdfparser.'/Document.php',
    $pdfparser.'/Header.php',
    $pdfparser.'/PDFObject.php',
    $pdfparser.'/Element.php',
    $pdfparser.'/Encoding.php',
    $pdfparser.'/Font.php',
    $pdfparser.'/Page.php',
    $pdfparser.'/Pages.php',
    $pdfparser.'/Element/ElementArray.php',
    $pdfparser.'/Element/ElementBoolean.php',
    $pdfparser.'/Element/ElementString.php',
    $pdfparser.'/Element/ElementDate.php',
    $pdfparser.'/Element/ElementHexa.php',
    $pdfparser.'/Element/ElementMissing.php',
    $pdfparser.'/Element/ElementName.php',
    $pdfparser.'/Element/ElementNull.php',
    $pdfparser.'/Element/ElementNumeric.php',
    $pdfparser.'/Element/ElementStruct.php',
    $pdfparser.'/Element/ElementXRef.php',
    $pdfparser.'/Encoding/StandardEncoding.php',
    $pdfparser.'/Encoding/ISOLatin1Encoding.php',
    $pdfparser.'/Encoding/ISOLatin9Encoding.php',
    $pdfparser.'/Encoding/MacRomanEncoding.php',
    $pdfparser.'/Encoding/WinAnsiEncoding.php',
    $pdfparser.'/Font/FontCIDFontType0.php',
    $pdfparser.'/Font/FontCIDFontType2.php',
    $pdfparser.'/Font/FontTrueType.php',
    $pdfparser.'/Font/FontType0.php',
    $pdfparser.'/Font/FontType1.php',
    $pdfparser.'/Font/FontType3.php',
    // $pdfparser.'/RawData/FilterHelper.php',
    // $pdfparser.'/RawData/RawDataParser.php',
    $pdfparser.'/XObject/Form.php',
    $pdfparser.'/XObject/Image.php'
);

foreach($prerequisites as $project => $includes) {
    foreach($includes as $mapping => $file) {
      require_once $file;
    }
}

/*
// Information for comparison with composer
use Datamatrix;
use PDF417;
use QRcode;
use TCPDF;
use TCPDF2DBarcode;
use TCPDFBarcode;
use TCPDF_COLORS;
use TCPDF_FILTERS;
use TCPDF_FONTS;
use TCPDF_FONT_DATA;
use TCPDF_IMAGES;
use TCPDF_IMPORT;
use TCPDF_PARSER;
use TCPDF_STATIC;
*/