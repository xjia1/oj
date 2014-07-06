#include "oj.h"

int q_fetch_data()
{
    if (!http_request_method_is_GET())
        return 1;

    if (!starts_with(http_request_path(), "/fetch/data/"))
        return 1;

    if (priv_fetch_data() != 0)
    {
        WARN("Unprivileged IP %s tried to fetch data", http_request_ip());
        return 0;
    }

    /* TODO q_fetch_code */
    return 0;
}
