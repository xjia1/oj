#include "oj.h"

void print_current_year()
{
    time_t t = time(NULL);
    struct tm tm = *localtime(&t);
    int year = tm.tm_year + 1900;
    char year_str[16];
    sprintf(year_str, "%d", year);
    print(year_str);
}
