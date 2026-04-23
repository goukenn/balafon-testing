# balafon-testing

- backup
- testing balafon features


## some script are for demonstration purpose 
only for testing balafon.

## PHP code treatment

- remove php not required comment `balafon --run .test/utils/detect_comment_block.php [dir]` 
- treat php docs `balafon --run .test/reflection/command-generate_framework_metadata.php --help`
- remove empty line `balafon --run .test/utils/remove-empty.line.php [dir]`