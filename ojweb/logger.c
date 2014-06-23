#include "oj.h"

void logger_init()
{
}

void logger(const char *file, int line, const char *level, const char *format, ...)
{
    time_t rawtime;
    struct tm *timeinfo;
    char buffer[80];
    va_list args;

    va_start(args, format);
    fflush(stdout);

    time(&rawtime);
    timeinfo = localtime(&rawtime);
    strftime(buffer, 80, "%Z  %F %a  %T %p", timeinfo);

    fprintf(stderr, "%s  %-7s ", buffer, level);
    vfprintf(stderr, format, args);
    fprintf(stderr, "  (%s:%d)\n", file, line);

    fflush(stderr);
    va_end(args);
}
