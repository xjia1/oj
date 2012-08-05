# php-OnlineJudge

## APC Configuration

On Ubuntu:

    $ sudo apt-get install php-apc

On Mac OS X with XAMPP:

    $ wget http://pecl.php.net/get/APC-3.1.11.tgz
    $ tar -xf APC-3.1.11.tgz
    $ cd APC-3.1.11
    $ sudo /Applications/XAMPP/xamppfiles/bin/phpize
    $ sudo MACOSX_DEPLOYMENT_TARGET=10.7 CFLAGS="-arch i386 -arch x86_64 -g -Os -pipe -no-cpp-precomp" CCFLAGS="-arch i386 -arch x86_64 -g -Os -pipe" CXXFLAGS="-arch i386 -arch x86_64 -g -Os -pipe" LDFLAGS="-arch i386 -arch x86_64 -bind_at_load" ./configure --with-apxs=/Applications/XAMPP/xamppfiles/bin/apxs --with-php-config=/Applications/XAMPP/xamppfiles/bin/php-config
    $ sudo make
    $ sudo make install

After running `phpize`, check whether there are any errors like:

    grep: /Applications/XAMPP/xamppfiles/include/php/php-5.3.1/php/main/php.h: No such file or directory
    grep: /Applications/XAMPP/xamppfiles/include/php/php-5.3.1/php/Zend/zend_modules.h: No such file or directory
    grep: /Applications/XAMPP/xamppfiles/include/php/php-5.3.1/php/Zend/zend_extensions.h: No such file or directory

In case there are errors like this, copy corresponding files from `/Developer/SDKs/MacOSX10.7.sdk/usr/include/php/`.

APC configuration in `php.ini`:

    apc.rfc1867=1
    apc.include_once_override=1
    apc.stat=1
    apc.max_file_size=5M
    apc.shm_size=256M
    apc.num_files_hint=10000
    apc.user_entries_hint=10000
