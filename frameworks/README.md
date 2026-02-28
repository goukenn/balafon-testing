# install

## laravel

```bash
# installer 
compose global require laravel/installer
laravel new project

# in stand alone 
composer require laravel/laravel
```

## codeigniter

```bash
composer require codeigniter4/codeigniter4-standard
composer require codeigniter/coding-standard
```

## symfony

```bash
composer require symfony/skeleton
```




```bash
# Balafon
balafon --run .test/reflection/generate_framework_metadata.php > /Volumes/Data/Dev/ai/claude/claude-app-balafon-wikireference/vite-project/src/data/sdk.json

# laravel
balafon --run .test/reflection/generate_framework_metadata.php --dir:/Volumes/Data/Dev/PHP/frameworks/vendor/laravel --title:Laravel --url:'https://laravel.com'> /Volumes/Data/Dev/ai/claude/claude-app-balafon-wikireference/vite-project/src/data/laravel.sdk.json

# symfony
balafon --run .test/reflection/generate_framework_metadata.php --dir:/Volumes/Data/Dev/PHP/frameworks/symfony/data-app/vendor/symfony --title:symfony --url:'https://symfony.com/download' > /Volumes/Data/Dev/ai/claude/claude-app-balafon-wikireference/vite-project/src/data/symfony.sdk.json

# codeigniter
balafon --run .test/reflection/generate_framework_metadata.php --dir:/Volumes/Data/Dev/PHP/frameworks/codeigniter4 --title:codeigniter4 --version:4.0 --url:'https://codeigniter.com/download' > /Volumes/Data/Dev/ai/claude/claude-app-balafon-wikireference/vite-project/src/data/codeigniter4.sdk.json

# wordpress 
balafon --run .test/reflection/generate_framework_metadata.php --dir:/Volumes/Data/Dev/PHP/frameworks/wordpress --title:wordpress --version:6.0 --url:'https://wordpress.org/download' > /Volumes/Data/Dev/ai/claude/claude-app-balafon-wikireference/vite-project/src/data/wordpress.6.sdk.json


# for balafon 
balafon --run .test/reflection/command-generate_framework_metadata.php --update-doc > /Volumes/Data/Dev/ai/claude/claude-app-balafon-wikireference/vite-project/src/data/sdk.json



```