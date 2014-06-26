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

int journal_read(void *buffer, size_t len)
{
    size_t n = fread(buffer, len, 1, journal);
    if (n != 1)
        return 1;
    return 0;
}

int journal_write(const void *data, size_t len)
{
    size_t n = fwrite(data, len, 1, journal);
    if (n != 1)
        return 1;
    if (fflush(journal) != 0)
        return 1;
    return 0;
}

int journal_read_uint8(uint8_t *u)
{
    return journal_read(u, sizeof(uint8_t));
}

int journal_write_uint8(uint8_t u)
{
    return journal_write(&u, sizeof(uint8_t));
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

    error = journal_write_uint8(u >> 8);
    if (error)
        return error;

    error = journal_write_uint8(u & 0xFF);
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

int journal_read_uint32(uint32_t *u)
{
    int error;
    uint16_t u1, u2;

    error = journal_read_uint16(&u1);
    if (error)
        return error;

    error = journal_read_uint16(&u2);
    if (error)
        return error;

    *u = (((uint32_t) u1) << 16) | ((uint32_t) u2);
    return 0;
}

int journal_write_uint32(uint32_t u)
{
    int error;

    error = journal_write_uint16(u >> 16);
    if (error)
        return error;

    error = journal_write_uint16(u & 0xFFFF);
    if (error)
        return error;

    return 0;
}

int journal_read_uint64(uint64_t *u)
{
    int error;
    uint32_t u1, u2;

    error = journal_read_uint32(&u1);
    if (error)
        return error;

    error = journal_read_uint32(&u2);
    if (error)
        return error;

    *u = (((uint64_t) u1) << 32) | ((uint64_t) u2);
    return 0;
}

int journal_write_uint64(uint64_t u)
{
    int error;

    error = journal_write_uint32(u >> 32);
    if (error)
        return error;

    error = journal_write_uint32(u & 0xFFFFFFFF);
    if (error)
        return error;

    return 0;
}

int journal_read_string(char **s)
{
    int error;
    uint32_t len;

    error = journal_read_uint32(&len);
    if (error)
        return error;

    *s = malloc(len + 1);
    if (!*s)
        return 1;

    error = journal_read(*s, len);
    if (error)
        return error;

    *s[len] = 0;
    return 0;
}

int journal_write_string(const char *s)
{
    int error;
    uint32_t len = strlen(s);

    error = journal_write_uint32(len);
    if (error)
        return error;

    error = journal_write(s, len);
    if (error)
        return error;

    return 0;
}
