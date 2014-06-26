#include "oj.h"

int language_is_supported(const char *lang)
{
    return strcmp(lang, "C") == 0
        || strcmp(lang, "C++") == 0
        || strcmp(lang, "Python") == 0;
}

int language_is_allowed(const char *lang, uint16_t problem_id)
{
    /* TODO check if language is allowed for this problem */
    (void)lang;
    (void)problem_id;
    return 1;
}
