#include "oj.h"

int starts_with(const char *s, const char *t)
{
    return memcmp(s, t, strlen(t)) == 0;
}
