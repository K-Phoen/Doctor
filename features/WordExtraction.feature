Feature: Extract metadata from Word files

  Scenario Outline: Extract metadata
    Given the files are located in the "word" directory
    When I extract metadata from "<file>"
    Then the author should be "<author>"
    Then the title should be "<title>"
    Then the creation date should be "<creation_date>"
    Then the keywords "<keywords>" should be found

  Examples:
    | file                        | author  | title                   | keywords | creation_date       |
    | Sample_11_ReadWord2007.docx | PHPWord | PHPWord Sample Document | phpword  | 2014-03-11 09:42:00 |
