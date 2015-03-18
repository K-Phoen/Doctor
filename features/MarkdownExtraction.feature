Feature: Extract metadata from markdown files

  Scenario Outline: Extract metadata
    Given the files are located in the "markdown" directory
    When I extract metadata from "<file>"
    Then the author should be "<author>"
    Then the title should be "<title>"

  Examples:
    | file                            | author | title |
    | author_creation_date_content.md |        | Title |
    | creation_date_content.md        |        |       |
    | title_content.md                |        | Title |
    | title_content_invalid_date.md   |        | Title |
