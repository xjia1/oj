#include "oj.h"

int language_is_supported(const char *lang)
{
    return strcmp(lang, "c") == 0
        || strcmp(lang, "cpp") == 0
        || strcmp(lang, "py") == 0;
}

int language_is_allowed(const char *lang, uint16_t problem_id)
{
    /* TODO check if language is allowed for this problem */
    (void)lang;
    (void)problem_id;
    return 1;
}
