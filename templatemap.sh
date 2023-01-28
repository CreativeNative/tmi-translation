#!/bin/bash
echo "
========================
 generate template maps
========================"
cd ./config || exit
../vendor/bin/templatemap_generator.php ../view > template_map.config.php
echo -e "TmiTranslation module \xE2\x9C\x94"