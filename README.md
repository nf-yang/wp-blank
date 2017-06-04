# wp-blank
Wordpress blank project to use [wp-core](https://github.com/nf-yang/wp-core).
These two combination can make a shared Wordpress system. (gitted and reusable)

## Requirements

* git client.
* php >= 5.3.2.
* [wp-core](https://github.com/nf-yang/wp-core) installed.
* Environment variable `WP_CORE_DIR` has been set to the path of wordpress core. 

## Usage 

### 1. clone the blank project
```
git clone https://github.com/nf-yang/wp-blank.git
cd wp-blank
```

### 2. run the build script
```
export WP_CORE_DIR=/path/to/wp-core/wordpress #if no WP_CORE_DIR defined
php run build.php
```

This will create some symbolic links and files for you.
![The file structure](https://raw.githubusercontent.com/wiki/nf-yang/wp-blank/images/wp-blank-structure.png)

### 3. access the project via browser to setup the new wordpress

### 4. delete the initial files and reset git repository
```
rm build.php
rm -fr .git
git init
```

 That's all! Now you can develop/deploy wordpress plugins or themes under the shared environment of Wordpress.
