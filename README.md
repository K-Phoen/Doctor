Doctor [![Build Status](https://travis-ci.org/K-Phoen/Doctor.svg?branch=master)](https://travis-ci.org/K-Phoen/Doctor)
======

Metadata extraction made simple.

This library currently supports the following formats:

  * Word-related: odt, doc, docx, rtf;
  * pdf;
  * markdown.

Usage
-----

```php
$wordExtractor = new \Doctor\Extractor\Word();

$doctor = new \Doctor\Doctor([
    $wordExtractor,
]);

var_dump($doctor->extract('sample_word.docx'));
/*
array(5) {
  'author' =>
  string(7) "Kévin "
  'title' =>
  string(13) "Title"
  'creation_date' =>
  class DateTime#6 (3) {
    public $date =>
    string(26) "2015-03-15 16:00:44.000000"
    public $timezone_type =>
    int(3)
    public $timezone =>
    string(12) "Europe/Paris"
  }
  'keywords' =>
  array(1) {
    [0] =>
    string(4) "test"
  }
  'content' =>
  string(0) ""
}
*/
```

License
-------

This library is under the [MIT](LICENSE) licence.
