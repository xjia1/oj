#include "oj.h"

void print_pagination(int curpage, int total, const char *prefix)
{
    print("<div class='pagination-centered'>");
    print("<ul class='pagination'>");

    if (curpage == 1) {
        print("<li class='arrow unavailable'><a href=''>&laquo;</a></li>");
    }
    else {
        print("<li class='arrow'><a href='");
        print(prefix);
        print_int(curpage - 1);
        print("'>&laquo;</a></li>");
    }

    for (int page = 1; page <= total; page++) {
        if (page == curpage)
            print("<li class='current'>");
        else
            print("<li>");

        print("<a href='");
        print(prefix);
        print_int(page);
        print("'>");
        print_int(page);
        print("</a></li>");
    }

    if (curpage == total) {
        print("<li class='arrow unavailable'><a href=''>&raquo;</a></li>");
    }
    else {
        print("<li class='arrow'><a href='");
        print(prefix);
        print_int(curpage + 1);
        print("'>&raquo;</a></li>");
    }

    print("</ul>");
    print("</div>");
}
