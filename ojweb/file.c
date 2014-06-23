#include "oj.h"

#ifdef _WIN32
    #include <io.h>
    static int access(const char *path, int amode) { return _access(path, amode); }
#else
    #include <unistd.h>
#endif

#include <sys/types.h>
#include <sys/stat.h>

int file_exists(const char *filename)
{
    return access(filename, 0) == 0;
}

int file_get_size(const char *filename)
{
    struct stat sb;
    if (stat(filename, &sb) == 0)
        return sb.st_size;
    return 0;
}

char *file_get_contents(const char *filename)
{
    FILE *f;
    long size;
    char *buf;

    f = fopen(filename, "rb");
    if (!f)
    {
        ERROR("file_get_contents(%s) fopen: %s", filename, strerror(errno));
        return NULL;
    }

    fseek(f, 0, SEEK_END);  /* XXX SEEK_END is not portable */
    size = ftell(f);
    rewind(f);

    buf = calloc(1, size + 1);
    if (!buf)
    {
        ERROR("file_get_contents(%s) calloc: %s", filename, strerror(errno));
        return NULL;
    }

    if (size > 0 && fread(buf, size, 1, f) != 1)
    {
        ERROR("file_get_contents(%s) fread: %s", filename, strerror(errno));
        free(buf);
        return NULL;
    }

    fclose(f);
    return buf;
}
