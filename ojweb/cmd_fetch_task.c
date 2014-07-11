#include "oj.h"

#define COMMAND_ID 0x0103
static uint64_t Fetch_Time;

static void print_preamble()
{
    print(
        "#!/bin/bash\n"
        /* exit on error */
        "set -e\n"
        /* exit on uninitialized variable */
        "set -u\n"
        /* show (-x) or hide (+x) commands */
        "set +x\n"
        /* web-dependent configurations */
        "OJ=\"http://localhost:9876\""
    );
}

static void print_compile_code(uint64_t submit_id)
{
    print("CODE=\"");
    print(sha1_to_string(state_submit_code_sha1(submit_id)));
    print(".");
    print(state_submit_language(submit_id));
    print("\"\n");

    print(
        /* ensure the code is present */
        "if [ ! -f $CODE ]; then $WGET \"$OJ/fetch/code/$CODE\"; fi\n"

        /* remove previous executable (if any) */
        "$RM -f a.out\n"

        /* compile the code */
        "set +e\n"
        "$TIMEOUT -s 9 10s $CXX -std=c++11 -O3 $CODE 2>compile.log\n"
        "RV=$?\n"
        "set -e\n"

        /*  if no executable is generated */
        "if [ ! -f a.out ]; then\n"
            /* if timeout returns 137 = 128 + 9 = SIGKILL */
            "if [ \"$RV\" -eq 137 ]; then\n"
                "echo \"compile time limit exceeded\"\n"
            "else\n"
                "echo \"compile error\"\n"
                "$CAT compile.log\n"
            "fi\n"

            /* do not proceed since there is nothing to execute */
            "exit\n"
        "fi\n"
    );
}

static void print_run_test_cases(uint64_t submit_id)
{
    /* TODO print_run_test_cases */
    (void)submit_id;
}

static void print_script(uint64_t submit_id)
{
    print_preamble();
    print_compile_code(submit_id);
    print_run_test_cases(submit_id);
}

static int execute()
{
    uint64_t submit_id = state_pop_waiting_list(Fetch_Time);
    INFO("Submit %" PRIu64 " fetched", submit_id);
    print_script(submit_id);
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
