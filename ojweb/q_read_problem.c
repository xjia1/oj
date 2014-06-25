#include "oj.h"

#define MAX_PROBLEMS_NUM 10000

int q_read_problem()
{
    if (!http_request_method_is_GET())
        return 1;

    if (!starts_with(http_request_path(), "/problem/"))
        return 1;

    int id = atoi(strcut(http_request_path(), "/problem/"));

    if (id < 0 || id >= MAX_PROBLEMS_NUM)
    {
        print("id out of range");
        return 0;
    }

    if (!state_is_problem_loaded(id))
    {
        print("problem not loaded");
        return 0;
    }

    if (priv_read_problem(id) != 0)
    {
        print("problem not visible");
        return 0;
    }

    print_header(state_problem_title(id));
    print("<div class='row'>");
    print("<div class='large-9 columns'>");
    print(state_problem_description(id));
    print("</div>");
    print("<div class='large-3 columns'>");
    /* TODO print statistics of test cases */
    /* TODO print submission constraints */
    print("<a class='button expand' href='/submit?problem=");
    print_int(id);
    print("'>Submit</a>");
    print("</div>");
    print("</div>");
    print_footer();
    return 0;
}
