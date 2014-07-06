#include "oj.h"

int q_fetch_code()
{
    if (!http_request_method_is_GET())
        return 1;

    if (!starts_with(http_request_path(), "/fetch/code/"))
        return 1;

    if (priv_fetch_code() != 0)
    {
        WARN("Unprivileged IP %s tried to fetch code", http_request_ip());
        return 0;
    }

    /* TODO q_fetch_code */
    return 0;
}
