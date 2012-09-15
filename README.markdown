# php-OnlineJudge

## Sending Emails

See [https://github.com/stfairy/mail](https://github.com/stfairy/mail).

## Apache Configuration

    $ sudo a2enmod rewrite
    $ sudo a2enmod headers
    $ sudo a2enmod expires
    $ sudo apt-get install libapache2-mod-rpaf

`/etc/apache2/sites-available/default`:

    <VirtualHost *:*>
      ####
        <IfModule mod_rpaf.c>
          RPAFenable On
          RPAFsethostname On
          RPAFproxy_ips 172.16.15.254 172.16.6.105 127.0.0.1
        </IfModule>
      ####
    </VirtualHost>

`/etc/apache2/apache2.conf`:

    KeepAliveTimeout 2
    ServerLimit 5000
    <IfModule mpm_prefork_module>
        StartServers           5
        MinSpareServers        5
        MaxSpareServers       10
        MaxClients           500
        MaxRequestsPerChild    0
    </IfModule>

## Nginx Configuration (for static assets)

    server {
      listen 80;
      server_name js.giti.me;
      root /var/www/js.giti.me/;

      location ~* \.(?:ico|css|js|jpg|jpeg|gif|png|swf|htm|html)$ {
        expires max;
        add_header Pragma public;
        add_header Cache-Control "public, must-revalidate, proxy-revalidate";
        access_log off;
        log_not_found off;
      }
      location = /robots.txt  { access_log off; log_not_found off; }
      location = /favicon.ico { access_log off; log_not_found off; }
      location ~ /\.          { access_log off; log_not_found off; deny all; }
    }

## PHP Configuration

    $ sudo apt-get install libapache2-mod-php5 php5-mysql php5-pgsql php5-curl php5-gd php5-mcrypt

`php.ini`:

    zlib.output_compression = On
    zlib.output_compression_level = 9
    [Session]
    session.save_handler = memcache
    session.save_path = "tcp://172.16.6.107:11211"

## Varnish Configuration

NOTE: I don't have enough time to document this. Just remember to install and start it:

    $ sudo apt-get install varnish
    $ sudo vim /etc/varnish/default.vcl
    $ sudo pkill varnishd
    $ sudo varnishd -f /etc/varnish/default.vcl -s malloc,1G -T 127.0.0.1:2000

Or to automatically start it, edit `/etc/default/varnish`:

    DAEMON_OPTS="-a :80 \
                 -T localhost:2000 \
                 -f /etc/varnish/default.vcl \
                 -S /etc/varnish/secret \
                 -s malloc,2G"

`/etc/varnish/default.vcl`: (see details here: https://www.varnish-cache.org/trac/wiki/LoadBalancing)

    backend default {
        .host = "127.0.0.1";
        .port = "8088";
        .probe = {
            .url = "/OnlineJudge/";
            .interval = 5s;
            .timeout = 3s;
            .window = 5;
            .threshold = 3;
        }
    }
    backend backup {
        .host = "172.16.6.106";
        .port = "8088";
        .probe = {
            .url = "/OnlineJudge/";
            .interval = 5s;
            .timeout = 3s;
            .window = 5;
            .threshold = 3;
        }
    }
    director oj_director round-robin {
        {
            .backend = default;
        }
        {
            .backend = backup;
        }
    }
    sub vcl_recv {
        if (req.url ~ "^/OnlineJudge/") {
            set req.backend = oj_director;
        }
        else {
            set req.backend = default;
        }
    }
    sub vcl_hash {
        # enable per-user caches
        if (req.http.Cookie) {
            hash_data(req.http.Cookie);
        }
    }

## MySQL Configuration

Remember to increase `max_connections`, `thread_stack` and `max_heap_table_size` in `my.cnf`:

    max_connections = 1000
    thread_stack = 640K
    max_heap_table_size = 1024M

Also change default character set to UTF-8:

    default-character-set = utf8

## PostgreSQL Configuration

OJ has already been migrated from MySQL to PostgreSQL.

    sudo apt-get install ruby rubygems libmysqlclient-dev libpq-dev
    sudo gem install mysql pg mysql2psql
    
    mysql2psql          # creates a .yml templae
    vim mysql2psql.yml  # edit the template
    mysql2psql          # connects to mysql database and write into postgres database

`postgresql.conf`:

    ssl = false
    shared_buffers = 1024M
    effective_cache_size = 2048M

`/etc/sysctl.d/30-postgresql-shm.conf`:

    # Maximum size of shared memory segment in bytes
    kernel.shmmax = 2047483648
    # Maximum total size of shared memory in pages (normally 4096 bytes)
    kernel.shmall = 2097152

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

    apc.stat=1
    apc.shm_size=256M
    apc.max_file_size=10M
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

In production, should edit `/etc/memcached.conf`: (`-l` for PHP load balancing)

    -m 1024
    -l 172.16.6.107

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

## Rejudger Configuration

    $ sudo apt-get install build-essential tcl-dev git python-pycurl openjdk-6-jdk
    
    $ cd
    $ scp -r xjia@172.16.9.36:big_dog_guard/ .
    $ cd big_dog_guard/
    $ make
    $ sudo mkdir -p /root/big_dog_guard/
    $ sudo make install
    
    $ cd
    $ scp -r xjia@172.16.9.36:rejudger/ .
    $ sudo mkdir -p /var/cache/rejudger
    $ sudo mkdir -p /etc/rejudger/
    $ sudo touch /etc/rejudger/java.policy
    
    $ sudo touch /var/log/rejudger.log
    $ sudo chmod +rw /var/log/rejudger.log
    
    $ sudo vim /etc/rc.local
    #!/bin/sh -e
    # ...
    /home/xjia/rejudger/rdaemon &
    exit 0
    
    $ sudo -i
    # cat > /etc/ld.so.conf.d/java.conf
    /usr/lib/jvm/java-6-openjdk-amd64/jre/lib/amd64/jli
    # ldconfig
    # ssh-keygen
    (* add root@ACMGrader-n public key to xjia@giti.me *)
    # mkdir -p /data
    # cd /data
    # git clone git@giti.me:ojcreator/oj.git
    # reboot

NOTE: May need to increase memory limit in `java.py` in case of CE or RE. If the judger has 2G RAM, just set the memory limit to 2G (i.e. `2048 * 1024 * 1024`).

## License

    Copyright 2012 Xiao Jia

    Licensed under the Apache License, Version 2.0 (the "License");
    you may not use this file except in compliance with the License.
    You may obtain a copy of the License at

       http://www.apache.org/licenses/LICENSE-2.0

    Unless required by applicable law or agreed to in writing, software
    distributed under the License is distributed on an "AS IS" BASIS,
    WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
    See the License for the specific language governing permissions and
    limitations under the License.

