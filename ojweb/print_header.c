#include "oj.h"

void print_header(const char *title)
{
    print("<!DOCTYPE html>");
    print("<html class='no-js' lang='zh-CN'>");
    print("<head>");
        print("<meta charset='utf-8' />");
        print("<meta name='viewport' content='width=device-width, initial-scale=1.0' />");
        print("<title>"); print(title); print(" | Online Judge</title>");
        print("<link rel='stylesheet' href='/css/foundation.css' />");
        print("<link rel='stylesheet' href='/css/oj.css' />");
        print("<script src='/js/vendor/modernizr.js'></script>");
    print("</head>");
    print("<body>");
    print_navbar();
}
