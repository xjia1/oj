#include "oj.h"

#define COMMAND_ID 0x0103
static uint64_t Fetch_Time;

static int execute()
{
    uint64_t submit_id = state_pop_waiting_list(Fetch_Time);
    INFO("Submit %" PRIu64 " fetched", submit_id);
    /* TODO generate and print tash.sh */
    return 0;
}

static void persist()
{
    if (journal_write_uint16(COMMAND_ID) != 0)
        FATAL("Unable to write command ID");

    if (journal_write_uint64(Fetch_Time) != 0)
        FATAL("Unable to write fetch time");
}

int cmd_fetch_task()
{
    if (!http_request_method_is_POST())
        return 1;

    if (strcmp(http_request_path(), "/fetch/task.sh") != 0)
        return 1;

    if (priv_fetch_task() != 0)
    {
        WARN("Unprivileged IP %s tried to fetch task.sh", http_request_ip());
        return 0;
    }

    Fetch_Time = current_time();

    if (execute() == 0)
        persist();

    return 0;
}

int cmd_fetch_task_replay()
{
    if (journal_expect_uint16(COMMAND_ID) != 0)
        return 1;

    if (journal_read_uint64(&Fetch_Time) != 0)
        FATAL("Unable to read fetch time");

    if (execute() != 0)
        FATAL("Unable to replay fetch task");

    return 0;
}
