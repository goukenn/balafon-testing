<?php

shell_exec('dotnet run --project /Volumes/Data/Dev/2023/dotnet '
.implode(' ', igk_io_getfiles(IGK_LIB_DIR, "/\.(php|pinc)/", true)))
." 2>&2 " ;