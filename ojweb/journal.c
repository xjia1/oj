#include "oj.h"

static FILE *journal;

void journal_init()
{
}

void journal_open(const char *filename, const char *mode)
{
    journal = fopen(filename, mode);
    if (!journal)
        FATAL("Unable to open journal %s with mode %s", filename, mode);
}

void journal_reopen(const char *filename, const char *mode)
{
    journal = freopen(filename, mode, journal);
    if (!journal)
        FATAL("Unable to reopen journal %s with mode %s", filename, mode);
}

int journal_is_eof()
{
    return feof(journal);
}

long journal_tell()
{
    return ftell(journal);
}

int journal_read_uint8(uint8_t *u)
{
    size_t n;

    n = fread(u, sizeof(uint8_t), 1, journal);
    if (n != 1)
        return 1;

    return 0;
}

int journal_write_uint8(uint8_t u)
{
    size_t n;

    n = fwrite(&u, sizeof(uint8_t), 1, journal);
    if (n != 1)
        return 1;

    if (fflush(journal) != 0)
        return 1;

    return 0;
}

int journal_read_uint16(uint16_t *u)
{
    int error;
    uint8_t u1, u2;

    error = journal_read_uint8(&u1);
    if (error)
        return error;

    error = journal_read_uint8(&u2);
    if (error)
        return error;

    *u = (((uint16_t) u1) << 8) | ((uint16_t) u2);
    return 0;
}

int journal_write_uint16(uint16_t u)
{
    int error;

    error = journal_write_uint8(u / 256);
    if (error)
        return error;

    error = journal_write_uint8(u % 256);
    if (error)
        return error;

    return 0;
}

int journal_expect_uint16(uint16_t u)
{
    int error;
    uint16_t v;

    error = journal_read_uint16(&v);
    if (error)
        return error;

    if (u == v)
        return 0;

    fseek(journal, -sizeof(uint16_t), SEEK_CUR);
    return 1;
}
