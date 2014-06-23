#include "oj.h"

void print_radio(const char *name, const char *value, const char *id, const char *label)
{
    print("<input type='radio' name='");
    print(name);
    print("' value='");
    print(value);
    print("' id='");
    print(id);
    print("'><label for='");
    print(id);
    print("'>");
    print(label);
    print("</label>");
}
