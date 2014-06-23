#include "oj.h"

void print_int(int i)
{
    static char buf[32];
    sprintf(buf, "%d", i);
    print(buf);
}
