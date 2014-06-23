#include "oj.h"

static int is_space(char c)
{
    return c == ' ' || c == '\t' || c == '\r' || c == '\n';
}

void rtrim(char *s)
{
    char *p = s + strlen(s) - 1;
    while (p >= s && is_space(*p)) *p-- = 0;
}
