#include "oj.h"

int pg_submit()
{
    if (!http_request_method_is_GET())
        return 1;

    if (strcmp(http_request_path(), "/submit") != 0)
        return 1;

    print_header("Submit");
    print("<div class='row'>");
    print("<div class='large-12 columns'>");
    print("<form method='POST' action='/submit'>");

    print("<div class='row'>");
        print("<div class='large-3 columns'>");
            print("<label for='problem_id'>Problem ID</label>");
            print("<input type='number' name='problem_id' value='");
            print(http_get_param("problem", ""));
            print("' id='problem_id'>");
        print("</div>");
    print("</div>");

    print("<div class='row'>");
        print("<div class='large-6 columns'>");
            print("<label>Language</label>");
            print_radio("language", "C", "language_c", "C");
            print_radio("language", "C++", "language_cpp", "C++");
            print_radio("language", "Java", "language_java", "Java");
            print_radio("language", "Python", "language_python", "Python");
        print("</div>");
    print("</div>");

    print("<div class='row'>");
        print("<div class='large-12 columns'>");
            print("<label>Code");
                print("<textarea style='font-family:monospace' rows='20' name='code'></textarea>");
            print("</label>");
        print("</div>");
    print("</div>");

    print("<input class='button expand' type='submit' value='Submit'>");

    print("</form>");
    print("</div>");
    print("</div>");
    print_footer();

    return 0;
}
