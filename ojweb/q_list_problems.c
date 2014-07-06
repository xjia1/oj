#include "oj.h"

#define MAX_PROBLEMS_NUM 10000
#define PROBLEMS_PER_PAGE 50

int q_list_problems()
{
    if (!http_request_method_is_GET())
        return 1;

    if (strcmp(http_request_path(), "/problems") != 0)
        return 1;

    const int page = atoi(http_get_param("page", "1"));

    if (page < 1 || page > MAX_PROBLEMS_NUM / PROBLEMS_PER_PAGE)
    {
        print("page out of range");
        return 0;
    }

    int num_loaded = 0;
    int k = 0;
    int IDs[PROBLEMS_PER_PAGE];
    const char *titles[PROBLEMS_PER_PAGE];

    for (int i = 0; i < MAX_PROBLEMS_NUM; i++) {
        if (state_is_problem_loaded(i)) {
            num_loaded++;
            if (num_loaded > PROBLEMS_PER_PAGE * (page - 1) && num_loaded <= PROBLEMS_PER_PAGE * page) {
                IDs[k] = i;
                titles[k] = state_problem_title(i);
                k++;
            }
        }
    }

    const int total_page = (num_loaded + PROBLEMS_PER_PAGE - 1) / PROBLEMS_PER_PAGE;
    const int num_problems = k;

    print_header("Problems");
    print("<div class='row'>");
    print("<div class='large-12 columns'>");
    print_pagination(page, total_page, "/problems?page=");
    print("<ul class='small-block-grid-5 list_problems'>");

    for (int k = 0; k < num_problems; k++) {
        print("<li><div class='th'><a href='/problem/");
        print_int(IDs[k]);
        print("'>");
        print("<span class='id'>");
        print_int(IDs[k]);
        print("</span>");
        print("<span class='title'>");
        print(titles[k]);
        print("</span>");
        print("</a></div></li>");
    }

    print("</ul>");
    print_pagination(page, total_page, "/problems?page=");
    print("</div>");
    print("</div>");
    print_footer();

    return 0;
}
