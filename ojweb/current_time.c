#include "oj.h"

uint64_t current_time(void)
{
    time_t rawtime;
    time(&rawtime);
    return rawtime;
}
