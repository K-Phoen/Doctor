Feature: Extract metadata from PDF files

  Scenario Outline: Extract metadata
    Given the files are located in the "pdf" directory
    When I extract metadata from "<file>"
    Then the author should be "<author>"
    Then the title should be "<title>"
    Then the creation date should be "<creation_date>"
    Then the keywords "<keywords>" should be found

  Examples:
    | file                                      | author      | title    | keywords     | creation_date       |
    | author_creation_date_keywords_content.pdf | Kévin Gomez | Test pdf | first,second | 2015-03-15 21:45:55 |
