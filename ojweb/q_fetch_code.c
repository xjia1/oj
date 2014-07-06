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

    const char *sha1 = strcut(http_request_path(), "/fetch/code/");
    for (size_t i = 0; i < state_num_submits(); i++)
    {
        if (memcmp(sha1, state_submit_code_sha1_at(i), 40) == 0)
        {
            print(state_submit_code_at(i));
            return 0;
        }
    }

    /* TODO set status code 404 */
    return 0;
}
