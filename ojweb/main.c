#include "oj.h"

static void init_modules()
{
    logger_init();
    journal_init();
    http_server_init();
    output_buffer_init();
}

static int process_request()
{
    if (pg_index() == 0) return 0;
    if (pg_submit() == 0) return 0;

    if (q_list_problems() == 0) return 0;
    if (q_read_problem() == 0) return 0;

    if (cmd_submit() == 0) return 0;
    if (cmd_load_problem() == 0) return 0;

    return 1;
}

static int replay_a_command()
{
    if (cmd_submit_replay() == 0) return 0;
    if (cmd_load_problem_replay() == 0) return 0;
    return 1;
}

int main()
{
    init_modules();
    INFO("Modules initialized");

    journal_open("/var/lib/sjtuoj/journal", "rb");
    for (;;)
    {
        if (replay_a_command() == 0)
            continue;
        if (journal_is_eof())
            break;
        FATAL("Unable to load a command from the journal at position %ld", journal_tell());
    }
    DEBUG("All commands in the journal are replayed");

    journal_reopen("/var/lib/sjtuoj/journal", "ab");
    http_server_set_port(9876);
    http_server_set_backlog(5);
    http_server_set_timeout(3, 0);
    http_server_start();
    INFO("HTTP server started on port 9876");

    for (;;)
    {
        if (http_server_accept_a_request() == 0)
        {
            output_buffer_reset();
            if (process_request() != 0)
                serve_static_files("../htdocs");
            output_buffer_flush();
        }
        http_server_close_connection();
    }
}
