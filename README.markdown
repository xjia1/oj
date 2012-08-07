# php-OnlineJudge

## Apache Configuration

    $ sudo a2enmod rewrite
    $ sudo a2enmod headers
    $ sudo a2enmod expires

## MySQL Configuration

Remember to increase `max_connections` and `thread_stack` in `my.cnf`:

    max_connections = 1000
    thread_stack = 640K

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

## `Memcached` Configuration

APC seems not working with XAMPP on Mac OS X ([see here](http://www.apachefriends.org/f/viewtopic.php?p=142520)). So for application-purpose caching (instead of opcode caching), we use `memcached`.

On Ubuntu:

    $ sudo apt-get install memcached
    $ sudo apt-get install php5-memcache

On Mac OS X with XAMPP:

    $ pecl download memcache
    $ tar -xf memcache-2.2.6.tgz
    $ cd memcache-2.2.6
    $ sudo /Applications/XAMPP/xamppfiles/bin/phpize
    $ sudo MACOSX_DEPLOYMENT_TARGET=10.7 CFLAGS="-arch i386 -arch x86_64 -g -Os -pipe -no-cpp-precomp" CCFLAGS="-arch i386 -arch x86_64 -g -Os -pipe" CXXFLAGS="-arch i386 -arch x86_64 -g -Os -pipe" LDFLAGS="-arch i386 -arch x86_64 -bind_at_load" ./configure --with-apxs=/Applications/XAMPP/xamppfiles/bin/apxs --with-php-config=/Applications/XAMPP/xamppfiles/bin/php-config
    $ sudo make
    $ sudo make install

Finally enable it in `php.ini`: (e.g. `/Applications/XAMPP/xamppfiles/etc/php.ini`)

    extension=memcache.so

Fortunately `memcached` got already installed by the Mac OS X Developer Tools (Xcode).
Just go ahead and start the `memcached` daemon:

    $ memcached -m 8 -l 127.0.0.1 -p 11211 -d
    
    -m 8 limits memcached to use a maximum of 8MB RAM to operate
    -l 127.0.0.1 -p 11211 is the ip and port to listen on
    -d tells it to start as a daemon
    (instead of -l and -p you can also use -s to use an unix domain socket)

## APD Configuration

[Advanced PHP debugger (APD)](http://cn2.php.net/manual/en/book.apd.php) is used to profile PHP scripts. 
Installation is very much similar to the previous two modules on Mac OS X with XAMPP:

    $ pecl download apd
    $ tar -xf apd-1.0.1.tgz
    $ cd apd-1.0.1
    $ sudo /Applications/XAMPP/xamppfiles/bin/phpize
    $ sudo MACOSX_DEPLOYMENT_TARGET=10.7 CFLAGS="-arch i386 -arch x86_64 -g -Os -pipe -no-cpp-precomp" CCFLAGS="-arch i386 -arch x86_64 -g -Os -pipe" CXXFLAGS="-arch i386 -arch x86_64 -g -Os -pipe" LDFLAGS="-arch i386 -arch x86_64 -bind_at_load" ./configure --with-apxs=/Applications/XAMPP/xamppfiles/bin/apxs --with-php-config=/Applications/XAMPP/xamppfiles/bin/php-config

Just before compiling the source codes, modify `php_apd.c` line 967 and change `CG(extended_info) = 1;` to
`CG(compiler_options) |= ZEND_COMPILE_EXTENDED_INFO;`. Otherwise the compilation will fail (with APD version 1.0.1).

    $ sudo make
    $ sudo make install

Then modify `php.ini` to load the extension:

    zend_extension = /Applications/XAMPP/xamppfiles/lib/php/php-5.3.1/extensions/no-debug-non-zts-20090626/apd.so
    apd.dumpdir = /tmp
    apd.statement_tracing = 0

APD usage is described [here](http://cn2.php.net/manual/en/apd.examples.usage.php). 
Note that two scripts shipped with APD (1.0.1) is also available in the sub-directory `scripts`,
i.e. `pprofp` and `pprof2calltree`. The first line of `pprofp` is modified slightly to suit Mac OS X environments.
Also the line `require "Console/Getopt.php"` may fail due to incorrect PEAR path configuration. 
Run the following command to get the PEAR directory:

    $ pear config-show | grep "PEAR directory"

And then update `/etc/php.ini` (this INI file may be different from the one used by your Web scripts) with a correct `include_path`:

    include_path = .:/path/to/your/pear/directory

Finally try `./scripts/pprofp` to see whether the configuration works.

    $ ./scripts/pprofp 
    pprofp <flags> <trace file>
        Sort options
        -a          Sort by alphabetic names of subroutines.
        -l          Sort by number of calls to subroutines
        -m          Sort by memory used in a function call.
        -r          Sort by real time spent in subroutines.
        -R          Sort by real time spent in subroutines (inclusive of child calls).
        -s          Sort by system time spent in subroutines.
        -S          Sort by system time spent in subroutines (inclusive of child calls).
        -u          Sort by user time spent in subroutines.
        -U          Sort by user time spent in subroutines (inclusive of child calls).
        -v          Sort by average amount of time spent in subroutines.
        -z          Sort by user+system time spent in subroutines. (default)

        Display options
        -c          Display Real time elapsed alongside call tree.
        -i          Suppress reporting for php builtin functions
        -O <cnt>    Specifies maximum number of subroutines to display. (default 15)
        -t          Display compressed call tree.
        -T          Display uncompressed call tree.

## License
