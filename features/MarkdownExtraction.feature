Feature: Extract metadata from markdown files

  Scenario Outline: Extract metadata
    Given the files are located in the "markdown" directory
    When I extract metadata from "<file>"
    Then the author should be "<author>"
    Then the title should be "<title>"
    Then the creation date should be "<creation_date>"

  Examples:
    | file                            | author | title | creation_date       |
    | author_creation_date_content.md |        | Title | 2015-02-22 00:00:00 |
    | creation_date_content.md        |        |       | 2015-02-22 00:00:00 |
    | title_content.md                |        | Title |                     |
    | title_content_invalid_date.md   |        | Title |                     |
