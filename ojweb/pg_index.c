#include "oj.h"

int pg_index()
{
    if (http_request_method_is_GET() && strcmp(http_request_path(), "/") == 0)
    {
        print_header("Home");
        print_footer();
        return 0;
    }
    return 1;
}
