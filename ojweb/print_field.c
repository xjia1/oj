#include "oj.h"

void print_field(const char *type, const char *name, const char *label)
{
    print("<div class='row'>");
        print("<div class='large-12 columns'>");
            print("<label for='");
            print(name);
            print("'>");
            print(label);
            print("</label>");
            print("<input type='");
            print(type);
            print("' name='");
            print(name);
            print("' value='");
            print(http_get_param(name, ""));
            print("' id='");
            print(name);
            print("'>");
        print("</div>");
    print("</div>");
}

void print_text_field(const char *name, const char *label)
{
    print_field("text", name, label);
}

void print_password_field(const char *name, const char *label)
{
    print_field("password", name, label);
}
